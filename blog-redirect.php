<?php
/**
 * 旧WordPress記事URL → 新ブログURL への 301 リダイレクター
 * .htaccess の「単一セグメント（/xxxxx/）で実ファイル・実ディレクトリが無いもの」を
 * ここに集約し、旧スラッグと一致すれば /blog/?id=<slug> へ 301 転送する。
 * 一致しなければ 404（ソフト404を避け、正しいステータスを返す）。
 */
require_once __DIR__ . '/includes/config.php';

$path = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?: '';
$slug = rawurldecode(trim($path, '/'));

// アーカイブ（旧WordPress全記事）から該当スラッグを探す
$archive = @json_decode((string)@file_get_contents(__DIR__ . '/data/blog-posts.json'), true);
$found = false;
foreach (($archive['items'] ?? []) as $it) {
  if ((string)($it['id'] ?? '') === $slug && !empty($it['published'])) { $found = true; break; }
}

if ($found && $slug !== '') {
  header('Location: /blog/?id=' . rawurlencode($slug), true, 301);
  exit;
}

// 見つからない場合は 404 を返す（ブログ一覧への誘導ページ）
http_response_code(404);
$page_title   = '記事が見つかりません｜' . SITE['name'];
$page_noindex = true;
require __DIR__ . '/includes/head.php';
?>
<body>
<?php require __DIR__ . '/includes/header.php'; ?>
<section class="page-hero"><h1>ページが見つかりません</h1></section>
<nav class="breadcrumb"><a href="/">ホーム</a> ＞ 404</nav>
<main class="section"><div class="container" style="max-width:760px;text-align:center">
  <p class="lead">お探しのページは削除されたか、URLが変更された可能性があります。</p>
  <div style="margin-top:22px">
    <a href="/" class="btn">トップへ</a>
    <a href="/blog/" class="btn btn--outline" style="margin-left:10px">ブログ・お知らせ一覧へ</a>
  </div>
</div></main>
<?php require __DIR__ . '/includes/footer.php'; ?>
