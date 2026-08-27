<?php
require __DIR__ . '/includes/auth.php';
require __DIR__ . '/includes/store.php';
// 件数は軽量な集計クエリで取得（本文を読まないためタイムアウトしない）
$news_err = $voice_err = '';
try { $news_count  = fs_count(NEWS_COLLECTION); }   catch (Throwable $e) { $news_count  = null; $news_err  = $e->getMessage(); }
try { $voice_count = fs_count(VOICES_COLLECTION); } catch (Throwable $e) { $voice_count = null; $voice_err = $e->getMessage(); }
?>
<!DOCTYPE html>
<html lang="ja"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ダッシュボード｜管理画面</title>
<meta name="robots" content="noindex,nofollow">
<link rel="stylesheet" href="/admin/assets/admin.css?v=<?= h(asset_ver()) ?>">
</head><body>
<header class="admin-bar">
  <span class="admin-bar__title">有限会社 縁 — 管理画面</span>
  <a href="/admin/logout.php" class="admin-bar__logout">ログアウト</a>
</header>
<main class="admin-main">
  <h1>ダッシュボード</h1>
  <?php if ($news_err || $voice_err): ?><p style="background:#fdecea;color:#c0392b;padding:10px 16px;border-radius:8px;margin-bottom:14px;font-size:.85rem"><?= h($news_err ?: $voice_err) ?></p><?php endif; ?>
  <div class="admin-cards">
    <a class="admin-card" href="/admin/news/">
      <span class="admin-card__label">ブログ・お知らせ</span>
      <span class="admin-card__num"><?= $news_count === null ? '取得エラー' : $news_count . ' 件' ?></span>
    </a>
    <a class="admin-card" href="/admin/voice/">
      <span class="admin-card__label">お客様の声</span>
      <span class="admin-card__num"><?= $voice_count === null ? '取得エラー' : $voice_count . ' 件' ?></span>
    </a>
    <a class="admin-card" href="/admin/goudou/">
      <span class="admin-card__label">合同散骨 実施予定日</span>
      <span class="admin-card__num">編集 →</span>
    </a>
    <a class="admin-card" href="/admin/inquiries/">
      <span class="admin-card__label">お問い合わせ受信・解析</span>
      <span class="admin-card__num">表示 →</span>
    </a>
    <a class="admin-card" href="/admin/analytics/">
      <span class="admin-card__label">アクセス解析</span>
      <span class="admin-card__num">表示 →</span>
    </a>
    <a class="admin-card" href="/admin/report/">
      <span class="admin-card__label">効果測定レポート（GA4・15日ごと）</span>
      <span class="admin-card__num">表示 →</span>
    </a>
    <a class="admin-card" href="/admin/ga4/">
      <span class="admin-card__label">GA4 詳細解析（プロ向け）</span>
      <span class="admin-card__num">表示 →</span>
    </a>
    <a class="admin-card" href="/admin/health.php">
      <span class="admin-card__label">Firestore接続検証</span>
      <span class="admin-card__num">確認 →</span>
    </a>
    <!-- 将来の管理モジュールはここに追加（お客様の声 / お問い合わせ受信 など） -->
  </div>
</main>
<?= dev_badge_html() ?>
</body></html>
