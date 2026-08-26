<?php
/**
 * お問い合わせ受信 一覧・解析（DB化されたフォーム送信内容）
 *  - 期間フィルタ／CSV出力／集計（月別・種別・地域・年代・性別・流入ページ）
 */
require __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../includes/store.php';

$items = [];
$fs_error = '';
try { $items = inquiries_all(); } catch (Throwable $e) { $fs_error = $e->getMessage(); }

// ---- 期間フィルタ ----
$range = (string)($_GET['range'] ?? '365');
$ranges = ['30' => '直近30日', '90' => '直近90日', '365' => '直近1年', 'all' => '全期間'];
if (!isset($ranges[$range])) $range = '365';
if ($range !== 'all') {
  $limitDate = date('Y-m-d', strtotime('-' . (int)$range . ' days'));
  $items = array_values(array_filter($items, fn($i) => substr((string)($i['received_at'] ?? ''), 0, 10) >= $limitDate));
}

// ---- CSV出力（Excelで開ける UTF-8 BOM 付き） ----
if (!empty($_GET['export'])) {
  header('Content-Type: text/csv; charset=UTF-8');
  header('Content-Disposition: attachment; filename="inquiries_' . date('Ymd') . '.csv"');
  echo "\xEF\xBB\xBF";
  $out = fopen('php://output', 'w');
  fputcsv($out, ['受信日時', 'お名前', 'ふりがな', 'メール', '電話', '種別', 'お住まい', '年代', '性別', '合同散骨希望日', '診断結果', '送信元ページ', '内容']);
  foreach ($items as $i) {
    fputcsv($out, [
      $i['received_at'] ?? '', $i['name'] ?? '', $i['kana'] ?? '', $i['email'] ?? '', $i['tel'] ?? '',
      $i['category'] ?? '', $i['pref'] ?? '', $i['age_group'] ?? '', $i['gender'] ?? '',
      $i['goudou_date'] ?? '', $i['shindan'] ?? '', $i['source'] ?? '', $i['message'] ?? '',
    ]);
  }
  fclose($out);
  exit;
}

// ---- 集計 ----
$agg = static function (array $items, callable $keyFn): array {
  $out = [];
  foreach ($items as $i) { $k = $keyFn($i); if ($k === '') $k = '（未回答）'; $out[$k] = ($out[$k] ?? 0) + 1; }
  arsort($out);
  return $out;
};
$kyushu = ['福岡県', '佐賀県', '長崎県', '熊本県', '大分県', '宮崎県', '沖縄県'];
$byMonth  = [];
for ($m = 11; $m >= 0; $m--) $byMonth[date('Y-m', strtotime("-$m month"))] = 0;
foreach ($items as $i) { $ym = substr((string)($i['received_at'] ?? ''), 0, 7); if (isset($byMonth[$ym])) $byMonth[$ym]++; }
$byCat    = $agg($items, fn($i) => trim((string)($i['category'] ?? '')));
$byRegion = $agg($items, function ($i) use ($kyushu) {
  $p = trim((string)($i['pref'] ?? ''));
  if ($p === '') return '';
  if ($p === '鹿児島県') return '鹿児島県';
  if ($p === '福岡県') return '福岡県';
  if (in_array($p, $kyushu, true)) return '九州（その他）';
  return '九州以外';
});
$byAge    = $agg($items, fn($i) => trim((string)($i['age_group'] ?? '')));
$byGender = $agg($items, fn($i) => trim((string)($i['gender'] ?? '')));
$bySource = $agg($items, function ($i) {
  $s = (string)($i['source'] ?? '');
  $p = parse_url($s, PHP_URL_PATH);
  return $p ? (string)$p : '';
});
$total = count($items);
$thisMonth = 0; $lastMonth = 0;
$tm = date('Y-m'); $lm = date('Y-m', strtotime('-1 month'));
foreach ($items as $i) {
  $ym = substr((string)($i['received_at'] ?? ''), 0, 7);
  if ($ym === $tm) $thisMonth++; elseif ($ym === $lm) $lastMonth++;
}
$barMax = max(1, max($byMonth ?: [1]));

function iq_bars(array $data, int $top = 8): string {
  $out = '';
  $max = max(1, max($data ?: [1]));
  $rows = array_slice($data, 0, $top, true);
  foreach ($rows as $k => $v) {
    $w = max(2, (int)round($v / $max * 100));
    $out .= '<div class="iq-bar"><span class="iq-bar__label">' . h($k) . '</span>'
          . '<span class="iq-bar__track"><span class="iq-bar__fill" style="width:' . $w . '%"></span></span>'
          . '<span class="iq-bar__num">' . (int)$v . '</span></div>';
  }
  return $out !== '' ? $out : '<p class="iq-empty">データがまだありません</p>';
}
?>
<!DOCTYPE html>
<html lang="ja"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>お問い合わせ受信・解析｜管理画面</title>
<meta name="robots" content="noindex,nofollow">
<link rel="stylesheet" href="/admin/assets/admin.css?v=<?= h(asset_ver()) ?>">
<style>
  .iq-summary{display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:12px;margin-bottom:22px}
  .iq-sum{background:#fff;border-radius:10px;padding:14px 16px;box-shadow:0 2px 8px rgba(0,0,0,.05)}
  .iq-sum b{display:block;font-size:1.5rem;color:#15709e}
  .iq-sum span{font-size:.78rem;color:#789}
  .iq-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:16px;margin-bottom:26px}
  .iq-panel{background:#fff;border-radius:10px;padding:16px 18px;box-shadow:0 2px 8px rgba(0,0,0,.05)}
  .iq-panel h2{font-size:.92rem;color:#0a3852;margin-bottom:12px}
  .iq-bar{display:flex;align-items:center;gap:8px;margin-bottom:7px;font-size:.82rem}
  .iq-bar__label{flex:none;width:38%;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;color:#345}
  .iq-bar__track{flex:1;background:#eef3f6;border-radius:99px;height:12px;overflow:hidden}
  .iq-bar__fill{display:block;height:100%;background:linear-gradient(90deg,#1f8fce,#15709e);border-radius:99px}
  .iq-bar__num{flex:none;width:34px;text-align:right;font-weight:700;color:#0a3852}
  .iq-empty{font-size:.82rem;color:#99a}
  .iq-month{display:flex;align-items:flex-end;gap:4px;height:110px}
  .iq-month>div{flex:1;display:flex;flex-direction:column;justify-content:flex-end;align-items:center;gap:4px}
  .iq-month i{display:block;width:100%;background:linear-gradient(180deg,#1f8fce,#15709e);border-radius:4px 4px 0 0;min-height:2px}
  .iq-month b{font-size:.68rem;color:#456}
  .iq-month small{font-size:.6rem;color:#9ab;writing-mode:vertical-rl;letter-spacing:0}
  .iq-table{width:100%;border-collapse:collapse;background:#fff;border-radius:10px;overflow:hidden;font-size:.85rem}
  .iq-table th,.iq-table td{padding:9px 12px;border-bottom:1px solid #eee;text-align:left;vertical-align:top}
  .iq-table th{background:#f2f6f8;font-size:.76rem;color:#456;white-space:nowrap}
  .iq-tools{display:flex;flex-wrap:wrap;gap:10px;align-items:center;margin-bottom:18px}
  .iq-tools a{font-size:.85rem}
  details.iq-msg summary{cursor:pointer;color:#15709e;font-size:.8rem}
  details.iq-msg p{white-space:pre-wrap;font-size:.83rem;background:#f7f9fa;border-radius:8px;padding:10px;margin-top:6px;max-width:480px}
</style>
</head><body>
<header class="admin-bar">
  <span class="admin-bar__title"><a href="/admin/">← ダッシュボード</a>　お問い合わせ受信・解析</span>
  <a href="/admin/logout.php" class="admin-bar__logout">ログアウト</a>
</header>
<main class="admin-main">
  <h1>お問い合わせ受信・解析</h1>
  <p style="font-size:.88rem;color:#667;margin-bottom:16px">フォームから送信された内容の記録と、Web運営に役立つ集計です。属性（お住まい・年代・性別）は任意回答のため、未回答が含まれます。</p>
  <?php if ($fs_error): ?><p style="background:#fdecea;color:#c0392b;padding:10px 16px;border-radius:8px;margin-bottom:14px">データ取得エラー: <?= h($fs_error) ?></p><?php endif; ?>

  <div class="iq-tools">
    <?php foreach ($ranges as $rk => $rl): ?>
      <a href="?range=<?= h($rk) ?>" class="admin-btn <?= $range === $rk ? '' : 'admin-btn--outline' ?>" style="padding:6px 14px;font-size:.8rem"><?= h($rl) ?></a>
    <?php endforeach; ?>
    <a href="?range=<?= h($range) ?>&amp;export=csv" class="admin-btn admin-btn--outline" style="padding:6px 14px;font-size:.8rem;margin-left:auto">CSVダウンロード</a>
  </div>

  <div class="iq-summary">
    <div class="iq-sum"><b><?= $total ?>件</b><span><?= h($ranges[$range]) ?>の受信</span></div>
    <div class="iq-sum"><b><?= $thisMonth ?>件</b><span>今月</span></div>
    <div class="iq-sum"><b><?= $lastMonth ?>件</b><span>先月</span></div>
  </div>

  <div class="iq-grid">
    <div class="iq-panel" style="grid-column:1/-1">
      <h2>月別の受信件数（直近12か月）</h2>
      <div class="iq-month">
        <?php foreach ($byMonth as $ym => $c): ?>
          <div><b><?= (int)$c ?></b><i style="height:<?= max(2, (int)round($c / $barMax * 80)) ?>px"></i><small><?= h(substr($ym, 2)) ?></small></div>
        <?php endforeach; ?>
      </div>
    </div>
    <div class="iq-panel"><h2>お問い合わせ種別</h2><?= iq_bars($byCat) ?></div>
    <div class="iq-panel"><h2>地域（お住まい）</h2><?= iq_bars($byRegion) ?></div>
    <div class="iq-panel"><h2>年代</h2><?= iq_bars($byAge) ?></div>
    <div class="iq-panel"><h2>性別</h2><?= iq_bars($byGender) ?></div>
    <div class="iq-panel" style="grid-column:1/-1"><h2>送信元ページ（どのページから相談が来たか）</h2><?= iq_bars($bySource, 10) ?></div>
  </div>

  <h2 style="font-size:1rem;color:#0a3852;margin-bottom:10px">受信一覧（新しい順・最大100件表示）</h2>
  <div style="overflow-x:auto">
    <table class="iq-table">
      <tr><th>受信日時</th><th>お名前</th><th>種別</th><th>属性</th><th>連絡先</th><th>内容ほか</th></tr>
      <?php foreach (array_slice($items, 0, 100) as $i): ?>
        <tr>
          <td style="white-space:nowrap"><?= h($i['received_at'] ?? '') ?></td>
          <td style="font-weight:700"><?= h($i['name'] ?? '') ?><br><span style="font-weight:400;color:#89a;font-size:.76rem"><?= h($i['kana'] ?? '') ?></span></td>
          <td><?= h($i['category'] ?? '') ?><?= !empty($i['goudou_date']) ? '<br><span style="font-size:.76rem;color:#567">希望日 ' . h($i['goudou_date']) . '</span>' : '' ?></td>
          <td style="font-size:.8rem;color:#456"><?= h(implode('／', array_filter([$i['pref'] ?? '', $i['age_group'] ?? '', $i['gender'] ?? '']))) ?: '—' ?></td>
          <td style="font-size:.8rem"><?= h($i['email'] ?? '') ?><br><?= h($i['tel'] ?? '') ?></td>
          <td>
            <?php if (!empty($i['message'])): ?>
              <details class="iq-msg"><summary>内容を見る</summary><p><?= h($i['message']) ?></p></details>
            <?php endif; ?>
            <?php if (!empty($i['shindan'])): ?><p style="font-size:.76rem;color:#567">診断: <?= h($i['shindan']) ?></p><?php endif; ?>
            <?php if (!empty($i['source'])): ?><p style="font-size:.72rem;color:#9ab"><?= h((string)(parse_url((string)$i['source'], PHP_URL_PATH) ?? '')) ?></p><?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
      <?php if (!$items && !$fs_error): ?><tr><td colspan="6" style="color:#888">まだ受信データがありません。フォームから送信があると自動で記録されます。</td></tr><?php endif; ?>
    </table>
  </div>
  <p style="font-size:.76rem;color:#99a;margin-top:14px">※ 個人情報を含むため、取り扱いにご注意ください。表示・CSVのデータは管理画面にログインした方のみ閲覧できます。</p>
</main>
</body></html>
