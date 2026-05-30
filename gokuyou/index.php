<?php
require_once __DIR__ . '/../includes/config.php';
$page_title     = 'ご供養について｜' . SITE['name'];
$page_desc      = 'ご供養について｜' . SITE['name'] . '（' . SITE['tagline'] . '）。宗教・宗派を問わず、心を込めて';
$page_canonical = SITE['url'] . '/gokuyou/';
require __DIR__ . '/../includes/head.php';
?>
<body>
<?php require __DIR__ . '/../includes/header.php'; ?>
<section class="page-hero">
  <h1>ご供養について</h1>
  <p>宗教・宗派を問わず、心を込めて</p>
</section>
<nav class="breadcrumb"><a href="/">ホーム</a> ＞ ご供養について</nav>
<main class="section">
  <div class="container prose">
    <span class="wip">このページは準備中です</span>
    <p class="lead">ご供養についてのページは現在準備中です。お急ぎの場合はお気軽にお問い合わせください。</p>
    <p style="margin-top:24px">
      <a href="/contact/" class="btn">お問い合わせ</a>
      <a href="<?= h(SITE['line_url']) ?>" target="_blank" rel="noopener" class="btn btn--outline" style="margin-left:10px">LINEで相談</a>
    </p>
  </div>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>
