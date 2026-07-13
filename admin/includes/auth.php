<?php
/** 認証ガード。各管理ページの先頭で require する。 */
require_once __DIR__ . '/../config.php';
session_set_cookie_params([
  'httponly' => true,
  'samesite' => 'Lax',
  'secure'   => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
             || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https'),
]);
session_start();
if (empty($_SESSION[ADMIN_SESSION_KEY])) {
  header('Location: /admin/login.php');
  exit;
}
