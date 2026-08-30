<?php
/**
 * フローティング導線（PC専用）— マークアップと配色の定義
 *
 * 表示するかどうか・どちらを出すかは assets/js/floating-cta.js が判断する。
 * ここでは2種類のテンプレートをDOMに置くだけで、初期状態ではどちらも描画されない。
 *
 *  ・schedule … 次回の合同海洋散骨の予定日（散骨に関心のある方向け）
 *  ・shindan  … 供養の選び方診断（どれを選べばよいか迷っている方向け）
 *
 * 旧 includes/sched-badge.php（無条件・即時表示）と assets/js/sd-nudge.js を統合したもの。
 */
if (!function_exists('goudou_upcoming')) {
  require_once __DIR__ . '/../admin/includes/store.php';
}
$fc_next = null;
try { $fc = goudou_upcoming(); $fc_next = $fc[0] ?? null; } catch (Throwable $e) { $fc_next = null; }
$fc_ts   = $fc_next ? strtotime((string)($fc_next['date'] ?? '')) : 0;
$fc_w    = $fc_ts ? ['日','月','火','水','木','金','土'][(int)date('w', $fc_ts)] : '';
?>
<style>
  /* ===== フローティング導線（PC専用・常に1つだけ） =====
     ヘッダー(z:100)より下に置き、ナビゲーションを覆わないようにする。 */
  .en-fcta{position:fixed;right:22px;bottom:22px;z-index:90;width:272px;
    background:#fff;border:1px solid #dde6ec;border-radius:14px;
    box-shadow:0 10px 30px rgba(10,56,82,.16);padding:15px 16px 14px;
    opacity:0;transform:translateY(8px);transition:opacity .22s ease,transform .22s ease}
  .en-fcta.is-in{opacity:1;transform:none}
  .en-fcta__close{position:absolute;top:4px;right:4px;width:32px;height:32px;
    display:grid;place-items:center;background:none;border:0;border-radius:50%;
    color:#9aa7ad;font-size:1.05rem;line-height:1;cursor:pointer;font-family:inherit}
  .en-fcta__close:hover{background:#f2f6f8;color:#5c6b73}
  .en-fcta__close:focus-visible{outline:2px solid #15709e;outline-offset:1px}
  .en-fcta__eyebrow{display:block;font-size:.6rem;letter-spacing:.2em;font-weight:700;margin:0 0 5px;color:#b08b3e}
  .en-fcta__title{margin:0 26px 6px 0;font-size:.94rem;font-weight:700;line-height:1.55;color:#12597a}
  .en-fcta__text{margin:0 0 11px;font-size:.78rem;line-height:1.7;color:#5c6b73}
  .en-fcta__link{display:block;text-align:center;background:#15709e;color:#fff;
    font-weight:700;font-size:.88rem;padding:10px 12px;border-radius:999px;text-decoration:none}
  .en-fcta__link:hover{background:#125e85}
  .en-fcta__link:focus-visible{outline:2px solid #0a3852;outline-offset:2px}
  /* 日程バッジは予定日そのものが主役なので、日付を大きく見せる */
  .en-fcta--schedule{border-color:#eae2d3;background:#fffdf9}
  .en-fcta__date{display:block;margin:0 0 2px;font-size:1.24rem;font-weight:700;line-height:1.3;
    color:#1c3b2a;font-family:"Shippori Mincho","Yu Mincho",serif}
  .en-fcta__sea{display:block;font-size:.72rem;color:#8a8578;margin:0 0 11px}
  .en-fcta--schedule .en-fcta__link{background:#2e5030}
  .en-fcta--schedule .en-fcta__link:hover{background:#24401f}
  @media (prefers-reduced-motion: reduce){.en-fcta{transition:none}}
  /* SPでは表示しない（日程は固定フッターバーに常設・診断は別枠で扱う） */
  @media(max-width:960px){.en-fcta{display:none}}
  @media print{.en-fcta{display:none}}
</style>

<?php if ($fc_next): ?>
<template id="en-fcta-tpl-schedule">
  <button type="button" class="en-fcta__close" aria-label="閉じる">×</button>
  <span class="en-fcta__eyebrow">SCHEDULE</span>
  <p class="en-fcta__title">次回の合同海洋散骨</p>
  <span class="en-fcta__date"><?= $fc_ts ? date('n月j日', $fc_ts) . '（' . h($fc_w) . '）' : h((string)$fc_next['date']) ?></span>
  <?php if (!empty($fc_next['sea'])): ?><span class="en-fcta__sea"><?= h((string)$fc_next['sea']) ?></span><?php endif; ?>
  <a href="/kaiyou-sou/#goudou-schedule" class="en-fcta__link">予定日一覧を見る</a>
</template>
<?php endif; ?>

<template id="en-fcta-tpl-shindan">
  <button type="button" class="en-fcta__close" aria-label="閉じる">×</button>
  <p class="en-fcta__title">どれを選べばいいか、迷っていませんか？</p>
  <p class="en-fcta__text">いくつかの質問で、今の状況に合う供養方法を整理できます（約3分・入力不要）</p>
  <a href="/shindan/" class="en-fcta__link">診断してみる</a>
</template>

<script>window.EN_FCTA_CONFIG = { hasSchedule: <?= $fc_next ? 'true' : 'false' ?> };</script>
<script src="/assets/js/floating-cta.js?v=<?= h(asset_ver()) ?>" defer></script>
