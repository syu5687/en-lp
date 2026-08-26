<?php
require_once __DIR__ . '/../includes/config.php';
$page_title     = 'サービス一覧｜' . SITE['name'];
$page_desc      = '海洋散骨・樹木葬・粉骨・洗骨・お手元供養・ペット供養・お墓じまい・遺品整理まで。' . SITE['name'] . 'のサービス一覧。';
$page_canonical = SITE['url'] . '/service/';
$page_hero_image = '/assets/img/hero-service.jpg';

// 現行サイト（en1150.co.jp/service/）のグループ構成・説明文を反映
$groups = [
  [
    'heading' => '海洋葬・樹木葬',
    'items' => [
      ['title'=>'海洋葬（海洋散骨）', 'href'=>'/kaiyou-sou/', 'img'=>'/assets/img/svc-kaiyou.jpg', 'desc'=>'海洋散骨は、亡くなられた方のご遺骨を母なる海へ還すこと。弊社は一般社団法人海洋散骨協会に加盟しており、協会で取り決められた節度あるルールのもと、海洋葬として執り行っております。'],
      ['title'=>'庭苑葬（樹木葬）', 'href'=>'/teien-sou/', 'img'=>'/assets/img/hero-teien-sou.jpg', 'desc'=>'自然葬の中で樹木葬を選ばれる方も増えています。弊社では“庭苑葬”として、美しい草花に囲まれて眠る新しいスタイルをご提案。従来の家墓はもちろん、お一人様・お二人様の個別墓や永代供養墓もご用意しています。'],
      ['title'=>'てらうみ', 'href'=>'https://www.teraumi.com', 'external'=>true, 'img'=>'/assets/img/plan-goudou.jpg', 'desc'=>'「お寺に納骨」＆「海へ散骨」の新しい供養のカタチ。粉骨したご遺骨の一部を寺院の納骨堂に納め、いつでもお参りできるようにしながら、残りのご遺骨を海へ散骨し自然に還します。福岡・熊本・鹿児島で展開しています。', 'cta'=>'公式サイトを見る'],
      ['title'=>'生前契約', 'href'=>'/seizen/', 'img'=>'/assets/img/hero-shindan.jpg', 'desc'=>'「海洋散骨をしたい」という想いを、お元気なうちに契約して託すサービスです。生前にご希望を形にしておくことで、ご自身の意思に沿った供養が実現でき、ご家族の負担も軽くなります。', 'cta'=>'詳しく見る'],
    ],
  ],
  [
    'heading' => '粉骨・洗骨',
    'items' => [
      ['title'=>'ご遺骨のパウダー化（粉骨）', 'href'=>'/powder-cleaning/', 'img'=>'/assets/img/svc-funkotsu.jpg', 'desc'=>'粉骨とは、ご遺骨を粉末状（パウダー状）にすること。海洋散骨や樹木葬などの自然葬、ご自宅供養やアクセサリーなどのお手元供養にする際に行います。'],
      ['title'=>'ご遺骨のクリーニング（洗骨）', 'href'=>'/powder-cleaning/', 'img'=>'/assets/img/svc-senkotsu.jpg', 'desc'=>'長年お墓に入っていて泥などで汚れているご遺骨を、アルカリ水を使いすべて手作業で洗浄します。洗浄後は殺菌・乾燥まで丁寧に行います。'],
    ],
  ],
  [
    'heading' => 'ペット供養',
    'items' => [
      ['title'=>'ペット海洋葬', 'href'=>'/pet-kaiyou-sou/', 'img'=>'/assets/img/hero-pet-kaiyou-sou.jpg', 'desc'=>'大切なご家族（ペット）が安らかに眠ることのできる供養のカタチをご提案いたします。'],
    ],
  ],
  [
    'heading' => 'お手元供養',
    'items' => [
      ['title'=>'ご自宅供養', 'href'=>'/temoto-kuyou/', 'img'=>'/assets/img/hero-temoto-kuyou.jpg', 'desc'=>'ご自宅供養は、本来墓地や寺院などに安置するご遺骨・ご遺灰を、ご自宅で安置するという方法です。一部をご自宅に安置したり、アクセサリーとして身につけたりもできます。'],
      ['title'=>'JEWELRYリフォーム', 'href'=>'/jewelry-reform/', 'img'=>'/assets/img/svc-jewelry.jpg', 'desc'=>'大切な方から受け継いだアクセサリーや宝石、お手持ちのジュエリーを、メモリアルジュエリーとして「わたしらしいスタイル」でそばに感じていただけます。'],
    ],
  ],
  [
    'heading' => 'お墓のお悩み',
    'items' => [
      ['title'=>'お墓のお引越し（改葬）', 'href'=>'/hikkoshi/', 'img'=>'/assets/img/hero-hikkoshi.jpg', 'desc'=>'お墓の引っ越し（改葬）とは、お墓や納骨堂からご遺骨を別の場所へ移すこと。必要な手続きの流れから、弊社がしっかりサポートいたします。'],
      ['title'=>'お墓じまい（お墓の整理）', 'href'=>'/grave/', 'img'=>'/assets/img/hero-grave.jpg', 'desc'=>'少子高齢化・核家族化、都市への人口集中など、様々な理由によりお墓の管理が難しくなっています。お墓じまいも、お墓じまいの後も、弊社がしっかりサポートいたします。'],
    ],
  ],
  [
    'heading' => '遺品整理',
    'items' => [
      ['title'=>'遺品整理', 'href'=>'/ihinseiri/', 'img'=>'/assets/img/svc-soudan.jpg', 'desc'=>'遺品に準ずる家財・衣類・家電ほかの物品を、地域行政の分別処分に沿って仕分け（袋わけ）。家電リサイクル法を順守のうえ、お客様との委託契約を交わしながら正しく行ってまいります。'],
      ['title'=>'仏壇じまい安心パック', 'coming'=>true, 'img'=>'/assets/img/hero-temoto-kuyou.jpg', 'desc'=>'お役目を終えたお仏壇のご供養からお引き取りまでを、まとめてお任せいただける安心のパックサービスです。詳細は準備が整い次第ご案内いたします。'],
    ],
  ],
  [
    'heading' => 'セミナー・説明会',
    'items' => [
      ['title'=>'セミナー・説明会の実績', 'href'=>'/blog/?cat=' . rawurlencode('セミナー・終活'), 'img'=>'/assets/img/svc-seminar.jpg', 'desc'=>'終活セミナーや供養の相談会など、これまでの取り組みの実績をご紹介いたします。', 'cta'=>'実績を見る'],
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
.svc-card{display:flex;flex-direction:column;background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);overflow:hidden;box-shadow:var(--shadow);transition:.3s}
.svc-card:hover{transform:translateY(-4px);box-shadow:var(--shadow-hover)}
.svc-card__img{display:block;aspect-ratio:16/9;overflow:hidden;background:#eef5f8}
.svc-card__img img{width:100%;height:100%;object-fit:cover;display:block;transition:transform .5s ease}
.svc-card:hover .svc-card__img img{transform:scale(1.05)}
.svc-card__body{display:flex;flex-direction:column;flex:1;padding:20px 24px 24px}
.svc-card h3{font-family:var(--serif);font-size:1.12rem;color:var(--green-mid);margin-bottom:10px}
.svc-card p{font-size:.9rem;line-height:1.85;color:var(--text);flex:1}
.svc-card__more{margin-top:16px;align-self:flex-start;color:var(--green);font-weight:600;font-size:.88rem}
.svc-card:hover .svc-card__more{color:var(--header-blue)}
/* 準備中カード（リンクなし） */
.svc-card--coming{cursor:default}
.svc-card--coming:hover{transform:none;box-shadow:var(--shadow)}
.svc-card--coming:hover .svc-card__img img{transform:none}
.svc-card--coming .svc-card__img{position:relative}
.svc-card--coming .svc-card__img img{filter:saturate(.6) brightness(.96)}
.svc-card--coming .svc-card__coming{position:absolute;top:10px;left:10px;background:var(--gold,#b18e63);background:#b18e63;color:#fff;font-size:.72rem;font-weight:700;letter-spacing:.12em;padding:4px 12px;border-radius:999px;box-shadow:0 2px 6px rgba(0,0,0,.2)}
.svc-card--coming .svc-card__more{color:var(--text-light)}
</style>

<main class="section">
  <div class="container">
    <?php foreach ($groups as $g): ?>
      <div class="svc-group">
        <div class="svc-group__head"><h2><?= h($g['heading']) ?></h2></div>
        <div class="card-grid">
          <?php foreach ($g['items'] as $it): ?>
            <?php $coming = !empty($it['coming']); $tag = $coming ? 'div' : 'a'; ?>
            <<?= $tag ?> class="svc-card<?= $coming ? ' svc-card--coming' : '' ?>"<?= $coming ? '' : ' href="' . h($it['href']) . '"' ?><?= !$coming && !empty($it['external']) ? ' target="_blank" rel="noopener"' : '' ?>>
              <?php if (!empty($it['img'])): ?>
                <span class="svc-card__img"><img src="<?= h($it['img']) ?>?v=<?= h(asset_ver()) ?>" alt="<?= h($it['title']) ?>" loading="lazy">
                  <?php if ($coming): ?><span class="svc-card__coming">準備中</span><?php endif; ?>
                </span>
              <?php endif; ?>
              <span class="svc-card__body">
                <h3><?= h($it['title']) ?></h3>
                <p><?= h($it['desc']) ?></p>
                <span class="svc-card__more"><?= $coming ? 'サービス準備中' : h($it['cta'] ?? '詳細を見る') . ' →' ?></span>
              </span>
            </<?= $tag ?>>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endforeach; ?>

    <!-- ご利用にあたってのご案内 -->
    <div style="margin-top:52px;background:var(--white);border:1px solid var(--border);border-radius:14px;padding:24px 28px">
      <p style="font-weight:700;color:var(--green-mid);margin-bottom:12px">ご利用にあたって</p>
      <p style="font-size:.92rem;color:var(--text-light);line-height:1.9;margin-bottom:14px">どのサービスも、ご相談・お見積りは無料です。お申し込み後のキャンセル・日程変更の取り扱いは、キャンセルポリシーをご確認ください。</p>
      <p style="display:flex;flex-wrap:wrap;gap:10px 24px">
        <a href="/flow/" style="color:var(--green);font-weight:700;text-decoration:underline">お申込みの流れ →</a>
        <a href="/policy/" style="color:var(--green);font-weight:700;text-decoration:underline">キャンセルポリシー →</a>
        <a href="/area/" style="color:var(--green);font-weight:700;text-decoration:underline">対応エリア →</a>
        <a href="/gokuyou/" style="color:var(--green);font-weight:700;text-decoration:underline">よくあるご質問 →</a>
      </p>
    </div>
  </div>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>
