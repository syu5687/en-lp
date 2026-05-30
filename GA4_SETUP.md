# GA4 設定ガイド

自前のアクセス解析（Firestore）と**併用**できます。GA4はマーケティング計測の二重化用です。

## ID の種類（重要）
| ID | 形式 | 用途 |
|---|---|---|
| 測定ID | `G-XXXXXXXXXX` | サイトのタグ（gtag.js）に使用 ★これが必要 |
| プロパティID | `399545209` | GA4管理画面 / Data API のレポート参照 |

サイト計測の有効化には **測定ID（G-で始まる）** が必要です。
GA4管理画面 → 管理 → データストリーム → ウェブ → 「測定ID」で確認できます。
（`399545209` はプロパティID＝レポート参照用で、タグには使えません）

## 本体HP・サービスページ等（en-lp）
`includes/config.php` の測定IDを設定するだけで全ページに自動で入ります：
```php
const GA4_MEASUREMENT_ID = 'G-XXXXXXXXXX';  // ← 取得した測定ID
const GA4_PROPERTY_ID    = '399545209';     // 参照用（設定済み）
```
- 未設定（空）の間はタグは出力されません（安全）
- 設定すると TOP・全下層ページの `<head>` に gtag.js が自動で入ります

## LP（ohaka/・pet/）にもGA4を入れる場合
LPは静的HTMLなので、各 `index.html` の `<head>` 内に直接貼り付けます：
```html
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-XXXXXXXXXX"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'G-XXXXXXXXXX');
</script>
```

## 自前計測との関係
- 自前計測（Firestore・`/admin/analytics/`）はCookie/IP不使用で軽量。運営者がすぐ確認する用
- GA4は流入チャネル・コンバージョン等の詳細分析用
- 両方入れても問題ありません（GA4はCookieを使うため、プライバシーポリシーにCookie利用の追記を推奨）
