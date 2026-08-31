<?php
/**
 * 福岡の海洋散骨 専用ページ（/kaiyou-sou/fukuoka/）
 *
 * 役割分担（重複・カニバリ回避）
 *   /fukuoka/               … 福岡営業所の総合ハブ。全サービスの入口と拠点情報。
 *   /kaiyou-sou/            … 鹿児島（錦江湾）の海洋散骨。サービスの本体ページ。
 *   本ページ                … 「海洋散骨 福岡 / 福岡 散骨 / 博多湾 散骨」の受け皿。
 *                             SEOの主力＋Google広告のLP。料金・海域・出航場所・流れ・
 *                             選び方・法律・墓じまい後の導線を、福岡の文脈で完結させる。
 *
 * 掲載する数字・事実は、すべてサイト内に既出のもののみを使用している
 * （プラン料金・粉骨料金・出航場所・協会加盟・実績・証明書・六価クロム対応など）。
 *
 * 計測：電話は tel: リンク、LINEは SITE['line_url'] を使う。いずれも includes/ga4.php の
 *       クリックデリゲーションが tel_click / line_click を自動送信する。CTAのclass名は
 *       既存の .btn / .btn--outline を流用し、GTM等の既存設定を壊さない。
 */
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../admin/includes/store.php'; // news_published()（15分キャッシュ済み・読み取り増なし）

/* 海洋散骨レポート（ブログ「海洋葬(海洋散骨)」カテゴリの最新6件）。/fukuoka/ から本ページへ移設した */
$kf_reports = [];
try {
  $cat_alias  = ['海洋葬' => '海洋葬(海洋散骨)', '海洋散骨' => '海洋葬(海洋散骨)'];
  $split_cats = fn(?string $x): array =>
    array_map(fn($c) => $cat_alias[$c] ?? $c,
      array_values(array_filter(array_map('trim', preg_split('/[、,\/／]/u', (string)$x)))));
  foreach (news_published() as $it) {
    if (in_array('海洋葬(海洋散骨)', $split_cats($it['category'] ?? ''), true)) {
      $kf_reports[] = $it;
      if (count($kf_reports) >= 6) break;
    }
  }
} catch (Throwable $e) { $kf_reports = []; }

$page_title     = '海洋散骨 福岡｜博多湾で散骨・海洋葬 委託54,450円〜｜縁（えん）';
$page_desc      = '福岡の海洋散骨（海洋葬）は、日本海洋散骨協会 加盟の有限会社 縁へ。博多湾など福岡の海域に対応し、委託54,450円〜・合同148,500円〜・チャーター176,000円〜（税込）。合同は姪浜旅客待合所から出航。粉骨から緯度・経度入りの散骨証明書まで一括。福岡営業所（中央区春吉）。相談・見積り無料、LINE可。';
$page_canonical = SITE['url'] . '/kaiyou-sou/fukuoka/';
$page_hero_image = '/fukuoka/images/fk-sea-flowers.jpg';
require __DIR__ . '/../../includes/head.php';

$fk       = SITE['fukuoka'];
$fk_tel   = str_replace('-', '', $fk['tel']);
$sticky_tel = $fk['tel'];   // SP固定CTAの電話番号を福岡営業所に差し替える（footer.php が参照）

/* 料金は /kaiyou-sou/ ・ /fukuoka/ と同一の値。ここで独自に変更しない */
$kf_plans = [
  ['name' => '委託海洋散骨', 'price' => '54,450',  'badge' => '期間限定価格（通常66,000円）',
   'img'  => '/assets/img/plan-itaku.jpg',
   'lead' => '立ち会い不要・ご遺骨は郵送でOK',
   'desc' => 'ご遺族様に代わり、スタッフが献花・献水とともに心を込めて散骨します。福岡に帰省できない方、乗船が難しい方に選ばれています。'],
  ['name' => '合同海洋散骨', 'price' => '148,500', 'badge' => '姪浜旅客待合所から出航',
   'img'  => '/assets/img/plan-goudou.jpg',
   'lead' => '数家族で乗り合わせ・船上でお見送り',
   'desc' => '複数のご遺族様で船を分け合うことで費用を抑えながら、ご自身の手でお見送りいただけます。実施予定日は下記のとおりです。'],
  ['name' => 'チャーター海洋散骨', 'price' => '176,000', 'badge' => null,
   'img'  => '/assets/img/plan-charter.jpg',
   'lead' => '船を貸し切り・ご家族だけで',
   'desc' => '日程も進行もご家族のご希望に合わせられます。献酒や読経のご要望も、事前のお打ち合わせでうかがいます。'],
];

$kf_faq = [
  ['q' => '福岡の海洋散骨はいくらかかりますか？',
   'a' => 'スタッフにお任せいただく委託海洋散骨が54,450円〜（期間限定価格・通常66,000円）、数家族で乗り合わせる合同海洋散骨が148,500円〜、船を貸し切るチャーター海洋散骨が176,000円〜（いずれも税込）です。粉骨（パウダー化）が必要な場合は24,200円〜、長く保管されていたご遺骨の洗骨は27,500円〜。金額は無料のお見積りで確定し、ご納得いただいてからのご契約となりますので、あとから追加料金をいただくことはありません。'],
  ['q' => '博多湾で散骨できますか？',
   'a' => 'できます。博多湾をはじめ福岡の海域での散骨に対応しています。海域は海水浴場や漁場を避けて選定し、一般社団法人日本海洋散骨協会のガイドラインに沿って決めています。故人様やご家族にゆかりのある海をご希望の場合も、まずはご相談ください。'],
  ['q' => 'どこから出航しますか？',
   'a' => '合同海洋散骨は姪浜旅客待合所（福岡市西区愛宕浜3丁目1-1）に集合し、そこから出航します。チャーター海洋散骨の出航場所は、ご希望の海域と日程に合わせて個別にご案内します。委託海洋散骨はご乗船いただかないため、集合場所はありません。'],
  ['q' => '海洋散骨は違法ではありませんか？',
   'a' => '法務省は「節度をもって葬送の一つとして行われる限り違法ではない」との見解を示しており、2021年には厚生労働省から散骨に関するガイドラインも公表されています。当社は日本海洋散骨協会の加盟事業者として、海域の選定・粉骨・環境への配慮などルールを順守して行っていますのでご安心ください。'],
  ['q' => '遺骨はそのまま散骨できますか？',
   'a' => 'できません。ご遺骨は必ずパウダー状に粉骨してから海へお還しします。当社では専用の設備で丁寧にパウダー化し、あわせて火葬時に生成されることがある六価クロムを専用キットで検査、検出された場合は骨灰専用の還元剤で無害化してから散骨します（2019年から実施・追加料金なし）。'],
  ['q' => '墓じまい後の遺骨も散骨できますか？',
   'a' => 'できます。お墓から取り出したご遺骨は土や湿気で汚れていることが多いため、洗骨（27,500円〜）で洗浄・殺菌・乾燥してから粉骨し、博多湾での散骨へお進みいただけます。墓石の撤去・改葬の行政手続きから散骨まで、福岡営業所の同じ窓口で一括して承ります。'],
  ['q' => '家族が乗船しなくても依頼できますか？',
   'a' => 'できます。委託海洋散骨（54,450円〜）は、ご遺族様に代わってスタッフが献花・献水とともにお見送りするプランです。ご遺骨はゆうパックでのご郵送でお預かりできますので、福岡へお越しいただく必要はありません。散骨後は緯度・経度入りの散骨証明書と当日のお写真をご自宅へお届けします。'],
  ['q' => '散骨証明書は発行されますか？',
   'a' => '発行します。散骨した海域の緯度・経度と当日のお写真を記載した「海洋葬証明書」をお渡し（委託の場合はご郵送）しています。のちほど同じ海域を訪れるメモリアルクルーズのご相談も承っています。'],
  ['q' => '福岡県外からでも依頼できますか？',
   'a' => 'ご依頼いただけます。ご相談はお電話・LINE・メールで完結し、ご遺骨はゆうパックでお送りいただけます。「実家が福岡にある」「故郷の海に還してあげたい」というご依頼を全国からいただいています。'],
  ['q' => '天候が悪い場合はどうなりますか？',
   'a' => '安全な出航ができないと判断した場合は中止とし、キャンセル料をいただかずに日程を振り替えます。出航の可否は原則として出航2日前までにご連絡します。'],
  ['q' => '当日はどのような服装で行けばよいですか？',
   'a' => '平服（普段のお出かけ着）でお越しください。乗船場所には一般の方もいらっしゃるため、喪服は着用されないのが一般的です。船上は揺れることがありますので、ヒールを避けた歩きやすいお履きものをおすすめします。'],
];
?>
<body>
<?php require __DIR__ . '/../../includes/header.php'; ?>

<!-- ① ファーストビュー：広告流入者が3〜5秒で「福岡の海洋散骨ページ」と分かる構成 -->
<section class="page-hero kf-hero">
  <h1>福岡・博多湾の海洋散骨（海洋葬）</h1>
  <p class="kf-hero__price">委託 <strong>54,450円〜</strong>／合同 <strong>148,500円〜</strong>／チャーター <strong>176,000円〜</strong><span>（税込）</span></p>
  <p class="kf-hero__sub">博多湾など福岡の海域に対応／合同は姪浜旅客待合所から出航／緯度・経度入りの散骨証明書つき</p>
  <ul class="kf-hero__chips">
    <li>日本海洋散骨協会 加盟</li>
    <li>実績3,800件以上</li>
    <li>海洋葬10年以上</li>
    <li>Google口コミ ★4.9</li>
    <li>追加料金なし</li>
  </ul>
  <div class="kf-hero__cta">
    <a href="tel:<?= h($fk_tel) ?>" class="btn kf-btn-tel">電話で相談 <span><?= h($fk['tel']) ?></span></a>
    <a href="<?= h(SITE['line_url']) ?>" target="_blank" rel="noopener" class="btn kf-btn-line">LINEで相談</a>
    <a href="/contact/?service=<?= rawurlencode('海洋葬') ?>" class="btn kf-btn-form">無料で見積りを依頼</a>
  </div>
  <p class="kf-hero__note">ご相談・お見積りは無料／<?= h(SITE['hours_jp']) ?>（メール・LINEは24時間受付）</p>
</section>
<nav class="breadcrumb"><a href="/">ホーム</a> ＞ <a href="/kaiyou-sou/">海洋葬（海洋散骨）</a> ＞ 福岡の海洋散骨</nav>

<style>
  /* FVはGoogle広告のLPとして使うため、料金・信頼要素・相談方法を1画面に収める。
     配色・角丸・影は既存サイトの値をそのまま使い、LPだけ別サイトに見えないようにする。 */
  .kf-hero{padding-top:56px;padding-bottom:60px}
  .kf-hero h1{margin-bottom:14px}
  .kf-hero__price{position:relative;font-size:1.05rem;line-height:1.9}
  .kf-hero__price strong{font-size:1.35em;font-weight:700}
  .kf-hero__price span{font-size:.78rem;opacity:.85;margin-left:2px}
  .kf-hero__sub{position:relative;margin-top:8px;font-size:.9rem;opacity:.94;line-height:1.9}
  .kf-hero__chips{position:relative;list-style:none;display:flex;flex-wrap:wrap;gap:8px;justify-content:center;margin:16px 0 0;padding:0}
  .kf-hero__chips li{background:rgba(255,255,255,.18);border:1px solid rgba(255,255,255,.34);border-radius:999px;padding:5px 14px;font-size:.8rem;font-weight:700}
  .kf-hero__cta{position:relative;display:flex;flex-wrap:wrap;gap:10px;justify-content:center;margin-top:22px}
  .kf-hero__cta .btn{min-height:52px;display:inline-flex;flex-direction:column;align-items:center;justify-content:center;line-height:1.35}
  .kf-hero__cta .btn span{font-size:.98rem;font-weight:700;letter-spacing:.02em}
  .kf-btn-tel{background:#fff !important;color:#15709e !important;border-color:#fff !important}
  .kf-btn-line{background:#06C755 !important;color:#fff !important;border-color:#06C755 !important}
  .kf-btn-form{background:#c9822a !important;color:#fff !important;border-color:#c9822a !important}
  .kf-hero__note{position:relative;margin-top:14px;font-size:.82rem;opacity:.9}
  @media(max-width:640px){
    .kf-hero{padding:44px 18px 48px}
    .kf-hero__price{font-size:.98rem}
    .kf-hero__chips li{font-size:.74rem;padding:4px 11px}
    .kf-hero__cta{flex-direction:column;gap:9px}
    .kf-hero__cta .btn{width:100%}
  }
  /* 汎用の小物（このページ内のみ） */
  .kf-plans{display:grid;grid-template-columns:repeat(3,1fr);gap:18px}
  .kf-plan{background:#fff;border:1px solid var(--border);border-radius:14px;overflow:hidden;box-shadow:var(--shadow);display:flex;flex-direction:column}
  .kf-plan__img{display:block;aspect-ratio:16/9;overflow:hidden;background:#eef5f8}
  .kf-plan__img img{width:100%;height:100%;object-fit:cover;display:block}
  .kf-plan__body{padding:18px 20px 20px;display:flex;flex-direction:column;flex:1}
  .kf-plan__body h3{color:var(--green-mid);font-size:1.05rem;margin-bottom:4px}
  .kf-plan__lead{font-size:.82rem;color:var(--green);font-weight:700;margin-bottom:8px}
  .kf-plan__price{color:var(--green);font-weight:700;margin-bottom:6px}
  .kf-plan__price span{font-size:1.7rem}
  .kf-plan__price small{font-size:.75rem;color:var(--text-light);font-weight:400;margin-left:2px}
  .kf-plan__badge{display:inline-block;align-self:flex-start;background:#d8b46a;color:#1c2b33;font-size:.72rem;font-weight:700;padding:3px 10px;border-radius:999px;margin-bottom:8px}
  .kf-plan__desc{font-size:.88rem;line-height:1.8;flex:1}
  .kf-etc{display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-top:18px}
  .kf-etc div{background:#fff;border:1px solid var(--border);border-radius:12px;padding:16px 18px}
  .kf-etc h3{font-size:.96rem;color:var(--green-mid);margin-bottom:4px}
  .kf-etc p.p{color:var(--green);font-weight:700;margin-bottom:6px}
  .kf-etc p.d{font-size:.85rem;line-height:1.75}
  .kf-check{display:grid;grid-template-columns:repeat(3,1fr);gap:14px}
  .kf-check__item{background:var(--cream);border:1px solid var(--border);border-radius:12px;padding:16px 18px}
  .kf-check__item h3{font-size:.98rem;color:var(--green-mid);margin-bottom:6px;display:flex;align-items:center;gap:8px}
  .kf-check__item h3 span{width:22px;height:22px;border-radius:50%;background:var(--green);color:#fff;display:grid;place-items:center;font-size:.75rem;flex:none}
  .kf-check__item p{font-size:.85rem;line-height:1.8}
  .kf-ctabar{max-width:720px;margin:26px auto 0;background:#fff;border:1.5px solid #cfe0d8;border-radius:14px;padding:18px 20px;text-align:center}
  .kf-ctabar p{font-size:.9rem;color:var(--text-light);margin-bottom:12px;line-height:1.9}
  .kf-ctabar__btns{display:flex;flex-wrap:wrap;gap:10px;justify-content:center}
  .kf-gallery{display:grid;grid-template-columns:repeat(3,1fr);gap:14px}
  .kf-gallery img{width:100%;aspect-ratio:3/2;object-fit:cover;border-radius:12px;box-shadow:0 6px 18px rgba(0,0,0,.1)}
  @media(max-width:860px){.kf-plans,.kf-etc,.kf-check,.kf-gallery{grid-template-columns:1fr}.kf-check{grid-template-columns:repeat(2,1fr)}}
  @media(max-width:540px){.kf-check{grid-template-columns:1fr}.kf-ctabar__btns .btn{width:100%}}
</style>

<main>
  <!-- ② 結論を先に（AI検索・AI Overviews からの引用を想定した要約段落） -->
  <section class="section">
    <div class="container" style="max-width:820px">
      <div class="prose" style="max-width:780px;margin:0 auto">
        <p class="lead"><strong>福岡の海洋散骨（海洋葬）</strong>とは、ご遺骨をパウダー状に粉骨したうえで、博多湾など福岡の海域へお還しするご供養です。有限会社 縁は<strong>福岡営業所（福岡市中央区春吉）</strong>を拠点に、一般社団法人日本海洋散骨協会の加盟事業者として、<strong>委託54,450円〜／合同148,500円〜／チャーター176,000円〜（税込）</strong>の3プランで対応しています。合同海洋散骨は<strong>姪浜旅客待合所（福岡市西区愛宕浜）</strong>から出航。ご遺骨のお預かり・洗骨・粉骨から、散骨海域の<strong>緯度・経度入り「海洋葬証明書」</strong>のお渡しまで、同じ窓口で一括して承ります。お墓じまいで取り出したご遺骨の散骨、県外にお住まいの方からのご依頼にも対応しています。</p>
      </div>
    </div>
  </section>

  <!-- ③ 福岡で海洋散骨をお考えの方へ -->
  <section class="section" style="background:var(--cream)">
    <div class="container" style="max-width:820px">
      <h2 style="text-align:center;margin-bottom:8px">福岡で海洋散骨をお考えの方へ</h2>
      <p style="text-align:center;color:var(--text-light);font-size:.92rem;margin-bottom:24px">こんなご事情から、福岡でも散骨を選ばれる方が増えています。</p>
      <ul style="list-style:none;display:grid;gap:12px;padding:0;max-width:680px;margin:0 auto">
        <li style="padding:14px 18px;background:#fff;border-radius:10px;border-left:4px solid var(--green)">「海が好きだった」「自然に還りたい」という故人様の希望を、福岡の海で叶えたい</li>
        <li style="padding:14px 18px;background:#fff;border-radius:10px;border-left:4px solid var(--green)">お墓を継ぐ人がいない。子どもに管理の負担を残したくない</li>
        <li style="padding:14px 18px;background:#fff;border-radius:10px;border-left:4px solid var(--green)">お墓じまいをしたあと、取り出したご遺骨の行き先が決まっていない</li>
        <li style="padding:14px 18px;background:#fff;border-radius:10px;border-left:4px solid var(--green)">県外に住んでいて福岡に帰れないが、故郷の海に還してあげたい</li>
        <li style="padding:14px 18px;background:#fff;border-radius:10px;border-left:4px solid var(--green)">格安の散骨業者が多く、どこに頼めばよいか判断できない</li>
      </ul>
      <p style="text-align:center;margin-top:22px;font-size:.95rem;color:var(--text-light)">どのご事情も、福岡営業所で対面のご相談が可能です。「話を聞くだけ」でも歓迎です。</p>
    </div>
  </section>

  <!-- ④ プラン・料金 -->
  <section class="section" id="price">
    <div class="container" style="max-width:960px">
      <p style="text-align:center;font-size:.78rem;letter-spacing:.28em;color:#b08b3e;font-weight:700;margin-bottom:8px">PRICE</p>
      <h2 style="text-align:center;margin-bottom:12px">福岡の海洋散骨プランと料金</h2>
      <p style="text-align:center;max-width:700px;margin:0 auto 26px;line-height:2;font-size:.95rem">料金はすべて税込です。<strong>金額は無料のお見積りで確定</strong>し、ご納得いただいてからのご契約となります。<strong>あとから追加料金をいただくことはありません。</strong></p>
      <div class="kf-plans">
        <?php foreach ($kf_plans as $pl): ?>
        <div class="kf-plan">
          <span class="kf-plan__img"><img src="<?= h($pl['img']) ?>?v=<?= h(asset_ver()) ?>" alt="<?= h($pl['name']) ?>のイメージ" width="1200" height="675" loading="lazy"></span>
          <div class="kf-plan__body">
            <h3><?= h($pl['name']) ?></h3>
            <p class="kf-plan__lead"><?= h($pl['lead']) ?></p>
            <p class="kf-plan__price"><span><?= h($pl['price']) ?></span>円〜<small>（税込）</small></p>
            <?php if ($pl['badge']): ?><p class="kf-plan__badge"><?= h($pl['badge']) ?></p><?php endif; ?>
            <p class="kf-plan__desc"><?= h($pl['desc']) ?></p>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <div class="kf-etc">
        <?php foreach ([
          ['粉骨（パウダー化）', '24,200円〜', '散骨には粉骨が必須です。乳鉢を使いすべて手作業で丁寧に。六価クロムの検査・無害化も追加料金なしで行います。'],
          ['洗骨（クリーニング）', '27,500円〜', '墓じまいで取り出したご遺骨や、長年保管されていたご遺骨の洗浄・殺菌・乾燥。散骨前のご利用が多い工程です。'],
          ['手元供養品への分骨', '5,500円', 'すべてを海に還さず、ひとつまみだけミニ骨壷やペンダントに残す方も多くいらっしゃいます（お持ち込み品への分骨）。'],
        ] as [$t, $p, $d]): ?>
        <div>
          <h3><?= h($t) ?></h3>
          <p class="p"><?= h($p) ?></p>
          <p class="d"><?= h($d) ?></p>
        </div>
        <?php endforeach; ?>
      </div>
      <p style="text-align:center;margin-top:18px;font-size:.85rem;color:var(--text-light)">※ 海域や出航場所、ご遺骨の状態・数量によって金額が変わる場合があります。まずは無料のお見積りでご確認ください。</p>
      <div class="kf-ctabar">
        <p>どのプランが合うか分からない、という段階でも大丈夫です。ご事情をうかがって、無料でお見積りをご案内します。</p>
        <div class="kf-ctabar__btns">
          <a href="tel:<?= h($fk_tel) ?>" class="btn">電話で相談（<?= h($fk['tel']) ?>）</a>
          <a href="<?= h(SITE['line_url']) ?>" target="_blank" rel="noopener" class="btn" style="background:#06C755;border-color:#06C755">LINEで相談</a>
          <a href="/contact/?service=<?= rawurlencode('海洋葬') ?>" class="btn btn--outline">無料見積りフォーム</a>
        </div>
      </div>
    </div>
  </section>

  <!-- ⑤ 散骨場所・出航場所・対応エリア -->
  <section class="section" style="background:var(--white)">
    <div class="container" style="max-width:900px">
      <h2 style="text-align:center;margin-bottom:8px">福岡・博多湾での散骨場所と出航場所</h2>
      <p style="text-align:center;color:var(--text-light);font-size:.92rem;margin-bottom:24px">海域は協会のガイドラインに沿って、海水浴場や漁場を避けて選定します。</p>
      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:16px">
        <div class="card">
          <h3 style="color:var(--green-mid);font-size:1rem;margin-bottom:8px">散骨する海域｜博多湾ほか福岡の海域</h3>
          <p style="font-size:.9rem;line-height:1.9">博多湾をはじめとする福岡の海域で散骨を行います。海水浴場・漁場・養殖場を避け、航路と天候を確認したうえで海域を決定します。故人様やご家族にゆかりのある海をご希望の場合は、実施の可否を含めてご相談ください。</p>
        </div>
        <div class="card">
          <h3 style="color:var(--green-mid);font-size:1rem;margin-bottom:8px">合同海洋散骨の出航場所｜姪浜</h3>
          <p style="font-size:.9rem;line-height:1.9"><strong>姪浜旅客待合所</strong>（福岡市西区愛宕浜3丁目1-1）にご集合いただき、出航します。福岡市地下鉄・姪浜駅から車で数分の場所です。</p>
          <p style="margin-top:10px"><a href="https://maps.app.goo.gl/ssPvPegY1qikqrEz9" target="_blank" rel="noopener" style="color:var(--green);font-weight:700;font-size:.9rem">Googleマップで見る →</a></p>
        </div>
        <div class="card">
          <h3 style="color:var(--green-mid);font-size:1rem;margin-bottom:8px">ご相談・お預かりの対応エリア</h3>
          <p style="font-size:.9rem;line-height:1.9"><strong>福岡市内全域</strong>（東・博多・中央・南・城南・早良・西区）、<strong>北九州エリア</strong>、<strong>筑後エリア</strong>（久留米・大牟田・柳川など）、筑豊エリア（飯塚・田川など）。佐賀・熊本・大分など隣県、および県外からのご郵送にも対応しています。</p>
        </div>
      </div>
      <p style="text-align:center;margin-top:16px;font-size:.85rem;color:var(--text-light)">※ チャーター海洋散骨の出航場所は、ご希望の海域・日程に合わせて個別にご案内します。</p>
    </div>
  </section>

  <!-- ⑥ 流れ -->
  <section class="section">
    <div class="container" style="max-width:780px">
      <p style="text-align:center;font-size:.78rem;letter-spacing:.28em;color:#b08b3e;font-weight:700;margin-bottom:8px">FLOW</p>
      <h2 style="text-align:center;margin-bottom:24px">ご相談から散骨証明書までの流れ</h2>
      <ol style="list-style:none;display:grid;gap:12px;padding:0">
        <?php foreach ([
          ['無料相談（電話・LINE・メール）', '福岡営業所での対面のご相談も承ります。「話を聞くだけ」でも歓迎です。'],
          ['プランのご提案とお見積り', 'ご希望の海域・日程・ご予算をうかがい、総額を確定してご提示します。'],
          ['お申し込み・ご遺骨のお預かり', '福岡営業所へのお持ち込み、お引き取り、ゆうパックでのご郵送から選べます。'],
          ['洗骨・粉骨（パウダー化）', '必要に応じて洗骨し、手作業で粉骨。六価クロムの検査・無害化も行います。'],
          ['出航・海洋散骨', '献花・献水・鐘の音とともに、博多湾など福岡の海域へお還しします。'],
          ['散骨証明書のお渡し・アフターサポート', '緯度・経度入りの海洋葬証明書と当日のお写真をお届け。メモリアルクルーズのご相談も承ります。'],
        ] as $i => [$t, $d]): ?>
        <li style="display:grid;grid-template-columns:40px 1fr;gap:14px;background:#fff;border:1px solid var(--border);border-radius:12px;padding:14px 18px;align-items:center">
          <span style="width:36px;height:36px;border-radius:50%;background:var(--green);color:#fff;display:grid;place-items:center;font-weight:700"><?= $i + 1 ?></span>
          <span><strong style="color:var(--green-mid)"><?= h($t) ?></strong><br><span style="font-size:.88rem;color:var(--text-light)"><?= h($d) ?></span></span>
        </li>
        <?php endforeach; ?>
      </ol>
      <p style="text-align:center;margin-top:20px;font-size:.9rem"><a href="/flow/" style="color:var(--green);font-weight:600">お申込みの流れをさらに詳しく見る →</a></p>
    </div>
  </section>

  <!-- ⑦ 合同海洋散骨の実施予定日（福岡開催のみ・管理画面から更新） -->
  <?php $gd_filter = '福岡'; $gd_area_label = '福岡'; require __DIR__ . '/../../includes/goudou-schedule.php'; ?>

  <!-- ⑧ 実際の様子（写真は当社施行のもの） -->
  <section class="section" style="background:var(--white)">
    <div class="container" style="max-width:900px">
      <h2 style="text-align:center;margin-bottom:8px">福岡での海洋散骨セレモニーの様子</h2>
      <p style="text-align:center;color:var(--text-light);font-size:.92rem;margin-bottom:24px">献花・献水・鐘。当日の様子は撮影し、証明書とあわせてお届けします。</p>
      <div class="kf-gallery">
        <img src="/fukuoka/images/fk-ceremony.jpg?v=<?= h(asset_ver()) ?>" alt="船上に用意された献花と献酒のセレモニーセット" width="900" height="600" loading="lazy">
        <img src="/fukuoka/images/fk-petals.jpg?v=<?= h(asset_ver()) ?>" alt="海へ花びらを手向ける散骨セレモニーの様子" width="900" height="600" loading="lazy">
        <img src="/fukuoka/images/fk-kensui.jpg?v=<?= h(asset_ver()) ?>" alt="散骨後に海へ水を手向ける献水の様子" width="900" height="600" loading="lazy">
        <img src="/fukuoka/images/fk-sankotsu.jpg?v=<?= h(asset_ver()) ?>" alt="海へご遺骨を還す散骨の様子" width="1200" height="800" loading="lazy">
        <img src="/fukuoka/images/fk-koe.jpg?v=<?= h(asset_ver()) ?>" alt="船上で故人さまへのメッセージカードを書くご家族" width="1200" height="800" loading="lazy">
        <img src="/fukuoka/images/fk-port.jpg?v=<?= h(asset_ver()) ?>" alt="福岡の港に停泊する海洋散骨のクルーズ船" width="1600" height="1067" loading="lazy">
      </div>
      <figure style="margin:26px auto 0;max-width:260px;text-align:center">
        <img src="/assets/img/certificate.jpg?v=<?= h(asset_ver()) ?>" alt="緯度・経度入りの海洋葬証明書" width="800" height="1074" loading="lazy" style="width:100%;height:auto;border-radius:12px;border:1px solid var(--border);box-shadow:0 8px 22px rgba(40,60,50,.12);background:#f2efe8">
        <figcaption style="margin-top:10px;font-size:.82rem;color:var(--text-light)">実際にお渡ししている「海洋葬証明書」。散骨海域の緯度・経度と当日のお写真入りです。</figcaption>
      </figure>
      <p style="text-align:center;margin-top:14px;font-size:.82rem;color:var(--text-light)">※ 写真はいずれも当社が施行したセレモニーのものです。</p>
    </div>
  </section>

  <!-- 海洋散骨レポート -->
  <?php if ($kf_reports): ?>
  <section class="section">
    <div class="container" style="max-width:960px">
      <p style="text-align:center;font-size:.78rem;letter-spacing:.28em;color:#b08b3e;font-weight:700;margin-bottom:8px">REPORT</p>
      <h2 style="text-align:center;margin-bottom:10px">海洋散骨レポート</h2>
      <p style="text-align:center;color:var(--text-light);font-size:.95rem;margin-bottom:28px">実際の海洋散骨の様子を、ブログでご紹介しています。<br class="sp-only">当日の雰囲気づくりの参考にご覧ください。</p>
      <div class="fkr-wrap">
        <button type="button" class="fkr-arrow fkr-arrow--prev" aria-label="前のレポートへ">‹</button>
        <div class="fkr-track" id="fkr-track">
          <?php foreach ($kf_reports as $it): ?>
          <a class="card fkr-card" href="/blog/?id=<?= h(rawurlencode($it['id'] ?? '')) ?>">
            <?php if (!empty($it['image'])): ?>
              <span style="display:block;aspect-ratio:16/9;overflow:hidden;background:#eef5f8"><img src="<?= h($it['image']) ?>" alt="<?= h($it['title'] ?? '') ?>" loading="lazy" style="width:100%;height:100%;object-fit:cover;display:block" onerror="var t=this.parentNode;if(t)t.remove()"></span>
            <?php endif; ?>
            <span style="display:flex;flex-direction:column;padding:16px 18px;flex:1">
              <p style="font-size:.78rem;color:var(--text-light)"><?= h($it['date'] ?? '') ?> ・ 海洋葬(海洋散骨)</p>
              <h3 style="font-size:.96rem;line-height:1.7"><?= h($it['title'] ?? '') ?></h3>
              <?php if (!empty($it['body'])): ?><p style="font-size:.85rem;flex:1;margin-top:6px"><?= h(mb_strimwidth(preg_replace('/\s+/u', ' ', strip_tags((string)$it['body'])), 0, 68, '…')) ?></p><?php endif; ?>
              <span style="margin-top:10px;align-self:flex-start;color:var(--green);font-weight:600;font-size:.85rem">詳しく読む →</span>
            </span>
          </a>
          <?php endforeach; ?>
        </div>
        <button type="button" class="fkr-arrow fkr-arrow--next" aria-label="次のレポートへ">›</button>
      </div>
      <p class="fkr-hint">← 横にスワイプすると他のレポートもご覧いただけます →</p>
      <style>
        .fkr-wrap{position:relative}
        .fkr-track{display:flex;gap:16px;overflow-x:auto;scroll-snap-type:x mandatory;-webkit-overflow-scrolling:touch;padding:4px 4px 14px;scrollbar-width:none}
        .fkr-track::-webkit-scrollbar{display:none}
        .fkr-card{flex:0 0 300px;display:flex;flex-direction:column;padding:0;overflow:hidden;scroll-snap-align:start}
        .fkr-arrow{position:absolute;top:50%;transform:translateY(-50%);z-index:2;width:40px;height:40px;border-radius:50%;border:1px solid var(--border);background:rgba(255,255,255,.95);color:var(--green-mid);font-size:1.5rem;line-height:1;cursor:pointer;box-shadow:0 4px 14px rgba(40,60,50,.18);display:grid;place-items:center;padding:0 0 3px}
        .fkr-arrow:hover{background:#fff}
        .fkr-arrow--prev{left:-14px}
        .fkr-arrow--next{right:-14px}
        .fkr-arrow[disabled]{opacity:.3;cursor:default}
        .fkr-hint{text-align:center;font-size:.74rem;color:var(--text-light);margin-top:2px}
        @media(max-width:768px){
          .fkr-card{flex:0 0 min(78vw,300px)}
          .fkr-arrow{display:none}
          .fkr-track{padding-bottom:10px}
        }
      </style>
      <script>
        (function () {
          var track = document.getElementById('fkr-track');
          var prev = document.querySelector('.fkr-arrow--prev');
          var next = document.querySelector('.fkr-arrow--next');
          if (!track || !prev || !next) return;
          var step = function () { return (track.querySelector('.fkr-card')?.offsetWidth || 300) + 16; };
          prev.addEventListener('click', function () { track.scrollBy({ left: -step(), behavior: 'smooth' }); });
          next.addEventListener('click', function () { track.scrollBy({ left: step(), behavior: 'smooth' }); });
          var sync = function () {
            prev.disabled = track.scrollLeft <= 4;
            next.disabled = track.scrollLeft >= track.scrollWidth - track.clientWidth - 4;
          };
          track.addEventListener('scroll', sync, { passive: true });
          window.addEventListener('resize', sync);
          sync();
        })();
      </script>
      <p style="text-align:center;margin-top:28px">
        <a href="/blog/?cat=<?= h(rawurlencode('海洋葬(海洋散骨)')) ?>" class="btn">海洋散骨レポート一覧はこちら</a>
      </p>
    </div>
  </section>
  <?php endif; ?>


  <!-- 海へ還る、あたらしいお見送りのかたち。（TOPと共通） -->
  <section class="fkc-fullbleed" style="background-image:url('/assets/img/top/fullbleed-bg.jpg?v=<?= h(asset_ver()) ?>')">
    <div class="fkc-fb-inner">
      <span class="fkc-fb-kicker">En — Ocean Memorial</span>
      <h2>海へ還る、<br>あたらしいお見送りのかたち。</h2>
    </div>
  </section>
  <style>
    .fkc-fullbleed{position:relative;width:100%;min-height:clamp(260px,38vw,440px);background-position:center;background-size:cover;background-repeat:no-repeat;display:flex;align-items:center;justify-content:center;text-align:center}
    .fkc-fullbleed::before{content:'';position:absolute;inset:0;background:linear-gradient(180deg,rgba(12,58,78,.30) 0%,rgba(12,58,78,.12) 55%,rgba(12,58,78,.22) 100%)}
    .fkc-fb-inner{position:relative;z-index:1;color:#fff;padding:0 24px}
    .fkc-fb-kicker{font-family:'Cormorant Garamond',serif;letter-spacing:.34em;text-transform:uppercase;font-size:.8rem;color:#fff;opacity:.9;display:block;margin-bottom:14px}
    .fkc-fullbleed h2{font-family:'Shippori Mincho','Yu Mincho',serif;font-weight:500;font-size:clamp(1.4rem,3.2vw,2.2rem);line-height:1.9;letter-spacing:.08em;color:#fff;text-shadow:0 2px 16px rgba(0,0,0,.25)}
  </style>

  <!-- ⑨ 選ばれる理由 -->
  <section class="section" style="background:linear-gradient(180deg,#f2f8fa,#e8f2f6)">
    <div class="container" style="max-width:900px">
      <p style="text-align:center;font-size:.78rem;letter-spacing:.28em;color:#b08b3e;font-weight:700;margin-bottom:8px">REASON</p>
      <h2 style="text-align:center;margin-bottom:10px">福岡で縁が選ばれる理由</h2>
      <p style="text-align:center;color:var(--text-light);font-size:.95rem;margin-bottom:28px">鹿児島で最初に海洋葬に取り組んで10年以上。福岡でも、同じ基準でお手伝いします。</p>
      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:14px;margin-bottom:22px">
        <div class="card" style="text-align:center"><p style="font-size:1.7rem;font-weight:700;color:var(--green)">3,800<span style="font-size:.9rem">件以上</span></p><p style="font-size:.85rem;color:var(--text-light)">鹿児島・福岡を中心に<br>全国の対応実績</p></div>
        <div class="card" style="text-align:center"><p style="font-size:1.7rem;font-weight:700;color:var(--green)">10年<span style="font-size:.9rem">以上</span></p><p style="font-size:.85rem;color:var(--text-light)">海洋葬の実績</p></div>
        <div class="card" style="text-align:center"><p style="font-size:1.7rem;font-weight:700;color:#f4b400">★4.9</p><p style="font-size:.85rem;color:var(--text-light)">Google口コミ評価<br>（本社プロフィール）</p></div>
        <div class="card" style="text-align:center"><img src="/assets/img/jmas-logo.png?v=<?= h(asset_ver()) ?>" alt="一般社団法人 日本海洋散骨協会 ロゴ" width="360" height="454" loading="lazy" style="width:52px;height:auto;margin:0 auto 6px;display:block"><p style="font-size:.85rem;color:var(--text-light)">日本海洋散骨協会の<br>加盟事業者</p></div>
      </div>
      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:16px">
        <div class="card"><h3 style="margin-bottom:8px;color:var(--green)">協会ルールを順守した運航</h3><p style="font-size:.92rem;line-height:1.9">日本海洋散骨協会の加盟事業者として、海域の選定・粉骨・環境への配慮を順守。天候と海況を見極め、安全第一で運航します。</p></div>
        <div class="card"><h3 style="margin-bottom:8px;color:var(--green)">墓じまいから散骨まで一括対応</h3><p style="font-size:.92rem;line-height:1.9">改葬の行政手続き・墓石の撤去・洗骨・粉骨・散骨・手元供養までを同じ窓口で。ご遺骨が業者間を行き来しません。</p></div>
        <div class="card"><h3 style="margin-bottom:8px;color:var(--green)">追加料金のない明快な料金</h3><p style="font-size:.92rem;line-height:1.9">お見積りは無料。金額はお見積りで確定し、あとから追加料金をいただくことはありません。</p></div>
        <div class="card"><h3 style="margin-bottom:8px;color:var(--green)">散骨後も、会いに行ける</h3><p style="font-size:.92rem;line-height:1.9">緯度・経度入りの証明書をお渡しし、同じ海域を訪れるメモリアルクルーズや手元供養もお手伝いします。</p></div>
      </div>
    </div>
  </section>

  <!-- ⑩ 法律・ルール・マナー -->
  <section class="section">
    <div class="container" style="max-width:820px">
      <h2 style="text-align:center;margin-bottom:8px">海洋散骨の法律・ルール・マナー</h2>
      <p style="text-align:center;color:var(--text-light);font-size:.92rem;margin-bottom:24px">「違法ではないか」というご不安に、事実でお答えします。</p>
      <div style="display:grid;gap:14px">
        <div class="card">
          <h3 style="color:var(--green-mid);font-size:1rem;margin-bottom:6px">法律上の扱い</h3>
          <p style="font-size:.92rem;line-height:1.95">法務省は「節度をもって葬送の一つとして行われる限り違法ではない」との見解を示しており、2021年には厚生労働省から散骨に関するガイドラインが公表されています。当社は一般社団法人日本海洋散骨協会の加盟事業者として、このガイドラインと協会ルールに沿って実施しています。</p>
        </div>
        <div class="card">
          <h3 style="color:var(--green-mid);font-size:1rem;margin-bottom:6px">粉骨は必須です</h3>
          <p style="font-size:.92rem;line-height:1.95">ご遺骨をそのまま海に撒くことはできません。散骨の前に必ずパウダー状に粉骨します。当社では、火葬時に生成されることがある六価クロムを専用キットで検査し、検出された場合は骨灰専用の還元剤で無害化してから海へお還しします（2019年から実施・追加料金なし）。</p>
        </div>
        <div class="card">
          <h3 style="color:var(--green-mid);font-size:1rem;margin-bottom:6px">海域と環境への配慮</h3>
          <p style="font-size:.92rem;line-height:1.95">海水浴場・漁場・養殖場を避けて海域を選定します。海に手向けられるのは自然に還る花びらのみで、包装やリボン、金属・プラスチック製品は撒くことができません。散骨用の袋も水溶性のものを使用しています。</p>
        </div>
        <div class="card">
          <h3 style="color:var(--green-mid);font-size:1rem;margin-bottom:6px">当日のマナー</h3>
          <p style="font-size:.92rem;line-height:1.95">服装は平服が一般的です。乗船場所には一般の方もいらっしゃるため、喪服は着用されないことをおすすめしています。船上は揺れることがありますので、歩きやすいお履きものでお越しください。</p>
        </div>
      </div>
    </div>
  </section>

  <!-- ⑪ 業者を選ぶポイント -->
  <section class="section" style="background:var(--white)">
    <div class="container" style="max-width:960px">
      <h2 style="text-align:center;margin-bottom:14px">福岡で海洋散骨業者を選ぶときの6つのポイント</h2>
      <p style="text-align:center;max-width:720px;margin:0 auto 26px;line-height:2">福岡でも格安をうたう散骨サービスが増えています。しかし「実際にどの海域で散骨されたのか分からない」「証明書が発行されない」「あとから追加料金を請求された」というケースも報告されています。一度きりのご供養だからこそ、<strong>他社さまとご比較の際は次の点をご確認ください。</strong></p>
      <div class="kf-check">
        <?php foreach ([
          ['協会に加盟しているか', '日本海洋散骨協会などのガイドラインに沿って運営しているか。海域の選定基準を説明できるか。'],
          ['粉骨の方法が明確か', 'どこで、どのように粉骨するのか。六価クロムの検査・無害化まで行っているか。'],
          ['散骨証明書が出るか', '散骨した海域の緯度・経度が記載されるか。当日の写真が届くか。'],
          ['追加料金がないか', '見積り後に出航費・手数料などが上乗せされないか。総額が事前に確定するか。'],
          ['地元に拠点があるか', '福岡に相談できる窓口があるか。天候中止時の振替に対応できるか。'],
          ['その後まで頼めるか', '墓じまい・洗骨・手元供養・メモリアルクルーズまで同じ窓口で相談できるか。'],
        ] as [$t, $d]): ?>
        <div class="kf-check__item">
          <h3><span>✓</span><?= h($t) ?></h3>
          <p><?= h($d) ?></p>
        </div>
        <?php endforeach; ?>
      </div>
      <div class="kf-ctabar">
        <p>「見積りだけ」「他社と比較したい」というご相談も歓迎です。こちらから営業のご連絡はいたしません。</p>
        <div class="kf-ctabar__btns">
          <a href="/contact/?service=<?= rawurlencode('海洋葬') ?>" class="btn">無料で見積りを依頼する</a>
          <a href="<?= h(SITE['line_url']) ?>" target="_blank" rel="noopener" class="btn btn--outline">LINEで相談</a>
        </div>
      </div>
    </div>
  </section>

  <!-- ⑫ 墓じまい後に散骨する場合 -->
  <section class="section" style="background:var(--cream)">
    <div class="container" style="max-width:860px">
      <h2 style="text-align:center;margin-bottom:8px">お墓じまい後に、福岡の海へ散骨する場合</h2>
      <p style="text-align:center;max-width:700px;margin:0 auto 24px;line-height:2;font-size:.94rem">お墓から取り出したご遺骨は、土や湿気で汚れていることがほとんどです。<strong>洗骨（27,500円〜）→ 粉骨（24,200円〜）→ 博多湾での散骨</strong>という順に進みます。改葬の行政手続きから散骨まで、福岡営業所の同じ窓口で承ります。</p>
      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:14px">
        <div class="card">
          <h3 style="font-size:.98rem;color:var(--green-mid);margin-bottom:6px">福岡の墓じまい</h3>
          <p style="font-size:.88rem;line-height:1.85">墓石の撤去から納骨まで一括対応する基本プランは330,000円（税込）。改葬許可申請のサポートはオプション（25,000円〜）です。</p>
          <p style="margin-top:10px"><a href="/grave/fukuoka/" style="color:var(--green);font-weight:700;font-size:.9rem">福岡の墓じまいを見る →</a></p>
        </div>
        <div class="card">
          <h3 style="font-size:.98rem;color:var(--green-mid);margin-bottom:6px">墓じまい後の散骨の手順と費用</h3>
          <p style="font-size:.88rem;line-height:1.85">手続きの流れ、業者の選び方、洗骨・粉骨を含めた費用の目安をまとめています。</p>
          <p style="margin-top:10px"><a href="/grave/sankotsu/" style="color:var(--green);font-weight:700;font-size:.9rem">墓じまい後の散骨を見る →</a></p>
        </div>
        <div class="card">
          <h3 style="font-size:.98rem;color:var(--green-mid);margin-bottom:6px">一部を手元に残す</h3>
          <p style="font-size:.88rem;line-height:1.85">すべてを海に還す必要はありません。ひとつまみだけミニ骨壷やペンダントに納める方も多くいらっしゃいます（分骨5,500円）。</p>
          <p style="margin-top:10px"><a href="/temoto-kuyou/" style="color:var(--green);font-weight:700;font-size:.9rem">お手元供養を見る →</a></p>
        </div>
      </div>
    </div>
  </section>

  <!-- ⑬ 県外にお住まいの方へ -->
  <section class="section" id="kengai">
    <div class="container" style="max-width:860px">
      <p style="text-align:center;font-size:.78rem;letter-spacing:.28em;color:#b08b3e;font-weight:700;margin-bottom:8px">NATIONWIDE</p>
      <h2 style="text-align:center;margin-bottom:14px">県外にお住まいの方へ</h2>
      <p style="text-align:center;max-width:720px;margin:0 auto 22px;line-height:2;font-size:.95rem">「実家が福岡にある」「故郷の海に還してあげたい」——帰省せずにご利用いただける<strong>委託海洋散骨（54,450円〜）</strong>をご用意しています。ご遺骨はゆうパックでのご郵送でお預かりし、粉骨から散骨、証明書のお届けまで当社が代行します。</p>
      <div style="display:flex;flex-wrap:wrap;gap:8px;justify-content:center;margin-bottom:22px">
        <?php foreach (['帰省・立ち会い不要', 'ご遺骨は郵送でOK', '梱包方法は資料でご案内', '散骨証明書と当日写真をお届け'] as $chip): ?>
        <span style="background:#fff;border:1px solid var(--border);border-radius:999px;padding:6px 16px;font-size:.85rem;font-weight:700;color:var(--green-mid)"><?= h($chip) ?></span>
        <?php endforeach; ?>
      </div>
      <p style="text-align:center"><a href="/contact/?service=<?= rawurlencode('海洋葬') ?>" class="btn">県外からのご相談はこちら</a></p>
    </div>
  </section>

  <!-- ⑭ 資料請求CTA（全ページ共通） -->
  <?php require __DIR__ . '/../../includes/shiryou-cta.php'; ?>

  <!-- ⑮ FAQ -->
  <section class="section" style="background:var(--white)">
    <div class="container" style="max-width:820px">
      <h2 style="text-align:center;margin-bottom:8px">福岡の海洋散骨 よくあるご質問</h2>
      <p style="text-align:center;font-size:.9rem;color:var(--text-light);margin-bottom:24px">このほかのご質問は、<a href="/kaiyou-sou/" style="color:var(--green);text-decoration:underline">海洋葬（海洋散骨）のページ</a>でも詳しくご説明しています。</p>
      <?php foreach ($kf_faq as $f): ?>
        <details style="background:#fff;border:1px solid var(--border);border-radius:10px;padding:16px 20px;margin-bottom:12px">
          <summary style="font-weight:600;cursor:pointer;color:var(--green-mid)">Q. <?= h($f['q']) ?></summary>
          <p style="margin-top:10px;font-size:.95rem;line-height:1.9">A. <?= h($f['a']) ?></p>
        </details>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- ⑯ 福岡営業所案内（→ /fukuoka/ へ戻す導線） -->
  <section class="section">
    <div class="container" style="max-width:780px">
      <h2 style="text-align:center;margin-bottom:20px">福岡のご相談窓口</h2>
      <div class="card" style="max-width:580px;margin:0 auto;text-align:center">
        <p style="font-weight:700;color:var(--green-mid);font-size:1.05rem;margin-bottom:8px"><?= h(SITE['name']) ?> <?= h($fk['name']) ?></p>
        <p style="font-size:.92rem;line-height:2">〒<?= h($fk['zip']) ?> <?= h($fk['address']) ?><br>
        電話 <a href="tel:<?= h($fk_tel) ?>" style="color:var(--green);font-weight:700;font-size:1.15rem"><?= h($fk['tel']) ?></a>（<?= h(SITE['hours_jp']) ?>）</p>
        <p style="margin-top:10px"><a href="https://maps.google.com/?cid=1235913108976072113" target="_blank" rel="noopener" style="color:var(--green);font-weight:600;font-size:.9rem">Googleマップで見る →</a></p>
        <p style="margin-top:12px;font-size:.85rem;color:var(--text-light)">合同海洋散骨の出航場所：姪浜旅客待合所（福岡市西区愛宕浜3丁目1-1）</p>
      </div>
      <p style="text-align:center;margin-top:16px;font-size:.9rem"><a href="/fukuoka/" style="color:var(--green);font-weight:600">福岡営業所のサービス全体（墓じまい・粉骨・生前契約など）を見る →</a></p>
      <p style="text-align:center;margin-top:6px;font-size:.85rem;color:var(--text-light)">本社（鹿児島）：〒<?= h(SITE['zip']) ?> <?= h(SITE['address']) ?>（TEL <?= h(SITE['tel']) ?>）</p>
    </div>
  </section>

  <!-- ⑰ 最終CTA -->
  <section class="section" style="background:linear-gradient(135deg,var(--green),var(--green-mid));color:#fff;text-align:center">
    <div class="container">
      <h2 style="color:#fff">福岡の海洋散骨、まずはご相談ください</h2>
      <p style="opacity:.92;margin-bottom:22px">「まだ決めていない」「話を聞くだけ」でも歓迎です。ご相談・お見積りは無料、こちらから営業のご連絡はいたしません。</p>
      <p style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap">
        <a href="tel:<?= h($fk_tel) ?>" class="btn" style="background:#fff;color:var(--green-mid)">電話で相談（<?= h($fk['tel']) ?>）</a>
        <a href="<?= h(SITE['line_url']) ?>" target="_blank" rel="noopener" class="btn" style="background:#06C755">LINEで相談</a>
        <a href="/contact/?service=<?= rawurlencode('海洋葬') ?>" class="btn" style="background:#d8b46a;color:#1c2b33">メールで相談・見積り</a>
      </p>
    </div>
  </section>
</main>

<script type="application/ld+json">
<?= json_encode([
  '@context' => 'https://schema.org',
  '@type' => 'Service',
  'serviceType' => '海洋散骨（海洋葬）',
  'name' => '福岡・博多湾の海洋散骨',
  'provider' => [
    '@type' => 'LocalBusiness',
    'name' => '有限会社 縁 福岡営業所',
    'telephone' => '+81-90-5000-4825',
    'address' => ['@type' => 'PostalAddress', 'streetAddress' => '春吉2丁目1-3 2F', 'addressLocality' => '福岡市中央区', 'addressRegion' => '福岡県', 'postalCode' => '810-0003', 'addressCountry' => 'JP'],
    'url' => SITE['url'] . '/fukuoka/',
    'hasMap' => 'https://maps.google.com/?cid=1235913108976072113',
  ],
  'areaServed' => [
    ['@type' => 'State', 'name' => '福岡県'],
    ['@type' => 'City', 'name' => '福岡市'],
    ['@type' => 'City', 'name' => '北九州市'],
    ['@type' => 'City', 'name' => '久留米市'],
  ],
  'offers' => [
    ['@type' => 'Offer', 'name' => '委託海洋散骨',       'price' => '54450',  'priceCurrency' => 'JPY', 'description' => '立ち会い不要。スタッフが代行して散骨（税込・期間限定価格）'],
    ['@type' => 'Offer', 'name' => '合同海洋散骨',       'price' => '148500', 'priceCurrency' => 'JPY', 'description' => '数家族で乗り合わせ。姪浜旅客待合所から出航（税込）'],
    ['@type' => 'Offer', 'name' => 'チャーター海洋散骨', 'price' => '176000', 'priceCurrency' => 'JPY', 'description' => '船を貸し切り、ご家族だけでお見送り（税込）'],
  ],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>
</script>
<script type="application/ld+json">
<?= json_encode([
  '@context' => 'https://schema.org',
  '@type' => 'BreadcrumbList',
  'itemListElement' => [
    ['@type' => 'ListItem', 'position' => 1, 'name' => 'ホーム', 'item' => SITE['url'] . '/'],
    ['@type' => 'ListItem', 'position' => 2, 'name' => '海洋葬（海洋散骨）', 'item' => SITE['url'] . '/kaiyou-sou/'],
    ['@type' => 'ListItem', 'position' => 3, 'name' => '福岡の海洋散骨', 'item' => SITE['url'] . '/kaiyou-sou/fukuoka/'],
  ],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>
</script>
<script type="application/ld+json">
<?= json_encode([
  '@context' => 'https://schema.org',
  '@type' => 'FAQPage',
  'mainEntity' => array_map(fn($f) => [
    '@type' => 'Question',
    'name' => $f['q'],
    'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f['a']],
  ], $kf_faq),
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>
</script>
<?php require __DIR__ . '/../../includes/footer.php'; ?>
