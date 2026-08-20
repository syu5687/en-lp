<?php
/**
 * 有限会社 縁 — サイト共通設定
 * すべてのページ・共通パーツから読み込む基本情報を一元管理。
 */

// ---- アプリバージョン ----
const APP_VERSION = 'v20260713-0073';

// ---- 開発モード（構築中フラグ）----
// true の間は「ブラウザキャッシュを無効化」し「画面右上に小さくバージョンを表示」します。
// ★本番公開時は false に切り替えてください（通常のキャッシュに戻り、バッジも消えます）。
const DEV_MODE = true;

// 本番公開時（DEV_MODE=false）の公開HTMLキャッシュ秒数。
// 記事などの更新は最大この秒数で反映されます（静的CSS/JS/画像は .htaccess で長期キャッシュ）。
const HTML_CACHE_TTL = 300; // 5分

// キャッシュ制御（HTMLはPHPが返す。CSS/JS/画像は .htaccess 側で長期キャッシュ）。
if (PHP_SAPI !== 'cli' && !headers_sent()) {
  $req_path = $_SERVER['SCRIPT_NAME'] ?? ($_SERVER['REQUEST_URI'] ?? '');
  $is_admin = (strpos($req_path, '/admin/') !== false) || ($req_path === '/admin');
  if (DEV_MODE || $is_admin) {
    // 構築中 ＋ 管理画面：キャッシュを一切残さない（管理画面は本番でも常に非キャッシュ）。
    header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
    header('Pragma: no-cache');
    header('Expires: 0');
  } else {
    // 本番の公開ページ：短時間キャッシュ＋要再検証（完成後はキャッシュを有効化）。
    header('Cache-Control: public, max-age=' . HTML_CACHE_TTL . ', must-revalidate');
  }
}

// ---- GCP / Firebase ----
// クロスプロジェクト構成: en-lp の Cloud Run=412102088439 / Firestore=en-hp-lp（番号941919710488）。
// ★ Firestore REST は「プロジェクトID(en-hp-lp)」を指定する。番号だと404になる事象があったためID指定が正
//   （引き継ぎ資料 §1「Firestore接続の仕組み」の決定事項）。
//   ※ 万一 /admin/health.php で「プロジェクトID取得」がNGの場合はこの値を確認すること。
const GCP_PROJECT_ID = 'en-hp-lp';

// ---- Cloud Storage（管理画面の画像アップロード先）----
// Cloud Run はファイル書込みが永続しないため、画像は GCS バケットに保存し
// /img/... （img.php プロキシ）経由で配信します。
// ★ バケットが未作成の場合: gsutil mb -l asia-northeast1 gs://en-hp-lp-media
//   サービスアカウントに roles/storage.objectAdmin を付与してください。
//   （/admin/health.php の「Storage 読み書きテスト」で確認できます）
const GCS_BUCKET = 'en-hp-lp-media';

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
  'hours'       => 'Mo-Sa 09:00-18:00', // 構造化データ用（schema.org形式）
  'hours_jp'    => '月〜土 9:00〜18:00',   // 画面表示用
  'line_url'    => 'https://line.me/R/ti/p/%40bkx9825r',
  'logo'        => '/assets/img/en.svg',
];

// ---- グローバルナビ（1か所で管理 → 全ページ自動反映）----
const NAV = [
  ['label' => 'サービス一覧',   'href' => '/service/'],
  ['label' => '供養の選び方',   'href' => '/shindan/'],
  ['label' => 'お客様の声',    'href' => '/voice/'],
  ['label' => '終活新聞',      'href' => '/blog/'],
  ['label' => 'よくある質問',   'href' => '/gokuyou/'],
  ['label' => 'スタッフ紹介',   'href' => '/staff/'],
];

// ---- サービス詳細ページ一覧（service/ ハブとフッターで使用）----
const SERVICES = [
  ['slug' => 'kaiyou-sou',      'title' => '海洋葬（海洋散骨）', 'price' => '54,450円〜'],
  ['slug' => 'powder-cleaning', 'title' => '粉骨・洗骨',        'price' => '24,200円〜'],
  ['slug' => 'grave',           'title' => 'お墓じまい',        'price' => 'ご相談無料'],
  ['slug' => 'teien-sou',       'title' => '樹木葬',            'price' => 'お問合せ'],
  ['slug' => 'temoto-kuyou',    'title' => 'お手元供養',        'price' => '各種対応'],
  ['slug' => 'jewelry-reform',  'title' => 'JEWELRYリフォーム', 'price' => 'お見積り無料'],
  ['slug' => 'pet-kaiyou-sou',  'title' => 'ペット供養',        'price' => '54,450円'],
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

/** アセット用キャッシュバスター。構築中は毎回更新／本番はバージョン固定。 */
function asset_ver(): string {
  return DEV_MODE ? (string)time() : APP_VERSION;
}

/** 構築中バージョンバッジ（画面右上・DEV_MODE時のみ表示） */
function dev_badge_html(): string {
  if (!DEV_MODE) return '';
  return '<div id="dev-ver-badge" style="position:fixed;top:8px;right:8px;z-index:99999;'
       . 'background:rgba(21,112,158,.88);color:#fff;font:600 11px/1 ui-monospace,SFMono-Regular,Menlo,monospace;'
       . 'letter-spacing:.04em;padding:4px 9px;border-radius:6px;pointer-events:none;'
       . 'box-shadow:0 1px 4px rgba(0,0,0,.28)">' . h(APP_VERSION) . '</div>';
}
