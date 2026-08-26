<?php
/**
 * 海洋葬（海洋散骨）専用ページ
 * 実際のセレモニー写真・お客様の声・縁の魅力・画像付きお申込みの流れを掲載。
 */
require_once __DIR__ . '/../includes/config.php';

$page_title     = '海洋葬（海洋散骨）｜鹿児島・福岡・九州対応｜有限会社 縁';
$page_desc      = '海洋葬（海洋散骨）は、ご遺骨を母なる海へ還すご供養です。鹿児島・錦江湾を中心に福岡・九州全域の海域に対応、立ち会い不要の委託海洋葬（54,450円〜）は全国からご利用いただけます。有限会社 縁（日本海洋散骨協会加盟）。';
$page_canonical = SITE['url'] . '/kaiyou-sou/';
$page_hero_image = '/assets/img/hero-kaiyou-sou.jpg';
require __DIR__ . '/../includes/head.php';

$ks_img = static fn(string $f): string => '/kaiyou-sou/images/' . $f . '?v=' . asset_ver();
?>
<body>
<?php require __DIR__ . '/../includes/header.php'; ?>

<section class="page-hero">
  <h1>海洋葬（海洋散骨）</h1>
  <p>「海に包まれて眠りたい」——大切な方の想いを、母なる海へ</p>
  <p style="margin-top:14px;display:flex;flex-wrap:wrap;gap:8px;justify-content:center">
    <span style="display:inline-block;background:#d8b46a;color:#1c2b33;padding:6px 18px;border-radius:999px;font-weight:700">委託海洋葬 54,450円〜（期間限定・通常66,000円）</span>
    <span style="display:inline-block;background:rgba(255,255,255,.18);padding:6px 18px;border-radius:999px;font-weight:700">合同海洋葬 148,500円〜</span>
    <span style="display:inline-block;background:rgba(255,255,255,.18);padding:6px 18px;border-radius:999px;font-weight:700">チャーター海洋葬 176,000円〜</span>
  </p>
</section>
<nav class="breadcrumb"><a href="/">ホーム</a> ＞ <a href="/service/">サービス</a> ＞ 海洋葬（海洋散骨）</nav>

<main>
  <!-- 導入 -->
  <section class="section">
    <div class="container" style="max-width:860px">
      <div class="prose" style="max-width:820px;margin:0 auto">
        <p class="lead">海洋葬（海洋散骨）は、亡くなられた方のご遺骨を母なる海へ還すこと。大自然の一部として、命あるものの自然な還り方でもあります。</p>
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
      </ul>
    </div>
  </section>

  <!-- 縁の海洋散骨の魅力 -->
  <section class="section">
    <div class="container" style="max-width:1000px">
      <p style="text-align:center;font-size:.78rem;letter-spacing:.28em;color:#b08b3e;font-weight:700;margin-bottom:8px">OUR STRENGTH</p>
      <h2 style="text-align:center;margin-bottom:10px">縁の海洋散骨、5つの魅力</h2>
      <p style="text-align:center;color:var(--text-light);font-size:.95rem;margin-bottom:32px">安心・信頼・安全・価格・充実のオプション。<br class="sp-only">選ばれ続けるのには、理由があります。</p>
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

  <!-- お申込みの流れ（画像付き） -->
  <section class="section" style="background:var(--white)">
    <div class="container" style="max-width:860px">
      <p style="text-align:center;font-size:.78rem;letter-spacing:.28em;color:#b08b3e;font-weight:700;margin-bottom:8px">FLOW</p>
      <h2 style="text-align:center;margin-bottom:26px">お申込みの流れ</h2>
      <div class="ks-flow">
        <?php
          $ks_flow = [
            ['お問い合わせ・無料相談', 'お電話・LINE・メールフォームからお気軽にどうぞ。「話を聞くだけ」でも歓迎です。ご相談・お見積りは無料です。', '/assets/img/svc-soudan.jpg', 'スタッフによる無料相談の様子'],
            ['プランのご提案とお見積り', 'ご希望・ご事情をうかがい、最適なプランをご提案。金額はお見積りで確定し、追加料金はありません。', null, null],
            ['ご遺骨のお引取り・お預かり', 'お持ち込みのほか、ご郵送（ゆうパック）にも対応。お引取りにうかがうこともできます。大切に、丁寧にお預かりします。', $ks_img('ks-kotsubako.jpg'), '丁寧にお預かりしたご遺骨'],
            ['粉骨（パウダー化）', '海洋散骨のルールに沿って、ご遺骨を専用の設備でパウダー状にします。立ち会いをご希望の場合はご相談ください。', '/assets/img/svc-funkotsu.jpg', '粉骨作業の様子'],
            ['海洋葬の実施', '献花・献水・鐘の音とともに、心を込めてご遺骨を海へお還しします。当日の様子は撮影し、お届けします。', $ks_img('ks-maku.jpg'), '花びらとともに行う海洋散骨セレモニー'],
            ['散骨証明書のお渡し・アフターサポート', '緯度・経度入りの散骨証明書をお渡しします。メモリアルクルーズや手元供養など、「その後」のご供養もお手伝いします。', $ks_img('ks-sea-flowers.jpg'), '散骨海域に広がる花びら'],
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
              <img src="<?= h($img) ?><?= str_starts_with($img, '/assets/') ? '?v=' . h(asset_ver()) : '' ?>" alt="<?= h($alt) ?>" width="900" height="600" loading="lazy" class="ks-flow__img">
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
    .ks-flow__img--ph{display:grid;place-items:center;background:var(--sea-light);color:var(--green);font-weight:700;font-size:.9rem;text-align:center;line-height:1.5}
    @media(max-width:640px){
      .ks-flow__step{grid-template-columns:36px 1fr}
      .ks-flow__num{width:36px;height:36px;font-size:1rem}
      .ks-flow__img{grid-column:1/-1;width:100%}
    }
  </style>

  <!-- 実施予定日（管理画面から更新） -->
  <?php require __DIR__ . '/../includes/goudou-schedule.php'; ?>

  <!-- よくあるご質問 -->
  <section class="section">
    <div class="container" style="max-width:820px">
      <h2>よくあるご質問</h2>
      <details style="background:var(--white);border:1px solid var(--border);border-radius:10px;padding:16px 20px;margin-bottom:12px">
        <summary style="font-weight:600;cursor:pointer;color:var(--green-mid)">Q. 海洋葬にふさわしい服装はありますか？</summary>
        <p style="margin-top:10px;font-size:.95rem">A. 船上にて執り行いますので、動きやすい服装、滑りにくく歩きやすいお履きものでお願いしております。協会の注意事項（海洋散骨ルールブック）もあわせてご一読ください。</p>
      </details>
      <details style="background:var(--white);border:1px solid var(--border);border-radius:10px;padding:16px 20px;margin-bottom:12px">
        <summary style="font-weight:600;cursor:pointer;color:var(--green-mid)">Q. 海洋葬の生前申し込みは可能ですか？</summary>
        <p style="margin-top:10px;font-size:.95rem">A. 生前のお申込みを承ることは可能です。<a href="/seizen/" style="color:var(--green);text-decoration:underline">海洋散骨 生前契約</a>のページもご覧ください。</p>
      </details>
      <details style="background:var(--white);border:1px solid var(--border);border-radius:10px;padding:16px 20px;margin-bottom:12px">
        <summary style="font-weight:600;cursor:pointer;color:var(--green-mid)">Q. 天候が悪い場合はどうなりますか？</summary>
        <p style="margin-top:10px;font-size:.95rem">A. 安全な出航ができないと判断した場合は中止とし、キャンセル料をいただかずに日程を振り替えます（出航可否は原則として出航2日前までにご連絡します）。詳しくは<a href="/policy/" style="color:var(--green);text-decoration:underline">キャンセルポリシー</a>をご覧ください。</p>
      </details>
    </div>
  </section>

  <!-- CTA -->
  <section class="section" style="background:linear-gradient(135deg,var(--green),var(--green-mid));color:#fff;text-align:center">
    <div class="container">
      <h2 style="color:#fff">まずはお気軽にご相談ください</h2>
      <p style="opacity:.92;margin-bottom:22px">ご相談・お見積りは無料です。宗教・宗派は問いません。</p>
      <a href="/contact/?service=<?= rawurlencode('海洋葬') ?>" class="btn" style="background:#fff;color:var(--green-mid)">お問い合わせ</a>
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
<?php require __DIR__ . '/../includes/footer.php'; ?>
