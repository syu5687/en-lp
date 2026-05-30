<?php
/**
 * GA4（gtag.js）共通パーツ。
 * config.php の GA4_MEASUREMENT_ID が設定されている時だけ出力。
 * head.php と index.php の <head> 内で require する。
 */
require_once __DIR__ . '/config.php';
if (defined('GA4_MEASUREMENT_ID') && GA4_MEASUREMENT_ID):
?>
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=<?= h(GA4_MEASUREMENT_ID) ?>"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', '<?= h(GA4_MEASUREMENT_ID) ?>');
</script>
<?php endif; ?>
