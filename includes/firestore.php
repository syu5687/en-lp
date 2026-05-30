<?php
/**
 * Firestore REST クライアント（軽量・依存なし）
 * - 認証：Cloud Run のサービスアカウント（メタデータサーバ）を最優先。
 *         ローカル開発時は環境変数 GOOGLE_APPLICATION_CREDENTIALS の鍵JSONを使用。
 * - HTTP：file_get_contents + stream context（ext-curl 不要）
 * - 必要権限：Cloud Run のサービスアカウントに「Cloud Datastore ユーザー
 *   (roles/datastore.user)」を付与してください。
 */

require_once __DIR__ . '/config.php';

/** プロジェクトID（config優先 → メタデータ自動取得） */
function fs_project_id(): string {
  if (defined('GCP_PROJECT_ID') && GCP_PROJECT_ID) return GCP_PROJECT_ID;
  static $pid = null;
  if ($pid !== null) return $pid;
  $pid = @file_get_contents(
    'http://metadata.google.internal/computeMetadata/v1/project/project-id',
    false,
    stream_context_create(['http' => ['header' => "Metadata-Flavor: Google\r\n", 'timeout' => 2]])
  ) ?: (getenv('GCP_PROJECT_ID') ?: '');
  return $pid;
}

/** アクセストークン取得（リクエスト内キャッシュ） */
function fs_token(): string {
  static $tok = null;
  if ($tok !== null) return $tok;

  // 1) Cloud Run / GCE メタデータサーバ
  $meta = @file_get_contents(
    'http://metadata.google.internal/computeMetadata/v1/instance/service-accounts/default/token',
    false,
    stream_context_create(['http' => ['header' => "Metadata-Flavor: Google\r\n", 'timeout' => 2]])
  );
  if ($meta) {
    $j = json_decode($meta, true);
    if (!empty($j['access_token'])) return $tok = $j['access_token'];
  }

  // 2) ローカル：サービスアカウント鍵JSON（GOOGLE_APPLICATION_CREDENTIALS）
  $keyPath = getenv('GOOGLE_APPLICATION_CREDENTIALS');
  if ($keyPath && is_file($keyPath)) {
    $key = json_decode((string)file_get_contents($keyPath), true);
    $now = time();
    $claim = [
      'iss'   => $key['client_email'],
      'scope' => 'https://www.googleapis.com/auth/datastore',
      'aud'   => $key['token_uri'],
      'iat'   => $now,
      'exp'   => $now + 3600,
    ];
    $b64 = fn($d) => rtrim(strtr(base64_encode($d), '+/', '-_'), '=');
    $jwt = $b64(json_encode(['alg' => 'RS256', 'typ' => 'JWT'])) . '.' . $b64(json_encode($claim));
    openssl_sign($jwt, $sig, $key['private_key'], OPENSSL_ALGO_SHA256);
    $assertion = $jwt . '.' . $b64($sig);
    $resp = file_get_contents($key['token_uri'], false, stream_context_create(['http' => [
      'method'  => 'POST',
      'header'  => "Content-Type: application/x-www-form-urlencoded\r\n",
      'content' => http_build_query([
        'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
        'assertion'  => $assertion,
      ]),
      'ignore_errors' => true,
    ]]));
    $j = json_decode((string)$resp, true);
    if (!empty($j['access_token'])) return $tok = $j['access_token'];
  }

  throw new RuntimeException('Firestore: アクセストークンを取得できませんでした。');
}

/** Firestore REST 呼び出し。$path 例: "documents/news" / "documents/news/ID" */
function fs_request(string $method, string $path, ?array $body = null) {
  $base = 'https://firestore.googleapis.com/v1/projects/' . fs_project_id()
        . '/databases/(default)/' . ltrim($path, '/');
  $opts = ['http' => [
    'method'        => $method,
    'header'        => "Authorization: Bearer " . fs_token() . "\r\nContent-Type: application/json\r\n",
    'ignore_errors' => true,
    'timeout'       => 10,
  ]];
  if ($body !== null) $opts['http']['content'] = json_encode($body, JSON_UNESCAPED_UNICODE);
  $raw = file_get_contents($base, false, stream_context_create($opts));
  return json_decode((string)$raw, true);
}

/* ---- ドキュメント⇔連想配列 変換 ---- */

/** PHP配列 → Firestore fields */
function fs_to_fields(array $data): array {
  $f = [];
  foreach ($data as $k => $v) {
    if (is_bool($v))      $f[$k] = ['booleanValue' => $v];
    elseif (is_int($v))   $f[$k] = ['integerValue' => (string)$v];
    elseif (is_float($v)) $f[$k] = ['doubleValue'  => $v];
    else                  $f[$k] = ['stringValue'  => (string)$v];
  }
  return $f;
}

/** Firestore document → PHP配列（idを含む） */
function fs_from_doc(array $doc): array {
  $out = [];
  if (!empty($doc['name'])) $out['id'] = basename($doc['name']);
  foreach (($doc['fields'] ?? []) as $k => $v) {
    $out[$k] = $v['booleanValue'] ?? $v['stringValue']
             ?? (isset($v['integerValue']) ? (int)$v['integerValue'] : null)
             ?? $v['doubleValue'] ?? null;
  }
  return $out;
}
