<?php
require_once __DIR__ . '/../includes/config.php';
$page_title     = 'ご供養について｜' . SITE['name'];
$page_desc      = '宗教・宗派を問わず、心を込めて。' . SITE['name'] . 'のご供養に対する考え方をご紹介します。';
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
  <div class="container prose" style="max-width:820px">
    <p class="lead">供養のかたちは、時代とともに多様になっています。当社は、ご家族お一人おひとりの想いに寄り添い、後悔のないお見送りをお手伝いします。</p>
    <p>お墓の継承や管理に悩む方が増える中、海洋散骨・樹木葬・お手元供養など、自然に寄り添う新しい供養が選ばれるようになりました。一方で「本当にこれでいいのか」という不安を抱える方も少なくありません。</p>
    <p>当社は、宗教・宗派を問わず中立の立場で、メリットだけでなく注意点も含めて丁寧にご説明します。ご相談・お見積りは無料です。急かすことは一切ありませんので、どうぞお気軽にご相談ください。</p>

    <h2>当社が大切にしていること</h2>
    <ul style="list-style:none;display:grid;gap:12px;padding:0">
      <li style="padding:14px 18px;background:var(--cream);border-radius:10px;border-left:4px solid var(--green)">ご遺骨トータルアドバイザーとして、最初から最後まで一貫してサポートします</li>
      <li style="padding:14px 18px;background:var(--cream);border-radius:10px;border-left:4px solid var(--green)">追加料金のない明朗なお見積りをお出しします</li>
      <li style="padding:14px 18px;background:var(--cream);border-radius:10px;border-left:4px solid var(--green)">遠方の方にも、郵送・委託で全国対応します</li>
    </ul>

    <h2>サービス一覧</h2>
    <div class="card-grid">
      <?php foreach (SERVICES as $s): ?>
        <a class="card" href="/<?= h($s['slug']) ?>/"><h3><?= h($s['title']) ?></h3><span class="price"><?= h($s['price']) ?></span></a>
      <?php endforeach; ?>
    </div>
    <p style="margin-top:28px"><a href="/contact/" class="btn">無料で相談する</a></p>
  </div>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>
