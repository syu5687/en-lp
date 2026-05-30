<?php
require_once __DIR__ . '/config.php';
session_start();
$_SESSION = [];
session_destroy();
header('Location: /admin/login.php');
exit;
