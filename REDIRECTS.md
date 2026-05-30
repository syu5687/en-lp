# 301リダイレクトマップ（旧WordPress → 新HP）

移行でURLが変わるページについて、SEO評価を引き継ぐため301リダイレクトを設定します。
以下を **既存のルート `.htaccess`**（en-lpリポジトリ直下）の `RewriteEngine On` 以降に追記してください。
※ 既存の `.htaccess` を上書きせず、追記マージしてください。

```apache
# ============================================================
#  301 リダイレクト（旧WordPress → 新HP）
# ============================================================
<IfModule mod_rewrite.c>
  RewriteEngine On

  # --- 1) HTTPS強制（Cloud Runは転送ヘッダで判定）---
  RewriteCond %{HTTP:X-Forwarded-Proto} =http
  RewriteRule ^ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

  # --- 2) www → 非www に正規化 ---
  RewriteCond %{HTTP_HOST} ^www\.en1150\.co\.jp$ [NC]
  RewriteRule ^ https://en1150.co.jp%{REQUEST_URI} [L,R=301]

  # --- 3) 旧WordPress固定ページ → 新ページ（実URLに合わせて調整）---
  #   例：旧スラッグ → 新パス
  # RewriteRule ^about/?$            /company/        [L,R=301]
  # RewriteRule ^contact-us/?$       /contact/        [L,R=301]
  # RewriteRule ^kaiyousan/?$        /kaiyou-sou/     [L,R=301]
  # RewriteRule ^funkotsu/?$         /powder-cleaning/ [L,R=301]
  # RewriteRule ^ohakajimai/?$       /grave/          [L,R=301]

  # --- 4) 旧ブログ記事（/post-XXXX/ や /?p=XXXX）→ 新ブログ ---
  #   個別対応できない記事はブログ一覧へ集約（暫定）
  RewriteRule ^post-[0-9]+/?$       /blog/           [L,R=301]
  RewriteCond %{QUERY_STRING} (^|&)p=[0-9]+
  RewriteRule ^/?$                  /blog/?          [L,R=301]

  # --- 5) LP：当面 /lp/ohaka/ は据え置き（リダイレクトしない）---
  #   将来 /ohaka/ に統一する際は、下記を有効化して301（今はコメントのまま）
  # RewriteRule ^lp/ohaka/?$        /ohaka/          [L,R=301]
</IfModule>
```

## 運用メモ
- **個別記事の対応表**：重要記事は `post-XXXX → 新URL` を1対1で追加するとSEO評価の引き継ぎが最大化します。一覧へ集約（上記4）は暫定措置です。
- **canonical**：新HP各ページは canonical を `https://en1150.co.jp/...`（非www）に設定済み。
- **sitemap.xml / robots.txt**：移行後に新サイトのものをルートへ設置し、Search Consoleで再送信。
- **確認**：`curl -I https://en1150.co.jp/post-5116/` → `HTTP/1.1 301` と `Location:` を確認。

## 旧URL → 新URL 対応表（記入用テンプレート）
| 旧URL | 新URL | 状態 |
|---|---|---|
| /about/ | /company/ | 要確認 |
| /post-5116/ | /blog/ | 暫定集約 |
| （旧サービスページ） | （新サービスページ） | 記入 |

旧WordPressのURL一覧（サイトマップやSearch Consoleのインデックス済みURL）をいただければ、
1対1の正確な301マップを作成します。
