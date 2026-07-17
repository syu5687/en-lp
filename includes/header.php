<?php require_once __DIR__ . '/config.php'; ?>
<header class="site-header">
  <div class="site-header__inner">
    <a href="/" class="site-logo" aria-label="<?= h(SITE['name']) ?> トップページ">
      <img src="<?= h(SITE['logo']) ?>" alt="<?= h(SITE['name']) ?> ロゴ" class="site-logo__img">
      <span class="site-logo__text"><?= h(SITE['name']) ?></span>
    </a>
    <nav class="site-nav" aria-label="グローバルナビゲーション">
      <button class="site-nav__toggle" aria-label="メニューを開く" aria-expanded="false">
        <span></span><span></span><span></span>
      </button>
      <ul class="site-nav__list">
        <?php foreach (NAV as $item): ?>
          <li><a href="<?= h($item['href']) ?>"><?= h($item['label']) ?></a></li>
        <?php endforeach; ?>
        <li class="site-nav__cta">
          <a href="/contact/">資料請求・ご相談</a>
        </li>
      </ul>
    </nav>
  </div>
</header>
