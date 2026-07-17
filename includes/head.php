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
?>
<!DOCTYPE html>
<html lang="ja" prefix="og: https://ogp.me/ns#">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= h($page_title) ?></title>
<meta name="description" content="<?= h($page_desc) ?>">
<?php if ($page_canonical): ?><link rel="canonical" href="<?= h($page_canonical) ?>"><?php endif; ?>
<meta name="robots" content="<?= $page_noindex ? 'noindex, follow' : 'index, follow, max-snippet:-1, max-image-preview:large' ?>">
<meta property="og:title" content="<?= h($page_title) ?>">
<meta property="og:description" content="<?= h($page_desc) ?>">
<meta property="og:type" content="website">
<meta property="og:site_name" content="<?= h(SITE['name'] . '｜' . SITE['tagline']) ?>">
<meta property="og:locale" content="ja_JP">
<link rel="stylesheet" href="/assets/css/common.css">
<?php require __DIR__ . '/ga4.php'; ?>
<?php require __DIR__ . '/jsonld.php'; ?>
</head>
