<?php
/**
 * ブログ記事の「いいね」データ層。
 *
 * コレクション : blog_likes
 * ドキュメントID: 記事ID（slug）
 * フィールド    : count（integer・累計）/ updated_at（timestamp・最終更新）
 *
 * ── コスト設計（frugal-cloud-architecture 準拠）─────────────────────
 *  1) 公開ページの読み取りは en_cache('blog_like_counts', BLOG_LIKE_CACHE_TTL) で
 *     「全件まとめて1回」だけ読む。記事ごとに読まないため、読み取り回数がPVに比例しない。
 *  2) 書き込み時にキャッシュを破棄しない。破棄すると「いいね1回 → 次のPVで全件再読込」に
 *     なり、読み取りがいいね数に比例してしまう。押した本人の画面はAPIの戻り値でJSが
 *     即時更新するため、本人は常に正しい数を見る（他の人には最大TTL分だけ古い数が出る）。
 *  3) 書き込みは increment の原子更新1回のみ。連打・ボットは blog_like_throttle_ok()
 *     （インスタンス単位のトークンバケット／分・日の二段）で上限を設ける。
 *  4) ランキングは 1) のキャッシュを並べ替えるだけ。Firestoreへの追加クエリは行わない。
 * ──────────────────────────────────────────────
 *
 * 個人情報は保存しない。IP・UA・ユーザー識別子をFirestoreへ書かない。
 * 二重押しの抑止はブラウザ側（localStorage）とCookieのみで行う。
 */
require_once __DIR__ . '/firestore.php';
require_once __DIR__ . '/../admin/includes/store.php'; // en_cache / news_published

const BLOG_LIKES_COLLECTION = 'blog_likes';

/* ============================================================
   記事IDの検証
   ============================================================ */

/** Firestoreのドキュメント名として安全なIDか（形式チェックのみ） */
function blog_like_id_format_ok(string $id): bool {
  return $id !== '' && strlen($id) <= 120 && (bool)preg_match('/\A[A-Za-z0-9._\-]+\z/', $id);
}

/**
 * 公開中の記事IDの集合。
 * news_published() は en_cache('news_published', 900) 済みなので追加の読み取りは発生しない。
 * JSONシード（news.json / blog-posts.json）はファイル読み込みのみでコスト0。
 */
function blog_like_allowed_ids(): array {
  return en_cache('blog_like_allow_ids', 1800, function () {
    $ids = [];
    try {
      foreach (news_published() as $it) {
        $i = (string)($it['id'] ?? '');
        if ($i !== '') $ids[$i] = true;
      }
    } catch (Throwable $e) { /* Firestore障害時はシードだけで判定する */ }
    foreach (['/../data/news.json', '/../data/blog-posts.json'] as $src) {
      $seed = @json_decode((string)@file_get_contents(__DIR__ . $src), true);
      foreach (($seed['items'] ?? []) as $it) {
        if (empty($it['published'])) continue;
        $i = (string)($it['id'] ?? '');
        if ($i !== '') $ids[$i] = true;
      }
    }
    return $ids;
  });
}

/** 公開記事として実在するIDか */
function blog_like_id_ok(string $id): bool {
  if (!blog_like_id_format_ok($id)) return false;
  $allow = blog_like_allowed_ids();
  return isset($allow[$id]);
}

/* ============================================================
   読み取り
   ============================================================ */

/** blog_likes の生ドキュメントを id => ['count'=>int,'updated_at'=>string] に変換 */
function blog_like_parse_docs(array $docs): array {
  $out = [];
  foreach ($docs as $d) {
    $id = isset($d['name']) ? basename((string)$d['name']) : '';
    if ($id === '') continue;
    $f  = $d['fields'] ?? [];
    // fs_from_doc は timestampValue を扱わないため、ここで直接読む
    $out[$id] = [
      'count'      => max(0, (int)($f['count']['integerValue'] ?? 0)),
      'updated_at' => (string)($f['updated_at']['timestampValue'] ?? ''),
    ];
  }
  return $out;
}

/** 全記事のいいね数（キャッシュ経由・公開ページ用）。id => count */
function blog_like_counts(): array {
  if (!BLOG_LIKE_ENABLED) return [];
  try {
    return en_cache('blog_like_counts', BLOG_LIKE_CACHE_TTL, function () {
      $map = [];
      foreach (blog_like_parse_docs(fs_list_all(BLOG_LIKES_COLLECTION)) as $id => $v) {
        $map[$id] = $v['count'];
      }
      return $map;
    });
  } catch (Throwable $e) {
    // 障害時はいいね欄を出さない。ページは白紙にしない。
    error_log('[blog-likes] counts failed: ' . $e->getMessage());
    return [];
  }
}

/** 1記事のいいね数（キャッシュ経由） */
function blog_like_count(string $id): int {
  $m = blog_like_counts();
  return max(0, (int)($m[$id] ?? 0));
}

/** 管理画面用：キャッシュを使わない生読み。id => ['count','updated_at'] */
function blog_like_counts_fresh(): array {
  return blog_like_parse_docs(fs_list_all(BLOG_LIKES_COLLECTION));
}

/**
 * ランキング（いいね数の多い順）。キャッシュ済みの集計を並べ替えるだけ。
 * @return array 0 => ['id'=>..., 'count'=>...]
 */
function blog_like_ranking(int $limit = 0): array {
  $m = blog_like_counts();
  arsort($m, SORT_NUMERIC);
  $out = [];
  foreach ($m as $id => $c) {
    if ($c <= 0) continue;
    $out[] = ['id' => $id, 'count' => (int)$c];
    if ($limit > 0 && count($out) >= $limit) break;
  }
  return $out;
}

/* ============================================================
   書き込み
   ============================================================ */

/**
 * いいね数を原子的に増減する。
 * DocumentTransform を使うため、ドキュメントが無ければ自動で作られる（count は 0 からの加算）。
 * 1コミット＝1ドキュメント＝1書き込み。
 *
 * @return int|null 更新後の件数。失敗時は null
 */
function blog_like_bump(string $id, int $delta): ?int {
  if ($delta !== 1 && $delta !== -1) return null;
  $name = 'projects/' . fs_project_id() . '/databases/(default)/documents/'
        . BLOG_LIKES_COLLECTION . '/' . $id;
  $res = fs_request('POST', 'documents:commit', [
    'writes' => [[
      'transform' => [
        'document'       => $name,
        'fieldTransforms' => [
          ['fieldPath' => 'count',      'increment' => ['integerValue' => (string)$delta]],
          ['fieldPath' => 'updated_at', 'setToServerValue' => 'REQUEST_TIME'],
        ],
      ],
    ]],
  ]);
  if (!is_array($res) || !empty($res['error'])) {
    error_log('[blog-likes] bump failed: ' . json_encode($res['error'] ?? 'no response', JSON_UNESCAPED_UNICODE));
    return null;
  }
  $v = $res['writeResults'][0]['transformResults'][0]['integerValue'] ?? null;
  return $v === null ? null : max(0, (int)$v);
}

/* ============================================================
   連打・ボット対策（インスタンス単位のトークンバケット）
   ============================================================
   Cloud Run はステートレスかつ複数インスタンスなので、ここでの上限は
   「1インスタンスあたり」になる。max-instances を掛けた値が最悪値。
   Firestoreの書き込み無料枠（2万/日）に対する保険として置いている。
   本格的な遮断は Cloudflare 側のレートリミットで行う（運用手順書に記載）。
*/
function blog_like_throttle_ok(): bool {
  $f   = sys_get_temp_dir() . '/en-like-bucket.json';
  $now = time();
  $day = date('Y-m-d');
  $d   = @json_decode((string)@file_get_contents($f), true);
  if (!is_array($d)) $d = [];
  if (($d['day'] ?? '') !== $day) { $d['day'] = $day; $d['dn'] = 0; }
  if ((int)($d['t'] ?? 0) + 60 < $now) { $d['t'] = $now; $d['n'] = 0; }
  if ((int)($d['n'] ?? 0) >= BLOG_LIKE_MAX_PER_MINUTE) return false;
  if ((int)($d['dn'] ?? 0) >= BLOG_LIKE_MAX_PER_DAY)   return false;
  $d['n']  = (int)($d['n'] ?? 0) + 1;
  $d['dn'] = (int)($d['dn'] ?? 0) + 1;
  @file_put_contents($f, json_encode($d), LOCK_EX);
  return true;
}

/* ============================================================
   Cookie（サーバー側の二重押し抑止。個人情報は入れない）
   ============================================================ */

const BLOG_LIKE_COOKIE = 'en_lk';

/** 記事IDの短縮ハッシュ（Cookieを小さく保つため） */
function blog_like_key(string $id): string { return substr(sha1($id), 0, 8); }

/** Cookieに記録済みの記事キー一覧 */
function blog_like_cookie_keys(): array {
  $raw = (string)($_COOKIE[BLOG_LIKE_COOKIE] ?? '');
  if ($raw === '') return [];
  $ks = array_filter(explode('.', $raw), fn($k) => (bool)preg_match('/\A[0-9a-f]{8}\z/', $k));
  return array_values(array_unique($ks));
}

/** Cookieを書き戻す（直近60件まで。古いものから捨てる） */
function blog_like_cookie_save(array $keys): void {
  $keys = array_slice(array_values(array_unique($keys)), -60);
  setcookie(BLOG_LIKE_COOKIE, implode('.', $keys), [
    'expires'  => time() + 365 * 86400,
    'path'     => '/',
    'samesite' => 'Lax',
    'httponly' => false, // 画面側で押下済み表示に使うため、JSから読めるようにする
    'secure'   => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
               || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https'),
  ]);
}
