<?php
/**
 * 墓じまい 福岡 専用ページ（/grave/fukuoka/）
 * 役割分担：/grave/=鹿児島の墓じまい特化LP／/fukuoka/=福岡営業所の総合ページ（散骨中心）／
 *           本ページ=「墓じまい 福岡」検索の受け皿。33万円プラン・手続き・遺骨の行き先は共通、
 *           エリア・霊園事情・博多湾散骨・拠点情報は福岡独自。
 */
require_once __DIR__ . '/../../includes/config.php';

$page_title     = '墓じまい 福岡｜撤去〜納骨まで一括 基本プラン33万円（税込）｜縁（えん）福岡営業所';
$page_desc      = '福岡の墓じまい（お墓じまい）は撤去から納骨まで一括対応、基本プラン33万円（税込）。福岡市・北九州・筑後など県内全域対応。改葬手続きサポート、取り出したご遺骨は粉骨〜博多湾での海洋散骨・手元供養までワンストップ。福岡営業所（中央区春吉）。無料相談・LINE受付中。';
$page_canonical = SITE['url'] . '/grave/fukuoka/';
$page_hero_image = '/assets/img/plan-goudou.jpg';
$page_updated    = '2026-11-03'; // 最終更新日（v0272で追加。表示と構造化データ dateModified に使う）
require __DIR__ . '/../../includes/head.php';

$fk = SITE['fukuoka'];
$fk_tel_link = str_replace('-', '', $fk['tel']);
?>
<body>
<?php require __DIR__ . '/../../includes/header.php'; ?>

<section class="page-hero">
  <h1>福岡の墓じまい、撤去から納骨まで一括対応</h1>
  <p>基本プラン <strong style="font-size:1.3em">33万円</strong>（税込）｜福岡市・北九州・筑後 県内全域</p>
  <p style="margin-top:10px;font-size:.92rem">現地確認とお見積もりで総額を確定。確定後の追加請求はありません</p>
  <p style="margin-top:12px;display:flex;flex-wrap:wrap;gap:8px;justify-content:center">
    <span style="display:inline-block;background:rgba(255,255,255,.18);padding:5px 16px;border-radius:999px;font-size:.82rem;font-weight:700">創業20年以上</span>
    <span style="display:inline-block;background:rgba(255,255,255,.18);padding:5px 16px;border-radius:999px;font-size:.82rem;font-weight:700">鹿児島・福岡で実績多数</span>
    <span style="display:inline-block;background:rgba(255,255,255,.18);padding:5px 16px;border-radius:999px;font-size:.82rem;font-weight:700">Google口コミ ★4.9</span>
  </p>
</section>
<nav class="breadcrumb"><a href="/">ホーム</a> ＞ <a href="/grave/">お墓じまい</a> ＞ 福岡の墓じまい</nav>
<div class="container" style="max-width:820px">
  <p style="font-size:.82rem;color:var(--text-light);margin:10px 0 0;text-align:right">最終更新：2026年11月3日</p>
</div>

<main>
  <!-- 導入・定義（GEO引用用） -->
  <section class="section">
    <div class="container" style="max-width:820px">
      <div class="prose" style="max-width:780px;margin:0 auto">
        <p class="lead"><strong>墓じまい（お墓じまい）</strong>とは、今あるお墓を撤去して更地に戻し、取り出したご遺骨を新しい供養先へ移すことです。有限会社 縁は、福岡営業所（福岡市中央区春吉）を拠点に、<strong>見積もり取得から墓石撤去・ご遺骨の引き出し・新しい納骨先へのご納骨までを基本プラン33万円（税込）で一括対応</strong>しています。取り出したご遺骨は、粉骨のうえ博多湾での海洋散骨や手元供養まで、同じ窓口でお手伝いできます。</p>
      </div>
    </div>
  </section>

  <!-- こんなお悩み（福岡文脈） -->
  <section class="section" style="background:var(--white)">
    <div class="container" style="max-width:820px">
      <h2>福岡で、こんなお悩みが増えています</h2>

      <ul style="list-style:none;display:grid;gap:12px;margin-top:20px">
        <li style="padding:14px 18px;background:var(--cream);border-radius:10px;border-left:4px solid var(--green)">実家のお墓が福岡にあるが、自分は転勤などで県外に住んでいて管理できない</li>
        <li style="padding:14px 18px;background:var(--cream);border-radius:10px;border-left:4px solid var(--green)">市営霊園・寺院墓地の管理料を払い続けているが、継ぐ人がいない</li>
        <li style="padding:14px 18px;background:var(--cream);border-radius:10px;border-left:4px solid var(--green)">親のお墓を整理したいが、費用と手続きが分からず止まっている</li>
        <li style="padding:14px 18px;background:var(--cream);border-radius:10px;border-left:4px solid var(--green)">お墓を閉じたあと、ご遺骨をどこへ移せばいいのか決められない</li>
      </ul>
    </div>
  </section>

  <!-- 33万円プラン（共通コンテンツ） -->
  <section class="section">
    <div class="container" style="max-width:820px">
      <h2 style="text-align:center">基本プラン33万円（税込）に含まれる内容</h2>
      <p style="text-align:center;color:var(--text-light);font-size:.92rem;margin:6px 0 22px">何が入っているのか、明確に。</p>
      <div class="card" style="max-width:640px;margin:0 auto">
        <ul style="list-style:none;display:grid;gap:10px">
          <?php foreach ([
            '墓石撤去工事の見積もり取得・段取り',
            '工事の手配・立ち会い（立ち会いの要不要は選択可）',
            '工事前後写真の撮影・報告',
            'ご遺骨の引き出し',
            '新しい納骨先へのご納骨（郵送／お引き取り）',
          ] as $li): ?>
          <li style="display:flex;gap:10px;align-items:flex-start"><span style="color:var(--green);font-weight:700">✓</span><span><?= h($li) ?></span></li>
          <?php endforeach; ?>
        </ul>
        <div style="margin-top:16px;padding-top:14px;border-top:1px solid var(--border);font-size:.9rem">
          <p style="font-weight:700;margin-bottom:6px">▼ 別オプション</p>
          <div style="display:flex;gap:14px;align-items:flex-start">
            <p style="flex:1;min-width:0">改葬許可申請（役所手続き）サポート ¥25,000／最下層を含む場合 ¥35,000<br><span style="font-size:.82rem;color:var(--text-light)">改葬許可申請書（写真は福岡市の様式例）の取り寄せ・記入のご案内までお手伝いします。</span></p>
            <img src="/grave/fukuoka/images/gf-kaiso-form.jpg?v=<?= asset_ver() ?>" alt="改葬許可申請書（福岡市の様式例）" width="722" height="1044" loading="lazy" style="width:86px;height:auto;flex-shrink:0;border:1px solid var(--border);border-radius:6px;background:#fff">
          </div>
        </div>
        <p style="margin-top:14px;font-size:.85rem;color:var(--text-light);line-height:1.9">特殊運搬など現場の条件で必要になる費用は、<strong>現地調査の段階ですべてお見積もりに含めてご提示</strong>します。総額にご納得いただいてからのご契約となり、<strong>確定したお見積もり後に追加請求することはありません</strong>。</p>
      </div>
    </div>
  </section>

  <!-- 施工事例 Before/After -->
  <section class="section" style="background:var(--white)">
    <div class="container" style="max-width:860px">
      <h2 style="text-align:center;margin-bottom:8px">撤去工事の実例（Before → After）</h2>
      <p style="text-align:center;color:var(--text-light);font-size:.92rem;margin-bottom:24px">工事の前後は写真でご報告します。区画は更地に戻し、管理者へお返しするまで対応します。</p>
      <div style="display:grid;gap:18px">
        <?php foreach ([
          ['gf-case1', '墓石の撤去・更地化'],
          ['gf-case2', '外柵を含む解体・整地'],
          ['gf-case3', '墓石撤去と区画の返還整備'],
          ['gf-case4', 'ご遺骨の取り出し'],
        ] as [$gc_img, $gc_t]): ?>
        <div class="card" style="padding:16px">
          <p style="font-weight:700;color:var(--green-mid);margin-bottom:10px"><?= h($gc_t) ?></p>
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
            <figure style="margin:0;position:relative">
              <img src="/grave/fukuoka/images/<?= $gc_img ?>-before.jpg?v=<?= asset_ver() ?>" alt="<?= h($gc_t) ?>：施工前" loading="lazy" style="width:100%;height:210px;object-fit:cover;border-radius:8px;display:block">
              <figcaption style="position:absolute;top:8px;left:8px;background:rgba(35,50,58,.78);color:#fff;font-size:.75rem;font-weight:700;padding:3px 10px;border-radius:999px">Before</figcaption>
            </figure>
            <figure style="margin:0;position:relative">
              <img src="/grave/fukuoka/images/<?= $gc_img ?>-after.jpg?v=<?= asset_ver() ?>" alt="<?= h($gc_t) ?>：施工後" loading="lazy" style="width:100%;height:210px;object-fit:cover;border-radius:8px;display:block">
              <figcaption style="position:absolute;top:8px;left:8px;background:var(--green);color:#fff;font-size:.75rem;font-weight:700;padding:3px 10px;border-radius:999px">After</figcaption>
            </figure>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <p style="text-align:center;margin-top:14px;font-size:.82rem;color:var(--text-light)">※ 写真はいずれも当社施工の実例です。</p>
      <p style="text-align:center;margin-top:10px;font-size:.9rem"><a href="/grave/" style="color:var(--green);font-weight:600">費用が変わる条件など、さらに詳しくはお墓じまい詳細ページで →</a></p>
    </div>
  </section>

  <!-- 依頼先の選び方（比較意図の受け皿・v0272） -->
  <section class="section" style="background:var(--cream)">
    <div class="container" style="max-width:880px">
      <h2 style="text-align:center;margin-bottom:8px">福岡で墓じまいの依頼先を選ぶときに見ておきたいこと</h2>
      <p style="text-align:center;color:var(--text-light);font-size:.92rem;margin-bottom:22px">墓じまいは、ほとんどの方が一度しか経験しません。「どこに頼めばいいのか分からない」というご相談をよくいただきます。</p>

      <div class="prose" style="max-width:800px;margin:0 auto">
        <p>福岡で墓じまいを依頼できる先は、大きく4つのタイプに分かれます。どれが良い・悪いではなく、<strong>得意な範囲が違います</strong>。ご自身の状況に近いタイプを選ぶことが、やり直しを避ける近道です。</p>
      </div>

      <div style="overflow-x:auto;margin-top:22px;-webkit-overflow-scrolling:touch">
        <table style="width:100%;border-collapse:collapse;font-size:.9rem;line-height:1.85;min-width:620px;background:var(--white);border-radius:10px;overflow:hidden">
          <thead>
            <tr style="background:var(--green-mid);color:#fff">
              <th style="padding:11px 13px;text-align:left;white-space:nowrap;font-size:.88rem">依頼先のタイプ</th>
              <th style="padding:11px 13px;text-align:left;font-size:.88rem">得意なこと</th>
              <th style="padding:11px 13px;text-align:left;font-size:.88rem">確認しておきたい点</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td style="padding:13px;border-bottom:1px solid var(--border);font-weight:700;vertical-align:top">石材店・墓石工事店</td>
              <td style="padding:13px;border-bottom:1px solid var(--border);vertical-align:top">墓石の撤去工事そのもの。地元の霊園の決まりや区画の事情に慣れています。</td>
              <td style="padding:13px;border-bottom:1px solid var(--border);vertical-align:top">改葬の手続きと、取り出したご遺骨の供養先は、ご自身で探すことになる場合があります。</td>
            </tr>
            <tr>
              <td style="padding:13px;border-bottom:1px solid var(--border);font-weight:700;vertical-align:top">一括見積もり・紹介サイト</td>
              <td style="padding:13px;border-bottom:1px solid var(--border);vertical-align:top">複数社の金額を一度に集められます。相場感をつかむのに向いています。</td>
              <td style="padding:13px;border-bottom:1px solid var(--border);vertical-align:top">実際に工事をするのは紹介された別の会社です。窓口と施工者が分かれるぶん、話の行き違いが起きやすくなります。</td>
            </tr>
            <tr>
              <td style="padding:13px;border-bottom:1px solid var(--border);font-weight:700;vertical-align:top">葬儀社</td>
              <td style="padding:13px;border-bottom:1px solid var(--border);vertical-align:top">閉眼供養・法要の手配や、お寺とのやり取りに慣れています。</td>
              <td style="padding:13px;border-bottom:1px solid var(--border);vertical-align:top">墓石の撤去は外注になることが多く、粉骨や散骨は別の会社への手配になる場合があります。</td>
            </tr>
            <tr>
              <td style="padding:13px;font-weight:700;vertical-align:top;background:rgba(21,112,158,.05)">撤去から供養先まで<br>一貫して受ける会社</td>
              <td style="padding:13px;vertical-align:top;background:rgba(21,112,158,.05)">撤去工事・改葬手続きのサポート・粉骨・納骨や散骨までを、一つの窓口で進められます。</td>
              <td style="padding:13px;vertical-align:top;background:rgba(21,112,158,.05)">会社によって「どこまで含むか」が大きく違います。見積書の範囲を必ず文字で確認してください。</td>
            </tr>
          </tbody>
        </table>
      </div>

      <h3 style="margin-top:36px;font-size:1.1rem;color:var(--green-mid)">金額以外に、見ておきたい5つのこと</h3>
      <ul style="list-style:none;padding:0;margin-top:16px;display:grid;gap:14px">
        <li style="padding:16px 18px;background:var(--white);border-radius:10px;border-left:4px solid var(--green)">
          <p style="font-weight:700;color:var(--green-mid);margin-bottom:6px">1. 見積もりが「総額」で出ているか</p>
          <p style="font-size:.92rem;line-height:1.9">墓じまいの費用は撤去工事費だけではありません。離檀の際のお布施、改葬許可の取得、ご遺骨の運搬、新しい供養先の費用まで足して、はじめて総額になります。工事費だけの金額を総額と思って契約すると、あとから積み上がります。<strong>現地を見ないまま出てきた金額は、見積もりではなく目安です。</strong></p>
          <p style="font-size:.88rem;margin-top:9px"><a href="/blog/?id=fukuoka-hakajimai-33man-naiyaku" style="color:var(--green);font-weight:600">基本プラン33万円に含まれるもの・含まれないもの →</a></p>
        </li>
        <li style="padding:16px 18px;background:var(--white);border-radius:10px;border-left:4px solid var(--green)">
          <p style="font-weight:700;color:var(--green-mid);margin-bottom:6px">2. ご遺骨の行き先まで相談できるか</p>
          <p style="font-size:.92rem;line-height:1.9">墓じまいは、お墓を撤去した時点では終わりません。ご遺骨の行き先が決まっていないと、工事だけ終わって手元に骨壷が残ります。霊園・納骨堂・寺院・散骨・手元供養のどれが合うかを、同じ窓口で相談できるかどうかで、その後の手間が変わります。</p>
          <p style="font-size:.88rem;margin-top:9px"><a href="/blog/?id=fukuoka-ikotsu-ikisaki-hikaku" style="color:var(--green);font-weight:600">福岡で遺骨の移転先を選ぶ。霊園・納骨堂・寺院・散骨の比べ方 →</a></p>
        </li>
        <li style="padding:16px 18px;background:var(--white);border-radius:10px;border-left:4px solid var(--green)">
          <p style="font-weight:700;color:var(--green-mid);margin-bottom:6px">3. 工事の前後を写真で報告してくれるか</p>
          <p style="font-size:.92rem;line-height:1.9">県外にお住まいで立ち会えない場合、更地になったことを確かめる手段は写真だけです。着手前・撤去後の報告があるかどうかを、契約前に確認してください。</p>
          <p style="font-size:.88rem;margin-top:9px"><a href="/blog/?id=fukuoka-genchi-tachiai-nashi" style="color:var(--green);font-weight:600">現地確認に立ち会えない。写真だけで進められますか →</a></p>
        </li>
        <li style="padding:16px 18px;background:var(--white);border-radius:10px;border-left:4px solid var(--green)">
          <p style="font-weight:700;color:var(--green-mid);margin-bottom:6px">4. 霊園・お寺への手続きを代わりに進めてくれるか</p>
          <p style="font-size:.92rem;line-height:1.9">市営霊園の返還条件、寺院墓地での閉眼供養や離檀の伝え方は、管理者ごとに違います。この部分を「ご自身で」と言われるかどうかは、かかる時間と気疲れに直結します。</p>
          <p style="font-size:.88rem;margin-top:9px"><a href="/blog/?id=fukuoka-shiei-bochi-henkan" style="color:var(--green);font-weight:600">福岡市の市営墓地を返還したい。手続きの順番と窓口 →</a>　｜　<a href="/blog/?id=fukuoka-kaiso-kyoka-doko" style="color:var(--green);font-weight:600">福岡で改葬許可はどこに出す？ →</a></p>
        </li>
        <li style="padding:16px 18px;background:var(--white);border-radius:10px;border-left:4px solid var(--green)">
          <p style="font-weight:700;color:var(--green-mid);margin-bottom:6px">5. 相談した人が、最後まで窓口でいてくれるか</p>
          <p style="font-size:.92rem;line-height:1.9">相談した相手と工事をする会社が違うと、伝えたはずの事情が現場に届いていないことがあります。誰が最後まで担当するのかを、最初に聞いておくと安心です。</p>
          <p style="font-size:.88rem;margin-top:9px"><a href="/blog/?id=ishizaiten-ikkatsu-chigai" style="color:var(--green);font-weight:600">石材店に頼むか、一括で頼むか。福岡での違いと使い分け →</a></p>
        </li>
      </ul>

      <div class="prose" style="max-width:800px;margin:28px auto 0">
        <p>縁（えん）は、4番目の「撤去から供養先まで一貫して受ける会社」にあたります。福岡営業所を拠点に、現地調査から撤去工事・改葬手続きのサポート・ご遺骨の粉骨・博多湾での海洋散骨や納骨先へのご納骨・手元供養までを、同じ窓口でお受けしています。基本プラン33万円（税込）に含まれる範囲は、このページの「基本プラン33万円（税込）に含まれる内容」でご確認いただけます。現地調査で総額を確定したあとの追加請求はありません。</p>
      </div>

      <p style="text-align:center;margin-top:18px;font-size:.9rem"><a href="/blog/?id=fukuoka-hakajimai-shippai" style="color:var(--green);font-weight:600">福岡の墓じまいでよくある失敗と、避け方 →</a></p>
    </div>
  </section>

  <!-- 福岡独自：地域事情と対応エリア -->
  <section class="section">
    <div class="container" style="max-width:860px">
      <h2 style="text-align:center;margin-bottom:8px">福岡の墓じまい事情と対応エリア</h2>
      <p style="text-align:center;color:var(--text-light);font-size:.92rem;margin-bottom:24px">都市部ならではの事情に、地元の営業所が対応します。</p>
      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:16px">
        <div class="card">
          <h3 style="color:var(--green-mid);font-size:1rem;margin-bottom:8px">市営霊園・寺院墓地の返還</h3>
          <p style="font-size:.9rem;line-height:1.9">平尾霊園・三日月山霊園・西部霊園などの市営霊園は、区画を更地に戻して返還する決まりがあります。返還条件の確認から更地工事、管理者への手続きまで段取りします。寺院墓地の場合の閉眼供養・離檀のご相談も承ります。</p>
        </div>
        <div class="card">
          <h3 style="color:var(--green-mid);font-size:1rem;margin-bottom:8px">重機が入りにくい都市部の区画</h3>
          <p style="font-size:.9rem;line-height:1.9">福岡市内には通路が狭く重機を近づけられない墓地も多くあります。人力での解体・運搬が必要な場合も、現地調査のうえ工事費を含めた総額を先に確定してからご契約いただくので安心です。</p>
        </div>
        <div class="card">
          <h3 style="color:var(--green-mid);font-size:1rem;margin-bottom:8px">対応エリア</h3>
          <p style="font-size:.9rem;line-height:1.9"><strong>福岡市内全域</strong>（東・博多・中央・南・城南・早良・西区）、<strong>北九州エリア</strong>（北九州市・行橋など）、<strong>筑後エリア</strong>（久留米・大牟田・柳川など）、筑豊エリア（飯塚・田川など）——福岡県内全域にうかがいます。隣県（佐賀・熊本・大分）もご相談ください。</p>
        </div>
      </div>
    </div>
  </section>

  <!-- 流れ（共通・簡略版） -->
  <section class="section" style="background:var(--white)">
    <div class="container" style="max-width:760px">
      <h2 style="text-align:center;margin-bottom:24px">ご相談から納骨までの流れ</h2>
      <ol style="list-style:none;display:grid;gap:12px;counter-reset:st">
        <?php foreach ([
          ['無料相談（電話・LINE・メール）', '「話を聞くだけ」でも歓迎です。県外からのご相談も電話・LINEで完結します。'],
          ['現地調査・お見積もり', '福岡営業所のスタッフが現地を確認し、総額を確定してご提示します。'],
          ['閉眼供養のご相談（希望者のみ）', '寺院のご紹介も可能です。お布施の目安もご案内します。'],
          ['墓石撤去工事・写真報告', '工事前後を写真でご報告。立ち会い不要でも進められます。'],
          ['ご遺骨の引き出し・粉骨', 'ご希望に応じて洗骨・粉骨し、六価クロム検査・無害化も標準実施します。'],
          ['新しい供養先へ', '博多湾での海洋散骨、納骨先へのご納骨、手元供養など、ご希望の形へ。'],
        ] as $i => [$t, $d]): ?>
        <li style="display:grid;grid-template-columns:40px 1fr;gap:14px;background:#fff;border:1px solid var(--border);border-radius:12px;padding:14px 18px;align-items:center">
          <span style="width:36px;height:36px;border-radius:50%;background:var(--green);color:#fff;display:grid;place-items:center;font-weight:700"><?= $i + 1 ?></span>
          <span><strong style="color:var(--green-mid)"><?= h($t) ?></strong><br><span style="font-size:.88rem;color:var(--text-light)"><?= h($d) ?></span></span>
        </li>
        <?php endforeach; ?>
      </ol>
    </div>
  </section>

  <!-- 墓じまい後の遺骨導線＋博多湾クロスセル -->
  <section class="section" style="background:var(--cream)">
    <div class="container" style="max-width:860px">
      <h2 style="text-align:center;margin-bottom:8px">墓じまい後のご遺骨には、いくつかの行き先があります</h2>
      <p style="text-align:center;max-width:680px;margin:0 auto 24px;line-height:2;font-size:.94rem">どれか一つに今決める必要はありません。<strong>「大部分を散骨して、少しだけ手元に残す」という組み合わせ</strong>もできます。</p>
      <?php /* v0257：行き先を4つに増やしたため minmax を 320px にしてPCは2×2。SPは従来どおり1列。 */ ?>
      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(320px,1fr));gap:14px">
        <div class="card">
          <h3 style="font-size:.98rem;color:var(--green-mid);margin-bottom:6px">博多湾での海洋散骨</h3>
          <p style="font-size:.88rem;line-height:1.85">姪浜から出港する合同海洋葬（148,500円〜）のほか、おまかせの委託散骨（54,450円〜）にも対応。福岡の海に還すご供養です。</p>
          <p style="margin-top:10px"><a href="/kaiyou-sou/fukuoka/" style="color:var(--green);font-weight:700;font-size:.9rem">福岡の海洋散骨について見る →</a></p>
        </div>
        <div class="card">
          <h3 style="font-size:.98rem;color:var(--green-mid);margin-bottom:6px">一部を手元供養・ジュエリーに</h3>
          <p style="font-size:.88rem;line-height:1.85">手のひらサイズのミニ骨壷や、お米一粒ほどのご遺骨を納めるリング・ペンダント。手を合わせる場所を残したい方に。</p>
          <p style="margin-top:10px"><a href="/temoto-kuyou/" style="color:var(--green);font-weight:700;font-size:.9rem">お手元供養を見る →</a></p>
        </div>
        <div class="card">
          <h3 style="font-size:.98rem;color:var(--green-mid);margin-bottom:6px">納骨先へのご納骨・樹木葬</h3>
          <p style="font-size:.88rem;line-height:1.85">永代供養墓・納骨堂・樹木葬など、お参りできる場所を残す選択肢。受け入れ先探しからご相談いただけます。</p>
          <p style="margin-top:10px"><a href="/teien-sou/" style="color:var(--green);font-weight:700;font-size:.9rem">樹木葬を見る →</a></p>
        </div>
        <div class="card">
          <h3 style="font-size:.98rem;color:var(--green-mid);margin-bottom:6px">一部を寺院・神社に納めて、残りを散骨</h3>
          <p style="font-size:.88rem;line-height:1.85">提携する寺院・神社の合祀墓へ一部を納め、残りを海へお還しします。年間管理費は不要、宗教・宗派も問いません。福岡県内の提携先は9か所です。</p>
          <p style="margin-top:10px"><a href="/teraumi/" style="color:var(--green);font-weight:700;font-size:.9rem">寺院納骨＋海洋散骨（teraumi）を見る →</a></p>
        </div>
      </div>
      <div style="max-width:600px;margin:24px auto 0;background:#fff;border:1.5px solid #cfe0d8;border-radius:14px;padding:20px 22px;text-align:center">
        <p style="font-weight:700;color:#1c3d2e;margin-bottom:8px">墓じまい後のご遺骨、どうするか決まっていますか？</p>
        <p style="font-size:.88rem;color:var(--text-light);margin-bottom:12px;line-height:1.9">まだ決まっていない方は、いくつかの質問に答えるだけで、今の状況に合う選択肢の組み合わせを整理できます（約3分・お名前の入力は不要）。</p>
        <a href="/shindan/" class="btn" style="background:#1c6b52">質問に答えて、選択肢を整理する →</a>
      </div>
    </div>
  </section>

  <!-- FAQ（福岡固有＋共通） -->
  <?php
    $gf_faq = [
      ['q' => '福岡の墓じまいの費用相場はいくらですか？',
       'a' => '全国的な相場は墓石撤去だけで1㎡あたり10〜15万円程度、行政手続きや運搬を含めると総額60〜120万円かかるケースもあります。当社の基本プラン33万円（税込）は、見積もり取得から撤去工事・写真報告・ご遺骨の引き出し・新しい納骨先へのご納骨までを含んだ金額です。現地調査で総額を確定し、確定後の追加請求はありません。'],
      ['q' => '福岡市営霊園（平尾・三日月山・西部など）の返還手続きも対応できますか？',
       'a' => 'はい。市営霊園は区画を更地に戻して返還する決まりがあり、返還条件の確認・更地工事・管理者への手続きまで一括でお手伝いします。寺院墓地・共同墓地も対応します。'],
      ['q' => '北九州や久留米など、福岡市以外にも来てもらえますか？',
       'a' => 'うかがいます。北九州エリア・筑後エリア・筑豊エリアを含む福岡県内全域に対応しています。移動にかかる費用が必要な場合も、現地調査の段階でお見積もりに含めてご提示しますので、あとから増えることはありません。'],
      ['q' => '改葬許可はどこでもらえますか？代行してもらえますか？',
       'a' => '改葬許可証は「今のお墓がある市区町村」の窓口で交付されます。申請書の書き方から埋蔵証明の取得まで、オプション（¥25,000〜）で申請をサポートします。'],
      ['q' => '県外に住んでいます。福岡に帰らずに墓じまいできますか？',
       'a' => 'できます。現地の立ち会いは不要で、お見積もり・打ち合わせはお電話・LINE・メールで完結します。工事の前後は写真でご報告し、取り出したご遺骨は粉骨のうえ郵送でお届けすることも、そのまま博多湾での散骨や納骨先へお納めすることもできます。'],
      ['q' => 'お寺への切り出し方や離檀料が心配です。',
       'a' => '離檀料に法的な支払い義務はなく、御礼として3万〜20万円程度が一般的な目安です。閉眼供養のお布施は1万〜5万円程度が目安。長くお世話になったお寺への伝え方から、一緒にご相談いただけます。'],
      ['q' => '取り出したご遺骨は博多湾で散骨できますか？',
       'a' => 'できます。当社は日本海洋散骨協会の加盟事業者として、姪浜から出港する博多湾の合同海洋葬（148,500円〜）と、スタッフにおまかせいただく委託海洋葬（54,450円〜）を行っています。散骨前の粉骨・六価クロム検査も含めて一括対応です。'],
      ['q' => 'ご遺骨の一部だけを手元に残すことはできますか？',
       'a' => 'できます。粉骨の際にご希望の量だけお分けし、大部分を散骨や納骨に、ひとつまみをミニ骨壷やペンダントでお手元に残せます。お持ち込みのお手元供養品への分骨は5,500円（税込）です。'],
      ['q' => 'どのくらいの期間がかかりますか？',
       'a' => 'ご相談から工事完了・納骨まで1〜3ヶ月程度が目安です。お彼岸やお盆前は工事が混み合うため、時期のご希望がある場合は早めのご相談がおすすめです。'],
      ['q' => '相談だけでも大丈夫ですか？どこに行けばいいですか？',
       'a' => 'もちろん大丈夫です。福岡営業所（福岡市中央区春吉2丁目1-3 2F）でのご相談のほか、お電話（090-5000-4825）・LINE・メールでもご相談いただけます。ご相談・お見積りは無料で、こちらから営業のご連絡はいたしません。'],
    ];
  ?>
  <section class="section" style="background:var(--white)">
    <div class="container" style="max-width:820px">
      <h2 style="text-align:center;margin-bottom:8px">福岡の墓じまい よくあるご質問</h2>
      <p style="text-align:center;font-size:.9rem;color:var(--text-light);margin-bottom:24px">このほかのご質問もお気軽にどうぞ。</p>
      <?php foreach ($gf_faq as $f): ?>
        <details style="background:#fff;border:1px solid var(--border);border-radius:10px;padding:16px 20px;margin-bottom:12px">
          <summary style="font-weight:600;cursor:pointer;color:var(--green-mid)">Q. <?= h($f['q']) ?></summary>
          <p style="margin-top:10px;font-size:.95rem;line-height:1.9">A. <?= h($f['a']) ?></p>
        </details>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- 拠点情報（MEO連携） -->
  <section class="section">
    <div class="container" style="max-width:760px">
      <h2 style="text-align:center;margin-bottom:20px">福岡のご相談窓口</h2>
      <div class="card" style="max-width:560px;margin:0 auto;text-align:center">
        <p style="font-weight:700;color:var(--green-mid);font-size:1.05rem;margin-bottom:8px">有限会社 縁 <?= h($fk['name']) ?></p>
        <p style="font-size:.92rem;line-height:2">〒<?= h($fk['zip']) ?> <?= h($fk['address']) ?><br>
        電話 <a href="tel:<?= h($fk_tel_link) ?>" style="color:var(--green);font-weight:700"><?= h($fk['tel']) ?></a>（9:00〜18:00・日曜定休）</p>
        <p style="margin-top:10px"><a href="https://maps.google.com/?cid=1235913108976072113" target="_blank" rel="noopener" style="color:var(--green);font-weight:600;font-size:.9rem">Googleマップで見る →</a></p>
        <p style="margin-top:12px;font-size:.85rem;color:var(--text-light)">合同海洋葬の出港場所：姪浜旅客待合所（福岡市西区愛宕浜3丁目1-1）</p>
      </div>
      <p style="text-align:center;margin-top:16px;font-size:.9rem"><a href="/kaiyou-sou/fukuoka/" style="color:var(--green);font-weight:600">博多湾での海洋散骨を詳しく見る →</a>　｜　<a href="/fukuoka/" style="color:var(--green);font-weight:600">福岡営業所のご案内 →</a></p>
    </div>
  </section>

  <!-- 関連記事（v0272：ページ → 記事の内部リンクを通す） -->
  <?php
    $gf_related = [
      ['fukuoka-hakajimai-33man-naiyaku', '福岡の墓じまい、基本プラン33万円に含まれるもの・含まれないもの', '見積書のどこまでが33万円で、何が別になるのかを項目ごとに。'],
      ['fukuoka-gyousha-5koumoku',        '福岡で墓じまいの業者を選ぶとき、金額以外に見る5項目', '安さだけで決めると後から積み上がります。判断材料の整理。'],
      ['fukuoka-kaiso-kyoka-doko',        '福岡で改葬許可はどこに出す？ 窓口と書類の順番', '申請先・必要書類・取得の順番を、迷わないように並べました。'],
      ['fukuoka-shiei-bochi-henkan',      '福岡市の市営墓地を返還したい。手続きの順番と窓口', '平尾・三日月山・西部霊園などの返還で、先に何を確認するか。'],
      ['fukuoka-hakajimai-kikan',         '福岡の墓じまいは何日かかる？ 申し込みから納骨までの目安', '混み合う時期と、逆算して動き始める時期の目安。'],
      ['fukuoka-ikotsu-ikisaki-hikaku',   '福岡で遺骨の移転先を選ぶ。霊園・納骨堂・寺院・散骨の比べ方', '費用・継ぐ人・お参りのしやすさで並べて考えます。'],
      ['fukuoka-hakajimai-shippai',       '福岡の墓じまいでよくある失敗と、避け方', '工事だけ終わって遺骨が残る、親族への連絡が後になる等。'],
      ['chikugo-kennan-hakajimai',        '筑後・県南の墓じまい。重機が入らない区画をどう進めるか', '通路が狭い区画・人力解体が必要な場合の進め方。'],
      ['fukuoka-shuuraku-bochi',          '福岡県内の集落墓地・共同墓地の墓じまい。地域の了解をどう取るか', '管理者がはっきりしない墓地で、誰にどう話すか。'],
    ];
  ?>
  <section class="section" style="background:var(--cream)">
    <div class="container" style="max-width:920px">
      <h2 style="text-align:center;margin-bottom:8px">福岡の墓じまいについて、さらに詳しく</h2>
      <p style="text-align:center;color:var(--text-light);font-size:.92rem;margin-bottom:24px">費用の内訳・手続き・期間など、気になるところから読めます。</p>
      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(252px,1fr));gap:14px">
        <?php foreach ($gf_related as $r): ?>
          <a class="card" href="/blog/?id=<?= rawurlencode($r[0]) ?>" style="display:block;text-decoration:none;color:inherit">
            <p style="font-size:.95rem;font-weight:700;color:var(--green-mid);line-height:1.7"><?= h($r[1]) ?></p>
            <p style="font-size:.84rem;color:var(--text-light);line-height:1.8;margin-top:8px"><?= h($r[2]) ?></p>
          </a>
        <?php endforeach; ?>
      </div>
      <p style="text-align:center;margin-top:22px;font-size:.92rem"><a href="/blog/?cat=<?= rawurlencode('お墓・納骨堂') ?>" style="color:var(--green);font-weight:600">「お墓・納骨堂」の記事をすべて見る →</a></p>
    </div>
  </section>

  <!-- 資料請求CTA（共通） -->
  <?php require __DIR__ . '/../../includes/shiryou-cta.php'; ?>

  <!-- CTA -->
  <section class="section" style="background:linear-gradient(135deg,var(--green),var(--green-mid));color:#fff;text-align:center">
    <div class="container">
      <h2 style="color:#fff">まずは無料でご相談ください</h2>
      <p style="opacity:.92;margin-bottom:22px">「話を聞くだけ」でも歓迎です。こちらから営業のご連絡はいたしません。</p>
      <a href="/contact/?service=<?= rawurlencode('お墓じまい') ?>" class="btn" style="background:#fff;color:var(--green-mid)">お問い合わせ</a>
      <a href="<?= h(SITE['line_url']) ?>" target="_blank" rel="noopener" class="btn" style="background:#06C755;margin-left:10px">LINEで相談</a>
      <a href="/contact/?service=<?= rawurlencode('資料請求（無料）') ?>" class="btn" style="background:#c9822a;margin-left:10px">無料で資料を受け取る</a>
      <p style="margin-top:18px">
        <?= h($fk['name']) ?> <a href="tel:<?= h($fk_tel_link) ?>" style="color:#fff;font-weight:700;font-size:1.2rem"><?= h($fk['tel']) ?></a><br>
        <span style="font-size:.9rem">本社（鹿児島） <a href="tel:<?= h(str_replace('-', '', SITE['tel'])) ?>" style="color:#fff;font-weight:700"><?= h(SITE['tel']) ?></a></span>
      </p>
    </div>
  </section>
</main>

<script type="application/ld+json">
<?= json_encode([
  '@context' => 'https://schema.org',
  '@type' => 'Service',
  'serviceType' => '墓じまい（お墓じまい）',
  'name' => '福岡の墓じまい 基本プラン',
  'provider' => [
    '@type' => 'LocalBusiness',
    'name' => '有限会社 縁 福岡営業所',
    'telephone' => '+81-90-5000-4825',
    'address' => ['@type' => 'PostalAddress', 'streetAddress' => '春吉2丁目1-3 2F', 'addressLocality' => '福岡市中央区', 'addressRegion' => '福岡県', 'postalCode' => '810-0003', 'addressCountry' => 'JP'],
    'url' => SITE['url'] . '/grave/fukuoka/',
    'hasMap' => 'https://maps.google.com/?cid=1235913108976072113',
  ],
  'areaServed' => [
    ['@type' => 'State', 'name' => '福岡県'],
    ['@type' => 'City', 'name' => '福岡市'],
    ['@type' => 'City', 'name' => '北九州市'],
    ['@type' => 'City', 'name' => '久留米市'],
  ],
  'offers' => ['@type' => 'Offer', 'price' => '330000', 'priceCurrency' => 'JPY', 'description' => '墓じまい基本プラン（見積もり取得〜撤去工事〜写真報告・遺骨引き出し・新納骨先への納骨まで・税込）'],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>
</script>
<script type="application/ld+json">
<?= json_encode([
  '@context' => 'https://schema.org',
  '@type' => 'WebPage',
  'name' => '墓じまい 福岡｜撤去〜納骨まで一括',
  'url' => SITE['url'] . '/grave/fukuoka/',
  'inLanguage' => 'ja',
  'dateModified' => $page_updated,
  'publisher' => ['@type' => 'Organization', 'name' => SITE['name'], 'url' => SITE['url'] . '/'],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>
</script>
<script type="application/ld+json">
<?= json_encode([
  '@context' => 'https://schema.org',
  '@type' => 'BreadcrumbList',
  'itemListElement' => [
    ['@type' => 'ListItem', 'position' => 1, 'name' => 'ホーム', 'item' => SITE['url'] . '/'],
    ['@type' => 'ListItem', 'position' => 2, 'name' => 'お墓じまい', 'item' => SITE['url'] . '/grave/'],
    ['@type' => 'ListItem', 'position' => 3, 'name' => '福岡の墓じまい', 'item' => SITE['url'] . '/grave/fukuoka/'],
  ],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>
</script>
<script type="application/ld+json">
<?= json_encode([
  '@context' => 'https://schema.org',
  '@type' => 'FAQPage',
  'mainEntity' => array_map(fn($f) => [
    '@type' => 'Question',
    'name' => $f['q'],
    'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f['a']],
  ], $gf_faq),
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>
</script>
<?php require __DIR__ . '/../../includes/footer.php'; ?>
