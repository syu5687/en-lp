<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../admin/includes/store.php'; // news_published() / voices_published()（キャッシュ済み・読み取り増なし）

/* ---- 海洋散骨レポート（ブログ「海洋葬(海洋散骨)」カテゴリの最新3件） ---- */
$fk_reports = [];
try {
  $cat_alias  = ['海洋葬' => '海洋葬(海洋散骨)', '海洋散骨' => '海洋葬(海洋散骨)'];
  $split_cats = fn(?string $s): array =>
    array_map(fn($c) => $cat_alias[$c] ?? $c,
      array_values(array_filter(array_map('trim', preg_split('/[、,\/／]/u', (string)$s)))));
  foreach (news_published() as $it) {
    if (in_array('海洋葬(海洋散骨)', $split_cats($it['category'] ?? ''), true)) {
      $fk_reports[] = $it;
      if (count($fk_reports) >= 3) break;
    }
  }
} catch (Throwable $e) { $fk_reports = []; }

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

$page_title     = '福岡の海洋散骨・粉骨・お墓じまい｜有限会社 縁 福岡営業所';
$page_desc      = '福岡で海洋散骨・粉骨・お墓じまい・生前契約のご相談なら、有限会社 縁 福岡営業所（福岡市中央区春吉）へ。博多湾など福岡の海域での散骨に対応。鹿児島・福岡を中心に全国3,800件以上の実績、日本海洋散骨協会加盟。ご相談・お見積り無料。';
$page_canonical = SITE['url'] . '/fukuoka/';
$page_hero_image = '/assets/img/hero-kaiyou-sou.jpg';
require __DIR__ . '/../includes/head.php';
$FUK = SITE['fukuoka'];
$FUK_MAP = 'https://maps.google.com/?cid=1235913108976072113';
$FUK_REVIEW = 'https://g.page/r/CbF1xKls2CYREBM/review';
?>
<body>
<?php require __DIR__ . '/../includes/header.php'; ?>
<section class="page-hero">
  <h1>福岡の海洋散骨・粉骨・お墓じまい</h1>
  <p>有限会社 縁 福岡営業所（福岡市中央区春吉）</p>
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
      <p class="lead" style="line-height:2.1">「海に還りたい」という想いに、福岡でもお応えします。<br>
      有限会社 縁は<strong>福岡営業所（福岡市中央区春吉）</strong>を拠点に、<br class="pc-only">
      海洋散骨・粉骨・お墓じまい・生前契約のご相談を承っています。</p>
      <img src="/fukuoka/images/fk-port.jpg?v=<?= h(asset_ver()) ?>" alt="福岡の港に停泊する海洋散骨のクルーズ船" width="1600" height="1067" loading="lazy"
           style="width:100%;aspect-ratio:16/9;object-fit:cover;border-radius:16px;box-shadow:0 10px 30px rgba(0,0,0,.12);margin-top:28px">
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
          ['href' => '/kaiyou-sou/',      'img' => '/assets/img/svc-kaiyou.jpg',          'alt' => '海洋散骨セレモニーで花びらが広がる海',      'w' => 1200, 'h' => 750,  'title' => '海洋散骨（海洋葬）',   'desc' => '博多湾など福岡の海域での散骨に対応。チャーター・合同・委託（立ち会い不要）の3プラン。緯度・経度入りの散骨証明書を発行します。'],
          ['href' => '/seizen/',          'img' => '/seizen/images/omoi-boat.webp',       'alt' => '海洋散骨の生前契約を託すクルーズ船',        'w' => 1200, 'h' => 800,  'title' => '海洋散骨 生前契約',    'desc' => '「海洋散骨をしたい」という想いを生前に契約して託せます。テレビでも紹介された、福岡対応のサービスです。'],
          ['href' => '/powder-cleaning/', 'img' => '/assets/img/svc-funkotsu.jpg',        'alt' => 'ご遺骨を丁寧にパウダー化する粉骨作業',      'w' => 1200, 'h' => 750,  'title' => '粉骨・洗骨',           'desc' => 'ご遺骨のパウダー化（24,200円〜）・クリーニング。お持ち込みのご相談のほか、郵送でもご利用いただけます。'],
          ['href' => '/grave/',           'img' => '/assets/img/hero-grave.jpg',          'alt' => '手を合わせてお参りするお墓',                'w' => 2000, 'h' => 1333, 'title' => 'お墓じまい',           'desc' => '撤去から納骨まで一括対応。改葬の行政手続きの代行も承ります。まずは現状をお聞かせください。'],
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

  <!-- 料金のご案内 -->
  <section class="section" id="price" style="background:var(--cream)">
    <div class="container" style="max-width:960px">
      <p style="text-align:center;font-size:.78rem;letter-spacing:.28em;color:#b08b3e;font-weight:700;margin-bottom:8px">PRICE</p>
      <h2 style="text-align:center;margin-bottom:12px">料金のご案内</h2>
      <p style="text-align:center;max-width:680px;margin:0 auto 28px;line-height:2;font-size:.95rem">
        料金はすべて税込です。金額は<strong>無料のお見積りで確定</strong>し、ご納得いただいてからのご契約となります。<br class="pc-only">
        <strong>あとから追加料金をいただくことはありません。</strong>
      </p>
      <div class="fk-price-plans">
        <?php
          $fk_plans = [
            ['name' => '委託海洋散骨',       'price' => '54,450',  'unit' => '円〜', 'img' => '/assets/img/plan-itaku.jpg',   'badge' => '期間限定価格（通常66,000円）',
             'desc' => 'ご遺族様に代わり、スタッフが心を込めて散骨します。立ち会い不要・ご遺骨の郵送OKで、全国からご利用いただけます。'],
            ['name' => '合同海洋散骨',       'price' => '148,500', 'unit' => '円〜', 'img' => '/assets/img/plan-goudou.jpg',  'badge' => null,
             'desc' => '複数のご遺族様で乗り合わせて行う海洋散骨です。費用を抑えながら、船上でのお見送りに立ち会えます。'],
            ['name' => 'チャーター海洋散骨', 'price' => '176,000', 'unit' => '円〜', 'img' => '/assets/img/plan-charter.jpg', 'badge' => null,
             'desc' => '船を貸し切り、ご遺族様やご友人など親しい方だけでゆっくりとお見送りいただけるプランです。'],
          ];
        ?>
        <?php foreach ($fk_plans as $pl): ?>
          <div class="fk-price-plan">
            <span style="display:block;aspect-ratio:16/9;overflow:hidden;background:#eef5f8">
              <img src="<?= h($pl['img']) ?>?v=<?= h(asset_ver()) ?>" alt="<?= h($pl['name']) ?>のイメージ" width="1200" height="675" loading="lazy" style="width:100%;height:100%;object-fit:cover;display:block">
            </span>
            <div class="fk-price-plan__body">
              <h3><?= h($pl['name']) ?></h3>
              <p class="fk-price-plan__price"><span><?= h($pl['price']) ?></span><?= h($pl['unit']) ?><small>（税込）</small></p>
              <?php if ($pl['badge']): ?><p class="fk-price-plan__badge"><?= h($pl['badge']) ?></p><?php endif; ?>
              <p class="fk-price-plan__desc"><?= h($pl['desc']) ?></p>
            </div>
          </div>
        <?php endforeach; ?>
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
        <a href="/kaiyou-sou/" class="btn btn--outline" style="margin-left:10px">海洋散骨のプランを詳しく見る</a>
      </div>
    </div>
  </section>
  <style>
    .fk-price-plans{display:grid;grid-template-columns:repeat(3,1fr);gap:18px}
    .fk-price-plan{background:#fff;border:1px solid var(--border);border-radius:14px;overflow:hidden;box-shadow:var(--shadow);display:flex;flex-direction:column}
    .fk-price-plan__body{padding:18px 20px 20px;display:flex;flex-direction:column;flex:1}
    .fk-price-plan__body h3{color:var(--green-mid);font-size:1.05rem;margin-bottom:6px}
    .fk-price-plan__price{color:var(--green);font-weight:700;margin-bottom:6px}
    .fk-price-plan__price span{font-size:1.7rem}
    .fk-price-plan__price small{font-size:.75rem;color:var(--text-light);font-weight:400;margin-left:2px}
    .fk-price-plan__badge{display:inline-block;align-self:flex-start;background:#d8b46a;color:#1c2b33;font-size:.72rem;font-weight:700;padding:3px 10px;border-radius:999px;margin-bottom:8px}
    .fk-price-plan__desc{font-size:.88rem;line-height:1.8}
    .fk-price-etc{display:grid;grid-template-columns:repeat(2,1fr);gap:14px;margin-top:18px}
    .fk-price-etc__row{background:#fff;border:1px solid var(--border);border-radius:12px;padding:16px 20px}
    .fk-price-etc__row h3{font-size:.98rem;color:var(--green-mid);margin-bottom:4px}
    .fk-price-etc__price{color:var(--green);font-weight:700;margin-bottom:6px}
    .fk-price-etc__desc{font-size:.85rem;line-height:1.75;color:var(--text)}
    @media(max-width:860px){.fk-price-plans{grid-template-columns:1fr}.fk-price-etc{grid-template-columns:1fr}}
  </style>

  <!-- 価格だけで選ばないで（比較チェックポイント） -->
  <section class="section" style="background:var(--white)">
    <div class="container" style="max-width:960px">
      <h2 style="text-align:center;margin-bottom:14px">料金の安さだけで選ばないでください</h2>
      <p style="text-align:center;max-width:720px;margin:0 auto 26px;line-height:2">
        福岡でも、格安をうたう散骨サービスが増えています。しかし「実際にどの海域で散骨されたのかわからない」「証明書が発行されない」「あとから追加料金を請求された」——そんなケースも報告されています。<br>
        大切な方をお見送りする一度きりのご供養だからこそ、<strong>他社さまとご比較の際は次のポイント</strong>をご確認ください。
      </p>
      <div class="fk-quality-list">
        <?php
          $fk_quality = [
            ['協会加盟', '日本海洋散骨協会の加盟事業者。ガイドラインと海域ルールを順守します。'],
            ['丁寧な粉骨', 'ご遺骨は一件ずつ丁寧にパウダー化。真空パック・桐箱でのお返しにも対応。'],
            ['散骨証明書', '緯度・経度入りの証明書と当日のお写真をお届け。お見送りがかたちで残ります。'],
            ['追加料金なし', '金額は無料見積りで確定。ご納得いただいてからのご契約です。'],
            ['アフターサポート', 'メモリアルクルーズや手元供養など、散骨後のご供養まで一貫対応。'],
            ['実績', '鹿児島・福岡を中心に全国3,800件以上・10年以上、Google口コミ★4.9。'],
          ];
        ?>
        <?php foreach ($fk_quality as [$t, $d]): ?>
          <div class="fk-quality-item">
            <h3><span>✓</span><?= h($t) ?></h3>
            <p><?= h($d) ?></p>
          </div>
        <?php endforeach; ?>
      </div>
      <p style="text-align:center;margin-top:20px;font-size:.9rem;color:var(--text-light)">「見積りだけ」「話を聞くだけ」でも歓迎です。どうぞ納得のいくまでご比較ください。</p>
    </div>
  </section>
  <style>
    .fk-quality-list{display:grid;grid-template-columns:repeat(3,1fr);gap:14px}
    .fk-quality-item{background:var(--cream);border:1px solid var(--border);border-radius:12px;padding:16px 18px}
    .fk-quality-item h3{font-size:.98rem;color:var(--green-mid);margin-bottom:6px;display:flex;align-items:center;gap:8px}
    .fk-quality-item h3 span{width:22px;height:22px;border-radius:50%;background:var(--green);color:#fff;display:grid;place-items:center;font-size:.75rem;flex:none}
    .fk-quality-item p{font-size:.85rem;line-height:1.8}
    @media(max-width:860px){.fk-quality-list{grid-template-columns:repeat(2,1fr)}}
    @media(max-width:540px){.fk-quality-list{grid-template-columns:1fr}}
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
      <div style="text-align:center;margin-top:28px"><a href="/contact/" class="btn">まずは無料で相談してみる</a></div>
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

  <!-- 安心・信頼・安全 -->
  <section class="section" style="background:linear-gradient(180deg,#f2f8fa,#e8f2f6)">
    <div class="container" style="max-width:960px">
      <p style="text-align:center;font-size:.78rem;letter-spacing:.28em;color:#b08b3e;font-weight:700;margin-bottom:8px">PROMISE</p>
      <h2 style="text-align:center;margin-bottom:10px">縁がお約束する「安心・信頼・安全」</h2>
      <p style="text-align:center;color:var(--text-light);font-size:.95rem;margin-bottom:30px">大切な方をお任せいただくからこそ。<br class="sp-only">福岡でも変わらない、縁の基準です。</p>
      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:18px">
        <div class="card" style="padding:0;overflow:hidden;display:flex;flex-direction:column">
          <span style="display:block;aspect-ratio:16/10;overflow:hidden"><img src="/fukuoka/images/fk-staff.jpg?v=<?= h(asset_ver()) ?>" alt="笑顔でご相談を迎えるスタッフ" width="900" height="600" loading="lazy" style="width:100%;height:100%;object-fit:cover;display:block"></span>
          <div style="padding:20px 22px 22px;flex:1">
            <p style="display:inline-block;background:var(--green);color:#fff;font-size:.75rem;font-weight:700;letter-spacing:.15em;padding:3px 14px;border-radius:999px;margin-bottom:10px">安心</p>
            <h3 style="margin-bottom:8px;font-size:1.05rem">寄り添う、専門スタッフ</h3>
            <p style="font-size:.9rem;line-height:1.9">事前のご相談から当日、アフターケアまで専門スタッフが寄り添い丁寧に対応。宗教・宗派を問わず、「話を聞くだけ」のご相談も歓迎です。</p>
          </div>
        </div>
        <div class="card" style="padding:0;overflow:hidden;display:flex;flex-direction:column">
          <span style="display:block;aspect-ratio:16/10;overflow:hidden"><img src="/fukuoka/images/fk-sea-flowers.jpg?v=<?= h(asset_ver()) ?>" alt="花びらが広がる海と散骨セレモニーの船上" width="1200" height="800" loading="lazy" style="width:100%;height:100%;object-fit:cover;display:block"></span>
          <div style="padding:20px 22px 22px;flex:1">
            <p style="display:inline-block;background:var(--green);color:#fff;font-size:.75rem;font-weight:700;letter-spacing:.15em;padding:3px 14px;border-radius:999px;margin-bottom:10px">信頼</p>
            <h3 style="margin-bottom:8px;font-size:1.05rem">実績3,800件以上・口コミ★4.9</h3>
            <p style="font-size:.9rem;line-height:1.9">鹿児島で最初に海洋葬へ取り組み10年以上。鹿児島・福岡を中心に全国3,800件以上の実績と、Google口コミ★4.9の評価をいただいています。</p>
          </div>
        </div>
        <div class="card" style="padding:0;overflow:hidden;display:flex;flex-direction:column">
          <span style="display:block;aspect-ratio:16/10;overflow:hidden"><img src="/fukuoka/images/fk-kensui.jpg?v=<?= h(asset_ver()) ?>" alt="安全に配慮しながら行う船上セレモニー" width="900" height="600" loading="lazy" style="width:100%;height:100%;object-fit:cover;display:block"></span>
          <div style="padding:20px 22px 22px;flex:1">
            <p style="display:inline-block;background:var(--green);color:#fff;font-size:.75rem;font-weight:700;letter-spacing:.15em;padding:3px 14px;border-radius:999px;margin-bottom:10px">安全</p>
            <h3 style="margin-bottom:8px;font-size:1.05rem">協会ルールを順守した運航</h3>
            <p style="font-size:.9rem;line-height:1.9">日本海洋散骨協会の加盟事業者として、散骨海域の選定や環境への配慮などルールを順守。天候・海況を見極め、安全第一の運航を行います。</p>
            <p style="margin-top:12px"><span style="display:inline-flex;align-items:center;gap:10px;background:#fff;border:1px solid var(--border);border-radius:10px;padding:8px 14px"><img src="/assets/img/jmas-logo.png?v=<?= h(asset_ver()) ?>" alt="一般社団法人 日本海洋散骨協会 ロゴ" width="360" height="454" loading="lazy" style="width:36px;height:auto"><span style="font-size:.74rem;line-height:1.6;color:#4a5a58">一般社団法人<br><strong style="color:#2a5a7a">日本海洋散骨協会</strong> 加盟事業者</span></span></p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- 実施予定日（管理画面から更新・福岡開催のみ表示） -->
  <?php $gd_filter = '福岡'; $gd_area_label = '福岡'; require __DIR__ . '/../includes/goudou-schedule.php'; ?>

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

  <!-- 海洋散骨レポート -->
  <?php if ($fk_reports): ?>
  <section class="section">
    <div class="container" style="max-width:960px">
      <p style="text-align:center;font-size:.78rem;letter-spacing:.28em;color:#b08b3e;font-weight:700;margin-bottom:8px">REPORT</p>
      <h2 style="text-align:center;margin-bottom:10px">海洋散骨レポート</h2>
      <p style="text-align:center;color:var(--text-light);font-size:.95rem;margin-bottom:28px">実際の海洋散骨の様子を、ブログでご紹介しています。<br class="sp-only">当日の雰囲気づくりの参考にご覧ください。</p>
      <div class="card-grid">
        <?php foreach ($fk_reports as $it): ?>
        <a class="card" href="/blog/?id=<?= h(rawurlencode($it['id'] ?? '')) ?>" style="display:flex;flex-direction:column;padding:0;overflow:hidden">
          <?php if (!empty($it['image'])): ?>
            <span style="display:block;aspect-ratio:16/9;overflow:hidden;background:#eef5f8"><img src="<?= h($it['image']) ?>" alt="<?= h($it['title'] ?? '') ?>" loading="lazy" style="width:100%;height:100%;object-fit:cover;display:block" onerror="var t=this.parentNode;if(t)t.remove()"></span>
          <?php endif; ?>
          <span style="display:flex;flex-direction:column;padding:18px 20px;flex:1">
            <p style="font-size:.8rem;color:var(--text-light)"><?= h($it['date'] ?? '') ?> ・ 海洋葬(海洋散骨)</p>
            <h3 style="font-size:1rem;line-height:1.7"><?= h($it['title'] ?? '') ?></h3>
            <?php if (!empty($it['body'])): ?><p style="font-size:.88rem;flex:1;margin-top:6px"><?= h(mb_strimwidth(preg_replace('/\s+/u', ' ', strip_tags((string)$it['body'])), 0, 76, '…')) ?></p><?php endif; ?>
            <span style="margin-top:12px;align-self:flex-start;color:var(--green);font-weight:600;font-size:.85rem">詳しく読む →</span>
          </span>
        </a>
        <?php endforeach; ?>
      </div>
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

  <!-- 県外にお住まいの方へ -->
  <section class="section" id="kengai" style="background:linear-gradient(180deg,#f4f9fb,#e9f3f7)">
    <div class="container" style="max-width:960px">
      <p style="text-align:center;font-size:.78rem;letter-spacing:.28em;color:#b08b3e;font-weight:700;margin-bottom:8px">NATIONWIDE</p>
      <h2 style="text-align:center;margin-bottom:14px">県外にお住まいの方へ</h2>
      <p style="text-align:center;max-width:720px;margin:0 auto 26px;line-height:2">
        「実家が福岡にある」「故郷の海に還してあげたい」——<br class="pc-only">
        そんな方のために、<strong>帰省しなくてもご利用いただける委託海洋葬（54,450円〜）</strong>をご用意しています。<br class="pc-only">
        ご遺骨はゆうパックでのご郵送でお預かりし、粉骨から散骨、証明書のお届けまで当社がすべて代行。<strong>全国どこにお住まいでもご利用いただけます。</strong>
      </p>
      <div style="display:flex;flex-wrap:wrap;gap:8px;justify-content:center;margin-bottom:30px">
        <span style="background:#fff;border:1px solid var(--border);border-radius:999px;padding:6px 16px;font-size:.85rem;font-weight:700;color:var(--green-mid)">帰省・立ち会い不要</span>
        <span style="background:#fff;border:1px solid var(--border);border-radius:999px;padding:6px 16px;font-size:.85rem;font-weight:700;color:var(--green-mid)">ご遺骨は郵送でOK</span>
        <span style="background:#fff;border:1px solid var(--border);border-radius:999px;padding:6px 16px;font-size:.85rem;font-weight:700;color:var(--green-mid)">お墓じまいからワンストップ</span>
        <span style="background:#fff;border:1px solid var(--border);border-radius:999px;padding:6px 16px;font-size:.85rem;font-weight:700;color:var(--green-mid)">散骨証明書を発行</span>
      </div>
      <div class="fk-kengai-steps">
        <?php
          $fk_kengai = [
            ['お電話・LINE・メールでご相談', '全国からご相談いただけます。ご事情やご希望をうかがい、お見積りを無料でご案内します。'],
            ['ご遺骨をゆうパックでご郵送', '梱包の方法や送り方は、写真付きの資料でわかりやすくご案内。日本郵便のゆうパックで安全にお送りいただけます。'],
            ['粉骨〜海洋散骨を当社が代行', '協会ルールに沿って丁寧に粉骨し、博多湾など福岡の海域で心を込めて散骨いたします。'],
            ['証明書とお写真をお届け', '散骨海域の緯度・経度入りの散骨証明書と、当日のセレモニーのお写真をご自宅へお届けします。'],
          ];
        ?>
        <?php foreach ($fk_kengai as $i => [$t, $d]): ?>
          <div class="fk-kengai-step">
            <div class="fk-kengai-step__num"><?= $i + 1 ?></div>
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
    .fk-kengai-steps{display:grid;grid-template-columns:repeat(4,1fr);gap:14px}
    .fk-kengai-step{background:#fff;border:1px solid var(--border);border-radius:14px;padding:20px 18px;box-shadow:var(--shadow);text-align:center}
    .fk-kengai-step__num{width:40px;height:40px;border-radius:50%;background:var(--green);color:#fff;display:grid;place-items:center;font-weight:700;font-size:1.05rem;margin:0 auto 12px}
    .fk-kengai-step h3{font-size:.98rem;color:var(--green-mid);margin-bottom:8px;line-height:1.5}
    .fk-kengai-step p{font-size:.85rem;line-height:1.8;text-align:left}
    @media(max-width:900px){.fk-kengai-steps{grid-template-columns:repeat(2,1fr)}}
    @media(max-width:520px){.fk-kengai-steps{grid-template-columns:1fr}}
  </style>

  <!-- よくあるご質問 -->
  <?php
    $fk_faq = [
      ['q' => '博多湾など、福岡の海で散骨できますか？',
       'a' => 'はい。博多湾をはじめ福岡の海域での海洋散骨に対応しています。故人様やご家族にゆかりのある海でのお見送りをご希望の場合も、海域についてお気軽にご相談ください。'],
      ['q' => '福岡営業所ではどのような相談ができますか？',
       'a' => '海洋散骨（チャーター・合同・委託）、粉骨・洗骨、お墓じまい、生前契約、ペット供養のご相談を対面で承っています。福岡市中央区春吉にございますので、資料を見ながらゆっくりお話しいただけます。ご相談・お見積りは無料です。'],
      ['q' => '福岡県外に住んでいますが、依頼できますか？',
       'a' => 'ご依頼いただけます。ご遺骨はゆうパックでのご郵送でお預かりでき、立ち会い不要の委託海洋葬（54,450円〜）なら帰省せずにすべてお任せいただけます。散骨後は緯度・経度入りの散骨証明書と当日のお写真をご自宅へお届けします。'],
      ['q' => '海洋散骨は法律的に問題ありませんか？',
       'a' => '法務省は「節度をもって葬送の一つとして行われる限り違法ではない」との見解を示しており、厚生労働省のガイドラインも公表されています。当社は日本海洋散骨協会の加盟事業者として、ルールに沿って適切な海域・方法で散骨を行いますのでご安心ください。'],
      ['q' => '費用はいくらかかりますか？あとから追加料金はありませんか？',
       'a' => '海洋散骨は委託54,450円〜・合同148,500円〜・チャーター176,000円〜（いずれも税込）、粉骨は24,200円〜です。金額は無料のお見積りで確定し、ご納得いただいてからのご契約となりますので、あとから追加料金をいただくことはありません。'],
      ['q' => 'お墓じまいから海洋散骨までまとめて頼めますか？',
       'a' => 'はい。墓石の撤去から納骨まで一括対応するお墓じまい（基本プラン330,000円・税込）と、取り出したご遺骨の粉骨・海洋散骨までワンストップで承ります。改葬許可申請（役所手続き）のサポートはオプション（25,000円〜）でご利用いただけます。',
       'link' => ['/grave/', 'お墓じまいについて詳しく見る']],
      ['q' => '生前に自分の散骨を申し込んでおくことはできますか？',
       'a' => '承れます。福岡営業所でも生前契約のご相談を受け付けています。ご家族とよく話し合ったうえで、遺言書やエンディングノートに残しておくことをおすすめします。',
       'link' => ['/seizen/', '海洋散骨 生前契約について詳しく見る']],
    ];
  ?>
  <section class="section">
    <div class="container" style="max-width:820px">
      <h2 style="text-align:center;margin-bottom:8px">よくあるご質問</h2>
      <p style="text-align:center;font-size:.9rem;color:var(--text-light);margin-bottom:24px">福岡でのご供養についてよくいただくご質問です。<a href="/kaiyou-sou/" style="color:var(--green);text-decoration:underline">海洋散骨のよくあるご質問</a>もあわせてご覧ください。</p>
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
  "description":"福岡の海洋散骨・粉骨・お墓じまい・生前契約のご相談窓口。博多湾など福岡の海域での散骨に対応。"
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
