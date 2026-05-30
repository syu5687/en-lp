<?php
require_once __DIR__ . '/../includes/config.php';
$page_title     = 'プライバシーポリシー｜' . SITE['name'];
$page_desc      = 'プライバシーポリシー｜' . SITE['name'] . '（' . SITE['tagline'] . '）。個人情報の取り扱いについて';
$page_canonical = SITE['url'] . '/privacy/';
require __DIR__ . '/../includes/head.php';
?>
<body>
<?php require __DIR__ . '/../includes/header.php'; ?>
<section class="page-hero">
  <h1>プライバシーポリシー</h1>
  <p>個人情報の取り扱いについて</p>
</section>
<nav class="breadcrumb"><a href="/">ホーム</a> ＞ プライバシーポリシー</nav>
<main class="section">
  <div class="container prose">
    <span class="wip">このページは準備中です</span>
    <p class="lead">プライバシーポリシーのページは現在準備中です。お急ぎの場合はお気軽にお問い合わせください。</p>
    <p style="margin-top:24px">
      <a href="/contact/" class="btn">お問い合わせ</a>
      <a href="<?= h(SITE['line_url']) ?>" target="_blank" rel="noopener" class="btn btn--outline" style="margin-left:10px">LINEで相談</a>
    </p>
  </div>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>
