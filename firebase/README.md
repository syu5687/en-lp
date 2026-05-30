# Firebase（Firestore）構築手順 — 実行ガイド

アプリ側のコードは実装済みです。以下の **GCP側のセットアップ** を行えば稼働します。
すべて Makise さんの GCP 権限で実行してください（自動スクリプト同梱）。

## 前提
- 対象：Cloud Run サービス `en-lp`（リージョン `asia-northeast1` / プロジェクト番号 `412102088439`）
- デフォルトのサービスアカウントを使う場合：`412102088439-compute@developer.gserviceaccount.com`

## 手順A：スクリプトで一括セットアップ（推奨）
ローカル or Cloud Shell で：
```bash
# プロジェクトIDを指定して実行（番号ではなくID）
PROJECT_ID=あなたのプロジェクトID bash firebase/setup.sh
```
このスクリプトが行うこと：
1. Firestore / Cloud Run API の有効化
2. Firestore（ネイティブモード・東京 asia-northeast1）の作成
3. Cloud Run のサービスアカウントに **`roles/datastore.user`** を付与

## 手順B：手動で行う場合
```bash
gcloud config set project PROJECT_ID
gcloud services enable firestore.googleapis.com
gcloud firestore databases create --location=asia-northeast1 --type=firestore-native
gcloud projects add-iam-policy-binding PROJECT_ID \
  --member="serviceAccount:412102088439-compute@developer.gserviceaccount.com" \
  --role="roles/datastore.user" --condition=None
```

## セキュリティルール（任意・クライアントSDKを使う場合のみ必要）
サーバ側（Cloud RunのSA経由）はルールをバイパスするため、当サイトは設定不要です。
将来クライアントSDKを使うなら、同梱の `firebase/firestore.rules`（全拒否ベース）をデプロイ：
```bash
npm i -g firebase-tools
firebase login
cp firebase/.firebaserc.example .firebaserc   # YOUR_PROJECT_ID を書き換え
firebase deploy --only firestore:rules
```

## インデックス
現在のクエリ（news一覧、pageviewsの ts 絞り込み＋並べ替え）は**単一フィールドの自動インデックス**で動作します。
複合インデックスは不要です（`firebase/firestore.indexes.json` は空）。

## 構築後の検証（重要）
1. アプリをデプロイ（ZIPをルートにマージ → push → Cloud Build）
2. 管理画面にログイン → **ダッシュボード → 「Firestore接続検証」(`/admin/health.php`)**
   - プロジェクトID取得・トークン取得・読み書きテストが **すべて ✔ OK** なら構築完了
3. （任意）`/admin/migrate.php` で `data/news.json` をFirestoreへ投入
4. サイトを数回閲覧 → `/admin/analytics/` にPVが反映されることを確認

## 使われるコレクション
| コレクション | 用途 | 書き込み元 |
|---|---|---|
| `news` | お知らせ・ブログ | 管理画面 |
| `pageviews` | アクセスログ | 各ページのビーコン (`/api/track.php`) |
| `_healthcheck` | 接続検証の一時データ（自動削除） | `/admin/health.php` |
