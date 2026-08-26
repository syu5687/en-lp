<?php
require_once __DIR__ . '/../includes/config.php';
$page_title     = '福岡の海洋散骨・粉骨・お墓じまい｜有限会社 縁 福岡営業所';
$page_desc      = '福岡で海洋散骨・粉骨・お墓じまい・生前契約のご相談なら、有限会社 縁 福岡営業所（福岡市中央区春吉）へ。博多湾など福岡の海域での散骨に対応。鹿児島・福岡を中心に全国3,800件以上の実績、日本海洋散骨協会加盟。ご相談・お見積り無料。';
$page_canonical = SITE['url'] . '/fukuoka/';
$page_hero_image = '/assets/img/hero-kaiyou-sou.jpg';
require __DIR__ . '/../includes/head.php';
$FUK = SITE['fukuoka'];
$FUK_MAP = 'https://maps.google.com/?cid=1235913108976072113';
$FUK_REVIEW = 'https://g.page/r/CbF1xKls2CYREBM/review';
?>
<body>
<?php require __DIR__ . '/../includes/header.php'; ?>
<section class="page-hero">
  <h1>福岡の海洋散骨・粉骨・お墓じまい</h1>
  <p>有限会社 縁 福岡営業所（福岡市中央区春吉）</p>
</section>
<nav class="breadcrumb"><a href="/">ホーム</a> ＞ 福岡営業所</nav>

<main>
  <!-- リード -->
  <section class="section">
    <div class="container" style="max-width:820px;text-align:center">
      <p class="lead" style="line-height:2.1">「海に還りたい」という想いに、福岡でもお応えします。<br>
      有限会社 縁は<strong>福岡営業所（福岡市中央区春吉）</strong>を拠点に、<br class="pc-only">
      海洋散骨・粉骨・お墓じまい・生前契約のご相談を承っています。</p>
      <img src="/fukuoka/images/fk-port.jpg?v=<?= h(asset_ver()) ?>" alt="福岡の港に停泊する海洋散骨のクルーズ船" width="1600" height="1067" loading="lazy"
           style="width:100%;aspect-ratio:16/9;object-fit:cover;border-radius:16px;box-shadow:0 10px 30px rgba(0,0,0,.12);margin-top:28px">
      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:14px;margin-top:30px">
        <div class="card" style="text-align:center"><p style="font-size:1.7rem;font-weight:700;color:var(--green)">3,800<span style="font-size:.9rem">件以上</span></p><p style="font-size:.85rem;color:var(--text-light)">鹿児島・福岡を中心に<br>全国の対応実績</p></div>
        <div class="card" style="text-align:center"><p style="font-size:1.7rem;font-weight:700;color:var(--green)">10年<span style="font-size:.9rem">以上</span></p><p style="font-size:.85rem;color:var(--text-light)">海洋葬の実績</p></div>
        <div class="card" style="text-align:center"><p style="font-size:1.7rem;font-weight:700;color:#f4b400">★4.9</p><p style="font-size:.85rem;color:var(--text-light)">Google口コミ評価<br>（本社プロフィール）</p></div>
        <div class="card" style="text-align:center"><p style="font-size:1.1rem;font-weight:700;color:var(--green);line-height:1.5;padding-top:8px">協会加盟</p><p style="font-size:.85rem;color:var(--text-light)">日本海洋散骨協会の<br>加盟事業者</p></div>
      </div>
    </div>
  </section>

  <!-- こんなお悩みに -->
  <section class="section" style="background:var(--cream)">
    <div class="container" style="max-width:820px">
      <h2 style="text-align:center;margin-bottom:26px">福岡で、こんなお悩みはありませんか？</h2>
      <ul style="list-style:none;display:grid;gap:12px;padding:0;max-width:680px;margin:0 auto">
        <li style="padding:14px 18px;background:#fff;border-radius:10px;border-left:4px solid var(--green)">故人の「海に散骨してほしい」という希望を、福岡の海で叶えたい</li>
        <li style="padding:14px 18px;background:#fff;border-radius:10px;border-left:4px solid var(--green)">お墓を継ぐ人がおらず、お墓じまいと今後の供養をまとめて相談したい</li>
        <li style="padding:14px 18px;background:#fff;border-radius:10px;border-left:4px solid var(--green)">ご遺骨の保管に困っている。粉骨してコンパクトにしたい</li>
        <li style="padding:14px 18px;background:#fff;border-radius:10px;border-left:4px solid var(--green)">自分の海洋散骨を、元気なうちに生前契約で決めておきたい</li>
      </ul>
      <p style="text-align:center;margin-top:22px;color:var(--text-light);font-size:.95rem">どのお悩みも、福岡営業所で対面のご相談が可能です。ご相談・お見積りは無料です。</p>
    </div>
  </section>

  <!-- 福岡でできること -->
  <section class="section">
    <div class="container" style="max-width:960px">
      <h2 style="text-align:center;margin-bottom:30px">福岡営業所でできること</h2>
      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:18px">
        <a class="card" href="/kaiyou-sou/" style="display:block">
          <h3 style="color:var(--green);margin-bottom:8px">海洋散骨（海洋葬）</h3>
          <p style="font-size:.92rem">博多湾など福岡の海域での散骨に対応。チャーター・合同・委託（立ち会い不要）の3プラン。緯度・経度入りの散骨証明書を発行します。</p>
        </a>
        <a class="card" href="/seizen/" style="display:block">
          <h3 style="color:var(--green);margin-bottom:8px">海洋散骨 生前契約</h3>
          <p style="font-size:.92rem">「海洋散骨をしたい」という想いを生前に契約して託せます。テレビでも紹介された、福岡対応のサービスです。</p>
        </a>
        <a class="card" href="/powder-cleaning/" style="display:block">
          <h3 style="color:var(--green);margin-bottom:8px">粉骨・洗骨</h3>
          <p style="font-size:.92rem">ご遺骨のパウダー化（24,200円〜）・クリーニング。お持ち込みのご相談のほか、郵送でもご利用いただけます。</p>
        </a>
        <a class="card" href="/grave/" style="display:block">
          <h3 style="color:var(--green);margin-bottom:8px">お墓じまい</h3>
          <p style="font-size:.92rem">撤去から納骨まで一括対応。改葬の行政手続きの代行も承ります。まずは現状をお聞かせください。</p>
        </a>
        <a class="card" href="/pet-kaiyou-sou/" style="display:block">
          <h3 style="color:var(--green);margin-bottom:8px">ペット供養</h3>
          <p style="font-size:.92rem">大切な家族の一員の粉骨・海洋散骨・手元供養。福岡からの郵送・ご相談に対応しています。</p>
        </a>
        <a class="card" href="/flow/" style="display:block">
          <h3 style="color:var(--green);margin-bottom:8px">お申込みの流れ</h3>
          <p style="font-size:.92rem">ご相談→お見積り（無料）→お申し込み→お預かり→施行→アフターサポートの6ステップをご案内します。</p>
        </a>
      </div>
      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:14px;margin-top:30px">
        <img src="/fukuoka/images/fk-ceremony.jpg?v=<?= h(asset_ver()) ?>" alt="船上に用意された献花と献酒のセレモニーセット" width="900" height="600" loading="lazy" style="width:100%;aspect-ratio:3/2;object-fit:cover;border-radius:12px;box-shadow:0 6px 18px rgba(0,0,0,.1)">
        <img src="/fukuoka/images/fk-petals.jpg?v=<?= h(asset_ver()) ?>" alt="海へ花びらを手向ける散骨セレモニーの様子" width="900" height="600" loading="lazy" style="width:100%;aspect-ratio:3/2;object-fit:cover;border-radius:12px;box-shadow:0 6px 18px rgba(0,0,0,.1)">
        <img src="/fukuoka/images/fk-kensui.jpg?v=<?= h(asset_ver()) ?>" alt="散骨後に海へ水を手向ける献水の様子" width="900" height="600" loading="lazy" style="width:100%;aspect-ratio:3/2;object-fit:cover;border-radius:12px;box-shadow:0 6px 18px rgba(0,0,0,.1)">
      </div>
      <p style="text-align:center;margin-top:12px;font-size:.85rem;color:var(--text-light)">実際の海洋散骨セレモニーの様子</p>
    </div>
  </section>

  <!-- 実施予定日（管理画面から更新） -->
  <?php require __DIR__ . '/../includes/goudou-schedule.php'; ?>

  <!-- 選ばれる理由 -->
  <section class="section">
    <div class="container" style="max-width:860px">
      <h2 style="text-align:center;margin-bottom:30px">縁が選ばれる理由</h2>
      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:16px">
        <div class="card"><h3 style="margin-bottom:8px;color:var(--green)">追加料金のない明快な料金</h3><p style="font-size:.92rem">お見積りは無料。金額はお見積りで確定し、あとから追加料金をいただくことはありません。</p></div>
        <div class="card"><h3 style="margin-bottom:8px;color:var(--green)">急かさない・押し付けない</h3><p style="font-size:.92rem">宗教・宗派を問わず中立の立場で、良さも注意点も丁寧にご説明。「話を聞くだけ」でも歓迎です。</p></div>
        <div class="card"><h3 style="margin-bottom:8px;color:var(--green)">最初から最後まで一貫対応</h3><p style="font-size:.92rem">ご遺骨のお引き取りから粉骨・散骨・その後の供養まで、一つの窓口でお手伝いします。</p></div>
        <div class="card"><h3 style="margin-bottom:8px;color:var(--green)">散骨後も、会いに行ける</h3><p style="font-size:.92rem">散骨海域を再訪するメモリアルクルーズや、お手元供養など、「その後」のご供養もお手伝いします。</p></div>
      </div>
      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:14px;margin-top:26px">
        <img src="/fukuoka/images/fk-sea-flowers.jpg?v=<?= h(asset_ver()) ?>" alt="花びらが広がる海と散骨セレモニーを行う船上のスタッフ" width="1200" height="800" loading="lazy" style="width:100%;aspect-ratio:3/2;object-fit:cover;border-radius:12px;box-shadow:0 6px 18px rgba(0,0,0,.1)">
        <img src="/fukuoka/images/fk-sankotsu.jpg?v=<?= h(asset_ver()) ?>" alt="海へご遺骨を還す散骨の様子" width="1200" height="800" loading="lazy" style="width:100%;aspect-ratio:3/2;object-fit:cover;border-radius:12px;box-shadow:0 6px 18px rgba(0,0,0,.1)">
      </div>
      <blockquote style="margin:30px auto 0;max-width:680px;padding:18px 22px;border-left:4px solid var(--green);background:var(--cream);border-radius:0 12px 12px 0">
        <p style="font-size:.95rem;line-height:1.9">「海洋葬・散骨が明るい雰囲気でしたので、気が楽になりました。お世話になり、ありがとうございました。」</p>
        <cite style="display:block;margin-top:8px;font-style:normal;font-size:.82rem;color:var(--text-light)">—— 福岡県 60歳代 男性 Y様（粉骨・チャーター海洋葬／お墓じまい）</cite>
      </blockquote>
      <p style="text-align:center;margin-top:16px"><a href="/voice/" style="color:var(--green);font-weight:700;text-decoration:underline">お客様の声をもっと見る →</a></p>
    </div>
  </section>

  <!-- 営業所案内 -->
  <section class="section" style="background:var(--cream)">
    <div class="container" style="max-width:860px">
      <h2 style="text-align:center;margin-bottom:26px">福岡営業所のご案内</h2>
      <img src="/fukuoka/images/fk-staff.jpg?v=<?= h(asset_ver()) ?>" alt="福岡の港で笑顔で迎えるスタッフ" width="900" height="600" loading="lazy"
           style="display:block;width:100%;max-width:560px;margin:0 auto 24px;aspect-ratio:3/2;object-fit:cover;border-radius:14px;box-shadow:0 8px 24px rgba(0,0,0,.12)">
      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:22px;align-items:start">
        <div class="card">
          <p style="font-weight:700;font-size:1.05rem;margin-bottom:10px"><?= h(SITE['name']) ?> <?= h($FUK['name']) ?></p>
          <p style="font-size:.95rem;line-height:2">〒<?= h($FUK['zip']) ?><br><?= h($FUK['address']) ?></p>
          <p style="margin-top:12px"><a href="tel:<?= h(str_replace('-', '', $FUK['tel'])) ?>" style="font-size:1.5rem;font-weight:700;color:var(--green-mid);text-decoration:none"><?= h($FUK['tel']) ?></a></p>
          <p style="font-size:.85rem;color:var(--text-light)"><?= h(SITE['hours_jp']) ?>／メール・LINEは24時間受付</p>
          <p style="margin-top:14px;font-size:.9rem">
            <a href="<?= h($FUK_MAP) ?>" target="_blank" rel="noopener" style="color:var(--green);font-weight:700;text-decoration:underline">Googleマップで見る →</a><br>
            <a href="<?= h($FUK_REVIEW) ?>" target="_blank" rel="noopener" style="color:var(--green);font-weight:700;text-decoration:underline">口コミを書く →</a>
          </p>
        </div>
        <iframe src="https://maps.google.com/maps?q=<?= rawurlencode('福岡県福岡市中央区春吉2丁目1-3') ?>&z=16&output=embed" width="100%" height="300" style="border:0;border-radius:12px" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="有限会社 縁 福岡営業所"></iframe>
      </div>
      <p style="text-align:center;margin-top:18px;font-size:.9rem;color:var(--text-light)">本社（鹿児島）：〒<?= h(SITE['zip']) ?> <?= h(SITE['address']) ?>（TEL <?= h(SITE['tel']) ?>）</p>
    </div>
  </section>

  <!-- CTA -->
  <section class="section" style="background:linear-gradient(135deg,var(--green),var(--green-mid));color:#fff;text-align:center">
    <div class="container">
      <h2 style="color:#fff">福岡でのご供養、まずはお話をお聞かせください</h2>
      <p style="opacity:.92;margin-bottom:22px">「まだ決めていない」「話を聞くだけ」でも大歓迎です。ご相談・お見積りは無料です。</p>
      <p style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap">
        <a href="tel:<?= h(str_replace('-', '', $FUK['tel'])) ?>" class="btn" style="background:#fff;color:var(--green-mid)">電話で相談（<?= h($FUK['tel']) ?>）</a>
        <a href="/contact/" class="btn" style="background:#d8b46a;color:#1c2b33">メールで相談・資料請求</a>
      </p>
    </div>
  </section>
</main>

<script type="application/ld+json">
{
  "@context":"https://schema.org",
  "@type":"BreadcrumbList",
  "itemListElement":[
    {"@type":"ListItem","position":1,"name":"ホーム","item":"https://en1150.co.jp/"},
    {"@type":"ListItem","position":2,"name":"福岡営業所","item":"https://en1150.co.jp/fukuoka/"}
  ]
}
</script>
<script type="application/ld+json">
{
  "@context":"https://schema.org",
  "@type":"LocalBusiness",
  "name":"有限会社 縁 福岡営業所",
  "url":"https://en1150.co.jp/fukuoka/",
  "telephone":"+81-90-5000-4825",
  "address":{"@type":"PostalAddress","postalCode":"810-0003","addressRegion":"福岡県","addressLocality":"福岡市中央区","streetAddress":"春吉2丁目1-3 2F","addressCountry":"JP"},
  "hasMap":"https://maps.google.com/?cid=1235913108976072113",
  "parentOrganization":{"@id":"https://en1150.co.jp/#organization"},
  "areaServed":[{"@type":"State","name":"福岡県"},{"@type":"AdministrativeArea","name":"北部九州"}],
  "openingHoursSpecification":[{"@type":"OpeningHoursSpecification","dayOfWeek":["Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"],"opens":"09:00","closes":"18:00"}],
  "description":"福岡の海洋散骨・粉骨・お墓じまい・生前契約のご相談窓口。博多湾など福岡の海域での散骨に対応。"
}
</script>
<?php require __DIR__ . '/../includes/footer.php'; ?>
