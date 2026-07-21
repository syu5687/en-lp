<?php
require __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../includes/store.php';
$items = [];
$fs_error = '';
try { $items = news_all(); } catch (Throwable $e) { $fs_error = $e->getMessage(); }
usort($items, fn($a,$b) => strcmp($b['date'] ?? '', $a['date'] ?? ''));
?>
<!DOCTYPE html>
<html lang="ja"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>お知らせ管理｜管理画面</title>
<meta name="robots" content="noindex,nofollow">
<link rel="stylesheet" href="/admin/assets/admin.css?v=<?= h(asset_ver()) ?>">
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
  <?php if ($fs_error): ?>
    <p class="admin-error">Firestoreに接続できませんでした。接続設定（サービスアカウントの権限）をご確認ください。<br><small><?= htmlspecialchars($fs_error) ?></small></p>
  <?php endif; ?>
  <table class="admin-table">
    <thead><tr><th></th><th>日付</th><th>カテゴリ</th><th>タイトル</th><th>公開</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($items as $it): ?>
      <?php
        $thumb = $it['image'] ?? '';
        if ($thumb === '' && !empty($it['images'][0])) $thumb = $it['images'][0];
        $img_count = is_array($it['images'] ?? null) ? count($it['images']) : ($thumb ? 1 : 0);
      ?>
      <tr>
        <td class="admin-td-thumb">
          <?php if ($thumb): ?>
            <span class="admin-thumb"><img src="<?= htmlspecialchars($thumb) ?>" alt="" loading="lazy">
            <?php if ($img_count > 1): ?><span class="admin-thumb__n"><?= (int)$img_count ?></span><?php endif; ?></span>
          <?php else: ?>
            <span class="admin-thumb admin-thumb--none">なし</span>
          <?php endif; ?>
        </td>
        <td><?= htmlspecialchars($it['date'] ?? '') ?></td>
        <td><?= htmlspecialchars($it['category'] ?? '') ?></td>
        <td><?= htmlspecialchars($it['title'] ?? '') ?></td>
        <td><?= !empty($it['published']) ? '公開' : '下書き' ?></td>
        <td><a href="/admin/news/edit.php?id=<?= urlencode($it['id'] ?? '') ?>">編集</a></td>
      </tr>
    <?php endforeach; ?>
    <?php if (!$items): ?><tr><td colspan="6">まだ記事がありません。</td></tr><?php endif; ?>
    </tbody>
  </table>
</main>
<?= dev_badge_html() ?>
</body></html>
