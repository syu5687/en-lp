<?php
require_once __DIR__ . '/../includes/config.php';
$page_title     = 'サービス一覧｜' . SITE['name'];
$page_desc      = '海洋散骨・粉骨・お墓じまい・樹木葬・お手元供養・ペット供養まで。' . SITE['name'] . 'のサービス一覧。';
$page_canonical = SITE['url'] . '/service/';
require __DIR__ . '/../includes/head.php';
?>
<body>
<?php require __DIR__ . '/../includes/header.php'; ?>
<section class="page-hero">
  <h1>サービス一覧</h1>
  <p>ご遺骨の供養を、ワンストップでサポートします</p>
</section>
<nav class="breadcrumb"><a href="/">ホーム</a> ＞ サービス一覧</nav>
<main class="section">
  <div class="container">
    <div class="card-grid">
      <?php foreach (SERVICES as $s): ?>
        <a class="card" href="/<?= h($s['slug']) ?>/">
          <h3><?= h($s['title']) ?></h3>
          <span class="price"><?= h($s['price']) ?></span>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>
