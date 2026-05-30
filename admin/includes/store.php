<?php
/**
 * データアクセス層（Firestore版）
 * コレクション: news / ドキュメントID = 記事ID
 * 画面側は news_all / news_find / news_upsert / news_delete を呼ぶだけ（旧JSON版と同一API）。
 */
require_once __DIR__ . '/../../includes/firestore.php';

const NEWS_COLLECTION = 'news';

function news_all(): array {
  $res = fs_request('GET', 'documents/' . NEWS_COLLECTION);
  $items = [];
  foreach (($res['documents'] ?? []) as $doc) $items[] = fs_from_doc($doc);
  return $items;
}

function news_find(string $id): ?array {
  $res = fs_request('GET', 'documents/' . NEWS_COLLECTION . '/' . rawurlencode($id));
  if (!empty($res['error']) || empty($res['fields'])) return null;
  return fs_from_doc($res);
}

function news_upsert(array $item): bool {
  $id = $item['id'] ?? '';
  unset($item['id']); // idはドキュメント名で管理
  $res = fs_request(
    'PATCH',
    'documents/' . NEWS_COLLECTION . '/' . rawurlencode($id),
    ['fields' => fs_to_fields($item)]
  );
  return empty($res['error']);
}

function news_delete(string $id): bool {
  $res = fs_request('DELETE', 'documents/' . NEWS_COLLECTION . '/' . rawurlencode($id));
  return empty($res['error']);
}

/** 公開記事のみ（日付降順）— 公開ページ用 */
function news_published(int $limit = 0): array {
  $items = array_values(array_filter(news_all(), fn($i) => !empty($i['published'])));
  usort($items, fn($a, $b) => strcmp($b['date'] ?? '', $a['date'] ?? ''));
  return $limit > 0 ? array_slice($items, 0, $limit) : $items;
}
