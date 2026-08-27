<?php
/**
 * 「← 前のページに戻る」ボタン（全ページ共通）
 *  - ヘッダーのすぐ下に固定表示（スクロールしても追従）。ページ内のスペースは取らず、
 *    ヒーロー画像などコンテンツの上に重ねて表示する。
 *  - サイト内の別ページから遷移してきた場合のみ表示（外部から直接来た訪問者には出さない）
 *  - PC/SPともにイメージカラーの青背景・白文字で大きめに表示
 */
?>
<button type="button" class="back-float-btn" id="en-back-btn" hidden>← 前のページに戻る</button>
<style>
  .back-float-btn{position:fixed;left:16px;top:80px;z-index:95;display:inline-flex;align-items:center;gap:6px;padding:12px 24px;background:#15709e;color:#fff;border:2px solid rgba(255,255,255,.85);border-radius:999px;font-size:1rem;font-weight:700;letter-spacing:.04em;cursor:pointer;box-shadow:0 6px 18px rgba(0,0,0,.3);font-family:inherit;transition:background .2s ease,transform .2s ease}
  .back-float-btn:hover{background:#125e85;transform:translateY(-1px)}
  @media(max-width:768px){
    .back-float-btn{left:12px;padding:11px 20px;font-size:.92rem}
  }
  @media print{.back-float-btn{display:none}}
</style>
<script>
  (function () {
    var btn = document.getElementById('en-back-btn');
    if (!btn) return;
    var sameSite = false;
    try { sameSite = document.referrer !== '' && new URL(document.referrer).host === location.host; } catch (e) {}
    if (!(sameSite && history.length > 1)) return;
    btn.hidden = false;
    btn.addEventListener('click', function () { history.back(); });
    // ヘッダーの高さに合わせて、ヘッダー直下に固定（SPのヘッダー縮小にも追従）
    var header = document.querySelector('.site-header') || document.querySelector('header');
    var place = function () {
      var h = 64;
      if (header) {
        var r = header.getBoundingClientRect();
        h = Math.max(0, r.bottom);
      }
      btn.style.top = (h + 10) + 'px';
    };
    place();
    window.addEventListener('scroll', place, { passive: true });
    window.addEventListener('resize', place);
  })();
</script>
