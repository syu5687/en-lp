<?php
require __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../includes/store.php';
$id = $_GET['id'] ?? '';
$item = $id ? (voice_find($id) ?? []) : [];
$is_new = empty($item);
?>
<!DOCTYPE html>
<html lang="ja"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $is_new ? '新規作成' : '編集' ?>｜お客様の声管理</title>
<meta name="robots" content="noindex,nofollow">
<link rel="stylesheet" href="/admin/assets/admin.css">
</head><body>
<header class="admin-bar">
  <span class="admin-bar__title"><a href="/admin/voice/">← 一覧へ戻る</a></span>
  <a href="/admin/logout.php" class="admin-bar__logout">ログアウト</a>
</header>
<main class="admin-main">
  <h1><?= $is_new ? '新規作成' : 'お客様の声を編集' ?></h1>
  <form method="post" action="/admin/voice/save.php" class="admin-form">
    <input type="hidden" name="id" value="<?= htmlspecialchars($item['id'] ?? '') ?>">
    <label>日付<input type="date" name="date" value="<?= htmlspecialchars($item['date'] ?? date('Y-m-d')) ?>" required></label>
    <label>ご依頼内容（サービス）<input type="text" name="service" value="<?= htmlspecialchars($item['service'] ?? '') ?>" placeholder="例：委託海洋葬"></label>
    <label>見出し（お客様の言葉）<input type="text" name="title" value="<?= htmlspecialchars($item['title'] ?? '') ?>" required></label>
    <label>ご依頼のきっかけ<textarea name="reason" rows="3"><?= htmlspecialchars($item['reason'] ?? '') ?></textarea></label>
    <label>ご感想<textarea name="impression" rows="6"><?= htmlspecialchars($item['impression'] ?? '') ?></textarea></label>
    <label>お客様の属性<input type="text" name="who" value="<?= htmlspecialchars($item['who'] ?? '') ?>" placeholder="例：鹿児島県 70歳代 女性 A様"></label>
    <label class="admin-check"><input type="checkbox" name="published" value="1" <?= !empty($item['published']) ? 'checked' : '' ?>> 公開する</label>
    <div class="admin-form__actions">
      <button type="submit" class="admin-btn">保存</button>
      <?php if (!$is_new): ?>
        <button type="submit" formaction="/admin/voice/delete.php" class="admin-btn admin-btn--danger"
                onclick="return confirm('削除しますか？');">削除</button>
      <?php endif; ?>
    </div>
  </form>
</main>
</body></html>
