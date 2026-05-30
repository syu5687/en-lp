<?php
require __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../includes/store.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['id'])) {
  news_delete($_POST['id']);
}
header('Location: /admin/news/');
