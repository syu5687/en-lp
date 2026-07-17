<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../admin/includes/store.php'; // news_published()
$page_title     = 'ブログ・お知らせ｜' . SITE['name'];
$page_desc      = SITE['name'] . 'からのお知らせ・供養に役立つ情報をお届けします。';
$page_canonical = SITE['url'] . '/blog/';

$items = [];
try { $items = news_published(); } catch (Throwable $e) { $items = []; }
// Firestore未接続・未移行時のフォールバック（data/news.json のシードを表示）
if (!$items) {
  $seed = @json_decode((string)@file_get_contents(__DIR__ . '/../data/news.json'), true);
  foreach (($seed['items'] ?? []) as $it) {
    if (!empty($it['published'])) $items[] = $it;
  }
  usort($items, fn($a, $b) => strcmp($b['date'] ?? '', $a['date'] ?? ''));
}

require __DIR__ . '/../includes/head.php';
?>
<body>
<?php require __DIR__ . '/../includes/header.php'; ?>
<section class="page-hero">
  <h1>ブログ・お知らせ</h1>
  <p>供養に役立つ情報をお届けします</p>
</section>
<nav class="breadcrumb"><a href="/">ホーム</a> ＞ ブログ・お知らせ</nav>
<main class="section">
  <div class="container">
    <?php if ($items): ?>
      <div class="card-grid">
        <?php foreach ($items as $it): ?>
          <article class="card">
            <p style="font-size:.8rem;color:var(--text-light)"><?= h($it['date'] ?? '') ?> ・ <?= h($it['category'] ?? '') ?></p>
            <h3><?= h($it['title'] ?? '') ?></h3>
            <?php if (!empty($it['body'])): ?><p style="font-size:.9rem"><?= h(mb_strimwidth($it['body'], 0, 80, '…')) ?></p><?php endif; ?>
          </article>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <span class="wip">記事は準備中です</span>
      <p class="lead">現在お知らせはありません。</p>
    <?php endif; ?>
  </div>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>
