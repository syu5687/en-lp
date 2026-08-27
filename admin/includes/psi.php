<?php
/**
 * PageSpeed Insights API 連携（表示速度診断）
 *  - 管理画面からのオンデマンド計測のみ（PVに比例したAPI呼び出しは発生しない）
 *  - 結果は Firestore「psi」コレクションに保存し、次回表示時は保存値を出す
 *  - APIキーなしでも動作（Googleの無償枠）。頻繁に計測して quota エラーが出る場合は
 *    includes/config.php の PSI_API_KEY にAPIキーを設定（PageSpeed Insights API を有効化して作成）
 */
require_once __DIR__ . '/store.php';

/** 計測対象ページ（パス => 表示名）。増やしたいときはここに追加 */
const PSI_PAGES = [
  '/'            => 'トップページ',
  '/kaiyou-sou/' => '海洋葬（海洋散骨）',
  '/fukuoka/'    => '福岡LP',
  '/contact/'    => 'お問い合わせ',
  '/blog/'       => 'ブログ一覧',
];

const PSI_COLLECTION = 'psi';

function psi_doc_id(string $path, string $strategy): string {
  return md5($path . '|' . $strategy);
}

/** 保存済みの計測結果をすべて取得（docId => 結果）。5分キャッシュ */
function psi_all(): array {
  return en_cache('psi_all', 300, function () {
    $out = [];
    foreach (fs_list_all(PSI_COLLECTION) as $doc) {
      $d = fs_from_doc($doc);
      if (!empty($d['id'])) $out[$d['id']] = $d;
    }
    return $out;
  });
}

/** PSI APIで1ページを計測し、結果をFirestoreへ保存して返す */
function psi_run(string $path, string $strategy): array {
  $url = rtrim(SITE['url'], '/') . $path;
  $api = 'https://www.googleapis.com/pagespeedonline/v5/runPagespeed'
       . '?url=' . rawurlencode($url)
       . '&strategy=' . rawurlencode($strategy)
       . '&category=performance&locale=ja';
  if (defined('PSI_API_KEY') && PSI_API_KEY !== '') $api .= '&key=' . rawurlencode(PSI_API_KEY);

  $raw = @file_get_contents($api, false, stream_context_create(['http' => [
    'timeout' => 120, 'ignore_errors' => true,
  ]]));
  $j = json_decode((string)$raw, true);
  if (!is_array($j)) throw new RuntimeException('PageSpeed Insights APIに接続できませんでした（時間をおいて再度お試しください）');
  if (!empty($j['error'])) {
    $msg = (string)($j['error']['message'] ?? 'APIエラー');
    if (stripos($msg, 'quota') !== false || (int)($j['error']['code'] ?? 0) === 429) {
      $msg .= '／計測回数の上限に達しました。時間をおくか、includes/config.php の PSI_API_KEY にAPIキーを設定してください。';
    }
    throw new RuntimeException($msg);
  }

  $lh = $j['lighthouseResult'] ?? [];
  $audits = $lh['audits'] ?? [];
  $dv = static fn(string $k): string => (string)($audits[$k]['displayValue'] ?? '—');

  // 改善提案（節約効果の大きい順・上位5件）を「タイトル｜短縮効果」の文字列で保存
  $opps = [];
  foreach ($audits as $a) {
    $savings = (float)($a['details']['overallSavingsMs'] ?? 0);
    if (($a['details']['type'] ?? '') === 'opportunity' && $savings >= 100) {
      $opps[] = ['t' => (string)($a['title'] ?? ''), 's' => $savings];
    }
  }
  usort($opps, fn($x, $y) => $y['s'] <=> $x['s']);
  $opps = array_map(
    fn($o) => $o['t'] . '｜約' . number_format($o['s'] / 1000, 1) . '秒短縮',
    array_slice($opps, 0, 5)
  );

  $result = [
    'path'          => $path,
    'strategy'      => $strategy,
    'measured_at'   => date('Y-m-d H:i'),
    'score'         => (int)round(((float)($lh['categories']['performance']['score'] ?? 0)) * 100),
    'fcp'           => $dv('first-contentful-paint'),
    'lcp'           => $dv('largest-contentful-paint'),
    'cls'           => $dv('cumulative-layout-shift'),
    'tbt'           => $dv('total-blocking-time'),
    'si'            => $dv('speed-index'),
    'opps'          => $opps,
    // 実ユーザーデータ（CrUX・十分なアクセスがあるページのみ返る）
    'field_overall' => (string)($j['loadingExperience']['overall_category'] ?? ''),
  ];

  $res = fs_request('PATCH', 'documents/' . PSI_COLLECTION . '/' . psi_doc_id($path, $strategy),
                    ['fields' => fs_to_fields($result)]);
  if (!empty($res['error'])) {
    // 保存に失敗しても計測結果自体は返す（画面には表示できる）
    error_log('[psi] save failed: ' . json_encode($res['error']));
  }
  en_cache_bust('psi_all');
  return $result;
}
