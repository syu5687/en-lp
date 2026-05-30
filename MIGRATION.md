# en1150.co.jp ドメイン移行手順（WordPress → Cloud Run）

本体HPの完成後、en1150.co.jp を **現WordPressサーバ → Cloud Run（en-lp）** に切り替える手順です。
切替後はHP・サービスページ・LP・管理画面・解析がすべて同一オリジン（en1150.co.jp）に統一されます。

## 前提・移行前チェックリスト
- [ ] 本体HP（index.php）の動作確認（Cloud Run直URLで全ページ表示OK）
- [ ] **wp-content画像のローカル化済み**（`scripts/fetch-wp-images.sh` 実行 → `/assets/img/` に取得）★WP停止後の404防止に必須
- [ ] LP（/lp/ohaka/）の表示・計測確認
- [ ] Firestore接続検証OK（`/admin/health.php`）
- [ ] 管理画面パスワード変更済み
- [ ] **DNSのTTLを事前に短縮**（例 300秒）— 切替・切戻しを素早く行うため、数日前に下げておく

## 手順1：Cloud Run にカスタムドメインを割当
ドメイン所有権の確認後、ドメインマッピングを作成します。

```bash
# 1) ドメイン所有権の確認（初回のみ）
#    Google Search Console で en1150.co.jp を確認しておく

# 2) マッピング作成（apex と www）
gcloud beta run domain-mappings create --service en-lp \
  --domain en1150.co.jp --region asia-northeast1
gcloud beta run domain-mappings create --service en-lp \
  --domain www.en1150.co.jp --region asia-northeast1

# 3) 必要なDNSレコードを確認
gcloud beta run domain-mappings describe --domain en1150.co.jp --region asia-northeast1
```
※ asia-northeast1 でドメインマッピングが使えない場合は、
  **グローバル外部HTTPSロードバランサ + サーバーレスNEG** 構成を使います（別途手順可）。

## 手順2：DNS切替（移行の山場）
現WordPressサーバを指している A/AAAA レコードを、手順1でCloud Runが提示した値に変更します。

- **apex（en1150.co.jp）**: Cloud Run提示の A/AAAA レコード（複数）に差し替え
- **www**: Cloud Run提示の CNAME（`ghs.googlehosted.com` 等）に変更
- SSL証明書はGoogleが**自動発行**（反映に最大24時間。通常は数十分〜数時間）

切替後、DNS伝播を待って `https://en1150.co.jp/` がCloud Run（新HP）を返すことを確認。

## 手順3：www → 非www の正規化
canonicalは非www（`https://en1150.co.jp`）に統一済み。
`www` アクセスを非wwwへ301する設定を `.htaccess` に追加（REDIRECTS.md 参照）。

## 手順4：切替後の確認
- [ ] `https://en1150.co.jp/` 新HP表示・SSL有効（鍵マーク）
- [ ] 主要ページ（/service/ 各サービス /blog/ /contact/）表示
- [ ] 画像がすべて表示（wp-content由来の404が無い）
- [ ] /lp/ohaka/ 表示・計測
- [ ] /admin/ ログイン・解析・接続検証
- [ ] Search Console で新サイトマップ送信、カバレッジ監視
- [ ] 301リダイレクト動作確認（旧URL→新URL）

## ロールバック
問題発生時はDNSのA/AAAAを旧WordPressサーバへ戻すだけ（TTLを下げてあれば数分で復旧）。
Cloud Run側は無停止で並行稼働しているため、切戻しは安全です。

## 補足：LP計測の簡素化（移行完了後）
完全移行後はLPもHPと同一オリジンになるため、LPのクロスオリジン用ビーコン
（`LP_TRACKING.md`）は、HPと同じ相対参照 `/assets/js/track.js` に置き換え可能です
（そのままでも動作します）。
