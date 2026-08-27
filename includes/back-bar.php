<?php
/**
 * 「← 前のページに戻る」バー（全ページ共通・ヘッダー直下）
 *  - サイト内の別ページから遷移してきた場合のみ表示（外部から直接来た訪問者には出さない）
 *  - PC/SPともにイメージカラーの青背景・白文字で大きめに表示
 */
?>
<div class="back-bar">
  <div class="back-bar__inner">
    <button type="button" class="back-bar__btn" id="en-back-btn" hidden>← 前のページに戻る</button>
  </div>
</div>
<style>
  .back-bar__inner{max-width:1120px;margin:0 auto;padding:0 20px}
  .back-bar__btn{display:inline-flex;align-items:center;gap:6px;margin:14px 0 0;padding:12px 26px;background:#15709e;color:#fff;border:none;border-radius:999px;font-size:1rem;font-weight:700;letter-spacing:.04em;cursor:pointer;box-shadow:0 4px 14px rgba(18,89,122,.28);font-family:inherit;transition:background .2s ease,transform .2s ease}
  .back-bar__btn:hover{background:#125e85;transform:translateY(-1px)}
  @media(max-width:768px){
    .back-bar__inner{padding:0 16px}
    .back-bar__btn{margin-top:12px;padding:12px 22px;font-size:.95rem}
  }
  @media print{.back-bar{display:none}}
</style>
<script>
  (function () {
    var btn = document.getElementById('en-back-btn');
    if (!btn) return;
    var sameSite = false;
    try { sameSite = document.referrer !== '' && new URL(document.referrer).host === location.host; } catch (e) {}
    if (sameSite && history.length > 1) {
      btn.hidden = false;
      btn.addEventListener('click', function () { history.back(); });
    }
  })();
</script>
