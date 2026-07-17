<?php
require __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../../includes/firestore.php';

// 期間（日数）
$days = max(1, min(90, (int)($_GET['days'] ?? 30)));
$cutoff = time() - $days * 86400;

$rows = [];
$err = '';
try {
  $rows = fs_query('pageviews', [
    'where'   => ['field' => 'ts', 'op' => 'GREATER_THAN_OR_EQUAL', 'value' => ['integerValue' => (string)$cutoff]],
    'orderBy' => ['ts', 'DESCENDING'],
    'limit'   => 5000,
  ]);
} catch (Throwable $e) { $err = $e->getMessage(); }

// 集計
$total = count($rows);
$byDay = $byPath = $byRef = $byDev = [];
for ($i = $days - 1; $i >= 0; $i--) $byDay[date('Y-m-d', time() - $i * 86400)] = 0;
foreach ($rows as $r) {
  $d = $r['day'] ?? '';
  if (isset($byDay[$d])) $byDay[$d]++;
  $byPath[$r['path'] ?? '(unknown)'] = ($byPath[$r['path'] ?? '(unknown)'] ?? 0) + 1;
  $refHost = '(直接/不明)';
  if (!empty($r['ref'])) { $h = parse_url($r['ref'], PHP_URL_HOST); if ($h) $refHost = $h; }
  $byRef[$refHost] = ($byRef[$refHost] ?? 0) + 1;
  $dev = $r['device'] ?? 'desktop';
  $byDev[$dev] = ($byDev[$dev] ?? 0) + 1;
}
arsort($byPath); arsort($byRef);
$maxDay = max(1, max($byDay ?: [1]));
$today = $byDay[date('Y-m-d')] ?? 0;
$devTotal = max(1, array_sum($byDev));
?>
<!DOCTYPE html>
<html lang="ja"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>アクセス解析｜管理画面</title>
<meta name="robots" content="noindex,nofollow">
<link rel="stylesheet" href="/admin/assets/admin.css?v=<?= h(asset_ver()) ?>">
</head><body>
<header class="admin-bar">
  <span class="admin-bar__title"><a href="/admin/">← ダッシュボード</a></span>
  <a href="/admin/logout.php" class="admin-bar__logout">ログアウト</a>
</header>
<main class="admin-main">
  <div class="admin-head">
    <h1>アクセス解析</h1>
    <form method="get" style="display:flex;gap:8px;align-items:center">
      <label style="flex-direction:row;gap:6px;align-items:center">期間
        <select name="days" onchange="this.form.submit()">
          <?php foreach ([7,14,30,60,90] as $d): ?>
            <option value="<?= $d ?>" <?= $d===$days?'selected':'' ?>><?= $d ?>日</option>
          <?php endforeach; ?>
        </select>
      </label>
    </form>
  </div>

  <?php if ($err): ?><p class="admin-error">データ取得エラー：<?= htmlspecialchars($err) ?><br>Firestoreの有効化とサービスアカウント権限をご確認ください。</p><?php endif; ?>

  <!-- KPI -->
  <div class="admin-cards" style="margin-bottom:24px">
    <div class="admin-card"><span class="admin-card__label">期間内ページビュー</span><span class="admin-card__num"><?= number_format($total) ?></span></div>
    <div class="admin-card"><span class="admin-card__label">本日</span><span class="admin-card__num"><?= number_format($today) ?></span></div>
    <div class="admin-card"><span class="admin-card__label">1日平均</span><span class="admin-card__num"><?= number_format(round($total / $days, 1), 1) ?></span></div>
    <div class="admin-card"><span class="admin-card__label">モバイル比率</span><span class="admin-card__num"><?= round(($byDev['mobile'] ?? 0) / $devTotal * 100) ?>%</span></div>
  </div>

  <!-- 日別グラフ -->
  <h2 style="font-size:1.05rem;margin:8px 0 12px">日別ページビュー（直近<?= $days ?>日）</h2>
  <div style="display:flex;align-items:flex-end;gap:2px;height:160px;background:#fff;border:1px solid #e2e6e2;border-radius:10px;padding:14px;overflow-x:auto">
    <?php foreach ($byDay as $day => $cnt): $hpct = round($cnt / $maxDay * 100); ?>
      <div title="<?= $day ?>: <?= $cnt ?>PV" style="flex:1;min-width:6px;display:flex;flex-direction:column;justify-content:flex-end;height:100%">
        <div style="background:#509F46;border-radius:3px 3px 0 0;height:<?= max(2,$hpct) ?>%"></div>
      </div>
    <?php endforeach; ?>
  </div>
  <p style="font-size:.75rem;color:#888;margin:6px 0 28px"><?= array_key_first($byDay) ?> 〜 <?= array_key_last($byDay) ?></p>

  <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px">
    <!-- 人気ページ -->
    <div>
      <h2 style="font-size:1.05rem;margin-bottom:10px">人気ページ TOP10</h2>
      <table class="admin-table"><thead><tr><th>ページ</th><th>PV</th></tr></thead><tbody>
        <?php $i=0; foreach ($byPath as $path => $cnt): if(++$i>10)break; ?>
          <tr><td><?= htmlspecialchars($path) ?></td><td><?= $cnt ?></td></tr>
        <?php endforeach; if(!$byPath): ?><tr><td colspan="2">データなし</td></tr><?php endif; ?>
      </tbody></table>
    </div>
    <!-- 流入元 -->
    <div>
      <h2 style="font-size:1.05rem;margin-bottom:10px">流入元 TOP10</h2>
      <table class="admin-table"><thead><tr><th>参照元</th><th>PV</th></tr></thead><tbody>
        <?php $i=0; foreach ($byRef as $ref => $cnt): if(++$i>10)break; ?>
          <tr><td><?= htmlspecialchars($ref) ?></td><td><?= $cnt ?></td></tr>
        <?php endforeach; if(!$byRef): ?><tr><td colspan="2">データなし</td></tr><?php endif; ?>
      </tbody></table>
    </div>
  </div>

  <p style="font-size:.78rem;color:#888;margin-top:24px">
    ※ Cookie・IPアドレス・個人情報は記録していません（プライバシー配慮の自前計測）。<br>
    ※ ボット及び管理画面へのアクセスは集計から除外しています。GA4を併用する場合は別途タグ設置も可能です。
  </p>
</main>
<?= dev_badge_html() ?>
</body></html>
