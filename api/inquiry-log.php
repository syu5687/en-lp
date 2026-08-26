<?php
/**
 * お問い合わせ受信ログAPI。
 * Cloudflare Worker（en-contact）がメール送信と同時に転送してくる問い合わせ内容を
 * Firestore「inquiries」コレクションへ保存する。
 *
 * セキュリティ:
 *  - HMAC-SHA256 署名（X-Signature ヘッダー）を共有鍵 INQUIRY_LOG_SECRET で検証。
 *    署名が一致しないリクエストはすべて拒否するため、第三者は書き込めない。
 *  - 保存項目はホワイトリスト方式・長さ制限つき。
 */
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../admin/includes/store.php';

header('Content-Type: application/json; charset=UTF-8');
header('X-Robots-Tag: noindex');

function il_fail(int $code, string $m): void {
  http_response_code($code);
  echo json_encode(['ok' => false, 'error' => $m], JSON_UNESCAPED_UNICODE);
  exit;
}

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') il_fail(405, 'POST only');

$raw = (string)file_get_contents('php://input');
if ($raw === '' || strlen($raw) > 32768) il_fail(400, 'bad payload');

$sig = (string)($_SERVER['HTTP_X_SIGNATURE'] ?? '');
$expect = hash_hmac('sha256', $raw, INQUIRY_LOG_SECRET);
if ($sig === '' || !hash_equals($expect, $sig)) il_fail(403, 'bad signature');

$d = json_decode($raw, true);
if (!is_array($d)) il_fail(400, 'bad json');

$take = static fn(string $k, int $max) => mb_substr(trim((string)($d[$k] ?? '')), 0, $max);

$item = [
  'name'        => $take('name', 120),
  'kana'        => $take('kana', 120),
  'email'       => $take('email', 200),
  'tel'         => $take('tel', 40),
  'category'    => $take('category', 100),
  'message'     => $take('message', 8000),
  'goudou_date' => $take('goudou_date', 20),
  'shindan'     => $take('shindan', 200),
  'pref'        => $take('pref', 20),
  'age_group'   => $take('age_group', 20),
  'gender'      => $take('gender', 20),
  'source'      => $take('source', 300),
  'received_at' => date('Y-m-d H:i:s'),
];

try {
  $ok = inquiry_add($item);
} catch (Throwable $e) {
  error_log('[inquiry-log] save failed: ' . $e->getMessage());
  $ok = false;
}
echo json_encode(['ok' => $ok], JSON_UNESCAPED_UNICODE);
