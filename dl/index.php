<?php
/**
 * 資料PDFの計測用中継ページ
 * /dl/?f=hakajimai&src=mail のようにアクセスすると、
 * GA4に pdf_open イベントを送ってからPDFへ自動転送する。
 * メール内のリンク・サイト内のリンクをこのURLにすることで、
 * 「どのPDFが・どの経路から・いつ開かれたか」をGA4で解析できる。
 */
require_once __DIR__ . '/../includes/config.php';

$dl_files = [
  'hakajimai' => ['/assets/docs/enshiryou-k7x2/hakajimai-guide.pdf',    '墓じまい完全ガイド 鹿児島・福岡版'],
  'checklist' => ['/assets/docs/enshiryou-k7x2/sankotsu-checklist.pdf', '海洋散骨で後悔しないためのチェックリスト'],
];

$f   = $_GET['f']   ?? '';
$src = preg_replace('/[^a-z0-9_-]/', '', (string)($_GET['src'] ?? 'direct')) ?: 'direct';

if (!isset($dl_files[$f])) { header('Location: /'); exit; }
[$dl_path, $dl_name] = $dl_files[$f];
$dl_url = $dl_path . '?v=' . asset_ver();
?><!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="robots" content="noindex, nofollow">
<title>資料を開いています｜有限会社 縁</title>
<?php require __DIR__ . '/../includes/ga4.php'; ?>
<noscript><meta http-equiv="refresh" content="0;url=<?= h($dl_url) ?>"></noscript>
<style>
body{margin:0;font-family:"Hiragino Kaku Gothic ProN","Noto Sans JP",sans-serif;background:#f4f9fb;color:#23323a;display:grid;place-items:center;min-height:100vh;text-align:center;padding:20px}
.box{background:#fff;border:1px solid #d8e4ea;border-radius:14px;padding:34px 30px;max-width:420px;box-shadow:0 10px 30px rgba(10,56,82,.08)}
.sp{width:34px;height:34px;border:3px solid #d8e4ea;border-top-color:#15709e;border-radius:50%;margin:0 auto 16px;animation:r 1s linear infinite}
@keyframes r{to{transform:rotate(360deg)}}
h1{font-size:1.05rem;color:#12597a;margin:0 0 8px}
p{font-size:.88rem;line-height:1.9;margin:0 0 14px;color:#5c6b73}
a{color:#15709e;font-weight:700}
</style>
</head>
<body>
<div class="box">
  <div class="sp" aria-hidden="true"></div>
  <h1>資料を開いています</h1>
  <p>「<?= h($dl_name) ?>」（PDF）をお届けします。<br>切り替わらない場合は下のリンクをタップしてください。</p>
  <p><a href="<?= h($dl_url) ?>" id="dl-link">📘 資料を開く</a></p>
</div>
<script>
(function () {
  var url = <?= json_encode($dl_url) ?>;
  try {
    if (typeof gtag === 'function') {
      gtag('event', 'pdf_open', {
        pdf_file: <?= json_encode($f) ?>,
        pdf_name: <?= json_encode($dl_name) ?>,
        link_src: <?= json_encode($src) ?>,
        page_path: location.pathname + location.search
      });
    }
  } catch (e) {}
  setTimeout(function () { location.replace(url); }, 500);
})();
</script>
</body>
</html>
