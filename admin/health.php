<?php
/**
 * Firestore 接続ヘルスチェック（要ログイン）。
 * プロジェクトID取得 → トークン取得 → テスト書込→読込→削除 を実行し結果を表示。
 * デプロイ直後の動作検証に使用。
 */
require __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/../includes/firestore.php';

function row($label, $ok, $detail = '') {
  $color = $ok ? '#2e7d32' : '#c0392b';
  $mark  = $ok ? '✔ OK' : '✖ NG';
  echo "<tr><td>" . htmlspecialchars($label) . "</td>"
     . "<td style='color:$color;font-weight:700'>$mark</td>"
     . "<td style='font-size:.85rem;color:#555'>" . htmlspecialchars($detail) . "</td></tr>";
}
?>
<!DOCTYPE html>
<html lang="ja"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Firestore 接続検証｜管理画面</title>
<meta name="robots" content="noindex,nofollow">
<link rel="stylesheet" href="/admin/assets/admin.css?v=<?= h(asset_ver()) ?>">
</head><body>
<header class="admin-bar">
  <span class="admin-bar__title"><a href="/admin/">← ダッシュボード</a></span>
  <a href="/admin/logout.php" class="admin-bar__logout">ログアウト</a>
</header>
<main class="admin-main">
  <h1>Firestore 接続検証</h1>
  <table class="admin-table"><thead><tr><th>項目</th><th>結果</th><th>詳細</th></tr></thead><tbody>
<?php
// 1) プロジェクトID
$pid = '';
try { $pid = fs_project_id(); } catch (Throwable $e) {}
row('プロジェクトID取得', $pid !== '', $pid !== '' ? $pid : '取得できません（GCP_PROJECT_ID未設定 / メタデータ不可）');

// 2) アクセストークン
$tokOk = false;
try { $tokOk = (bool)fs_token(); } catch (Throwable $e) { $tokErr = $e->getMessage(); }
row('アクセストークン取得', $tokOk, $tokOk ? 'サービスアカウント認証OK' : ($tokErr ?? '失敗'));

// 3) 書込→読込→削除
$rwOk = false; $rwDetail = '';
if ($tokOk) {
  try {
    $id = 'healthcheck-' . substr(uniqid(), -6);
    $w = fs_request('PATCH', 'documents/_healthcheck/' . $id, ['fields' => fs_to_fields(['ok' => true, 'ts' => time()])]);
    $r = fs_request('GET', 'documents/_healthcheck/' . $id);
    fs_request('DELETE', 'documents/_healthcheck/' . $id);
    $rwOk = empty($w['error']) && !empty($r['fields']);
    $rwDetail = $rwOk ? '書込・読込・削除すべて成功（権限OK）' : ('応答: ' . json_encode($w['error'] ?? $r['error'] ?? 'unknown', JSON_UNESCAPED_UNICODE));
  } catch (Throwable $e) { $rwDetail = $e->getMessage(); }
}
row('読み書きテスト', $rwOk, $rwDetail);

// 4) Cloud Storage（画像アップロード先）
require_once __DIR__ . '/../includes/storage.php';
$stOk = false; $stDetail = '';
try {
  $obj = 'news/_healthcheck/ping-' . substr(uniqid(), -6) . '.png';
  // 1x1 PNG
  $png = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8z8BQDwAEhQGAhKmMIQAAAABJRU5ErkJggg==');
  $w = storage_put($obj, $png, 'image/png');
  $r = $w['ok'] ? storage_get($obj) : null;
  if ($w['ok']) storage_delete($obj);
  $stOk = $w['ok'] && $r !== null;
  $stDetail = $stOk
    ? ('保存・取得・削除すべて成功（モード: ' . $w['mode'] . ($w['mode'] === 'gcs' ? ' / バケット: ' . GCS_BUCKET : ' / ローカル開発') . '）')
    : ($w['error'] ?: '取得に失敗しました');
} catch (Throwable $e) { $stDetail = $e->getMessage(); }
row('Storage 読み書きテスト（画像アップロード）', $stOk, $stDetail);
?>
  </tbody></table>
  <p style="margin-top:20px;font-size:.88rem;color:#555">
    すべて ✔ なら Firestore 構築完了です。NGの場合は <code>firebase/setup.sh</code> で権限付与（roles/datastore.user）と Firestore 有効化をご確認ください。
  </p>
  <p style="margin-top:8px"><a class="admin-btn" href="/admin/analytics/">アクセス解析へ</a> <a class="admin-btn" href="/admin/news/">お知らせ管理へ</a></p>
</main>
<?= dev_badge_html() ?>
</body></html>
