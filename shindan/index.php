<?php
require_once __DIR__ . '/../includes/config.php';
$page_title     = '供養の選び方診断｜遺骨・お墓をどうするか、3分で整理できます｜' . SITE['name'];
$page_desc      = '「遺骨をどうすればいいか分からない」「海洋散骨か手元供養か迷う」「墓じまいの後はどうする？」——いくつかの質問に答えるだけで、今の状況に合う供養方法の組み合わせを整理できます。お名前の入力は不要。鹿児島・福岡の供養専門会社 縁（えん）。';
$page_canonical = SITE['url'] . '/shindan/';
$page_hero_image = '/assets/img/hero-shindan.jpg';
require __DIR__ . '/../includes/head.php';
?>
<body>
<?php require __DIR__ . '/../includes/header.php'; ?>
<section class="page-hero">
  <h1>供養の選び方診断</h1>
  <p>いくつかの質問で、今の状況に合う選択肢を整理できます（約3分）</p>
</section>
<nav class="breadcrumb"><a href="/">ホーム</a> ＞ 供養の選び方診断</nav>

<style>
.shindan-lead{max-width:720px;margin:0 auto 8px;text-align:center;font-size:1rem;line-height:2;color:var(--text)}
.shindan-note{max-width:720px;margin:0 auto 30px;text-align:center;font-size:.82rem;color:var(--text-light)}
.shindan-card{max-width:760px;margin:0 auto;background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);box-shadow:var(--shadow);padding:34px 30px 30px}
.sd-progress{max-width:300px;margin:0 auto 6px;height:6px;border-radius:999px;background:var(--cream-dark,#eee);overflow:hidden}
.sd-progress b{display:block;height:100%;background:var(--green);border-radius:999px;transition:width .35s}
.sd-progress-txt{text-align:center;font-size:.75rem;color:var(--text-light);margin-bottom:18px}
.sd-q{font-family:var(--serif);font-size:1.3rem;color:var(--green-mid);text-align:center;line-height:1.6;margin-bottom:6px}
.sd-qsub{text-align:center;font-size:.86rem;color:var(--text-light);margin-bottom:22px}
.sd-options{display:grid;gap:12px;margin-top:18px}
.sd-opt{display:flex;flex-direction:column;gap:3px;text-align:left;background:var(--sea-light);border:1px solid var(--border);border-radius:12px;padding:16px 20px;cursor:pointer;transition:.2s;font-family:inherit;color:var(--text);width:100%;min-height:56px;justify-content:center}
.sd-opt:hover{background:#fff;border-color:var(--green);transform:translateY(-2px);box-shadow:0 8px 20px rgba(21,112,158,.12)}
.sd-opt b{font-size:1rem;font-weight:600;color:var(--green-mid)}
.sd-opt span{font-size:.82rem;color:var(--text-light)}
.sd-back{display:inline-flex;align-items:center;gap:6px;margin-top:22px;background:none;border:none;color:var(--text-light);font-size:.85rem;cursor:pointer;font-family:inherit}
.sd-back:hover{color:var(--green)}
/* result */
.sd-result-label{display:block;width:fit-content;margin:0 auto 10px;background:var(--green);color:#fff;font-size:.78rem;font-weight:700;letter-spacing:.08em;padding:5px 16px;border-radius:999px}
.sd-result-name{font-family:var(--serif);font-size:1.45rem;color:var(--green-mid);text-align:center;line-height:1.6;margin-bottom:16px}
.sd-block{background:var(--cream);border-radius:12px;padding:16px 20px;margin-bottom:12px}
.sd-block h4{font-size:.85rem;color:#8a6a2a;font-weight:700;margin-bottom:6px;letter-spacing:.04em}
.sd-block p,.sd-block li{font-size:.92rem;line-height:1.9}
.sd-block ul{list-style:none;display:grid;gap:4px}
.sd-block li::before{content:'・'}
.sd-svc{display:grid;gap:10px;margin:16px 0 6px}
.sd-svc a{display:flex;justify-content:space-between;align-items:center;gap:10px;background:#fff;border:1.5px solid var(--green);border-radius:12px;padding:14px 18px;color:var(--green-mid);font-weight:700;text-decoration:none;transition:.2s}
.sd-svc a:hover{background:var(--green);color:#fff}
.sd-svc a small{font-weight:400;font-size:.78rem;color:var(--text-light)}
.sd-svc a:hover small{color:#e8f4f9}
.sd-alt{margin-top:14px}
.sd-alt h4{font-size:.85rem;color:var(--text-light);text-align:center;margin-bottom:10px;font-weight:600}
.sd-alt-list{display:flex;flex-wrap:wrap;gap:8px;justify-content:center}
.sd-alt-list a{font-size:.84rem;color:var(--green);border:1px solid var(--border);border-radius:999px;padding:6px 14px;background:#fff;text-decoration:none}
.sd-alt-list a:hover{background:var(--green);color:#fff;border-color:var(--green)}
.sd-reassure{margin:20px auto 0;background:var(--sea-light);border-radius:12px;padding:16px 20px;font-size:.86rem;line-height:1.9;color:var(--text);text-align:center}
.sd-contact{margin-top:24px;background:linear-gradient(135deg,var(--green),var(--green-mid));border-radius:var(--radius-lg);color:#fff;text-align:center;padding:26px 22px}
.sd-contact h3{color:#fff;font-family:var(--serif);font-size:1.15rem;margin-bottom:6px}
.sd-contact p{opacity:.94;font-size:.88rem;margin-bottom:16px}
.sd-contact .btns{display:flex;flex-wrap:wrap;gap:10px;justify-content:center}
.sd-tel{display:block;margin-top:12px;color:#fff;font-weight:700;font-size:1.15rem}
/* よくある迷い（SEO用・静的） */
.sd-guide{max-width:820px;margin:44px auto 0}
.sd-guide h2{text-align:center;margin-bottom:8px}
.sd-guide>p{text-align:center;font-size:.9rem;color:var(--text-light);margin-bottom:22px}
.sd-guide-item{background:#fff;border:1px solid var(--border);border-radius:12px;padding:18px 22px;margin-bottom:12px}
.sd-guide-item h3{font-size:1rem;color:var(--green-mid);margin-bottom:8px}
.sd-guide-item p{font-size:.92rem;line-height:1.95}
.sd-guide-item a{color:var(--green);font-weight:600}
@media(max-width:560px){.shindan-card{padding:24px 16px}.sd-q{font-size:1.1rem}.sd-result-name{font-size:1.22rem}.sd-opt{padding:15px 16px}}
</style>

<main class="section">
  <div class="container">
    <p class="shindan-lead">海洋散骨・墓じまい・粉骨・手元供養——名前は知っていても、<br class="pc-only">「自分の場合はどれなのか」は分かりにくいものです。<br>いくつかの質問に答えると、今の状況に合う選択肢の組み合わせが表示されます。</p>
    <p class="shindan-note">お名前や連絡先の入力はありません。診断は目安です。途中で戻ることも、やり直すこともできます。</p>

    <div class="shindan-card">
      <div id="sd-quiz"><!-- JSで描画 --></div>
    </div>

    <div style="text-align:center;margin-top:22px">
      <p style="font-size:.9rem;color:var(--text-light)">質問に答えるより、直接話すほうが早い方はこちらからどうぞ。</p>
      <a href="/contact/" class="btn btn--outline" style="margin-top:10px">まずは無料で相談する</a>
    </div>

    <!-- よくある迷い（検索・AI引用向けの静的コンテンツ） -->
    <div class="sd-guide">
      <h2>診断でよくある、3つの迷い</h2>
      <p>実際のご相談でも、この3つで迷われる方がほとんどです。</p>
      <div class="sd-guide-item">
        <h3>1. 遺骨を全部撒いてしまっていいのか迷う</h3>
        <p>海洋散骨は、ご遺骨のすべてを撒かなければいけないものではありません。大部分を海へ、ひとつまみをミニ骨壷やペンダントでお手元に——という分け方（分骨）ができます。散骨したご遺骨は戻りませんが、残した分をあとから散骨することはいつでもできるので、迷ったら少量を残しておくのがおすすめです。<a href="/blog/?id=sankotsu-ichibu-nokosu">遺骨の一部を手元に残す方法と費用 →</a></p>
      </div>
      <div class="sd-guide-item">
        <h3>2. 墓じまいをした後、遺骨をどこへ移すか決まらない</h3>
        <p>行き先が決まっていなくても、墓じまいの相談は始められます。取り出したご遺骨は、海洋散骨・樹木葬・永代供養墓・手元供養などから選べます。当社は撤去から粉骨・散骨・納骨まで一括で対応しているため、「まず出してから、落ち着いて決める」こともできます。<a href="/grave/">お墓じまいについて詳しく →</a></p>
      </div>
      <div class="sd-guide-item">
        <h3>3. 供養方法ごとの費用の違いが分からない</h3>
        <p>目安として、委託海洋散骨54,450円〜、粉骨24,200円〜、墓じまい基本プラン33万円（いずれも税込）です。お墓を維持する場合の管理料や、納骨堂・永代供養との違いは比較記事にまとめています。<a href="/blog/?id=kuyou-hiyou-hikaku">供養方法の費用比較を見る →</a></p>
      </div>
    </div>
  </div>
</main>

<script>
(function(){
  var SITE_TEL = <?= json_encode(SITE['tel']) ?>;
  var SITE_LINE = <?= json_encode(SITE['line_url']) ?>;
  function ga(ev,params){ try{ if(window.gtag){ params=params||{}; params.page_path=location.pathname; gtag('event',ev,params); } }catch(e){} }

  /* ---------- サービス定義 ---------- */
  var SV = {
    'kaiyou-sou':      {name:'海洋散骨（海洋葬）', href:'/kaiyou-sou/',      s:'委託54,450円〜・鹿児島／福岡の海域・全国郵送対応', cat:'海洋葬（海洋散骨）'},
    'teien-sou':       {name:'樹木葬（庭苑葬）',   href:'/teien-sou/',       s:'草花に囲まれる自然葬・永代供養対応', cat:'樹木葬'},
    'grave':           {name:'お墓じまい',         href:'/grave/',           s:'撤去〜納骨まで基本プラン33万円（税込）', cat:'お墓じまい'},
    'hikkoshi':        {name:'お墓のお引越し（改葬）', href:'/hikkoshi/',    s:'手続き代行から一括対応', cat:'お墓のお引越し'},
    'temoto-kuyou':    {name:'お手元供養',         href:'/temoto-kuyou/',    s:'ミニ骨壷・ミニ仏壇・分骨5,500円（税込）', cat:'お手元供養'},
    'powder-cleaning': {name:'粉骨・洗骨',         href:'/powder-cleaning/', s:'粉骨24,200円〜・六価クロム検査つき・郵送全国対応', cat:'粉骨・洗骨'},
    'pet-kaiyou-sou':  {name:'ペット海洋葬',       href:'/pet-kaiyou-sou/',  s:'鹿児島・錦江湾で半年に一度開催', cat:'ペット供養'},
    'jewelry-reform':  {name:'メモリアルジュエリー', href:'/jewelry-reform/', s:'お米一粒ほどのご遺骨を指輪に封入', cat:'JEWELRYリフォーム'},
    'seizen':          {name:'海洋散骨 生前契約',   href:'/seizen/',          s:'お元気なうちに内容と費用を確定', cat:'海洋葬（海洋散骨）'}
  };

  /* ---------- 質問ツリー ---------- */
  var TREE = {
    start:{q:'いま、いちばん近いお悩み・状況はどれですか？',sub:'直感で選んでください（あとから戻れます）',opts:[
      {b:'お墓をどうするか悩んでいる',s:'管理・承継・墓じまいなど',next:'m1'},
      {b:'ご遺骨をどうするか考えている',s:'散骨・納骨・自宅保管など',next:'i1'},
      {b:'海洋散骨が気になっている',s:'費用や流れを知りたい',next:'k1'},
      {b:'一部を手元に残す方法を知りたい',s:'ミニ骨壷・ジュエリーなど',next:'t1'},
      {b:'自分の将来の準備をしておきたい',s:'生前準備・終活',next:'s1'},
      {b:'ペットのご供養を考えている',s:'',res:'pet'},
      {b:'何から考えればいいか分からない',s:'状況の整理から始めます',next:'g1'}
    ]},
    g1:{q:'いまの状況に近いのはどれですか？',sub:'状況だけで大丈夫です',opts:[
      {b:'ご遺骨が自宅にある',s:'',next:'i2'},
      {b:'お墓のことで悩んでいる',s:'',next:'m1'},
      {b:'これからのこと（生前の準備）',s:'',next:'s1'},
      {b:'情報を集めはじめたところ',s:'',res:'consult'}
    ]},
    m1:{q:'お墓について、近いのはどれですか？',opts:[
      {b:'管理や承継が負担。お墓を閉じたい',s:'墓じまいを検討',next:'m2'},
      {b:'お墓を別の場所へ移したい',s:'近くへ・新しい霊園へ',res:'hikkoshi'},
      {b:'お墓の中のご遺骨の行き先を考えたい',s:'',next:'m2'}
    ]},
    m2:{q:'お墓じまいの後、ご遺骨はどうしたいですか？',sub:'決まっていなくても大丈夫です',opts:[
      {b:'海など、自然に還したい',s:'',next:'m3'},
      {b:'樹木葬・永代供養などに納めたい',s:'',res:'grave-teien'},
      {b:'まだ決めていない',s:'',res:'grave-consult'}
    ]},
    m3:{q:'ご遺骨は、すべて海に還しますか？',sub:'散骨した分は、あとから取り戻せません',opts:[
      {b:'すべて還したい',s:'',region:true,res:'grave-kaiyou'},
      {b:'一部は手元に残したい',s:'',region:true,res:'grave-kaiyou-temoto'},
      {b:'そこで迷っている',s:'',flag:'mayoi',region:true,res:'grave-kaiyou-temoto'}
    ]},
    i1:{q:'ご遺骨は、いまどちらにありますか？',opts:[
      {b:'自宅にある（納骨前）',s:'',next:'i2'},
      {b:'お墓・納骨堂の中にある',s:'取り出しからのご案内になります',next:'m1'},
      {b:'長年保管していて、状態が気になる',s:'カビ・湿気など',res:'powder'}
    ]},
    i2:{q:'どんな形が、お気持ちに近いですか？',opts:[
      {b:'海など、自然に還したい',s:'',next:'i3'},
      {b:'自宅に置いて供養したい',s:'',res:'temoto'},
      {b:'身につけられる形にしたい',s:'指輪・ペンダント',res:'jewelry'},
      {b:'比べてから決めたい',s:'',res:'consult'}
    ]},
    i3:{q:'ご遺骨は、すべて海に還しますか？',sub:'散骨した分は、あとから取り戻せません',opts:[
      {b:'すべて還したい',s:'',next:'k2'},
      {b:'一部は手元に残したい',s:'',flag:'partial',next:'k2'},
      {b:'そこで迷っている',s:'',flag:'mayoi',next:'k2'}
    ]},
    k1:{q:'散骨について、いまのお気持ちに近いのは？',opts:[
      {b:'すべて散骨するつもり',s:'',next:'k2'},
      {b:'一部を残すか迷っている',s:'',flag:'mayoi',next:'k2'},
      {b:'家族の意見がまとまっていない',s:'',flag:'family',next:'k2'}
    ]},
    k2:{q:'散骨の当日は、ご家族で乗船したいですか？',opts:[
      {b:'乗船して、自分たちで見送りたい',s:'チャーター・合同海洋葬',flag:'josen',region:true,res:'kaiyou'},
      {b:'おまかせしたい（委託）',s:'スタッフが代わりに散骨・全国から郵送可',region:true,res:'kaiyou'},
      {b:'費用を見て決めたい',s:'',region:true,res:'kaiyou'}
    ]},
    t1:{q:'手元に残す形は、どちらが近いですか？',opts:[
      {b:'ミニ骨壷などで、自宅に置きたい',s:'',res:'temoto'},
      {b:'指輪やペンダントで、身につけたい',s:'',res:'jewelry'},
      {b:'残りのご遺骨の供養もあわせて考えたい',s:'散骨など',next:'i3'}
    ]},
    s1:{q:'ご自身の将来について、近いのはどれですか？',opts:[
      {b:'「海に散骨してほしい」と家族に託したい',s:'',res:'seizen'},
      {b:'お墓を持たない供養を探している',s:'',next:'s2'},
      {b:'今あるお墓を、将来どうするか考えたい',s:'',next:'m1'},
      {b:'何を決めておくべきか、まず知りたい',s:'',res:'seizen-consult'}
    ]},
    s2:{q:'どちらのイメージが近いですか？',opts:[
      {b:'海へ還る',s:'',res:'seizen'},
      {b:'樹木や草花のそばで眠る',s:'',res:'teien'}
    ]},
    region:{q:'お住まい（またはご遺骨のある地域）はどちらですか？',sub:'ご案内の方法が変わるためうかがいます',opts:[
      {b:'鹿児島県',s:'',rg:'鹿児島'},
      {b:'福岡県・九州各県',s:'',rg:'福岡・九州'},
      {b:'それ以外の地域',s:'',rg:'その他'}
    ]}
  };

  /* ---------- 結果定義 ---------- */
  var RES = {
    'kaiyou':{ dynamic:true },
    'grave-kaiyou':{
      name:'お墓じまい ＋ 粉骨・海洋散骨',
      why:'お墓の維持の負担をなくし、取り出したご遺骨を海に還す組み合わせです。撤去・改葬手続き・粉骨・散骨まで一つの窓口で完結するため、業者を探し直す必要がありません。',
      fit:['お墓の管理・承継の負担をなくしたい方','散骨後は、お墓の維持費がかからない形にしたい方'],
      care:'閉眼供養のお布施（1〜5万円程度）や、寺院墓地の場合の離檀のご挨拶など、工事費以外に必要なものは事前にすべてご説明します。',
      main:['grave','kaiyou-sou'], rel:['powder-cleaning','temoto-kuyou']},
    'grave-kaiyou-temoto':{
      name:'お墓じまい ＋ 海洋散骨 ＋ 一部を手元供養',
      why:'お墓を閉じて大部分を海へ還し、ひとつまみをミニ骨壷などでお手元に残す組み合わせです。「全部撒いてしまうのは寂しい」という気持ちと、「お墓の負担をなくしたい」という事情を両立できます。',
      fit:['お墓は閉じたいが、手を合わせる場所も残したい方','ご家族の中に「少し残したい」という方がいる場合'],
      care:'散骨したご遺骨はあとから取り戻せません。残す量は粉骨のときに決められますので、迷ったら多めに残しておき、あとから散骨する形もできます。',
      main:['grave','kaiyou-sou','temoto-kuyou'], rel:['jewelry-reform','powder-cleaning']},
    'grave-teien':{
      name:'お墓じまい ＋ 樹木葬・永代供養',
      why:'今のお墓を閉じて、承継者がいなくても供養が続く形（樹木葬・永代供養墓など）へ移す組み合わせです。「お参りする場所は残したい」方に合っています。',
      fit:['お参りできる場所を残したい方','子どもや親族に管理を引き継がせたくない方'],
      care:'合祀（他の方と一緒に埋葬）を選ぶと、あとから特定のご遺骨を取り出せなくなります。個別区画か合祀かは、ご家族で事前に話し合っておくのがおすすめです。',
      main:['grave','teien-sou'], rel:['kaiyou-sou','temoto-kuyou']},
    'grave-consult':{
      name:'お墓じまい（行き先は、これから一緒に考える）',
      why:'ご遺骨の行き先が決まっていなくても、墓じまいの検討は始められます。当社は撤去から散骨・樹木葬・手元供養まで自社で対応しているため、「まず取り出してから、落ち着いて決める」ことができます。',
      fit:['お墓の負担はなくしたいが、その後をまだ決めていない方','家族との相談材料がほしい方'],
      care:'石材店・散骨業者・納骨先を別々に探すと、連絡や遺骨の受け渡しが煩雑になりがちです。窓口を一つにまとめると負担が減ります。',
      main:['grave'], rel:['kaiyou-sou','teien-sou','temoto-kuyou']},
    'hikkoshi':{
      name:'お墓のお引越し（改葬）',
      why:'お墓が遠くてお参りが大変な場合や、新しい霊園へ移したい場合は「改葬」という手続きで、ご遺骨を移せます。改葬許可申請の代行からお手伝いします。',
      fit:['お墓が遠方にあり、お参りの負担が大きい方','お参りしやすい場所にお墓を残したい方'],
      care:'移した先でも管理料などの維持費は続きます。維持をなくしたい場合は、墓じまい＋散骨・永代供養の組み合わせもあわせてご検討ください。',
      main:['hikkoshi'], rel:['grave','kaiyou-sou']},
    'powder':{
      name:'粉骨・洗骨（ご遺骨のケア）',
      why:'長年保管したご遺骨の湿気・カビ・汚れは、洗骨（アルカリ水での手洗い洗浄）と乾燥・殺菌で整えられます。粉骨まで行えば真空パック＋桐箱で、この先も安心して保管できます。',
      fit:['ご自宅で長く保管していて、状態が気になる方','この先の散骨・納骨に備えて、きれいに整えておきたい方'],
      care:'当社は粉骨の際、発がん性物質「六価クロム」の検査・無害化も標準で行っています（追加料金なし）。',
      main:['powder-cleaning'], rel:['temoto-kuyou','kaiyou-sou']},
    'temoto':{
      name:'お手元供養',
      why:'ご遺骨をご自宅で保管するのは法律上も問題なく、期限もありません。手のひらサイズのミニ骨壷なら、お仏壇がなくても棚の上に置けます。',
      fit:['いつも身近に感じながら供養したい方','納骨や散骨を急がず、ゆっくり考えたい方'],
      care:'長期保管は湿気によるカビに注意が必要です。粉骨＋真空パックにしておくと安心です。将来、散骨や納骨に切り替えることもできます。',
      main:['temoto-kuyou'], rel:['jewelry-reform','powder-cleaning','kaiyou-sou']},
    'jewelry':{
      name:'メモリアルジュエリー ＋ お手元供養',
      why:'お米一粒ほどのご遺骨を指輪の内側に封入します。見た目は普段使いのジュエリーなので、そのまま身につけて出かけられます。お手持ちの指輪の加工も査定できます。',
      fit:['形見として身につけたい方','ご家族それぞれが少しずつ持ちたい場合'],
      care:'ジュエリーに納めるのはごく少量です。残りのご遺骨の供養（散骨・納骨・自宅保管）も、あわせて同じ窓口でご相談いただけます。',
      main:['jewelry-reform','temoto-kuyou'], rel:['kaiyou-sou']},
    'teien':{
      name:'樹木葬（庭苑葬）',
      why:'墓石の代わりに草花に囲まれて眠る自然葬です。承継者がいなくても供養が続く永代供養に対応しています。',
      fit:['自然に還りたいが、お参りできる「場所」も残したい方','お墓の承継者がいない方'],
      care:'区画や合祀の形式によって、あとからご遺骨を取り出せるかどうかが変わります。見学時にご確認ください。',
      main:['teien-sou'], rel:['kaiyou-sou','grave']},
    'seizen':{
      name:'海洋散骨 生前契約',
      why:'「海に散骨してほしい」という希望は、伝えるだけでは実現されないことがあります。生前契約で内容と費用を確定しておけば、希望どおりのお見送りが確実になり、ご家族の迷いと負担もなくなります。',
      fit:['自分の供養は自分で決めておきたい方','家族に費用や手配の負担を残したくない方'],
      care:'ご家族に契約のことを伝え、エンディングノートや遺言書にも残しておくと確実です。伝え方のご相談も承ります。',
      main:['seizen'], rel:['kaiyou-sou','temoto-kuyou']},
    'seizen-consult':{
      name:'生前準備の無料相談から',
      why:'決めておくと家族が助かるのは、大きく「供養の形」「費用の目安」「伝え方」の3つです。何をどこまで決めるべきかは状況によって違うため、まず現状を聞かせていただくのが近道です。',
      fit:['漠然と将来が不安な方','終活を何から始めるか決めかねている方'],
      care:'相談したからといって、契約を勧めることはありません。「話を聞くだけ」で大丈夫です。',
      main:['seizen'], rel:['kaiyou-sou','teien-sou','grave']},
    'pet':{
      name:'ペット海洋葬',
      why:'大切なご家族であるペットを、鹿児島・錦江湾の海へお見送りします。半年に一度、ペット専用の委託海洋葬を行っています。',
      fit:['自然が好きだった子を、海へ還してあげたい方','お骨の供養先を探している方'],
      care:'一部を残してペット用のミニ骨壷やジュエリーにすることもできます。',
      main:['pet-kaiyou-sou'], rel:['temoto-kuyou','jewelry-reform']},
    'consult':{
      name:'まずは「比べる材料」から',
      why:'決めきれないのは情報が足りないだけで、自然なことです。費用と特徴の比較記事と、無料のガイドブック（PDF）をご用意しています。読んでから考えても、相談しながら決めても、どちらでも大丈夫です。',
      fit:['それぞれの違いを知ってから決めたい方','家族と話すための材料がほしい方'],
      care:'ご相談は無料で、こちらから営業のお電話をかけることはありません。',
      main:[], rel:['kaiyou-sou','grave','temoto-kuyou','teien-sou'],
      guide:true}
  };

  /* ---------- 状態 ---------- */
  var quiz = document.getElementById('sd-quiz');
  var history = ['start'];
  var ans = {flags:{}, region:'', pending:null};
  var started = false;
  var EST = 5; // 想定質問数（進捗表示用）

  function esc(s){return String(s).replace(/[&<>"]/g,function(c){return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;'}[c];});}

  function progress(){
    var d = history.length;
    var pct = Math.min(90, Math.round(d/EST*100));
    return '<div class="sd-progress"><b style="width:'+pct+'%"></b></div><p class="sd-progress-txt">質問 '+d+' ／ だいたい'+EST+'問で終わります</p>';
  }

  function renderNode(key){
    var node=TREE[key];
    var h=progress();
    h+='<div class="sd-q">'+esc(node.q)+'</div>';
    if(node.sub){h+='<div class="sd-qsub">'+esc(node.sub)+'</div>';}
    h+='<div class="sd-options">';
    node.opts.forEach(function(o,idx){
      h+='<button class="sd-opt" data-idx="'+idx+'"><b>'+esc(o.b)+'</b>'+(o.s?'<span>'+esc(o.s)+'</span>':'')+'</button>';
    });
    h+='</div>';
    if(history.length>1){h+='<button class="sd-back" data-back="1">← 前の質問にもどる</button>';}
    quiz.innerHTML=h;
    quiz.querySelectorAll('.sd-opt').forEach(function(btn){
      btn.addEventListener('click',function(){
        var o=node.opts[+btn.getAttribute('data-idx')];
        if(!started){started=true;ga('shindan_start');}
        ga('shindan_answer',{sd_step:key, sd_choice:o.b});
        if(o.flag){ans.flags[o.flag]=true;}
        if(key==='region'){ans.region=o.rg||''; renderResult(ans.pending); scrollCard(); return;}
        if(o.region&&o.res){ans.pending=o.res; history.push('region'); renderNode('region');}
        else if(o.next){history.push(o.next);renderNode(o.next);}
        else if(o.res){renderResult(o.res);}
        scrollCard();
      });
    });
    var back=quiz.querySelector('[data-back]');
    if(back){back.addEventListener('click',function(){history.pop();renderNode(history[history.length-1]);scrollCard();});}
  }

  /* 散骨系の結果は回答フラグから組み立てる */
  function buildKaiyou(){
    var partial = ans.flags.partial||ans.flags.mayoi||ans.flags.family;
    var r={
      name: partial?'海洋散骨 ＋ 一部を手元供養':'海洋散骨（海洋葬）',
      fit:[], main:['kaiyou-sou'], rel:['powder-cleaning'],
      care:'散骨したご遺骨は、あとから取り戻せません。残す量は粉骨のときに決められます。'
    };
    if(ans.flags.josen){
      r.why='ご家族で乗船して見送るなら、チャーター海洋葬（貸切・176,000円〜）か合同海洋葬（乗り合わせ・148,500円〜）が候補です。';
      r.fit.push('自分たちの手でお見送りしたい方');
    }else{
      r.why='費用を抑えるなら、スタッフが代わりに散骨する委託海洋葬（54,450円〜・期間限定価格）が候補です。散骨海域の緯度・経度入りの証明書と当日の写真をお届けします。';
      r.fit.push('費用を抑えたい方、遠方の方（ご遺骨は郵送でお預かりできます）');
    }
    if(ans.flags.mayoi){
      r.why+=' そして「全部撒くか迷っている」なら、ひとまず少量を手元に残しておくのがおすすめです。残した分をあとから散骨することはいつでもできます。';
      r.main.push('temoto-kuyou'); r.rel.push('jewelry-reform');
      r.fit.push('全部撒いてしまうことに、ためらいがある方');
    }else if(ans.flags.family){
      r.why+=' ご家族で意見が分かれている場合は、「大部分を散骨し、残したい方がそれぞれ少しずつ持つ」形で両方の気持ちを立てられます（お一人はミニ骨壷、お一人はペンダント、など）。';
      r.main.push('temoto-kuyou'); r.rel.push('jewelry-reform');
      r.fit.push('家族の合意を作りたい方');
    }else if(ans.flags.partial){
      r.why+=' 一部はミニ骨壷やペンダントに納めてお手元に。分骨は粉骨の際にお分けするだけなので、特別な手続きは不要です。';
      r.main.push('temoto-kuyou'); r.rel.push('jewelry-reform');
      r.fit.push('手を合わせる場所も残したい方');
    }
    return r;
  }

  function regionNote(){
    if(ans.region==='鹿児島') return '鹿児島本社（にしだ病院近く）で、実物を見ながらご相談いただけます。';
    if(ans.region==='福岡・九州') return '福岡営業所でもご相談いただけます。散骨は博多湾の海域にも対応しています。';
    if(ans.region==='その他') return 'ご遺骨は日本郵便のゆうパックで全国からお送りいただけます。お打ち合わせはお電話・LINE・メールで完結できます。';
    return '';
  }

  function renderResult(key){
    var r = RES[key] && RES[key].dynamic ? buildKaiyou() : RES[key];
    if(!r){ r = RES['consult']; key='consult'; }
    ga('shindan_result',{sd_result:key, sd_region:ans.region||'(未回答)'});
    var sdSummary = r.name + (ans.region ? '／地域：' + ans.region : '');
    var mainSvc = r.main && r.main.length ? SV[r.main[0]] : null;
    var sdCat = mainSvc && mainSvc.cat ? mainSvc.cat : 'その他';
    var contactUrl='/contact/?service='+encodeURIComponent(sdCat)+'&shindan='+encodeURIComponent(sdSummary)+'&from=shindan';

    var h='<span class="sd-result-label">今の状況なら、まずこの選択肢から</span>';
    h+='<h2 class="sd-result-name">'+esc(r.name)+'</h2>';
    h+='<div class="sd-block"><h4>あなたの回答から</h4><p>'+esc(r.why)+'</p>'+(regionNote()?'<p style="margin-top:6px">'+esc(regionNote())+'</p>':'')+'</div>';
    if(r.fit&&r.fit.length){h+='<div class="sd-block"><h4>こんな方に向いています</h4><ul>'+r.fit.map(function(f){return '<li>'+esc(f)+'</li>';}).join('')+'</ul></div>';}
    if(r.care){h+='<div class="sd-block"><h4>知っておきたいこと</h4><p>'+esc(r.care)+'</p></div>';}
    if(r.main&&r.main.length){
      h+='<div class="sd-svc">';
      r.main.forEach(function(k){var v=SV[k]; if(v){h+='<a href="'+v.href+'" data-cta="svc:'+k+'">'+esc(v.name)+'を見る<small>'+esc(v.s)+'</small></a>';}});
      h+='</div>';
    }
    if(r.rel&&r.rel.length){
      h+='<div class="sd-alt"><h4>あわせて検討される方が多いサービス</h4><div class="sd-alt-list">';
      r.rel.forEach(function(k){var v=SV[k]; if(v&&(!r.main||r.main.indexOf(k)<0)){h+='<a href="'+v.href+'" data-cta="rel:'+k+'">'+esc(v.name)+'</a>';}});
      h+='</div></div>';
    }
    h+='<p class="sd-reassure">診断は目安です。「これで合っているのかな」と思われたら、その疑問ごとお聞かせください。ご事情に合わせて一緒に整理します。宗教・宗派は問いません。</p>';
    h+='<div class="sd-contact"><h3>この結果をもとに、無料で相談できます</h3><p>ご相談・お見積りは無料。こちらから営業のお電話をかけることはありません。</p><div class="btns">';
    h+='<a href="'+contactUrl+'" class="btn" style="background:#fff;color:var(--green-mid)" data-cta="form">相談フォーム</a>';
    h+='<a href="'+SITE_LINE+'" target="_blank" rel="noopener" class="btn" style="background:#06C755" data-cta="line">LINEで相談</a>';
    h+='<a href="/contact/?service='+encodeURIComponent('資料請求（無料）')+'&shindan='+encodeURIComponent(sdSummary)+'&from=shindan" class="btn" style="background:rgba(255,255,255,.16);border:1px solid rgba(255,255,255,.6)" data-cta="shiryou">無料ガイドをもらう</a>';
    h+='</div><a href="tel:'+SITE_TEL+'" class="sd-tel" data-cta="tel">'+SITE_TEL+'</a></div>';
    h+='<div style="text-align:center"><button class="sd-back" data-restart="1">↺ もう一度診断する</button></div>';
    quiz.innerHTML=h;
    quiz.querySelectorAll('[data-cta]').forEach(function(a){
      a.addEventListener('click',function(){ga('shindan_cta',{sd_result:key, sd_cta:a.getAttribute('data-cta')});});
    });
    var rs=quiz.querySelector('[data-restart]');
    if(rs){rs.addEventListener('click',function(){history=['start'];ans={flags:{},region:'',pending:null};renderNode('start');scrollCard();});}
  }

  function scrollCard(){var c=document.querySelector('.shindan-card');if(c){var y=c.getBoundingClientRect().top+window.pageYOffset-90;window.scrollTo({top:y,behavior:'smooth'});}}

  renderNode('start');
})();
</script>

<script type="application/ld+json">
{
  "@context":"https://schema.org",
  "@type":"FAQPage",
  "mainEntity":[
    {"@type":"Question","name":"遺骨をすべて散骨するか迷っています。どう考えればいいですか？","acceptedAnswer":{"@type":"Answer","text":"海洋散骨は、ご遺骨のすべてを撒く必要はありません。大部分を海へ還し、ひとつまみをミニ骨壷やペンダントで手元に残す「分骨」ができます。散骨したご遺骨は戻りませんが、残した分をあとから散骨することはいつでもできるため、迷ったら少量を残しておくのがおすすめです。"}},
    {"@type":"Question","name":"墓じまいの後、遺骨の行き先が決まっていなくても相談できますか？","acceptedAnswer":{"@type":"Answer","text":"できます。取り出したご遺骨は海洋散骨・樹木葬・永代供養墓・手元供養などから選べます。有限会社縁は撤去から粉骨・散骨・納骨まで一括対応のため、先にお墓じまいを進めながら、行き先を落ち着いて決めることができます。"}},
    {"@type":"Question","name":"供養方法ごとの費用の目安を教えてください。","acceptedAnswer":{"@type":"Answer","text":"有限会社縁の税込目安は、委託海洋散骨54,450円〜、粉骨24,200円〜、洗骨27,500円〜、お墓じまい基本プラン33万円です。手元供養はミニ骨壷など品物により異なり、お持ち込み品への分骨は5,500円です。お見積りは無料で、お見積り後の追加料金はありません。"}}
  ]
}
</script>
<?php require __DIR__ . '/../includes/footer.php'; ?>
