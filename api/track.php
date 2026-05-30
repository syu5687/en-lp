<?php
/**
 * アクセスログ受信エンドポイント（クライアントのビーコンを受け取りFirestoreに記録）。
 * 記録項目: path / day / ts / ref（リファラ）/ device。Cookie・IP・個人情報は保存しません。
 * 管理画面パスとボットは除外。ページ描画はブロックしません（sendBeacon）。
 */
require_once __DIR__ . '/../includes/firestore.php';

http_response_code(204); // 先に応答を返す姿勢（本文なし）
$raw = file_get_contents('php://input');
$d   = json_decode((string)$raw, true) ?: [];

$path = substr((string)($d['path'] ?? ''), 0, 300);
if ($path === '' || strpos($path, '/admin') === 0 || strpos($path, '/api') === 0) exit;

$ua = $_SERVER['HTTP_USER_AGENT'] ?? '';
if ($ua !== '' && preg_match('/bot|crawler|spider|slurp|bingpreview|facebookexternalhit/i', $ua)) exit;

$now = time();
$doc = [
  'path'   => $path,
  'day'    => date('Y-m-d', $now),
  'ts'     => $now,
  'ref'    => substr((string)($d['ref'] ?? ''), 0, 300),
  'device' => preg_match('/Mobile|Android|iPhone|iPod/i', $ua) ? 'mobile' : 'desktop',
];

try { fs_request('POST', 'documents/pageviews', ['fields' => fs_to_fields($doc)]); }
catch (Throwable $e) { /* 計測失敗はページ表示に影響させない */ }
