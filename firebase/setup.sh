#!/usr/bin/env bash
# =============================================================
#  有限会社 縁 — Firestore セットアップ（クロスプロジェクト対応）
#  構成: en-lp(Cloud Run)=412102088439 / Firestore=941919710488
#  実行前に: gcloud auth login 済み / 両プロジェクトへの権限
#  使い方:   bash firebase/setup.sh
# =============================================================
set -euo pipefail

# ---- 設定 ----
FIRESTORE_PROJECT="${FIRESTORE_PROJECT:-941919710488}"  # Firestoreがあるプロジェクト
CLOUDRUN_PROJECT="${CLOUDRUN_PROJECT:-412102088439}"    # en-lpが動くプロジェクト
REGION="${REGION:-asia-northeast1}"
SERVICE="${SERVICE:-en-lp}"
LOCATION="${LOCATION:-asia-northeast1}"                 # Firestoreロケーション

echo "▶ Firestoreプロジェクト: $FIRESTORE_PROJECT / Cloud Runプロジェクト: $CLOUDRUN_PROJECT"

echo "▶ Firestore側でAPIを有効化"
gcloud services enable firestore.googleapis.com --project "$FIRESTORE_PROJECT"

echo "▶ Firestore(ネイティブモード) を作成（既存ならスキップ）"
gcloud firestore databases create --location="$LOCATION" --type=firestore-native \
  --project "$FIRESTORE_PROJECT" 2>/dev/null || echo "  （既に作成済みのようです）"

echo "▶ en-lp のサービスアカウントを特定"
SA="$(gcloud run services describe "$SERVICE" --region "$REGION" --project "$CLOUDRUN_PROJECT" \
      --format='value(spec.template.spec.serviceAccountName)' 2>/dev/null || true)"
if [ -z "$SA" ]; then
  SA="${CLOUDRUN_PROJECT}-compute@developer.gserviceaccount.com"
  echo "  未指定 → デフォルトを使用: $SA"
else
  echo "  サービスアカウント: $SA"
fi

echo "▶ ★クロスプロジェクト権限付与：en-lpのSAに、Firestore側($FIRESTORE_PROJECT)で datastore.user を付与"
gcloud projects add-iam-policy-binding "$FIRESTORE_PROJECT" \
  --member="serviceAccount:${SA}" \
  --role="roles/datastore.user" \
  --condition=None

echo ""
echo "✔ 完了。アプリ側は GCP_PROJECT_ID='${FIRESTORE_PROJECT}' を設定済み。"
echo "  次: デプロイ → /admin/health.php で接続検証"
