<?php
/**
 * 効果測定レポート（リニューアル後のGA4アクセス解析・15日ごと）
 * - GA4_REPORT_ANCHOR（リニューアル公開日）を起点に15日を1期間として自動集計
 * - 完了した期間はこの画面を開いたときに自動生成され、Firestore（ga_reports）に蓄積される
 * - 進行中の期間は「速報」としてリアルタイム表示（保存はしない）
 */
require __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/ga4-report.php';

$reports = [];
$gen_err = $list_err = '';
try {
  [$reports, $gen_err] = ga_reports_sync(3);
} catch (Throwable $e) { $list_err = $e->getMessage(); }

// 進行中の期間（速報・保存しない）
$live = null; $live_err = '';
$cur = ga4_current_period();
if ($cur) {
  try { $live = ['start' => $cur['start'], 'end' => $cur['end'], 'data' => ga4_fetch($cur['start'], $cur['end'])]; }
  catch (Throwable $e) { $live_err = $e->getMessage(); }
}

// 比較用：期間IDの昇順配列（前期比・初期比の計算）
$asc = $reports; ksort($asc);
$ascIds = array_keys($asc);
$first = $ascIds ? $asc[$ascIds[0]] : null;

/** 増減表示 */
function delta_html($now, $prev, bool $pct = true): string {
  if ($prev === null) return '<span class="rp-delta">—</span>';
  $prev = (float)$prev;
  if ($prev == 0.0) return '<span class="rp-delta">—</span>';
  $diff = ((float)$now - $prev) / $prev * 100;
  $cls = $diff > 0 ? 'up' : ($diff < 0 ? 'down' : '');
  $sign = $diff > 0 ? '+' : '';
  return '<span class="rp-delta ' . $cls . '">' . $sign . number_format($diff, 1) . '%</span>';
}
function fmt_sec(int $s): string { return floor($s / 60) . '分' . ($s % 60) . '秒'; }

/** 1レポート分の中身を描画 */
function render_report(array $data, ?array $prevData, string $start, string $end): void {
  $s = $data['summary']; $cv = $data['cv'];
  $cvTotal = array_sum($cv);
  $ps = $prevData['summary'] ?? null;
  $pcv = $prevData['cv'] ?? null;
  $pcvTotal = $pcv !== null ? array_sum($pcv) : null;
  ?>
  <div class="rp-kpis">
    <div class="rp-kpi"><span>セッション</span><strong><?= number_format($s['sessions']) ?></strong><?= delta_html($s['sessions'], $ps['sessions'] ?? null) ?></div>
    <div class="rp-kpi"><span>ユーザー</span><strong><?= number_format($s['users']) ?></strong><?= delta_html($s['users'], $ps['users'] ?? null) ?></div>
    <div class="rp-kpi"><span>新規ユーザー</span><strong><?= number_format($s['newUsers']) ?></strong><?= delta_html($s['newUsers'], $ps['newUsers'] ?? null) ?></div>
    <div class="rp-kpi"><span>ページビュー</span><strong><?= number_format($s['pv']) ?></strong><?= delta_html($s['pv'], $ps['pv'] ?? null) ?></div>
    <div class="rp-kpi rp-kpi--cv"><span>お問い合わせ行動（CV）</span><strong><?= number_format($cvTotal) ?></strong><?= delta_html($cvTotal, $pcvTotal) ?></div>
    <div class="rp-kpi"><span>平均セッション時間</span><strong style="font-size:1.05rem"><?= fmt_sec($s['avgSec']) ?></strong></div>
    <div class="rp-kpi"><span>エンゲージメント率</span><strong style="font-size:1.05rem"><?= $s['engRate'] ?>%</strong></div>
  </div>
  <div class="rp-cvrow">
    CV内訳：フォーム送信 <strong><?= $cv['generate_lead'] ?></strong> ／ 電話タップ <strong><?= $cv['tel_click'] ?></strong> ／ LINEタップ <strong><?= $cv['line_click'] ?></strong>
    <span style="color:#8a948a;font-size:.78rem">（tel_click・line_click はタグ設置後の期間から計測されます）</span>
  </div>

  <?php $max = 1; foreach ($data['daily'] as $d) $max = max($max, $d['v']); ?>
  <p class="rp-sub">日別セッション</p>
  <div class="rp-chart">
    <?php foreach ($data['daily'] as $d): ?>
      <div class="rp-bar" title="<?= h($d['d']) ?>: <?= (int)$d['v'] ?>"><i style="height:<?= max(2, round($d['v'] / $max * 100)) ?>%"></i></div>
    <?php endforeach; ?>
  </div>

  <div class="rp-cols">
    <div>
      <p class="rp-sub">流入チャネル</p>
      <?php $cmax = 1; foreach ($data['channels'] as $c) $cmax = max($cmax, $c['v']); ?>
      <table class="rp-table">
        <?php foreach ($data['channels'] as $c): ?>
          <tr><td><?= h($c['name']) ?></td>
              <td class="rp-table__bar"><i style="width:<?= round($c['v'] / $cmax * 100) ?>%"></i></td>
              <td class="rp-table__num"><?= number_format($c['v']) ?></td></tr>
        <?php endforeach; ?>
      </table>
    </div>
    <div>
      <p class="rp-sub">人気ページ TOP10</p>
      <table class="rp-table">
        <?php foreach ($data['pages'] as $pg): ?>
          <tr><td style="word-break:break-all"><?= h($pg['path']) ?></td><td class="rp-table__num"><?= number_format($pg['v']) ?></td></tr>
        <?php endforeach; ?>
      </table>
    </div>
  </div>
  <?php
}
?>
<!DOCTYPE html>
<html lang="ja"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>効果測定レポート｜管理画面</title>
<meta name="robots" content="noindex,nofollow">
<link rel="stylesheet" href="/admin/assets/admin.css?v=<?= h(asset_ver()) ?>">
<style>
  .rp-block{background:#fff;border:1px solid #e2e6e2;border-radius:12px;margin-bottom:18px;overflow:hidden}
  .rp-block>summary{cursor:pointer;padding:14px 18px;font-weight:700;display:flex;flex-wrap:wrap;gap:10px;align-items:center;background:#f7faf7}
  .rp-block>summary small{color:#8a948a;font-weight:400}
  .rp-body{padding:16px 18px}
  .rp-kpis{display:grid;grid-template-columns:repeat(auto-fit,minmax(140px,1fr));gap:10px;margin-bottom:10px}
  .rp-kpi{background:#f7faf7;border:1px solid #e2e6e2;border-radius:10px;padding:10px 12px;display:flex;flex-direction:column;gap:2px}
  .rp-kpi span{font-size:.72rem;color:#5f6d5f}
  .rp-kpi strong{font-size:1.3rem;color:#2e5030}
  .rp-kpi--cv{background:#fdf6e8;border-color:#ecd9ae}
  .rp-delta{font-size:.75rem;color:#8a948a}
  .rp-delta.up{color:#1e7c33;font-weight:700}
  .rp-delta.down{color:#c0392b;font-weight:700}
  .rp-cvrow{font-size:.85rem;background:#fdf6e8;border:1px solid #ecd9ae;border-radius:8px;padding:8px 12px;margin-bottom:14px}
  .rp-sub{font-size:.82rem;font-weight:700;color:#5f6d5f;margin:14px 0 6px}
  .rp-chart{display:flex;align-items:flex-end;gap:3px;height:110px;background:#fbfdfb;border:1px solid #e2e6e2;border-radius:8px;padding:10px}
  .rp-bar{flex:1;display:flex;align-items:flex-end;height:100%}
  .rp-bar i{display:block;width:100%;background:#509F46;border-radius:3px 3px 0 0}
  .rp-cols{display:grid;grid-template-columns:1fr 1fr;gap:18px}
  @media(max-width:760px){.rp-cols{grid-template-columns:1fr}}
  .rp-table{width:100%;border-collapse:collapse;font-size:.85rem}
  .rp-table td{padding:5px 6px;border-bottom:1px solid #eef2ee}
  .rp-table__bar{width:40%}
  .rp-table__bar i{display:block;height:10px;background:#8fc487;border-radius:5px}
  .rp-table__num{text-align:right;white-space:nowrap;font-weight:700;color:#2e5030}
  .rp-badge{font-size:.7rem;font-weight:700;padding:2px 10px;border-radius:999px}
  .rp-badge--live{background:#e8f2fb;color:#15709e}
  .rp-badge--ok{background:#e8f6ea;color:#1e7c33}
  .rp-setup{background:#fdecea;border:1px solid #f0b9b3;border-radius:10px;padding:14px 18px;font-size:.85rem;line-height:1.9;margin-bottom:18px}
  .rp-hero{background:linear-gradient(135deg,#2e5030,#509F46);color:#fff;border-radius:12px;padding:18px 20px;margin-bottom:18px}
  .rp-hero h2{font-size:1rem;margin-bottom:8px;color:#fff}
  .rp-hero .rp-kpi{background:rgba(255,255,255,.12);border:none}
  .rp-hero .rp-kpi span{color:#d8e8d4}
  .rp-hero .rp-kpi strong{color:#fff}
  .rp-hero .rp-delta{color:#d8e8d4}
  .rp-hero .rp-delta.up{color:#ffe18a}
  .rp-hero .rp-delta.down{color:#ffb3a7}
</style>
</head><body>
<header class="admin-bar">
  <span class="admin-bar__title"><a href="/admin/">← ダッシュボード</a></span>
  <a href="/admin/logout.php" class="admin-bar__logout">ログアウト</a>
</header>
<main class="admin-main">
  <div class="admin-head">
    <h1>効果測定レポート（15日ごと）</h1>
  </div>
  <p style="font-size:.85rem;color:#5f6d5f;margin-bottom:16px">
    リニューアル公開日（<?= h(GA4_REPORT_ANCHOR) ?>）を起点に、GA4のアクセス解析を15日ごとに自動集計しています。
    完了した期間のレポートはこの画面を開いたときに自動で作成・保存され、蓄積されていきます。
  </p>

  <?php if ($list_err): ?>
    <p class="admin-error">レポートの読み込みエラー：<?= h($list_err) ?></p>
  <?php endif; ?>

  <?php if ($gen_err || $live_err): ?>
    <div class="rp-setup">
      <strong>GA4に接続できませんでした：</strong><?= h($gen_err ?: $live_err) ?><br>
      初回は次の設定が必要です：GA4管理画面 →「管理」→「プロパティのアクセス管理」→「＋」→ 下記のサービスアカウントを<strong>「閲覧者」</strong>として追加してください。<br>
      サービスアカウント：<code style="background:#fff;padding:2px 8px;border-radius:6px"><?= h(ga4_sa_email()) ?></code><br>
      追加後、このページを再読み込みすると自動でレポートが作成されます。
    </div>
  <?php endif; ?>

  <?php
    // ---- リニューアル効果サマリー（最初の期間 vs 最新の完了期間）----
    $ids = array_keys($reports); // 新しい順
    if (count($reports) >= 2 && $first):
      $latest = $reports[$ids[0]];
      $ld = $latest['data']; $fd = $first['data'];
  ?>
    <div class="rp-hero">
      <h2>リニューアル効果（最初の15日間 <?= h($first['start']) ?>〜 と 最新期間 <?= h($latest['start']) ?>〜 の比較）</h2>
      <div class="rp-kpis" style="margin-bottom:0">
        <div class="rp-kpi"><span>セッション</span><strong><?= number_format($ld['summary']['sessions']) ?></strong><?= delta_html($ld['summary']['sessions'], $fd['summary']['sessions']) ?></div>
        <div class="rp-kpi"><span>ユーザー</span><strong><?= number_format($ld['summary']['users']) ?></strong><?= delta_html($ld['summary']['users'], $fd['summary']['users']) ?></div>
        <div class="rp-kpi"><span>ページビュー</span><strong><?= number_format($ld['summary']['pv']) ?></strong><?= delta_html($ld['summary']['pv'], $fd['summary']['pv']) ?></div>
        <div class="rp-kpi"><span>CV（問い合わせ行動）</span><strong><?= number_format(array_sum($ld['cv'])) ?></strong><?= delta_html(array_sum($ld['cv']), array_sum($fd['cv'])) ?></div>
      </div>
    </div>
  <?php endif; ?>

  <?php if ($live && $live['data']): ?>
    <details class="rp-block" open>
      <summary>
        <span class="rp-badge rp-badge--live">速報</span>
        今期間 <?= h($live['start']) ?> 〜 <?= h($live['end']) ?>
        <small>（集計中・期間終了後に確定保存されます）</small>
      </summary>
      <div class="rp-body">
        <?php
          $prevOfLive = $ids ? $reports[$ids[0]]['data'] : null;
          render_report($live['data'], $prevOfLive, $live['start'], $live['end']);
        ?>
      </div>
    </details>
  <?php endif; ?>

  <?php if (!$reports && !$live): ?>
    <p style="color:#5f6d5f">まだ完了した期間がありません。最初のレポートは <?= h(date('Y-m-d', strtotime(GA4_REPORT_ANCHOR) + 15 * 86400)) ?> 以降に自動作成されます。</p>
  <?php endif; ?>

  <?php $i = 0; foreach ($reports as $id => $rp): $pos = array_search($id, $ascIds, true); $prevData = $pos > 0 ? $asc[$ascIds[$pos - 1]]['data'] : null; ?>
    <details class="rp-block" <?= $i === 0 ? 'open' : '' ?>>
      <summary>
        <span class="rp-badge rp-badge--ok">確定</span>
        第<?= $pos + 1 ?>期 <?= h($rp['start']) ?> 〜 <?= h($rp['end']) ?>
        <small>（作成 <?= h($rp['generated_at'] ?? '') ?>・前期比つき）</small>
      </summary>
      <div class="rp-body">
        <?php render_report($rp['data'], $prevData, $rp['start'], $rp['end']); ?>
      </div>
    </details>
  <?php $i++; endforeach; ?>

  <p style="font-size:.78rem;color:#8a948a;margin-top:8px">
    データソース：Google Analytics 4（プロパティID <?= h(GA4_PROPERTY_ID) ?>）／
    レポートはFirestore（ga_reports）に保存され、GA4の保持期間に関わらず残り続けます。
  </p>
</main>
</body></html>
