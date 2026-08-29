<?php
/**
 * 粉骨・洗骨 専用ページ
 * 実際の作業写真・5つのこだわり・お客様の声・画像付きの流れを掲載。
 */
require_once __DIR__ . '/../includes/config.php';

$page_title     = '粉骨・洗骨｜全国郵送対応（ご遺骨のパウダー化・クリーニング）｜有限会社 縁';
$page_desc      = 'ご遺骨のパウダー化（粉骨24,200円〜）・クリーニング（洗骨27,500円〜）。発がん性物質「六価クロム」の検査・無害化処理に対応。乳鉢を使いすべて手作業で、一件ずつ丁寧に。異物除去・洗浄・殺菌・乾燥から真空パック・桐箱でのお返しまで。ご遺骨の郵送で全国どこからでもご利用いただけます。鹿児島・福岡はお持ち込みも可能。有限会社 縁。';
$page_canonical = SITE['url'] . '/powder-cleaning/';
$page_hero_image = '/assets/img/hero-powder-cleaning.jpg';
require __DIR__ . '/../includes/head.php';

$pc_img = static fn(string $f): string => '/powder-cleaning/images/' . $f . '?v=' . asset_ver();
?>
<body>
<?php require __DIR__ . '/../includes/header.php'; ?>

<section class="page-hero">
  <h1>粉骨・洗骨（ご遺骨のパウダー化・クリーニング）</h1>
  <p>すべて手作業で、一件ずつ丁寧に。ご遺骨の郵送で全国対応</p>
  <p style="margin-top:14px;display:flex;flex-wrap:wrap;gap:8px;justify-content:center">
    <span style="display:inline-block;background:rgba(255,255,255,.18);padding:6px 18px;border-radius:999px;font-weight:700">粉骨 24,200円〜</span>
    <span style="display:inline-block;background:rgba(255,255,255,.18);padding:6px 18px;border-radius:999px;font-weight:700">洗骨 27,500円〜</span>
  </p>
</section>
<nav class="breadcrumb"><a href="/">ホーム</a> ＞ <a href="/service/">サービス</a> ＞ 粉骨・洗骨</nav>

<main>
  <!-- 導入 -->
  <section class="section">
    <div class="container" style="max-width:860px">
      <div class="prose" style="max-width:820px;margin:0 auto">
        <p class="lead">粉骨とは文字どおり、ご遺骨を粉末状（パウダー状）にすることをいいます。海洋散骨や樹木葬などの自然葬、ご自宅供養やアクセサリーなどのお手元供養にする際には、この粉骨を行います。</p>
        <p>洗骨（ご遺骨のクリーニング）は、長年お墓に入っていて泥などで汚れているご遺骨を、アルカリ水を使いすべて手作業で洗浄すること。洗浄後は殺菌・乾燥まで丁寧に行います。</p>
        <p>粉骨のみのご依頼も承っております。お手元供養やご自宅での保管をお考えの方にもご利用いただけます。</p>
      </div>
      <img src="<?= h($pc_img('pc-staff.jpg')) ?>" alt="乳鉢と乳棒を使い、両手で丁寧にご遺骨を粉骨する様子" width="1400" height="933" loading="lazy"
           style="width:100%;aspect-ratio:16/9;object-fit:cover;border-radius:16px;box-shadow:0 10px 30px rgba(0,0,0,.12);margin-top:30px">
      <p style="text-align:center;margin-top:10px;font-size:.85rem;color:var(--text-light)">当社の粉骨室での実際の作業風景。機械任せにせず、スタッフが心を込めて行います。</p>
    </div>
  </section>

  <!-- こんな方へ -->
  <section class="section" style="background:var(--white)">
    <div class="container" style="max-width:820px">
      <h2>こんな方におすすめです</h2>
      <ul style="list-style:none;display:grid;gap:12px">
        <li style="padding:14px 18px;background:var(--cream);border-radius:10px;border-left:4px solid var(--green)">散骨・樹木葬の前にご遺骨をパウダー化したい方</li>
        <li style="padding:14px 18px;background:var(--cream);border-radius:10px;border-left:4px solid var(--green)">お墓じまいでご遺骨の湿気・カビ・汚れが気になる方</li>
        <li style="padding:14px 18px;background:var(--cream);border-radius:10px;border-left:4px solid var(--green)">お手元供養のためコンパクトに整えたい方</li>
        <li style="padding:14px 18px;background:var(--cream);border-radius:10px;border-left:4px solid var(--green)">遠方にお住まいの方（ご遺骨の郵送で全国からご利用いただけます）</li>
      </ul>
    </div>
  </section>

  <!-- 縁の粉骨・洗骨 5つのこだわり -->
  <section class="section">
    <div class="container" style="max-width:1000px">
      <div style="display:flex;align-items:center;justify-content:center;gap:30px;margin-bottom:32px;text-align:left" class="pc-st-head">
        <div>
          <p style="font-size:.78rem;letter-spacing:.28em;color:#b08b3e;font-weight:700;margin-bottom:8px">OUR COMMITMENT</p>
          <h2 style="margin:0 0 8px">縁の粉骨・洗骨、5つのこだわり</h2>
          <p style="color:var(--text-light);font-size:.95rem">大切な方のご遺骨だからこそ、一件ずつ・すべて手作業で。<br class="pc-only">お預かりからお返しまでの品質に妥協しません。</p>
        </div>
        <img src="/assets/img/daihyo-guide.jpg?v=<?= h(asset_ver()) ?>" alt="ご相談を案内する代表" width="360" height="360" loading="lazy" style="width:150px;height:150px;flex:none;border-radius:50%;border:5px solid #fff;box-shadow:0 8px 24px rgba(18,89,122,.16);background:#fff;object-fit:cover" class="pc-st-photo">
      </div>
      <style>@media(max-width:640px){.pc-st-head{gap:12px !important}.pc-st-photo{width:98px !important;height:98px !important;border-width:3px !important}}</style>
      <div class="pc-strength">
        <div class="pc-st">
          <img src="<?= h($pc_img('pc-funkotsu-hands.jpg')) ?>" alt="乳鉢と乳棒で丁寧に行う手作業の粉骨" width="1000" height="666" loading="lazy">
          <div class="pc-st__body">
            <p class="pc-st__tag">手作業</p>
            <h3>乳鉢を使った、丁寧な手作業</h3>
            <p>粉骨は乳鉢と乳棒を使い、すべて手作業。他のご遺骨と混ざることがないよう、一件ずつ心を込めてパウダー状に仕上げます。</p>
          </div>
        </div>
        <div class="pc-st">
          <img src="<?= h($pc_img('pc-ijobutsu.jpg')) ?>" alt="ピンセットと磁石でご遺骨から釘などの異物を取り除く作業" width="1000" height="666" loading="lazy">
          <div class="pc-st__body">
            <p class="pc-st__tag">異物除去</p>
            <h3>釘や金属も、ひとつずつ除去</h3>
            <p>火葬や埋葬の過程で混ざった釘・金属などの異物を、ピンセットと磁石を使ってひとつずつ取り除きます。散骨や環境への配慮にも欠かせない工程です。</p>
          </div>
        </div>
        <div class="pc-st">
          <img src="<?= h($pc_img('pc-alkali.jpg')) ?>" alt="アルカリ水に浸けてご遺骨を洗浄する洗骨の工程" width="1000" height="666" loading="lazy">
          <div class="pc-st__body">
            <p class="pc-st__tag">洗骨</p>
            <h3>アルカリ水＋超音波の洗浄</h3>
            <p>長年お墓の中で付いた泥や汚れは、アルカリ水での浸け置きとブラシの手洗い、超音波洗浄機を組み合わせて丁寧に落とします。</p>
          </div>
        </div>
        <div class="pc-st">
          <img src="<?= h($pc_img('pc-dryer.jpg')) ?>" alt="乾燥庫でご遺骨をしっかり乾燥させる工程" width="1000" height="666" loading="lazy">
          <div class="pc-st__body">
            <p class="pc-st__tag">殺菌・乾燥</p>
            <h3>専用機器で殺菌・乾燥、水分まで計測</h3>
            <p>洗浄後は専用の乾燥庫でしっかり乾燥・殺菌。水分計で乾燥状態を数値で確認してから次の工程へ進むので、カビや臭いの原因を残しません。</p>
          </div>
        </div>
        <div class="pc-st pc-st--wide">
          <img src="<?= h($pc_img('pc-kiribako.jpg')) ?>" alt="真空パックしたご遺骨を桐箱に納めてお返しする仕上げ" width="1000" height="666" loading="lazy">
          <div class="pc-st__body">
            <p class="pc-st__tag">仕上げ・お返し</p>
            <h3>真空パック＋桐箱で、湿気から守ってお返し</h3>
            <p>パウダー化したご遺骨は真空パックで密封し、桐箱に納めてお返しします。ご自宅での保管も安心。散骨用の水溶性袋など、その後のご供養に合わせた仕上げにも対応します。</p>
          </div>
        </div>
      </div>
    </div>
  </section>
  <style>
    .pc-strength{display:grid;grid-template-columns:repeat(auto-fit,minmax(290px,1fr));gap:20px}
    .pc-st{background:#fff;border:1px solid var(--border);border-radius:14px;overflow:hidden;box-shadow:var(--shadow)}
    .pc-st img{width:100%;aspect-ratio:16/9;object-fit:cover;display:block}
    .pc-st__body{padding:18px 20px 22px}
    .pc-st__tag{display:inline-block;background:var(--green);color:#fff;font-size:.75rem;font-weight:700;padding:3px 14px;border-radius:999px;margin-bottom:10px}
    .pc-st h3{font-size:1.05rem;color:var(--green-mid);margin-bottom:8px}
    .pc-st__body p:last-child{font-size:.92rem;line-height:1.9}
    .pc-st--wide{grid-column:1/-1;display:grid;grid-template-columns:minmax(240px,2fr) 3fr}
    .pc-st--wide img{height:100%;aspect-ratio:auto;min-height:200px}
    @media(max-width:640px){.pc-st--wide{display:block}.pc-st--wide img{aspect-ratio:16/9;min-height:0}}
  </style>

  <!-- 六価クロムの検査・無害化 -->
  <section class="section" style="background:linear-gradient(180deg,#fdf9f0,#fff)">
    <div class="container" style="max-width:1000px">
      <p style="text-align:center;font-size:.78rem;letter-spacing:.28em;color:#b08b3e;font-weight:700;margin-bottom:8px">SAFETY</p>
      <h2 style="text-align:center;margin-bottom:14px">ご存知ですか？ <span style="display:inline-block">ご遺骨と「六価クロム」のこと</span></h2>
      <p style="text-align:center;max-width:760px;margin:0 auto 10px;line-height:2">
        火葬炉の耐熱ステンレスなどに含まれるクロムが火葬の高温で酸化し、<br class="pc-only">ご遺骨に<strong>「六価クロム」という有害物質</strong>が付着・生成されることがあります。<br class="pc-only">
        六価クロムは国際がん研究機関（IARC）で発がん性が認められている物質で、土壌の環境基準も定められています。
      </p>
      <p style="text-align:center;max-width:760px;margin:0 auto 30px;line-height:2;font-size:.95rem;color:var(--text-light)">
        すべてのご遺骨から検出されるわけではありませんが、<strong style="color:var(--text)">検査をしなければ分かりません</strong>。<br class="pc-only">
        散骨や樹木葬でご遺骨を自然に還すとき、またお手元で保管されるときの安心のため、<br class="pc-only">当社では<strong style="color:var(--text)">検査と無害化処理を行ったうえで粉骨</strong>しています。
      </p>
      <p style="text-align:center;margin:0 auto 30px">
        <span style="display:inline-block;background:#b08b3e;color:#fff;font-weight:700;padding:9px 26px;border-radius:999px;font-size:.95rem">粉骨をお受けしたご遺骨に標準実施｜追加料金はいただきません</span>
      </p>
      <div class="pc-cr6-grid">
        <div class="pc-cr6">
          <img src="<?= h($pc_img('pc-cr6-check.jpg')) ?>" alt="六価クロム検査キットと標準色カード。検査液で六価クロムの有無を確認する" width="1400" height="933" loading="lazy">
          <div class="pc-cr6__body">
            <p class="pc-cr6__step">STEP 1</p>
            <h3>専用キットで検査</h3>
            <p>粉骨の前に、専用の検査キットでご遺骨に六価クロムが含まれていないかを確認します。</p>
          </div>
        </div>
        <div class="pc-cr6">
          <img src="<?= h($pc_img('pc-cr6-positive.jpg')) ?>" alt="六価クロムが検出され検査液がピンク色に変色した検査管と標準色カード" width="1400" height="933" loading="lazy">
          <div class="pc-cr6__body">
            <p class="pc-cr6__step">STEP 2</p>
            <h3>変色したら「検出」のサイン</h3>
            <p>六価クロムが含まれていると、検査液がピンク色に変色します。写真は実際に検出された際のものです。</p>
          </div>
        </div>
        <div class="pc-cr6">
          <img src="<?= h($pc_img('pc-cr6-agent.jpg')) ?>" alt="骨灰専用の六価クロム還元剤（無害化処理剤）のボトル" width="1400" height="933" loading="lazy">
          <div class="pc-cr6__body">
            <p class="pc-cr6__step">STEP 3</p>
            <h3>骨灰専用の還元剤で無害化</h3>
            <p>検出された場合は、骨灰専用の還元剤で毒性の低い三価クロムに還元し、無害化してから粉骨します。</p>
          </div>
        </div>
      </div>
      <p style="text-align:center;margin-top:24px;font-size:.95rem;line-height:2;max-width:760px;margin-left:auto;margin-right:auto">
        海に還すご遺骨だから、そして大切な方のご遺骨だからこそ。手間を惜しまず、確認できたことだけをお伝えする。<br class="pc-only">
        縁（えん）は<strong>2019年から六価クロム対策に取り組んでいる、環境配慮の先駆けの散骨・粉骨事業者</strong>です。<br>
        検査・無害化処理の<strong>費用はすべて粉骨料金に含まれており、追加料金はいただきません</strong>。
      </p>
      <div style="max-width:760px;margin:22px auto 0;background:#fff;border:1px solid #e3d5b8;border-left:4px solid #b08b3e;border-radius:12px;padding:18px 22px">
        <p style="font-weight:700;color:#8a6a2a;margin-bottom:6px">格安サービスとの違いは、こうした「見えない工程」にあります</p>
        <p style="font-size:.92rem;line-height:1.95;margin:0">六価クロムの検査・無害化は、手間と時間のかかる工程のため、格安をうたう粉骨・散骨サービスでは省略されていることが少なくありません。料金を比較される際は、金額だけでなく<strong>「六価クロムの検査をしていますか？」</strong>とひとこと確認してみてください。その業者の環境と安全への姿勢が分かります。
      </p>
    </div>
  </section>
  <style>
    .pc-cr6-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:18px}
    .pc-cr6{background:#fff;border:1px solid var(--border);border-radius:14px;overflow:hidden;box-shadow:var(--shadow)}
    .pc-cr6 img{width:100%;aspect-ratio:3/2;object-fit:cover;display:block}
    .pc-cr6__body{padding:16px 18px 20px}
    .pc-cr6__step{display:inline-block;background:#b08b3e;color:#fff;font-size:.72rem;font-weight:700;letter-spacing:.12em;padding:3px 12px;border-radius:999px;margin-bottom:9px}
    .pc-cr6 h3{font-size:1rem;color:var(--green-mid);margin-bottom:7px}
    .pc-cr6__body p:last-child{font-size:.9rem;line-height:1.85}
    @media(max-width:760px){.pc-cr6-grid{grid-template-columns:1fr}}
  </style>

  <!-- プラン・料金 -->
  <section class="section" style="background:var(--white)">
    <div class="container">
      <h2>プラン・料金</h2>
      <div class="plan-grid">
        <div class="plan-card">
          <div class="plan-card__media" style="background-image:url('<?= h($pc_img('pc-powder.jpg')) ?>')">
            <span class="plan-card__price">24,200円〜</span>
          </div>
          <div class="plan-card__body">
            <h3>ご遺骨のパウダー化（粉骨）</h3>
            <p>乳鉢を使いすべて手作業でご遺骨を粉末状に。散骨・樹木葬・お手元供養の前処理に。真空パック＋桐箱でお返しします。</p>
          </div>
        </div>
        <div class="plan-card">
          <div class="plan-card__media" style="background-image:url('<?= h($pc_img('pc-brush.jpg')) ?>')">
            <span class="plan-card__price">27,500円〜</span>
          </div>
          <div class="plan-card__body">
            <h3>ご遺骨のクリーニング（洗骨）</h3>
            <p>泥などで汚れたご遺骨を、アルカリ水とブラシですべて手作業で洗浄。超音波洗浄・殺菌・乾燥まで行います。</p>
          </div>
        </div>
      </div>
      <p style="font-size:.82rem;color:var(--text-light);margin-top:14px">※ 表示はすべて税込目安です。詳細はお見積り（無料）にてご案内します。</p>
    </div>
  </section>

  <!-- 作業風景ギャラリー -->
  <section class="section">
    <div class="container" style="max-width:960px">
      <p style="text-align:center;font-size:.78rem;letter-spacing:.28em;color:#b08b3e;font-weight:700;margin-bottom:8px">GALLERY</p>
      <h2 style="text-align:center;margin-bottom:10px">実際の作業風景</h2>
      <p style="text-align:center;color:var(--text-light);font-size:.95rem;margin-bottom:26px">お預かりしたご遺骨がどのように扱われるのか、工程を写真でご紹介します。（クリックで拡大できます）</p>
      <div class="pc-gallery">
        <?php
          $pc_gal = [
            ['pc-tray.jpg',       'トレイに広げて状態を確認したご遺骨'],
            ['pc-ijobutsu.jpg',   'ピンセットと磁石での異物除去'],
            ['pc-brush.jpg',      'ブラシを使った手作業の洗骨'],
            ['pc-ultrasonic.jpg', '超音波洗浄機での洗浄'],
            ['pc-dryer.jpg',      '乾燥庫での乾燥・殺菌'],
            ['pc-moisture.jpg',   '水分計による乾燥状態のチェック'],
            ['pc-moisture-dry.jpg', '水分量0.1%までしっかり乾燥させた状態'],
            ['pc-cr6-check.jpg',  '六価クロム検査キットでの検査'],
            ['pc-cr6-agent.jpg',  '骨灰専用の六価クロム還元剤（無害化処理）'],
            ['pc-funkotsu-hands.jpg', '乳鉢での手作業による粉骨'],
            ['pc-powder.jpg',     'きめ細かなパウダー状に仕上がったご遺骨'],
            ['pc-vacuum.jpg',     '真空パック機での密封'],
          ];
        ?>
        <?php foreach ($pc_gal as [$f, $alt]): ?>
          <button type="button" class="pc-gal" data-img="<?= h($pc_img($f)) ?>" aria-label="<?= h($alt) ?>を拡大表示">
            <img src="<?= h($pc_img($f)) ?>" alt="<?= h($alt) ?>" width="1000" height="666" loading="lazy">
            <span class="pc-gal__zoom">🔍</span>
          </button>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
  <div id="pc-lightbox" hidden>
    <img src="" alt="作業風景写真の拡大表示">
    <span id="pc-lightbox-close" aria-label="閉じる">×</span>
  </div>
  <style>
    .pc-gallery{display:grid;grid-template-columns:repeat(auto-fill,minmax(230px,1fr));gap:14px}
    .pc-gal{position:relative;display:block;padding:0;border:1px solid var(--border);border-radius:12px;overflow:hidden;background:#fff;cursor:zoom-in;box-shadow:var(--shadow);transition:.25s;font-family:inherit}
    .pc-gal:hover{transform:translateY(-3px);box-shadow:var(--shadow-hover)}
    .pc-gal img{width:100%;aspect-ratio:3/2;object-fit:cover;display:block}
    .pc-gal__zoom{position:absolute;right:8px;bottom:8px;background:rgba(21,112,158,.85);color:#fff;font-size:.72rem;font-weight:600;padding:3px 9px;border-radius:999px;pointer-events:none}
    #pc-lightbox{position:fixed;inset:0;z-index:9999;background:rgba(20,40,50,.86);display:flex;align-items:center;justify-content:center;padding:24px;cursor:zoom-out}
    #pc-lightbox[hidden]{display:none}
    #pc-lightbox img{max-width:92vw;max-height:92vh;border-radius:8px;box-shadow:0 20px 60px rgba(0,0,0,.5);background:#fff}
    #pc-lightbox-close{position:fixed;top:14px;right:20px;color:#fff;font-size:2rem;line-height:1;cursor:pointer;opacity:.85}
  </style>
  <script>
    (function () {
      var lb = document.getElementById('pc-lightbox');
      var im = lb.querySelector('img');
      document.querySelectorAll('.pc-gal').forEach(function (b) {
        b.addEventListener('click', function () { im.src = b.dataset.img; lb.hidden = false; document.body.style.overflow = 'hidden'; });
      });
      lb.addEventListener('click', function () { lb.hidden = true; im.src = ''; document.body.style.overflow = ''; });
      document.addEventListener('keydown', function (e) { if (e.key === 'Escape' && !lb.hidden) lb.click(); });
    })();
  </script>

  <!-- お客様の声 -->
  <section class="section" style="background:var(--cream)">
    <div class="container" style="max-width:860px">
      <p style="text-align:center;font-size:.78rem;letter-spacing:.28em;color:#b08b3e;font-weight:700;margin-bottom:8px">VOICE</p>
      <h2 style="text-align:center;margin-bottom:26px">ご利用いただいたお客様の声</h2>
      <blockquote style="margin:0 auto;max-width:680px;padding:20px 24px;border-left:4px solid var(--green);background:#fff;border-radius:0 12px 12px 0;box-shadow:var(--shadow)">
        <p style="font-size:.98rem;line-height:1.95">「海洋葬・散骨が明るい雰囲気でしたので、気が楽になりました。お世話になり、ありがとうございました。」</p>
        <cite style="display:block;margin-top:10px;font-style:normal;font-size:.82rem;color:var(--text-light)">—— 福岡県 60歳代 男性 Y様（粉骨・チャーター海洋葬／お墓じまい）</cite>
      </blockquote>
      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:18px;margin-top:18px">
        <div class="card">
          <p style="display:inline-block;background:var(--sea-light);color:var(--green);font-size:.72rem;font-weight:600;padding:3px 10px;border-radius:999px">お墓じまい＋粉骨・委託海洋葬</p>
          <h3 style="margin:12px 0 10px;line-height:1.6;font-size:1rem">「自分たちが動ける間に、きちんとした形で残したい」</h3>
          <p style="font-size:.9rem;line-height:1.9">ずっと気になっていたことを終えることができ、とてもホッとした気持ちです。ありがとうございました。</p>
          <p style="text-align:right;font-size:.8rem;color:var(--text-light);margin-top:12px">（神奈川県 60歳代 女性）</p>
        </div>
        <div class="card">
          <p style="display:inline-block;background:var(--sea-light);color:var(--green);font-size:.72rem;font-weight:600;padding:3px 10px;border-radius:999px">粉骨・委託海洋葬（ご郵送）</p>
          <h3 style="margin:12px 0 10px;line-height:1.6;font-size:1rem">「父が希望していた、海への散骨」</h3>
          <p style="font-size:.9rem;line-height:1.9">父が希望していた、鹿児島の海への散骨をしていただける業者様だったので依頼しました。お墓の引き継ぎの心配がなくなり、ホッとしています。</p>
          <p style="text-align:right;font-size:.8rem;color:var(--text-light);margin-top:12px">（千葉県 50歳代 男性）</p>
        </div>
      </div>
      <p style="text-align:center;margin-top:22px">
        <a href="/voice/" class="btn btn--outline">お客様の声をもっと見る</a>
      </p>
    </div>
  </section>

  <!-- ご利用の流れ（画像付き） -->
  <section class="section" style="background:var(--white)">
    <div class="container" style="max-width:860px">
      <p style="text-align:center;font-size:.78rem;letter-spacing:.28em;color:#b08b3e;font-weight:700;margin-bottom:8px">FLOW</p>
      <h2 style="text-align:center;margin-bottom:26px">ご利用の流れ</h2>
      <div class="pc-flow">
        <?php
          $pc_flow = [
            ['お問い合わせ・無料相談', 'お電話・LINE・メールフォームからお気軽にどうぞ。ご相談・お見積りは無料です。', '/assets/img/svc-soudan.jpg', 'スタッフによる無料相談の様子'],
            ['ご遺骨のお引取り', 'お持ち込み（鹿児島・福岡）のほか、ご郵送（ゆうパック）で全国からご利用いただけます。梱包方法もご案内します。', null, null],
            ['クリーニング（洗骨）', 'アルカリ水での洗浄・ブラシの手洗い・超音波洗浄で、泥や汚れを丁寧に落とします。', $pc_img('pc-alkali.jpg'), 'アルカリ水でのご遺骨の洗浄'],
            ['殺菌・乾燥', '専用の乾燥庫でしっかり乾燥・殺菌し、水分計で状態を確認します。', $pc_img('pc-dryer.jpg'), '乾燥庫での乾燥・殺菌'],
            ['パウダー化（粉骨）', '異物を取り除いたうえで、乳鉢を使いすべて手作業でパウダー状に仕上げます。', $pc_img('pc-funkotsu-hands.jpg'), '乳鉢での手作業による粉骨'],
            ['ご返送またはお引渡し', '真空パックで密封し、桐箱に納めてお返しします（お預かりから約1週間）。', $pc_img('pc-kiribako.jpg'), '真空パックと桐箱での仕上げ'],
          ];
        ?>
        <?php foreach ($pc_flow as $i => [$t, $d, $img, $alt]): ?>
          <div class="pc-flow__step">
            <div class="pc-flow__num"><?= $i + 1 ?></div>
            <div class="pc-flow__body">
              <h3><?= h($t) ?></h3>
              <p><?= h($d) ?></p>
            </div>
            <?php if ($img): ?>
              <img src="<?= h($img) ?><?= str_starts_with($img, '/assets/') ? '?v=' . h(asset_ver()) : '' ?>" alt="<?= h($alt) ?>" width="1000" height="666" loading="lazy" class="pc-flow__img">
            <?php else: ?>
              <div class="pc-flow__img pc-flow__img--ph" aria-hidden="true">郵送で<br>全国対応</div>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
  <style>
    .pc-flow{display:grid;gap:16px}
    .pc-flow__step{display:grid;grid-template-columns:44px 1fr 200px;gap:16px;align-items:center;background:#fff;border:1px solid var(--border);border-radius:14px;padding:18px 20px;box-shadow:var(--shadow)}
    .pc-flow__num{width:44px;height:44px;border-radius:50%;background:var(--green);color:#fff;display:grid;place-items:center;font-weight:700;font-size:1.15rem}
    .pc-flow__body h3{font-size:1.02rem;color:var(--green-mid);margin-bottom:6px}
    .pc-flow__body p{font-size:.9rem;line-height:1.85}
    .pc-flow__img{width:200px;aspect-ratio:3/2;object-fit:cover;border-radius:10px}
    .pc-flow__img--ph{display:grid;place-items:center;background:var(--sea-light);color:var(--green);font-weight:700;font-size:.9rem;text-align:center;line-height:1.5}
    @media(max-width:640px){
      .pc-flow__step{grid-template-columns:36px 1fr}
      .pc-flow__num{width:36px;height:36px;font-size:1rem}
      .pc-flow__img{grid-column:1/-1;width:100%}
    }
  </style>

  <!-- よくあるご質問 -->
  <?php
    $pc_faq = [
      ['q' => 'なぜ粉骨（パウダー化）が必要なのですか？',
       'a' => '海洋散骨や樹木葬などの自然葬を行う際は、ご遺骨をパウダー状にすることがルールとされています。また、粉骨するとご遺骨の容積が小さくなるため、ミニ骨壷でのお手元供養や納骨スペースの節約にも役立ちます。'],
      ['q' => 'ご遺骨に「六価クロム」という有害物質が含まれていると聞きました。本当ですか？',
       'a' => '含まれている場合があります。火葬炉の耐熱ステンレスなどに含まれるクロムが火葬の高温で酸化し、発がん性のある「六価クロム」となってご遺骨に付着・生成されることがあると言われています。すべてのご遺骨から検出されるわけではありませんが、検査をしなければ分かりません。当社では専用の検査キットで確認し、検出された場合は無害化処理を行ったうえで粉骨しています。'],
      ['q' => '六価クロムの検査・無害化処理はお願いできますか？',
       'a' => 'はい。当社では粉骨の工程のなかで、専用の検査キットによる六価クロムの検査を行い、検出された場合は骨灰専用の還元剤で毒性の低い三価クロムに還元する無害化処理を行っています。散骨や樹木葬でご遺骨を自然に還すときの環境への配慮としても、お手元供養で身近に保管されるときの安心のためにも大切な工程です。検査・無害化処理の費用は粉骨料金に含まれており、追加料金はいただきません。2019年から取り組んでいますので、安心してお任せください。'],
      ['q' => 'パウダー化して手元に戻るまで、どのくらいかかりますか？',
       'a' => 'ご郵送いただいてからお返しまで、約1週間ほどお時間をいただいております。ご相談内容によっては短縮も可能ですので、お急ぎの場合はお申し付けください。'],
      ['q' => '機械で処理するのですか？',
       'a' => 'いいえ。当社は乳鉢と乳棒を使い、すべて手作業で一件ずつ丁寧にパウダー化しています。機械任せにせず、スタッフが心を込めて行うのが当社のこだわりです。'],
      ['q' => '洗骨（クリーニング）はどのような場合に必要ですか？',
       'a' => 'お墓じまいなどで長年お墓に入っていたご遺骨は、泥や湿気で汚れていることがほとんどです。当社ではアルカリ水を使いすべて手作業で洗浄し、殺菌・乾燥まで丁寧に行ってから粉骨します。古いご遺骨もあきらめずにご相談ください。'],
      ['q' => 'どんな書類の提出が必要ですか？',
       'a' => '火葬許可証（または埋葬許可証など）と、当社指定の書類（同意書等）をいただいております。書類がお手元にない場合の手続きもご案内しますので、まずはご相談ください。'],
      ['q' => 'ご遺骨の郵送はどのようにすればよいですか？',
       'a' => '日本郵便のゆうパックでお送りいただけます（ご遺骨を送れるのは日本郵便のみです）。梱包の方法や必要書類は、お申し込み時に写真付きの資料で分かりやすくご案内しますのでご安心ください。'],
      ['q' => '直接持ち込むことはできますか？',
       'a' => 'はい。鹿児島本社・福岡営業所へのお持ち込みを承っています。ご訪問の日時は事前にご連絡ください。遠方の方はご郵送が便利です。'],
      ['q' => '粉骨後のご遺骨はどのような状態で戻ってきますか？',
       'a' => '真空パックで密封し、桐箱に納めてお返しします。湿気からご遺骨を守れるため、ご自宅での保管も安心です。散骨用の水溶性袋など、その後のご供養に合わせた仕上げにも対応します。'],
      ['q' => '分骨して一部だけ手元に残すことはできますか？',
       'a' => 'できます。パウダー化したご遺骨の一部をミニ骨壷やメモリアルジュエリーに、残りを散骨・納骨に、という形も承ります。ご希望をお聞かせください。'],
      ['q' => '費用はいくらですか？あとから追加料金はありませんか？',
       'a' => '粉骨24,200円〜・洗骨（クリーニング）27,500円〜（いずれも税込）です。ご遺骨の状態や骨壺の大きさにより異なる場合がありますので、無料のお見積りで金額を確定します。お見積り後の追加料金はありません。'],
      ['q' => '粉骨だけ頼んで、あとから散骨をお願いすることもできますか？',
       'a' => 'もちろんできます。まず粉骨してご自宅で供養し、お気持ちの整理がついてから海洋散骨をご依頼いただく方も多くいらっしゃいます。粉骨から散骨・証明書発行まで同じ窓口でお手伝いします。'],
    ];
  ?>
  <section class="section">
    <div class="container" style="max-width:820px">
      <h2 style="text-align:center;margin-bottom:8px">よくあるご質問</h2>
      <p style="text-align:center;font-size:.9rem;color:var(--text-light);margin-bottom:24px">粉骨・洗骨についてよくいただくご質問をまとめました。このほかのご質問もお気軽にお問い合わせください。</p>
      <?php foreach ($pc_faq as $f): ?>
        <details style="background:var(--white);border:1px solid var(--border);border-radius:10px;padding:16px 20px;margin-bottom:12px">
          <summary style="font-weight:600;cursor:pointer;color:var(--green-mid)">Q. <?= h($f['q']) ?></summary>
          <p style="margin-top:10px;font-size:.95rem;line-height:1.9">A. <?= h($f['a']) ?></p>
        </details>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- CTA -->
  <section class="section" style="background:linear-gradient(135deg,var(--green),var(--green-mid));color:#fff;text-align:center">
    <div class="container">
      <h2 style="color:#fff">まずはお気軽にご相談ください</h2>
      <p style="opacity:.92;margin-bottom:22px">ご相談・お見積りは無料です。宗教・宗派は問いません。</p>
      <a href="/contact/?service=<?= rawurlencode('粉骨・洗骨') ?>" class="btn" style="background:#fff;color:var(--green-mid)">お問い合わせ</a>
      <a href="<?= h(SITE['line_url']) ?>" target="_blank" rel="noopener" class="btn" style="background:#06C755;margin-left:10px">LINEで相談</a>
      <p style="margin-top:18px">
        本社（鹿児島）<a href="tel:<?= h(str_replace('-', '', SITE['tel'])) ?>" style="color:#fff;font-weight:700;font-size:1.2rem"><?= h(SITE['tel']) ?></a><br>
        <span style="font-size:.9rem"><?= h(SITE['fukuoka']['name']) ?> <a href="tel:<?= h(str_replace('-', '', SITE['fukuoka']['tel'])) ?>" style="color:#fff;font-weight:700"><?= h(SITE['fukuoka']['tel']) ?></a></span>
      </p>
    </div>
  </section>
</main>

<script type="application/ld+json">
{
  "@context":"https://schema.org",
  "@type":"Service",
  "serviceType":"粉骨・洗骨（ご遺骨のパウダー化・クリーニング）",
  "provider":{"@type":"Organization","name":"<?= h(SITE['name']) ?>","url":"<?= h(SITE['url']) ?>/"},
  "areaServed":"鹿児島・福岡はお持ち込み可、ご遺骨の郵送で全国対応",
  "name":"粉骨・洗骨サービス",
  "description":"ご遺骨のパウダー化（粉骨24,200円〜）・クリーニング（洗骨27,500円〜）。乳鉢を使いすべて手作業で一件ずつ丁寧に。真空パック＋桐箱でお返しします。"
}
</script>
<script type="application/ld+json">
{
  "@context":"https://schema.org",
  "@type":"BreadcrumbList",
  "itemListElement":[
    {"@type":"ListItem","position":1,"name":"ホーム","item":"https://en1150.co.jp/"},
    {"@type":"ListItem","position":2,"name":"サービス","item":"https://en1150.co.jp/service/"},
    {"@type":"ListItem","position":3,"name":"粉骨・洗骨","item":"https://en1150.co.jp/powder-cleaning/"}
  ]
}
</script>
<script type="application/ld+json">
<?= json_encode([
  '@context' => 'https://schema.org',
  '@type' => 'FAQPage',
  'mainEntity' => array_map(fn($f) => [
    '@type' => 'Question',
    'name' => $f['q'],
    'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f['a']],
  ], $pc_faq),
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>

</script>
<?php require __DIR__ . '/../includes/footer.php'; ?>
