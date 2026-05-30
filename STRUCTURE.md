# en-lp ディレクトリ構成

ページ追加・管理画面の拡張に耐えるための階層構造です。
**既存ファイル（Dockerfile / cloudbuild.yaml / apache/ / ohaka/ / pet/ / README.md / VERSION / CHANGELOG.md）はそのまま。** 以下を追加マージします。

```
en-lp/
├── index.html              … 本体HP（TOP・自己完結のまま）
│
├── assets/                 … 共通アセット
│   ├── css/common.css      … 下層ページ共通スタイル（ブランド #509F46）
│   ├── js/common.js        … 共通JS（ハンバーガーメニュー等）
│   ├── img/en.svg     … ロゴ
│   └── fonts/
│
├── includes/               … PHP共通パーツ（1か所直せば全ページ反映）
│   ├── config.php          … NAP・ナビ・サービス一覧を一元管理
│   ├── head.php            … <head>（meta/OGP/CSS）
│   ├── header.php          … ヘッダー＋グローバルナビ
│   └── footer.php          … フッター（NAP）＋SP固定CTA＋JS
│
├── _template/index.php     … 新規ページの雛形（コピーして使う）
│
├── service/                … サービス一覧ハブ（config から自動生成）
├── kaiyou-sou/  powder-cleaning/  grave/  teien-sou/
├── temoto-kuyou/  pet-kaiyou-sou/  ihinseiri/  hikkoshi/   … サービス詳細
├── gokuyou/  staff/  voice/  blog/  contact/  company/  privacy/  … 共通ページ
│   └── 各 index.php は現在「準備中」スタブ（共通ヘッダー/フッター適用済み）
│
├── data/                   … コンテンツデータ
│   ├── news.json           … お知らせ（管理画面が読み書き）
│   └── .htaccess           … 直接Webアクセス禁止
│
├── admin/                  … 管理画面（要ログイン / noindex）
│   ├── config.php          … ログインパスワード・永続性の注意
│   ├── login.php  logout.php  index.php(ダッシュボード)
│   ├── includes/auth.php   … 認証ガード
│   ├── includes/store.php  … データアクセス層（JSON↔Firestore差替点）
│   ├── news/ (index/edit/save/delete.php)  … お知らせCRUD
│   └── assets/admin.css
│
├── ohaka/  pet/            … 既存LP（変更なし）
└── Dockerfile / cloudbuild.yaml / apache/  … 既存（変更なし）
```

## 新しいページの追加手順
1. `_template/` を新スラッグ名のフォルダにコピー
2. ページ先頭の `$page_title / $page_desc / $page_canonical` を編集
3. `<main>` の中身を実装（ヘッダー/フッター/ナビ/CSSは自動適用）
4. ナビやサービス一覧に載せる場合は `includes/config.php` の `NAV` / `SERVICES` に追記するだけで全ページ反映

## 管理画面
- URL: `/admin/`（パスワードログイン）
- パスワードは `admin/config.php` の `ADMIN_PASSWORD_HASH` を必ず変更
  - 生成: `php -r "echo password_hash('新パスワード', PASSWORD_DEFAULT);"`

## データ永続化：Firestore
管理画面のデータは **Firestore** に保存します（Cloud Run はファイルが揮発するため）。
PHP から Firestore REST API を呼び、**Cloud Run のサービスアカウント認証**で動作（鍵ファイル・SDK不要）。
- データアクセス層：`admin/includes/store.php`（`includes/firestore.php` を利用）
- コレクション `news` / ドキュメントID＝記事ID / フィールド `date,category,title,body,published`
- セットアップは **FIREBASE_SETUP.md** を参照（Firestore有効化 → SAに `roles/datastore.user` 付与）
- `data/news.json` は初回移行用のシード（`/admin/migrate.php` で取込→削除推奨）

## 命名の重複（要判断）
- `grave/`（サービス詳細）と既存 `ohaka/`（お墓じまいキャンペーンLP）
- `pet-kaiyou-sou/`（サービス詳細）と既存 `pet/`（ペットLP）

→ サービス詳細ページから既存LPへ誘導する想定。重複を避けたい場合は、サービス詳細リンクを既存LPへ向ける（リダイレクト）か、どちらかに統合してください。
