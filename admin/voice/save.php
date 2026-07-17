<?php
require __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../includes/store.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: /admin/voice/'); exit; }
$id = trim($_POST['id'] ?? '');
if ($id === '') $id = date('Ymd') . '-' . substr(uniqid(), -4);
$item = [
  'id'         => $id,
  'date'       => $_POST['date'] ?? date('Y-m-d'),
  'service'    => $_POST['service'] ?? '',
  'title'      => $_POST['title'] ?? '',
  'reason'     => $_POST['reason'] ?? '',
  'impression' => $_POST['impression'] ?? '',
  'who'        => $_POST['who'] ?? '',
  'published'  => !empty($_POST['published']),
];
voice_upsert($item);
header('Location: /admin/voice/');
