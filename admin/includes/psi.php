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

/**
 * ブラウザ側で計測したPSI結果を検証してFirestoreへ保存する。
 * （PSI APIはCORS対応のため、計測は管理画面のブラウザから直接行う。
 *   サーバー経由だと計測30〜60秒でゲートウェイに切断され502になるための構成変更）
 */
function psi_save(array $d): array {
  $path     = (string)($d['path'] ?? '');
  $strategy = (string)($d['strategy'] ?? '');
  if (!array_key_exists($path, PSI_PAGES)) throw new InvalidArgumentException('対象外のページです');
  if (!in_array($strategy, ['mobile', 'desktop'], true)) throw new InvalidArgumentException('bad strategy');

  $s = static fn($k, $max) => mb_substr(trim((string)($d[$k] ?? '')), 0, $max);
  $opps = [];
  foreach ((array)($d['opps'] ?? []) as $o) {
    if (is_string($o) && trim($o) !== '') $opps[] = mb_substr(trim($o), 0, 200);
    if (count($opps) >= 5) break;
  }
  $field = strtoupper($s('field_overall', 20));
  if (!in_array($field, ['FAST', 'AVERAGE', 'SLOW', ''], true)) $field = '';

  $result = [
    'path'          => $path,
    'strategy'      => $strategy,
    'measured_at'   => date('Y-m-d H:i'),
    'score'         => max(0, min(100, (int)($d['score'] ?? 0))),
    'fcp'           => $s('fcp', 40),
    'lcp'           => $s('lcp', 40),
    'cls'           => $s('cls', 40),
    'tbt'           => $s('tbt', 40),
    'si'            => $s('si', 40),
    'opps'          => $opps,
    'field_overall' => $field,
  ];

  $res = fs_request('PATCH', 'documents/' . PSI_COLLECTION . '/' . psi_doc_id($path, $strategy),
                    ['fields' => fs_to_fields($result)]);
  if (!empty($res['error'])) throw new RuntimeException('保存に失敗しました: ' . (string)($res['error']['message'] ?? ''));
  en_cache_bust('psi_all');
  return $result;
}
