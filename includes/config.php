<?php
/**
 * 有限会社 縁 — サイト共通設定
 * すべてのページ・共通パーツから読み込む基本情報を一元管理。
 */

// ---- GCP / Firebase ----
// 空文字なら Cloud Run のメタデータサーバから自動取得。ローカル等で固定したい場合のみ記入。
const GCP_PROJECT_ID = '';

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
  ['label' => 'ご供養について', 'href' => '/gokuyou/'],
  ['label' => 'スタッフ紹介',  'href' => '/staff/'],
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
