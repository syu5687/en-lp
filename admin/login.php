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

// セキュリティヘッダー
header('X-Frame-Options: DENY');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: same-origin');

/** クライアントIP（Cloud Run では X-Forwarded-For の先頭が実IP） */
function login_client_ip(): string {
  $xff = (string)($_SERVER['HTTP_X_FORWARDED_FOR'] ?? '');
  if ($xff !== '') {
    $parts = array_map('trim', explode(',', $xff));
    if (!empty($parts[0])) return $parts[0];
  }
  return (string)($_SERVER['REMOTE_ADDR'] ?? 'unknown');
}

/**
 * ログイン試行制限（総当たり対策）
 *   同一IPで10分以内に5回失敗 → 15分間ロック。
 *   インスタンスローカルの一時ファイルに記録（軽量・外部依存なし）。
 */
const LOGIN_MAX_FAILS   = 5;
const LOGIN_FAIL_WINDOW = 600;   // 10分
const LOGIN_LOCK_SECS   = 900;   // 15分

function login_guard_file(string $ip): string {
  return sys_get_temp_dir() . '/en-admin-fail-' . hash('sha256', $ip) . '.json';
}
function login_guard_state(string $ip): array {
  $f = login_guard_file($ip);
  $d = @json_decode((string)@file_get_contents($f), true);
  return is_array($d) ? $d : ['fails' => [], 'locked_until' => 0];
}
function login_guard_save(string $ip, array $st): void {
  @file_put_contents(login_guard_file($ip), json_encode($st), LOCK_EX);
}
function login_guard_locked(string $ip): int {
  $st = login_guard_state($ip);
  $rest = (int)$st['locked_until'] - time();
  return max(0, $rest);
}
function login_guard_fail(string $ip): void {
  $st = login_guard_state($ip);
  $now = time();
  $st['fails'] = array_values(array_filter((array)$st['fails'], fn($t) => $now - (int)$t < LOGIN_FAIL_WINDOW));
  $st['fails'][] = $now;
  if (count($st['fails']) >= LOGIN_MAX_FAILS) {
    $st['locked_until'] = $now + LOGIN_LOCK_SECS;
    $st['fails'] = [];
  }
  login_guard_save($ip, $st);
}
function login_guard_clear(string $ip): void {
  @unlink(login_guard_file($ip));
}

$error = '';
$notice = !empty($_GET['expired']) ? '一定時間操作がなかったため、自動的にログアウトしました。' : '';
$ip = login_client_ip();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $lock = login_guard_locked($ip);
  if ($lock > 0) {
    $error = 'ログイン試行が多すぎます。約' . (int)ceil($lock / 60) . '分後にもう一度お試しください。';
    error_log('[admin-login] locked ip=' . $ip);
  } else {
    $pw = $_POST['password'] ?? '';
    if (password_verify($pw, ADMIN_PASSWORD_HASH)) {
      session_regenerate_id(true); // セッション固定攻撃対策
      $_SESSION[ADMIN_SESSION_KEY] = true;
      $_SESSION['en_last_active'] = time();
      login_guard_clear($ip);
      error_log('[admin-login] success ip=' . $ip);
      header('Location: /admin/'); exit;
    }
    login_guard_fail($ip);
    error_log('[admin-login] failed ip=' . $ip);
    usleep(500000); // 応答を0.5秒遅らせ、機械的な総当たりを鈍らせる
    $error = 'パスワードが違います。';
  }
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
    <?php if ($notice): ?><p class="admin-error" style="background:#fff8e1;color:#8a6d1a"><?= htmlspecialchars($notice) ?></p><?php endif; ?>
    <?php if ($error): ?><p class="admin-error"><?= htmlspecialchars($error) ?></p><?php endif; ?>
    <input type="password" name="password" placeholder="パスワード" required autofocus>
    <button type="submit">ログイン</button>
    <p style="text-align:center;font-size:.68rem;color:#bbb;margin-top:16px">有限会社 縁 管理システム <?= htmlspecialchars(APP_VERSION) ?></p>
  </form>
<?= dev_badge_html() ?>
</body></html>
