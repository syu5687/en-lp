<?php
/**
 * 海洋葬（海洋散骨）専用ページ
 * 実際のセレモニー写真・お客様の声・縁の魅力・画像付きお申込みの流れを掲載。
 */
require_once __DIR__ . '/../includes/config.php';

$page_title     = '海洋散骨 鹿児島｜錦江湾の海洋葬・散骨なら有限会社 縁｜委託54,450円〜';
$page_desc      = '鹿児島で散骨・海洋散骨をお考えなら、日本海洋散骨協会加盟の有限会社 縁へ。海洋葬は錦江湾を中心とした鹿児島の海域に対応。合同148,500円〜・チャーター176,000円〜、立ち会い不要の委託海洋葬（54,450円〜）は全国からご利用いただけます。粉骨・墓じまいもワンストップ。';
$page_canonical = SITE['url'] . '/kaiyou-sou/';
$page_hero_image = '/assets/img/hero-kaiyou-sou.jpg';
require __DIR__ . '/../includes/head.php';

$ks_img = static fn(string $f): string => '/kaiyou-sou/images/' . $f . '?v=' . asset_ver();
?>
<body>
<?php require __DIR__ . '/../includes/header.php'; ?>

<section class="page-hero">
  <h1>鹿児島の海洋散骨（海洋葬）</h1>
  <p>「海に包まれて眠りたい」——大切な方の想いを、母なる海へ</p>
  <p style="margin-top:14px;display:flex;flex-wrap:wrap;gap:8px;justify-content:center">
    <span style="display:inline-block;background:#d8b46a;color:#1c2b33;padding:6px 18px;border-radius:999px;font-weight:700">委託海洋葬 54,450円〜（期間限定・通常66,000円）</span>
    <span style="display:inline-block;background:rgba(255,255,255,.18);padding:6px 18px;border-radius:999px;font-weight:700">合同海洋葬 148,500円〜</span>
    <span style="display:inline-block;background:rgba(255,255,255,.18);padding:6px 18px;border-radius:999px;font-weight:700">チャーター海洋葬 176,000円〜</span>
  </p>
</section>
<nav class="breadcrumb"><a href="/">ホーム</a> ＞ <a href="/service/">サービス</a> ＞ 海洋葬（海洋散骨）</nav>

<?php /* 地域の入口を明示する。福岡（博多湾）は専用ページに分けている */ ?>
<div class="ks-areaswitch">
  <p><strong>このページは鹿児島・錦江湾の海洋散骨</strong>についてご説明しています。福岡・博多湾での散骨をお探しの方は <a href="/kaiyou-sou/fukuoka/">福岡の海洋散骨ページ</a> をご覧ください。</p>
</div>
<style>
  .ks-areaswitch{max-width:860px;margin:14px auto 0;padding:0 24px}
  .ks-areaswitch p{background:#f2f8fa;border:1px solid #d3e6ee;border-radius:10px;padding:11px 16px;font-size:.88rem;line-height:1.8;color:#20505f}
  .ks-areaswitch a{color:var(--green);font-weight:700;text-decoration:underline}
</style>

<main>
  <!-- 導入 -->
  <section class="section">
    <div class="container" style="max-width:860px">
      <div class="prose" style="max-width:820px;margin:0 auto">
        <p class="lead">鹿児島で散骨・海洋散骨をお考えの方へ。海洋葬（海洋散骨）は、亡くなられた方のご遺骨を母なる海へ還すこと。大自然の一部として、命あるものの自然な還り方でもあります。</p>
        <p>死後、自然にご遺骨を還してほしいという想いの方へ最適の方法です。ただし、海にまくご遺骨は細かく砕く（粉骨）こと、散骨する場所を選ぶことなど、配慮が必要です。</p>
        <p>当社は一般社団法人日本海洋散骨協会に加盟し、協会で取り決められているルールを順守。環境にも配慮した海洋葬（散骨）を行っておりますので、安心してご相談ください。</p>
      </div>
      <img src="<?= h($ks_img('ks-sea-flowers.jpg')) ?>" alt="花びらが広がる海へ手を合わせる、海洋散骨セレモニーの様子" width="1600" height="1067" loading="lazy"
           style="width:100%;aspect-ratio:16/9;object-fit:cover;border-radius:16px;box-shadow:0 10px 30px rgba(0,0,0,.12);margin-top:30px">
      <p style="text-align:center;margin-top:10px;font-size:.85rem;color:var(--text-light)">花びらとともにご遺骨を海へ。当社が実施した海洋散骨セレモニーの実際の風景です。</p>
    </div>
  </section>

  <!-- こんな方へ -->
  <section class="section" style="background:var(--white)">
    <div class="container" style="max-width:820px">
      <h2>こんな方におすすめです</h2>
      <ul style="list-style:none;display:grid;gap:12px">
        <li style="padding:14px 18px;background:var(--cream);border-radius:10px;border-left:4px solid var(--green)">お墓の継承者がいない、管理の負担を残したくない方</li>
        <li style="padding:14px 18px;background:var(--cream);border-radius:10px;border-left:4px solid var(--green)">故人が海を愛していた、自然に還してあげたい方</li>
        <li style="padding:14px 18px;background:var(--cream);border-radius:10px;border-left:4px solid var(--green)">遠方にお住まいで、立ち会いが難しい方（委託海洋葬に対応）</li>
        <li style="padding:14px 18px;background:var(--cream);border-radius:10px;border-left:4px solid var(--green)"><a href="/grave/" style="color:inherit">墓じまい（お墓じまい）</a>とあわせて、取り出したご遺骨の行き先をお探しの方——撤去から散骨まで一社で完結できます</li>
      </ul>
    </div>
  </section>

  <!-- 縁の海洋散骨の魅力 -->
  <section class="section">
    <div class="container" style="max-width:1000px">
      <div style="display:flex;align-items:center;justify-content:center;gap:30px;margin-bottom:32px;text-align:left" class="ks-st-head">
        <div>
          <p style="font-size:.78rem;letter-spacing:.28em;color:#b08b3e;font-weight:700;margin-bottom:8px">OUR STRENGTH</p>
          <h2 style="margin:0 0 8px">縁の海洋散骨、5つの魅力</h2>
          <p style="color:var(--text-light);font-size:.95rem">安心・信頼・安全・価格・充実のオプション。<br class="sp-only">選ばれ続けるのには、理由があります。</p>
        </div>
        <img src="/assets/img/daihyo-guide.jpg?v=<?= h(asset_ver()) ?>" alt="ご相談を案内する代表" width="360" height="360" loading="lazy" style="width:150px;height:150px;flex:none;border-radius:50%;border:5px solid #fff;box-shadow:0 8px 24px rgba(18,89,122,.16);background:#fff;object-fit:cover" class="ks-st-photo">
      </div>
      <style>@media(max-width:640px){.ks-st-head{gap:12px !important}.ks-st-photo{width:98px !important;height:98px !important;border-width:3px !important}}</style>
      <div class="ks-strength">
        <div class="ks-st">
          <img src="<?= h($ks_img('ks-staff-sea.jpg')) ?>" alt="船上から海を見つめ、故人を偲ぶスタッフ" width="900" height="600" loading="lazy">
          <div class="ks-st__body">
            <p class="ks-st__tag">安心</p>
            <h3>寄り添う、専門スタッフ</h3>
            <p>事前のご相談から当日、アフターケアまで専門スタッフが寄り添い丁寧に対応。宗教・宗派を問わず、「話を聞くだけ」のご相談も歓迎です。</p>
          </div>
        </div>
        <div class="ks-st">
          <img src="<?= h($ks_img('ks-fukan.jpg')) ?>" alt="洋上で行われる海洋散骨セレモニー" width="1200" height="800" loading="lazy">
          <div class="ks-st__body">
            <p class="ks-st__tag">信頼</p>
            <h3>鹿児島で最初に始めて10年以上</h3>
            <p>まだ海洋葬が知られていない頃から鹿児島で最初に取り組み、鹿児島・福岡を中心に全国3,800件以上の実績。Google口コミ評価は★4.9をいただいています。</p>
          </div>
        </div>
        <div class="ks-st">
          <img src="<?= h($ks_img('ks-bow.jpg')) ?>" alt="出航した散骨クルーズ船の船首から望む海" width="1200" height="800" loading="lazy">
          <div class="ks-st__body">
            <p class="ks-st__tag">安全</p>
            <h3>協会ルールを順守した運航</h3>
            <p>日本海洋散骨協会の加盟事業者として、散骨海域の選定や環境への配慮などルールを順守。天候・海況を見極め、無理のない安全第一の運航を行います。</p>
            <p style="margin-top:12px"><span style="display:inline-flex;align-items:center;gap:10px;background:var(--sea-light);border-radius:10px;padding:8px 14px"><img src="/assets/img/jmas-logo.png?v=<?= h(asset_ver()) ?>" alt="一般社団法人 日本海洋散骨協会 ロゴ" width="360" height="454" loading="lazy" style="width:40px;height:auto"><span style="font-size:.76rem;line-height:1.6;color:#4a5a58">一般社団法人<br><strong style="color:#2a5a7a">日本海洋散骨協会</strong> 加盟事業者</span></span></p>
          </div>
        </div>
        <div class="ks-st">
          <img src="<?= h($ks_img('ks-kotsubako.jpg')) ?>" alt="丁寧にお預かりしたご遺骨" width="900" height="600" loading="lazy">
          <div class="ks-st__body">
            <p class="ks-st__tag">価格</p>
            <h3>追加料金のない明快な料金</h3>
            <p>委託海洋葬54,450円〜（期間限定・通常66,000円）。金額は無料のお見積りで確定し、あとから追加料金をいただくことはありません。</p>
          </div>
        </div>
        <div class="ks-st ks-st--wide">
          <img src="<?= h($ks_img('ks-bell.jpg')) ?>" alt="出航の合図に鳴らす船上の鐘" width="900" height="600" loading="lazy">
          <div class="ks-st__body">
            <p class="ks-st__tag">充実のオプション</p>
            <h3>「その後」まで、想いに応える</h3>
            <p>メモリアルクルーズ（散骨海域への再訪）、天国への手紙（無料）、当日の撮影、緯度・経度入りの散骨証明書、手元供養品・遺骨ジュエリーまで。散骨後も、想いをつなぐご供養をご用意しています。</p>
          </div>
        </div>
      </div>
    </div>
  </section>
  <style>
    .ks-strength{display:grid;grid-template-columns:repeat(auto-fit,minmax(290px,1fr));gap:20px}
    .ks-st{background:#fff;border:1px solid var(--border);border-radius:14px;overflow:hidden;box-shadow:var(--shadow)}
    .ks-st img{width:100%;aspect-ratio:16/9;object-fit:cover;display:block}
    .ks-st__body{padding:18px 20px 22px}
    .ks-st__tag{display:inline-block;background:var(--green);color:#fff;font-size:.75rem;font-weight:700;padding:3px 14px;border-radius:999px;margin-bottom:10px}
    .ks-st h3{font-size:1.05rem;color:var(--green-mid);margin-bottom:8px}
    .ks-st__body p:last-child{font-size:.92rem;line-height:1.9}
    .ks-st--wide{grid-column:1/-1;display:grid;grid-template-columns:minmax(240px,2fr) 3fr}
    .ks-st--wide img{height:100%;aspect-ratio:auto;min-height:200px}
    @media(max-width:640px){.ks-st--wide{display:block}.ks-st--wide img{aspect-ratio:16/9;min-height:0}}
  </style>

  <!-- プラン・料金 -->
  <section class="section" style="background:var(--white)">
    <div class="container">
      <h2>プラン・料金</h2>
      <div class="plan-grid">
        <?php
          $ks_plans = [
            ['name' => 'チャーター海洋葬', 'price' => '176,000円〜', 'img' => '/assets/img/plan-charter.jpg', 'desc' => '船を貸し切り、ご遺族様やご友人など親しい方だけで散骨を行います。'],
            ['name' => '合同海洋葬',       'price' => '148,500円〜', 'img' => '/assets/img/plan-goudou.jpg',  'desc' => '複数のご遺族様で乗り合わせ、または委託海洋葬の出港時に合わせて行います。'],
            ['name' => '委託海洋葬',       'price' => '54,450円〜',  'img' => '/assets/img/plan-itaku.jpg',   'desc' => 'ご遺族様に代わり、私たちスタッフが心を込めて行う海洋葬です。期間限定価格（通常66,000円）。'],
            ['name' => 'ペット海洋葬',     'price' => 'お問い合わせ', 'img' => '/assets/img/hero-pet-kaiyou-sou.jpg', 'desc' => '鹿児島・錦江湾にて、半年に一度行うペット専用の委託海洋葬です。'],
            ['name' => 'メモリアルクルーズ', 'price' => '176,000円', 'img' => '/assets/img/plan-cruise.jpg',  'desc' => '海洋葬を行なった海域で、ゆっくりとご供養いただけます。'],
            ['name' => '天国への手紙',     'price' => '無料',        'img' => '/assets/img/plan-tegami.jpg',  'desc' => '故人様への想いを手紙にしたため、海洋葬を行なった海域へお届けします。'],
          ];
        ?>
        <?php foreach ($ks_plans as $pl): ?>
          <div class="plan-card">
            <div class="plan-card__media" style="background-image:url('<?= h($pl['img']) ?>')">
              <span class="plan-card__price"><?= h($pl['price']) ?></span>
            </div>
            <div class="plan-card__body">
              <h3><?= h($pl['name']) ?></h3>
              <p><?= h($pl['desc']) ?></p>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
      <p style="font-size:.82rem;color:var(--text-light);margin-top:14px">※ 表示はすべて税込目安です。詳細はお見積り（無料）にてご案内します。キャンセル時の取り扱いは<a href="/policy/" style="color:var(--green);text-decoration:underline">キャンセルポリシー</a>をご覧ください。</p>
    </div>
  </section>

  <!-- 分骨という選択肢（手元供養・ジュエリーへの導線） -->
  <section class="section" id="bunkotsu" style="background:var(--cream)">
    <div class="container" style="max-width:1000px">
      <p style="text-align:center;font-size:.78rem;letter-spacing:.28em;color:#b08b3e;font-weight:700;margin-bottom:8px">BUNKOTSU</p>
      <h2 style="text-align:center;margin-bottom:14px">ご遺骨は、<span style="display:inline-block">すべて海に還さなくてもかまいません</span></h2>
      <p style="text-align:center;max-width:720px;margin:0 auto 8px;line-height:2">
        「全部撒いてしまったら、手を合わせる場所がなくなる気がして…」<br>
        ——ご相談のなかで、よくうかがう言葉です。
      </p>
      <p style="text-align:center;max-width:720px;margin:0 auto 30px;line-height:2">
        海洋散骨は、ご遺骨のすべてを撒かなければいけないものではありません。<br class="pc-only">
        パウダー化したご遺骨の大部分を海へ、ひとつまみをお手元に。<br class="pc-only">
        実際に、そのように分けて見送られるご家族が多くいらっしゃいます。
      </p>
      <div class="ks-bk-src">パウダー化したご遺骨を、ご希望の分だけお分けします（分骨）</div>
      <div class="ks-bk-arrows" aria-hidden="true"><span>↓</span><span>↓</span><span>↓</span></div>
      <div class="ks-bk-grid">
        <div class="ks-bk">
          <img src="/kaiyou-sou/images/ks-bk-kaiyou.jpg?v=<?= h(asset_ver()) ?>" alt="船上に用意された献花とご遺骨の桐箱。海洋散骨セレモニーの準備" width="1000" height="667" loading="lazy">
          <div class="ks-bk__body">
            <div class="ks-bk__head">1</div>
            <h3>すべてを海洋散骨に</h3>
            <p>ご遺骨のことをここで終えたい方に。散骨海域の緯度・経度入りの散骨証明書と当日のお写真が残るので、お参りはメモリアルクルーズや「天国への手紙」でできます。</p>
          </div>
        </div>
        <div class="ks-bk">
          <img src="/kaiyou-sou/images/ks-bk-temoto.jpg?v=<?= h(asset_ver()) ?>" alt="リビングの棚に置かれた、手のひらサイズの手元供養容器とおりん" width="1000" height="667" loading="lazy">
          <div class="ks-bk__body">
            <div class="ks-bk__head">2</div>
            <h3>大部分を散骨し、<br>一部をご自宅に</h3>
            <p>手のひらサイズのミニ骨壷（卵型・ガラス製など）に納めて、棚の上やリビングに。お仏壇がなくても置けます。お持ち込みの骨壷・ペンダントへの分骨は5,500円（税込）です。</p>
            <a href="/temoto-kuyou/" class="ks-bk__link">手元に残す方法を見る →</a>
          </div>
        </div>
        <div class="ks-bk">
          <img src="/kaiyou-sou/images/ks-bk-jewelry.jpg?v=<?= h(asset_ver()) ?>" alt="ご遺骨を封入できるゴールドのメモリアルリング" width="730" height="352" loading="lazy">
          <div class="ks-bk__body">
            <div class="ks-bk__head">3</div>
            <h3>大部分を散骨し、<br>ごく少量をジュエリーに</h3>
            <p>お米一粒ほどのご遺骨を、指輪の内側に封入します。見た目は普段使いのリングやペンダントなので、そのまま身につけて外出できます。お手持ちの指輪の加工も査定します。</p>
            <a href="/jewelry-reform/" class="ks-bk__link">メモリアルジュエリーを見る →</a>
          </div>
        </div>
      </div>
      <div style="max-width:760px;margin:26px auto 0;background:#fff;border:1px solid var(--border);border-left:4px solid var(--green);border-radius:12px;padding:18px 22px">
        <p style="font-weight:700;color:var(--green-mid);margin-bottom:6px">迷ったら、少量を残しておくことをおすすめしています</p>
        <p style="font-size:.92rem;line-height:1.95;margin:0">散骨したご遺骨は、あとから「少し残しておけばよかった」と思っても戻りません。一方、残しておいたご遺骨をあとから散骨することは、いつでもできます。ご家族で意見が分かれている場合も、まず一部を残しておけば、急いで結論を出す必要がなくなります。お母様はミニ骨壷に、娘様はペンダントに——と、ご家族それぞれで少しずつ分けることもできます。</p>
      </div>
      <p style="text-align:center;margin-top:24px">
        <a href="/contact/?service=<?= rawurlencode('海洋葬') ?>" class="btn btn--outline">散骨と手元供養、まとめて相談する（無料）</a>
      </p>
    </div>
  </section>
  <style>
    .ks-bk-src{max-width:560px;margin:0 auto;background:var(--green-mid,#12597a);color:#fff;text-align:center;font-weight:700;font-size:.95rem;padding:12px 18px;border-radius:12px}
    .ks-bk-arrows{display:grid;grid-template-columns:repeat(3,1fr);max-width:860px;margin:6px auto;text-align:center;color:var(--green-mid);font-size:1.2rem;font-weight:700}
    .ks-bk-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:16px}
    .ks-bk{background:#fff;border:1px solid var(--border);border-radius:14px;box-shadow:var(--shadow);display:flex;flex-direction:column;overflow:hidden}
    .ks-bk>img{width:100%;aspect-ratio:16/10;object-fit:cover;display:block}
    .ks-bk__body{padding:18px 20px 22px;display:flex;flex-direction:column;flex:1}
    .ks-bk__head{width:34px;height:34px;border-radius:50%;background:var(--green);color:#fff;display:grid;place-items:center;font-weight:700;margin-bottom:10px}
    .ks-bk h3{font-size:1.02rem;color:var(--green-mid);margin-bottom:8px;line-height:1.6}
    .ks-bk p{font-size:.9rem;line-height:1.9;flex:1}
    .ks-bk__link{display:inline-block;margin-top:12px;color:var(--green);font-weight:700;font-size:.92rem;text-decoration:none;border-bottom:2px solid var(--green)}
    @media(max-width:760px){
      .ks-bk-grid{grid-template-columns:1fr}
      .ks-bk-arrows{grid-template-columns:1fr}
      .ks-bk-arrows span:nth-child(n+2){display:none}
    }
  </style>

  <!-- セレモニーギャラリー -->
  <section class="section">
    <div class="container" style="max-width:960px">
      <p style="text-align:center;font-size:.78rem;letter-spacing:.28em;color:#b08b3e;font-weight:700;margin-bottom:8px">GALLERY</p>
      <h2 style="text-align:center;margin-bottom:10px">海洋散骨セレモニーの様子</h2>
      <p style="text-align:center;color:var(--text-light);font-size:.95rem;margin-bottom:26px">献花・献水・鐘の音とともに、心を込めてお送りします。（写真はクリックで拡大できます）</p>
      <div class="ks-gallery">
        <?php
          $ks_gal = [
            ['ks-ceremony.jpg',      '船上に用意された献花・献酒のセレモニーセット'],
            ['ks-hanabira.jpg',      '海へ手向ける花びらを納めた器'],
            ['ks-maku.jpg',          '花びらを海へ撒くセレモニーの瞬間'],
            ['ks-funkotsu-maku.jpg', 'パウダー状にしたご遺骨を海へ還す様子'],
            ['ks-kensui.jpg',        '散骨後に海へ水を手向ける献水'],
            ['ks-bell.jpg',          '故人を偲び鳴らす船上の鐘'],
          ];
        ?>
        <?php foreach ($ks_gal as [$f, $alt]): ?>
          <button type="button" class="ks-gal" data-img="<?= h($ks_img($f)) ?>" aria-label="<?= h($alt) ?>を拡大表示">
            <img src="<?= h($ks_img($f)) ?>" alt="<?= h($alt) ?>" width="900" height="600" loading="lazy">
            <span class="ks-gal__zoom">🔍</span>
          </button>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
  <div id="ks-lightbox" hidden>
    <img src="" alt="セレモニー写真の拡大表示">
    <span id="ks-lightbox-close" aria-label="閉じる">×</span>
  </div>
  <style>
    .ks-gallery{display:grid;grid-template-columns:repeat(auto-fill,minmax(230px,1fr));gap:14px}
    .ks-gal{position:relative;display:block;padding:0;border:1px solid var(--border);border-radius:12px;overflow:hidden;background:#fff;cursor:zoom-in;box-shadow:var(--shadow);transition:.25s;font-family:inherit}
    .ks-gal:hover{transform:translateY(-3px);box-shadow:var(--shadow-hover)}
    .ks-gal img{width:100%;aspect-ratio:3/2;object-fit:cover;display:block}
    .ks-gal__zoom{position:absolute;right:8px;bottom:8px;background:rgba(21,112,158,.85);color:#fff;font-size:.72rem;font-weight:600;padding:3px 9px;border-radius:999px;pointer-events:none}
    #ks-lightbox{position:fixed;inset:0;z-index:9999;background:rgba(20,40,50,.86);display:flex;align-items:center;justify-content:center;padding:24px;cursor:zoom-out}
    #ks-lightbox[hidden]{display:none}
    #ks-lightbox img{max-width:92vw;max-height:92vh;border-radius:8px;box-shadow:0 20px 60px rgba(0,0,0,.5);background:#fff}
    #ks-lightbox-close{position:fixed;top:14px;right:20px;color:#fff;font-size:2rem;line-height:1;cursor:pointer;opacity:.85}
  </style>
  <script>
    (function () {
      var lb = document.getElementById('ks-lightbox');
      var im = lb.querySelector('img');
      document.querySelectorAll('.ks-gal').forEach(function (b) {
        b.addEventListener('click', function () { im.src = b.dataset.img; lb.hidden = false; document.body.style.overflow = 'hidden'; });
      });
      lb.addEventListener('click', function () { lb.hidden = true; im.src = ''; document.body.style.overflow = ''; });
      document.addEventListener('keydown', function (e) { if (e.key === 'Escape' && !lb.hidden) lb.click(); });
    })();
  </script>

  <!-- お客様の声 -->
  <section class="section" style="background:var(--cream)">
    <div class="container" style="max-width:960px">
      <p style="text-align:center;font-size:.78rem;letter-spacing:.28em;color:#b08b3e;font-weight:700;margin-bottom:8px">VOICE</p>
      <h2 style="text-align:center;margin-bottom:26px">海洋葬をご利用いただいたお客様の声</h2>
      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:18px">
        <div class="card">
          <p style="display:inline-block;background:var(--sea-light);color:var(--green);font-size:.72rem;font-weight:600;padding:3px 10px;border-radius:999px">チャーター海洋葬</p>
          <h3 style="margin:12px 0 10px;line-height:1.6;font-size:1rem">「これで妻も喜んでいることでしょう」</h3>
          <p style="font-size:.9rem;line-height:1.9">最初のやり取りからこちらの希望がすべて叶い、とても喜んでいます。散骨場所も完全に希望通りとなり安心しました。当日の船上セレモニーやチャーター船の船長様にも大変優しくしていただき、感謝の念に堪えません。</p>
          <p style="text-align:right;font-size:.8rem;color:var(--text-light);margin-top:12px">（大阪府 70歳代 男性 M様）</p>
        </div>
        <div class="card">
          <p style="display:inline-block;background:var(--sea-light);color:var(--green);font-size:.72rem;font-weight:600;padding:3px 10px;border-radius:999px">合同海洋葬</p>
          <h3 style="margin:12px 0 10px;line-height:1.6;font-size:1rem">「狭いお墓の中よりも、広い海で過ごしたい」</h3>
          <p style="font-size:.9rem;line-height:1.9">主人が亡くなる寸前に「骨は海に撒いてほしい」と言われ、約束をしておりました。担当者様には終始、親切・丁寧に対応していただき、私自身も気持ちの整理がつきました。主人もきっと喜んでいることと思います。</p>
          <p style="text-align:right;font-size:.8rem;color:var(--text-light);margin-top:12px">（鹿児島県 60歳代 女性 Y様）</p>
        </div>
        <div class="card">
          <p style="display:inline-block;background:var(--sea-light);color:var(--green);font-size:.72rem;font-weight:600;padding:3px 10px;border-radius:999px">委託海洋葬</p>
          <h3 style="margin:12px 0 10px;line-height:1.6;font-size:1rem">「大好きな海で育ち、そこへ帰れたことが何よりです」</h3>
          <p style="font-size:.9rem;line-height:1.9">子供がいないためお墓を作ってもという思いと、主人が以前から海洋葬を望んでいました。大好きな海で育ち、そこへ帰れたことが何よりです。本人が一番喜んでいると思います。</p>
          <p style="text-align:right;font-size:.8rem;color:var(--text-light);margin-top:12px">（熊本県 70歳代 女性 T様）</p>
        </div>
      </div>
      <p style="text-align:center;margin-top:22px">
        <a href="/voice/" class="btn btn--outline">お客様の声をもっと見る</a>
      </p>
    </div>
  </section>

  <!-- 海洋散骨レポート（共通パーツ・鹿児島の記事＋地域を問わない記事のみ表示）
       福岡ページにだけ置かれていたブロックを v0249 で共通化し、本体ページにも掲載した -->
  <?php
    $br_region = 'kagoshima';
    $br_title  = '鹿児島の海洋散骨レポート';
    $br_lead   = '錦江湾での海洋散骨の様子をブログでご紹介しています。当日の雰囲気づくりの参考にご覧ください。';
    require __DIR__ . '/../includes/blog-reports.php';
  ?>

  <!-- お申込みの流れ（画像付き） -->
  <section class="section" style="background:var(--white)">
    <div class="container" style="max-width:860px">
      <p style="text-align:center;font-size:.78rem;letter-spacing:.28em;color:#b08b3e;font-weight:700;margin-bottom:8px">FLOW</p>
      <h2 style="text-align:center;margin-bottom:26px">お申込みの流れ</h2>
      <div class="ks-flow">
        <?php
          $ks_flow = [
            ['お問い合わせ・無料相談', 'お電話・LINE・メールフォームからお気軽にどうぞ。「話を聞くだけ」でも歓迎です。ご相談・お見積りは無料です。', '/assets/img/svc-soudan.jpg', 'スタッフによる無料相談の様子'],
            ['プランのご提案とお見積り', 'ご希望・ご事情をうかがい、最適なプランをご提案。金額はお見積りで確定し、追加料金はありません。', $ks_img('ks-mitsumori.jpg'), 'スタッフがプランと見積りをご説明する打ち合わせの様子'],
            ['ご遺骨のお引取り・お預かり', 'お持ち込みのほか、ご郵送（ゆうパック）にも対応。お引取りにうかがうこともできます。大切に、丁寧にお預かりします。', $ks_img('ks-hikitori.jpg'), '大切にお預かりするご遺骨の骨壷'],
            ['粉骨（パウダー化）', '海洋散骨のルールに沿って、ご遺骨を専用の設備でパウダー状にします。立ち会いをご希望の場合はご相談ください。', '/assets/img/svc-funkotsu.jpg', '粉骨作業の様子'],
            ['海洋葬の実施', '献花・献水・鐘の音とともに、心を込めてご遺骨を海へお還しします。当日の様子は撮影し、お届けします。', $ks_img('ks-maku.jpg'), '花びらとともに行う海洋散骨セレモニー'],
            ['散骨証明書のお渡し・アフターサポート', '緯度・経度入りの散骨証明書をお渡しします。メモリアルクルーズや手元供養など、「その後」のご供養もお手伝いします。', '/assets/img/certificate.jpg', '緯度・経度入りの海洋葬証明書'],
          ];
        ?>
        <?php foreach ($ks_flow as $i => [$t, $d, $img, $alt]): ?>
          <div class="ks-flow__step">
            <div class="ks-flow__num"><?= $i + 1 ?></div>
            <div class="ks-flow__body">
              <h3><?= h($t) ?></h3>
              <p><?= h($d) ?></p>
            </div>
            <?php if ($img): ?>
              <img src="<?= h($img) ?><?= str_starts_with($img, '/assets/') ? '?v=' . h(asset_ver()) : '' ?>" alt="<?= h($alt) ?>" width="900" height="600" loading="lazy" class="ks-flow__img<?= str_contains((string)$img, 'certificate') ? ' ks-flow__img--cert' : '' ?>">
            <?php else: ?>
              <div class="ks-flow__img ks-flow__img--ph" aria-hidden="true">無料<br>見積り</div>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      </div>
      <p style="text-align:center;margin-top:24px"><a href="/flow/" style="color:var(--green);font-weight:700;text-decoration:underline">お申込みの流れをくわしく見る →</a></p>
    </div>
  </section>
  <style>
    .ks-flow{display:grid;gap:16px}
    .ks-flow__step{display:grid;grid-template-columns:44px 1fr 200px;gap:16px;align-items:center;background:#fff;border:1px solid var(--border);border-radius:14px;padding:18px 20px;box-shadow:var(--shadow)}
    .ks-flow__num{width:44px;height:44px;border-radius:50%;background:var(--green);color:#fff;display:grid;place-items:center;font-weight:700;font-size:1.15rem}
    .ks-flow__body h3{font-size:1.02rem;color:var(--green-mid);margin-bottom:6px}
    .ks-flow__body p{font-size:.9rem;line-height:1.85}
    .ks-flow__img{width:200px;aspect-ratio:3/2;object-fit:cover;border-radius:10px}
    .ks-flow__img--cert{aspect-ratio:3/4;object-fit:cover;object-position:center 42%;background:#f2efe8;border:1px solid var(--border)}
    .ks-flow__img--ph{display:grid;place-items:center;background:var(--sea-light);color:var(--green);font-weight:700;font-size:.9rem;text-align:center;line-height:1.5}
    @media(max-width:640px){
      .ks-flow__step{grid-template-columns:36px 1fr}
      .ks-flow__num{width:36px;height:36px;font-size:1rem}
      .ks-flow__img{grid-column:1/-1;width:100%}
      .ks-flow__img--cert{width:min(68%,250px);margin:0 auto}
    }
  </style>

  <!-- 資料請求CTA -->
  <?php require __DIR__ . '/../includes/shiryou-cta.php'; ?>

  <!-- 実施予定日（管理画面から更新） -->
  <?php require __DIR__ . '/../includes/goudou-schedule.php'; ?>

  <!-- 県外にお住まいの方へ -->
  <section class="section" id="kengai" style="background:linear-gradient(180deg,#f4f9fb,#e9f3f7)">
    <div class="container" style="max-width:960px">
      <p style="text-align:center;font-size:.78rem;letter-spacing:.28em;color:#b08b3e;font-weight:700;margin-bottom:8px">NATIONWIDE</p>
      <h2 style="text-align:center;margin-bottom:14px">県外にお住まいの方へ</h2>
      <p style="text-align:center;max-width:720px;margin:0 auto 26px;line-height:2">
        「実家が鹿児島・福岡にある」「故郷の海に還してあげたい」——<br class="pc-only">
        そんな方のために、<strong>帰省しなくてもご利用いただける委託海洋葬（54,450円〜）</strong>をご用意しています。<br class="pc-only">
        ご遺骨はゆうパックでのご郵送でお預かりし、粉骨から散骨、証明書のお届けまで当社がすべて代行。<strong>全国どこにお住まいでもご利用いただけます。</strong>
      </p>
      <p style="text-align:center;margin:-10px 0 26px;font-size:.92rem">福岡の海（博多湾）でのお見送りをご希望の方は <a href="/fukuoka/" style="color:var(--green);font-weight:700">福岡営業所のページ →</a><br class="sp-only">墓じまいで取り出したご遺骨を散骨する場合は <a href="/grave/sankotsu/" style="color:var(--green);font-weight:700">墓じまい後の散骨 →</a></p>
      <div style="display:flex;flex-wrap:wrap;gap:8px;justify-content:center;margin-bottom:30px">
        <span style="background:#fff;border:1px solid var(--border);border-radius:999px;padding:6px 16px;font-size:.85rem;font-weight:700;color:var(--green-mid)">帰省・立ち会い不要</span>
        <span style="background:#fff;border:1px solid var(--border);border-radius:999px;padding:6px 16px;font-size:.85rem;font-weight:700;color:var(--green-mid)">ご遺骨は郵送でOK</span>
        <span style="background:#fff;border:1px solid var(--border);border-radius:999px;padding:6px 16px;font-size:.85rem;font-weight:700;color:var(--green-mid)">お墓じまいからワンストップ</span>
        <span style="background:#fff;border:1px solid var(--border);border-radius:999px;padding:6px 16px;font-size:.85rem;font-weight:700;color:var(--green-mid)">散骨証明書を発行</span>
      </div>
      <div class="ks-kengai-steps">
        <?php
          $ks_kengai = [
            ['お電話・LINE・メールでご相談', '全国からご相談いただけます。ご事情やご希望をうかがい、お見積りを無料でご案内します。'],
            ['ご遺骨をゆうパックでご郵送', '梱包の方法や送り方は、写真付きの資料でわかりやすくご案内。日本郵便のゆうパックで安全にお送りいただけます。'],
            ['粉骨〜海洋散骨を当社が代行', '協会ルールに沿って丁寧に粉骨し、鹿児島・錦江湾などの海域で心を込めて散骨いたします。'],
            ['証明書とお写真をお届け', '散骨海域の緯度・経度入りの散骨証明書と、当日のセレモニーのお写真をご自宅へお届けします。'],
          ];
        ?>
        <?php foreach ($ks_kengai as $i => [$t, $d]): ?>
          <div class="ks-kengai-step">
            <div class="ks-kengai-step__num"><?= $i + 1 ?></div>
            <h3><?= h($t) ?></h3>
            <p><?= h($d) ?></p>
          </div>
        <?php endforeach; ?>
      </div>
      <figure style="margin:26px auto 0;max-width:270px;text-align:center">
        <img src="/assets/img/certificate.jpg?v=<?= h(asset_ver()) ?>" alt="緯度・経度入りの海洋葬証明書" width="800" height="1074" loading="lazy"
             style="width:100%;height:auto;border-radius:12px;border:1px solid var(--border);box-shadow:0 8px 22px rgba(40,60,50,.12);background:#f2efe8">
        <figcaption style="margin-top:10px;font-size:.82rem;color:var(--text-light)">実際にお渡ししている「海洋葬証明書」。散骨海域の緯度・経度と当日のお写真入りです。</figcaption>
      </figure>
      <p style="text-align:center;margin-top:22px;font-size:.9rem;color:var(--text-light)">
        お墓じまい（改葬手続き・墓石の撤去）からご遺骨の受け入れ、海洋散骨までまとめてのご依頼も可能です。<br class="pc-only">
        「何から始めればいいかわからない」という段階でも、どうぞお気軽にご相談ください。
      </p>
      <div style="text-align:center;margin-top:18px">
        <a href="/contact/?service=<?= rawurlencode('海洋葬') ?>" class="btn">県外からのご相談はこちら</a>
        <a href="<?= h(SITE['line_url']) ?>" target="_blank" rel="noopener" class="btn btn--outline" style="margin-left:10px">LINEで相談</a>
      </div>
    </div>
  </section>
  <style>
    .ks-kengai-steps{display:grid;grid-template-columns:repeat(4,1fr);gap:14px}
    .ks-kengai-step{background:#fff;border:1px solid var(--border);border-radius:14px;padding:20px 18px;box-shadow:var(--shadow);text-align:center}
    .ks-kengai-step__num{width:40px;height:40px;border-radius:50%;background:var(--green);color:#fff;display:grid;place-items:center;font-weight:700;font-size:1.05rem;margin:0 auto 12px}
    .ks-kengai-step h3{font-size:.98rem;color:var(--green-mid);margin-bottom:8px;line-height:1.5}
    .ks-kengai-step p{font-size:.85rem;line-height:1.8;text-align:left}
    @media(max-width:900px){.ks-kengai-steps{grid-template-columns:repeat(2,1fr)}}
    @media(max-width:520px){.ks-kengai-steps{grid-template-columns:1fr}}
  </style>

  <!-- 選ばれる品質（価格だけで選ばないで） -->
  <section class="section" style="background:var(--white)">
    <div class="container" style="max-width:960px">
      <p style="text-align:center;font-size:.78rem;letter-spacing:.28em;color:#b08b3e;font-weight:700;margin-bottom:8px">QUALITY</p>
      <h2 style="text-align:center;margin-bottom:14px">料金の安さだけで選ばないでください</h2>
      <p style="text-align:center;max-width:720px;margin:0 auto 28px;line-height:2">
        海洋散骨を行う業者は年々増え、なかには格安をうたうサービスもあります。<br class="pc-only">
        しかし「実際にどの海域で散骨されたのかわからない」「証明書が発行されない」「あとから追加料金を請求された」——そんなケースも報告されています。<br class="pc-only">
        大切な方のご遺骨を託す、一度きりのご供養だからこそ、<strong>料金だけでなく「どこで・誰が・どのように」散骨するのか</strong>をご確認ください。
      </p>
      <div class="ks-quality-grid">
        <?php
          $ks_quality = [
            ['協会加盟の事業者か', '縁は一般社団法人日本海洋散骨協会の加盟事業者。ガイドラインと海域のルールを順守し、環境に配慮した散骨を行います。'],
            ['粉骨の品質と六価クロム対策', 'ご遺骨は一件ずつ丁寧にパウダー化。発がん性物質「六価クロム」の検査・無害化処理まで行ってから海にお還しします（2019年から実施）。'],
            ['散骨の証明', '散骨海域の緯度・経度入りの「散骨証明書」と当日のお写真をお届け。どこでお見送りしたかが、かたちで残ります。'],
            ['料金の明確さ', '金額は無料のお見積りで確定。ご納得いただいてからのご契約で、あとから追加料金をいただくことはありません。'],
            ['散骨後のご供養', 'メモリアルクルーズ、天国への手紙（無料）、手元供養など、「その後」のご供養まで自社で一貫してお手伝いします。'],
            ['実績と信頼', '鹿児島・福岡を中心に全国3,800件以上・10年以上の実績。Google口コミ評価★4.9をいただいています。'],
          ];
        ?>
        <?php foreach ($ks_quality as $i => [$t, $d]): ?>
          <div class="ks-quality-item">
            <h3><span>✓</span><?= h($t) ?></h3>
            <p><?= h($d) ?></p>
          </div>
        <?php endforeach; ?>
      </div>
      <div class="ks-cr6">
        <p class="ks-cr6__title">たとえば——ご遺骨の「六価クロム」検査・無害化</p>
        <p class="ks-cr6__text">火葬炉の耐熱ステンレスなどに由来する発がん性物質「六価クロム」が、ご遺骨に付着していることがあります。当社は散骨前の粉骨の際、専用キットで検査し、検出された場合は骨灰専用の還元剤で無害化してから海にお還しします（2019年から標準実施・追加料金なし）。格安サービスでは省略されがちな、見えない工程です。</p>
        <div class="ks-cr6__imgs">
          <figure><img src="/powder-cleaning/images/pc-cr6-check.jpg?v=<?= h(asset_ver()) ?>" alt="六価クロム検査キットと標準色カード" width="1400" height="933" loading="lazy"><figcaption>専用キットで検査</figcaption></figure>
          <figure><img src="/powder-cleaning/images/pc-cr6-positive.jpg?v=<?= h(asset_ver()) ?>" alt="六価クロムが検出され検査液がピンク色に変色した状態" width="1400" height="933" loading="lazy"><figcaption>変色したら「検出」のサイン</figcaption></figure>
          <figure><img src="/powder-cleaning/images/pc-cr6-agent.jpg?v=<?= h(asset_ver()) ?>" alt="骨灰専用の六価クロム還元剤" width="1400" height="933" loading="lazy"><figcaption>専用還元剤で無害化</figcaption></figure>
        </div>
        <p style="text-align:center;margin-top:14px"><a href="/powder-cleaning/" class="ks-cr6__link">六価クロムの検査・無害化について詳しく見る →</a></p>
      </div>
      <p style="text-align:center;margin-top:22px;font-size:.9rem;color:var(--text-light)">
        他社さまとご比較の際は、上の6点をチェックリストとしてご活用ください。<br class="pc-only">
        「見積りだけ」「話を聞くだけ」でも歓迎です。どうぞ納得のいくまでご比較ください。
      </p>
    </div>
  </section>
  <style>
    .ks-quality-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:16px}
    .ks-quality-item{background:var(--cream);border:1px solid var(--border);border-radius:14px;padding:20px 22px}
    .ks-quality-item h3{font-size:1rem;color:var(--green-mid);margin-bottom:8px;display:flex;align-items:center;gap:8px}
    .ks-quality-item h3 span{width:24px;height:24px;border-radius:50%;background:var(--green);color:#fff;display:grid;place-items:center;font-size:.8rem;flex:none}
    .ks-quality-item p{font-size:.88rem;line-height:1.85}
    @media(max-width:900px){.ks-quality-grid{grid-template-columns:repeat(2,1fr)}}
    @media(max-width:560px){.ks-quality-grid{grid-template-columns:1fr}}
    .ks-cr6{margin-top:26px;background:#fdf9f0;border:1px solid #e3d5b8;border-radius:14px;padding:22px 24px}
    .ks-cr6__title{text-align:center;font-weight:700;color:#8a6a2a;margin-bottom:8px}
    .ks-cr6__text{max-width:760px;margin:0 auto 18px;font-size:.92rem;line-height:1.95}
    .ks-cr6__imgs{display:grid;grid-template-columns:repeat(3,1fr);gap:12px}
    .ks-cr6__imgs figure{margin:0}
    .ks-cr6__imgs img{width:100%;aspect-ratio:3/2;object-fit:cover;border-radius:10px;display:block}
    .ks-cr6__imgs figcaption{text-align:center;font-size:.78rem;color:var(--text-light);margin-top:6px}
    .ks-cr6__link{color:var(--green);font-weight:700;text-decoration:none;border-bottom:2px solid var(--green)}
    @media(max-width:640px){.ks-cr6{padding:18px 16px}.ks-cr6__imgs{grid-template-columns:1fr;max-width:420px;margin:0 auto}}
  </style>

  <!-- よくあるご質問 -->
  <?php
    $ks_faq = [
      ['q' => '海洋散骨はどのような方に選ばれていますか？',
       'a' => '「海が好きだった」「自然に還りたい」という故人様やご本人の希望のほか、「お墓を継ぐ人がいない」「ご遺骨のことで家族に負担を残したくない」という承継の事情、「お墓や納骨堂の費用を抑えたい」という経済的な理由で選ばれる方が増えています。お墓を持たないご供養の方法なので、従来のかたちにとらわれず自由に故人様をお見送りしたい方に向いています。'],
      ['q' => '海洋散骨は違法ではありませんか？法律上の扱いを教えてください',
       'a' => '法務省は「節度をもって葬送の一つとして行われる限り違法ではない」との見解を示しており、2021年には厚生労働省から散骨に関するガイドラインも公表されています。当社は一般社団法人日本海洋散骨協会の加盟事業者として、ガイドラインと協会ルールに沿って適切な海域・方法で散骨を行いますのでご安心ください。なお、ご遺骨を粉骨せずそのまま海に撒くことはできません。当社では散骨前に必ず専用の設備で丁寧にパウダー化します。'],
      ['q' => 'お墓に納骨されているご遺骨を取り出して散骨できますか？',
       'a' => 'できます。墓地の管理者への連絡や、改葬（かいそう）の手続きが必要になる場合がありますが、手続きのご案内からお手伝いします。お墓からすべてのご遺骨を取り出して墓石を解体・撤去する場合は「お墓じまい」となります。当社はお墓じまいから粉骨・海洋散骨まで一括で承れますので、まとめてご相談ください。',
       'link' => ['/grave/', 'お墓じまいについて詳しく見る']],
      ['q' => '散骨はいつ行うのがよいですか？時期に決まりはありますか？',
       'a' => '決まりはありません。お墓への納骨は四十九日を目安に行うことが多いですが、宗教・宗派を問わない海洋散骨は、どのタイミングで行っても問題ありません。ご家族が集まりやすい日程や、海が穏やかな季節に合わせて決められる方が多いです。'],
      ['q' => 'ご遺骨をすべて散骨しなくても大丈夫ですか？一部だけ残せますか？',
       'a' => '大丈夫です。パウダー化したご遺骨の大部分を散骨し、一部を手のひらサイズのミニ骨壷やペンダントに納めてお手元に残す方は多くいらっしゃいます。お持ち込みのお手元供養品への分骨は5,500円（税込）です。散骨したご遺骨はあとから取り戻せませんので、迷われている場合は少量を残しておくことをおすすめしています。残した分をあとから散骨することはいつでもできます。',
       'link' => ['/temoto-kuyou/', 'お手元供養について詳しく見る']],
      ['q' => '散骨用とジュエリー用に分けてもらえますか？家族ごとに少しずつ分けることもできますか？',
       'a' => 'できます。粉骨の際に、散骨用・お手元用・ジュエリー用と必要な分だけお分けします。指輪に納めるのはお米一粒ほどのごく少量です。ご家族それぞれが少しずつ持たれる形（お一人はミニ骨壷、お一人はペンダントなど）も承ります。',
       'link' => ['/jewelry-reform/', 'メモリアルジュエリーについて詳しく見る']],
      ['q' => '手元供養やメモリアルジュエリーも一緒にお願いできますか？',
       'a' => 'はい。粉骨・海洋散骨・手元供養・ジュエリーまで、すべて同じ窓口で承ります。別々の業者に依頼する場合と違い、ご遺骨の受け渡しが社内で完結するため、大切なご遺骨があちこちを行き来する心配がありません。ミニ骨壷などの手元供養品は、実物を見ながらお選びいただけます。'],
      ['q' => '忙しくて乗船できません。委託散骨だと故人に申し訳ない気もするのですが…',
       'a' => 'そのようにお感じになる方は少なくありませんが、どうぞご安心ください。委託海洋葬では、経験を積んだスタッフがご遺族様に代わり、献花・献水とともに心を込めてお見送りします。散骨後は海域の緯度・経度入りの散骨証明書と当日のお写真をお届けしますので、どのようにお見送りしたかをご確認いただけます。後日、メモリアルクルーズで同じ海域を訪れてご供養いただくこともできます。'],
      ['q' => '散骨で海を汚すことにはなりませんか？環境への影響が心配です。',
       'a' => '当社は環境への配慮を最優先に散骨を行っています。ご遺骨には、火葬の高温により発がん性物質「六価クロム」が付着・生成されている場合があるため、粉骨の前に専用キットで検査し、検出された場合は骨灰専用の還元剤で無害化してから海にお還しします（2019年から実施・追加料金なし）。また、献花は自然に還る花びらのみ、散骨用の袋も水溶性のものを使用し、海域も海水浴場や漁場を避けて選定しています。',
       'link' => ['/powder-cleaning/', '六価クロムの検査・無害化について詳しく見る']],
      ['q' => 'ご遺骨と一緒にお花や思い出の品を撒くことはできますか？',
       'a' => '海に撒けるのは、花びらなど自然に還るものに限られます（環境保護のため、包装やリボンは外していただきます）。金属・プラスチック製品など自然に還らないものは撒くことができません。ご遺品の供養についても別途ご相談いただけます。'],
      ['q' => '小さな子どもや高齢の家族も乗船できますか？',
       'a' => 'ご乗船いただけます。小さなお子様は必ず大人の方が付き添い、目を離さないようお願いします。ご高齢の方は船の揺れに十分ご注意ください。車いすをご利用の方やお体のご事情がある方は、安全にご案内するため事前にお知らせください。'],
      ['q' => '献酒や読経など、セレモニーの希望は伝えられますか？',
       'a' => 'チャーター海洋葬では、事前のお打ち合わせでご要望をうかがいます。故人様がお好きだったお酒の献酒など、お気軽にお聞かせください。僧侶に読経をお願いしたい場合もご相談いただけます。合同海洋葬は複数のご家族様との乗り合わせのため、ご希望に添えない場合があります。'],
      ['q' => '当日はどのような服装がよいですか？喪服は必要ですか？',
       'a' => '平服（普段のお出かけ着）でお越しください。乗船場所には一般の方もいらっしゃるため、喪服は着用されないのが一般的です。船上は揺れることがありますので、動きやすい服装と、ヒールを避けた歩きやすいお履きものをおすすめします。'],
      ['q' => '天候が悪い場合はどうなりますか？',
       'a' => '安全な出航ができないと判断した場合は中止とし、キャンセル料をいただかずに日程を振り替えます（出航可否は原則として出航2日前までにご連絡します）。',
       'link' => ['/policy/', 'キャンセルポリシーを見る']],
      ['q' => '生前に自分の海洋散骨を申し込んでおくことはできますか？',
       'a' => '承れます。「亡くなったあと家族に負担をかけたくない」と生前に契約される方が増えています。ただし、ご家族に伝えていないと実現されない場合もありますので、ご家族・ご親族とよく話し合い、遺言書やエンディングノートにも残しておくことをおすすめします。',
       'link' => ['/seizen/', '海洋散骨 生前契約について詳しく見る']],
      ['q' => '希望日の何日前までに申し込めばよいですか？',
       'a' => '粉骨や船の手配がありますので、お日にちに余裕をもってご相談いただくのがおすすめです。お急ぎのご事情がある場合もできる限り調整いたしますので、まずはお電話・LINEでご相談ください。'],
      ['q' => 'ご遺骨を預かってもらうことはできますか？',
       'a' => 'はい。粉骨から散骨までの間、責任をもって大切にお預かりします。ご自宅にご遺骨を置くスペースがない場合などもご相談ください。遠方の方はゆうパックでのご郵送でもお預かりできます。'],
      ['q' => '申し込み後のキャンセルはできますか？',
       'a' => 'キャンセルは可能です。ただし、実施日が近づいてからのキャンセルには所定のキャンセル料がかかる場合があります。天候不良による中止の場合は、キャンセル料なしで日程を振り替えます。',
       'link' => ['/policy/', 'キャンセルポリシーを見る']],
    ];
  ?>
  <section class="section">
    <div class="container" style="max-width:820px">
      <h2 style="text-align:center;margin-bottom:8px">よくあるご質問</h2>
      <p style="text-align:center;font-size:.9rem;color:var(--text-light);margin-bottom:24px">海洋散骨についてよくいただくご質問をまとめました。このほかのご質問もお気軽にお問い合わせください。</p>
      <?php foreach ($ks_faq as $f): ?>
        <details style="background:var(--white);border:1px solid var(--border);border-radius:10px;padding:16px 20px;margin-bottom:12px">
          <summary style="font-weight:600;cursor:pointer;color:var(--green-mid)">Q. <?= h($f['q']) ?></summary>
          <p style="margin-top:10px;font-size:.95rem;line-height:1.9">A. <?= h($f['a']) ?><?php if (!empty($f['link'])): ?> <a href="<?= h($f['link'][0]) ?>" style="color:var(--green);text-decoration:underline"><?= h($f['link'][1]) ?> →</a><?php endif; ?></p>
        </details>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- CTA -->
  <section class="section" style="background:linear-gradient(135deg,var(--green),var(--green-mid));color:#fff;text-align:center">
    <div class="container">
      <h2 style="color:#fff">まずはお気軽にご相談ください</h2>
      <p style="opacity:.92;margin-bottom:22px">ご相談・お見積りは無料です。宗教・宗派は問いません。</p>
      <a href="/contact/?service=<?= rawurlencode('海洋葬') ?>" class="btn" style="background:#fff;color:var(--green-mid)">お問い合わせ</a>
      <a href="<?= h(SITE['line_url']) ?>" target="_blank" rel="noopener" class="btn" style="background:#06C755;margin-left:10px">LINEで相談</a>
      <a href="/contact/?service=<?= rawurlencode('資料請求（無料）') ?>" class="btn" style="background:#c9822a;margin-left:10px">無料で資料を受け取る</a>
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
  "serviceType":"海洋葬（海洋散骨）",
  "provider":{"@type":"Organization","name":"<?= h(SITE['name']) ?>","url":"<?= h(SITE['url']) ?>/"},
  "areaServed":"鹿児島・福岡・九州を中心に全国対応",
  "name":"海洋葬（海洋散骨）サービス",
  "description":"ご遺骨を母なる海へ還す海洋葬（海洋散骨）。委託・合同・チャーターの3プラン。日本海洋散骨協会加盟。"
}
</script>
<script type="application/ld+json">
{
  "@context":"https://schema.org",
  "@type":"BreadcrumbList",
  "itemListElement":[
    {"@type":"ListItem","position":1,"name":"ホーム","item":"https://en1150.co.jp/"},
    {"@type":"ListItem","position":2,"name":"サービス","item":"https://en1150.co.jp/service/"},
    {"@type":"ListItem","position":3,"name":"海洋葬（海洋散骨）","item":"https://en1150.co.jp/kaiyou-sou/"}
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
  ], $ks_faq),
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>

</script>
<?php require __DIR__ . '/../includes/footer.php'; ?>
