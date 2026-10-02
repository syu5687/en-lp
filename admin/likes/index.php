<?php
/**
 * いいね管理（ランキング）。
 * blog_likes コレクションの件数を、記事タイトル・カテゴリと突き合わせて多い順に表示する。
 *
 * 管理画面はアクセス数が少ないため、ここではキャッシュを使わず生読みする
 * （公開ページ側は includes/blog-likes.php のキャッシュ経由）。
 */
require __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../includes/store.php';
require __DIR__ . '/../../includes/blog-likes.php';

$err = '';
$likes = [];
try { $likes = blog_like_counts_fresh(); } catch (Throwable $e) { $err = $e->getMessage(); }

/* ---- 記事のメタ情報（タイトル・カテゴリ・公開日）を集める -------------- */
$meta = [];
$add_meta = function (array $it) use (&$meta) {
  $id = (string)($it['id'] ?? '');
  if ($id === '' || isset($meta[$id])) return;
  $meta[$id] = [
    'title'     => (string)($it['title'] ?? ''),
    'category'  => (string)($it['category'] ?? ''),
    'date'      => (string)($it['date'] ?? ''),
    'published' => !empty($it['published']),
  ];
};
try { foreach (news_all() as $it) $add_meta($it); } catch (Throwable $e) { if ($err === '') $err = $e->getMessage(); }
foreach (['/../../data/news.json', '/../../data/blog-posts.json'] as $src) {
  $seed = @json_decode((string)@file_get_contents(__DIR__ . $src), true);
  foreach (($seed['items'] ?? []) as $it) $add_meta($it);
}

/* ---- 表示モード -------------------------------------------------------- */
$mode = ($_GET['mode'] ?? 'liked') === 'all' ? 'all' : 'liked';

$rows = [];
if ($mode === 'all') {
  foreach ($meta as $id => $m) {
    if (!$m['published']) continue;
    $rows[] = ['id' => $id, 'count' => (int)($likes[$id]['count'] ?? 0),
               'updated_at' => (string)($likes[$id]['updated_at'] ?? '')] + $m;
  }
} else {
  foreach ($likes as $id => $v) {
    if ((int)$v['count'] <= 0) continue;
    $m = $meta[$id] ?? ['title' => '（記事情報が見つかりません）', 'category' => '', 'date' => '', 'published' => false];
    $rows[] = ['id' => $id, 'count' => (int)$v['count'], 'updated_at' => (string)$v['updated_at']] + $m;
  }
}
usort($rows, function ($a, $b) {
  if ($a['count'] !== $b['count']) return $b['count'] <=> $a['count'];
  return strcmp((string)$b['date'], (string)$a['date']);
});

$total_likes   = 0; foreach ($likes as $v) $total_likes += max(0, (int)$v['count']);
$liked_articles = 0; foreach ($likes as $v) if ((int)$v['count'] > 0) $liked_articles++;

/* ---- CSV出力 ----------------------------------------------------------- */
if (!empty($_GET['csv'])) {
  header('Content-Type: text/csv; charset=UTF-8');
  header('Content-Disposition: attachment; filename="blog-likes-' . date('Ymd') . '.csv"');
  echo "\xEF\xBB\xBF"; // Excel向けBOM
  $out = fopen('php://output', 'w');
  fputcsv($out, ['順位', 'いいね数', 'タイトル', 'カテゴリ', '公開日', '記事ID', '最終更新']);
  foreach ($rows as $i => $r) {
    fputcsv($out, [$i + 1, $r['count'], $r['title'], $r['category'], $r['date'], $r['id'],
                   $r['updated_at'] === '' ? '' : date('Y-m-d H:i', strtotime($r['updated_at']))]);
  }
  fclose($out);
  exit;
}

/** 順位の表示（同数は同順位） */
function lk_rank(array $rows, int $i): string {
  $r = 1;
  for ($j = 0; $j < $i; $j++) if ($rows[$j]['count'] > $rows[$i]['count']) $r = $j + 2;
  return (string)$r;
}
?>
<!DOCTYPE html>
<html lang="ja"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>いいね管理｜管理画面</title>
<meta name="robots" content="noindex,nofollow">
<link rel="stylesheet" href="/admin/assets/admin.css?v=<?= h(asset_ver()) ?>">
<style>
  .lk-stats{display:flex;flex-wrap:wrap;gap:12px;margin:0 0 18px}
  .lk-stat{background:#fff;border:1px solid #e3e8ea;border-radius:10px;padding:12px 18px;min-width:150px}
  .lk-stat__label{display:block;font-size:.78rem;color:#6b7a80;margin-bottom:4px}
  .lk-stat__num{display:block;font-size:1.5rem;font-weight:700;color:#12597a;line-height:1.2}
  .lk-tabs{display:flex;gap:8px;margin:0 0 14px;flex-wrap:wrap;align-items:center}
  .lk-tab{display:inline-block;padding:7px 16px;border-radius:999px;border:1px solid #cfdbe1;background:#fff;color:#35454d;text-decoration:none;font-size:.85rem;font-weight:600}
  .lk-tab.is-active{background:#12597a;border-color:#12597a;color:#fff}
  .lk-note{font-size:.82rem;color:#6b7a80;line-height:1.8;margin:0 0 16px}
  .lk-rank{font-weight:700;color:#12597a;text-align:center;width:3.5em}
  .lk-num{font-weight:700;text-align:right;width:6em;white-space:nowrap}
  .lk-zero{color:#9aa8ae}
  @media (max-width:640px){
    .admin-table th:nth-child(4),.admin-table td:nth-child(4),
    .admin-table th:nth-child(7),.admin-table td:nth-child(7){display:none}
  }
</style>
</head><body>
<header class="admin-bar">
  <span class="admin-bar__title"><a href="/admin/">← ダッシュボード</a></span>
  <a href="/admin/logout.php" class="admin-bar__logout">ログアウト</a>
</header>
<main class="admin-main">
  <div class="admin-head">
    <h1>いいね管理・ランキング</h1>
    <a class="admin-btn" href="?mode=<?= h($mode) ?>&amp;csv=1">CSVで書き出す</a>
  </div>

  <?php if ($err): ?>
    <p class="admin-error">データの取得に失敗しました。<br><small><?= htmlspecialchars($err) ?></small></p>
  <?php endif; ?>

  <?php if (!BLOG_LIKE_ENABLED): ?>
    <p class="admin-error">いいね機能は現在停止中です（<code>includes/config.php</code> の <code>BLOG_LIKE_ENABLED</code> が false）。過去の件数は下に表示されます。</p>
  <?php endif; ?>

  <div class="lk-stats">
    <div class="lk-stat"><span class="lk-stat__label">いいね 合計</span><span class="lk-stat__num"><?= number_format($total_likes) ?></span></div>
    <div class="lk-stat"><span class="lk-stat__label">いいねがついた記事</span><span class="lk-stat__num"><?= number_format($liked_articles) ?></span></div>
    <div class="lk-stat"><span class="lk-stat__label">公開記事の総数</span><span class="lk-stat__num"><?= number_format(count(array_filter($meta, fn($m) => $m['published']))) ?></span></div>
  </div>

  <nav class="lk-tabs">
    <a class="lk-tab<?= $mode === 'liked' ? ' is-active' : '' ?>" href="?mode=liked">いいねがある記事</a>
    <a class="lk-tab<?= $mode === 'all'   ? ' is-active' : '' ?>" href="?mode=all">全記事（0件も表示）</a>
  </nav>

  <p class="lk-note">
    いいねは記事ごとの累計です。押した本人のブラウザでは取り消しもできます。<br>
    「どの記事が読まれているか」はGA4の記事別PVで、「読んだ人が良いと感じたか」はこの数字で見てください。両方を並べて判断すると、次に書く記事の方向が決まります。
  </p>

  <table class="admin-table">
    <thead><tr>
      <th class="lk-rank">順位</th><th class="lk-num">いいね</th><th>タイトル</th>
      <th>カテゴリ</th><th>公開日</th><th></th><th>最終更新</th>
    </tr></thead>
    <tbody>
    <?php foreach ($rows as $i => $r): ?>
      <tr>
        <td class="lk-rank"><?= $r['count'] > 0 ? h(lk_rank($rows, $i)) : '—' ?></td>
        <td class="lk-num<?= $r['count'] > 0 ? '' : ' lk-zero' ?>"><?= number_format($r['count']) ?></td>
        <td><?= htmlspecialchars($r['title']) ?><?php if (!$r['published']): ?> <small style="color:#c0392b">（非公開）</small><?php endif; ?></td>
        <td><?= htmlspecialchars($r['category']) ?></td>
        <td><?= htmlspecialchars($r['date']) ?></td>
        <td style="white-space:nowrap">
          <a href="/blog/?id=<?= urlencode($r['id']) ?>" target="_blank" rel="noopener">記事</a>
          ／<a href="/admin/news/edit.php?id=<?= urlencode($r['id']) ?>">編集</a>
        </td>
        <td style="white-space:nowrap;font-size:.82rem;color:#6b7a80"><?= $r['updated_at'] === '' ? '—' : h(date('Y-m-d H:i', strtotime($r['updated_at']))) ?></td>
      </tr>
    <?php endforeach; ?>
    <?php if (!$rows): ?>
      <tr><td colspan="7">まだいいねがありません。記事ページのいいねボタンが表示されているかご確認ください。</td></tr>
    <?php endif; ?>
    </tbody>
  </table>
</main>
<?= dev_badge_html() ?>
</body></html>
