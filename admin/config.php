<?php
/**
 * 管理画面の設定。
 *
 * データ永続化は Firestore（admin/includes/store.php + includes/firestore.php）。
 * Cloud Run のサービスアカウント認証で動作するため、鍵ファイルは不要です。
 * セットアップ手順は FIREBASE_SETUP.md を参照。
 */

// ▼ 管理画面ログインパスワード（必ず変更してください）
//   生成例: php -r "echo password_hash('新しいパスワード', PASSWORD_DEFAULT);"
const ADMIN_PASSWORD_HASH = '$2y$10$DPQDpsY1xsHpaJ573MKX0e/VrgzA3iIRMRnFhmOt7UPnN/fYboFxi'; // = "change-me"（必ず変更）

const ADMIN_SESSION_KEY = 'en_admin_authed';
