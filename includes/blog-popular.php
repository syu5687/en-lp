<?php
/**
 * 記事別アクセス数（人気の記事）のデータ層。
 *
 * ■ なぜGA4から取るのか
 *   自前計測（pageviews コレクション）は track.js が location.pathname だけを送っており、
 *   ブログ記事はすべて "/blog/" として記録される。記事ごとのPVは残っていない。
 *   一方GA4には page_location がクエリ付きで入っているため、記事別PVは過去分も取得できる。
 *
 * ■ 公開ページからGA4 APIは呼ばない（重要）
 *   admin/includes/ga4-report.php は「管理画面専用。公開ページからは一切呼ばれない」設計。
 *   その前提を崩さないため、ここでは次の形にしている。
 *
 *     GA4 Data API ──(管理画面を開いた時／手動更新)──> Firestore site_meta/blog_popular
 *                                                            │
 *                                 公開ページ ──(en_cache 6時間)─┘  ※1ドキュメント読むだけ
 *
 *   公開ページは外部APIを叩かないため、表示が遅くなることも、障害に引きずられることもない。
 *   読み取りは「1ドキュメント × 1日4回」程度でPVに比例しない。
 *
 * ■ データが無い／古い場合
 *   人気の記事ボタンを出さない（壊れたタブを見せない）。順位の信頼性を優先する。
 */
require_once __DIR__ . '/firestore.php';
require_once __DIR__ . '/../admin/includes/store.php'; // en_cache / en_cache_bust

const BLOG_POPULAR_COLLECTION = 'site_meta';
const BLOG_POPULAR_DOC        = 'blog_popular';

/* ============================================================
   読み取り（公開ページ・管理画面の両方から使う）
   ============================================================ */

/**
 * 保存済みの記事別PV。
 * @return array ['map' => [記事ID => PV], 'updated_at' => 'Y-m-d H:i:s', 'days' => int]
 */
function blog_popular_stored(): array {
  try {
    return en_cache('blog_popular_map', BLOG_POPULAR_CACHE_TTL, function () {
      $res = fs_request('GET', 'documents/' . BLOG_POPULAR_COLLECTION . '/' . BLOG_POPULAR_DOC);
      if (!is_array($res) || !empty($res['error']) || empty($res['fields'])) return [];
      $f   = $res['fields'];
      $map = json_decode((string)($f['map_json']['stringValue'] ?? ''), true);
      if (!is_array($map)) return [];
      return [
        'map'        => $map,
        'updated_at' => (string)($f['updated_at']['stringValue'] ?? ''),
        'days'       => (int)($f['days']['integerValue'] ?? 0),
      ];
    });
  } catch (Throwable $e) {
    error_log('[blog-popular] read failed: ' . $e->getMessage());
    return [];
  }
}

/** 記事ID => PV。データが無ければ空配列 */
function blog_popular_map(): array {
  $d = blog_popular_stored();
  return is_array($d['map'] ?? null) ? $d['map'] : [];
}

/** 人気の記事を出せる状態か（データがあり、古すぎない） */
function blog_popular_available(): bool {
  if (!BLOG_SORT_ENABLED) return false;
  $d = blog_popular_stored();
  if (empty($d['map'])) return false;
  $u = (string)($d['updated_at'] ?? '');
  if ($u === '') return false;
  $age = time() - (int)strtotime($u);
  return $age >= 0 && $age <= BLOG_POPULAR_STALE_SEC;
}

/** 最終更新の表示用文字列（管理画面で使う） */
function blog_popular_updated_at(): string {
  return (string)(blog_popular_stored()['updated_at'] ?? '');
}

/* ============================================================
   更新（管理画面・ログイン後からのみ呼ぶ）
   ============================================================ */

/**
 * GA4 Data API から記事別PVを取得し、Firestoreへ1ドキュメントとして保存する。
 * 例外は呼び出し側で捕捉して画面に出す。
 *
 * @return array ['count' => 取得記事数, 'days' => 集計日数]
 */
function blog_popular_refresh(): array {
  require_once __DIR__ . '/../admin/includes/ga4-report.php'; // ga4_batch / ga4_rows

  $days  = BLOG_POPULAR_DAYS;
  $start = date('Y-m-d', strtotime("-{$days} days"));

  $reports = ga4_batch([[
    'dateRanges' => [['startDate' => $start, 'endDate' => 'yesterday']],
    'dimensions' => [['name' => 'pagePathPlusQueryString']],
    'metrics'    => [['name' => 'screenPageViews']],
    'dimensionFilter' => ['filter' => [
      'fieldName'    => 'pagePathPlusQueryString',
      'stringFilter' => ['matchType' => 'BEGINS_WITH', 'value' => '/blog/?'],
    ]],
    'orderBys' => [['metric' => ['metricName' => 'screenPageViews'], 'desc' => true]],
    'limit'    => 400,
  ]]);

  $map = [];
  foreach (ga4_rows($reports[0] ?? [], 1, 1) as $row) {
    $path = (string)($row[0] ?? '');
    $pv   = (int)round((float)($row[1] ?? 0));
    if ($pv <= 0) continue;
    $qs = (string)(parse_url($path, PHP_URL_QUERY) ?? '');
    if ($qs === '') continue;
    parse_str($qs, $q);
    $id = isset($q['id']) ? trim((string)$q['id']) : '';
    // 記事IDとして妥当なものだけを採用（?cat= や ?p= の一覧ページを除外）
    if ($id === '' || !preg_match('/\A[A-Za-z0-9._\-]{1,120}\z/', $id)) continue;
    $map[$id] = ($map[$id] ?? 0) + $pv; // 同一記事が複数URL（utm付き等）で出た分を合算
  }
  arsort($map, SORT_NUMERIC);

  $now = date('Y-m-d H:i:s');
  $res = fs_request(
    'PATCH',
    'documents/' . BLOG_POPULAR_COLLECTION . '/' . BLOG_POPULAR_DOC,
    ['fields' => fs_to_fields([
      'map_json'   => json_encode($map, JSON_UNESCAPED_UNICODE),
      'updated_at' => $now,
      'days'       => $days,
      'articles'   => count($map),
    ])]
  );
  if (!is_array($res) || !empty($res['error'])) {
    throw new RuntimeException('保存に失敗しました: ' . ($res['error']['message'] ?? 'unknown'));
  }
  en_cache_bust('blog_popular_map');
  return ['count' => count($map), 'days' => $days];
}
