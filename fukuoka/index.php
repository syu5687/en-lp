<?php
/**
 * 福岡営業所の総合ハブ（/fukuoka/）
 *
 * 役割分担
 *   本ページ                … 福岡営業所そのものと、福岡で提供している全サービスの入口。
 *                             ブランド・営業所系の検索（有限会社 縁 福岡／縁 福岡営業所／
 *                             福岡 供養相談）の受け皿。将来 /hakajimai/fukuoka/ 等を
 *                             足していける地域ハブとして設計している。
 *   /kaiyou-sou/fukuoka/    … 「海洋散骨 福岡・福岡 散骨・博多湾 散骨」の専門ページ。
 *                             SEOの主力かつGoogle広告のLP。海洋散骨の詳細はすべてこちら。
 *   /grave/fukuoka/         … 「墓じまい 福岡」の専門ページ。
 *
 * したがって本ページでは海洋散骨を詳しく書かない。概要＋料金の目安にとどめ、
 * 「福岡の海洋散骨について詳しく見る」で /kaiyou-sou/fukuoka/ へ送る。
 */
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../admin/includes/store.php'; // voices_published()（キャッシュ済み・読み取り増なし）

/* ---- お客様の声（福岡・海洋葬関連を優先して3件） ---- */
$fk_voices = [];
try { $fk_voices = voices_published(); } catch (Throwable $e) { $fk_voices = []; }
if (!$fk_voices) {
  $seed = @json_decode((string)@file_get_contents(__DIR__ . '/../data/voices.json'), true);
  foreach (($seed['items'] ?? []) as $v) if (!empty($v['published'])) $fk_voices[] = $v;
}
usort($fk_voices, function ($a, $b) {
  $score = fn($v) => (int)(mb_strpos(($v['who'] ?? '') . ($v['service'] ?? ''), '福岡') !== false) * 2
                   + (int)(mb_strpos(($v['service'] ?? '') . ($v['title'] ?? ''), '海洋') !== false);
  return $score($b) <=> $score($a);
});
$fk_voices = array_slice($fk_voices, 0, 3);

$page_title     = '有限会社 縁 福岡営業所｜福岡の供養相談窓口（海洋散骨・墓じまい・粉骨）';
$page_desc      = '有限会社 縁 福岡営業所（福岡市中央区春吉2丁目1-3 2F）のご案内。海洋散骨・墓じまい・粉骨・洗骨・生前契約・ペット供養のご相談を、対面・電話・LINEで承ります。福岡県内全域に対応、鹿児島・福岡を中心に全国3,800件以上の実績。ご相談・お見積りは無料です。';
$page_canonical = SITE['url'] . '/fukuoka/';
$page_hero_image = '/fukuoka/images/fk-port.jpg';
require __DIR__ . '/../includes/head.php';
$FUK = SITE['fukuoka'];
$sticky_tel = $FUK['tel']; // SP固定CTAの電話番号を福岡営業所に差し替える（footer.php が参照）
$FUK_MAP = 'https://maps.google.com/?cid=1235913108976072113';
$FUK_REVIEW = 'https://g.page/r/CbF1xKls2CYREBM/review';
?>
<body>
<?php require __DIR__ . '/../includes/header.php'; ?>
<section class="page-hero">
  <h1>有限会社 縁 福岡営業所｜福岡の供養のご相談窓口</h1>
  <p>海洋散骨・お墓じまい・粉骨・生前契約・ペット供養を、ひとつの窓口で。<br>福岡市中央区春吉／福岡県内全域に対応</p>
  <p style="margin-top:16px">
    <span style="display:inline-flex;align-items:center;gap:12px;background:#fff;border-radius:12px;padding:10px 18px;box-shadow:0 4px 14px rgba(0,0,0,.18)">
      <img src="/assets/img/jmas-logo.png?v=<?= h(asset_ver()) ?>" alt="一般社団法人 日本海洋散骨協会 ロゴ" width="360" height="454" style="width:44px;height:auto">
      <span style="font-size:.82rem;line-height:1.6;color:#4a5a58;text-align:left;font-weight:600">一般社団法人<br><strong style="color:#2a5a7a;font-size:.95rem">日本海洋散骨協会</strong> 加盟事業者</span>
    </span>
  </p>
</section>
<nav class="breadcrumb"><a href="/">ホーム</a> ＞ 福岡営業所</nav>

<main>
  <!-- リード -->
  <section class="section">
    <div class="container" style="max-width:820px;text-align:center">
      <p class="lead" style="line-height:2.1">有限会社 縁の<strong>福岡営業所</strong>は、福岡市中央区春吉にあるご供養の相談窓口です。<br class="pc-only">
      <strong>海洋散骨・お墓じまい・粉骨・洗骨・生前契約・ペット供養</strong>を、ひとつの窓口でご相談いただけます。<br class="pc-only">
      「何から始めればいいか分からない」という段階でも、どうぞお気軽にお越しください。</p>
      <p style="margin-top:16px;font-size:.95rem;color:var(--text-light)">ご相談・お見積りは無料。こちらから営業のご連絡はいたしません。</p>
      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:14px;margin-top:30px">
        <div class="card" style="text-align:center"><p style="font-size:1.7rem;font-weight:700;color:var(--green)">3,800<span style="font-size:.9rem">件以上</span></p><p style="font-size:.85rem;color:var(--text-light)">鹿児島・福岡を中心に<br>全国の対応実績</p></div>
        <div class="card" style="text-align:center"><p style="font-size:1.7rem;font-weight:700;color:var(--green)">10年<span style="font-size:.9rem">以上</span></p><p style="font-size:.85rem;color:var(--text-light)">海洋葬の実績</p></div>
        <div class="card" style="text-align:center"><p style="font-size:1.7rem;font-weight:700;color:#f4b400">★4.9</p><p style="font-size:.85rem;color:var(--text-light)">Google口コミ評価<br>（本社プロフィール）</p></div>
        <div class="card" style="text-align:center"><img src="/assets/img/jmas-logo.png?v=<?= h(asset_ver()) ?>" alt="一般社団法人 日本海洋散骨協会 ロゴ" width="360" height="454" loading="lazy" style="width:52px;height:auto;margin:0 auto 6px;display:block"><p style="font-size:.85rem;color:var(--text-light)">日本海洋散骨協会の<br>加盟事業者</p></div>
      </div>
    </div>
  </section>

  <!-- こんなお悩みに -->
  <section class="section" style="background:var(--cream)">
    <div class="container" style="max-width:820px">
      <div class="fk-worry-head" style="display:flex;align-items:center;justify-content:center;gap:30px;margin-bottom:26px;text-align:left">
        <div>
          <h2 style="margin:0 0 8px">福岡で、こんなお悩みはありませんか？</h2>
          <p style="color:var(--text-light);font-size:.92rem;margin:0">どんな小さなことでも、代表・スタッフが直接お答えします。</p>
        </div>
        <img src="/assets/img/daihyo-guide.jpg?v=<?= h(asset_ver()) ?>" alt="ご相談を案内する代表" width="360" height="360" loading="lazy"
             class="fk-worry-photo" style="width:150px;height:150px;flex:none;border-radius:50%;border:5px solid #fff;box-shadow:0 8px 24px rgba(18,89,122,.16);background:#fff;object-fit:cover">
      </div>
      <style>@media(max-width:640px){.fk-worry-head{gap:12px !important}.fk-worry-photo{width:98px !important;height:98px !important;border-width:3px !important}}</style>
      <ul style="list-style:none;display:grid;gap:12px;padding:0;max-width:680px;margin:0 auto">
        <li style="padding:14px 18px;background:#fff;border-radius:10px;border-left:4px solid var(--green)">故人の「海に散骨してほしい」という希望を、福岡の海で叶えたい</li>
        <li style="padding:14px 18px;background:#fff;border-radius:10px;border-left:4px solid var(--green)">お墓を継ぐ人がおらず、お墓じまいと今後の供養をまとめて相談したい</li>
        <li style="padding:14px 18px;background:#fff;border-radius:10px;border-left:4px solid var(--green)">ご遺骨の保管に困っている。粉骨してコンパクトにしたい</li>
        <li style="padding:14px 18px;background:#fff;border-radius:10px;border-left:4px solid var(--green)">自分の海洋散骨を、元気なうちに生前契約で決めておきたい</li>
      </ul>
      <p style="text-align:center;margin-top:22px;color:var(--text-light);font-size:.95rem">どのお悩みも、福岡営業所で対面のご相談が可能です。ご相談・お見積りは無料です。</p>
    </div>
  </section>

  <!-- 福岡でできること -->
  <section class="section">
    <div class="container" style="max-width:960px">
      <h2 style="text-align:center;margin-bottom:30px">福岡営業所でできること</h2>
      <?php
        $fk_services = [
          ['href' => '/kaiyou-sou/fukuoka/', 'img' => '/assets/img/svc-kaiyou.jpg',        'alt' => '海洋散骨セレモニーで花びらが広がる海',      'w' => 1200, 'h' => 750,  'title' => '海洋散骨（海洋葬）',   'desc' => '博多湾など福岡の海域での散骨。委託・合同・チャーターの3プランと、出航場所・流れ・法律まで専用ページでご説明しています。'],
          ['href' => '/seizen/',          'img' => '/seizen/images/omoi-boat.webp',       'alt' => '海洋散骨の生前契約を託すクルーズ船',        'w' => 1200, 'h' => 800,  'title' => '海洋散骨 生前契約',    'desc' => '「海洋散骨をしたい」という想いを生前に契約して託せます。テレビでも紹介された、福岡対応のサービスです。'],
          ['href' => '/powder-cleaning/', 'img' => '/assets/img/svc-funkotsu.jpg',        'alt' => 'ご遺骨を丁寧にパウダー化する粉骨作業',      'w' => 1200, 'h' => 750,  'title' => '粉骨・洗骨',           'desc' => 'ご遺骨のパウダー化（24,200円〜）・クリーニング。お持ち込みのご相談のほか、郵送でもご利用いただけます。'],
          ['href' => '/grave/fukuoka/',   'img' => '/assets/img/hero-grave.jpg',          'alt' => '手を合わせてお参りするお墓',                'w' => 2000, 'h' => 1333, 'title' => 'お墓じまい',           'desc' => '撤去から納骨まで一括対応の基本プラン33万円（税込）。改葬の行政手続きのサポートも承ります。'],
          ['href' => '/pet-kaiyou-sou/',  'img' => '/assets/img/hero-pet-kaiyou-sou.jpg', 'alt' => '大切な家族の一員を見送る穏やかな海',        'w' => 2000, 'h' => 1333, 'title' => 'ペット供養',           'desc' => '大切な家族の一員の粉骨・海洋散骨・手元供養。福岡からの郵送・ご相談に対応しています。'],
          ['href' => '/flow/',            'img' => '/assets/img/svc-soudan.jpg',          'alt' => 'スタッフが丁寧にご相談を伺う様子',          'w' => 1200, 'h' => 750,  'title' => 'お申込みの流れ',       'desc' => 'ご相談→お見積り（無料）→お申し込み→お預かり→施行→アフターサポートの6ステップをご案内します。'],
        ];
      ?>
      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:18px">
        <?php foreach ($fk_services as $s): ?>
        <a class="card" href="<?= h($s['href']) ?>" style="display:flex;flex-direction:column;padding:0;overflow:hidden">
          <span style="display:block;aspect-ratio:16/9;overflow:hidden;background:#eef5f8">
            <img src="<?= h($s['img']) ?>?v=<?= h(asset_ver()) ?>" alt="<?= h($s['alt']) ?>" width="<?= (int)$s['w'] ?>" height="<?= (int)$s['h'] ?>" loading="lazy" style="width:100%;height:100%;object-fit:cover;display:block">
          </span>
          <span style="display:flex;flex-direction:column;flex:1;padding:18px 20px 20px">
            <h3 style="color:var(--green);margin-bottom:8px"><?= h($s['title']) ?></h3>
            <p style="font-size:.92rem;flex:1"><?= h($s['desc']) ?></p>
            <span style="margin-top:12px;color:var(--green);font-weight:600;font-size:.85rem">詳しく見る →</span>
          </span>
        </a>
        <?php endforeach; ?>
      </div>
      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:14px;margin-top:30px">
        <img src="/fukuoka/images/fk-ceremony.jpg?v=<?= h(asset_ver()) ?>" alt="船上に用意された献花と献酒のセレモニーセット" width="900" height="600" loading="lazy" style="width:100%;aspect-ratio:3/2;object-fit:cover;border-radius:12px;box-shadow:0 6px 18px rgba(0,0,0,.1)">
        <img src="/fukuoka/images/fk-petals.jpg?v=<?= h(asset_ver()) ?>" alt="海へ花びらを手向ける散骨セレモニーの様子" width="900" height="600" loading="lazy" style="width:100%;aspect-ratio:3/2;object-fit:cover;border-radius:12px;box-shadow:0 6px 18px rgba(0,0,0,.1)">
        <img src="/fukuoka/images/fk-kensui.jpg?v=<?= h(asset_ver()) ?>" alt="散骨後に海へ水を手向ける献水の様子" width="900" height="600" loading="lazy" style="width:100%;aspect-ratio:3/2;object-fit:cover;border-radius:12px;box-shadow:0 6px 18px rgba(0,0,0,.1)">
      </div>
      <p style="text-align:center;margin-top:12px;font-size:.85rem;color:var(--text-light)">実際の海洋散骨セレモニーの様子</p>
    </div>
  </section>

  <!-- 福岡向けの詳しいご案内（地域ハブ → 専門ページへの導線）
       今後 /funkotsu/fukuoka/ などを追加する場合も、このカードを増やすだけで済む構成にしている -->
  <section class="section" style="background:var(--white)">
    <div class="container" style="max-width:860px">
      <h2 style="text-align:center;margin-bottom:8px">福岡向けの詳しいご案内</h2>
      <p style="text-align:center;color:var(--text-light);font-size:.92rem;margin-bottom:24px">よくご相談いただく2つのサービスは、福岡の情報だけをまとめた専用ページをご用意しています。</p>
      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:18px">
        <a class="card" href="/kaiyou-sou/fukuoka/" style="display:flex;flex-direction:column">
          <p style="display:inline-block;align-self:flex-start;background:var(--sea-light);color:var(--green);font-size:.72rem;font-weight:700;padding:3px 12px;border-radius:999px">海洋散骨</p>
          <h3 style="margin:12px 0 8px;color:var(--green-mid);font-size:1.05rem">福岡・博多湾の海洋散骨</h3>
          <p style="font-size:.9rem;line-height:1.9;flex:1">博多湾での散骨海域、姪浜旅客待合所からの出航、委託54,450円〜の3プラン、ご相談から散骨証明書までの流れ、法律上の扱い、業者選びのポイントまで。</p>
          <span style="margin-top:12px;color:var(--green);font-weight:700;font-size:.9rem">福岡の海洋散骨について詳しく見る →</span>
        </a>
        <a class="card" href="/grave/fukuoka/" style="display:flex;flex-direction:column">
          <p style="display:inline-block;align-self:flex-start;background:#f6efdf;color:#a8802f;font-size:.72rem;font-weight:700;padding:3px 12px;border-radius:999px">お墓じまい</p>
          <h3 style="margin:12px 0 8px;color:var(--green-mid);font-size:1.05rem">福岡の墓じまい</h3>
          <p style="font-size:.9rem;line-height:1.9;flex:1">基本プラン33万円（税込）に含まれる内容、市営霊園の返還手続き、撤去工事の実例（Before→After）、改葬許可申請のサポートまで。</p>
          <span style="margin-top:12px;color:var(--green);font-weight:700;font-size:.9rem">福岡の墓じまいについて詳しく見る →</span>
        </a>
      </div>
    </div>
  </section>

  <!-- 料金のご案内 -->
  <section class="section" id="price" style="background:var(--cream)">
    <div class="container" style="max-width:960px">
      <p style="text-align:center;font-size:.78rem;letter-spacing:.28em;color:#b08b3e;font-weight:700;margin-bottom:8px">PRICE</p>
      <h2 style="text-align:center;margin-bottom:12px">料金のご案内</h2>
      <p style="text-align:center;font-size:.92rem;margin-bottom:14px;background:#fdf9f0;border:1px solid #e3d5b8;border-radius:10px;padding:10px 14px;max-width:560px;margin-left:auto;margin-right:auto">お墓じまいをご検討の方へ：<a href="/grave/fukuoka/" style="color:#a8802f;font-weight:700">福岡の墓じまい専用ページ（基本プラン33万円）→</a></p>
      <p style="text-align:center;max-width:680px;margin:0 auto 28px;line-height:2;font-size:.95rem">
        料金はすべて税込です。金額は<strong>無料のお見積りで確定</strong>し、ご納得いただいてからのご契約となります。<br class="pc-only">
        <strong>あとから追加料金をいただくことはありません。</strong>
      </p>
      <div class="fk-price-sea">
        <div class="fk-price-sea__head">
          <p class="fk-price-sea__label">海洋散骨（海洋葬）</p>
          <p class="fk-price-sea__note">博多湾など福岡の海域／合同は姪浜旅客待合所から出航</p>
        </div>
        <ul class="fk-price-sea__list">
          <li><span>委託</span><strong>54,450円〜</strong><small>立ち会い不要・郵送OK</small></li>
          <li><span>合同</span><strong>148,500円〜</strong><small>数家族で乗り合わせ</small></li>
          <li><span>チャーター</span><strong>176,000円〜</strong><small>船を貸し切り</small></li>
        </ul>
        <p class="fk-price-sea__cta"><a href="/kaiyou-sou/fukuoka/" class="btn">福岡の海洋散骨について詳しく見る</a></p>
      </div>
      <div class="fk-price-etc">
        <?php
          $fk_etc = [
            ['粉骨（パウダー化）', '24,200円〜', '乳鉢を使いすべて手作業で丁寧に。真空パック＋桐箱でお返しします。郵送でのご利用も可能です。'],
            ['洗骨（クリーニング）', '27,500円〜', '長年の保管や墓じまいで汚れたご遺骨を、洗浄・殺菌・乾燥まで丁寧にクリーニングします。'],
            ['お墓じまい', '基本プラン 330,000円', '墓石の撤去から納骨まで一括対応。改葬許可申請（役所手続き）はオプション（25,000円〜）です。'],
            ['ペット海洋散骨', 'お問い合わせください', '大切な家族の一員の粉骨・海洋散骨・手元供養。内容に応じてお見積りいたします。'],
          ];
        ?>
        <?php foreach ($fk_etc as [$t, $p, $d]): ?>
          <div class="fk-price-etc__row">
            <h3><?= h($t) ?></h3>
            <p class="fk-price-etc__price"><?= h($p) ?></p>
            <p class="fk-price-etc__desc"><?= h($d) ?></p>
          </div>
        <?php endforeach; ?>
      </div>
      <p style="text-align:center;margin-top:20px;font-size:.85rem;color:var(--text-light)">
        ※ 海域や出港場所、ご遺骨の状態などにより金額が変わる場合があります。まずは無料のお見積りでご確認ください。
      </p>
      <div style="text-align:center;margin-top:16px">
        <a href="/contact/" class="btn">無料でお見積りを依頼する</a>
        <a href="/kaiyou-sou/fukuoka/" class="btn btn--outline" style="margin-left:10px">福岡の海洋散骨を詳しく見る</a>
      </div>
    </div>
  </section>
  <style>
    .fk-price-sea{background:#fff;border:1px solid var(--border);border-radius:14px;box-shadow:var(--shadow);padding:22px 24px}
    .fk-price-sea__head{text-align:center;margin-bottom:16px}
    .fk-price-sea__label{font-size:1.05rem;font-weight:700;color:var(--green-mid)}
    .fk-price-sea__note{font-size:.85rem;color:var(--text-light);margin-top:4px}
    .fk-price-sea__list{list-style:none;display:grid;grid-template-columns:repeat(3,1fr);gap:12px;padding:0;margin:0}
    .fk-price-sea__list li{background:var(--cream);border-radius:12px;padding:14px 16px;text-align:center}
    .fk-price-sea__list span{display:block;font-size:.82rem;font-weight:700;color:var(--green-mid);margin-bottom:2px}
    .fk-price-sea__list strong{display:block;font-size:1.25rem;color:var(--green);font-weight:700}
    .fk-price-sea__list small{display:block;font-size:.76rem;color:var(--text-light);margin-top:4px}
    .fk-price-sea__cta{text-align:center;margin-top:18px}
    .fk-price-etc{display:grid;grid-template-columns:repeat(2,1fr);gap:14px;margin-top:18px}
    .fk-price-etc__row{background:#fff;border:1px solid var(--border);border-radius:12px;padding:16px 20px}
    .fk-price-etc__row h3{font-size:.98rem;color:var(--green-mid);margin-bottom:4px}
    .fk-price-etc__price{color:var(--green);font-weight:700;margin-bottom:6px}
    .fk-price-etc__desc{font-size:.85rem;line-height:1.75;color:var(--text)}
    @media(max-width:860px){.fk-price-sea__list{grid-template-columns:1fr}.fk-price-etc{grid-template-columns:1fr}.fk-price-sea{padding:20px 18px}}
  </style>

  <!-- 他社と比較して、縁が選ばれる理由（TOPと共通） -->
  <section class="section fkc-comparison">
    <div class="container" style="max-width:960px">
      <p style="text-align:center;font-size:.78rem;letter-spacing:.28em;color:#b08b3e;font-weight:700;margin-bottom:8px">COMPARISON</p>
      <h2 style="text-align:center;margin-bottom:12px">他社と比較して、縁が選ばれる理由</h2>
      <p style="text-align:center;max-width:680px;margin:0 auto 32px;line-height:2;font-size:.95rem">同じ「ご供養サポート」でも、対応範囲・価格・サポート体制には大きな違いがあります。</p>
      <div class="fkc-table-wrap">
        <table class="fkc-table">
          <thead><tr><th>比較項目</th><th class="fkc-th-other">一般的な業者</th><th class="fkc-th-en">有限会社 縁 <span class="fkc-th-badge">おすすめ</span></th></tr></thead>
          <tbody>
            <tr><td>粉骨の料金</td><td class="fkc-td-other"><span class="fkc-icon">△</span>1万5,000円〜3万円が相場</td><td class="fkc-td-en"><span class="fkc-icon">◎</span><strong>24,200円〜</strong>の明瞭価格</td></tr>
            <tr><td>海洋散骨の料金</td><td class="fkc-td-other"><span class="fkc-icon">△</span>5万円〜15万円が一般的</td><td class="fkc-td-en"><span class="fkc-icon">◎</span><strong>54,450円〜</strong>で明瞭価格</td></tr>
            <tr><td>対応範囲</td><td class="fkc-td-other"><span class="fkc-icon">△</span>散骨のみ、粉骨のみなど<br>個別サービスが中心</td><td class="fkc-td-en"><span class="fkc-icon">◎</span>改葬・粉骨・散骨・納骨<br><strong>ワンストップ</strong>で完結</td></tr>
            <tr><td>墓じまいの<br>手続きサポート</td><td class="fkc-td-other"><span class="fkc-icon">✕</span>行政手続きは自己対応<br>または別途費用</td><td class="fkc-td-en"><span class="fkc-icon">◎</span>改葬許可申請〜撤去まで<br><strong>すべてサポート</strong></td></tr>
            <tr><td>資格・認定</td><td class="fkc-td-other"><span class="fkc-icon">△</span>無資格の業者も存在</td><td class="fkc-td-en"><span class="fkc-icon">◎</span>終活カウンセラー/散骨プロデューサー<br><strong>日本海洋散骨協会加盟</strong></td></tr>
            <tr><td>宗教・宗派</td><td class="fkc-td-other"><span class="fkc-icon">△</span>寺院系は宗派制限あり</td><td class="fkc-td-en"><span class="fkc-icon">◎</span><strong>宗教・宗派一切不問</strong></td></tr>
            <tr><td>遠方からの依頼</td><td class="fkc-td-other"><span class="fkc-icon">△</span>対面が必要な場合が多い</td><td class="fkc-td-en"><span class="fkc-icon">◎</span>ご遺骨の<strong>郵送受付OK</strong><br>委託散骨で立会い不要</td></tr>
            <tr><td>追加料金</td><td class="fkc-td-other"><span class="fkc-icon">✕</span>出張費・手数料等あり</td><td class="fkc-td-en"><span class="fkc-icon">◎</span><strong>追加料金なし</strong>の明瞭会計</td></tr>
            <tr><td>相談のしやすさ</td><td class="fkc-td-other"><span class="fkc-icon">△</span>電話・メールのみ</td><td class="fkc-td-en"><span class="fkc-icon">◎</span>電話・メール・<strong>LINE対応</strong></td></tr>
          </tbody>
        </table>
      </div>
      <p style="text-align:center;margin-top:18px;font-size:.78rem;color:var(--text-light)">※ 一般的な業者の情報は当社調べによる相場・傾向です。</p>
      <div style="text-align:center;margin-top:28px"><a href="/contact/" class="btn cta-sunset-fk">まずは無料で相談してみる</a></div>
      <style>.cta-sunset-fk{background:linear-gradient(135deg,#d8a24a,#c9822a) !important;border-color:transparent !important;color:#fff !important;box-shadow:0 6px 20px rgba(201,130,42,.35)}.cta-sunset-fk:hover{background:linear-gradient(135deg,#cc9640,#bb7722) !important;color:#fff !important}</style>
    </div>
  </section>
  <style>
    .fkc-comparison{background:#eef3f2}
    .fkc-table-wrap{max-width:900px;margin:0 auto;border-radius:14px;overflow-x:auto;background:#fff;border:1px solid rgba(39,92,88,.16);box-shadow:0 10px 30px rgba(20,40,50,.08)}
    .fkc-table{width:100%;min-width:640px;border-collapse:collapse}
    .fkc-table thead tr{background:#1f8fce}
    .fkc-table th{padding:16px 18px;font-size:.9rem;font-weight:600;color:#fff;text-align:center;vertical-align:middle;letter-spacing:.04em}
    .fkc-table th:first-child{text-align:left;width:26%;background:rgba(0,0,0,.12)}
    .fkc-table th.fkc-th-other{width:32%;background:rgba(0,0,0,.06);color:rgba(255,255,255,.75);font-weight:400}
    .fkc-table th.fkc-th-en{width:42%;background:rgba(169,134,95,.32)}
    .fkc-th-badge{display:inline-block;background:#b18e63;color:#fff;font-size:.58rem;font-weight:600;padding:2px 9px;border-radius:6px;margin-left:6px;vertical-align:middle}
    .fkc-table tbody tr{border-bottom:1px solid rgba(34,32,27,.1)}
    .fkc-table tbody tr:last-child{border-bottom:none}
    .fkc-table td{padding:16px 18px;font-size:.85rem;vertical-align:middle;line-height:1.75}
    .fkc-table td:first-child{font-weight:600;color:#1b413f;font-size:.88rem;background:rgba(246,241,232,.6);border-right:1px solid rgba(34,32,27,.1)}
    .fkc-td-other{text-align:center;color:#7a7668;border-right:1px solid rgba(34,32,27,.1)}
    .fkc-icon{display:block;font-size:1.15rem;margin-bottom:4px}
    .fkc-td-en{text-align:center;color:#1b413f;background:rgba(169,134,95,.05)}
    .fkc-td-en strong{color:#b18e63;font-weight:700}
    @media(max-width:768px){.fkc-table th,.fkc-table td{padding:12px 10px;font-size:.75rem}.fkc-th-badge{display:block;margin:4px auto 0}}
  </style>

  <!-- 選ばれる理由 -->
  <section class="section">
    <div class="container" style="max-width:860px">
      <h2 style="text-align:center;margin-bottom:30px">縁が選ばれる理由</h2>
      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:16px">
        <div class="card"><h3 style="margin-bottom:8px;color:var(--green)">追加料金のない明快な料金</h3><p style="font-size:.92rem">お見積りは無料。金額はお見積りで確定し、あとから追加料金をいただくことはありません。</p></div>
        <div class="card"><h3 style="margin-bottom:8px;color:var(--green)">急かさない・押し付けない</h3><p style="font-size:.92rem">宗教・宗派を問わず中立の立場で、良さも注意点も丁寧にご説明。「話を聞くだけ」でも歓迎です。</p></div>
        <div class="card"><h3 style="margin-bottom:8px;color:var(--green)">最初から最後まで一貫対応</h3><p style="font-size:.92rem">ご遺骨のお引き取りから粉骨・散骨・その後の供養まで、一つの窓口でお手伝いします。</p></div>
        <div class="card"><h3 style="margin-bottom:8px;color:var(--green)">散骨後も、会いに行ける</h3><p style="font-size:.92rem">散骨海域を再訪するメモリアルクルーズや、お手元供養など、「その後」のご供養もお手伝いします。</p></div>
      </div>
      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:14px;margin-top:26px">
        <img src="/fukuoka/images/fk-sea-flowers.jpg?v=<?= h(asset_ver()) ?>" alt="花びらが広がる海と散骨セレモニーを行う船上のスタッフ" width="1200" height="800" loading="lazy" style="width:100%;aspect-ratio:3/2;object-fit:cover;border-radius:12px;box-shadow:0 6px 18px rgba(0,0,0,.1)">
        <img src="/fukuoka/images/fk-sankotsu.jpg?v=<?= h(asset_ver()) ?>" alt="海へご遺骨を還す散骨の様子" width="1200" height="800" loading="lazy" style="width:100%;aspect-ratio:3/2;object-fit:cover;border-radius:12px;box-shadow:0 6px 18px rgba(0,0,0,.1)">
      </div>
    </div>
  </section>

  <!-- お客様の声 -->
  <section class="section" style="background:var(--cream)">
    <div class="container" style="max-width:960px">
      <p style="text-align:center;font-size:.78rem;letter-spacing:.28em;color:#b08b3e;font-weight:700;margin-bottom:8px">VOICE</p>
      <h2 style="text-align:center;margin-bottom:14px">お客様の声</h2>
      <p style="text-align:center;color:var(--text-light);font-size:.95rem;margin-bottom:22px">船上で、故人さまへのお手紙を書かれるご家族。<br class="sp-only">その想いのそばに、私たちはいます。</p>
      <img src="/fukuoka/images/fk-koe.jpg?v=<?= h(asset_ver()) ?>" alt="船上で故人さまへのメッセージカードを書くご家族" width="1200" height="800" loading="lazy"
           style="display:block;width:100%;max-width:680px;margin:0 auto 28px;aspect-ratio:3/2;object-fit:cover;border-radius:16px;box-shadow:0 10px 30px rgba(0,0,0,.12)">
      <?php if ($fk_voices): ?>
      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:18px">
        <?php foreach ($fk_voices as $v): ?>
        <div class="card" style="display:flex;flex-direction:column">
          <p style="display:inline-block;align-self:flex-start;background:var(--sea-light);color:var(--green);font-size:.72rem;font-weight:600;padding:3px 10px;border-radius:999px"><?= h($v['service'] ?? '') ?></p>
          <h3 style="margin:12px 0 10px;line-height:1.6;font-size:1rem">「<?= h($v['title'] ?? '') ?>」</h3>
          <?php if (!empty($v['impression'])): ?><p style="font-size:.88rem;line-height:1.9;flex:1"><?= h(mb_strimwidth(preg_replace('/\s+/u', ' ', (string)$v['impression']), 0, 150, '…')) ?></p><?php endif; ?>
          <p style="text-align:right;font-size:.8rem;color:var(--text-light);margin-top:12px">（<?= h($v['who'] ?? '') ?>）</p>
        </div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
      <div style="display:flex;flex-wrap:wrap;align-items:center;justify-content:center;gap:14px;margin-top:26px">
        <p style="font-size:1.1rem;font-weight:700;color:#f4b400;margin:0">★4.9 <span style="font-size:.8rem;color:var(--text-light);font-weight:400">Google口コミ評価</span></p>
        <a href="/voice/" class="btn btn--outline" style="font-size:.9rem">お客様の声をもっと見る</a>
        <a href="<?= h($FUK_REVIEW) ?>" target="_blank" rel="noopener" style="color:var(--green);font-weight:700;text-decoration:underline;font-size:.9rem">口コミを書く →</a>
      </div>
    </div>
  </section>

  <!-- よくあるご質問 -->
  <?php
    /* 海洋散骨の詳細FAQ（博多湾・出航場所・法律・料金の内訳など）は
       /kaiyou-sou/fukuoka/ に集約した。ここは営業所・エリア・相談方法に絞る。 */
    $fk_faq = [
      ['q' => '福岡営業所ではどのような相談ができますか？',
       'a' => '海洋散骨（海洋葬）、お墓じまい、粉骨・洗骨、海洋散骨の生前契約、ペット供養のご相談を承っています。福岡市中央区春吉にございますので、資料を見ながらゆっくりお話しいただけます。ご相談・お見積りは無料で、こちらから営業のご連絡はいたしません。'],
      ['q' => '予約は必要ですか？電話やLINEだけでも相談できますか？',
       'a' => 'ご来所いただく場合は、確実にご案内できるよう事前にお電話（090-5000-4825）またはLINEでご連絡ください。ご来所いただかなくても、お電話・LINE・メールフォームだけでご相談からお見積りまで進められます。'],
      ['q' => '福岡のどのエリアまで対応していますか？',
       'a' => '福岡市内全域（東・博多・中央・南・城南・早良・西区）、北九州エリア、筑後エリア（久留米・大牟田・柳川など）、筑豊エリア（飯塚・田川など）を含む福岡県内全域に対応しています。佐賀・熊本・大分などの隣県もご相談ください。'],
      ['q' => '福岡県外に住んでいますが、依頼できますか？',
       'a' => 'ご依頼いただけます。ご相談はお電話・LINE・メールで完結し、ご遺骨はゆうパックでのご郵送でお預かりできます。立ち会い不要の委託海洋散骨（54,450円〜）なら、帰省せずにすべてお任せいただけます。'],
      ['q' => '費用はどのくらいかかりますか？',
       'a' => '海洋散骨は委託54,450円〜・合同148,500円〜・チャーター176,000円〜、粉骨は24,200円〜、洗骨は27,500円〜、お墓じまいは基本プラン330,000円（いずれも税込）です。金額は無料のお見積りで確定し、ご納得いただいてからのご契約となりますので、あとから追加料金をいただくことはありません。'],
      ['q' => '福岡の海洋散骨について、もっと詳しく知りたいのですが。',
       'a' => '博多湾での散骨海域、姪浜からの出航、3つのプランの違い、ご相談から散骨証明書までの流れ、法律上の扱いや業者選びのポイントまで、福岡の海洋散骨専用ページにまとめています。',
       'link' => ['/kaiyou-sou/fukuoka/', '福岡の海洋散骨について詳しく見る']],
      ['q' => 'お墓じまいから海洋散骨までまとめて頼めますか？',
       'a' => 'はい。墓石の撤去から納骨まで一括対応するお墓じまい（基本プラン330,000円・税込）と、取り出したご遺骨の洗骨・粉骨・海洋散骨までワンストップで承ります。改葬許可申請（役所手続き）のサポートはオプション（25,000円〜）でご利用いただけます。',
       'link' => ['/grave/fukuoka/', '福岡の墓じまいについて詳しく見る']],
      ['q' => '生前に自分の散骨を申し込んでおくことはできますか？',
       'a' => '承れます。福岡営業所でも生前契約のご相談を受け付けています。ご家族とよく話し合ったうえで、遺言書やエンディングノートに残しておくことをおすすめします。',
       'link' => ['/seizen/', '海洋散骨 生前契約について詳しく見る']],
    ];
  ?>
  <section class="section">
    <div class="container" style="max-width:820px">
      <h2 style="text-align:center;margin-bottom:8px">よくあるご質問</h2>
      <p style="text-align:center;font-size:.9rem;color:var(--text-light);margin-bottom:24px">福岡営業所とご相談方法についてのご質問です。海洋散骨そのものについては<a href="/kaiyou-sou/fukuoka/" style="color:var(--green);text-decoration:underline">福岡の海洋散骨ページ</a>、墓じまいは<a href="/grave/fukuoka/" style="color:var(--green);text-decoration:underline">福岡の墓じまいページ</a>をご覧ください。</p>
      <?php foreach ($fk_faq as $f): ?>
        <details style="background:var(--white);border:1px solid var(--border);border-radius:10px;padding:16px 20px;margin-bottom:12px">
          <summary style="font-weight:600;cursor:pointer;color:var(--green-mid)">Q. <?= h($f['q']) ?></summary>
          <p style="margin-top:10px;font-size:.95rem;line-height:1.9">A. <?= h($f['a']) ?><?php if (!empty($f['link'])): ?> <a href="<?= h($f['link'][0]) ?>" style="color:var(--green);text-decoration:underline"><?= h($f['link'][1]) ?> →</a><?php endif; ?></p>
        </details>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- 営業所案内 -->
  <section class="section" style="background:var(--cream)">
    <div class="container" style="max-width:860px">
      <h2 style="text-align:center;margin-bottom:26px">福岡営業所のご案内</h2>
      <img src="/fukuoka/images/fk-staff.jpg?v=<?= h(asset_ver()) ?>" alt="福岡の港で笑顔で迎えるスタッフ" width="900" height="600" loading="lazy"
           style="display:block;width:100%;max-width:560px;margin:0 auto 24px;aspect-ratio:3/2;object-fit:cover;border-radius:14px;box-shadow:0 8px 24px rgba(0,0,0,.12)">
      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:22px;align-items:start">
        <div class="card">
          <p style="font-weight:700;font-size:1.05rem;margin-bottom:10px"><?= h(SITE['name']) ?> <?= h($FUK['name']) ?></p>
          <p style="font-size:.95rem;line-height:2">〒<?= h($FUK['zip']) ?><br><?= h($FUK['address']) ?></p>
          <p style="margin-top:12px"><a href="tel:<?= h(str_replace('-', '', $FUK['tel'])) ?>" style="font-size:1.5rem;font-weight:700;color:var(--green-mid);text-decoration:none"><?= h($FUK['tel']) ?></a></p>
          <p style="font-size:.85rem;color:var(--text-light)"><?= h(SITE['hours_jp']) ?>／メール・LINEは24時間受付</p>
          <p style="margin-top:14px;font-size:.9rem">
            <a href="<?= h($FUK_MAP) ?>" target="_blank" rel="noopener" style="color:var(--green);font-weight:700;text-decoration:underline">Googleマップで見る →</a><br>
            <a href="<?= h($FUK_REVIEW) ?>" target="_blank" rel="noopener" style="color:var(--green);font-weight:700;text-decoration:underline">口コミを書く →</a>
          </p>
        </div>
        <iframe src="https://maps.google.com/maps?q=<?= rawurlencode('福岡県福岡市中央区春吉2丁目1-3') ?>&z=16&output=embed" width="100%" height="300" style="border:0;border-radius:12px" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="有限会社 縁 福岡営業所"></iframe>
      </div>
      <p style="text-align:center;margin-top:18px;font-size:.9rem;color:var(--text-light)">本社（鹿児島）：〒<?= h(SITE['zip']) ?> <?= h(SITE['address']) ?>（TEL <?= h(SITE['tel']) ?>）</p>
    </div>
  </section>

  <!-- CTA -->
  <section class="section" style="background:linear-gradient(135deg,var(--green),var(--green-mid));color:#fff;text-align:center">
    <div class="container">
      <h2 style="color:#fff">福岡でのご供養、まずはお話をお聞かせください</h2>
      <p style="opacity:.92;margin-bottom:22px">「まだ決めていない」「話を聞くだけ」でも大歓迎です。ご相談・お見積りは無料です。</p>
      <p style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap">
        <a href="tel:<?= h(str_replace('-', '', $FUK['tel'])) ?>" class="btn" style="background:#fff;color:var(--green-mid)">電話で相談（<?= h($FUK['tel']) ?>）</a>
        <a href="/contact/" class="btn" style="background:#d8b46a;color:#1c2b33">メールで相談・資料請求</a>
      </p>
    </div>
  </section>
</main>

<script type="application/ld+json">
{
  "@context":"https://schema.org",
  "@type":"BreadcrumbList",
  "itemListElement":[
    {"@type":"ListItem","position":1,"name":"ホーム","item":"https://en1150.co.jp/"},
    {"@type":"ListItem","position":2,"name":"福岡営業所","item":"https://en1150.co.jp/fukuoka/"}
  ]
}
</script>
<script type="application/ld+json">
{
  "@context":"https://schema.org",
  "@type":"LocalBusiness",
  "name":"有限会社 縁 福岡営業所",
  "url":"https://en1150.co.jp/fukuoka/",
  "telephone":"+81-90-5000-4825",
  "address":{"@type":"PostalAddress","postalCode":"810-0003","addressRegion":"福岡県","addressLocality":"福岡市中央区","streetAddress":"春吉2丁目1-3 2F","addressCountry":"JP"},
  "hasMap":"https://maps.google.com/?cid=1235913108976072113",
  "parentOrganization":{"@id":"https://en1150.co.jp/#organization"},
  "areaServed":[{"@type":"State","name":"福岡県"},{"@type":"AdministrativeArea","name":"北部九州"}],
  "openingHoursSpecification":[{"@type":"OpeningHoursSpecification","dayOfWeek":["Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"],"opens":"09:00","closes":"18:00"}],
  "description":"福岡市中央区春吉のご供養相談窓口。海洋散骨・お墓じまい・粉骨・洗骨・生前契約・ペット供養を、福岡県内全域で承ります。"
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
  ], $fk_faq),
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>

</script>
<?php require __DIR__ . '/../includes/footer.php'; ?>
