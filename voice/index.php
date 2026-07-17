<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../admin/includes/store.php'; // voices_published()
$page_title     = 'お客様の声｜' . SITE['name'];
$page_desc      = '全国のお客様からいただいた海洋葬・お墓じまい・お手元供養などのご感想をご紹介します。' . SITE['name'] . '。';
$page_canonical = SITE['url'] . '/voice/';

// 管理画面（Firestore）から公開分を取得。未接続・未移行時は data/voices.json をフォールバック表示。
$voices = [];
try { $voices = voices_published(); } catch (Throwable $e) { $voices = []; }
if (!$voices) {
  $seed = @json_decode((string)@file_get_contents(__DIR__ . '/../data/voices.json'), true);
  foreach (($seed['items'] ?? []) as $v) {
    if (!empty($v['published'])) $voices[] = $v;
  }
  usort($voices, fn($a, $b) => strcmp($b['date'] ?? '', $a['date'] ?? ''));
}

require __DIR__ . '/../includes/head.php';
?>
<body>
<?php require __DIR__ . '/../includes/header.php'; ?>
<section class="page-hero">
  <h1>お客様の声</h1>
  <p>ご利用者様からいただいたご感想をご紹介します</p>
</section>
<nav class="breadcrumb"><a href="/">ホーム</a> ＞ お客様の声</nav>

<main class="section">
  <div class="container" style="max-width:900px">
    <div class="card-grid">
      <?php foreach ($voices as $v): ?>
        <div class="card">
          <p style="display:inline-block;background:var(--sea-light);color:var(--green);font-size:.72rem;font-weight:600;padding:3px 10px;border-radius:999px"><?= h($v['service']) ?></p>
          <h3 style="margin:12px 0 10px;line-height:1.6">「<?= h($v['title']) ?>」</h3>
          <p style="font-size:.85rem;color:var(--text-light);margin-bottom:8px"><strong>ご依頼のきっかけ：</strong><?= h($v['reason']) ?></p>
          <p style="font-size:.9rem;line-height:1.9"><?= h($v['impression']) ?></p>
          <p style="text-align:right;font-size:.8rem;color:var(--text-light);margin-top:12px">（<?= h($v['who']) ?>）</p>
        </div>
      <?php endforeach; ?>
    </div>

    <div style="text-align:center;margin-top:36px">
      <p style="margin-bottom:16px">ご相談・お見積りは無料です。お気軽にお問い合わせください。</p>
      <a href="/contact/" class="btn">お問い合わせ</a>
      <a href="<?= h(SITE['line_url']) ?>" target="_blank" rel="noopener" class="btn btn--outline" style="margin-left:10px">LINEで相談</a>
    </div>
  </div>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>
