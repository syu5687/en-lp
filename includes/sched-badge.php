<?php
/**
 * 右下フローティング：次回の合同海洋散骨予定日（全ページ共通）
 * - 管理画面（/admin/goudou/）で登録された「公開中・本日以降」の直近日程を表示
 * - クリックで予定日一覧へ（同一ページ内にセクションがあればページ内スクロール、
 *   無ければ /kaiyou-sou/ の予定日セクションへ遷移）
 * - 予定が1件も無い場合は何も表示しない
 * - データは en_cache（5分）経由のため、PVが増えてもFirestore読み取りは増えない
 */
if (!function_exists('goudou_upcoming')) {
  require_once __DIR__ . '/../admin/includes/store.php';
}
$sb_next = null;
try { $sb = goudou_upcoming(); $sb_next = $sb[0] ?? null; } catch (Throwable $e) { $sb_next = null; }
if ($sb_next):
  $sb_ts = strtotime((string)($sb_next['date'] ?? ''));
  $sb_w  = $sb_ts ? ['日', '月', '火', '水', '木', '金', '土'][(int)date('w', $sb_ts)] : '';
?>
<style>
  .fixed-sched-badge{position:fixed;bottom:24px;right:24px;z-index:98;display:block;width:172px;background:#fffdf9;color:#2a3b33;border:1px solid #eae2d3;border-radius:14px;padding:12px 14px 10px;text-decoration:none;box-shadow:0 8px 26px rgba(40,60,50,.18);transition:transform .25s ease,box-shadow .25s ease;overflow:hidden}
  .fixed-sched-badge::before{content:'';position:absolute;top:0;left:0;right:0;height:3px;background:linear-gradient(90deg,#c9a25a,#e6cf9a)}
  /* キラッと光るシャイン演出（5秒ごと） */
  .fixed-sched-badge::after{content:'';position:absolute;top:-60%;left:-80%;width:50%;height:220%;background:linear-gradient(105deg,rgba(255,255,255,0) 0%,rgba(255,246,220,.85) 50%,rgba(255,255,255,0) 100%);transform:rotate(18deg);animation:sched-shine 5s ease-in-out infinite;pointer-events:none}
  @keyframes sched-shine{0%{left:-80%}18%{left:130%}100%{left:130%}}
  @media (prefers-reduced-motion: reduce){.fixed-sched-badge::after{animation:none;display:none}}
  .fixed-sched-badge:hover{transform:translateY(-3px);box-shadow:0 12px 32px rgba(40,60,50,.24)}
  .fixed-sched-badge__eyebrow{display:block;font-size:.6rem;letter-spacing:.22em;color:#b08b3e;font-weight:700;margin-bottom:3px}
  .fixed-sched-badge__label{display:block;font-size:.72rem;font-weight:700;color:#2e5030;margin-bottom:3px;line-height:1.4}
  .fixed-sched-badge__date{display:block;font-size:1.12rem;font-weight:700;line-height:1.3;color:#1c3b2a;font-family:"Shippori Mincho","Yu Mincho",serif}
  .fixed-sched-badge__sea{display:block;font-size:.68rem;color:#8a8578;margin-top:2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
  .fixed-sched-badge__more{display:block;margin-top:7px;padding-top:6px;border-top:1px solid #eee5d4;font-size:.7rem;font-weight:700;color:#b08b3e;text-align:right}
  @media(max-width:960px){.fixed-sched-badge{bottom:150px;right:8px;width:150px;padding:10px 12px 8px}.fixed-sched-badge__date{font-size:.98rem}}
  @media print{.fixed-sched-badge{display:none}}
</style>
<a class="fixed-sched-badge" href="/kaiyou-sou/#goudou-schedule" aria-label="合同海洋散骨の実施予定日一覧を見る">
  <span class="fixed-sched-badge__eyebrow">SCHEDULE</span>
  <span class="fixed-sched-badge__label">次回の合同海洋散骨</span>
  <span class="fixed-sched-badge__date"><?= $sb_ts ? date('n月j日', $sb_ts) . '（' . h($sb_w) . '）' : h((string)$sb_next['date']) ?></span>
  <?php if (!empty($sb_next['sea'])): ?><span class="fixed-sched-badge__sea"><?= h((string)$sb_next['sea']) ?></span><?php endif; ?>
  <span class="fixed-sched-badge__more">予定日一覧 →</span>
</a>
<script>
  // 同一ページ内に予定日セクションがあれば、ページ内スクロールに切り替える
  (function () {
    var b = document.currentScript.previousElementSibling;
    if (b && document.getElementById('goudou-schedule')) b.setAttribute('href', '#goudou-schedule');
  })();
</script>
<?php endif; ?>
