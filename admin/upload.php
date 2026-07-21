<?php
/**
 * 画像アップロードAPI（管理画面用・要ログイン）
 * POST multipart: file=<画像1枚>
 * 返却: {"ok":true,"url":"/img/news/2026/07/xxxx.jpg"} または {"ok":false,"error":"..."}
 * 保存先: Cloud Storage（GCS_BUCKET）。ローカル開発時は data/uploads/。
 */
require __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/../includes/storage.php';

header('Content-Type: application/json; charset=UTF-8');
function up_fail(string $m): void {
  echo json_encode(['ok' => false, 'error' => $m], JSON_UNESCAPED_UNICODE);
  exit;
}

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') up_fail('POSTのみ対応です');
if (empty($_FILES['file']) || !is_uploaded_file($_FILES['file']['tmp_name'] ?? '')) up_fail('ファイルがありません');

$f = $_FILES['file'];
if ($f['error'] !== UPLOAD_ERR_OK) up_fail('アップロードエラー（code=' . (int)$f['error'] . '）');
if ($f['size'] > 10 * 1024 * 1024) up_fail('ファイルサイズは10MB以下にしてください');

// 実体を検査（拡張子偽装対策・GD不要の getimagesize を使用）
$info  = @getimagesize($f['tmp_name']);
$types = [IMAGETYPE_JPEG => 'jpg', IMAGETYPE_PNG => 'png', IMAGETYPE_GIF => 'gif', IMAGETYPE_WEBP => 'webp'];
if (!$info || !isset($types[$info[2]])) up_fail('画像ファイル（JPEG / PNG / GIF / WebP）のみ登録できます');

$ext    = $types[$info[2]];
$object = 'news/' . date('Y/m/') . date('Ymd-His') . '-' . bin2hex(random_bytes(4)) . '.' . $ext;

$res = storage_put($object, (string)file_get_contents($f['tmp_name']), $info['mime']);
if (!$res['ok']) up_fail($res['error']);

echo json_encode(['ok' => true, 'url' => '/img/' . $object, 'mode' => $res['mode']], JSON_UNESCAPED_UNICODE);
