<?php
/**
 * 管理画面の設定。
 *
 * データ永続化は Firestore（admin/includes/store.php + includes/firestore.php）。
 * Cloud Run のサービスアカウント認証で動作するため、鍵ファイルは不要です。
 * セットアップ手順は FIREBASE_SETUP.md を参照。
 */

// ▼ 管理画面ログインパスワード
//   標準運用ルール（{productname}{year}方式）に基づき初期値を "en2026" に設定。
//   本番運用前に必ず変更してください。
//   生成例: php -r "echo password_hash('新しいパスワード', PASSWORD_DEFAULT);"
const ADMIN_PASSWORD_HASH = '$2y$12$UYELP7V6V0lO1jZnNNMvO.4tHz.YxUbeyoPRj9qvCotObLt47aotW'; // = "en2026"（要変更）

const ADMIN_SESSION_KEY = 'en_admin_authed';
