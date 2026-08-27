<?php
/**
 * 表示速度計測API（管理画面の「計測する」ボタンから fetch で呼ぶ）
 *   POST JSON: { path, strategy(mobile|desktop) }
 *   認証: 管理セッション＋X-CSRF-Token（auth.php）
 *   ※ PSIの計測は30〜60秒かかることがあるため、タイムアウトを長めに確保
 */
require __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../includes/psi.php';

header('Content-Type: application/json; charset=UTF-8');
set_time_limit(150);

function psi_fail(int $code, string $m): void {
  http_response_code($code);
  echo json_encode(['ok' => false, 'error' => $m], JSON_UNESCAPED_UNICODE);
  exit;
}

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') psi_fail(405, 'POST only');

$d = json_decode((string)file_get_contents('php://input'), true);
if (!is_array($d)) psi_fail(400, 'bad json');

$path     = (string)($d['path'] ?? '');
$strategy = (string)($d['strategy'] ?? '');

if (!array_key_exists($path, PSI_PAGES)) psi_fail(400, '対象外のページです');
if (!in_array($strategy, ['mobile', 'desktop'], true)) psi_fail(400, 'bad strategy');

try {
  $r = psi_run($path, $strategy);
} catch (Throwable $e) {
  psi_fail(502, $e->getMessage());
}
echo json_encode(['ok' => true, 'result' => $r], JSON_UNESCAPED_UNICODE);
