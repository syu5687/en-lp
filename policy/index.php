<?php
require_once __DIR__ . '/../includes/config.php';
$page_title     = 'キャンセルポリシー｜' . SITE['name'];
$page_desc      = '有限会社 縁の海洋散骨・粉骨等に関するキャンセルポリシーです。キャンセル料の基準、天候・海況による中止時の取り扱い、日程変更、ご返金についてご案内します。';
$page_canonical = SITE['url'] . '/policy/';
require __DIR__ . '/../includes/head.php';
?>
<body>
<?php require __DIR__ . '/../includes/header.php'; ?>
<section class="page-hero">
  <h1>キャンセルポリシー</h1>
  <p>お申し込み後のキャンセル・変更について</p>
</section>
<nav class="breadcrumb"><a href="/">ホーム</a> ＞ キャンセルポリシー</nav>

<main class="section">
  <div class="container prose" style="max-width:760px">
    <p class="lead">ご事情の変化によるキャンセル・日程変更は、どうぞ遠慮なくお申し出ください。お客様に安心してお申し込みいただけるよう、キャンセル時の取り扱いを以下のとおり定めています。</p>

    <h2>キャンセル料について</h2>
    <p>海洋散骨（チャーター・合同・委託）のお申し込み後にキャンセルされる場合、実施予定日を基準として、以下のキャンセル料を申し受けます。オプション料金もお申し込み総額に含みます。</p>
    <div style="overflow-x:auto">
      <table style="width:100%;border-collapse:collapse;margin:14px 0">
        <tr style="background:var(--sea-light)">
          <th style="border:1px solid var(--border);padding:12px;text-align:left;width:50%">キャンセルのお申し出時期</th>
          <th style="border:1px solid var(--border);padding:12px;text-align:left">キャンセル料</th>
        </tr>
        <tr><td style="border:1px solid var(--border);padding:12px">実施予定日の2週間前まで</td><td style="border:1px solid var(--border);padding:12px"><strong>無料</strong></td></tr>
        <tr><td style="border:1px solid var(--border);padding:12px">実施予定日の1週間前まで</td><td style="border:1px solid var(--border);padding:12px">料金総額の30％</td></tr>
        <tr><td style="border:1px solid var(--border);padding:12px">実施予定日の2日前まで</td><td style="border:1px solid var(--border);padding:12px">料金総額の70％</td></tr>
        <tr><td style="border:1px solid var(--border);padding:12px">実施予定日の前日・当日</td><td style="border:1px solid var(--border);padding:12px">料金総額の100％</td></tr>
      </table>
    </div>

    <h2>粉骨後のキャンセルについて</h2>
    <p>すでにご遺骨の粉骨（パウダー化）を行っている場合は、パウダー状のご遺骨を丁寧に梱包してご返却し、その時点までの粉骨作業料金を申し受けます。</p>

    <h2>天候・海況による中止について</h2>
    <p>海洋散骨は、天候・海況により安全な出航ができないと判断した場合、中止となることがあります（出航可否は原則として出航2日前までにご連絡します）。この場合、<strong>キャンセル料はいただかず、日程を振り替え</strong>させていただきます。振替日程はご相談のうえ決定します。</p>

    <h2>日程の変更について</h2>
    <p>お客様のご都合による日程変更は、できる限り柔軟に対応いたします。お早めにご相談ください。直前の変更の場合は、上記キャンセル料に準じた変更料を申し受けることがあります。</p>

    <h2>ご返金について</h2>
    <p>ご返金が発生する場合は、銀行振込にてお返しします。恐れ入りますが、振込手数料およびご遺骨のご返送にかかる送料はお客様のご負担となります。</p>

    <h2>お問い合わせ</h2>
    <p>キャンセル・変更のご連絡、ご不明な点は下記までお願いいたします。</p>
    <p>
      <?= h(SITE['name']) ?><br>
      本社（鹿児島）：<a href="tel:<?= h(str_replace('-', '', SITE['tel'])) ?>" style="color:var(--green);font-weight:700"><?= h(SITE['tel']) ?></a>（<?= h(SITE['hours_jp']) ?>）<br>
      <?= h(SITE['fukuoka']['name']) ?>：<a href="tel:<?= h(str_replace('-', '', SITE['fukuoka']['tel'])) ?>" style="color:var(--green);font-weight:700"><?= h(SITE['fukuoka']['tel']) ?></a><br>
      <a href="/contact/" style="color:var(--green);font-weight:700">メールフォームはこちら</a>
    </p>
  </div>
</main>

<script type="application/ld+json">
{
  "@context":"https://schema.org",
  "@type":"BreadcrumbList",
  "itemListElement":[
    {"@type":"ListItem","position":1,"name":"ホーム","item":"https://en1150.co.jp/"},
    {"@type":"ListItem","position":2,"name":"キャンセルポリシー","item":"https://en1150.co.jp/policy/"}
  ]
}
</script>
<?php require __DIR__ . '/../includes/footer.php'; ?>
