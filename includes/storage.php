<?php
/**
 * 画像ストレージ層（Cloud Storage / ローカル開発フォールバック）
 * - Cloud Run 本番: GCS バケット（GCS_BUCKET）へ REST で保存・取得。
 *   認証は Firestore と同じサービスアカウント（fs_token）を使用。
 *   必要権限: roles/storage.objectAdmin
 * - ローカル開発（トークン取得不可時）: data/uploads/ 配下に保存。
 * 公開配信は /img/<object> → img.php プロキシ経由（長期キャッシュ付き）。
 */
require_once __DIR__ . '/firestore.php';

/** ローカル保存ディレクトリ */
function storage_local_dir(): string {
  return __DIR__ . '/../data/uploads';
}

/** トークン（取得できなければ null＝ローカルモード） */
function storage_token(): ?string {
  static $tok = false;
  if ($tok !== false) return $tok;
  try { return $tok = fs_token(); } catch (Throwable $e) { return $tok = null; }
}

/** オブジェクト名の安全チェック（生成規則に一致するもののみ許可） */
function storage_valid_object(string $object): bool {
  return (bool)preg_match('#^[a-z0-9_\-]+/[A-Za-z0-9/_\-.]+\.(jpe?g|png|gif|webp)$#i', $object)
      && strpos($object, '..') === false;
}

/** 保存。戻り値: ['ok'=>bool, 'mode'=>'gcs'|'local', 'error'=>string] */
function storage_put(string $object, string $data, string $contentType): array {
  $tok = storage_token();
  if ($tok === null) {
    // ローカル開発モード
    $path = storage_local_dir() . '/' . $object;
    if (!is_dir(dirname($path))) @mkdir(dirname($path), 0775, true);
    return @file_put_contents($path, $data) !== false
      ? ['ok' => true, 'mode' => 'local', 'error' => '']
      : ['ok' => false, 'mode' => 'local', 'error' => 'ローカル保存に失敗しました'];
  }
  $url = 'https://storage.googleapis.com/upload/storage/v1/b/' . rawurlencode(GCS_BUCKET)
       . '/o?uploadType=media&name=' . rawurlencode($object);
  $raw = @file_get_contents($url, false, stream_context_create(['http' => [
    'method'        => 'POST',
    'header'        => "Authorization: Bearer $tok\r\nContent-Type: $contentType\r\n",
    'content'       => $data,
    'ignore_errors' => true,
    'timeout'       => 30,
  ]]));
  $j = json_decode((string)$raw, true);
  if (!empty($j['name'])) return ['ok' => true, 'mode' => 'gcs', 'error' => ''];
  $msg = $j['error']['message'] ?? 'GCSへの保存に失敗しました（バケット ' . GCS_BUCKET . ' の存在と権限を確認）';
  return ['ok' => false, 'mode' => 'gcs', 'error' => $msg];
}

/** 取得。戻り値: 画像バイナリ or null */
function storage_get(string $object): ?string {
  // 1) ローカル（開発時・またはローカル保存分）
  $path = storage_local_dir() . '/' . $object;
  if (is_file($path)) return (string)file_get_contents($path);
  // 2) GCS
  $tok = storage_token();
  if ($tok === null) return null;
  $url = 'https://storage.googleapis.com/storage/v1/b/' . rawurlencode(GCS_BUCKET)
       . '/o/' . rawurlencode($object) . '?alt=media';
  $ctx = stream_context_create(['http' => [
    'header'        => "Authorization: Bearer $tok\r\n",
    'ignore_errors' => true,
    'timeout'       => 20,
  ]]);
  $raw = @file_get_contents($url, false, $ctx);
  // エラー時はJSONが返る（先頭が '{' なら失敗と判定）
  if ($raw === false || $raw === '' || $raw[0] === '{') return null;
  return $raw;
}

/** 削除（成功可否のみ） */
function storage_delete(string $object): bool {
  $tok = storage_token();
  if ($tok === null) {
    $path = storage_local_dir() . '/' . $object;
    return is_file($path) ? @unlink($path) : false;
  }
  $url = 'https://storage.googleapis.com/storage/v1/b/' . rawurlencode(GCS_BUCKET)
       . '/o/' . rawurlencode($object);
  $raw = @file_get_contents($url, false, stream_context_create(['http' => [
    'method' => 'DELETE',
    'header' => "Authorization: Bearer $tok\r\n",
    'ignore_errors' => true,
    'timeout' => 15,
  ]]));
  return $raw !== false && trim((string)$raw) === '';
}
