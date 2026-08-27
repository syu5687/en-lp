<?php
/**
 * 受信メールの対応ステータス更新API（管理画面の一覧から fetch で呼ぶ）
 *   POST JSON: { id, status（未対応|対応中|対応済み）, staff（担当者名） }
 *   認証: 管理セッション必須（auth.php）＋ X-CSRF-Token ヘッダー
 */
require __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../includes/store.php';

header('Content-Type: application/json; charset=UTF-8');

function iqs_fail(int $code, string $m): void {
  http_response_code($code);
  echo json_encode(['ok' => false, 'error' => $m], JSON_UNESCAPED_UNICODE);
  exit;
}

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') iqs_fail(405, 'POST only');

$d = json_decode((string)file_get_contents('php://input'), true);
if (!is_array($d)) iqs_fail(400, 'bad json');

$id     = trim((string)($d['id'] ?? ''));
$status = trim((string)($d['status'] ?? ''));
$staff  = trim((string)($d['staff'] ?? ''));

if ($id === '' || !preg_match('/^[A-Za-z0-9_-]{4,120}$/', $id)) iqs_fail(400, 'bad id');
if (!in_array($status, INQUIRY_STATUSES, true)) iqs_fail(400, 'bad status');
if ($status !== '未対応' && $staff === '') iqs_fail(400, '担当者名を入力してください');

try {
  $ok = inquiry_update_status($id, $status, $staff);
} catch (Throwable $e) {
  iqs_fail(500, $e->getMessage());
}
echo json_encode(['ok' => $ok, 'status' => $status, 'staff' => $staff, 'at' => date('Y-m-d H:i')], JSON_UNESCAPED_UNICODE);
