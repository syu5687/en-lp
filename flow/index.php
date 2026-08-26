<?php
require_once __DIR__ . '/../includes/config.php';
$page_title     = 'お申込みの流れ｜ご相談から施行・アフターサポートまで｜' . SITE['name'];
$page_desc      = '有限会社 縁へのお申込みの流れをご案内します。無料相談→お見積り→お申し込み→ご遺骨のお預かり→施行→アフターサポートまで。お見積り後の追加料金はありません。全国から郵送・委託でのご依頼も可能です。';
$page_canonical = SITE['url'] . '/flow/';
$page_hero_image = '/assets/img/hero-contact.jpg';
require __DIR__ . '/../includes/head.php';

$steps = [
  ['title' => 'ご相談（無料）', 'desc' => 'お電話・メールフォーム・LINEでお気軽にご相談ください。「まだ決めていない」「話を聞くだけ」でも大歓迎です。ご事情やご希望を伺いながら、供養の選択肢を一緒に考えます。急かすことは一切ありません。', 'note' => '', 'contacts' => true],
  ['title' => 'お見積り（無料）', 'desc' => 'ご希望の内容に沿って、無料でお見積りをご提示します。金額はお見積りで確定し、あとから追加料金をいただくことはありません。お墓じまい等は現地確認のうえでお出しします。', 'note' => '相見積もりも歓迎です'],
  ['title' => 'お申し込み', 'desc' => '内容にご納得いただけたら、正式にお申し込みください。日程やプランの詳細を確定します。ご家族で改めてご検討いただいてからで構いません。', 'note' => ''],
  ['title' => 'ご遺骨・お品のお預かり', 'desc' => 'ご遺骨は「郵送」「お持ち込み」「お引き取り」からご都合のよい方法でお預かりします。遠方の方も郵送で全国からご依頼いただけます。火葬許可証（埋葬許可証）のコピー等、必要書類はご案内します。', 'note' => '梱包方法・送り方は丁寧にご案内しますのでご安心ください'],
  ['title' => '施行', 'desc' => '海洋散骨・粉骨・お墓じまいなど、お申し込みの内容を心を込めて執り行います。海洋散骨では緯度・経度入りの散骨証明書を発行、お墓じまいでは工事前後の写真をご報告します。', 'note' => '海洋散骨は天候により日程が前後する場合があります'],
  ['title' => 'アフターサポート', 'desc' => '施行後のご供養も続けてお手伝いします。散骨海域を再訪するメモリアルクルーズ、お手元供養やジュエリーへの加工、ご遺品の整理まで。「その後」のご相談もいつでもどうぞ。', 'note' => ''],
];
?>
<body>
<?php require __DIR__ . '/../includes/header.php'; ?>
<section class="page-hero">
  <h1>お申込みの流れ</h1>
  <p>ご相談から施行、その後のサポートまで</p>
</section>
<nav class="breadcrumb"><a href="/">ホーム</a> ＞ お申込みの流れ</nav>

<main class="section">
  <div class="container" style="max-width:820px">
    <p class="lead" style="text-align:center;margin-bottom:36px">
      どのサービスも、基本の流れは同じ6ステップです。<br>
      お見積りまでは<strong>すべて無料</strong>。内容が決まっていなくても、ご相談から始められます。
    </p>

    <ol class="apply-steps">
      <?php foreach ($steps as $i => $st): ?>
        <li class="apply-step">
          <div class="apply-step__num"><?= $i + 1 ?></div>
          <div class="apply-step__body">
            <h2><?= h($st['title']) ?></h2>
            <p><?= h($st['desc']) ?></p>
            <?php if (!empty($st['contacts'])): ?>
              <div class="apply-contacts">
                <div class="apply-contact">
                  <p class="apply-contact__name">本社（鹿児島）</p>
                  <a class="apply-contact__tel" href="tel:<?= h(str_replace('-', '', SITE['tel'])) ?>"><?= h(SITE['tel']) ?></a>
                  <p class="apply-contact__hours"><?= h(SITE['hours_jp']) ?></p>
                </div>
                <div class="apply-contact">
                  <p class="apply-contact__name"><?= h(SITE['fukuoka']['name']) ?></p>
                  <a class="apply-contact__tel" href="tel:<?= h(str_replace('-', '', SITE['fukuoka']['tel'])) ?>"><?= h(SITE['fukuoka']['tel']) ?></a>
                  <p class="apply-contact__hours"><?= h(SITE['hours_jp']) ?></p>
                </div>
              </div>
              <p class="apply-step__note">※ <a href="/contact/" style="color:var(--green);font-weight:700">メールフォーム</a>・LINEは24時間受付しています</p>
            <?php endif; ?>
            <?php if ($st['note']): ?><p class="apply-step__note">※ <?= h($st['note']) ?></p><?php endif; ?>
          </div>
        </li>
      <?php endforeach; ?>
    </ol>

    <div style="background:var(--cream);border-radius:14px;padding:24px 26px;margin-top:36px">
      <h2 style="font-size:1.05rem;margin-bottom:12px">サービスごとの詳しい流れ</h2>
      <p style="font-size:.92rem;color:var(--text-light);margin-bottom:14px">施行の内容や必要書類はサービスによって異なります。詳しくは各ページをご覧ください。</p>
      <p class="apply-links">
        <a href="/kaiyou-sou/">海洋葬（海洋散骨）→</a>
        <a href="/powder-cleaning/">粉骨・洗骨 →</a>
        <a href="/grave/#flow">お墓じまい →</a>
        <a href="/seizen/">海洋散骨 生前契約 →</a>
        <a href="/pet-kaiyou-sou/">ペット供養 →</a>
        <a href="/ihinseiri/">遺品整理 →</a>
      </p>
    </div>

    <div style="text-align:center;margin-top:40px">
      <a href="/contact/" class="btn">まずは無料で相談する</a>
      <p style="margin-top:14px;font-size:.9rem;color:var(--text-light)">
        お電話でも承ります：<a href="tel:<?= h(SITE['tel']) ?>" style="color:var(--green);font-weight:700"><?= h(SITE['tel']) ?></a>（<?= h(SITE['hours_jp']) ?>）
      </p>
    </div>
  </div>
</main>

<style>
.apply-steps{list-style:none;margin:0;padding:0;position:relative}
.apply-steps::before{content:'';position:absolute;left:23px;top:14px;bottom:14px;width:2px;background:var(--sea-light)}
.apply-step{display:flex;gap:18px;margin-bottom:22px;position:relative}
.apply-step__num{flex:none;width:48px;height:48px;border-radius:50%;background:var(--green);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:1.15rem;z-index:1;box-shadow:0 0 0 4px #fff}
.apply-step__body{background:var(--white);border:1px solid var(--border);border-radius:12px;padding:18px 22px;flex:1}
.apply-step__body h2{font-size:1.08rem;margin-bottom:8px}
.apply-step__body p{font-size:.95rem;line-height:1.95}
.apply-step__note{margin-top:8px;font-size:.83rem !important;color:var(--text-light)}
.apply-contacts{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:12px;margin-top:14px}
.apply-contact{background:var(--cream);border:1px solid var(--border);border-radius:10px;padding:12px 16px;text-align:center}
.apply-contact__name{font-size:.82rem;font-weight:700;color:var(--green);margin-bottom:4px}
.apply-contact__tel{display:block;font-size:1.3rem;font-weight:700;color:var(--green-mid);text-decoration:none;letter-spacing:.04em}
.apply-contact__tel:hover{text-decoration:underline}
.apply-contact__hours{font-size:.78rem;color:var(--text-light);margin-top:2px}
.apply-links{display:flex;flex-wrap:wrap;gap:10px 22px}
.apply-links a{color:var(--green);font-weight:700;font-size:.93rem;text-decoration:underline}
</style>

<script type="application/ld+json">
{
  "@context":"https://schema.org",
  "@type":"BreadcrumbList",
  "itemListElement":[
    {"@type":"ListItem","position":1,"name":"ホーム","item":"https://en1150.co.jp/"},
    {"@type":"ListItem","position":2,"name":"お申込みの流れ","item":"https://en1150.co.jp/flow/"}
  ]
}
</script>
<script type="application/ld+json">
<?= json_encode([
  '@context' => 'https://schema.org',
  '@type' => 'HowTo',
  'name' => '有限会社 縁 お申込みの流れ',
  'description' => '無料相談からお見積り・お申し込み・ご遺骨のお預かり・施行・アフターサポートまでの6ステップ。',
  'step' => array_map(fn($st, $i) => [
    '@type' => 'HowToStep',
    'position' => $i + 1,
    'name' => $st['title'],
    'text' => $st['desc'],
  ], $steps, array_keys($steps)),
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>
</script>
<?php require __DIR__ . '/../includes/footer.php'; ?>
