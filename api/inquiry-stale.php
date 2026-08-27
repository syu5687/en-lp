<?php
/**
 * 3日以上ステータスが動いていない未完了のお問い合わせ一覧API。
 * Cloudflare Worker（en-contact）の日次Cronが呼び出し、件数があれば管理者へ通知メールを送る。
 *
 * セキュリティ: /api/inquiry-log.php と同じ HMAC-SHA256 署名（X-Signature）を
 *               共有鍵 INQUIRY_LOG_SECRET で検証。第三者は呼び出せない。
 * 返却は通知に必要な最小限の項目のみ（メール・電話・本文は含めない）。
 */
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../admin/includes/store.php';

header('Content-Type: application/json; charset=UTF-8');
header('X-Robots-Tag: noindex');

function iqst_fail(int $code, string $m): void {
  http_response_code($code);
  echo json_encode(['ok' => false, 'error' => $m], JSON_UNESCAPED_UNICODE);
  exit;
}

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') iqst_fail(405, 'POST only');

$raw = (string)file_get_contents('php://input');
if ($raw === '' || strlen($raw) > 1024) iqst_fail(400, 'bad payload');

$sig = (string)($_SERVER['HTTP_X_SIGNATURE'] ?? '');
$expect = hash_hmac('sha256', $raw, INQUIRY_LOG_SECRET);
if ($sig === '' || !hash_equals($expect, $sig)) iqst_fail(403, 'bad signature');

$d = json_decode($raw, true);
$days = max(1, min(30, (int)($d['days'] ?? 3)));

try {
  $stale = inquiries_stale($days);
} catch (Throwable $e) {
  iqst_fail(500, $e->getMessage());
}

$items = array_map(static fn(array $i) => [
  'name'        => (string)($i['name'] ?? ''),
  'category'    => (string)($i['category'] ?? ''),
  'received_at' => (string)($i['received_at'] ?? ''),
  'status'      => (string)($i['status'] ?? '未対応') ?: '未対応',
  'staff'       => (string)($i['staff'] ?? ''),
  'last_change' => (string)($i['status_updated_at'] ?? '') ?: (string)($i['received_at'] ?? ''),
], $stale);

echo json_encode(['ok' => true, 'days' => $days, 'count' => count($items), 'items' => $items], JSON_UNESCAPED_UNICODE);
