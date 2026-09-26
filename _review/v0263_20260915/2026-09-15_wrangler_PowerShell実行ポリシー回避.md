# en1150｜`npx wrangler deploy` が PowerShell で止まる場合の対処

作成：2026-09-15
現象：`npx : このシステムではスクリプトの実行が無効になっているため、ファイル C:\Program Files\nodejs\npx.ps1 を読み込むことができません`

---

## 1. 原因

PowerShell の実行ポリシー（ExecutionPolicy）が `Restricted` のため、`.ps1` スクリプトが実行できません。
`npx` は Windows に `npx.ps1`（PowerShell用）と `npx.cmd`（コマンドプロンプト用）の2つが入っており、
PowerShell からは `.ps1` の方が呼ばれて止まっています。

**Node.js や wrangler の問題ではありません。** PowerShell のセキュリティ設定です。

---

## 2. 対処（推奨順）

### 方法A：`npx.cmd` を使う（**推奨**・設定変更なし）

`.ps1` を経由せず `.cmd` を直接呼びます。**これが一番安全で、今すぐ動きます。**

```powershell
cd D:\__github_win\en-lp\push\en-lp\worker\en-contact
npx.cmd wrangler deploy
```

### 方法B：コマンドプロンプト（cmd）で実行

PowerShell を閉じて「コマンド プロンプト」を開き、同じコマンドを実行します。

```
cd /d D:\__github_win\en-lp\push\en-lp\worker\en-contact
npx wrangler deploy
```

PowerShell のまま cmd を呼ぶこともできます。

```powershell
cmd /c "cd /d D:\__github_win\en-lp\push\en-lp\worker\en-contact && npx wrangler deploy"
```

### 方法C：実行ポリシーを変更する（恒久対応・**セキュリティ設定の変更**）

今後も PowerShell から npm / npx を使うなら、この変更で解決します。
ただし **PowerShell のセキュリティ設定を緩める操作**なので、内容を理解したうえでご自身で判断してください。
こちらでは実行しません。

```powershell
Get-ExecutionPolicy -List          # 現在の設定を確認（変更前に控える）
Set-ExecutionPolicy -Scope CurrentUser RemoteSigned
```

`RemoteSigned` は「ローカルで作ったスクリプトは実行可・インターネットから落としたものは署名が必要」という設定で、
開発機では一般的な値です。`Unrestricted` や `Bypass` にする必要はありません。
対象を `-Scope CurrentUser` に限定すれば、機械全体ではなくこのユーザーだけに適用されます。

**まずは方法Aで進めて、必要になったら方法Cを検討する**のが安全です。

---

## 3. 実行時に出るもの（想定）

### ① wrangler のインストール確認

`worker/en-contact/` に `package.json` が無いため、npx が wrangler をその場で取得します。

```
Need to install the following packages:
  wrangler@x.y.z
Ok to proceed? (y)
```

→ `y` を入力してください。

### ② Cloudflare の認証

`.wrangler` フォルダが既にあるため認証済みの可能性がありますが、**未確認**です。
求められた場合はブラウザが開くので、Cloudflare アカウントでログインしてください。

```
wrangler login
```

### ③ デプロイ結果

成功すると、このような出力になります。

```
Uploaded en-contact (x.xx sec)
Published en-contact (x.xx sec)
  https://en-contact.mk-cbe.workers.dev
```

---

## 4. 補足（今回の Worker の構成）

`wrangler.toml` の内容：

| 項目 | 値 |
|---|---|
| name | `en-contact` → `https://en-contact.mk-cbe.workers.dev` |
| main | `worker.js` |
| compatibility_date | `2026-08-01` |
| cron | `0 0 * * *`（UTC 00:00 = 日本時間 09:00・稼働確認メール） |

- 通知先・送信元は `worker.js` 冒頭の `CONFIG` で管理（`vars` 不要）
- `BREVO_API_KEY` は Worker 側の secret として登録済み。**デプロイしても secret は消えません**
  （もし `BREVO_API_KEY` 未設定のエラーが出たら `npx.cmd wrangler secret put BREVO_API_KEY`）
- cron は Workers Paid プラン前提の記述があります。以前デプロイできているため問題ないはずですが、
  プラン起因のエラーが出たら教えてください

---

## 5. デプロイ後の確認

Worker が新しくなったかは、**送信テスト1件**で判定できます。

広告パラメータ付きで着地してから送信してください。

```
https://en1150.co.jp/lp1/?gclid=TEST123&utm_source=google&utm_medium=cpc&utm_campaign=TEST_CAMPAIGN&utm_content=TEST_AD&utm_term=TEST_KW
```

- 種別：資料請求（無料）
- メール：`mk@lu-m.co.jp`
- 内容欄：**【テスト送信】** と明記

| 確認項目 | Worker が新しい場合 | 古いままの場合 |
|---|---|---|
| 担当者通知メール | 「キャンペーン：TEST_CAMPAIGN」「Google広告クリック（gclid あり）」が出る | 出ない |
| `/admin/inquiries/` の一覧 | 「広告（クリック計測あり）：TEST_CAMPAIGN / TEST_AD / TEST_KW」が出る | 出ない |
| CSVエクスポート | 8列（フォーム名・着地ページ・広告媒体・広告メディア・広告キャンペーン・広告コンテンツ・検索キーワード・gclid）が入る | 空 |
| 自動返信 | 届く（PDF選択時に「📮 詳しい資料を郵送でお届けします」が**出ないこと**） | — |

⚠ `en_nt` クッキーを持つ端末ではGA4タグが出力されません。
GA4の `contact_submit` まで確認するならシークレットウィンドウで。その場合、
**テスト送信がGA4キーイベントとGoogle広告CVに1件入ります。**

---

## 6. ダメ出し検証

| 視点 | 想定される失敗 | 対策 |
|---|---|---|
| エンジニア | 実行ポリシーを `Bypass` や `Unrestricted` に変えて、機械全体のセキュリティを緩めてしまう | 方法Aで足ります。変えるとしても `-Scope CurrentUser RemoteSigned` まで |
| エンジニア | `npx` が毎回 wrangler をダウンロードし、バージョンが変わって挙動が変わる | 気になる場合は `worker/en-contact/` に `package.json` を置いて wrangler をバージョン固定する（別途対応可） |
| 運用 | デプロイしたつもりで失敗しており、広告情報が保存されないまま広告を切り替える | 5章の送信テストで必ず確認してから広告を切り替える |
| セキュリティ | `wrangler login` でブラウザ認証したまま、共有端末に認証情報が残る | 作業後に不要なら `npx.cmd wrangler logout` |
