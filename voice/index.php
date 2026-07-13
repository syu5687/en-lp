<?php
require_once __DIR__ . '/../includes/config.php';
$page_title     = 'お客様の声｜' . SITE['name'];
$page_desc      = 'お客様の声｜' . SITE['name'] . '（' . SITE['tagline'] . '）。全国のお客様からいただいた声';
$page_canonical = SITE['url'] . '/voice/';
$page_noindex   = true; // 準備中のため noindex（本実装時に false へ）
require __DIR__ . '/../includes/head.php';
?>
<body>
<?php require __DIR__ . '/../includes/header.php'; ?>
<section class="page-hero">
  <h1>お客様の声</h1>
  <p>全国のお客様からいただいた声</p>
</section>
<nav class="breadcrumb"><a href="/">ホーム</a> ＞ お客様の声</nav>
<main class="section">
  <div class="container prose">
    <span class="wip">このページは準備中です</span>
    <p class="lead">お客様の声のページは現在準備中です。お急ぎの場合はお気軽にお問い合わせください。</p>
    <p style="margin-top:24px">
      <a href="/contact/" class="btn">お問い合わせ</a>
      <a href="<?= h(SITE['line_url']) ?>" target="_blank" rel="noopener" class="btn btn--outline" style="margin-left:10px">LINEで相談</a>
    </p>
  </div>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>
