<?php
require_once __DIR__ . '/../includes/config.php';
$page_title     = 'プライバシーポリシー｜' . SITE['name'];
$page_desc      = SITE['name'] . 'における個人情報の取り扱い方針（プライバシーポリシー）です。';
$page_canonical = SITE['url'] . '/privacy/';
require __DIR__ . '/../includes/head.php';
?>
<body>
<?php require __DIR__ . '/../includes/header.php'; ?>
<section class="page-hero">
  <h1>プライバシーポリシー</h1>
  <p>個人情報の取り扱いについて</p>
</section>
<nav class="breadcrumb"><a href="/">ホーム</a> ＞ プライバシーポリシー</nav>

<main class="section">
  <div class="container prose" style="max-width:820px">
    <p class="lead"><?= h(SITE['name']) ?>（以下「当社」）は、お客様の個人情報の保護を重要な責務と考え、以下の方針に基づき適切に取り扱います。</p>

    <h2>1. 事業者情報</h2>
    <p>
      名称：<?= h(SITE['name']) ?><br>
      所在地：〒<?= h(SITE['zip']) ?> <?= h(SITE['address']) ?><br>
      連絡先：TEL <?= h(SITE['tel']) ?> ／ <?= h(SITE['email']) ?>
    </p>

    <h2>2. 取得する個人情報</h2>
    <p>当社は、お問い合わせ・お申し込みの際に、お名前、ふりがな、メールアドレス、電話番号、お問い合わせ内容などの個人情報を取得します。</p>

    <h2>3. 利用目的</h2>
    <p>取得した個人情報は、次の目的の範囲内で利用します。</p>
    <ul style="padding-left:1.2em">
      <li>お問い合わせ・ご相談への回答、お見積りのご案内のため</li>
      <li>ご依頼いただいたサービスの提供・連絡のため</li>
      <li>サービス向上のためのご連絡・ご案内のため</li>
    </ul>

    <h2>4. 第三者への提供</h2>
    <p>当社は、法令に基づく場合を除き、ご本人の同意なく個人情報を第三者に提供しません。サービス提供に必要な範囲で委託先に提供する場合は、適切な監督を行います。</p>

    <h2>5. 安全管理</h2>
    <p>当社は、個人情報の漏えい・滅失・毀損の防止その他の安全管理のために必要かつ適切な措置を講じます。</p>

    <h2>6. アクセス解析・Cookieについて</h2>
    <p>当サイトでは、サイト改善のために自前のアクセス解析を行っていますが、<strong>Cookie・IPアドレス・個人を特定する情報は取得・保存していません</strong>。記録するのは閲覧ページ・日時・参照元・端末種別（PC/モバイル）のみです。</p>

    <h2>7. 開示・訂正・削除の請求</h2>
    <p>ご本人からの個人情報の開示・訂正・利用停止・削除のご請求には、ご本人確認のうえ、法令に従い適切に対応します。下記窓口までご連絡ください。</p>

    <h2>8. お問い合わせ窓口</h2>
    <p><?= h(SITE['name']) ?>　TEL <?= h(SITE['tel']) ?>（<?= h(SITE['hours']) ?>） ／ <?= h(SITE['email']) ?></p>

    <h2>9. 本ポリシーの改定</h2>
    <p>当社は、必要に応じて本ポリシーを改定することがあります。改定後の内容は本ページに掲載した時点から適用されます。</p>

    <p style="margin-top:24px;font-size:.85rem;color:var(--text-light)">制定日：<?= date('Y') ?>年</p>
  </div>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>
