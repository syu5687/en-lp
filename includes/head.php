<?php
/**
 * 共通 <head>。各ページの先頭で次の変数を定義してから読み込む：
 *   $page_title       … <title>（必須）
 *   $page_desc        … meta description（任意）
 *   $page_canonical   … canonical URL（任意 / 例: SITE['url'].'/staff/'）
 */
require_once __DIR__ . '/config.php';
$page_title    = $page_title    ?? SITE['name'];
$page_desc     = $page_desc     ?? (SITE['name'] . '｜' . SITE['tagline']);
$page_canonical = $page_canonical ?? null;
$page_noindex  = $page_noindex  ?? false; // 準備中ページ等は true で noindex
$page_hero_image = $page_hero_image ?? null; // ページ見出しの背景画像（未指定なら hero-default.jpg）
$page_og_image  = $page_og_image  ?? (SITE['url'] . '/assets/og-image.jpg'); // SNS共有用画像（1200×630）
?>
<!DOCTYPE html>
<html lang="ja" prefix="og: https://ogp.me/ns#">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= h($page_title) ?></title>
<meta name="description" content="<?= h($page_desc) ?>">
<?php if ($page_canonical): ?><link rel="canonical" href="<?= h($page_canonical) ?>"><?php endif; ?>
<?php
// 日英対応ページの hreflang 自動出力（対応表: includes/lang-map.php）
require_once __DIR__ . '/lang-map.php';
$hl_path = $page_canonical ? (parse_url($page_canonical, PHP_URL_PATH) ?: '/') : null;
if ($hl_path !== null && ($hl_en = en_counterpart($hl_path)) !== null):
?>
<link rel="alternate" hreflang="ja" href="<?= h(SITE['url'] . $hl_path) ?>">
<link rel="alternate" hreflang="en" href="<?= h(SITE['url'] . $hl_en) ?>">
<link rel="alternate" hreflang="x-default" href="<?= h(SITE['url'] . $hl_path) ?>">
<?php endif; ?>
<meta name="robots" content="<?= $page_noindex ? 'noindex, follow' : 'index, follow, max-snippet:-1, max-image-preview:large' ?>">
<meta property="og:title" content="<?= h($page_title) ?>">
<meta property="og:description" content="<?= h($page_desc) ?>">
<meta property="og:type" content="website">
<meta property="og:site_name" content="<?= h(SITE['name'] . '｜' . SITE['tagline']) ?>">
<meta property="og:locale" content="ja_JP">
<?php if ($page_canonical): ?><meta property="og:url" content="<?= h($page_canonical) ?>">
<?php endif; ?><meta property="og:image" content="<?= h($page_og_image) ?>">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:image" content="<?= h($page_og_image) ?>">
<link rel="icon" href="/assets/img/en.svg" type="image/svg+xml">
<link rel="stylesheet" href="/assets/css/common.css?v=<?= h(asset_ver()) ?>">
<?php if (!empty($page_hero_image)): ?><style>.page-hero{--hero-image:url('<?= h($page_hero_image . (str_contains($page_hero_image, '?') ? '' : '?v=' . asset_ver())) ?>')}</style><?php endif; ?>
<?php /* v0280：流入元の記憶。訪問の最初のページで utm_* / gclid / 着地ページを sessionStorage に控える。
     /contact/ に着く頃には URL パラメータが消えているため、ここで拾っておかないと広告の紐付けができない。
     保存できない環境（プライベートモード等）でも try/catch で握りつぶし、表示には一切影響させない。 */ ?>
<script>
(function(){try{
  var K='en_attr',KEYS=['utm_source','utm_medium','utm_campaign','utm_content','utm_term','gclid'];
  var qp=new URLSearchParams(location.search);
  var hasNew=KEYS.some(function(k){return !!qp.get(k);});
  var cur=null;try{cur=JSON.parse(sessionStorage.getItem(K)||'null');}catch(e){}
  if(!hasNew&&cur)return;                      /* 2ページ目以降は上書きしない */
  var o={};
  if(hasNew)KEYS.forEach(function(k){var v=qp.get(k);if(v)o[k]=String(v).slice(0,200);});
  o.landing_page=(location.pathname+location.search).slice(0,200);
  try{sessionStorage.setItem(K,JSON.stringify(o));}catch(e){}
}catch(e){}})();
</script>
<?php require __DIR__ . '/ga4.php'; ?>
<?php require __DIR__ . '/jsonld.php'; ?>
</head>
