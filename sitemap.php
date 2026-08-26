<?php
require_once __DIR__ . '/includes/config.php';
header('Content-Type: application/xml; charset=UTF-8');

$paths = ['/','/service/','/shindan/','/kuyou/','/gokuyou/','/staff/','/voice/','/blog/','/company/','/contact/','/privacy/','/seizen/','/area/','/about/','/onayami/','/flow/','/fukuoka/','/policy/'];
foreach (SERVICES as $s) $paths[] = '/' . $s['slug'] . '/';

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
foreach (array_unique($paths) as $p) {
  echo "  <url><loc>" . htmlspecialchars(SITE['url'] . $p) . "</loc><changefreq>weekly</changefreq></url>\n";
}

// ---- ブログ記事（旧WordPressアーカイブ ＋ news.json）を追加 ----
// 同一記事（同日付＋同タイトル・記号無視）は一覧と同じ基準で1本に統一する。
$by_key = [];
$score = fn(array $it): int =>
  (!empty($it['body_html']) ? 4 : 0) + (!empty($it['image']) ? 2 : 0) + (!empty($it['images']) ? 1 : 0);
foreach (['/data/news.json', '/data/blog-posts.json'] as $src) {
  $j = @json_decode((string)@file_get_contents(__DIR__ . $src), true);
  foreach (($j['items'] ?? []) as $it) {
    if (empty($it['published']) || (string)($it['id'] ?? '') === '') continue;
    $key = ($it['date'] ?? '') . '|' . preg_replace('/[^\p{L}\p{N}]+/u', '', (string)($it['title'] ?? ''));
    if (!isset($by_key[$key]) || $score($it) > $score($by_key[$key])) $by_key[$key] = $it;
  }
}
$posts = [];
foreach ($by_key as $it) $posts[(string)$it['id']] = $it['date'] ?? '';
foreach ($posts as $id => $date) {
  $loc = SITE['url'] . '/blog/?id=' . rawurlencode($id);
  echo "  <url><loc>" . htmlspecialchars($loc) . "</loc>";
  if ($date) echo "<lastmod>" . htmlspecialchars(substr((string)$date, 0, 10)) . "</lastmod>";
  echo "<changefreq>monthly</changefreq></url>\n";
}
echo '</urlset>' . "\n";
