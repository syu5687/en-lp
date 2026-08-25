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
<nav class="breadcrumb"><a href="/">ホーム</a> ＞ <a href="/blog/">終活と供養の話</a> ＞ 記事が見つかりません</nav>
<main class="section"><div class="container" style="max-width:760px;text-align:center">
  <p class="lead">お探しの記事は削除されたか、URLが変更された可能性があります。</p>
  <a href="/blog/" class="btn">終活と供養の話一覧へ</a>
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
<nav class="breadcrumb"><a href="/">ホーム</a> ＞ <a href="/blog/">終活と供養の話</a> ＞ <?= h(mb_strimwidth($post['title'] ?? '', 0, 24, '…')) ?></nav>

<main class="section">
  <article class="container" style="max-width:760px">
    <?php if (!empty($post['body_html'])): ?>
      <?php /* 旧WordPress本文HTML（生成時にサニタイズ済み・画像はルート相対に書き換え済み）*/ ?>
      <div class="prose prose--html"><?= $post['body_html'] ?></div>
      <script>
        // 配信元が消えた外部画像（旧ブログのホットリンク）は自動的に非表示にする
        document.querySelectorAll('.prose--html img').forEach(function (i) {
          if (i.complete && !i.naturalWidth) { i.style.display = 'none'; return; }
          i.addEventListener('error', function () { i.style.display = 'none'; });
        });
      </script>
    <?php else: ?>
      <?php
        // 画像（複数対応）。1枚目をメイン、2枚目以降を本文下にギャラリー表示。
        $gallery = [];
        if (!empty($post['images']) && is_array($post['images'])) $gallery = array_values(array_filter($post['images']));
        elseif (!empty($post['image'])) $gallery = [$post['image']];
      ?>
      <?php if ($gallery): ?>
        <img src="<?= h($gallery[0]) ?>" alt="<?= h($post['title'] ?? '') ?>" style="width:100%;border-radius:var(--radius-lg);margin-bottom:28px">
      <?php endif; ?>
      <div class="prose">
        <?php
          $body  = (string)($post['body'] ?? '');
          $paras = preg_split('/\n+/', $body);
          foreach ($paras as $p) { $p = trim($p); if ($p !== '') echo '<p>' . h($p) . '</p>' . "\n"; }
        ?>
      </div>
      <?php if (count($gallery) > 1): ?>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:14px;margin-top:26px">
          <?php foreach (array_slice($gallery, 1) as $g): ?>
            <img src="<?= h($g) ?>" alt="<?= h($post['title'] ?? '') ?>" loading="lazy" style="width:100%;aspect-ratio:4/3;object-fit:cover;border-radius:12px;box-shadow:0 6px 18px rgba(18,89,122,.10)">
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    <?php endif; ?>
    <?php if (!empty($post['link'])): ?>
      <p style="margin-top:22px;font-size:.9rem;color:var(--text-light)">参考リンク：<a href="<?= h($post['link']) ?>" target="_blank" rel="noopener" style="color:var(--green);font-weight:600"><?= h($post['link']) ?></a></p>
    <?php endif; ?>
    <div style="margin-top:38px;text-align:center">
      <a href="/blog/" class="btn btn--outline">← 終活と供養の話一覧へ</a>
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
$page_title     = '終活と供養の話｜' . SITE['name'];
$page_desc      = SITE['name'] . 'からのお知らせ・供養に役立つ情報をお届けします。';

// ---- 記事の収集（Firestore → news.json シード → 旧WordPressアーカイブ を統合）----
// 同じ記事が複数ソースにある場合（同日付＋同タイトル）は、本文HTML・画像を持つ
// 情報の濃い方を優先する（シードの要約版がアーカイブ完全版を隠してしまう問題の対策）。
$by_key = [];
$seen_ids = [];
$blog_score = function (array $it): int {
  return (!empty($it['body_html']) ? 4 : 0)
       + (!empty($it['image'])     ? 2 : 0)
       + (!empty($it['images'])    ? 1 : 0);
};
$push = function (array $it) use (&$by_key, &$seen_ids, $blog_score) {
  if (empty($it['published'])) return;
  $id = (string)($it['id'] ?? '');
  if ($id === '' || isset($seen_ids[$id])) return;
  $seen_ids[$id] = true;
  // タイトルは記号・絵文字・空白を除いて比較（「…胡蝶蘭」と「…胡蝶蘭🌸」を同一視）
  $key = ($it['date'] ?? '') . '|' . preg_replace('/[^\p{L}\p{N}]+/u', '', (string)($it['title'] ?? ''));
  if (!isset($by_key[$key]) || $blog_score($it) > $blog_score($by_key[$key])) $by_key[$key] = $it;
};
try { foreach (news_published() as $it) $push($it); } catch (Throwable $e) {}
$seed = @json_decode((string)@file_get_contents(__DIR__ . '/../data/news.json'), true);
foreach (($seed['items'] ?? []) as $it) $push($it);
foreach (blog_archive_items() as $it) $push($it);
$all = array_values($by_key);
usort($all, fn($a, $b) => strcmp($b['date'] ?? '', $a['date'] ?? ''));

// ---- カテゴリ絞り込み ----
// 「お知らせ／海洋葬」「お知らせ, ブログ」のような複数カテゴリは分解して扱う
// （※「お墓・納骨堂」等の「・」はカテゴリ名の一部なので区切りにしない）
$cat_alias = ['海洋葬' => '海洋葬(海洋散骨)', '海洋散骨' => '海洋葬(海洋散骨)'];
$split_cats = fn(?string $s): array =>
  array_values(array_unique(array_map(
    fn($c) => $cat_alias[$c] ?? $c,
    array_filter(array_map('trim', preg_split('/[,、\/／]+/u', (string)$s)))
  )));
$cat = isset($_GET['cat']) ? trim((string)$_GET['cat']) : '';
$filtered = $cat === '' ? $all
  : array_values(array_filter($all, fn($it) => in_array($cat, $split_cats($it['category'] ?? ''), true)));

// ---- ページネーション（30件/ページ）----
$per_page = 30;
$total    = count($filtered);
$pages    = max(1, (int)ceil($total / $per_page));
$page_no  = isset($_GET['p']) ? max(1, (int)$_GET['p']) : 1;
if ($page_no > $pages) $page_no = $pages;
$items    = array_slice($filtered, ($page_no - 1) * $per_page, $per_page);

// canonical（2ページ目以降・カテゴリ絞り込みはクエリ付き）
$page_canonical = SITE['url'] . '/blog/';
$page_hero_image = '/assets/img/hero-blog.jpg';
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
  <h1>終活と供養の話</h1>
  <p>供養に役立つ情報をお届けします</p>
</section>
<nav class="breadcrumb"><a href="/">ホーム</a> ＞ 終活と供養の話<?php if ($cat !== ''): ?> ＞ <?= h($cat) ?><?php endif; ?></nav>
<main class="section">
  <div class="container">
    <?php
      // 記事数の多い順にカテゴリを集計（複数カテゴリは分解してそれぞれに加算）
      $cat_counts = [];
      foreach ($all as $it) {
        foreach ($split_cats($it['category'] ?? '') as $c) $cat_counts[$c] = ($cat_counts[$c] ?? 0) + 1;
      }
      arsort($cat_counts);
      // 主要カテゴリ（上位）だけを見せ、残りは「その他」で開閉
      $cat_visible = 7;
      $cat_open    = false; // 絞り込み中のカテゴリが「その他」側にあれば最初から開く
      if ($cat !== '') {
        $idx = array_search($cat, array_keys($cat_counts), true);
        if ($idx !== false && $idx >= $cat_visible) $cat_open = true;
      }
    ?>
    <?php if ($cat_counts): ?>
      <nav class="blog-cats<?= $cat_open ? ' is-open' : '' ?>" id="blog-cats" aria-label="カテゴリで絞り込み">
        <a href="/blog/" class="chip<?= $cat === '' ? ' is-active' : '' ?>">すべて<span class="chip__n"><?= count($all) ?></span></a>
        <?php $i = 0; foreach ($cat_counts as $c => $n): $i++; ?>
          <a href="/blog/?cat=<?= h(rawurlencode($c)) ?>"
             class="chip<?= $cat === $c ? ' is-active' : '' ?><?= $i > $cat_visible ? ' chip--more' : '' ?>"><?= h($c) ?><span class="chip__n"><?= (int)$n ?></span></a>
        <?php endforeach; ?>
        <?php if (count($cat_counts) > $cat_visible): ?>
          <button type="button" class="chip chip--toggle" id="cats-toggle" aria-expanded="<?= $cat_open ? 'true' : 'false' ?>">
            <span class="chip--toggle__open">その他のカテゴリ（<?= count($cat_counts) - $cat_visible ?>） ＋</span>
            <span class="chip--toggle__close">閉じる −</span>
          </button>
        <?php endif; ?>
      </nav>
      <script>
        (function () {
          var t = document.getElementById('cats-toggle'), w = document.getElementById('blog-cats');
          if (t) t.addEventListener('click', function () {
            var open = w.classList.toggle('is-open');
            t.setAttribute('aria-expanded', open ? 'true' : 'false');
          });
        })();
      </script>
    <?php endif; ?>

    <?php if ($items): ?>
      <p style="font-size:.85rem;color:var(--text-light);margin-bottom:14px"><?= number_format($total) ?>件<?php if ($pages > 1): ?>　（<?= $page_no ?> / <?= $pages ?>ページ）<?php endif; ?></p>
      <div class="card-grid">
        <?php foreach ($items as $it): ?>
          <a class="card" href="/blog/?id=<?= h(rawurlencode($it['id'] ?? '')) ?>" style="display:flex;flex-direction:column;padding:0;overflow:hidden">
            <?php if (!empty($it['image'])): ?>
              <span class="card-thumb"><img src="<?= h($it['image']) ?>" alt="" loading="lazy"
                onerror="var t=this.closest('.card-thumb');if(t)t.remove()"></span>
            <?php endif; ?>
            <span style="display:flex;flex-direction:column;padding:18px 20px;flex:1">
            <p style="font-size:.8rem;color:var(--text-light)"><?= h($it['date'] ?? '') ?> ・ <?= h($it['category'] ?? '') ?></p>
            <h3><?= h($it['title'] ?? '') ?></h3>
            <?php if (!empty($it['body'])): ?><p style="font-size:.9rem;flex:1"><?= h(mb_strimwidth(preg_replace('/\s+/', ' ', (string)$it['body']), 0, 80, '…')) ?></p><?php endif; ?>
            <span style="margin-top:12px;align-self:flex-start;color:var(--green);font-weight:600;font-size:.85rem">続きを読む →</span>
            </span>
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
  .blog-cats{display:flex;flex-wrap:wrap;gap:7px;margin-bottom:26px;padding:14px 16px;background:#f4f9fb;border:1px solid #e2eef3;border-radius:14px}
  .chip{display:inline-flex;align-items:center;gap:5px;padding:5px 12px;border-radius:999px;background:#fff;color:var(--text);font-size:.8rem;font-weight:600;border:1px solid #dcebf1;transition:.15s;line-height:1.5;cursor:pointer}
  .chip:hover{background:#e6f2f7;border-color:#bcdce8}
  .chip.is-active{background:var(--green);color:#fff;border-color:var(--green)}
  .chip__n{font-size:.68rem;opacity:.55;font-weight:700}
  .chip.is-active .chip__n{opacity:.85}
  .chip--more{display:none}
  .blog-cats.is-open .chip--more{display:inline-flex}
  .chip--toggle{background:transparent;border:1px dashed #a9cddc;color:var(--green-mid,#15709e);font-family:inherit}
  .chip--toggle:hover{background:#e6f2f7}
  .chip--toggle__close{display:none}
  .blog-cats.is-open .chip--toggle__open{display:none}
  .blog-cats.is-open .chip--toggle__close{display:inline}
  @media (max-width:640px){
    .blog-cats{padding:12px;gap:6px}
    .chip{font-size:.76rem;padding:4px 10px}
  }
  .pager__btn{display:inline-flex;align-items:center;justify-content:center;min-width:40px;height:40px;padding:0 12px;border-radius:8px;background:#eef5f8;color:var(--text);font-weight:600;font-size:.9rem;transition:.15s}
  .pager__btn:hover{background:#e0eef4}
  .pager__btn.is-active{background:var(--green);color:#fff}
  .pager__dots{color:var(--text-light);padding:0 2px}
  .card-thumb{display:block;aspect-ratio:16/10;overflow:hidden;background:#eef5f8}
  .card-thumb img{width:100%;height:100%;object-fit:cover;display:block}
  .prose--html img{max-width:100%;height:auto;border-radius:10px;margin:8px 0;box-shadow:0 6px 18px rgba(18,89,122,.10)}
  .prose--html h2,.prose--html h3,.prose--html h4,.prose--html h5{margin:1.4em 0 .5em;line-height:1.5}
  .prose--html p{margin:0 0 1em;line-height:1.95}
  .prose--html a{color:var(--green);font-weight:600;word-break:break-all}
  .prose--html table{width:100%;border-collapse:collapse;margin:1em 0}
  .prose--html td,.prose--html th{border:1px solid #d8e6ec;padding:8px 10px}
  .prose--html ul,.prose--html ol{margin:0 0 1em;padding-left:1.4em}
  .prose--html li{margin:.3em 0;line-height:1.85}
</style>
<?php require __DIR__ . '/../includes/footer.php'; ?>
