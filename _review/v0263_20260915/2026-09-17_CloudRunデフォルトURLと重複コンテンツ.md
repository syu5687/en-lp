# Cloud Run のデフォルトHTTPSエンドポイントURLと重複コンテンツ

作成：2026-09-17 ／ 対象：en1150（有限会社 縁）／ サービス `en-lp`（asia-northeast1）

---

## 0. 結論

**理屈のうえでは重複コンテンツになります。ただし en1150 の現状は「ほぼ無害」で、穴が2つあります。**

`https://<service>-<hash>.asia-northeast1.run.app` は、独自ドメインと**まったく同じコンテンツを返す別ホスト**です。
Googlebot が到達でき、301・canonical・noindex のいずれも無ければ、重複URLとして扱われます。

en1150 では canonical がほぼ全ページで独自ドメインの絶対URLになっているため、
run.app 版がクロールされても正規版へ統合される可能性が高い状態です。
ただし **canonical は「ヒント」であって保証ではありません。** 下の穴を埋めておくのが安全です。

---

## 1. 実測（リポジトリ確認・2026-09-17）

| 確認項目 | 実測 | 判定 |
|---|---|---|
| `includes/config.php` の `SITE['url']` | `'https://en1150.co.jp'` ハードコード | ◎ 安全 |
| canonical / hreflang / og:url の出力 | すべて `SITE['url']` 起点の絶対URL | ◎ 安全 |
| `sitemap.php` の `<loc>` | `SITE['url'] . $path` の絶対URL | ◎ 安全 |
| `apache/000-default.conf` | `ServerName en1150.co.jp` ＋ `UseCanonicalName On` | ○ Apache自動生成の転送先は独自ドメインになる |
| `.htaccess` 1c（末尾スラッシュ補完） | `https://en1150.co.jp/$1/` へ絶対URL301 | ○ ディレクトリURLは実質すでに独自ドメインへ逃げる |
| `robots.txt` | 静的ファイル。run.app でも `Allow: /` を返す | **△ 穴②** |
| ホスト名による301 | **存在しない** | **△ 穴①** |
| canonical が1つも無いページ | **6件**（後述） | **△ 穴③** |

### canonical が出力されていないページ（6件）

```
/teien-sou/        樹木葬
/jewelry-reform/   ジュエリーリフォーム
/ihinseiri/        遺品整理
/temoto-kuyou/     手元供養
/hikkoshi/         お墓の引っ越し
/dl/               資料PDF計測中継（noindex が適切）
```

※ `/ohaka/` `/pet/` も canonical 無しだが、中身は `header('Location: …', 301)` のみの転送ファイルなので対象外。

**この6ページは run.app と無関係にも危険です。** `?utm_*` `?cb=` `?fbclid=` 付きでシェアされた場合も
別URL扱いになり得るため、run.app 対策より先に効きます。

---

## 2. run.app が発見される経路（「リンクが無いから大丈夫」ではない）

- **証明書の透明性ログ（Certificate Transparency）** — run.app のホスト名は公開ログに載る
- Chrome のURL履歴・ブックマーク由来のクロール
- Cloud Build のログやドキュメント、社内資料に貼られたURL
- 外部サイト・SNSでの言及

実際にインデックスされている事例は珍しくありません。「発見されない」前提の設計は避けるべきです。

なお `run.app` は **Public Suffix List に登録済み**なので、`xxx.run.app` は独自ドメインとは
まったく別サイト扱いになります。ドメイン評価を丸ごと食い合うというより、**URL単位の重複判定**になります。

---

## 3. 対応案

### 【S】`.htaccess` にホスト名301を追加 — 推奨・根治

既存の `.htaccess` の **1b（www→非www）の直後**に追加します。

```apache
  # ---- 1b-2) Cloud Run デフォルトURL（*.run.app）→ 独自ドメインへ 301 ----
  # 同一コンテンツが別ホストで配信され重複URLになるのを防ぐ。
  RewriteCond %{HTTP_HOST} \.run\.app$ [NC]
  RewriteRule ^ https://en1150.co.jp%{REQUEST_URI} [R=301,L]
```

**期待効果**：canonical の有無に依存せず重複の根を断つ。GA4の `page_location` にホスト名が混ざるのも止まる。

**リスク**
- **デプロイ直後の動作確認を run.app でできなくなる。** 切り分け手段が1つ減ります。
- Cloud Run の起動プローブは既定でTCPなので301でも問題なし。**HTTPプローブを設定している場合のみ**要確認。
- Cloud Build に疎通テストがある場合、run.app を叩いていないか導入前に確認。

### 【A】Cloud Run 側で「デフォルトのHTTPSエンドポイントURL」を無効化

**期待効果**：URL自体が存在しなくなる。最も確実。

**リスク**
- 独自ドメインのマッピングやDNSが壊れたとき、**復旧用の到達経路が消えます。**
- 切り戻しはコンソールで有効化し直すだけ（数分）なので、致命的ではありません。

→ **S案を先に入れて数日安定を確認してから、A案を重ねる**のが順当です。いきなりA案だけは避けてください。

### 【B】canonical 欠落6ページの補完 — run.app より優先

`/teien-sou/` `/jewelry-reform/` `/ihinseiri/` `/temoto-kuyou/` `/hikkoshi/` に
`<link rel="canonical" href="https://en1150.co.jp/…/">` を追加。
`/dl/` は計測中継ページなので canonical ではなく **`noindex, follow`** が適切です。

### 【C】robots.txt のホスト出し分け — 不要

run.app のときだけ `Disallow: /` を返す手もありますが、
**Disallow はインデックス自体を防げません**（URLだけ検索結果に載ることがある）。301の方が確実なので、S案を入れるなら不要です。

---

## 4. ダメ出し検証（この対応が失敗するとしたら）

- **失敗要因①**：S案の301を入れた直後に独自ドメイン側が壊れると、run.app からも飛ばされて確認手段がゼロになる。
  → だからS案とA案を**同時にやらない**。S案を入れてもURL自体は生きているので、一時的に外せば確認できます。

- **失敗要因②**：Cloud Build や外部監視が run.app を叩いていて、301で失敗判定になる。
  → 導入前に Cloud Build の設定と、監視サービスの登録URLを確認。

- **失敗要因③**：そもそも run.app がインデックスされておらず、工数だけかかって成果ゼロ。
  → **先に実態を確認するのが正しい順序**（下記）。ただしS案は3行で終わるので、確認より先に入れてしまっても損はありません。

- **失敗要因④**：Cloudflare 等がドメイン側に挟まっている場合、`%{HTTP_HOST}` が期待通り来ない。
  → 導入後に run.app URL を直接開いて301が返るか確認。

---

## 5. 実態確認の手順

1. Cloud Run コンソール → サービス `en-lp` → デフォルトURL を控える
2. Google で `site:` 検索（そのホスト名を指定）→ ヒット0件であること
3. GA4 → 探索 → ディメンション「ホスト名」→ `run.app` が混ざっていないか
4. Search Console →「ページ」→「クロール済み - インデックス未登録」の推移

---

## 6. 優先順

| 優先 | 内容 | 所要 |
|---|---|---|
| **S** | canonical 欠落5ページの補完＋`/dl/` を noindex 化 | 20分 |
| **S** | `.htaccess` に `*.run.app` → 独自ドメイン 301 を追加 | 5分 |
| A | GA4ホスト名・`site:`検索で run.app のインデックス実態を確認 | 10分 |
| B | 安定確認後、Cloud Run デフォルトURLの無効化を検討 | 5分 |

---

## 7. 補足：Cloudflare Worker 側

`en-contact.mk-cbe.workers.dev` も同じ構造ですが、**JSON APIのみでHTMLを返さない**ため
重複コンテンツの対象外です。対応不要。

---

## 8. 未確認事項

- Cloud Run サービス `en-lp` のデフォルトURL文字列（コンソールでの確認が必要）
- 現時点で run.app がインデックスされているか（`site:` 検索・GA4ホスト名で確認）
- Cloud Build にデプロイ後の疎通テストが設定されているか
