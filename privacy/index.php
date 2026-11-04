<?php
require_once __DIR__ . '/../includes/config.php';
$page_title     = '個人情報保護方針｜' . SITE['name'];
$page_desc      = SITE['name'] . 'における個人情報の取り扱いについて（個人情報保護方針）です。';
$page_canonical = SITE['url'] . '/privacy/';
require __DIR__ . '/../includes/head.php';
?>
<body>
<?php require __DIR__ . '/../includes/header.php'; ?>
<section class="page-hero">
  <h1>個人情報保護方針</h1>
  <p>個人情報の取り扱いについて</p>
</section>
<nav class="breadcrumb"><a href="/">ホーム</a> ＞ 個人情報保護方針</nav>

<main class="section">
  <div class="container prose" style="max-width:820px">
    <?php /* v0276：本文は includes/privacy-text.php に集約した（フォーム内にも同じ文章を出すため） */ ?>
    <?php require __DIR__ . '/../includes/privacy-text.php'; ?>
  </div>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>
