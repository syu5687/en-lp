<?php
/**
 * English service page — Sea Burial (Kaiyōsō) in Japan (Phase 2)
 * 日本語 /kaiyou-sou/ の英語対応ページ。詳しいガイド形式：合法性・セレモニー・
 * プラン・手元供養・流れ・FAQ。問い合わせは /en/#contact の英語フォームへ誘導。
 */
require_once __DIR__ . '/../../includes/config.php';
$en_canonical = SITE['url'] . '/en/sea-burial-japan/';
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sea Burial (Kaiyōsō) in Japan: Legality, Cost &amp; How It Works — En Co., Ltd.</title>
<meta name="description" content="A practical guide to sea burial (kaiyōsō) in Japan: the legal rules, what the ceremony looks like, plans from ¥54,450, keeping part of the ashes, and the step-by-step process. By En Co., Ltd., Kagoshima &amp; Fukuoka — 3,800+ ceremonies since 2013.">
<link rel="canonical" href="<?= h($en_canonical) ?>">
<?php require_once __DIR__ . '/../../includes/lang-map.php'; en_lang_tags('/en/sea-burial-japan/'); ?>
<meta property="og:title" content="Sea Burial (Kaiyōsō) in Japan: Legality, Cost &amp; How It Works">
<meta property="og:description" content="The legal rules, the ceremony, plans from ¥54,450, and the step-by-step process — a practical guide by En Co., Ltd., Kagoshima &amp; Fukuoka, Japan.">
<meta property="og:image" content="<?= h(SITE['url']) ?>/kaiyou-sou/images/ks-fukan.jpg">
<meta property="og:url" content="<?= h($en_canonical) ?>">
<meta property="og:type" content="article">
<?php require __DIR__ . '/../../includes/ga4.php'; ?>
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
.hero{background:linear-gradient(rgba(10,56,82,.55),rgba(10,56,82,.55)),url('/kaiyou-sou/images/ks-fukan.jpg?v=<?= h(asset_ver()) ?>') center/cover;color:#fff;padding:76px 22px 66px;text-align:center}
.hero .crumb{font-size:.78rem;opacity:.85;margin:0 0 18px}
.hero .crumb a{color:#fff}
.hero h1{font-size:clamp(1.6rem,4.2vw,2.4rem);margin:0 0 14px;line-height:1.4;font-weight:600;letter-spacing:.01em}
.hero p{max-width:660px;margin:0 auto 26px;font-size:1rem;opacity:.96}
.btn{display:inline-block;padding:13px 30px;border-radius:999px;text-decoration:none;font-weight:700;font-size:.95rem}
.btn-w{background:#fff;color:var(--navy)}
/* sections */
section{padding:54px 0}
section.alt{background:var(--cream)}
h2{font-size:1.5rem;color:var(--navy);text-align:center;margin:0 0 8px;line-height:1.4}
.sub{text-align:center;color:var(--light);font-size:.92rem;margin:0 0 30px}
h3{color:var(--green);font-size:1.08rem;margin:0 0 8px}
/* summary (AI-citable) */
.cite{max-width:780px;margin:0 auto;background:var(--goldbg);border:1px solid #e3d5b8;border-left:5px solid var(--gold);border-radius:0 12px 12px 0;padding:22px 26px;font-size:.98rem}
/* rules */
.rules{display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:18px;max-width:860px;margin:0 auto}
.rule{background:#fff;border:1px solid var(--line);border-radius:14px;padding:22px}
.rule .no{display:inline-grid;place-items:center;width:34px;height:34px;border-radius:50%;background:var(--sea);color:var(--green);font-weight:700;margin-bottom:10px}
.rule p{font-size:.9rem;margin:0;color:var(--light)}
/* gallery */
.gal{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:14px}
.gal figure{margin:0;background:#fff;border:1px solid var(--line);border-radius:12px;overflow:hidden}
.gal img{width:100%;aspect-ratio:3/2;object-fit:cover;display:block}
.gal figcaption{padding:10px 14px;font-size:.8rem;color:var(--light)}
/* plans table */
.ptable{width:100%;border-collapse:collapse;background:#fff;border:1px solid var(--line);border-radius:14px;overflow:hidden;font-size:.9rem}
.ptable th{background:var(--navy);color:#fff;text-align:left;padding:12px 16px;font-weight:600}
.ptable td{padding:13px 16px;border-top:1px solid var(--line);vertical-align:top}
.ptable td:first-child{font-weight:700;color:var(--navy);white-space:nowrap}
.ptable .pp{color:var(--navy);font-weight:700;white-space:nowrap}
.tscroll{overflow-x:auto;max-width:860px;margin:0 auto}
@media(max-width:640px){
  .ptable thead{display:none}
  .ptable,.ptable tbody,.ptable tr,.ptable td{display:block;width:100%}
  .ptable tr{border-top:1px solid var(--line);padding:14px 16px}
  .ptable tr:first-child{border-top:none}
  .ptable td{padding:0;border:none}
  .ptable td:first-child{font-size:1rem;margin-bottom:2px;white-space:normal}
  .ptable td.pp{margin-bottom:6px;white-space:normal}
}
.note{font-size:.78rem;color:var(--light);text-align:center;margin-top:14px}
/* keepsake */
.keep{display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:18px;max-width:860px;margin:0 auto}
.keep .k{background:#fff;border:1px solid var(--line);border-radius:14px;overflow:hidden}
.keep img{width:100%;aspect-ratio:16/10;object-fit:cover;display:block}
.keep div{padding:16px 20px}
.keep p{font-size:.88rem;margin:6px 0 0;color:var(--light)}
/* steps */
.steps{counter-reset:s;max-width:720px;margin:0 auto;padding:0;list-style:none}
.steps li{position:relative;padding:0 0 26px 58px}
.steps li::before{counter-increment:s;content:counter(s);position:absolute;left:0;top:0;width:38px;height:38px;border-radius:50%;background:var(--ocean);color:#fff;display:grid;place-items:center;font-weight:700;font-family:inherit}
.steps li::after{content:'';position:absolute;left:18.5px;top:42px;bottom:4px;width:1.5px;background:var(--line)}
.steps li:last-child::after{display:none}
.steps b{color:var(--navy)}
.steps p{margin:2px 0 0;font-size:.92rem;color:var(--light)}
/* faq */
.faq{max-width:780px;margin:0 auto}
.faq details{background:#fff;border:1px solid var(--line);border-radius:10px;margin-bottom:10px;padding:0 20px}
.faq summary{cursor:pointer;font-weight:700;color:var(--navy);padding:15px 0;font-size:.96rem;list-style-position:outside}
.faq details p{margin:0 0 16px;font-size:.92rem;color:var(--text)}
/* cta */
.cta{background:var(--navy);color:#fff;text-align:center;padding:60px 22px}
.cta h2{color:#fff}
.cta p{max-width:560px;margin:0 auto 24px;font-size:.95rem;opacity:.94}
/* footer */
footer{background:var(--navy);color:#cfdde6;padding:36px 22px;font-size:.82rem;line-height:2;border-top:1px solid rgba(255,255,255,.15)}
footer a{color:#fff}
@media(max-width:640px){section{padding:42px 0}.hd-right .hd-cta{display:none}}
</style>
</head>
<body>

<header class="hd">
  <div class="hd-in">
    <a class="hd-logo" href="/en/">En Co., Ltd.<small>Sea Burial in Kagoshima &amp; Fukuoka, Japan</small></a>
    <div class="hd-right sans">
      <a class="hd-jp" href="/kaiyou-sou/">日本語</a>
      <a class="hd-cta" href="/en/#contact">Ask in English</a>
    </div>
  </div>
</header>

<div class="hero">
  <p class="crumb sans"><a href="/en/">Sea Burial in Japan</a> &rsaquo; Complete Guide</p>
  <h1>Sea Burial (Kaiyōsō) in Japan:<br>Legality, Cost &amp; How It Works</h1>
  <p>Everything families ask us before entrusting a loved one to the sea — the legal rules, what actually happens aboard the vessel, what it costs, and how to arrange it from Japan or from overseas.</p>
  <a class="btn btn-w sans" href="/en/#contact">Ask us in English — free consultation</a>
</div>

<section>
  <div class="wrap">
    <div class="cite">
      <b>In short.</b> Sea burial (<i>kaiyōsō</i>, 海洋葬) is practiced legally across Japan under national guidelines published by the Ministry of Health, Labour and Welfare in 2021: ashes must first be ground to a fine powder (under 2&nbsp;mm) and scattered away from shores, fishing grounds and shipping lanes. En Co., Ltd. — a member of the Japan Sea Scattering Association — has performed more than 3,800 ceremonies off Kagoshima and Fukuoka since 2013. An unattended ceremony costs <b>¥54,450</b> tax included; attended ceremonies start at ¥148,500 (shared vessel) or ¥176,000 (private charter). Part of the ashes can always be kept as a keepsake.
    </div>
  </div>
</section>

<section class="alt">
  <div class="wrap">
    <h2>Is Sea Burial Legal in Japan?</h2>
    <p class="sub">Yes — when four rules are respected. We follow all of them on every sailing.</p>
    <div class="rules sans">
      <div class="rule"><span class="no">1</span><h3>Powder, not fragments</h3><p>Ashes must be ground to a fine powder under 2&nbsp;mm before scattering. This is the firmest rule in Japan, and powdering is included in every one of our plans — done by hand in our own facility.</p></div>
      <div class="rule"><span class="no">2</span><h3>Away from people's daily waters</h3><p>Scattering takes place offshore, away from beaches, fishing grounds, aquaculture and busy sea lanes, at sea areas agreed with local operators.</p></div>
      <div class="rule"><span class="no">3</span><h3>With respect and moderation</h3><p>Japan's Ministry of Justice holds that scattering ashes is not illegal when performed as a funeral rite with restraint. Flowers are offered as loose petals only — no wreaths, cellophane or anything that does not return to nature.</p></div>
      <div class="rule"><span class="no">4</span><h3>Under association rules</h3><p>As a member of the Japan Sea Scattering Association, we operate under its guidelines and the 2021 national guidelines of the Ministry of Health, Labour and Welfare — including weather standards, safe vessel operation and environmental testing of the ashes.</p></div>
    </div>
  </div>
</section>

<section>
  <div class="wrap">
    <h2>What the Ceremony Looks Like</h2>
    <p class="sub">A quiet, non-denominational farewell — flowers, water, and the ship's bell. Religious elements can be arranged on request.</p>
    <div class="gal sans">
      <figure><img src="/kaiyou-sou/images/ks-ceremony.jpg?v=<?= h(asset_ver()) ?>" alt="Ceremony set with flowers and sake prepared on deck" loading="lazy"><figcaption>The ceremony set prepared on deck: flowers, water and sake.</figcaption></figure>
      <figure><img src="/kaiyou-sou/images/ks-funkotsu-maku.jpg?v=<?= h(asset_ver()) ?>" alt="Powdered ashes being returned to the sea" loading="lazy"><figcaption>The powdered ashes are returned gently to the sea.</figcaption></figure>
      <figure><img src="/kaiyou-sou/images/ks-maku.jpg?v=<?= h(asset_ver()) ?>" alt="Flower petals scattered over the water" loading="lazy"><figcaption>Petals are scattered over the same waters.</figcaption></figure>
      <figure><img src="/kaiyou-sou/images/ks-kensui.jpg?v=<?= h(asset_ver()) ?>" alt="Offering of water after the scattering" loading="lazy"><figcaption>An offering of water follows the scattering.</figcaption></figure>
      <figure><img src="/kaiyou-sou/images/ks-bell.jpg?v=<?= h(asset_ver()) ?>" alt="The ship's bell rung in memory of the departed" loading="lazy"><figcaption>The ship's bell is rung in memory of the departed.</figcaption></figure>
      <figure><img src="/kaiyou-sou/images/ks-bow.jpg?v=<?= h(asset_ver()) ?>" alt="Staff bowing towards the sea after the ceremony" loading="lazy"><figcaption>A final bow — the vessel circles the site before returning.</figcaption></figure>
    </div>
    <p class="note sans">Every ceremony is photographed, and the exact position is recorded by GPS for your certificate. For unattended ceremonies, this is how you are there without being there.</p>
  </div>
</section>

<section class="alt" id="plans">
  <div class="wrap">
    <h2>Plans &amp; Pricing</h2>
    <p class="sub">All prices include tax and the powdering of the ashes. The total is fixed in a written quote — no charges are added afterwards.</p>
    <div class="tscroll sans">
      <table class="ptable">
        <thead><tr><th>Plan</th><th>Price (tax incl.)</th><th>What it is</th></tr></thead>
        <tbody>
        <tr><td>Unattended Sea Burial</td><td class="pp">¥54,450<br><small style="font-weight:400;color:var(--light)">limited-time (regular ¥66,000)</small></td><td>Our staff perform the full ceremony on your behalf. The plan most requested by families overseas — you receive the GPS certificate, photos, and optionally video or a live stream.</td></tr>
        <tr><td>Group Ceremony</td><td class="pp">¥148,500〜</td><td>You attend, sharing the vessel with other families. Held regularly in Kinko Bay (Kagoshima) and Hakata Bay (Fukuoka).</td></tr>
        <tr><td>Private Charter</td><td class="pp">¥176,000〜</td><td>A vessel reserved for your family alone, with flexible timing and personal touches — music, a favourite drink, letters.</td></tr>
        <tr><td>Pet Sea Burial</td><td class="pp">ask us</td><td>A dedicated unattended ceremony for pets, held twice a year in Kinko Bay, Kagoshima.</td></tr>
        <tr><td>Memorial Cruise</td><td class="pp">¥176,000</td><td>A return voyage to the sea area of a past ceremony — many families sail out again on anniversaries.</td></tr>
        <tr><td>Letter to Heaven</td><td class="pp">free</td><td>Write a letter to the one you lost; we carry your words to the waters where the ceremony took place.</td></tr>
        </tbody>
      </table>
    </div>
    <p class="note sans">US$ guide: ¥54,450 ≈ US$370. Exchange rates vary — we confirm payment options for your country in the quote. <a href="/en/#plans">Overseas arrangements are explained on our English top page.</a></p>
  </div>
</section>

<section>
  <div class="wrap">
    <h2>You Do Not Have to Scatter Everything</h2>
    <p class="sub">Scattered ashes cannot be recovered — so many families keep a small part. We prepare this before the ceremony.</p>
    <div class="keep sans">
      <div class="k"><img src="/kaiyou-sou/images/ks-bk-kaiyou.jpg?v=<?= h(asset_ver()) ?>" alt="All ashes returned to the sea" loading="lazy"><div><h3>All to the sea</h3><p>The complete return — everything is scattered at the ceremony. A clean farewell chosen by those who feel the sea is where their loved one belongs.</p></div></div>
      <div class="k"><img src="/kaiyou-sou/images/ks-bk-temoto.jpg?v=<?= h(asset_ver()) ?>" alt="A palm-sized mini urn kept at home" loading="lazy"><div><h3>Most to the sea, some at home</h3><p>A palm-sized mini urn — egg-shaped ceramic or glass — that sits on a shelf or in the living room. No home altar needed. Dividing into an urn you bring is ¥5,500 (tax incl.).</p></div></div>
      <div class="k"><img src="/kaiyou-sou/images/ks-bk-jewelry.jpg?v=<?= h(asset_ver()) ?>" alt="Memorial jewellery holding a small amount of ashes" loading="lazy"><div><h3>A trace kept close</h3><p>A quantity about the size of a grain of rice, sealed inside a memorial ring or pendant — carried with you, anywhere in the world. Keepsakes ship internationally.</p></div></div>
    </div>
  </div>
</section>

<section class="alt">
  <div class="wrap">
    <h2>How to Arrange a Sea Burial</h2>
    <p class="sub">Six steps from first email to certificate. From overseas, everything can be done in English by email.</p>
    <ol class="steps sans">
      <li><b>Free consultation</b><p>Tell us your situation by email or through <a href="/en/#contact">our English form</a> — "just asking" is welcome. Consultation and quotes are free, and we reply within 2 business days.</p></li>
      <li><b>Plan and written quote</b><p>We propose the plan that fits your wishes and confirm the total in writing. Nothing is added to the quoted amount afterwards.</p></li>
      <li><b>The ashes reach us</b><p>Bring them in person, send them within Japan by Japan Post (yu-pack) with our packing guidance, or ask a relative in Japan to do so. Travelling from abroad, we advise on documents and airline rules for your route.</p></li>
      <li><b>Powdering</b><p>We grind the ashes to a fine powder as the rules require — by hand, with environmental-safety testing (hexavalent chromium check and neutralisation). If you wish to be present for this, ask us.</p></li>
      <li><b>The ceremony</b><p>Flowers, an offering of water, the ship's bell — attended by you, or performed carefully on your behalf. The ceremony is photographed for you.</p></li>
      <li><b>Certificate and afterwards</b><p>You receive a certificate with the date and GPS coordinates (English version on request), with photos. Later, a memorial cruise can take you back to the same waters.</p></li>
    </ol>
  </div>
</section>

<section id="faq">
  <div class="wrap">
    <h2>Frequently Asked Questions</h2>
    <p class="sub">More questions — including bringing ashes into Japan — are answered on <a href="/en/#faq">our English top page</a>.</p>
    <div class="faq sans">
      <details><summary>How do you decide the date? What if the weather turns?</summary><p>Attended ceremonies are booked around your travel dates; unattended ceremonies join the next suitable sailing. Sailings are confirmed about two days ahead based on sea conditions, and if the weather prevents a safe departure, the ceremony is rescheduled at no charge. Safety standards follow the Japan Sea Scattering Association guidelines.</p></details>
      <details><summary>Where exactly are the ashes scattered?</summary><p>Off Kagoshima, ceremonies take place in Kinko Bay within sight of the volcano Sakurajima; off Fukuoka, in the waters beyond Hakata Bay. Both are designated sea areas away from shores, fishing grounds and sea lanes. The exact position of your ceremony is recorded by GPS and printed on the certificate.</p></details>
      <details><summary>Can the certificate be issued in English?</summary><p>Yes. The certificate of sea burial — date, sea area and GPS coordinates — can be issued in English on request, at no extra charge. Overseas families often use it as the family's permanent record of the farewell.</p></details>
      <details><summary>Can we watch the ceremony from overseas?</summary><p>Yes. For unattended ceremonies we can arrange video recording or a live stream (Zoom or similar), so family members anywhere in the world can be present at the moment of the farewell. Ask for this when you request your quote.</p></details>
      <details><summary>Is a grave or family altar still needed afterwards?</summary><p>No — after a sea burial there is nothing that must be maintained, and no ongoing fees. This is one reason families closing a family grave (hakajimai) choose it. Those who want a focal point for remembrance often keep a mini urn or memorial jewellery, or join our memorial cruise on anniversaries.</p></details>
      <details><summary>Can you also handle closing a family grave in Japan?</summary><p>Yes. We handle grave closure (hakajimai) across Kyushu — removal of the stone, retrieval of the remains, the paperwork, then powdering and sea burial — as one service. If your family grave is in Japan and you live overseas, we can manage the whole sequence with a relative in Japan or directly with you by email.</p></details>
    </div>
  </div>
</section>

<div class="cta">
  <h2>Tell Us Your Situation</h2>
  <p class="sans">Free, in English, no obligation — we reply within 2 business days. The form takes about two minutes.</p>
  <a class="btn btn-w sans" href="/en/#contact">Open the English inquiry form</a>
</div>

<footer class="sans">
  <div class="wrap">
    <b style="color:#fff">En Co., Ltd.（有限会社 縁）</b><br>
    Head office: 7-7-3 Sakanoue, Kagoshima City, Kagoshima 891-0150, Japan<br>
    Fukuoka office: 2F, 2-1-3 Haruyoshi, Chuo-ku, Fukuoka City, Japan<br>
    Member, Japan Sea Scattering Association · <a href="/en/">English top page</a> · <a href="/kaiyou-sou/">日本語（このページの日本語版）</a> · <a href="/privacy/">Privacy Policy</a>
  </div>
</footer>

<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    {"@type": "ListItem", "position": 1, "name": "Sea Burial in Japan", "item": "<?= h(SITE['url']) ?>/en/"},
    {"@type": "ListItem", "position": 2, "name": "Legality, Cost & How It Works", "item": "<?= h($en_canonical) ?>"}
  ]
}
</script>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Service",
  "name": "Sea Burial (Ash Scattering) in Japan",
  "serviceType": "Sea burial / ash scattering ceremony",
  "provider": {"@type": "Organization", "name": "En Co., Ltd.", "url": "<?= h(SITE['url']) ?>/en/", "email": "info@en1150.co.jp", "memberOf": {"@type": "Organization", "name": "Japan Sea Scattering Association"}},
  "areaServed": ["Kagoshima", "Fukuoka", "Kyushu", "Worldwide (arrangements by email)"],
  "availableLanguage": ["Japanese", "English"],
  "offers": [
    {"@type": "Offer", "name": "Unattended Sea Burial", "price": "54450", "priceCurrency": "JPY"},
    {"@type": "Offer", "name": "Group Ceremony", "price": "148500", "priceCurrency": "JPY"},
    {"@type": "Offer", "name": "Private Charter", "price": "176000", "priceCurrency": "JPY"}
  ],
  "url": "<?= h($en_canonical) ?>"
}
</script>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    {"@type": "Question", "name": "Where exactly are ashes scattered in a Japanese sea burial?", "acceptedAnswer": {"@type": "Answer", "text": "En Co., Ltd. scatters ashes in designated sea areas off Kagoshima (Kinko Bay, within sight of Sakurajima) and Fukuoka (beyond Hakata Bay), away from shores, fishing grounds and sea lanes. The exact position of each ceremony is recorded by GPS and printed on the certificate."}},
    {"@type": "Question", "name": "Can a sea burial certificate be issued in English?", "acceptedAnswer": {"@type": "Answer", "text": "Yes. The certificate of sea burial — showing the date, sea area and GPS coordinates — can be issued in English on request at no extra charge."}},
    {"@type": "Question", "name": "What happens if the weather is bad on the day of a sea burial?", "acceptedAnswer": {"@type": "Answer", "text": "Sailings are confirmed about two days in advance based on sea conditions. If the weather prevents a safe departure, the ceremony is rescheduled at no charge, following the safety standards of the Japan Sea Scattering Association."}},
    {"@type": "Question", "name": "Is a grave still needed after a sea burial in Japan?", "acceptedAnswer": {"@type": "Answer", "text": "No. After a sea burial there is nothing that must be maintained and no ongoing fees. Families who want a focal point for remembrance often keep part of the ashes in a mini urn or memorial jewellery, or return to the same waters on a memorial cruise."}}
  ]
}
</script>
</body>
</html>
