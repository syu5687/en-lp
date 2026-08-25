<?php require_once __DIR__ . '/config.php'; ?>
<header class="site-header">
  <div class="site-header__inner">
    <a href="/" class="site-logo" aria-label="<?= h(SITE['name']) ?> トップページ">
      <img src="<?= h(SITE['logo']) ?>" alt="<?= h(SITE['name']) ?> ロゴ" class="site-logo__img">
      <span class="site-logo__text"><?= h(SITE['name']) ?></span>
    </a>
    <nav class="site-nav" aria-label="グローバルナビゲーション">
      <button class="site-nav__toggle" aria-label="メニューを開く" aria-expanded="false">
        <span></span><span></span><span></span>
      </button>
      <ul class="site-nav__list">
        <?php foreach (NAV as $item): ?>
          <li><a href="<?= h($item['href']) ?>"><?= h($item['label']) ?></a></li>
        <?php endforeach; ?>
        <li class="site-nav__cta">
          <a href="/contact/">資料請求・ご相談</a>
        </li>
      </ul>
    </nav>
  </div>
</header>
<!-- 文字サイズ変更 -->
<div class="fontsize-ctl" role="group" aria-label="文字サイズの変更">
  <span class="fontsize-ctl__label">文字サイズ</span>
  <button type="button" data-fs="100">標準</button>
  <button type="button" data-fs="115">大</button>
  <button type="button" data-fs="130">特大</button>
</div>
<style>
  .fontsize-ctl{position:fixed;right:10px;top:76px;z-index:101;display:flex;align-items:center;gap:5px;background:rgba(255,255,255,.95);border:1px solid #d8e2e6;border-radius:999px;padding:4px 8px 4px 12px;box-shadow:0 2px 10px rgba(9,45,66,.12)}
  .fontsize-ctl__label{font-size:11px;color:#5f6d6b;letter-spacing:.05em}
  .fontsize-ctl button{font-family:inherit;cursor:pointer;border:1px solid #c9d6db;background:#fff;color:#15709e;font-size:11px;font-weight:600;padding:3px 9px;border-radius:999px;line-height:1.4}
  .fontsize-ctl button.is-on{background:#15709e;border-color:#15709e;color:#fff}
  @media(max-width:960px){.fontsize-ctl{top:auto;bottom:76px;right:8px;padding:3px 6px 3px 10px}.fontsize-ctl__label{display:none}}
  @media print{.fontsize-ctl{display:none}}
</style>
<script>
  (function () {
    var KEY = 'en-fontsize';
    var ctl = document.querySelector('.fontsize-ctl');
    if (!ctl) return;
    function place() {
      if (window.matchMedia('(max-width:960px)').matches) { ctl.style.top = ''; return; }
      var hd = document.querySelector('.site-header');
      if (hd) ctl.style.top = (hd.offsetHeight + 8) + 'px';
    }
    function apply(v) {
      document.documentElement.style.fontSize = v + '%';
      ctl.querySelectorAll('button').forEach(function (b) {
        b.classList.toggle('is-on', b.getAttribute('data-fs') === String(v));
      });
      place();
    }
    window.addEventListener('resize', place);
    window.addEventListener('load', place);
    var saved = 100;
    try { saved = parseInt(localStorage.getItem(KEY), 10) || 100; } catch (e) {}
    apply(saved);
    ctl.querySelectorAll('button').forEach(function (b) {
      b.addEventListener('click', function () {
        var v = parseInt(b.getAttribute('data-fs'), 10);
        apply(v);
        try { localStorage.setItem(KEY, String(v)); } catch (e) {}
      });
    });
  })();
</script>
