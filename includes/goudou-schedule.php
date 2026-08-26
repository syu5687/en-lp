<?php
/**
 * 合同海洋散骨 実施予定日ブロック（TOP・福岡LPで共用）
 * 管理画面（/admin/goudou/）で登録した「公開中・本日以降」の日付を表示する。
 * 事前に admin/includes/store.php が読み込まれている前提（未読込なら読み込む）。
 */
if (!function_exists('goudou_upcoming')) {
  require_once __DIR__ . '/../admin/includes/store.php';
}
$gd_items = [];
try { $gd_items = goudou_upcoming(); } catch (Throwable $e) { $gd_items = []; }

$gd_fmt = static function (string $ymd): array {
  $ts = strtotime($ymd);
  if (!$ts) return [$ymd, ''];
  $w = ['日', '月', '火', '水', '木', '金', '土'][(int)date('w', $ts)];
  return [date('Y年n月j日', $ts), $w];
};
?>
<section class="goudou-sched" id="goudou-schedule">
  <div class="goudou-sched__inner">
    <p class="goudou-sched__eyebrow">SCHEDULE</p>
    <h2 class="goudou-sched__title">合同海洋散骨 実施予定日</h2>
    <p class="goudou-sched__lead">複数のご家族で乗り合わせて行う「合同海洋葬」の出航予定日です。<br class="pc-only">委託海洋葬（立ち会い不要）もこの日程で心を込めてお送りします。</p>
    <?php if ($gd_items): ?>
      <div class="goudou-sched__grid">
        <?php foreach ($gd_items as $g): [$d, $w] = $gd_fmt((string)($g['date'] ?? '')); ?>
          <div class="goudou-card<?= ($g['status'] ?? '') === '受付終了' ? ' goudou-card--closed' : '' ?>">
            <p class="goudou-card__date"><?= h($d) ?><span class="goudou-card__wday">（<?= h($w) ?>）</span></p>
            <?php if (!empty($g['sea'])): ?><p class="goudou-card__sea"><?= h($g['sea']) ?></p><?php endif; ?>
            <p class="goudou-card__status goudou-card__status--<?= ($g['status'] ?? '') === '受付終了' ? 'closed' : ((($g['status'] ?? '') === '残りわずか') ? 'few' : 'open') ?>"><?= h($g['status'] ?? '受付中') ?></p>
            <?php if (!empty($g['note'])): ?><p class="goudou-card__note"><?= h($g['note']) ?></p><?php endif; ?>
          </div>
        <?php endforeach; ?>
      </div>
      <p class="goudou-sched__caution">※ 天候・海況により日程が変更となる場合があります。</p>
    <?php else: ?>
      <p class="goudou-sched__empty">次回の開催日程は調整中です。ご希望の時期がありましたら、お気軽にお問い合わせください。</p>
    <?php endif; ?>
    <p class="goudou-sched__cta">
      <a href="/contact/?service=<?= rawurlencode('合同海洋葬') ?>" class="goudou-sched__btn">合同海洋散骨に申し込む・相談する</a>
      <a href="/kaiyou-sou/" class="goudou-sched__link">海洋葬のプラン・料金を見る →</a>
    </p>
  </div>
</section>
<style>
.goudou-sched{background:linear-gradient(180deg,#0a3852,#0f4d70);padding:56px 20px;color:#fff}
.goudou-sched__inner{max-width:960px;margin:0 auto;text-align:center}
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
.goudou-sched__caution{font-size:.8rem;color:rgba(255,255,255,.7);margin-top:16px}
.goudou-sched__empty{background:rgba(255,255,255,.1);border:1px dashed rgba(255,255,255,.4);border-radius:12px;padding:22px;font-size:.95rem;max-width:640px;margin:0 auto}
.goudou-sched__cta{margin-top:26px;display:flex;flex-direction:column;align-items:center;gap:12px}
.goudou-sched__btn{display:inline-block;background:#d8b46a;color:#1c2b33;font-weight:700;padding:14px 34px;border-radius:999px;text-decoration:none;font-size:1rem;box-shadow:0 6px 18px rgba(0,0,0,.25);transition:.2s}
.goudou-sched__btn:hover{filter:brightness(1.07);color:#1c2b33}
.goudou-sched__link{color:rgba(255,255,255,.85);font-size:.88rem;text-decoration:underline}
.goudou-sched__link:hover{color:#fff}
@media(max-width:600px){.goudou-sched{padding:44px 16px}.goudou-sched__title{font-size:1.35rem}}
</style>
