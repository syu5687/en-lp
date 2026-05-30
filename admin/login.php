<?php
require_once __DIR__ . '/config.php';
session_start();
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $pw = $_POST['password'] ?? '';
  if (password_verify($pw, ADMIN_PASSWORD_HASH)) {
    $_SESSION[ADMIN_SESSION_KEY] = true;
    header('Location: /admin/'); exit;
  }
  $error = 'パスワードが違います。';
}
?>
<!DOCTYPE html>
<html lang="ja"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>管理画面ログイン｜有限会社 縁</title>
<meta name="robots" content="noindex,nofollow">
<link rel="stylesheet" href="/admin/assets/admin.css">
</head><body class="admin-login">
  <form method="post" class="admin-login__box">
    <h1>管理画面</h1>
    <?php if ($error): ?><p class="admin-error"><?= htmlspecialchars($error) ?></p><?php endif; ?>
    <input type="password" name="password" placeholder="パスワード" required autofocus>
    <button type="submit">ログイン</button>
  </form>
</body></html>
