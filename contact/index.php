<?php
require_once __DIR__ . '/../includes/config.php';
$page_title     = 'お問い合わせ｜' . SITE['name'];
$page_desc      = 'お問い合わせ｜' . SITE['name'] . '（' . SITE['tagline'] . '）。ご相談・お見積りは無料です';
$page_canonical = SITE['url'] . '/contact/';
require __DIR__ . '/../includes/head.php';
?>
<body>
<?php require __DIR__ . '/../includes/header.php'; ?>
<section class="page-hero">
  <h1>お問い合わせ</h1>
  <p>ご相談・お見積りは無料です</p>
</section>
<nav class="breadcrumb"><a href="/">ホーム</a> ＞ お問い合わせ</nav>
<main class="section">
  <div class="container prose">
    <span class="wip">このページは準備中です</span>
    <p class="lead">お問い合わせのページは現在準備中です。お急ぎの場合はお気軽にお問い合わせください。</p>
    <p style="margin-top:24px">
      <a href="/contact/" class="btn">お問い合わせ</a>
      <a href="<?= h(SITE['line_url']) ?>" target="_blank" rel="noopener" class="btn btn--outline" style="margin-left:10px">LINEで相談</a>
    </p>
  </div>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>
