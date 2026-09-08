/* Release: v2026090802 — in-page navigation enhancement. */
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


const lpForm = document.getElementById('lp-contact-form');
if (lpForm) {
  const lpLoadedAt = Date.now();
  const lpMessage = document.getElementById('lp-form-msg');
  const lpButton = document.getElementById('lp-submit-btn');
  const lpCategory = lpForm.querySelector('[name="category"]');
  const lpTextarea = lpForm.querySelector('[name="message"]');

  document.querySelectorAll('.form-jump').forEach(link => {
    link.addEventListener('click', () => {
      const selected = link.dataset.formCategory;
      if (selected && lpCategory) lpCategory.value = selected;
      if (selected === '資料請求（無料）' && lpTextarea && !lpTextarea.value.trim()) {
        lpTextarea.value = '鹿児島・錦江湾の海洋散骨について、無料資料を希望します。';
      }
    });
  });

  lpForm.addEventListener('submit', async event => {
    event.preventDefault();
    lpMessage.className = 'lp-form-result';
    lpMessage.textContent = '';
    if (!lpForm.checkValidity()) {
      lpForm.reportValidity();
      return;
    }
    const data = Object.fromEntries(new FormData(lpForm).entries());
    if (!/^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(data.email || '')) {
      lpMessage.className = 'lp-form-result ng';
      lpMessage.textContent = 'メールアドレスの形式をご確認ください。';
      lpMessage.focus();
      return;
    }
    data.source = location.href;
    data.formName = '鹿児島・錦江湾 海洋散骨LP（lp1）';
    data.elapsedMs = Date.now() - lpLoadedAt;
    lpButton.disabled = true;
    lpButton.firstChild.textContent = '送信中… ';
    try {
      const response = await fetch(lpForm.dataset.endpoint, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
      });
      const result = await response.json().catch(() => ({}));
      if (!response.ok || result.ok === false) throw new Error('send failed');
      lpMessage.className = 'lp-form-result ok';
      lpMessage.textContent = 'お問い合わせを受け付けました。確認メールをご確認ください。';
      lpForm.reset();
      const looksHuman = data.elapsedMs >= 5000 && !data.website;
      if (typeof gtag === 'function' && looksHuman) {
        gtag('event', 'generate_lead', {
          form_name: 'lp1_kagoshima_ocean_burial',
          category: data.category || '(未選択)',
          method: 'inline_form'
        });
      }
    } catch (error) {
      lpMessage.className = 'lp-form-result ng';
      lpMessage.textContent = '送信できませんでした。お手数ですが、お電話またはLINEでご連絡ください。';
    } finally {
      lpButton.disabled = false;
      lpButton.firstChild.textContent = '無料で相談・資料請求する ';
      lpMessage.focus();
    }
  });
}
