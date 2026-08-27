// 共通JS — ハンバーガーメニュー開閉
document.addEventListener('DOMContentLoaded', () => {
  const toggle = document.querySelector('.site-nav__toggle');
  const list = document.querySelector('.site-nav__list');
  if (toggle && list) {
    toggle.addEventListener('click', () => {
      const open = list.classList.toggle('is-open');
      toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
      document.body.classList.toggle('sp-menu-open', open); // 右固定タブ等を隠す
    });
  }
});

// SP: スクロール時にヘッダーを縮小して画面領域を確保
(function () {
  var header = document.querySelector('.site-header') || document.querySelector('.header');
  if (!header) return;
  var mq = window.matchMedia('(max-width: 768px)');
  var last = false;
  function onScroll() {
    if (!mq.matches) { if (last) { header.classList.remove('is-shrink'); last = false; } return; }
    var shrink = window.scrollY > 80;
    if (shrink !== last) { header.classList.toggle('is-shrink', shrink); last = shrink; }
  }
  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll();
})();
