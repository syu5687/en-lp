<?php /* 文字サイズ変更コントロール（開閉式）。header.php と TOP(index.php) から読み込む共通部品 */ ?>
<!-- 文字サイズ変更（開閉式） -->
<div class="fontsize-ctl" role="group" aria-label="文字サイズの変更">
  <div class="fontsize-ctl__panel" hidden>
    <span class="fontsize-ctl__label">文字サイズ</span>
    <button type="button" data-fs="100">標準</button>
    <button type="button" data-fs="115">大</button>
    <button type="button" data-fs="130">特大</button>
  </div>
  <button type="button" class="fontsize-ctl__toggle" aria-expanded="false" aria-label="文字サイズを変更する">
    <span class="fontsize-ctl__toggle-txt">文字<br>サイズ</span>
  </button>
</div>
<style>
  .fontsize-ctl{position:fixed;right:10px;top:76px;z-index:300;display:flex;align-items:center;gap:8px;flex-direction:row}
  .fontsize-ctl__toggle{width:46px;height:46px;border-radius:50%;border:1px solid #c9d6db;background:rgba(255,255,255,.96);color:#15709e;cursor:pointer;font-family:inherit;box-shadow:0 2px 10px rgba(9,45,66,.18);display:flex;align-items:center;justify-content:center;padding:0}
  .fontsize-ctl__toggle-txt{font-size:10px;font-weight:700;line-height:1.25;letter-spacing:.02em}
  .fontsize-ctl.is-open .fontsize-ctl__toggle{background:#15709e;border-color:#15709e;color:#fff}
  .fontsize-ctl__panel{display:flex;align-items:center;gap:5px;background:rgba(255,255,255,.97);border:1px solid #d8e2e6;border-radius:999px;padding:5px 9px 5px 13px;box-shadow:0 2px 12px rgba(9,45,66,.16)}
  .fontsize-ctl__panel[hidden]{display:none}
  .fontsize-ctl__label{font-size:11px;color:#5f6d6b;letter-spacing:.05em;white-space:nowrap}
  .fontsize-ctl__panel button{font-family:inherit;cursor:pointer;border:1px solid #c9d6db;background:#fff;color:#15709e;font-size:12px;font-weight:600;padding:6px 11px;border-radius:999px;line-height:1.4;white-space:nowrap}
  .fontsize-ctl__panel button.is-on{background:#15709e;border-color:#15709e;color:#fff}
  @media(max-width:960px){
    .fontsize-ctl{top:auto;bottom:88px;right:8px}
    .fontsize-ctl__label{display:none}
    .fontsize-ctl__toggle{width:44px;height:44px}
  }
  @media print{.fontsize-ctl{display:none}}
</style>
<script>
  (function () {
    var KEY = 'en-fontsize';
    var ctl = document.querySelector('.fontsize-ctl');
    if (!ctl) return;
    var toggle = ctl.querySelector('.fontsize-ctl__toggle');
    var panel  = ctl.querySelector('.fontsize-ctl__panel');
    function place() {
      if (window.matchMedia('(max-width:960px)').matches) { ctl.style.top = ''; return; }
      var hd = document.querySelector('.site-header') || document.querySelector('.header');
      if (hd) ctl.style.top = (hd.offsetHeight + 8) + 'px';
    }
    function apply(v) {
      document.documentElement.style.fontSize = v + '%';
      ctl.querySelectorAll('.fontsize-ctl__panel button').forEach(function (b) {
        b.classList.toggle('is-on', b.getAttribute('data-fs') === String(v));
      });
      place();
    }
    function open()  { panel.hidden = false; ctl.classList.add('is-open');  toggle.setAttribute('aria-expanded', 'true'); }
    function close() { panel.hidden = true;  ctl.classList.remove('is-open'); toggle.setAttribute('aria-expanded', 'false'); }
    toggle.addEventListener('click', function (e) {
      e.stopPropagation();
      panel.hidden ? open() : close();
    });
    document.addEventListener('click', function (e) { if (!ctl.contains(e.target)) close(); });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') close(); });
    window.addEventListener('resize', place);
    window.addEventListener('load', place);
    var saved = 100;
    try { saved = parseInt(localStorage.getItem(KEY), 10) || 100; } catch (e) {}
    apply(saved);
    ctl.querySelectorAll('.fontsize-ctl__panel button').forEach(function (b) {
      b.addEventListener('click', function () {
        var v = parseInt(b.getAttribute('data-fs'), 10);
        apply(v);
        try { localStorage.setItem(KEY, String(v)); } catch (e) {}
      });
    });
  })();
</script>
