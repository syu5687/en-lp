<?php
/**
 * 寺院納骨＋海洋散骨「teraumi（てらうみ）」（/teraumi/）
 *
 * 位置づけ
 *   旧 teraumi.com（有限会社 縁が2023年12月から運営していた専用サイト）を統合したページ。
 *   別ブランドではなく、縁が対応できる供養方法のひとつとして扱う。
 *   表記は必ず「寺院納骨＋海洋散骨 teraumi（てらうみ）」の順。ブランド名だけの見出しは作らない。
 *
 * 役割分担（重複・カニバリ回避）
 *   /kaiyou-sou/          … 鹿児島・錦江湾の海洋散骨。サービスの本体ページ（URL変更禁止）。
 *   /kaiyou-sou/fukuoka/  … 福岡・博多湾の海洋散骨。
 *   /temoto-kuyou/        … 一部を手元に残す方法。
 *   本ページ              … 「海洋散骨 一部 納骨 / 遺骨 一部 お寺 / 散骨 全部しない」の受け皿。
 *                           「海洋散骨 鹿児島」「墓じまい 鹿児島」等はここでは狙わない。
 *
 * ★デザイン方針（v0256で全面見直し）
 *   ページ専用のUIを作らない。common.css のグローバルコンポーネントだけで組む。
 *     .page-hero / .breadcrumb / .section / .container / .card-grid / .card /
 *     .btn / .btn--outline / .prose / details+summary
 *   カラム数は「コンテナ幅」で決める（.card-grid は auto-fill minmax(260px,1fr)）。
 *     max-width:620px → 2列 ／ max-width:900px → 3列 ／ SPは自動で1列。
 *   ページ専用CSSは末尾の4ユーティリティのみ（.tr-list / .tr-dl / .tr-arrow / .tr-num）。
 *   これ以上は増やさない。teraumi だけが別のデザインシステムを持つ状態にしない。
 *   資料請求ブロックは includes/shiryou-cta.php（既存の全サイト共通パーツ。/kaiyou-sou/
 *   /grave/ /blog/ など9ページで使用中）をそのまま require している。teraumi用に作った
 *   ものではないので、.shiryou-cta 系のCSSはこのページの専用CSSには数えない。
 *
 * 守っていること（コピー）
 *   ・通常の海洋散骨を否定しない。海そのものが故人を思う場所・手を合わせる場所になることを明記する。
 *   ・「散骨するとお参りする場所がなくなる」という論法は使わない。
 *   ・teraumi側を「建物のあるお参り先がある」と書かない（v0257）。海洋散骨に何かが欠けている
 *     ように読める比較をしない。teraumiは「そのうえで一部を提携先へ納める方法」と書く。
 *   ・法手続きは断定しない（v0257）。改葬許可は「原則として必要／自治体・納骨状況で確認事項が
 *     異なるため個別にご案内」と書く。
 *   ・料金はFVに出さず、中盤で内訳とセットで出す。直後に予算別の代替案を必ず置く。
 *   ・料金・実績の数字を丸めない（59,950円〜 を「6万円ほど」と書かない）。
 *   ・分骨5,500円は既存ページと同じ適用条件（お持ち込みのお手元供養品へのご納骨）で書く。
 *   ・主CTAは申込ではなく相談。「まだ決められない」方を必ず受け入れる。
 *
 * 計測
 *   電話は tel: リンク、LINEは SITE['line_url']。includes/ga4.php のクリックデリゲーションが
 *   tel_click / line_click を自動送信する。フォームへは ?service= と from=teraumi を付ける。
 */
require_once __DIR__ . '/../includes/config.php';

$page_title     = '寺院納骨＋海洋散骨 teraumi｜遺骨の一部をお寺へ納める供養｜縁';
$page_desc      = 'ご遺骨の一部をお寺や神社に納め、残りを海へ散骨する供養方法「teraumi」。粉骨・納骨・委託海洋葬まで一括対応。宗教宗派不問・管理費不要。270,000円（税込）。有限会社 縁';
$page_canonical = SITE['url'] . '/teraumi/';
$page_hero_image = '/kaiyou-sou/images/ks-sea-flowers.jpg';
require __DIR__ . '/../includes/head.php';

$tr_tel  = str_replace('-', '', SITE['tel']);
$tr_svc  = '寺院納骨＋海洋散骨（teraumi）';           // SERVICES の title と完全一致させる
$tr_form = '/contact/?service=' . rawurlencode($tr_svc) . '&from=teraumi';

/* 料金の内訳。旧 teraumi.com plans.php の掲載内容をそのまま使用している。 */
$tr_price_tera = ['合祀墓の使用権', '納骨作業料', '7年〜の供養料'];
$tr_price_umi  = ['委託海洋葬の基本料金', '事務手数料', '粉骨作業料', 'ご遺骨の乾燥料', '献花・献酒', '海洋葬証明書の発行', '骨壺の処分'];

/* 提携先。旧 teraumi.com list.php の掲載内容をそのまま使用している。
   合計28か所（実名掲載20・名称非公開8）／11都府県。
   名称非公開の8か所は旧サイトでも宗派と特徴のみの掲載だったため同じ扱いにし、件数（n）には含める。 */
$tr_temples = [
  ['area' => '東京都',   'n' => 3, 'items' => ['光円寺（真宗大谷派・港区虎ノ門）', '幸國寺（日蓮宗・新宿区原町）', '曹洞宗の寺院 1か所（羽田駅から電車で1本・緑豊かな都心の寺院）']],
  ['area' => '神奈川県', 'n' => 1, 'items' => ['長源寺（真言宗・横浜市旭区上川井町）']],
  ['area' => '大阪府',   'n' => 1, 'items' => ['本昌寺（日蓮宗・岸和田市五軒屋町）']],
  ['area' => '京都府',   'n' => 1, 'items' => ['海宝寺（黄檗宗・京都市伏見区桃山町）']],
  ['area' => '滋賀県',   'n' => 1, 'items' => ['本福寺（浄土真宗本願寺派・大津市本堅田）']],
  ['area' => '福井県',   'n' => 1, 'items' => ['安楽寺（真言宗・あわら市北潟）']],
  ['area' => '福岡県',   'n' => 9, 'items' => ['信行寺（浄土真宗本願寺派・粕屋郡宇美町）', '西岸寺（真宗大谷派・田川市川宮）', '法林寺（浄土真宗本願寺派・糸島市前原中央）', '覺法寺（真宗大谷派・京都郡苅田町与原）', '恩誓寺（浄土真宗本願寺派・糸島市加布里）', '一心寺（浄土真宗本願寺派・粕屋郡志免町吉原）', '福岡市内の寺院 3か所（浄土宗／浄土真宗本願寺派／曹洞宗）']],
  ['area' => '熊本県',   'n' => 2, 'items' => ['香福寺（浄土真宗・熊本市中央区本山）', '公園墓地 1か所（熊本市内を一望できる屋外納骨堂）']],
  ['area' => '宮崎県',   'n' => 1, 'items' => ['立正寺（日蓮宗・宮崎市末広）']],
  ['area' => '大分県',   'n' => 1, 'items' => ['金剛宝寺（真言宗・玖珠郡九重町大字湯坪）']],
  ['area' => '鹿児島県', 'n' => 7, 'items' => ['顕證寺（浄土真宗本願寺派・南さつま市加世田唐仁原）', '神徳稲荷神社（神道・鹿屋市新栄町）', '谷山御所霊園（鹿児島市上福元町）', '新生田上霊園（鹿児島市田上台）', '鹿児島市内の寺院 3か所（真言宗／浄土真宗本願寺派／臨済宗）']],
];
$tr_temple_total = array_sum(array_column($tr_temples, 'n'));   // 28
$tr_area_total   = count($tr_temples);                          // 11

/* 散骨海域。旧 teraumi.com list.php の掲載内容。 */
$tr_seas = [
  '九州'       => '福岡＝博多湾／熊本＝天草／鹿児島＝鹿児島湾（錦江湾）／沖縄＝那覇沖',
  '中国・四国' => '岡山・広島＝瀬戸内海／徳島＝徳島沖／高知＝香南沖／愛媛＝瀬戸内海／香川＝高松沖',
  '近畿'       => '大阪・兵庫＝大阪湾／和歌山＝南紀白浜沖',
  '中部・北陸' => '静岡＝駿河湾／三重＝伊勢湾／富山＝富山湾／福井＝若狭湾',
  '関東'       => '千葉・東京＝東京湾／神奈川＝相模湾・東京湾',
  '北海道'     => '小樽・函館',
];

/* 利用の流れ。旧 teraumi.com index.php の6ステップを、納骨と散骨が並行することが分かるように整理した。 */
$tr_flow = [
  ['t' => '事前のご相談・資料請求', 'd' => 'お電話・LINE・メールでご相談ください。費用とお申し込み方法をまとめた資料をお送りします。この段階では「検討している」だけで問題ありません。'],
  ['t' => '納骨先の見学（ご希望の方のみ）', 'd' => 'お寺・神社を実際に見てから決めたい方には、担当者から見学のご案内をします。見学せずにお決めいただくこともできます。'],
  ['t' => 'お申し込み・ご遺骨のお預かり', 'd' => 'ご来店・お引き取り・ご郵送のいずれかでご遺骨をお預かりします。火葬許可証・埋葬許可証・改葬許可証のいずれかの写しが必要です。'],
  ['t' => '粉骨（パウダー化）', 'd' => 'お預かりしたご遺骨をパウダー状にします。納骨する分と散骨する分は、この工程でお分けします。'],
  ['t' => '納骨と法要', 'd' => '納骨先で納骨法要を行います。日程が決まりましたらお寺・神社からご連絡します。年に1回の合同法要も行われます。'],
  ['t' => '海洋散骨と証明書のお届け', 'd' => 'ご遺骨をお預かりしてから半年以内に、スタッフが代わって散骨します。終了後、散骨した海域を記載した海洋葬証明書と当日のお写真をお届けします。'],
];

/* FAQ。旧 teraumi.com plans.php のQ&A 12問と、実際に検索される質問を突き合わせて12問に整理した。
   見出しは検索される言い回しに寄せ、答えは1〜2文で言い切ってから補足する（AI検索対策）。 */
$tr_faq = [
  ['q' => '海洋散骨する遺骨の一部を、お寺に納骨できますか？',
   'a' => 'できます。それが寺院納骨＋海洋散骨「teraumi」です。パウダー状にしたご遺骨を、納骨する分と散骨する分にお分けし、一部を提携する寺院・神社の合祀墓へ納め、残りを海へ散骨します。粉骨・納骨・海洋散骨・証明書の発行までを、有限会社 縁の同じ窓口で承ります。'],
  ['q' => '遺骨を全部散骨しなくてもいいですか？',
   'a' => 'かまいません。ご遺骨のすべてを撒かなければならない決まりはありません。一部を寺院・神社に納める、ご自宅のミニ骨壺に残す、メモリアルジュエリーに納める——どの分け方も選べます。分ける量は粉骨のときに決められます。'],
  ['q' => 'teraumiと普通の海洋散骨は何が違いますか？',
   'a' => '違いは「ご遺骨の一部をお寺や神社にも納めるかどうか」だけです。通常の海洋散骨はご遺骨のすべてを海へお還しし、散骨した海そのものが、故人を思う場所・手を合わせる場所になります。teraumiは、そのうえで一部をご希望の提携先へ納める方法です。どちらが良いということはなく、ご希望とご家族のお気持ちで選んでいただくものです。'],
  ['q' => '管理費は必要ですか？',
   'a' => '必要ありません。納骨後の管理はお寺・神社にお任せいただき、年間管理費はいただきません。檀家・信徒になる必要もありません。'],
  ['q' => '仏教徒ではないのですが、お寺に納骨できますか？',
   'a' => 'できます。宗教・宗派は問いません。提携先の合祀墓へ納めさせていただき、納骨後はその寺院・神社の作法でのご供養となります。神社・霊園を納骨先に選ぶこともできます。'],
  ['q' => 'お寺に納めた遺骨を、あとから返してもらえますか？',
   'a' => 'できません。納めたご遺骨は7年〜の個別供養ののち、合祀（ほかの方と一緒に埋葬）されます。あとから取り出すことはできませんので、「いつか手元に戻すかもしれない」という場合は、寺院納骨ではなくお手元供養をおすすめしています。'],
  ['q' => '納骨するお寺や散骨する海は選べますか？',
   'a' => '選べます。納骨先は提携する寺院・神社・霊園から、散骨海域は鹿児島湾（錦江湾）と博多湾からお選びいただけます。それ以外の海域も、散骨が可能な場所であれば別途費用で対応します。'],
  ['q' => '納骨や散骨に立ち会えますか？',
   'a' => 'このプランの海洋散骨は、スタッフが代わって行う委託海洋葬です。出航のお見送りはできますが、ご乗船をご希望の場合は別途費用がかかります。納骨も一任いただき、納骨の様子はお写真でご報告します。'],
  ['q' => '日程は指定できますか？',
   'a' => '日程のご指定は承っておりません。ご遺骨をお預かりしてから半年以内に散骨し、納骨の日程は納骨先からご連絡します。日程を決めて執り行いたい場合は、チャーター海洋葬をご検討ください。'],
  ['q' => '墓じまいをしてから、一部だけ散骨することはできますか？',
   'a' => 'できます。お墓からのご遺骨の取り出し、改葬許可の手続き、墓石の撤去までを承ったうえで、一部を寺院・神社へ、残りを海へお還しします。お墓まで伺ってのお預かりにも対応しています。'],
  ['q' => '一部を納骨する場合、改葬許可は必要ですか？',
   'a' => '現在お墓や納骨堂に納められているご遺骨を別の納骨先へ移す場合は、原則として市区町村での改葬許可の手続きが必要です。自治体や現在の納骨状況によって確認事項が異なるため、個別にご案内します。ご自宅で保管されているご遺骨の場合は、火葬許可証または埋葬許可証の写しをご用意ください。'],
  ['q' => '2人分をまとめてお願いできますか？',
   'a' => '承れます。このプランに含まれるのはお一人分です。お二人目以降は粉骨が22,000円（税込・1柱ごと）、海洋散骨は追加費用なし、納骨は納骨先によって変わりますのでお見積り時にご案内します。'],
];

/* details（アコーディオン）の見た目は、既存の全サービスページと同じインライン指定に合わせる。 */
$tr_acc = 'background:#fff;border:1px solid var(--border);border-radius:10px;padding:16px 20px;margin-bottom:12px';
$tr_sum = 'font-weight:600;cursor:pointer;color:var(--green-mid)';
?>
<body>
<?php require __DIR__ . '/../includes/header.php'; ?>

<!-- ① ファーストビュー
     商品名からではなく、ユーザーの迷いから始める。金額・CTA・営業時間はここには置かない。
     .page-hero の h1 と p は common.css で完結しているため、このFVに専用CSSは一切ない。 -->
<section class="page-hero">
  <h1>遺骨の一部を寺院へ納め、<span style="display:inline-block">残りを海へ散骨する「teraumi」</span></h1>
  <?php /* SPで語の途中で折り返さないよう、意味のかたまりごとに inline-block で包む。
           専用クラスを増やさないため、ここだけインラインで指定している。 */ ?>
  <p><span style="display:inline-block">海洋散骨はしたい。</span><span style="display:inline-block">でも、</span><span style="display:inline-block">ご遺骨を全部海へ還すことには</span><span style="display:inline-block">迷いがある。</span></p>
  <p><span style="display:inline-block">ご遺骨の一部を</span><span style="display:inline-block">提携する寺院・神社に納め、</span><span style="display:inline-block">残りを海洋散骨する方法があります。</span></p>
  <p><span style="display:inline-block">日本海洋散骨協会 加盟　／</span><span style="display:inline-block">　提携先 <?= $tr_area_total ?>都府県・<?= $tr_temple_total ?>か所　／</span><span style="display:inline-block">　管理費 不要</span></p>
</section>
<nav class="breadcrumb"><a href="/">ホーム</a> ＞ <a href="/service/">サービス一覧</a> ＞ 寺院納骨＋海洋散骨（teraumi）</nav>

<main>

  <!-- ② teraumiとは -->
  <section class="section" id="about">
    <div class="container" style="max-width:820px">
      <h2 style="text-align:center">寺院納骨＋海洋散骨「teraumi」とは</h2>
      <div class="prose">
        <p>有限会社 縁は、ご遺骨の一部を提携する寺院・神社・霊園に納め、残りを海へ散骨する供養方法「teraumi（てらうみ）」を行っています。粉骨から納骨、海洋散骨、証明書のお届けまで、同じ窓口で承ります。</p>
        <p>この方法が生まれたのは、ご相談のなかで「海に還してあげたいが、全部撒いてしまうことに迷いがある」「故人は散骨を希望していたが、家族は納める場所も持ちたい」というお話をたびたびうかがってきたためです。どちらかを諦めるのではなく、ご遺骨を分けて両方を叶える形として組み立てました。</p>
        <p>納骨先は<?= $tr_area_total ?>都府県・<?= $tr_temple_total ?>か所の提携先からお選びいただけます。年間管理費はかからず、宗教・宗派も問いません。檀家・信徒になっていただく必要もありません。</p>
      </div>
    </div>
  </section>

  <!-- ③ 向いている方／向いていない方
       teraumiを勧めない相手をはっきり書く。あてはまらない方が早い段階で適したページへ移れるようにする。
       .card-grid は auto-fill minmax(260px,1fr)。container を 620px にすることで2列になる（SPは1列）。 -->
  <section class="section" id="fit" style="background:var(--cream)">
    <div class="container" style="max-width:660px">
      <h2 style="text-align:center;margin-bottom:8px">どんな方に向いていますか</h2>
      <p style="text-align:center;font-size:.92rem;color:var(--text-light);margin-bottom:26px">向いていない場合もあります。あてはまらないときは、無理にこの方法を選ぶ必要はありません。</p>
      <div class="card-grid">
        <div class="card">
          <h3>この方法が合いやすい方</h3>
          <ul class="tr-list tr-list--yes">
            <li>ご遺骨の一部を、お寺や神社にも納めたい</li>
            <li>すべてを散骨することに、まだ迷いがある</li>
            <li>故人は散骨を希望していたが、ご家族は納める場所も持ちたい</li>
            <li>お墓の管理や承継の負担はなくしたい</li>
            <li>お寺や神社にも、お参りできる場所を持っておきたい</li>
          </ul>
        </div>
        <div class="card">
          <h3>ほかの方法のほうが合う方</h3>
          <ul class="tr-list">
            <li>ご遺骨をすべて海へ還したい → <a href="/kaiyou-sou/">海洋散骨（54,450円〜）</a></li>
            <li>一部をご自宅や身近な場所に残したい → <a href="/temoto-kuyou/">お手元供養</a></li>
            <li>納めたご遺骨を、いつか手元に戻すかもしれない</li>
            <li>納骨の日程を自分で決めたい</li>
            <li>海洋散骨は希望せず、全量を納骨したい</li>
          </ul>
        </div>
      </div>
      <p style="text-align:center;margin-top:24px;font-size:.92rem"><a href="/shindan/" style="color:var(--green);font-weight:700">どれが合うか分からない方は、3分の診断でご希望を整理できます →</a></p>
    </div>
  </section>

  <!-- ④ 通常の海洋散骨との違い（優劣をつけず並列に書く） -->
  <section class="section" id="diff">
    <div class="container" style="max-width:660px">
      <h2 style="text-align:center;margin-bottom:8px">普通の海洋散骨とは何が違いますか</h2>
      <p style="text-align:center;font-size:.92rem;color:var(--text-light);margin-bottom:26px">違いは「ご遺骨の一部をお寺や神社にも納めるかどうか」だけです。どちらが良いということはありません。</p>
      <div class="card-grid">
        <div class="card">
          <h3>海洋散骨（通常）</h3>
          <dl class="tr-dl">
            <dt>ご遺骨</dt><dd>すべてを海へお還しします。</dd>
            <dt>手を合わせる場所</dt><dd>散骨した海そのものが、故人を思う場所・手を合わせる場所になります。緯度・経度入りの海洋葬証明書をお渡ししますので、同じ海域を訪れることもできます。</dd>
            <dt>費用</dt><dd>委託 54,450円〜／合同 148,500円〜／チャーター 176,000円〜（税込）</dd>
            <dt>その後の費用</dt><dd>かかりません。</dd>
          </dl>
        </div>
        <div class="card">
          <h3>寺院納骨＋海洋散骨（teraumi）</h3>
          <dl class="tr-dl">
            <dt>ご遺骨</dt><dd>一部を寺院・神社・霊園へ納め、残りを海へお還しします。</dd>
            <dt>手を合わせる場所</dt><dd>散骨した海に加えて、納骨先の寺院・神社にもお参りいただけます。納骨法要と年1回の合同法要が行われます。</dd>
            <dt>費用</dt><dd>270,000円（税込・粉骨と委託海洋葬を含む）</dd>
            <dt>その後の費用</dt><dd>管理費はかかりません。7年〜の供養ののち合祀されます。</dd>
          </dl>
        </div>
      </div>
    </div>
  </section>

  <!-- ⑤ 一部を残す、ほかの方法（ここでteraumiを推さない） -->
  <section class="section" id="other" style="background:var(--cream)">
    <div class="container" style="max-width:900px">
      <h2 style="text-align:center;margin-bottom:8px">一部を残す方法は、ほかにもあります</h2>
      <p style="text-align:center;font-size:.92rem;color:var(--text-light);margin-bottom:26px">残す場所によって、費用も手続きも変わります。3つを並べてご覧ください。</p>
      <div class="card-grid">
        <div class="card">
          <h3>ご自宅に残す</h3>
          <p style="font-size:.9rem;line-height:1.9">手のひらサイズのミニ骨壺に納めて、棚の上やリビングに。お仏壇がなくても置けます。お客様お持ち込みのお手元供養品へのご納骨は、分骨費用として5,500円（税込）です。</p>
          <p style="margin-top:12px"><a href="/temoto-kuyou/" style="color:var(--green);font-weight:700;font-size:.9rem">お手元供養を見る →</a></p>
        </div>
        <div class="card">
          <h3>身につける</h3>
          <p style="font-size:.9rem;line-height:1.9">お米一粒ほどのご遺骨を指輪の内側に封入します。見た目は普段使いのジュエリーなので、そのまま身につけて外出できます。</p>
          <p style="margin-top:12px"><a href="/jewelry-reform/" style="color:var(--green);font-weight:700;font-size:.9rem">メモリアルジュエリーを見る →</a></p>
        </div>
        <div class="card">
          <h3>寺院・神社に納める</h3>
          <p style="font-size:.9rem;line-height:1.9">提携先の合祀墓に納めます。管理費はかからず、宗教・宗派も問いません。お寺や神社にもお参りできる場所を持ちたい方に選ばれています。これが teraumi です。</p>
          <p style="margin-top:12px"><a href="#price" style="color:var(--green);font-weight:700;font-size:.9rem">費用と内訳を見る →</a></p>
        </div>
      </div>
    </div>
  </section>

  <!-- ⑥ 仕組み（縦積みで組み、SPで潰れないようにする） -->
  <section class="section" id="flow-img">
    <div class="container" style="max-width:660px">
      <h2 style="text-align:center;margin-bottom:22px">ご遺骨は、どのように分けますか</h2>
      <div class="card" style="text-align:center;font-weight:700;color:var(--green-mid)">ご遺骨をお預かりし、パウダー状にします（粉骨）</div>
      <p class="tr-arrow" aria-hidden="true">↓</p>
      <div class="card-grid">
        <div class="card">
          <h3>一部 → 寺院・神社へ納骨</h3>
          <p style="font-size:.9rem;line-height:1.9">提携先の合祀墓へ。納骨法要のあと、いつでもお参りいただけます。</p>
        </div>
        <div class="card">
          <h3>残り → 海洋散骨</h3>
          <p style="font-size:.9rem;line-height:1.9">鹿児島湾または博多湾へ。献花・献酒とともにお還しします。</p>
        </div>
      </div>
      <p style="text-align:center;margin-top:20px;font-size:.92rem;color:var(--text-light)">分ける量は粉骨のときに決められます。迷われている場合は、納める分を多めにしておくこともできます。</p>
    </div>
  </section>

  <!-- ⑦⑧ 納骨と散骨、それぞれの内容 -->
  <section class="section" style="background:var(--cream)">
    <div class="container" style="max-width:660px">
      <h2 style="text-align:center;margin-bottom:26px">納骨と散骨、それぞれの内容</h2>
      <div class="card-grid">
        <div class="card" id="tera">
          <h3>寺院・神社への納骨について</h3>
          <p style="font-size:.9rem;line-height:1.9">パウダー状にしたご遺骨を小さな袋に封入し、提携先の合祀墓へ納めます。納骨の際には法要を行い、その後は年に1回の合同法要が行われます。回忌法要も、ご希望があれば個別にご依頼いただけます。</p>
          <p style="font-size:.9rem;line-height:1.9;margin-top:12px">年間管理費はかかりません。檀家・信徒になっていただく必要もなく、宗教・宗派も問いません。納骨後は納骨先の作法でのご供養となります。</p>
          <p style="font-size:.86rem;line-height:1.85;margin-top:12px;color:var(--text-light)">7年〜の個別供養ののち合祀されます。合祀後はご遺骨をお返しできません。</p>
        </div>
        <div class="card" id="umi">
          <h3>海洋散骨について</h3>
          <p style="font-size:.9rem;line-height:1.9">スタッフが代わってお見送りする委託海洋葬です。粉骨・献花・献酒が含まれています。ご遺骨をお預かりしてから半年以内に散骨し、終了後に散骨した海域を記載した海洋葬証明書と当日のお写真をお届けします。</p>
          <p style="font-size:.9rem;line-height:1.9;margin-top:12px">有限会社 縁は一般社団法人日本海洋散骨協会の加盟事業者です。海水浴場や漁場を避けた海域を選び、協会のガイドラインに沿って行っています。</p>
          <p style="font-size:.9rem;line-height:1.9;margin-top:12px"><a href="/kaiyou-sou/" style="color:var(--green);font-weight:700">委託海洋葬について詳しく見る →</a></p>
        </div>
      </div>
    </div>
  </section>

  <!-- ⑨ 対応する寺院・神社・霊園と海域（GEOの中核。地域ごとに畳んで縦長を防ぐ） -->
  <section class="section" id="temples">
    <div class="container" style="max-width:820px">
      <h2 style="text-align:center;margin-bottom:8px">どの地域のお寺に納骨できますか</h2>
      <p style="text-align:center;font-size:.92rem;color:var(--text-light);margin-bottom:26px">現在の提携先は<?= $tr_area_total ?>都府県・<?= $tr_temple_total ?>か所です。地域名を押すと一覧が開きます。</p>
      <?php foreach ($tr_temples as $t): ?>
        <details style="<?= h($tr_acc) ?>">
          <summary style="<?= h($tr_sum) ?>"><?= h($t['area']) ?>（<?= (int)$t['n'] ?>か所）</summary>
          <ul class="tr-list" style="margin-top:10px"><?php foreach ($t['items'] as $i): ?><li><?= h($i) ?></li><?php endforeach; ?></ul>
        </details>
      <?php endforeach; ?>
      <p style="font-size:.88rem;color:var(--text-light);line-height:1.9;margin-top:14px">※ 一部の提携先は、掲載の都合により宗派と特徴のみを記載しています。実際の納骨先はご相談時にご案内し、ご希望があれば見学もしていただけます。</p>

      <h3 style="margin-top:36px;margin-bottom:14px;color:var(--green-mid)">散骨できる海域</h3>
      <p style="font-size:.92rem;line-height:1.95;margin-bottom:14px">このプランに含まれるのは<strong>鹿児島湾（錦江湾）</strong>と<strong>博多湾</strong>での散骨です。そのほかの海域も、散骨が可能な場所であれば別途費用で対応します。</p>
      <?php foreach ($tr_seas as $k => $v): ?>
        <p style="font-size:.9rem;line-height:1.9;margin-bottom:4px"><strong style="color:var(--green-mid)"><?= h($k) ?></strong>：<?= h($v) ?></p>
      <?php endforeach; ?>
      <p style="font-size:.88rem;color:var(--text-light);line-height:1.9;margin-top:14px">ご遺骨は日本郵便のゆうパックで全国からお送りいただけます。お打ち合わせはお電話・LINE・メールで完結します。</p>
    </div>
  </section>

  <!-- ⑩ 料金（ここで初めて金額を出す。直後に予算別の代替案を必ず置く） -->
  <section class="section" id="price" style="background:var(--cream)">
    <div class="container" style="max-width:660px">
      <h2 style="text-align:center;margin-bottom:8px">費用はいくらですか</h2>
      <p style="text-align:center;font-size:.92rem;color:var(--text-light);margin-bottom:26px">粉骨から納骨・海洋散骨・証明書の発行までを含んだ金額です。</p>
      <div class="card" style="text-align:center;margin-bottom:20px">
        <p style="color:var(--green);font-weight:700;margin-bottom:4px"><span style="font-size:2.2rem">270,000</span>円<small style="font-size:.8rem;color:var(--text-light);font-weight:400;margin-left:4px">（税込）</small></p>
        <p style="font-size:.9rem;color:var(--text-light);margin:0">お一人分・粉骨・納骨・委託海洋葬を含む</p>
      </div>
      <div class="card-grid">
        <div class="card">
          <h3>納骨（寺院・神社側）に含まれるもの</h3>
          <ul class="tr-list"><?php foreach ($tr_price_tera as $i): ?><li><?= h($i) ?></li><?php endforeach; ?></ul>
        </div>
        <div class="card">
          <h3>海洋散骨側に含まれるもの</h3>
          <ul class="tr-list"><?php foreach ($tr_price_umi as $i): ?><li><?= h($i) ?></li><?php endforeach; ?></ul>
        </div>
      </div>
      <div class="card-grid" style="margin-top:16px">
        <div class="card"><h3>お二人目以降の粉骨</h3><p style="font-size:.9rem;line-height:1.9">22,000円（税込・1柱ごと）</p></div>
        <div class="card"><h3>お二人目以降の海洋散骨</h3><p style="font-size:.9rem;line-height:1.9">追加費用はかかりません</p></div>
        <div class="card"><h3>お二人目以降の納骨</h3><p style="font-size:.9rem;line-height:1.9">納骨先により異なります。お見積り時にご案内します</p></div>
        <div class="card"><h3>ご遺骨のお引き取り</h3><p style="font-size:.9rem;line-height:1.9">1kmあたり200円＋高速道路料金などの実費</p></div>
      </div>

      <!-- 逃げ道：270,000円が合わない方を行き止まりにしない。
           どちらも海洋散骨を行う方法であることを明示し、海洋散骨の価値を下げない。 -->
      <div class="card" style="margin-top:24px;border-left:4px solid var(--green)">
        <h3>「一部を残したいが、この金額は難しい」という場合</h3>
        <p style="font-size:.92rem;line-height:1.95">ご遺骨の一部を残す方法は、寺院納骨だけではありません。<strong>委託海洋葬 54,450円〜 ＋ 分骨 5,500円（税込）で、59,950円〜</strong>という形もあります。</p>
        <p style="font-size:.92rem;line-height:1.95;margin-top:10px">どちらも海洋散骨を行う方法です。違いは、一部のご遺骨をご自宅など身近な場所に残すか、寺院・神社等へ納めるかです。<br>
        ※ 分骨費用5,500円（税込）は、お客様お持ち込みのお手元供養品へご納骨する場合の費用です。</p>
        <p style="font-size:.92rem;line-height:1.95;margin-top:10px">金額でどちらかに決める必要はありませんので、ご予算も含めてご相談ください。</p>
        <p style="margin-top:14px"><a href="/temoto-kuyou/" class="btn btn--outline">海洋散骨＋お手元供養を見る</a></p>
      </div>
    </div>
  </section>

  <!-- ⑪ 墓じまいとの組み合わせ -->
  <section class="section" id="grave">
    <div class="container" style="max-width:820px">
      <h2 style="text-align:center">墓じまいのあとに選ぶこともできます</h2>
      <div class="prose">
        <p>先祖代々のお墓を閉じる際にも、この方法をお選びいただけます。お墓からのご遺骨の取り出し、改葬許可の手続き、墓石の撤去までを承ったうえで、一部を寺院・神社へ、残りを海へお還しします。お墓まで伺ってのお預かりにも対応しています。</p>
        <p>長くお墓に納められていたご遺骨は、土や湿気で汚れていることが多いため、洗骨（27,500円〜）で洗浄・殺菌・乾燥してから粉骨します。墓石の撤去は無料でお見積りします。</p>
        <p>墓じまい後のご遺骨の行き先は、海洋散骨・寺院や霊園への永代供養・樹木葬・お手元供養・この方法（一部を納骨し一部を散骨）から選べます。決まっていない状態でも、墓じまいの検討は始められます。</p>
      </div>
      <p style="text-align:center;margin-top:20px">
        <a href="/grave/" class="btn btn--outline">お墓じまいについて見る</a>
        <a href="/grave/sankotsu/" class="btn btn--outline">墓じまい後の散骨について見る</a>
      </p>
    </div>
  </section>

  <!-- ⑫ 利用の流れ -->
  <section class="section" id="flow" style="background:var(--cream)">
    <div class="container" style="max-width:820px">
      <h2 style="text-align:center;margin-bottom:26px">ご依頼の流れ</h2>
      <?php foreach ($tr_flow as $n => $f): ?>
        <div class="card" style="display:flex;gap:16px;align-items:flex-start;margin-bottom:12px">
          <span class="tr-num"><?= $n + 1 ?></span>
          <div>
            <h3><?= h($f['t']) ?></h3>
            <p style="font-size:.9rem;line-height:1.9;margin:0"><?= h($f['d']) ?></p>
          </div>
        </div>
      <?php endforeach; ?>
      <p style="font-size:.88rem;color:var(--text-light);line-height:1.9;margin-top:12px">※ 納骨（5）と海洋散骨（6）は並行して進みます。お申し込み時に必要なのは、火葬許可証・埋葬許可証・改葬許可証のいずれかの写し1部、印鑑、ご遺骨です。</p>
      <p style="text-align:center;margin-top:16px;font-size:.9rem"><a href="/powder-cleaning/" style="color:var(--green);font-weight:700">粉骨・洗骨について詳しく見る →</a></p>
    </div>
  </section>

  <!-- ⑬ 資料請求CTA（全ページ共通パーツ） -->
  <?php require __DIR__ . '/../includes/shiryou-cta.php'; ?>

  <!-- ⑭ FAQ -->
  <section class="section" id="faq" style="background:var(--white)">
    <div class="container" style="max-width:820px">
      <h2 style="text-align:center;margin-bottom:8px">よくあるご質問</h2>
      <p style="text-align:center;font-size:.9rem;color:var(--text-light);margin-bottom:24px">海洋散骨そのものについては、<a href="/kaiyou-sou/" style="color:var(--green);text-decoration:underline">海洋葬（海洋散骨）のページ</a>でも詳しくご説明しています。用語は<a href="/glossary/" style="color:var(--green);text-decoration:underline">供養用語辞典</a>をご覧ください。</p>
      <?php foreach ($tr_faq as $f): ?>
        <details style="<?= h($tr_acc) ?>">
          <summary style="<?= h($tr_sum) ?>">Q. <?= h($f['q']) ?></summary>
          <p style="margin-top:10px;font-size:.95rem;line-height:1.9">A. <?= h($f['a']) ?></p>
        </details>
      <?php endforeach; ?>
      <div class="card" style="text-align:center;margin-top:24px;background:var(--cream)">
        <p style="font-size:.94rem;margin-bottom:14px">ここに載っていないことは、直接おたずねください。ご相談・お見積りは無料です。</p>
        <p style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap;margin:0">
          <a href="tel:<?= h($tr_tel) ?>" class="btn">電話で聞いてみる（<?= h(SITE['tel']) ?>）</a>
          <a href="<?= h(SITE['line_url']) ?>" target="_blank" rel="noopener" class="btn btn--outline">LINEで聞いてみる</a>
        </p>
      </div>
    </div>
  </section>

  <!-- ⑮ 最終CTA（主CTAは申込ではなく相談。まだ決めていない方を必ず受け入れる） -->
  <section class="section" id="cta" style="background:linear-gradient(135deg,var(--green),var(--green-mid));color:#fff;text-align:center">
    <div class="container">
      <h2 style="color:#fff">どの供養方法が合うか、一緒に整理しませんか</h2>
      <p style="opacity:.92;margin-bottom:8px">「まだ決めていない」「家族と話す材料がほしい」でも歓迎です。ご相談・お見積りは無料、こちらから営業のご連絡はいたしません。</p>
      <p style="opacity:.86;font-size:.88rem;margin-bottom:22px">受付：<?= h(SITE['hours_jp']) ?>（メール・LINEは24時間受付）</p>
      <p style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap">
        <a href="tel:<?= h($tr_tel) ?>" class="btn" style="background:#fff;color:var(--green-mid)">電話で相談する（<?= h(SITE['tel']) ?>）</a>
        <a href="<?= h(SITE['line_url']) ?>" target="_blank" rel="noopener" class="btn" style="background:#06C755">LINEで相談する</a>
        <a href="<?= h($tr_form) ?>" class="btn" style="background:#d8b46a;color:#1c2b33">メールで相談・見積り</a>
      </p>
      <p style="margin-top:18px;font-size:.9rem;opacity:.92">まだ方法を決めかねている方は、<a href="/shindan/" style="color:#fff;text-decoration:underline">3分の診断でご希望を整理する</a>こともできます。</p>
    </div>
  </section>
</main>

<style>
  /* このページ専用のCSSはこの4つだけ。レイアウト・カード・ボタン・見出しは
     すべて common.css のグローバルコンポーネント（.card-grid / .card / .btn / .section /
     .container / .page-hero / .prose）に任せている。列数はコンテナ幅で決める。 */
  .tr-list{list-style:none;margin:0;padding:0}
  .tr-list li{font-size:.9rem;line-height:1.8;padding:7px 0 7px 18px;position:relative;border-bottom:1px dashed var(--border)}
  .tr-list li:last-child{border-bottom:none}
  .tr-list li::before{content:'・';position:absolute;left:0;color:var(--green);font-weight:700}
  .tr-list--yes li::before{content:'✓'}
  .tr-list a{color:var(--green);font-weight:700}
  .tr-dl{margin:0}
  .tr-dl dt{font-size:.8rem;font-weight:700;color:var(--text-light);margin-top:12px}
  .tr-dl dd{font-size:.9rem;line-height:1.85;margin:2px 0 0}
  .tr-arrow{text-align:center;color:var(--green-mid);font-size:1.2rem;font-weight:700;margin:8px 0}
  .tr-num{flex:none;width:30px;height:30px;border-radius:50%;background:var(--green);color:#fff;display:grid;place-items:center;font-size:.82rem;font-weight:700}
</style>

<script type="application/ld+json">
<?= json_encode([
  '@context' => 'https://schema.org',
  '@type' => 'Service',
  'serviceType' => '寺院納骨＋海洋散骨',
  'name' => '寺院納骨＋海洋散骨「teraumi」',
  'description' => 'ご遺骨の一部を提携する寺院・神社・霊園に納め、残りを海へ散骨する供養方法。粉骨・納骨・委託海洋葬・海洋葬証明書の発行を含む。管理費不要・宗教宗派不問。',
  'provider' => ['@id' => SITE['url'] . '/#organization'],   // 既存のOrganizationを参照（別法人と誤解させない）
  'url' => SITE['url'] . '/teraumi/',
  'areaServed' => array_map(fn($t) => ['@type' => 'State', 'name' => $t['area']], $tr_temples),
  'offers' => [
    '@type' => 'Offer',
    'price' => '270000',
    'priceCurrency' => 'JPY',
    'description' => 'お一人分。粉骨・寺院または神社への納骨・委託海洋葬・献花献酒・海洋葬証明書を含む（税込）',
  ],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>
</script>
<script type="application/ld+json">
<?= json_encode([
  '@context' => 'https://schema.org',
  '@type' => 'BreadcrumbList',
  'itemListElement' => [
    ['@type' => 'ListItem', 'position' => 1, 'name' => 'ホーム', 'item' => SITE['url'] . '/'],
    ['@type' => 'ListItem', 'position' => 2, 'name' => 'サービス一覧', 'item' => SITE['url'] . '/service/'],
    ['@type' => 'ListItem', 'position' => 3, 'name' => '寺院納骨＋海洋散骨（teraumi）', 'item' => SITE['url'] . '/teraumi/'],
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
  ], $tr_faq),
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>
</script>
<?php require __DIR__ . '/../includes/footer.php'; ?>
