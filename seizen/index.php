<?php
require_once __DIR__ . '/../includes/config.php';
?><!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>海洋散骨 生前契約｜「海洋散骨をしたい」という想いを託す｜<?= h(SITE['name']) ?></title>
<meta name="description" content="海洋散骨の生前契約。生前にご希望を契約して託すことで、ご自身の意思に沿った海洋散骨が実現し、ご家族の負担も軽くなります。鹿児島・福岡対応、有限会社縁。">
<link rel="canonical" href="https://en1150.co.jp/seizen/">
<link rel="icon" href="/assets/img/en.svg" type="image/svg+xml">
<meta property="og:title" content="海洋散骨 生前契約｜有限会社 縁">
<meta property="og:description" content="「海洋散骨をしたい」という想いを生前に契約して託す。鹿児島・福岡の海洋散骨生前契約。">
<meta property="og:type" content="website">
<meta property="og:url" content="https://en1150.co.jp/seizen/">
<meta property="og:image" content="https://en1150.co.jp/assets/img/plan-goudou.jpg">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700&family=Shippori+Mincho:wght@500;600;700&display=swap" rel="stylesheet">

<!-- Breadcrumb 構造化データ -->
<script type="application/ld+json">
{
  "@context":"https://schema.org",
  "@type":"BreadcrumbList",
  "itemListElement":[
    {"@type":"ListItem","position":1,"name":"ホーム","item":"https://en1150.co.jp/"},
    {"@type":"ListItem","position":2,"name":"サービス一覧","item":"https://en1150.co.jp/service/"},
    {"@type":"ListItem","position":3,"name":"海洋散骨 生前契約","item":"https://en1150.co.jp/seizen/"}
  ]
}
</script>
<script type="application/ld+json">
{
  "@context":"https://schema.org",
  "@type":"Service",
  "name":"海洋散骨 生前契約",
  "provider":{"@type":"Organization","name":"有限会社 縁","telephone":"0993-78-4650"},
  "areaServed":["鹿児島県","福岡県"],
  "description":"生前に海洋散骨の希望を契約して託すサービス。利用者の死後、代表遺族の申し込みをもって確定となります。"
}
</script>

<link rel="stylesheet" href="/assets/css/common.css?v=<?= h(asset_ver()) ?>">

<style>
  :root{
    --green-900:#1c3d2e; --green-800:#234d3a; --green-700:#2d6149; --green-500:#4a9270; --green-50:#eef5f0;
    --cream-50:#faf7f0; --cream-100:#f3ede0; --cream-200:#e8dfcc;
    --ink-900:#1a2620; --ink-700:#3d4a44; --ink-500:#6b7670;
    --gold:#a88a4d; --gold-light:#f0d97a;
    --sea-700:#15709e; --sea-900:#0a3852; --sea-800:#0f4d70; --sea-100:#e8f3f8;
    --shadow-sm:0 2px 8px rgba(28,61,46,.06); --shadow-md:0 8px 28px rgba(28,61,46,.10);
    --radius-sm:8px; --radius-md:14px; --radius-lg:20px;
    --serif:"Shippori Mincho","Yu Mincho","游明朝",serif;
    --sans:"Noto Sans JP","Hiragino Sans","ヒラギノ角ゴ ProN",sans-serif;
  }
  .sz *,.sz *::before,.sz *::after{box-sizing:border-box}
  body{background:var(--cream-50)}
  .sz{font-family:var(--sans);color:var(--ink-900);line-height:1.85;font-size:15px;letter-spacing:.02em;overflow-x:hidden}
  .sz img{max-width:100%;height:auto;display:block}

  .sz .section{padding:56px 20px}
  .sz .section>*{max-width:780px;margin-left:auto;margin-right:auto}
  .sz .section--alt{background:var(--cream-100)}
  .sz .section-eyebrow{text-align:center;font-size:11px;letter-spacing:.35em;color:var(--gold);font-weight:600;margin-bottom:10px}
  .sz .section-title{text-align:center;font-family:var(--serif);font-size:1.55rem;font-weight:700;color:var(--green-900);line-height:1.6;margin-bottom:8px}
  .sz .section-sub{text-align:center;font-size:.85rem;color:var(--ink-500);margin-bottom:30px}
  .sz .wave{display:block;margin:0 auto 14px;width:120px;height:14px;color:var(--sea-700)}

  /* Hero */
  .sz-hero{background:var(--cream-50);padding:0 20px 52px}
  .sz-hero__inner{max-width:1160px;margin:0 auto}
  /* メインビジュアル：画面横いっぱい＋キャッチコピーを画像上に重ねる */
  .sz-hero__visual{position:relative;width:100vw;margin:0 calc(50% - 50vw) 26px;overflow:hidden}
  .sz-hero__visual>img{width:100%;height:clamp(340px,46vw,600px);object-fit:cover;display:block}
  .sz-hero__visual::before{content:"";position:absolute;inset:0;background:linear-gradient(180deg,rgba(10,56,82,.30) 0%,rgba(10,56,82,.12) 45%,rgba(10,56,82,.34) 100%);pointer-events:none}
  .sz-hero__copy{position:absolute;inset:0;display:flex;flex-direction:column;justify-content:center;align-items:center;text-align:center;padding:24px;z-index:1}
  .sz-hero__eyebrow{font-size:11px;letter-spacing:.3em;color:var(--gold-light);font-weight:700;text-shadow:0 1px 8px rgba(10,56,82,.5)}
  .sz-hero__title{font-family:var(--serif);font-size:clamp(1.45rem,4.5vw,2.5rem);font-weight:700;color:#fff;line-height:1.6;margin:12px 0 8px;text-shadow:0 2px 16px rgba(10,56,82,.55)}
  .sz-hero__title em{font-style:normal;color:#fff;border-bottom:3px solid var(--gold-light);padding-bottom:2px}
  .sz-hero__lead{font-family:var(--serif);font-size:clamp(.9rem,1.6vw,1.05rem);color:rgba(255,255,255,.92);text-shadow:0 1px 10px rgba(10,56,82,.5)}
  .sz-hero__note{max-width:560px;margin:0 auto 26px;background:#fff;border:1px solid var(--cream-200);border-left:4px solid var(--gold);border-radius:var(--radius-sm);padding:12px 16px;font-size:.88rem;color:var(--ink-700);text-align:center}
  /* キービジュアル：画面横いっぱい（文字入りのためトリミングなし） */
  .sz .section>.sz-keyvisual{max-width:none;width:100vw;margin:26px calc(50% - 50vw) 0;border-radius:0;overflow:hidden;box-shadow:none}
  .sz-keyvisual img{width:100%}
  .sz-hero__cta{display:flex;flex-direction:column;gap:12px;max-width:560px;margin:0 auto}
  .sz-btn-tel{background:#fff;border:1px solid var(--cream-200);border-radius:999px;padding:14px 22px;display:flex;align-items:center;justify-content:center;gap:10px;box-shadow:var(--shadow-sm);transition:.25s}
  .sz-btn-tel:hover{transform:translateY(-2px);box-shadow:var(--shadow-md)}
  .sz-btn-tel .num{font-family:var(--serif);font-size:1.35rem;font-weight:700;color:var(--green-900);line-height:1}
  .sz-btn-tel .lbl{font-size:.75rem;color:var(--ink-500)}
  .sz-btn-contact{background:var(--sea-700);color:#fff;border-radius:999px;padding:15px 22px;display:flex;align-items:center;justify-content:center;gap:10px;font-weight:700;letter-spacing:.06em;box-shadow:var(--shadow-sm);transition:.25s}
  .sz-btn-contact:hover{transform:translateY(-2px);background:var(--sea-800);color:#fff}

  /* 汎用カード */
  .sz-card{background:#fff;border:1px solid var(--cream-200);border-radius:var(--radius-md);box-shadow:var(--shadow-sm);padding:22px 20px}
  .sz-prose p{margin-bottom:1em}
  .sz-prose p:last-child{margin-bottom:0}

  /* TV */
  .sz-tv{display:flex;flex-direction:column;align-items:center;gap:14px;text-align:center}
  .sz-tv__shots{display:grid;grid-template-columns:1fr;gap:12px;width:100%}
  .sz-tv__shots img{border-radius:var(--radius-md);box-shadow:var(--shadow-sm);width:100%}

  /* 3枚カード画像・フェーズ画像 */
  .sz-cards3{display:grid;grid-template-columns:1fr;gap:14px;margin-top:26px}
  .sz-cards3 img{border-radius:var(--radius-md);box-shadow:var(--shadow-sm);background:#fff;width:100%}
  .sz-phaseimgs{display:flex;flex-direction:column;gap:16px;margin-top:24px}
  .sz-phaseimgs img{border-radius:var(--radius-md);box-shadow:var(--shadow-sm);background:#fff;width:100%}

  /* 手紙イメージ（全幅） */
  .sz-letter img{width:100%;max-height:420px;object-fit:cover}
  .sz-tv__btn{display:inline-flex;align-items:center;gap:8px;background:var(--green-700);color:#fff;border-radius:999px;padding:12px 26px;font-weight:700;font-size:.92rem;transition:.25s}
  .sz-tv__btn:hover{background:var(--green-900);color:#fff;transform:translateY(-2px)}

  /* 登場人物 */
  .sz-actors{display:grid;grid-template-columns:1fr;gap:12px;margin-bottom:26px}
  .sz-actor{background:#fff;border:1px solid var(--cream-200);border-radius:var(--radius-md);padding:16px;text-align:center;box-shadow:var(--shadow-sm)}
  .sz-actor h3{font-family:var(--serif);font-size:1.05rem;color:var(--sea-800);margin-bottom:4px}
  .sz-actor p{font-size:.85rem;color:var(--ink-700)}

  /* ステップカード（流れ） */
  .sz-steps{display:flex;flex-direction:column;gap:0}
  .sz-step{background:#fff;border:1px solid var(--cream-200);border-radius:var(--radius-md);box-shadow:var(--shadow-sm);padding:20px}
  .sz-step + .sz-step{margin-top:34px;position:relative}
  .sz-step + .sz-step::before{content:"▼";position:absolute;top:-28px;left:50%;transform:translateX(-50%);color:var(--sea-700);font-size:15px}
  .sz-step__tag{display:inline-block;background:var(--sea-700);color:#fff;font-size:.72rem;font-weight:700;letter-spacing:.12em;padding:4px 14px;border-radius:999px;margin-bottom:10px}
  .sz-step__title{font-family:var(--serif);font-size:1.08rem;font-weight:700;color:var(--green-900);margin-bottom:6px}
  .sz-step__body{font-size:.9rem;color:var(--ink-700)}
  .sz-step__body ul{list-style:none;margin-top:8px}
  .sz-step__body li{position:relative;padding-left:18px;margin-bottom:4px}
  .sz-step__body li::before{content:"・";position:absolute;left:2px;color:var(--sea-700)}
  .sz-step__docs{margin-top:10px;background:var(--sea-100);border-radius:var(--radius-sm);padding:10px 14px;font-size:.85rem;color:var(--sea-800);font-weight:600}
  .sz-step__note{margin-top:8px;font-size:.8rem;color:var(--ink-500)}
  .sz-step__cert{margin-top:10px;border-top:1px dashed var(--cream-200);padding-top:10px;font-size:.88rem}
  .sz-step__cert strong{color:var(--sea-700)}

  /* 関係図（テキスト版） */
  .sz-relation{background:#fff;border:1px solid var(--cream-200);border-radius:var(--radius-md);box-shadow:var(--shadow-sm);padding:20px;margin-top:26px}
  .sz-relation h3{font-family:var(--serif);font-size:1rem;color:var(--green-900);text-align:center;margin-bottom:12px}
  .sz-relation ol{list-style:none;counter-reset:rel}
  .sz-relation li{counter-increment:rel;position:relative;padding-left:34px;margin-bottom:10px;font-size:.9rem}
  .sz-relation li::before{content:"（" counter(rel) "）";position:absolute;left:0;color:var(--sea-700);font-weight:700}
  .sz-relation small{color:var(--ink-500)}

  /* 大切なこと（契約前・契約・契約後） */
  .sz-phase{margin-top:26px}
  .sz-phase__label{width:130px;margin:0 auto 14px;text-align:center;background:var(--sea-700);color:#fff;border-radius:999px;padding:8px 0;font-weight:700;letter-spacing:.15em;font-size:.9rem}
  .sz-phase__box{background:var(--sea-100);border-radius:var(--radius-md);padding:20px}
  .sz-phase__box + .sz-phase__label{margin-top:26px}
  .sz-phase__item + .sz-phase__item{margin-top:16px;border-top:1px dashed rgba(21,112,158,.25);padding-top:16px}
  .sz-phase__item h4{color:var(--sea-700);font-size:1rem;font-weight:700;margin-bottom:4px}
  .sz-phase__item p{font-size:.9rem;color:var(--ink-700)}
  .sz-phase__item .em{color:var(--sea-800);font-weight:700}
  .sz-phase__item .note{font-size:.8rem;color:var(--ink-500);margin-top:4px}

  /* 想い */
  .sz-omoi{display:grid;grid-template-columns:1fr;gap:24px;align-items:center}
  .sz-omoi__img{border-radius:var(--radius-lg);overflow:hidden;box-shadow:var(--shadow-md)}

  /* お考えの方 */
  .sz-who{display:grid;grid-template-columns:1fr;gap:12px}
  .sz-who__card{background:#fff;border:1px solid var(--cream-200);border-radius:var(--radius-md);box-shadow:var(--shadow-sm);padding:0 0 16px;overflow:hidden}
  .sz-who__img{width:100%;height:170px;object-fit:cover;margin-bottom:12px}
  .sz-who__card h3{font-size:.98rem;font-weight:700;color:var(--sea-800);margin:0 18px 4px}
  .sz-who__card p{font-size:.88rem;color:var(--ink-700);margin:0 18px}

  /* 声 */
  .sz-voice{display:grid;grid-template-columns:1fr;gap:12px}
  .sz-voice__card{background:#fff;border:1px solid var(--cream-200);border-radius:var(--radius-md);box-shadow:var(--shadow-sm);padding:0 0 18px;overflow:hidden}
  .sz-voice__img{width:100%;height:180px;object-fit:cover;margin-bottom:12px}
  .sz-voice__card h3,.sz-voice__card p{margin-left:20px;margin-right:20px}
  .sz-voice__card h3{font-family:var(--serif);font-size:1rem;color:var(--green-900);margin-bottom:6px}
  .sz-voice__card h3::before{content:"“";color:var(--gold);margin-right:4px;font-size:1.2rem}
  .sz-voice__card p{font-size:.88rem;color:var(--ink-700)}

  /* お寺にも納骨 */
  .sz-tera{background:#fff;border:1px solid var(--cream-200);border-radius:var(--radius-lg);box-shadow:var(--shadow-sm);padding:26px 22px;text-align:center}
  .sz-tera p{font-size:.92rem;color:var(--ink-700);margin-bottom:.8em}
  .sz-tera strong{color:var(--green-800)}

  /* 最終CTA（母艦ブルー帯） */
  .sz-final{background:linear-gradient(180deg,var(--sea-900) 0%,var(--sea-800) 100%);color:#fff;padding:64px 20px 72px;text-align:center;position:relative;overflow:hidden}
  .sz-final::before{content:"";position:absolute;inset:0;background:radial-gradient(ellipse at 20% 0%,rgba(208,183,143,.16),transparent 50%),radial-gradient(ellipse at 80% 100%,rgba(31,143,206,.22),transparent 50%);pointer-events:none}
  .sz-final>*{position:relative;max-width:560px;margin-left:auto;margin-right:auto}
  .sz-final__eyebrow{font-size:11px;letter-spacing:.4em;color:var(--gold-light);font-weight:600;margin-bottom:10px}
  .sz-final__title{font-family:var(--serif);font-size:1.5rem;font-weight:700;line-height:1.7;margin-bottom:8px}
  .sz-final__lead{font-size:.92rem;color:rgba(255,255,255,.85);margin-bottom:24px}
  .sz-final__tel{background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.25);border-radius:var(--radius-md);padding:16px;margin-bottom:14px}
  .sz-final__tel-label{font-size:11px;color:var(--gold-light);letter-spacing:.2em;margin-bottom:6px}
  .sz-final__tel-num{font-family:var(--serif);font-size:28px;font-weight:700;color:#fff;line-height:1;display:flex;align-items:center;justify-content:center;gap:8px}
  .sz-final__tel-num svg{width:20px;height:20px}
  .sz-final__tel-time{font-size:10.5px;color:rgba(255,255,255,.7);margin-top:6px;letter-spacing:.1em}
  .sz-final__contact{background:#fff;color:var(--sea-800);border-radius:var(--radius-md);padding:15px 20px;display:flex;align-items:center;justify-content:center;gap:10px;font-weight:700;box-shadow:0 6px 16px rgba(0,0,0,.25);transition:.2s;margin-bottom:26px}
  .sz-final__contact:hover{transform:translateY(-1px);color:var(--sea-800)}
  .sz-final__company{font-size:.85rem;color:rgba(255,255,255,.8);line-height:2}

  /* PC */
  @media(min-width:960px){
    .sz .section{padding:72px 48px}
    .sz .section-title{font-size:1.9rem}
    .sz-hero{padding:64px 48px 72px}
    .sz-hero__cta{flex-direction:row}
    .sz-hero__cta>*{flex:1 1 0}
    .sz-tv__shots{grid-template-columns:1fr 1fr 1fr}
    .sz-cards3{grid-template-columns:1fr 1fr 1fr;max-width:900px}
    .sz-who{grid-template-columns:1fr 1fr;max-width:900px}
    .sz-voice{grid-template-columns:1fr 1fr;max-width:900px}
    .sz-omoi{grid-template-columns:1.2fr .8fr;max-width:980px}
    .sz-final{padding:80px 48px 88px}
  }
  @media(prefers-reduced-motion:reduce){
    .sz *,.sz *::before,.sz *::after{transition-duration:.01ms!important;animation-duration:.01ms!important}
  }
</style>
</head>
<body>
<?php require __DIR__ . '/../includes/header.php'; ?>

<nav class="breadcrumb"><a href="/">ホーム</a> ＞ <a href="/service/">サービス一覧</a> ＞ 海洋散骨 生前契約</nav>

<main class="sz" id="main-content">

  <!-- Hero -->
  <section class="sz-hero">
    <div class="sz-hero__visual">
      <img src="images/hero-water.webp?v=<?= h(asset_ver()) ?>" alt="水面に浮かぶ花" width="2400" height="1228" fetchpriority="high">
      <div class="sz-hero__copy">
        <p class="sz-hero__eyebrow">SEIZEN KEIYAKU ─ 生前の希望を叶えるために</p>
        <h1 class="sz-hero__title">「海洋散骨をしたい」という想いを託す<br><em>海洋散骨 生前契約</em></h1>
        <p class="sz-hero__lead">海洋散骨・生前契約 ─ 種類と流れ</p>
      </div>
    </div>
    <div class="sz-hero__inner">
      <p class="sz-hero__note">生前契約は、利用者の死後、<br>代表遺族の申し込みをもって確定となります</p>
      <div class="sz-hero__cta">
        <a class="sz-btn-tel" href="tel:0993784650">
          <span class="lbl">お電話で相談</span>
          <span class="num">0993-78-4650</span>
        </a>
        <a class="sz-btn-contact" href="/contact/">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
          資料請求・ご相談
        </a>
      </div>
    </div>
  </section>

  <!-- 導入 -->
  <section class="section">
    <p class="section-eyebrow">ABOUT</p>
    <h2 class="section-title">遺骨を海に「海洋葬」<br>墓を持たない選択</h2>
    <div class="sz-card sz-prose" style="margin-top:22px">
      <p>近年、海洋散骨という新しい形の葬送が注目されています。</p>
      <p>海洋散骨は、故人を海へと送り出すことで、その思い出を海とともに永遠に刻むことができる儀式です。また生まれ育った故郷の馴染み深い海での散骨を望まれ、「亡き後は故郷の海での散骨」と生前に家族と話し合い決めておく方が増えております。</p>
    </div>
    <div class="sz-keyvisual">
      <img src="images/keyvisual.webp?v=<?= h(asset_ver()) ?>" alt="海に行けばいつでも会える　海洋散骨を選びました ─ 残された家族に父が選んだのは、お墓を残さない選択" width="2400" height="1070" loading="lazy">
    </div>
  </section>

  <!-- TV -->
  <section class="section section--alt">
    <p class="section-eyebrow">MEDIA</p>
    <h2 class="section-title">当社の取り組みが<br>TVで放送されました</h2>
    <div class="sz-tv" style="margin-top:20px">
      <div class="sz-tv__shots">
        <img src="images/tv-1.webp?v=<?= h(asset_ver()) ?>" alt="TV放送：海洋散骨 体験クルーズの様子" width="1200" height="675" loading="lazy">
        <img src="images/tv-2.webp?v=<?= h(asset_ver()) ?>" alt="TV放送：粉にした遺骨を水溶性の袋に" width="1200" height="675" loading="lazy">
        <img src="images/tv-3.webp?v=<?= h(asset_ver()) ?>" alt="TV放送：スタッフインタビュー" width="1372" height="762" loading="lazy">
      </div>
      <p style="font-size:.9rem;color:var(--ink-700)">海洋散骨・供養の多様化への取り組みが、テレビ番組で紹介されました。</p>
      <a class="sz-tv__btn" href="https://news.ntv.co.jp/n/fbs/category/life/fs5a5d946b5f2341e2894be034beca46e3" target="_blank" rel="noopener">
        放送内容はこちらから
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
      </a>
    </div>
  </section>

  <!-- 生前契約から海洋散骨までの流れ -->
  <section class="section">
    <p class="section-eyebrow">FLOW</p>
    <h2 class="section-title">生前契約から<br>海洋散骨までの流れ</h2>
    <p class="section-sub">生前契約に関わる3者の役割</p>

    <div class="sz-diagram" style="background:#fff;border:1px solid var(--cream-200);border-radius:var(--radius-md);box-shadow:var(--shadow-sm);padding:18px">
      <img src="images/diagram-sankaku.webp?v=<?= h(asset_ver()) ?>" alt="3者の関係図：①利用者→縁 海洋散骨生前予約（費用は発生しません）②代表遺族→利用者 海洋散骨生前申込み連絡（海洋散骨費用の授受）③縁→利用者 海洋散骨生前予約 ④代表遺族→縁 利用申込者の没後、海洋散骨の申し込み ⑤料金の支払い" width="2026" height="1570" loading="lazy" style="max-width:640px;margin:0 auto">
    </div>

    <div class="sz-cards3">
      <img src="images/card-step1.webp?v=<?= h(asset_ver()) ?>" alt="利用者・代表遺族：連名で生前契約の申込みをします。必要書類【海洋散骨生前申込書】キャンセル料は0円です" width="964" height="1074" loading="lazy">
      <img src="images/card-step2.webp?v=<?= h(asset_ver()) ?>" alt="代表遺族：利用者の死後、代表遺族は有限会社縁へ連絡を行い、海洋散骨の申込みを行います。必要書類【海洋散骨申込書・火葬許可証もしくは死亡証明書等】海洋散骨申し込み時のサービス費用が請求金額となります。代表遺族の変更があった際は「縁」まで連絡をお願いします" width="968" height="1074" loading="lazy">
      <img src="images/card-en.webp?v=<?= h(asset_ver()) ?>" alt="有限会社 縁：利用者の希望のプランにて海洋散骨を行います" width="964" height="1074" loading="lazy">
    </div>
  </section>

  <!-- 生前予約 大切なこと -->
  <section class="section section--alt">
    <p class="section-eyebrow">IMPORTANT</p>
    <h2 class="section-title">生前予約　大切なこと</h2>

    <div class="sz-phaseimgs">
      <img src="images/phase-pre.webp?v=<?= h(asset_ver()) ?>" alt="契約前｜伝える：海洋散骨をしてほしいという想いを「代表遺族（予定）」となる方に伝えます。話し合う：お一人で抱えることなく、ご親族の皆さんで共有してください。※正式契約以前は、代表遺族が全ての責任を負う必要はありません。※大切な情報を共有することで後にトラブルが発生することを防ぎます。" width="2400" height="996" loading="lazy">
      <img src="images/phase-keiyaku.webp?v=<?= h(asset_ver()) ?>" alt="契約｜結ぶ：申込書を有限会社縁にて受理します。※この段階では有限会社縁からの請求は発生いたしません。託す：海洋散骨への想いが確定したら代表遺族となる予定の方に必要費用を託します。" width="2400" height="981" loading="lazy">
      <img src="images/phase-post.webp?v=<?= h(asset_ver()) ?>" alt="契約後｜見直す・叶える" width="2400" height="977" loading="lazy">
    </div>
  </section>

  <!-- 利用者が亡くなった後の流れ -->
  <section class="section">
    <p class="section-eyebrow">AFTER</p>
    <h2 class="section-title">〜利用者が亡くなった後〜<br>海洋散骨までの流れ</h2>

    <div class="sz-flowimg" style="margin-top:24px;background:#fff;border:1px solid var(--cream-200);border-radius:var(--radius-md);box-shadow:var(--shadow-sm);padding:18px">
      <img src="images/flow-after.webp?v=<?= h(asset_ver()) ?>" alt="ご連絡：利用者のご訃報を有限会社縁に伝える（※海洋散骨を希望される時期の連絡でも可 TEL:0993-78-4650）→ 日程調整：利用者様の海洋散骨を行う時期を決めます（例えば、故人の没後すぐに／しばらくご自宅にて手元供養をしてから／〇回忌まではお寺やお墓に納骨してから など）→ 申し込み：海洋散骨の正式申し込み・料金の支払い（※散骨ご希望の時期に海洋葬申込みとなります。※海洋散骨の申込み時の料金を代表遺族がお支払い）→ 預ける：ご遺骨のお預かり（郵送・お持ち込み・出張預かり（費用別途））→ 海洋散骨：実施前連絡・海洋散骨当日（天候による出航可否判断を出航2日前に連絡します）海洋散骨証明書発行：緯度・経度が記載された海洋散骨証明書を発行します" width="2400" height="2291" loading="lazy" style="max-width:720px;margin:0 auto">
    </div>
  </section>

  <!-- 縁の想い -->
  <section class="section section--alt">
    <p class="section-eyebrow">OUR WISH</p>
    <h2 class="section-title">〜縁の想い〜</h2>
    <div class="sz-omoi" style="margin-top:22px">
      <div class="sz-card sz-prose">
        <p>近年、社会状況や生活環境の変化により、納骨の形も多様化しています。「お墓」や「納骨堂」に限らず、樹木葬や海洋散骨などの自然葬、お手元供養など、幅広い選択肢から選べるようになりました。</p>
        <p>私たち有限会社縁では、ご先祖様や身近な方だけでなく、ご自身の納骨に関する希望を生前に話し合い、安心して過ごせる人生・時間を過ごすお手伝いをしたいと考え、「生前契約」を承ることとしました。</p>
        <p>これにより、「供養」や「納骨」に関する不安を解消し、死後も自身の希望が叶うことへの望みを感じ、安らかな日々をお過ごしいただけるお手伝いをさせていただけたら幸いです。皆様が安心して大切な毎日を過ごせるよう、サポートを続けてまいります。</p>
      </div>
      <div class="sz-omoi__img">
        <img src="images/omoi-boat.webp?v=<?= h(asset_ver()) ?>" alt="船上から海を見つめるスタッフ" loading="lazy" width="2400" height="1600">
      </div>
    </div>
  </section>

  <!-- 手紙イメージ -->
  <div class="sz-letter">
    <img src="images/letter.webp?v=<?= h(asset_ver()) ?>" alt="故人への手書きの手紙を読む様子" loading="lazy" width="1200" height="800">
  </div>

  <!-- このようなお考えの方 -->
  <section class="section">
    <p class="section-eyebrow">FOR YOU</p>
    <h2 class="section-title">〜このようなお考えの方が<br>ご利用されております〜</h2>
    <div class="sz-who" style="margin-top:22px">
      <div class="sz-who__card">
        <img class="sz-who__img" src="images/who-kazoku.webp?v=<?= h(asset_ver()) ?>" alt="リビングでくつろぐ三世代家族" loading="lazy" width="2400" height="1602">
        <h3>家族に安心してもらいたい方</h3>
        <p>死後、家族が納骨や葬儀に関する判断で悩まないよう、事前に決めておくことで穏やかな気持ちで過ごせます。</p>
      </div>
      <div class="sz-who__card">
        <img class="sz-who__img" src="images/who-ishi.webp?v=<?= h(asset_ver()) ?>" alt="資料を見ながら話し合うご夫婦" loading="lazy" width="2400" height="1350">
        <h3>ご自身の意思や希望をお持ちの方</h3>
        <p>自分が望む場所や方法で納骨されたいという強い希望があり、その意思をしっかり伝えることで、不安なく過ごすことができます。</p>
      </div>
      <div class="sz-who__card">
        <img class="sz-who__img" src="images/who-anshin.webp?v=<?= h(asset_ver()) ?>" alt="愛犬とソファでくつろぐご夫婦" loading="lazy" width="2400" height="1600">
        <h3>安心感を得たい方</h3>
        <p>自分の最期がどのように扱われるかを明確にすることで、精神的な安心感や心の平穏を得ることができます。</p>
      </div>
      <div class="sz-who__card">
        <img class="sz-who__img" src="images/who-kizuna.webp?v=<?= h(asset_ver()) ?>" alt="夕日の海辺を歩く家族" loading="lazy" width="2400" height="1600">
        <h3>家族との絆を感じたい方</h3>
        <p>ご自身の家族が海洋散骨をされたので、と同じ場所に散骨したいと希望される方。</p>
      </div>
    </div>
  </section>

  <!-- ご利用者の声 -->
  <section class="section section--alt">
    <p class="section-eyebrow">VOICE</p>
    <h2 class="section-title">ご利用者の声</h2>
    <div class="sz-voice" style="margin-top:22px">
      <div class="sz-voice__card">
        <img class="sz-voice__img" src="images/voice-fisher.webp?v=<?= h(asset_ver()) ?>" alt="海辺で釣りをする人" loading="lazy" width="2400" height="1601">
        <h3>自分らしい納骨をお願いできて安心です</h3>
        <p>釣りが好きな私は、自身が亡くなった後は、海洋散骨をしてほしいと、こだわりがあるので、生前にしっかりと話し合って決めておけるのは大きな安心です。自分の希望通りに送ってもらえることが何より嬉しいです。</p>
      </div>
      <div class="sz-voice__card">
        <img class="sz-voice__img" src="images/voice-sakura.webp?v=<?= h(asset_ver()) ?>" alt="桜の下を歩くご夫婦" loading="lazy" width="2400" height="1602">
        <h3>準備を済ませたことで心が軽くなりました</h3>
        <p>自分の最期について事前に準備を進めておくことで、漠然とした不安が消えて、気持ちが落ち着きました。これで毎日を安心して過ごせます。</p>
      </div>
    </div>
  </section>

  <!-- お寺にも納骨をしたいという方へ -->
  <section class="section">
    <p class="section-eyebrow">TEMPLE ＆ OCEAN</p>
    <h2 class="section-title">〜お寺にも納骨をしたい<br>という方へ〜</h2>
    <div class="sz-tera" style="margin-top:22px">
      <p>納骨堂に少量のお骨を納め、さらにお骨を海へ散骨（海洋葬）します。広大で美しい九州の海でご家族・ご先祖様を自然にお還しします。</p>
      <p>少量をお寺に納骨し寺院で永代供養しますので、安心して納骨ができます。</p>
      <p><strong>全国の寺院との提携がある安心の縁にお任せください</strong></p>
    </div>
  </section>

  <!-- 最終CTA -->
  <section class="sz-final" id="contact">
    <p class="sz-final__eyebrow">CONTACT</p>
    <h2 class="sz-final__title">有限会社縁の<br>海洋散骨生前契約</h2>
    <p class="sz-final__lead">まずはお気軽にご連絡ください。</p>
    <div class="sz-final__tel">
      <p class="sz-final__tel-label">お電話でのご相談</p>
      <a href="tel:0993784650" class="sz-final__tel-num">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
        0993-78-4650
      </a>
      <p class="sz-final__tel-time">受付：月〜土 9:00 - 18:00</p>
    </div>
    <a href="/contact/" class="sz-final__contact">
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
      資料請求・ご相談
    </a>
    <p class="sz-final__company">
      有限会社縁<br>
      〒897-0202　鹿児島県南九州市川辺町清水9860<br>
      電話 0993-78-4650
    </p>
  </section>

</main>

<?php require __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>
