# 縁 お墓じまいLP

鹿児島・有限会社縁（en1150.co.jp）様向け お墓じまい専用ランディングページ

- **Version**: v20260501-0001
- **Stack**: PHP 8.1 + Apache（Cloud Run）
- **Region**: asia-northeast1（東京）
- **公開予定URL**: https://en1150.co.jp/grave-lp/

---

## 📁 ファイル構成

```
en-ohakajimai-lp_v20260501-0001/
├── Dockerfile               # Cloud Run用Docker定義（php:8.1-apache）
├── cloudbuild.yaml          # Cloud Build CI/CD設定
├── apache/
│   ├── ports.conf           # PORT=8080 設定
│   └── 000-default.conf     # VirtualHost設定（AllowOverride All）
├── index.html               # メインLP（55.6KB）
├── images/
│   ├── hero-family.jpg      # MV画像（87.7KB）
│   ├── staff1.jpg           # 代表者写真（17.5KB）
│   └── staff2.jpg           # アドバイザー写真（9.5KB）
├── .htaccess                # gzip / キャッシュ / セキュリティヘッダ
├── robots.txt               # クローラー許可（AI bot含む）
├── sitemap.xml              # 検索エンジン用サイトマップ
├── .dockerignore            # Dockerビルド除外設定
├── VERSION                  # バージョン情報
├── CHANGELOG.md             # 変更履歴
└── README.md                # 本ファイル
```

---

## 🚀 デプロイ手順

### A. ローカル動作確認

```bash
# Dockerイメージをビルド
docker build -t en-ohakajimai-lp:local .

# ローカル起動（http://localhost:8080）
docker run -p 8080:8080 en-ohakajimai-lp:local
```

### B. Cloud Run へ手動デプロイ（初回 or 緊急）

```bash
# プロジェクト設定
gcloud config set project YOUR_PROJECT_ID

# Artifact Registry リポジトリ作成（初回のみ）
gcloud artifacts repositories create en-lp \
  --repository-format=docker \
  --location=asia-northeast1 \
  --description="縁 LP用 Dockerリポジトリ"

# ビルド & プッシュ
gcloud builds submit \
  --tag asia-northeast1-docker.pkg.dev/YOUR_PROJECT_ID/en-lp/en-ohakajimai-lp:v20260501-0001

# Cloud Run へデプロイ
gcloud run deploy en-ohakajimai-lp \
  --image asia-northeast1-docker.pkg.dev/YOUR_PROJECT_ID/en-lp/en-ohakajimai-lp:v20260501-0001 \
  --region asia-northeast1 \
  --platform managed \
  --allow-unauthenticated \
  --port 8080 \
  --memory 256Mi \
  --cpu 1 \
  --min-instances 0 \
  --max-instances 10
```

### C. Cloud Build トリガーで自動デプロイ（推奨）

1. GitHub リポジトリと Cloud Build を連携
2. トリガー設定で `cloudbuild.yaml` を指定
3. main ブランチへ push すると自動でビルド → デプロイ

```bash
# トリガー作成例
gcloud builds triggers create github \
  --repo-name=YOUR_REPO_NAME \
  --repo-owner=YOUR_GITHUB_USER \
  --branch-pattern=^main$ \
  --build-config=cloudbuild.yaml \
  --name=en-ohakajimai-lp-deploy
```

### D. 独自ドメイン接続

```bash
# 1. ドメイン所有権確認（Search Console等で）
# 2. Cloud Run でドメインマッピング
gcloud beta run domain-mappings create \
  --service en-ohakajimai-lp \
  --domain grave-lp.en1150.co.jp \
  --region asia-northeast1

# 3. 表示されるDNSレコード（A / AAAA / CNAME）を
#    Cloudflare等のDNSプロバイダに登録
```

---

## 🔄 バージョンアップ手順

### 1. 変更を加える
```bash
# ファイル編集後、ローカルで動作確認
docker build -t en-ohakajimai-lp:test .
docker run -p 8080:8080 en-ohakajimai-lp:test
```

### 2. バージョン番号を更新
- `VERSION` ファイルを `v20260501-0002` などに更新
- `CHANGELOG.md` に変更内容を追記

### 3. ZIPファイル名も同じバージョン
```
en-ohakajimai-lp_v20260501-0002.zip
```

### 4. GitHub に push
- Cloud Build トリガーが自動的にデプロイ実行

### 5. ロールバック
```bash
# 過去のリビジョンを確認
gcloud run revisions list --service en-ohakajimai-lp --region asia-northeast1

# 特定リビジョンに即時切り替え
gcloud run services update-traffic en-ohakajimai-lp \
  --to-revisions=en-ohakajimai-lp-00003-abc=100 \
  --region asia-northeast1
```

---

## 📊 想定コスト

Cloud Run 設定値（min-instances=0、無料枠あり）の想定：

| 項目 | 月間想定 | コスト目安 |
|------|---------|-----------|
| リクエスト数 | 〜10,000 | 無料枠内 |
| CPU時間 | 〜180秒/日 | 無料枠内 |
| メモリ | 256Mi | 無料枠内 |
| **合計** | — | **¥0〜数百円/月** |

> ※ 100万リクエスト/月までは無料枠で収まる構成。

---

## 🛠 メンテナンス連絡先

- **制作**: LINK-UP Management（Makise）
- **クライアント**: 有限会社 縁
- **Tech Stack**: Cloud Run / Apache / PHP / GitHub / Cloud Build
