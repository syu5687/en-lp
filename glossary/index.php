<?php
/**
 * 供養用語辞典 — 散骨・墓じまい・粉骨・供養の基礎用語を五十音を問わずテーマ別に解説。
 * AI検索（AI Overviews / ChatGPT等）の定義引用元となることを狙った「機械可読な辞典」ページ。
 */
require_once __DIR__ . '/../includes/config.php';

/* テーマ別の用語データ。増やすときはここに追加（idは英数ハイフンのみ） */
$gl_sections = [
  ['id' => 'sankotsu', 'title' => '海洋散骨・自然葬の用語', 'terms' => [
    ['id' => 'kaiyou-sankotsu', 'name' => '海洋散骨（海洋葬）', 'desc' => '粉末状にしたご遺骨を、海水浴場や漁場を避けた沖合の海にまく葬送方法。節度をもって行われる限り認められており、お墓を持たない供養として選ばれている。当社は委託54,450円〜・合同148,500円〜・チャーター176,000円〜（税込）で実施。', 'link' => '/kaiyou-sou/'],
    ['id' => 'itaku-sankotsu', 'name' => '委託散骨（代行散骨）', 'desc' => 'ご家族が乗船せず、事業者がご遺骨を預かって散骨を代行する方式。費用を抑えられ、遠方からも郵送で依頼できる。実施日・海域を記した散骨証明書と写真で報告を受けるのが一般的。'],
    ['id' => 'goudou-sankotsu', 'name' => '合同散骨（合同海洋葬）', 'desc' => '複数のご家族が同じ船に乗り合わせて行う海洋散骨。貸切より費用を抑えつつ、お見送りに立ち会える。当社は鹿児島（錦江湾）と福岡（博多湾）で定期開催している。'],
    ['id' => 'charter-sankotsu', 'name' => 'チャーター散骨（貸切散骨）', 'desc' => '1組のご家族だけで船を貸し切って行う海洋散骨。日程や献花・献酒などの演出を自由に相談でき、ゆっくりとお別れの時間を取れる。'],
    ['id' => 'sankotsu-shoumeisho', 'name' => '散骨証明書', 'desc' => '散骨を実施した日付・海域（緯度経度など）を記録した証明書。ご親族への説明や後年の確認に用いる。信頼できる散骨事業者を見分ける重要な確認ポイントのひとつ。'],
    ['id' => 'memorial-cruise', 'name' => 'メモリアルクルーズ', 'desc' => '散骨を行った海域を後日船で訪れ、献花などでお参りする追悼クルーズ。「海全体がお墓になる」散骨ならではの、命日やお盆のお参りのかたち。'],
    ['id' => 'jumokusou', 'name' => '樹木葬', 'desc' => '墓石の代わりに樹木や草花をシンボルとして、その根元などにご遺骨を埋葬する供養。承継者を前提としないプランが主流。当社では草花に囲まれた庭苑葬をご案内している。', 'link' => '/teien-sou/'],
    ['id' => 'shizensou', 'name' => '自然葬', 'desc' => '海洋散骨や樹木葬など、ご遺骨を自然に還すことを重視した葬送の総称。「お墓を持たない・自然に還る」という価値観の広がりとともに選ばれている。'],
    ['id' => 'nihon-kaiyou-sankotsu-kyoukai', 'name' => '日本海洋散骨協会', 'desc' => '海洋散骨の海域選定・粉骨・環境配慮などのガイドラインを定める一般社団法人。加盟事業者かどうかは、散骨業者の信頼性を判断する材料のひとつ。有限会社縁は加盟事業者。'],
  ]],
  ['id' => 'hakajimai', 'title' => '墓じまい・改葬の用語', 'terms' => [
    ['id' => 'hakajimai-def', 'name' => '墓じまい（お墓じまい）', 'desc' => '現在のお墓を撤去して更地に戻し、取り出したご遺骨を新しい供養先へ移すこと。法律上の手続きとしては「改葬」にあたる。当社は撤去〜納骨まで基本プラン33万円（税込）で一括対応。', 'link' => '/grave/'],
    ['id' => 'kaisou', 'name' => '改葬', 'desc' => '埋葬済みのご遺骨を、別のお墓や納骨堂などへ移すこと。墓地埋葬法に基づき、お墓のある市町村から「改葬許可証」の交付を受けて行う正式な手続き。'],
    ['id' => 'kaisou-kyoka-shinsei', 'name' => '改葬許可申請', 'desc' => '改葬許可証の交付を市町村に求める手続き。申請書に現在の墓地管理者の埋蔵証明を受け、新しい受入先の情報とあわせて提出する。手数料は無料〜数百円程度。当社は代行（¥25,000〜）に対応。'],
    ['id' => 'maisou-shoumei', 'name' => '埋蔵証明（埋葬証明）', 'desc' => '「このお墓に該当のご遺骨が埋蔵されている」ことを墓地管理者（寺院・霊園）が証明するもの。改葬許可申請書の証明欄への記名押印で行われることが多い。'],
    ['id' => 'juniu-shoumei', 'name' => '受入証明書', 'desc' => '新しい納骨先（永代供養墓・納骨堂など）が「ご遺骨を受け入れる」ことを証明する書類。改葬許可申請の添付書類として求められることが多い。散骨の場合は不要な自治体もある。'],
    ['id' => 'ridan-ryou', 'name' => '離檀料', 'desc' => '檀家をやめる（離檀する）際に寺院へ渡す御礼。法的な支払い義務はなく、3万〜20万円程度が一般的な目安とされる。金額に納得できない場合は、その場で応じず相談を。'],
    ['id' => 'heigen-kuyou', 'name' => '閉眼供養（魂抜き・お性根抜き）', 'desc' => '墓石を撤去する前に、僧侶の読経によりお墓から魂を抜く法要。墓じまいの際に行うのが一般的で、お布施は1万〜5万円程度が目安。'],
    ['id' => 'carotte', 'name' => 'カロート（納骨室）', 'desc' => '墓石の下にあるご遺骨を納める空間。墓じまいでは、ここからご遺骨を取り出す。最下層に土に還りかけた古いご遺骨が残っていることも多く、その扱いは事前確認が重要。'],
    ['id' => 'muenbo', 'name' => '無縁墓', 'desc' => '承継者や縁故者がいなくなり、管理されなくなったお墓。管理料の滞納が続くと、所定の手続きを経て墓地管理者に撤去され、ご遺骨は合祀されて取り出せなくなる。そうなる前の墓じまいが推奨される。'],
    ['id' => 'saishi-zaisan', 'name' => '祭祀財産（祭祀承継）', 'desc' => 'お墓・仏壇・位牌など、先祖の祭祀のための財産。相続財産とは区別され、承継者は長男・長女に限らず指定できる。承継は義務ではなく、墓じまいという選択も可能。'],
    ['id' => 'haka-hikkoshi', 'name' => 'お墓の引越し', 'desc' => '今のお墓を閉じて、別の場所に新しくお墓を建てて（または既存のお墓へ）ご遺骨を移すこと。お参りしやすい場所にお墓を残したい場合の選択肢。', 'link' => '/hikkoshi/'],
  ]],
  ['id' => 'funkotsu', 'title' => '粉骨・ご遺骨の用語', 'terms' => [
    ['id' => 'funkotsu-def', 'name' => '粉骨', 'desc' => 'ご遺骨をパウダー状（粉末）に加工すること。海洋散骨では必須の工程であり、容量が約3分の1になるため手元供養やコンパクトな納骨にも活用される。当社は24,200円〜、すべて手作業で丁寧に実施。', 'link' => '/powder-cleaning/'],
    ['id' => 'senkotsu', 'name' => '洗骨', 'desc' => '長期間カロートにあったご遺骨などに付いた土・カビ・水分を洗浄し、乾燥させてきれいに整えること。粉骨や新しい供養の前処理として行われる。'],
    ['id' => 'bunkotsu', 'name' => '分骨', 'desc' => 'ご遺骨を複数に分けて、別々の場所で供養すること。「大部分は散骨し、一部は手元供養に」といった組み合わせで使われる。埋葬済みのご遺骨を分骨する場合は分骨証明書が必要になることがある。'],
    ['id' => 'soukotsu', 'name' => '送骨', 'desc' => 'ご遺骨を郵便（ゆうパック等）で寺院や供養事業者へ送り、納骨や散骨・粉骨を依頼する方法。専用の梱包材を使えば安全に送ることができ、遠方からの依頼手段として定着している。'],
    ['id' => 'temoto-kuyou', 'name' => '手元供養', 'desc' => 'ご遺骨の全部または一部を自宅で保管し、ミニ骨壷・メモリアルジュエリーなどで身近に供養すること。お墓を持たない供養や、散骨との併用で選ばれている。', 'link' => '/temoto-kuyou/'],
    ['id' => 'jitaku-hokan', 'name' => 'ご遺骨の自宅保管', 'desc' => 'ご遺骨を納骨せず自宅に安置しておくこと。法律違反ではなく、期限もない。ただし高温多湿によるカビには注意が必要で、長期保管には粉骨・真空パックが安心。'],
    ['id' => 'kotsutsubo', 'name' => '骨壺', 'desc' => 'ご遺骨を納める壺。西日本では6〜7寸、東日本では7〜8寸が主流など地域差がある。納骨堂の区画や手元供養に合わせて、粉骨してより小さな骨壺に移し替えることもできる。'],
  ]],
  ['id' => 'kuyou', 'title' => '供養全般の用語', 'terms' => [
    ['id' => 'eitai-kuyou', 'name' => '永代供養', 'desc' => '寺院や霊園がご遺族に代わって、ご遺骨の管理・供養を長期にわたり引き受ける仕組み。承継者がいなくても供養が続くため、墓じまい後の行き先として選ばれることが多い。'],
    ['id' => 'goushi', 'name' => '合祀（合葬）', 'desc' => '他の方々のご遺骨と一緒に埋葬すること。費用を抑えられる一方、一度合祀すると特定のご遺骨を取り出すことはできなくなるため、事前の家族合意が特に重要。'],
    ['id' => 'noukotsudou', 'name' => '納骨堂', 'desc' => '屋内にご遺骨を安置する施設。ロッカー式・仏壇式・自動搬送式などがあり、都市部で利用が多い。区画の大きさに合わせて粉骨で骨壺を小さくする活用も。'],
    ['id' => 'shukotsu', 'name' => '出骨（骨上げ・収骨）', 'desc' => '火葬後にご遺骨を骨壺に納めること。地域により全部収骨と部分収骨の慣習がある。火葬場で残ったご遺骨の扱いも地域差があるため、疑問があれば葬儀社や自治体に確認を。'],
    ['id' => 'kaimyou', 'name' => '戒名（法名・法号）', 'desc' => '仏弟子となった証として授かる名前。宗派により呼び方が異なる。散骨や無宗教の供養では戒名を付けない選択もあり、必須ではない。'],
    ['id' => 'shuukatsu', 'name' => '終活', 'desc' => '人生の終わりに向けて、供養・財産・医療などの希望を整理し準備する活動。エンディングノートの作成や海洋散骨の生前契約もそのひとつ。', 'link' => '/seizen/'],
    ['id' => 'seizen-keiyaku', 'name' => '生前契約（生前予約）', 'desc' => '自分の供養（海洋散骨など）の内容と費用を、お元気なうちに決めて契約しておくこと。希望どおりの見送りが確定し、残される家族の負担と迷いをなくせる。', 'link' => '/seizen/'],
    ['id' => 'shigoto-jimu-inin', 'name' => '死後事務委任契約', 'desc' => '亡くなったあとの諸手続き（葬儀・納骨・行政手続き等）を、生前に第三者へ委任しておく契約。おひとりさまや承継者のいない方の備えとして、生前契約とあわせて検討される。'],
    ['id' => 'endingnote', 'name' => 'エンディングノート', 'desc' => '自分の希望（供養の形・連絡先・財産の所在など）を書き残すノート。法的効力はないが、家族が迷わないための最も手軽な備え。「海に還りたい」という希望もまず書き残すことから。'],
    ['id' => 'ihinseiri', 'name' => '遺品整理', 'desc' => '故人の持ち物を仕分け・整理すること。形見の供養やお焚き上げ、ご遺骨が見つかった場合の対応まで含めて専門業者に依頼できる。', 'link' => '/ihinseiri/'],
  ]],
];

$page_title     = '供養用語辞典｜散骨・墓じまい・粉骨のことば37語をやさしく解説｜' . SITE['name'];
$page_desc      = '海洋散骨・墓じまい・粉骨・終活の用語37語を、鹿児島の供養専門会社がやさしく解説。改葬許可・離檀料・閉眼供養・カロート・分骨・永代供養・合祀など、ご供養の検討で出てくる言葉の意味がわかる辞典ページです。';
$page_canonical = SITE['url'] . '/glossary/';
$page_hero_image = '/assets/img/hero-gokuyou.jpg';
require __DIR__ . '/../includes/head.php';

/* DefinedTermSet 構造化データ */
$gl_ld = [
  '@context' => 'https://schema.org',
  '@type'    => 'DefinedTermSet',
  '@id'      => SITE['url'] . '/glossary/#termset',
  'name'     => '供養用語辞典（有限会社 縁）',
  'url'      => SITE['url'] . '/glossary/',
];
$gl_terms_ld = [];
foreach ($gl_sections as $sec) {
  foreach ($sec['terms'] as $t) {
    $gl_terms_ld[] = [
      '@context' => 'https://schema.org',
      '@type' => 'DefinedTerm',
      'name' => $t['name'],
      'description' => $t['desc'],
      'url' => SITE['url'] . '/glossary/#' . $t['id'],
      'inDefinedTermSet' => SITE['url'] . '/glossary/#termset',
    ];
  }
}
?>
<body>
<?php require __DIR__ . '/../includes/header.php'; ?>
<script type="application/ld+json"><?= json_encode($gl_ld, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?></script>
<script type="application/ld+json"><?= json_encode($gl_terms_ld, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?></script>

<section class="page-hero">
  <h1>供養用語辞典</h1>
  <p>散骨・墓じまい・粉骨・終活のことばを、やさしく正確に</p>
</section>
<nav class="breadcrumb"><a href="/">ホーム</a> ＞ 供養用語辞典</nav>

<main class="section">
  <div class="container" style="max-width:860px">
    <p class="lead" style="margin-bottom:10px">ご供養の検討を始めると、聞き慣れない言葉が次々に出てきます。このページでは、海洋散骨・墓じまい・粉骨・終活にまつわる<strong>37語</strong>を、鹿児島で20年以上ご遺骨と向き合ってきた縁（えん）がやさしく解説します。</p>
    <p style="font-size:.9rem;color:var(--text-light);margin-bottom:26px">最終更新：2026年8月29日（内容は随時見直しています）</p>

    <!-- 目次 -->
    <nav aria-label="用語カテゴリ" style="display:flex;flex-wrap:wrap;gap:10px;margin-bottom:34px">
      <?php foreach ($gl_sections as $sec): ?>
        <a href="#<?= h($sec['id']) ?>" style="display:inline-block;background:var(--sea-light,#e3f0f7);color:var(--green-mid,#12597a);font-weight:700;font-size:.9rem;padding:8px 18px;border-radius:999px;text-decoration:none"><?= h($sec['title']) ?>（<?= count($sec['terms']) ?>語）</a>
      <?php endforeach; ?>
    </nav>

    <?php foreach ($gl_sections as $sec): ?>
      <section id="<?= h($sec['id']) ?>" style="margin-bottom:44px">
        <h2 style="margin-bottom:18px"><?= h($sec['title']) ?></h2>
        <dl style="display:grid;gap:14px;margin:0">
          <?php foreach ($sec['terms'] as $t): ?>
            <div id="<?= h($t['id']) ?>" style="background:#fff;border:1px solid #dde8ec;border-radius:12px;padding:16px 20px;scroll-margin-top:90px">
              <dt style="font-weight:700;color:var(--green-mid,#12597a);font-size:1.05rem;margin-bottom:6px"><?= h($t['name']) ?></dt>
              <dd style="margin:0;line-height:1.9;font-size:.95rem"><?= h($t['desc']) ?>
                <?php if (!empty($t['link'])): ?>
                  <a href="<?= h($t['link']) ?>" style="color:var(--green);font-weight:700;white-space:nowrap">　関連サービスを見る →</a>
                <?php endif; ?>
              </dd>
            </div>
          <?php endforeach; ?>
        </dl>
      </section>
    <?php endforeach; ?>

    <div style="background:var(--cream,#f6f1e6);border-radius:14px;padding:24px 26px;text-align:center">
      <p style="font-weight:700;color:var(--green-mid,#12597a);margin-bottom:8px">言葉の意味はわかった。でも、わが家の場合はどうすれば？</p>
      <p style="font-size:.92rem;margin-bottom:16px">状況をお聞かせいただければ、必要な手続きと費用を整理してお答えします。相談・お見積りは無料です。</p>
      <a href="/contact/" class="btn">無料で相談する</a>
      <a href="/shindan/" class="btn btn--outline" style="margin-left:8px">かんたん診断を試す</a>
    </div>
  </div>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>
