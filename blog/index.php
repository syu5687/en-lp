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

/** data/blog-posts.json（旧WordPress全記事アーカイブ）を読み込む */
function blog_archive_items(): array {
  static $c = null;
  if ($c !== null) return $c;
  $seed = @json_decode((string)@file_get_contents(__DIR__ . '/../data/blog-posts.json'), true);
  return $c = ($seed['items'] ?? []);
}
/** アーカイブから公開記事をIDで取得 */
function blog_archive_find(string $id): ?array {
  foreach (blog_archive_items() as $it) {
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
  if (!$post || empty($post['published'])) $post = blog_archive_find($blog_id);

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

// ---- 記事の収集（Firestore → news.json シード → 旧WordPressアーカイブ を統合）----
$all = [];
$seen = [];
$push = function (array $it) use (&$all, &$seen) {
  if (empty($it['published'])) return;
  $id = (string)($it['id'] ?? '');
  if ($id === '' || isset($seen[$id])) return;
  $seen[$id] = true;
  $all[] = $it;
};
try { foreach (news_published() as $it) $push($it); } catch (Throwable $e) {}
$seed = @json_decode((string)@file_get_contents(__DIR__ . '/../data/news.json'), true);
foreach (($seed['items'] ?? []) as $it) $push($it);
foreach (blog_archive_items() as $it) $push($it);
usort($all, fn($a, $b) => strcmp($b['date'] ?? '', $a['date'] ?? ''));

// ---- カテゴリ絞り込み ----
$cat = isset($_GET['cat']) ? trim((string)$_GET['cat']) : '';
$filtered = $cat === '' ? $all : array_values(array_filter($all, fn($it) => ($it['category'] ?? '') === $cat));

// ---- ページネーション（30件/ページ）----
$per_page = 30;
$total    = count($filtered);
$pages    = max(1, (int)ceil($total / $per_page));
$page_no  = isset($_GET['p']) ? max(1, (int)$_GET['p']) : 1;
if ($page_no > $pages) $page_no = $pages;
$items    = array_slice($filtered, ($page_no - 1) * $per_page, $per_page);

// canonical（2ページ目以降・カテゴリ絞り込みはクエリ付き）
$page_canonical = SITE['url'] . '/blog/';
$qp = [];
if ($cat !== '')     $qp['cat'] = $cat;
if ($page_no > 1)    $qp['p']   = $page_no;
if ($qp) $page_canonical .= '?' . http_build_query($qp);

// 絞り込み用リンクのベース
$link_base = fn(array $over) => '/blog/' . (($q = http_build_query(array_filter(array_merge(['cat' => $cat], $over), fn($v) => $v !== '' && $v !== 0))) ? '?' . $q : '');

require __DIR__ . '/../includes/head.php';
?>
<body>
<?php require __DIR__ . '/../includes/header.php'; ?>
<section class="page-hero">
  <h1>ブログ・お知らせ</h1>
  <p>供養に役立つ情報をお届けします</p>
</section>
<nav class="breadcrumb"><a href="/">ホーム</a> ＞ ブログ・お知らせ<?php if ($cat !== ''): ?> ＞ <?= h($cat) ?><?php endif; ?></nav>
<main class="section">
  <div class="container">
    <?php
      // 記事数の多い順にカテゴリを並べ、絞り込みチップを表示
      $cat_counts = [];
      foreach ($all as $it) { $c = (string)($it['category'] ?? ''); if ($c !== '') $cat_counts[$c] = ($cat_counts[$c] ?? 0) + 1; }
      arsort($cat_counts);
    ?>
    <?php if ($cat_counts): ?>
      <div class="blog-cats" style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:26px">
        <a href="/blog/" class="chip<?= $cat === '' ? ' is-active' : '' ?>">すべて<span class="chip__n"><?= count($all) ?></span></a>
        <?php foreach ($cat_counts as $c => $n): ?>
          <a href="/blog/?cat=<?= h(rawurlencode($c)) ?>" class="chip<?= $cat === $c ? ' is-active' : '' ?>"><?= h($c) ?><span class="chip__n"><?= (int)$n ?></span></a>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <?php if ($items): ?>
      <p style="font-size:.85rem;color:var(--text-light);margin-bottom:14px"><?= number_format($total) ?>件<?php if ($pages > 1): ?>　（<?= $page_no ?> / <?= $pages ?>ページ）<?php endif; ?></p>
      <div class="card-grid">
        <?php foreach ($items as $it): ?>
          <a class="card" href="/blog/?id=<?= h(rawurlencode($it['id'] ?? '')) ?>" style="display:flex;flex-direction:column">
            <p style="font-size:.8rem;color:var(--text-light)"><?= h($it['date'] ?? '') ?> ・ <?= h($it['category'] ?? '') ?></p>
            <h3><?= h($it['title'] ?? '') ?></h3>
            <?php if (!empty($it['body'])): ?><p style="font-size:.9rem;flex:1"><?= h(mb_strimwidth(preg_replace('/\s+/', ' ', (string)$it['body']), 0, 80, '…')) ?></p><?php endif; ?>
            <span style="margin-top:12px;align-self:flex-start;color:var(--green);font-weight:600;font-size:.85rem">続きを読む →</span>
          </a>
        <?php endforeach; ?>
      </div>

      <?php if ($pages > 1): ?>
        <nav class="pager" style="display:flex;justify-content:center;align-items:center;gap:6px;flex-wrap:wrap;margin-top:36px">
          <?php if ($page_no > 1): ?>
            <a class="pager__btn" href="<?= h($link_base(['p' => $page_no - 1 > 1 ? $page_no - 1 : ''])) ?>">← 前へ</a>
          <?php endif; ?>
          <?php
            // 先頭・末尾・現在ページ周辺を表示
            $win = 2;
            $show = [];
            for ($i = 1; $i <= $pages; $i++) {
              if ($i <= 1 || $i > $pages - 1 || abs($i - $page_no) <= $win) $show[] = $i;
            }
            $prev = 0;
            foreach ($show as $i):
              if ($prev && $i - $prev > 1) echo '<span class="pager__dots">…</span>';
              $prev = $i;
          ?>
            <?php if ($i === $page_no): ?>
              <span class="pager__btn is-active"><?= $i ?></span>
            <?php else: ?>
              <a class="pager__btn" href="<?= h($link_base(['p' => $i > 1 ? $i : ''])) ?>"><?= $i ?></a>
            <?php endif; ?>
          <?php endforeach; ?>
          <?php if ($page_no < $pages): ?>
            <a class="pager__btn" href="<?= h($link_base(['p' => $page_no + 1])) ?>">次へ →</a>
          <?php endif; ?>
        </nav>
      <?php endif; ?>
    <?php else: ?>
      <span class="wip">記事は準備中です</span>
      <p class="lead">現在お知らせはありません。</p>
    <?php endif; ?>
  </div>
</main>

<style>
  .chip{display:inline-flex;align-items:center;gap:6px;padding:6px 14px;border-radius:999px;background:#eef5f8;color:var(--text);font-size:.82rem;font-weight:600;border:1px solid transparent;transition:.15s}
  .chip:hover{background:#e0eef4}
  .chip.is-active{background:var(--green);color:#fff}
  .chip__n{font-size:.72rem;opacity:.7;font-weight:700}
  .chip.is-active .chip__n{opacity:.85}
  .pager__btn{display:inline-flex;align-items:center;justify-content:center;min-width:40px;height:40px;padding:0 12px;border-radius:8px;background:#eef5f8;color:var(--text);font-weight:600;font-size:.9rem;transition:.15s}
  .pager__btn:hover{background:#e0eef4}
  .pager__btn.is-active{background:var(--green);color:#fff}
  .pager__dots{color:var(--text-light);padding:0 2px}
</style>
<?php require __DIR__ . '/../includes/footer.php'; ?>
