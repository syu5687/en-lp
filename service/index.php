<?php
require_once __DIR__ . '/../includes/config.php';
$page_title     = 'サービス一覧｜' . SITE['name'];
$page_desc      = '海洋散骨・樹木葬・粉骨・洗骨・お手元供養・ペット供養・お墓じまい・遺品整理まで。' . SITE['name'] . 'のサービス一覧。';
$page_canonical = SITE['url'] . '/service/';

// 現行サイト（en1150.co.jp/service/）のグループ構成・説明文を反映
$groups = [
  [
    'heading' => '海洋葬・樹木葬',
    'items' => [
      ['title'=>'海洋葬（海洋散骨）', 'href'=>'/kaiyou-sou/', 'desc'=>'海洋散骨は、亡くなられた方のご遺骨を母なる海へ還すこと。弊社は一般社団法人海洋散骨協会に加盟しており、協会で取り決められた節度あるルールのもと、海洋葬として執り行っております。'],
      ['title'=>'庭苑葬（樹木葬）', 'href'=>'/teien-sou/', 'desc'=>'自然葬の中で樹木葬を選ばれる方も増えています。弊社では“庭苑葬”として、美しい草花に囲まれて眠る新しいスタイルをご提案。従来の家墓はもちろん、お一人様・お二人様の個別墓や永代供養墓もご用意しています。'],
    ],
  ],
  [
    'heading' => '粉骨・洗骨',
    'items' => [
      ['title'=>'ご遺骨のパウダー化（粉骨）', 'href'=>'/powder-cleaning/', 'desc'=>'粉骨とは、ご遺骨を粉末状（パウダー状）にすること。海洋散骨や樹木葬などの自然葬、ご自宅供養やアクセサリーなどのお手元供養にする際に行います。'],
      ['title'=>'ご遺骨のクリーニング（洗骨）', 'href'=>'/powder-cleaning/', 'desc'=>'長年お墓に入っていて泥などで汚れているご遺骨を、アルカリ水を使いすべて手作業で洗浄します。洗浄後は殺菌・乾燥まで丁寧に行います。'],
    ],
  ],
  [
    'heading' => 'ペット供養',
    'items' => [
      ['title'=>'ペット海洋葬', 'href'=>'/pet-kaiyou-sou/', 'desc'=>'大切なご家族（ペット）が安らかに眠ることのできる供養のカタチをご提案いたします。'],
    ],
  ],
  [
    'heading' => 'お手元供養',
    'items' => [
      ['title'=>'ご自宅供養', 'href'=>'/temoto-kuyou/', 'desc'=>'ご自宅供養は、本来墓地や寺院などに安置するご遺骨・ご遺灰を、ご自宅で安置するという方法です。一部をご自宅に安置したり、アクセサリーとして身につけたりもできます。'],
      ['title'=>'JEWELRYリフォーム', 'href'=>'/jewelry-reform/', 'desc'=>'大切な方から受け継いだアクセサリーや宝石、お手持ちのジュエリーを、メモリアルジュエリーとして「わたしらしいスタイル」でそばに感じていただけます。'],
    ],
  ],
  [
    'heading' => 'お墓のお悩み',
    'items' => [
      ['title'=>'お墓のお引越し（改葬）', 'href'=>'/hikkoshi/', 'desc'=>'お墓の引っ越し（改葬）とは、お墓や納骨堂からご遺骨を別の場所へ移すこと。必要な手続きの流れから、弊社がしっかりサポートいたします。'],
      ['title'=>'お墓じまい（お墓の整理）', 'href'=>'/grave/', 'desc'=>'少子高齢化・核家族化、都市への人口集中など、様々な理由によりお墓の管理が難しくなっています。お墓じまいも、お墓じまいの後も、弊社がしっかりサポートいたします。'],
    ],
  ],
  [
    'heading' => '遺品整理',
    'items' => [
      ['title'=>'遺品整理', 'href'=>'/ihinseiri/', 'desc'=>'遺品に準ずる家財・衣類・家電ほかの物品を、地域行政の分別処分に沿って仕分け（袋わけ）。家電リサイクル法を順守のうえ、お客様との委託契約を交わしながら正しく行ってまいります。'],
    ],
  ],
  [
    'heading' => 'セミナー・説明会',
    'items' => [
      ['title'=>'セミナー・説明会の実績', 'href'=>'/blog/', 'desc'=>'終活セミナーや供養の相談会など、これまでの取り組みの実績をご紹介いたします。', 'cta'=>'実績を見る'],
    ],
  ],
];

require __DIR__ . '/../includes/head.php';
?>
<body>
<?php require __DIR__ . '/../includes/header.php'; ?>
<section class="page-hero">
  <h1>サービス一覧</h1>
  <p>ご遺骨の供養を、ワンストップでサポートします</p>
</section>
<nav class="breadcrumb"><a href="/">ホーム</a> ＞ サービス一覧</nav>

<style>
.svc-group{margin-top:44px}
.svc-group:first-of-type{margin-top:0}
.svc-group__head{display:flex;align-items:center;gap:12px;margin-bottom:20px}
.svc-group__head h2{margin:0;font-family:var(--serif);font-size:1.35rem;color:var(--green-mid);font-weight:600;letter-spacing:.04em;white-space:nowrap}
.svc-group__head::after{content:'';flex:1;height:1px;background:var(--border)}
.svc-card{display:flex;flex-direction:column;background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);padding:24px;box-shadow:var(--shadow);transition:.3s}
.svc-card:hover{transform:translateY(-4px);box-shadow:var(--shadow-hover)}
.svc-card h3{font-family:var(--serif);font-size:1.12rem;color:var(--green-mid);margin-bottom:10px}
.svc-card p{font-size:.9rem;line-height:1.85;color:var(--text);flex:1}
.svc-card__more{margin-top:16px;align-self:flex-start;color:var(--green);font-weight:600;font-size:.88rem}
.svc-card:hover .svc-card__more{color:var(--header-blue)}
</style>

<main class="section">
  <div class="container">
    <?php foreach ($groups as $g): ?>
      <div class="svc-group">
        <div class="svc-group__head"><h2><?= h($g['heading']) ?></h2></div>
        <div class="card-grid">
          <?php foreach ($g['items'] as $it): ?>
            <a class="svc-card" href="<?= h($it['href']) ?>">
              <h3><?= h($it['title']) ?></h3>
              <p><?= h($it['desc']) ?></p>
              <span class="svc-card__more"><?= h($it['cta'] ?? '詳細を見る') ?> →</span>
            </a>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>
