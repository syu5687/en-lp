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

$page_title     = $service['seo_title'] ?? ($service['title'] . '｜' . SITE['name']);
$page_desc      = $service['seo_desc'] ?? trim(($service['lead'] ?? '') !== '' ? ($service['lead'] . '（' . SITE['name'] . '・' . SITE['tagline'] . '）') : ($service['title'] . '｜' . SITE['name'] . '（' . SITE['tagline'] . '）'));
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

  <?php if (!empty($service['strength'])): ?>
  <section class="section">
    <div class="container" style="max-width:1000px">
      <p style="text-align:center;font-size:.78rem;letter-spacing:.28em;color:#b08b3e;font-weight:700;margin-bottom:8px">OUR STRENGTH</p>
      <h2 style="text-align:center;margin-bottom:28px"><?= h($service['strength_title'] ?? '私たちのこだわり') ?></h2>
      <div class="svc-strength">
        <?php foreach ($service['strength'] as $st): ?>
          <div class="svc-st">
            <?php if (!empty($st['img'])): ?><img src="<?= h($st['img']) ?>?v=<?= h(asset_ver()) ?>" alt="<?= h($st['alt'] ?? $st['title']) ?>" width="1000" height="666" loading="lazy"><?php endif; ?>
            <div class="svc-st__body">
              <?php if (!empty($st['tag'])): ?><p class="svc-st__tag"><?= h($st['tag']) ?></p><?php endif; ?>
              <h3><?= h($st['title']) ?></h3>
              <p><?= h($st['text']) ?></p>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
  <style>
    .svc-strength{display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:20px}
    .svc-st{background:#fff;border:1px solid var(--border);border-radius:14px;overflow:hidden;box-shadow:var(--shadow)}
    .svc-st img{width:100%;aspect-ratio:16/10;object-fit:cover;display:block}
    .svc-st__body{padding:18px 20px 22px}
    .svc-st__tag{display:inline-block;background:var(--green);color:#fff;font-size:.75rem;font-weight:700;padding:3px 14px;border-radius:999px;margin-bottom:10px}
    .svc-st h3{font-size:1.03rem;color:var(--green-mid);margin-bottom:8px}
    .svc-st__body p:last-child{font-size:.92rem;line-height:1.9}
  </style>
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

  <?php if (!empty($service['gallery'])): ?>
  <section class="section">
    <div class="container" style="max-width:960px">
      <h2 class="section-title" style="text-align:center"><?= h($service['gallery_title'] ?? '作品例') ?></h2>
      <p style="text-align:center;margin:8px 0 22px;font-size:.9rem;color:var(--text-light)">写真はクリック（タップ）で拡大できます</p>
      <div class="svc-gallery">
        <?php foreach ($service['gallery'] as $g): ?>
          <button type="button" class="svc-gal" data-img="<?= h($g['src']) ?>?v=<?= h(asset_ver()) ?>" aria-label="<?= h($g['alt']) ?>を拡大表示">
            <img src="<?= h($g['src']) ?>?v=<?= h(asset_ver()) ?>" alt="<?= h($g['alt']) ?>" loading="lazy">
            <span class="svc-gal__zoom">🔍</span>
          </button>
        <?php endforeach; ?>
      </div>
      <?php if (!empty($service['gallery_note'])): ?><p style="text-align:center;margin-top:14px;font-size:.88rem;color:var(--text-light)"><?= h($service['gallery_note']) ?></p><?php endif; ?>
    </div>
  </section>
  <div id="svc-lightbox" hidden>
    <img src="" alt="写真の拡大表示">
    <span id="svc-lightbox-close" aria-label="閉じる">×</span>
  </div>
  <style>
    .svc-gallery{display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:14px}
    .svc-gal{position:relative;display:block;padding:0;border:1px solid var(--border);border-radius:12px;overflow:hidden;background:#fff;cursor:zoom-in;box-shadow:var(--shadow);transition:.25s;font-family:inherit}
    .svc-gal:hover{transform:translateY(-3px);box-shadow:var(--shadow-hover)}
    .svc-gal img{width:100%;aspect-ratio:3/2;object-fit:cover;display:block}
    .svc-gal__zoom{position:absolute;right:8px;bottom:8px;background:rgba(21,112,158,.85);color:#fff;font-size:.72rem;font-weight:600;padding:3px 9px;border-radius:999px;pointer-events:none}
    #svc-lightbox{position:fixed;inset:0;z-index:9999;background:rgba(20,40,50,.86);display:flex;align-items:center;justify-content:center;padding:24px;cursor:zoom-out}
    #svc-lightbox[hidden]{display:none}
    #svc-lightbox img{max-width:92vw;max-height:92vh;border-radius:8px;box-shadow:0 20px 60px rgba(0,0,0,.5);background:#fff}
    #svc-lightbox-close{position:fixed;top:14px;right:20px;color:#fff;font-size:2rem;line-height:1;cursor:pointer;opacity:.85}
  </style>
  <script>
    (function () {
      var lb = document.getElementById('svc-lightbox');
      var im = lb.querySelector('img');
      document.querySelectorAll('.svc-gal').forEach(function (b) {
        b.addEventListener('click', function () { im.src = b.dataset.img; lb.hidden = false; document.body.style.overflow = 'hidden'; });
      });
      lb.addEventListener('click', function () { lb.hidden = true; im.src = ''; document.body.style.overflow = ''; });
      document.addEventListener('keydown', function (e) { if (e.key === 'Escape' && !lb.hidden) lb.click(); });
    })();
  </script>
  <?php endif; ?>

  <?php if (!empty($service['voices'])): ?>
  <section class="section" style="background:var(--cream)">
    <div class="container" style="max-width:900px">
      <p style="text-align:center;font-size:.78rem;letter-spacing:.28em;color:#b08b3e;font-weight:700;margin-bottom:8px">VOICE</p>
      <h2 style="text-align:center;margin-bottom:26px">ご利用いただいたお客様の声</h2>
      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:18px">
        <?php foreach ($service['voices'] as $v): ?>
          <div class="card">
            <?php if (!empty($v['tag'])): ?><p style="display:inline-block;background:var(--sea-light);color:var(--green);font-size:.72rem;font-weight:600;padding:3px 10px;border-radius:999px"><?= h($v['tag']) ?></p><?php endif; ?>
            <h3 style="margin:12px 0 10px;line-height:1.6;font-size:1rem">「<?= h($v['title']) ?>」</h3>
            <p style="font-size:.9rem;line-height:1.9"><?= h($v['text']) ?></p>
            <p style="text-align:right;font-size:.8rem;color:var(--text-light);margin-top:12px">（<?= h($v['who']) ?>）</p>
          </div>
        <?php endforeach; ?>
      </div>
      <p style="text-align:center;margin-top:22px"><a href="/voice/" class="btn btn--outline">お客様の声をもっと見る</a></p>
    </div>
  </section>
  <?php endif; ?>

  <?php if (!empty($service['flow'])): ?>
  <section class="section" style="background:var(--white)">
    <div class="container" style="max-width:820px">
      <h2>ご利用の流れ</h2>
      <ol style="display:grid;gap:14px;padding-left:0;list-style:none;counter-reset:step">
        <?php foreach ($service['flow'] as $st): $fa = is_array($st) ? $st : ['t' => $st]; ?>
          <li class="svc-flow__step" style="display:flex;gap:14px;align-items:center;flex-wrap:wrap<?= !empty($fa['img']) ? ';background:var(--cream);border-radius:12px;padding:14px 16px' : '' ?>">
            <span style="flex:none;width:30px;height:30px;border-radius:50%;background:var(--green);color:#fff;display:grid;place-items:center;font-weight:700;counter-increment:step"></span>
            <span style="flex:1;min-width:200px;padding-top:3px">
              <?php if (!empty($fa['d'])): ?><strong style="display:block;color:var(--green-mid)"><?= h($fa['t']) ?></strong><span style="font-size:.9rem;line-height:1.8"><?= h($fa['d']) ?></span>
              <?php else: ?><?= h($fa['t']) ?><?php endif; ?>
            </span>
            <?php if (!empty($fa['img'])): ?>
              <img src="<?= h($fa['img']) ?>?v=<?= h(asset_ver()) ?>" alt="<?= h($fa['alt'] ?? $fa['t']) ?>" width="1000" height="666" loading="lazy" class="svc-flow__img" style="width:170px;aspect-ratio:3/2;object-fit:cover;border-radius:10px">
            <?php endif; ?>
          </li>
        <?php endforeach; ?>
      </ol>
      <style>@media(max-width:560px){.svc-flow__img{width:100% !important}}</style>
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
