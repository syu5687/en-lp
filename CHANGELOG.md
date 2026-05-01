# CHANGELOG — 縁 お墓じまいLP

バージョン管理規則: `v{YYYYMMDD}-{NNNN}`（同日内連番）

---

## v20260501-0002 — 2026-05-01

### 🎨 公式ロゴ実装

**変更内容**
- Header / Footer のテキストロゴを公式ロゴ画像（en_logo.png）に差し替え
- 元SVG（86KB / base64埋込PNG）を最適化 → **12KB のクリーン PNG** に変換
  - PIL で 240×108px にリサイズ
  - pngquant で品質80-95範囲で再圧縮（86%削減）
- Header: 80×36px で左寄せ表示
- Footer: 120×54px、CSS filter で白色反転（暗背景対応）
- preload 対象に追加（LCP最適化）
- 構造化データ（LocalBusiness）の logo URL を新パスに更新

---

## v20260501-0001 — 2026-05-01

### 🎉 初回リリース

**サイト構成**
- 鹿児島・有限会社縁向け お墓じまい専用LP（モバイルファースト）
- 単一HTML + 画像分離型（Cloud Run 配信最適化）

**実装機能**
- Hero（3世代家族写真）+ メインCTA
- お悩み訴求（3ペイン）
- お墓じまいとは / 大切なポイント
- 追加料金なしの安心パック（4項目 / 330,000円〜）
- 中間CTA
- 施工事例 ビフォーアフター ×2
- 代表者メッセージ（staff1写真）
- 改葬申請サポート + アドバイザーカード（staff2写真）
- 対応エリア（九州7県 / 都市別）
- FAQ ×5（LLMO対応）
- 最終CTA（電話 / LINE / メール 3経路）
- フッター + 固定下部ナビ

**SEO/MEO/LLMO**
- 構造化データ4種: LocalBusiness / Service / FAQPage / BreadcrumbList
- OGP / Twitter Card / canonical
- robots.txt（GPTBot / ClaudeBot / PerplexityBot / Google-Extended 許可）
- sitemap.xml（image extension対応）

**パフォーマンス**
- Hero画像 preload + fetchpriority="high"
- スタッフ画像は loading="lazy" + decoding="async"
- gzip圧縮 + 長期キャッシュ（.htaccess）
- Cache-Control: immutable（画像/フォント1年）
- HTML軽量化: 55.6KB / 画像合計114.7KB

**セキュリティ**
- X-Content-Type-Options / X-Frame-Options
- Referrer-Policy / Permissions-Policy / HSTS

**インフラ**
- Cloud Run / php:8.1-apache / PORT=8080
- Cloud Build CI/CD（asia-northeast1）
- min-instances=0（コスト最適化）
- max-instances=10 / concurrency=80

---

## ロードマップ

### v20260501-0003（次回想定）
- [ ] 問い合わせフォーム実装（Cloudflare Worker + Resend API）
- [ ] GA4 / GTM タグ設置
- [ ] CTA別クリック計測
- [ ] WebP/AVIF対応

### 将来想定
- [ ] LP A/Bテスト基盤
- [ ] LINE Messaging API 直接連携
- [ ] 多言語対応（英語 / 中国語）
