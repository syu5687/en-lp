<?php
require_once __DIR__ . '/includes/config.php';
header('Content-Type: application/xml; charset=UTF-8');

// ※ /staff/ /voice/ は準備中（noindex）のため sitemap から除外。本実装時に戻す。
$paths = ['/','/service/','/kuyou/','/gokuyou/','/blog/','/company/','/contact/','/privacy/'];
foreach (SERVICES as $s) $paths[] = '/' . $s['slug'] . '/';
$paths[] = '/lp/ohaka/';
$paths[] = '/lp/pet/';

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
foreach (array_unique($paths) as $p) {
  echo "  <url><loc>" . htmlspecialchars(SITE['url'] . $p) . "</loc><changefreq>weekly</changefreq></url>\n";
}
echo '</urlset>' . "\n";
