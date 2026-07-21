<?php
require_once __DIR__ . '/includes/config.php';
header('Content-Type: application/xml; charset=UTF-8');

$paths = ['/','/service/','/shindan/','/kuyou/','/gokuyou/','/staff/','/voice/','/blog/','/company/','/contact/','/privacy/'];
foreach (SERVICES as $s) $paths[] = '/' . $s['slug'] . '/';
$paths[] = '/lp/ohaka/';
$paths[] = '/lp/pet/';

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
foreach (array_unique($paths) as $p) {
  echo "  <url><loc>" . htmlspecialchars(SITE['url'] . $p) . "</loc><changefreq>weekly</changefreq></url>\n";
}

// ---- ブログ記事（旧WordPressアーカイブ ＋ news.json）を追加 ----
$posts = [];
$archive = @json_decode((string)@file_get_contents(__DIR__ . '/data/blog-posts.json'), true);
foreach (($archive['items'] ?? []) as $it) {
  if (empty($it['published'])) continue;
  $id = (string)($it['id'] ?? '');
  if ($id !== '') $posts[$id] = $it['date'] ?? '';
}
$news = @json_decode((string)@file_get_contents(__DIR__ . '/data/news.json'), true);
foreach (($news['items'] ?? []) as $it) {
  if (empty($it['published'])) continue;
  $id = (string)($it['id'] ?? '');
  if ($id !== '' && !isset($posts[$id])) $posts[$id] = $it['date'] ?? '';
}
foreach ($posts as $id => $date) {
  $loc = SITE['url'] . '/blog/?id=' . rawurlencode($id);
  echo "  <url><loc>" . htmlspecialchars($loc) . "</loc>";
  if ($date) echo "<lastmod>" . htmlspecialchars(substr((string)$date, 0, 10)) . "</lastmod>";
  echo "<changefreq>monthly</changefreq></url>\n";
}
echo '</urlset>' . "\n";
