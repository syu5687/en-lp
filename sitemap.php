<?php
/**
 * sitemap.xml（検索エンジン登録用サイトマップ）
 * - 公開URL: https://en1150.co.jp/sitemap.xml （.htaccess で本ファイルへ内部書き換え）
 *            https://en1150.co.jp/sitemap.php でも同一内容を配信
 * - 固定ページ ＋ サービスページ ＋ ブログ記事（Firestore管理分・旧WordPressアーカイブ）を出力
 * - Firestore は en_cache（15分キャッシュ）経由で読むため、クロールが増えても読み取り回数は増えない
 */
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/admin/includes/store.php'; // news_published() / en_cache
header('Content-Type: application/xml; charset=UTF-8');

/* ---- 固定ページ（priority: トップ1.0 / 主要サービス0.8 / その他0.6）---- */
$main = ['/' => '1.0'];
$high = ['/en/', '/en/sea-burial-japan/', '/kaiyou-sou/', '/kaiyou-sou/fukuoka/', '/fukuoka/', '/grave/', '/grave/fukuoka/', '/powder-cleaning/', '/seizen/', '/service/', '/temoto-kuyou/', '/pet-kaiyou-sou/'];
$paths = ['/en/', '/en/sea-burial-japan/', '/kaiyou-sou/fukuoka/', '/grave/fukuoka/', '/service/','/shindan/','/kuyou/','/gokuyou/','/staff/','/voice/','/blog/','/company/','/contact/','/privacy/','/seizen/','/area/','/about/','/onayami/','/flow/','/fukuoka/','/policy/','/glossary/'];
foreach (SERVICES as $s) $paths[] = '/' . $s['slug'] . '/';

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
echo "  <url><loc>" . htmlspecialchars(SITE['url'] . '/') . "</loc><changefreq>weekly</changefreq><priority>1.0</priority></url>\n";
foreach (array_unique($paths) as $p) {
  $pr = in_array($p, $high, true) ? '0.8' : '0.6';
  echo "  <url><loc>" . htmlspecialchars(SITE['url'] . $p) . "</loc><changefreq>weekly</changefreq><priority>{$pr}</priority></url>\n";
}

/* ---- ブログ記事 ----
 * ソース: ① Firestore（管理画面から投稿・en_cache経由） ② data/news.json ③ data/blog-posts.json（旧WordPress）
 * 同一記事（同日付＋同タイトル・記号無視）は一覧ページと同じ基準で1本に統一する。
 */
$by_key = [];
$score = fn(array $it): int =>
  (!empty($it['_fs']) ? 8 : 0) + (!empty($it['body_html']) ? 4 : 0) + (!empty($it['image']) ? 2 : 0) + (!empty($it['images']) ? 1 : 0);
$add = function (array $it) use (&$by_key, $score): void {
  if (empty($it['published']) || (string)($it['id'] ?? '') === '') return;
  $key = ($it['date'] ?? '') . '|' . preg_replace('/[^\p{L}\p{N}]+/u', '', (string)($it['title'] ?? ''));
  if (!isset($by_key[$key]) || $score($it) > $score($by_key[$key])) $by_key[$key] = $it;
};

// ① Firestore（障害時は無視してJSONのみで継続 — サイトマップは止めない）
try {
  foreach (news_published() as $it) { $it['_fs'] = true; $add($it); }
} catch (Throwable $e) { /* noop */ }

// ②③ JSONアーカイブ
foreach (['/data/news.json', '/data/blog-posts.json'] as $src) {
  $j = @json_decode((string)@file_get_contents(__DIR__ . $src), true);
  foreach (($j['items'] ?? []) as $it) $add($it);
}

foreach ($by_key as $it) {
  $loc = SITE['url'] . '/blog/?id=' . rawurlencode((string)$it['id']);
  echo "  <url><loc>" . htmlspecialchars($loc) . "</loc>";
  $date = (string)($it['date'] ?? '');
  if ($date !== '') echo "<lastmod>" . htmlspecialchars(substr($date, 0, 10)) . "</lastmod>";
  echo "<changefreq>monthly</changefreq><priority>0.5</priority></url>\n";
}
echo '</urlset>' . "\n";
