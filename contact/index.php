<?php
require_once __DIR__ . '/../includes/config.php';
$page_title     = 'お問い合わせ｜' . SITE['name'];
$page_desc      = SITE['name'] . 'へのご相談・お見積りは無料です。海洋散骨・粉骨・お墓じまい等、お気軽にお問い合わせください。';
$page_canonical = SITE['url'] . '/contact/';
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
        <a href="/privacy/" target="_blank" rel="noopener">プライバシーポリシー</a>に同意します
      </label>
      <button type="submit" class="btn" id="submit-btn">送信する</button>
    </form>

    <p style="margin-top:24px;font-size:.9rem;color:var(--text-light)">
      お電話でも承ります：<a href="tel:<?= h(SITE['tel']) ?>" style="color:var(--green);font-weight:700"><?= h(SITE['tel']) ?></a>（<?= h(SITE['hours']) ?>）
    </p>
  </div>
</main>

<style>
.contact-form{display:flex;flex-direction:column;gap:18px;background:var(--white);border:1px solid var(--border);border-radius:var(--radius);padding:28px}
.contact-form label{display:flex;flex-direction:column;gap:8px;font-weight:600;font-size:.9rem}
.contact-form input,.contact-form select,.contact-form textarea{padding:12px;border:1px solid var(--border);border-radius:8px;font-size:1rem;font-family:inherit}
.contact-form .req{display:inline-block;background:var(--green);color:#fff;font-size:.7rem;padding:2px 8px;border-radius:4px;margin-left:6px}
.contact-consent{flex-direction:row!important;align-items:center;gap:8px;font-weight:400!important}
#form-msg:not(:empty){padding:14px;border-radius:8px;margin-bottom:20px;font-size:.95rem}
#form-msg.ok{background:#e8f5e9;color:#2e7d32}
#form-msg.ng{background:#fdecea;color:#c0392b}
</style>

<script>
const WORKER_URL = <?= json_encode(CONTACT_WORKER_URL) ?>;
const form = document.getElementById('contact-form');
const msg  = document.getElementById('form-msg');
const btn  = document.getElementById('submit-btn');
form.addEventListener('submit', async (e) => {
  e.preventDefault();
  msg.className = ''; msg.textContent = '';
  if (!form.checkValidity()) { form.reportValidity(); return; }
  const data = Object.fromEntries(new FormData(form).entries());
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
