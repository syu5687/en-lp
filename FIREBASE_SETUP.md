# Firebase（Firestore）セットアップ手順

管理画面のデータは **Firestore** に保存します。PHP から Firestore REST API を呼び、
**Cloud Run のサービスアカウント認証**で動作します（鍵ファイル・SDK・gRPC 不要）。

## 1. Firestore を有効化
1. Firebase コンソール または GCP コンソールで、対象プロジェクトの **Firestore（ネイティブモード）** を有効化
2. ロケーションは `asia-northeast1`（en-lp と同リージョン）推奨

## 2. Cloud Run サービスアカウントに権限付与
en-lp サービスが使うサービスアカウントに、以下のロールを付与：

```
roles/datastore.user   （Cloud Datastore ユーザー）
```

付与例（gcloud）:
```bash
# 1) en-lp が使うSAを確認
gcloud run services describe en-lp --region asia-northeast1 \
  --format='value(spec.template.spec.serviceAccountName)'

# 2) ロール付与（SA未指定ならデフォルトの {PROJECT_NUMBER}-compute@developer.gserviceaccount.com）
gcloud projects add-iam-policy-binding PROJECT_ID \
  --member="serviceAccount:SERVICE_ACCOUNT_EMAIL" \
  --role="roles/datastore.user"
```

## 3. プロジェクトID
`includes/config.php` の `GCP_PROJECT_ID` は空のままでOK
（Cloud Run のメタデータサーバから自動取得します）。
固定したい場合のみ文字列で指定してください。

## 4. 初回データ移行（任意）
シード `data/news.json` を Firestore に取り込む場合：
1. デプロイ後、管理画面にログイン
2. ブラウザで `/admin/migrate.php` を開く（取り込み実行）
3. `/admin/news/` で確認
4. 完了後、`admin/migrate.php` と `data/news.json` は削除推奨

## 5. データ構造
- コレクション: `news`
- ドキュメントID: 記事ID（例 `20260415-001`）
- フィールド: `date` `category` `title` `body` `published`(bool)

## ローカル開発（任意）
ローカルでFirestoreに繋ぐ場合のみ、サービスアカウント鍵を使います：
```bash
export GOOGLE_APPLICATION_CREDENTIALS=/path/to/service-account.json
export GCP_PROJECT_ID=あなたのプロジェクトID
php -S 127.0.0.1:8000
```
（本番Cloud Runでは鍵不要・メタデータ認証で自動動作）

## 公開ページとの連動
- `/blog/` は Firestore の公開記事（`published=true`）を日付降順で表示
- TOP（index.html）のブログ欄をFirestore連動にする場合は、
  クライアント側 Firebase JS SDK で読む方法と、index.php 化してサーバ側で読む方法があります（別途対応可）。
