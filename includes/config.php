<?php
/**
 * 有限会社 縁 — サイト共通設定
 * すべてのページ・共通パーツから読み込む基本情報を一元管理。
 */

// ---- GCP / Firebase ----
// en-lp の Cloud Run は 412102088439、Firestore は 941919710488（クロスプロジェクト構成）。
// 自身のプロジェクト(412...)ではなく Firestore のある 941919710488 を明示する。
const GCP_PROJECT_ID = '941919710488';

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
  ['label' => 'サービス',     'href' => '/service/'],
  ['label' => 'ご供養について', 'href' => '/kuyou/'],
  ['label' => 'よくあるご質問', 'href' => '/gokuyou/'],
  ['label' => 'お客様の声',    'href' => '/voice/'],
  ['label' => 'ブログ',       'href' => '/blog/'],
  ['label' => 'お問い合わせ',  'href' => '/contact/'],
];

// ---- サービス詳細ページ一覧（service/ ハブとフッターで使用）----
const SERVICES = [
  ['slug' => 'kaiyou-sou',      'title' => '海洋葬（海洋散骨）', 'price' => '38,500円〜'],
  ['slug' => 'powder-cleaning', 'title' => '粉骨・洗骨',        'price' => '5,000円〜'],
  ['slug' => 'grave',           'title' => 'お墓じまい',        'price' => 'ご相談無料'],
  ['slug' => 'teien-sou',       'title' => '樹木葬',            'price' => 'お問合せ'],
  ['slug' => 'temoto-kuyou',    'title' => 'お手元供養',        'price' => '各種対応'],
  ['slug' => 'pet-kaiyou-sou',  'title' => 'ペット供養',        'price' => 'お問合せ'],
  ['slug' => 'ihinseiri',       'title' => '遺品整理',          'price' => 'お問合せ'],
  ['slug' => 'hikkoshi',        'title' => 'お墓のお引越し',    'price' => 'ご相談無料'],
];

/** HTMLエスケープ簡易ヘルパー */
function h(?string $s): string {
  return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
}
