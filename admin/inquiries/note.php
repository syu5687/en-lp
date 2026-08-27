<?php
/**
 * 電話対応メモ登録API（管理画面の一覧モーダルから fetch で呼ぶ）
 *   POST JSON: { id, memo, staff }
 *   対応履歴（history）へ記録し、ステータス「未対応」は「対応中」へ自動更新。
 */
require __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../includes/store.php';

header('Content-Type: application/json; charset=UTF-8');

function iqn_fail(int $code, string $m): void {
  http_response_code($code);
  echo json_encode(['ok' => false, 'error' => $m], JSON_UNESCAPED_UNICODE);
  exit;
}

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') iqn_fail(405, 'POST only');

$d = json_decode((string)file_get_contents('php://input'), true);
if (!is_array($d)) iqn_fail(400, 'bad json');

$id    = trim((string)($d['id'] ?? ''));
$memo  = mb_substr(trim((string)($d['memo'] ?? '')), 0, 5000);
$staff = mb_substr(trim((string)($d['staff'] ?? '')), 0, 40);

if (!preg_match('/^inq[0-9]{14}-[0-9a-f]{6}$/', $id)) iqn_fail(400, 'bad id');
if ($memo === '') iqn_fail(400, 'メモを入力してください');
if ($staff === '') iqn_fail(400, '担当者名を入力してください');

try {
  $ok = inquiry_append_history($id, [
    't'     => 'tel',
    'at'    => date('Y-m-d H:i'),
    'staff' => $staff,
    'body'  => $memo,
  ], $staff);
} catch (Throwable $e) {
  iqn_fail(500, $e->getMessage());
}
if (!$ok) iqn_fail(500, '保存に失敗しました');
echo json_encode(['ok' => true, 'at' => date('Y-m-d H:i')], JSON_UNESCAPED_UNICODE);
