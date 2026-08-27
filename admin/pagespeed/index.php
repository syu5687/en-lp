<?php
/**
 * 表示速度診断（PageSpeed Insights）
 *  - 主要ページをモバイル/PCで計測し、スコアとCore Web Vitals・改善提案を表示
 *  - 計測はボタンを押したときだけ（結果は保存され、次回以降も表示される）
 */
require __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../includes/psi.php';

$saved = [];
$fs_error = '';
try { $saved = psi_all(); } catch (Throwable $e) { $fs_error = $e->getMessage(); }

function psi_cell(array $saved, string $path, string $strategy): array {
  return $saved[psi_doc_id($path, $strategy)] ?? [];
}
function psi_score_class(int $s): string {
  return $s >= 90 ? 'good' : ($s >= 50 ? 'mid' : 'bad');
}
?>
<!DOCTYPE html>
<html lang="ja"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>表示速度診断（PageSpeed Insights）｜管理画面</title>
<meta name="robots" content="noindex,nofollow">
<link rel="stylesheet" href="/admin/assets/admin.css?v=<?= h(asset_ver()) ?>">
<style>
  .ps-note{font-size:.85rem;color:#667;line-height:1.9;margin-bottom:18px}
  .ps-grid{display:grid;gap:16px}
  .ps-card{background:#fff;border-radius:12px;padding:18px 20px;box-shadow:0 2px 8px rgba(0,0,0,.05)}
  .ps-card__head{display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:8px;margin-bottom:12px}
  .ps-card__title{font-size:1rem;color:#0a3852;font-weight:700}
  .ps-card__path{font-size:.76rem;color:#89a}
  .ps-cols{display:grid;grid-template-columns:1fr 1fr;gap:14px}
  @media(max-width:700px){.ps-cols{grid-template-columns:1fr}}
  .ps-col{border:1px solid #e3eaee;border-radius:10px;padding:14px}
  .ps-col__label{font-size:.78rem;font-weight:700;color:#456;margin-bottom:10px}
  .ps-result{display:flex;align-items:center;gap:14px;flex-wrap:wrap}
  .ps-score{width:64px;height:64px;border-radius:50%;display:grid;place-items:center;font-size:1.3rem;font-weight:700;border:4px solid}
  .ps-score--good{color:#1d7a3e;border-color:#1d7a3e;background:#eef8f0}
  .ps-score--mid{color:#b05e00;border-color:#e08a2e;background:#fdf4e7}
  .ps-score--bad{color:#c0392b;border-color:#c0392b;background:#fdecea}
  .ps-metrics{display:flex;flex-wrap:wrap;gap:6px;font-size:.74rem}
  .ps-metrics span{background:#f2f6f8;border-radius:6px;padding:3px 8px;color:#345}
  .ps-metrics b{color:#0a3852}
  .ps-meta{font-size:.7rem;color:#9ab;margin-top:8px}
  .ps-empty{font-size:.82rem;color:#99a}
  .ps-btn{margin-top:10px;padding:8px 16px;border:1px solid #15709e;background:#fff;color:#15709e;border-radius:8px;font-size:.8rem;font-weight:700;cursor:pointer;font-family:inherit}
  .ps-btn:hover{background:#15709e;color:#fff}
  .ps-btn:disabled{opacity:.5;cursor:default}
  .ps-opps{margin-top:10px;font-size:.76rem}
  .ps-opps summary{cursor:pointer;color:#15709e}
  .ps-opps li{margin:4px 0 0 18px;color:#345;list-style:disc}
  .ps-err{color:#c0392b;font-size:.76rem;font-weight:700;margin-top:8px;min-height:1em}
  .ps-field{font-size:.7rem;font-weight:700;border-radius:999px;padding:2px 10px;display:inline-block}
  .ps-field--FAST{background:#e8f5e9;color:#2e7d32}
  .ps-field--AVERAGE{background:#fff3e0;color:#c26400}
  .ps-field--SLOW{background:#fdecea;color:#c0392b}
</style>
</head><body>
<header class="admin-bar">
  <span class="admin-bar__title"><a href="/admin/">← ダッシュボード</a>　表示速度診断（PageSpeed Insights）</span>
  <a href="/admin/logout.php" class="admin-bar__logout">ログアウト</a>
</header>
<main class="admin-main">
  <h1>表示速度診断</h1>
  <p class="ps-note">
    Googleの PageSpeed Insights で主要ページの表示速度を計測します。スコアは <b style="color:#1d7a3e">90以上=良好</b>／<b style="color:#b05e00">50〜89=改善の余地</b>／<b style="color:#c0392b">50未満=要改善</b>。
    計測には30〜60秒ほどかかります。結果は保存され、次に開いたときもそのまま表示されます。<br>
    ※ 計測はこの画面から実行したときだけ行われるため、費用はかかりません（Googleの無償枠）。
  </p>
  <?php if ($fs_error): ?><p style="background:#fdecea;color:#c0392b;padding:10px 16px;border-radius:8px;margin-bottom:14px">保存済みデータの取得エラー: <?= h($fs_error) ?>（計測は実行できます）</p><?php endif; ?>

  <div class="ps-grid">
    <?php foreach (PSI_PAGES as $path => $label): ?>
      <div class="ps-card">
        <div class="ps-card__head">
          <span class="ps-card__title"><?= h($label) ?></span>
          <span class="ps-card__path"><?= h(rtrim(SITE['url'], '/') . $path) ?></span>
        </div>
        <div class="ps-cols">
          <?php foreach (['mobile' => '📱 モバイル', 'desktop' => '🖥 PC'] as $strategy => $sLabel):
            $r = psi_cell($saved, $path, $strategy); ?>
            <div class="ps-col" data-path="<?= h($path) ?>" data-strategy="<?= h($strategy) ?>">
              <p class="ps-col__label"><?= h($sLabel) ?></p>
              <div class="ps-body">
                <?php if ($r): $sc = (int)($r['score'] ?? 0); ?>
                  <div class="ps-result">
                    <div class="ps-score ps-score--<?= psi_score_class($sc) ?>"><?= $sc ?></div>
                    <div>
                      <div class="ps-metrics">
                        <span>LCP <b><?= h((string)($r['lcp'] ?? '—')) ?></b></span>
                        <span>CLS <b><?= h((string)($r['cls'] ?? '—')) ?></b></span>
                        <span>FCP <b><?= h((string)($r['fcp'] ?? '—')) ?></b></span>
                        <span>TBT <b><?= h((string)($r['tbt'] ?? '—')) ?></b></span>
                      </div>
                      <?php if (!empty($r['field_overall'])): ?>
                        <p style="margin-top:6px"><span class="ps-field ps-field--<?= h((string)$r['field_overall']) ?>">実ユーザー体感: <?= ['FAST' => '速い', 'AVERAGE' => 'ふつう', 'SLOW' => '遅い'][(string)$r['field_overall']] ?? h((string)$r['field_overall']) ?></span></p>
                      <?php endif; ?>
                      <p class="ps-meta"><?= h((string)($r['measured_at'] ?? '')) ?> 計測</p>
                    </div>
                  </div>
                  <?php if (!empty($r['opps'])): ?>
                    <details class="ps-opps"><summary>改善できる項目（<?= count((array)$r['opps']) ?>件）</summary>
                      <ul><?php foreach ((array)$r['opps'] as $o): ?><li><?= h((string)$o) ?></li><?php endforeach; ?></ul>
                    </details>
                  <?php endif; ?>
                <?php else: ?>
                  <p class="ps-empty">まだ計測していません。</p>
                <?php endif; ?>
              </div>
              <button type="button" class="ps-btn">計測する</button>
              <p class="ps-err"></p>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
  <p style="font-size:.76rem;color:#99a;margin-top:16px">※ スコアは計測ごとに±数点ぶれます。傾向（90台を維持できているか・大きく落ちていないか）を見るのがおすすめです。</p>
</main>
<script>
(function () {
  var CSRF = <?= json_encode(csrf_token()) ?>;
  var CLASSMAP = function (s) { return s >= 90 ? 'good' : (s >= 50 ? 'mid' : 'bad'); };
  var FIELD_JA = { FAST: '速い', AVERAGE: 'ふつう', SLOW: '遅い' };
  document.querySelectorAll('.ps-col').forEach(function (col) {
    var btn = col.querySelector('.ps-btn');
    var err = col.querySelector('.ps-err');
    btn.addEventListener('click', async function () {
      btn.disabled = true;
      btn.textContent = '計測中…（30〜60秒お待ちください）';
      err.textContent = '';
      try {
        var res = await fetch('/admin/pagespeed/api.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'X-CSRF-Token': CSRF },
          body: JSON.stringify({ path: col.dataset.path, strategy: col.dataset.strategy })
        });
        var text = await res.text();
        var j;
        try { j = JSON.parse(text); } catch (e) { throw new Error('サーバー応答が不正です（HTTP ' + res.status + '）'); }
        if (!res.ok || !j.ok) throw new Error(j.error || '計測に失敗しました');
        var r = j.result;
        var oppsHtml = '';
        if (r.opps && r.opps.length) {
          oppsHtml = '<details class="ps-opps"><summary>改善できる項目（' + r.opps.length + '件）</summary><ul>'
            + r.opps.map(function (o) { var li = document.createElement('li'); li.textContent = o; return li.outerHTML; }).join('')
            + '</ul></details>';
        }
        var fieldHtml = '';
        if (r.field_overall) {
          fieldHtml = '<p style="margin-top:6px"><span class="ps-field ps-field--' + r.field_overall + '">実ユーザー体感: ' + (FIELD_JA[r.field_overall] || r.field_overall) + '</span></p>';
        }
        col.querySelector('.ps-body').innerHTML =
          '<div class="ps-result"><div class="ps-score ps-score--' + CLASSMAP(r.score) + '">' + r.score + '</div>'
          + '<div><div class="ps-metrics">'
          + '<span>LCP <b>' + r.lcp + '</b></span><span>CLS <b>' + r.cls + '</b></span>'
          + '<span>FCP <b>' + r.fcp + '</b></span><span>TBT <b>' + r.tbt + '</b></span>'
          + '</div>' + fieldHtml + '<p class="ps-meta">' + r.measured_at + ' 計測</p></div></div>' + oppsHtml;
      } catch (e) {
        err.textContent = e.message;
      } finally {
        btn.disabled = false;
        btn.textContent = '再計測する';
      }
    });
  });
})();
</script>
</body></html>
