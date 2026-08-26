<?php
require_once __DIR__ . '/../includes/config.php';
$page_title     = 'お問い合わせ｜' . SITE['name'];
$page_desc      = SITE['name'] . 'へのご相談・お見積りは無料です。海洋散骨・粉骨・お墓じまい等、お気軽にお問い合わせください。';
$page_canonical = SITE['url'] . '/contact/';
$page_hero_image = '/assets/img/hero-contact.jpg';
require __DIR__ . '/../includes/head.php';
?>
<body>
<?php require __DIR__ . '/../includes/header.php'; ?>
<section class="page-hero">
  <h1>お問い合わせ</h1>
  <p>ご相談・お見積りは無料です。お気軽にどうぞ。</p>
</section>
<nav class="breadcrumb"><a href="/">ホーム</a> ＞ お問い合わせ</nav>

<main class="section">
  <div class="container" style="max-width:720px">

    <!-- LINE誘導（副導線）-->
    <div class="card" style="text-align:center;margin-bottom:32px;border-color:#06C755">
      <p style="margin-bottom:12px">LINEでのご相談も承っています（友だち追加でそのまま相談可能）</p>
      <a href="<?= h(SITE['line_url']) ?>" target="_blank" rel="noopener" class="btn" style="background:#06C755">LINEで相談する</a>
    </div>

    <h2>フォームからのお問い合わせ</h2>

    <div id="shindan-note" hidden style="background:var(--sea-light);border:1px solid var(--border);border-left:4px solid var(--green);border-radius:8px;padding:14px 18px;margin-bottom:20px;font-size:.92rem">
      「供養の選び方」診断の結果：<strong id="shindan-service" style="color:var(--green-mid)"></strong> についてのご相談ですね。<br>下記フォームにそのまま入力してお送りください。
    </div>

    <div id="form-msg" role="status" aria-live="polite"></div>

    <form id="contact-form" class="contact-form" novalidate>
      <label>お名前 <span class="req">必須</span>
        <input type="text" name="name" required>
      </label>
      <label>ふりがな
        <input type="text" name="kana">
      </label>
      <label>メールアドレス <span class="req">必須</span>
        <input type="email" name="email" required>
      </label>
      <label>電話番号
        <input type="tel" name="tel" inputmode="tel">
      </label>
      <label>お問い合わせ種別
        <select name="category">
          <option value="">選択してください</option>
          <?php foreach (SERVICES as $s): ?>
            <option value="<?= h($s['title']) ?>"><?= h($s['title']) ?></option>
          <?php endforeach; ?>
          <option value="その他">その他</option>
        </select>
      </label>
      <label>お問い合わせ内容 <span class="req">必須</span>
        <textarea name="message" rows="6" required></textarea>
      </label>
      <label class="contact-consent">
        <input type="checkbox" name="consent" value="1" required>
        <span><a href="/privacy/" target="_blank" rel="noopener">プライバシーポリシー</a>に同意します</span>
      </label>
      <button type="submit" class="btn" id="submit-btn">送信する</button>
    </form>

    <p style="margin-top:24px;font-size:.9rem;color:var(--text-light)">
      お電話でも承ります：<a href="tel:<?= h(SITE['tel']) ?>" style="color:var(--green);font-weight:700"><?= h(SITE['tel']) ?></a>（<?= h(SITE['hours_jp']) ?>）
    </p>
  </div>
</main>

<style>
.contact-form{display:flex;flex-direction:column;gap:18px;background:var(--white);border:1px solid var(--border);border-radius:var(--radius);padding:28px}
.contact-form label{display:flex;flex-direction:column;gap:8px;font-weight:600;font-size:.9rem}
.contact-form input,.contact-form select,.contact-form textarea{padding:12px;border:1px solid var(--border);border-radius:8px;font-size:1rem;font-family:inherit}
.contact-form .req{display:inline-block;background:var(--green);color:#fff;font-size:.7rem;padding:2px 8px;border-radius:4px;margin-left:6px;align-self:flex-start}
.contact-consent{flex-direction:row!important;align-items:center;justify-content:center;gap:10px;font-weight:400!important}
.contact-consent input[type=checkbox]{width:18px;height:18px;padding:0!important;margin:0;flex:none;accent-color:var(--green)}
.contact-consent span{white-space:nowrap}
.contact-consent a{color:var(--green);font-weight:600;text-decoration:underline}
#form-msg:not(:empty){padding:14px;border-radius:8px;margin-bottom:20px;font-size:.95rem}
#form-msg.ok{background:#e8f5e9;color:#2e7d32}
#form-msg.ng{background:#fdecea;color:#c0392b}
</style>

<script>
const WORKER_URL = <?= json_encode(CONTACT_WORKER_URL) ?>;
const form = document.getElementById('contact-form');
const msg  = document.getElementById('form-msg');
const btn  = document.getElementById('submit-btn');

// 「供養の選び方」診断からの遷移：選択したご供養を表示し、種別に自動セット
let shindanService = '';
(function () {
  const params = new URLSearchParams(location.search);
  const svc = (params.get('service') || '').trim();
  if (!svc) return;
  shindanService = svc;
  // お知らせバナー
  const note = document.getElementById('shindan-note');
  document.getElementById('shindan-service').textContent = svc;
  note.hidden = false;
  // 種別セレクトに反映（一致する選択肢がなければ追加して選択）
  const sel = form.querySelector('select[name="category"]');
  let opt = [...sel.options].find(o => o.value === svc)
         || [...sel.options].find(o => o.value && (svc.indexOf(o.value) === 0 || o.value.indexOf(svc) === 0));
  if (!opt) {
    opt = new Option(svc, svc);
    sel.insertBefore(opt, sel.querySelector('option[value="その他"]'));
  }
  sel.value = opt.value;
})();
form.addEventListener('submit', async (e) => {
  e.preventDefault();
  msg.className = ''; msg.textContent = '';
  if (!form.checkValidity()) { form.reportValidity(); return; }
  const data = Object.fromEntries(new FormData(form).entries());
  if (!/^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(data.email || '')) {
    msg.className = 'ng';
    msg.textContent = 'メールアドレスの形式をご確認ください。';
    return;
  }
  data.source = location.href;
  data.formName = 'en1150.co.jp お問い合わせフォーム';
  if (shindanService) data.shindan = shindanService; // 診断結果を通知メールにも記載
  btn.disabled = true; btn.textContent = '送信中…';
  try {
    const res = await fetch(WORKER_URL, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(data),
    });
    if (!res.ok) throw new Error('send failed');
    msg.className = 'ok';
    msg.textContent = 'お問い合わせを送信しました。担当者より折り返しご連絡いたします。';
    form.reset();
  } catch (err) {
    msg.className = 'ng';
    msg.textContent = '送信に失敗しました。お手数ですがお電話（<?= h(SITE['tel']) ?>）またはLINEでご連絡ください。';
  } finally {
    btn.disabled = false; btn.textContent = '送信する';
  }
});
</script>

<?php require __DIR__ . '/../includes/footer.php'; ?>
