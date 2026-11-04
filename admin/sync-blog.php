<?php
/**
 * @version v20260713-0272
 * data/blog-sync.json に書いた「このIDだけ」を Firestore へ上書きする画面。
 *
 * なぜ別画面にしたか：
 *   migrate-blog.php は「Firestoreに同じIDがあればスキップ」なので、
 *   すでに取り込み済みの旧記事を直しても公開ページに反映されない。
 *   かといって ?force=1 は519件すべてを上書きしてしまい、
 *   管理画面で手直しした記事まで消えるため使えない。
 *   そこで「上書きするIDを明示したリスト」だけを対象にする。
 *
 * 使い方：
 *   1) /admin/sync-blog.php        … 対象記事の一覧を確認（上書きはしない）
 *   2) /admin/sync-blog.php?run=1  … 一覧の記事だけを上書き
 *
 * 注意：
 *   ・対象記事を管理画面で手直ししていた場合、その内容は data/blog-posts.json の
 *     内容に置き換わります。実行前に必ず一覧を確認してください。
 *   ・反映が終わったら data/blog-sync.json の ids を [] に戻します。
 */
require __DIR__ . '/includes/auth.php';
require __DIR__ . '/includes/store.php';

header('Content-Type: text/html; charset=UTF-8');
@set_time_limit(180);

$seed  = __DIR__ . '/../data/blog-posts.json';
$list  = __DIR__ . '/../data/blog-sync.json';

$items = is_file($seed)
  ? (json_decode((string)file_get_contents($seed), true)['items'] ?? [])
  : [];
$conf  = is_file($list)
  ? (json_decode((string)file_get_contents($list), true) ?? [])
  : [];
$ids   = array_values(array_filter(array_map('strval', (array)($conf['ids'] ?? ''))));

$by_id = [];
foreach ($items as $it) { if (!empty($it['id'])) $by_id[(string)$it['id']] = $it; }

$targets = [];
$unknown = [];
foreach ($ids as $id) {
  if (isset($by_id[$id])) $targets[] = $by_id[$id];
  else                    $unknown[] = $id;
}

$run = !empty($_GET['run']);
?><!DOCTYPE html>
<html lang="ja"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>指定記事の上書き反映｜管理画面</title>
<meta name="robots" content="noindex,nofollow">
<link rel="stylesheet" href="/admin/assets/admin.css?v=<?= h(asset_ver()) ?>">
<style>
  .sy{max-width:860px;margin:24px auto;padding:0 16px;font-size:.95rem;line-height:1.8}
  .sy pre{background:#f6f6f2;border:1px solid #ddd;border-radius:8px;padding:14px;white-space:pre-wrap;font-size:.85rem;max-height:420px;overflow:auto}
  .sy .btn{display:inline-block;background:#15709e;color:#fff;padding:10px 22px;border-radius:8px;text-decoration:none;font-weight:700}
  .sy .btn--ghost{background:#888}
  .sy .warn{background:#fff6e6;border-left:4px solid #d08a00;padding:12px 16px;border-radius:6px}
  .sy ol{padding-left:1.4em}
  .sy ol li{margin-bottom:4px}
</style>
</head><body>
<header class="admin-bar">
  <span class="admin-bar__title">有限会社 縁 ― 指定記事の上書き反映</span>
  <a href="/admin/" class="admin-bar__logout">ダッシュボードへ</a>
</header>
<main class="sy">
<h1>指定した記事だけを Firestore へ上書き</h1>

<?php if (!$targets): ?>
  <p>data/blog-sync.json に対象の記事IDがありません。上書きする記事はありません。</p>
  <p><a class="btn btn--ghost" href="/admin/">ダッシュボードへ</a></p>

<?php elseif (!$run): ?>
  <?php if (!empty($conf['note'])): ?><p><?= h((string)$conf['note']) ?></p><?php endif; ?>
  <div class="warn">
    <p><strong>以下の <?= count($targets) ?> 件を、data/blog-posts.json の内容で上書きします。</strong></p>
    <p>対象の記事を管理画面で手直ししていた場合、その手直しは失われます。身に覚えのない記事が混じっていたら実行しないでください。</p>
  </div>
  <ol>
    <?php foreach ($targets as $t): ?>
      <li><?= h((string)($t['date'] ?? '')) ?>　<?= h((string)($t['title'] ?? '')) ?>
        <a href="/blog/?id=<?= rawurlencode((string)$t['id']) ?>" target="_blank" rel="noopener">現在の記事を見る</a></li>
    <?php endforeach; ?>
  </ol>
  <?php if ($unknown): ?>
    <p class="warn">data/blog-posts.json に見つからないID（無視します）：<?= h(implode(' / ', $unknown)) ?></p>
  <?php endif; ?>
  <p><a class="btn" href="?run=1">この <?= count($targets) ?> 件を上書きする</a>
     <a class="btn btn--ghost" href="/admin/">やめる</a></p>

<?php else:
  $ok = 0; $ng = 0; $log = [];
  foreach ($targets as $t) {
    $id = (string)$t['id'];
    $done = false;
    try { $done = news_upsert($t); } catch (Throwable $e) { $done = false; }
    if ($done) { $ok++; $log[] = "OK: {$id}"; }
    else       { $ng++; $log[] = "NG: {$id}"; }
  }
?>
  <p><strong><?= count($targets) ?> 件を処理しました（成功 <?= $ok ?> / 失敗 <?= $ng ?>）。</strong></p>
  <pre><?= h(implode("\n", $log)) ?></pre>
  <p>反映が終わったら、data/blog-sync.json の <code>ids</code> を <code>[]</code> に戻してください。
     空にしておくことで、次回以降この画面を開いても上書きは起きません。</p>
  <p><a class="btn" href="/admin/news/">ブログ一覧で確認する</a>
     <a class="btn btn--ghost" href="/blog/" target="_blank" rel="noopener">公開ページを確認する</a></p>
<?php endif; ?>
</main>
<?= dev_badge_html() ?>
</body></html>
