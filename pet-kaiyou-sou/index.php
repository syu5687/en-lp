<?php
require_once __DIR__ . '/../includes/config.php';
$page_title     = 'ペット供養｜' . SITE['name'];
$page_desc      = 'ペット供養｜' . SITE['name'] . '（' . SITE['tagline'] . '）。大切な家族のペットの海洋散骨';
$page_canonical = SITE['url'] . '/pet-kaiyou-sou/';
require __DIR__ . '/../includes/head.php';
?>
<body>
<?php require __DIR__ . '/../includes/header.php'; ?>
<section class="page-hero">
  <h1>ペット供養</h1>
  <p>大切な家族のペットの海洋散骨</p>
</section>
<nav class="breadcrumb"><a href="/">ホーム</a> ＞ ペット供養</nav>
<main class="section">
  <div class="container prose">
    <span class="wip">このページは準備中です</span>
    <p class="lead">ペット供養のページは現在準備中です。お急ぎの場合はお気軽にお問い合わせください。</p>
    <p style="margin-top:24px">
      <a href="/contact/" class="btn">お問い合わせ</a>
      <a href="<?= h(SITE['line_url']) ?>" target="_blank" rel="noopener" class="btn btn--outline" style="margin-left:10px">LINEで相談</a>
    </p>
  </div>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>
