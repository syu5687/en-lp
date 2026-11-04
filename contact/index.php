<?php
require_once __DIR__ . '/../includes/config.php';
$page_title     = 'お問い合わせ｜' . SITE['name'];
$page_desc      = SITE['name'] . 'へのご相談・お見積りは無料です。海洋散骨・粉骨・お墓じまい等、お気軽にお問い合わせください。';
$page_canonical = SITE['url'] . '/contact/';
$page_hero_image = '/assets/img/hero-contact.jpg';
require __DIR__ . '/../includes/head.php';
?>
<body>
<?php require __DIR__ . '/../includes/header.php'; ?>
<section class="page-hero">
  <h1>お問い合わせ</h1>
  <p>ご相談・お見積りは無料です。お気軽にどうぞ。</p>
</section>
<nav class="breadcrumb"><a href="/">ホーム</a> ＞ お問い合わせ</nav>

<main class="section">
  <div class="container" style="max-width:720px">

    <!-- LINE誘導（副導線）-->
    <div class="card" style="text-align:center;margin-bottom:32px;border-color:#06C755">
      <p style="margin-bottom:12px">LINEでのご相談も承っています（友だち追加でそのまま相談可能）</p>
      <a href="<?= h(SITE['line_url']) ?>" target="_blank" rel="noopener" class="btn" style="background:#06C755">LINEで相談する</a>
    </div>

    <h2>フォームからのお問い合わせ</h2>

    <div style="background:#f6efdd;border-left:4px solid #a8802f;border-radius:0 10px 10px 0;padding:14px 18px;margin-bottom:20px;font-size:.92rem;line-height:1.9">
      <strong style="color:#0a3852">ご安心ください。</strong>ご相談いただいても、こちらから営業のお電話やしつこいご連絡は一切いたしません。<br>「まだ決めていない」「話を聞いてみたいだけ」という段階のご相談こそ、いちばんお役に立てます。
    </div>

    <div id="shindan-note" hidden style="background:var(--sea-light);border:1px solid var(--border);border-left:4px solid var(--green);border-radius:8px;padding:14px 18px;margin-bottom:20px;font-size:.92rem">
      「供養の選び方」診断の結果：<strong id="shindan-service" style="color:var(--green-mid)"></strong> についてのご相談ですね。<br>下記フォームにそのまま入力してお送りください。
      <span id="shindan-path-row" hidden style="display:block;margin-top:6px;font-size:.8rem;color:var(--text-light)">診断でお選びいただいた内容：<span id="shindan-path"></span>（送信時に一緒にお伝えします）</span>
    </div>

    <form id="contact-form" class="contact-form" novalidate>
      <!-- ハニーポット（人間には見えない欄。ボットが入力したら弾く） -->
      <div class="hp-field" aria-hidden="true">
        <label>ウェブサイト<input type="text" name="website" tabindex="-1" autocomplete="off"></label>
      </div>
      <label>お名前 <span class="req">必須</span>
        <input type="text" name="name" required>
      </label>
      <label>ふりがな <span class="req req--opt">任意</span>
        <input type="text" name="kana">
      </label>
      <label>メールアドレス <span class="req">必須</span>
        <input type="email" name="email" required>
      </label>
      <label>電話番号 <span class="req req--opt">任意</span>
        <input type="tel" name="tel" inputmode="tel">
      </label>
      <?php /* v0275：お問い合わせ種別をチェック項目にし、任意3項目（お住まい・ご年代・性別）を廃止。
               ・種別＝サービスのみ。複数選択できる（「墓じまい＋海洋散骨」のような相談が1件で拾える）
               ・資料請求は種別から外し、専用のチェック枠にした。「選んだサービスの資料を郵送する」という意味。
               ・資料請求＝郵送／ガイドブック＝PDF と役割を分けたので、お届け方法のラジオは廃止した。 */ ?>
      <fieldset id="svc-box">
        <legend class="svc-legend">お問い合わせ種別 <span class="req">必須</span></legend>
        <p class="svc-sub">ご相談の内容をお選びください（いくつでも選べます）。</p>
        <div class="svc-grid">
          <?php foreach (SERVICES as $s): ?>
            <label class="svc-item"><input type="checkbox" name="svc[]" value="<?= h($s['title']) ?>"><span><?= h($s['title']) ?></span></label>
          <?php endforeach; ?>
          <label class="svc-item"><input type="checkbox" name="svc[]" value="その他・まだ決まっていない"><span>その他・まだ決まっていない</span></label>
        </div>
        <p id="svc-error" class="svc-error" hidden>ご相談の内容を1つ以上お選びください。</p>
        <?php /* v0276：「その他」を選ばれた方に、内容を書いていただく欄（任意）。
                 入力があれば種別の文字列に（　）付きで足して、担当者通知に一緒に出す。 */ ?>
        <label id="svc-other-field" hidden>どのようなご相談ですか <span class="req req--opt">任意</span>
          <input type="text" name="svc_other" maxlength="60" placeholder="例：永代供養について／まだ決めかねています">
        </label>
      </fieldset>

      <fieldset id="shiryou-box">
        <legend class="sr-badge">無料・郵送</legend>
        <label class="sr-item"><input type="checkbox" name="shiryou_post" value="1"><span><b>上で選んだサービスの資料を郵送で受け取る</b><small>詳しい資料を無料でお送りします。お届け先のご入力をお願いします</small></span></label>
      </fieldset>


      <div id="post-addr" hidden>
        <p id="post-addr-title" style="font-size:.85rem;color:#8a6a2a;font-weight:700;margin:0 0 4px">資料のお届け先をご入力ください</p>
        <label>郵便番号 <span class="req" id="zip-req">必須</span>
          <input type="text" name="zip" inputmode="numeric" autocomplete="postal-code" placeholder="890-0000" maxlength="8">
          <span id="zip-note" style="font-weight:400;font-size:.78rem;color:var(--text-light)">入力すると住所が自動で入ります</span>
        </label>
        <label>ご住所（お届け先） <span class="req" id="addr-req">必須</span>
          <input type="text" name="addr" autocomplete="street-address" placeholder="鹿児島県鹿児島市〇〇町1-2-3 〇〇マンション101">
          <span id="addr-note" style="font-weight:400;font-size:.78rem;color:var(--text-light)">郵便番号を入れると自動で入ります。続けて番地・建物名までご入力ください。</span>
        </label>
      </div>

      <label id="goudou-date-field" hidden>合同海洋散骨 ご希望日 <span class="req req--opt">任意</span>
        <input type="date" name="goudou_date">
        <span style="font-weight:400;font-size:.8rem;color:var(--text-light)">実施予定日からお選びいただいた日付です。変更も可能です。</span>
      </label>
      <label>お問い合わせ内容 <span class="req">必須</span>
        <textarea name="message" rows="6" required></textarea>
      </label>
      <fieldset id="guide-box">
        <legend class="gb-badge">無料プレゼント</legend>
        <?php /* v0275：資料請求＝郵送／ガイドブック＝PDF に役割を分けた */ ?>
        <p class="gb-title">📘 無料ガイドブックも一緒に受け取れます</p>
        <p class="gb-sub">チェックすると、<strong>送信後すぐこの画面でお読みいただけます</strong>（PDF）。ご登録のメールアドレスにもお送りします。どのご相談と一緒でもOK・無料です。</p>
        <label class="gb-item"><input type="checkbox" name="guide_hakajimai" value="1"><span><b>墓じまい完全ガイド 鹿児島・福岡版</b>（全10ページ）<small>費用の内訳・改葬許可の5ステップ・菩提寺への切り出し方</small></span></label>
        <label class="gb-item"><input type="checkbox" name="guide_sankotsu" value="1"><span><b>海洋散骨で後悔しないためのチェックリスト</b>（全9ページ）<small>業者選び7項目・委託/合同/貸切の選び方・当日の流れ</small></span></label>
      </fieldset>

      <?php /* v0276：別ページへのリンクではなく、フォーム内に全文を載せる。
               本文は includes/privacy-text.php（/privacy/ と同じもの）。 */ ?>
      <div id="privacy-box">
        <p class="pv-title">個人情報の取り扱いについて</p>
        <div id="privacy-inline" tabindex="0" role="region" aria-label="個人情報保護方針">
          <?php require __DIR__ . '/../includes/privacy-text.php'; ?>
        </div>
      </div>
      <label class="contact-consent">
        <input type="checkbox" name="consent" value="1" required>
        <span>上記の個人情報の取り扱いに同意します <span class="req">必須</span></span>
      </label>
      <p style="font-size:.82rem;color:var(--text-light);margin:-6px 0 0">送信後、受付確認の自動返信メールが届きます。営業のご連絡はいたしませんので、ご安心ください。</p>
      <button type="submit" class="btn" id="submit-btn">送信する</button>
      <div id="form-msg" role="status" aria-live="polite"></div>
      <?php /* v0275：ガイドブックをご希望の方へ、送信後この場でPDFをお渡しする枠 */ ?>
      <div id="guide-result" hidden>
        <p class="gr-title">📘 ご希望のガイドブックです</p>
        <p class="gr-sub">今すぐお読みいただけます。ご登録のメールアドレスにも同じリンクをお送りしました。</p>
        <div id="guide-result-links"></div>
      </div>
    </form>

    <p style="margin-top:24px;font-size:.9rem;color:var(--text-light)">
      お電話でも承ります：<a href="tel:<?= h(SITE['tel']) ?>" style="color:var(--green);font-weight:700"><?= h(SITE['tel']) ?></a>（<?= h(SITE['hours_jp']) ?>）
    </p>
  </div>
</main>

<style>
/* v0259：ガイドブックのお届け方法。既存の .gb-item と同じ指定の強さで書く
   （.contact-form label が flex-direction:column を当てているため、行方向を明示する） */
/* v0275：お届け方法（#delivery-box）は廃止した。資料請求＝郵送／ガイドブック＝PDF。 */
#post-addr{border:1px solid #eadfc4;border-radius:10px;padding:14px 16px;background:#fffdf9;display:flex;flex-direction:column;gap:10px}
/* v0274：ID指定の display:flex が hidden 属性（UAの display:none）に勝ってしまい、
   お届け先が常に表示されたままになっていた。属性セレクタを足して確実に閉じる。
   同じ理由で #delivery-box と #goudou-date-field にも display を書かないこと。 */
#post-addr[hidden]{display:none}
/* v0275：お問い合わせ種別（チェック項目）と資料請求枠 */
#svc-box{border:1px solid var(--border);border-radius:10px;padding:14px 16px 16px;background:var(--white)}
#svc-box[hidden]{display:none}
#svc-box .svc-legend{font-weight:700;font-size:.92rem;color:var(--green-mid);padding:0 6px}
#svc-box .svc-sub{font-size:.8rem;color:var(--text-light);margin:2px 0 12px}
#svc-box .svc-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(210px,1fr));gap:8px}
#svc-box .svc-item{display:flex;flex-direction:row !important;align-items:center;gap:9px;background:#fafafa;border:1px solid var(--border);border-radius:9px;padding:10px 12px;cursor:pointer;font-weight:400 !important;font-size:.9rem}
#svc-box .svc-item:hover{border-color:var(--green)}
#svc-box .svc-item input{width:18px;height:18px;padding:0 !important;flex:none;accent-color:var(--green)}
#svc-box .svc-item:has(input:checked){background:#eef6fa;border-color:var(--green)}
#svc-box .svc-error{color:#c0392b;font-weight:700;font-size:.84rem;margin:10px 0 0}
#svc-box .svc-error[hidden]{display:none}
#shiryou-box{border:1px solid #cfe0e8;border-radius:10px;padding:12px 16px 14px;background:#f7fbfc;margin-top:-4px}
#shiryou-box[hidden]{display:none}
#shiryou-box .sr-badge{background:var(--green);color:#fff;font-size:.72rem;font-weight:700;padding:3px 10px;border-radius:999px;margin-left:4px}
#shiryou-box .sr-item{display:flex;flex-direction:row !important;align-items:flex-start;gap:10px;cursor:pointer;font-weight:400 !important;margin-top:6px}
#shiryou-box .sr-item input{width:18px;height:18px;margin-top:3px;padding:0 !important;flex:none;accent-color:var(--green)}
#shiryou-box .sr-item b{color:var(--green-mid);font-size:.92rem}
#shiryou-box .sr-item small{display:block;font-size:.76rem;color:var(--text-light);margin-top:2px;line-height:1.7}
/* v0276：「その他」の内容欄 */
#svc-box #svc-other-field{margin-top:12px;display:flex;flex-direction:column;gap:6px;font-weight:600;font-size:.88rem;color:var(--green-mid)}
#svc-box #svc-other-field[hidden]{display:none}
/* v0276：フォーム内に載せる個人情報保護方針 */
#privacy-box{border:1px solid var(--border);border-radius:10px;padding:14px 16px;background:var(--white)}
#privacy-box .pv-title{font-weight:700;font-size:.92rem;color:var(--green-mid);margin:0 0 10px}
#privacy-inline{max-height:220px;overflow-y:auto;-webkit-overflow-scrolling:touch;border:1px solid var(--border);border-radius:8px;padding:14px 16px;background:#fcfcfa;font-size:.82rem;line-height:1.95;color:#4a555c}
#privacy-inline:focus-visible{outline:2px solid var(--green);outline-offset:2px}
#privacy-inline .lead{margin:0 0 12px}
#privacy-inline h2{font-size:.86rem;font-weight:700;color:var(--green-mid);margin:16px 0 4px;padding:0;border:0}
#privacy-inline h2:first-of-type{margin-top:0}
#privacy-inline p{margin:0 0 8px}
#privacy-inline ul{margin:0 0 8px}
#privacy-inline li{margin-bottom:2px}
/* v0275：送信後のガイドブック受け取り枠 */
#guide-result{border:1px solid #e3d5b8;border-radius:10px;padding:16px 18px;background:#fffdf5;margin-top:14px}
#guide-result[hidden]{display:none}
#guide-result .gr-title{font-weight:700;color:#8a6a2a;margin:0 0 4px}
#guide-result .gr-sub{font-size:.84rem;color:var(--text-light);margin:0 0 12px;line-height:1.8}
#guide-result a{display:block;background:#c9822a;color:#fff;text-decoration:none;font-weight:700;font-size:.92rem;padding:12px 16px;border-radius:9px;margin-bottom:8px;text-align:center}
#guide-result a:last-child{margin-bottom:0}
#guide-result a:hover{background:#b0701f}

.contact-form{display:flex;flex-direction:column;gap:18px;background:var(--white);border:1px solid var(--border);border-radius:var(--radius);padding:28px}
.contact-form label{display:flex;flex-direction:column;gap:8px;font-weight:600;font-size:.9rem}
.contact-form label[hidden]{display:none !important} /* 合同散骨ご希望日欄は日付指定の遷移時のみ表示 */
.contact-attrs{display:grid;grid-template-columns:repeat(3,1fr);gap:14px}
.contact-attrs label{display:flex;flex-direction:column;gap:8px;font-weight:600;font-size:.9rem}
@media(max-width:560px){.contact-attrs{grid-template-columns:1fr}}
.contact-form input,.contact-form select,.contact-form textarea{padding:12px;border:1px solid var(--border);border-radius:8px;font-size:1rem;font-family:inherit}
.contact-form .req{display:inline-block;background:var(--green);color:#fff;font-size:.7rem;padding:2px 8px;border-radius:4px;margin-left:6px;align-self:flex-start}
/* v0274：必須ではないときのバッジ。赤系の必須表示のまま任意にすると誤解を招くため。 */
.contact-form .req--opt{background:#9aa0a6}
.contact-consent{flex-direction:row!important;align-items:center;justify-content:center;gap:10px;font-weight:400!important}
.contact-consent input[type=checkbox]{width:18px;height:18px;padding:0!important;margin:0;flex:none;accent-color:var(--green)}
.contact-consent span{white-space:nowrap}
.contact-consent a{color:var(--green);font-weight:600;text-decoration:underline}
#form-msg:not(:empty){padding:14px;border-radius:8px;margin-top:4px;font-size:.95rem;text-align:center}
#form-msg.ok{background:#e8f5e9;color:#2e7d32}
#form-msg.ng{background:#fdecea;color:#c0392b}
.hp-field{position:absolute !important;left:-9999px !important;top:-9999px !important;height:1px;width:1px;overflow:hidden}
#guide-box{border:2px solid #c9822a;border-radius:12px;background:#fdf9f0;padding:16px 18px 14px;margin:4px 0}
#guide-box .gb-badge{background:#c9822a;color:#fff;font-size:.72rem;font-weight:700;letter-spacing:.08em;padding:3px 14px;border-radius:999px}
#guide-box .gb-title{font-weight:700;color:#8a6a2a;margin:6px 0 2px;font-size:.98rem}
#guide-box .gb-sub{font-size:.8rem;color:var(--text-light);margin:0 0 10px}
#guide-box .gb-item{display:flex;flex-direction:row !important;align-items:flex-start;gap:10px;background:#fff;border:1px solid #e3d5b8;border-radius:10px;padding:10px 14px;margin-bottom:8px;cursor:pointer;font-weight:400 !important}
#guide-box .gb-item:hover{border-color:#c9822a}
#guide-box .gb-item input{width:18px;height:18px;margin-top:3px;padding:0 !important;flex:none;accent-color:#c9822a}
#guide-box .gb-item b{color:var(--green-mid);font-size:.92rem}
#guide-box .gb-item small{display:block;font-size:.76rem;color:var(--text-light);margin-top:2px}
</style>

<script>
const WORKER_URL = <?= json_encode(CONTACT_WORKER_URL) ?>;
const PAGE_LOADED_AT = Date.now();
const form = document.getElementById('contact-form');
const msg  = document.getElementById('form-msg');
const btn  = document.getElementById('submit-btn');

// 「供養の選び方」診断からの遷移：選択したご供養を表示し、種別に自動セット
let shindanService = '';
let shindanPath = '';
(function () {
  const params = new URLSearchParams(location.search);
  const svc = (params.get('service') || '').trim();
  const sd  = (params.get('shindan') || '').trim();
  const sdp = (params.get('sdpath') || '').trim();
  if (!svc && !sd) return;
  // v0259：通知メールの「診断結果（供養の選び方）」欄には、診断を実際に通ったときだけ入れる。
  // 以前は ?service= の値も入れていたため、資料請求ボタンから来ただけの方が
  // 「診断結果：資料請求（無料）」と表示され、社内で紛らわしくなっていた。
  shindanService = sd;
  shindanPath = sdp;
  // お知らせバナー（表示は ?service= でも出す。送信する診断結果とは別扱い）
  const note = document.getElementById('shindan-note');
  document.getElementById('shindan-service').textContent = sd || svc;
  if (sdp) {
    document.getElementById('shindan-path').textContent = sdp;
    document.getElementById('shindan-path-row').hidden = false;
  }
  note.hidden = false;
  if (!svc) return;
  /* v0275：種別はチェック項目になったので、?service= は該当のチェックを入れる形にする。
     サイト内の資料請求CTA（includes/shiryou-cta.php ほか）は
     /contact/?service=資料請求（無料） のままなので、その場合は資料請求の枠にチェックを入れる。
     既存のリンクを一切書き換えずに済ませるための受け皿。 */
  if (svc.indexOf('資料請求') === 0) {
    const sr = form.querySelector('input[name="shiryou_post"]');
    if (sr) sr.checked = true;
    return;
  }
  const boxes = [...form.querySelectorAll('input[name="svc[]"]')];
  const hit = boxes.find(b => b.value === svc)
           || boxes.find(b => svc.indexOf(b.value) === 0 || b.value.indexOf(svc) === 0);
  if (hit) hit.checked = true;
  else { const o = boxes.find(b => b.value.indexOf('その他') === 0); if (o) o.checked = true; }
})();
// お届け先の表示制御（v0275：条件は「資料請求にチェックが入っているか」の1本だけ）
(function () {
  const ta  = form.querySelector('textarea[name="message"]');
  const g1  = form.querySelector('input[name="guide_hakajimai"]');
  const g2  = form.querySelector('input[name="guide_sankotsu"]');
  const srPost = form.querySelector('input[name="shiryou_post"]');
  const svcBoxes = [...form.querySelectorAll('input[name="svc[]"]')];
  const addrBox = document.getElementById('post-addr');
  const addrTitle = document.getElementById('post-addr-title');
  const zipInp  = form.querySelector('input[name="zip"]');
  const addrInp = form.querySelector('input[name="addr"]');

  /* v0259：表示条件を整理した。
     ・お届け方法  … ガイドブックが1つでもチェックされていたら出す
     ・住所欄      … お届け方法で「冊子（郵送）」が選ばれたときだけ出す
     種別セレクトは一切条件に使わない。種別を変えても住所欄は消えない。
     hidden の要素に required が残るとChromeは検証に失敗してもエラーを出せず、
     送信が黙って止まるため、hidden と required は必ずセットで切り替える。 */
  /* v0273：必須かどうかと、画面に出すかどうかを分ける。
     本文に「郵送してほしい」と書かれたときは、住所欄を“任意”で開くため。
     値を消すのは欄を閉じるときだけにする（任意表示のまま消すと入力が飛ぶ）。 */
  const setField = (el, required, visible) => {
    if (!el) return;
    el.required = !!required;
    if (!visible) el.value = '';   // 非表示の値を送らない
  };
  /* v0274：バッジの文言も実際の状態に合わせる。 */
  const zipReqBadge  = document.getElementById('zip-req');
  const addrReqBadge = document.getElementById('addr-req');
  const setReqBadge = (el, required) => {
    if (!el) return;
    el.textContent = required ? '必須' : '任意';
    el.classList.toggle('req--opt', !required);
  };

  /* ============================================================
     v0273：本文に「郵送してほしい」と書かれた場合の受け皿
     ------------------------------------------------------------
     2026-11-04 の実例：
       種別もガイドブックも選ばず、内容欄に「パンフレットを、送付希望です。」
       とだけ書いて送信された。住所欄は一度も表示されず、お届け先が分からないため
       資料を発送できなかった。
     チェックボックスを押す方だけを想定していたのが原因なので、
     本文の言葉からも住所欄を開く。ただし required にはしない（誤検知で
     送信できなくなるほうが損失が大きいため、あくまで“ご案内”に留める）。
     ============================================================ */
  const POST_WANT = /(郵送|送付|パンフ|カタログ|冊子|資料|送ってくだ|送って下さ|お送りくだ|お送り下さ|届けてくだ|届けて下さ|郵便で)/;
  const POST_DENY = /(不要|いりません|要りません|結構です|送らないで|必要ありません)/;
  const wantsPostFromText = (v) => {
    const t = String(v || '');
    if (!t) return false;
    if (!POST_WANT.test(t)) return false;
    if (POST_DENY.test(t)) return false;   // 「資料は不要です」などを拾わない
    return true;
  };
  let msgPostHint = false;   // 本文から郵送希望を読み取ったか（いちど立てたら下げない）
  const apply = () => {
    /* v0275：住所が要るのは「資料請求（郵送）にチェックが入っているとき」だけ。
       ガイドブックはPDFでお渡しするので住所は不要。
       本文から郵送希望を読み取ったときは、必須にはせず“任意”で欄だけ開く（v0273）。 */
    const addrRequired = !!(srPost && srPost.checked);
    const needsAddr    = addrRequired || msgPostHint;
    if (addrTitle) {
      addrTitle.textContent = addrRequired
        ? '資料のお届け先をご入力ください'
        : '資料の郵送をご希望の場合は、お届け先をご入力ください（任意）';
    }
    if (addrBox) {
      const wasHidden = addrBox.hidden;
      addrBox.hidden = !needsAddr;
      setField(zipInp,  addrRequired, needsAddr);
      setField(addrInp, addrRequired, needsAddr);
      setReqBadge(zipReqBadge,  addrRequired);
      setReqBadge(addrReqBadge, addrRequired);
      /* v0261：表示・非表示のどちらでも必ず検証状態を更新する。
         setCustomValidity のメッセージは required と無関係に要素を「無効」にするため、
         不正な住所を入れたまま住所欄を閉じると、非表示の欄が原因でフォーム全体が
         送信できなくなる（reportValidity は非表示要素にフォーカスできず、エラーも出せない）。 */
      refreshAddr();
      void wasHidden;
    }
  };
  /* ============================================================
     v0260：郵便番号→住所の自動入力と、お届け先の入力チェック
     ------------------------------------------------------------
     2026-09-13 の報告：
       ① 住所が「鹿児島県」だけで送信できてしまう
       ② 郵便番号を入れても住所が自動で入らない
     どちらも「お住まい」の都道府県を住所欄へ先に入れていたことが原因。
     プリフィルをやめたうえで、下の3点を足す。
       ・郵便番号は7桁で検証（全角・ハイフンも受ける）
       ・住所は「都道府県だけ」「番地なし」を通さない
       ・自動入力は CORS が通らない環境に備えて JSONP へ退避する
     ============================================================ */
  const zipNote  = document.getElementById('zip-note');
  const addrNote = document.getElementById('addr-note');
  const ZIP_NOTE_DEFAULT  = '入力すると住所が自動で入ります';
  const ADDR_NOTE_DEFAULT = '郵便番号を入れると自動で入ります。続けて番地・建物名までご入力ください。';

  const toHalf = (v) => String(v || '').replace(/[０-９]/g, (c) => String.fromCharCode(c.charCodeAt(0) - 0xFEE0));
  const digits = (v) => toHalf(v).replace(/[^0-9]/g, '');
  // 都道府県名だけ、の判定（例：「鹿児島県」「東京都」「大阪府」「北海道」）
  const isPrefOnly = (v) => /^(北海道|東京都|京都府|大阪府|[^\s]{2,3}県)$/.test(String(v || '').trim());

  const setNote = (el, text, ng) => {
    if (!el) return;
    el.textContent = text;
    el.style.color = ng ? '#c0392b' : '';
    el.style.fontWeight = ng ? '700' : '400';
  };

  // 住所：都道府県だけ／市区町村どまり／番地なし を弾く
  const addrProblem = (v) => {
    const t = String(v || '').trim();
    if (!t) return '';                     // 空欄は required 側の仕事
    if (isPrefOnly(t)) return '市区町村・番地までご入力ください。';
    const m = t.match(/^(北海道|東京都|京都府|大阪府|[^\s]{2,3}県)/);
    const rest = m ? t.slice(m[0].length) : t;
    if (rest.replace(/\s/g, '').length < 4) return '市区町村・番地までご入力ください。';
    if (!/[0-9０-９一二三四五六七八九十丁番地号]/.test(rest)) return '番地までご入力ください（例：鹿児島市本港新町35）。';
    return '';
  };

  const zipProblem = (v) => {
    const z = digits(v);
    if (!z) return '';                     // 空欄は required 側の仕事
    if (z.length !== 7) return '郵便番号は7桁でご入力ください（例：890-0053）。';
    return '';
  };

  function refreshAddr() {
    if (addrInp) {
      const ng = addrInp.required ? addrProblem(addrInp.value) : '';
      addrInp.setCustomValidity(ng);
      if (ng) setNote(addrNote, ng, true);
      else if (addrNote && addrNote.style.color) setNote(addrNote, ADDR_NOTE_DEFAULT, false);
    }
    if (zipInp) {
      const ng = zipInp.required ? zipProblem(zipInp.value) : '';
      zipInp.setCustomValidity(ng);
      if (ng) setNote(zipNote, ng, true);
      else if (zipNote && zipNote.style.color) setNote(zipNote, ZIP_NOTE_DEFAULT, false);
    }
  }

  // CORS が通らない環境向けの退避（zipcloud は callback パラメータに対応している）
  const jsonpZip = (z) => new Promise((resolve, reject) => {
    const cb = '__enZipCb' + Date.now() + Math.floor(Math.random() * 1000);
    const sc = document.createElement('script');
    const finish = () => {
      clearTimeout(timer);
      try { delete window[cb]; } catch (e) { window[cb] = undefined; }
      if (sc.parentNode) sc.parentNode.removeChild(sc);
    };
    const timer = setTimeout(() => { finish(); reject(new Error('timeout')); }, 6000);
    window[cb] = (j) => { finish(); resolve(j && j.results && j.results[0]); };
    sc.onerror = () => { finish(); reject(new Error('script')); };
    sc.src = 'https://zipcloud.ibsnet.co.jp/api/search?zipcode=' + z + '&callback=' + cb;
    document.head.appendChild(sc);
  });

  let zipAutoFilled = '';
  let zipSeq = 0;

  const lookupZip = async (z) => {
    const seq = ++zipSeq;
    setNote(zipNote, '住所を検索中…', false);
    let a = null;
    try {
      const r = await fetch('https://zipcloud.ibsnet.co.jp/api/search?zipcode=' + z);
      const j = await r.json();
      a = j && j.results && j.results[0];
    } catch (e) { a = null; }
    if (!a) { try { a = await jsonpZip(z); } catch (e) { a = null; } }
    if (seq !== zipSeq) return;                       // 入力が進んでいたら捨てる
    if (!a) {
      setNote(zipNote, '住所を自動で取得できませんでした。お手数ですが手入力をお願いします。', true);
      return;
    }
    const auto = (a.address1 || '') + (a.address2 || '') + (a.address3 || '');
    const cur = String(addrInp.value || '').trim();
    // 空・前回の自動入力値・都道府県だけ、のときは上書きしてよい
    if (!cur || cur === zipAutoFilled || isPrefOnly(cur)) {
      addrInp.value = auto;
      zipAutoFilled = auto;
      setNote(zipNote, ZIP_NOTE_DEFAULT, false);
      setNote(addrNote, '続けて番地・建物名までご入力ください。', false);
      try { addrInp.focus(); addrInp.setSelectionRange(auto.length, auto.length); } catch (e) {}
    } else {
      setNote(zipNote, '入力済みのご住所を優先しました（郵便番号からは「' + auto + '」）', false);
    }
    refreshAddr();
  };

  if (zipInp && addrInp) {
    const onZip = () => {
      refreshAddr();
      const z = digits(zipInp.value);
      if (z.length === 7) lookupZip(z);
    };
    zipInp.addEventListener('input', onZip);
    zipInp.addEventListener('change', onZip);
    zipInp.addEventListener('blur', refreshAddr);
    addrInp.addEventListener('input', refreshAddr);
    addrInp.addEventListener('blur', refreshAddr);
  }
  // v0275：住所欄の表示を決めているのは資料請求のチェックだけ。
  if (srPost) srPost.addEventListener('change', apply);
  // 種別を選んだらエラー表示を消す／「その他」の内容欄を出し入れする
  const otherBox   = svcBoxes.find(b => b.value.indexOf('その他') === 0);
  const otherField = document.getElementById('svc-other-field');
  const otherInput = form.querySelector('input[name="svc_other"]');
  const applyOther = () => {
    const on = !!(otherBox && otherBox.checked);
    if (otherField) otherField.hidden = !on;
    if (!on && otherInput) otherInput.value = '';   // 閉じたら値も消す
  };
  svcBoxes.forEach(b => b.addEventListener('change', () => {
    const e = document.getElementById('svc-error');
    if (e && svcBoxes.some(x => x.checked)) e.hidden = true;
    applyOther();
  }));
  applyOther();
  /* v0273：内容欄の入力を見て、郵送希望らしければ住所欄を開く（いちど開いたら閉じない） */
  if (ta) {
    const watchMsg = () => {
      if (msgPostHint) return;
      if (!wantsPostFromText(ta.value)) return;
      msgPostHint = true;
      apply();
    };
    ta.addEventListener('input', watchMsg);
    ta.addEventListener('blur',  watchMsg);
  }

  /* v0273：送信直前の最後の砦。
     郵送希望らしい本文なのにお届け先が空なら、1回だけ止めてご案内する。
     2回目の送信は止めない（誤検知で送れなくなるのを避ける）。 */
  let postAsked = false;
  window.__enPostGuard = () => {
    if (postAsked) return '';
    if (!ta || !wantsPostFromText(ta.value)) return '';
    if (addrInp && String(addrInp.value || '').trim()) return '';
    postAsked = true;
    msgPostHint = true;
    apply();
    try {
      addrBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
      if (zipInp) zipInp.focus();
    } catch (e) {}
    return '資料の郵送をご希望でしょうか。ご希望でしたら、お届け先（郵便番号・ご住所）をご入力ください。ご不要の場合は、もう一度「送信する」を押していただければそのまま送信されます。';
  };

  /* v0275：送信が終わって form.reset() をしたあとの後始末。
     reset はチェックを外すだけで apply() を呼ばないため、資料請求のチェックが消えても
     お届け先の欄が「必須」のまま開きっぱなしになっていた。 */
  window.__enAfterReset = () => {
    msgPostHint = false;
    postAsked = false;
    const e = document.getElementById('svc-error');
    if (e) e.hidden = true;
    apply();
    applyOther();   // v0276：「その他」の内容欄も閉じる
    if (zipNote)  setNote(zipNote,  ZIP_NOTE_DEFAULT,  false);
    if (addrNote) setNote(addrNote, ADDR_NOTE_DEFAULT, false);
  };

  window.__enRefreshAddr = refreshAddr; // v0260：送信直前のチェックから呼ぶ
  setTimeout(apply, 0); // ?service= からの自動選択にも反応
})();
// 合同海洋散骨 実施予定日からの遷移：?date=YYYY-MM-DD をご希望日欄にセット
(function () {
  const params = new URLSearchParams(location.search);
  const d = (params.get('date') || '').trim();
  const field = document.getElementById('goudou-date-field');
  if (!/^\d{4}-\d{2}-\d{2}$/.test(d)) return;
  field.hidden = false;
  field.querySelector('input[name="goudou_date"]').value = d;
  const note = document.getElementById('shindan-note');
  if (note.hidden) {
    document.getElementById('shindan-service').textContent = '合同海洋散骨（ご希望日 ' + d.replace(/-/g, '/') + '）';
    note.hidden = false;
  }
})();
/* v0258：フォームの最初の入力で form_start を1回だけ送る。
   GA4の拡張計測「フォームの操作」は2026年8月に5回しか記録されておらず、
   このフォームでは当てにならないため自前で撃つ。表示→入力開始→送信の離脱が測れるようになる。 */
(function () {
  var started = false;
  form.addEventListener('input', function () {
    if (started) return;
    started = true;
    if (typeof gtag === 'function') {
      gtag('event', 'form_start', { form_name: 'contact', page_path: location.pathname });
    }
  }, { once: false });
})();

form.addEventListener('submit', async (e) => {
  e.preventDefault();
  msg.className = ''; msg.textContent = '';
  if (typeof window.__enRefreshAddr === 'function') window.__enRefreshAddr(); // v0260：お届け先の再チェック
  /* v0273：本文で資料の郵送を希望されているのにお届け先が空のとき、1回だけご案内する。 */
  if (typeof window.__enPostGuard === 'function') {
    const ask = window.__enPostGuard();
    if (ask) { msg.className = 'ng'; msg.textContent = ask; return; }
  }
  /* v0275：お問い合わせ種別はチェックボックスのグループなので required が使えない。
     ブラウザ任せにできないぶん、ここで明示的に見る。 */
  const svcChecked = [...form.querySelectorAll('input[name="svc[]"]:checked')].map(b => b.value);
  const svcErr = document.getElementById('svc-error');
  if (!svcChecked.length) {
    if (svcErr) svcErr.hidden = false;
    msg.className = 'ng';
    msg.textContent = 'ご相談の内容を1つ以上お選びください。';
    try { document.getElementById('svc-box').scrollIntoView({ behavior:'smooth', block:'center' }); } catch (e2) {}
    return;
  }
  if (svcErr) svcErr.hidden = true;
  if (!form.checkValidity()) { form.reportValidity(); return; }
  const data = Object.fromEntries(new FormData(form).entries());
  /* v0275：複数選択なので FormData の entries では最後の1件しか残らない。
     担当者通知メールの「お問い合わせ種別」欄にそのまま出したいので、ここで組み立てる。 */
  delete data['svc[]'];
  delete data.svc_other;
  /* v0276：「その他」に内容を書いていただいた場合は、種別の文字列に（　）付きで足す。
     担当者通知の「お問い合わせ種別」が1行で読めるようにするため、別項目にはしない。 */
  const otherTxt = String((form.querySelector('input[name="svc_other"]') || {}).value || '').trim();
  data.category = svcChecked.map(v =>
    (otherTxt && v.indexOf('その他') === 0) ? (v + '（' + otherTxt + '）') : v
  ).join('／');
  if (!/^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(data.email || '')) {
    msg.className = 'ng';
    msg.textContent = 'メールアドレスの形式をご確認ください。';
    return;
  }
  /* v0275：お届け方法のラジオは廃止。資料請求（郵送）かどうかだけを送る。 */
  delete data.shiryou_post;
  if (form.querySelector('input[name="shiryou_post"]').checked) data.shiryou = '郵送を希望';
  /* v0274：これまで post-addr の hidden だけで判定していたため、CSSの都合で欄が出ていた
     期間は、お客様が入力したお届け先が送信直前に捨てられていた。
     欄を閉じるときに値は空にしているので、「値が入っていれば送る」で十分かつ安全。 */
  if (!String(data.zip  || '').trim()) delete data.zip;
  if (!String(data.addr || '').trim()) delete data.addr;
  data.source = location.href;
  data.formName = 'en1150.co.jp お問い合わせフォーム';
  data.elapsedMs = Date.now() - PAGE_LOADED_AT; // 表示から送信までの時間（ボット判定用）
  if (shindanService) data.shindan = shindanService; // 診断結果を通知メールにも記載
  if (shindanPath) data.shindan_path = shindanPath;  // 診断で選ばれた全回答
  btn.disabled = true; btn.textContent = '送信中…';
  try {
    const res = await fetch(WORKER_URL, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(data),
    });
    if (!res.ok) throw new Error('send failed');
    msg.className = 'ok';
    msg.textContent = 'お問い合わせを送信しました。担当者より折り返しご連絡いたします。';
    /* v0275：ガイドブックをご希望の方には、この場でPDFをお渡しする。
       メールが届かない方・迷惑メールに入る方にも確実に受け取っていただくため。
       リンク先は /dl/ の計測用中継ページ（GA4に pdf_open を送ってからPDFへ転送する）。 */
    (function () {
      const want = [];
      if (data.guide_hakajimai) want.push({ f:'hakajimai', t:'墓じまい完全ガイド 鹿児島・福岡版（PDF・全10ページ）' });
      if (data.guide_sankotsu)  want.push({ f:'checklist', t:'海洋散骨で後悔しないためのチェックリスト（PDF・全9ページ）' });
      if (!want.length) return;
      const box = document.getElementById('guide-result');
      const list = document.getElementById('guide-result-links');
      if (!box || !list) return;
      list.innerHTML = '';
      want.forEach(function (g) {
        const a = document.createElement('a');
        a.href = '/dl/?f=' + g.f + '&src=form';
        a.target = '_blank'; a.rel = 'noopener';
        a.textContent = '▶ ' + g.t + ' を開く';
        list.appendChild(a);
      });
      box.hidden = false;
      try { box.scrollIntoView({ behavior:'smooth', block:'center' }); } catch (e3) {}
    })();
    form.reset();
    if (typeof window.__enAfterReset === 'function') window.__enAfterReset();
    // ---- CV測定（GA4）----
    // v0258：2026年8月のGA4で generate_lead が202PVに対し212回発火しており、
    // 「お問い合わせページの表示」でも発火している疑いが強い（原因はGA4管理画面側）。
    // そこで、このコードからしか発火しない contact_submit を新設する。
    // 以後の「問い合わせ送信数」は contact_submit を見る。generate_lead は
    // Google広告のコンバージョン取り込みが参照している可能性があるため残す。
    // ボットによる送信をCVに数えないため、「表示から5秒以上経過・ハニーポット空・メール形式OK」の場合のみ発火。
    var looksHuman = data.elapsedMs >= 5000 && !data.website && /@.+\./.test(data.email || '');
    if (typeof gtag === 'function' && looksHuman) {
      var cvParams = {
        form_name: 'contact',
        category: data.category || '(未選択)',
        svc_count: svcChecked.length,                 // v0275：選ばれたサービスの数
        shiryou: data.shiryou ? '郵送希望' : '(なし)', // v0275：資料請求の有無
        shindan: data.shindan || '(なし)',
        elapsed_sec: Math.round(data.elapsedMs / 1000)
      };
      gtag('event', 'contact_submit', cvParams);   // ★これが正しい送信数
      gtag('event', 'generate_lead', cvParams);    // 既存の広告連携のため維持
    }
  } catch (err) {
    msg.className = 'ng';
    msg.textContent = '送信に失敗しました。お手数ですがお電話（<?= h(SITE['tel']) ?>）またはLINEでご連絡ください。';
  } finally {
    btn.disabled = false; btn.textContent = '送信する';
  }
});
</script>

<?php require __DIR__ . '/../includes/footer.php'; ?>
