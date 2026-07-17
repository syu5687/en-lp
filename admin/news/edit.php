<?php
require __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../includes/store.php';
$id = $_GET['id'] ?? '';
$item = $id ? (news_find($id) ?? []) : [];
$is_new = empty($item);
?>
<!DOCTYPE html>
<html lang="ja"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $is_new ? '新規作成' : '編集' ?>｜お知らせ管理</title>
<meta name="robots" content="noindex,nofollow">
<link rel="stylesheet" href="/admin/assets/admin.css">
</head><body>
<header class="admin-bar">
  <span class="admin-bar__title"><a href="/admin/news/">← 一覧へ戻る</a></span>
  <a href="/admin/logout.php" class="admin-bar__logout">ログアウト</a>
</header>
<main class="admin-main">
  <h1><?= $is_new ? '新規作成' : '記事を編集' ?></h1>
  <form method="post" action="/admin/news/save.php" class="admin-form">
    <input type="hidden" name="id" value="<?= htmlspecialchars($item['id'] ?? '') ?>">
    <label>日付<input type="date" name="date" value="<?= htmlspecialchars($item['date'] ?? date('Y-m-d')) ?>" required></label>
    <fieldset class="admin-cats">
      <legend>カテゴリ（複数選択可）</legend>
      <?php
        $selected = array_filter(array_map('trim', explode(',', (string)($item['category'] ?? ''))));
        foreach (BLOG_CATEGORIES as $cat): ?>
        <label class="admin-check admin-cat"><input type="checkbox" name="categories[]" value="<?= htmlspecialchars($cat) ?>" <?= in_array($cat, $selected, true) ? 'checked' : '' ?>> <?= htmlspecialchars($cat) ?></label>
      <?php endforeach; ?>
    </fieldset>
    <label>タイトル<input type="text" name="title" value="<?= htmlspecialchars($item['title'] ?? '') ?>" required></label>
    <label>本文<textarea name="body" rows="8"><?= htmlspecialchars($item['body'] ?? '') ?></textarea></label>
    <label>サムネイル画像URL（任意）<input type="text" name="image" value="<?= htmlspecialchars($item['image'] ?? '') ?>" placeholder="/assets/img/news-thumb.jpg"></label>
    <label>リンクURL（任意・記事詳細や外部ページ）<input type="text" name="link" value="<?= htmlspecialchars($item['link'] ?? '') ?>" placeholder="https://en1150.co.jp/post-xxxx/"></label>
    <label class="admin-check"><input type="checkbox" name="published" value="1" <?= !empty($item['published']) ? 'checked' : '' ?>> 公開する</label>
    <div class="admin-form__actions">
      <button type="submit" class="admin-btn">保存</button>
      <?php if (!$is_new): ?>
        <button type="submit" formaction="/admin/news/delete.php" class="admin-btn admin-btn--danger"
                onclick="return confirm('削除しますか？');">削除</button>
      <?php endif; ?>
    </div>
  </form>
</main>
</body></html>
