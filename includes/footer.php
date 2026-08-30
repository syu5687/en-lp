<?php require_once __DIR__ . '/config.php'; ?>
<footer class="site-footer">
  <div class="site-footer__inner">
    <div class="site-footer__brand">
      <p class="site-footer__name"><?= h(SITE['name']) ?></p>
      <p class="site-footer__addr"><span style="font-weight:700">本社</span>　〒<?= h(SITE['zip']) ?> <?= h(SITE['address']) ?></p>
      <p class="site-footer__tel">TEL <?= h(SITE['tel']) ?>（<?= h(SITE['hours_jp']) ?>）</p>
      <p class="site-footer__addr" style="margin-top:8px"><span style="font-weight:700"><?= h(SITE['fukuoka']['name']) ?></span>　〒<?= h(SITE['fukuoka']['zip']) ?> <?= h(SITE['fukuoka']['address']) ?></p>
      <p class="site-footer__tel">TEL <?= h(SITE['fukuoka']['tel']) ?></p>
      <p class="site-footer__mail" style="margin-top:8px"><?= h(SITE['email']) ?></p>
      <p style="margin-top:10px"><a href="https://www.instagram.com/en1150en/" target="_blank" rel="noopener" aria-label="Instagram" style="display:inline-flex;align-items:center;gap:8px;color:inherit;text-decoration:none"><svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2.2c3.2 0 3.6 0 4.85.07 1.17.05 1.8.25 2.23.41.56.22.96.48 1.38.9.42.42.68.82.9 1.38.16.42.36 1.06.41 2.23.06 1.26.07 1.64.07 4.85s0 3.6-.07 4.85c-.05 1.17-.25 1.8-.41 2.23-.22.56-.48.96-.9 1.38-.42.42-.82.68-1.38.9-.42.16-1.06.36-2.23.41-1.26.06-1.64.07-4.85.07s-3.6 0-4.85-.07c-1.17-.05-1.8-.25-2.23-.41a3.7 3.7 0 0 1-1.38-.9 3.7 3.7 0 0 1-.9-1.38c-.16-.42-.36-1.06-.41-2.23C2.2 15.6 2.2 15.2 2.2 12s0-3.6.07-4.85c.05-1.17.25-1.8.41-2.23.22-.56.48-.96.9-1.38.42-.42.82-.68 1.38-.9.42-.16 1.06-.36 2.23-.41C8.4 2.2 8.8 2.2 12 2.2m0-2.2C8.7 0 8.3 0 7.05.07 5.78.13 4.9.33 4.14.63c-.79.3-1.46.72-2.13 1.38A5.9 5.9 0 0 0 .63 4.14C.33 4.9.13 5.78.07 7.05 0 8.3 0 8.7 0 12s0 3.7.07 4.95c.06 1.27.26 2.15.56 2.91.3.79.72 1.46 1.38 2.13a5.9 5.9 0 0 0 2.13 1.38c.76.3 1.64.5 2.91.56C8.3 24 8.7 24 12 24s3.7 0 4.95-.07c1.27-.06 2.15-.26 2.91-.56a5.9 5.9 0 0 0 2.13-1.38 5.9 5.9 0 0 0 1.38-2.13c.3-.76.5-1.64.56-2.91C24 15.7 24 15.3 24 12s0-3.7-.07-4.95c-.06-1.27-.26-2.15-.56-2.91a5.9 5.9 0 0 0-1.38-2.13A5.9 5.9 0 0 0 19.86.63c-.76-.3-1.64-.5-2.91-.56C15.7 0 15.3 0 12 0Zm0 5.84A6.16 6.16 0 1 0 18.16 12 6.16 6.16 0 0 0 12 5.84Zm0 10.15A4 4 0 1 1 16 12a4 4 0 0 1-4 4Zm7.85-10.4a1.44 1.44 0 1 1-1.44-1.44 1.44 1.44 0 0 1 1.44 1.44Z"/></svg><span>Instagram</span></a></p>
      <p style="margin-top:16px"><span style="display:inline-flex;align-items:center;gap:12px;background:#fff;border:1px solid #d9e4e6;border-radius:12px;padding:10px 16px"><img src="/assets/img/jmas-logo.png?v=<?= h(asset_ver()) ?>" alt="一般社団法人 日本海洋散骨協会 ロゴ" width="360" height="454" loading="lazy" style="width:46px;height:auto"><span style="font-size:.78rem;line-height:1.6;color:#5f6d6b;text-align:left">一般社団法人<br><strong style="color:#2a5a7a">日本海洋散骨協会</strong> 加盟事業者</span></span></p>
    </div>
    <nav class="site-footer__nav" aria-label="フッターナビ">
      <ul>
        <?php foreach (SERVICES as $s): ?>
          <li><a href="/<?= h($s['slug']) ?>/"><?= h($s['title']) ?></a></li>
        <?php endforeach; ?>
        <li><a href="/seizen/">海洋散骨 生前契約</a></li>
        <li><a href="/shindan/">供養の選び方（かんたん診断）</a></li>
      </ul>
      <ul>
        <li><a href="/about/">縁とは</a></li>
        <li><a href="/kuyou/">ご供養について</a></li>
        <li><a href="/onayami/">供養のお悩み解決</a></li>
        <li><a href="/voice/">お客様の声</a></li>
        <li><a href="/blog/">終活と供養の話</a></li>
        <li><a href="/flow/">お申込みの流れ</a></li>
        <li><a href="/gokuyou/">よくあるご質問</a></li>
        <li><a href="/glossary/">供養用語辞典</a></li>
        <li><a href="/staff/">スタッフ紹介</a></li>
        <li><a href="/area/">対応エリア</a></li>
        <li><a href="/fukuoka/">福岡営業所</a></li>
        <li><a href="/company/">会社概要</a></li>
        <li><a href="/contact/">お問い合わせ</a></li>
        <li><a href="/policy/">キャンセルポリシー</a></li>
        <li><a href="/privacy/">プライバシーポリシー</a></li>
      </ul>
    </nav>
  </div>
  <p class="site-footer__copy">&copy; <?= date('Y') ?> <?= h(SITE['name']) ?></p>
  <p class="site-footer__ver" style="margin-top:6px;font-size:.68rem;opacity:.55;letter-spacing:.05em;text-align:center"><?= h(APP_VERSION) ?></p>
</footer>

<!-- 右側固定タブは v0235 で廃止（PC/SPとも）。導線はヘッダーナビ・診断誘導・フッターに集約 -->

<!-- SP固定フッターCTA -->
<div class="sticky-cta">
  <a href="tel:<?= h(SITE['tel']) ?>" class="sticky-cta__tel">電話相談</a>
  <a href="/contact/" class="sticky-cta__mail">メール・LINE相談</a>
<?php
  // 第3枠: 次回の合同海洋散骨（予定が無いときはLINE相談に戻す）
  $sc_next = null;
  try { if (!function_exists('goudou_upcoming')) require_once __DIR__ . '/../admin/includes/store.php'; $x = goudou_upcoming(); $sc_next = $x[0] ?? null; } catch (Throwable $e) { $sc_next = null; }
  if ($sc_next): $sc_ts = strtotime((string)($sc_next['date'] ?? '')); $sc_w = $sc_ts ? ['日','月','火','水','木','金','土'][(int)date('w', $sc_ts)] : '';
?>
  <a href="/kaiyou-sou/#goudou-schedule" class="sticky-cta__sched" aria-label="次回の合同海洋散骨の予定日一覧を見る">
    <img src="/assets/img/goudou-photo.jpg?v=<?= h(asset_ver()) ?>" alt="" class="sticky-cta__sched-photo">
    <span class="sticky-cta__sched-txt"><span class="sticky-cta__sched-label">次回の合同散骨</span><span class="sticky-cta__sched-date"><?= $sc_ts ? date('n/j', $sc_ts) . '（' . h($sc_w) . '）' : '' ?></span></span>
  </a>
<?php else: ?>
  <a href="<?= h(SITE['line_url']) ?>" target="_blank" rel="noopener" class="sticky-cta__line">LINE相談</a>
<?php endif; ?>
</div>

<?php /* フローティング導線（PC専用・常に1つだけ）。旧 sched-badge.php + sd-nudge.js を統合 */ ?>
<?php require __DIR__ . '/floating-cta.php'; ?>
<script src="/assets/js/common.js?v=<?= h(asset_ver()) ?>" defer></script>
<script src="/assets/js/track.js?v=<?= h(asset_ver()) ?>" defer></script>
<?= dev_badge_html() ?>
</body>
</html>
