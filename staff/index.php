<?php
require_once __DIR__ . '/../includes/config.php';
$page_title     = 'スタッフ紹介｜' . SITE['name'];
$page_desc      = 'スタッフ紹介｜' . SITE['name'] . '（' . SITE['tagline'] . '）。ご遺骨トータルアドバイザーがご対応';
$page_canonical = SITE['url'] . '/staff/';
$page_noindex   = true; // 準備中のため noindex（本実装時に false へ）
require __DIR__ . '/../includes/head.php';
?>
<body>
<?php require __DIR__ . '/../includes/header.php'; ?>
<section class="page-hero">
  <h1>スタッフ紹介</h1>
  <p>ご遺骨トータルアドバイザーがご対応</p>
</section>
<nav class="breadcrumb"><a href="/">ホーム</a> ＞ スタッフ紹介</nav>
<main class="section">
  <div class="container prose">
    <span class="wip">このページは準備中です</span>
    <p class="lead">スタッフ紹介のページは現在準備中です。お急ぎの場合はお気軽にお問い合わせください。</p>
    <p style="margin-top:24px">
      <a href="/contact/" class="btn">お問い合わせ</a>
      <a href="<?= h(SITE['line_url']) ?>" target="_blank" rel="noopener" class="btn btn--outline" style="margin-left:10px">LINEで相談</a>
    </p>
  </div>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>
