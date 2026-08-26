<?php
/**
 * 認証ガード。各管理ページの先頭で require する。
 *  - セッションCookie堅牢化（HttpOnly / SameSite / HTTPS時Secure）
 *  - 無操作タイムアウト（8時間）
 *  - CSRF対策：管理画面への全POSTでトークンを検証
 *      フォームは <?= csrf_field() ?> を送信、fetch系は X-CSRF-Token ヘッダーを送信
 *  - クリックジャッキング等を防ぐセキュリティヘッダー
 */
require_once __DIR__ . '/../config.php';

session_set_cookie_params([
  'httponly' => true,
  'samesite' => 'Lax',
  'secure'   => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
             || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https'),
]);
session_start();

// セキュリティヘッダー（管理画面のみ）
header('X-Frame-Options: DENY');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: same-origin');

if (empty($_SESSION[ADMIN_SESSION_KEY])) {
  header('Location: /admin/login.php');
  exit;
}

// 無操作タイムアウト（8時間）
if (!defined('ADMIN_IDLE_TIMEOUT')) define('ADMIN_IDLE_TIMEOUT', 8 * 3600);
$now = time();
if (!empty($_SESSION['en_last_active']) && ($now - (int)$_SESSION['en_last_active']) > ADMIN_IDLE_TIMEOUT) {
  $_SESSION = [];
  session_destroy();
  header('Location: /admin/login.php?expired=1');
  exit;
}
$_SESSION['en_last_active'] = $now;

// ===== CSRF =====
if (empty($_SESSION['en_csrf'])) {
  $_SESSION['en_csrf'] = bin2hex(random_bytes(32));
}
function csrf_token(): string { return (string)($_SESSION['en_csrf'] ?? ''); }
function csrf_field(): string { return '<input type="hidden" name="_csrf" value="' . h(csrf_token()) . '">'; }

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
  $t = (string)($_POST['_csrf'] ?? ($_SERVER['HTTP_X_CSRF_TOKEN'] ?? ''));
  if ($t === '' || !hash_equals(csrf_token(), $t)) {
    http_response_code(403);
    $wantsJson = str_contains((string)($_SERVER['HTTP_ACCEPT'] ?? ''), 'json')
              || !empty($_SERVER['HTTP_X_CSRF_TOKEN'])
              || str_contains((string)($_SERVER['SCRIPT_NAME'] ?? ''), 'upload.php');
    if ($wantsJson) {
      header('Content-Type: application/json; charset=UTF-8');
      echo json_encode(['ok' => false, 'error' => 'セッションの有効期限が切れました。ページを再読み込みしてください。'], JSON_UNESCAPED_UNICODE);
    } else {
      header('Content-Type: text/html; charset=UTF-8');
      echo '不正なリクエストです。お手数ですが、前のページに戻って再読み込みのうえ、もう一度お試しください。';
    }
    exit;
  }
}
