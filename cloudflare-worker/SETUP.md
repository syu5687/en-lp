# お問い合わせフォーム セットアップ（Cloudflare Worker + Resend）

フォーム（`/contact/`）→ Cloudflare Worker → Resend でメール送信します。
標準スタック（Cloudflare Worker + Resend）に準拠。

## 1. Resend 準備
1. Resend に登録し、送信ドメイン `en1150.co.jp` を認証（DNSにSPF/DKIM登録）
   - 認証前のテストは `from: onboarding@resend.dev` でも送信可
2. API キーを発行（後で Worker の Secret に設定）

## 2. Worker をデプロイ
1. Cloudflare ダッシュボード → Workers & Pages → Create Worker
2. 名前を `en-contact` 等にして作成
3. エディタに `cloudflare-worker/contact-worker.js` の内容を貼り付けてデプロイ
4. デプロイ後の公開URL（例 `https://en-contact.xxxx.workers.dev`）を控える

## 3. Worker の環境変数を設定
Worker → 設定 → 変数 で以下を登録：

| 変数 | 種類 | 値の例 |
|---|---|---|
| `RESEND_API_KEY` | Secret | re_xxxxxxxx |
| `MAIL_TO` | Text | info@en1150.co.jp |
| `MAIL_CC` | Text | mk@emanet.jp（任意） |
| `MAIL_FROM` | Text | noreply@en1150.co.jp（認証済ドメイン） |
| `ALLOW_ORIGIN` | Text | https://en1150.co.jp |
| `MAIL_COMPANY` | Text | 有限会社 縁（自動返信の署名・任意） |
| `MAIL_TEL` | Text | 099-801-3637（自動返信の案内・任意） |

## 4. サイト側にURLを設定
`includes/config.php` の `CONTACT_WORKER_URL` を、手順2で控えた公開URLに変更：
```php
const CONTACT_WORKER_URL = 'https://en-contact.xxxx.workers.dev';
```

## 5. 動作確認
`/contact/` でフォーム送信 → `MAIL_TO` にメールが届けば成功。
失敗時はフォームに電話・LINEの案内が表示されます（フォールバック）。

## 仕様メモ
- フォーム→Workerは `application/json` の POST
- 必須: お名前 / メール / 内容 / 同意。未入力はWorker側でも再チェック
- `reply_to` に問い合わせ者のメールを設定（返信しやすい）
- CORS は `ALLOW_ORIGIN` で制限（未設定時は `*`）
