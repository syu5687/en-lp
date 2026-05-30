/**
 * 有限会社 縁 — お問い合わせ送信 Worker（Cloudflare Workers / Service Worker形式）
 * フロント(/contact/)からJSON POSTを受け取り、Resend API でメール送信する。
 *
 * 必要な環境変数（Cloudflareダッシュボード → Worker → 設定 → 変数）:
 *   RESEND_API_KEY  … Resend のAPIキー（Secret）
 *   MAIL_TO         … 送信先（例: info@en1150.co.jp）
 *   MAIL_CC         … CC（例: mk@emanet.jp）任意
 *   MAIL_FROM       … 差出人（Resendで認証済みドメイン 例: noreply@en1150.co.jp）
 *   ALLOW_ORIGIN    … 許可するオリジン（例: https://en1150.co.jp）
 */

addEventListener('fetch', (event) => {
  event.respondWith(handle(event.request));
});

function cors(origin) {
  return {
    'Access-Control-Allow-Origin': origin || '*',
    'Access-Control-Allow-Methods': 'POST, OPTIONS',
    'Access-Control-Allow-Headers': 'Content-Type',
  };
}

async function handle(request) {
  const origin = (typeof ALLOW_ORIGIN !== 'undefined' && ALLOW_ORIGIN) ? ALLOW_ORIGIN : '*';

  if (request.method === 'OPTIONS') {
    return new Response(null, { status: 204, headers: cors(origin) });
  }
  if (request.method !== 'POST') {
    return json({ ok: false, error: 'method' }, 405, origin);
  }

  let d;
  try { d = await request.json(); } catch (e) { return json({ ok: false, error: 'json' }, 400, origin); }

  // バリデーション
  if (!d.name || !d.email || !d.message || !d.consent) {
    return json({ ok: false, error: 'validation' }, 400, origin);
  }

  const esc = (s) => String(s || '').replace(/[<>&]/g, (c) => ({ '<': '&lt;', '>': '&gt;', '&': '&amp;' }[c]));
  const text =
    `お問い合わせがありました。\n\n` +
    `■お名前: ${d.name}\n` +
    `■ふりがな: ${d.kana || '-'}\n` +
    `■メール: ${d.email}\n` +
    `■電話: ${d.tel || '-'}\n` +
    `■種別: ${d.category || '-'}\n` +
    `■内容:\n${d.message}\n`;

  const html =
    `<h2>お問い合わせ</h2>` +
    `<p><b>お名前:</b> ${esc(d.name)}（${esc(d.kana || '-')}）</p>` +
    `<p><b>メール:</b> ${esc(d.email)}</p>` +
    `<p><b>電話:</b> ${esc(d.tel || '-')}</p>` +
    `<p><b>種別:</b> ${esc(d.category || '-')}</p>` +
    `<p><b>内容:</b><br>${esc(d.message).replace(/\n/g, '<br>')}</p>`;

  const payload = {
    from: (typeof MAIL_FROM !== 'undefined' && MAIL_FROM) ? MAIL_FROM : 'onboarding@resend.dev',
    to: [(typeof MAIL_TO !== 'undefined' && MAIL_TO) ? MAIL_TO : 'info@en1150.co.jp'],
    subject: `【お問い合わせ】${d.name} 様（${d.category || 'その他'}）`,
    reply_to: d.email,
    text,
    html,
  };
  if (typeof MAIL_CC !== 'undefined' && MAIL_CC) payload.cc = [MAIL_CC];

  const r = await fetch('https://api.resend.com/emails', {
    method: 'POST',
    headers: {
      'Authorization': `Bearer ${RESEND_API_KEY}`,
      'Content-Type': 'application/json',
    },
    body: JSON.stringify(payload),
  });

  if (!r.ok) {
    const err = await r.text();
    return json({ ok: false, error: 'resend', detail: err }, 502, origin);
  }

  // --- 申込者への自動返信（控え）---
  try {
    const fromAddr = (typeof MAIL_FROM !== 'undefined' && MAIL_FROM) ? MAIL_FROM : 'onboarding@resend.dev';
    const company = (typeof MAIL_COMPANY !== 'undefined' && MAIL_COMPANY) ? MAIL_COMPANY : '有限会社 縁';
    const tel = (typeof MAIL_TEL !== 'undefined' && MAIL_TEL) ? MAIL_TEL : '099-801-3637';
    const autoText =
      `${d.name} 様\n\n` +
      `この度は${company}へお問い合わせいただき、誠にありがとうございます。\n` +
      `以下の内容で受け付けました。担当者より折り返しご連絡いたします。\n\n` +
      `──────────\n` +
      `■種別: ${d.category || '-'}\n` +
      `■内容:\n${d.message}\n` +
      `──────────\n\n` +
      `お急ぎの場合はお電話（${tel}）でもお問い合わせいただけます。\n\n` +
      `${company}`;
    await fetch('https://api.resend.com/emails', {
      method: 'POST',
      headers: { 'Authorization': `Bearer ${RESEND_API_KEY}`, 'Content-Type': 'application/json' },
      body: JSON.stringify({
        from: fromAddr,
        to: [d.email],
        subject: `【${company}】お問い合わせを受け付けました`,
        text: autoText,
      }),
    });
  } catch (e) { /* 自動返信失敗は本送信の成否に影響させない */ }

  return json({ ok: true }, 200, origin);
}

function json(obj, status, origin) {
  return new Response(JSON.stringify(obj), {
    status,
    headers: { 'Content-Type': 'application/json', ...cors(origin) },
  });
}
