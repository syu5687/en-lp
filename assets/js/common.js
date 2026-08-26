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
