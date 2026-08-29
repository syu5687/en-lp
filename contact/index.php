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

    <div style="background:#f6efdd;border-left:4px solid #a8802f;border-radius:0 10px 10px 0;padding:14px 18px;margin-bottom:20px;font-size:.92rem;line-height:1.9">
      <strong style="color:#0a3852">ご安心ください。</strong>ご相談いただいても、こちらから営業のお電話やしつこいご連絡は一切いたしません。<br>「まだ決めていない」「話を聞いてみたいだけ」という段階のご相談こそ、いちばんお役に立てます。
    </div>

    <div id="shindan-note" hidden style="background:var(--sea-light);border:1px solid var(--border);border-left:4px solid var(--green);border-radius:8px;padding:14px 18px;margin-bottom:20px;font-size:.92rem">
      「供養の選び方」診断の結果：<strong id="shindan-service" style="color:var(--green-mid)"></strong> についてのご相談ですね。<br>下記フォームにそのまま入力してお送りください。
    </div>

    <form id="contact-form" class="contact-form" novalidate>
      <!-- ハニーポット（人間には見えない欄。ボットが入力したら弾く） -->
      <div class="hp-field" aria-hidden="true">
        <label>ウェブサイト<input type="text" name="website" tabindex="-1" autocomplete="off"></label>
      </div>
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
      <div class="contact-attrs">
        <label>お住まい（任意）
          <select name="pref">
            <option value="">選択しない</option>
            <?php $prefs = ['北海道','青森県','岩手県','宮城県','秋田県','山形県','福島県','茨城県','栃木県','群馬県','埼玉県','千葉県','東京都','神奈川県','新潟県','富山県','石川県','福井県','山梨県','長野県','岐阜県','静岡県','愛知県','三重県','滋賀県','京都府','大阪府','兵庫県','奈良県','和歌山県','鳥取県','島根県','岡山県','広島県','山口県','徳島県','香川県','愛媛県','高知県','福岡県','佐賀県','長崎県','熊本県','大分県','宮崎県','鹿児島県','沖縄県'];
              foreach ($prefs as $pf): ?><option value="<?= h($pf) ?>"><?= h($pf) ?></option><?php endforeach; ?>
          </select>
        </label>
        <label>ご年代（任意）
          <select name="age_group">
            <option value="">選択しない</option>
            <?php foreach (['40代以下','50代','60代','70代','80代以上'] as $ag): ?><option value="<?= h($ag) ?>"><?= h($ag) ?></option><?php endforeach; ?>
          </select>
        </label>
        <label>性別（任意）
          <select name="gender">
            <option value="">回答しない</option>
            <option value="女性">女性</option>
            <option value="男性">男性</option>
            <option value="その他">その他</option>
          </select>
        </label>
      </div>
      <p style="font-size:.78rem;color:var(--text-light);margin:-8px 0 0">※ 任意項目は、サービス改善のための統計にのみ利用します（<a href="/privacy/" target="_blank" rel="noopener" style="color:var(--green);text-decoration:underline">プライバシーポリシー</a>）。</p>
      <label>お問い合わせ種別
        <select name="category">
          <option value="">選択してください</option>
          <option value="資料請求（無料）">資料請求（無料・PDFをメールでお届け）</option>
          <?php foreach (SERVICES as $s): ?>
            <option value="<?= h($s['title']) ?>"><?= h($s['title']) ?></option>
          <?php endforeach; ?>
          <option value="その他">その他</option>
        </select>
      </label>
      <label id="goudou-date-field" hidden>合同海洋散骨 ご希望日
        <input type="date" name="goudou_date">
        <span style="font-weight:400;font-size:.8rem;color:var(--text-light)">実施予定日からお選びいただいた日付です。変更も可能です。</span>
      </label>
      <label>お問い合わせ内容 <span class="req">必須</span>
        <textarea name="message" rows="6" required></textarea>
      </label>
      <label class="contact-consent">
        <input type="checkbox" name="consent" value="1" required>
        <span><a href="/privacy/" target="_blank" rel="noopener">プライバシーポリシー</a>に同意します</span>
      </label>
      <p style="font-size:.82rem;color:var(--text-light);margin:-6px 0 0">送信後、受付確認の自動返信メールが届きます。営業のご連絡はいたしませんので、ご安心ください。</p>
      <button type="submit" class="btn" id="submit-btn">送信する</button>
      <div id="form-msg" role="status" aria-live="polite"></div>
    </form>

    <p style="margin-top:24px;font-size:.9rem;color:var(--text-light)">
      お電話でも承ります：<a href="tel:<?= h(SITE['tel']) ?>" style="color:var(--green);font-weight:700"><?= h(SITE['tel']) ?></a>（<?= h(SITE['hours_jp']) ?>）
    </p>
  </div>
</main>

<style>
.contact-form{display:flex;flex-direction:column;gap:18px;background:var(--white);border:1px solid var(--border);border-radius:var(--radius);padding:28px}
.contact-form label{display:flex;flex-direction:column;gap:8px;font-weight:600;font-size:.9rem}
.contact-form label[hidden]{display:none !important} /* 合同散骨ご希望日欄は日付指定の遷移時のみ表示 */
.contact-attrs{display:grid;grid-template-columns:repeat(3,1fr);gap:14px}
.contact-attrs label{display:flex;flex-direction:column;gap:8px;font-weight:600;font-size:.9rem}
@media(max-width:560px){.contact-attrs{grid-template-columns:1fr}}
.contact-form input,.contact-form select,.contact-form textarea{padding:12px;border:1px solid var(--border);border-radius:8px;font-size:1rem;font-family:inherit}
.contact-form .req{display:inline-block;background:var(--green);color:#fff;font-size:.7rem;padding:2px 8px;border-radius:4px;margin-left:6px;align-self:flex-start}
.contact-consent{flex-direction:row!important;align-items:center;justify-content:center;gap:10px;font-weight:400!important}
.contact-consent input[type=checkbox]{width:18px;height:18px;padding:0!important;margin:0;flex:none;accent-color:var(--green)}
.contact-consent span{white-space:nowrap}
.contact-consent a{color:var(--green);font-weight:600;text-decoration:underline}
#form-msg:not(:empty){padding:14px;border-radius:8px;margin-top:4px;font-size:.95rem;text-align:center}
#form-msg.ok{background:#e8f5e9;color:#2e7d32}
#form-msg.ng{background:#fdecea;color:#c0392b}
.hp-field{position:absolute !important;left:-9999px !important;top:-9999px !important;height:1px;width:1px;overflow:hidden}
</style>

<script>
const WORKER_URL = <?= json_encode(CONTACT_WORKER_URL) ?>;
const PAGE_LOADED_AT = Date.now();
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
// 資料請求：種別で選ばれたら、内容欄を自動で埋めて手間をなくす
(function () {
  const sel = form.querySelector('select[name="category"]');
  const ta  = form.querySelector('textarea[name="message"]');
  const FILL = '資料請求：「墓じまい完全ガイド 鹿児島・福岡版」「海洋散骨で後悔しないためのチェックリスト」（PDF）を希望します。';
  const apply = () => {
    if (sel.value === '資料請求（無料）' && !ta.value.trim()) ta.value = FILL;
    if (sel.value !== '資料請求（無料）' && ta.value === FILL) ta.value = '';
  };
  sel.addEventListener('change', apply);
  setTimeout(apply, 0); // ?service= からの自動選択にも反応
})();
// 合同海洋散骨 実施予定日からの遷移：?date=YYYY-MM-DD をご希望日欄にセット
(function () {
  const params = new URLSearchParams(location.search);
  const d = (params.get('date') || '').trim();
  const field = document.getElementById('goudou-date-field');
  if (!/^\d{4}-\d{2}-\d{2}$/.test(d)) return;
  field.hidden = false;
  field.querySelector('input[name="goudou_date"]').value = d;
  const note = document.getElementById('shindan-note');
  if (note.hidden) {
    document.getElementById('shindan-service').textContent = '合同海洋散骨（ご希望日 ' + d.replace(/-/g, '/') + '）';
    note.hidden = false;
  }
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
  data.elapsedMs = Date.now() - PAGE_LOADED_AT; // 表示から送信までの時間（ボット判定用）
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
    // CV測定（GA4）：キーイベント用に generate_lead を送信
    // ボットによる送信をCVに数えないため、「表示から5秒以上経過・ハニーポット空・メール形式OK」の場合のみ発火
    var looksHuman = data.elapsedMs >= 5000 && !data.website && /@.+\./.test(data.email || '');
    if (typeof gtag === 'function' && looksHuman) {
      gtag('event', 'generate_lead', {
        form_name: 'contact',
        category: data.category || '(未選択)',
        shindan: data.shindan || '(なし)'
      });
    }
  } catch (err) {
    msg.className = 'ng';
    msg.textContent = '送信に失敗しました。お手数ですがお電話（<?= h(SITE['tel']) ?>）またはLINEでご連絡ください。';
  } finally {
    btn.disabled = false; btn.textContent = '送信する';
  }
});
</script>

<?php require __DIR__ . '/../includes/footer.php'; ?>
