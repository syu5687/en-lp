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
        <li class="site-nav__insta"><a href="https://www.instagram.com/en1150en/" target="_blank" rel="noopener" aria-label="Instagram" title="Instagram" style="display:inline-flex;align-items:center;gap:6px"><svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2.2c3.2 0 3.6 0 4.85.07 1.17.05 1.8.25 2.23.41.56.22.96.48 1.38.9.42.42.68.82.9 1.38.16.42.36 1.06.41 2.23.06 1.26.07 1.64.07 4.85s0 3.6-.07 4.85c-.05 1.17-.25 1.8-.41 2.23-.22.56-.48.96-.9 1.38-.42.42-.82.68-1.38.9-.42.16-1.06.36-2.23.41-1.26.06-1.64.07-4.85.07s-3.6 0-4.85-.07c-1.17-.05-1.8-.25-2.23-.41a3.7 3.7 0 0 1-1.38-.9 3.7 3.7 0 0 1-.9-1.38c-.16-.42-.36-1.06-.41-2.23C2.2 15.6 2.2 15.2 2.2 12s0-3.6.07-4.85c.05-1.17.25-1.8.41-2.23.22-.56.48-.96.9-1.38.42-.42.82-.68 1.38-.9.42-.16 1.06-.36 2.23-.41C8.4 2.2 8.8 2.2 12 2.2m0-2.2C8.7 0 8.3 0 7.05.07 5.78.13 4.9.33 4.14.63c-.79.3-1.46.72-2.13 1.38A5.9 5.9 0 0 0 .63 4.14C.33 4.9.13 5.78.07 7.05 0 8.3 0 8.7 0 12s0 3.7.07 4.95c.06 1.27.26 2.15.56 2.91.3.79.72 1.46 1.38 2.13a5.9 5.9 0 0 0 2.13 1.38c.76.3 1.64.5 2.91.56C8.3 24 8.7 24 12 24s3.7 0 4.95-.07c1.27-.06 2.15-.26 2.91-.56a5.9 5.9 0 0 0 2.13-1.38 5.9 5.9 0 0 0 1.38-2.13c.3-.76.5-1.64.56-2.91C24 15.7 24 15.3 24 12s0-3.7-.07-4.95c-.06-1.27-.26-2.15-.56-2.91a5.9 5.9 0 0 0-1.38-2.13A5.9 5.9 0 0 0 19.86.63c-.76-.3-1.64-.5-2.91-.56C15.7 0 15.3 0 12 0Zm0 5.84A6.16 6.16 0 1 0 18.16 12 6.16 6.16 0 0 0 12 5.84Zm0 10.15A4 4 0 1 1 16 12a4 4 0 0 1-4 4Zm7.85-10.4a1.44 1.44 0 1 1-1.44-1.44 1.44 1.44 0 0 1 1.44 1.44Z"/></svg><span class="site-nav__insta-label">Instagram</span></a></li>
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
