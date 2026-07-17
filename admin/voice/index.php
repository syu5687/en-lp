<?php
require __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../includes/store.php';
$items = voices_all();
usort($items, fn($a,$b) => strcmp($b['date'] ?? '', $a['date'] ?? ''));
?>
<!DOCTYPE html>
<html lang="ja"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>お客様の声 管理｜管理画面</title>
<meta name="robots" content="noindex,nofollow">
<link rel="stylesheet" href="/admin/assets/admin.css?v=<?= h(asset_ver()) ?>">
</head><body>
<header class="admin-bar">
  <span class="admin-bar__title"><a href="/admin/">← ダッシュボード</a></span>
  <a href="/admin/logout.php" class="admin-bar__logout">ログアウト</a>
</header>
<main class="admin-main">
  <div class="admin-head">
    <h1>お客様の声</h1>
    <a class="admin-btn" href="/admin/voice/edit.php">＋ 新規作成</a>
  </div>
  <table class="admin-table">
    <thead><tr><th>日付</th><th>ご依頼内容</th><th>見出し</th><th>公開</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($items as $it): ?>
      <tr>
        <td><?= htmlspecialchars($it['date'] ?? '') ?></td>
        <td><?= htmlspecialchars($it['service'] ?? '') ?></td>
        <td><?= htmlspecialchars($it['title'] ?? '') ?></td>
        <td><?= !empty($it['published']) ? '公開' : '下書き' ?></td>
        <td><a href="/admin/voice/edit.php?id=<?= urlencode($it['id'] ?? '') ?>">編集</a></td>
      </tr>
    <?php endforeach; ?>
    <?php if (!$items): ?><tr><td colspan="5">まだ登録がありません。</td></tr><?php endif; ?>
    </tbody>
  </table>
</main>
<?= dev_badge_html() ?>
</body></html>
