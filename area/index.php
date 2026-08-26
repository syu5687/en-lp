<?php
require_once __DIR__ . '/../includes/config.php';
$page_title     = '対応エリア｜鹿児島・福岡・九州全域・全国郵送対応｜' . SITE['name'];
$page_desc      = '海洋散骨・粉骨・お墓じまいの対応エリアのご案内。鹿児島本社（錦江湾）と福岡営業所の2拠点で九州全域（佐賀・長崎・熊本・大分・宮崎・沖縄・離島）に対応。全国からの郵送粉骨・委託海洋散骨も承ります。' . SITE['name'] . '。';
$page_canonical = SITE['url'] . '/area/';
$page_hero_image = '/assets/img/hero-kaiyou-sou.jpg';
require __DIR__ . '/../includes/head.php';
?>
<body>
<?php require __DIR__ . '/../includes/header.php'; ?>
<section class="page-hero">
  <h1>対応エリア</h1>
  <p>鹿児島・福岡の2拠点から、九州全域・全国へ。</p>
</section>
<nav class="breadcrumb"><a href="/">ホーム</a> ＞ 対応エリア</nav>

<main class="section">
  <div class="container" style="max-width:860px">

    <p class="lead" style="text-align:center;margin-bottom:40px">
      有限会社 縁は、<strong>鹿児島本社</strong>と<strong>福岡営業所</strong>の2拠点体制で、<br class="pc-only">
      九州全域の海洋散骨・粉骨・お墓じまいに対応しています。<br>
      ご遺骨の郵送・委託散骨により、<strong>全国どこからでも</strong>ご利用いただけます。
    </p>

    <!-- 鹿児島 -->
    <div class="card" style="margin-bottom:26px">
      <h2 style="color:var(--green);margin-bottom:12px">鹿児島県（本社）</h2>
      <p>本社を置く鹿児島は、私たちのホームグラウンドです。桜島を望む<strong>錦江湾</strong>を中心に、県内全域の海洋散骨・粉骨・お墓じまい・樹木葬に対応。<strong>種子島・屋久島・奄美群島などの離島</strong>のお墓じまい・海洋散骨のご相談も承ります。</p>
      <p style="margin-top:10px;font-size:.92rem;color:var(--text-light)">本社：〒<?= h(SITE['zip']) ?> <?= h(SITE['address']) ?>（TEL <?= h(SITE['tel']) ?>）</p>
    </div>

    <!-- 福岡 -->
    <div class="card" style="margin-bottom:26px">
      <h2 style="color:var(--green);margin-bottom:12px">福岡県（福岡営業所）</h2>
      <p><strong>福岡営業所</strong>を拠点に、福岡・北部九州エリアのご相談に対応しています。福岡の海域での海洋散骨、<a href="/seizen/" style="color:var(--green);font-weight:600">海洋散骨の生前契約</a>、粉骨・お墓じまいのご相談まで。対面でのご相談をご希望の方もお気軽にお問い合わせください。</p>
      <p style="margin-top:10px;font-size:.92rem;color:var(--text-light)"><?= h(SITE['fukuoka']['name']) ?>：〒<?= h(SITE['fukuoka']['zip']) ?> <?= h(SITE['fukuoka']['address']) ?>（TEL <?= h(SITE['fukuoka']['tel']) ?>）</p>
    </div>

    <!-- 九州全域 -->
    <div class="card" style="margin-bottom:26px">
      <h2 style="color:var(--green);margin-bottom:12px">九州全域</h2>
      <p><strong>佐賀・長崎（対馬・五島含む）・熊本・大分・宮崎・沖縄</strong>まで、九州・沖縄全域に対応しています。「生まれ育った島の海に還してあげたい」——故人様ゆかりの海でのお見送りなど、ご希望の海域でのご相談も可能な限りお応えします。出張相談も承ります。</p>
    </div>

    <!-- 全国 -->
    <div class="card" style="margin-bottom:26px;border-color:var(--green)">
      <h2 style="color:var(--green);margin-bottom:12px">全国対応（郵送・委託）</h2>
      <p>遠方にお住まいの方も、ご遺骨を郵送いただくことで<strong>全国どこからでも</strong>ご利用いただけます。</p>
      <ul style="margin:12px 0 0;padding-left:1.4em;line-height:2">
        <li><a href="/powder-cleaning/" style="color:var(--green);font-weight:600">郵送粉骨</a> — ご遺骨を郵送いただき、粉骨してご返送。全国対応です。</li>
        <li><a href="/kaiyou-sou/" style="color:var(--green);font-weight:600">委託海洋散骨</a> — 立ち会い不要。私たちが心を込めて鹿児島・錦江湾でお見送りし、緯度・経度入りの散骨証明書を発行します。</li>
        <li><a href="/pet-kaiyou-sou/" style="color:var(--green);font-weight:600">ペット供養</a> — ペットのご遺骨も全国から郵送で承ります。</li>
      </ul>
      <p style="margin-top:12px;font-size:.92rem;color:var(--text-light)">東京・神奈川・大阪・愛知など、これまで全国各地からご依頼をいただいています。</p>
    </div>

    <div style="text-align:center;margin-top:36px">
      <a href="/contact/" class="btn">お住まいの地域から相談する（無料）</a>
      <p style="margin-top:14px;font-size:.9rem;color:var(--text-light)">
        お電話でも承ります：<a href="tel:<?= h(SITE['tel']) ?>" style="color:var(--green);font-weight:700"><?= h(SITE['tel']) ?></a>（<?= h(SITE['hours_jp']) ?>）
      </p>
    </div>
  </div>
</main>

<script type="application/ld+json">
{
  "@context":"https://schema.org",
  "@type":"BreadcrumbList",
  "itemListElement":[
    {"@type":"ListItem","position":1,"name":"ホーム","item":"https://en1150.co.jp/"},
    {"@type":"ListItem","position":2,"name":"対応エリア","item":"https://en1150.co.jp/area/"}
  ]
}
</script>
<script type="application/ld+json">
{
  "@context":"https://schema.org",
  "@type":"Service",
  "name":"海洋散骨・粉骨・お墓じまい（対応エリア）",
  "provider":{"@id":"https://en1150.co.jp/#organization"},
  "areaServed":[
    {"@type":"State","name":"鹿児島県"},
    {"@type":"State","name":"福岡県"},
    {"@type":"State","name":"佐賀県"},
    {"@type":"State","name":"長崎県"},
    {"@type":"State","name":"熊本県"},
    {"@type":"State","name":"大分県"},
    {"@type":"State","name":"宮崎県"},
    {"@type":"State","name":"沖縄県"},
    {"@type":"Country","name":"日本"}
  ],
  "description":"鹿児島本社と福岡営業所の2拠点で九州全域の海洋散骨・粉骨・お墓じまいに対応。全国からの郵送粉骨・委託海洋散骨も可能。"
}
</script>
<?php require __DIR__ . '/../includes/footer.php'; ?>
