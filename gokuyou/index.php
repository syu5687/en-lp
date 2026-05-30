<?php
require_once __DIR__ . '/../includes/config.php';
$page_title     = 'よくあるご質問｜' . SITE['name'];
$page_desc      = '海洋散骨・粉骨・お墓じまい・樹木葬・ペット供養などに関するよくあるご質問にお答えします。' . SITE['name'] . '。';
$page_canonical = SITE['url'] . '/gokuyou/';

$faqs = [
  ['海洋散骨は法律的に問題ないですか？', '法務省は「節度をもって行えば違法ではない」との見解を示しています。当社は一般社団法人日本海洋散骨協会のガイドラインに準じ、適切な場所・方法で行いますのでご安心ください。'],
  ['遠方に住んでいても依頼できますか？', 'はい、全国からご依頼いただけます。ご遺骨は郵送（ゆうパック）での受付も可能で、委託海洋葬であればお立ち会いなしでも承ります。'],
  ['粉骨だけの依頼もできますか？', 'もちろん可能です。粉骨のみのご依頼も承っております（5,000円〜）。お手元供養やご自宅での保管をお考えの方にもご利用いただいています。'],
  ['お墓じまいの手続きがよく分かりません。', 'ご安心ください。改葬許可申請などの行政手続きから、墓石の撤去、ご遺骨の取り出し、新しい供養先のご提案まで、すべてサポートいたします。'],
  ['費用は事前に分かりますか？追加料金は？', 'お見積りは無料です。ご納得いただいてからのご契約となり、追加料金は一切いただきません。'],
  ['宗教・宗派は問われますか？', '宗教・宗派は問いません。どなたでも中立の立場でご相談を承ります。'],
  ['ペットの海洋散骨はできますか？', 'はい、承っております。鹿児島・錦江湾にて、半年に一度ペット専用の委託海洋葬を実施しています。'],
];

require __DIR__ . '/../includes/head.php';
?>
<body>
<?php require __DIR__ . '/../includes/header.php'; ?>
<section class="page-hero">
  <h1>よくあるご質問</h1>
  <p>ご相談前に、よくいただくご質問をまとめました</p>
</section>
<nav class="breadcrumb"><a href="/">ホーム</a> ＞ よくあるご質問</nav>

<main class="section">
  <div class="container" style="max-width:820px">
    <?php foreach ($faqs as $f): ?>
      <details style="background:var(--white);border:1px solid var(--border);border-radius:10px;padding:16px 20px;margin-bottom:12px">
        <summary style="font-weight:600;cursor:pointer;color:var(--green-mid)">Q. <?= h($f[0]) ?></summary>
        <p style="margin-top:10px;font-size:.95rem">A. <?= h($f[1]) ?></p>
      </details>
    <?php endforeach; ?>

    <div style="text-align:center;margin-top:32px">
      <p style="margin-bottom:16px">ここに無いご質問も、お気軽にお問い合わせください。</p>
      <a href="/contact/" class="btn">お問い合わせ</a>
      <a href="<?= h(SITE['line_url']) ?>" target="_blank" rel="noopener" class="btn btn--outline" style="margin-left:10px">LINEで相談</a>
    </div>
  </div>
</main>

<script type="application/ld+json"><?= json_encode([
  '@context' => 'https://schema.org',
  '@type'    => 'FAQPage',
  'mainEntity' => array_map(fn($f) => [
    '@type' => 'Question',
    'name'  => $f[0],
    'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f[1]],
  ], $faqs),
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?></script>
<?php require __DIR__ . '/../includes/footer.php'; ?>
