<?php
/**
 * データアクセス層（Firestore版）
 * コレクション: news / ドキュメントID = 記事ID
 * 画面側は news_all / news_find / news_upsert / news_delete を呼ぶだけ（旧JSON版と同一API）。
 */
require_once __DIR__ . '/../../includes/firestore.php';

const NEWS_COLLECTION = 'news';


/* ============================================================
   読み取りキャッシュ（Firestore無料枠の節約）
   公開ページ向けの一覧取得を一時ファイルにキャッシュし、
   Firestoreの読み取り回数を大幅に削減する。
   - 通常: TTL内はFirestoreへアクセスしない
   - 障害時（クォータ超過等）: 期限切れキャッシュがあればそれを表示（サイトを止めない）
   - 管理画面で保存・削除すると該当キャッシュは即時破棄
   ============================================================ */
function en_cache(string $key, int $ttl, callable $fn) {
  $f = sys_get_temp_dir() . '/en-fscache-' . preg_replace('/[^a-zA-Z0-9_\-]/', '_', $key) . '.json';
  $mt = @filemtime($f);
  if ($mt !== false && time() - $mt < $ttl) {
    $d = @json_decode((string)@file_get_contents($f), true);
    if (is_array($d) && array_key_exists('v', $d)) return $d['v'];
  }
  try {
    $v = $fn();
  } catch (Throwable $e) {
    $d = @json_decode((string)@file_get_contents($f), true); // 障害時は古いキャッシュで継続
    if (is_array($d) && array_key_exists('v', $d)) return $d['v'];
    throw $e;
  }
  @file_put_contents($f, json_encode(['v' => $v], JSON_UNESCAPED_UNICODE), LOCK_EX);
  return $v;
}
function en_cache_bust(string ...$keys): void {
  foreach ($keys as $k) {
    @unlink(sys_get_temp_dir() . '/en-fscache-' . preg_replace('/[^a-zA-Z0-9_\-]/', '_', $k) . '.json');
  }
}

function news_all(): array {
  $items = [];
  foreach (fs_list_all(NEWS_COLLECTION) as $doc) $items[] = fs_from_doc($doc);
  return $items;
}

function news_find(string $id): ?array {
  return en_cache('news_one_' . $id, 900, function () use ($id) {
    $res = fs_request('GET', 'documents/' . NEWS_COLLECTION . '/' . rawurlencode($id));
    if (!empty($res['error']) || empty($res['fields'])) return null;
    return fs_from_doc($res);
  });
}

function news_upsert(array $item): bool {
  $id = $item['id'] ?? '';
  unset($item['id']); // idはドキュメント名で管理
  $res = fs_request(
    'PATCH',
    'documents/' . NEWS_COLLECTION . '/' . rawurlencode($id),
    ['fields' => fs_to_fields($item)]
  );
  en_cache_bust('news_published', 'news_one_' . $id);
  return empty($res['error']);
}

function news_delete(string $id): bool {
  $res = fs_request('DELETE', 'documents/' . NEWS_COLLECTION . '/' . rawurlencode($id));
  en_cache_bust('news_published', 'news_one_' . $id);
  return empty($res['error']);
}

/** 公開記事のみ（日付降順）— 公開ページ用 */
function news_published(int $limit = 0): array {
  $items = en_cache('news_published', 900, function () {
    $x = array_values(array_filter(news_all(), fn($i) => !empty($i['published'])));
    usort($x, fn($a, $b) => strcmp($b['date'] ?? '', $a['date'] ?? ''));
    return $x;
  });
  return $limit > 0 ? array_slice($items, 0, $limit) : $items;
}

/* ============================================================
   お客様の声（voices コレクション）
   フィールド: service（ご依頼内容）/ title（見出し）/ reason（きっかけ）
             / impression（ご感想）/ who（属性）/ date / published
   ============================================================ */
const VOICES_COLLECTION = 'voices';

function voices_all(): array {
  $items = [];
  foreach (fs_list_all(VOICES_COLLECTION) as $doc) $items[] = fs_from_doc($doc);
  return $items;
}

function voice_find(string $id): ?array {
  $res = fs_request('GET', 'documents/' . VOICES_COLLECTION . '/' . rawurlencode($id));
  if (!empty($res['error']) || empty($res['fields'])) return null;
  return fs_from_doc($res);
}

function voice_upsert(array $item): bool {
  $id = $item['id'] ?? '';
  unset($item['id']);
  $res = fs_request('PATCH', 'documents/' . VOICES_COLLECTION . '/' . rawurlencode($id), ['fields' => fs_to_fields($item)]);
  en_cache_bust('voices_published');
  return empty($res['error']);
}

function voice_delete(string $id): bool {
  $res = fs_request('DELETE', 'documents/' . VOICES_COLLECTION . '/' . rawurlencode($id));
  en_cache_bust('voices_published');
  return empty($res['error']);
}

/** 公開のみ（日付降順）— 公開ページ用 */
function voices_published(int $limit = 0): array {
  $items = en_cache('voices_published', 900, function () {
    $x = array_values(array_filter(voices_all(), fn($i) => !empty($i['published'])));
    usort($x, fn($a, $b) => strcmp($b['date'] ?? '', $a['date'] ?? ''));
    return $x;
  });
  return $limit > 0 ? array_slice($items, 0, $limit) : $items;
}

/* ============================================================
   合同海洋散骨 実施予定日（goudou コレクション）
   フィールド: date（Y-m-d）/ sea（海域）/ status（受付中・残りわずか・受付終了）
             / note（備考）/ published
   ============================================================ */
const GOUDOU_COLLECTION = 'goudou';

function goudou_all(): array {
  $items = [];
  foreach (fs_list_all(GOUDOU_COLLECTION) as $doc) $items[] = fs_from_doc($doc);
  usort($items, fn($a, $b) => strcmp($a['date'] ?? '', $b['date'] ?? ''));
  return $items;
}

function goudou_upsert(array $item): bool {
  $id = $item['id'] ?? '';
  unset($item['id']);
  $res = fs_request('PATCH', 'documents/' . GOUDOU_COLLECTION . '/' . rawurlencode($id), ['fields' => fs_to_fields($item)]);
  en_cache_bust('goudou_all_pub');
  return empty($res['error']);
}

function goudou_delete(string $id): bool {
  $res = fs_request('DELETE', 'documents/' . GOUDOU_COLLECTION . '/' . rawurlencode($id));
  en_cache_bust('goudou_all_pub');
  return empty($res['error']);
}

/** 公開中かつ本日以降の開催日（日付昇順）— 公開ページ用 */
function goudou_upcoming(): array {
  $today = date('Y-m-d');
  $items = en_cache('goudou_all_pub', 300, fn() => array_values(array_filter(goudou_all(), fn($i) => !empty($i['published']))));
  return array_values(array_filter($items, fn($i) => ($i['date'] ?? '') >= $today));
}

/* ===== お問い合わせ受信（inquiries） ===== */
const INQUIRIES_COLLECTION = 'inquiries';

/** 受信内容を1件保存（/api/inquiry-log.php から使用） */
function inquiry_add(array $item): bool {
  $id = 'inq' . date('YmdHis') . '-' . bin2hex(random_bytes(3));
  $res = fs_request(
    'PATCH',
    'documents/' . INQUIRIES_COLLECTION . '/' . rawurlencode($id),
    ['fields' => fs_to_fields($item)]
  );
  return empty($res['error']);
}

/** 全受信（受信日時の降順） */
function inquiries_all(): array {
  $items = array_map('fs_from_doc', fs_list_all(INQUIRIES_COLLECTION));
  usort($items, fn($a, $b) => strcmp($b['received_at'] ?? '', $a['received_at'] ?? ''));
  return $items;
}
