/* Release: v2026091503 — 広告経由の識別子保存（gclid/utm）と contact_submit 計測を追加。 */
'use strict';

/* --------------------------------------------------------------
   広告経由の識別子（gclid / utm_*）を保持する。
   - LP到着時のURLから取得し、sessionStorage へ退避（再読み込み対策）
   - 取得できない場合は空文字。送信は止めない
   -------------------------------------------------------------- */
const LP_AD_KEYS = ['gclid', 'utm_source', 'utm_medium', 'utm_campaign', 'utm_content', 'utm_term'];
const LP_AD_STORE = 'en_lp1_ad_params';
const lpAdParams = (() => {
  const out = {};
  LP_AD_KEYS.forEach(k => { out[k] = ''; });
  try {
    const q = new URLSearchParams(location.search);
    let fromUrl = false;
    LP_AD_KEYS.forEach(k => {
      const v = (q.get(k) || '').slice(0, 200);
      out[k] = v;
      if (v) fromUrl = true;
    });
    if (fromUrl) {
      sessionStorage.setItem(LP_AD_STORE, JSON.stringify(out));
    } else {
      const saved = JSON.parse(sessionStorage.getItem(LP_AD_STORE) || '{}');
      LP_AD_KEYS.forEach(k => { out[k] = String(saved[k] || '').slice(0, 200); });
    }
  } catch (e) {
    LP_AD_KEYS.forEach(k => { if (typeof out[k] !== 'string') out[k] = ''; });
  }
  return out;
})();
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


/* フッターの公式サイトボタン。LPからの離脱先を1件だけ計測する（既存イベント名は変更しない）。 */
const lpOfficial = document.getElementById('lp-official-link');
if (lpOfficial) {
  lpOfficial.addEventListener('click', () => {
    if (typeof gtag === 'function') {
      gtag('event', 'outbound_internal', {
        link_url: lpOfficial.getAttribute('href') || '',
        link_label: '公式サイト（フッター）',
        page_path: '/lp1/'
      });
    }
  });
}

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
    data.form_name = 'lp1_kagoshima_ocean_burial';
    data.landing_page = '/lp1/';
    LP_AD_KEYS.forEach(k => { data[k] = lpAdParams[k] || ''; });
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
        const lpEventParams = {
          form_name: 'lp1_kagoshima_ocean_burial',
          category: data.category || '(未選択)',
          method: 'inline_form'
        };
        /* 送信数の正指標。本体フォーム（/contact/）と同じ contact_submit を送る。 */
        gtag('event', 'contact_submit', lpEventParams);
        /* 既存イベント名は変更しないため generate_lead も従来どおり送る。 */
        gtag('event', 'generate_lead', lpEventParams);
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
