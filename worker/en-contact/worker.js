/**
 * @version v0011 | 2026-08-27 | en1150.co.jp お問い合わせフォーム送信Worker（管理画面からの返信送信 /reply を追加） | Cloudflare Workers
 *
 * /contact/ フォームからのJSONを受け取り、Brevoで
 *   ①担当者へ通知 ②お客様へ受付確認(自動返信)。
 * さらに毎日1回、稼働確認メールを送る(Cron Trigger)。
 *
 * 秘密情報は BREVO_API_KEY のみ： npx wrangler secret put BREVO_API_KEY
 * 通知先・送信元・文言はこの CONFIG で管理（vars 不要）。
 */

var CONFIG = {
  TO: "info@en1150.co.jp",                // 担当者宛
  CC: ["mk@lu-m.co.jp"],                  // CC（複数可）
  BCC: [],                                // BCC（複数可）
  FROM_NAME: "有限会社 縁",
  FROM_EMAIL: "noreply@nfz33.com",        // ★ Brevo認証済みドメインのアドレス
  SUBJECT_PREFIX: "【お問い合わせ】",
  ALLOWED_ORIGINS: [                      // 受付を許可するオリジン（設置元のみ）
    "https://en1150.co.jp",
    "https://www.en1150.co.jp"
  ],
  AUTO_REPLY: true,                       // お客様への受付確認メール
  AUTO_REPLY_SUBJECT: "【有限会社 縁】お問い合わせを承りました",
  AUTO_REPLY_NOTE: "※このメールは自動送信用メールアドレスです。返信はできません。お急ぎの場合はお電話（099-801-3637）でご連絡ください。",
  // ---- 資料請求（無料）: 種別に「資料請求」を含む送信は、自動返信でPDFリンクをお届けする ----
  SHIRYOU_MATCH: "資料請求",
  SHIRYOU_SUBJECT: "【有限会社 縁】ご請求の資料（無料PDF）をお届けします",
  SHIRYOU_LINKS: [
    { label: "墓じまい完全ガイド 鹿児島・福岡版（PDF・全10ページ）",           url: "https://en1150.co.jp/assets/docs/enshiryou-k7x2/hakajimai-guide.pdf" },
    { label: "海洋散骨で後悔しないためのチェックリスト（PDF・全9ページ）",   url: "https://en1150.co.jp/assets/docs/enshiryou-k7x2/sankotsu-checklist.pdf" }
  ],
  SHIRYOU_LINE_URL: "https://line.me/R/ti/p/%40bkx9825r",
  BREVO_LIST_ID: null,                    // コンタクト登録する場合のみリストID
  LOG_URL: "https://en1150.co.jp/api/inquiry-log.php",   // 受信内容のDB保存先（管理画面の解析用）
  LOG_SECRET: "fd66345cdcff8de89a8775c9ccb7666eb3e82a0fb129d887899911df8a2c65f2", // サイト側と共有のHMAC鍵
  MONITOR_TO: ["info@en1150.co.jp", "mk@lu-m.co.jp"], // 毎日の稼働確認メール宛先（複数可）
  MONITOR_SUBJECT: "【自動稼働確認】en1150 お問い合わせフォーム 正常稼働中",
  STALE_URL: "https://en1150.co.jp/api/inquiry-stale.php", // 3日以上未対応の案件一覧API
  STALE_DAYS: 3,                                           // この日数ステータスが動かなければ通知
  STALE_TO: ["info@en1150.co.jp", "mk@lu-m.co.jp"],        // 未対応通知の宛先
  ADMIN_INQUIRIES_URL: "https://en1150.co.jp/admin/inquiries/",
  REPLY_SUBJECT_NOTE: "",                                  // 返信件名に付ける接頭辞（不要なら空）
  REPLY_REPLYTO: "info@en1150.co.jp",                      // お客様が返信したときの届き先
  REPLY_BCC: ["info@en1150.co.jp"],                        // 控えのBCC（送信履歴をメールでも残す）
  FORM_NAME: "en1150.co.jp お問い合わせフォーム",
  FORM_URL: "https://en1150.co.jp/contact/",
  // メール本文に必ず出す基本項目（キー: 表示ラベル）。フォームの name 属性に合わせる。
  FIELDS: { name: "お名前", kana: "ふりがな", email: "メール", tel: "電話", pref: "お住まい（都道府県）", age_group: "ご年代", gender: "性別", category: "お問い合わせ種別", guides: "ご希望の資料", zip: "郵便番号", addr: "ご住所（資料お届け先）", goudou_date: "合同海洋散骨 ご希望日", shindan: "診断結果（供養の選び方）" },
  REQUIRED: ["name", "email", "message"],  // 最低限の必須チェック

  // ---- 営業メールフィルタ ----
  // BLOCK_WORDS: 本文・名前・会社系欄にこの語が含まれたら即ブロック（静かに破棄）。営業が来るたびにここへ追加。
  BLOCK_WORDS: ["キーマンノック", "AIコールセンター", "timerex.net", "テレアポ", "アポ代行", "営業代行", "商談を設定", "アポイントの獲得"],
  // BLOCK_EMAILS / BLOCK_DOMAINS: 差出人メールでのブロック（例: "spam@example.com" / "example.com"）
  BLOCK_EMAILS: [],
  BLOCK_DOMAINS: [],
  // 営業らしさのスコア判定に使う語（1グループ=1点）。供養のお客様が使いそうな語は入れないこと。
  SALES_WORDS: [
    "テレアポ", "アポイント", "商談", "営業マン", "セールス",
    "集客", "マーケティング", "広告運用", "リスティング", "SEO対策", "MEO",
    "コンサルティング", "採用支援", "人材紹介", "求人広告",
    "補助金", "助成金", "LP制作", "ホームページ制作", "WEB制作", "システム開発", "DX支援", "AI活用",
    "オンラインでお話", "お打ち合わせの候補", "ご都合のよい日時", "情報収集レベル",
  ],
};

var BREVO_EMAIL = "https://api.brevo.com/v3/smtp/email";
var BREVO_CONTACT = "https://api.brevo.com/v3/contacts";

/** 受信内容を en1150.co.jp のDBへ転送（HMAC-SHA256署名付き） */
async function logInquiry(d) {
  const body = JSON.stringify({
    name: d.name || "", kana: d.kana || "", email: d.email || "", tel: d.tel || "",
    category: d.category || "", message: d.message || "", goudou_date: d.goudou_date || "",
    shindan: d.shindan || "", pref: d.pref || "", age_group: d.age_group || "", gender: d.gender || "",
    source: d.source || "",
  });
  const key = await crypto.subtle.importKey(
    "raw", new TextEncoder().encode(CONFIG.LOG_SECRET),
    { name: "HMAC", hash: "SHA-256" }, false, ["sign"]
  );
  const mac = await crypto.subtle.sign("HMAC", key, new TextEncoder().encode(body));
  const sig = [...new Uint8Array(mac)].map((b) => b.toString(16).padStart(2, "0")).join("");
  await fetch(CONFIG.LOG_URL, {
    method: "POST",
    headers: { "Content-Type": "application/json", "X-Signature": sig },
    body,
  });
}

export default {
  async fetch(request, env, ctx) {
    const origin = request.headers.get("Origin") || "";
    const allowOrigin = CONFIG.ALLOWED_ORIGINS.includes(origin) ? origin : CONFIG.ALLOWED_ORIGINS[0];
    const cors = {
      "Access-Control-Allow-Origin": allowOrigin,
      "Access-Control-Allow-Methods": "POST, OPTIONS",
      "Access-Control-Allow-Headers": "Content-Type",
      "Vary": "Origin"
    };
    if (request.method === "OPTIONS") return new Response(null, { headers: cors });
    if (request.method !== "POST") return new Response("Method not allowed", { status: 405, headers: cors });
    const json = (o, s = 200) => new Response(JSON.stringify(o), { status: s, headers: { "Content-Type": "application/json", ...cors } });

    // ---- 管理画面からの返信送信（サーバー間・HMAC署名で認証。Originチェックは行わない） ----
    if (new URL(request.url).pathname === "/reply") {
      try {
        if (!env.BREVO_API_KEY) return json({ ok: false, error: "BREVO_API_KEY 未設定" }, 500);
        const raw = await request.text();
        if (!raw || raw.length > 32768) return json({ ok: false, error: "bad payload" }, 400);
        const sig = request.headers.get("X-Signature") || "";
        const key = await crypto.subtle.importKey(
          "raw", new TextEncoder().encode(CONFIG.LOG_SECRET),
          { name: "HMAC", hash: "SHA-256" }, false, ["sign"]
        );
        const mac = await crypto.subtle.sign("HMAC", key, new TextEncoder().encode(raw));
        const expect = [...new Uint8Array(mac)].map((b) => b.toString(16).padStart(2, "0")).join("");
        if (sig !== expect) return json({ ok: false, error: "bad signature" }, 403);

        const r = JSON.parse(raw);
        const esc2 = (s) => String(s ?? "").replace(/[<>&]/g, (c) => ({ "<": "&lt;", ">": "&gt;", "&": "&amp;" }[c]));
        if (!/^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(String(r.to || ""))) return json({ ok: false, error: "bad to" }, 400);
        if (!r.subject || !r.body) return json({ ok: false, error: "missing subject/body" }, 400);

        const replyHtml = `<div style="font-family:sans-serif;max-width:640px;margin:0 auto;padding:20px;color:#222;line-height:1.9;font-size:15px;white-space:pre-wrap;">${esc2(r.body)}</div>`;
        const mail = {
          sender: { name: CONFIG.FROM_NAME, email: CONFIG.FROM_EMAIL },
          to: [{ email: r.to, name: r.toName || undefined }],
          subject: `${CONFIG.REPLY_SUBJECT_NOTE}${r.subject}`,
          htmlContent: replyHtml,
          textContent: String(r.body),
          replyTo: { email: CONFIG.REPLY_REPLYTO, name: CONFIG.FROM_NAME },
        };
        if (CONFIG.REPLY_BCC.length) mail.bcc = CONFIG.REPLY_BCC.map((e) => ({ email: e }));
        const br = await fetch(BREVO_EMAIL, { method: "POST", headers: { "api-key": env.BREVO_API_KEY, "Content-Type": "application/json", "accept": "application/json" }, body: JSON.stringify(mail) });
        const bres = await br.json().catch(() => ({}));
        return json({ ok: br.ok, ...bres }, br.ok ? 200 : 502);
      } catch (e) {
        return json({ ok: false, error: e.message }, 500);
      }
    }

    try {
      if (!env.BREVO_API_KEY) return json({ ok: false, error: "BREVO_API_KEY 未設定" }, 500);

      // ---- ボット・不正送信対策 ----
      // ① Origin必須＋許可リスト一致（Originを送らない直接POSTや他サイトからの送信を拒否）
      if (!CONFIG.ALLOWED_ORIGINS.includes(origin)) return json({ ok: false, error: "forbidden origin" }, 403);
      const d = await request.json();
      // ② ハニーポット：見えない欄に入力があればボット（本物っぽく応答して静かに破棄）
      if (d.website) return json({ ok: true });
      // ③ 表示から3秒未満の即時送信はボットとみなす
      if (typeof d.elapsedMs === "number" && d.elapsedMs < 3000) return json({ ok: true });
      // ④ 内容の異常な長さを拒否（緩い上限）
      if (String(d.message || "").length > 8000 || String(d.name || "").length > 100) return json({ ok: false, error: "too long" }, 400);

      // ⑤ 営業メールフィルタ
      //    - ブロックリスト該当 → 本物っぽく応答して静かに破棄（メール送信なし・DB保存のみ）
      //    - スコア判定: URL入り本文+営業ワード等で加点。4点以上=破棄 / 2〜3点=件名に【営業？】を付けて自動返信なしで通知
      const hay = [d.message, d.name, d.kana, d.category].map((x) => String(x || "")).join("\n");
      const mailAddr = String(d.email || "").toLowerCase();
      const mailDom = mailAddr.split("@")[1] || "";
      const hardBlock =
        CONFIG.BLOCK_WORDS.some((w) => hay.includes(w)) ||
        CONFIG.BLOCK_EMAILS.includes(mailAddr) ||
        CONFIG.BLOCK_DOMAINS.some((dom) => mailDom === dom || mailDom.endsWith("." + dom));
      let salesScore = 0;
      if (/https?:\/\//.test(String(d.message || ""))) salesScore += 2;          // 本文にURL（一般のお客様はまず貼らない）
      salesScore += CONFIG.SALES_WORDS.filter((w) => hay.includes(w)).length;      // 営業ワード 1語=1点
      if (/株式会社|合同会社|Inc\.|Co\.,/.test(String(d.name || ""))) salesScore += 1; // 名前欄が社名
      const isSpam = hardBlock || salesScore >= 4;
      const isSuspect = !isSpam && salesScore >= 2;
      if (isSpam) {
        // 記録だけ残して破棄（管理画面の受信ログで後から確認・誤判定の救済ができる）
        const spamLog = logInquiry({ ...d, category: "[営業ブロック] " + (d.category || "") }).catch(() => {});
        if (ctx && ctx.waitUntil) ctx.waitUntil(spamLog); else await spamLog;
        return json({ ok: true });
      }

      for (const k of CONFIG.REQUIRED) if (!d[k]) return json({ ok: false, error: `missing ${k}` }, 400);

      const esc = (s) => String(s ?? "").replace(/[<>&]/g, (c) => ({ "<": "&lt;", ">": "&gt;", "&": "&amp;" }[c]));
      const validEmail = (e) => /^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(String(e || ""));
      const emailOk = validEmail(d.email);   // 不正なメールでも担当者通知だけは必ず届くようにする
      const sender = { name: CONFIG.FROM_NAME, email: CONFIG.FROM_EMAIL };

      // 基本項目テーブル
      let rows = "";
      // 資料チェックボックス（guide_*）を表示用の1項目にまとめる
      {
        const gsel = [];
        if (String(d.guide_hakajimai || "") === "1") gsel.push(CONFIG.SHIRYOU_LINKS[0].label);
        if (String(d.guide_sankotsu || "") === "1") gsel.push(CONFIG.SHIRYOU_LINKS[1].label);
        if (gsel.length) d.guides = gsel.join(" ／ ");
      }
      for (const [k, label] of Object.entries(CONFIG.FIELDS)) {
        if (d[k]) rows += `<tr><th style="text-align:left;padding:6px 12px;color:#888;width:32%;">${esc(label)}</th><td style="padding:6px 12px;${k === "name" ? "font-weight:bold;" : ""}">${esc(d[k])}</td></tr>`;
      }
      // お問い合わせ内容（message）
      const detail = d.message
        ? `<div style="background:#f6f2ea;border:1px solid #d8cdb9;border-radius:8px;padding:14px;font-size:14px;white-space:pre-wrap;">${esc(d.message).replace(/\n/g, "<br>")}</div>`
        : "";

      // 送信元フォーム（どのフォームからの問い合わせかを判別）。生URLは出さずアンカー化。
      const srcAdmin = d.source
        ? `<p style="margin-top:22px;padding-top:12px;border-top:1px solid #e5ddcd;font-size:13px;color:#555;">送信元フォーム：<a href="${esc(d.source)}" style="color:#15709e;">${esc(d.formName || d.source)}</a></p>`
        : "";
      const srcCust = d.source
        ? `<p style="margin-top:18px;font-size:13px;color:#777;">お問い合わせページ：<a href="${esc(d.source)}" style="color:#15709e;">${esc(d.formName || "こちら")}</a></p>`
        : "";

      const adminHtml = `
        <div style="font-family:sans-serif;max-width:640px;margin:0 auto;padding:20px;color:#222;">
          <h2 style="color:#15709e;border-bottom:2px solid #15709e;padding-bottom:8px;">お問い合わせを受信しました</h2>
          <table style="width:100%;border-collapse:collapse;font-size:14px;margin-top:12px;">${rows}</table>
          ${detail ? `<h3 style="margin-top:18px;color:#15709e;">お問い合わせ内容</h3>${detail}` : ""}
          ${srcAdmin}
        </div>`;

      const adminBody = {
        sender, to: [{ email: CONFIG.TO }],
        subject: `${isSuspect ? "【営業？】" : ""}${CONFIG.SUBJECT_PREFIX}${esc(d.name || "")}様${d.category ? "（" + esc(d.category) + "）" : ""}`,
        htmlContent: adminHtml,
        replyTo: emailOk ? { email: d.email, name: d.name } : undefined
      };
      if (CONFIG.CC.length) adminBody.cc = CONFIG.CC.map((e) => ({ email: e }));
      if (CONFIG.BCC.length) adminBody.bcc = CONFIG.BCC.map((e) => ({ email: e }));

      const adminRes = await fetch(BREVO_EMAIL, { method: "POST", headers: { "api-key": env.BREVO_API_KEY, "Content-Type": "application/json", "accept": "application/json" }, body: JSON.stringify(adminBody) });
      const adminResult = await adminRes.json().catch(() => ({}));

      let autoReplyOk = null;
      if (CONFIG.AUTO_REPLY && emailOk && !isSuspect) {
        // 送付する資料の決定：チェックボックスが選ばれていればその資料のみ、
        // 種別が「資料請求」でチェックなしなら2冊とも送付
        const wantG1 = String(d.guide_hakajimai || "") === "1";
        const wantG2 = String(d.guide_sankotsu || "") === "1";
        const catShiryou = !!(d.category && String(d.category).includes(CONFIG.SHIRYOU_MATCH));
        const sendLinks = (wantG1 || wantG2)
          ? CONFIG.SHIRYOU_LINKS.filter((_, i) => (i === 0 && wantG1) || (i === 1 && wantG2))
          : (catShiryou ? CONFIG.SHIRYOU_LINKS : []);
        const isShiryou = sendLinks.length > 0;
        const shiryouBlock = isShiryou ? `
            <div style="background:#f6efdd;border-radius:10px;padding:16px 18px;margin:14px 0;">
              <p style="margin:0 0 10px;font-weight:bold;color:#0a3852;">▼ ご請求いただいた資料はこちらからダウンロードできます</p>
              ${sendLinks.map((l) => `<p style="margin:6px 0;"><a href="${l.url}" style="color:#0f4d70;font-weight:bold;">📘 ${esc(l.label)}</a></p>`).join("")}
              <p style="margin:10px 0 0;font-size:12px;color:#8a7a55;">※ リンクはいつでも開けます。印刷してご家族との話し合いにもお使いください。</p>
            </div>
            <p style="font-size:14px;">資料をお読みになって疑問が出てきましたら、このままLINEでお気軽にご相談いただけます（無料・営業のご連絡はいたしません）。<br><a href="${CONFIG.SHIRYOU_LINE_URL}" style="color:#06C755;font-weight:bold;">▶ LINEで相談する</a>　／　お電話 099-801-3637</p>` : "";
        const introText = isShiryou
          ? `この度は資料をご請求いただきありがとうございます。<br>下記リンクからすぐにご覧いただけます。`
          : `この度はお問い合わせいただきありがとうございます。<br>以下の内容で承りました。担当者より改めてご連絡いたします。`;
        const custHtml = `
          <div style="font-family:sans-serif;max-width:640px;margin:0 auto;padding:20px;color:#222;line-height:1.8;">
            <p>${esc(d.name || "")} 様</p>
            <p>${introText}</p>
            ${shiryouBlock}
            <table style="width:100%;border-collapse:collapse;font-size:14px;">${rows}</table>
            ${detail}
            ${srcCust}
            <p style="margin-top:16px;font-size:13px;color:#777;">${esc(CONFIG.AUTO_REPLY_NOTE)}</p>
          </div>`;
        const cr = await fetch(BREVO_EMAIL, { method: "POST", headers: { "api-key": env.BREVO_API_KEY, "Content-Type": "application/json", "accept": "application/json" }, body: JSON.stringify({ sender, to: [{ email: d.email, name: d.name }], subject: isShiryou ? CONFIG.SHIRYOU_SUBJECT : CONFIG.AUTO_REPLY_SUBJECT, htmlContent: custHtml }) });
        autoReplyOk = cr.ok;
      }

      if (CONFIG.BREVO_LIST_ID && d.email) {
        await fetch(BREVO_CONTACT, { method: "POST", headers: { "api-key": env.BREVO_API_KEY, "Content-Type": "application/json", "accept": "application/json" }, body: JSON.stringify({ email: d.email, attributes: { NOM: d.name, SMS: d.tel }, listIds: [Number(CONFIG.BREVO_LIST_ID)], updateEnabled: true }) });
      }

      // 受信内容をサイト側DB（Firestore）へ保存（HMAC署名付き・失敗してもメール送信には影響しない）
      const logP = logInquiry(d).catch(() => {});
      if (ctx && ctx.waitUntil) ctx.waitUntil(logP); else await logP;

      return json({ ok: adminRes.ok, autoReply: autoReplyOk, ...adminResult }, adminRes.ok ? 200 : 500);
    } catch (e) {
      return json({ ok: false, error: e.message }, 500);
    }
  },

  // 毎日の稼働確認（Cron Trigger）。届いていれば Worker＋Brevo は正常。
  async scheduled(event, env, ctx) {
    if (!env.BREVO_API_KEY) return;
    const now = new Date().toISOString();
    const escM = (s) => String(s ?? "").replace(/[<>&]/g, (c) => ({ "<": "&lt;", ">": "&gt;", "&": "&amp;" }[c]));
    const formLink = CONFIG.FORM_URL
      ? `<p style="font-size:13px;color:#555;">対象フォーム：<a href="${escM(CONFIG.FORM_URL)}" style="color:#15709e;">${escM(CONFIG.FORM_NAME || CONFIG.FORM_URL)}</a></p>`
      : "";
    const body = {
      sender: { name: CONFIG.FROM_NAME, email: CONFIG.FROM_EMAIL },
      to: (Array.isArray(CONFIG.MONITOR_TO) ? CONFIG.MONITOR_TO : [CONFIG.MONITOR_TO]).map((e) => ({ email: e })),
      subject: CONFIG.MONITOR_SUBJECT,
      htmlContent: `<div style="font-family:sans-serif;line-height:1.8;color:#222;"><p>フォームのメール送信機能は<b>正常に稼働しています</b>。</p>${formLink}<p>この自動確認メールが毎日届いていれば正常です。届かない日があれば要確認。</p><p style="font-size:12px;color:#888;">自動送信／${now} UTC</p></div>`
    };
    ctx.waitUntil(fetch(BREVO_EMAIL, { method: "POST", headers: { "api-key": env.BREVO_API_KEY, "Content-Type": "application/json", "accept": "application/json" }, body: JSON.stringify(body) }));

    // ---- 3日以上ステータスが動いていない問い合わせの通知 ----
    ctx.waitUntil((async () => {
      try {
        const reqBody = JSON.stringify({ days: CONFIG.STALE_DAYS });
        const key = await crypto.subtle.importKey(
          "raw", new TextEncoder().encode(CONFIG.LOG_SECRET),
          { name: "HMAC", hash: "SHA-256" }, false, ["sign"]
        );
        const mac = await crypto.subtle.sign("HMAC", key, new TextEncoder().encode(reqBody));
        const sig = [...new Uint8Array(mac)].map((b) => b.toString(16).padStart(2, "0")).join("");
        const res = await fetch(CONFIG.STALE_URL, {
          method: "POST",
          headers: { "Content-Type": "application/json", "X-Signature": sig },
          body: reqBody,
        });
        const j = await res.json().catch(() => null);
        if (!j || !j.ok || !j.count) return;   // 対象なし・エラー時は何も送らない

        let trows = "";
        for (const it of j.items.slice(0, 30)) {
          trows += `<tr>
            <td style="padding:6px 10px;border-bottom:1px solid #eee;font-weight:bold;">${escM(it.name)} 様</td>
            <td style="padding:6px 10px;border-bottom:1px solid #eee;">${escM(it.category || "—")}</td>
            <td style="padding:6px 10px;border-bottom:1px solid #eee;white-space:nowrap;">${escM((it.received_at || "").slice(0, 16))}</td>
            <td style="padding:6px 10px;border-bottom:1px solid #eee;">${escM(it.status)}${it.staff ? "（" + escM(it.staff) + "）" : ""}</td>
          </tr>`;
        }
        const alertBody = {
          sender: { name: CONFIG.FROM_NAME, email: CONFIG.FROM_EMAIL },
          to: (Array.isArray(CONFIG.STALE_TO) ? CONFIG.STALE_TO : [CONFIG.STALE_TO]).map((e) => ({ email: e })),
          subject: `【要対応】お問い合わせ ${j.count}件が${CONFIG.STALE_DAYS}日以上対応されていません`,
          htmlContent: `<div style="font-family:sans-serif;max-width:640px;margin:0 auto;padding:20px;color:#222;line-height:1.7;">
            <h2 style="color:#c0392b;border-bottom:2px solid #c0392b;padding-bottom:8px;">対応が止まっているお問い合わせがあります</h2>
            <p>ステータスが「対応済み」にならないまま<b>${CONFIG.STALE_DAYS}日以上</b>経過したお問い合わせが <b>${j.count}件</b> あります。</p>
            <table style="width:100%;border-collapse:collapse;font-size:14px;">
              <tr><th style="text-align:left;padding:6px 10px;background:#f2f6f8;">お名前</th><th style="text-align:left;padding:6px 10px;background:#f2f6f8;">種別</th><th style="text-align:left;padding:6px 10px;background:#f2f6f8;">受信日時</th><th style="text-align:left;padding:6px 10px;background:#f2f6f8;">状況（担当）</th></tr>
              ${trows}
            </table>
            <p style="margin-top:16px;"><a href="${escM(CONFIG.ADMIN_INQUIRIES_URL)}" style="color:#15709e;font-weight:bold;">→ 管理画面で確認・ステータスを更新する</a></p>
            <p style="font-size:12px;color:#888;">対応後は一覧のステータスを「対応済み」にすると、この通知は止まります（毎日1回の自動チェック）。</p>
          </div>`,
        };
        await fetch(BREVO_EMAIL, { method: "POST", headers: { "api-key": env.BREVO_API_KEY, "Content-Type": "application/json", "accept": "application/json" }, body: JSON.stringify(alertBody) });
      } catch (e) { /* 通知失敗は握りつぶす（翌日再試行） */ }
    })());
  }
};
