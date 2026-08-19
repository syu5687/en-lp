<?php
require_once __DIR__ . '/../includes/config.php';
$page_title     = '会社案内｜' . SITE['name'];
$page_desc      = SITE['name'] . 'の会社案内・会社概要。' . SITE['tagline'] . '。代表者 堤 裕加里。';
$page_canonical = SITE['url'] . '/company/';
$page_hero_image = '/assets/img/hero-company.jpg';
require __DIR__ . '/../includes/head.php';
?>
<body>
<?php require __DIR__ . '/../includes/header.php'; ?>
<section class="page-hero">
  <h1>会社案内</h1>
  <p>有限会社 縁について</p>
</section>
<nav class="breadcrumb"><a href="/">ホーム</a> ＞ 会社案内</nav>

<main class="section">
  <div class="container" style="max-width:820px">

    <!-- 代表挨拶 -->
    <h2>代表挨拶</h2>
    <div class="card" style="margin-bottom:36px;display:flex;gap:26px;flex-wrap:wrap;align-items:flex-start">
      <img src="/assets/img/shacho.jpg" alt="代表 堤 裕加里" style="width:220px;max-width:100%;border-radius:12px;object-fit:cover;flex:none;box-shadow:0 8px 22px rgba(18,89,122,.14)">
      <div style="flex:1;min-width:260px">
        <p class="prose" style="line-height:2">何事も前向きにとらえ、一つひとつの出来事をチャンスと思い、日々取り組んでいます。皆さまお一人おひとりのお悩みにお応えできるよう、ご一緒させていただければと思います。</p>
        <p class="prose" style="line-height:2;margin-top:12px">ありがたいことに、たくさんの出会い・感謝すべきご縁をいただいています。この恵まれた環境にこたえるべく、“日進月歩”という言葉を心にきざみ、日々成長してまいります。どうぞよろしくお願いいたします。</p>
        <p style="text-align:right;margin-top:14px;color:var(--green-mid);font-weight:600">代表　堤 裕加里</p>
      </div>
    </div>

    <!-- 会社概要 -->
    <h2>会社概要</h2>
    <table class="admin-table" style="width:100%;background:#fff;margin-bottom:36px">
      <tbody>
        <tr><th style="width:30%">商号</th><td><?= h(SITE['name']) ?></td></tr>
        <tr><th>代表者</th><td>堤 裕加里</td></tr>
        <tr><th>所在地</th><td>〒<?= h(SITE['zip']) ?> <?= h(SITE['address']) ?></td></tr>
        <tr><th>TEL / FAX</th><td><?= h(SITE['tel']) ?></td></tr>
        <tr><th>メール</th><td><?= h(SITE['email']) ?></td></tr>
        <tr><th>営業時間</th><td>9:00〜18:00（日曜定休）</td></tr>
        <tr><th>設立</th><td>※要記入</td></tr>
        <tr><th>事業内容</th><td>海洋散骨・粉骨・洗骨・お墓じまい・改葬・樹木葬・お手元供養・ペット供養・遺品整理</td></tr>
        <tr><th>加盟団体</th><td>一般社団法人 日本海洋散骨協会</td></tr>
      </tbody>
    </table>

    <!-- アクセス -->
    <h2>アクセス</h2>
    <p class="prose" style="margin-bottom:14px">〒<?= h(SITE['zip']) ?> <?= h(SITE['address']) ?></p>
    <div style="border-radius:var(--radius-lg);overflow:hidden;border:1px solid var(--border);box-shadow:var(--shadow)">
      <iframe src="https://maps.google.com/maps?q=<?= rawurlencode('鹿児島県鹿児島市坂之上7丁目7-3') ?>&z=15&output=embed" width="100%" height="340" style="border:0;display:block" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="有限会社 縁 所在地"></iframe>
    </div>

    <div style="text-align:center;margin-top:36px">
      <a href="/staff/" class="btn btn--outline">スタッフ紹介</a>
      <a href="/contact/" class="btn" style="margin-left:10px">お問い合わせ</a>
    </div>
  </div>
</main>
<style>.admin-table th{background:var(--sea-light);color:var(--green-mid);text-align:left}.admin-table th,.admin-table td{padding:14px 16px;border-bottom:1px solid var(--border)}</style>
<?php require __DIR__ . '/../includes/footer.php'; ?>
