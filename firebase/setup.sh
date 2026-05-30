#!/usr/bin/env bash
# =============================================================
#  有限会社 縁 — Firestore セットアップスクリプト
#  実行前に: gcloud auth login 済み / 対象プロジェクトの権限を持つこと
#  使い方:   PROJECT_ID=あなたのプロジェクトID bash firebase/setup.sh
# =============================================================
set -euo pipefail

# ---- 設定（必要に応じて変更）----
PROJECT_ID="${PROJECT_ID:-}"          # 必須：環境変数で渡す
REGION="${REGION:-asia-northeast1}"   # Cloud Run / Firestore のリージョン
SERVICE="${SERVICE:-en-lp}"           # Cloud Run サービス名
LOCATION="${LOCATION:-asia-northeast1}" # Firestore ロケーション（東京）

if [ -z "$PROJECT_ID" ]; then
  echo "✖ PROJECT_ID を指定してください： PROJECT_ID=xxxx bash firebase/setup.sh"; exit 1
fi

echo "▶ プロジェクト設定: $PROJECT_ID"
gcloud config set project "$PROJECT_ID"

echo "▶ 必要APIを有効化"
gcloud services enable firestore.googleapis.com run.googleapis.com

echo "▶ Firestore(ネイティブモード) を作成（既に存在する場合はスキップ）"
gcloud firestore databases create --location="$LOCATION" --type=firestore-native 2>/dev/null \
  || echo "  （Firestoreは既に作成済みのようです）"

echo "▶ Cloud Run のサービスアカウントを取得"
SA="$(gcloud run services describe "$SERVICE" --region "$REGION" \
      --format='value(spec.template.spec.serviceAccountName)' 2>/dev/null || true)"
if [ -z "$SA" ]; then
  PNUM="$(gcloud projects describe "$PROJECT_ID" --format='value(projectNumber)')"
  SA="${PNUM}-compute@developer.gserviceaccount.com"
  echo "  サービスアカウント未指定 → デフォルトを使用: $SA"
else
  echo "  サービスアカウント: $SA"
fi

echo "▶ Firestoreアクセス権限を付与 (roles/datastore.user)"
gcloud projects add-iam-policy-binding "$PROJECT_ID" \
  --member="serviceAccount:${SA}" \
  --role="roles/datastore.user" \
  --condition=None

echo ""
echo "✔ 完了しました。"
echo "  次: アプリをデプロイ → /admin/health.php で接続検証 → /admin/migrate.php で初期データ投入(任意)"
