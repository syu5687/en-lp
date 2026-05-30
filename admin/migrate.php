<?php
/**
 * 初回データ移行スクリプト（一度だけ実行）
 *   data/news.json のシードを Firestore コレクション "news" に取り込みます。
 *   実行後は安全のためファイルごと削除してください。
 *   アクセス: /admin/migrate.php （要ログイン）
 */
require __DIR__ . '/includes/auth.php';
require __DIR__ . '/includes/store.php';

header('Content-Type: text/plain; charset=UTF-8');

$seed = __DIR__ . '/../data/news.json';
if (!is_file($seed)) { echo "data/news.json が見つかりません。\n"; exit; }

$json  = json_decode((string)file_get_contents($seed), true);
$items = $json['items'] ?? [];
$ok = 0; $ng = 0;
foreach ($items as $it) {
  if (empty($it['id'])) { $ng++; continue; }
  news_upsert($it) ? $ok++ : $ng++;
}
echo "移行完了： 成功 {$ok} 件 / 失敗 {$ng} 件\n";
echo "確認: /admin/news/ を開いてください。\n";
echo "※ 完了したら admin/migrate.php と data/news.json は削除推奨。\n";
