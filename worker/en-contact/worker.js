/**
 * @version v0006 | 2026-08-26 | en1150.co.jp お問い合わせフォーム送信Worker（run.appオリジン削除） | Cloudflare Workers
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
  BREVO_LIST_ID: null,                    // コンタクト登録する場合のみリストID
  MONITOR_TO: ["info@en1150.co.jp", "mk@lu-m.co.jp"], // 毎日の稼働確認メール宛先（複数可）
  MONITOR_SUBJECT: "【自動稼働確認】en1150 お問い合わせフォーム 正常稼働中",
  FORM_NAME: "en1150.co.jp お問い合わせフォーム",
  FORM_URL: "https://en1150.co.jp/contact/",
  // メール本文に必ず出す基本項目（キー: 表示ラベル）。フォームの name 属性に合わせる。
  FIELDS: { name: "お名前", kana: "ふりがな", email: "メール", tel: "電話", category: "お問い合わせ種別", shindan: "診断結果（供養の選び方）" },
  REQUIRED: ["name", "email", "message"]  // 最低限の必須チェック
};

var BREVO_EMAIL = "https://api.brevo.com/v3/smtp/email";
var BREVO_CONTACT = "https://api.brevo.com/v3/contacts";

export default {
  async fetch(request, env) {
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

      for (const k of CONFIG.REQUIRED) if (!d[k]) return json({ ok: false, error: `missing ${k}` }, 400);

      const esc = (s) => String(s ?? "").replace(/[<>&]/g, (c) => ({ "<": "&lt;", ">": "&gt;", "&": "&amp;" }[c]));
      const validEmail = (e) => /^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(String(e || ""));
      const emailOk = validEmail(d.email);   // 不正なメールでも担当者通知だけは必ず届くようにする
      const sender = { name: CONFIG.FROM_NAME, email: CONFIG.FROM_EMAIL };

      // 基本項目テーブル
      let rows = "";
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
        subject: `${CONFIG.SUBJECT_PREFIX}${esc(d.name || "")}様${d.category ? "（" + esc(d.category) + "）" : ""}`,
        htmlContent: adminHtml,
        replyTo: emailOk ? { email: d.email, name: d.name } : undefined
      };
      if (CONFIG.CC.length) adminBody.cc = CONFIG.CC.map((e) => ({ email: e }));
      if (CONFIG.BCC.length) adminBody.bcc = CONFIG.BCC.map((e) => ({ email: e }));

      const adminRes = await fetch(BREVO_EMAIL, { method: "POST", headers: { "api-key": env.BREVO_API_KEY, "Content-Type": "application/json", "accept": "application/json" }, body: JSON.stringify(adminBody) });
      const adminResult = await adminRes.json().catch(() => ({}));

      let autoReplyOk = null;
      if (CONFIG.AUTO_REPLY && emailOk) {
        const custHtml = `
          <div style="font-family:sans-serif;max-width:640px;margin:0 auto;padding:20px;color:#222;line-height:1.8;">
            <p>${esc(d.name || "")} 様</p>
            <p>この度はお問い合わせいただきありがとうございます。<br>以下の内容で承りました。担当者より改めてご連絡いたします。</p>
            <table style="width:100%;border-collapse:collapse;font-size:14px;">${rows}</table>
            ${detail}
            ${srcCust}
            <p style="margin-top:16px;font-size:13px;color:#777;">${esc(CONFIG.AUTO_REPLY_NOTE)}</p>
          </div>`;
        const cr = await fetch(BREVO_EMAIL, { method: "POST", headers: { "api-key": env.BREVO_API_KEY, "Content-Type": "application/json", "accept": "application/json" }, body: JSON.stringify({ sender, to: [{ email: d.email, name: d.name }], subject: CONFIG.AUTO_REPLY_SUBJECT, htmlContent: custHtml }) });
        autoReplyOk = cr.ok;
      }

      if (CONFIG.BREVO_LIST_ID && d.email) {
        await fetch(BREVO_CONTACT, { method: "POST", headers: { "api-key": env.BREVO_API_KEY, "Content-Type": "application/json", "accept": "application/json" }, body: JSON.stringify({ email: d.email, attributes: { NOM: d.name, SMS: d.tel }, listIds: [Number(CONFIG.BREVO_LIST_ID)], updateEnabled: true }) });
      }

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
  }
};
