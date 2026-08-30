<?php
/**
 * 合同海洋散骨 実施予定日ブロック（TOP・福岡LPで共用）
 * 管理画面（/admin/goudou/）で登録した「公開中・本日以降」の日付を表示する。
 * 事前に admin/includes/store.php が読み込まれている前提（未読込なら読み込む）。
 *
 * $gd_filter（任意）: 読み込み前にセットすると「海域・出航地」にその文字列を含む
 *                     開催日だけを表示する（例: 福岡LPでは $gd_filter = '福岡';）。
 * $gd_area_label（任意）: 見出し・空表示に使う地域名（例: '福岡'）。
 */
if (!function_exists('goudou_upcoming')) {
  require_once __DIR__ . '/../admin/includes/store.php';
}
$gd_filter = $gd_filter ?? '';
$gd_area_label = $gd_area_label ?? '';
$gd_items = [];
try { $gd_items = goudou_upcoming(); } catch (Throwable $e) { $gd_items = []; }
if ($gd_filter !== '') {
  $gd_items = array_values(array_filter(
    $gd_items,
    static fn($g) => mb_strpos((string)($g['sea'] ?? ''), $gd_filter) !== false
  ));
}

/* 出港場所（合同海洋葬の集合場所）。$gd_filter がある場合は該当地域のみ表示 */
$gd_ports = [
  ['area' => '鹿児島', 'name' => 'いおワールド鹿児島水族館 しおかぜ通り横', 'addr' => '鹿児島市本港新町35', 'map' => 'https://maps.app.goo.gl/hauXyNG3seQ4QWCu9'],
  ['area' => '福岡',   'name' => '姪浜旅客待合所',                       'addr' => '福岡市西区愛宕浜3丁目1-1', 'map' => 'https://maps.app.goo.gl/ssPvPegY1qikqrEz9'],
];
if ($gd_filter !== '') {
  $gd_ports_f = array_values(array_filter($gd_ports, static fn($p) => mb_strpos($p['area'], $gd_filter) !== false));
  if ($gd_ports_f) $gd_ports = $gd_ports_f;
}

$gd_fmt = static function (string $ymd): array {
  $ts = strtotime($ymd);
  if (!$ts) return [$ymd, ''];
  $w = ['日', '月', '火', '水', '木', '金', '土'][(int)date('w', $ts)];
  return [date('Y年n月j日', $ts), $w];
};
?>
<section class="goudou-sched" id="goudou-schedule">
  <div class="goudou-sched__inner">
    <img src="/assets/img/goudou-photo.jpg?v=<?= h(asset_ver()) ?>" alt="合同海洋散骨の乗船風景。スタッフがご遺族を船へご案内する様子" width="1400" height="933" loading="lazy" class="goudou-sched__photo">
    <p class="goudou-sched__eyebrow">SCHEDULE</p>
    <h2 class="goudou-sched__title">合同海洋散骨 実施予定日<?= $gd_area_label !== '' ? '（' . h($gd_area_label) . '開催）' : '' ?></h2>
    <p class="goudou-sched__lead">複数のご家族で乗り合わせて行う「合同海洋葬」の出航予定日です。<br class="pc-only">委託海洋葬（立ち会い不要）もこの日程で心を込めてお送りします。</p>
    <?php if ($gd_items): ?>
      <div class="goudou-sched__grid">
        <?php foreach ($gd_items as $g): [$d, $w] = $gd_fmt((string)($g['date'] ?? '')); ?>
          <div class="goudou-card<?= ($g['status'] ?? '') === '受付終了' ? ' goudou-card--closed' : '' ?>">
            <p class="goudou-card__date"><?= h($d) ?><span class="goudou-card__wday">（<?= h($w) ?>）</span></p>
            <?php if (!empty($g['sea'])): ?><p class="goudou-card__sea"><?= h($g['sea']) ?></p><?php endif; ?>
            <p class="goudou-card__status goudou-card__status--<?= ($g['status'] ?? '') === '受付終了' ? 'closed' : ((($g['status'] ?? '') === '残りわずか') ? 'few' : 'open') ?>"><?= h($g['status'] ?? '受付中') ?></p>
            <?php if (!empty($g['note'])): ?><p class="goudou-card__note"><?= h($g['note']) ?></p><?php endif; ?>
            <?php if (($g['status'] ?? '受付中') !== '受付終了'): ?>
              <a class="goudou-card__apply" href="/contact/?service=<?= rawurlencode('合同海洋葬') ?>&amp;date=<?= rawurlencode((string)($g['date'] ?? '')) ?>">この日に申し込む</a>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      </div>
      <p class="goudou-sched__caution">※ 天候・海況により日程が変更となる場合があります。</p>
    <?php else: ?>
      <p class="goudou-sched__empty"><?= $gd_area_label !== '' ? h($gd_area_label) . 'での' : '' ?>次回の開催日程は調整中です。ご希望の時期がありましたら、お気軽にお問い合わせください。</p>
    <?php endif; ?>
    <div class="goudou-ports">
      <p class="goudou-ports__title">出港場所（集合場所）</p>
      <div class="goudou-ports__grid">
        <?php foreach ($gd_ports as $pt): ?>
          <div class="goudou-port">
            <p class="goudou-port__area"><?= h($pt['area']) ?></p>
            <p class="goudou-port__name"><?= h($pt['name']) ?></p>
            <p class="goudou-port__addr"><?= h($pt['addr']) ?></p>
            <a class="goudou-port__map" href="<?= h($pt['map']) ?>" target="_blank" rel="noopener">Googleマップで見る →</a>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
    <p class="goudou-sched__cta">
      <a href="/contact/?service=<?= rawurlencode('合同海洋葬') ?>" class="goudou-sched__btn">合同海洋散骨に申し込む・相談する</a>
      <a href="/kaiyou-sou/" class="goudou-sched__link">海洋葬のプラン・料金を見る →</a>
    </p>
  </div>
</section>
<style>
.goudou-sched{background:linear-gradient(180deg,#0a3852,#0f4d70);padding:56px 20px;color:#fff}
.goudou-sched__inner{max-width:960px;margin:0 auto;text-align:center}
.goudou-sched__photo{width:170px;height:170px;border-radius:50%;object-fit:cover;object-position:62% 55%;border:6px solid rgba(255,255,255,.92);box-shadow:0 10px 30px rgba(0,0,0,.28);margin:0 auto 18px;display:block}
@media(max-width:600px){.goudou-sched__photo{width:120px;height:120px;border-width:4px}}
.goudou-sched__eyebrow{font-size:.78rem;letter-spacing:.28em;color:#d8b46a;font-weight:700;margin-bottom:10px}
.goudou-sched__title{color:#fff;font-size:1.6rem;margin-bottom:14px}
.goudou-sched__lead{color:rgba(255,255,255,.85);font-size:.95rem;line-height:1.9;margin-bottom:26px}
.goudou-sched__grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(230px,1fr));gap:16px;max-width:820px;margin:0 auto}
.goudou-card{background:#fff;color:#123;border-radius:14px;padding:20px 16px;box-shadow:0 8px 24px rgba(0,0,0,.18)}
.goudou-card--closed{opacity:.65}
.goudou-card__date{font-size:1.3rem;font-weight:700;color:#0a3852;line-height:1.4}
.goudou-card__wday{font-size:.9rem;font-weight:600;color:#567}
.goudou-card__sea{font-size:.85rem;color:#456;margin-top:4px}
.goudou-card__status{display:inline-block;margin-top:10px;padding:3px 14px;border-radius:999px;font-size:.8rem;font-weight:700}
.goudou-card__status--open{background:#e8f5e9;color:#2e7d32}
.goudou-card__status--few{background:#fff3e0;color:#c26400}
.goudou-card__status--closed{background:#eee;color:#888}
.goudou-card__note{font-size:.78rem;color:#789;margin-top:8px}
.goudou-card__apply{display:block;margin-top:12px;background:var(--green,#1c6b52);color:#fff;font-size:.85rem;font-weight:700;padding:9px 10px;border-radius:999px;text-decoration:none;transition:.2s}
.goudou-card__apply:hover{filter:brightness(1.12);color:#fff}
.goudou-sched__caution{font-size:.8rem;color:rgba(255,255,255,.7);margin-top:16px}
.goudou-sched__empty{background:rgba(255,255,255,.1);border:1px dashed rgba(255,255,255,.4);border-radius:12px;padding:22px;font-size:.95rem;max-width:640px;margin:0 auto}
.goudou-ports{margin-top:30px}
.goudou-ports__title{font-size:.95rem;font-weight:700;color:#d8b46a;letter-spacing:.12em;margin-bottom:14px}
.goudou-ports__grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:14px;max-width:720px;margin:0 auto}
.goudou-port{background:rgba(255,255,255,.10);border:1px solid rgba(255,255,255,.28);border-radius:12px;padding:16px 18px;text-align:center}
.goudou-port__area{display:inline-block;background:#d8b46a;color:#1c2b33;font-size:.76rem;font-weight:700;border-radius:999px;padding:2px 14px;margin-bottom:8px}
.goudou-port__name{font-size:.95rem;font-weight:700;color:#fff;line-height:1.6}
.goudou-port__addr{font-size:.82rem;color:rgba(255,255,255,.8);margin-top:4px}
.goudou-port__map{display:inline-block;margin-top:10px;font-size:.84rem;font-weight:700;color:#fff;text-decoration:underline}
.goudou-port__map:hover{color:#d8b46a}
.goudou-sched__cta{margin-top:26px;display:flex;flex-direction:column;align-items:center;gap:12px}
.goudou-sched__btn{display:inline-block;background:#d8b46a;color:#1c2b33;font-weight:700;padding:14px 34px;border-radius:999px;text-decoration:none;font-size:1rem;box-shadow:0 6px 18px rgba(0,0,0,.25);transition:.2s}
.goudou-sched__btn:hover{filter:brightness(1.07);color:#1c2b33}
.goudou-sched__link{color:rgba(255,255,255,.85);font-size:.88rem;text-decoration:underline}
.goudou-sched__link:hover{color:#fff}
@media(max-width:600px){.goudou-sched{padding:44px 16px}.goudou-sched__title{font-size:1.35rem}}
</style>
<script>
/* 予定日セクションの閲覧を計測（フローティング導線から来た場合は出所も送る） */
(function () {
  var sec = document.getElementById('goudou-schedule');
  if (!sec || !('IntersectionObserver' in window) || sec.dataset.gaBound) return;
  sec.dataset.gaBound = '1';
  var src = ''; try { src = sessionStorage.getItem('en_fcta_src') || ''; } catch (e) {}
  var io = new IntersectionObserver(function (es) {
    es.forEach(function (e) {
      if (!e.isIntersecting) return;
      io.disconnect();
      try {
        if (window.gtag) {
          var p = { page_path: location.pathname };
          if (src) p.banner_source = src;
          window.gtag('event', 'schedule_section_view', p);
        }
      } catch (err) {}
    });
  }, { threshold: 0.25 });
  io.observe(sec);
})();
</script>
