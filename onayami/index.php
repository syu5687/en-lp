<?php
require_once __DIR__ . '/../includes/config.php';
$page_title     = '供養のお悩み解決｜よくあるお困りごとと解決事例｜' . SITE['name'];
$page_desc      = '「お墓を継ぐ人がいない」「遠方でお参りできない」「散骨後のお参りが心配」——供養のよくあるお悩みと、縁の解決策・実際のお客様の声をご紹介します。ご相談・お見積りは無料です。';
$page_canonical = SITE['url'] . '/onayami/';
$page_hero_image = '/assets/img/svc-soudan.jpg';
require __DIR__ . '/../includes/head.php';

// お悩み → 解決策 → 実際の声（data/voices.json の公開済みの声から引用）
$cases = [
  [
    'cat' => 'お墓の継承', 'title' => 'お墓を継ぐ人がいない。子どもに負担を残したくない',
    'answer' => 'お墓じまい（撤去〜納骨まで基本プラン33万円・税込）で管理の負担をなくし、海洋散骨や樹木葬など「継がなくてよい供養」へ切り替える方が増えています。改葬の行政手続きの代行も承ります。',
    'links' => [['お墓じまい', '/grave/'], ['海洋葬（海洋散骨）', '/kaiyou-sou/'], ['樹木葬', '/teien-sou/']],
    'voice' => ['まだ罪悪感はありますが、すっきりした部分もあります。良かったと思っています。いろんな感情がいったりきたり。感謝の気持ちです。', '鹿児島県 70歳代 女性 A様（委託海洋葬）'],
  ],
  [
    'cat' => '遠方・全国', 'title' => '遠方に住んでいて、お墓参りや手続きに行けない',
    'answer' => 'ご遺骨の郵送・委託海洋葬（立ち会い不要）で、全国どこからでもご利用いただけます。お墓じまいも現地写真の報告付きで、遠方のままお任せいただけます。神奈川・千葉・大阪など全国からご依頼をいただいています。',
    'links' => [['対応エリア（全国郵送）', '/area/'], ['粉骨・洗骨', '/powder-cleaning/']],
    'voice' => ['両親を2人揃って、地元に帰してあげられてほっとしています。天候が一番心配でしたが、スタッフの方の気配りで安心して送れました。', '神奈川県 60歳代 女性 B様（合同海洋葬）'],
  ],
  [
    'cat' => '故人の希望', 'title' => '「海に還りたい」という故人の希望を叶えたい',
    'answer' => '桜島を望む錦江湾を中心に、チャーター・合同・委託の3つの海洋葬プランをご用意しています。日本海洋散骨協会のガイドラインに準拠し、緯度・経度入りの散骨証明書を発行します。',
    'links' => [['海洋葬（海洋散骨）', '/kaiyou-sou/']],
    'voice' => ['担当者様には終始、親切・丁寧に対応していただき、私自身も気持ちの整理がつきました。主人もきっと喜んでいることと思います。', '鹿児島県 60歳代 女性 Y様（合同海洋葬）'],
  ],
  [
    'cat' => 'お参りの場所', 'title' => '散骨したら、手を合わせる場所がなくなるのが不安',
    'answer' => '散骨した海域を再び訪れるメモリアルクルーズ、想いを手紙にして海へ届ける「天国への手紙」（無料）、ご遺骨の一部を身近に置くお手元供養など、散骨後の「心の置きどころ」まで一緒に考えます。',
    'links' => [['お手元供養', '/temoto-kuyou/'], ['海洋葬のプラン', '/kaiyou-sou/']],
    'voice' => ['故人の願いを叶えてあげられ、とてもよかったです。偶然にも我が家から望める場所に散骨。息を引き取った時間に毎日手を合わせて偲び、心穏やかに過ごしています。', '鹿児島県 70歳代 女性 C様（チャーター海洋葬）'],
  ],
  [
    'cat' => 'ご家族の合意', 'title' => '家族の中で意見が分かれていて、決められない',
    'answer' => 'どちらが正しいかを争う必要はありません。反対される方も、故人を大切に思う気持ちは同じです。ご家族ご一緒の無料相談で、それぞれの供養の流れ・良さ・注意点を中立の立場でご説明します。急かすことはありません。',
    'links' => [['無料相談', '/contact/'], ['供養の選び方（かんたん診断）', '/shindan/']],
    'voice' => ['海洋葬・散骨が明るい雰囲気でしたので、気が楽になりました。お世話になり、ありがとうございました。', '福岡県 60歳代 男性 Y様（粉骨・チャーター海洋葬／お墓じまい）'],
  ],
  [
    'cat' => '費用', 'title' => 'できるだけ費用を抑えて、きちんと供養したい',
    'answer' => '委託海洋葬は54,450円、粉骨は24,200円〜。お見積りは無料で、あとから追加料金をいただくことはありません。ご事情に合わせて、無理のない供養のかたちをご提案します。',
    'links' => [['海洋葬の料金プラン', '/kaiyou-sou/'], ['粉骨・洗骨', '/powder-cleaning/']],
    'voice' => null,
  ],
  [
    'cat' => 'ご自身の終活', 'title' => '自分の供養は、元気なうちに自分で決めておきたい',
    'answer' => '海洋散骨の生前契約なら、生前にご希望を契約して託すことで、ご自身の意思に沿った海洋散骨が実現し、ご家族の負担も軽くなります。エンディングノート代わりのご相談も歓迎です。',
    'links' => [['海洋散骨 生前契約', '/seizen/']],
    'voice' => ['ずっと気になっていたことを終えることができ、とてもホッとした気持ちです。ありがとうございました。', '神奈川県 60歳代 女性（墓じまい＋委託海洋葬）'],
  ],
  [
    'cat' => 'ペット', 'title' => 'ペットのご遺骨を、どうしてあげればいいか分からない',
    'answer' => '大切な家族に種類の違いはありません。ペットの粉骨・海洋散骨（錦江湾にて実施）・納骨・手元供養までワンストップで承ります。全国からの郵送にも対応しています。',
    'links' => [['ペット供養', '/pet-kaiyou-sou/']],
    'voice' => null,
  ],
  [
    'cat' => '遺品・仏壇', 'title' => '実家の片付けや、仏壇・遺品の処分に困っている',
    'answer' => '遺品整理から、お仏壇・ご遺品の供養（協力寺院でのお焚き上げ）、特殊清掃まで対応。お墓じまいや供養とまとめてご相談いただけるのが、縁の強みです。',
    'links' => [['遺品整理', '/ihinseiri/'], ['お墓じまい', '/grave/']],
    'voice' => null,
  ],
];
?>
<body>
<?php require __DIR__ . '/../includes/header.php'; ?>
<section class="page-hero">
  <h1>供養のお悩み解決</h1>
  <p>よくあるお困りごとと、縁の解決策・実際の声</p>
</section>
<nav class="breadcrumb"><a href="/">ホーム</a> ＞ 供養のお悩み解決</nav>

<main class="section">
  <div class="container" style="max-width:860px">
    <p class="lead" style="text-align:center;margin-bottom:14px">供養のお悩みは、人それぞれ。でも、悩みの多くには先に同じ道を通った方がいます。</p>
    <p style="text-align:center;color:var(--text-light);font-size:.95rem;margin-bottom:36px">よくあるお悩みと縁の解決策、そして実際にご利用いただいた方の声をご紹介します。<br>
    <a href="/shindan/" style="color:var(--green);font-weight:700;text-decoration:underline">迷ったら「供養の選び方」かんたん診断もどうぞ →</a></p>

    <?php foreach ($cases as $c): ?>
      <article class="onayami-card">
        <p class="onayami-cat"><?= h($c['cat']) ?></p>
        <h2 class="onayami-q">「<?= h($c['title']) ?>」</h2>
        <div class="onayami-a">
          <p class="onayami-a-label">縁の解決策</p>
          <p><?= h($c['answer']) ?></p>
          <p class="onayami-links">
            <?php foreach ($c['links'] as $l): ?>
              <a href="<?= h($l[1]) ?>"><?= h($l[0]) ?> →</a>
            <?php endforeach; ?>
          </p>
        </div>
        <?php if (!empty($c['voice'])): ?>
          <blockquote class="onayami-voice">
            <p>「<?= h($c['voice'][0]) ?>」</p>
            <cite>—— <?= h($c['voice'][1]) ?></cite>
          </blockquote>
        <?php endif; ?>
      </article>
    <?php endforeach; ?>

    <p style="text-align:center;margin-top:10px"><a href="/voice/" class="btn btn--outline">お客様の声をもっと見る</a></p>

    <div style="text-align:center;margin-top:40px;background:var(--cream);border-radius:14px;padding:30px 20px">
      <h2 style="margin-bottom:10px">当てはまるお悩みが無くても、大丈夫です</h2>
      <p style="color:var(--text-light);margin-bottom:20px">状況を伺いながら、一緒に考えます。「話を聞くだけ」でもお気軽にどうぞ。</p>
      <a href="/contact/" class="btn">無料で相談する</a>
      <p style="margin-top:14px;font-size:.9rem;color:var(--text-light)">
        お電話でも承ります：<a href="tel:<?= h(SITE['tel']) ?>" style="color:var(--green);font-weight:700"><?= h(SITE['tel']) ?></a>（<?= h(SITE['hours_jp']) ?>）
      </p>
    </div>
  </div>
</main>

<style>
.onayami-card{background:var(--white);border:1px solid var(--border);border-radius:14px;padding:26px 26px 22px;margin-bottom:22px}
.onayami-cat{display:inline-block;background:var(--sea-light);color:var(--green);font-size:.72rem;font-weight:700;padding:3px 12px;border-radius:999px;margin-bottom:10px}
.onayami-q{font-size:1.15rem;line-height:1.7;margin-bottom:14px}
.onayami-a{background:var(--cream);border-radius:10px;padding:16px 18px}
.onayami-a-label{font-size:.75rem;font-weight:700;color:var(--green);letter-spacing:.12em;margin-bottom:6px}
.onayami-a p{line-height:1.95;font-size:.95rem}
.onayami-links{margin-top:10px;display:flex;flex-wrap:wrap;gap:8px 16px}
.onayami-links a{color:var(--green);font-weight:700;font-size:.9rem;text-decoration:underline}
.onayami-voice{margin:16px 0 0;padding:14px 18px;border-left:4px solid var(--green);background:#fbfaf6;border-radius:0 10px 10px 0}
.onayami-voice p{font-size:.92rem;line-height:1.9;color:var(--text)}
.onayami-voice cite{display:block;margin-top:8px;font-style:normal;font-size:.8rem;color:var(--text-light)}
</style>

<script type="application/ld+json">
{
  "@context":"https://schema.org",
  "@type":"BreadcrumbList",
  "itemListElement":[
    {"@type":"ListItem","position":1,"name":"ホーム","item":"https://en1150.co.jp/"},
    {"@type":"ListItem","position":2,"name":"供養のお悩み解決","item":"https://en1150.co.jp/onayami/"}
  ]
}
</script>
<script type="application/ld+json">
<?= json_encode([
  '@context' => 'https://schema.org',
  '@type' => 'FAQPage',
  'mainEntity' => array_map(fn($c) => [
    '@type' => 'Question',
    'name' => $c['title'],
    'acceptedAnswer' => ['@type' => 'Answer', 'text' => $c['answer']],
  ], $cases),
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>
</script>
<?php require __DIR__ . '/../includes/footer.php'; ?>
