<?php
require_once __DIR__ . '/../includes/config.php';
$page_title     = '樹木葬｜' . SITE['name'];
$page_desc      = '樹木葬｜' . SITE['name'] . '（' . SITE['tagline'] . '）。自然に還る、管理不要の埋葬';
$page_canonical = SITE['url'] . '/teien-sou/';
require __DIR__ . '/../includes/head.php';
?>
<body>
<?php require __DIR__ . '/../includes/header.php'; ?>
<section class="page-hero">
  <h1>樹木葬</h1>
  <p>自然に還る、管理不要の埋葬</p>
</section>
<nav class="breadcrumb"><a href="/">ホーム</a> ＞ 樹木葬</nav>
<main class="section">
  <div class="container prose">
    <span class="wip">このページは準備中です</span>
    <p class="lead">樹木葬のページは現在準備中です。お急ぎの場合はお気軽にお問い合わせください。</p>
    <p style="margin-top:24px">
      <a href="/contact/" class="btn">お問い合わせ</a>
      <a href="<?= h(SITE['line_url']) ?>" target="_blank" rel="noopener" class="btn btn--outline" style="margin-left:10px">LINEで相談</a>
    </p>
  </div>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>
