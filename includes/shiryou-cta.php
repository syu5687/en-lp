<?php
/**
 * 資料請求CTA（全ページ共用）
 * フォームで「資料請求（無料）」を選んで送信すると、自動返信メールに
 * PDF2冊のダウンロードリンクが届く（worker v0012〜）。
 */
?>
<section class="shiryou-cta" aria-labelledby="shiryou-title">
  <div class="shiryou-cta__inner">
    <p class="shiryou-cta__eyebrow">FREE GUIDEBOOK</p>
    <h2 id="shiryou-title" class="shiryou-cta__title">無料ガイドブックをお届けします</h2>
    <p class="shiryou-cta__lead">検討中の方のための資料を2冊ご用意しました。フォームからご請求いただくと、<strong>自動返信メールですぐにPDFをお届け</strong>します（無料・こちらから営業のご連絡はいたしません）。</p>
    <div class="shiryou-cta__grid">
      <div class="shiryou-book">
        <p class="shiryou-book__badge">墓じまいを考え始めた方に</p>
        <p class="shiryou-book__name">墓じまい完全ガイド<br>鹿児島・福岡版</p>
        <ul class="shiryou-book__list">
          <li>費用の内訳と、金額が変わる条件</li>
          <li>改葬許可の5ステップ（窓口案内つき）</li>
          <li>菩提寺への切り出し方・会話例</li>
          <li>業者見積りチェック12項目</li>
        </ul>
      </div>
      <div class="shiryou-book">
        <p class="shiryou-book__badge">海洋散骨を検討中の方に</p>
        <p class="shiryou-book__name">海洋散骨で後悔しないための<br>チェックリスト</p>
        <ul class="shiryou-book__list">
          <li>業者選び 7つのチェック項目</li>
          <li>委託・合同・貸切の選び方と料金</li>
          <li>当日の流れ・持ち物・服装</li>
          <li>家族の同意を得る話し合いガイド</li>
        </ul>
      </div>
    </div>
    <p class="shiryou-cta__btnwrap">
      <a class="shiryou-cta__btn" href="/contact/?service=<?= rawurlencode('資料請求（無料）') ?>">無料で資料を受け取る（メールで即お届け）</a>
    </p>
    <p class="shiryou-cta__note">※ お名前とメールアドレスだけでご請求いただけます。</p>
  </div>
</section>
<style>
.shiryou-cta{background:linear-gradient(135deg,#f6efdd,#fbf7ec);padding:46px 20px;border-top:1px solid #eadfc4;border-bottom:1px solid #eadfc4}
.shiryou-cta__inner{max-width:880px;margin:0 auto;text-align:center}
.shiryou-cta__eyebrow{font-size:.75rem;letter-spacing:.26em;color:#a8802f;font-weight:700;margin:0 0 8px}
.shiryou-cta__title{font-size:1.45rem;color:#0a3852;margin:0 0 10px;line-height:1.5}
.shiryou-cta__lead{font-size:.93rem;color:#3d4d55;line-height:1.9;max-width:640px;margin:0 auto 24px}
.shiryou-cta__grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:16px;max-width:760px;margin:0 auto}
.shiryou-book{background:#fff;border:1px solid #e3d9c0;border-radius:14px;padding:20px 22px;text-align:left;box-shadow:0 6px 20px rgba(90,70,20,.08)}
.shiryou-book__badge{display:inline-block;background:#0f4d70;color:#fff;font-size:.72rem;font-weight:700;border-radius:999px;padding:3px 12px;margin:0 0 10px}
.shiryou-book__name{font-size:1.05rem;font-weight:700;color:#0a3852;line-height:1.55;margin:0 0 10px}
.shiryou-book__list{margin:0;padding-left:1.2em;font-size:.85rem;color:#3d4d55;line-height:1.85}
.shiryou-cta__btnwrap{margin:24px 0 0}
.shiryou-cta__btn{display:inline-block;background:#c9822a;color:#fff;font-weight:700;font-size:1rem;padding:15px 34px;border-radius:999px;text-decoration:none;box-shadow:0 6px 18px rgba(160,100,20,.28);transition:.2s}
.shiryou-cta__btn:hover{filter:brightness(1.08);color:#fff;transform:translateY(-1px)}
.shiryou-cta__note{font-size:.78rem;color:#8a7a55;margin:10px 0 0}
@media(max-width:600px){.shiryou-cta{padding:38px 16px}.shiryou-cta__title{font-size:1.25rem}}
</style>
