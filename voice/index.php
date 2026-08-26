<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../admin/includes/store.php'; // voices_published()
$page_title     = 'お客様の声｜' . SITE['name'];
$page_desc      = '全国のお客様からいただいた海洋葬・お墓じまい・お手元供養などのご感想をご紹介します。' . SITE['name'] . '。';
$page_canonical = SITE['url'] . '/voice/';
$page_hero_image = '/assets/img/hero-voice.jpg';

// 管理画面（Firestore）から公開分を取得。未接続・未移行時は data/voices.json をフォールバック表示。
$voices = [];
try { $voices = voices_published(); } catch (Throwable $e) { $voices = []; }
if (!$voices) {
  $seed = @json_decode((string)@file_get_contents(__DIR__ . '/../data/voices.json'), true);
  foreach (($seed['items'] ?? []) as $v) {
    if (!empty($v['published'])) $voices[] = $v;
  }
  usort($voices, fn($a, $b) => strcmp($b['date'] ?? '', $a['date'] ?? ''));
}

require __DIR__ . '/../includes/head.php';
?>
<body>
<?php require __DIR__ . '/../includes/header.php'; ?>
<section class="page-hero">
  <h1>お客様の声</h1>
  <p>ご利用者様からいただいたご感想をご紹介します</p>
</section>
<nav class="breadcrumb"><a href="/">ホーム</a> ＞ お客様の声</nav>

<main class="section">
  <div class="container" style="max-width:820px;margin-bottom:34px">
    <div style="background:var(--white);border:1px solid var(--border);border-radius:14px;padding:24px 26px;display:flex;flex-wrap:wrap;align-items:center;gap:18px;justify-content:space-between">
      <div>
        <p style="font-weight:700;margin-bottom:4px">Googleの口コミでも高い評価をいただいています</p>
        <p style="font-size:1.4rem;font-weight:700;color:#f4b400">★ 4.9 <span style="font-size:.85rem;color:var(--text-light);font-weight:400">（Googleビジネスプロフィール）</span></p>
      </div>
      <div style="display:flex;gap:10px;flex-wrap:wrap">
        <a href="https://maps.google.com/?cid=2494401172745547436" target="_blank" rel="noopener" class="btn btn--outline" style="font-size:.9rem">Googleで口コミを見る</a>
        <a href="https://g.page/r/Cazu0JSm5J0iEBM/review" target="_blank" rel="noopener" class="btn" style="font-size:.9rem">口コミを書く</a>
      </div>
    </div>
  </div>
  <div class="container" style="max-width:900px">
    <div class="card-grid">
      <?php foreach ($voices as $v): ?>
        <div class="card">
          <p style="display:inline-block;background:var(--sea-light);color:var(--green);font-size:.72rem;font-weight:600;padding:3px 10px;border-radius:999px"><?= h($v['service']) ?></p>
          <h3 style="margin:12px 0 10px;line-height:1.6">「<?= h($v['title']) ?>」</h3>
          <p style="font-size:.85rem;color:var(--text-light);margin-bottom:8px"><strong>ご依頼のきっかけ：</strong><?= h($v['reason']) ?></p>
          <p style="font-size:.9rem;line-height:1.9"><?= h($v['impression']) ?></p>
          <p style="text-align:right;font-size:.8rem;color:var(--text-light);margin-top:12px">（<?= h($v['who']) ?>）</p>
        </div>
      <?php endforeach; ?>
    </div>

    <?php
      // 直筆アンケート（旧サイトから引き継いだスキャン原本）
      $voice_scans = [];
      for ($i = 1; $i <= 7; $i++) {
        $f = sprintf('/assets/img/voice-scan-%02d.jpg', $i);
        if (is_file(__DIR__ . '/..' . $f)) $voice_scans[] = $f;
      }
    ?>
    <?php if ($voice_scans): ?>
      <section style="margin-top:56px">
        <h2 style="text-align:center;font-family:var(--serif);color:var(--green-mid);font-size:1.35rem">お客様直筆のアンケート</h2>
        <p style="text-align:center;font-size:.9rem;color:var(--text-light);margin:10px 0 26px">実際にお客様からお寄せいただいたアンケート用紙です（クリックで拡大できます）</p>
        <div class="voice-scans">
          <?php foreach ($voice_scans as $n => $s): ?>
            <button type="button" class="voice-scan" data-img="<?= h($s) ?>?v=<?= h(asset_ver()) ?>" aria-label="アンケート<?= $n + 1 ?>を拡大表示">
              <img src="<?= h($s) ?>?v=<?= h(asset_ver()) ?>" alt="お客様直筆アンケート <?= $n + 1 ?>" loading="lazy">
              <span class="voice-scan__zoom">🔍 拡大</span>
            </button>
          <?php endforeach; ?>
        </div>
      </section>

      <div id="voice-lightbox" hidden>
        <img src="" alt="アンケート拡大表示">
        <span id="voice-lightbox-close" aria-label="閉じる">×</span>
      </div>
      <style>
        .voice-scans{display:grid;grid-template-columns:repeat(auto-fill,minmax(170px,1fr));gap:14px}
        .voice-scan{position:relative;display:block;padding:0;border:1px solid var(--border);border-radius:12px;overflow:hidden;background:#fff;cursor:zoom-in;box-shadow:var(--shadow);transition:.25s;font-family:inherit}
        .voice-scan:hover{transform:translateY(-3px);box-shadow:var(--shadow-hover)}
        .voice-scan img{width:100%;aspect-ratio:3/4.2;object-fit:cover;object-position:top;display:block}
        .voice-scan__zoom{position:absolute;right:8px;bottom:8px;background:rgba(21,112,158,.85);color:#fff;font-size:.68rem;font-weight:600;padding:3px 9px;border-radius:999px;pointer-events:none}
        #voice-lightbox{position:fixed;inset:0;z-index:9999;background:rgba(20,40,50,.86);display:flex;align-items:center;justify-content:center;padding:24px;cursor:zoom-out}
        #voice-lightbox[hidden]{display:none}
        #voice-lightbox img{max-width:min(92vw,760px);max-height:92vh;border-radius:8px;box-shadow:0 20px 60px rgba(0,0,0,.5);background:#fff}
        #voice-lightbox-close{position:fixed;top:14px;right:20px;color:#fff;font-size:2rem;line-height:1;cursor:pointer;opacity:.85}
      </style>
      <script>
        (function () {
          var lb = document.getElementById('voice-lightbox');
          var im = lb.querySelector('img');
          document.querySelectorAll('.voice-scan').forEach(function (b) {
            b.addEventListener('click', function () { im.src = b.dataset.img; lb.hidden = false; document.body.style.overflow = 'hidden'; });
          });
          lb.addEventListener('click', function () { lb.hidden = true; im.src = ''; document.body.style.overflow = ''; });
          document.addEventListener('keydown', function (e) { if (e.key === 'Escape' && !lb.hidden) lb.click(); });
        })();
      </script>
    <?php endif; ?>

    <div style="text-align:center;margin-top:36px">
      <p style="margin-bottom:16px">ご相談・お見積りは無料です。お気軽にお問い合わせください。</p>
      <a href="/contact/" class="btn">お問い合わせ</a>
      <a href="<?= h(SITE['line_url']) ?>" target="_blank" rel="noopener" class="btn btn--outline" style="margin-left:10px">LINEで相談</a>
    </div>
  </div>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>
