#!/usr/bin/env bash
# =============================================================
#  本体HPの外部画像（旧WordPress wp-content）をローカルへ取得
#  WordPress(en1150.co.jp)が生きている「今のうち」に実行してください。
#  実行場所: en1150.co.jp に到達できる環境（手元PC/Cloud Shell等）
#  使い方:   bash scripts/fetch-wp-images.sh
#  取得先:   ./assets/img/
# =============================================================
set -euo pipefail
DEST="$(cd "$(dirname "$0")/.." && pwd)/assets/img"
mkdir -p "$DEST"
BASE="https://en1150.co.jp/wp-content"

dl() { echo "▶ $2"; curl -fsSL "$1" -o "$DEST/$2" && echo "  ✓ $2" || echo "  ✖ 取得失敗: $1"; }

# テーマ スライド画像
for n in 001 003 004 005 006 007; do
  dl "$BASE/themes/en-theme/img/index/slide-img${n}.jpg" "slide-img${n}.jpg"
done
# アップロード画像
dl "$BASE/uploads/2026/01/IMG_1924.jpg" "IMG_1924.jpg"
dl "$BASE/uploads/2026/04/Gemini_Generated_Image_f1yt8rf1yt8rf1yt.png" "Gemini_Generated_Image_f1yt8rf1yt8rf1yt.png"
dl "$BASE/uploads/2026/04/Gemini_Generated_Image_tex9b1tex9b1tex9.png" "Gemini_Generated_Image_tex9b1tex9b1tex9.png"

echo ""
echo "✔ 完了。assets/img/ に保存しました。index.php は既にローカル参照に更新済みです。"
echo "  この後 push → Cloud Build で反映されます。"
