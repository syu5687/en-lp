<?php
/**
 * GA4（gtag.js）共通パーツ。
 * config.php の GA4_MEASUREMENT_ID が設定されている時だけ出力。
 * head.php と index.php の <head> 内で require する。
 */
require_once __DIR__ . '/config.php';
// 管理画面に入室したことのある端末（運営者）にはGA4タグを出力しない（自己アクセスの除外）
if (!empty($_COOKIE['en_nt'])) return;
// 本番ドメイン以外（ローカル開発・プレビュー・Cloud Run直URL）では出力しない（テスト閲覧の混入防止）
$ga4_host = $_SERVER['HTTP_HOST'] ?? '';
if ($ga4_host !== 'en1150.co.jp' && $ga4_host !== 'www.en1150.co.jp') return;
if (defined('GA4_MEASUREMENT_ID') && GA4_MEASUREMENT_ID):
?>
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=<?= h(GA4_MEASUREMENT_ID) ?>"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', '<?= h(GA4_MEASUREMENT_ID) ?>');

  // ---- CVイベント自動計測（電話タップ・LINEタップ）----
  // tel_click / line_click を全ページで自動送信。
  // フォーム送信の generate_lead は /contact/ 側で送信済み。
  // GA4管理画面でこの3つを「キーイベント」に設定するとCVとして集計されます。
  document.addEventListener('click', function (e) {
    var a = e.target && e.target.closest ? e.target.closest('a[href]') : null;
    if (!a) return;
    var href = a.getAttribute('href') || '';
    try {
      if (href.indexOf('tel:') === 0) {
        gtag('event', 'tel_click', { phone_number: href.slice(4), page_path: location.pathname });
        return;
      }
      var host = '';
      try { host = new URL(href, location.href).hostname.toLowerCase(); } catch (e2) {}
      if (host === 'line.me' || host === 'lin.ee' || (host && host.slice(-8) === '.line.me')) {
        gtag('event', 'line_click', { page_path: location.pathname });
      }
    } catch (err) {}
  }, true);
</script>
<?php endif; ?>
