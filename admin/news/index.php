<?php
require __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../includes/store.php';
$items = news_all();
usort($items, fn($a,$b) => strcmp($b['date'] ?? '', $a['date'] ?? ''));
?>
<!DOCTYPE html>
<html lang="ja"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>お知らせ管理｜管理画面</title>
<meta name="robots" content="noindex,nofollow">
<link rel="stylesheet" href="/admin/assets/admin.css">
</head><body>
<header class="admin-bar">
  <span class="admin-bar__title"><a href="/admin/">← ダッシュボード</a></span>
  <a href="/admin/logout.php" class="admin-bar__logout">ログアウト</a>
</header>
<main class="admin-main">
  <div class="admin-head">
    <h1>ブログ・お知らせ</h1>
    <a class="admin-btn" href="/admin/news/edit.php">＋ 新規作成</a>
  </div>
  <table class="admin-table">
    <thead><tr><th>日付</th><th>カテゴリ</th><th>タイトル</th><th>公開</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($items as $it): ?>
      <tr>
        <td><?= htmlspecialchars($it['date'] ?? '') ?></td>
        <td><?= htmlspecialchars($it['category'] ?? '') ?></td>
        <td><?= htmlspecialchars($it['title'] ?? '') ?></td>
        <td><?= !empty($it['published']) ? '公開' : '下書き' ?></td>
        <td><a href="/admin/news/edit.php?id=<?= urlencode($it['id'] ?? '') ?>">編集</a></td>
      </tr>
    <?php endforeach; ?>
    <?php if (!$items): ?><tr><td colspan="5">まだ記事がありません。</td></tr><?php endif; ?>
    </tbody>
  </table>
</main>
</body></html>
