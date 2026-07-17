<?php
require_once __DIR__ . '/config.php';
// セッションCookieを堅牢化（HttpOnly / SameSite / HTTPS時Secure）
session_set_cookie_params([
  'httponly' => true,
  'samesite' => 'Lax',
  'secure'   => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
             || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https'),
]);
session_start();
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $pw = $_POST['password'] ?? '';
  if (password_verify($pw, ADMIN_PASSWORD_HASH)) {
    session_regenerate_id(true); // セッション固定攻撃対策
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
<link rel="stylesheet" href="/admin/assets/admin.css?v=<?= h(asset_ver()) ?>">
</head><body class="admin-login">
  <form method="post" class="admin-login__box">
    <h1>管理画面</h1>
    <p style="text-align:center;font-size:.72rem;color:#999;margin:-4px 0 12px;letter-spacing:.05em"><?= htmlspecialchars(APP_VERSION) ?></p>
    <?php if ($error): ?><p class="admin-error"><?= htmlspecialchars($error) ?></p><?php endif; ?>
    <input type="password" name="password" placeholder="パスワード" required autofocus>
    <button type="submit">ログイン</button>
    <p style="text-align:center;font-size:.68rem;color:#bbb;margin-top:16px">有限会社 縁 管理システム <?= htmlspecialchars(APP_VERSION) ?></p>
  </form>
<?= dev_badge_html() ?>
</body></html>
