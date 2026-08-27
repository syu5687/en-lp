<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/admin/includes/store.php';
// 新着ブログ3件。Firestore → news.json → 旧WordPressアーカイブ を統合し、
// 同一記事（同日付＋同タイトル）は本文HTML・画像を持つ情報の濃い方を優先する。
$by_key = []; $seen_ids = [];
$blog_score = fn(array $it): int =>
  (!empty($it['body_html']) ? 4 : 0) + (!empty($it['image']) ? 2 : 0) + (!empty($it['images']) ? 1 : 0);
$push_item = function (array $it) use (&$by_key, &$seen_ids, $blog_score) {
  if (empty($it['published'])) return;
  $id = (string)($it['id'] ?? '');
  if ($id === '' || isset($seen_ids[$id])) return;
  $seen_ids[$id] = true;
  // タイトルは記号・絵文字・空白を除いて比較（絵文字有無の表記ゆれを同一視）
  $key = ($it['date'] ?? '') . '|' . preg_replace('/[^\p{L}\p{N}]+/u', '', (string)($it['title'] ?? ''));
  if (!isset($by_key[$key]) || $blog_score($it) > $blog_score($by_key[$key])) $by_key[$key] = $it;
};
try { foreach (news_published() as $it) $push_item($it); } catch (Throwable $e) {}
foreach (['/data/news.json', '/data/blog-posts.json'] as $src) {
  $seed = @json_decode((string)@file_get_contents(__DIR__ . $src), true);
  foreach (($seed['items'] ?? []) as $it) $push_item($it);
}
$blog_items = array_values($by_key);
usort($blog_items, fn($a, $b) => strcmp($b['date'] ?? '', $a['date'] ?? ''));
$blog_items = array_slice($blog_items, 0, 6);
?>
<!DOCTYPE html>
<html lang="ja" prefix="og: https://ogp.me/ns#">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>海洋散骨・粉骨・お墓じまい｜鹿児島の供養トータルサポート 有限会社 縁</title>
<meta name="description" content="鹿児島・福岡で海洋散骨・粉骨・お墓じまい・樹木葬のご相談なら有限会社縁。鹿児島本社・福岡営業所の2拠点で九州全域に対応、全国からの郵送粉骨・委託散骨も。粉骨24,200円〜、海洋葬54,450円〜。日本海洋散骨協会加盟。">
<meta name="keywords" content="海洋散骨,鹿児島,粉骨,お墓じまい,樹木葬,海洋葬,散骨,供養,お手元供養,宇宙葬,生前契約,有限会社縁">
<link rel="canonical" href="https://en1150.co.jp/">
<meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large">
<meta name="author" content="有限会社 縁">
<!-- OGP -->
<meta property="og:title" content="海洋散骨・粉骨・お墓じまい｜鹿児島の供養トータルサポート【有限会社 縁】">
<meta property="og:description" content="鹿児島で海洋散骨・粉骨・お墓じまい・樹木葬のご相談なら有限会社縁。粉骨24,200円〜。日本海洋散骨協会加盟。">
<meta property="og:type" content="website">
<meta property="og:url" content="https://en1150.co.jp/">
<meta property="og:site_name" content="有限会社 縁｜鹿児島の供養トータルサポート">
<meta property="og:locale" content="ja_JP">
<meta property="og:image" content="https://en1150.co.jp/assets/og-image.jpg">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:image" content="https://en1150.co.jp/assets/og-image.jpg">
<link rel="icon" href="/assets/img/en.svg" type="image/svg+xml">
<style>
/* 有限会社 縁 — トップページ スタイル */
:root {
  /* 海（ティール/藍緑）× 砂浜（生成り/金）× 波しぶき（白）— 緑ロゴと調和する海松色基調 */
  --color-deep-green: #275c58;   /* 深い海松色（sea green-teal） */
  --color-green-mid: #1b413f;    /* 深海（バンド/フッター） */
  --color-green-light: #6fb1ad;  /* 浅瀬 */
  --color-aqua: #4c928d;         /* 波・アクセント */
  --color-aqua-soft: rgba(76,146,141,0.12);
  --color-cream: #f4f1ea;        /* 砂 */
  --color-cream-dark: #e8e4d8;
  --color-paper-cool: #eef3f2;   /* 潮だまり（寒色の紙） */
  --color-gold: #b18e63;         /* 砂金 */
  --color-gold-light: #d0b78f;
  --color-text: #23201b;
  --color-text-light: #6b655a;
  --color-white: #fffdf9;
  --color-border: rgba(39,92,88,0.16);
  --color-line: rgba(34,32,27,0.10);
  --font-serif: 'Shippori Mincho', 'Noto Serif JP', 'Yu Mincho', serif;
  --font-sans: 'Noto Sans JP', 'Hiragino Sans', sans-serif;
  --font-display: 'Cormorant Garamond', serif;
  --shadow-soft: none;
  --shadow-card: none;
  --shadow-hover: none;
  --radius: 2px;
  --radius-lg: 3px;
  --transition: 0.34s cubic-bezier(0.4, 0, 0.2, 1);
}
*, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
html { scroll-behavior: smooth; font-size: 16px; }
body { font-family: var(--font-sans); color: var(--color-text); background: var(--color-cream); line-height: 1.95; -webkit-font-smoothing: antialiased; overflow-x: hidden; letter-spacing: 0.02em; font-weight: 400; }
img { max-width: 100%; height: auto; display: block; }
a { text-decoration: none; color: inherit; transition: var(--transition); }
.container { max-width: 1080px; margin: 0 auto; padding: 0 32px; }

/* 英字ラベル（kicker）は小さく・控えめに・使いすぎない */
.section-label { font-family: var(--font-display); font-size: 0.82rem; letter-spacing: 0.32em; color: var(--color-gold); text-transform: uppercase; margin-bottom: 16px; display: inline-flex; align-items: center; gap: 12px; font-weight: 500; }
/* kicker の飾りを直線 → 波の曲線に */
.section-label::before { content: ''; width: 34px; height: 9px; background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='34' height='9' viewBox='0 0 34 9' fill='none' stroke='%23b18e63' stroke-width='1.1'%3E%3Cpath d='M0 5 C 4.5 0.5, 8.5 0.5, 12 5 S 21 9.5, 25 5 S 30 1.5, 34 4'/%3E%3C/svg%3E") no-repeat center/contain; }
.testimonials .section-label::before { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='34' height='9' viewBox='0 0 34 9' fill='none' stroke='%23d0b78f' stroke-width='1.1'%3E%3Cpath d='M0 5 C 4.5 0.5, 8.5 0.5, 12 5 S 21 9.5, 25 5 S 30 1.5, 34 4'/%3E%3C/svg%3E"); }
.services-header .section-label, .flow-header .section-label, .staff-header .section-label,
.testimonials-header .section-label, .strengths-header .section-label, .comparison-header .section-label,
.area-header .section-label, .media-header .section-label, .faq-header .section-label,
.blog-header .section-label, .media-coverage-header .section-label, .gallery-header .section-label,
.worry-header .section-label { justify-content: center; }
.section-title { font-family: var(--font-serif); font-size: clamp(1.5rem, 2.7vw, 2rem); font-weight: 500; color: var(--color-green-mid); line-height: 1.65; letter-spacing: 0.06em; margin-bottom: 16px; }
.section-desc { font-size: 0.9rem; color: var(--color-text-light); line-height: 2.1; max-width: 640px; }
.fade-up { opacity: 0; transform: translateY(20px); transition: opacity 0.8s ease, transform 0.8s ease; }
.fade-up.visible { opacity: 1; transform: translateY(0); }

/* HEADER */
.header { position: fixed; top: 0; left: 0; right: 0; z-index: 100; background: rgba(246,241,232,0.9); backdrop-filter: blur(6px); -webkit-backdrop-filter: blur(6px); border-bottom: 1px solid var(--color-line); transition: var(--transition); }
.header-inner { display: flex; align-items: center; justify-content: space-between; max-width: 1160px; margin: 0 auto; padding: 14px 32px; transition: padding 0.25s ease; }
@media (max-width: 768px) {
  .header.is-shrink .header-inner { padding: 5px 20px; }
  .header.is-shrink .header-logo img { height: 28px !important; }
  .header.is-shrink .header-logo-text { font-size: 0.92rem; }
  .header-logo img, .header-logo-text { transition: all 0.25s ease; }
}
.header-logo-text { font-family: var(--font-serif); font-size: 1.12rem; font-weight: 600; color: var(--color-green-mid); letter-spacing: 0.14em; }
.header-nav { display: flex; align-items: center; gap: clamp(12px, 1.5vw, 28px); }
.header-nav a { font-size: 0.82rem; font-weight: 400; color: var(--color-text); position: relative; white-space: nowrap; }
.header-nav a::after { content: ''; position: absolute; bottom: -4px; left: 0; width: 0; height: 1px; background: var(--color-gold); transition: var(--transition); }
.header-nav a:hover::after { width: 100%; }
.nav-dd { position: relative; display: inline-flex; align-items: center; }
.nav-dd > a { display: inline-flex; align-items: center; }
.nav-dd-caret { font-size: 0.6em; margin-left: 4px; opacity: 0.7; }
.nav-dd-menu { position: absolute; top: 100%; left: 50%; transform: translateX(-50%); min-width: 220px; background: #fff; border-radius: 10px; box-shadow: 0 10px 30px rgba(9,45,66,0.18); padding: 8px 0; opacity: 0; visibility: hidden; transition: opacity 0.18s ease, visibility 0.18s; z-index: 120; display: block; }
.nav-dd-menu::before { content: ''; position: absolute; top: -10px; left: 0; right: 0; height: 10px; }
.nav-dd:hover .nav-dd-menu, .nav-dd:focus-within .nav-dd-menu { opacity: 1; visibility: visible; }
.nav-dd-menu a { display: block; padding: 9px 18px; color: #1c3b40 !important; font-size: 0.85rem; white-space: nowrap; }
.nav-dd-menu a::after { display: none; }
.nav-dd-menu a:hover { background: #eef5f4; color: #15709e !important; }
.nav-dd-menu .nav-dd-top { font-weight: 700; color: #15709e !important; border-bottom: 1px solid #e4ebee; margin-bottom: 6px; padding-bottom: 12px; }
@media (max-width: 860px) {
  /* SPメニュー：開いた時のパネル（不透明グラデ・左寄せ・角丸） */
  .header-nav.is-open { display: flex; flex-direction: column; align-items: stretch; text-align: left; position: absolute; top: 100%; left: 0; right: 0; gap: 0; background: linear-gradient(180deg, #15709e 0%, #0e567d 100%); padding: 6px 20px 90px; box-shadow: 0 8px 24px rgba(0,0,0,0.25); border-radius: 0 0 16px 16px; max-height: calc(100dvh - 70px); overflow-y: auto; -webkit-overflow-scrolling: touch; overscroll-behavior: contain; }
  .header-nav > a, .nav-dd > a { display: block; padding: 14px 4px; border-bottom: 1px solid rgba(255,255,255,0.14); font-size: 1rem; font-weight: 700; color: #fff !important; }
  .header-nav a::after { display: none; }
  .header-nav .header-cta-btn { display: block; background: #fff; color: #15709e !important; border: 0; border-radius: 999px; text-align: center; margin-top: 16px; padding: 13px; font-size: 0.98rem; font-weight: 700; }
  .header-nav .header-cta-btn:hover { background: #fff; color: #15709e !important; }
  .nav-dd-menu .nav-dd-top { display: none; }
  .nav-dd { display: block; border-bottom: 1px solid rgba(255,255,255,0.14); }
  .nav-dd > a { border-bottom: 0; }
  .nav-dd-menu { position: static; transform: none; opacity: 1; visibility: visible; box-shadow: none; background: transparent; padding: 0 0 12px 8px; min-width: 0; }
  .nav-dd-menu a { color: rgba(255,255,255,0.94) !important; opacity: 1; padding: 9px 12px; font-size: 0.88rem; font-weight: 500; border-left: 2px solid rgba(255,255,255,0.3); margin-left: 4px; white-space: normal; }
  .nav-dd-menu a:hover, .nav-dd-menu a:active { background: rgba(255,255,255,0.1); color: #fff !important; }
  .nav-dd-caret { display: none; }
  /* メニュー展開中は右固定タブ・文字サイズボタンを隠して重なりを防ぐ */
  body.sp-menu-open .side-tabs, body.sp-menu-open .fontsize-ctl { display: none !important; }
}
.header-cta-btn { display: inline-flex; align-items: center; gap: 6px; background: transparent; color: var(--color-green-mid) !important; padding: 9px 16px; white-space: nowrap; border: 1px solid var(--color-deep-green); border-radius: var(--radius); font-size: 0.78rem; font-weight: 500; letter-spacing: 0.06em; box-shadow: none !important; }
.header-cta-btn:hover { background: var(--color-deep-green); color: var(--color-white) !important; }
.header-cta-btn::after { display: none !important; }
.nav-toggle { display: none; background: none; border: none; cursor: pointer; width: 32px; height: 32px; position: relative; }
.nav-toggle span { display: block; width: 22px; height: 1.5px; background: var(--color-green-mid); position: absolute; left: 5px; transition: var(--transition); }
.nav-toggle span:nth-child(1) { top: 9px; }
.nav-toggle span:nth-child(2) { top: 15px; }
.nav-toggle span:nth-child(3) { top: 21px; }
.nav-toggle.is-open span:nth-child(1) { top: 15px; transform: rotate(45deg); }
.nav-toggle.is-open span:nth-child(2) { opacity: 0; }
.nav-toggle.is-open span:nth-child(3) { top: 15px; transform: rotate(-45deg); }

/* HERO */
.hero { position: relative; display: flex; align-items: center; overflow: hidden; background: var(--color-white); border-bottom: 1px solid var(--color-line); }
.hero-bg::after { content: ''; position: absolute; inset: 0; background: none; }
.hero-inner { position: relative; z-index: 2; max-width: 1080px; margin: 0 auto; padding: 150px 32px 110px; display: grid; grid-template-columns: 1.12fr 0.88fr; gap: 68px; align-items: center; }
.hero-badge { display: inline-flex; align-items: center; gap: 8px; background: transparent; border: 1px solid var(--color-border); padding: 7px 16px; border-radius: var(--radius); font-size: 0.72rem; color: var(--color-deep-green); letter-spacing: 0.1em; margin-bottom: 28px; }
.hero-badge::before { content: ''; width: 5px; height: 5px; border-radius: 50%; background: var(--color-gold); }
.hero-h1 { font-family: var(--font-serif); font-size: clamp(1.7rem, 3.4vw, 2.5rem); font-weight: 500; color: var(--color-green-mid); line-height: 2.0; letter-spacing: 0.06em; margin-bottom: 26px; }
.hero-h1 em { font-style: normal; color: var(--color-gold); border-bottom: 1px solid var(--color-gold-light); padding-bottom: 2px; }
.hero-sub { font-size: 0.95rem; color: var(--color-text-light); line-height: 2.35; margin-bottom: 40px; }
.hero-sub em { font-style: normal; color: var(--color-deep-green); font-weight: 500; }
.hero-ctas { display: flex; gap: 18px; flex-wrap: wrap; align-items: center; }
.btn-primary { display: inline-flex; align-items: center; gap: 9px; background: var(--color-deep-green); color: var(--color-white); padding: 15px 36px; border-radius: var(--radius); font-size: 0.9rem; font-weight: 500; letter-spacing: 0.05em; border: none; cursor: pointer; transition: var(--transition); box-shadow: none !important; }
.btn-primary:hover { background: var(--color-green-mid); transform: none; }
.btn-secondary { display: inline-flex; align-items: center; gap: 8px; background: transparent; color: var(--color-white); padding: 15px 34px; border-radius: var(--radius); font-size: 0.88rem; font-weight: 500; border: 1px solid rgba(255,255,255,0.4); cursor: pointer; transition: var(--transition); box-shadow: none !important; }
.btn-secondary:hover { background: rgba(255,255,255,0.1); border-color: rgba(255,255,255,0.6); }
.btn-line { background: #2f7d4f; box-shadow: none !important; }
.btn-line:hover { background: #266741; }
.hero-trust-card { background: var(--color-cream); backdrop-filter: none; border: 1px solid var(--color-border); box-shadow: none; border-radius: var(--radius); padding: 8px 34px; }
.hero-trust-title { font-family: var(--font-serif); font-size: 1rem; font-weight: 600; color: var(--color-green-mid); margin: 24px 0; padding-bottom: 0; border-bottom: none; letter-spacing: 0.06em; }
.trust-items { display: flex; flex-direction: column; gap: 0; }
.trust-item { display: flex; align-items: flex-start; gap: 18px; padding: 20px 0; border-top: 1px solid var(--color-line); }
.trust-icon { flex-shrink: 0; width: auto; height: auto; background: none; border-radius: 0; display: block; font-family: var(--font-display); font-size: 1.3rem; font-weight: 600; color: var(--color-gold); letter-spacing: 0.04em; line-height: 1.2; }
.trust-item-text h4 { font-family: var(--font-serif); font-size: 0.92rem; font-weight: 600; color: var(--color-green-mid); margin-bottom: 3px; letter-spacing: 0.04em; }
.trust-item-text p { font-size: 0.76rem; color: var(--color-text-light); line-height: 1.7; }
.hero-price-highlight { margin: 20px 0 12px; padding: 20px 0 4px; background: none; border-radius: 0; border: none; border-top: 1px solid var(--color-border); }
.hero-price-highlight p { font-size: 0.72rem; color: var(--color-text-light); margin-bottom: 12px; letter-spacing: 0.08em; }
.hero-price-highlight .prices { display: flex; gap: 28px; flex-wrap: wrap; align-items: baseline; }
.price-tag { font-family: var(--font-serif); font-size: 0.88rem; color: var(--color-green-mid); }
.price-tag strong { font-family: var(--font-display); font-size: 1.5rem; font-weight: 600; color: var(--color-gold); letter-spacing: 0.02em; }
@keyframes fadeInUp { from { opacity: 0; transform: translateY(18px); } to { opacity: 1; transform: translateY(0); } }
@keyframes fadeInDown { from { opacity: 0; transform: translateY(-12px); } to { opacity: 1; transform: translateY(0); } }
@keyframes fadeInRight { from { opacity: 0; transform: translateY(18px); } to { opacity: 1; transform: translateY(0); } }

/* STATS（重なり・カウントアップ演出を廃し、静かな数字帯に） */
.stats { position: relative; z-index: 10; margin-top: 0; background: var(--color-green-mid); }
.stats-inner { max-width: 1000px; margin: 0 auto; padding: 54px 32px; }
.stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 0; background: none; border-radius: 0; box-shadow: none; overflow: visible; }
.stat-card { text-align: center; padding: 6px 16px; position: relative; transition: none; }
.stat-card:not(:last-child)::after { content: ''; position: absolute; right: 0; top: 16%; height: 68%; width: 1px; background: rgba(255,255,255,0.16); }
.stat-card:hover--disabled { background: none; }
.stat-icon { display: none; }
.stat-number { font-family: var(--font-display); font-size: 2.9rem; font-weight: 500; color: var(--color-gold-light); line-height: 1; margin-bottom: 10px; letter-spacing: 0.01em; }
.stat-number .stat-unit { font-family: var(--font-serif); font-size: 0.82rem; font-weight: 400; color: rgba(255,255,255,0.72); margin-left: 4px; }
.stat-label { font-size: 0.76rem; color: rgba(255,255,255,0.68); font-weight: 400; letter-spacing: 0.06em; }

/* MEDIA COVERAGE */
.media-coverage { padding: 90px 0; background: var(--color-white); }
.media-coverage-header { text-align: center; margin-bottom: 48px; }
.media-coverage-grid { display: flex; justify-content: center; gap: 0; flex-wrap: wrap; max-width: 900px; margin: 0 auto; border: 1px solid var(--color-line); }
.media-coverage-item { display: flex; flex-direction: column; align-items: center; gap: 14px; padding: 34px 40px; background: var(--color-white); border-radius: 0; border: none; border-left: 1px solid var(--color-line); min-width: 200px; flex: 1; transition: var(--transition); }
.media-coverage-item:first-child { border-left: none; }
.media-coverage-item:hover--disabled { box-shadow: none; transform: none; background: var(--color-cream); }
.media-coverage-icon { width: 52px; height: 52px; display: flex; align-items: center; justify-content: center; }
.media-coverage-item h4 { font-family: var(--font-serif); font-size: 0.95rem; font-weight: 600; color: var(--color-green-mid); text-align: center; letter-spacing: 0.04em; }
.media-coverage-item p { font-size: 0.75rem; color: var(--color-text-light); text-align: center; line-height: 1.8; }
.media-coverage-note { text-align: center; margin-top: 26px; font-size: 0.76rem; color: var(--color-text-light); }
@media (max-width: 768px) { .media-coverage-grid { flex-direction: column; } .media-coverage-item { border-left: none; border-top: 1px solid var(--color-line); } .media-coverage-item:first-child { border-top: none; } }

/* WORRY */
.worry { padding: 100px 0 80px; background: var(--color-cream); position: relative; }
.worry::before { content: ''; position: absolute; top: 0; left: 50%; transform: translateX(-50%); width: 1px; height: 54px; background: linear-gradient(to bottom, var(--color-gold), transparent); }
.worry-header { display: flex; align-items: center; justify-content: center; gap: 34px; text-align: left; margin-bottom: 52px; }
.worry-header .section-label { justify-content: flex-start; }
.worry-header .section-title::before { margin: 0 0 16px; }
.worry-header__photo { width: 172px; height: 172px; flex: none; border-radius: 50%; border: 5px solid #fff; box-shadow: 0 8px 24px rgba(18,89,122,0.16); background: #fff; object-fit: cover; }
.worry-header__note { margin-top: 12px; font-size: 0.92rem; color: #5a6b69; }
@media (max-width: 640px) { .worry-header { gap: 12px; } .worry-header__photo { width: 104px; height: 104px; border-width: 3px; } .worry-header__note { font-size: 0.8rem; } }
.worry-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 0; max-width: 900px; margin: 0 auto 44px; border-top: 1px solid var(--color-line); border-left: 1px solid var(--color-line); }
.worry-card { background: none; border-radius: 0; padding: 24px 26px; display: flex; align-items: flex-start; gap: 15px; transition: var(--transition); border: none; border-right: 1px solid var(--color-line); border-bottom: 1px solid var(--color-line); }
.worry-card:hover { border-color: var(--color-line); box-shadow: none; background: var(--color-white); }
.worry-check { flex-shrink: 0; width: 24px; height: 24px; background: none; border: 1px solid var(--color-gold); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--color-gold); font-size: 0.72rem; font-weight: 600; }
.worry-card p { font-size: 0.9rem; line-height: 1.85; }
.worry-answer { text-align: center; padding: 40px 0 0; }
.worry-answer-text { font-family: var(--font-serif); font-size: clamp(1.15rem, 2.5vw, 1.55rem); color: var(--color-green-mid); font-weight: 500; line-height: 1.9; letter-spacing: 0.05em; }
.worry-answer-text em { font-style: normal; color: var(--color-gold); border-bottom: 1px solid var(--color-gold); padding-bottom: 2px; }

/* SERVICES */
.services { padding: 100px 0; background: var(--color-white); }
.services-header { text-align: center; margin-bottom: 56px; }
.services-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 0; border-top: 1px solid var(--color-line); border-left: 1px solid var(--color-line); }
.service-card { background: var(--color-white); border-radius: 0; overflow: hidden; transition: var(--transition); border: none; border-right: 1px solid var(--color-line); border-bottom: 1px solid var(--color-line); cursor: pointer; }
.service-card:hover { transform: none; box-shadow: none; background: var(--color-cream); }
.service-card-img { width: 100%; height: 190px; object-fit: cover; transition: var(--transition); filter: saturate(0.92); }
.service-card:hover .service-card-img { transform: none; }
.service-card-img-wrap { overflow: hidden; position: relative; }
.service-card-price { position: absolute; bottom: 0; right: 0; background: var(--color-green-mid); color: var(--color-white); padding: 6px 14px; border-radius: 0; font-size: 0.74rem; font-weight: 500; letter-spacing: 0.04em; }
.service-card-body { padding: 28px 26px; }
.service-card-body h3 { font-family: var(--font-serif); font-size: 1.1rem; font-weight: 600; color: var(--color-green-mid); margin-bottom: 10px; letter-spacing: 0.04em; }
.service-card-body p { font-size: 0.82rem; color: var(--color-text-light); line-height: 1.9; margin-bottom: 18px; }
.service-link { display: inline-flex; align-items: center; gap: 7px; font-size: 0.78rem; font-weight: 500; color: var(--color-green-mid); letter-spacing: 0.06em; padding-top: 14px; border-top: 1px solid var(--color-line); width: 100%; }
.service-link::after { content: '→'; transition: var(--transition); margin-left: auto; color: var(--color-gold); }
.service-card:hover .service-link::after { transform: translateX(4px); }

/* GALLERY */
.gallery { padding: 90px 0; background: var(--color-cream); overflow: hidden; }
.gallery-header { text-align: center; margin-bottom: 48px; }
.gallery-grid { display: flex; gap: 12px; overflow-x: auto; overflow-y: hidden; scroll-snap-type: x mandatory; -webkit-overflow-scrolling: touch; padding-bottom: 10px;
  scrollbar-width: thin; scrollbar-color: var(--color-green-light, #6fb1ad) transparent; }
.gallery-grid::-webkit-scrollbar { height: 6px; }
.gallery-grid::-webkit-scrollbar-thumb { background: var(--color-green-light, #6fb1ad); border-radius: 999px; }
.gallery-item { position: relative; overflow: hidden; border-radius: var(--radius, 10px); aspect-ratio: 4/3; cursor: pointer; flex: 0 0 auto; width: 262px; scroll-snap-align: start; }
.gallery-item img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.6s ease, filter 0.6s ease; filter: saturate(0.92); }
.gallery-item:hover img { transform: scale(1.05); filter: saturate(1); }
.gallery-item::after { content: ''; position: absolute; inset: 0; background: linear-gradient(to top, rgba(27,65,63,0.2) 0%, transparent 50%); opacity: 0; transition: opacity 0.3s ease; }
.gallery-item:hover::after { opacity: 1; }
@media (max-width: 768px) { .gallery-item { width: 220px; } }

/* FLOW */
.flow { padding: 100px 0; background: var(--color-white); }
.flow-header { text-align: center; margin-bottom: 60px; }
.flow-steps { display: grid; grid-template-columns: repeat(4, 1fr); gap: 28px; position: relative; }
.flow-steps::before { content: ''; position: absolute; top: 30px; left: 12%; right: 12%; height: 1px; background: var(--color-border); z-index: 0; }
.flow-step { text-align: center; position: relative; z-index: 1; }
.flow-step-num { display: inline-flex; align-items: center; justify-content: center; width: 62px; height: 62px; background: var(--color-white); border: 1px solid var(--color-gold); border-radius: 50%; font-family: var(--font-display); font-size: 1.3rem; font-weight: 600; color: var(--color-gold); margin-bottom: 22px; box-shadow: none; }
.flow-step h4 { font-family: var(--font-serif); font-size: 0.95rem; font-weight: 600; color: var(--color-green-mid); margin-bottom: 8px; letter-spacing: 0.04em; }
.flow-step p { font-size: 0.8rem; color: var(--color-text-light); line-height: 1.8; }

/* STAFF */
.staff { padding: 100px 0; background: var(--color-cream); position: relative; overflow: hidden; }
.staff-header { text-align: center; margin-bottom: 56px; }
.staff-card { display: grid; grid-template-columns: 280px 1fr; gap: 52px; align-items: center; max-width: 900px; margin: 0 auto; background: var(--color-white); border-radius: 0; padding: 44px; border: 1px solid var(--color-border); }
.staff-photo-wrap { position: relative; border-radius: 0; overflow: hidden; aspect-ratio: 3/4; background: var(--color-cream-dark); }
.staff-photo-placeholder { width: 100%; height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 12px; background: var(--color-green-mid); color: var(--color-white); }
.staff-photo-placeholder .staff-avatar { font-family: var(--font-serif); font-size: 3rem; opacity: 0.6; }
.staff-photo-placeholder p { font-size: 0.72rem; opacity: 0.5; }
.staff-badges { position: absolute; bottom: 12px; left: 12px; right: 12px; display: flex; flex-wrap: wrap; gap: 6px; }
.staff-badge { background: rgba(44,74,41,0.86); color: var(--color-white); padding: 4px 11px; border-radius: var(--radius); font-size: 0.64rem; font-weight: 500; letter-spacing: 0.04em; }
.staff-role { font-size: 0.78rem; color: var(--color-gold); font-weight: 500; margin-bottom: 6px; letter-spacing: 0.1em; }
.staff-name { font-family: var(--font-serif); font-size: 1.6rem; font-weight: 600; color: var(--color-green-mid); margin-bottom: 4px; letter-spacing: 0.06em; }
.staff-name-en { font-family: var(--font-display); font-size: 0.85rem; color: var(--color-text-light); letter-spacing: 0.14em; margin-bottom: 22px; }
.staff-message { font-size: 0.88rem; color: var(--color-text); line-height: 2.25; margin-bottom: 26px; }
.staff-certs h4 { font-size: 0.76rem; font-weight: 600; color: var(--color-green-mid); margin-bottom: 12px; padding-bottom: 8px; border-bottom: 1px solid var(--color-border); letter-spacing: 0.08em; }
.staff-cert-list { display: flex; flex-wrap: wrap; gap: 8px; }
.staff-cert { display: inline-flex; align-items: center; gap: 5px; background: var(--color-cream); padding: 6px 13px; border-radius: var(--radius); font-size: 0.72rem; font-weight: 400; color: var(--color-green-mid); border: 1px solid var(--color-border); }
.staff-cert::before { content: '—'; color: var(--color-gold); font-weight: 600; }
.staff-team { max-width: 900px; margin: 40px auto 0; text-align: center; }
.staff-team-link { display: inline-flex; align-items: center; gap: 7px; color: var(--color-green-mid); font-size: 0.84rem; font-weight: 500; letter-spacing: 0.06em; }
.staff-team-link:hover { color: var(--color-gold); }
.staff-team-link::after { content: '→'; color: var(--color-gold); }

/* TESTIMONIALS */
.testimonials { padding: 100px 0; background: var(--color-green-mid); position: relative; overflow: hidden; }
.testimonials::before { display: none; }
.testimonials .section-label { color: var(--color-gold-light); }
.testimonials .section-label::before { background: var(--color-gold-light); }
.testimonials .section-title { color: var(--color-white); }
.testimonials-header { text-align: center; margin-bottom: 56px; position: relative; z-index: 1; }
.testimonials-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px; position: relative; z-index: 1; }
.testimonial-card { background: rgba(255,255,255,0.96); border: none; border-radius: var(--radius); padding: 36px; transition: var(--transition); box-shadow: none; }
.testimonial-card:hover { background: var(--color-white); box-shadow: none; }
.testimonial-quote { font-size: 2.2rem; color: var(--color-gold); opacity: 0.55; font-family: var(--font-display); line-height: 1; margin-bottom: 10px; }
.testimonial-card p { font-size: 0.88rem; color: var(--color-text); line-height: 2.1; margin-bottom: 22px; }
.testimonial-meta { display: flex; align-items: center; gap: 12px; padding-top: 18px; border-top: 1px solid var(--color-border); }
.testimonial-avatar { width: 38px; height: 38px; border-radius: 50%; background: rgba(63,107,57,0.12); display: flex; align-items: center; justify-content: center; font-size: 0.9rem; color: var(--color-deep-green); font-family: var(--font-serif); }
.testimonial-meta-text span { display: block; font-size: 0.76rem; color: var(--color-text-light); }
.testimonial-meta-text strong { font-size: 0.82rem; color: var(--color-text); font-weight: 600; }
.testimonial-location { display: inline-flex; align-items: center; gap: 4px; background: rgba(63,107,57,0.1); padding: 3px 10px; border-radius: var(--radius); font-size: 0.64rem; font-weight: 500; margin-top: 8px; color: var(--color-deep-green); }
.testimonials-more { text-align: center; margin-top: 44px; position: relative; z-index: 1; }

/* STRENGTHS */
.strengths { padding: 100px 0; background: var(--color-white); }
.strengths-header { text-align: center; margin-bottom: 60px; }
.strengths-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 0; border-left: 1px solid var(--color-line); }
.strength-card { display: block; gap: 0; align-items: flex-start; padding: 8px 34px 20px; background: none; border-radius: 0; border: none; border-left: 1px solid var(--color-line); transition: var(--transition); }
.strength-card:first-child, .strengths-grid > .strength-card:nth-child(4) { }
.strength-card:hover--disabled { box-shadow: none; }
.strength-num { flex-shrink: 0; display: block; font-family: var(--font-display); font-size: 2rem; font-weight: 600; color: var(--color-gold); line-height: 1; padding-bottom: 16px; margin-bottom: 20px; border-bottom: 1px solid var(--color-border); }
.strength-card h4 { font-family: var(--font-serif); font-size: 1.02rem; font-weight: 600; color: var(--color-green-mid); margin-bottom: 10px; letter-spacing: 0.04em; }
.strength-card p { font-size: 0.82rem; color: var(--color-text-light); line-height: 1.95; }

/* COMPARISON */
.comparison { padding: 100px 0; background: var(--color-cream); }
.comparison-header { text-align: center; margin-bottom: 56px; }
.comparison-table-wrap { max-width: 900px; margin: 0 auto; border-radius: 0; overflow: hidden; box-shadow: none; background: var(--color-white); border: 1px solid var(--color-border); }
.comparison-table { width: 100%; border-collapse: collapse; }
.comparison-table thead tr { background: var(--color-green-mid); }
.comparison-table th { padding: 18px 20px; font-family: var(--font-serif); font-size: 0.9rem; font-weight: 500; color: var(--color-white); text-align: center; vertical-align: middle; letter-spacing: 0.04em; }
.comparison-table th:first-child { text-align: left; width: 30%; background: rgba(0,0,0,0.12); }
.comparison-table th.th-other { width: 30%; background: rgba(0,0,0,0.06); color: rgba(255,255,255,0.7); font-weight: 400; }
.comparison-table th.th-en { width: 40%; background: rgba(169,134,95,0.28); }
.th-en-badge { display: inline-block; background: var(--color-gold); color: var(--color-white); font-family: var(--font-sans); font-size: 0.58rem; font-weight: 600; padding: 2px 9px; border-radius: var(--radius); margin-left: 6px; vertical-align: middle; letter-spacing: 0.04em; }
.comparison-table tbody tr { border-bottom: 1px solid var(--color-line); transition: var(--transition); }
.comparison-table tbody tr:last-child { border-bottom: none; }
.comparison-table tbody tr:hover { background: rgba(63,107,57,0.03); }
.comparison-table td { padding: 18px 20px; font-size: 0.85rem; vertical-align: middle; line-height: 1.75; }
.comparison-table td:first-child { font-weight: 600; color: var(--color-green-mid); font-family: var(--font-serif); font-size: 0.88rem; background: rgba(246,241,232,0.6); border-right: 1px solid var(--color-line); }
.td-other { text-align: center; color: var(--color-text-light); border-right: 1px solid var(--color-line); }
.td-other .td-icon, .td-en .td-icon { display: block; font-size: 1.15rem; margin-bottom: 4px; }
.td-en { text-align: center; color: var(--color-green-mid); font-weight: 400; background: rgba(169,134,95,0.05); }
.td-en strong { color: var(--color-gold); font-weight: 700; }
.comparison-note { text-align: center; margin-top: 24px; font-size: 0.76rem; color: var(--color-text-light); }
.comparison-cta { text-align: center; margin-top: 40px; }

/* AREA */
.area { padding: 100px 0; background: var(--color-white); }
.area-header { text-align: center; margin-bottom: 56px; }
.area-content { display: grid; grid-template-columns: 1fr 1fr; gap: 52px; max-width: 900px; margin: 0 auto; align-items: start; }
.area-map-wrap { background: var(--color-cream); border-radius: 0; padding: 34px; border: 1px solid var(--color-border); text-align: center; }
.area-map-svg { width: 100%; max-width: 360px; margin: 0 auto; }
.area-primary { background: var(--color-green-mid); color: var(--color-white); border-radius: 0; padding: 30px; margin-bottom: 18px; }
.area-primary h4 { font-family: var(--font-serif); font-size: 1rem; font-weight: 600; margin-bottom: 12px; padding-bottom: 10px; border-bottom: 1px solid rgba(255,255,255,0.15); letter-spacing: 0.04em; }
.area-primary p { font-size: 0.85rem; line-height: 1.9; color: rgba(255,255,255,0.82); }
.area-nationwide { background: var(--color-cream); border-radius: 0; padding: 30px; border: 1px solid var(--color-border); }
.area-nationwide h4 { font-family: var(--font-serif); font-size: 1rem; font-weight: 600; color: var(--color-green-mid); margin-bottom: 12px; padding-bottom: 10px; border-bottom: 1px solid var(--color-border); }
.area-nationwide p { font-size: 0.85rem; line-height: 1.9; color: var(--color-text-light); }
.area-nationwide-badge { display: inline-flex; align-items: center; gap: 6px; background: var(--color-gold); color: var(--color-white); padding: 6px 14px; border-radius: var(--radius); font-size: 0.74rem; font-weight: 500; margin-top: 12px; }

/* MEDIA (activity) */
.media { padding: 100px 0; background: var(--color-cream); }
.media-header { text-align: center; margin-bottom: 56px; }
.media-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; max-width: 900px; margin: 0 auto; }
.media-card { background: var(--color-white); border-radius: 0; overflow: hidden; border: 1px solid var(--color-border); transition: var(--transition); text-align: center; }
a.media-card:hover { box-shadow: none; transform: none; background: var(--color-cream); }
.media-card-img-placeholder { width: 100%; height: 132px; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 8px; background: var(--color-cream-dark); border-bottom: 1px solid var(--color-line); }
.media-card-img { width: 100%; height: 150px; overflow: hidden; border-bottom: 1px solid var(--color-line); }
.media-card-img img { width: 100%; height: 100%; object-fit: cover; display: block; transition: transform 0.5s ease; }
aa.media-card:hover .media-card-img img { transform: scale(1.05); }
.media-card-img-placeholder .media-emoji { font-family: var(--font-serif); font-size: 1.9rem; color: var(--color-gold); opacity: 0.85; width: 52px; height: 52px; border: 1px solid var(--color-gold-light); border-radius: 50%; display: flex; align-items: center; justify-content: center; }
.media-card-body { padding: 18px 16px; }
.media-card-tag { display: inline-block; background: none; color: var(--color-gold); padding: 0 0 6px; border-radius: 0; font-size: 0.66rem; font-weight: 600; margin-bottom: 6px; letter-spacing: 0.1em; }
.media-card-body h4 { font-family: var(--font-serif); font-size: 0.82rem; font-weight: 600; color: var(--color-green-mid); line-height: 1.7; }
.media-card-body p { font-size: 0.72rem; color: var(--color-text-light); margin-top: 6px; font-family: var(--font-display); letter-spacing: 0.08em; }

/* FAQ */
.faq { padding: 100px 0; background: var(--color-white); }
.faq-header { text-align: center; margin-bottom: 56px; }
.faq-list { max-width: 780px; margin: 0 auto; display: flex; flex-direction: column; gap: 0; border-top: 1px solid var(--color-line); }
.faq-item { background: none; border-radius: 0; border: none; border-bottom: 1px solid var(--color-line); overflow: hidden; }
.faq-question { width: 100%; padding: 24px 8px; display: flex; align-items: center; justify-content: space-between; background: none; border: none; cursor: pointer; font-family: var(--font-sans); font-size: 0.92rem; font-weight: 500; color: var(--color-text); text-align: left; transition: var(--transition); }
.faq-question:hover { background: none; color: var(--color-deep-green); }
.faq-q-label { display: inline-flex; align-items: center; justify-content: center; width: auto; height: auto; background: none; color: var(--color-gold); border-radius: 0; font-family: var(--font-display); font-size: 1.1rem; font-weight: 600; margin-right: 16px; flex-shrink: 0; }
.faq-toggle { width: 20px; height: 20px; flex-shrink: 0; position: relative; }
.faq-toggle::before, .faq-toggle::after { content: ''; position: absolute; background: var(--color-gold); border-radius: 0; }
.faq-toggle::before { width: 13px; height: 1px; top: 50%; left: 50%; transform: translate(-50%, -50%); }
.faq-toggle::after { width: 1px; height: 13px; top: 50%; left: 50%; transform: translate(-50%, -50%); transition: var(--transition); }
.faq-item.open .faq-toggle::after { transform: translate(-50%, -50%) rotate(90deg); opacity: 0; }
.faq-answer { max-height: 0; overflow: hidden; transition: max-height 0.4s ease; }
.faq-answer-inner { padding: 0 8px 24px 40px; font-size: 0.85rem; color: var(--color-text-light); line-height: 2.1; }

/* BLOG */
.blog { padding: 100px 0; background: var(--color-cream); }
.blog-header { text-align: center; margin-bottom: 56px; }
.blog-grid { display: flex; gap: 24px; overflow-x: auto; scroll-snap-type: x mandatory; -webkit-overflow-scrolling: touch; padding: 4px 4px 14px; scrollbar-width: none; }
.blog-grid::-webkit-scrollbar { display: none; }
.blog-card { flex: 0 0 300px; scroll-snap-align: start; }
.tpb-wrap { position: relative; }
.tpb-arrow { position: absolute; top: 50%; transform: translateY(-50%); z-index: 2; width: 40px; height: 40px; border-radius: 50%; border: 1px solid var(--color-border); background: rgba(255,255,255,.95); color: var(--color-green-mid, #2e5030); font-size: 1.5rem; line-height: 1; cursor: pointer; box-shadow: 0 4px 14px rgba(40,60,50,.18); display: grid; place-items: center; padding: 0 0 3px; }
.tpb-arrow:hover { background: #fff; }
.tpb-arrow--prev { left: -14px; }
.tpb-arrow--next { right: -14px; }
.tpb-arrow[disabled] { opacity: .3; cursor: default; }
.tpb-hint { text-align: center; font-size: .74rem; color: var(--color-text-light, #7c8a88); margin-top: 2px; }
@media (max-width: 768px) { .blog-card { flex-basis: min(78vw, 300px); } .tpb-arrow { display: none; } }
.blog-card { background: var(--color-white); border-radius: 0; overflow: hidden; border: 1px solid var(--color-border); transition: var(--transition); cursor: pointer; }
.blog-card:hover { transform: none; box-shadow: none; background: var(--color-white); border-color: var(--color-gold-light); }
.blog-card-img { width: 100%; height: 190px; object-fit: cover; transition: var(--transition); filter: saturate(0.92); }
.blog-card:hover .blog-card-img { transform: none; }
.blog-card-img-wrap { overflow: hidden; position: relative; }
.blog-card-new { position: absolute; top: 0; left: 0; background: var(--color-gold); color: var(--color-white); padding: 3px 11px; border-radius: 0; font-size: 0.62rem; font-weight: 600; letter-spacing: 0.08em; }
.blog-card-body { padding: 22px 20px; }
.blog-card-date { font-size: 0.72rem; color: var(--color-gold); margin-bottom: 8px; font-family: var(--font-display); letter-spacing: 0.08em; }
.blog-card-cat { display: inline-block; margin-left: 6px; padding: 1px 9px; border-radius: 999px; background: var(--sea-light, #e3f1f8); color: var(--header-blue, #15709e); font-size: 0.64rem; font-weight: 600; letter-spacing: 0.02em; vertical-align: middle; font-family: inherit; }
.blog-card-body h4 { font-family: var(--font-serif); font-size: 0.92rem; font-weight: 600; color: var(--color-green-mid); line-height: 1.75; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
.blog-more { text-align: center; margin-top: 44px; }
.blog-more a { display: inline-flex; align-items: center; gap: 7px; color: var(--color-green-mid); font-size: 0.84rem; font-weight: 500; letter-spacing: 0.06em; }
.blog-more a:hover { color: var(--color-gold); }
.blog-more a::after { content: '→'; color: var(--color-gold); }

/* CTA（グラデ廃止・深緑ベタ＋静かな金） */
.cta-section .container { position: relative; z-index: 1; }
.cta-title { font-family: var(--font-serif); font-size: clamp(1.4rem, 3vw, 1.9rem); color: var(--color-green-mid); font-weight: 500; line-height: 1.8; margin-bottom: 14px; letter-spacing: 0.05em; }
.cta-sub { font-size: 0.9rem; color: var(--color-text-light); margin-bottom: 40px; }
.cta-buttons { display: flex; gap: 18px; justify-content: center; flex-wrap: wrap; margin-bottom: 28px; }
.cta-tel { font-family: var(--font-display); font-size: 1.9rem; color: var(--color-green-mid); letter-spacing: 0.06em; }
.cta-tel-note { font-size: 0.74rem; color: var(--color-text-light); margin-top: 6px; }

/* FOOTER */
.footer { background: var(--color-green-mid); padding: 70px 0 30px; color: rgba(255,255,255,0.6); }
.footer-inner { display: grid; grid-template-columns: 1fr 2fr; gap: 52px; margin-bottom: 44px; }
.footer-brand h3 { font-family: var(--font-serif); font-size: 1.15rem; color: var(--color-white); margin-bottom: 14px; letter-spacing: 0.1em; }
.footer-brand p { font-size: 0.8rem; line-height: 1.9; }
.footer-brand .footer-tel { display: block; font-family: var(--font-display); font-size: 1.3rem; color: var(--color-gold-light); margin-top: 14px; letter-spacing: 0.04em; }
.footer-nav-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; }
.footer-nav-col h4 { font-size: 0.8rem; font-weight: 600; color: var(--color-white); margin-bottom: 14px; letter-spacing: 0.06em; }
.footer-nav-col ul { list-style: none; }
.footer-nav-col li { margin-bottom: 9px; }
.footer-nav-col a { font-size: 0.78rem; color: rgba(255,255,255,0.5); transition: var(--transition); }
.footer-nav-col a:hover { color: var(--color-gold-light); }
.footer-assoc { display: flex; align-items: center; gap: 12px; padding: 18px 0; border-top: 1px solid rgba(255,255,255,0.1); margin-bottom: 20px; }
.footer-assoc p { font-size: 0.72rem; color: rgba(255,255,255,0.4); letter-spacing: 0.04em; }
.footer-bottom { border-top: 1px solid rgba(255,255,255,0.08); padding-top: 22px; text-align: center; font-size: 0.72rem; color: rgba(255,255,255,0.35); letter-spacing: 0.04em; }

/* STICKY */
.sticky-cta { display: none; position: fixed; bottom: 0; left: 0; right: 0; z-index: 99; }
.sticky-cta-inner { display: flex; }
.sticky-btn { flex: 1; display: flex; align-items: center; justify-content: center; gap: 6px; padding: 14px 2px; border-radius: 0 !important; font-size: 0.88rem; font-weight: 600; text-align: center; border: none; cursor: pointer; letter-spacing: 0.04em; white-space: nowrap; }
.sticky-btn-tel { background: #15709e; color: var(--color-white); }
.sticky-btn-mail { background: #2b7d76; color: var(--color-white); }
.sticky-btn-line { background: #2f7d4f; color: var(--color-white); }
.sticky-btn-sched { background: linear-gradient(135deg,#fffdf9,#fdf3dd); border-top: 3px solid #c9a25a; padding: 6px 4px; gap: 7px; }
.sticky-sched-photo { width: 34px; height: 34px; border-radius: 50%; object-fit: cover; object-position: 62% 55%; border: 1.5px solid #e6cf9a; flex: none; }
.sticky-sched-txt { display: flex; flex-direction: column; line-height: 1.25; text-align: left; }
.sticky-sched-label { font-size: 0.56rem; font-weight: 700; color: #b08b3e; letter-spacing: 0.03em; white-space: nowrap; }
.sticky-sched-date { font-size: 0.92rem; font-weight: 700; color: #1c3b2a; font-family: var(--font-serif); white-space: nowrap; }

/* SP専用ナビリンク（PCのヘッダーには表示しない） */
.nav-sp-only { display: none; }
.header-nav.is-open .nav-sp-only { display: flex; }
.header-nav.is-open .nav-sp-only--sub { font-size: 0.85rem; opacity: 0.85; }

/* RESPONSIVE */
@media (max-width: 1024px) {
  .header-nav { display: none; } .nav-toggle { display: block; }
  .hero-inner { grid-template-columns: 1fr; gap: 44px; padding-top: 120px; }
  .hero-trust-card { max-width: 520px; }
  .stats-grid { grid-template-columns: repeat(2, 1fr); gap: 32px 0; }
  .stat-card:nth-child(2)::after { display: none; }
  .services-grid { grid-template-columns: repeat(2, 1fr); }
  .flow-steps { grid-template-columns: repeat(2, 1fr); gap: 36px; } .flow-steps::before { display: none; }
  .staff-card { grid-template-columns: 1fr; gap: 32px; } .staff-photo-wrap { max-width: 300px; margin: 0 auto; }
  .testimonials-grid { grid-template-columns: repeat(2, 1fr); }
  .strengths-grid { grid-template-columns: repeat(2, 1fr); }
  .area-content { grid-template-columns: 1fr; }
  .media-grid { grid-template-columns: repeat(2, 1fr); }
  .footer-inner { grid-template-columns: 1fr; }
}
@media (max-width: 768px) {
  .hero { min-height: auto; } .hero-inner { padding: 100px 24px 64px; }
  .hero-h1 { font-size: 1.34rem; line-height: 1.7; letter-spacing: 0.02em; margin-bottom: 16px; }
  .hero-ctas { flex-direction: column; align-items: stretch; } .hero-ctas a { width: 100%; text-align: center; justify-content: center; }
  .hero-price-highlight .prices { flex-direction: column; gap: 10px; }
  .stats { margin-top: 0; } .stat-card { padding: 16px; } .stat-number { font-size: 2.2rem; }
  .services-grid { grid-template-columns: 1fr; }
  .strengths-grid { grid-template-columns: 1fr; } .strength-card { border-left: none; border-top: 1px solid var(--color-line); padding: 24px 0; }
  .strengths-grid { border-left: none; }
  .testimonials-grid { grid-template-columns: 1fr; }
  .comparison-table th, .comparison-table td { padding: 14px 10px; font-size: 0.75rem; }
  .comparison-table th:first-child { width: 26%; } .th-en-badge { display: block; margin: 4px auto 0; }
  .comparison-table-wrap { margin: 0 -8px; }

  .media-grid { grid-template-columns: 1fr 1fr; }
  .footer-nav-grid { grid-template-columns: 1fr 1fr; }
  .sticky-cta { display: block; } .footer { padding-bottom: 80px; } body { padding-bottom: 64px; }
}

/* ============================================================
   波の曲線モチーフ（海洋葬らしさ）
   - .wave-top : 深海バンド（stats / testimonials / footer）が
                 波の稜線を描いて立ち上がる区切り
   - .hero-wave-lines : ヒーロー下部の波しぶきの線
   ============================================================ */
.stats, .testimonials, .footer { position: relative; }
.wave-top { position: absolute; left: 0; bottom: 100%; width: 100%; height: 48px; display: block; line-height: 0; pointer-events: none; }
.wave-top svg { display: block; width: 100%; height: 100%; }
.wave-top .wave-fill { fill: var(--color-green-mid); }
@media (max-width: 768px) { .wave-top { height: 30px; } }
/* 水色帯の下側の波（上側 .wave-top と対、上下で波デザインに） */
.wave-bottom { position: absolute; left: 0; top: 100%; width: 100%; height: 48px; display: block; line-height: 0; pointer-events: none; z-index: 2; }
.wave-bottom svg { display: block; width: 100%; height: 100%; }
.wave-bottom .wave-fill { fill: #b8e0ee; }
@media (max-width: 768px) { .wave-bottom { height: 30px; } }

.hero { position: relative; }
.hero-wave-lines { position: absolute; left: 0; right: 0; bottom: 46px; width: 100%; z-index: 1; opacity: 0.8; pointer-events: none; }
.hero-wave-lines svg { display: block; width: 100%; height: auto; }
@media (max-width: 768px) { .hero-wave-lines { display: none; } }

/* 砂浜と潮の交互リズム：一部の生成りセクションを寒色寄りの紙に */
.services { background: var(--color-white); }
.comparison { background: var(--color-paper-cool); }
.blog { background: var(--color-paper-cool); }

/* ============================================================
   明るい・オーシャンブルー・柔らか（角丸）方向への調整
   （参考トーン: arluis.com のような明るいリゾート感）
   後段オーバーライドとして最終適用。
   ============================================================ */
:root {
  --color-ocean: #1f8fce;        /* ボタン等のオーシャンブルー */
  --color-ocean-dark: #1774a8;
  --color-sea-light: #eaf6f7;    /* 明るい潮色バンド */
  --radius: 12px;
  --radius-lg: 18px;
  --shadow-card: 0 10px 30px rgba(20,74,88,0.07);
  --shadow-hover: 0 16px 40px rgba(20,74,88,0.11);
}
body { background: #f7f5ef; }

/* 角丸を全体へ（柔らかさ） */
[class]{ }
.hero-badge, .header-cta-btn, .btn-primary, .btn-secondary, .btn-line,
.service-card-price, .staff-badge, .staff-cert, .testimonial-location,
.media-card-tag, .area-nationwide-badge, .th-en-badge, .blog-card-new,
/* .sticky-btn は下層と同じフラットデザイン（丸ピル化しない） */

/* ボタン：オーシャンブルー */
.btn-primary { background: var(--color-ocean); }
.btn-primary:hover { background: var(--color-ocean-dark); }
.header-cta-btn { border-color: var(--color-ocean); color: var(--color-ocean) !important; }
.header-cta-btn:hover { background: var(--color-ocean); color: #fff !important; }
.service-card-price { background: var(--color-ocean); border-radius: 999px; }
/* .sticky-btn-tel は共通バー色（#15709e）を使用 */
.testimonials-more .btn-secondary { color: var(--color-ocean); border-color: var(--color-ocean); }
.testimonials-more .btn-secondary:hover { background: rgba(31,143,206,0.08); border-color: var(--color-ocean); }
/* 塗りつぶしオーシャンブルーのCTAボタン（背景と同化しない強調用） */
.btn-ocean { display: inline-flex; background: var(--color-ocean) !important; color: #fff !important; border-color: var(--color-ocean) !important; font-weight: 600; box-shadow: 0 4px 14px rgba(18,89,122,0.25) !important; }
.btn-ocean:hover { background: var(--color-ocean-dark) !important; border-color: var(--color-ocean-dark) !important; color: #fff !important; }
.header-nav a::after, .flow-step-num { border-color: var(--color-ocean); }

/* 明るいバンド（深緑帯→淡い潮色） */
.stats { background: var(--color-sea-light); }
.stats .stat-number { color: var(--color-ocean); }
.stats .stat-number .stat-unit { color: var(--color-deep-green); }
.stats .stat-label { color: var(--color-text-light); }
.stats .stat-card:not(:last-child)::after { background: rgba(39,92,88,0.16); }
.stats .wave-fill { fill: #b8e0ee; }

.testimonials { background: var(--color-sea-light); }
.testimonials .section-title { color: var(--color-deep-green); }
.testimonials .section-label { color: var(--color-gold); }
.testimonials .wave-fill { fill: #b8e0ee; }
.testimonial-card { border: 1px solid var(--color-line); box-shadow: var(--shadow-card); border-radius: var(--radius-lg); }
.testimonial-avatar { background: var(--color-ocean); }

.footer { background: #245f59; }
.footer .wave-fill { fill: #245f59; }

/* カード類：角丸＋やわらかい影＋間隔（枠に柔らかさ） */
.services-grid { border: none; gap: 22px; }
.service-card { border: 1px solid var(--color-line); border-radius: var(--radius-lg); box-shadow: var(--shadow-card); overflow: hidden; }
.service-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-hover); background: var(--color-white); }
.service-card:hover .service-card-img { transform: scale(1.03); }

.worry-grid { border: none; gap: 18px; }
.worry-card { border: 1px solid var(--color-line); border-radius: var(--radius); box-shadow: var(--shadow-card); background: var(--color-white); }

.strengths-grid { border: none; gap: 22px; }
.strength-card { border: 1px solid var(--color-line); border-radius: var(--radius-lg); box-shadow: var(--shadow-card); padding: 32px 30px; background: var(--color-white); }
.strength-card:hover--disabled { transform: translateY(-3px); box-shadow: var(--shadow-hover); }

.media-coverage-grid { border: none; gap: 18px; }
.media-coverage-item { border: 1px solid var(--color-line); border-radius: var(--radius-lg); box-shadow: var(--shadow-card); }
.media-coverage-item:first-child { border: 1px solid var(--color-line); }

.blog-card, .media-card { border-radius: var(--radius-lg); box-shadow: var(--shadow-card); }
.blog-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-hover); }
a.media-card:hover { transform: translateY(-3px); box-shadow: var(--shadow-hover); }

.faq-list { border-top: none; gap: 12px; }
.faq-item { border: 1px solid var(--color-line); border-radius: var(--radius); }

.hero-trust-card { border-radius: var(--radius-lg); box-shadow: var(--shadow-card); background: #fff; }
.comparison-table-wrap { border-radius: var(--radius-lg); box-shadow: var(--shadow-card); }
.staff-card { border-radius: var(--radius-lg); box-shadow: var(--shadow-card); }
.staff-photo-wrap { border-radius: var(--radius); }
.area-map-wrap, .area-primary, .area-nationwide { border-radius: var(--radius-lg); }
.area-map-svg rect { rx: 10; }

/* 横幅いっぱいの画像バンド */
.fullbleed { position: relative; width: 100%; min-height: clamp(280px, 40vw, 480px); background-position: center; background-size: cover; background-repeat: no-repeat; display: flex; align-items: center; justify-content: center; text-align: center; }
.fullbleed::before { content: ''; position: absolute; inset: 0; background: linear-gradient(180deg, rgba(12,58,78,0.30) 0%, rgba(12,58,78,0.12) 55%, rgba(12,58,78,0.22) 100%); }
.fullbleed .fb-inner { position: relative; z-index: 1; color: #fff; padding: 0 24px; }
.fullbleed .fb-kicker { font-family: var(--font-display); letter-spacing: 0.34em; text-transform: uppercase; font-size: 0.8rem; color: #fff; opacity: 0.9; display: block; margin-bottom: 16px; }
.fullbleed h2 { font-family: var(--font-serif); font-weight: 500; font-size: clamp(1.5rem, 3.2vw, 2.3rem); line-height: 1.9; letter-spacing: 0.08em; text-shadow: 0 2px 16px rgba(0,0,0,0.25); }

/* ============================================================
   フォントを Win/Mac 標準の見やすいものへ ＋ 背景のベージュを除去
   （Webフォント依存をやめ、OS標準フォントで自然に表示）
   ============================================================ */
:root {
  /* 標準フォント：本文=OS標準ゴシック / 見出し=OS標準明朝 / 数字=Georgia */
  --font-sans: -apple-system, BlinkMacSystemFont, "Segoe UI", "Hiragino Kaku Gothic ProN", "Hiragino Sans", "Yu Gothic", "Yu Gothic UI", "Meiryo", sans-serif;
  --font-serif: "Hiragino Mincho ProN", "Yu Mincho", "YuMincho", "MS PMincho", serif;
  --font-display: Georgia, "Times New Roman", serif;
  /* ベージュ（生成り）を外し、寒色寄りのニュートラルへ */
  --color-cream: #f4f7f8;        /* 旧: 生成り → 明るいクール系 */
  --color-cream-dark: #e7edf0;
  --color-paper-cool: #eef3f2;
  --color-white: #ffffff;        /* 旧: 温白色 → 純白 */
  --color-line: rgba(30,45,50,0.10);
}
body { background: #ffffff; }
.header { background: rgba(255,255,255,0.88); }
.blog, .comparison { background: var(--color-paper-cool); }

/* ============================================================
   「縁が選ばれる理由」をヒーローから独立バンドへ移設
   ============================================================ */
/* ヒーローは1カラムの大きなビジュアルに */
.hero-inner { grid-template-columns: 1fr; max-width: 900px; }
.hero-content { max-width: 40em; }

/* 移設先：ヒーロー直下の独立バンド */
.reasons-band { padding: 86px 0 76px; background: var(--color-white); }
.reasons-band .rb-head { text-align: center; margin-bottom: 46px; }
.reasons-band .rb-head .section-label { justify-content: center; }
.reasons-row { display: grid; grid-template-columns: repeat(3, 1fr); gap: 22px; }
.rb-item { border: 1px solid var(--color-line); border-radius: var(--radius-lg); box-shadow: var(--shadow-card); padding: 36px 30px; text-align: center; background: var(--color-white); transition: var(--transition); }

.rb-no { display: block; font-family: var(--font-display); font-size: 1.9rem; color: var(--color-ocean); margin-bottom: 14px; letter-spacing: 0.04em; }
.rb-item h4 { font-family: var(--font-serif); font-size: 1.06rem; font-weight: 600; color: var(--color-deep-green); margin-bottom: 12px; letter-spacing: 0.04em; }
.rb-item p { font-size: 0.85rem; color: var(--color-text-light); line-height: 1.95; }
.rb-price { margin-top: 32px; display: flex; justify-content: center; align-items: baseline; gap: 28px; flex-wrap: wrap; padding-top: 28px; border-top: 1px solid var(--color-line); }
.rb-price-label { font-size: 0.78rem; color: var(--color-text-light); letter-spacing: 0.08em; }
.rb-price .price-tag { font-family: var(--font-serif); font-size: 0.9rem; color: var(--color-deep-green); }
.rb-price .price-tag strong { font-family: var(--font-display); font-size: 1.5rem; color: var(--color-gold); margin: 0 2px; }
@media (max-width: 768px) { .reasons-row { grid-template-columns: 1fr; } }

/* ============================================================
   もっと明るく（arluis 調：白基調・エアリー・寒色ブルー＋ゴールド）
   ============================================================ */
:root { --color-sea-light: #eef7f8; --color-paper-cool: #f1f7f8; }
body { background: #ffffff; }
.header { background: rgba(255,255,255,0.92); }
/* 見出しを少し明るいティールへ */
.section-title, .hero-h1 { color: #2b7d76; }
.hero-h1 em { color: var(--color-gold); }
/* 余白を増やして軽やかに */
.reasons-band, .services, .strengths, .flow, .staff, .testimonials, .comparison,
.area, .media, .faq, .blog, .worry, .gallery, .media-coverage { padding-top: 118px; padding-bottom: 118px; }
/* 濃い面を明るく：フッター（濃緑→ライト＋濃色テキスト） */
.footer { background: #eef4f5; color: #5f6d6b; border-top: 1px solid var(--color-line); }
.footer .wave-fill { fill: #eef4f5; }
.footer-brand h3, .footer-nav-col h4 { color: var(--color-deep-green); }
.footer-brand p, .footer-nav-col a { color: #6b7573; }
.footer-brand .footer-tel { color: var(--color-ocean); }
.footer-nav-col a:hover { color: var(--color-ocean); }
.footer-assoc { border-top-color: var(--color-line); }
.footer-assoc p { color: #8a938f; }
.footer-bottom { border-top-color: var(--color-line); color: #9aa3a0; }
/* 拠点エリアの濃緑パネルを明るく */
.area-primary { background: var(--color-sea-light); color: var(--color-text); }
.area-primary h4 { color: var(--color-deep-green); border-bottom-color: var(--color-line); }
.area-primary p { color: var(--color-text-light); }
/* 比較表ヘッダをオーシャンブルーの明るいアクセントに */
.comparison-table thead tr { background: var(--color-ocean); }
.comparison-table th:first-child { background: rgba(255,255,255,0.12); }
.comparison-table th.th-other { background: rgba(255,255,255,0.14); color: rgba(255,255,255,0.9); }
.comparison-table th.th-en { background: rgba(255,255,255,0.22); }
/* 全幅画像バンドのオーバーレイを軽く（明るく） */
.fullbleed::before { background: linear-gradient(180deg, rgba(10,45,60,0.14) 0%, rgba(10,45,60,0.26) 100%); }
.fullbleed h2, .fullbleed .fb-kicker { text-shadow: 0 2px 20px rgba(0,0,0,0.32); }

/* ============================================================
   メインビジュアル（ヒーロー）の波デザインを復活・強調
   ============================================================ */
/* ヒーロー下端の塗り波（写真が波形に切り替わる＝白いバンドへつながる） */
.hero-wave-bottom { position: absolute; left: 0; bottom: -1px; width: 100%; line-height: 0; z-index: 3; pointer-events: none; }
.hero-wave-bottom svg { display: block; width: 100%; height: 72px; }
.hero-wave-bottom .wave-fill-1 { fill: rgba(31,143,206,0.16); }
.hero-wave-bottom .wave-fill-2 { fill: rgba(76,146,141,0.20); }
.hero-wave-bottom .wave-fill-3 { fill: #ffffff; }
@media (max-width: 768px) { .hero-wave-bottom svg { height: 44px; } }
/* 線の波を、明るい写真でも見える寒色に */
.hero-wave-lines { bottom: 92px; opacity: 0.9; }
.hero-wave-lines path:nth-child(1) { stroke: #1f8fce !important; stroke-opacity: 0.55 !important; stroke-width: 1.6 !important; }
.hero-wave-lines path:nth-child(2) { stroke: #4c928d !important; stroke-opacity: 0.5 !important; stroke-width: 1.4 !important; }

/* ============================================================
   ヒーローの波を整理：横切る細い波線を撤去し、下端の塗り波のみに
   ============================================================ */
.hero-wave-lines { display: none !important; }
.hero-wave-bottom svg { height: 84px; }
.hero-wave-bottom .wave-fill-1 { fill: rgba(31,143,206,0.14); }
.hero-wave-bottom .wave-fill-2 { fill: rgba(76,146,141,0.16); }
.hero-wave-bottom .wave-fill-3 { fill: #ffffff; }
@media (max-width: 768px) { .hero-wave-bottom svg { height: 50px; } }

/* ============================================================
   コンパクト化（accordia 調：余白を詰めて見やすく・密度を上げる）
   ============================================================ */
:root { --sec-pad: 58px; }
body { line-height: 1.8; }
/* セクション上下余白を圧縮 */
.reasons-band, .services, .strengths, .flow, .staff, .testimonials, .comparison,
.area, .media, .faq, .blog, .worry, .gallery, .media-coverage { padding-top: var(--sec-pad) !important; padding-bottom: var(--sec-pad) !important; }
.stats-inner { padding: 34px 32px; }
.cta-section { padding: 60px 0 !important; }
.footer { padding: 46px 0 26px; }
/* 見出し下の余白を詰める */
.services-header, .flow-header, .staff-header, .testimonials-header, .strengths-header,
.comparison-header, .area-header, .media-header, .faq-header, .blog-header,
.media-coverage-header, .gallery-header, .worry-header, .reasons-band .rb-head { margin-bottom: 30px !important; }
.section-title { margin-bottom: 12px; }
.section-label { margin-bottom: 12px; }
.worry::before { height: 34px; }
.worry-answer { padding-top: 26px; }
/* グリッド間隔を少し詰める */
.services-grid, .strengths-grid, .reasons-row { gap: 16px; }
.blog-grid, .media-grid, .testimonials-grid { gap: 18px; }
.worry-grid { gap: 14px; }
.flow-steps { gap: 20px; }
/* カード内側もコンパクトに */
.service-card-body { padding: 22px 22px; }
.rb-item { padding: 28px 24px; }
.strength-card { padding: 26px 26px; }
.worry-card { padding: 18px 20px; }
.testimonial-card { padding: 28px; }
.staff-card { padding: 34px; gap: 40px; }
/* ヒーローを少し低く */
.hero-inner { padding: 116px 32px 80px; }
/* 行間を詰める（読みやすさは維持） */
.hero-sub { line-height: 2.0; margin-bottom: 30px; }
.section-desc { line-height: 1.9; }
.service-card-body p, .rb-item p, .strength-card p, .worry-card p, .testimonial-card p { line-height: 1.8; }
.rb-price { margin-top: 24px; padding-top: 22px; }

/* ============================================================
   ヘッダー背景を青系（オーシャンブルー）に
   ============================================================ */
.header { background: #15709e; backdrop-filter: none; -webkit-backdrop-filter: none; border-bottom: 1px solid rgba(255,255,255,0.14); }
.header-logo-text { color: #ffffff; }
.header-nav a { color: #ffffff; }
.header-nav a::after { background: #ffffff; }
.header-cta-btn { background: transparent; color: #ffffff !important; border-color: rgba(255,255,255,0.8); }
.header-cta-btn:hover { background: #ffffff; color: #15709e !important; }
.nav-toggle span { background: #ffffff; }

/* ============================================================
   「こんなお悩みはありませんか？」を横幅いっぱいの背景画像でおしゃれに
   背景実画像は /assets/img/onayami-bg.jpg に配置（無い場合は明るい水色で表示）
   ============================================================ */
.worry { position: relative; overflow: visible; padding: 82px 0 !important;
  background: linear-gradient(120deg, #e6f4f8 0%, #bfe6f1 55%, #a8dced 100%); }
.worry::before { display: none; }               /* 旧・縦罫を撤去 */
.worry::after { display: none; }                /* 旧・非存在画像オーバーレイを撤去 */
.worry > .container { position: relative; z-index: 1; }
.worry .wave-top .wave-fill { fill: #b8e0ee; }   /* はっきり見えるソフトなオーシャンブルー */
.worry .wave-bottom .wave-fill { fill: #b8e0ee; }
.worry-header .section-title { color: #12597a; }
.worry-card { background: rgba(255,255,255,0.86); border: 1px solid rgba(255,255,255,0.9);
  box-shadow: 0 12px 34px rgba(18,89,122,0.14); backdrop-filter: blur(3px); -webkit-backdrop-filter: blur(3px); }
.worry-card p { color: #2b3a3a; }
.worry-check { background: var(--color-ocean); border: 1px solid var(--color-ocean); color: #ffffff; }
.worry-answer-text { color: #12597a; }
.worry-answer-text em { color: var(--color-ocean); border-bottom-color: var(--color-ocean); }

/* ============================================================
   ご利用の流れの下：打ち合わせ・作業風景の横自動スクロール（arluis 参考）
   ============================================================ */
.scene-marquee { padding: 4px 0 58px; background: var(--color-white); overflow: hidden; }
.scene-marquee .sm-cap { text-align: center; margin-bottom: 22px; }
.scene-marquee .sm-cap .section-label { justify-content: center; margin-bottom: 0; }
.marquee { overflow: hidden; width: 100%;
  -webkit-mask-image: linear-gradient(90deg, transparent, #000 6%, #000 94%, transparent);
          mask-image: linear-gradient(90deg, transparent, #000 6%, #000 94%, transparent); }
.marquee-track { display: flex; gap: 14px; width: max-content; animation: sceneScroll 40s linear infinite; }
.marquee:hover .marquee-track { animation-play-state: paused; }
.mq-item { flex: 0 0 auto; width: 230px; height: 154px; border-radius: var(--radius-lg); overflow: hidden;
  box-shadow: var(--shadow-card); background: linear-gradient(135deg, #e3eef1, #d3e5eb); }
.mq-item img { width: 100%; height: 100%; object-fit: cover; display: block; }
@keyframes sceneScroll { from { transform: translateX(0); } to { transform: translateX(-50%); } }
@media (max-width: 768px) { .mq-item { width: 180px; height: 120px; } }

/* ============================================================
   各ブロックのタイトル文字の上に波線デザインを設置
   （英語ラベル横の小波は撤去し、波はタイトル上へ一本化）
   ============================================================ */
.section-label::before { display: none; }
.section-title::before {
  content: ''; display: block; width: 58px; height: 12px; margin: 0 auto 16px;
  background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='58' height='12' viewBox='0 0 58 12' fill='none' stroke='%232e9fd4' stroke-width='1.7' stroke-linecap='round'%3E%3Cpath d='M1 7 C 8 1.5, 15 1.5, 21 7 C 27 12.5, 34 12.5, 40 7 C 46 1.5, 52 1.5, 57 6'/%3E%3C/svg%3E") no-repeat center/contain;
}
/* 左寄せ見出し（サービス詳細レンダラ等）でも中央の波は左端起点に */
.services-header .section-title::before, .flow-header .section-title::before,
.staff-header .section-title::before, .testimonials-header .section-title::before,
.strengths-header .section-title::before, .comparison-header .section-title::before,
.area-header .section-title::before, .media-header .section-title::before,
.faq-header .section-title::before, .blog-header .section-title::before,
.media-coverage-header .section-title::before, .gallery-header .section-title::before,
.worry-header .section-title::before, .reasons-band .rb-head .section-title::before { margin: 0 auto 16px; }
/* テスティモニアル帯は濃色ではないが、波は視認できる青のまま */

/* ============================================================
   ヘッダーロゴ：画像と社名を横並び（下に回り込むのを修正）
   ============================================================ */
.header-logo { display: inline-flex; align-items: center; gap: 10px; }
.header-logo img { display: block; height: 40px; width: auto; margin: 0 !important; }
.header-logo-text { line-height: 1; }

/* ============================================================
   お悩み解決メッセージに写真バナー（明るい左側にメッセージ重ね）
   実画像は assets/img/onayami-answer.jpg に配置
   ============================================================ */
.worry-answer { padding-top: 36px; }
.worry-answer-banner { position: relative; border-radius: var(--radius-lg); overflow: hidden;
  min-height: 320px; display: flex; align-items: center;
  background: #cfe6ee url('/assets/img/onayami-answer.jpg') center/cover no-repeat;
  box-shadow: 0 14px 40px rgba(18,89,122,0.16); }
.worry-answer-banner::before { content: ''; position: absolute; inset: 0;
  background: linear-gradient(90deg, rgba(255,255,255,0.92) 0%, rgba(255,255,255,0.64) 38%, rgba(255,255,255,0.12) 66%, rgba(255,255,255,0) 82%); }
.worry-answer .worry-answer-text { position: relative; z-index: 1; text-align: left; max-width: 58%; padding: 44px 52px; margin: 0; }
@media (max-width: 768px) {
  .worry-answer-banner { min-height: 300px; align-items: flex-end; }
  .worry-answer-banner::before { background: linear-gradient(180deg, rgba(255,255,255,0) 28%, rgba(255,255,255,0.9) 100%); }
  .worry-answer .worry-answer-text { max-width: 100%; padding: 24px 24px 30px; }
}


/* --- 背景写真（現行画像を保持） --- */
.hero-bg { position: absolute; inset: 0; background: linear-gradient(135deg, rgba(255,255,255,0.90) 0%, rgba(255,255,255,0.78) 50%, rgba(255,255,255,0.88) 100%), url('/assets/img/top/top-banner-bg.jpg?v=<?= h(asset_ver()) ?>') center/cover no-repeat; }
.cta-section { padding: 60px 0 !important; }

</style>
<!-- ============================================
     LLMO: JSON-LD 構造化データ
     ============================================ -->
<!-- 1. WebSite -->
<script type="application/ld+json">
{"@context":"https://schema.org","@type":"WebSite","name":"有限会社 縁","alternateName":["縁","en1150","鹿児島海洋散骨 縁"],"url":"https://en1150.co.jp/","description":"鹿児島を拠点に、海洋散骨・粉骨・お墓じまい・樹木葬・お手元供養・宇宙葬など、ご遺骨の供養をトータルでサポートする有限会社縁の公式サイトです。","publisher":{"@type":"Organization","name":"有限会社 縁"},"inLanguage":"ja"}
</script>
<!-- 2. Organization + LocalBusiness (MEO/LLMO) -->
<script type="application/ld+json">
{"@context":"https://schema.org","@type":["Organization","LocalBusiness"],"@id":"https://en1150.co.jp/#organization","name":"有限会社 縁","alternateName":"鹿児島海洋散骨 縁","url":"https://en1150.co.jp/","logo":"https://en1150.co.jp/assets/logo.svg","description":"鹿児島を拠点に海洋散骨・粉骨・お墓じまい・樹木葬・お手元供養・宇宙葬まで、ご遺骨の供養をワンストップでサポート。日本海洋散骨協会加盟事業者。","founder":{"@type":"Person","name":"堤 裕加里","jobTitle":"代表取締役"},"address":{"@type":"PostalAddress","streetAddress":"坂之上7丁目7-3","addressLocality":"鹿児島市","addressRegion":"鹿児島県","postalCode":"891-0150","addressCountry":"JP"},"geo":{"@type":"GeoCoordinates","latitude":31.5058,"longitude":130.5248},"telephone":"099-801-3637","email":"info@en1150.co.jp","openingHours":"Mo-Sa 09:00-18:00","priceRange":"¥5,000〜","areaServed":[{"@type":"State","name":"鹿児島県"},{"@type":"State","name":"福岡県"},{"@type":"State","name":"宮崎県"},{"@type":"State","name":"熊本県"},{"@type":"AdministrativeArea","name":"九州"},{"@type":"Country","name":"日本"}],"department":[{"@type":"LocalBusiness","name":"有限会社 縁 福岡営業所","telephone":"090-5000-4825","address":{"@type":"PostalAddress","postalCode":"810-0003","addressRegion":"福岡県","addressLocality":"福岡市中央区","streetAddress":"春吉2丁目1-3 2F","addressCountry":"JP"}}],"sameAs":["https://www.instagram.com/en1150en/","https://www.facebook.com/en1150/"],"memberOf":{"@type":"Organization","name":"一般社団法人日本海洋散骨協会"},"hasCredential":{"@type":"EducationalOccupationalCredential","credentialCategory":"ご遺骨トータルアドバイザー"}}
</script>
<!-- 3. Service -->
<script type="application/ld+json">
{"@context":"https://schema.org","@type":"Service","serviceType":"海洋散骨（海洋葬）","provider":{"@id":"https://en1150.co.jp/#organization"},"name":"海洋散骨（海洋葬）サービス","description":"鹿児島・錦江湾を中心に九州各地で海洋散骨を実施。チャーター海洋葬・合同海洋葬・委託海洋葬の3プランをご用意。ご遺骨のお引取りから粉骨・海洋葬までトータルサポート。","areaServed":"九州全域・日本全国対応","offers":{"@type":"AggregateOffer","lowPrice":"38500","highPrice":"275000","priceCurrency":"JPY"}}
</script>
<!-- 4. FAQPage (LLMO最重要) -->
<script type="application/ld+json">
{"@context":"https://schema.org","@type":"FAQPage","mainEntity":[{"@type":"Question","name":"海洋散骨は法律的に問題ないですか？","acceptedAnswer":{"@type":"Answer","text":"法務省は「節度をもって行えば違法ではない」との見解を示しています。当社は一般社団法人日本海洋散骨協会に加盟し、協会のガイドラインに準じ、適切な場所・方法で散骨を行っておりますのでご安心ください。"}},{"@type":"Question","name":"遠方でも依頼できますか？","acceptedAnswer":{"@type":"Answer","text":"はい、全国からご依頼いただけます。ご遺骨の郵送（ゆうパック）での受付も可能です。委託海洋葬であれば、お立ち会いなしでも承ります。施術後は写真付きの海洋葬証明書をお送りします。"}},{"@type":"Question","name":"墓じまいの手続きがわからないのですが…","acceptedAnswer":{"@type":"Answer","text":"ご安心ください。改葬許可申請や墓石の撤去、ご遺骨の取り出しから新しい供養先のご提案まで、すべてサポートいたします。まずはお気軽にご相談ください。"}},{"@type":"Question","name":"費用は事前にわかりますか？","acceptedAnswer":{"@type":"Answer","text":"はい、お見積りは無料です。ご相談内容を伺った上で明確な料金をご提示し、ご納得いただいてからのご契約となります。追加料金は一切ございません。"}},{"@type":"Question","name":"粉骨だけの依頼もできますか？","acceptedAnswer":{"@type":"Answer","text":"もちろん可能です。粉骨のみのご依頼も24,200円（税込）〜承っております。お手元供養やご自宅での保管をお考えの方にもご利用いただいています。"}},{"@type":"Question","name":"ペットの海洋散骨はできますか？","acceptedAnswer":{"@type":"Answer","text":"はい、ペットの海洋散骨も承っております。鹿児島錦江湾にて半年に一度、ペット専用の委託海洋葬を実施しています。大切なペットを自然の海にお還しするお手伝いをさせていただきます。"}}]}
</script>
<!-- 5. BreadcrumbList -->
<script type="application/ld+json">
{"@context":"https://schema.org","@type":"BreadcrumbList","itemListElement":[{"@type":"ListItem","position":1,"name":"ホーム","item":"https://en1150.co.jp/"}]}
</script>
<?php require __DIR__ . '/includes/ga4.php'; ?>
</head>
<body>

<header class="header" role="banner"><div class="header-inner">
  <a href="/" class="header-logo" aria-label="有限会社 縁 トップページ"><img src="/assets/img/en.svg" alt="有限会社 縁 ロゴ" style="height:40px;width:auto;margin-right:8px;vertical-align:middle;"><span class="header-logo-text">有限会社 縁</span></a>
  <nav class="header-nav" role="navigation" aria-label="メインナビゲーション">
    <a href="/about/">縁とは</a><span class="nav-dd"><a href="/service/">サービス一覧<span class="nav-dd-caret" aria-hidden="true">▾</span></a><span class="nav-dd-menu"><a href="/service/" class="nav-dd-top">サービス一覧を見る</a><a href="/kaiyou-sou/">海洋葬（海洋散骨）</a><a href="/powder-cleaning/">粉骨・洗骨</a><a href="/grave/">お墓じまい</a><a href="/teien-sou/">樹木葬</a><a href="/temoto-kuyou/">お手元供養</a><a href="/jewelry-reform/">JEWELRYリフォーム</a><a href="/pet-kaiyou-sou/">ペット供養</a><a href="/ihinseiri/">遺品整理</a><a href="/hikkoshi/">お墓のお引越し</a><a href="/seizen/">海洋散骨 生前契約</a><a href="/area/">対応エリア</a></span></span><span class="nav-dd"><a href="/shindan/">供養の選び方<span class="nav-dd-caret" aria-hidden="true">▾</span></a><span class="nav-dd-menu"><a href="/shindan/" class="nav-dd-top">供養の選び方（かんたん診断）</a><a href="/onayami/">供養のお悩み解決</a><a href="/gokuyou/">よくあるご質問</a></span></span><a href="/voice/">お客様の声</a><a href="/blog/">終活と供養の話</a><a href="/gokuyou/">よくある質問</a><a href="/staff/">スタッフ紹介</a>
    <a href="/flow/" class="nav-sp-only">お申込みの流れ</a><a href="/fukuoka/" class="nav-sp-only">福岡営業所</a><a href="/kuyou/" class="nav-sp-only">ご供養について</a><a href="/company/" class="nav-sp-only">会社概要</a><a href="/contact/" class="nav-sp-only">お問い合わせ</a><a href="/policy/" class="nav-sp-only nav-sp-only--sub">キャンセルポリシー</a><a href="/privacy/" class="nav-sp-only nav-sp-only--sub">プライバシーポリシー</a>
    <a href="https://www.instagram.com/en1150en/" target="_blank" rel="noopener" aria-label="Instagram" title="Instagram" style="display:inline-flex;align-items:center"><svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" style="vertical-align:-3px"><path d="M12 2.2c3.2 0 3.6 0 4.85.07 1.17.05 1.8.25 2.23.41.56.22.96.48 1.38.9.42.42.68.82.9 1.38.16.42.36 1.06.41 2.23.06 1.26.07 1.64.07 4.85s0 3.6-.07 4.85c-.05 1.17-.25 1.8-.41 2.23-.22.56-.48.96-.9 1.38-.42.42-.82.68-1.38.9-.42.16-1.06.36-2.23.41-1.26.06-1.64.07-4.85.07s-3.6 0-4.85-.07c-1.17-.05-1.8-.25-2.23-.41a3.7 3.7 0 0 1-1.38-.9 3.7 3.7 0 0 1-.9-1.38c-.16-.42-.36-1.06-.41-2.23C2.2 15.6 2.2 15.2 2.2 12s0-3.6.07-4.85c.05-1.17.25-1.8.41-2.23.22-.56.48-.96.9-1.38.42-.42.82-.68 1.38-.9.42-.16 1.06-.36 2.23-.41C8.4 2.2 8.8 2.2 12 2.2m0-2.2C8.7 0 8.3 0 7.05.07 5.78.13 4.9.33 4.14.63c-.79.3-1.46.72-2.13 1.38A5.9 5.9 0 0 0 .63 4.14C.33 4.9.13 5.78.07 7.05 0 8.3 0 8.7 0 12s0 3.7.07 4.95c.06 1.27.26 2.15.56 2.91.3.79.72 1.46 1.38 2.13a5.9 5.9 0 0 0 2.13 1.38c.76.3 1.64.5 2.91.56C8.3 24 8.7 24 12 24s3.7 0 4.95-.07c1.27-.06 2.15-.26 2.91-.56a5.9 5.9 0 0 0 2.13-1.38 5.9 5.9 0 0 0 1.38-2.13c.3-.76.5-1.64.56-2.91C24 15.7 24 15.3 24 12s0-3.7-.07-4.95c-.06-1.27-.26-2.15-.56-2.91a5.9 5.9 0 0 0-1.38-2.13A5.9 5.9 0 0 0 19.86.63c-.76-.3-1.64-.5-2.91-.56C15.7 0 15.3 0 12 0Zm0 5.84A6.16 6.16 0 1 0 18.16 12 6.16 6.16 0 0 0 12 5.84Zm0 10.15A4 4 0 1 1 16 12a4 4 0 0 1-4 4Zm7.85-10.4a1.44 1.44 0 1 1-1.44-1.44 1.44 1.44 0 0 1 1.44 1.44Z"/></svg></a>
    <a href="/contact/" class="header-cta-btn">資料請求・ご相談</a>
  </nav>
  <button class="nav-toggle" aria-label="メニュー"><span></span><span></span><span></span></button>
</div></header>

<!-- HERO -->
<section class="hero"><div class="hero-bg"></div><div class="hero-wave-lines" aria-hidden="true"><svg viewBox="0 0 1440 70" preserveAspectRatio="none" fill="none"><path d="M0,38 C240,10 480,58 720,34 C960,12 1200,54 1440,30" stroke="#ffffff" stroke-opacity="0.55" stroke-width="1.4"/><path d="M0,52 C240,26 480,72 720,48 C960,26 1200,66 1440,44" stroke="#d0b78f" stroke-opacity="0.5" stroke-width="1.2"/></svg></div><div class="hero-inner">
  <div class="hero-content">
    <div class="hero-badge">日本海洋散骨協会 加盟事業者</div>
    <h1 class="hero-h1">ご供養の不安を、<em>安穏</em>に。<br>大切な方を想う気持ちに<br>寄り添うご供養のかたち。</h1>
    <p class="hero-sub">海洋散骨・粉骨・お墓じまい・樹木葬──<br>鹿児島を拠点に、<em>全国からのご依頼に対応</em>。ご供養のすべてをワンストップでサポート。<br>宗教・宗派を問わず、どなたでもご利用いただけます。</p>
    <div class="hero-ctas">
      <a href="/contact/" class="btn-primary">無料相談・資料請求</a>
      <a href="https://line.me/R/ti/p/%40bkx9825r" class="btn-primary btn-line" target="_blank" rel="noopener">LINEで気軽に相談</a>
    </div>
  </div>
</div>
<div class="hero-wave-bottom" aria-hidden="true"><svg viewBox="0 0 1440 72" preserveAspectRatio="none"><path class="wave-fill-1" d="M0,34 C220,8 430,8 660,30 C900,54 1080,54 1260,32 C1350,20 1400,24 1440,30 L1440,72 L0,72 Z"/><path class="wave-fill-2" d="M0,44 C220,20 430,20 660,40 C900,62 1080,62 1260,42 C1350,32 1400,36 1440,42 L1440,72 L0,72 Z"/><path class="wave-fill-3" d="M0,54 C220,32 430,32 660,50 C900,70 1080,70 1260,52 C1350,44 1400,46 1440,52 L1440,72 L0,72 Z"/></svg></div>
</section>

<!-- 期間限定キャンペーンバナー -->
<a href="/kaiyou-sou/" class="cam-banner" aria-label="委託海洋葬 期間限定価格 54,450円（税込）の詳細を見る">
  <span class="cam-banner__badge">期間限定価格</span>
  <span class="cam-banner__body">
    <span class="cam-banner__name">委託海洋葬</span>
    <span class="cam-banner__prices">
      <span class="cam-banner__old">通常価格 66,000円</span>
      <span class="cam-banner__arrow" aria-hidden="true">→</span>
      <span class="cam-banner__new">54,450<small>円（税込）</small></span>
    </span>
  </span>
  <span class="cam-banner__cta">詳しく見る →</span>
</a>
<style>
.cam-banner{display:flex;align-items:center;justify-content:center;gap:22px;flex-wrap:wrap;background:linear-gradient(90deg,#8a2b2b,#b0483f);color:#fff;padding:16px 20px;text-decoration:none;transition:.2s}
.cam-banner:hover{filter:brightness(1.07);color:#fff}
.cam-banner__badge{background:#ffd77a;color:#5c2a12;font-weight:700;font-size:.8rem;padding:5px 16px;border-radius:999px;letter-spacing:.08em;flex:none}
.cam-banner__body{display:flex;align-items:center;gap:16px;flex-wrap:wrap;justify-content:center}
.cam-banner__name{font-size:1.15rem;font-weight:700;letter-spacing:.06em}
.cam-banner__prices{display:flex;align-items:baseline;gap:10px;flex-wrap:wrap}
.cam-banner__old{font-size:.85rem;opacity:.85;text-decoration:line-through}
.cam-banner__arrow{font-size:.9rem;opacity:.85}
.cam-banner__new{font-size:1.7rem;font-weight:700;color:#ffe9a8;line-height:1}
.cam-banner__new small{font-size:.85rem;font-weight:600;margin-left:2px}
.cam-banner__cta{border:1px solid rgba(255,255,255,.7);border-radius:999px;padding:7px 18px;font-size:.85rem;font-weight:700;flex:none}
@media(max-width:600px){.cam-banner{gap:10px;padding:14px 14px}.cam-banner__name{font-size:1rem}.cam-banner__new{font-size:1.45rem}.cam-banner__cta{display:none}}
</style>

<!-- 縁が選ばれる理由（ヒーローから移設） -->
<section class="reasons-band">
  <div class="container">
    <div class="rb-head fade-up">
      <span class="section-label">Why En</span>
      <h2 class="section-title">縁が選ばれる理由</h2>
    </div>
    <div class="reasons-row fade-up">
      <div class="rb-item"><span class="rb-no">01</span><h4>宗教・宗派不問</h4><p>どなたでも安心してご利用いただけます。</p></div>
      <div class="rb-item"><span class="rb-no">02</span><h4>ワンストップ対応</h4><p>ご相談から改葬・納骨まで一貫してサポートいたします。</p></div>
      <div class="rb-item"><span class="rb-no">03</span><h4>有資格者が対応</h4><p>終活カウンセラー・散骨プロデューサー資格を保有。</p></div>
    </div>
    <div class="rb-price fade-up">
      <span class="price-tag">粉骨 <strong>24,200</strong>円〜</span>
      <span class="price-tag">海洋葬 <strong>54,450</strong>円〜</span>
    </div>
  </div>
</section>

<!-- ① STATS -->
<section class="stats"><svg class="wave-top" viewBox="0 0 1440 48" preserveAspectRatio="none" aria-hidden="true"><path class="wave-fill" d="M0,26 C180,4 360,4 540,24 C720,44 900,44 1080,24 C1260,6 1380,14 1440,22 L1440,48 L0,48 Z"/></svg><svg class="wave-bottom" viewBox="0 0 1440 48" preserveAspectRatio="none" aria-hidden="true"><path class="wave-fill" d="M0,22 C180,44 360,44 540,24 C720,4 900,4 1080,24 C1260,42 1380,34 1440,26 L1440,0 L0,0 Z"/></svg><div class="stats-inner"><div class="stats-grid fade-up">
  <div class="stat-card">
    <div class="stat-icon"></div>
    <div class="stat-number">3,800<span class="stat-unit">件以上</span></div><div class="stat-label">ご供養の対応実績</div>
  </div>
  <div class="stat-card">
    <div class="stat-icon"></div>
    <div class="stat-number">10<span class="stat-unit">年以上</span></div><div class="stat-label">業界での経験</div>
  </div>
  <div class="stat-card">
    <div class="stat-icon"></div>
    <div class="stat-number">100<span class="stat-unit">回以上</span></div><div class="stat-label">セミナー・相談会実績</div>
  </div>
  <div class="stat-card">
    <div class="stat-icon"></div>
    <a href="https://maps.google.com/?cid=2494401172745547436" target="_blank" rel="noopener" style="text-decoration:none;display:block"><div class="stat-number">☆4.9<span class="stat-unit">（最大5）</span></div><div class="stat-label">Google口コミ評価を見る →</div></a>
  </div>
</div>
<p class="fade-up" style="text-align:center;margin-top:34px"><span style="display:inline-flex;align-items:center;gap:14px;background:#fff;border:1px solid #d9e4e6;border-radius:14px;padding:12px 22px;box-shadow:0 4px 14px rgba(9,45,66,0.06);max-width:100%;box-sizing:border-box"><img src="/assets/img/jmas-logo.png?v=<?= h(asset_ver()) ?>" alt="一般社団法人 日本海洋散骨協会 ロゴ" width="360" height="454" loading="lazy" style="width:54px;height:auto;flex:none"><span style="font-size:0.86rem;line-height:1.7;color:#4a5a58;text-align:left;max-width:340px;min-width:0">一般社団法人 <strong style="color:#2a5a7a">日本海洋散骨協会</strong> 加盟事業者<br><span style="font-size:0.76rem;color:#7a8a88">協会のガイドラインを順守し、環境に配慮した海洋散骨を行っています</span></span></span></p>
</div></section>


<!-- ===== MEDIA COVERAGE (メディア掲載) ===== -->
<section class="media-coverage"><div class="container">
  <div class="media-coverage-header fade-up">
    <p class="section-label">Media</p>
    <h2 class="section-title">メディアで紹介されました</h2>
  </div>
  <div class="media-coverage-grid fade-up">
    <div class="media-coverage-item">
      <div class="media-coverage-icon">
        <svg viewBox="0 0 56 56" fill="none" xmlns="http://www.w3.org/2000/svg">
          <rect x="6" y="12" width="44" height="28" rx="3" stroke="#509F46" stroke-width="2.5" fill="none"/>
          <rect x="12" y="18" width="32" height="16" rx="1" fill="#509F46" opacity="0.1"/>
          <line x1="20" y1="44" x2="36" y2="44" stroke="#509F46" stroke-width="2.5" stroke-linecap="round"/>
          <line x1="28" y1="40" x2="28" y2="44" stroke="#509F46" stroke-width="2.5"/>
          <circle cx="28" cy="26" r="6" stroke="#509F46" stroke-width="1.5" fill="#509F46" opacity="0.15"/>
          <polygon points="26,23 26,29 31,26" fill="#509F46"/>
        </svg>
      </div>
      <h4>テレビ</h4>
      <p>NHK「あさイチ」<br>NHK「シブ5時」<br>NHK「NHKスペシャル」</p>
    </div>
    <div class="media-coverage-item">
      <div class="media-coverage-icon">
        <svg viewBox="0 0 56 56" fill="none" xmlns="http://www.w3.org/2000/svg">
          <circle cx="28" cy="28" r="18" stroke="#509F46" stroke-width="2.5" fill="none"/>
          <circle cx="28" cy="28" r="10" stroke="#509F46" stroke-width="1.5" fill="#509F46" opacity="0.1"/>
          <circle cx="28" cy="28" r="3" fill="#509F46"/>
          <line x1="28" y1="10" x2="28" y2="6" stroke="#509F46" stroke-width="2" stroke-linecap="round"/>
          <line x1="42" y1="14" x2="46" y2="10" stroke="#509F46" stroke-width="2" stroke-linecap="round"/>
          <line x1="46" y1="28" x2="50" y2="28" stroke="#509F46" stroke-width="2" stroke-linecap="round"/>
        </svg>
      </div>
      <h4>ラジオ</h4>
      <p>MBCラジオ<br>「○○の時間」<br>ほか出演多数</p>
    </div>
    <div class="media-coverage-item">
      <div class="media-coverage-icon">
        <svg viewBox="0 0 56 56" fill="none" xmlns="http://www.w3.org/2000/svg">
          <rect x="10" y="6" width="28" height="40" rx="2" stroke="#509F46" stroke-width="2.5" fill="none"/>
          <rect x="10" y="6" width="28" height="12" fill="#509F46" opacity="0.1"/>
          <line x1="16" y1="24" x2="32" y2="24" stroke="#509F46" stroke-width="1.5" stroke-linecap="round"/>
          <line x1="16" y1="29" x2="32" y2="29" stroke="#509F46" stroke-width="1.5" stroke-linecap="round"/>
          <line x1="16" y1="34" x2="28" y2="34" stroke="#509F46" stroke-width="1.5" stroke-linecap="round"/>
          <line x1="16" y1="39" x2="30" y2="39" stroke="#509F46" stroke-width="1.5" stroke-linecap="round"/>
          <rect x="38" y="10" width="8" height="32" rx="1" stroke="#509F46" stroke-width="1.5" fill="#509F46" opacity="0.08"/>
        </svg>
      </div>
      <h4>雑誌・新聞</h4>
      <p>終活関連誌<br>地方紙<br>ほか掲載多数</p>
    </div>
  </div>
  <p class="media-coverage-note fade-up">※ 上記は掲載実績の一部です</p>
</div></section>

<!-- WORRY -->
<section class="worry"><svg class="wave-top" viewBox="0 0 1440 48" preserveAspectRatio="none" aria-hidden="true"><path class="wave-fill" d="M0,26 C180,4 360,4 540,24 C720,44 900,44 1080,24 C1260,6 1380,14 1440,22 L1440,48 L0,48 Z"/></svg><svg class="wave-bottom" viewBox="0 0 1440 48" preserveAspectRatio="none" aria-hidden="true"><path class="wave-fill" d="M0,22 C180,44 360,44 540,24 C720,4 900,4 1080,24 C1260,42 1380,34 1440,26 L1440,0 L0,0 Z"/></svg><div class="container">
  <div class="worry-header fade-up"><div class="worry-header__txt"><p class="section-label">Worries</p><h2 class="section-title">こんなお悩みはありませんか？</h2><p class="worry-header__note">どんな小さなことでも、お気軽にご相談ください。</p></div><img src="/assets/img/daihyo-guide.jpg?v=<?= h(asset_ver()) ?>" alt="ご相談を案内する代表" width="360" height="360" class="worry-header__photo" loading="lazy"></div>
  <div class="worry-grid fade-up">
    <div class="worry-card"><span class="worry-check">✓</span><p>お墓の管理が難しくなり、<strong>墓じまい</strong>を考えている</p></div>
    <div class="worry-card"><span class="worry-check">✓</span><p>故人の希望で<strong>海洋散骨</strong>をしたいが、どこに頼めばいいかわからない</p></div>
    <div class="worry-card"><span class="worry-check">✓</span><p><strong>遺骨の保管方法</strong>に困っている、自宅で供養したい</p></div>
    <div class="worry-card"><span class="worry-check">✓</span><p>子どもに<strong>お墓の負担</strong>を残したくない</p></div>
    <div class="worry-card"><span class="worry-check">✓</span><p><strong>粉骨</strong>をお願いしたいが、費用や手順がわからない</p></div>
    <div class="worry-card"><span class="worry-check">✓</span><p>お墓の引越し（<strong>改葬</strong>）の手続きがわからない</p></div>
  </div>
  <p class="fade-up" style="text-align:center;margin-top:26px"><a href="/onayami/" class="btn-secondary btn-ocean">お悩み別の解決策と実際の声を見る →</a></p>
  <div class="worry-answer fade-up">
  <div class="worry-answer-banner">
    <p class="worry-answer-text">そのお悩み、<em>縁</em>が<br>まるごと解決いたします。</p>
  </div>
</div>
</div></section>

<!-- SERVICES -->
<?php require __DIR__ . '/includes/goudou-schedule.php'; ?>

<section class="services"><div class="container">
  <div class="services-header fade-up"><p class="section-label">Services</p><h2 class="section-title">ご供養のトータルサポート</h2><p class="section-desc" style="margin:0 auto;">大切な方を想うさまざまなカタチに対応。ご相談から施行まで一貫してお手伝いいたします。</p></div>
  <div class="services-grid fade-up">
    <a href="/kaiyou-sou/" class="service-card"><div class="service-card-img-wrap"><img src="/assets/img/svc-kaiyou.jpg" alt="海洋葬" class="service-card-img"><span class="service-card-price">54,450円〜</span></div><div class="service-card-body"><h3>海洋葬（海洋散骨）</h3><p>ご希望の海で大切な方を海洋葬。委託（代理）やチャーターなど、ご希望のプランで行えます。</p><span class="service-link">詳しく見る</span></div></a>
    <a href="/powder-cleaning/" class="service-card"><div class="service-card-img-wrap"><img src="/assets/img/slide-img003.jpg" alt="粉骨・洗骨" class="service-card-img"><span class="service-card-price">24,200円〜</span></div><div class="service-card-body"><h3>粉骨・洗骨</h3><p>ご遺骨のパウダー化・クリーニング。散骨やお手元供養の前処理として丁寧に対応。</p><span class="service-link">詳しく見る</span></div></a>
    <a href="/grave/" class="service-card"><div class="service-card-img-wrap"><img src="/assets/img/slide-img005.jpg" alt="お墓じまい" class="service-card-img"><span class="service-card-price">ご相談無料</span></div><div class="service-card-body"><h3>お墓の整理（お墓じまい）</h3><p>墓じまいの手続き・行政対応・ご遺骨の取り出しから新しい供養先まで一括対応。</p><span class="service-link">詳しく見る</span></div></a>
    <a href="/teien-sou/" class="service-card"><div class="service-card-img-wrap"><img src="/assets/img/hero-teien-sou.jpg" alt="樹木葬" class="service-card-img"><span class="service-card-price">お問合せ</span></div><div class="service-card-body"><h3>樹木葬</h3><p>自然に還る安らかな埋葬方法。管理不要で、後世への負担もありません。</p><span class="service-link">詳しく見る</span></div></a>
    <a href="/temoto-kuyou/" class="service-card"><div class="service-card-img-wrap"><img src="/assets/img/hero-temoto-kuyou.jpg" alt="お手元供養" class="service-card-img"><span class="service-card-price">各種対応</span></div><div class="service-card-body"><h3>お手元供養</h3><p>ご自宅で身近に大切な方を偲ぶ。ジュエリーリフォームやミニ骨壺など多彩なご提案。</p><span class="service-link">詳しく見る</span></div></a>
    <a href="/pet-kaiyou-sou/" class="service-card"><div class="service-card-img-wrap"><img src="/assets/img/hero-pet-kaiyou-sou.jpg" alt="ペット供養" class="service-card-img"><span class="service-card-price">お問合せ</span></div><div class="service-card-body"><h3>ペット供養</h3><p>大切な家族であるペットの海洋散骨にも対応。心を込めてお送りいたします。</p><span class="service-link">詳しく見る</span></div></a>
  </div>
</div></section>

<!-- FLOW -->
<section class="flow"><div class="container">
  <div class="flow-header fade-up"><p class="section-label">Flow</p><h2 class="section-title">ご利用の流れ</h2><p class="section-desc" style="margin:0 auto;">初めての方でも安心。4つのステップでご供養をサポートいたします。</p></div>
  <div class="flow-steps fade-up">
    <div class="flow-step"><div class="flow-step-num">01</div><h4>お問合せ・ご相談</h4><p>お電話・LINE・メールで<br>お気軽にご連絡ください</p></div>
    <div class="flow-step"><div class="flow-step-num">02</div><h4>ヒアリング・ご提案</h4><p>ご要望やご事情を伺い<br>最適なプランをご提案</p></div>
    <div class="flow-step"><div class="flow-step-num">03</div><h4>お見積り・ご契約</h4><p>明瞭な料金をご提示<br>ご納得の上でご契約</p></div>
    <div class="flow-step"><div class="flow-step-num">04</div><h4>施行・アフターフォロー</h4><p>丁寧に施行し<br>ご供養後もサポート</p></div>
  </div>
</div></section>


<!-- SCENE MARQUEE（打ち合わせ・作業風景の横自動スクロール） -->
<section class="scene-marquee">
  <div class="sm-cap fade-up"><span class="section-label">Scene</span></div>
  <div class="marquee"><div class="marquee-track"><div class="mq-item"><img src="/assets/img/slide-img001.jpg" alt="打ち合わせ・作業風景" loading="lazy"></div><div class="mq-item"><img src="/assets/img/slide-img004.jpg" alt="打ち合わせ・作業風景" loading="lazy"></div><div class="mq-item"><img src="/assets/img/IMG_1924.jpg" alt="打ち合わせ・作業風景" loading="lazy"></div><div class="mq-item"><img src="/assets/img/slide-img005.jpg" alt="打ち合わせ・作業風景" loading="lazy"></div><div class="mq-item"><img src="/assets/img/slide-img003.jpg" alt="打ち合わせ・作業風景" loading="lazy"></div><div class="mq-item"><img src="/assets/img/slide-img006.jpg" alt="打ち合わせ・作業風景" loading="lazy"></div><div class="mq-item"><img src="/assets/img/slide-img007.jpg" alt="打ち合わせ・作業風景" loading="lazy"></div><div class="mq-item"><img src="/assets/img/slide-img001.jpg" alt="打ち合わせ・作業風景" loading="lazy"></div><div class="mq-item"><img src="/assets/img/slide-img004.jpg" alt="打ち合わせ・作業風景" loading="lazy"></div><div class="mq-item"><img src="/assets/img/IMG_1924.jpg" alt="打ち合わせ・作業風景" loading="lazy"></div><div class="mq-item"><img src="/assets/img/slide-img005.jpg" alt="打ち合わせ・作業風景" loading="lazy"></div><div class="mq-item"><img src="/assets/img/slide-img003.jpg" alt="打ち合わせ・作業風景" loading="lazy"></div><div class="mq-item"><img src="/assets/img/slide-img006.jpg" alt="打ち合わせ・作業風景" loading="lazy"></div><div class="mq-item"><img src="/assets/img/slide-img007.jpg" alt="打ち合わせ・作業風景" loading="lazy"></div></div></div>
</section>

<!-- ===== GALLERY ===== -->
<section class="gallery"><div class="container">
  <div class="gallery-header fade-up"><p class="section-label">Gallery</p><h2 class="section-title">ギャラリー</h2></div>
  <div class="gallery-grid fade-up">
    <div class="gallery-item"><img src="/assets/img/top/gallery-01.jpg?v=<?= h(asset_ver()) ?>" alt="海洋散骨の様子"></div>
    <div class="gallery-item"><img src="/assets/img/top/gallery-02.jpg?v=<?= h(asset_ver()) ?>" alt="エンディングノート"></div>
    <div class="gallery-item"><img src="/assets/img/top/gallery-03.jpg?v=<?= h(asset_ver()) ?>" alt="セレモニーの花"></div>
    <div class="gallery-item"><img src="/assets/img/top/gallery-04.jpg?v=<?= h(asset_ver()) ?>" alt="お手元供養"></div>
    <div class="gallery-item"><img src="/assets/img/top/gallery-05.jpg?v=<?= h(asset_ver()) ?>" alt="手紙を書く"></div>
    <div class="gallery-item"><img src="/assets/img/top/gallery-06.jpg?v=<?= h(asset_ver()) ?>" alt="想いを込めて"></div>
    <div class="gallery-item"><img src="/assets/img/top/gallery-07.jpg?v=<?= h(asset_ver()) ?>" alt="洗骨の様子"></div>
    <div class="gallery-item"><img src="/assets/img/top/gallery-08.jpg?v=<?= h(asset_ver()) ?>" alt="粉骨パッケージ"></div>
    <div class="gallery-item"><img src="/assets/img/top/gallery-09.jpg?v=<?= h(asset_ver()) ?>" alt="パンフレット"></div>
    <div class="gallery-item"><img src="/assets/img/top/gallery-10.jpg?v=<?= h(asset_ver()) ?>" alt="ミニ骨壺"></div>
  </div>
</div></section>

<!-- ② STAFF -->
<section class="staff"><div class="container">
  <div class="staff-header fade-up"><p class="section-label">Message</p><h2 class="section-title">代表ごあいさつ</h2><p class="section-desc" style="margin:0 auto;">ご供養という大切な場面を、誰がお手伝いするのか。顔の見える安心をお届けします。</p></div>
  <div class="staff-card fade-up">
    <div class="staff-photo-wrap">
      <img src="/assets/img/top/staff-photo.jpg?v=<?= h(asset_ver()) ?>" alt="代表" style="width:100%;height:100%;object-fit:cover;">
      <div class="staff-badges"><span class="staff-badge">終活カウンセラー</span><span class="staff-badge">散骨プロデューサー</span></div>
    </div>
    <div class="staff-info">
      <p class="staff-role">代表</p>
      <h3 class="staff-name">堤</h3>
      <p class="staff-name-en">Tsutsumi</p>
      <p class="staff-message">「大切な方を亡くされたご家族に寄り添い、安心してご供養いただけるお手伝いをしたい」──その想いから、有限会社 縁を立ち上げました。<br><br>お墓の管理にお困りの方、新しい供養のかたちをお探しの方、ご遺骨の扱い方がわからない方。さまざまなお悩みに、ご供養のトータルアドバイザーとして真摯にお応えいたします。<br><br>まずはお気軽にご相談ください。お話を伺うだけでも構いません。</p>
      <div class="staff-certs"><h4>保有資格</h4><div class="staff-cert-list"><span class="staff-cert">終活カウンセラー</span><span class="staff-cert">散骨プロデューサー</span><span class="staff-cert">小型船舶操縦士</span><span class="staff-cert">日本海洋散骨協会会員</span></div></div>
    </div>
  </div>
  <div class="staff-team fade-up"><a href="/staff/" class="staff-team-link">スタッフ紹介を見る</a></div>
</div></section>

<!-- TESTIMONIALS -->
<section class="testimonials"><svg class="wave-top" viewBox="0 0 1440 48" preserveAspectRatio="none" aria-hidden="true"><path class="wave-fill" d="M0,26 C180,4 360,4 540,24 C720,44 900,44 1080,24 C1260,6 1380,14 1440,22 L1440,48 L0,48 Z"/></svg><svg class="wave-bottom" viewBox="0 0 1440 48" preserveAspectRatio="none" aria-hidden="true"><path class="wave-fill" d="M0,22 C180,44 360,44 540,24 C720,4 900,4 1080,24 C1260,42 1380,34 1440,26 L1440,0 L0,0 Z"/></svg><div class="container">
  <div class="testimonials-header fade-up"><p class="section-label">Voice</p><h2 class="section-title">お客様の声</h2></div>
  <div class="testimonials-grid fade-up">
    <div class="testimonial-card"><div class="testimonial-quote">"</div><p>息子が眠る海に、妻の遺灰を撒いてほしいという想いを叶えていただきました。スタッフの方々の心遣いに感謝しています。</p><div class="testimonial-meta"><div class="testimonial-avatar"></div><div class="testimonial-meta-text"><strong>K.M 様</strong><span>委託海洋葬をご利用</span><span class="testimonial-location"> 鹿児島県</span></div></div></div>
    <div class="testimonial-card"><div class="testimonial-quote">"</div><p>東京から遺骨を郵送で送り、故郷の鹿児島の海に両親を還すことができました。遠方でも丁寧にご対応いただき、セレモニーの写真や動画も送っていただけて安心でした。</p><div class="testimonial-meta"><div class="testimonial-avatar"></div><div class="testimonial-meta-text"><strong>A.N 様</strong><span>委託海洋葬をご利用</span><span class="testimonial-location"> 東京都からご依頼</span></div></div></div>
    <div class="testimonial-card"><div class="testimonial-quote">"</div><p>墓じまいから海洋散骨まで、すべてお任せできて本当に安心でした。料金も明瞭で、最初の相談から丁寧に対応していただきました。</p><div class="testimonial-meta"><div class="testimonial-avatar"></div><div class="testimonial-meta-text"><strong>S.T 様</strong><span>お墓じまい＋海洋葬をご利用</span><span class="testimonial-location"> 福岡県からご依頼</span></div></div></div>
    <div class="testimonial-card"><div class="testimonial-quote">"</div><p>大阪に住んでいますが、実家の墓じまいをお願いしました。改葬手続きから粉骨、散骨まですべてワンストップで対応してくださり、何度も鹿児島に行く必要がなく助かりました。</p><div class="testimonial-meta"><div class="testimonial-avatar"></div><div class="testimonial-meta-text"><strong>Y.K 様</strong><span>お墓じまい＋粉骨＋委託海洋葬</span><span class="testimonial-location"> 大阪府からご依頼</span></div></div></div>
  </div>
  <div class="testimonials-more"><a href="/voice/" class="btn-secondary btn-ocean">お客様の声をもっと見る</a></div>
</div></section>

<!-- STRENGTHS -->
<section class="strengths"><div class="container">
  <div class="strengths-header fade-up"><p class="section-label">Why Choose Us</p><h2 class="section-title">縁が選ばれる6つの理由</h2></div>
  <div class="strengths-grid fade-up">
    <div class="strength-card"><span class="strength-num">01</span><div><h4>宗教・宗派を問わない</h4><p>どなたでも安心してご利用いただけます。無宗教の方にも対応。</p></div></div>
    <div class="strength-card"><span class="strength-num">02</span><div><h4>負担の少ない明瞭価格</h4><p>粉骨24,200円〜、海洋葬54,450円〜。追加料金なしの安心設計。</p></div></div>
    <div class="strength-card"><span class="strength-num">03</span><div><h4>ご供養のワンストップ対応</h4><p>改葬手続きから粉骨・散骨・納骨まで、まるごとお任せいただけます。</p></div></div>
    <div class="strength-card"><span class="strength-num">04</span><div><h4>有資格スタッフが対応</h4><p>終活カウンセラー・散骨プロデューサー・船舶免許を保有したスタッフ。</p></div></div>
    <div class="strength-card"><span class="strength-num">05</span><div><h4>セミナー・相談会の実績多数</h4><p>終活セミナーや供養相談会を各地で開催。正しい知識をお伝えしています。</p></div></div>
    <div class="strength-card"><span class="strength-num">06</span><div><h4>日本海洋散骨協会加盟</h4><p>業界ガイドラインに沿った適正な散骨を行う認定事業者です。</p></div></div>
  </div>
</div></section>


<!-- FULL-BLEED IMAGE -->
<section class="fullbleed" style="background-image:url('/assets/img/top/fullbleed-bg.jpg?v=<?= h(asset_ver()) ?>')">
  <div class="fb-inner">
    <span class="fb-kicker">En — Ocean Memorial</span>
    <h2>海へ還る、<br>あたらしいお見送りのかたち。</h2>
  </div>
</section>

<!-- COMPARISON -->
<section class="comparison"><div class="container">
  <div class="comparison-header fade-up"><p class="section-label">Comparison</p><h2 class="section-title">他社と比較して、縁が選ばれる理由</h2><p class="section-desc" style="margin:0 auto;">同じ「ご供養サポート」でも、対応範囲・価格・サポート体制には大きな違いがあります。</p></div>
  <div class="comparison-table-wrap fade-up"><table class="comparison-table"><thead><tr><th>比較項目</th><th class="th-other">一般的な業者</th><th class="th-en">有限会社 縁 <span class="th-en-badge">おすすめ</span></th></tr></thead><tbody>
    <tr><td>粉骨の料金</td><td class="td-other"><span class="td-icon">△</span>1万5,000円〜3万円が相場</td><td class="td-en"><span class="td-icon">◎</span><strong>24,200円〜</strong>の明瞭価格</td></tr>
    <tr><td>海洋散骨の料金</td><td class="td-other"><span class="td-icon">△</span>5万円〜15万円が一般的</td><td class="td-en"><span class="td-icon">◎</span><strong>54,450円〜</strong>で明瞭価格</td></tr>
    <tr><td>対応範囲</td><td class="td-other"><span class="td-icon">△</span>散骨のみ、粉骨のみなど<br>個別サービスが中心</td><td class="td-en"><span class="td-icon">◎</span>改葬・粉骨・散骨・納骨<br><strong>ワンストップ</strong>で完結</td></tr>
    <tr><td>墓じまいの<br>手続きサポート</td><td class="td-other"><span class="td-icon">✕</span>行政手続きは自己対応<br>または別途費用</td><td class="td-en"><span class="td-icon">◎</span>改葬許可申請〜撤去まで<br><strong>すべてサポート</strong></td></tr>
    <tr><td>資格・認定</td><td class="td-other"><span class="td-icon">△</span>無資格の業者も存在</td><td class="td-en"><span class="td-icon">◎</span>終活カウンセラー/散骨プロデューサー<br><strong>日本海洋散骨協会加盟</strong></td></tr>
    <tr><td>宗教・宗派</td><td class="td-other"><span class="td-icon">△</span>寺院系は宗派制限あり</td><td class="td-en"><span class="td-icon">◎</span><strong>宗教・宗派一切不問</strong></td></tr>
    <tr><td>遠方からの依頼</td><td class="td-other"><span class="td-icon">△</span>対面が必要な場合が多い</td><td class="td-en"><span class="td-icon">◎</span>ご遺骨の<strong>郵送受付OK</strong><br>委託散骨で立会い不要</td></tr>
    <tr><td>追加料金</td><td class="td-other"><span class="td-icon">✕</span>出張費・手数料等あり</td><td class="td-en"><span class="td-icon">◎</span><strong>追加料金なし</strong>の明瞭会計</td></tr>
    <tr><td>相談のしやすさ</td><td class="td-other"><span class="td-icon">△</span>電話・メールのみ</td><td class="td-en"><span class="td-icon">◎</span>電話・メール・<strong>LINE対応</strong></td></tr>
  </tbody></table></div>
  <p class="comparison-note fade-up">※ 一般的な業者の情報は当社調べによる相場・傾向です。</p>
  <div class="comparison-cta fade-up"><a href="/contact/" class="btn-primary" style="background:var(--color-deep-green);box-shadow:0 4px 20px rgba(80,159,70,0.25);">まずは無料で相談してみる</a></div>
</div></section>

<!-- ③ AREA -->
<section class="area"><div class="container">
  <div class="area-header fade-up"><p class="section-label">Area</p><h2 class="section-title">対応エリア</h2><p class="section-desc" style="margin:0 auto;">鹿児島を拠点に九州全域はもちろん、<strong>東京・大阪・名古屋</strong>など全国各地からのご依頼を多数いただいています。</p></div>
  <div class="area-content fade-up">
    <div class="area-map-wrap">
      <svg class="area-map-svg" viewBox="205.2 448.2 114.1 152.9" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="対応エリア 九州地図">
<rect x="205.2" y="448.2" width="114.1" height="152.9" fill="#eef3f2"/>
<path d="M287.4 492.7l-0.2 0.2-0.4 0.3 0 0.2-0.2 0.3 0 0.6 0.3 0.8-0.1 0.3-0.2 0.4-0.3 0.4-0.2 0.3-0.2 0.3-0.3 0.2-0.4 0-1.8-0.3-1.8 0-0.2 0-0.5 0.1-0.8 0.4-0.4 0.3-0.4 0.3-0.4 0.7-0.7 0.8-0.4 0.6-0.2 0.8-0.7 1.1-0.1 0.3-0.1 0.2 0 0.3 0 0.3 0.2 1 0 0.6-0.5 1.5 0.2 0.7 0.6 1.9 0.1 0.6-0.6 2.1-1.4-0.6-1.7-1.2-0.4-0.2-0.4 0-0.5 0-0.4 0.1-0.6 0.8-0.5 0.3-0.5 0.1-1.1 0.1-0.5 0.3-0.5 0.5-0.7 0.5-0.4 0.4-0.3 0.6 0.1 0.6 0 0.2 0 0.3-0.1 0.1-0.4 0.1-1.1 0.2-0.5 0.3-0.1-0.9 0.1-1.5-0.2-0.7 0-0.4-0.1-0.2-0.1-0.1-0.2-0.1-0.1 0-0.2-0.1-0.6-0.4-0.2-0.3-0.1-0.4 0-0.3-0.1-0.1-0.4-0.1-0.1-0.5-0.1-0.4 0.1-0.4 0.2-0.3 0.2-0.3 0.2-0.1 0.1-0.3 0.1-0.7 0.2-0.3 0.3-0.2 0.4-0.2 0.3-0.2 0.2-0.2 0.6 0 0.3-0.2 0.3-0.6 0.2-0.2 0.2-0.6 0.2-0.3 0.3-0.3 0.4-0.2 0.3 0 0.2-0.1 0.2-0.3 0.1-1.1 0-0.2-0.2-1.2 0-0.1-0.3-0.3-0.3-0.1-0.6 0.2-0.8 0.4-0.4 0.2-0.6 0.5-0.3 0-0.4-0.2-0.7-0.7-0.4-0.3-0.4-0.2-1.8-1.1-0.4-0.2-0.5-0.1-0.8-0.1-0.6 0.1-1.4 0.2-1.2 0-0.9 0.1-1.6 0 0.1-0.1 0.1-0.8 0.3-0.4 0.9-0.4 0.6-0.2 0.3 0.1 0.3-0.2 0.1-0.1 0.4-0.1 0.4-0.1 0.3-0.3 0.2-0.5-0.3 0-1 0.3-0.2-0.2-0.1-0.4-0.3-0.1-0.3-0.1-0.2-0.2 0.1-0.2 0.1-0.3 0.3-0.3 0.3 0 0.3 0 0.3-0.2 0.5-0.4-0.1-0.4 0.2-0.4 0.3 0 0.4 0 0.2 0 0.2-0.1 0.2-0.3 0.1-0.8 0.4 0.1 0.3 0.3 0.2 0.3-0.3 0.3 0.1 0.6 0.4 0.2 0.2 0.2 0.6-0.2 0.1 0.3-0.3 0.5 0.2 0.3 0.4 0.2 0.1 0.1 0.2-0.2 0.2 0 0.1-0.2 0.3-0.5 0.4 0 0.8 0 0.5-0.2 0.5 0 0.2-0.4 0.1-0.5 0.2-0.7-0.2-0.3-0.1-0.4-0.6 0.4-0.5 0.4-0.4 0.2-0.3-0.2-0.4-0.2-0.1-0.2-0.4 0.1-0.3-0.3-0.2-0.6 0.1-0.3 0.4 0.1 0.1 0.5 0.4 0.3 0.6 0 1.2-0.5 0.5-0.4 0.4-0.5 0.4-0.4 0.4-0.1 0.3-0.4 0.4-0.5 0.1-0.7-0.1-0.6-0.3-0.1-0.3-0.2 0.1-0.5 0.1-0.2 0.3-0.1 0.3-0.2 0.1-0.3 0.3-1 0.3 0.2 0.3 0 0.7-0.5 0.2-0.2 0.1-0.3 0.2-0.1 0.5-0.1 1.4 0.1 0.6-0.1 0.6-0.5 0.9-1.3 0.2-0.1 0.4-0.1 0.3 0 0.4 0.3 0.8-0.5 1 0 1.1 0.2-0.2 0.7 0.4-0.2 0.8 0 0.2 0.4 0.3 0.6 0.5 0.3 0.5-0.1 0.3-0.3 0.5-0.6 0.2-0.8 0.8-0.6 0.9-0.4 0.4 0.4-0.1 0.7-0.3 0.5-0.1 0.2 0 0.2-0.3 0.5 0.3 0.3-0.1 0.5-0.4 0 0.1 0.3 0 0.3-0.1 0.3-0.2 0.2-0.5 0.3 0 0.5 0 0.1 0.1 0.1 0.6 0.2 0.6-0.2-0.1 1.2 0.1 0.3 0.1 0.9 0.3 0.7 0.9 1.6 0.4 1 0.5 0.6 0.6 0.4 0.5-0.1 0.8 0.2 1.2 0.2 0.4 0.1z" fill="#e7e0d1" stroke="#8fb0ab" stroke-width="0.7" stroke-linejoin="round"/>
<path d="M253.3 498.1l1.6 0 0.9-0.1 1.2 0 1.4-0.2 0.6-0.1 0.8 0.1 0.5 0.1 0.4 0.2 1.8 1.1 0.4 0.2 0.4 0.3 0.7 0.7 0.4 0.2 0.3 0 0.6-0.5 0.4-0.2 0.8-0.4 0.6-0.2 0.3 0.1 0.3 0.3 0 0.1 0.2 1.2 0 0.2-0.1 1.1-0.2 0.3-0.2 0.1-0.3 0-0.4 0.2-0.3 0.3-0.2 0.3-0.2 0.6-0.2 0.2-0.3 0.6-0.3 0.2-0.6 0-0.2 0.2-0.3 0.2-0.4 0.2-0.3 0.2-0.2 0.3-0.1 0.7-0.1 0.3-0.2 0.1-0.2 0.3-0.2 0.3-0.1 0.4 0.1 0.4 0.1 0.5-0.4 0-0.7-0.2-0.4-0.1-0.3 0-0.9-1-0.5-0.3-0.6 1-1.3 0.8-0.8 0.9-0.6-0.4 0.1 0.7 0.4 0.6 0.4 1.1 1.3 2.2 0.5 0.5-0.1 0.7-0.4 0.2 0 0.2-2.9-0.5-1.3-0.5-0.8-0.5-0.5-0.4-0.6-0.6-2.1-1.8-0.2-0.4 0-0.3 0.4-0.4 0.1-0.3 0.1-0.4-0.2-0.5-0.3-0.4-0.4-0.2-1.3-0.3-1-0.6-0.4-0.3-0.2-0.4-0.1-0.3-0.2-0.4-0.9-1.4-0.2-0.4 0-0.4 0.1-0.3 0.9-1 0.5-0.3 0.1-0.2 0.2 0.9 0.2 0.4 0.3 0.3 0.4 0.2-0.1-0.5-0.2-0.8 0.1-1.1 0.4-0.7 0.4-0.4-0.3-0.4 0.2-0.2-1.5-1-0.6-0.7-0.3-0.3 0.3-0.5 0.2-0.5 0.4-0.1 0.5 0.6 0.2 0.5 0.2 0.4 0.4 0.2 0.3-0.2-0.2-0.5-0.6-0.9-0.2-1.1 0.5-0.7-0.1-0.7 0.5 0.5 0.5 0.1 0.7-0.4 0.9 0.5 0.6 0.7-0.1 0.4-0.4 0.4-0.1 0.4 0.4 0.2 0.2-0.3 0.2 0.3 0 0.4 0.2 0.3 0.4 0.2 0.8 0.1 0.4-0.2 0.2-0.3z" fill="#e7e0d1" stroke="#8fb0ab" stroke-width="0.7" stroke-linejoin="round"/>
<path d="M246.8 502.6l-0.1 0.2-0.5 0.3-0.9 1-0.1 0.3 0 0.4 0.2 0.4 0.9 1.4 0.2 0.4 0.1 0.3 0.2 0.4 0.4 0.3 1 0.6 1.3 0.3 0.4 0.2 0.3 0.4 0.2 0.5-0.1 0.4-0.1 0.3-0.4 0.4 0 0.3 0.2 0.4 2.1 1.8 0.6 0.6 0.5 0.4 0.8 0.5 1.3 0.5 2.9 0.5 0 0.5-0.4 0.5-0.8 0.2-0.4 0.5-0.4 0.2-0.6 0.3-0.3 0.2 0.5 0.2 0.3 0.5 0.6 0.5 0.8 0 0.4-0.1 1.5-1.1 1.8 0 1 0.7 0.3 0.8 0.6 1.4 0.1 0.4 0 1.2 0 0.3-0.6 0.7-0.1 0.5 0.3 0.5-0.6 0.8-1 0.5-1.1 0-0.3 0.3 0 0.4-0.1 0.2-0.5 0.1-0.2 0.4-0.2 0.2-0.8 0.1-0.5 0.4-0.4-0.1 0.2-0.3-0.1-0.6-0.4-0.2-0.3-0.3-0.5-0.2 0-0.7 0-0.8 0.4 0 0.3-0.1 0.4-0.4 0.3-0.3 0.4-0.3 0.3-0.3 0.3-0.3-0.1-0.7-0.3-0.2-0.3-0.1-0.1-0.3 0.1-0.2 0.1-0.3-0.3-0.3-0.9-0.1-0.3 0.2-0.4 0-0.9-0.2-0.9 0.4-0.4 0.4-0.5 0.5-0.7 0.1-0.5 0.3-0.6 0.1-0.3-0.3 0-0.3-0.1-0.1-0.3 0.2 0.2 0.4-0.4 0.6-0.7 1.1-0.4 0.6-0.2 0.6-0.6 0.5-0.6 0.3-0.4 0-0.1 0.3-0.2 0.5-0.7 0.7-0.6 0.5-0.1 0.3-0.2-0.1-0.4-0.1-0.4 0.2-0.3 0.2-0.2-0.4 0.5-0.4 0.4-0.2 0.3-0.1 0.3-0.3 0.3-0.8 0.3-0.6 0.3-0.5 0.1-0.5-0.1-0.2-0.3 0.2-0.2-0.2 0-0.3 0.3-0.1 0.2-0.2 0.3-0.1 0.3 0.3 0.3-0.2 0.4-0.8-1 0.1 0-0.5-0.6-1.3-0.3-0.3-0.3-0.1-0.2-0.2 0-0.5-0.2-0.3-0.5-0.2-0.3 0.3-0.3 0.1-0.3-0.7-0.7-0.4-0.3-0.6-0.3-0.5-0.1-0.4 0-0.4-0.1-0.4-0.3-0.4-0.3-0.2-0.4-0.2-0.2-1 0.1-0.8 0.4-0.4 0.2-0.5-0.1-0.4 0-0.6 0.3-0.2 0.1-1 0.5-0.9 1.1 0.5 0.5 0.7-0.1 0.4 0.1 0 0.1 0.1 0.1-0.2 0.2-0.2 0.2 0 0.1 0.3-0.2 0.2-0.4 0.8-0.1 0.5 0.2 0.5 0.3-0.2 0.4-0.8 0.9 0.3 0.4 0.5 0.4 0.6-0.3 0.3 0 0.3 0 0.8 0 0.6-0.2 0.2-0.2-0.3-0.2-0.8-0.2 0 0.1 1.2 0.4 0.4 0.1 0.5-0.4 0.4 0.6 0.4 0.2 0.2 0.3 0.3 0.3 0.2 0.1 0.2 0.2-0.1 0.1-0.2 0.4-0.2 0.2-0.3 0.2-0.5 1.5 0.5 1.6 0.7 0.4-0.1-0.5-0.7-1.6-2.3 0-1 0.2-0.5 0.3-0.8-0.1-0.7-0.9-0.7-0.9-0.9-0.9 0.3-0.5 0.4-0.3 0-0.1-0.7-0.7-0.5-0.3 0.2-0.4 0.4-0.4 0.1-0.4-0.3-0.1-0.4-0.3-0.9 0.2 0 0.3 0.1-0.1-0.6 0-0.3 0.4-0.2 0-0.3-0.4 0-0.5 0.6-0.2-0.4 0-0.4 0-0.5-0.4-0.1 0.1 0.7-0.1 0.8-0.1 0.3-0.2-0.1-0.5 0-0.2 0.3-0.2 0.1-0.2-0.9 0.7-0.1 0.1-0.4-0.1-0.4-0.3-0.2-0.3-0.2-0.3-0.2 0.1-0.3 0-0.2-0.1-0.2-0.2-0.2-0.3 0.1-0.3 0.1-0.3-0.1-0.5-0.5-0.3-0.1-0.7 0-0.4-0.5 0.3-1.1 0.1-0.6 0.3-0.1 0.2 0 0.3-0.2-0.1-0.3-0.1-0.4-0.4-0.7 0.1-0.9 0.1-0.8 0.4-0.4 0.8 0.8 0.7-0.1 0.9-1.3 0.1 0.3 0 1 0.7 0.3 1.2-0.4 1.9 0.6 0.2 0.6z m-27.4 22.9l0.4 0.6 0.3 0.4-0.1 0.1-0.5 0-0.7 0-1.2 0.3-0.1-0.4-0.6-0.2-0.2 0.1-0.2 0.1-0.1 0.1-0.1 0.4 0.1 0.2 0.1 0 0.4 0.5 0 0.5-0.1 0.4-0.5 0.5-0.5-0.3-0.6-0.8-0.7-0.1-1.4 0.1-0.3 0.4-1-0.6-0.6-0.2 0.1-0.6 0.2-0.4 0.1-0.2 0.2-0.4 0.1-0.2 0.3-0.1 0 0.1 0.1 0.8 0.1 0.3 0-0.2 0.4-1 0-0.3 0-0.4 0-0.4-0.1-0.3-0.1-0.2 0-0.1-0.1-0.2 0-0.2 0-0.1 0.2-0.4-0.2-0.6 0.2-0.5 0.3-0.3 0.6 0 0.1 0.5 0.5 0.9 0.1-0.1 0.5-0.3 0.6-0.1 1.1-0.6 0.1-0.5 0.7-0.4 0.1 0.7 0 0.2 0 0.3 0.5 0.2 0.4 0 0.3 0.6-0.3 1.4 0.4 0.5 0.7 0.5z m6.4-10.9l0.4 0.2 1.7-0.8 0.4 0.7-0.2 0.6-0.6 0.3-0.5 0.7-0.6 0.1-0.5 0.6-0.2 0.4-0.1 0.5 0.2 0.6 0 0.3 0 0.4-0.3-0.1-0.6-0.1-0.1 0.5 0.2 0.3 0 0.4-0.1 0.3-0.6-0.1 0-0.7-0.2-0.7 0.1-1.2 0.1-0.5-0.4-0.8-0.2-0.2-1.1-0.2-0.3-0.1 0.1-0.5 0.5-0.2 0.6-0.5 0.5 0.5 0.5-0.4-0.3-0.7 0.2-1.4 0.5-0.2 0 0.9 0.3 0 0.2-0.2 0-0.6-0.1-0.5 0.2-0.7 0.4-0.2 0.2-0.7-0.3-0.4 0-0.4 0.3-1.5 0.3 0.3 0 0.6-0.2 0.4-0.1 0.3 0.2 0.3 0.3 0.1-0.2 0.8-0.2 0.6-0.2 0.6-0.1 0.6-0.1 0.6 0 1.1z m13.4-14.8l0.2 1.5-0.2 0.5-0.4 0.6-0.4 0.4 0.3 0.3-0.3 0.5-0.4 0.1 0 0.4-0.4 0.3 0.1 0.5-0.3 0.7-1.4 1.6-1 0.7-1 0.3-0.6-0.2-0.4-0.1 0.1-0.4 0.1-0.8 0.3 0.5 0.7 0.6 0.5-0.1 0.2-0.3-0.1-0.1-0.3-0.2-0.3-0.1-0.2-0.3 0.1-0.5 0.4-0.4 0.7 0.1-0.3-0.6-0.1-0.8 1-0.7 0.1-1 0-0.8 1.5-0.4 0.4-0.8 0.3 0.1 0.2 0.4 0.4-0.4-0.4-0.4 0-0.5 0.9-0.2z m6.5-12.8l0.7 0.3 0 0.5-0.5 0.5-0.7 0-0.7 0-0.4 0.7 0 0.7-0.3-0.1-0.6-0.5-0.4-0.5 0.1-0.5-0.3-0.2-0.4 0.2-0.2-0.4-0.1-0.6 0.9 0.4 0.3 0 0-0.2-0.8-0.6-0.1-0.7 0-0.4 0.6 0-0.1-0.6 0.1-0.6 0.3-0.4 0-0.4 0.4 0.3 1.9 0.6 0 0.3-0.1 0.5 0 0.4 0.1 0.3 0.4 0.4 0.5 0.2-0.2 0-0.3 0.2-0.1 0 0 0.2z m-13.6-17.8l0.1-0.6 0.8 0.1 0.1 0.2-0.1 0.3-0.5 0.2-0.2 0.5 0.1 0.6 0.1 0.4-0.5 0.4-0.3 0 0 0.2 0.1 0.1-0.1 0.5 0 0.5 0 0.4-0.1 0.2-0.2 0-0.1 0.3 0 0.4-0.3 0.6-0.3 0.2-0.1 0.3-0.3 0.2-0.1 0.3-0.3 0-0.2 0.2-0.3 0.2-0.1-0.3-0.2-0.1-0.3-0.4-0.3-0.1-0.3 0.2-0.2 0.1-0.1-1.4 0.3-0.9-0.1-0.9 0.1-1.1 0.3-0.2 0-0.6 0.2-0.8 0.1-1.5 0.2-0.5 0.5 0.1 0.1 0.2-0.1 0.3 0 0.7 0.2 0 0.2-0.2 0.3-0.1 0.4-0.2 0.4 0-0.1-0.3-0.1-0.3 0.4 0 0.2 0.2 0.4 0.4 0 0.7 0.3 0.3z m4.1-12.1l0.3 0.4 0.5 0.4-0.1 0.3-0.1 0.3-0.3 0.9-0.5 1.4-1.3 1.2-0.6 1 0.1 1-0.7 0.7 0 0.5 0.4-0.1 0.6-0.6 0 0.3-0.2 0.5-0.1 0.2 0.1 0.6 0.3 0.4 0.3 0.1-0.2 0.5-0.4 0.2-0.2 0.3-0.4 0.6-0.7 0.2-0.5-0.1-0.6-0.7 0.4-0.4 0.8 0.3 0-1-0.4-0.2-0.5 0.6-0.3 0 0-0.7 0-0.3 0.1-0.5-0.3 0.3-0.3 0.4 0.1 0.6-0.2 0.2-0.4-0.5-0.7 0.2-0.5-0.1 0.1-0.5 0.8 0 0.3-0.2 0.1-1.4-0.2-0.7 0.3-0.3 0.2 0 0.2 0 0.2-0.2 0.4-0.5-0.1-0.2-0.5 0.5-0.3 0.1-0.2-0.4 0.4-0.9 0.5-1.2 0.6-0.5 0-0.3-0.5-0.6-0.5 0.1-0.1-0.4 0.4-0.7 0.2-0.5 0.4-1.6 0.4 0 0.7 0 0.5 0.1 0.4-0.4 0.5-0.7 0.5-0.1-0.2-0.3 0.1-0.3 0.5 0 0.3-0.2 0.5 0 0.1 0.3 0.3 0.3 0.2 0.3-0.1 0.5-0.2 0.2-0.2 0.1 0.2 0.1 0.3 0.1 0 0.4-0.2 0.6-0.2 0.1-0.3-0.2-0.3 0.1z m-16.2 63.4l0.1 0.1 0.2 0.5-0.1 0.2-0.3 0.5-0.4 0.5-0.2 0.1-0.3 0-0.5-0.4-0.2-0.1-0.2-0.3 0-1.1 0-0.3 0.1-0.2 0.2-0.1 0.4 0.2 0.2 0.4 0 0.4 0.1 0.2 0.2 0.1 0-0.1 0-0.1-0.1-0.1 0-0.3-0.2-0.3-0.1-0.4 0.2-0.1 0.7 0.5 0.2 0.2z m1.6-1.9l0 0.3 0.1 0.8-0.1 0.4-0.2 0.1-0.1 0.1 0.1 0.1 0.1 0.3-0.2 0.3-0.1 0-0.1-0.3-0.1-0.2-0.3 0-0.1-0.1 0.1-0.1 0-0.2-0.1-0.2-0.1-0.1-0.2-0.1-0.4-0.5-0.2-0.1-0.2 0.1 0-0.2 0.3-0.3 0.5 0.1 0.4 0.5 0.2 0.1-0.1-0.4 0.1-0.1 0.2 0.1 0.2-0.1 0-0.4 0.1-0.2 0.1 0.2 0.1 0.1z m2.2-0.9l0 0.2 0 0.1 0 1.2-0.1 0.3-0.2 0 0-0.3-0.1-0.1-0.3-0.2-0.2-0.1-0.5 0.2-0.1-0.1-0.1-0.2-0.1-0.1-0.1 0-0.1-0.6 0-0.3 0.3 0 0.5 0.3 0.1 0 0-0.2-0.2-0.2-0.1-0.2-0.1-0.2 0-0.1-0.1-0.2 0.1-0.1 0.1-0.1 0-0.1 0.2 0 0.5 0.2 0.4 0.3 0.1 0.3 0.1 0.3z m3.1-14l0.2 0.5 0.2 0.3 0 0.3-0.4 0.3-0.2 0.4-0.3 0-0.4 0-0.4-0.3-0.1-0.1-0.2-0.1-0.2 0.1 0.1-0.3 0.4-0.5 0.4-0.2 0.3-0.1 0.4-0.4 0.2 0.1z m12.3-6.8l0.5-0.1 0.1 0.3-0.2 0.3-0.4 0.2-0.5-0.1-0.3-0.2-0.3 0.1 0 0.2-0.2 0-0.2 0 0.1-0.3 0.5-0.6 0.5-0.3 0.3 0 0.1 0.3 0 0.2z" fill="#e7e0d1" stroke="#8fb0ab" stroke-width="0.7" stroke-linejoin="round"/>
<path d="M273.1 545.7l-1.1-0.7-1.9-1.5-0.6-0.3-0.5 0.1-0.3 0.3-0.3 0.1-0.4 0.1-0.4 0.2-1.6 0.5-0.6 0.3-0.7 0.2-0.5 0-0.5-0.2-0.3-0.3-0.7-0.9-0.4-0.3 0.1-0.2 0.3-0.1 0-0.1 0-0.5 0-0.1 2.2-2.3 0.2-0.9 0.1-0.2 0.4-0.2 0.1-0.2 0.1-0.3-0.1-0.3 0-0.3 0.3-0.7 0.2-0.1 0.8 0.8 0.2-0.4 0.1-0.4-0.1-1 0.1-0.3 0.8-1 0.6-0.4 0.3-0.9-0.1-0.9-0.6-0.4 0.3-0.4 0.3-0.1 0.9 0-0.3-0.5-0.6-0.5-0.1-0.4 0.1-0.3 2.1-1.9 0.5-0.5 0.3-0.9-0.8 0-4.8 0.9-0.5 0.3-0.4-0.2 0.3-0.5 0.5-0.4 2.1-1.1 0.6-0.6 0.3-0.1 0.5 0 0.4-0.2 0.3 0 0.3 0 0-0.3-0.4-0.1-0.4-0.4-0.2-0.4 0-0.4 0-0.2 0.1-0.3 0.4-0.4 0.1-0.2-0.1-0.2-0.1-0.1 0-0.2-0.4-0.7-2.3-1.8-1.3-1.5-0.8-0.5-0.3-0.6-0.1-1.4 0.5-0.3 1.1-0.2 0.4-0.1 0.1-0.1 0-0.3 0-0.2-0.1-0.6 0.3-0.6 0.4-0.4 0.7-0.5 0.5-0.5 0.5-0.3 1.1-0.1 0.5-0.1 0.5-0.3 0.6-0.8 0.4-0.1 0.5 0 0.4 0 0.4 0.2 1.7 1.2 1.4 0.6 1 0.4 2.4 1.3 0.7 0.2 0.4-0.2 0.2-0.4 0.4-0.9 0-0.3-0.2-0.3-0.5-0.8-0.2-0.3-0.2-0.9 0-0.3 0.2-0.3 0.8-0.2 0.7-0.3 0.4-0.2 0.5 0.1 0.9 0.5 1 0.8 0.4 0.6 1 1.8 0.2 0.4 0.6 0.7 0.2 0.4 0.4 1 0.4 0.9 0.1 0.4 0 0.4 0.1 1.6 0.1 0.5 0.5 0.9 1.8 1.6-1.6 0.4-0.3 0.2-0.5 0.4-0.2 0.3-0.3 0.9-0.4 0.8-0.9 0.9-0.2 0.3-0.4 0.9-0.4 0.4-0.6 0.6-0.4 0.5-0.2 0.6-0.2 1-0.1 0.3-0.3 0.2-0.3 0.1-0.5 0-0.3 0.1-0.4 0.4-0.3 0.7-0.6 1.6-0.1 0.6 0 0.4 0.1 0.3 0.1 0.4 0.2 0.6 0.2 0.4 0.7 0.6 0.3 0.4 0.3 0.6 0.1 0.5 0.5 0.8 0.1 0.4 0 0.5-0.2 0.4-0.6 0.8-0.3 0.7 0 0.4 0.1 0.4 0.5 0.8 0.2 0.5 0.1 0.7-0.2 0.3-0.3 0.1-0.3 0-1.4-0.4-0.3 0.1-0.3 0.2-0.5 0.8-0.5 0.4-0.4 0.2-0.8-0.2-0.4 0-0.4 0.1-0.9 0.6-0.4 0.2-0.5 0.1-0.3 0-2 0-0.9 0z m-16.4-3.9l0.7 0.1 0.3 0.2 0.2 0.7-0.1 0.4-0.5 1.4-0.2 0.3-0.5 0.2-0.4-0.3-0.2-0.5 0.1-0.6-0.3-0.3-0.2-0.6-0.1-0.6 0-0.5 0.3-0.2 0.3 0.1 0.6 0.2z m7.5-10.6l0.9-0.5 0.5 0 0.3 0.5-0.9 0.6-1.3 3.3-0.9 0.8-0.1-0.2 0-0.4 0-0.7-0.1-0.4-0.3 0.1-0.6 0.4-1.1 0.1-0.6 0.1-0.7 0.3-0.6-0.9-0.2-0.5 0-0.5 0.1-0.2 0.2-0.1 0.4-0.1 0.3-0.1 0.6-0.7 0.6-0.5 0.5-0.2 1-0.3 0.7-0.5 0.2 0 0.2 0.2 0 0.2 0.2 0.2 0.2 0.1 0.5-0.1z m-7.3-1.2l0.9 0.8 0.3 1.5 0 2.9 0.1 0.3 0.1 0.2 0 0.3-0.1 0.9 0 0.3-0.1 0.2-0.3 0.3-0.3 0.2-0.7 0.3-0.1 0.2-0.6 1.3-0.1 0.1-0.7 0.4-0.2 0.2-0.7 0.9-0.4 0.3-0.7 0.4-0.7 0.3-0.5 0 0-0.3 0.4-1.8-0.3 0.2-0.4 0-0.3 0-0.5 0.3 0-0.2 0.1-0.3 0.1-0.2 0.9-0.9 0.6-0.4 0.4 0 0.4 0 0.3-0.1 0.3-0.3-0.3 0-0.2-0.1-0.5-0.2-0.6 0.3-0.5-0.3-0.4-0.6-0.1-0.8 0-0.4 0.2-0.3 0.1-0.3 0.3-0.2-0.2-0.5 0.2-0.7 0.3-0.7 0.4-0.6 0.3-0.7-0.1-0.7-0.7-1.1 0.4 0 0.3 0.2 0.2 0.2 0.3 0.1 0.4-0.1 0.6-0.3 0.7-0.2 0.6-0.2 0.5-0.2 0.6 0.1z m2.5 8.4l0.5 0.3 0.1 0.3-0.1 0.2-0.2 0.3-0.4 0.3-0.3 0.1-0.2 0.1-0.3 0.1-0.1 0 0-0.4 0.1-0.4 0.1-0.3 0.2-0.2 0.1-0.1 0.1 0 0-0.1 0.4-0.2z m5.9-10.2l0.2 0.2 0 0.4-0.1 0.6-0.1 0.3-0.4 0.1-0.2 0.1 0.1 0.4-0.2-0.1-0.4-0.3-0.1-0.4 0.2-0.5-0.1-0.3-0.3-0.1 0-0.3 0.4-0.7 0.1 0 0 0.1 0.1 0 0.3-0.2 0.2-0.1 0.3 0.1 0.2 0.2-0.1 0.3-0.1 0.2z" fill="#e7e0d1" stroke="#8fb0ab" stroke-width="0.7" stroke-linejoin="round"/>
<path d="M287.4 492.7l0.5 0.2 1.1 0.1 0.8 0.8 1.2 0 1.3 0.2 1.4-0.2 0.2-0.3 1.1-0.9 0.4-0.6 0.3-0.7 0.4-0.5 0.7-0.3 0.9-0.2 0.6 0 0.5-0.1 0.5 0.2 0.6 0.4 0.6-0.1 0.6 0.1 0.6 0.9 0.7 1.3 0.5 0.8 0.2 0.7 0.1 0.8-0.3 0.9 0.2 1-0.3 0.7-0.5 1.1-0.1 0.8-0.3 0.1-0.6-0.3-0.5-0.2-0.5 0.2-0.2 0.5 0.1 0.5-0.1 0.5-0.7 0.2-0.3 0.1-0.1 0.5-0.6 0.1-0.6 0-0.3-0.2-0.4-0.4-0.8 0.1-0.2 0.7 0.4 2.4 0.5 0.4 0.6 0.2 1.8 0.1 1.3-0.6 1.3 0.6 0.3-0.6 1.5 0.6 1.8 0.3 0.8-0.3 1-0.5 0.5 0.1-0.6 1-0.3 0.7 0.1 0.6-0.3 0.2-0.4 0.2-0.6 0.6-0.1 0.6-0.5 0.9 1 0.5 0.8-0.5 0.9 0 0.3 0.1-0.8 0.5-0.4 0.6-0.2 0.4 0.5 0.2 0.3-0.1 0.4 0.1 0.3 0.2 0.4 0.1 0.1-0.4 0.1-0.5 0.2 0 0.3 0.6 0.2 0.3 0.2 0.2 0.3 0.1 0.3-0.1 0.1-0.2 0.2-0.8 0.5 0.9-0.1 0.5-0.8 0.2-1.1-0.1-0.5 0-0.2 0.7-0.4 0.5-0.1 1.1 0.3 0.2 0.6 0.6 0.4 0 0.3 0.5 0.3-0.1 0.3-0.1 0.2 0.1 0.1 0.2 0.2 0.1 0.4-0.2 0 0.2 0.1 0.2 0.2-0.1 0.4-0.5 0.8 0.1 0.6 0.1 0.1 0.4-0.6-0.1-0.6 0.3-0.6 0.1-0.9 0.7-0.3 0.5 0.1 0.3 0.3 0.1 0.1 0.3-0.3 0.5-0.3 0.3-0.2 0.1-0.3 0.2-0.2 0.4 0.4 0 0.8-0.1 0.2 0.2-0.2 0.5-0.5 0.4-0.5 0.2-0.4 0-0.6 0.5-1.3-0.2-0.3 0.7 0 1 0 0.1-1-0.1-0.2-0.2-0.1-0.4 0-0.6-0.3-1-0.3-0.4-0.4-0.2-0.4 0-0.6-0.2-0.4-0.1-0.4 0.1-0.2 0.3-0.2 0.2-0.4 0.9-0.2 0.3-0.1 0.2-0.4 0.1-2.7 0.1-0.6 0.2-0.3 0.3-0.6 0.1-0.4 0-0.3-0.1-0.2-0.2-0.2-0.2-0.1-0.3 0.1-0.3 0.1-0.3-0.1-0.3-0.1-0.3-0.2-0.3-0.4-0.2-0.4-0.1-0.4 0.1-0.8 0.4-0.6 0.1-0.6 0-0.7-0.1-0.5-0.2-0.5-0.4-1.8-1.6-0.5-0.9-0.1-0.5-0.1-1.6 0-0.4-0.1-0.4-0.4-0.9-0.4-1-0.2-0.4-0.6-0.7-0.2-0.4-1-1.8-0.4-0.6-1-0.8-0.9-0.5-0.5-0.1-0.4 0.2-0.7 0.3-0.8 0.2-0.2 0.3 0 0.3 0.2 0.9 0.2 0.3 0.5 0.8 0.2 0.3 0 0.3-0.4 0.9-0.2 0.4-0.4 0.2-0.7-0.2-2.4-1.3-1-0.4 0.6-2.1-0.1-0.6-0.6-1.9-0.2-0.7 0.5-1.5 0-0.6-0.2-1 0-0.3 0-0.3 0.1-0.2 0.1-0.3 0.7-1.1 0.2-0.8 0.4-0.6 0.7-0.8 0.4-0.7 0.4-0.3 0.4-0.3 0.8-0.4 0.5-0.1 0.2 0 1.8 0 1.8 0.3 0.4 0 0.3-0.2 0.2-0.3 0.2-0.3 0.3-0.4 0.2-0.4 0.1-0.3-0.3-0.8 0-0.6 0.2-0.3 0-0.2 0.4-0.3 0.2-0.2z" fill="#e7e0d1" stroke="#8fb0ab" stroke-width="0.7" stroke-linejoin="round"/>
<path d="M285.9 567.1l0.1-0.1 0.1-0.2 0.7-1.1 0.2-0.5 0-0.5 0.1-0.8 0-0.4-0.1-0.4-0.4-0.4-0.5-0.5-0.9-0.1-0.3 0-0.3-0.1-0.1 0-0.9-0.5-0.7-0.1-0.3-0.3-0.6-1-0.1-0.4-0.1-0.3 0-0.4-0.1-0.2-0.1-0.4-0.8-0.8-0.3-0.5-2-0.8-0.2-0.2-0.3-0.4-0.1-0.8 0.1-1.5-0.2-0.6-0.8-0.8-0.6-0.3-0.7-0.6-0.5-0.7-1.9-2.9-0.3-0.3-0.3-0.6 0.4-0.9 0.9 0 2 0 0.3 0 0.5-0.1 0.4-0.2 0.9-0.6 0.4-0.1 0.4 0 0.8 0.2 0.4-0.2 0.5-0.4 0.5-0.8 0.3-0.2 0.3-0.1 1.4 0.4 0.3 0 0.3-0.1 0.2-0.3-0.1-0.7-0.2-0.5-0.5-0.8-0.1-0.4 0-0.4 0.3-0.7 0.6-0.8 0.2-0.4 0-0.5-0.1-0.4-0.5-0.8-0.1-0.5-0.3-0.6-0.3-0.4-0.7-0.6-0.2-0.4-0.2-0.6-0.1-0.4-0.1-0.3 0-0.4 0.1-0.6 0.6-1.6 0.3-0.7 0.4-0.4 0.3-0.1 0.5 0 0.3-0.1 0.3-0.2 0.1-0.3 0.2-1 0.2-0.6 0.4-0.5 0.6-0.6 0.4-0.4 0.4-0.9 0.2-0.3 0.9-0.9 0.4-0.8 0.3-0.9 0.2-0.3 0.5-0.4 0.3-0.2 1.6-0.4 0.5 0.4 0.5 0.2 0.7 0.1 0.6 0 0.6-0.1 0.8-0.4 0.4-0.1 0.4 0.1 0.4 0.2 0.2 0.3 0.1 0.3 0.1 0.3-0.1 0.3-0.1 0.3 0.1 0.3 0.2 0.2 0.2 0.2 0.3 0.1 0.4 0 0.6-0.1 0.3-0.3 0.6-0.2 2.7-0.1 0.4-0.1 0.1-0.2 0.2-0.3 0.4-0.9 0.2-0.2 0.2-0.3 0.4-0.1 0.4 0.1 0.6 0.2 0.4 0 0.4 0.2 0.3 0.4 0.3 1 0 0.6 0.1 0.4 0.2 0.2 1 0.1-0.1 0.2-0.1 0.1-0.1 0.1-0.1 0.1-0.1 0.6 0 0.5-0.3 0.2-0.4-0.6-0.3-0.2-0.2 0.5-0.4 0.3-0.4 0.6-0.4 0.3-0.1 0.2-0.1 0.2 0.1 0.1 0 0.3-0.1 0.4-0.6 0.9-0.1 0.2-0.4 0.2-0.6-0.1-0.2 0.1-0.1 0.6-0.5 1.1 0.1 0.4 0.3 0.4 0.5 0 0.2 0.1 0.5 0.6-0.6 0.6-0.6 0.2-0.5-0.2-0.6-0.1-0.1 0.7 0.5 0.5 0.5 0.7-0.8 0.5-0.3 0.2-0.3 0.1-0.2 0.1-0.2 0.3-0.2 0.9-0.1 0.8 0 0.2-0.2 0.1-0.2 0.2-0.3 0.7-0.4 1.7-0.5 1.2-0.4 1.7-0.6 2.2-1 3.2-1.1 3.7-0.3 1 0.1 0.7-0.1 0.3 0.3 0.9 0.3 0.6 0.8 0.3-0.1 0.3-0.4 0.6-0.1 0.3-0.7 3.8 0.1 1-0.9 0.8-0.3 0.4-0.2 0.6-0.1 0.1-0.3 0.6-0.2 0.4 0 0.4 0.1 0.2 0.1 0.1 0 0.2 0 0.7-0.1 0.2-0.4 0.4-0.1 0.2-0.2 1.7-0.2 0.3-0.4 0.5-0.1 0.3 0 0.3-0.1 0.2-0.2 0.1-0.1-0.1-0.1 0-0.2-0.1 0.2-0.2 0-0.1 0-0.2-1.1 0.2-0.6 0.1-0.5-0.3-0.1-0.2 0-0.6-0.1-0.3-0.1-0.2-0.6-0.7-0.2-0.2-0.1-0.1-0.2 0-0.7 0.1-0.5-0.4-0.3-0.2z" fill="#e7e0d1" stroke="#8fb0ab" stroke-width="0.7" stroke-linejoin="round"/>
<path d="M 245.9,555.8 L 246.1,556.3 L 246.29999999999998,557.0 L 246.39999999999998,557.7 L 245.99999999999997,557.9000000000001 L 245.69999999999996,558.2 L 244.69999999999996,560.0 L 244.29999999999995,561.1 L 243.99999999999994,561.4 L 243.29999999999995,561.9 L 242.79999999999995,561.6 L 242.29999999999995,561.2 L 242.09999999999997,560.6 L 242.79999999999995,560.4 L 243.19999999999996,559.1999999999999 L 243.89999999999995,558.1999999999999 L 244.39999999999995,557.9 L 244.99999999999994,557.6999999999999 L 245.39999999999995,557.1999999999999 L 245.69999999999996,556.4999999999999 L 245.89999999999995,555.7999999999998 L 245.9,555.8 M 285.9,567.0999999999999 L 285.29999999999995,567.0999999999999 L 284.69999999999993,567.3 L 283.5999999999999,567.8 L 283.19999999999993,568.1999999999999 L 282.79999999999995,568.5999999999999 L 282.29999999999995,569.4999999999999 L 281.9,570.7999999999998 L 282.29999999999995,571.0999999999998 L 283.29999999999995,571.3999999999997 L 283.99999999999994,571.5999999999998 L 284.59999999999997,571.9999999999998 L 284.7,572.3999999999997 L 284.59999999999997,572.5999999999998 L 283.9,573.0999999999998 L 283.7,573.5999999999998 L 284.2,573.7999999999998 L 285.2,573.3999999999999 L 285.4,573.6999999999998 L 283.29999999999995,575.4999999999998 L 282.49999999999994,575.3999999999997 L 281.69999999999993,575.6999999999997 L 281.69999999999993,576.3999999999997 L 281.3999999999999,576.7999999999997 L 280.8999999999999,577.8999999999997 L 280.5999999999999,578.3999999999997 L 280.19999999999993,578.6999999999997 L 279.79999999999995,579.0999999999997 L 279.29999999999995,579.3999999999996 L 278.79999999999995,579.5999999999997 L 277.69999999999993,579.8999999999996 L 276.99999999999994,580.1999999999996 L 276.19999999999993,580.1999999999996 L 275.3999999999999,580.5999999999996 L 274.8999999999999,580.9999999999995 L 274.69999999999993,581.2999999999995 L 273.49999999999994,581.5999999999995 L 272.59999999999997,582.5999999999995 L 272.09999999999997,583.0999999999995 L 271.49999999999994,583.0999999999995 L 271.69999999999993,580.7999999999995 L 272.3999999999999,580.0999999999995 L 273.5999999999999,579.3999999999994 L 273.9999999999999,579.0999999999995 L 274.5999999999999,576.7999999999995 L 274.2999999999999,576.2999999999995 L 275.0999999999999,574.8999999999995 L 275.3999999999999,573.7999999999995 L 275.5999999999999,572.0999999999995 L 275.4999999999999,571.3999999999994 L 275.0999999999999,570.4999999999994 L 274.7999999999999,569.3999999999994 L 274.0999999999999,568.2999999999994 L 272.7999999999999,567.4999999999994 L 272.4999999999999,566.5999999999995 L 272.89999999999986,565.0999999999995 L 272.59999999999985,564.4999999999994 L 270.89999999999986,564.1999999999995 L 270.39999999999986,563.8999999999995 L 270.09999999999985,563.7999999999995 L 269.6999999999999,563.5999999999995 L 269.4999999999999,563.0999999999995 L 270.1999999999999,562.1999999999995 L 270.89999999999986,561.8999999999995 L 271.59999999999985,561.9999999999995 L 272.39999999999986,561.9999999999995 L 273.09999999999985,562.1999999999996 L 273.39999999999986,562.7999999999996 L 273.29999999999984,563.6999999999996 L 273.39999999999986,563.8999999999996 L 273.79999999999984,564.0999999999997 L 274.1999999999998,563.9999999999997 L 274.3999999999998,563.8999999999996 L 274.99999999999983,563.3999999999996 L 275.59999999999985,562.2999999999996 L 275.99999999999983,560.8999999999996 L 275.79999999999984,559.6999999999996 L 274.99999999999983,558.8999999999996 L 273.79999999999984,558.8999999999996 L 272.29999999999984,558.2999999999996 L 271.09999999999985,558.6999999999996 L 270.1999999999999,559.7999999999996 L 270.09999999999985,560.1999999999996 L 269.99999999999983,561.0999999999996 L 269.8999999999998,561.2999999999996 L 269.0999999999998,562.2999999999996 L 268.8999999999998,562.5999999999996 L 268.5999999999998,563.7999999999996 L 268.0999999999998,564.6999999999996 L 267.6999999999998,565.5999999999996 L 267.3999999999998,566.9999999999995 L 267.2999999999998,567.6999999999996 L 267.4999999999998,568.3999999999996 L 268.2999999999998,570.3999999999996 L 268.69999999999976,571.7999999999996 L 268.9999999999998,572.4999999999997 L 269.4999999999998,572.6999999999997 L 269.89999999999975,572.8999999999997 L 270.4999999999998,573.7999999999997 L 271.5999999999998,573.8999999999997 L 271.5999999999998,574.3999999999997 L 271.2999999999998,575.5999999999998 L 271.19999999999976,576.0999999999998 L 270.9999999999998,576.7999999999998 L 270.4999999999998,577.0999999999998 L 269.89999999999975,577.2999999999998 L 269.39999999999975,577.8999999999999 L 268.9999999999998,577.4999999999999 L 268.69999999999976,577.4999999999999 L 268.39999999999975,577.6999999999999 L 267.89999999999975,577.6999999999999 L 267.4999999999998,577.4999999999999 L 267.39999999999975,577.1999999999999 L 267.2999999999997,576.6999999999999 L 267.09999999999974,575.9 L 266.69999999999976,575.5 L 265.39999999999975,574.7 L 260.89999999999975,574.5 L 260.2999999999997,574.6 L 259.4999999999997,574.9 L 258.6999999999997,574.8 L 258.3999999999997,574.3 L 258.29999999999967,573.6999999999999 L 258.19999999999965,573.1999999999999 L 257.89999999999964,572.4 L 257.49999999999966,572.0 L 257.79999999999967,572.0 L 258.29999999999967,572.1 L 258.29999999999967,571.7 L 257.99999999999966,571.1 L 257.19999999999965,570.3000000000001 L 256.29999999999967,569.6 L 255.69999999999968,569.2 L 256.49999999999966,568.8000000000001 L 256.69999999999965,568.8000000000001 L 256.99999999999966,568.8000000000001 L 257.39999999999964,568.9000000000001 L 257.5999999999996,569.0000000000001 L 257.9999999999996,569.1000000000001 L 258.4999999999996,569.8000000000002 L 258.8999999999996,570.2000000000002 L 259.3999999999996,569.4000000000002 L 260.79999999999956,568.0000000000002 L 261.19999999999953,566.9000000000002 L 261.49999999999955,566.3000000000002 L 261.79999999999956,565.1000000000001 L 261.99999999999955,564.0000000000001 L 261.69999999999953,563.1000000000001 L 262.0999999999995,562.4000000000001 L 262.0999999999995,561.8000000000001 L 261.7999999999995,561.2 L 260.8999999999995,560.3000000000001 L 259.7999999999995,558.5000000000001 L 259.3999999999995,558.2000000000002 L 258.0999999999995,557.4000000000002 L 257.5999999999995,557.2000000000002 L 257.5999999999995,556.9000000000002 L 257.19999999999953,556.1000000000003 L 257.49999999999955,554.9000000000002 L 257.79999999999956,554.6000000000003 L 258.79999999999956,554.9000000000002 L 259.09999999999957,555.1000000000003 L 258.29999999999956,554.3000000000003 L 257.99999999999955,553.8000000000003 L 258.49999999999955,552.8000000000003 L 258.69999999999953,551.3000000000003 L 258.49999999999955,550.6000000000003 L 258.19999999999953,549.9000000000002 L 257.8999999999995,549.5000000000002 L 257.7999999999995,549.4000000000002 L 257.2999999999995,548.9000000000002 L 257.2999999999995,548.3000000000002 L 257.6999999999995,548.0000000000002 L 258.1999999999995,547.2000000000003 L 258.1999999999995,546.4000000000003 L 257.4999999999995,545.7000000000003 L 258.2999999999995,544.7000000000003 L 259.4999999999995,544.3000000000003 L 260.39999999999947,545.4000000000003 L 260.9999999999995,545.2000000000003 L 261.5999999999995,544.8000000000003 L 261.8999999999995,544.4000000000003 L 261.99999999999955,544.2000000000003 L 262.29999999999956,543.3000000000003 L 262.69999999999953,543.6000000000003 L 263.3999999999995,544.5000000000002 L 263.69999999999953,544.8000000000002 L 264.19999999999953,545.0000000000002 L 264.69999999999953,545.0000000000002 L 265.3999999999995,544.8000000000002 L 265.99999999999955,544.5000000000002 L 267.59999999999957,544.0000000000002 L 267.99999999999955,543.8000000000002 L 268.3999999999995,543.7000000000002 L 268.69999999999953,543.6000000000001 L 268.99999999999955,543.3000000000002 L 269.49999999999955,543.2000000000002 L 270.09999999999957,543.5000000000001 L 271.99999999999955,545.0000000000001 L 273.09999999999957,545.7000000000002 L 272.6999999999996,546.6000000000001 L 272.9999999999996,547.2000000000002 L 273.2999999999996,547.5000000000001 L 275.1999999999996,550.4000000000001 L 275.6999999999996,551.1000000000001 L 276.3999999999996,551.7000000000002 L 276.9999999999996,552.0000000000001 L 277.7999999999996,552.8000000000001 L 277.9999999999996,553.4000000000001 L 277.8999999999996,554.9000000000001 L 277.9999999999996,555.7 L 278.2999999999996,556.1 L 278.4999999999996,556.3000000000001 L 280.4999999999996,557.1 L 280.7999999999996,557.6 L 281.5999999999996,558.4 L 281.69999999999965,558.8 L 281.79999999999967,559.0 L 281.79999999999967,559.4 L 281.8999999999997,559.6999999999999 L 281.9999999999997,560.0999999999999 L 282.59999999999974,561.0999999999999 L 282.89999999999975,561.3999999999999 L 283.59999999999974,561.4999999999999 L 284.4999999999997,561.9999999999999 L 284.59999999999974,561.9999999999999 L 284.89999999999975,562.0999999999999 L 285.19999999999976,562.0999999999999 L 286.09999999999974,562.1999999999999 L 286.59999999999974,562.6999999999999 L 286.9999999999997,563.0999999999999 L 287.09999999999974,563.4999999999999 L 287.09999999999974,563.8999999999999 L 286.9999999999997,564.6999999999998 L 286.9999999999997,565.1999999999998 L 286.7999999999997,565.6999999999998 L 286.09999999999974,566.7999999999998 L 285.9999999999997,566.9999999999999 L 285.8999999999997,567.0999999999999 L 285.9,567.0999999999999 M 250.2999999999999,553.3999999999999 L 250.0999999999999,553.5999999999999 L 250.0999999999999,553.6999999999999 L 250.1999999999999,553.9 L 250.0999999999999,554.1999999999999 L 250.0999999999999,554.4 L 250.1999999999999,554.5 L 250.1999999999999,554.6 L 250.1999999999999,554.9 L 249.99999999999991,555.1 L 249.6999999999999,555.1 L 249.3999999999999,555.2 L 248.9999999999999,555.4000000000001 L 248.59999999999988,555.4000000000001 L 248.3999999999999,555.1000000000001 L 248.1999999999999,555.1000000000001 L 248.0999999999999,555.0000000000001 L 248.0999999999999,554.8000000000001 L 247.89999999999992,554.6 L 247.79999999999993,554.3000000000001 L 247.69999999999993,554.0000000000001 L 247.89999999999992,553.9000000000001 L 247.89999999999992,553.8000000000001 L 247.49999999999991,553.9000000000001 L 247.29999999999993,554.3000000000001 L 247.09999999999994,554.3000000000001 L 246.99999999999994,554.2 L 246.99999999999994,554.0 L 247.09999999999994,553.6 L 247.29999999999993,553.2 L 247.69999999999993,553.0 L 248.29999999999993,553.2 L 249.39999999999992,554.1 L 249.5999999999999,554.2 L 249.6999999999999,554.0 L 249.49999999999991,553.7 L 249.6999999999999,553.4000000000001 L 250.0999999999999,553.4000000000001 L 250.2999999999999,553.4000000000001 L 250.2999999999999,553.3999999999999" fill="#2f6f68" stroke="#173a38" stroke-width="0.8" stroke-linejoin="round"/>
<text x="272.0" y="494.0" font-family="'Noto Sans JP',sans-serif" font-size="5" fill="#5c564c" text-anchor="middle">福岡</text>
<text x="258.0" y="503.5" font-family="'Noto Sans JP',sans-serif" font-size="5" fill="#5c564c" text-anchor="middle">佐賀</text>
<text x="247.0" y="515.0" font-family="'Noto Sans JP',sans-serif" font-size="5" fill="#5c564c" text-anchor="middle">長崎</text>
<text x="268.0" y="524.0" font-family="'Noto Sans JP',sans-serif" font-size="5" fill="#5c564c" text-anchor="middle">熊本</text>
<text x="294.9" y="506.9" font-family="'Noto Sans JP',sans-serif" font-size="5" fill="#5c564c" text-anchor="middle">大分</text>
<text x="289.9" y="545.5" font-family="'Noto Sans JP',sans-serif" font-size="5" fill="#5c564c" text-anchor="middle">宮崎</text>
<text x="272.5" y="560.0" font-family="'Shippori Mincho','Noto Serif JP',serif" font-size="6.2" font-weight="700" fill="#ffffff" text-anchor="middle">鹿児島</text>
<circle cx="268.0" cy="553.0" r="6.5" fill="none" stroke="#b18e63" stroke-width="0.8"><animate attributeName="r" values="4;10" dur="2.6s" repeatCount="indefinite"/><animate attributeName="opacity" values="0.6;0" dur="2.6s" repeatCount="indefinite"/></circle>
<circle cx="268.0" cy="553.0" r="2.8" fill="#b18e63" stroke="#fff" stroke-width="1"/>
<circle cx="268.0" cy="553.0" r="1" fill="#fff"/><circle cx="261" cy="490" r="6" fill="none" stroke="#1f8fce" stroke-width="0.8"><animate attributeName="r" values="3.6;9" dur="2.6s" repeatCount="indefinite"/><animate attributeName="opacity" values="0.6;0" dur="2.6s" repeatCount="indefinite"/></circle><circle cx="261" cy="490" r="2.6" fill="#1f8fce" stroke="#fff" stroke-width="1"/><circle cx="261" cy="490" r="0.9" fill="#fff"/>
<circle cx="209.2" cy="587" r="2.2" fill="#b18e63"/>
<text x="214.2" y="588.6" font-family="'Noto Sans JP',sans-serif" font-size="4.4" fill="#8a6f4d">本社所在地（鹿児島市）</text>
<circle cx="209.2" cy="595" r="2.2" fill="#1f8fce"/>
<text x="214.2" y="596.6" font-family="'Noto Sans JP',sans-serif" font-size="4.4" fill="#2d6f88">営業所（福岡市）</text>
</svg>
    </div>
    <div class="area-info">
      <div class="area-primary"><h4>拠点エリア</h4><p><strong>鹿児島本社</strong>と<strong>福岡営業所</strong>の2拠点体制で、<strong>九州全域</strong>（福岡・佐賀・長崎・熊本・大分・宮崎・沖縄）での海洋散骨・粉骨・お墓じまいに対応しています。<br><br>全国からの郵送粉骨・委託海洋散骨も承ります。出張相談もお気軽にお問い合わせください。<br><br><a href="/area/" style="color:var(--color-deep-green);font-weight:700;text-decoration:underline">対応エリアのご案内はこちら →</a></p></div>
      <div class="area-nationwide"><h4>全国対応について</h4><p>ご遺骨は<strong>ゆうパック</strong>での郵送受付が可能です。<br>北海道から沖縄まで、全国どこからでもご依頼いただけます。<br>委託海洋葬であれば、お立ち会いなしでも施行いたします。</p><span class="area-nationwide-badge">全国どこからでも郵送OK</span>
        <div style="margin-top:16px;padding:16px;background:var(--color-white);border-radius:var(--radius);border:1px solid var(--color-border);">
          <p style="font-size:0.78rem;font-weight:600;color:var(--color-deep-green);margin-bottom:8px;">県外からの主なご依頼実績</p>
          <p style="font-size:0.75rem;color:var(--color-text-light);line-height:1.8;">東京都・神奈川県・大阪府・愛知県・兵庫県・千葉県・埼玉県・福岡県・沖縄県 ほか多数</p>
        </div></div>
    </div>
  </div>
</div></section>

<!-- ④ MEDIA -->
<section class="media"><div class="container">
  <div class="media-header fade-up"><p class="section-label">Track Record</p><h2 class="section-title">セミナー・活動実績</h2><p class="section-desc" style="margin:0 auto;">終活セミナーや供養相談会の開催、業界団体への参加を通じて、正しい知識の普及に努めています。</p></div>
  <div class="media-grid fade-up">
    <a href="/post-5043/" class="media-card"><div class="media-card-img"><img src="/assets/img/media-seminar1.jpg" alt="終活セミナーの様子" loading="lazy"></div><div class="media-card-body"><span class="media-card-tag">セミナー</span><h4>寺院と地域防災を考える「ゆかりの会」</h4><p>2026.03</p></div></a>
    <a href="/post-4896/" class="media-card"><div class="media-card-img"><img src="/assets/img/media-seminar2.jpg" alt="供養相談会の様子" loading="lazy"></div><div class="media-card-body"><span class="media-card-tag">相談会</span><h4>マルヤガーデンズ供養の無料相談会</h4><p>2026.01</p></div></a>
    <a href="/post-4743/" class="media-card"><div class="media-card-img"><img src="/assets/img/media-seminar3.jpg" alt="活動の様子" loading="lazy"></div><div class="media-card-body"><span class="media-card-tag">法要</span><h4>博多湾にて法要クルーズ</h4><p>2025.09</p></div></a>
    <a href="/post-4959/" class="media-card"><div class="media-card-img"><img src="/assets/img/media-seminar4.jpg" alt="研修会の様子" loading="lazy"></div><div class="media-card-body"><span class="media-card-tag">研修会</span><h4>かくれ念仏を学ぶ研修会レポート</h4><p>2026.02</p></div></a>
  </div>
</div></section>

<!-- FAQ -->
<section class="faq" aria-label="よくある質問"><div class="container">
  <div class="faq-header fade-up"><p class="section-label">FAQ</p><h2 class="section-title">よくあるご質問</h2></div>
  <div class="faq-list fade-up" itemscope itemtype="https://schema.org/FAQPage">
    <div class="faq-item" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question"><button class="faq-question"><span><span class="faq-q-label">Q</span><span itemprop="name">海洋散骨は法律的に問題ないですか？</span></span><span class="faq-toggle"></span></button><div class="faq-answer" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer"><div class="faq-answer-inner" itemprop="text">法務省は「節度をもって行えば違法ではない」との見解を示しています。当社は日本海洋散骨協会のガイドラインに準じ、適切な場所・方法で散骨を行っておりますのでご安心ください。</div></div></div>
    <div class="faq-item" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question"><button class="faq-question"><span><span class="faq-q-label">Q</span><span itemprop="name">遠方でも依頼できますか？</span></span><span class="faq-toggle"></span></button><div class="faq-answer" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer"><div class="faq-answer-inner" itemprop="text">はい、全国からご依頼いただけます。ご遺骨の郵送（ゆうパック）での受付も可能です。委託海洋葬であれば、お立ち会いなしでも承ります。</div></div></div>
    <div class="faq-item" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question"><button class="faq-question"><span><span class="faq-q-label">Q</span><span itemprop="name">墓じまいの手続きがわからないのですが…</span></span><span class="faq-toggle"></span></button><div class="faq-answer" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer"><div class="faq-answer-inner" itemprop="text">ご安心ください。改葬許可申請や墓石の撤去、ご遺骨の取り出しから新しい供養先のご提案まで、すべてサポートいたします。まずはお気軽にご相談ください。</div></div></div>
    <div class="faq-item" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question"><button class="faq-question"><span><span class="faq-q-label">Q</span><span itemprop="name">費用は事前にわかりますか？</span></span><span class="faq-toggle"></span></button><div class="faq-answer" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer"><div class="faq-answer-inner" itemprop="text">はい、お見積りは無料です。ご相談内容を伺った上で明確な料金をご提示し、ご納得いただいてからのご契約となります。追加料金は一切ございません。</div></div></div>
    <div class="faq-item" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question"><button class="faq-question"><span><span class="faq-q-label">Q</span><span itemprop="name">粉骨だけの依頼もできますか？</span></span><span class="faq-toggle"></span></button><div class="faq-answer" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer"><div class="faq-answer-inner" itemprop="text">もちろん可能です。粉骨のみのご依頼も24,200円（税込）〜承っております。お手元供養やご自宅での保管をお考えの方にもご利用いただいています。</div></div></div>
  </div>
</div></section>

<!-- ⑤ BLOG -->
<section class="blog"><div class="container">
  <div class="blog-header fade-up"><p class="section-label">News &amp; Blog</p><h2 class="section-title">お知らせ＆終活と供養の話</h2></div>
  <div class="tpb-wrap fade-up">
  <button type="button" class="tpb-arrow tpb-arrow--prev" aria-label="前の記事へ">‹</button>
  <div class="blog-grid" id="tpb-track">
<?php if ($blog_items): foreach ($blog_items as $it):
  $bdate = !empty($it['date']) ? str_replace('-', '.', substr($it['date'],0,7)) : '';
  $bhref = !empty($it['id']) ? '/blog/?id=' . rawurlencode($it['id']) : '/blog/';
?>
    <a href="<?= h($bhref) ?>" class="blog-card"><div class="blog-card-img-wrap"><img src="<?= h(!empty($it['image']) ? $it['image'] : '/assets/img/hero-default.jpg') ?>" alt="<?= h($it['title'] ?? '') ?>" class="blog-card-img" loading="lazy" onerror="this.src='/assets/img/hero-default.jpg';this.onerror=null"></div><div class="blog-card-body"><p class="blog-card-date"><?= h($bdate) ?><?php if(!empty($it['category'])): ?> <span class="blog-card-cat"><?= h($it['category']) ?></span><?php endif; ?></p><h4><?= h($it['title']) ?></h4></div></a>
<?php endforeach; else: ?>
    <a href="https://en1150.co.jp/post-5116/" class="blog-card"><div class="blog-card-img-wrap"><img src="/assets/img/Gemini_Generated_Image_tex9b1tex9b1tex9.png" alt="墓じまい後の遺骨、どうすれば？『委託海洋葬』という選択肢" class="blog-card-img"><span class="blog-card-new">NEW</span></div><div class="blog-card-body"><p class="blog-card-date">2026.04</p><h4>墓じまい後の遺骨、どうすれば？『委託海洋葬』という選択肢</h4></div></a>
    <a href="https://en1150.co.jp/post-5083/" class="blog-card"><div class="blog-card-img-wrap"><img src="/assets/img/Gemini_Generated_Image_f1yt8rf1yt8rf1yt.png" alt="【動画添付あり】必見！1分でわかるお墓じまい" class="blog-card-img"><span class="blog-card-new">NEW</span></div><div class="blog-card-body"><p class="blog-card-date">2026.04</p><h4>【動画添付あり】必見！1分でわかるお墓じまい</h4></div></a>
    <a href="https://en1150.co.jp/post-4916/" class="blog-card"><div class="blog-card-img-wrap"><img src="/assets/img/IMG_1924.jpg" alt="なぜ今、海洋葬を選ぶ人が増えているのか" class="blog-card-img"></div><div class="blog-card-body"><p class="blog-card-date">2026.01</p><h4>なぜ今、海洋葬を選ぶ人が増えているのか</h4></div></a>
<?php endif; ?>
  </div>
  <button type="button" class="tpb-arrow tpb-arrow--next" aria-label="次の記事へ">›</button>
  </div>
  <p class="tpb-hint">← 横にスワイプすると他の記事もご覧いただけます →</p>
  <script>
    (function () {
      var track = document.getElementById('tpb-track');
      var prev = document.querySelector('.tpb-arrow--prev');
      var next = document.querySelector('.tpb-arrow--next');
      if (!track || !prev || !next) return;
      var step = function () { return (track.querySelector('.blog-card') ? track.querySelector('.blog-card').offsetWidth : 300) + 24; };
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
  <div class="blog-more fade-up"><a href="/blog/">お知らせ一覧を見る</a></div>
</div></section>

<!-- CTA -->
<section class="cta-section"><div class="container">
  <h2 class="cta-title fade-up">ご供養のこと、ひとりで悩まないでください。<br>まずはお気軽にご相談ください。</h2>
  <p class="cta-sub fade-up">相談・資料請求は無料です。お電話・LINE・メールで承ります。</p>
  <div class="cta-buttons fade-up">
    <a href="/contact/" class="btn-primary" style="font-size:1rem;padding:16px 40px;">無料相談・資料請求</a>
    <a href="https://line.me/R/ti/p/%40bkx9825r" class="btn-primary btn-line" style="font-size:1rem;padding:16px 40px;" target="_blank" rel="noopener">LINEで相談する</a>
  </div>
  <div class="fade-up"><a href="tel:099-801-3637" class="cta-tel">099-801-3637</a><p class="cta-tel-note">受付時間：9:00〜18:00</p></div>
</div></section>

<!-- FOOTER -->
<footer class="footer" role="contentinfo"><svg class="wave-top" viewBox="0 0 1440 48" preserveAspectRatio="none" aria-hidden="true"><path class="wave-fill" d="M0,26 C180,4 360,4 540,24 C720,44 900,44 1080,24 C1260,6 1380,14 1440,22 L1440,48 L0,48 Z"/></svg><div class="container">
  <div class="footer-inner">
    <div class="footer-brand"><h3>有限会社 縁</h3><p><strong>本社</strong><br>〒891-0150<br>鹿児島県鹿児島市坂之上7丁目7-3</p><a href="tel:099-801-3637" class="footer-tel">099-801-3637</a><p style="margin-top:10px"><strong>福岡営業所</strong><br>〒810-0003<br>福岡県福岡市中央区春吉2丁目1-3 2F</p><a href="tel:090-5000-4825" class="footer-tel" style="font-size:1rem">090-5000-4825</a><p style="font-size:0.75rem;margin-top:8px;opacity:0.7;">Email: <a href="mailto:info@en1150.co.jp" style="color:inherit;">info@en1150.co.jp</a><br>営業時間: 9:00〜18:00（日曜定休）</p><p style="margin-top:10px"><a href="https://www.instagram.com/en1150en/" target="_blank" rel="noopener" style="color:inherit;display:inline-flex;align-items:center;gap:7px"><svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" style="vertical-align:-3px"><path d="M12 2.2c3.2 0 3.6 0 4.85.07 1.17.05 1.8.25 2.23.41.56.22.96.48 1.38.9.42.42.68.82.9 1.38.16.42.36 1.06.41 2.23.06 1.26.07 1.64.07 4.85s0 3.6-.07 4.85c-.05 1.17-.25 1.8-.41 2.23-.22.56-.48.96-.9 1.38-.42.42-.82.68-1.38.9-.42.16-1.06.36-2.23.41-1.26.06-1.64.07-4.85.07s-3.6 0-4.85-.07c-1.17-.05-1.8-.25-2.23-.41a3.7 3.7 0 0 1-1.38-.9 3.7 3.7 0 0 1-.9-1.38c-.16-.42-.36-1.06-.41-2.23C2.2 15.6 2.2 15.2 2.2 12s0-3.6.07-4.85c.05-1.17.25-1.8.41-2.23.22-.56.48-.96.9-1.38.42-.42.82-.68 1.38-.9.42-.16 1.06-.36 2.23-.41C8.4 2.2 8.8 2.2 12 2.2m0-2.2C8.7 0 8.3 0 7.05.07 5.78.13 4.9.33 4.14.63c-.79.3-1.46.72-2.13 1.38A5.9 5.9 0 0 0 .63 4.14C.33 4.9.13 5.78.07 7.05 0 8.3 0 8.7 0 12s0 3.7.07 4.95c.06 1.27.26 2.15.56 2.91.3.79.72 1.46 1.38 2.13a5.9 5.9 0 0 0 2.13 1.38c.76.3 1.64.5 2.91.56C8.3 24 8.7 24 12 24s3.7 0 4.95-.07c1.27-.06 2.15-.26 2.91-.56a5.9 5.9 0 0 0 2.13-1.38 5.9 5.9 0 0 0 1.38-2.13c.3-.76.5-1.64.56-2.91C24 15.7 24 15.3 24 12s0-3.7-.07-4.95c-.06-1.27-.26-2.15-.56-2.91a5.9 5.9 0 0 0-1.38-2.13A5.9 5.9 0 0 0 19.86.63c-.76-.3-1.64-.5-2.91-.56C15.7 0 15.3 0 12 0Zm0 5.84A6.16 6.16 0 1 0 18.16 12 6.16 6.16 0 0 0 12 5.84Zm0 10.15A4 4 0 1 1 16 12a4 4 0 0 1-4 4Zm7.85-10.4a1.44 1.44 0 1 1-1.44-1.44 1.44 1.44 0 0 1 1.44 1.44Z"/></svg><span>Instagram</span></a></p><p style="margin-top:16px"><span style="display:inline-flex;align-items:center;gap:12px;background:#fff;border:1px solid #d9e4e6;border-radius:12px;padding:10px 16px"><img src="/assets/img/jmas-logo.png?v=<?= h(asset_ver()) ?>" alt="一般社団法人 日本海洋散骨協会 ロゴ" width="360" height="454" loading="lazy" style="width:46px;height:auto"><span style="font-size:0.78rem;line-height:1.6;color:#5f6d6b;text-align:left">一般社団法人<br><strong style="color:#2a5a7a">日本海洋散骨協会</strong> 加盟事業者</span></span></p></div>
    <div class="footer-nav-grid">
      <div class="footer-nav-col"><h4>サービス</h4><ul><li><a href="/kaiyou-sou/">海洋葬（海洋散骨）</a></li><li><a href="/seizen/">海洋散骨 生前契約</a></li><li><a href="/teien-sou/">樹木葬</a></li><li><a href="/powder-cleaning/">粉骨・洗骨</a></li><li><a href="/temoto-kuyou/">お手元供養</a></li><li><a href="/jewelry-reform/">JEWELRYリフォーム</a></li></ul></div>
      <div class="footer-nav-col"><h4>お墓のお悩み</h4><ul><li><a href="/grave/">お墓じまい</a></li><li><a href="/hikkoshi/">お墓の引越し（改葬）</a></li><li><a href="/pet-kaiyou-sou/">ペット供養</a></li><li><a href="/ihinseiri/">遺品整理</a></li><li><a href="/shindan/">供養の選び方（かんたん診断）</a></li><li><a href="/onayami/">供養のお悩み解決</a></li></ul></div>
      <div class="footer-nav-col"><h4>情報</h4><ul><li><a href="/about/">縁とは</a></li><li><a href="/voice/">お客様の声</a></li><li><a href="/blog/">終活と供養の話</a></li><li><a href="/staff/">スタッフ紹介</a></li><li><a href="/company/">会社案内</a></li><li><a href="/area/">対応エリア</a></li><li><a href="/fukuoka/">福岡営業所</a></li><li><a href="/flow/">お申込みの流れ</a></li><li><a href="/gokuyou/">よくあるご質問</a></li><li><a href="/contact/">お問合せ</a></li><li><a href="/policy/">キャンセルポリシー</a></li><li><a href="/privacy/">個人情報保護方針</a></li></ul></div>
    </div>
  </div>
  <div class="footer-assoc"><p>一般社団法人日本海洋散骨協会 加盟事業者</p></div>
  <div class="footer-bottom">&copy; 有限会社 縁（えん） All Rights Reserved.<br><span style="font-size:.68rem;opacity:.55;letter-spacing:.05em"><?= h(APP_VERSION) ?></span></div>
</div></footer>

<div class="sticky-cta"><div class="sticky-cta-inner">
  <a href="tel:099-801-3637" class="sticky-btn sticky-btn-tel">電話相談</a>
  <a href="/contact/" class="sticky-btn sticky-btn-mail">メール・LINE相談</a>
<?php
  $tp_next = null;
  try { $tpx = goudou_upcoming(); $tp_next = $tpx[0] ?? null; } catch (Throwable $e) { $tp_next = null; }
  if ($tp_next): $tp_ts = strtotime((string)($tp_next['date'] ?? '')); $tp_w = $tp_ts ? ['日','月','火','水','木','金','土'][(int)date('w', $tp_ts)] : '';
?>
  <a href="#goudou-schedule" class="sticky-btn sticky-btn-sched" aria-label="次回の合同海洋散骨の予定日一覧を見る">
    <img src="/assets/img/goudou-photo.jpg?v=<?= h(asset_ver()) ?>" alt="" class="sticky-sched-photo">
    <span class="sticky-sched-txt"><span class="sticky-sched-label">次回の合同散骨</span><span class="sticky-sched-date"><?= $tp_ts ? date('n/j', $tp_ts) . '（' . h($tp_w) . '）' : '' ?></span></span>
  </a>
<?php else: ?>
  <a href="https://line.me/R/ti/p/%40bkx9825r" class="sticky-btn sticky-btn-line" target="_blank" rel="noopener">LINE相談</a>
<?php endif; ?>
</div></div>

<?php require __DIR__ . '/includes/sched-badge.php'; ?>

<script>
// SP: スクロール時にヘッダーを縮小して画面領域を確保
(function () {
  var header = document.querySelector('.header');
  if (!header) return;
  var mq = window.matchMedia('(max-width: 768px)');
  var last = false;
  function onScroll() {
    if (!mq.matches) { if (last) { header.classList.remove('is-shrink'); last = false; } return; }
    var shrink = window.scrollY > 80;
    if (shrink !== last) { header.classList.toggle('is-shrink', shrink); last = shrink; }
  }
  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll();
})();
</script>
<script>
const observer = new IntersectionObserver((entries) => {
  entries.forEach(entry => { if (entry.isIntersecting) entry.target.classList.add('visible'); });
}, { threshold: 0.15, rootMargin: '0px 0px -40px 0px' });
document.querySelectorAll('.fade-up').forEach(el => observer.observe(el));

const counterObserver = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      const el = entry.target;
      const target = parseInt(el.getAttribute('data-target'));
      if (!target || el.dataset.counted) return;
      el.dataset.counted = 'true';
      const unitSpan = el.querySelector('.stat-unit');
      const unitText = unitSpan ? unitSpan.textContent : '';
      const duration = 2000;
      const start = performance.now();
      const animate = (now) => {
        const elapsed = now - start;
        const progress = Math.min(elapsed / duration, 1);
        const eased = 1 - Math.pow(1 - progress, 3);
        const current = Math.floor(eased * target);
        el.textContent = current.toLocaleString();
        if (unitText) { const span = document.createElement('span'); span.className = 'stat-unit'; span.textContent = unitText; el.appendChild(span); }
        if (progress < 1) requestAnimationFrame(animate);
      };
      requestAnimationFrame(animate);
    }
  });
}, { threshold: 0.5 });
document.querySelectorAll('.stat-number[data-target]').forEach(el => counterObserver.observe(el));

document.querySelectorAll('.faq-question').forEach(btn => {
  btn.addEventListener('click', () => {
    const item = btn.parentElement;
    const answer = item.querySelector('.faq-answer');
    const isOpen = item.classList.contains('open');
    document.querySelectorAll('.faq-item').forEach(i => { i.classList.remove('open'); i.querySelector('.faq-answer').style.maxHeight = null; });
    if (!isOpen) { item.classList.add('open'); answer.style.maxHeight = answer.scrollHeight + 'px'; }
  });
});

window.addEventListener('scroll', () => { document.querySelector('.header').style.boxShadow = window.scrollY > 100 ? '0 2px 20px rgba(0,0,0,0.06)' : 'none'; });

const navToggle = document.querySelector('.nav-toggle');
const headerNav = document.querySelector('.header-nav');
if (navToggle) {
  navToggle.addEventListener('click', () => {
    const isOpen = headerNav.classList.toggle('is-open');
    navToggle.classList.toggle('is-open', isOpen);
    navToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    document.body.classList.toggle('sp-menu-open', isOpen);
  });
}
</script>
<script src="/assets/js/track.js?v=<?= h(asset_ver()) ?>" defer></script>
<style>
.side-tabs{position:fixed;right:0;top:50%;transform:translateY(-50%);z-index:150;display:flex;flex-direction:column;gap:10px;align-items:flex-end}
.side-finder{display:flex;flex-direction:column;align-items:center;gap:12px;background:linear-gradient(180deg,#1f8fce 0%,#15709e 100%);color:#fff;padding:16px 11px;border-radius:12px 0 0 12px;box-shadow:-4px 4px 16px rgba(18,89,122,.28);text-decoration:none;transition:.25s}
.side-finder:hover{padding-right:17px;filter:brightness(1.06);color:#fff}
.side-finder .sf-badge{width:26px;height:26px;border-radius:50%;background:rgba(255,255,255,.22);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.95rem;flex:none}
.side-finder .sf-label{writing-mode:vertical-rl;text-orientation:upright;font-weight:700;font-size:.98rem;letter-spacing:.14em;white-space:nowrap}
@media(max-width:768px){.side-finder{padding:12px 8px;gap:9px}.side-finder .sf-label{font-size:.82rem;letter-spacing:.1em}.side-finder .sf-badge{width:22px;height:22px;font-size:.82rem}}
.side-flow{display:flex;flex-direction:column;align-items:center;background:linear-gradient(180deg,#c9a25a 0%,#a88a4d 100%);color:#fff;padding:16px 11px;border-radius:12px 0 0 12px;box-shadow:-4px 4px 16px rgba(120,90,30,.28);text-decoration:none;transition:.25s}
.side-flow:hover{padding-right:17px;filter:brightness(1.06);color:#fff}
.side-flow .sf-label{writing-mode:vertical-rl;text-orientation:upright;font-weight:700;font-size:.92rem;letter-spacing:.12em;white-space:nowrap}
@media(max-width:768px){.side-flow{padding:12px 8px}.side-flow .sf-label{font-size:.78rem;letter-spacing:.08em}}
</style>
<!-- 右側固定・縦長タブ（供養の選び方／お申込みの流れ） -->
<div class="side-tabs">
  <a href="/shindan/" class="side-finder" aria-label="供養の選び方（かんたん診断）">
    <span class="sf-badge" aria-hidden="true">?</span>
    <span class="sf-label">供養の選び方</span>
  </a>
  <a href="/flow/" class="side-flow" aria-label="お申込みの流れ">
    <span class="sf-label">お申込みの流れ</span>
  </a>
</div>
<?php require __DIR__ . '/includes/fontsize.php'; ?>
<?= dev_badge_html() ?>
</body>
</html>
