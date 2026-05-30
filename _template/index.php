<?php
/**
 * ▼ 新しいページの作り方
 *   1. このフォルダ（_template/）の index.php を新しいスラッグのフォルダにコピー
 *   2. 下の3変数を書き換える
 *   3. <main> の中身を実装する
 * 共通ヘッダー/フッター/ナビ/CSSは自動で適用されます。
 */
require_once __DIR__ . '/../includes/config.php';

$page_title     = 'ページタイトル｜' . SITE['name'];
$page_desc      = 'このページの説明文（meta description）。';
$page_canonical = SITE['url'] . '/your-slug/';

require __DIR__ . '/../includes/head.php';
?>
<body>
<?php require __DIR__ . '/../includes/header.php'; ?>

<section class="page-hero">
  <h1>ページ見出し</h1>
  <p>サブコピー</p>
</section>
<nav class="breadcrumb"><a href="/">ホーム</a> ＞ ページ名</nav>

<main class="section">
  <div class="container prose">
    <p class="lead">ここに本文を実装します。</p>
  </div>
</main>

<?php require __DIR__ . '/../includes/footer.php'; ?>
