<?php
require __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../includes/store.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: /admin/news/'); exit; }
$id = trim($_POST['id'] ?? '');
if ($id === '') $id = date('Ymd') . '-' . substr(uniqid(), -4);
// 画像（複数）。1枚目を image（サムネイル）として自動設定。
$images = [];
foreach ((array)($_POST['images'] ?? []) as $u) {
  $u = trim((string)$u);
  if ($u !== '' && preg_match('#^(/img/|/wp-content/|/assets/|https?://)#', $u)) $images[] = $u;
}

$item = [
  'id'        => $id,
  'date'      => $_POST['date'] ?? date('Y-m-d'),
  'category'  => (isset($_POST['categories']) && is_array($_POST['categories']))
                   ? implode(', ', array_map('trim', $_POST['categories']))
                   : ($_POST['category'] ?? 'お知らせ'),
  'title'     => $_POST['title'] ?? '',
  'body'      => $_POST['body'] ?? '',
  'images'    => $images,
  'image'     => $images[0] ?? trim((string)($_POST['image'] ?? '')),
  'link'      => $_POST['link'] ?? '',
  'published' => !empty($_POST['published']),
];
news_upsert($item);
header('Location: /admin/news/');
