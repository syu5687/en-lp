<?php
/**
 * 墓じまい後の散骨（/grave/sankotsu/）
 *
 * 対策クエリ（GSC・直近3ヶ月／いずれもクリック0・平均順位40〜75位）:
 *   散骨 墓じまい 業者(78) / 散骨 墓じまい(70) / 散骨 墓じまい 手続き 流れ(68)
 *   散骨 墓じまい 業者 メリット(66) / 散骨 墓じまい 業者 依頼(63)
 *   散骨 墓じまい 手続き(52) / 散骨 墓じまい 業者 契約(48)
 *
 * 役割：/grave/（墓じまい）と /kaiyou-sou/（海洋散骨）の間にあり、
 *       どちらのページでも受けきれていない「墓じまい → 散骨」の一連の流れを扱う。
 *       柱ページ（/grave/・/kaiyou-sou/）と双方向にリンクする。
 */
require_once __DIR__ . '/../../includes/config.php';

$page_title     = '墓じまい後の散骨｜手続きの流れと業者の選び方・費用｜縁（えん）';
$page_desc      = '墓じまいで取り出したご遺骨を散骨するまでの流れを、手続き・費用・期間の順に解説します。改葬許可の要否、洗骨と粉骨が必要な理由、業者を選ぶときの確認点まで。撤去から散骨までを一社で行う有限会社 縁（鹿児島・福岡）の実際の金額（墓じまい33万円〜・粉骨24,200円〜・委託海洋葬54,450円〜／すべて税込）も公開しています。';
$page_canonical = SITE['url'] . '/grave/sankotsu/';
$page_hero_image = '/assets/img/hero-grave.jpg';
require __DIR__ . '/../../includes/head.php';
?>
<body>
<?php require __DIR__ . '/../../includes/header.php'; ?>

<section class="page-hero">
  <h1>墓じまいをして、ご遺骨を散骨するまで</h1>
  <p>手続き・費用・期間と、業者を選ぶときに確認したいこと</p>
</section>
<nav class="breadcrumb"><a href="/">ホーム</a> ＞ <a href="/grave/">お墓じまい</a> ＞ 墓じまい後の散骨</nav>

<main>
  <!-- 結論（冒頭に置く：AI検索の要約に使われるため） -->
  <section class="section">
    <div class="container" style="max-width:820px">
      <div class="prose" style="max-width:780px;margin:0 auto">
        <p class="lead"><strong>墓じまいをして散骨する場合、必要な手続きは「改葬許可申請」ではなく「改葬（除籍）の届出」で足りることが多く、ご遺骨は洗骨・乾燥のうえ2mm以下に粉骨してから海へお還しします。</strong>期間はご相談から3〜4ヶ月、費用は墓石の撤去から散骨まで一括で <strong>おおよそ38万〜45万円</strong>（当社の場合：墓じまい基本プラン33万円＋粉骨24,200円〜＋委託海洋葬54,450円〜／すべて税込）が目安です。撤去と散骨を別の業者に頼むと、ご遺骨の受け渡しと日程調整をご遺族が担うことになります。</p>
      </div>
    </div>
  </section>

  <!-- なぜ「墓じまい＋散骨」が増えているのか -->
  <section class="section" style="background:var(--white)">
    <div class="container" style="max-width:820px">
      <h2>なぜ、墓じまいと散骨をセットで考える方が増えているのか</h2>
      <div class="prose" style="margin-top:18px">
        <p>墓じまいは「お墓を閉じること」ですが、閉じただけでは終わりません。<strong>取り出したご遺骨を、どこかへ移す必要があります。</strong>ここで多くの方が立ち止まります。</p>
        <p>新しい納骨先（永代供養墓・納骨堂・樹木葬）を選べば、また管理と費用が続きます。継ぐ人がいないからお墓を閉じたのに、同じ問題を先送りしているだけではないか——そう感じて散骨にたどり着く方が多いのが実情です。</p>
        <p>散骨であれば、その後の管理も費用も発生しません。ご遺骨の一部だけを手元に残せば、手を合わせる場所も残せます。<strong>「全部を海に還さなければならない」わけではない</strong>ことは、あまり知られていません。</p>
      </div>
    </div>
  </section>

  <!-- 手続き（クエリ：散骨 墓じまい 手続き / 手続き 流れ） -->
  <section class="section">
    <div class="container" style="max-width:860px">
      <h2 style="text-align:center;margin-bottom:8px">必要な手続きは何か</h2>
      <p style="text-align:center;color:var(--text-light);font-size:.92rem;margin-bottom:26px">散骨は「改葬」とは扱いが異なるため、必要な書類も変わります。</p>
      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:16px">
        <div class="card">
          <h3 style="color:var(--green-mid);font-size:1rem;margin-bottom:8px">散骨する場合</h3>
          <p style="font-size:.9rem;line-height:1.9">改葬許可証は、ご遺骨を「別の墓地・納骨堂へ移す」ときに必要な書類です。散骨は墓地に納めないため、多くの自治体では<strong>改葬許可申請ではなく、墓地の管理者への届出と、お墓のあった市区町村での手続き</strong>で足ります。ただし取り扱いは自治体ごとに異なり、散骨でも改葬許可を求める窓口もあります。当社が窓口に確認してご案内します。</p>
        </div>
        <div class="card">
          <h3 style="color:var(--green-mid);font-size:1rem;margin-bottom:8px">一部を納骨先へ移す場合</h3>
          <p style="font-size:.9rem;line-height:1.9">「大部分を散骨し、一部を永代供養墓へ」という組み合わせでは、納骨する分について<strong>改葬許可証が必要</strong>です。申請先は「今のお墓がある市区町村」で、受入証明書と埋蔵証明書を添えて申請します。書類の取り寄せから記入のご案内まで、オプション（25,000円〜）で代行できます。</p>
        </div>
        <div class="card">
          <h3 style="color:var(--green-mid);font-size:1rem;margin-bottom:8px">お寺の墓地の場合</h3>
          <p style="font-size:.9rem;line-height:1.9">埋蔵証明はお寺に記入していただく必要があるため、閉眼供養と離檀のご相談が先になります。離檀料に法的な支払い義務はなく、御礼として3万〜20万円程度が目安です。伝え方から一緒に考えます。</p>
        </div>
      </div>
      <p style="text-align:center;margin-top:18px;font-size:.9rem"><a href="/grave/" style="color:var(--green);font-weight:600">墓じまいの費用と工事の詳細はこちら →</a></p>
    </div>
  </section>

  <!-- 流れ（クエリ：手続き 流れ） -->
  <section class="section" style="background:var(--white)">
    <div class="container" style="max-width:760px">
      <h2 style="text-align:center;margin-bottom:8px">墓じまいから散骨までの流れ</h2>
      <p style="text-align:center;color:var(--text-light);font-size:.92rem;margin-bottom:24px">ご相談から散骨証明書のお渡しまで、3〜4ヶ月が目安です。</p>
      <style>
        .gs-flow{list-style:none;display:grid;gap:12px;padding:0;margin:0}
        .gs-flow li{display:grid;grid-template-columns:40px 1fr auto;gap:14px;background:#fff;border:1px solid var(--border);border-radius:12px;padding:14px 18px;align-items:center}
        .gs-flow__num{width:36px;height:36px;border-radius:50%;background:var(--green);color:#fff;display:grid;place-items:center;font-weight:700}
        .gs-flow__span{font-size:.78rem;color:var(--text-light);white-space:nowrap;align-self:start;padding-top:4px}
        @media(max-width:640px){
          /* SPでは見出しと所要期間が同じ行に並ぶと見出しが折り返して読みにくいため、期間を下段へ回す */
          .gs-flow li{grid-template-columns:36px 1fr;align-items:start}
          .gs-flow__span{grid-column:2;padding-top:0;margin-top:2px}
        }
      </style>
      <ol class="gs-flow">
        <?php foreach ([
          ['無料相談・現地調査', 'お墓の場所と大きさ、ご遺骨の数を確認し、総額を確定してご提示します。県外にお住まいでも、写真とお電話・LINEで進められます。', '2週間'],
          ['書類の準備', '墓地管理者への届出、必要な場合の改葬許可申請、受入証明の取得。代行をご希望の場合はお任せいただけます。', '2〜4週間'],
          ['閉眼供養（ご希望の場合）', '僧侶による魂抜き。お寺のご紹介も可能です。お布施の目安は1万〜5万円程度です。', '1日'],
          ['墓石の撤去とご遺骨の取り出し', '工事の前後を写真でご報告し、区画を更地に戻して管理者へお返しします。立ち会いは不要です。', '1〜2週間'],
          ['洗骨・乾燥・粉骨', '長く土中にあったご遺骨は湿気を含み、土や根が混じっていることもあります。洗浄・乾燥のうえ2mm以下にパウダー化し、六価クロムの検査と無害化も行います。', '2〜3週間'],
          ['散骨・証明書のお渡し', '錦江湾（鹿児島）または博多湾（福岡）で散骨し、緯度・経度入りの散骨証明書と当日の写真をお届けします。', '天候により調整'],
        ] as $i => [$t, $d, $span]): ?>
        <li>
          <span class="gs-flow__num"><?= $i + 1 ?></span>
          <span><strong style="color:var(--green-mid)"><?= h($t) ?></strong><br><span style="font-size:.88rem;color:var(--text-light);line-height:1.85"><?= h($d) ?></span></span>
          <span class="gs-flow__span">目安 <?= h($span) ?></span>
        </li>
        <?php endforeach; ?>
      </ol>
    </div>
  </section>

  <!-- 費用（一次情報） -->
  <section class="section">
    <div class="container" style="max-width:820px">
      <h2 style="text-align:center;margin-bottom:8px">費用の内訳</h2>
      <p style="text-align:center;color:var(--text-light);font-size:.92rem;margin-bottom:24px">当社の実際の金額です。すべて税込で、確定後の追加請求はありません。</p>
      <div style="overflow-x:auto">
        <table style="width:100%;border-collapse:collapse;background:#fff;font-size:.92rem;border:1px solid var(--border);border-radius:12px;overflow:hidden">
          <thead>
            <tr><th style="background:var(--green-mid);color:#fff;text-align:left;padding:12px 16px;font-weight:600">項目</th><th style="background:var(--green-mid);color:#fff;text-align:left;padding:12px 16px;font-weight:600">金額（税込）</th><th style="background:var(--green-mid);color:#fff;text-align:left;padding:12px 16px;font-weight:600">内容</th></tr>
          </thead>
          <tbody>
            <?php foreach ([
              ['墓じまい 基本プラン', '330,000円', '見積もり取得・撤去工事・写真報告・ご遺骨の取り出し・納骨先へのご納骨まで'],
              ['粉骨（パウダー化）', '24,200円〜', '洗骨・乾燥・異物除去・六価クロムの検査と無害化を含みます'],
              ['委託海洋葬', '54,450円〜', '立ち会い不要。散骨証明書と当日の写真をお届けします'],
              ['（合同海洋葬に参加する場合）', '148,500円〜', 'ご家族が乗船してお見送りする場合の金額です'],
              ['改葬許可申請の代行', '25,000円〜', '一部を納骨先へ移す場合など、必要なときのみのオプションです'],
              ['分骨（手元供養品へ）', '5,500円', 'お持ち込みのミニ骨壷・ペンダントへお分けする場合'],
            ] as [$n, $p, $d]): ?>
            <tr>
              <td style="padding:13px 16px;border-top:1px solid var(--border);font-weight:700;color:var(--green-mid);white-space:nowrap"><?= h($n) ?></td>
              <td style="padding:13px 16px;border-top:1px solid var(--border);white-space:nowrap;font-weight:700"><?= h($p) ?></td>
              <td style="padding:13px 16px;border-top:1px solid var(--border);color:var(--text-light);font-size:.88rem;line-height:1.8"><?= h($d) ?></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <p style="text-align:center;margin-top:16px;font-size:.9rem;color:var(--text-light)">立ち会い不要の組み合わせでおおよそ<strong>38万〜45万円</strong>が目安です。墓石が大きい場合や重機が入らない区画では工事費が変わりますが、<strong>現地調査の時点で総額に含めてご提示</strong>します。</p>
    </div>
  </section>

  <!-- 業者の選び方（クエリ：業者 / 業者 メリット / 業者 契約） -->
  <section class="section" style="background:var(--cream)">
    <div class="container" style="max-width:860px">
      <h2 style="text-align:center;margin-bottom:8px">業者を選ぶときに確認したいこと</h2>
      <p style="text-align:center;color:var(--text-light);font-size:.92rem;margin-bottom:24px">墓じまいと散骨を別々に頼むか、一社に任せるか。判断の材料になる点をまとめます。</p>
      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:16px">
        <?php foreach ([
          ['ご遺骨の受け渡しを誰がするか', '撤去業者と散骨業者が別だと、取り出したご遺骨をご遺族が受け取り、改めて散骨業者へ送る手間が生まれます。一社で完結する場合は、この受け渡しがそのまま社内でつながります。'],
          ['粉骨の工程が含まれているか', '散骨には2mm以下への粉骨が必須です。撤去だけの見積もりには粉骨が入っていないことが多く、あとから別費用になります。見積書に粉骨と洗骨が入っているかご確認ください。'],
          ['散骨の海域と証明の有無', '「どこで散骨したのか分からない」という声は実際にあります。緯度・経度入りの証明書と当日の写真が出るか、確認しておくと安心です。'],
          ['協会に加盟しているか', '一般社団法人日本海洋散骨協会の加盟事業者は、海域の選定や環境への配慮についてガイドラインの順守が求められます。当社は加盟事業者です。'],
          ['総額がいつ確定するか', '「基本料金」だけを示し、工事の当日に追加請求が発生する例が報告されています。現地調査の段階で総額が確定するか、確定後の追加がないかをご確認ください。'],
          ['ご遺骨を全部散骨しなくてよいか', '手元に少し残したいという希望に応じられるか。当社では粉骨の際にご希望の量をお分けし、ミニ骨壷やペンダントへ納められます。'],
        ] as [$t, $d]): ?>
        <div class="card">
          <h3 style="color:var(--green-mid);font-size:1rem;margin-bottom:8px"><span style="color:var(--green)">✓</span> <?= h($t) ?></h3>
          <p style="font-size:.9rem;line-height:1.9"><?= h($d) ?></p>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- FAQ -->
  <?php
    $gs_faq = [
      ['q' => '墓じまいをして散骨する場合、改葬許可証は必要ですか？',
       'a' => '散骨は墓地・納骨堂に納める行為ではないため、多くの自治体では改葬許可申請ではなく、墓地管理者への届出などで足ります。ただし取り扱いは自治体によって異なり、散骨でも改葬許可を求める窓口もあります。また、ご遺骨の一部を永代供養墓や納骨堂へ移す場合は、その分について改葬許可証が必要です。当社がお墓のある市区町村へ確認し、必要な書類をご案内します。'],
      ['q' => '墓じまいから散骨まで、どのくらいの期間がかかりますか？',
       'a' => 'ご相談から散骨証明書のお渡しまで、3〜4ヶ月が目安です。内訳は、現地調査と見積もりに約2週間、書類の準備に2〜4週間、撤去工事に1〜2週間、洗骨・乾燥・粉骨に2〜3週間、その後の散骨は天候を見て日程を決めます。お彼岸やお盆の前は工事が混み合うため、時期のご希望がある場合は早めにご相談ください。'],
      ['q' => '墓じまいと散骨は、同じ業者に頼んだほうがよいですか？',
       'a' => '必須ではありませんが、別々に頼む場合は、取り出したご遺骨をご遺族が受け取って散骨業者へ送る手間と、両社の日程調整がご遺族の負担になります。また撤去の見積もりに粉骨が含まれていないことが多く、あとから別費用が発生しがちです。当社は撤去・洗骨・粉骨・散骨をすべて自社で行うため、窓口ひとつで完結します。'],
      ['q' => 'お墓から取り出したご遺骨は、そのまま散骨できますか？',
       'a' => 'できません。散骨にはご遺骨を2mm以下のパウダー状にすることが必要です。さらに長く土中にあったご遺骨は湿気を含み、土や根、まれに副葬品が混じっていることもあるため、洗浄・乾燥をしてから粉骨します。当社では六価クロムの検査と無害化も標準で行っています（追加料金はありません）。'],
      ['q' => '費用は全部でいくらかかりますか？',
       'a' => '当社の場合、墓じまい基本プラン330,000円＋粉骨24,200円〜＋委託海洋葬54,450円〜で、おおよそ38万〜45万円（すべて税込）が目安です。墓石の大きさや重機が入るかどうかで工事費は変わりますが、現地調査の段階で総額に含めてご提示し、確定後に追加請求することはありません。'],
      ['q' => '県外に住んでいますが、帰省せずに進められますか？',
       'a' => '進められます。お見積もり・打ち合わせはお電話・LINE・メールで完結し、工事の立ち会いも不要です。工事の前後は写真でご報告し、散骨後は証明書とお写真をご自宅へお届けします。ご遺骨をお手元に残す場合も、郵送でお送りできます。'],
      ['q' => 'ご遺骨を全部散骨しなくても大丈夫ですか？',
       'a' => '大丈夫です。粉骨の際にご希望の量だけお分けし、大部分を散骨、ひとつまみを手のひらサイズのミニ骨壷やペンダントに納める方が多くいらっしゃいます。散骨したご遺骨は取り戻せませんので、迷われている場合は少量を残しておくことをおすすめしています。残した分をあとから散骨することはいつでもできます。お持ち込みの手元供養品への分骨は5,500円（税込）です。'],
    ];
  ?>
  <section class="section" style="background:var(--white)">
    <div class="container" style="max-width:820px">
      <h2 style="text-align:center;margin-bottom:8px">よくあるご質問</h2>
      <p style="text-align:center;font-size:.9rem;color:var(--text-light);margin-bottom:24px">このほかのご質問もお気軽にどうぞ。</p>
      <?php foreach ($gs_faq as $f): ?>
        <details style="background:#fff;border:1px solid var(--border);border-radius:10px;padding:16px 20px;margin-bottom:12px">
          <summary style="font-weight:600;cursor:pointer;color:var(--green-mid)">Q. <?= h($f['q']) ?></summary>
          <p style="margin-top:10px;font-size:.95rem;line-height:1.9">A. <?= h($f['a']) ?></p>
        </details>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- 関連ページ（柱ページと双方向） -->
  <section class="section">
    <div class="container" style="max-width:860px">
      <h2 style="text-align:center;margin-bottom:22px">あわせてご覧ください</h2>
      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:14px">
        <div class="card">
          <h3 style="font-size:.98rem;color:var(--green-mid);margin-bottom:6px">お墓じまい</h3>
          <p style="font-size:.88rem;line-height:1.85">撤去工事の実例、費用が変わる条件、離島対応など、墓じまいそのものの詳細はこちらです。</p>
          <p style="margin-top:10px"><a href="/grave/" style="color:var(--green);font-weight:700;font-size:.9rem">墓じまいのページへ →</a></p>
        </div>
        <div class="card">
          <h3 style="font-size:.98rem;color:var(--green-mid);margin-bottom:6px">海洋散骨（海洋葬）</h3>
          <p style="font-size:.88rem;line-height:1.85">3つのプラン、セレモニーの様子、散骨証明書。散骨そのものについて詳しく知りたい方へ。</p>
          <p style="margin-top:10px"><a href="/kaiyou-sou/" style="color:var(--green);font-weight:700;font-size:.9rem">海洋散骨のページへ →</a></p>
        </div>
        <div class="card">
          <h3 style="font-size:.98rem;color:var(--green-mid);margin-bottom:6px">粉骨・洗骨</h3>
          <p style="font-size:.88rem;line-height:1.85">散骨の前提となる工程です。カビや土が混じったご遺骨の洗浄・乾燥もこちらで行います。</p>
          <p style="margin-top:10px"><a href="/powder-cleaning/" style="color:var(--green);font-weight:700;font-size:.9rem">粉骨・洗骨のページへ →</a></p>
        </div>
      </div>
    </div>
  </section>

  <?php require __DIR__ . '/../../includes/shiryou-cta.php'; ?>

  <section class="section" style="background:linear-gradient(135deg,var(--green),var(--green-mid));color:#fff;text-align:center">
    <div class="container">
      <h2 style="color:#fff">まずは今の状況をお聞かせください</h2>
      <p style="opacity:.92;margin-bottom:22px">「まだ決めていない」「費用だけ知りたい」でも歓迎です。こちらから営業のご連絡はいたしません。</p>
      <a href="/contact/?service=<?= rawurlencode('お墓じまい') ?>" class="btn" style="background:#fff;color:var(--green-mid)">お問い合わせ</a>
      <a href="<?= h(SITE['line_url']) ?>" target="_blank" rel="noopener" class="btn" style="background:#06C755;margin-left:10px">LINEで相談</a>
      <p style="margin-top:18px">
        本社（鹿児島）<a href="tel:<?= h(str_replace('-', '', SITE['tel'])) ?>" style="color:#fff;font-weight:700;font-size:1.2rem"><?= h(SITE['tel']) ?></a><br>
        <span style="font-size:.9rem"><?= h(SITE['fukuoka']['name']) ?> <a href="tel:<?= h(str_replace('-', '', SITE['fukuoka']['tel'])) ?>" style="color:#fff;font-weight:700"><?= h(SITE['fukuoka']['tel']) ?></a></span>
      </p>
    </div>
  </section>
</main>

<script type="application/ld+json">
<?= json_encode([
  '@context' => 'https://schema.org',
  '@type' => 'BreadcrumbList',
  'itemListElement' => [
    ['@type' => 'ListItem', 'position' => 1, 'name' => 'ホーム', 'item' => SITE['url'] . '/'],
    ['@type' => 'ListItem', 'position' => 2, 'name' => 'お墓じまい', 'item' => SITE['url'] . '/grave/'],
    ['@type' => 'ListItem', 'position' => 3, 'name' => '墓じまい後の散骨', 'item' => $page_canonical],
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
  ], $gs_faq),
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>
</script>
<?php require __DIR__ . '/../../includes/footer.php'; ?>
