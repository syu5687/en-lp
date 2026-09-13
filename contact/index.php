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
      <label>ふりがな
        <input type="text" name="kana">
      </label>
      <label>メールアドレス <span class="req">必須</span>
        <input type="email" name="email" required>
      </label>
      <label>電話番号
        <input type="tel" name="tel" inputmode="tel">
      </label>
      <div class="contact-attrs">
        <label>お住まい（任意）
          <select name="pref">
            <option value="">選択しない</option>
            <?php $prefs = ['北海道','青森県','岩手県','宮城県','秋田県','山形県','福島県','茨城県','栃木県','群馬県','埼玉県','千葉県','東京都','神奈川県','新潟県','富山県','石川県','福井県','山梨県','長野県','岐阜県','静岡県','愛知県','三重県','滋賀県','京都府','大阪府','兵庫県','奈良県','和歌山県','鳥取県','島根県','岡山県','広島県','山口県','徳島県','香川県','愛媛県','高知県','福岡県','佐賀県','長崎県','熊本県','大分県','宮崎県','鹿児島県','沖縄県'];
              foreach ($prefs as $pf): ?><option value="<?= h($pf) ?>"><?= h($pf) ?></option><?php endforeach; ?>
          </select>
        </label>
        <label>ご年代（任意）
          <select name="age_group">
            <option value="">選択しない</option>
            <?php foreach (['40代以下','50代','60代','70代','80代以上'] as $ag): ?><option value="<?= h($ag) ?>"><?= h($ag) ?></option><?php endforeach; ?>
          </select>
        </label>
        <label>性別（任意）
          <select name="gender">
            <option value="">回答しない</option>
            <option value="女性">女性</option>
            <option value="男性">男性</option>
            <option value="その他">その他</option>
          </select>
        </label>
      </div>
      <p style="font-size:.78rem;color:var(--text-light);margin:-8px 0 0">※ 任意項目は、サービス改善のための統計にのみ利用します（<a href="/privacy/" target="_blank" rel="noopener" style="color:var(--green);text-decoration:underline">プライバシーポリシー</a>）。</p>
      <label>お問い合わせ種別
        <select name="category">
          <option value="">選択してください</option>
          <option value="資料請求（無料）">資料請求（無料・詳しい資料を郵送でお届け）</option>
          <?php foreach (SERVICES as $s): ?>
            <option value="<?= h($s['title']) ?>"><?= h($s['title']) ?></option>
          <?php endforeach; ?>
          <option value="その他">その他</option>
        </select>
      </label>
      <fieldset id="guide-box">
        <legend class="gb-badge">無料プレゼント</legend>
        <?php /* v0259：お届け方法を下で選べるようにしたので、ここで「PDF」と断定しない */ ?>
        <p class="gb-title">📘 無料ガイドブックも一緒に受け取れます</p>
        <p class="gb-sub">チェックすると、PDF（メール）か、詳しい資料の郵送でお届けします。どのご相談と一緒でもOK・無料です。</p>
        <label class="gb-item"><input type="checkbox" name="guide_hakajimai" value="1"><span><b>墓じまい完全ガイド 鹿児島・福岡版</b>（全10ページ）<small>費用の内訳・改葬許可の5ステップ・菩提寺への切り出し方</small></span></label>
        <label class="gb-item"><input type="checkbox" name="guide_sankotsu" value="1"><span><b>海洋散骨で後悔しないためのチェックリスト</b>（全9ページ）<small>業者選び7項目・委託/合同/貸切の選び方・当日の流れ</small></span></label>
      </fieldset>

      <?php /* v0259：お届け方法を明示的な選択にした。
               これまで住所欄の表示条件が「種別セレクト = 資料請求（無料）」に紐づいていたため、
               ガイドブックにチェックを入れたまま種別を別のサービスに変えると住所欄が消え、
               冊子の送り先が分からないまま送信される事故が起きていた（2026年9月の実例あり）。
               冊子を郵送する必要があるかどうかを決めているのはガイドブックのチェックなので、
               表示条件をそちらに移し、PDFか冊子かはご本人に選んでいただく形にする。 */ ?>
      <div id="delivery-box" hidden>
        <p class="dlv-title">ガイドブックのお届け方法 <span class="req">必須</span></p>
        <label class="dlv-item"><input type="radio" name="delivery" value="PDF（メール）" checked><span><b>PDFをメールで受け取る</b><small>送信後の自動返信メールですぐに届きます</small></span></label>
        <label class="dlv-item"><input type="radio" name="delivery" value="冊子（郵送）"><span><b>詳しい資料を郵送で受け取る</b><small>無料でお送りします。お届け先のご入力が必要です</small></span></label>
      </div>

      <div id="post-addr" hidden>
        <p id="post-addr-title" style="font-size:.85rem;color:#8a6a2a;font-weight:700;margin:0 0 4px">詳しい資料の郵送をご希望のため、お届け先をご入力ください</p>
        <label>郵便番号 <span class="req">必須</span>
          <input type="text" name="zip" inputmode="numeric" autocomplete="postal-code" placeholder="890-0000" maxlength="8">
          <span id="zip-note" style="font-weight:400;font-size:.78rem;color:var(--text-light)">入力すると住所が自動で入ります</span>
        </label>
        <label>ご住所（お届け先） <span class="req">必須</span>
          <input type="text" name="addr" autocomplete="street-address" placeholder="鹿児島県鹿児島市〇〇町1-2-3 〇〇マンション101">
          <span id="addr-note" style="font-weight:400;font-size:.78rem;color:var(--text-light)">郵便番号を入れると自動で入ります。続けて番地・建物名までご入力ください。</span>
        </label>
      </div>

      <label id="goudou-date-field" hidden>合同海洋散骨 ご希望日
        <input type="date" name="goudou_date">
        <span style="font-weight:400;font-size:.8rem;color:var(--text-light)">実施予定日からお選びいただいた日付です。変更も可能です。</span>
      </label>
      <label>お問い合わせ内容 <span class="req">必須</span>
        <textarea name="message" rows="6" required></textarea>
      </label>
      <label class="contact-consent">
        <input type="checkbox" name="consent" value="1" required>
        <span><a href="/privacy/" target="_blank" rel="noopener">プライバシーポリシー</a>に同意します</span>
      </label>
      <p style="font-size:.82rem;color:var(--text-light);margin:-6px 0 0">送信後、受付確認の自動返信メールが届きます。営業のご連絡はいたしませんので、ご安心ください。</p>
      <button type="submit" class="btn" id="submit-btn">送信する</button>
      <div id="form-msg" role="status" aria-live="polite"></div>
    </form>

    <p style="margin-top:24px;font-size:.9rem;color:var(--text-light)">
      お電話でも承ります：<a href="tel:<?= h(SITE['tel']) ?>" style="color:var(--green);font-weight:700"><?= h(SITE['tel']) ?></a>（<?= h(SITE['hours_jp']) ?>）
    </p>
  </div>
</main>

<style>
/* v0259：ガイドブックのお届け方法。既存の .gb-item と同じ指定の強さで書く
   （.contact-form label が flex-direction:column を当てているため、行方向を明示する） */
#delivery-box{border:1px solid #cfe0e8;border-radius:10px;padding:14px 16px;background:#f7fbfc;margin-top:-4px}
#delivery-box .dlv-title{font-weight:700;font-size:.92rem;color:var(--green-mid);margin:0 0 10px;display:flex;align-items:center;gap:8px}
#delivery-box .dlv-item{display:flex;flex-direction:row !important;align-items:flex-start;gap:10px;background:#fff;border:1px solid #d9e6ec;border-radius:10px;padding:10px 14px;margin-bottom:8px;cursor:pointer;font-weight:400 !important}
#delivery-box .dlv-item:last-child{margin-bottom:0}
#delivery-box .dlv-item:hover{border-color:#15709e}
#delivery-box .dlv-item input{width:18px;height:18px;margin-top:3px;padding:0 !important;flex:none;accent-color:#15709e}
#delivery-box .dlv-item b{color:var(--green-mid);font-size:.92rem}
#delivery-box .dlv-item small{display:block;font-size:.76rem;color:var(--text-light);margin-top:2px;line-height:1.7}
#post-addr{border:1px solid #eadfc4;border-radius:10px;padding:14px 16px;background:#fffdf9;display:flex;flex-direction:column;gap:10px}

.contact-form{display:flex;flex-direction:column;gap:18px;background:var(--white);border:1px solid var(--border);border-radius:var(--radius);padding:28px}
.contact-form label{display:flex;flex-direction:column;gap:8px;font-weight:600;font-size:.9rem}
.contact-form label[hidden]{display:none !important} /* 合同散骨ご希望日欄は日付指定の遷移時のみ表示 */
.contact-attrs{display:grid;grid-template-columns:repeat(3,1fr);gap:14px}
.contact-attrs label{display:flex;flex-direction:column;gap:8px;font-weight:600;font-size:.9rem}
@media(max-width:560px){.contact-attrs{grid-template-columns:1fr}}
.contact-form input,.contact-form select,.contact-form textarea{padding:12px;border:1px solid var(--border);border-radius:8px;font-size:1rem;font-family:inherit}
.contact-form .req{display:inline-block;background:var(--green);color:#fff;font-size:.7rem;padding:2px 8px;border-radius:4px;margin-left:6px;align-self:flex-start}
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
  // 種別セレクトに反映（一致する選択肢がなければ追加して選択）
  const sel = form.querySelector('select[name="category"]');
  let opt = [...sel.options].find(o => o.value === svc)
         || [...sel.options].find(o => o.value && (svc.indexOf(o.value) === 0 || o.value.indexOf(svc) === 0));
  if (!opt) {
    opt = new Option(svc, svc);
    sel.insertBefore(opt, sel.querySelector('option[value="その他"]'));
  }
  sel.value = opt.value;
})();
// 資料請求：種別で選ばれたら、内容欄を自動で埋めて手間をなくす
(function () {
  const sel = form.querySelector('select[name="category"]');
  const ta  = form.querySelector('textarea[name="message"]');
  const g1  = form.querySelector('input[name="guide_hakajimai"]');
  const g2  = form.querySelector('input[name="guide_sankotsu"]');
  const FILL = '資料請求：詳しい資料の郵送を希望します。';
  const addrBox = document.getElementById('post-addr');
  const addrTitle = document.getElementById('post-addr-title');
  const zipInp  = form.querySelector('input[name="zip"]');
  const addrInp = form.querySelector('input[name="addr"]');
  const dlvBox  = document.getElementById('delivery-box');
  const dlvPost = form.querySelector('input[name="delivery"][value="冊子（郵送）"]');
  const prefSel = form.querySelector('select[name="pref"]');

  /* v0259：表示条件を整理した。
     ・お届け方法  … ガイドブックが1つでもチェックされていたら出す
     ・住所欄      … お届け方法で「冊子（郵送）」が選ばれたときだけ出す
     種別セレクトは一切条件に使わない。種別を変えても住所欄は消えない。
     hidden の要素に required が残るとChromeは検証に失敗してもエラーを出せず、
     送信が黙って止まるため、hidden と required は必ずセットで切り替える。 */
  const setField = (el, show) => {
    if (!el) return;
    el.required = !!show;
    if (!show) el.value = '';   // 非表示の値を送らない
  };
  const dlvPdf = form.querySelector('input[name="delivery"][value="PDF（メール）"]');
  let dlvTouched = false;   // ご本人がお届け方法を選んだか
  const apply = () => {
    const isShiryou  = sel.value === '資料請求（無料）';
    if (isShiryou) {
      if (g1) g1.checked = true;
      if (g2) g2.checked = true;
      if (!ta.value.trim()) ta.value = FILL;
      // 種別「資料請求（無料・冊子を郵送でお届け）」は冊子の郵送を約束しているので、
      // ご本人がまだ選んでいなければ郵送を初期値にする。
      if (!dlvTouched && dlvPost) dlvPost.checked = true;
    }
    if (!isShiryou && ta.value === FILL) ta.value = '';

    // 自動チェックのあとに判定する（先に読むと初回表示で漏れる）
    const wantsGuide = !!((g1 && g1.checked) || (g2 && g2.checked));
    // 資料を希望していないなら、お届け方法は送らない（PDFに戻す）
    if (!wantsGuide && !dlvTouched && dlvPdf) dlvPdf.checked = true;

    // お届け方法：資料を1つでも希望していたら選んでいただく
    if (dlvBox) dlvBox.hidden = !wantsGuide;

    /* v0260：お届け先の表示条件（2026-09-13 syu確認）
         ・種別が「資料請求（無料）」 … PDF／冊子のどちらを選んでいても必ず表示する
                                        （冊子を郵送する前提のサービスのため）
         ・それ以外の種別            … ガイドブックを希望し、かつ「冊子（郵送）」を
                                        選んだときだけ表示する */
    const needsAddr = isShiryou || (wantsGuide && !!(dlvPost && dlvPost.checked));
    if (addrTitle) {
      addrTitle.textContent = (dlvPost && dlvPost.checked)
        ? '詳しい資料の郵送をご希望のため、お届け先をご入力ください'
        : '資料のお届け先をご入力ください';
    }
    if (addrBox) {
      const wasHidden = addrBox.hidden;
      addrBox.hidden = !needsAddr;
      setField(zipInp,  needsAddr);
      setField(addrInp, needsAddr);
      /* v0260：ここで「お住まい」の都道府県を住所欄に入れるのをやめた。
         2026年9月13日の不具合報告の原因がこれだった。
         ・required は「空でないこと」しか見ないため、「鹿児島県」だけで送信が通ってしまう
         ・郵便番号の自動入力が「住所欄が空のときだけ上書き」の条件だったため、
           先に県名が入っていると自動入力が効かない
         県名は郵便番号から自動で入るので、先に入れておく必要はない。 */
      if (needsAddr && wasHidden) refreshAddr();
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
  sel.addEventListener('change', apply);
  // v0259：住所欄の表示を決めているのはこちら側なので、必ず監視する
  if (g1) g1.addEventListener('change', apply);
  if (g2) g2.addEventListener('change', apply);
  form.querySelectorAll('input[name="delivery"]').forEach(r => r.addEventListener('change', () => { dlvTouched = true; apply(); }));
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
  if (!form.checkValidity()) { form.reportValidity(); return; }
  const data = Object.fromEntries(new FormData(form).entries());
  if (!/^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(data.email || '')) {
    msg.className = 'ng';
    msg.textContent = 'メールアドレスの形式をご確認ください。';
    return;
  }
  /* v0260：画面に出ていた項目だけを送る（非表示の div の中でも FormData には入るため）。
     資料請求はPDFを選んでいてもお届け先を伺うので、delivery の値では判定しない。 */
  if (document.getElementById('delivery-box').hidden) delete data.delivery;
  if (document.getElementById('post-addr').hidden) { delete data.zip; delete data.addr; }
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
    form.reset();
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
