<?php
/**
 * お客様の声 初期データ移行スクリプト（1回だけ実行）
 *   data/voices.json のシードを Firestore コレクション "voices" に取り込みます。
 *   実行方法: ログイン後に /admin/migrate-voice.php をブラウザで開く
 *   ※ 既に同じIDがある場合は上書き（再実行しても重複しません）
 */
require __DIR__ . '/includes/auth.php';
require __DIR__ . '/includes/store.php';

header('Content-Type: text/plain; charset=UTF-8');

$seed = __DIR__ . '/../data/voices.json';
if (!is_file($seed)) { echo "data/voices.json が見つかりません。\n"; exit; }

$j = json_decode((string)file_get_contents($seed), true);
$items = $j['items'] ?? [];
if (!$items) { echo "取り込むデータがありません。\n"; exit; }

$ok = $ng = 0;
foreach ($items as $it) {
  if (empty($it['id'])) { $ng++; continue; }
  voice_upsert($it) ? $ok++ : $ng++;
}
echo "完了: 成功 {$ok} 件 / 失敗 {$ng} 件\n";
echo "確認: /admin/voice/ を開いてください（ダッシュボードの件数にも反映されます）。\n";
echo "※ 以後、お客様の声の追加・編集は管理画面から行えます。\n";
