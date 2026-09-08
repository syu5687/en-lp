/* Release: v2026090803 — in-page navigation enhancement. */
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
  const lpPostal = document.getElementById('lp-postal-fields');
  const lpZip = lpForm.querySelector('[name="zip"]');
  const lpAddress = lpForm.querySelector('[name="addr"]');
  const lpZipStatus = document.getElementById('lp-zip-status');
  const lpZipButton = document.getElementById('lp-zip-lookup');

  const updatePostalFields = () => {
    const needsPostal = lpCategory && lpCategory.value === '資料請求（無料）';
    lpPostal.hidden = !needsPostal;
    lpZip.required = needsPostal;
    lpAddress.required = needsPostal;
  };

  const lookupPostalCode = () => {
    const digits = (lpZip.value || '').replace(/[^0-9]/g, '');
    if (digits.length !== 7) {
      lpZipStatus.textContent = '郵便番号を7桁で入力してください。';
      return;
    }
    lpZipStatus.textContent = '住所を確認しています…';
    const callbackName = '__lpZip' + Date.now();
    const script = document.createElement('script');
    const cleanup = () => {
      delete window[callbackName];
      script.remove();
    };
    window[callbackName] = result => {
      const item = result && result.results && result.results[0];
      if (item) {
        lpAddress.value = (item.address1 || '') + (item.address2 || '') + (item.address3 || '');
        lpZipStatus.textContent = '住所を自動入力しました。番地以降をご確認ください。';
        lpAddress.focus();
      } else {
        lpZipStatus.textContent = '住所が見つかりませんでした。直接ご入力ください。';
      }
      cleanup();
    };
    script.onerror = () => {
      lpZipStatus.textContent = '住所を取得できませんでした。直接ご入力ください。';
      cleanup();
    };
    script.src = 'https://zipcloud.ibsnet.co.jp/api/search?zipcode=' + digits + '&callback=' + callbackName;
    document.head.appendChild(script);
  };

  lpCategory.addEventListener('change', updatePostalFields);
  lpZipButton.addEventListener('click', lookupPostalCode);
  lpZip.addEventListener('input', () => {
    if ((lpZip.value || '').replace(/[^0-9]/g, '').length === 7) lookupPostalCode();
  });
  updatePostalFields();

  document.querySelectorAll('.form-jump').forEach(link => {
    link.addEventListener('click', () => {
      const selected = link.dataset.formCategory;
      if (selected && lpCategory) { lpCategory.value = selected; updatePostalFields(); }
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
