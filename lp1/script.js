/* Release: v2026090801 — in-page navigation enhancement. */
'use strict';
document.querySelectorAll('a[href^="#"]').forEach(link => {
  link.addEventListener('click', () => {
    const hash = link.getAttribute('href');
    if (!hash || hash === '#') return;
    const destination = document.getElementById(hash.slice(1));
    if (destination) {
      destination.setAttribute('tabindex', '-1');
      destination.focus({ preventScroll: true });
    }
  });
});
