# 縁 マルチLP（en-lp）

鹿児島・有限会社縁のランディングページを統合管理する Cloud Run プロジェクト。

- **Version**: v20260502-0001
- **Stack**: PHP 8.1 + Apache（Cloud Run）
- **Region**: asia-northeast1（東京）
- **Cloud Run URL**: https://en-lp-412102088439.asia-northeast1.run.app/

## 公開URL一覧

| LP | 公開URL（WordPress経由） | Cloud Run直URL |
|----|----------------------|----------------|
| お墓じまい | https://en1150.co.jp/lp/ohaka/ | /ohaka/ |
| ペット供養 | （未実装） | （pet/） |

---

## 📁 ディレクトリ構造

```
.
├── Dockerfile             # 統合Docker定義（php:8.1-apache）
├── cloudbuild.yaml        # 統合CI/CD
├── .htaccess              # ルーティング・圧縮・キャッシュ
├── .dockerignore
├── apache/                # Apache設定（PORT=8080）
│   ├── ports.conf
│   └── 000-default.conf
├── ohaka/                 # お墓じまいLP
│   ├── index.html
│   ├── images/
│   │   ├── en_logo.png
│   │   ├── hero-family.jpg
│   │   ├── staff1.jpg
│   │   └── staff2.jpg
│   ├── llms.txt
│   ├── robots.txt
│   └── sitemap.xml
├── pet/                   # ペット供養LP（将来）
├── VERSION
├── CHANGELOG.md
└── README.md
```

---

## 🚀 デプロイ手順

### A. ローカル動作確認

```bash
docker build -t en-lp:local .
docker run -p 8080:8080 en-lp:local

# ブラウザで以下を確認
# http://localhost:8080/         → /ohaka/ にリダイレクト
# http://localhost:8080/ohaka/   → お墓じまいLP
```

### B. Cloud Run 手動デプロイ

```bash
gcloud config set project YOUR_PROJECT_ID

gcloud run deploy en-lp \
  --source . \
  --region asia-northeast1 \
  --platform managed \
  --allow-unauthenticated \
  --port 8080 \
  --memory 256Mi \
  --cpu 1 \
  --min-instances 0 \
  --max-instances 10
```

### C. Cloud Build 自動デプロイ

main ブランチに push すると `cloudbuild.yaml` が自動実行される。

---

## 🔄 LP追加方法

### 例: ペット供養LP（/lp/pet/）追加

1. **Cloud Run 側にディレクトリを追加**:
   ```
   pet/
   ├── index.html
   ├── images/
   ├── llms.txt
   ├── robots.txt
   └── sitemap.xml
   ```

2. **WordPress側にプロキシディレクトリ作成**:
   ```
   en1150.co.jp/lp/pet/
   ├── index.php          # プロキシ本体
   └── .htaccess
   ```
   - プロキシindex.phpの定数 `PATH_PREFIX` を `/lp/pet` に変更
   - `UPSTREAM_PATH` を `/pet/` に変更

3. **Cloud Run 再デプロイ**:
   ```bash
   git add pet/
   git commit -m "Add pet LP"
   git push origin main
   # → Cloud Build が自動でデプロイ
   ```

---

## 🛠 メンテナンス

- **制作**: LINK-UP Management（Makise）
- **クライアント**: 有限会社 縁
- **Tech Stack**: Cloud Run / Apache / PHP / GitHub / Cloud Build
