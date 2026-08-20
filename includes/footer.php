<?php require_once __DIR__ . '/config.php'; ?>
<footer class="site-footer">
  <div class="site-footer__inner">
    <div class="site-footer__brand">
      <p class="site-footer__name"><?= h(SITE['name']) ?></p>
      <p class="site-footer__addr">〒<?= h(SITE['zip']) ?> <?= h(SITE['address']) ?></p>
      <p class="site-footer__tel">TEL <?= h(SITE['tel']) ?>（<?= h(SITE['hours_jp']) ?>）</p>
      <p class="site-footer__mail"><?= h(SITE['email']) ?></p>
    </div>
    <nav class="site-footer__nav" aria-label="フッターナビ">
      <ul>
        <?php foreach (SERVICES as $s): ?>
          <li><a href="/<?= h($s['slug']) ?>/"><?= h($s['title']) ?></a></li>
        <?php endforeach; ?>
      </ul>
      <ul>
        <li><a href="/kuyou/">ご供養について</a></li>
        <li><a href="/gokuyou/">よくあるご質問</a></li>
        <li><a href="/staff/">スタッフ紹介</a></li>
        <li><a href="/company/">会社概要</a></li>
        <li><a href="/contact/">お問い合わせ</a></li>
        <li><a href="/privacy/">プライバシーポリシー</a></li>
      </ul>
    </nav>
  </div>
  <p class="site-footer__copy">&copy; <?= date('Y') ?> <?= h(SITE['name']) ?></p>
  <p class="site-footer__ver" style="margin-top:6px;font-size:.68rem;opacity:.55;letter-spacing:.05em"><?= h(APP_VERSION) ?></p>
</footer>

<!-- 右側固定・縦長「供養の選び方」タブ -->
<a href="/shindan/" class="side-finder" aria-label="供養の選び方（かんたん診断）">
  <span class="sf-badge" aria-hidden="true">?</span>
  <span class="sf-label">供養の選び方</span>
</a>

<!-- SP固定フッターCTA -->
<div class="sticky-cta">
  <a href="tel:<?= h(SITE['tel']) ?>" class="sticky-cta__tel">電話で相談</a>
  <a href="<?= h(SITE['line_url']) ?>" target="_blank" rel="noopener" class="sticky-cta__line">LINEで相談</a>
</div>

<script src="/assets/js/common.js?v=<?= h(asset_ver()) ?>" defer></script>
<script src="/assets/js/track.js?v=<?= h(asset_ver()) ?>" defer></script>
<?= dev_badge_html() ?>
</body>
</html>
