<?php
/**
 * GA4 詳細解析（プロ向け）
 * - GA4 Data API から詳細データを取得して多面的に表示（15分キャッシュ）
 * - 期間: 昨日までの 7 / 14 / 28 / 90 日間（前期間との比較付き）
 */
require __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/ga4-report.php';

$days = (int)($_GET['days'] ?? 28);
if (!in_array($days, [7, 14, 28, 90], true)) $days = 28;

$D = null; $err = '';
try { $D = ga4_pro($days); } catch (Throwable $e) { $err = $e->getMessage(); }

// 検索キーワード（Search Console）— GA4本体とは独立して取得
$KW = null; $kw_err = '';
try { $KW = gsc_keywords($days); } catch (Throwable $e) { $kw_err = $e->getMessage(); }

function pct($v, int $dec = 1): string { return number_format((float)$v * 100, $dec) . '%'; }
function dur($sec): string { $sec = (int)round((float)$sec); return floor($sec / 60) . '分' . ($sec % 60) . '秒'; }
function delta($cur, $prev): string {
  if ((float)$prev == 0.0) return '<span class="gd">—</span>';
  $d = ((float)$cur - (float)$prev) / (float)$prev * 100;
  $cls = $d > 0 ? 'up' : ($d < 0 ? 'down' : '');
  return '<span class="gd ' . $cls . '">' . ($d > 0 ? '+' : '') . number_format($d, 1) . '%</span>';
}
function barTable(array $rows, array $headers, int $barCol = 1, array $fmt = []): void {
  $max = 1; foreach ($rows as $r) $max = max($max, (float)$r[$barCol]);
  echo '<table class="gt"><tr>';
  foreach ($headers as $h) echo '<th>' . h($h) . '</th>';
  echo '</tr>';
  foreach ($rows as $r) {
    echo '<tr>';
    foreach ($r as $i => $c) {
      if ($i === $barCol) {
        echo '<td class="gt-num">' . number_format((float)$c) . '<i class="gt-bar" style="width:' . round((float)$c / $max * 100) . '%"></i></td>';
      } elseif (isset($fmt[$i])) {
        echo '<td class="gt-num">' . $fmt[$i]($c) . '</td>';
      } else {
        echo '<td>' . h((string)$c) . '</td>';
      }
    }
    echo '</tr>';
  }
  echo '</table>';
}
?>
<!DOCTYPE html>
<html lang="ja"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>GA4詳細解析｜管理画面</title>
<meta name="robots" content="noindex,nofollow">
<link rel="stylesheet" href="/admin/assets/admin.css?v=<?= h(asset_ver()) ?>">
<style>
  .gwrap{display:grid;grid-template-columns:1fr 1fr;gap:18px}
  @media(max-width:900px){.gwrap{grid-template-columns:1fr}}
  .gcard{background:#fff;border:1px solid #e2e6e2;border-radius:12px;padding:16px 18px;overflow-x:auto}
  .gcard h2{font-size:.95rem;color:#2e5030;margin:0 0 4px}
  .gcard .gnote{font-size:.72rem;color:#8a948a;margin:0 0 10px}
  .gkpis{display:grid;grid-template-columns:repeat(auto-fit,minmax(140px,1fr));gap:10px;margin-bottom:18px}
  .gkpi{background:#fff;border:1px solid #e2e6e2;border-radius:10px;padding:10px 12px}
  .gkpi span{display:block;font-size:.7rem;color:#5f6d5f}
  .gkpi strong{display:block;font-size:1.25rem;color:#2e5030;margin:2px 0}
  .gkpi--cv{background:#fdf6e8;border-color:#ecd9ae}
  .gd{font-size:.74rem;color:#8a948a}
  .gd.up{color:#1e7c33;font-weight:700}
  .gd.down{color:#c0392b;font-weight:700}
  .gt{width:100%;border-collapse:collapse;font-size:.82rem}
  .gt th{text-align:left;padding:6px;border-bottom:2px solid #e2e6e2;color:#5f6d5f;font-size:.72rem;white-space:nowrap}
  .gt td{padding:6px;border-bottom:1px solid #eef2ee;vertical-align:middle;word-break:break-all}
  .gt-num{white-space:nowrap;font-weight:700;color:#2e5030;position:relative;min-width:90px}
  .gt-bar{display:block;height:5px;background:#8fc487;border-radius:3px;margin-top:3px}
  .gchart{display:flex;align-items:flex-end;gap:2px;height:120px;background:#fbfdfb;border:1px solid #e2e6e2;border-radius:8px;padding:10px}
  .gchart i{flex:1;background:#509F46;border-radius:2px 2px 0 0;min-width:3px}
  .gtabs{display:flex;gap:8px;align-items:center}
  .gtabs a{padding:7px 16px;border-radius:999px;font-size:.85rem;font-weight:700;text-decoration:none;color:#2e5030;background:#eef2ee}
  .gtabs a.on{background:#2e5030;color:#fff}
</style>
</head><body>
<header class="admin-bar">
  <span class="admin-bar__title"><a href="/admin/">← ダッシュボード</a></span>
  <a href="/admin/logout.php" class="admin-bar__logout">ログアウト</a>
</header>
<main class="admin-main">
  <div class="admin-head">
    <h1>GA4 詳細解析</h1>
    <div class="gtabs">
      <?php foreach ([7, 14, 28, 90] as $dd): ?>
        <a href="?days=<?= $dd ?>" class="<?= $dd === $days ? 'on' : '' ?>"><?= $dd ?>日</a>
      <?php endforeach; ?>
    </div>
  </div>

  <?php if ($err): ?>
    <p class="admin-error">GA4に接続できませんでした：<?= h($err) ?><br>
    「効果測定レポート」画面の案内（サービスアカウント権限・Data API有効化）をご確認ください。</p>
  <?php elseif ($D): ?>
    <?php
      $k = $D['kpi']['cur']; $p = $D['kpi']['prev'];
      $cvCur = array_sum($D['cv']['cur']); $cvPrev = array_sum($D['cv']['prev']);
      $cvrCur = $k[0] > 0 ? $cvCur / $k[0] : 0; $cvrPrev = $p[0] > 0 ? $cvPrev / $p[0] : 0;
    ?>
    <p style="font-size:.82rem;color:#5f6d5f;margin-bottom:14px">
      対象期間：<?= h($D['range']['start']) ?> 〜 <?= h($D['range']['end']) ?>（<?= $days ?>日間）
      ／ 比較：前<?= $days ?>日間（<?= h($D['range']['pstart']) ?>〜<?= h($D['range']['pend']) ?>）
      ／ データは15分キャッシュ
    </p>

    <!-- KPI -->
    <div class="gkpis">
      <div class="gkpi"><span>セッション</span><strong><?= number_format($k[0]) ?></strong><?= delta($k[0], $p[0]) ?></div>
      <div class="gkpi"><span>ユーザー</span><strong><?= number_format($k[1]) ?></strong><?= delta($k[1], $p[1]) ?></div>
      <div class="gkpi"><span>新規ユーザー</span><strong><?= number_format($k[2]) ?></strong><?= delta($k[2], $p[2]) ?></div>
      <div class="gkpi"><span>ページビュー</span><strong><?= number_format($k[3]) ?></strong><?= delta($k[3], $p[3]) ?></div>
      <div class="gkpi"><span>エンゲージメント率</span><strong><?= pct($k[4]) ?></strong><?= delta($k[4], $p[4]) ?></div>
      <div class="gkpi"><span>直帰率</span><strong><?= pct($k[5]) ?></strong><?= delta($k[5], $p[5]) ?></div>
      <div class="gkpi"><span>平均セッション時間</span><strong style="font-size:1rem"><?= dur($k[6]) ?></strong><?= delta($k[6], $p[6]) ?></div>
      <div class="gkpi"><span>セッション/ユーザー</span><strong><?= number_format($k[7], 2) ?></strong><?= delta($k[7], $p[7]) ?></div>
      <div class="gkpi gkpi--cv"><span>CV（問い合わせ行動）</span><strong><?= number_format($cvCur) ?></strong><?= delta($cvCur, $cvPrev) ?></div>
      <div class="gkpi gkpi--cv"><span>CVR（CV÷セッション）</span><strong><?= pct($cvrCur, 2) ?></strong><?= delta($cvrCur, $cvrPrev) ?></div>
    </div>
    <p style="font-size:.78rem;color:#8a948a;margin:-8px 0 16px">
      CV内訳：フォーム送信 <?= number_format($D['cv']['cur']['generate_lead']) ?>
      ／ 電話タップ <?= number_format($D['cv']['cur']['tel_click']) ?>
      ／ LINEタップ <?= number_format($D['cv']['cur']['line_click']) ?>
    </p>

    <!-- 日別 -->
    <div class="gcard" style="margin-bottom:18px">
      <h2>日別セッション</h2>
      <?php $dmax = 1; foreach ($D['daily'] as $r) $dmax = max($dmax, (float)$r[1]); ?>
      <div class="gchart">
        <?php foreach ($D['daily'] as $r): ?>
          <i title="<?= h($r[0]) ?>: セッション<?= (int)$r[1] ?> / ユーザー<?= (int)$r[2] ?> / PV<?= (int)$r[3] ?>" style="height:<?= max(2, round((float)$r[1] / $dmax * 100)) ?>%"></i>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="gwrap">
      <div class="gcard">
        <h2>流入チャネル</h2><p class="gnote">どの経路から来たか（Organic=自然検索 / Paid=広告 / Direct=直接 / Referral=他サイト）</p>
        <?php barTable($D['channels'], ['チャネル', 'セッション', 'ユーザー', 'エンゲージ率', '直帰率'], 1, [3 => fn($v) => pct($v), 4 => fn($v) => pct($v)]); ?>
      </div>
      <div class="gcard">
        <h2>参照元 / メディア</h2><p class="gnote">google/organic=Google自然検索、google/cpc=Google広告</p>
        <?php barTable(array_map(fn($r) => [$r[0] . ' / ' . $r[1], $r[2], $r[3]], $D['sources']), ['参照元/メディア', 'セッション', 'エンゲージ率'], 1, [2 => fn($v) => pct($v)]); ?>
      </div>
      <div class="gcard">
        <h2>ランディングページ</h2><p class="gnote">最初に着地したページ。直帰率が高いLPは改善候補</p>
        <?php barTable($D['landing'], ['LP', 'セッション', 'エンゲージ率', '直帰率'], 1, [2 => fn($v) => pct($v), 3 => fn($v) => pct($v)]); ?>
      </div>
      <div class="gcard">
        <h2>人気ページ TOP20</h2>
        <?php barTable($D['pages'], ['ページ', 'PV', 'ユーザー'], 1); ?>
      </div>
      <div class="gcard">
        <h2>CV発生ページ</h2><p class="gnote">どのページで問い合わせ行動が起きたか</p>
        <?php barTable(array_map(fn($r) => [['generate_lead' => 'フォーム', 'tel_click' => '電話', 'line_click' => 'LINE'][$r[0]] ?? $r[0], $r[1], $r[2]], $D['cvpages']), ['種類', 'ページ', '回数'], 2); ?>
      </div>
      <div class="gcard">
        <h2>新規 vs リピーター</h2>
        <?php barTable(array_map(fn($r) => [['new' => '新規', 'returning' => 'リピーター'][$r[0]] ?? ($r[0] ?: '(不明)'), $r[1], $r[2], $r[3]], $D['newret']), ['区分', 'セッション', 'エンゲージ率', '平均滞在'], 1, [2 => fn($v) => pct($v), 3 => fn($v) => dur($v)]); ?>
        <h2 style="margin-top:16px">デバイス</h2>
        <?php barTable(array_map(fn($r) => [['mobile' => 'スマホ', 'desktop' => 'PC', 'tablet' => 'タブレット'][$r[0]] ?? $r[0], $r[1], $r[2]], $D['devices']), ['デバイス', 'セッション', 'エンゲージ率'], 1, [2 => fn($v) => pct($v)]); ?>
        <h2 style="margin-top:16px">ブラウザ</h2>
        <?php barTable($D['browsers'], ['ブラウザ', 'セッション'], 1); ?>
      </div>
      <div class="gcard">
        <h2>地域（都道府県）</h2>
        <?php barTable($D['regions'], ['都道府県', 'セッション', 'ユーザー'], 1); ?>
      </div>
      <div class="gcard">
        <h2>市区町村</h2>
        <?php barTable($D['cities'], ['市区町村', 'セッション'], 1); ?>
      </div>
      <div class="gcard">
        <h2>時間帯別セッション</h2><p class="gnote">問い合わせ対応や広告スケジュール調整の参考に</p>
        <?php $hmax = 1; foreach ($D['hours'] as $r) $hmax = max($hmax, (float)$r[1]); ?>
        <div class="gchart" style="height:90px">
          <?php for ($hh = 0; $hh < 24; $hh++): $v = 0; foreach ($D['hours'] as $r) if ((int)$r[0] === $hh) $v = (float)$r[1]; ?>
            <i title="<?= $hh ?>時: <?= (int)$v ?>" style="height:<?= max(2, round($v / $hmax * 100)) ?>%"></i>
          <?php endfor; ?>
        </div>
        <p class="gnote" style="margin-top:4px">0時 → 23時</p>
        <h2 style="margin-top:14px">曜日別セッション</h2>
        <?php
          $wmap = ['Monday' => '月', 'Tuesday' => '火', 'Wednesday' => '水', 'Thursday' => '木', 'Friday' => '金', 'Saturday' => '土', 'Sunday' => '日'];
          $worder = array_keys($wmap); $wrows = [];
          foreach ($worder as $wn) { foreach ($D['weekdays'] as $r) if ($r[0] === $wn) $wrows[] = [$wmap[$wn], $r[1]]; }
          barTable($wrows, ['曜日', 'セッション'], 1);
        ?>
      </div>
    </div>

    <!-- 検索キーワード（Search Console） -->
    <div class="gcard" style="margin-top:18px">
      <h2>検索キーワード（Google自然検索・Search Console）</h2>
      <?php if ($kw_err): ?>
        <div class="rp-setup" style="background:#fdecea;border:1px solid #f0b9b3;border-radius:10px;padding:12px 16px;font-size:.82rem;line-height:1.9;margin-top:8px">
          <strong>Search Consoleに接続できませんでした：</strong><?= h($kw_err) ?><br>
          初回は次の2つの設定が必要です：<br>
          ① <a href="https://console.cloud.google.com/apis/library/searchconsole.googleapis.com?project=412102088439" target="_blank" rel="noopener">Search Console API を有効化</a>（1クリック）<br>
          ② <a href="https://search.google.com/search-console/users?resource_id=sc-domain:en1150.co.jp" target="_blank" rel="noopener">GSCのユーザーと権限</a> → 「ユーザーを追加」→ <code><?= h(ga4_sa_email()) ?></code> を権限「制限付き」で追加<br>
          設定後、このページを再読み込みしてください。
        </div>
      <?php elseif ($KW): ?>
        <p class="gnote">対象期間：<?= h($KW['start']) ?> 〜 <?= h($KW['end']) ?>（GSCのデータは3日遅れで確定するため終端が異なります）</p>
        <div class="gwrap" style="margin-top:8px">
          <div>
            <h2 style="font-size:.85rem">検索クエリ TOP20</h2>
            <?php barTable($KW['queries'], ['キーワード', 'クリック', '表示回数', 'CTR', '平均掲載順位'], 1,
              [2 => fn($v) => number_format((float)$v), 3 => fn($v) => pct($v), 4 => fn($v) => number_format((float)$v, 1) . '位']); ?>
          </div>
          <div>
            <h2 style="font-size:.85rem">キーワード × 流入ページ TOP15</h2>
            <?php barTable($KW['qpages'], ['キーワード', 'ページ', 'クリック', '表示回数'], 2,
              [3 => fn($v) => number_format((float)$v)]); ?>
          </div>
        </div>
      <?php endif; ?>
    </div>

    <p style="font-size:.78rem;color:#8a948a;margin-top:16px">
      ※ 広告の検索語句は Google広告の「検索語句レポート」で確認できます。
    </p>
  <?php endif; ?>
</main>
</body></html>
