<?php
require_once __DIR__ . '/../includes/config.php';
$page_title     = 'お墓じまい｜' . SITE['name'];
$page_desc      = 'お墓じまい｜' . SITE['name'] . '（' . SITE['tagline'] . '）。手続きから新しい供養先まで一括対応';
$page_canonical = SITE['url'] . '/grave/';
require __DIR__ . '/../includes/head.php';
?>
<body>
<?php require __DIR__ . '/../includes/header.php'; ?>
<section class="page-hero">
  <h1>お墓じまい</h1>
  <p>手続きから新しい供養先まで一括対応</p>
</section>
<nav class="breadcrumb"><a href="/">ホーム</a> ＞ お墓じまい</nav>
<main class="section">
  <div class="container prose">
    <span class="wip">このページは準備中です</span>
    <p class="lead">お墓じまいのページは現在準備中です。お急ぎの場合はお気軽にお問い合わせください。</p>
    <p style="margin-top:24px">
      <a href="/contact/" class="btn">お問い合わせ</a>
      <a href="<?= h(SITE['line_url']) ?>" target="_blank" rel="noopener" class="btn btn--outline" style="margin-left:10px">LINEで相談</a>
    </p>
  </div>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>
