<?php
/**
 * いいね・アクセス管理（ランキング）。
 *
 *  - いいね数  : blog_likes コレクション（生読み）
 *  - アクセス数: site_meta/blog_popular（GA4から取得して保存したもの）
 *
 * 管理画面はアクセス数が少ないため、ここではキャッシュを使わず生読みする。
 * 人気記事データが古い場合は、このページを開いたときに自動で更新する
 * （GA4 Data API を呼ぶのは管理画面側だけ。公開ページからは呼ばない）。
 */
require __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../includes/store.php';
require __DIR__ . '/../../includes/blog-likes.php';
require __DIR__ . '/../../includes/blog-popular.php';

$err = $msg = '';

/* ---- 人気記事データの更新（手動／自動）-------------------------------- */
$do_refresh = (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && ($_POST['action'] ?? '') === 'refresh');
if (!$do_refresh) {
  // 24時間以上古ければ自動更新（1日1回程度に収まる）
  $u = blog_popular_updated_at();
  if ($u === '' || (time() - (int)strtotime($u)) > 86400) $do_refresh = true;
}
if ($do_refresh) {
  try {
    $r = blog_popular_refresh();
    $msg = "人気記事データを更新しました（直近{$r['days']}日間・{$r['count']}記事）。";
  } catch (Throwable $e) {
    $err = 'GA4からの取得に失敗しました：' . $e->getMessage();
  }
}

$pv_updated = blog_popular_updated_at();
$pv_map     = blog_popular_map();

/* ---- いいね ------------------------------------------------------------ */
$likes = [];
try { $likes = blog_like_counts_fresh(); } catch (Throwable $e) { if ($err === '') $err = $e->getMessage(); }

/* ---- 記事のメタ情報 ---------------------------------------------------- */
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

/* ---- 並び替えと絞り込み ------------------------------------------------ */
$sort = in_array(($_GET['sort'] ?? 'likes'), ['likes', 'pv'], true) ? $_GET['sort'] : 'likes';
$mode = ($_GET['mode'] ?? 'active') === 'all' ? 'all' : 'active';

$ids = [];
if ($mode === 'all') {
  foreach ($meta as $id => $m) if ($m['published']) $ids[$id] = true;
} else {
  foreach ($likes as $id => $v) if ((int)$v['count'] > 0) $ids[$id] = true;
  foreach ($pv_map as $id => $pv) if ((int)$pv > 0) $ids[$id] = true;
}

$rows = [];
foreach (array_keys($ids) as $id) {
  $m = $meta[$id] ?? ['title' => '（記事情報が見つかりません）', 'category' => '', 'date' => '', 'published' => false];
  $rows[] = [
    'id'         => $id,
    'likes'      => (int)($likes[$id]['count'] ?? 0),
    'pv'         => (int)($pv_map[$id] ?? 0),
    'updated_at' => (string)($likes[$id]['updated_at'] ?? ''),
  ] + $m;
}
$key = $sort === 'pv' ? 'pv' : 'likes';
usort($rows, function ($a, $b) use ($key) {
  if ($a[$key] !== $b[$key]) return $b[$key] <=> $a[$key];
  return strcmp((string)$b['date'], (string)$a['date']);
});

$total_likes = 0; foreach ($likes as $v)  $total_likes += max(0, (int)$v['count']);
$liked_cnt   = 0; foreach ($likes as $v)  if ((int)$v['count'] > 0) $liked_cnt++;
$total_pv    = 0; foreach ($pv_map as $v) $total_pv += max(0, (int)$v);

/* ---- CSV --------------------------------------------------------------- */
if (!empty($_GET['csv'])) {
  header('Content-Type: text/csv; charset=UTF-8');
  header('Content-Disposition: attachment; filename="blog-ranking-' . date('Ymd') . '.csv"');
  echo "\xEF\xBB\xBF";
  $o = fopen('php://output', 'w');
  fputcsv($o, ['順位', 'いいね', 'アクセス(PV)', 'タイトル', 'カテゴリ', '公開日', '記事ID']);
  foreach ($rows as $i => $r) {
    fputcsv($o, [$i + 1, $r['likes'], $r['pv'], $r['title'], $r['category'], $r['date'], $r['id']]);
  }
  fclose($o);
  exit;
}

/** 同数は同順位 */
function lk_rank(array $rows, int $i, string $key): string {
  $r = 1;
  for ($j = 0; $j < $i; $j++) if ($rows[$j][$key] > $rows[$i][$key]) $r = $j + 2;
  return (string)$r;
}
$qs = fn(array $o) => '?' . http_build_query(array_merge(['sort' => $sort, 'mode' => $mode], $o));
?>
<!DOCTYPE html>
<html lang="ja"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>いいね・アクセス ランキング｜管理画面</title>
<meta name="robots" content="noindex,nofollow">
<link rel="stylesheet" href="/admin/assets/admin.css?v=<?= h(asset_ver()) ?>">
<style>
  .lk-stats{display:flex;flex-wrap:wrap;gap:12px;margin:0 0 18px}
  .lk-stat{background:#fff;border:1px solid #e3e8ea;border-radius:10px;padding:12px 18px;min-width:140px}
  .lk-stat__label{display:block;font-size:.78rem;color:#6b7a80;margin-bottom:4px}
  .lk-stat__num{display:block;font-size:1.5rem;font-weight:700;color:#12597a;line-height:1.2}
  .lk-tabs{display:flex;gap:8px;margin:0 0 6px;flex-wrap:wrap;align-items:center}
  .lk-tab{display:inline-block;padding:7px 16px;border-radius:999px;border:1px solid #cfdbe1;background:#fff;color:#35454d;text-decoration:none;font-size:.85rem;font-weight:600}
  .lk-tab.is-active{background:#12597a;border-color:#12597a;color:#fff}
  .lk-tabs__sep{width:1px;height:22px;background:#dde5e9;margin:0 4px}
  .lk-note{font-size:.82rem;color:#6b7a80;line-height:1.8;margin:10px 0 16px}
  .lk-rank{font-weight:700;color:#12597a;text-align:center;width:3.5em}
  .lk-num{font-weight:700;text-align:right;width:5.5em;white-space:nowrap}
  .lk-zero{color:#9aa8ae;font-weight:400}
  .lk-refresh{display:inline-flex;align-items:center;gap:8px}
  @media (max-width:640px){
    .admin-table th:nth-child(5),.admin-table td:nth-child(5),
    .admin-table th:nth-child(6),.admin-table td:nth-child(6){display:none}
  }
</style>
</head><body>
<header class="admin-bar">
  <span class="admin-bar__title"><a href="/admin/">← ダッシュボード</a></span>
  <a href="/admin/logout.php" class="admin-bar__logout">ログアウト</a>
</header>
<main class="admin-main">
  <div class="admin-head">
    <h1>いいね・アクセス ランキング</h1>
    <a class="admin-btn" href="<?= h($qs(['csv' => 1])) ?>">CSVで書き出す</a>
  </div>

  <?php if ($msg): ?><p style="background:#eaf6ee;color:#1c6b52;padding:10px 16px;border-radius:8px;margin-bottom:14px;font-size:.85rem"><?= h($msg) ?></p><?php endif; ?>
  <?php if ($err): ?><p class="admin-error"><?= htmlspecialchars($err) ?></p><?php endif; ?>
  <?php if (!BLOG_LIKE_ENABLED): ?>
    <p class="admin-error">いいね機能は停止中です（<code>includes/config.php</code> の <code>BLOG_LIKE_ENABLED</code> が false）。</p>
  <?php endif; ?>

  <div class="lk-stats">
    <div class="lk-stat"><span class="lk-stat__label">いいね 合計</span><span class="lk-stat__num"><?= number_format($total_likes) ?></span></div>
    <div class="lk-stat"><span class="lk-stat__label">いいねがついた記事</span><span class="lk-stat__num"><?= number_format($liked_cnt) ?></span></div>
    <div class="lk-stat"><span class="lk-stat__label">記事PV 合計（<?= (int)BLOG_POPULAR_DAYS ?>日）</span><span class="lk-stat__num"><?= number_format($total_pv) ?></span></div>
    <div class="lk-stat"><span class="lk-stat__label">公開記事の総数</span><span class="lk-stat__num"><?= number_format(count(array_filter($meta, fn($m) => $m['published']))) ?></span></div>
  </div>

  <nav class="lk-tabs">
    <a class="lk-tab<?= $sort === 'likes' ? ' is-active' : '' ?>" href="<?= h($qs(['sort' => 'likes'])) ?>">いいねが多い順</a>
    <a class="lk-tab<?= $sort === 'pv'    ? ' is-active' : '' ?>" href="<?= h($qs(['sort' => 'pv'])) ?>">アクセスが多い順</a>
    <span class="lk-tabs__sep"></span>
    <a class="lk-tab<?= $mode === 'active' ? ' is-active' : '' ?>" href="<?= h($qs(['mode' => 'active'])) ?>">実績がある記事</a>
    <a class="lk-tab<?= $mode === 'all'    ? ' is-active' : '' ?>" href="<?= h($qs(['mode' => 'all'])) ?>">全記事</a>
  </nav>

  <p class="lk-note">
    <strong>アクセス数はGA4から取得した直近<?= (int)BLOG_POPULAR_DAYS ?>日間の記事別PV</strong>です。
    最終更新：<?= $pv_updated === '' ? '未取得' : h($pv_updated) ?>（このページを開いたとき、24時間以上古ければ自動で更新します）
    <span class="lk-refresh">
      <form method="post" style="display:inline">
        <?= csrf_field() ?><input type="hidden" name="action" value="refresh">
        <button type="submit" class="admin-btn" style="padding:5px 14px;font-size:.8rem">今すぐ更新</button>
      </form>
    </span><br>
    <strong>いいね</strong>は記事ごとの累計です。「読まれているか（PV）」と「良いと感じたか（いいね）」を並べて見ると、次に書く記事の方向が決まります。
  </p>

  <table class="admin-table">
    <thead><tr>
      <th class="lk-rank">順位</th>
      <th class="lk-num">いいね</th>
      <th class="lk-num">PV</th>
      <th>タイトル</th>
      <th>カテゴリ</th>
      <th>公開日</th>
      <th></th>
    </tr></thead>
    <tbody>
    <?php foreach ($rows as $i => $r): ?>
      <tr>
        <td class="lk-rank"><?= $r[$key] > 0 ? h(lk_rank($rows, $i, $key)) : '—' ?></td>
        <td class="lk-num<?= $r['likes'] > 0 ? '' : ' lk-zero' ?>"><?= number_format($r['likes']) ?></td>
        <td class="lk-num<?= $r['pv'] > 0 ? '' : ' lk-zero' ?>"><?= number_format($r['pv']) ?></td>
        <td><?= htmlspecialchars($r['title']) ?><?php if (!$r['published']): ?> <small style="color:#c0392b">（非公開）</small><?php endif; ?></td>
        <td><?= htmlspecialchars($r['category']) ?></td>
        <td><?= htmlspecialchars($r['date']) ?></td>
        <td style="white-space:nowrap">
          <a href="/blog/?id=<?= urlencode($r['id']) ?>" target="_blank" rel="noopener">記事</a>
          ／<a href="/admin/news/edit.php?id=<?= urlencode($r['id']) ?>">編集</a>
        </td>
      </tr>
    <?php endforeach; ?>
    <?php if (!$rows): ?>
      <tr><td colspan="7">まだ実績がありません。「全記事」タブで一覧を確認できます。</td></tr>
    <?php endif; ?>
    </tbody>
  </table>
</main>
<?= dev_badge_html() ?>
</body></html>
