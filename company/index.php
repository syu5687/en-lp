<?php
require_once __DIR__ . '/../includes/config.php';
$page_title     = '会社概要｜' . SITE['name'];
$page_desc      = SITE['name'] . 'の会社概要。' . SITE['tagline'] . '。';
$page_canonical = SITE['url'] . '/company/';
require __DIR__ . '/../includes/head.php';
?>
<body>
<?php require __DIR__ . '/../includes/header.php'; ?>
<section class="page-hero">
  <h1>会社概要</h1>
  <p>有限会社 縁について</p>
</section>
<nav class="breadcrumb"><a href="/">ホーム</a> ＞ 会社概要</nav>

<main class="section">
  <div class="container" style="max-width:820px">
    <table class="admin-table" style="width:100%;background:#fff">
      <tbody>
        <tr><th style="width:30%">商号</th><td><?= h(SITE['name']) ?></td></tr>
        <tr><th>所在地</th><td>〒<?= h(SITE['zip']) ?> <?= h(SITE['address']) ?></td></tr>
        <tr><th>電話番号</th><td><?= h(SITE['tel']) ?></td></tr>
        <tr><th>メール</th><td><?= h(SITE['email']) ?></td></tr>
        <tr><th>営業時間</th><td>9:00〜18:00（日曜定休）</td></tr>
        <tr><th>代表者</th><td>※要記入</td></tr>
        <tr><th>設立</th><td>※要記入</td></tr>
        <tr><th>事業内容</th><td>海洋散骨・粉骨・お墓じまい・樹木葬・お手元供養・ペット供養・遺品整理・お墓のお引越し</td></tr>
        <tr><th>加盟団体</th><td>一般社団法人 日本海洋散骨協会</td></tr>
      </tbody>
    </table>
    <p style="margin-top:24px"><a href="/contact/" class="btn">お問い合わせ</a></p>
  </div>
</main>
<style>.admin-table th{background:#eef4ec;color:var(--green-mid)}.admin-table th,.admin-table td{padding:14px 16px;border-bottom:1px solid var(--border)}</style>
<?php require __DIR__ . '/../includes/footer.php'; ?>
