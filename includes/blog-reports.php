<?php
/**
 * 海洋散骨レポート（ブログ「海洋葬(海洋散骨)」カテゴリの横スワイプ一覧）— 地域ページ共用パーツ
 *
 * 経緯：もともと /fukuoka/ にだけ置かれていたブロックを v0246 で /kaiyou-sou/fukuoka/ へ移設した。
 *       その結果、海洋散骨の本体ページである /kaiyou-sou/（鹿児島）にレポートが無い状態になったため、
 *       v0249 で共通パーツ化し、鹿児島・福岡の両方から地域を指定して使えるようにした。
 *
 * 地域の出し分け（誤解防止）
 *   鹿児島ページに博多湾の事例、福岡ページに錦江湾の事例が並ぶと実績を誤読させるため、
 *   タイトルの地名だけで判定し、もう一方の地域を明示している記事を除外する。
 *   本文で判定すると「福岡営業所」等の案内文に反応して誤除外が起きるため、本文は見ない。
 *   並び順は日付の新しい順。絞り込みで3件未満になる場合は絞り込みを行わない。
 *
 * 使い方（読み込み前に変数をセット）:
 *   $br_region = 'kagoshima';  // 'kagoshima' | 'fukuoka' | '' （空なら全件）
 *   $br_title  = '鹿児島の海洋散骨レポート';   // 任意
 *   $br_lead   = '…';                          // 任意
 *   require __DIR__ . '/../includes/blog-reports.php';
 *
 * データ取得は news_published()（15分のファイルキャッシュ経由）。Firestore障害時は
 * 古いキャッシュで継続し、それも無ければ空配列＝セクションごと非表示にする（ページは落とさない）。
 */
if (!function_exists('news_published')) {
  require_once __DIR__ . '/../admin/includes/store.php';
}

$br_region = $br_region ?? '';
$br_title  = $br_title  ?? '海洋散骨レポート';
$br_lead   = $br_lead   ?? '実際の海洋散骨の様子を、ブログでご紹介しています。当日の雰囲気づくりの参考にご覧ください。';
$br_limit  = $br_limit  ?? 6;

$BR_WORDS = [
  'kagoshima' => ['鹿児島', '錦江湾', '桜島'],
  'fukuoka'   => ['福岡', '博多', '姪浜'],
];

$br_items = [];
try {
  $cat_alias  = ['海洋葬' => '海洋葬(海洋散骨)', '海洋散骨' => '海洋葬(海洋散骨)'];
  $split_cats = static fn(?string $s): array =>
    array_map(static fn($c) => $cat_alias[$c] ?? $c,
      array_values(array_filter(array_map('trim', preg_split('/[、,\/／]/u', (string)$s)))));

  // 判定はタイトルのみで行う。本文には「福岡営業所」「鹿児島本社」などの案内文が
  // 入るため、本文で判定すると地域と無関係な記事まで除外されてしまう（v0249の不具合）。
  $hit = static function (array $it, array $words): bool {
    $t = (string)($it['title'] ?? '');
    foreach ($words as $w) if (mb_strpos($t, $w) !== false) return true;
    return false;
  };

  $own   = $BR_WORDS[$br_region] ?? [];
  $other = [];
  foreach ($BR_WORDS as $k => $w) if ($k !== $br_region) $other = array_merge($other, $w);

  $all  = [];   // カテゴリ該当の全記事
  $kept = [];   // その地域の記事＋地域を限定していない記事
  foreach (news_published() as $it) {
    if (!in_array('海洋葬(海洋散骨)', $split_cats($it['category'] ?? ''), true)) continue;
    $all[] = $it;
    // タイトルがもう一方の地域だけを指している記事は出さない
    if ($br_region === '' || $hit($it, $own) || !$hit($it, $other)) $kept[] = $it;
  }
  // 地域判定で件数が減りすぎたときは絞り込まない（セクションがスカスカになるのを防ぐ）
  $merged = count($kept) >= 3 ? $kept : $all;
  usort($merged, static fn($a, $b) => strcmp((string)($b['date'] ?? ''), (string)($a['date'] ?? '')));
  $br_items = array_slice($merged, 0, $br_limit);
} catch (Throwable $e) {
  $br_items = [];
}

if ($br_items):
?>
  <section class="section">
    <div class="container" style="max-width:960px">
      <p style="text-align:center;font-size:.78rem;letter-spacing:.28em;color:#b08b3e;font-weight:700;margin-bottom:8px">REPORT</p>
      <h2 style="text-align:center;margin-bottom:10px"><?= h($br_title) ?></h2>
      <p style="text-align:center;color:var(--text-light);font-size:.95rem;margin-bottom:28px"><?= h($br_lead) ?></p>
      <div class="fkr-wrap">
        <button type="button" class="fkr-arrow fkr-arrow--prev" aria-label="前のレポートへ">‹</button>
        <div class="fkr-track" id="fkr-track">
          <?php foreach ($br_items as $it): ?>
          <a class="card fkr-card" href="/blog/?id=<?= h(rawurlencode($it['id'] ?? '')) ?>">
            <?php if (!empty($it['image'])): ?>
              <span style="display:block;aspect-ratio:16/9;overflow:hidden;background:#eef5f8"><img src="<?= h($it['image']) ?>" alt="<?= h($it['title'] ?? '') ?>" loading="lazy" style="width:100%;height:100%;object-fit:cover;display:block" onerror="var t=this.parentNode;if(t)t.remove()"></span>
            <?php endif; ?>
            <span style="display:flex;flex-direction:column;padding:16px 18px;flex:1">
              <p style="font-size:.78rem;color:var(--text-light)"><?= h($it['date'] ?? '') ?> ・ 海洋葬(海洋散骨)</p>
              <h3 style="font-size:.96rem;line-height:1.7"><?= h($it['title'] ?? '') ?></h3>
              <?php if (!empty($it['body'])): ?><p style="font-size:.85rem;flex:1;margin-top:6px"><?= h(mb_strimwidth(preg_replace('/\s+/u', ' ', strip_tags((string)$it['body'])), 0, 68, '…')) ?></p><?php endif; ?>
              <span style="margin-top:10px;align-self:flex-start;color:var(--green);font-weight:600;font-size:.85rem">詳しく読む →</span>
            </span>
          </a>
          <?php endforeach; ?>
        </div>
        <button type="button" class="fkr-arrow fkr-arrow--next" aria-label="次のレポートへ">›</button>
      </div>
      <p class="fkr-hint">← 横にスワイプすると他のレポートもご覧いただけます →</p>
      <style>
        .fkr-wrap{position:relative}
        .fkr-track{display:flex;gap:16px;overflow-x:auto;scroll-snap-type:x mandatory;-webkit-overflow-scrolling:touch;padding:4px 4px 14px;scrollbar-width:none}
        .fkr-track::-webkit-scrollbar{display:none}
        .fkr-card{flex:0 0 300px;display:flex;flex-direction:column;padding:0;overflow:hidden;scroll-snap-align:start}
        .fkr-arrow{position:absolute;top:50%;transform:translateY(-50%);z-index:2;width:40px;height:40px;border-radius:50%;border:1px solid var(--border);background:rgba(255,255,255,.95);color:var(--green-mid);font-size:1.5rem;line-height:1;cursor:pointer;box-shadow:0 4px 14px rgba(40,60,50,.18);display:grid;place-items:center;padding:0 0 3px}
        .fkr-arrow:hover{background:#fff}
        .fkr-arrow--prev{left:-14px}
        .fkr-arrow--next{right:-14px}
        .fkr-arrow[disabled]{opacity:.3;cursor:default}
        .fkr-hint{text-align:center;font-size:.74rem;color:var(--text-light);margin-top:2px}
        @media(max-width:768px){
          .fkr-card{flex:0 0 min(78vw,300px)}
          .fkr-arrow{display:none}
          .fkr-track{padding-bottom:10px}
        }
      </style>
      <script>
        (function () {
          var track = document.getElementById('fkr-track');
          var prev = document.querySelector('.fkr-arrow--prev');
          var next = document.querySelector('.fkr-arrow--next');
          if (!track || !prev || !next) return;
          var step = function () { return (track.querySelector('.fkr-card')?.offsetWidth || 300) + 16; };
          prev.addEventListener('click', function () { track.scrollBy({ left: -step(), behavior: 'smooth' }); });
          next.addEventListener('click', function () { track.scrollBy({ left: step(), behavior: 'smooth' }); });
          var sync = function () {
            prev.disabled = track.scrollLeft <= 4;
            next.disabled = track.scrollLeft >= track.scrollWidth - track.clientWidth - 4;
          };
          track.addEventListener('scroll', sync, { passive: true });
          window.addEventListener('resize', sync);
          sync();
        })();
      </script>
      <p style="text-align:center;margin-top:28px">
        <a href="/blog/?cat=<?= h(rawurlencode('海洋葬(海洋散骨)')) ?>" class="btn">海洋散骨レポート一覧はこちら</a>
      </p>
    </div>
  </section>
<?php endif; ?>
