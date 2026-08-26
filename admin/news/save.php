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

/**
 * リッチテキスト本文（body_html）のサニタイズ。
 * 許可タグ・許可属性のホワイトリスト方式。script等の危険な要素は除去する。
 */
function sanitize_body_html(string $html): string {
  $html = trim($html);
  if ($html === '') return '';

  $allowed = [ // タグ => 許可属性
    'p' => ['style'], 'br' => [], 'strong' => [], 'b' => [], 'em' => [], 'i' => [],
    'u' => [], 's' => [], 'sub' => [], 'sup' => [], 'blockquote' => [],
    'h2' => ['style'], 'h3' => ['style'], 'h4' => ['style'], 'h5' => ['style'],
    'ul' => [], 'ol' => [], 'li' => ['style'],
    'a' => ['href', 'target', 'rel'],
    'img' => ['src', 'alt', 'width', 'height', 'style'],
    'span' => ['style'],
    'table' => [], 'thead' => [], 'tbody' => [], 'tr' => [], 'td' => [], 'th' => [],
  ];
  // style属性はこのプロパティのみ許可
  $ok_style = static function (string $style): string {
    $keep = [];
    foreach (explode(';', $style) as $decl) {
      if (!str_contains($decl, ':')) continue;
      [$prop, $val] = array_map('trim', explode(':', $decl, 2));
      $prop = strtolower($prop);
      if (!in_array($prop, ['font-size', 'text-align', 'color', 'background-color', 'max-width'], true)) continue;
      if (!preg_match('/^[#\w\s.,%()-]+$/u', $val)) continue; // url() や expression() を弾く
      if (stripos($val, 'url') !== false || stripos($val, 'expression') !== false) continue;
      $keep[] = $prop . ':' . $val;
    }
    return implode(';', $keep);
  };
  $ok_url = static fn(string $u): bool =>
    (bool)preg_match('#^(https?://|/|mailto:|tel:)#i', trim($u));

  $doc = new DOMDocument();
  libxml_use_internal_errors(true);
  $doc->loadHTML('<?xml encoding="UTF-8"><div id="__rte_root__">' . $html . '</div>',
                 LIBXML_NOERROR | LIBXML_NOWARNING);
  libxml_clear_errors();
  $root = $doc->getElementById('__rte_root__');
  if (!$root) return '';

  $walk = static function (DOMNode $node) use (&$walk, $allowed, $ok_style, $ok_url): void {
    for ($i = $node->childNodes->length - 1; $i >= 0; $i--) {
      $child = $node->childNodes->item($i);
      if (!$child instanceof DOMElement) continue;
      $tag = strtolower($child->nodeName);
      if (in_array($tag, ['script', 'style', 'iframe', 'object', 'embed', 'form', 'link', 'meta'], true)) {
        $node->removeChild($child); // 危険要素は中身ごと削除
        continue;
      }
      $walk($child);
      if (!isset($allowed[$tag])) {
        // 許可外タグは剥がして中身だけ残す（div等）
        while ($child->firstChild) $node->insertBefore($child->firstChild, $child);
        $node->removeChild($child);
        continue;
      }
      // 属性のホワイトリスト適用
      for ($a = $child->attributes->length - 1; $a >= 0; $a--) {
        $attr = $child->attributes->item($a);
        $name = strtolower($attr->name);
        if (!in_array($name, $allowed[$tag], true)) { $child->removeAttribute($attr->name); continue; }
        $val = (string)$attr->value;
        if ($name === 'style') {
          $clean = $ok_style($val);
          if ($clean === '') $child->removeAttribute($attr->name);
          else $child->setAttribute('style', $clean);
        } elseif ($name === 'href' || $name === 'src') {
          if (!$ok_url($val)) $child->removeAttribute($attr->name);
        } elseif ($name === 'target') {
          if ($val !== '_blank') $child->removeAttribute($attr->name);
        }
      }
      if ($tag === 'a' && $child->getAttribute('target') === '_blank') {
        $child->setAttribute('rel', 'noopener');
      }
      if ($tag === 'img' && !$child->hasAttribute('src')) {
        $node->removeChild($child); // src の無い（=弾かれた）画像は削除
      }
    }
  };
  $walk($root);

  $out = '';
  foreach ($root->childNodes as $c) $out .= $doc->saveHTML($c);
  return trim($out);
}

$body_html = sanitize_body_html((string)($_POST['body_html'] ?? ''));
// 一覧の抜粋・meta description 用のプレーンテキスト本文
$body_plain = trim((string)($_POST['body'] ?? ''));
if ($body_plain === '' && $body_html !== '') {
  $body_plain = trim(preg_replace('/[ \t]*\n[ \t]*/', "\n",
    html_entity_decode(strip_tags(preg_replace('#<(br|/p|/h\d|/li)[^>]*>#i', "\n", $body_html)), ENT_QUOTES, 'UTF-8')));
}

$item = [
  'id'        => $id,
  'date'      => $_POST['date'] ?? date('Y-m-d'),
  'category'  => (isset($_POST['categories']) && is_array($_POST['categories']))
                   ? implode(', ', array_map('trim', $_POST['categories']))
                   : ($_POST['category'] ?? 'お知らせ'),
  'title'     => $_POST['title'] ?? '',
  'body'      => $body_plain,
  'body_html' => $body_html,
  'images'    => $images,
  'image'     => $images[0] ?? trim((string)($_POST['image'] ?? '')),
  'link'      => $_POST['link'] ?? '',
  'published' => !empty($_POST['published']),
];

// Firestore の PATCH は全置換のため、フォームに無い既存フィールドは引き継ぐ。
$existing = null;
try { $existing = news_find($id); } catch (Throwable $e) { $existing = null; }
if ($existing) {
  foreach ($existing as $k => $v) {
    if (!array_key_exists($k, $item)) $item[$k] = $v;
  }
  // JSが無効な環境等で body_html が送られなかった場合は既存のHTML本文を保持
  if ($body_html === '' && !isset($_POST['body_html']) && !empty($existing['body_html'])) {
    $item['body_html'] = $existing['body_html'];
  }
}
if ($item['body_html'] === '') unset($item['body_html']);

news_upsert($item);
header('Location: /admin/news/');
