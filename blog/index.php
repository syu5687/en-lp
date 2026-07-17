<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../admin/includes/store.php'; // news_published() / news_find()

/** data/news.json（フォールバック）から公開記事をIDで取得 */
function blog_seed_find(string $id): ?array {
  $seed = @json_decode((string)@file_get_contents(__DIR__ . '/../data/news.json'), true);
  foreach (($seed['items'] ?? []) as $it) {
    if (($it['id'] ?? '') === $id && !empty($it['published'])) return $it;
  }
  return null;
}

$blog_id = isset($_GET['id']) ? trim((string)$_GET['id']) : '';

/* ============================================================
   詳細（単一記事）モード ： /blog/?id=xxxx
   ============================================================ */
if ($blog_id !== '') {
  $post = null;
  try { $post = news_find($blog_id); } catch (Throwable $e) { $post = null; }
  if (!$post || empty($post['published'])) $post = blog_seed_find($blog_id);

  if (!$post || empty($post['published'])) {
    http_response_code(404);
    $page_title    = '記事が見つかりません｜' . SITE['name'];
    $page_noindex  = true;
    require __DIR__ . '/../includes/head.php'; ?>
<body>
<?php require __DIR__ . '/../includes/header.php'; ?>
<section class="page-hero"><h1>記事が見つかりません</h1></section>
<nav class="breadcrumb"><a href="/">ホーム</a> ＞ <a href="/blog/">ブログ・お知らせ</a> ＞ 記事が見つかりません</nav>
<main class="section"><div class="container" style="max-width:760px;text-align:center">
  <p class="lead">お探しの記事は削除されたか、URLが変更された可能性があります。</p>
  <a href="/blog/" class="btn">ブログ・お知らせ一覧へ</a>
</div></main>
<?php require __DIR__ . '/../includes/footer.php'; ?>
<?php
    exit;
  }

  $page_title     = ($post['title'] ?? '記事') . '｜' . SITE['name'];
  $page_desc      = mb_strimwidth(preg_replace('/\s+/', ' ', (string)($post['body'] ?? '')), 0, 110, '…');
  $page_canonical = SITE['url'] . '/blog/?id=' . rawurlencode($blog_id);

  $article_ld = [
    '@context'         => 'https://schema.org',
    '@type'            => 'Article',
    'headline'         => $post['title'] ?? '',
    'datePublished'    => $post['date'] ?? '',
    'author'           => ['@type' => 'Organization', 'name' => SITE['name']],
    'publisher'        => ['@type' => 'Organization', 'name' => SITE['name'], 'url' => SITE['url'] . '/'],
    'mainEntityOfPage' => $page_canonical,
  ];

  require __DIR__ . '/../includes/head.php';
  ?>
<body>
<?php require __DIR__ . '/../includes/header.php'; ?>
<section class="page-hero">
  <?php if (!empty($post['category'])): ?>
    <p style="position:relative"><span style="display:inline-block;background:rgba(255,255,255,.18);padding:4px 14px;border-radius:999px;font-size:.78rem;font-weight:600"><?= h($post['category']) ?></span></p>
  <?php endif; ?>
  <h1 style="font-size:1.55rem;line-height:1.55;max-width:820px;margin:14px auto 0"><?= h($post['title'] ?? '') ?></h1>
  <p style="margin-top:12px"><?= h($post['date'] ?? '') ?></p>
</section>
<nav class="breadcrumb"><a href="/">ホーム</a> ＞ <a href="/blog/">ブログ・お知らせ</a> ＞ <?= h(mb_strimwidth($post['title'] ?? '', 0, 24, '…')) ?></nav>

<main class="section">
  <article class="container" style="max-width:760px">
    <?php if (!empty($post['image'])): ?>
      <img src="<?= h($post['image']) ?>" alt="<?= h($post['title'] ?? '') ?>" style="width:100%;border-radius:var(--radius-lg);margin-bottom:28px">
    <?php endif; ?>
    <div class="prose">
      <?php
        $body  = (string)($post['body'] ?? '');
        $paras = preg_split('/\n+/', $body);
        foreach ($paras as $p) { $p = trim($p); if ($p !== '') echo '<p>' . h($p) . '</p>' . "\n"; }
      ?>
    </div>
    <?php if (!empty($post['link'])): ?>
      <p style="margin-top:22px;font-size:.9rem;color:var(--text-light)">参考リンク：<a href="<?= h($post['link']) ?>" target="_blank" rel="noopener" style="color:var(--green);font-weight:600"><?= h($post['link']) ?></a></p>
    <?php endif; ?>
    <div style="margin-top:38px;text-align:center">
      <a href="/blog/" class="btn btn--outline">← ブログ・お知らせ一覧へ</a>
    </div>
  </article>
</main>

<section class="section" style="background:linear-gradient(135deg,var(--green),var(--green-mid));color:#fff;text-align:center">
  <div class="container">
    <h2 style="color:#fff">ご相談・お見積りは無料です</h2>
    <p style="opacity:.92;margin-bottom:22px">供養に関するお悩みは、お気軽にお問い合わせください。宗教・宗派は問いません。</p>
    <a href="/contact/" class="btn" style="background:#fff;color:var(--green-mid)">お問い合わせ</a>
    <a href="<?= h(SITE['line_url']) ?>" target="_blank" rel="noopener" class="btn" style="background:#06C755;margin-left:10px">LINEで相談</a>
  </div>
</section>

<script type="application/ld+json"><?= json_encode($article_ld, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?></script>
<?php require __DIR__ . '/../includes/footer.php'; ?>
<?php
  exit;
}

/* ============================================================
   一覧モード ： /blog/
   ============================================================ */
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
          <a class="card" href="/blog/?id=<?= h(rawurlencode($it['id'] ?? '')) ?>" style="display:flex;flex-direction:column">
            <p style="font-size:.8rem;color:var(--text-light)"><?= h($it['date'] ?? '') ?> ・ <?= h($it['category'] ?? '') ?></p>
            <h3><?= h($it['title'] ?? '') ?></h3>
            <?php if (!empty($it['body'])): ?><p style="font-size:.9rem;flex:1"><?= h(mb_strimwidth($it['body'], 0, 80, '…')) ?></p><?php endif; ?>
            <span style="margin-top:12px;align-self:flex-start;color:var(--green);font-weight:600;font-size:.85rem">続きを読む →</span>
          </a>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <span class="wip">記事は準備中です</span>
      <p class="lead">現在お知らせはありません。</p>
    <?php endif; ?>
  </div>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>
