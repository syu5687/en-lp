<?php
/**
 * サービス詳細ページ共通レンダラー。
 * 各サービスフォルダの index.php で $service 配列を定義してから require する。
 *
 * $service = [
 *   'slug','title','sub','price_label','lead',
 *   'intro' => [..paragraphs..],
 *   'points'=> [..「こんな方へ」..],
 *   'plans' => [ ['name','price','desc'], ... ]      // 任意
 *   'flow'  => [ '手順1', '手順2', ... ],             // 任意
 *   'faq'   => [ ['q','a'], ... ],                    // 任意
 * ];
 */
require_once __DIR__ . '/config.php';

$page_title     = $service['title'] . '｜' . SITE['name'];
$page_desc      = trim(($service['lead'] ?? '') !== '' ? ($service['lead'] . '（' . SITE['name'] . '・' . SITE['tagline'] . '）') : ($service['title'] . '｜' . SITE['name'] . '（' . SITE['tagline'] . '）'));
$page_canonical = SITE['url'] . '/' . $service['slug'] . '/';
// サービスごとの見出し背景画像（$service['hero_image'] があれば個別画像を使用）
if (!empty($service['hero_image'])) $page_hero_image = $service['hero_image'];

// Service 構造化データ（SEO/LLMO）
$jsonld = [
  '@context' => 'https://schema.org',
  '@type'    => 'Service',
  'serviceType' => $service['title'],
  'provider' => ['@type' => 'Organization', 'name' => SITE['name'], 'url' => SITE['url'] . '/'],
  'areaServed' => '鹿児島県を中心に全国対応',
  'name'     => $service['title'] . 'サービス',
  'description' => $service['lead'] ?? '',
];

// パンくず構造化データ
$breadcrumb = [
  '@context' => 'https://schema.org',
  '@type'    => 'BreadcrumbList',
  'itemListElement' => [
    ['@type'=>'ListItem','position'=>1,'name'=>'ホーム','item'=>SITE['url'].'/'],
    ['@type'=>'ListItem','position'=>2,'name'=>'サービス','item'=>SITE['url'].'/service/'],
    ['@type'=>'ListItem','position'=>3,'name'=>$service['title'],'item'=>SITE['url'].'/'.$service['slug'].'/'],
  ],
];

require __DIR__ . '/head.php';
?>
<body>
<?php require __DIR__ . '/header.php'; ?>

<section class="page-hero">
  <h1><?= h($service['title']) ?></h1>
  <?php if (!empty($service['sub'])): ?><p><?= h($service['sub']) ?></p><?php endif; ?>
  <?php if (!empty($service['price_label'])): ?>
    <?php $__labels = is_array($service['price_label']) ? $service['price_label'] : [$service['price_label']]; ?>
    <p style="margin-top:14px;display:flex;flex-wrap:wrap;gap:8px;justify-content:center">
      <?php foreach ($__labels as $__l): ?>
        <span style="display:inline-block;background:rgba(255,255,255,.18);padding:6px 18px;border-radius:999px;font-weight:700"><?= h($__l) ?></span>
      <?php endforeach; ?>
    </p>
  <?php endif; ?>
</section>
<nav class="breadcrumb"><a href="/">ホーム</a> ＞ <a href="/service/">サービス</a> ＞ <?= h($service['title']) ?></nav>

<main>
  <!-- 導入 -->
  <section class="section">
    <div class="container prose" style="max-width:820px">
      <?php if (!empty($service['lead'])): ?><p class="lead"><?= h($service['lead']) ?></p><?php endif; ?>
      <?php foreach (($service['intro'] ?? []) as $p): ?><p><?= h($p) ?></p><?php endforeach; ?>
    </div>
  </section>

  <?php if (!empty($service['points'])): ?>
  <section class="section" style="background:var(--white)">
    <div class="container" style="max-width:820px">
      <h2>こんな方におすすめです</h2>
      <ul style="list-style:none;display:grid;gap:12px">
        <?php foreach ($service['points'] as $pt): ?>
          <li style="padding:14px 18px;background:var(--cream);border-radius:10px;border-left:4px solid var(--green)"><?= h($pt) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  </section>
  <?php endif; ?>

  <?php if (!empty($service['plans'])): ?>
  <section class="section">
    <div class="container">
      <h2>プラン・料金</h2>
      <div class="plan-grid">
        <?php foreach ($service['plans'] as $pl): ?>
          <div class="plan-card">
            <div class="plan-card__media<?= empty($pl['img']) ? ' plan-card__media--ph' : '' ?>"<?php if (!empty($pl['img'])): ?> style="background-image:url('<?= h($pl['img']) ?>')"<?php endif; ?>>
              <?php if (empty($pl['img'])): ?><span class="plan-card__wave" aria-hidden="true"></span><?php endif; ?>
              <?php if (!empty($pl['price'])): ?><span class="plan-card__price"><?= h($pl['price']) ?></span><?php endif; ?>
            </div>
            <div class="plan-card__body">
              <h3><?= h($pl['name']) ?></h3>
              <?php if (!empty($pl['desc'])): ?><p><?= h($pl['desc']) ?></p><?php endif; ?>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
      <p style="font-size:.82rem;color:var(--text-light);margin-top:14px">※ 表示はすべて税込目安です。詳細はお見積り（無料）にてご案内します。</p>
    </div>
  </section>
  <?php endif; ?>

  <?php if (!empty($service['flow'])): ?>
  <section class="section" style="background:var(--white)">
    <div class="container" style="max-width:820px">
      <h2>ご利用の流れ</h2>
      <ol style="display:grid;gap:14px;padding-left:0;list-style:none;counter-reset:step">
        <?php foreach ($service['flow'] as $st): ?>
          <li style="display:flex;gap:14px;align-items:flex-start">
            <span style="flex:none;width:30px;height:30px;border-radius:50%;background:var(--green);color:#fff;display:grid;place-items:center;font-weight:700;counter-increment:step"></span>
            <span style="padding-top:3px"><?= h($st) ?></span>
          </li>
        <?php endforeach; ?>
      </ol>
      <style>ol[style*="counter-reset:step"] li span:first-child::before{content:counter(step)}</style>
    </div>
  </section>
  <?php endif; ?>

  <?php if (!empty($service['faq'])): ?>
  <section class="section">
    <div class="container" style="max-width:820px">
      <h2>よくあるご質問</h2>
      <?php foreach ($service['faq'] as $f): ?>
        <details style="background:var(--white);border:1px solid var(--border);border-radius:10px;padding:16px 20px;margin-bottom:12px">
          <summary style="font-weight:600;cursor:pointer;color:var(--green-mid)">Q. <?= h($f['q']) ?></summary>
          <p style="margin-top:10px;font-size:.95rem">A. <?= h($f['a']) ?></p>
        </details>
      <?php endforeach; ?>
    </div>
  </section>
  <?php endif; ?>

  <!-- CTA -->
  <section class="section" style="background:linear-gradient(135deg,var(--green),var(--green-mid));color:#fff;text-align:center">
    <div class="container">
      <h2 style="color:#fff">まずはお気軽にご相談ください</h2>
      <p style="opacity:.92;margin-bottom:22px">ご相談・お見積りは無料です。宗教・宗派は問いません。</p>
      <a href="/contact/" class="btn" style="background:#fff;color:var(--green-mid)">お問い合わせ</a>
      <a href="<?= h(SITE['line_url']) ?>" target="_blank" rel="noopener" class="btn" style="background:#06C755;margin-left:10px">LINEで相談</a>
      <p style="margin-top:18px"><a href="tel:<?= h(SITE['tel']) ?>" style="color:#fff;font-weight:700;font-size:1.2rem"><?= h(SITE['tel']) ?></a></p>
    </div>
  </section>
</main>

<script type="application/ld+json"><?= json_encode($jsonld, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?></script>
<script type="application/ld+json"><?= json_encode($breadcrumb, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?></script>
<?php require __DIR__ . '/footer.php'; ?>
