# CHANGELOG — 縁 マルチLP（en-lp）

Cloud Run プロジェクト: `en-lp`
公開URL: https://en1150.co.jp/lp/{LP名}/
バックエンド（Cloud Run）: https://en-lp-412102088439.asia-northeast1.run.app/{LP名}/

---

## v20260502-0001 — 2026-05-02

### 🏗 マルチLP構成へ移行

**構成変更**
- 単一LP構成（`/lp-ohaka/`） → **マルチLP構成（`/lp/ohaka/` + `/lp/pet/` 等）**
- ルートに統合Dockerfile・cloudbuild.yaml・.htaccessを配置
- 各LPは独立ディレクトリ（ohaka/ pet/ ...）として配置

**ディレクトリ構造**
```
リポジトリルート/
├── Dockerfile             # 統合Docker定義
├── cloudbuild.yaml        # 統合CI/CD
├── .htaccess              # 統合ルーティング・gzip・キャッシュ
├── .dockerignore
├── apache/
│   ├── ports.conf         # PORT=8080
│   └── 000-default.conf
├── ohaka/                 # お墓じまいLP
│   ├── index.html
│   ├── images/
│   ├── llms.txt
│   ├── robots.txt
│   └── sitemap.xml
└── pet/                   # ペット供養LP（将来）
```

**URL対応**
| 公開URL | Cloud Run内部パス |
|--------|------------------|
| `https://en1150.co.jp/lp/ohaka/` | `/ohaka/index.html` |
| `https://en1150.co.jp/lp/ohaka/images/*` | `/ohaka/images/*` |
| `https://en1150.co.jp/lp/pet/`（将来） | `/pet/index.html` |

**含まれるLP**
- `ohaka/` v20260502-0001 — お墓じまいLP（v0005ベース、URL変更）
  - 全URLを `/lp-ohaka/` → `/lp/ohaka/` に更新
  - 構造化データ・OGP・hreflang・llms.txt の全URL対応

**ルートDockerfile設計**
- 単一 `php:8.1-apache` イメージ
- 全LPを 1つの Cloud Run サービスで配信（コスト効率最大化）
- 各LP追加時はディレクトリを増やすだけで自動的に配信される

**新たな機能**
- ルート `/` アクセス時はメインLPの `/ohaka/` へ302リダイレクト
- LP増加時もインフラ構成変更不要

---

## マルチLP追加の手順

### 例: ペットLP追加

1. `pet/` ディレクトリに index.html / images/ / llms.txt 等を配置
2. プロキシ側（en1150.co.jp WordPress）に `/lp/pet/` のディレクトリを作成し、プロキシindex.phpを設置
3. Cloud Run 再デプロイ
4. PROXY_PATH定数を `/lp/pet` 用に書き換えるか、共通プロキシ化

---

## ロードマップ

- [ ] ペット供養LP実装（pet/）
- [ ] 海洋葬LP実装（kaiyou/）
- [ ] 樹木葬LP実装（jumokusou/）
- [ ] 共通プロキシ化（1ファイルで全LPを transparent proxy）
