<?php
require_once __DIR__ . '/../includes/config.php';
$page_title     = 'スタッフ紹介｜' . SITE['name'];
$page_desc      = 'スタッフ紹介｜' . SITE['name'] . '（' . SITE['tagline'] . '）。エンディングトータルアドバイザーが、お客様お一人おひとりのお悩みに寄り添います。';
$page_canonical = SITE['url'] . '/staff/';

// 現行サイト（en1150.co.jp/staff/）の内容を反映
$staff = [
  [
    'name'  => '堤（つつみ）',
    'role'  => '代表',
    'photo' => '/assets/img/staff-tsutsumi.jpg',
    'intro' => '何事も前向きにとらえ、一つひとつの出来事をチャンスと思い、日々取り組んでいます。好きなことは食べることで、歩くかごしまミシュランガイドです（自称）。皆さまお一人おひとりのお悩みにお応えできるよう、ご一緒させていただければと思います。どうぞよろしくお願いします。',
    'motto' => '日進月歩',
    'motto_text' => 'ありがたいことに、たくさんの出会い・感謝すべきご縁をいただいています。この恵まれた環境にこたえるべく日々成長していきたいと思い、“日進月歩”という言葉を心にきざんでいます。',
  ],
  [
    'name'  => '大迫（おおさこ）',
    'role'  => 'エンディングトータルアドバイザー',
    'photo' => '/assets/img/staff-osako.jpg',
    'intro' => 'エンディングトータルアドバイザーの大迫です。長年、葬儀の世界に身を置いていましたが、供養の大切さを学びたいと転身いたしました。葬儀や供養で困ったら、アドバイス・サポートで何かとお役に立てると思います。お客様の心に寄り添い、何でもご相談いただけるよう日々奮闘してまいりますので、よろしくお願いします。',
    'motto' => '',
    'motto_text' => '',
  ],
];

require __DIR__ . '/../includes/head.php';
?>
<body>
<?php require __DIR__ . '/../includes/header.php'; ?>
<section class="page-hero">
  <h1>スタッフ紹介</h1>
  <p>エンディングトータルアドバイザーが、お客様に寄り添います</p>
</section>
<nav class="breadcrumb"><a href="/">ホーム</a> ＞ スタッフ紹介</nav>

<main class="section">
  <div class="container" style="max-width:860px">
    <?php foreach ($staff as $m): ?>
      <div class="card" style="margin-bottom:22px;display:flex;gap:28px;flex-wrap:wrap;align-items:flex-start">
        <?php if (!empty($m['photo'])): ?>
          <img src="<?= h($m['photo']) ?>" alt="<?= h($m['name']) ?>" style="width:220px;max-width:100%;aspect-ratio:4/3;object-fit:cover;object-position:center 28%;border-radius:12px;flex:none;box-shadow:0 8px 22px rgba(18,89,122,.14)">
        <?php endif; ?>
        <div style="flex:1;min-width:260px">
          <p style="font-size:.8rem;color:var(--green);font-weight:600;letter-spacing:.06em"><?= h($m['role']) ?></p>
          <h3 style="font-size:1.3rem;margin:4px 0 12px"><?= h($m['name']) ?></h3>
          <p class="prose" style="line-height:1.95"><?= h($m['intro']) ?></p>
          <?php if ($m['motto']): ?>
            <div style="margin-top:16px;padding:16px 18px;background:var(--sea-light);border-radius:var(--radius)">
              <p style="font-weight:600;color:var(--green-mid)">好きな言葉（座右の銘）　“<?= h($m['motto']) ?>”</p>
              <p style="margin-top:6px;font-size:.92rem;line-height:1.9"><?= h($m['motto_text']) ?></p>
            </div>
          <?php endif; ?>
        </div>
      </div>
    <?php endforeach; ?>

    <div style="text-align:center;margin-top:28px">
      <a href="/contact/" class="btn">お問い合わせ</a>
      <a href="<?= h(SITE['line_url']) ?>" target="_blank" rel="noopener" class="btn btn--outline" style="margin-left:10px">LINEで相談</a>
    </div>
  </div>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>
