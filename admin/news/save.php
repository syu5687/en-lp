<?php
require __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../includes/store.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: /admin/news/'); exit; }
$id = trim($_POST['id'] ?? '');
if ($id === '') $id = date('Ymd') . '-' . substr(uniqid(), -4);
$item = [
  'id'        => $id,
  'date'      => $_POST['date'] ?? date('Y-m-d'),
  'category'  => $_POST['category'] ?? 'お知らせ',
  'title'     => $_POST['title'] ?? '',
  'body'      => $_POST['body'] ?? '',
  'image'     => $_POST['image'] ?? '',
  'link'      => $_POST['link'] ?? '',
  'published' => !empty($_POST['published']),
];
news_upsert($item);
header('Location: /admin/news/');
