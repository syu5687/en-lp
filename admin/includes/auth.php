<?php
/** 認証ガード。各管理ページの先頭で require する。 */
require_once __DIR__ . '/../config.php';
session_start();
if (empty($_SESSION[ADMIN_SESSION_KEY])) {
  header('Location: /admin/login.php');
  exit;
}
