<?php
/**
 * 有限会社 縁 — サイト共通設定
 * すべてのページ・共通パーツから読み込む基本情報を一元管理。
 */

// ---- アプリバージョン ----
const APP_VERSION = 'v20260713-0039';

// ---- GCP / Firebase ----
// クロスプロジェクト構成: en-lp の Cloud Run=412102088439 / Firestore=en-hp-lp（番号941919710488）。
// ★ Firestore REST は「プロジェクトID(en-hp-lp)」を指定する。番号だと404になる事象があったためID指定が正
//   （引き継ぎ資料 §1「Firestore接続の仕組み」の決定事項）。
//   ※ 万一 /admin/health.php で「プロジェクトID取得」がNGの場合はこの値を確認すること。
const GCP_PROJECT_ID = 'en-hp-lp';

// ---- GA4 ----
// 測定ID（ページ埋め込み用・G-XXXXXXXXXX）。設定すると全ページにgtag.jsが入る。
// ※ 399545209 はプロパティID（数値）でタグには使えません。下に測定IDを入れてください。
const GA4_MEASUREMENT_ID = 'G-BST60JN5FD'; // 例: 'G-XXXXXXXXXX'
// プロパティID（GA4 Data API / レポート参照用の数値ID）
const GA4_PROPERTY_ID = '399545209';

// ---- お問い合わせ（Cloudflare Worker + Resend）----
// Workerをデプロイ後、その公開URLをここに設定。
const CONTACT_WORKER_URL = 'https://en-contact.YOUR-SUBDOMAIN.workers.dev';

// ---- サイト基本情報（NAP / ブランド）----
const SITE = [
  'name'        => '有限会社 縁',
  'name_kana'   => 'えん',
  'tagline'     => '鹿児島の供養トータルサポート',
  'url'         => 'https://en1150.co.jp',
  'tel'         => '099-801-3637',
  'email'       => 'info@en1150.co.jp',
  'zip'         => '891-0150',
  'address'     => '鹿児島県鹿児島市坂之上7丁目7-3',
  'hours'       => 'Mo-Sa 09:00-18:00',
  'line_url'    => 'https://line.me/R/ti/p/%40bkx9825r',
  'logo'        => '/assets/img/en.svg',
];

// ---- グローバルナビ（1か所で管理 → 全ページ自動反映）----
const NAV = [
  ['label' => 'サービス一覧',   'href' => '/service/'],
  ['label' => 'お客様の声',    'href' => '/voice/'],
  ['label' => '終活新聞',      'href' => '/blog/'],
  ['label' => 'よくある質問',   'href' => '/gokuyou/'],
  ['label' => 'スタッフ紹介',   'href' => '/staff/'],
];

// ---- サービス詳細ページ一覧（service/ ハブとフッターで使用）----
const SERVICES = [
  ['slug' => 'kaiyou-sou',      'title' => '海洋葬（海洋散骨）', 'price' => '38,500円〜'],
  ['slug' => 'powder-cleaning', 'title' => '粉骨・洗骨',        'price' => '5,000円〜'],
  ['slug' => 'grave',           'title' => 'お墓じまい',        'price' => 'ご相談無料'],
  ['slug' => 'teien-sou',       'title' => '樹木葬',            'price' => 'お問合せ'],
  ['slug' => 'temoto-kuyou',    'title' => 'お手元供養',        'price' => '各種対応'],
  ['slug' => 'jewelry-reform',  'title' => 'JEWELRYリフォーム', 'price' => 'お見積り無料'],
  ['slug' => 'pet-kaiyou-sou',  'title' => 'ペット供養',        'price' => 'お問合せ'],
  ['slug' => 'ihinseiri',       'title' => '遺品整理',          'price' => 'お問合せ'],
  ['slug' => 'hikkoshi',        'title' => 'お墓のお引越し',    'price' => 'ご相談無料'],
];

// ---- ブログ・お知らせのカテゴリ（管理画面の登録候補・現行サイト準拠）----
const BLOG_CATEGORIES = [
  '366',
  'teraumi',
  'お墓・納骨堂',
  'お客様のご質問にお答えします',
  'お悩み相談',
  'お手元供養',
  'お知らせ',
  'ご遺骨パウダー（粉骨）・洗骨',
  'スタッフ紹介',
  'セミナー・終活',
  'ブログ',
  'ゆかりの会',
  '実績日記',
  '未分類',
  '法要クルーズ',
  '海洋散骨体験クルーズ',
  '海洋葬(海洋散骨)',
];

/** HTMLエスケープ簡易ヘルパー */
function h(?string $s): string {
  return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
}
