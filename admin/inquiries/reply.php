<?php
/**
 * 受信メールへの返信送信API（管理画面の一覧モーダルから fetch で呼ぶ）
 *   POST JSON: { id, to, subject, body, staff }
 *   送信経路: Cloudflare Worker（en-contact /reply・HMAC署名）→ Brevo
 *   送信後は対応履歴（history）へ記録し、ステータス「未対応」は「対応中」へ自動更新。
 */
require __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../includes/store.php';

header('Content-Type: application/json; charset=UTF-8');

function iqr_fail(int $code, string $m): void {
  http_response_code($code);
  echo json_encode(['ok' => false, 'error' => $m], JSON_UNESCAPED_UNICODE);
  exit;
}

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') iqr_fail(405, 'POST only');

$d = json_decode((string)file_get_contents('php://input'), true);
if (!is_array($d)) iqr_fail(400, 'bad json');

$id      = trim((string)($d['id'] ?? ''));
$to      = trim((string)($d['to'] ?? ''));
$subject = mb_substr(trim((string)($d['subject'] ?? '')), 0, 200);
$body    = mb_substr(trim((string)($d['body'] ?? '')), 0, 10000);
$staff   = mb_substr(trim((string)($d['staff'] ?? '')), 0, 40);

if (!preg_match('/^inq[0-9]{14}-[0-9a-f]{6}$/', $id)) iqr_fail(400, 'bad id');
if (!filter_var($to, FILTER_VALIDATE_EMAIL)) iqr_fail(400, '宛先メールアドレスが不正です');
if ($subject === '') iqr_fail(400, '件名を入力してください');
if ($body === '') iqr_fail(400, '本文を入力してください');
if ($staff === '') iqr_fail(400, '担当者名を入力してください');

// 念のため、宛先がこの受信データのメールアドレスと一致することを確認（誤送信・改ざん防止）
$doc = null;
try { $doc = inquiry_find($id); } catch (Throwable $e) { iqr_fail(500, $e->getMessage()); }
if ($doc === null) iqr_fail(404, '受信データが見つかりません');
if (strcasecmp(trim((string)($doc['email'] ?? '')), $to) !== 0) iqr_fail(400, '宛先が受信データのメールアドレスと一致しません');

// Worker（/reply）へ HMAC 署名付きで送信依頼
$payload = json_encode([
  'to'       => $to,
  'toName'   => (string)($doc['name'] ?? ''),
  'subject'  => $subject,
  'body'     => $body,
  'staff'    => $staff,
], JSON_UNESCAPED_UNICODE);
$sig = hash_hmac('sha256', $payload, INQUIRY_LOG_SECRET);
$resp = @file_get_contents(CONTACT_WORKER_URL . '/reply', false, stream_context_create(['http' => [
  'method'        => 'POST',
  'header'        => "Content-Type: application/json\r\nX-Signature: {$sig}\r\n",
  'content'       => $payload,
  'timeout'       => 20,
  'ignore_errors' => true,
]]));
$j = json_decode((string)$resp, true);
if (!is_array($j) || empty($j['ok'])) {
  iqr_fail(502, 'メール送信に失敗しました' . (is_array($j) && !empty($j['error']) ? '：' . $j['error'] : '（Workerに接続できません。wrangler deploy 済みかご確認ください）'));
}

// 履歴へ記録
$ok = false;
try {
  $ok = inquiry_append_history($id, [
    't'       => 'email',
    'at'      => date('Y-m-d H:i'),
    'staff'   => $staff,
    'to'      => $to,
    'subject' => $subject,
    'body'    => $body,
  ], $staff);
} catch (Throwable $e) {
  // 送信自体は成功しているので、履歴保存失敗はメッセージ付きで返す
  echo json_encode(['ok' => true, 'warn' => '送信は完了しましたが履歴の保存に失敗しました：' . $e->getMessage()], JSON_UNESCAPED_UNICODE);
  exit;
}
echo json_encode(['ok' => true, 'saved' => $ok, 'at' => date('Y-m-d H:i')], JSON_UNESCAPED_UNICODE);
