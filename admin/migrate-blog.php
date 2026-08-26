<?php
/**
 * @version v20260713-0110
 * 旧WordPress全記事アーカイブ（data/blog-posts.json）を Firestore「news」へ一括取り込み。
 *
 * 使い方（要ログイン）:
 *   1) /admin/migrate-blog.php        … 取り込み前の確認画面（件数表示）
 *   2) /admin/migrate-blog.php?run=1  … 実行（50件ずつ自動継続）
 *
 * ・既に同じIDの記事が Firestore にある場合は上書きせずスキップします
 *   （管理画面で編集済みの記事を守るため）。強制上書きは ?run=1&force=1。
 * ・取り込み後も data/blog-posts.json は残して構いません
 *   （公開側はID重複を自動排除し、Firestore側を優先表示します）。
 */
require __DIR__ . '/includes/auth.php';
require __DIR__ . '/includes/store.php';

header('Content-Type: text/html; charset=UTF-8');
@set_time_limit(120);

const MB_CHUNK = 50; // 1回の実行で処理する件数

$seed_file = __DIR__ . '/../data/blog-posts.json';
$items = is_file($seed_file)
  ? (json_decode((string)file_get_contents($seed_file), true)['items'] ?? [])
  : [];
$total = count($items);

$run   = !empty($_GET['run']);
$force = !empty($_GET['force']);
$from  = max(0, (int)($_GET['from'] ?? 0));

?><!DOCTYPE html>
<html lang="ja"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>過去記事の取り込み｜管理画面</title>
<meta name="robots" content="noindex,nofollow">
<link rel="stylesheet" href="/admin/assets/admin.css?v=<?= h(asset_ver()) ?>">
<style>
  .mig{max-width:760px;margin:24px auto;padding:0 16px;font-size:.95rem;line-height:1.8}
  .mig pre{background:#f6f6f2;border:1px solid #ddd;border-radius:8px;padding:14px;white-space:pre-wrap;font-size:.85rem;max-height:340px;overflow:auto}
  .mig .btn{display:inline-block;background:#15709e;color:#fff;padding:10px 22px;border-radius:8px;text-decoration:none;font-weight:700}
  .mig .btn--ghost{background:#888}
  .mig .bar{height:10px;background:#e8e8e2;border-radius:5px;overflow:hidden;margin:10px 0}
  .mig .bar i{display:block;height:100%;background:#15709e}
</style>
</head><body>
<header class="admin-bar">
  <span class="admin-bar__title">有限会社 縁 — 過去記事の取り込み</span>
  <a href="/admin/" class="admin-bar__logout">ダッシュボードへ</a>
</header>
<main class="mig">
<h1>旧ブログ記事 → Firestore 取り込み</h1>
<?php if (!$total): ?>
  <p>data/blog-posts.json が見つからないか、記事がありません。</p>
<?php elseif (!$run): ?>
  <p>アーカイブ記事 <strong><?= $total ?> 件</strong> を Firestore（管理画面のブログ・お知らせ）に取り込みます。<br>
  50件ずつ自動で進みます（数分かかることがあります）。既に管理画面にある同じIDの記事はスキップされます。</p>
  <p><a class="btn" href="?run=1">取り込みを開始する</a></p>
<?php else:
  $chunk = array_slice($items, $from, MB_CHUNK);
  $ok = 0; $skip = 0; $ng = 0; $log = [];
  foreach ($chunk as $it) {
    $id = (string)($it['id'] ?? '');
    if ($id === '') { $ng++; $log[] = 'NG (IDなし): ' . mb_strimwidth((string)($it['title'] ?? ''), 0, 40, '…'); continue; }
    if (!$force) {
      $exists = null;
      try { $exists = news_find($id); } catch (Throwable $e) { $exists = null; }
      if ($exists) { $skip++; $log[] = "SKIP (既存): {$id}"; continue; }
    }
    $done = false;
    try { $done = news_upsert($it); } catch (Throwable $e) { $done = false; }
    if ($done) { $ok++; $log[] = "OK: {$id} " . mb_strimwidth((string)($it['title'] ?? ''), 0, 34, '…'); }
    else       { $ng++; $log[] = "NG: {$id}"; }
  }
  $next = $from + MB_CHUNK;
  $doneCount = min($next, $total);
  $pct = (int)round($doneCount / $total * 100);
?>
  <p><strong><?= $from + 1 ?>〜<?= min($next, $total) ?> 件目</strong> を処理しました（成功 <?= $ok ?> / スキップ <?= $skip ?> / 失敗 <?= $ng ?>）</p>
  <div class="bar"><i style="width:<?= $pct ?>%"></i></div>
  <p>進捗：<?= $doneCount ?> / <?= $total ?> 件（<?= $pct ?>%）</p>
  <pre><?= h(implode("\n", $log)) ?></pre>
<?php if ($next < $total): ?>
  <p><a class="btn" href="?run=1&from=<?= $next ?><?= $force ? '&force=1' : '' ?>" id="next">続きを取り込む（残り <?= $total - $next ?> 件）</a></p>
  <script>/* 自動継続（3秒後）。止めたい場合はページを閉じてください */
    setTimeout(function(){ location.href = document.getElementById('next').href; }, 3000);</script>
<?php else: ?>
  <p><strong>すべての取り込みが完了しました。</strong></p>
  <p><a class="btn" href="/admin/news/">ブログ・お知らせ一覧で確認する</a>
     <a class="btn btn--ghost" href="/blog/" target="_blank" rel="noopener">公開ページを確認する</a></p>
<?php endif; endif; ?>
</main>
<?= dev_badge_html() ?>
</body></html>
