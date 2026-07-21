<?php
require __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../includes/store.php';
$id = $_GET['id'] ?? '';
$item = $id ? (news_find($id) ?? []) : [];
$is_new = empty($item);
?>
<!DOCTYPE html>
<html lang="ja"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $is_new ? '新規作成' : '編集' ?>｜お知らせ管理</title>
<meta name="robots" content="noindex,nofollow">
<link rel="stylesheet" href="/admin/assets/admin.css?v=<?= h(asset_ver()) ?>">
</head><body>
<header class="admin-bar">
  <span class="admin-bar__title"><a href="/admin/news/">← 一覧へ戻る</a></span>
  <a href="/admin/logout.php" class="admin-bar__logout">ログアウト</a>
</header>
<main class="admin-main">
  <h1><?= $is_new ? '新規作成' : '記事を編集' ?></h1>
  <form method="post" action="/admin/news/save.php" class="admin-form">
    <input type="hidden" name="id" value="<?= htmlspecialchars($item['id'] ?? '') ?>">
    <label>日付<input type="date" name="date" value="<?= htmlspecialchars($item['date'] ?? date('Y-m-d')) ?>" required></label>
    <fieldset class="admin-cats">
      <legend>カテゴリ（複数選択可）</legend>
      <?php
        $selected = array_filter(array_map('trim', explode(',', (string)($item['category'] ?? ''))));
        foreach (BLOG_CATEGORIES as $cat): ?>
        <label class="admin-check admin-cat"><input type="checkbox" name="categories[]" value="<?= htmlspecialchars($cat) ?>" <?= in_array($cat, $selected, true) ? 'checked' : '' ?>> <?= htmlspecialchars($cat) ?></label>
      <?php endforeach; ?>
    </fieldset>
    <label>タイトル<input type="text" name="title" value="<?= htmlspecialchars($item['title'] ?? '') ?>" required></label>
    <label>本文<textarea name="body" rows="8"><?= htmlspecialchars($item['body'] ?? '') ?></textarea></label>

    <?php
      // 画像（複数）。旧データ（imageのみ）は1枚として引き継ぐ。
      $imgs = [];
      if (!empty($item['images']) && is_array($item['images'])) $imgs = $item['images'];
      elseif (!empty($item['image'])) $imgs = [$item['image']];
    ?>
    <div class="admin-imgs">
      <p class="admin-imgs__label">画像（複数登録可）</p>
      <p class="admin-imgs__note">1枚目がサムネイルとして一覧に表示されます。◀ ▶ で並び替えできます。</p>
      <div id="img-list" class="admin-img-grid">
        <?php foreach ($imgs as $u): ?>
          <div class="admin-img">
            <img src="<?= htmlspecialchars($u) ?>" alt="">
            <input type="hidden" name="images[]" value="<?= htmlspecialchars($u) ?>">
            <span class="admin-img__thumb-badge">サムネイル</span>
            <span class="admin-img__ctrl">
              <button type="button" class="mv" data-dir="-1" title="前へ">◀</button>
              <button type="button" class="mv" data-dir="1" title="後へ">▶</button>
              <button type="button" class="rm" title="削除">×</button>
            </span>
          </div>
        <?php endforeach; ?>
      </div>
      <label class="admin-btn admin-btn--outline admin-imgs__add">＋ 画像を追加
        <input type="file" id="img-input" accept="image/jpeg,image/png,image/gif,image/webp" multiple hidden>
      </label>
      <span id="img-status" class="admin-imgs__status" hidden>アップロード中…</span>
    </div>
    <label>リンクURL（任意・記事詳細や外部ページ）<input type="text" name="link" value="<?= htmlspecialchars($item['link'] ?? '') ?>" placeholder="https://en1150.co.jp/post-xxxx/"></label>
    <label class="admin-check"><input type="checkbox" name="published" value="1" <?= !empty($item['published']) ? 'checked' : '' ?>> 公開する</label>
    <div class="admin-form__actions">
      <button type="submit" class="admin-btn">保存</button>
      <?php if (!$is_new): ?>
        <button type="submit" formaction="/admin/news/delete.php" class="admin-btn admin-btn--danger"
                onclick="return confirm('削除しますか？');">削除</button>
      <?php endif; ?>
    </div>
  </form>
</main>

<script>
(function () {
  var list   = document.getElementById('img-list');
  var input  = document.getElementById('img-input');
  var status = document.getElementById('img-status');

  function makeTile(url) {
    var d = document.createElement('div');
    d.className = 'admin-img';
    d.innerHTML =
      '<img src="' + url.replace(/"/g, '&quot;') + '" alt="">' +
      '<input type="hidden" name="images[]" value="' + url.replace(/"/g, '&quot;') + '">' +
      '<span class="admin-img__thumb-badge">サムネイル</span>' +
      '<span class="admin-img__ctrl">' +
        '<button type="button" class="mv" data-dir="-1" title="前へ">◀</button>' +
        '<button type="button" class="mv" data-dir="1" title="後へ">▶</button>' +
        '<button type="button" class="rm" title="削除">×</button>' +
      '</span>';
    return d;
  }

  // 削除・並び替え（イベント委譲）
  list.addEventListener('click', function (e) {
    var btn = e.target.closest('button');
    if (!btn) return;
    var tile = btn.closest('.admin-img');
    if (btn.classList.contains('rm')) {
      if (confirm('この画像を外しますか？（保存で確定します）')) tile.remove();
      return;
    }
    if (btn.classList.contains('mv')) {
      var dir = parseInt(btn.dataset.dir, 10);
      if (dir < 0 && tile.previousElementSibling) list.insertBefore(tile, tile.previousElementSibling);
      if (dir > 0 && tile.nextElementSibling)     list.insertBefore(tile.nextElementSibling, tile);
    }
  });

  // 大きい画像はブラウザ側で縮小してから送る（最大1600px / JPEG 0.85）
  async function shrink(file) {
    if (file.type === 'image/gif' || file.size < 400 * 1024) return file;
    try {
      var bmp = await createImageBitmap(file);
      var max = 1600;
      var scale = Math.min(1, max / Math.max(bmp.width, bmp.height));
      if (scale >= 1 && file.size < 2 * 1024 * 1024) return file;
      var w = Math.round(bmp.width * scale), h = Math.round(bmp.height * scale);
      var c = document.createElement('canvas'); c.width = w; c.height = h;
      c.getContext('2d').drawImage(bmp, 0, 0, w, h);
      var blob = await new Promise(function (res) { c.toBlob(res, 'image/jpeg', 0.85); });
      return blob && blob.size < file.size ? new File([blob], file.name.replace(/\.\w+$/, '.jpg'), { type: 'image/jpeg' }) : file;
    } catch (_) { return file; }
  }

  input.addEventListener('change', async function () {
    var files = Array.from(input.files || []);
    if (!files.length) return;
    status.hidden = false;
    for (var i = 0; i < files.length; i++) {
      status.textContent = 'アップロード中… (' + (i + 1) + '/' + files.length + ')';
      try {
        var f  = await shrink(files[i]);
        var fd = new FormData();
        fd.append('file', f, f.name);
        var r = await fetch('/admin/upload.php', { method: 'POST', body: fd });
        var j = await r.json();
        if (j.ok) list.appendChild(makeTile(j.url));
        else alert(files[i].name + '：' + (j.error || 'アップロードに失敗しました'));
      } catch (err) {
        alert(files[i].name + '：通信エラーが発生しました');
      }
    }
    status.hidden = true;
    input.value = '';
  });
})();
</script>
<?= dev_badge_html() ?>
</body></html>
