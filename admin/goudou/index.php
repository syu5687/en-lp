<?php
/** 合同海洋散骨 実施予定日の管理（一覧・追加・編集・削除） */
require __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../includes/store.php';

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!empty($_POST['delete_id'])) {
    goudou_delete((string)$_POST['delete_id']);
    $msg = '削除しました。';
  } else {
    $id = trim((string)($_POST['id'] ?? ''));
    $date = trim((string)($_POST['date'] ?? ''));
    if ($date === '') {
      $msg = '開催日を入力してください。';
    } else {
      if ($id === '') $id = 'g' . str_replace('-', '', $date) . '-' . substr(uniqid(), -4);
      goudou_upsert([
        'id'        => $id,
        'date'      => $date,
        'sea'       => trim((string)($_POST['sea'] ?? '')),
        'status'    => (string)($_POST['status'] ?? '受付中'),
        'note'      => trim((string)($_POST['note'] ?? '')),
        'published' => !empty($_POST['published']),
      ]);
      $msg = '保存しました。';
    }
  }
}

$items = [];
$fs_error = '';
try { $items = goudou_all(); } catch (Throwable $e) { $fs_error = $e->getMessage(); }
$edit = null;
if (!empty($_GET['edit'])) {
  foreach ($items as $it) if (($it['id'] ?? '') === $_GET['edit']) { $edit = $it; break; }
}
?>
<!DOCTYPE html>
<html lang="ja"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>合同散骨 実施予定日｜管理画面</title>
<meta name="robots" content="noindex,nofollow">
<link rel="stylesheet" href="/admin/assets/admin.css?v=<?= h(asset_ver()) ?>">
<style>
  .gd-table{width:100%;border-collapse:collapse;background:#fff;border-radius:10px;overflow:hidden;font-size:.92rem}
  .gd-table th,.gd-table td{padding:10px 14px;border-bottom:1px solid #eee;text-align:left}
  .gd-table th{background:#f2f6f8;font-size:.8rem;color:#456}
  .gd-badge{display:inline-block;padding:2px 10px;border-radius:999px;font-size:.75rem;font-weight:700}
  .gd-badge--on{background:#e8f5e9;color:#2e7d32}
  .gd-badge--off{background:#eee;color:#888}
  .gd-past{opacity:.55}
  .gd-form{background:#fff;border-radius:10px;padding:20px;margin-bottom:26px;display:grid;gap:12px;max-width:640px}
  .gd-form label{display:flex;flex-direction:column;gap:6px;font-weight:600;font-size:.85rem}
  .gd-form input,.gd-form select{padding:9px;border:1px solid #ccd5da;border-radius:8px;font-size:.95rem;font-family:inherit}
  .gd-msg{background:#e8f5e9;color:#2e7d32;padding:10px 16px;border-radius:8px;margin-bottom:16px;font-size:.9rem}
</style>
</head><body>
<header class="admin-bar">
  <span class="admin-bar__title"><a href="/admin/">← ダッシュボード</a>　合同散骨 実施予定日</span>
  <a href="/admin/logout.php" class="admin-bar__logout">ログアウト</a>
</header>
<main class="admin-main">
  <h1>合同海洋散骨 実施予定日</h1>
  <p style="font-size:.9rem;color:#667;margin-bottom:18px">ここで登録した開催日が、トップページと福岡ページの「合同海洋散骨 実施予定日」枠に表示されます（公開中かつ本日以降の日付のみ）。</p>
  <?php if ($msg): ?><p class="gd-msg"><?= h($msg) ?></p><?php endif; ?>
  <?php if ($fs_error): ?><p class="gd-msg" style="background:#fdecea;color:#c0392b">データ取得エラー: <?= h($fs_error) ?></p><?php endif; ?>

  <form method="post" class="gd-form">
    <h2 style="font-size:1rem"><?= $edit ? '開催日を編集' : '開催日を追加' ?></h2>
    <input type="hidden" name="id" value="<?= h($edit['id'] ?? '') ?>">
    <label>開催日（必須）
      <input type="date" name="date" required value="<?= h($edit['date'] ?? '') ?>">
    </label>
    <label>海域・出航地
      <input type="text" name="sea" placeholder="例：鹿児島・錦江湾／福岡・博多湾" value="<?= h($edit['sea'] ?? '鹿児島・錦江湾') ?>">
    </label>
    <label>受付状況
      <?php $st = $edit['status'] ?? '受付中'; ?>
      <select name="status">
        <?php foreach (['受付中', '残りわずか', '受付終了'] as $o): ?>
          <option value="<?= h($o) ?>" <?= $st === $o ? 'selected' : '' ?>><?= h($o) ?></option>
        <?php endforeach; ?>
      </select>
    </label>
    <label>備考（任意）
      <input type="text" name="note" placeholder="例：お申し込み締切 ○月○日" value="<?= h($edit['note'] ?? '') ?>">
    </label>
    <label class="admin-check" style="flex-direction:row;align-items:center;gap:8px;font-weight:600">
      <input type="checkbox" name="published" value="1" <?= (!$edit || !empty($edit['published'])) ? 'checked' : '' ?>> 公開する
    </label>
    <div>
      <button type="submit" class="admin-btn">保存</button>
      <?php if ($edit): ?><a href="/admin/goudou/" class="admin-btn admin-btn--outline" style="margin-left:8px">キャンセル</a><?php endif; ?>
    </div>
  </form>

  <table class="gd-table">
    <tr><th>開催日</th><th>海域・出航地</th><th>受付状況</th><th>備考</th><th>公開</th><th></th></tr>
    <?php $today = date('Y-m-d'); foreach ($items as $it): ?>
      <tr class="<?= ($it['date'] ?? '') < $today ? 'gd-past' : '' ?>">
        <td style="font-weight:700"><?= h($it['date'] ?? '') ?><?= ($it['date'] ?? '') < $today ? '（終了）' : '' ?></td>
        <td><?= h($it['sea'] ?? '') ?></td>
        <td><?= h($it['status'] ?? '') ?></td>
        <td><?= h($it['note'] ?? '') ?></td>
        <td><span class="gd-badge <?= !empty($it['published']) ? 'gd-badge--on' : 'gd-badge--off' ?>"><?= !empty($it['published']) ? '公開中' : '非公開' ?></span></td>
        <td style="white-space:nowrap">
          <a href="?edit=<?= h(rawurlencode($it['id'] ?? '')) ?>">編集</a>
          <form method="post" style="display:inline" onsubmit="return confirm('この開催日を削除しますか？');">
            <input type="hidden" name="delete_id" value="<?= h($it['id'] ?? '') ?>">
            <button type="submit" style="background:none;border:0;color:#c0392b;cursor:pointer;padding:0;font-size:inherit;font-family:inherit">削除</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
    <?php if (!$items && !$fs_error): ?><tr><td colspan="6" style="color:#888">まだ開催日が登録されていません。上のフォームから追加してください。</td></tr><?php endif; ?>
  </table>
</main>
</body></html>
