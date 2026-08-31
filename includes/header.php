<?php
require_once __DIR__ . '/config.php';
// グローバルナビのサブメニュー（親のhref => 子項目）
$nav_sub = [
  '/service/' => array_merge(
    [['label' => 'サービス一覧を見る', 'href' => '/service/', 'top' => true]],
    // 地域ページを持つサービスは、親の直下に子項目を差し込む
    //   /kaiyou-sou/    = 鹿児島・錦江湾 ／ /kaiyou-sou/fukuoka/ = 福岡・博多湾
    //   /grave/         = 鹿児島の墓じまい ／ /grave/fukuoka/     = 福岡の墓じまい
    //   いずれも親URLは変更しない（既存のSEO評価を維持するため）
    array_merge(...array_map(function ($s) {
      $regions = [
        'kaiyou-sou' => [['鹿児島・錦江湾', '/kaiyou-sou/'], ['福岡・博多湾', '/kaiyou-sou/fukuoka/']],
        'grave'      => [['鹿児島・墓じまい', '/grave/'],    ['福岡・墓じまい', '/grave/fukuoka/']],
      ];
      $rows = [['label' => $s['title'], 'href' => '/' . $s['slug'] . '/']];
      foreach ($regions[$s['slug']] ?? [] as [$label, $href]) {
        $rows[] = ['label' => $label, 'href' => $href, 'child' => true];
      }
      return $rows;
    }, SERVICES)),
    [
      ['label' => '海洋散骨 生前契約', 'href' => '/seizen/'],
      ['label' => '対応エリア',        'href' => '/area/'],
    ]
  ),
  '/shindan/' => [
    ['label' => '供養の選び方（かんたん診断）', 'href' => '/shindan/', 'top' => true],
    ['label' => '供養のお悩み解決',   'href' => '/onayami/'],
    ['label' => 'よくあるご質問',     'href' => '/gokuyou/'],
    ['label' => '供養用語辞典',       'href' => '/glossary/'],
  ],
];
require_once __DIR__ . '/lang-switch.php';
en_lang_switch_css();
?>
<header class="site-header">
  <div class="site-header__inner">
    <a href="/" class="site-logo" aria-label="<?= h(SITE['name']) ?> トップページ">
      <?php /* ヘッダーはロゴマークのみ。社名テキストは横幅確保のため置かない。
               正式社名は alt / aria-label と、FV本文・会社概要・フッターで担保している。 */ ?>
      <img src="<?= h(SITE['logo']) ?>" alt="<?= h(SITE['name']) ?> ロゴ" class="site-logo__img">
    </a>
    <nav class="site-nav" aria-label="グローバルナビゲーション">
      <button class="site-nav__toggle" aria-label="メニューを開く" aria-expanded="false">
        <span class="site-nav__toggle-label" aria-hidden="true">MENU</span>
        <span class="site-nav__toggle-bars"><span></span><span></span><span></span></span>
      </button>
      <ul class="site-nav__list">
        <?php /* SPメニュー最上部：言語切替（メニューを開いて最初に目に入る位置） */ ?>
        <li class="site-nav__lang"><?php en_lang_switch('menu_sp'); ?></li>
        <?php /* SPメニュー最上部の重要導線（PCナビには表示しない） */ ?>
        <li class="sp-nav-extra sp-nav-shindan"><a href="/shindan/">供養の選び方（かんたん診断）<span class="sp-nav-shindan__tag">約3分</span></a></li>
        <?php foreach (NAV as $item): $sub = $nav_sub[$item['href']] ?? null; ?>
          <?php if ($sub): ?>
            <li class="has-subnav">
              <a href="<?= h($item['href']) ?>" aria-haspopup="true"><?= h($item['label']) ?><span class="subnav-caret" aria-hidden="true">▾</span></a>
              <ul class="subnav">
                <?php foreach ($sub as $c): ?>
                  <li class="<?= !empty($c['top']) ? 'subnav__top' : (!empty($c['child']) ? 'subnav__child' : '') ?>"><a href="<?= h($c['href']) ?>"><?= h($c['label']) ?></a></li>
                <?php endforeach; ?>
              </ul>
            </li>
          <?php else: ?>
            <li><a href="<?= h($item['href']) ?>"><?= h($item['label']) ?></a></li>
          <?php endif; ?>
        <?php endforeach; ?>
        <?php /* SPハンバーガー専用の追加リンク（PCナビには表示しない） */ ?>
        <li class="sp-nav-extra"><a href="/flow/">お申込みの流れ</a></li>
        <li class="sp-nav-extra"><a href="/fukuoka/">福岡営業所</a></li>
        <li class="sp-nav-extra"><a href="/kuyou/">ご供養について</a></li>
        <li class="sp-nav-extra"><a href="/company/">会社概要</a></li>
        <li class="sp-nav-extra"><a href="/contact/">お問い合わせ</a></li>
        <li class="sp-nav-extra sp-nav-extra--sub"><a href="/policy/">キャンセルポリシー</a></li>
        <li class="sp-nav-extra sp-nav-extra--sub"><a href="/privacy/">プライバシーポリシー</a></li>
        <li class="site-nav__insta"><a href="https://www.instagram.com/en1150en/" target="_blank" rel="noopener" aria-label="Instagram" title="Instagram" style="display:inline-flex;align-items:center;gap:6px"><svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2.2c3.2 0 3.6 0 4.85.07 1.17.05 1.8.25 2.23.41.56.22.96.48 1.38.9.42.42.68.82.9 1.38.16.42.36 1.06.41 2.23.06 1.26.07 1.64.07 4.85s0 3.6-.07 4.85c-.05 1.17-.25 1.8-.41 2.23-.22.56-.48.96-.9 1.38-.42.42-.82.68-1.38.9-.42.16-1.06.36-2.23.41-1.26.06-1.64.07-4.85.07s-3.6 0-4.85-.07c-1.17-.05-1.8-.25-2.23-.41a3.7 3.7 0 0 1-1.38-.9 3.7 3.7 0 0 1-.9-1.38c-.16-.42-.36-1.06-.41-2.23C2.2 15.6 2.2 15.2 2.2 12s0-3.6.07-4.85c.05-1.17.25-1.8.41-2.23.22-.56.48-.96.9-1.38.42-.42.82-.68 1.38-.9.42-.16 1.06-.36 2.23-.41C8.4 2.2 8.8 2.2 12 2.2m0-2.2C8.7 0 8.3 0 7.05.07 5.78.13 4.9.33 4.14.63c-.79.3-1.46.72-2.13 1.38A5.9 5.9 0 0 0 .63 4.14C.33 4.9.13 5.78.07 7.05 0 8.3 0 8.7 0 12s0 3.7.07 4.95c.06 1.27.26 2.15.56 2.91.3.79.72 1.46 1.38 2.13a5.9 5.9 0 0 0 2.13 1.38c.76.3 1.64.5 2.91.56C8.3 24 8.7 24 12 24s3.7 0 4.95-.07c1.27-.06 2.15-.26 2.91-.56a5.9 5.9 0 0 0 2.13-1.38 5.9 5.9 0 0 0 1.38-2.13c.3-.76.5-1.64.56-2.91C24 15.7 24 15.3 24 12s0-3.7-.07-4.95c-.06-1.27-.26-2.15-.56-2.91a5.9 5.9 0 0 0-1.38-2.13A5.9 5.9 0 0 0 19.86.63c-.76-.3-1.64-.5-2.91-.56C15.7 0 15.3 0 12 0Zm0 5.84A6.16 6.16 0 1 0 18.16 12 6.16 6.16 0 0 0 12 5.84Zm0 10.15A4 4 0 1 1 16 12a4 4 0 0 1-4 4Zm7.85-10.4a1.44 1.44 0 1 1-1.44-1.44 1.44 1.44 0 0 1 1.44 1.44Z"/></svg><span class="site-nav__insta-label">Instagram</span></a></li>
        <li class="site-nav__cta">
          <a href="/contact/">資料請求・ご相談</a>
        </li>
        <?php /* 言語切替はCTAの右。主CTAより一段弱い視覚優先度にしている */ ?>
        <li class="site-nav__lang-pc"><?php en_lang_switch('header_pc', 'dark'); ?></li>
      </ul>
    </nav>
  </div>
</header>
<?php require __DIR__ . '/fontsize.php'; ?>
