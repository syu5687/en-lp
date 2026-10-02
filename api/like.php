<?php
/**
 * ブログ記事の「いいね」API。
 *
 *   POST /api/like.php
 *   Content-Type: application/json
 *   {"id":"<記事ID>","action":"add"|"remove"}
 *   → {"ok":true,"count":12,"liked":true}
 *
 * セキュリティ・乱用対策（公開APIのため多段で絞る）
 *  1) POST のみ。本文は 512 バイトまで。
 *  2) 同一オリジンからの操作のみ許可（Sec-Fetch-Site / Origin / Referer を検証）。
 *  3) 記事IDは形式検証のうえ、公開中の記事として実在するものだけ受け付ける
 *     （存在しないIDでコレクションを汚されるのを防ぐ）。
 *  4) Cookie で同一ブラウザからの二重加算を抑止。
 *  5) インスタンス単位のトークンバケット（分・日）で書き込み総量に上限を設ける。
 *  6) BLOG_LIKE_ENABLED = false にすれば機能全体を即停止できる。
 *
 * 保存するのは記事IDごとの件数のみ。IP・UA・個人を識別する情報は保存しない。
 */
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/blog-likes.php';

header('Content-Type: application/json; charset=UTF-8');
header('Cache-Control: no-store');
header('X-Robots-Tag: noindex');

function lk_out(int $code, array $body): void {
  http_response_code($code);
  echo json_encode($body, JSON_UNESCAPED_UNICODE);
  exit;
}
function lk_fail(int $code, string $m): void { lk_out($code, ['ok' => false, 'error' => $m]); }

if (!BLOG_LIKE_ENABLED)                               lk_fail(404, 'disabled');
if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST')    lk_fail(405, 'POST only');

/* ---- 同一オリジン確認 ---------------------------------------------------- */
$site = (string)($_SERVER['HTTP_SEC_FETCH_SITE'] ?? '');
if ($site !== '' && $site !== 'same-origin') lk_fail(403, 'cross origin');
if ($site === '') {
  // Sec-Fetch-Site を送らない環境向けの代替判定
  $host   = (string)($_SERVER['HTTP_HOST'] ?? '');
  $ref    = (string)($_SERVER['HTTP_ORIGIN'] ?? ($_SERVER['HTTP_REFERER'] ?? ''));
  $refHost = $ref === '' ? '' : (string)(parse_url($ref, PHP_URL_HOST) ?? '');
  $ok = $refHost !== '' && ($refHost === $host || $refHost === parse_url(SITE['url'], PHP_URL_HOST));
  if (!$ok) lk_fail(403, 'cross origin');
}

/* ---- 入力 --------------------------------------------------------------- */
$raw = (string)file_get_contents('php://input');
if ($raw === '' || strlen($raw) > 512) lk_fail(400, 'bad payload');
$d = json_decode($raw, true);
if (!is_array($d)) lk_fail(400, 'bad json');

$id     = trim((string)($d['id'] ?? ''));
$action = (string)($d['action'] ?? 'add');
if (!in_array($action, ['add', 'remove'], true)) lk_fail(400, 'bad action');
if (!blog_like_id_ok($id))                       lk_fail(400, 'unknown article');

/* ---- Cookie の状態と突き合わせる ---------------------------------------- */
$key   = blog_like_key($id);
$keys  = blog_like_cookie_keys();
$has   = in_array($key, $keys, true);

if ($action === 'add' && $has) {
  // すでに押している。書き込みは行わず、現在値を返す
  lk_out(200, ['ok' => true, 'count' => blog_like_count($id), 'liked' => true, 'noop' => true]);
}
if ($action === 'remove' && !$has) {
  lk_out(200, ['ok' => true, 'count' => blog_like_count($id), 'liked' => false, 'noop' => true]);
}

/* ---- 書き込み総量の上限 -------------------------------------------------- */
if (!blog_like_throttle_ok()) lk_fail(429, 'too many requests');

/* ---- 更新 --------------------------------------------------------------- */
$delta = $action === 'add' ? 1 : -1;
$count = blog_like_bump($id, $delta);
if ($count === null) lk_fail(500, 'update failed');

if ($action === 'add') { $keys[] = $key; }
else { $keys = array_values(array_filter($keys, fn($k) => $k !== $key)); }
blog_like_cookie_save($keys);

lk_out(200, ['ok' => true, 'count' => $count, 'liked' => $action === 'add']);
