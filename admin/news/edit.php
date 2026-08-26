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
<!-- リッチテキストエディタ（Quill） -->
<link rel="stylesheet" href="/admin/assets/quill/quill.snow.css?v=<?= h(asset_ver()) ?>">
<script src="/admin/assets/quill/quill.js?v=<?= h(asset_ver()) ?>"></script>
<style>
  .rte-wrap{background:#fff;border-radius:8px}
  #editor{min-height:320px;font-size:1rem;line-height:1.9;background:#fff}
  #editor .ql-editor{min-height:320px}
  #editor img{max-width:100%;height:auto}
  .ql-toolbar.ql-snow{border-radius:8px 8px 0 0;background:#fafaf6}
  .ql-container.ql-snow{border-radius:0 0 8px 8px;font-family:inherit}
  /* 文字サイズの選択肢を日本語表記に */
  .ql-picker.ql-size .ql-picker-label::before,
  .ql-picker.ql-size .ql-picker-item::before{content:'標準'}
  .ql-picker.ql-size .ql-picker-label[data-value="0.85em"]::before,
  .ql-picker.ql-size .ql-picker-item[data-value="0.85em"]::before{content:'小'}
  .ql-picker.ql-size .ql-picker-label[data-value="1.2em"]::before,
  .ql-picker.ql-size .ql-picker-item[data-value="1.2em"]::before{content:'大'}
  .ql-picker.ql-size .ql-picker-label[data-value="1.5em"]::before,
  .ql-picker.ql-size .ql-picker-item[data-value="1.5em"]::before{content:'特大'}
  /* 見出しの選択肢を日本語表記に */
  .ql-picker.ql-header .ql-picker-label::before,
  .ql-picker.ql-header .ql-picker-item::before{content:'本文'}
  .ql-picker.ql-header .ql-picker-label[data-value="3"]::before,
  .ql-picker.ql-header .ql-picker-item[data-value="3"]::before{content:'見出し'}
  .ql-picker.ql-header .ql-picker-label[data-value="4"]::before,
  .ql-picker.ql-header .ql-picker-item[data-value="4"]::before{content:'小見出し'}
  .rte-note{font-size:.8rem;color:#888;margin-top:6px;font-weight:400}
</style>
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
    <div class="admin-rte">
      <p style="font-weight:600;font-size:.9rem;margin:0 0 8px">本文</p>
      <div class="rte-wrap">
        <div id="editor"></div>
      </div>
      <p class="rte-note">太字・文字サイズ・色・リンク・画像の挿入ができます。画像ボタン（🖼）で本文の好きな位置に画像を入れられます。</p>
      <textarea name="body" id="body-plain" hidden><?= htmlspecialchars($item['body'] ?? '') ?></textarea>
      <input type="hidden" name="body_html" id="body-html" value="">
      <?php /* 既存本文をエディタへ安全に渡す（JSONとして埋め込み） */ ?>
      <script id="rte-initial" type="application/json"><?= json_encode([
        'html' => (string)($item['body_html'] ?? ''),
        'text' => (string)($item['body'] ?? ''),
      ], JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP) ?></script>
    </div>

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
<script>
/* ===== リッチテキストエディタ（Quill）初期化 ===== */
(function () {
  // 文字サイズ・配置は class ではなく style 属性で出力（公開ページ側でそのまま表示できる）
  var SizeStyle = Quill.import('attributors/style/size');
  SizeStyle.whitelist = ['0.85em', '1.2em', '1.5em'];
  Quill.register(SizeStyle, true);
  var AlignStyle = Quill.import('attributors/style/align');
  Quill.register(AlignStyle, true);

  // 画像ボタン：アップロードAPI経由でGCSへ保存し、URLをカーソル位置へ挿入
  async function shrinkFile(file) {
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
  function imageHandler() {
    var q = this.quill;
    var inp = document.createElement('input');
    inp.type = 'file';
    inp.accept = 'image/jpeg,image/png,image/gif,image/webp';
    inp.onchange = async function () {
      var file = inp.files && inp.files[0];
      if (!file) return;
      try {
        var f  = await shrinkFile(file);
        var fd = new FormData();
        fd.append('file', f, f.name);
        var r = await fetch('/admin/upload.php', { method: 'POST', body: fd });
        var j = await r.json();
        if (!j.ok) { alert(j.error || '画像のアップロードに失敗しました'); return; }
        var range = q.getSelection(true);
        q.insertEmbed(range.index, 'image', j.url, 'user');
        q.setSelection(range.index + 1);
      } catch (e) { alert('画像のアップロードで通信エラーが発生しました'); }
    };
    inp.click();
  }

  var quill = new Quill('#editor', {
    theme: 'snow',
    placeholder: '本文を入力してください',
    modules: {
      toolbar: {
        container: [
          [{ header: [3, 4, false] }],
          ['bold', 'italic', 'underline', 'strike'],
          [{ size: ['0.85em', false, '1.2em', '1.5em'] }],
          [{ color: [] }, { background: [] }],
          [{ list: 'ordered' }, { list: 'bullet' }, { align: [] }],
          ['link', 'image'],
          ['clean']
        ],
        handlers: { image: imageHandler }
      }
    }
  });

  // 既存本文の読み込み（HTML本文があればそれを、無ければプレーン本文を段落に）
  try {
    var init = JSON.parse(document.getElementById('rte-initial').textContent || '{}');
    if (init.html) {
      quill.setContents(quill.clipboard.convert({ html: init.html }), 'silent');
    } else if (init.text) {
      var html = init.text.split(/\n+/).map(function (t) {
        t = t.trim();
        return t ? '<p>' + t.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;') + '</p>' : '';
      }).join('');
      if (html) quill.setContents(quill.clipboard.convert({ html: html }), 'silent');
    }
  } catch (_) {}

  // 保存時：整形済みHTMLとプレーンテキスト（一覧の抜粋用）を隠しフィールドへ
  document.querySelector('.admin-form').addEventListener('submit', function () {
    var text = quill.getText().replace(/\s+$/,'');
    var hasImage = quill.root.querySelector('img') !== null;
    document.getElementById('body-html').value  = (text || hasImage) ? quill.getSemanticHTML() : '';
    document.getElementById('body-plain').value = text.replace(/\n/g, '\n').trim();
  });
})();
</script>
<?= dev_badge_html() ?>
</body></html>
