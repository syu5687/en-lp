<?php
/**
 * GA4 Data API 連携 ＋ 15日ごとの効果測定レポート生成・蓄積
 *
 * - 認証: Cloud Run のサービスアカウント（メタデータサーバのトークンを流用）。
 *         事前に GA4 プロパティの「プロパティのアクセス管理」でこのサービスアカウントの
 *         メールアドレスに「閲覧者」権限を付与しておくこと（ga4_sa_email() で確認可）。
 * - 期間: GA4_REPORT_ANCHOR（リニューアル公開日）から15日ごとに1期間。
 * - 蓄積: 完了した期間のレポートは Firestore コレクション ga_reports に保存し、
 *         以後は保存済みデータを表示するだけ（GA4 API は期間ごとに1回しか呼ばない）。
 * - コスト: 管理画面（ログイン必須）専用。公開ページからは一切呼ばれないため、
 *          PVに比例した読み取り・API呼び出しは発生しない（コストガード準拠）。
 */
require_once __DIR__ . '/../../includes/firestore.php'; // fs_token / fs_request / fs_from_doc / fs_to_fields
require_once __DIR__ . '/store.php';

const GA_REPORTS_COLLECTION = 'ga_reports';

/** Cloud Run サービスアカウントのメールアドレス（GA4側に閲覧者として追加する対象） */
function ga4_sa_email(): string {
  $email = @file_get_contents(
    'http://metadata.google.internal/computeMetadata/v1/instance/service-accounts/default/email',
    false,
    stream_context_create(['http' => ['header' => "Metadata-Flavor: Google\r\n", 'timeout' => 2]])
  );
  return $email ?: '(Cloud Run上でのみ表示されます)';
}

/* ============================================================
   期間計算（GA4_REPORT_ANCHOR から15日ごと）
   ============================================================ */
/** 完了済みの期間一覧（古い順）。[['start'=>'Y-m-d','end'=>'Y-m-d','id'=>'rYYYYMMDD'], ...] */
function ga4_completed_periods(): array {
  $anchor = strtotime(GA4_REPORT_ANCHOR . ' 00:00:00');
  // GA4の当日データは未確定のため「昨日まで」に完全に収まる期間のみ完了扱い
  $yesterday = strtotime(date('Y-m-d', time() - 86400));
  $periods = [];
  for ($s = $anchor; ; $s += 15 * 86400) {
    $e = $s + 14 * 86400;
    if ($e > $yesterday) break;
    $periods[] = ['start' => date('Y-m-d', $s), 'end' => date('Y-m-d', $e), 'id' => 'r' . date('Ymd', $e)];
    if (count($periods) >= 60) break; // 安全弁
  }
  return $periods;
}

/** 進行中の期間（速報表示用）。完了済み期間と重複せず、まだ1日も経過していなければ null */
function ga4_current_period(): ?array {
  $anchor = strtotime(GA4_REPORT_ANCHOR . ' 00:00:00');
  $yesterday = strtotime(date('Y-m-d', time() - 86400));
  if ($yesterday < $anchor) return null;
  $elapsed = (int)floor(($yesterday - $anchor) / 86400);
  $s = $anchor + intdiv($elapsed, 15) * 15 * 86400;
  $e = min($s + 14 * 86400, $yesterday);
  // ちょうど期間の最終日＝昨日の場合は「完了済み」扱いになるため、速報は次期間（データなし）→ null
  if ($e >= $s + 14 * 86400) return null;
  if ($e < $s) return null;
  return ['start' => date('Y-m-d', $s), 'end' => date('Y-m-d', $e), 'id' => ''];
}

/* ============================================================
   GA4 Data API
   ============================================================ */
/** batchRunReports を1回呼び、集計済みの配列を返す。失敗時は RuntimeException */
function ga4_fetch(string $start, string $end): array {
  $range = [['startDate' => $start, 'endDate' => $end]];
  $body = ['requests' => [
    [ // 0: サマリー
      'dateRanges' => $range,
      'metrics' => [
        ['name' => 'sessions'], ['name' => 'totalUsers'], ['name' => 'newUsers'],
        ['name' => 'screenPageViews'], ['name' => 'averageSessionDuration'], ['name' => 'engagementRate'],
      ],
    ],
    [ // 1: 日別
      'dateRanges' => $range,
      'dimensions' => [['name' => 'date']],
      'metrics' => [['name' => 'sessions']],
      'orderBys' => [['dimension' => ['dimensionName' => 'date']]],
      'limit' => 20,
    ],
    [ // 2: チャネル別
      'dateRanges' => $range,
      'dimensions' => [['name' => 'sessionDefaultChannelGroup']],
      'metrics' => [['name' => 'sessions']],
      'orderBys' => [['metric' => ['metricName' => 'sessions'], 'desc' => true]],
      'limit' => 10,
    ],
    [ // 3: 人気ページ
      'dateRanges' => $range,
      'dimensions' => [['name' => 'pagePath']],
      'metrics' => [['name' => 'screenPageViews']],
      'orderBys' => [['metric' => ['metricName' => 'screenPageViews'], 'desc' => true]],
      'limit' => 10,
    ],
    [ // 4: CVイベント
      'dateRanges' => $range,
      'dimensions' => [['name' => 'eventName']],
      'metrics' => [['name' => 'eventCount']],
      'dimensionFilter' => ['filter' => ['fieldName' => 'eventName', 'inListFilter' => [
        'values' => ['generate_lead', 'tel_click', 'line_click'],
      ]]],
      'limit' => 10,
    ],
  ]];

  $url = 'https://analyticsdata.googleapis.com/v1beta/properties/' . rawurlencode(GA4_PROPERTY_ID) . ':batchRunReports';
  $resp = @file_get_contents($url, false, stream_context_create(['http' => [
    'method'  => 'POST',
    'header'  => "Authorization: Bearer " . ga4_scoped_token('https://www.googleapis.com/auth/analytics.readonly') . "\r\nContent-Type: application/json\r\n",
    'content' => json_encode($body),
    'timeout' => 20,
    'ignore_errors' => true,
  ]]));
  $j = json_decode((string)$resp, true);
  if (!is_array($j) || !empty($j['error'])) {
    $msg = $j['error']['message'] ?? 'GA4 Data API へ接続できませんでした';
    throw new RuntimeException($msg);
  }
  $reports = $j['reports'] ?? [];

  $rows = fn(int $i): array => $reports[$i]['rows'] ?? [];
  $mv = fn(array $row, int $i) => (float)($row['metricValues'][$i]['value'] ?? 0);
  $dv = fn(array $row, int $i) => (string)($row['dimensionValues'][$i]['value'] ?? '');

  // 0: サマリー
  $s = $rows(0)[0] ?? null;
  $summary = [
    'sessions' => $s ? (int)$mv($s, 0) : 0,
    'users'    => $s ? (int)$mv($s, 1) : 0,
    'newUsers' => $s ? (int)$mv($s, 2) : 0,
    'pv'       => $s ? (int)$mv($s, 3) : 0,
    'avgSec'   => $s ? (int)round($mv($s, 4)) : 0,
    'engRate'  => $s ? round($mv($s, 5) * 100, 1) : 0.0,
  ];
  // 1: 日別
  $daily = [];
  foreach ($rows(1) as $r) {
    $d = $dv($r, 0); // YYYYMMDD
    $daily[] = ['d' => substr($d, 0, 4) . '-' . substr($d, 4, 2) . '-' . substr($d, 6, 2), 'v' => (int)$mv($r, 0)];
  }
  // 2: チャネル
  $channels = [];
  foreach ($rows(2) as $r) $channels[] = ['name' => $dv($r, 0), 'v' => (int)$mv($r, 0)];
  // 3: ページ
  $pages = [];
  foreach ($rows(3) as $r) $pages[] = ['path' => $dv($r, 0), 'v' => (int)$mv($r, 0)];
  // 4: CV
  $cv = ['generate_lead' => 0, 'tel_click' => 0, 'line_click' => 0];
  foreach ($rows(4) as $r) $cv[$dv($r, 0)] = (int)$mv($r, 0);

  return ['summary' => $summary, 'daily' => $daily, 'channels' => $channels, 'pages' => $pages, 'cv' => $cv];
}

/* ============================================================
   レポートの保存・読み出し（Firestore: ga_reports）
   ============================================================ */
/** 保存済みレポートを全件取得（期間の新しい順）。[id => report] */
function ga_reports_all(): array {
  $items = [];
  foreach (fs_list_all(GA_REPORTS_COLLECTION) as $doc) {
    $it = fs_from_doc($doc);
    if (!empty($it['data']) && is_string($it['data'])) $it['data'] = json_decode($it['data'], true);
    $items[(string)$it['id']] = $it;
  }
  krsort($items); // id = rYYYYMMDD → 新しい順
  return $items;
}

/** 1期間分を生成してFirestoreへ保存。生成済みデータを返す */
function ga_report_generate(array $p): array {
  $data = ga4_fetch($p['start'], $p['end']);
  $item = [
    'start' => $p['start'],
    'end' => $p['end'],
    'generated_at' => date('Y-m-d H:i:s'),
    'data' => json_encode($data, JSON_UNESCAPED_UNICODE),
  ];
  fs_request('PATCH', 'documents/' . GA_REPORTS_COLLECTION . '/' . rawurlencode($p['id']), ['fields' => fs_to_fields($item)]);
  $item['id'] = $p['id'];
  $item['data'] = $data;
  return $item;
}

/**
 * 未生成の完了期間があれば生成して蓄積する（1回の画面表示につき最大 $max 期間）。
 * 戻り値: [保存済み全レポート(新しい順), 生成エラーメッセージ or '']
 */
function ga_reports_sync(int $max = 3): array {
  $stored = ga_reports_all();
  $err = '';
  $made = 0;
  foreach (ga4_completed_periods() as $p) {
    if (isset($stored[$p['id']]) || $made >= $max) continue;
    try {
      $stored[$p['id']] = ga_report_generate($p);
      $made++;
    } catch (Throwable $e) {
      $err = $e->getMessage();
      break; // 権限未設定などは以降も失敗するため打ち切り
    }
  }
  krsort($stored);
  return [$stored, $err];
}

/* ============================================================
   GA4 詳細解析（/admin/ga4/ 用）
   - batchRunReports を3回（計12レポート）呼び、プロ向けの分析データ一式を返す
   - en_cache（15分）で期間ごとにキャッシュ。管理画面専用のためPV比例の呼び出しは無い
   ============================================================ */

/** 汎用: batchRunReports（最大5件）を1回実行して reports 配列を返す */
function ga4_batch(array $requests): array {
  $url = 'https://analyticsdata.googleapis.com/v1beta/properties/' . rawurlencode(GA4_PROPERTY_ID) . ':batchRunReports';
  $resp = @file_get_contents($url, false, stream_context_create(['http' => [
    'method'  => 'POST',
    'header'  => "Authorization: Bearer " . ga4_scoped_token('https://www.googleapis.com/auth/analytics.readonly') . "\r\nContent-Type: application/json\r\n",
    'content' => json_encode(['requests' => $requests]),
    'timeout' => 25,
    'ignore_errors' => true,
  ]]));
  $j = json_decode((string)$resp, true);
  if (!is_array($j) || !empty($j['error'])) {
    throw new RuntimeException($j['error']['message'] ?? 'GA4 Data API へ接続できませんでした');
  }
  return $j['reports'] ?? [];
}

/** 行→[dim値..., metric値...] の変換ヘルパー */
function ga4_rows(array $report, int $dims, int $mets): array {
  $out = [];
  foreach (($report['rows'] ?? []) as $r) {
    $row = [];
    for ($i = 0; $i < $dims; $i++) $row[] = (string)($r['dimensionValues'][$i]['value'] ?? '');
    for ($i = 0; $i < $mets; $i++) $row[] = (float)($r['metricValues'][$i]['value'] ?? 0);
    $out[] = $row;
  }
  return $out;
}

/**
 * 詳細解析データ一式（当期間＋前期間比較）
 * $days: 集計日数（昨日までのN日間）
 */
function ga4_pro_fetch(int $days): array {
  $end   = date('Y-m-d', strtotime('-1 day'));
  $start = date('Y-m-d', strtotime("-{$days} days"));
  $pEnd   = date('Y-m-d', strtotime("-" . ($days + 1) . " days"));
  $pStart = date('Y-m-d', strtotime("-" . ($days * 2) . " days"));
  $cur  = [['startDate' => $start, 'endDate' => $end]];
  $both = [['startDate' => $start, 'endDate' => $end], ['startDate' => $pStart, 'endDate' => $pEnd]];
  $CV = ['generate_lead', 'tel_click', 'line_click'];
  $cvFilter = ['filter' => ['fieldName' => 'eventName', 'inListFilter' => ['values' => $CV]]];
  $m = fn(...$n) => array_map(fn($x) => ['name' => $x], $n);
  $d = fn(...$n) => array_map(fn($x) => ['name' => $x], $n);
  $descBy = fn($name) => [['metric' => ['metricName' => $name], 'desc' => true]];

  // ---- バッチ1 ----
  $b1 = ga4_batch([
    ['dateRanges' => $both, 'metrics' => $m('sessions','totalUsers','newUsers','screenPageViews','engagementRate','bounceRate','averageSessionDuration','sessionsPerUser')],
    ['dateRanges' => $both, 'dimensions' => $d('eventName'), 'metrics' => $m('eventCount'), 'dimensionFilter' => $cvFilter],
    ['dateRanges' => $cur, 'dimensions' => $d('date'), 'metrics' => $m('sessions','totalUsers','screenPageViews'), 'orderBys' => [['dimension' => ['dimensionName' => 'date']]], 'limit' => 100],
    ['dateRanges' => $cur, 'dimensions' => $d('sessionDefaultChannelGroup'), 'metrics' => $m('sessions','totalUsers','engagementRate','bounceRate'), 'orderBys' => $descBy('sessions'), 'limit' => 12],
    ['dateRanges' => $cur, 'dimensions' => $d('sessionSource','sessionMedium'), 'metrics' => $m('sessions','engagementRate'), 'orderBys' => $descBy('sessions'), 'limit' => 15],
  ]);
  // ---- バッチ2 ----
  $b2 = ga4_batch([
    ['dateRanges' => $cur, 'dimensions' => $d('landingPage'), 'metrics' => $m('sessions','engagementRate','bounceRate'), 'orderBys' => $descBy('sessions'), 'limit' => 15],
    ['dateRanges' => $cur, 'dimensions' => $d('pagePath'), 'metrics' => $m('screenPageViews','activeUsers'), 'orderBys' => $descBy('screenPageViews'), 'limit' => 20],
    ['dateRanges' => $cur, 'dimensions' => $d('deviceCategory'), 'metrics' => $m('sessions','engagementRate')],
    ['dateRanges' => $cur, 'dimensions' => $d('region'), 'metrics' => $m('sessions','totalUsers'), 'orderBys' => $descBy('sessions'), 'limit' => 12],
    ['dateRanges' => $cur, 'dimensions' => $d('city'), 'metrics' => $m('sessions'), 'orderBys' => $descBy('sessions'), 'limit' => 12],
  ]);
  // ---- バッチ3 ----
  $b3 = ga4_batch([
    ['dateRanges' => $cur, 'dimensions' => $d('hour'), 'metrics' => $m('sessions'), 'orderBys' => [['dimension' => ['dimensionName' => 'hour']]], 'limit' => 24],
    ['dateRanges' => $cur, 'dimensions' => $d('dayOfWeekName'), 'metrics' => $m('sessions')],
    ['dateRanges' => $cur, 'dimensions' => $d('newVsReturning'), 'metrics' => $m('sessions','engagementRate','averageSessionDuration')],
    ['dateRanges' => $cur, 'dimensions' => $d('eventName','pagePath'), 'metrics' => $m('eventCount'), 'dimensionFilter' => $cvFilter, 'orderBys' => $descBy('eventCount'), 'limit' => 15],
    ['dateRanges' => $cur, 'dimensions' => $d('browser'), 'metrics' => $m('sessions'), 'orderBys' => $descBy('sessions'), 'limit' => 8],
  ]);

  // ---- パース ----
  // KPI（dateRange別: 行に dateRange dimension が自動付与される）
  $kpiRows = $b1[0]['rows'] ?? [];
  $kpi = ['cur' => array_fill(0, 8, 0.0), 'prev' => array_fill(0, 8, 0.0)];
  foreach ($kpiRows as $r) {
    $which = (($r['dimensionValues'][0]['value'] ?? 'date_range_0') === 'date_range_0') ? 'cur' : 'prev';
    foreach ($r['metricValues'] as $i => $v) $kpi[$which][$i] = (float)$v['value'];
  }
  // CVイベント（期間別）
  $cv = ['cur' => array_fill_keys($CV, 0), 'prev' => array_fill_keys($CV, 0)];
  foreach (($b1[1]['rows'] ?? []) as $r) {
    $dv = $r['dimensionValues'];
    // dims: eventName + dateRange（順序はレスポンス依存のため名前で判定）
    $ev = null; $which = 'cur';
    foreach ($dv as $x) {
      $val = (string)($x['value'] ?? '');
      if (in_array($val, $CV, true)) $ev = $val;
      if ($val === 'date_range_1') $which = 'prev';
    }
    if ($ev !== null) $cv[$which][$ev] += (float)($r['metricValues'][0]['value'] ?? 0);
  }

  return [
    'range' => ['start' => $start, 'end' => $end, 'pstart' => $pStart, 'pend' => $pEnd, 'days' => $days],
    'kpi' => $kpi,
    'cv' => $cv,
    'daily'    => ga4_rows($b1[2], 1, 3),
    'channels' => ga4_rows($b1[3], 1, 4),
    'sources'  => ga4_rows($b1[4], 2, 2),
    'landing'  => ga4_rows($b2[0], 1, 3),
    'pages'    => ga4_rows($b2[1], 1, 2),
    'devices'  => ga4_rows($b2[2], 1, 2),
    'regions'  => ga4_rows($b2[3], 1, 2),
    'cities'   => ga4_rows($b2[4], 1, 1),
    'hours'    => ga4_rows($b3[0], 1, 1),
    'weekdays' => ga4_rows($b3[1], 1, 1),
    'newret'   => ga4_rows($b3[2], 1, 3),
    'cvpages'  => ga4_rows($b3[3], 2, 1),
    'browsers' => ga4_rows($b3[4], 1, 1),
  ];
}

/** キャッシュ付き（15分）。管理画面専用 */
function ga4_pro(int $days): array {
  return en_cache('ga4_pro_' . $days, 900, fn() => ga4_pro_fetch($days));
}

/* ============================================================
   Search Console（検索キーワード）連携
   - GA4には自然検索クエリが存在しないため、GSC APIから取得して補完する
   - 認証: Cloud Runメタデータサーバの ?scopes= でwebmasters.readonlyスコープの
           トークンを取得（鍵ファイル不要・同じサービスアカウント）
   - 事前設定: ①Search Console APIをプロジェクトで有効化
              ②GSCの「設定→ユーザーと権限」でSAメールを追加（権限:制限付きでOK）
   ============================================================ */

/** 指定スコープのアクセストークン（Cloud Runメタデータ経由・リクエスト内キャッシュ） */
function ga4_scoped_token(string $scope): string {
  static $cache = [];
  if (isset($cache[$scope])) return $cache[$scope];
  $meta = @file_get_contents(
    'http://metadata.google.internal/computeMetadata/v1/instance/service-accounts/default/token?scopes=' . rawurlencode($scope),
    false,
    stream_context_create(['http' => ['header' => "Metadata-Flavor: Google\r\n", 'timeout' => 3]])
  );
  $j = json_decode((string)$meta, true);
  if (!empty($j['access_token'])) return $cache[$scope] = $j['access_token'];
  return $cache[$scope] = fs_token(); // フォールバック（ローカル等）
}

const GSC_SITE = 'sc-domain:en1150.co.jp';

/** GSC Search Analytics API 呼び出し */
function gsc_query_api(array $body): array {
  $url = 'https://searchconsole.googleapis.com/webmasters/v3/sites/' . rawurlencode(GSC_SITE) . '/searchAnalytics/query';
  $resp = @file_get_contents($url, false, stream_context_create(['http' => [
    'method'  => 'POST',
    'header'  => "Authorization: Bearer " . ga4_scoped_token('https://www.googleapis.com/auth/webmasters.readonly') . "\r\nContent-Type: application/json\r\n",
    'content' => json_encode($body),
    'timeout' => 20,
    'ignore_errors' => true,
  ]]));
  $j = json_decode((string)$resp, true);
  if (!is_array($j) || !empty($j['error'])) {
    throw new RuntimeException($j['error']['message'] ?? 'Search Console API へ接続できませんでした');
  }
  return $j['rows'] ?? [];
}

/**
 * 検索キーワードデータ（15分キャッシュ）
 * 戻り値: ['queries'=>[[query,clicks,impr,ctr,pos]...], 'qpages'=>[[query,page,clicks,impr]...]]
 * ※ GSCのデータは2〜3日遅れで確定するため、期間は3日前を終端にする
 */
function gsc_keywords(int $days): array {
  return en_cache('gsc_kw_' . $days, 900, function () use ($days) {
    $end   = date('Y-m-d', strtotime('-3 days'));
    $start = date('Y-m-d', strtotime('-' . ($days + 3) . ' days'));
    $queries = [];
    foreach (gsc_query_api(['startDate' => $start, 'endDate' => $end, 'dimensions' => ['query'], 'rowLimit' => 20]) as $r) {
      $queries[] = [(string)($r['keys'][0] ?? ''), (float)($r['clicks'] ?? 0), (float)($r['impressions'] ?? 0), (float)($r['ctr'] ?? 0), (float)($r['position'] ?? 0)];
    }
    $qpages = [];
    foreach (gsc_query_api(['startDate' => $start, 'endDate' => $end, 'dimensions' => ['query', 'page'], 'rowLimit' => 15]) as $r) {
      $page = preg_replace('#^https?://[^/]+#', '', (string)($r['keys'][1] ?? ''));
      $qpages[] = [(string)($r['keys'][0] ?? ''), $page, (float)($r['clicks'] ?? 0), (float)($r['impressions'] ?? 0)];
    }
    return ['start' => $start, 'end' => $end, 'queries' => $queries, 'qpages' => $qpages];
  });
}
