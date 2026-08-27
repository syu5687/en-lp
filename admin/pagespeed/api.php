<?php
/**
 * 表示速度計測の保存API
 *   計測はブラウザがPSI APIを直接呼んで行い（CORS対応・タイムアウトなし）、
 *   このAPIは検証済みサマリーの保存だけを担当する（数百ミリ秒で完了）。
 *   POST JSON: { path, strategy, score, fcp, lcp, cls, tbt, si, opps[], field_overall }
 *   認証: 管理セッション＋X-CSRF-Token（auth.php）
 */
require __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../includes/psi.php';

header('Content-Type: application/json; charset=UTF-8');

function psi_fail(int $code, string $m): void {
  http_response_code($code);
  echo json_encode(['ok' => false, 'error' => $m], JSON_UNESCAPED_UNICODE);
  exit;
}

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') psi_fail(405, 'POST only');

$d = json_decode((string)file_get_contents('php://input'), true);
if (!is_array($d)) psi_fail(400, 'bad json');

try {
  $r = psi_save($d);
} catch (InvalidArgumentException $e) {
  psi_fail(400, $e->getMessage());
} catch (Throwable $e) {
  psi_fail(500, $e->getMessage());
}
echo json_encode(['ok' => true, 'result' => $r], JSON_UNESCAPED_UNICODE);
