<?php
/**
 * 海洋散骨 福岡 専用ページ（/kaiyou-sou/fukuoka/）
 * 役割分担：/kaiyou-sou/=海洋散骨の総合ページ（鹿児島・錦江湾中心）／/fukuoka/=福岡営業所の総合ページ／
 *           本ページ=「海洋散骨 福岡」「散骨 博多湾」検索と広告CP3の受け皿。
 *           プラン・料金・粉骨基準は共通、博多湾・姪浜出港・福岡の海域事情・拠点情報は福岡独自。
 */
require_once __DIR__ . '/../../includes/config.php';

$page_title     = '海洋散骨 福岡｜博多湾での海洋葬 委託54,450円〜｜縁（えん）福岡営業所';
$page_desc      = '福岡の海洋散骨（海洋葬）は日本海洋散骨協会加盟の縁へ。博多湾など福岡の海域に対応、姪浜から出港する合同海洋葬148,500円〜、立ち会い不要の委託54,450円〜（税込・粉骨込み）。緯度・経度入りの散骨証明書と当日の写真をお届け。福岡営業所（中央区春吉）で相談無料。';
$page_canonical = SITE['url'] . '/kaiyou-sou/fukuoka/';
$page_hero_image = '/assets/img/svc-kaiyou.jpg';
require __DIR__ . '/../../includes/head.php';

$fk = SITE['fukuoka'];
$fk_tel_link = str_replace('-', '', $fk['tel']);
?>
<body>
<?php require __DIR__ . '/../../includes/header.php'; ?>

<section class="page-hero">
  <h1>福岡の海洋散骨、博多湾の海へお還しします</h1>
  <p>委託海洋葬 <strong style="font-size:1.3em">54,450円</strong>（税込）〜｜合同148,500円〜・チャーター176,000円〜</p>
  <p style="margin-top:10px;font-size:.92rem">粉骨・証明書・当日の写真まで込み。お見積り確定後の追加料金はありません</p>
  <p style="margin-top:12px;display:flex;flex-wrap:wrap;gap:8px;justify-content:center">
    <span style="display:inline-block;background:rgba(255,255,255,.18);padding:5px 16px;border-radius:999px;font-size:.82rem;font-weight:700">日本海洋散骨協会 加盟</span>
    <span style="display:inline-block;background:rgba(255,255,255,.18);padding:5px 16px;border-radius:999px;font-size:.82rem;font-weight:700">2013年から実績3,800件以上</span>
    <span style="display:inline-block;background:rgba(255,255,255,.18);padding:5px 16px;border-radius:999px;font-size:.82rem;font-weight:700">Google口コミ ★4.9</span>
  </p>
</section>
<nav class="breadcrumb"><a href="/">ホーム</a> ＞ <a href="/kaiyou-sou/">海洋散骨</a> ＞ 福岡の海洋散骨</nav>

<main>
  <!-- 導入・定義（GEO引用用） -->
  <section class="section">
    <div class="container" style="max-width:820px">
      <div class="prose" style="max-width:780px;margin:0 auto">
        <p class="lead"><strong>海洋散骨（海洋葬）</strong>とは、パウダー状にしたご遺骨を、ルールに沿って海へお還しするご供養です。有限会社 縁は日本海洋散骨協会の加盟事業者として、<strong>博多湾など福岡の海域での散骨</strong>に対応しています。合同海洋葬は<strong>姪浜（福岡市西区）から出港</strong>し、立ち会い不要の委託海洋葬（54,450円・税込）なら県外にお住まいのままでもご利用いただけます。粉骨・散骨証明書（緯度・経度入り）・当日の写真まで含んだ明朗価格です。</p>
      </div>
    </div>
  </section>

  <!-- こんな方に（福岡文脈） -->
  <section class="section" style="background:var(--white)">
    <div class="container" style="max-width:820px">
      <h2>福岡で、こんなご相談が増えています</h2>

      <ul style="list-style:none;display:grid;gap:12px;margin-top:20px">
        <li style="padding:14px 18px;background:var(--cream);border-radius:10px;border-left:4px solid var(--green)">故人が福岡で育った・海が好きだったので、博多湾でお見送りしたい</li>
        <li style="padding:14px 18px;background:var(--cream);border-radius:10px;border-left:4px solid var(--green)">墓じまいで取り出したご遺骨の行き先を探している</li>
        <li style="padding:14px 18px;background:var(--cream);border-radius:10px;border-left:4px solid var(--green)">お墓を建てても継ぐ人がいない。管理の要らない供養にしたい</li>
        <li style="padding:14px 18px;background:var(--cream);border-radius:10px;border-left:4px solid var(--green)">自宅に置いたままのご遺骨を、そろそろきちんと供養したい</li>
      </ul>
    </div>
  </section>

  <!-- プラン・料金 -->
  <section class="section">
    <div class="container" style="max-width:860px">
      <h2 style="text-align:center">福岡の海洋散骨 3つのプラン</h2>
      <p style="text-align:center;color:var(--text-light);font-size:.92rem;margin:6px 0 22px">いずれも税込・粉骨込み。金額は無料のお見積りで確定し、あとから追加料金をいただくことはありません。</p>
      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:16px">
        <?php foreach ([
          ['name' => '委託海洋葬', 'price' => '54,450円〜', 'badge' => '期間限定価格（通常66,000円）', 'img' => '/assets/img/plan-itaku.jpg',
           'desc' => 'ご遺族様に代わり、スタッフが献花とともに丁寧に散骨します。立ち会い不要・ご遺骨は郵送でお預かりでき、県外からのご依頼が最も多いプランです。'],
          ['name' => '合同海洋葬', 'price' => '148,500円〜', 'badge' => '姪浜から出港', 'img' => '/assets/img/plan-goudou.jpg',
           'desc' => '複数のご遺族様で乗り合わせ、博多湾でお見送りするプランです。姪浜旅客待合所（福岡市西区）から定期的に出港しています。'],
          ['name' => 'チャーター海洋葬', 'price' => '176,000円〜', 'badge' => null, 'img' => '/assets/img/plan-charter.jpg',
           'desc' => '船を貸し切り、ご家族・ご友人だけでゆっくりとお見送りするプランです。日程や演出のご希望にも柔軟に対応できます。'],
        ] as $pl): ?>
        <div class="card" style="padding:0;overflow:hidden;display:flex;flex-direction:column">
          <img src="<?= h($pl['img']) ?>?v=<?= asset_ver() ?>" alt="<?= h($pl['name']) ?>" width="1200" height="750" loading="lazy" style="width:100%;aspect-ratio:16/10;object-fit:cover;display:block">
          <div style="padding:18px 20px;display:flex;flex-direction:column;flex:1">
            <h3 style="color:var(--green-mid);font-size:1.05rem;margin-bottom:2px"><?= h($pl['name']) ?></h3>
            <p style="font-size:1.25rem;font-weight:700;color:var(--green)"><?= h($pl['price']) ?><?php if ($pl['badge']): ?> <span style="font-size:.72rem;background:var(--cream);color:var(--green-mid);padding:3px 10px;border-radius:999px;font-weight:700;vertical-align:middle"><?= h($pl['badge']) ?></span><?php endif; ?></p>
            <p style="font-size:.88rem;line-height:1.85;margin-top:8px"><?= h($pl['desc']) ?></p>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <p style="text-align:center;margin-top:16px;font-size:.85rem;color:var(--text-light)">散骨後に同じ海域を再訪する<strong>メモリアルクルーズ（176,000円）</strong>、故人様への手紙を海域へ届ける<strong>「天国への手紙」（無料）</strong>もございます。<br>※ 海域や出港場所、ご遺骨の状態などにより金額が変わる場合があります。</p>
      <p style="text-align:center;margin-top:10px;font-size:.9rem"><a href="/kaiyou-sou/" style="color:var(--green);font-weight:600">プランの詳細・セレモニーの様子は海洋散骨ページで →</a></p>
    </div>
  </section>

  <!-- 福岡独自：博多湾・出港地・海域 -->
  <section class="section" style="background:var(--white)">
    <div class="container" style="max-width:860px">
      <h2 style="text-align:center;margin-bottom:8px">博多湾での散骨と出港場所</h2>
      <p style="text-align:center;color:var(--text-light);font-size:.92rem;margin-bottom:24px">福岡の海をよく知る事業者として、ルールに沿った海域でお見送りします。</p>
      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:16px">
        <div class="card">
          <h3 style="color:var(--green-mid);font-size:1rem;margin-bottom:8px">散骨する海域</h3>
          <p style="font-size:.9rem;line-height:1.9">博多湾をはじめ福岡の海域に対応します。海水浴場や漁場、養殖場、航路を避けた海域を選定し、散骨した位置は緯度・経度で記録して証明書に記載します。ゆかりの海でのお見送りをご希望の場合も、海域からご相談いただけます。</p>
        </div>
        <div class="card">
          <h3 style="color:var(--green-mid);font-size:1rem;margin-bottom:8px">出港場所（合同海洋葬）</h3>
          <p style="font-size:.9rem;line-height:1.9"><strong>姪浜旅客待合所</strong>（福岡市西区愛宕浜3丁目1-1）から出港します。地下鉄姪浜駅からタクシーで約5分、駐車場もあります。乗船からご帰港まで1時間半〜2時間程度が目安です。</p>
          <p style="margin-top:8px"><a href="https://maps.app.goo.gl/ssPvPegY1qikqrEz9" target="_blank" rel="noopener" style="color:var(--green);font-weight:600;font-size:.88rem">出港場所をGoogleマップで見る →</a></p>
        </div>
        <div class="card">
          <h3 style="color:var(--green-mid);font-size:1rem;margin-bottom:8px">協会ルールを守った運航</h3>
          <p style="font-size:.9rem;line-height:1.9">日本海洋散骨協会のガイドラインに沿い、ご遺骨は2mm以下のパウダー状にし、六価クロム検査・無害化も標準実施。献花は花びらのみ、自然に還らないものは海に入れません。天候・海況を見極め、安全第一で運航します。</p>
        </div>
      </div>
    </div>
  </section>

  <!-- 選ばれる品質（価格だけで選ばないで・福岡版） -->
  <section class="section">
    <div class="container" style="max-width:960px">
      <p style="text-align:center;font-size:.78rem;letter-spacing:.28em;color:#b08b3e;font-weight:700;margin-bottom:8px">QUALITY</p>
      <h2 style="text-align:center;margin-bottom:14px">料金の安さだけで選ばないでください</h2>
      <p style="text-align:center;max-width:720px;margin:0 auto 28px;line-height:2">
        福岡でも、格安をうたう散骨サービスが増えています。<br class="pc-only">
        しかし「実際にどの海域で散骨されたのかわからない」「証明書が発行されない」「あとから追加料金を請求された」——そんなケースも報告されています。<br class="pc-only">
        大切な方のご遺骨を託す、一度きりのご供養だからこそ、<strong>料金だけでなく「どこで・誰が・どのように」散骨するのか</strong>をご確認ください。
      </p>
      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:14px">
        <?php foreach ([
          ['協会加盟の事業者か', '縁は一般社団法人日本海洋散骨協会の加盟事業者。ガイドラインと海域のルールを順守し、環境に配慮した散骨を行います。'],
          ['粉骨の品質と六価クロム対策', 'ご遺骨は一件ずつ丁寧にパウダー化。発がん性物質「六価クロム」の検査・無害化処理まで行ってから海にお還しします（2019年から実施）。'],
          ['散骨の証明', '散骨海域の緯度・経度入りの「散骨証明書」と当日のお写真をお届け。博多湾のどこでお見送りしたかが、かたちで残ります。'],
          ['料金の明確さ', '金額は無料のお見積りで確定。ご納得いただいてからのご契約で、あとから追加料金をいただくことはありません。'],
          ['散骨後のご供養', 'メモリアルクルーズ、天国への手紙（無料）、手元供養など、「その後」のご供養まで自社で一貫してお手伝いします。'],
          ['実績と信頼', '鹿児島・福岡を中心に全国3,800件以上・10年以上の実績。Google口コミ評価★4.9をいただいています。'],
        ] as [$t, $d]): ?>
        <div class="card">
          <h3 style="color:var(--green-mid);font-size:1rem;margin-bottom:8px"><span style="color:var(--green)">✓</span> <?= h($t) ?></h3>
          <p style="font-size:.9rem;line-height:1.9"><?= h($d) ?></p>
        </div>
        <?php endforeach; ?>
      </div>
      <div class="card" style="margin-top:22px;background:var(--cream)">
        <p style="font-weight:700;color:var(--green-mid);margin-bottom:8px">たとえば——ご遺骨の「六価クロム」検査・無害化</p>
        <p style="font-size:.9rem;line-height:1.9">火葬炉の耐熱ステンレスなどに由来する発がん性物質「六価クロム」が、ご遺骨に付着していることがあります。当社は散骨前の粉骨の際、専用キットで検査し、検出された場合は骨灰専用の還元剤で無害化してから海にお還しします（2019年から標準実施・追加料金なし）。格安サービスでは省略されがちな、見えない工程です。</p>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:10px;margin-top:14px">
          <figure style="margin:0"><img src="/powder-cleaning/images/pc-cr6-check.jpg?v=<?= h(asset_ver()) ?>" alt="六価クロム検査キットと標準色カード" width="1400" height="933" loading="lazy" style="width:100%;aspect-ratio:3/2;object-fit:cover;border-radius:8px;display:block"><figcaption style="font-size:.78rem;color:var(--text-light);text-align:center;margin-top:6px">専用キットで検査</figcaption></figure>
          <figure style="margin:0"><img src="/powder-cleaning/images/pc-cr6-positive.jpg?v=<?= h(asset_ver()) ?>" alt="六価クロムが検出され検査液がピンク色に変色した状態" width="1400" height="933" loading="lazy" style="width:100%;aspect-ratio:3/2;object-fit:cover;border-radius:8px;display:block"><figcaption style="font-size:.78rem;color:var(--text-light);text-align:center;margin-top:6px">変色したら「検出」のサイン</figcaption></figure>
          <figure style="margin:0"><img src="/powder-cleaning/images/pc-cr6-agent.jpg?v=<?= h(asset_ver()) ?>" alt="骨灰専用の六価クロム還元剤" width="1400" height="933" loading="lazy" style="width:100%;aspect-ratio:3/2;object-fit:cover;border-radius:8px;display:block"><figcaption style="font-size:.78rem;color:var(--text-light);text-align:center;margin-top:6px">専用還元剤で無害化</figcaption></figure>
        </div>
        <p style="text-align:center;margin-top:12px"><a href="/powder-cleaning/" style="color:var(--green);font-weight:600;font-size:.9rem">六価クロムの検査・無害化について詳しく見る →</a></p>
      </div>
      <p style="text-align:center;margin-top:22px;font-size:.9rem;color:var(--text-light)">
        他社さまとご比較の際は、上の6点をチェックリストとしてご活用ください。<br class="pc-only">
        「見積りだけ」「話を聞くだけ」でも歓迎です。どうぞ納得のいくまでご比較ください。
      </p>
    </div>
  </section>

  <!-- セレモニーの様子 -->
  <section class="section" style="background:var(--white)">
    <div class="container" style="max-width:860px">
      <h2 style="text-align:center;margin-bottom:8px">セレモニーの様子</h2>
      <p style="text-align:center;color:var(--text-light);font-size:.92rem;margin-bottom:22px">献花・献水・鐘の音とともにお見送りします。</p>
      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:12px">
        <?php foreach ([
          ['ks-ceremony.jpg', '船上に用意された献花・献酒のセレモニーセット'],
          ['ks-maku.jpg', '花びらを海へ撒くセレモニーの瞬間'],
          ['ks-kensui.jpg', '散骨後に海へ水を手向ける献水'],
          ['ks-bell.jpg', '故人を偲び鳴らす船上の鐘'],
        ] as [$gf, $ga]): ?>
        <figure style="margin:0"><img src="/kaiyou-sou/images/<?= h($gf) ?>?v=<?= h(asset_ver()) ?>" alt="<?= h($ga) ?>" width="900" height="600" loading="lazy" style="width:100%;aspect-ratio:3/2;object-fit:cover;border-radius:10px;display:block;border:1px solid var(--border)"></figure>
        <?php endforeach; ?>
      </div>
      <p style="text-align:center;margin-top:14px;font-size:.9rem"><a href="/kaiyou-sou/" style="color:var(--green);font-weight:600">セレモニーの写真をもっと見る（海洋散骨ページ）→</a></p>
    </div>
  </section>

  <!-- 実施予定日（管理画面から更新・福岡のみ） -->
  <?php $gd_filter = '福岡'; $gd_area_label = '福岡'; require __DIR__ . '/../../includes/goudou-schedule.php'; ?>

  <!-- 流れ -->
  <section class="section" style="background:var(--white)">
    <div class="container" style="max-width:760px">
      <h2 style="text-align:center;margin-bottom:24px">お申込みから散骨までの流れ</h2>
      <ol style="list-style:none;display:grid;gap:12px;counter-reset:st">
        <?php foreach ([
          ['無料相談（電話・LINE・メール）', '「話を聞くだけ」でも歓迎です。福岡営業所（中央区春吉）での対面相談もできます。'],
          ['プランのご提案・お見積り', 'ご希望をうかがい、総額を確定してご提示します。確定後の追加料金はありません。'],
          ['ご遺骨のお預かり', 'お持ち込みのほか、ゆうパックでのご郵送にも対応。梱包の手順は丁寧にご案内します。'],
          ['粉骨（パウダー化）', '協会ルールに沿って2mm以下に粉骨し、六価クロム検査・無害化を標準実施します。'],
          ['海洋葬の実施', '博多湾の海域で、献花・献水とともにお見送りします。当日の様子は写真に残します。'],
          ['散骨証明書のお渡し', '緯度・経度入りの散骨証明書と当日の写真をお届けします。メモリアルクルーズで再訪もできます。'],
        ] as $i => [$t, $d]): ?>
        <li style="display:grid;grid-template-columns:40px 1fr;gap:14px;background:#fff;border:1px solid var(--border);border-radius:12px;padding:14px 18px;align-items:center">
          <span style="width:36px;height:36px;border-radius:50%;background:var(--green);color:#fff;display:grid;place-items:center;font-weight:700"><?= $i + 1 ?></span>
          <span><strong style="color:var(--green-mid)"><?= h($t) ?></strong><br><span style="font-size:.88rem;color:var(--text-light)"><?= h($d) ?></span></span>
        </li>
        <?php endforeach; ?>
      </ol>
      <figure style="max-width:340px;margin:24px auto 0;text-align:center">
        <img src="/assets/img/certificate.jpg?v=<?= asset_ver() ?>" alt="緯度・経度入りの海洋葬証明書" width="900" height="1200" loading="lazy" style="width:100%;border:1px solid var(--border);border-radius:10px">
        <figcaption style="margin-top:10px;font-size:.82rem;color:var(--text-light)">実際にお渡ししている「海洋葬証明書」。散骨海域の緯度・経度と当日のお写真入りです。</figcaption>
      </figure>
    </div>
  </section>

  <!-- クロスセル：墓じまい・粉骨・手元供養 -->
  <section class="section" style="background:var(--cream)">
    <div class="container" style="max-width:860px">
      <h2 style="text-align:center;margin-bottom:8px">墓じまいから散骨まで、一つの窓口で</h2>
      <p style="text-align:center;max-width:680px;margin:0 auto 24px;line-height:2;font-size:.94rem">ご遺骨をすべて海に還す必要はありません。<strong>「大部分を散骨して、少しだけ手元に残す」という組み合わせ</strong>もできます。</p>
      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:14px">
        <div class="card">
          <h3 style="font-size:.98rem;color:var(--green-mid);margin-bottom:6px">福岡の墓じまい</h3>
          <p style="font-size:.88rem;line-height:1.85">墓石の撤去から遺骨の取り出し、納骨先まで基本プラン33万円（税込）。取り出したご遺骨をそのまま博多湾での散骨へつなげられます。</p>
          <p style="margin-top:10px"><a href="/grave/fukuoka/" style="color:var(--green);font-weight:700;font-size:.9rem">福岡の墓じまいを見る →</a></p>
        </div>
        <div class="card">
          <h3 style="font-size:.98rem;color:var(--green-mid);margin-bottom:6px">粉骨・洗骨</h3>
          <p style="font-size:.88rem;line-height:1.85">散骨の前提となる粉骨は24,200円〜。一件ずつ手作業で行い、洗骨・乾燥・真空パックにも対応します。粉骨のみのご依頼も可能です。</p>
          <p style="margin-top:10px"><a href="/powder-cleaning/" style="color:var(--green);font-weight:700;font-size:.9rem">粉骨・洗骨を見る →</a></p>
        </div>
        <div class="card">
          <h3 style="font-size:.98rem;color:var(--green-mid);margin-bottom:6px">一部を手元供養に</h3>
          <p style="font-size:.88rem;line-height:1.85">手のひらサイズのミニ骨壷や、お米一粒ほどのご遺骨を納めるリング・ペンダント。手を合わせる場所を残したい方に。</p>
          <p style="margin-top:10px"><a href="/temoto-kuyou/" style="color:var(--green);font-weight:700;font-size:.9rem">お手元供養を見る →</a></p>
        </div>
      </div>
    </div>
  </section>

  <!-- FAQ（福岡固有＋共通） -->
  <?php
    $kf_faq = [
      ['q' => '博多湾で散骨できますか？場所は選べますか？',
       'a' => 'はい。博多湾をはじめ福岡の海域での海洋散骨に対応しています。海水浴場・漁場・養殖場・航路を避けた海域から選定し、散骨した位置は緯度・経度で記録して証明書に記載します。ゆかりの海でのお見送りをご希望の場合もご相談ください。'],
      ['q' => '福岡の海洋散骨の費用はいくらですか？',
       'a' => '立ち会い不要の委託海洋葬が54,450円（税込・期間限定価格、通常66,000円）、乗り合いの合同海洋葬が148,500円〜、貸し切りのチャーター海洋葬が176,000円〜です。いずれも粉骨・散骨証明書・当日の写真を含みます。金額は無料のお見積りで確定し、あとから追加料金をいただくことはありません。'],
      ['q' => '合同海洋葬はどこから出港しますか？',
       'a' => '姪浜旅客待合所（福岡市西区愛宕浜3丁目1-1）から出港します。地下鉄姪浜駅からタクシーで約5分です。実施予定日はこのページの開催スケジュールでご確認いただけます。'],
      ['q' => '散骨は違法ではありませんか？',
       'a' => '法務省は「節度をもって葬送の一つとして行われる限り違法ではない」との見解を示しており、厚生労働省のガイドライン（2021年）も公表されています。当社は日本海洋散骨協会の加盟事業者として、ご遺骨を2mm以下に粉骨し、適切な海域・方法で散骨を行います。'],
      ['q' => '県外に住んでいても、福岡の海で散骨してもらえますか？',
       'a' => 'できます。ご遺骨はゆうパックでのご郵送でお預かりでき、立ち会い不要の委託海洋葬（54,450円〜）なら帰省せずにすべてお任せいただけます。散骨後は緯度・経度入りの散骨証明書と当日のお写真をご自宅へお届けします。'],
      ['q' => '墓じまいで取り出した遺骨も散骨できますか？',
       'a' => 'できます。長くお墓に納められていたご遺骨は湿気を含んでいることが多いため、洗骨・乾燥のうえ粉骨してから散骨します。福岡県内の墓じまい（基本プラン33万円・税込）から博多湾での散骨まで、一つの窓口で一括対応できます。'],
      ['q' => '遺骨の全部ではなく、一部だけ散骨することはできますか？',
       'a' => 'できます。粉骨の際にご希望の量だけお分けし、大部分を散骨、ひとつまみをミニ骨壷やペンダントでお手元に残す方も多くいらっしゃいます。お持ち込みのお手元供養品への分骨は5,500円（税込）です。'],
      ['q' => '当日の服装や持ち物に決まりはありますか？',
       'a' => '喪服である必要はありません。船上は風がありますので、動きやすく体温調節しやすい服装と滑りにくい靴がおすすめです。献花用の花びらなどはこちらでご用意します。'],
      ['q' => '天候が悪い場合はどうなりますか？',
       'a' => '安全を最優先し、海況によっては日程を変更します。出港の可否は事前に見極めてご連絡し、延期による追加料金はいただきません。'],
      ['q' => 'ペットの遺骨も散骨できますか？',
       'a' => 'はい、ペットのご遺骨の散骨もご相談いただけます。ご家族と同じように丁寧に粉骨し、海へお還しします。詳しくはお問い合わせください。'],
      ['q' => '相談だけでも大丈夫ですか？どこに行けばいいですか？',
       'a' => 'もちろん大丈夫です。福岡営業所（福岡市中央区春吉2丁目1-3 2F）でのご相談のほか、お電話（090-5000-4825）・LINE・メールでもご相談いただけます。ご相談・お見積りは無料で、こちらから営業のご連絡はいたしません。'],
    ];
  ?>
  <section class="section" style="background:var(--white)">
    <div class="container" style="max-width:820px">
      <h2 style="text-align:center;margin-bottom:8px">福岡の海洋散骨 よくあるご質問</h2>
      <p style="text-align:center;font-size:.9rem;color:var(--text-light);margin-bottom:24px">このほかのご質問もお気軽にどうぞ。</p>
      <?php foreach ($kf_faq as $f): ?>
        <details style="background:#fff;border:1px solid var(--border);border-radius:10px;padding:16px 20px;margin-bottom:12px">
          <summary style="font-weight:600;cursor:pointer;color:var(--green-mid)">Q. <?= h($f['q']) ?></summary>
          <p style="margin-top:10px;font-size:.95rem;line-height:1.9">A. <?= h($f['a']) ?></p>
        </details>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- 拠点情報（MEO連携） -->
  <section class="section">
    <div class="container" style="max-width:760px">
      <h2 style="text-align:center;margin-bottom:20px">福岡のご相談窓口</h2>
      <div class="card" style="max-width:560px;margin:0 auto;text-align:center">
        <p style="font-weight:700;color:var(--green-mid);font-size:1.05rem;margin-bottom:8px">有限会社 縁 <?= h($fk['name']) ?></p>
        <p style="font-size:.92rem;line-height:2">〒<?= h($fk['zip']) ?> <?= h($fk['address']) ?><br>
        電話 <a href="tel:<?= h($fk_tel_link) ?>" style="color:var(--green);font-weight:700"><?= h($fk['tel']) ?></a>（9:00〜18:00・日曜定休）</p>
        <p style="margin-top:10px"><a href="https://maps.google.com/?cid=1235913108976072113" target="_blank" rel="noopener" style="color:var(--green);font-weight:600;font-size:.9rem">Googleマップで見る →</a></p>
        <p style="margin-top:12px;font-size:.85rem;color:var(--text-light)">合同海洋葬の出港場所：姪浜旅客待合所（福岡市西区愛宕浜3丁目1-1）</p>
      </div>
      <p style="text-align:center;margin-top:16px;font-size:.9rem"><a href="/fukuoka/" style="color:var(--green);font-weight:600">福岡営業所のサービス全体（粉骨・墓じまいなど）はこちら →</a></p>
    </div>
  </section>

  <!-- 資料請求CTA（共通） -->
  <?php require __DIR__ . '/../../includes/shiryou-cta.php'; ?>

  <!-- CTA -->
  <section class="section" style="background:linear-gradient(135deg,var(--green),var(--green-mid));color:#fff;text-align:center">
    <div class="container">
      <h2 style="color:#fff">まずは無料でご相談ください</h2>
      <p style="opacity:.92;margin-bottom:22px">「話を聞くだけ」でも歓迎です。こちらから営業のご連絡はいたしません。</p>
      <a href="/contact/?service=<?= rawurlencode('海洋散骨') ?>" class="btn" style="background:#fff;color:var(--green-mid)">お問い合わせ</a>
      <a href="<?= h(SITE['line_url']) ?>" target="_blank" rel="noopener" class="btn" style="background:#06C755;margin-left:10px">LINEで相談</a>
      <a href="/contact/?service=<?= rawurlencode('資料請求（無料）') ?>" class="btn" style="background:#c9822a;margin-left:10px">無料で資料を受け取る</a>
      <p style="margin-top:18px">
        <?= h($fk['name']) ?> <a href="tel:<?= h($fk_tel_link) ?>" style="color:#fff;font-weight:700;font-size:1.2rem"><?= h($fk['tel']) ?></a><br>
        <span style="font-size:.9rem">本社（鹿児島） <a href="tel:<?= h(str_replace('-', '', SITE['tel'])) ?>" style="color:#fff;font-weight:700"><?= h(SITE['tel']) ?></a></span>
      </p>
    </div>
  </section>
</main>

<script type="application/ld+json">
<?= json_encode([
  '@context' => 'https://schema.org',
  '@type' => 'Service',
  'serviceType' => '海洋散骨（海洋葬）',
  'name' => '福岡の海洋散骨（博多湾）',
  'provider' => [
    '@type' => 'LocalBusiness',
    'name' => '有限会社 縁 福岡営業所',
    'telephone' => '+81-90-5000-4825',
    'address' => ['@type' => 'PostalAddress', 'streetAddress' => '春吉2丁目1-3 2F', 'addressLocality' => '福岡市中央区', 'addressRegion' => '福岡県', 'postalCode' => '810-0003', 'addressCountry' => 'JP'],
    'url' => SITE['url'] . '/kaiyou-sou/fukuoka/',
    'hasMap' => 'https://maps.google.com/?cid=1235913108976072113',
    'memberOf' => ['@type' => 'Organization', 'name' => '日本海洋散骨協会'],
  ],
  'areaServed' => [
    ['@type' => 'State', 'name' => '福岡県'],
    ['@type' => 'City', 'name' => '福岡市'],
    ['@type' => 'City', 'name' => '北九州市'],
  ],
  'offers' => [
    ['@type' => 'Offer', 'name' => '委託海洋葬', 'price' => '54450', 'priceCurrency' => 'JPY', 'description' => '立ち会い不要の委託海洋散骨（税込・粉骨・証明書・写真込み）'],
    ['@type' => 'Offer', 'name' => '合同海洋葬', 'price' => '148500', 'priceCurrency' => 'JPY', 'description' => '姪浜から出港する乗り合いの海洋散骨（税込）'],
    ['@type' => 'Offer', 'name' => 'チャーター海洋葬', 'price' => '176000', 'priceCurrency' => 'JPY', 'description' => '貸し切りの海洋散骨（税込）'],
  ],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>
</script>
<script type="application/ld+json">
<?= json_encode([
  '@context' => 'https://schema.org',
  '@type' => 'BreadcrumbList',
  'itemListElement' => [
    ['@type' => 'ListItem', 'position' => 1, 'name' => 'ホーム', 'item' => SITE['url'] . '/'],
    ['@type' => 'ListItem', 'position' => 2, 'name' => '海洋散骨', 'item' => SITE['url'] . '/kaiyou-sou/'],
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
