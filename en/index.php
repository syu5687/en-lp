<?php
/**
 * English landing page — Sea Burial in Japan (Phase 1)
 * 海外向け統合LP：サービス・料金・流れ・信頼・FAQ・英語フォームを1ページに集約。
 * 送信は既存の en-contact worker（lang=en で英語自動返信・海外タグ通知）。
 */
require_once __DIR__ . '/../includes/config.php';
$en_canonical = SITE['url'] . '/en/';
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sea Burial in Japan | Ash Scattering in Kagoshima &amp; Fukuoka — En Co., Ltd.</title>
<meta name="description" content="Sea burial (ash scattering) in Kagoshima and Fukuoka, Japan. 3,800+ ceremonies since 2013. Unattended service from ¥54,450 with GPS certificate, photos and video. English email support for families overseas.">
<link rel="canonical" href="<?= h($en_canonical) ?>">
<?php require_once __DIR__ . '/../includes/lang-map.php'; en_lang_tags('/en/'); ?>
<meta property="og:title" content="Sea Burial in Japan — Kagoshima &amp; Fukuoka | En Co., Ltd.">
<meta property="og:description" content="Ash scattering at sea in southern Japan, with English support. Unattended ceremonies from ¥54,450, GPS certificate, photos and video for families overseas.">
<meta property="og:image" content="<?= h(SITE['url']) ?>/assets/img/top/slide-1.jpg">
<meta property="og:url" content="<?= h($en_canonical) ?>">
<meta property="og:type" content="website">
<?php require __DIR__ . '/../includes/ga4.php'; ?>
<style>
:root{--navy:#0a3852;--ocean:#15709e;--sea:#e3f0f7;--gold:#a8802f;--goldbg:#fdf9f0;--cream:#f7f4ec;--line:#dde6ec;--text:#26333b;--light:#5c6b73;--green:#12597a}
*{box-sizing:border-box}
body{margin:0;font-family:Georgia,'Times New Roman',serif;color:var(--text);background:#fff;line-height:1.75;font-size:16.5px}
.sans{font-family:-apple-system,'Segoe UI',Roboto,Helvetica,Arial,sans-serif}
a{color:var(--ocean)}
.wrap{max-width:960px;margin:0 auto;padding:0 22px}
/* header */
.hd{background:#fff;border-bottom:1px solid var(--line);position:sticky;top:0;z-index:50}
.hd-in{max-width:960px;margin:0 auto;padding:12px 22px;display:flex;align-items:center;justify-content:space-between;gap:12px}
.hd-logo{font-weight:700;color:var(--navy);text-decoration:none;font-size:1.02rem}
.hd-logo small{display:block;font-size:.68rem;color:var(--light);font-weight:400;letter-spacing:.06em}
.hd-right{display:flex;align-items:center;gap:14px;font-size:.8rem}
.hd-jp{color:var(--light);text-decoration:none}
.hd-cta{background:var(--ocean);color:#fff;text-decoration:none;font-weight:700;padding:9px 20px;border-radius:999px;font-size:.85rem}
/* hero */
.hero{background:linear-gradient(rgba(10,56,82,.55),rgba(10,56,82,.55)),url('/assets/img/top/slide-1.jpg?v=<?= h(asset_ver()) ?>') center/cover;color:#fff;padding:84px 22px 76px;text-align:center}
.hero h1{font-size:clamp(1.7rem,4.5vw,2.6rem);margin:0 0 14px;line-height:1.35;font-weight:600;letter-spacing:.01em}
.hero p{max-width:640px;margin:0 auto 26px;font-size:1.02rem;opacity:.96}
.hero .btns{display:flex;gap:12px;justify-content:center;flex-wrap:wrap}
.btn{display:inline-block;padding:13px 30px;border-radius:999px;text-decoration:none;font-weight:700;font-size:.95rem}
.btn-w{background:#fff;color:var(--navy)}
.btn-o{border:1.5px solid rgba(255,255,255,.85);color:#fff}
/* trust strip */
.strip{background:var(--navy);color:#fff;padding:16px 22px}
.strip-in{max-width:960px;margin:0 auto;display:flex;flex-wrap:wrap;justify-content:center;gap:8px 34px;font-size:.84rem}
.strip b{color:#ffd98a}
/* sections */
section{padding:56px 0}
section.alt{background:var(--cream)}
h2{font-size:1.5rem;color:var(--navy);text-align:center;margin:0 0 8px;line-height:1.4}
.sub{text-align:center;color:var(--light);font-size:.92rem;margin:0 0 32px}
h3{color:var(--green);font-size:1.08rem;margin:0 0 8px}
/* summary (AI-citable) */
.cite{max-width:780px;margin:0 auto;background:var(--goldbg);border:1px solid #e3d5b8;border-left:5px solid var(--gold);border-radius:0 12px 12px 0;padding:22px 26px;font-size:.98rem}
/* plans */
.plans{display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:18px}
.plan{background:#fff;border:1px solid var(--line);border-radius:14px;padding:24px 22px;display:flex;flex-direction:column}
.plan.hl{border:2px solid var(--gold);position:relative}
.plan.hl::before{content:'Most requested from overseas';position:absolute;top:-12px;left:50%;transform:translateX(-50%);background:var(--gold);color:#fff;font-size:.68rem;font-weight:700;padding:3px 14px;border-radius:999px;white-space:nowrap;font-family:inherit}
.plan h3{margin-bottom:4px}
.plan .price{font-size:1.5rem;color:var(--navy);font-weight:700;margin:6px 0 2px}
.plan .usd{font-size:.78rem;color:var(--light);margin-bottom:12px}
.plan ul{margin:0 0 8px;padding-left:18px;font-size:.88rem;color:var(--text)}
.plan li{margin-bottom:5px}
.note{font-size:.78rem;color:var(--light);text-align:center;margin-top:14px}
/* steps */
.steps{counter-reset:s;max-width:720px;margin:0 auto;padding:0;list-style:none}
.steps li{position:relative;padding:0 0 26px 58px}
.steps li::before{counter-increment:s;content:counter(s);position:absolute;left:0;top:0;width:38px;height:38px;border-radius:50%;background:var(--ocean);color:#fff;display:grid;place-items:center;font-weight:700;font-family:inherit}
.steps li::after{content:'';position:absolute;left:18.5px;top:42px;bottom:4px;width:1.5px;background:var(--line)}
.steps li:last-child::after{display:none}
.steps b{color:var(--navy)}
.steps p{margin:2px 0 0;font-size:.92rem;color:var(--light)}
/* areas */
.areas{display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:18px}
.area{border-radius:14px;overflow:hidden;border:1px solid var(--line);background:#fff}
.area img{width:100%;aspect-ratio:16/9;object-fit:cover;display:block}
.area div{padding:18px 20px}
.area p{font-size:.9rem;margin:6px 0 0;color:var(--light)}
/* proof */
.proof{display:grid;grid-template-columns:minmax(280px,1.1fr) 1fr;gap:26px;align-items:center;max-width:860px;margin:0 auto}
.proof img{width:100%;border-radius:10px;border:1px solid var(--line);box-shadow:0 12px 30px rgba(10,56,82,.12)}
.proof ul{padding-left:18px;font-size:.92rem}
@media(max-width:700px){.proof{grid-template-columns:1fr}}
/* faq */
.faq{max-width:780px;margin:0 auto}
.faq details{background:#fff;border:1px solid var(--line);border-radius:10px;margin-bottom:10px;padding:0 20px}
.faq summary{cursor:pointer;font-weight:700;color:var(--navy);padding:15px 0;font-size:.96rem;list-style-position:outside}
.faq details p{margin:0 0 16px;font-size:.92rem;color:var(--text)}
/* form */
.form-card{max-width:640px;margin:0 auto;background:#fff;border:1px solid var(--line);border-radius:14px;padding:30px 28px;box-shadow:0 10px 30px rgba(10,56,82,.07)}
.form-card label{display:block;font-family:-apple-system,'Segoe UI',sans-serif;font-size:.85rem;font-weight:600;color:var(--navy);margin-bottom:14px}
.form-card input,.form-card select,.form-card textarea{width:100%;margin-top:5px;padding:11px 12px;border:1px solid #c9d6de;border-radius:8px;font-size:.95rem;font-family:inherit;background:#fff;color:var(--text)}
.form-card textarea{min-height:110px;resize:vertical}
.req{color:#b3261e;font-size:.72rem;margin-left:4px}
.consent{display:flex;gap:8px;align-items:flex-start;font-weight:400 !important;font-size:.8rem !important;color:var(--light)}
.consent input{width:16px;height:16px;margin-top:2px}
.submit{width:100%;background:var(--ocean);color:#fff;border:none;border-radius:999px;padding:15px;font-size:1rem;font-weight:700;cursor:pointer;font-family:inherit}
.submit:disabled{opacity:.6}
#f-msg.ok{color:#1b7a4b;font-weight:700}
#f-msg.ng{color:#b3261e;font-weight:700}
.hp{position:absolute!important;left:-9999px!important;top:-9999px!important;height:1px;width:1px;overflow:hidden}
/* footer */
footer{background:var(--navy);color:#cfdde6;padding:36px 22px;font-size:.82rem;line-height:2}
footer a{color:#fff}
@media(max-width:640px){section{padding:44px 0}.hd-right .hd-cta{display:none}}
</style>
</head>
<body>

<header class="hd">
  <div class="hd-in">
    <a class="hd-logo" href="/en/">En Co., Ltd.<small>Sea Burial in Kagoshima &amp; Fukuoka, Japan</small></a>
    <div class="hd-right sans">
      <a class="hd-jp" href="/">日本語</a>
      <a class="hd-cta" href="#contact">Ask in English</a>
    </div>
  </div>
</header>

<div class="hero">
  <h1>Sea Burial in Southern Japan</h1>
  <p>For those whose story includes Japan — we return ashes to the sea off Kagoshima and Fukuoka, with care, documentation, and English support. You can attend in person, or we can perform the ceremony on your behalf and send full proof to you overseas.</p>
  <div class="btns sans">
    <a class="btn btn-w" href="#contact">Ask us in English — free, no obligation</a>
    <a class="btn btn-o" href="#plans">See plans &amp; pricing</a>
  </div>
</div>

<div class="strip sans"><div class="strip-in">
  <span><b>3,800+</b> ceremonies</span>
  <span>Since <b>2013</b></span>
  <span><b>★4.9</b> Google rating</span>
  <span>Member, <b>Japan Sea Scattering Association</b></span>
  <span>English support by <b>email</b></span>
</div></div>

<section>
  <div class="wrap">
    <div class="cite">
      <b>Sea burial in Japan — the essentials.</b> Sea burial (<i>kaiyōsō</i>) is legal in Japan when performed respectfully: ashes must first be ground to a fine powder (under 2&nbsp;mm) and scattered well away from shores, fishing grounds and shipping lanes, following the 2021 guidelines of Japan's Ministry of Health, Labour and Welfare. En Co., Ltd. — a member of the Japan Sea Scattering Association based in Kagoshima and Fukuoka — has performed more than 3,800 ceremonies since 2013. Unattended sea burial starts at <b>¥54,450</b> (approx. US$370) and includes powdering of the ashes, the ceremony, a GPS-referenced certificate, and photographs. Families overseas can arrange everything by email in English.
    </div>
  </div>
</section>

<section class="alt" id="plans">
  <div class="wrap">
    <h2>Plans &amp; Pricing</h2>
    <p class="sub">All prices include tax. A written quote is provided before you commit — no additional charges after the quote.</p>
    <div class="plans sans">
      <div class="plan hl">
        <h3>Unattended Sea Burial</h3>
        <div class="price">¥54,450</div>
        <div class="usd">approx. US$370 · limited-time price (regular ¥66,000)</div>
        <ul>
          <li>Our staff perform the ceremony with flowers and care</li>
          <li>Powdering of the ashes included (with environmental-safety testing)</li>
          <li>GPS-referenced certificate + photos of the ceremony</li>
          <li>Ideal if you cannot travel to Japan</li>
        </ul>
      </div>
      <div class="plan">
        <h3>Group Ceremony</h3>
        <div class="price">¥148,500〜</div>
        <div class="usd">approx. US$1,000 · shared vessel, you attend</div>
        <ul>
          <li>Attend the ceremony together with other families</li>
          <li>Held regularly in Kinko Bay (Kagoshima) and Hakata Bay (Fukuoka)</li>
          <li>Flower petals, water offering, and a moment of silence</li>
        </ul>
      </div>
      <div class="plan">
        <h3>Private Charter</h3>
        <div class="price">¥176,000〜</div>
        <div class="usd">approx. US$1,190 · your family only</div>
        <ul>
          <li>A vessel reserved for your family alone</li>
          <li>Flexible timing and personal touches (music, favourite drink, letters)</li>
          <li>Non-religious by default; religious elements on request</li>
        </ul>
      </div>
    </div>
    <p class="note sans">Exchange rates vary — US$ figures are a rough guide only. For families overseas we also arrange video recording, online live streaming of the ceremony, and international shipping of keepsakes: ask us for a personal quote.</p>
  </div>
</section>

<section>
  <div class="wrap">
    <h2>How It Works</h2>
    <p class="sub">Most arrangements from overseas are completed entirely by email.</p>
    <ol class="steps sans">
      <li><b>Tell us your situation</b><p>Where the ashes are now, whether you wish to attend, and your rough timing. We reply in English within 2 business days with options and a written quote.</p></li>
      <li><b>Getting the ashes to us</b><p>If the ashes are in Japan, they can be sent to us by Japan Post (yu-pack) — we guide you or a relative through packing. If you are bringing them from abroad, we advise on documents and airline rules for your route.</p></li>
      <li><b>Preparation</b><p>We respectfully grind the ashes to powder (a legal requirement for scattering in Japan), test and neutralise hexavalent chromium for environmental safety, and remove any non-organic material.</p></li>
      <li><b>The ceremony</b><p>At sea off Kagoshima or Fukuoka, with flower petals and a moment of silence — attended by you, or performed carefully on your behalf.</p></li>
      <li><b>Your proof and keepsakes</b><p>You receive a GPS-referenced certificate and photographs (video on request). If you wish, a small portion of the ashes can be kept aside and returned to you in a mini urn or memorial jewellery.</p></li>
    </ol>
  </div>
</section>

<section class="alt">
  <div class="wrap">
    <h2>Where the Ceremonies Take Place</h2>
    <p class="sub">Two home ports in southern Japan — both with deep connections to families around the world.</p>
    <div class="areas sans">
      <div class="area">
        <img src="/kaiyou-sou/images/ks-fukan.jpg?v=<?= h(asset_ver()) ?>" alt="Kinko Bay, Kagoshima, with Sakurajima volcano in view" loading="lazy">
        <div><h3>Kagoshima — Kinko Bay</h3>
        <p>Ceremonies held within sight of Sakurajima, the bay's iconic volcano. Kagoshima was the home port for generations of Japanese families who emigrated to Hawaii, the Americas and beyond — for many, this is the sea of their ancestors.</p></div>
      </div>
      <div class="area">
        <img src="/assets/img/plan-goudou.jpg?v=<?= h(asset_ver()) ?>" alt="Memorial vessel leaving Hakata Bay, Fukuoka" loading="lazy">
        <div><h3>Fukuoka — Hakata Bay</h3>
        <p>The gateway to Kyushu, minutes from Fukuoka Airport — practical if you wish to attend in person. A familiar sea for those who studied, worked, or were stationed in northern Kyushu.</p></div>
      </div>
    </div>
  </div>
</section>

<section>
  <div class="wrap">
    <h2>Proof You Can Hold</h2>
    <p class="sub">Especially for families who cannot attend, the proof matters. Here is what you receive.</p>
    <div class="proof sans">
      <img src="/assets/img/en/cert-sample.jpg?v=<?= h(asset_ver()) ?>" alt="Sample certificate of sea burial with GPS coordinates, issued in English" loading="lazy">
      <ul>
        <li><b>Certificate of Sea Burial</b> — date, sea area, and GPS coordinates, issued in English on request</li>
        <li><b>Photographs</b> of the ceremony (video recording available)</li>
        <li><b>Online live streaming</b> of the ceremony, on request</li>
        <li><b>Keepsake option</b> — a small portion of the ashes returned in a mini urn or set into memorial jewellery, shipped internationally</li>
      </ul>
    </div>
  </div>
</section>

<section class="alt" id="faq">
  <div class="wrap">
    <h2>Questions Families Ask Us</h2>
    <p class="sub">If your question is not here, just ask — that is what the form below is for.</p>
    <div class="faq sans">
      <details><summary>Is sea burial legal in Japan?</summary><p>Yes. Japan's Ministry of Justice has stated that scattering ashes is not illegal when done with respect and moderation, and the Ministry of Health, Labour and Welfare published national guidelines in 2021. The key requirements: ashes must be ground to powder (under 2&nbsp;mm), and scattering must take place away from shores, fishing grounds and busy sea lanes. As a member of the Japan Sea Scattering Association, we follow these guidelines on every ceremony.</p></details>
      <details><summary>Can foreigners arrange a sea burial in Japan?</summary><p>Yes. There is no nationality requirement. We regularly assist families of former residents, international couples, and relatives living overseas. All arrangements can be made by email in English.</p></details>
      <details><summary>Can you perform the ceremony if we cannot travel to Japan?</summary><p>Yes — this is our unattended (proxy) service, and it is the plan most requested by families overseas. Our staff perform the full ceremony with flowers and a moment of silence, and you receive the GPS certificate, photographs and, on request, video or a live stream.</p></details>
      <details><summary>How much does it cost?</summary><p>Unattended sea burial is ¥54,450 (approx. US$370, limited-time price). Attending a group ceremony starts at ¥148,500, and a private charter at ¥176,000. Powdering of the ashes is included. Optional extras: video, live streaming, keepsake urns and jewellery, and international shipping. We confirm the total in a written quote before you commit, and payment options for your country as part of the quote.</p></details>
      <details><summary>Can I bring cremated ashes into Japan?</summary><p>Generally yes. Cremated remains are not subject to Japan's animal or plant quarantine, and no import permit is required in typical cases. We strongly recommend carrying the death certificate and cremation certificate (in English) with the ashes, and using an urn or container that can pass through X-ray screening. Rules can change — we confirm the current requirements for your specific route as part of our free consultation.</p></details>
      <details><summary>Can ashes travel on a plane?</summary><p>Most airlines allow cremated remains in carry-on baggage, and many publish specific instructions. Requirements differ by airline and route, so we advise checking with your carrier — tell us your route and we will help you prepare.</p></details>
      <details><summary>Can we ship the ashes to you instead?</summary><p>If the ashes are already in Japan, yes — Japan Post's yu-pack service is the established way to send ashes domestically, and we provide step-by-step packing guidance. International mailing of ashes depends on the postal rules of your country and is often restricted; in most cases we recommend carrying the ashes to Japan, or consulting us about your specific country first.</p></details>
      <details><summary>What documents do you need?</summary><p>For ashes cremated in Japan: the cremation permit (火葬許可証) or equivalent. For ashes from overseas: a copy of the death certificate and cremation certificate, plus a signed authorization form (we provide the template). We will list exactly what is needed for your case in our first reply.</p></details>
      <details><summary>Do the ashes have to be powdered?</summary><p>Yes — powdering (funkotsu) is a firm rule for scattering in Japan. It is included in every plan, done by hand with care in our own facility, together with environmental-safety testing (hexavalent chromium check and neutralisation, our standard practice since 2019).</p></details>
      <details><summary>Can we keep part of the ashes?</summary><p>Yes, and many families do. Before the ceremony we can set aside any amount you wish — for a keepsake urn at home, or a small quantity (about a grain of rice) sealed inside a memorial ring or pendant. Keepsakes can be shipped internationally.</p></details>
      <details><summary>Is the ceremony religious?</summary><p>By default, no — the ceremony is a respectful, non-denominational farewell with flowers and silence. Buddhist, Christian or other religious elements can be arranged on request.</p></details>
      <details><summary>How long does it take?</summary><p>Once the ashes and documents reach us, preparation takes about one week. Unattended ceremonies are then scheduled with the next suitable sailing; attended ceremonies are booked around your travel dates. Weather can shift dates — sailings are confirmed about two days ahead.</p></details>
      <details><summary>What happens if the weather is bad?</summary><p>Safety comes first. If conditions prevent a safe sailing, the ceremony is rescheduled at no charge.</p></details>
      <details><summary>What languages do you support?</summary><p>Email consultations in English, with replies within 2 business days. Phone support is currently in Japanese only. Video calls (Zoom/Google Meet) with translation support can be arranged for complex cases.</p></details>
      <details><summary>Who are you?</summary><p>En Co., Ltd. (有限会社 縁) is a memorial services company founded in Kagoshima, with an office in Fukuoka. We were among the first to offer sea burial in Kagoshima and have performed more than 3,800 ceremonies. We hold a ★4.9 rating on Google and are a member of the Japan Sea Scattering Association. Head office: 7-7-3 Sakanoue, Kagoshima City, Japan.</p></details>
    </div>
  </div>
</section>

<section id="contact">
  <div class="wrap">
    <h2>Tell Us Your Situation</h2>
    <p class="sub">Free, in English, and no obligation. We reply within 2 business days.<br>We will never call you unsolicited or pass your details to anyone.</p>
    <div class="form-card">
      <form id="en-form">
        <div class="hp" aria-hidden="true"><label>Website<input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>
        <label>Your name <span class="req">required</span><input type="text" name="name" required autocomplete="name"></label>
        <label>Email <span class="req">required</span><input type="email" name="email" required autocomplete="email"></label>
        <label>Country of residence
          <input type="text" name="country" placeholder="e.g. United States" autocomplete="country-name">
        </label>
        <label>Where are the ashes now?
          <select name="ashes_now">
            <option value="">Please choose</option>
            <option>In Japan</option>
            <option>Outside Japan (with me / family)</option>
            <option>Not yet — planning ahead</option>
          </select>
        </label>
        <label>Preferred area
          <select name="en_area">
            <option value="">Please choose</option>
            <option>Kagoshima (Kinko Bay)</option>
            <option>Fukuoka (Hakata Bay)</option>
            <option>Either / not sure</option>
          </select>
        </label>
        <label>Would you like to attend?
          <select name="attend">
            <option value="">Please choose</option>
            <option>We would like to attend in person</option>
            <option>Unattended — please perform it on our behalf</option>
            <option>Not sure yet</option>
          </select>
        </label>
        <label>Preferred timing
          <input type="text" name="timing" placeholder="e.g. Spring 2027 / no fixed date">
        </label>
        <label>Anything you would like us to know <span class="req">required</span>
          <textarea name="message" required placeholder="Your situation in a few lines is enough — we will guide you from there."></textarea>
        </label>
        <label class="consent"><input type="checkbox" name="consent" value="1" required><span>I agree to the <a href="/privacy/" target="_blank" rel="noopener">privacy policy</a> (Japanese). Your details are used only to reply to this inquiry.</span></label>
        <button type="submit" class="submit" id="en-submit">Send — we'll reply within 2 business days</button>
        <p id="f-msg" role="status" style="text-align:center;font-size:.88rem;margin:12px 0 0"></p>
      </form>
    </div>
  </div>
</section>

<footer class="sans">
  <div class="wrap">
    <b style="color:#fff">En Co., Ltd.（有限会社 縁）</b><br>
    Head office: 7-7-3 Sakanoue, Kagoshima City, Kagoshima 891-0150, Japan<br>
    Fukuoka office: 2F, 2-1-3 Haruyoshi, Chuo-ku, Fukuoka City, Japan<br>
    Member, Japan Sea Scattering Association · <a href="/">日本語サイト（Japanese site）</a> · <a href="/privacy/">Privacy Policy</a>
  </div>
</footer>

<script>
(function () {
  var WORKER_URL = <?= json_encode(CONTACT_WORKER_URL) ?>;
  var t0 = Date.now();
  var form = document.getElementById('en-form');
  var btn = document.getElementById('en-submit');
  var msg = document.getElementById('f-msg');
  form.addEventListener('submit', async function (e) {
    e.preventDefault();
    msg.className = ''; msg.textContent = '';
    if (!form.checkValidity()) { form.reportValidity(); return; }
    var data = Object.fromEntries(new FormData(form).entries());
    data.lang = 'en';
    data.category = 'Sea Burial (English inquiry)';
    data.source = location.href;
    data.formName = 'en1150.co.jp English inquiry form';
    data.elapsedMs = Date.now() - t0;
    btn.disabled = true; btn.textContent = 'Sending…';
    try {
      var res = await fetch(WORKER_URL, { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(data) });
      if (!res.ok) throw new Error('send failed');
      msg.className = 'ok';
      msg.textContent = 'Thank you — your message has been sent. A confirmation email is on its way, and we will reply within 2 business days.';
      form.reset();
      var looksHuman = data.elapsedMs >= 5000 && !data.website && /@.+\./.test(data.email || '');
      if (typeof gtag === 'function' && looksHuman) {
        gtag('event', 'generate_lead', { form_name: 'contact_en', category: data.category, lang: 'en' });
      }
    } catch (err) {
      msg.className = 'ng';
      msg.textContent = 'Sorry, the message could not be sent. Please email us directly at info@en1150.co.jp.';
    } finally {
      btn.disabled = false; btn.textContent = "Send — we'll reply within 2 business days";
    }
  });
})();
</script>

<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Organization",
  "name": "En Co., Ltd.",
  "alternateName": ["有限会社 縁", "Yugen Kaisha En"],
  "url": "<?= h($en_canonical) ?>",
  "email": "info@en1150.co.jp",
  "telephone": "+81-99-801-3637",
  "foundingDate": "2013",
  "address": {"@type": "PostalAddress", "streetAddress": "7-7-3 Sakanoue", "addressLocality": "Kagoshima", "addressRegion": "Kagoshima", "postalCode": "891-0150", "addressCountry": "JP"},
  "areaServed": ["Kagoshima", "Fukuoka", "Kyushu", "Worldwide (arrangements by email)"],
  "availableLanguage": ["Japanese", "English"],
  "memberOf": {"@type": "Organization", "name": "Japan Sea Scattering Association"},
  "description": "Sea burial (ash scattering) services in Kagoshima and Fukuoka, Japan. Over 3,800 ceremonies since 2013. Unattended sea burial from ¥54,450 with GPS certificate, photos and video. English support by email for families overseas."
}
</script>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    {"@type": "Question", "name": "Is sea burial legal in Japan?", "acceptedAnswer": {"@type": "Answer", "text": "Yes. Japan's Ministry of Justice has stated that scattering ashes is not illegal when done with respect and moderation, and the Ministry of Health, Labour and Welfare published national guidelines in 2021. Ashes must be ground to powder under 2mm and scattered away from shores, fishing grounds and busy sea lanes. En Co., Ltd. is a member of the Japan Sea Scattering Association and follows these guidelines on every ceremony."}},
    {"@type": "Question", "name": "Can foreigners arrange a sea burial in Japan?", "acceptedAnswer": {"@type": "Answer", "text": "Yes. There is no nationality requirement. En Co., Ltd. regularly assists families of former residents of Japan, international couples, and relatives living overseas. All arrangements can be made by email in English."}},
    {"@type": "Question", "name": "Can a sea burial in Japan be performed if the family cannot travel to Japan?", "acceptedAnswer": {"@type": "Answer", "text": "Yes. En Co., Ltd. offers an unattended (proxy) sea burial from ¥54,450: staff perform the full ceremony with flowers off Kagoshima or Fukuoka, and the family receives a GPS-referenced certificate, photographs, and optionally video or a live stream."}},
    {"@type": "Question", "name": "How much does sea burial cost in Japan?", "acceptedAnswer": {"@type": "Answer", "text": "At En Co., Ltd., unattended sea burial costs ¥54,450 (approx. US$370). Attending a group ceremony starts at ¥148,500 and a private charter at ¥176,000, tax included. Powdering of the ashes is included in every plan and a written quote is provided before any commitment."}},
    {"@type": "Question", "name": "Can I bring cremated ashes into Japan?", "acceptedAnswer": {"@type": "Answer", "text": "Generally yes. Cremated remains are not subject to Japan's animal or plant quarantine and no import permit is required in typical cases. Carrying the death certificate and cremation certificate in English is strongly recommended, along with an urn that can pass X-ray screening. Rules can change, so requirements should be confirmed for the specific route."}},
    {"@type": "Question", "name": "Can we keep part of the ashes instead of scattering everything?", "acceptedAnswer": {"@type": "Answer", "text": "Yes. Before the ceremony any amount can be set aside and returned in a keepsake urn, or a small quantity sealed inside a memorial ring or pendant. Keepsakes can be shipped internationally."}}
  ]
}
</script>
</body>
</html>
