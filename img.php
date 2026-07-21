<?php
/**
 * 画像配信プロキシ： /img/<object> → Cloud Storage（またはローカル開発保存分）
 * オブジェクト名はユニーク生成のため長期キャッシュ（1年・immutable）を付与。
 * .htaccess: RewriteRule ^img/(.+)$ /img.php?p=$1
 */
require_once __DIR__ . '/includes/storage.php';

$p = (string)($_GET['p'] ?? '');
if ($p === '') {
  $path = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?: '';
  if (strpos($path, '/img/') === 0) $p = rawurldecode(substr($path, 5));
}

if (!storage_valid_object($p)) { http_response_code(404); exit; }

$data = storage_get($p);
if ($data === null) { http_response_code(404); exit; }

$ext   = strtolower(pathinfo($p, PATHINFO_EXTENSION));
$mimes = ['jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png',
          'gif' => 'image/gif', 'webp' => 'image/webp'];

header('Content-Type: ' . ($mimes[$ext] ?? 'application/octet-stream'));
header('Content-Length: ' . strlen($data));
header('Cache-Control: public, max-age=31536000, immutable'); // config.php の no-store を上書き
header_remove('Pragma');
header_remove('Expires');
echo $data;
