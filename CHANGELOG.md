# CHANGELOG — 縁 お墓じまいLP

バージョン管理規則: `v{YYYYMMDD}-{NNNN}`（同日内連番）

公開URL: **https://en1150.co.jp/lp-ohaka/**
バックエンド（Cloud Run）: https://en-lp-412102088439.asia-northeast1.run.app/

---

## v20260501-0003 — 2026-05-01

### 🌐 公開URL確定 — `/grave-lp/` → `/lp-ohaka/`

**変更内容**
- canonical URL: `/grave-lp/` → `/lp-ohaka/`
- OGP url: 同上
- 構造化データ JSON-LD（LocalBusiness logo URL）: `/lp-ohaka/images/en_logo.png`
- sitemap.xml: 全URL `/lp-ohaka/` に更新
- robots.txt の Sitemap ディレクティブ更新

**運用構成**
- WordPress（en1150.co.jp）に PHP リバースプロキシを配置
- `/lp-ohaka/` 配下への全リクエストを Cloud Run へ転送
- 別パッケージ: `en1150-lp-ohaka-proxy_v20260501-0001.zip`

---

## v20260501-0002 — 2026-05-01

### 🎨 公式ロゴ実装

- Header / Footer のテキストロゴを公式ロゴ画像（en_logo.png）に差し替え
- 元SVG（86KB / base64埋込PNG）を最適化 → **12KB のクリーン PNG** に変換
- Header: 80×36px、Footer: 120×54px（CSS filterで白色反転）
- preload に追加（LCP最適化）

---

## v20260501-0001 — 2026-05-01

### 🎉 初回リリース

- 鹿児島・有限会社縁向け お墓じまい専用LP（モバイルファースト）
- 単一HTML + 画像分離型（Cloud Run 配信最適化）
- 構造化データ4種、OGP、AI bot許可、九州7県MEO対応
- Cloud Run / php:8.1-apache / PORT=8080
- Cloud Build CI/CD（asia-northeast1）

---

## ロードマップ

### v20260501-0004（次回想定）
- [ ] 問い合わせフォーム実装（Cloudflare Worker + Resend API）
- [ ] GA4 / GTM タグ設置
- [ ] CTA別クリック計測
- [ ] WebP/AVIF対応

### 将来想定
- [ ] LP A/Bテスト基盤
- [ ] LINE Messaging API 直接連携
- [ ] 多言語対応（英語 / 中国語）
