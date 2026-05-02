# CHANGELOG — 縁 お墓じまいLP

バージョン管理規則: `v{YYYYMMDD}-{NNNN}`（同日内連番）

公開URL: **https://en1150.co.jp/lp-ohaka/**
バックエンド（Cloud Run）: https://en-lp-412102088439.asia-northeast1.run.app/

---

## v20260501-0004 — 2026-05-01

### 🚀 SEO/MEO/LLMO 完全対応版

**LLMO（AI検索最適化）強化**
- `llms.txt` 新規作成 — Anthropic / Perplexity 推奨のGEO標準ファイル
  - 会社情報・サービス料金・対応エリア・FAQ・主要ページをMarkdown形式で集約
  - AIモデルの context window に最適化された構造
- robots.txt の AI bot許可リストを2026年版に拡張（**18種類**）
  - OpenAI: GPTBot / ChatGPT-User / OAI-SearchBot
  - Anthropic: ClaudeBot / Claude-Web / anthropic-ai
  - Perplexity: PerplexityBot / Perplexity-User
  - Google: Google-Extended / Googlebot / Googlebot-Image
  - Microsoft: Bingbot / CCBot
  - Apple: Applebot / Applebot-Extended
  - Meta: FacebookBot / Meta-ExternalAgent / Meta-ExternalFetcher
  - その他: DuckAssistBot / YandexBot / Bytespider / Diffbot / ImagesiftBot
- **H1直下のエンティティ定義**（40-50字）追加 — LLM抽出向けプライマリ定義
- speakable Schema 追加 — Google Assistant / 音声検索対応

**SEO技術強化**
- `Article` 構造化データ追加 — datePublished/dateModified でフレッシュネスシグナル
- `Person` 構造化データ追加 — 代表者プロフィール、保有資格、E-E-A-T強化
- `WebSite + SearchAction` 構造化データ追加 — サイトリンク検索ボックス対応
- `hreflang="ja-JP"` + `hreflang="x-default"` 明示
- OG画像を専用LP画像（hero-family.jpg）に変更（width/height/alt付き）
- Twitter Card image 追加
- article:published_time / article:modified_time メタタグ追加

**UX/アクセシビリティ強化**
- パンくずリスト視覚的実装（JSON-LD + 画面表示の両立）
- HTML Microdata（itemscope/itemprop）併用
- スキップリンク（メインコンテンツへ直接ジャンプ）追加
- aria-label / aria-current 強化

**ファイルサイズ**
- index.html: 55.4KB → 60.4KB（構造化データ・エンティティ定義追加分）
- llms.txt: 新規（約3KB）
- robots.txt: 0.4KB → 1.5KB（AI bot リスト拡張）

---

## v20260501-0003 — 2026-05-01

### 🌐 公開URL確定 — `/grave-lp/` → `/lp-ohaka/`

- canonical / OGP / JSON-LD logo URL を `/lp-ohaka/` に更新
- sitemap.xml 全URL更新
- WordPress配下にPHPリバースプロキシ設置（別パッケージ）

---

## v20260501-0002 — 2026-05-01

### 🎨 公式ロゴ実装

- Header / Footer のテキストロゴを公式ロゴ画像（en_logo.png）に差し替え
- 元SVG（86KB）→ 最適化PNG（12KB）= 86%削減
- preload に追加（LCP最適化）

---

## v20260501-0001 — 2026-05-01

### 🎉 初回リリース

- 鹿児島・有限会社縁向け お墓じまい専用LP（モバイルファースト）
- 単一HTML + 画像分離型（Cloud Run 配信最適化）
- Cloud Run / php:8.1-apache / PORT=8080
- Cloud Build CI/CD（asia-northeast1）

---

## ロードマップ

### v20260501-0005（次回想定 — コンテンツ拡充）
- [ ] 「お墓じまいの流れ」詳細ページ（Article schema + HowTo schema）
- [ ] 「費用相場」解説ページ（Article schema）
- [ ] 「離檀料」「閉眼供養」「改葬許可申請」用語解説ページ群
- [ ] 市区町村別ランディングページ（鹿児島市・福岡市・佐賀市等）
- [ ] お客様の声 / Review 構造化データ

### v20260501-0006（フォーム実装）
- [ ] 問い合わせフォーム（Cloudflare Worker + Resend API）
- [ ] LINE Messaging API 直接連携
- [ ] GA4 / GTM タグ
- [ ] CTA別クリック計測

### 将来想定
- [ ] WebP/AVIF対応
- [ ] LP A/Bテスト基盤
- [ ] 多言語対応（英語）
