<?php
/**
 * お問い合わせ受信 一覧・解析（DB化されたフォーム送信内容）
 *  - 期間フィルタ／CSV出力／集計（月別・種別・地域・年代・性別・流入ページ）
 */
require __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../includes/store.php';

$items = [];
$fs_error = '';
try { $items = inquiries_all(); } catch (Throwable $e) { $fs_error = $e->getMessage(); }

// ---- 期間フィルタ ----
$range = (string)($_GET['range'] ?? '365');
$ranges = ['30' => '直近30日', '90' => '直近90日', '365' => '直近1年', 'all' => '全期間'];
if (!isset($ranges[$range])) $range = '365';
if ($range !== 'all') {
  $limitDate = date('Y-m-d', strtotime('-' . (int)$range . ' days'));
  $items = array_values(array_filter($items, fn($i) => substr((string)($i['received_at'] ?? ''), 0, 10) >= $limitDate));
}

// ---- 対応ステータス集計 ----
$iq_status = static fn(array $i): string => in_array((string)($i['status'] ?? ''), INQUIRY_STATUSES, true) ? (string)$i['status'] : '未対応';
$staleLimit = date('Y-m-d H:i:s', time() - 3 * 86400);
$iq_is_stale = static function (array $i) use ($iq_status, $staleLimit): bool {
  if ($iq_status($i) === '対応済み') return false;
  if (str_starts_with((string)($i['category'] ?? ''), '[営業ブロック]')) return false;
  $last = (string)($i['status_updated_at'] ?? '') ?: (string)($i['received_at'] ?? '');
  return $last !== '' && $last <= $staleLimit;
};
$stCount = ['未対応' => 0, '対応中' => 0, '対応済み' => 0];
$staleCount = 0;
foreach ($items as $i) { $stCount[$iq_status($i)]++; if ($iq_is_stale($i)) $staleCount++; }

// ---- CSV出力（Excelで開ける UTF-8 BOM 付き） ----
if (!empty($_GET['export'])) {
  header('Content-Type: text/csv; charset=UTF-8');
  header('Content-Disposition: attachment; filename="inquiries_' . date('Ymd') . '.csv"');
  echo "\xEF\xBB\xBF";
  $out = fopen('php://output', 'w');
  fputcsv($out, ['受信日時', 'お名前', 'ふりがな', 'メール', '電話', '種別', 'お住まい', '年代', '性別', '合同散骨希望日', '診断結果', '送信元ページ', '内容', 'ステータス', '担当者', 'ステータス更新日時']);
  foreach ($items as $i) {
    fputcsv($out, [
      $i['received_at'] ?? '', $i['name'] ?? '', $i['kana'] ?? '', $i['email'] ?? '', $i['tel'] ?? '',
      $i['category'] ?? '', $i['pref'] ?? '', $i['age_group'] ?? '', $i['gender'] ?? '',
      $i['goudou_date'] ?? '', $i['shindan'] ?? '', $i['source'] ?? '', $i['message'] ?? '',
      $iq_status($i), $i['staff'] ?? '', $i['status_updated_at'] ?? '',
    ]);
  }
  fclose($out);
  exit;
}

// ---- 集計 ----
$agg = static function (array $items, callable $keyFn): array {
  $out = [];
  foreach ($items as $i) { $k = $keyFn($i); if ($k === '') $k = '（未回答）'; $out[$k] = ($out[$k] ?? 0) + 1; }
  arsort($out);
  return $out;
};
$kyushu = ['福岡県', '佐賀県', '長崎県', '熊本県', '大分県', '宮崎県', '沖縄県'];
$byMonth  = [];
for ($m = 11; $m >= 0; $m--) $byMonth[date('Y-m', strtotime("-$m month"))] = 0;
foreach ($items as $i) { $ym = substr((string)($i['received_at'] ?? ''), 0, 7); if (isset($byMonth[$ym])) $byMonth[$ym]++; }
$byCat    = $agg($items, fn($i) => trim((string)($i['category'] ?? '')));
$byRegion = $agg($items, function ($i) use ($kyushu) {
  $p = trim((string)($i['pref'] ?? ''));
  if ($p === '') return '';
  if ($p === '鹿児島県') return '鹿児島県';
  if ($p === '福岡県') return '福岡県';
  if (in_array($p, $kyushu, true)) return '九州（その他）';
  return '九州以外';
});
$byAge    = $agg($items, fn($i) => trim((string)($i['age_group'] ?? '')));
$byGender = $agg($items, fn($i) => trim((string)($i['gender'] ?? '')));
$bySource = $agg($items, function ($i) {
  $s = (string)($i['source'] ?? '');
  $p = parse_url($s, PHP_URL_PATH);
  return $p ? (string)$p : '';
});
$total = count($items);
$thisMonth = 0; $lastMonth = 0;
$tm = date('Y-m'); $lm = date('Y-m', strtotime('-1 month'));
foreach ($items as $i) {
  $ym = substr((string)($i['received_at'] ?? ''), 0, 7);
  if ($ym === $tm) $thisMonth++; elseif ($ym === $lm) $lastMonth++;
}
$barMax = max(1, max($byMonth ?: [1]));

function iq_bars(array $data, int $top = 8): string {
  $out = '';
  $max = max(1, max($data ?: [1]));
  $rows = array_slice($data, 0, $top, true);
  foreach ($rows as $k => $v) {
    $w = max(2, (int)round($v / $max * 100));
    $out .= '<div class="iq-bar"><span class="iq-bar__label">' . h($k) . '</span>'
          . '<span class="iq-bar__track"><span class="iq-bar__fill" style="width:' . $w . '%"></span></span>'
          . '<span class="iq-bar__num">' . (int)$v . '</span></div>';
  }
  return $out !== '' ? $out : '<p class="iq-empty">データがまだありません</p>';
}
?>
<!DOCTYPE html>
<html lang="ja"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>お問い合わせ受信・解析｜管理画面</title>
<meta name="robots" content="noindex,nofollow">
<link rel="stylesheet" href="/admin/assets/admin.css?v=<?= h(asset_ver()) ?>">
<style>
  .iq-summary{display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:12px;margin-bottom:22px}
  .iq-sum{background:#fff;border-radius:10px;padding:14px 16px;box-shadow:0 2px 8px rgba(0,0,0,.05)}
  .iq-sum b{display:block;font-size:1.5rem;color:#15709e}
  .iq-sum span{font-size:.78rem;color:#789}
  .iq-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:16px;margin-bottom:26px}
  .iq-panel{background:#fff;border-radius:10px;padding:16px 18px;box-shadow:0 2px 8px rgba(0,0,0,.05)}
  .iq-panel h2{font-size:.92rem;color:#0a3852;margin-bottom:12px}
  .iq-bar{display:flex;align-items:center;gap:8px;margin-bottom:7px;font-size:.82rem}
  .iq-bar__label{flex:none;width:38%;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;color:#345}
  .iq-bar__track{flex:1;background:#eef3f6;border-radius:99px;height:12px;overflow:hidden}
  .iq-bar__fill{display:block;height:100%;background:linear-gradient(90deg,#1f8fce,#15709e);border-radius:99px}
  .iq-bar__num{flex:none;width:34px;text-align:right;font-weight:700;color:#0a3852}
  .iq-empty{font-size:.82rem;color:#99a}
  .iq-month{display:flex;align-items:flex-end;gap:4px;height:110px}
  .iq-month>div{flex:1;display:flex;flex-direction:column;justify-content:flex-end;align-items:center;gap:4px}
  .iq-month i{display:block;width:100%;background:linear-gradient(180deg,#1f8fce,#15709e);border-radius:4px 4px 0 0;min-height:2px}
  .iq-month b{font-size:.68rem;color:#456}
  .iq-month small{font-size:.6rem;color:#9ab;writing-mode:vertical-rl;letter-spacing:0}
  .iq-table{width:100%;border-collapse:collapse;background:#fff;border-radius:10px;overflow:hidden;font-size:.85rem}
  .iq-table th,.iq-table td{padding:9px 12px;border-bottom:1px solid #eee;text-align:left;vertical-align:top}
  .iq-table th{background:#f2f6f8;font-size:.76rem;color:#456;white-space:nowrap}
  .iq-tools{display:flex;flex-wrap:wrap;gap:10px;align-items:center;margin-bottom:18px}
  .iq-tools a{font-size:.85rem}
  .iq-msg-toggle{background:none;border:none;cursor:pointer;color:#15709e;font-size:.8rem;padding:0;white-space:nowrap}
  .iq-msg-toggle:hover{text-decoration:underline}
  .iq-msgrow td{background:#f4f9fc;border-bottom:1px solid #d9e6ee;padding:12px 16px}
  .iq-msgbox{white-space:pre-wrap;font-size:.88rem;line-height:2;color:#23323a;background:#fff;border:1px solid #d3e2ea;border-left:4px solid #15709e;border-radius:8px;padding:14px 18px;max-width:860px}
  .iq-msgbox b{display:block;font-size:.74rem;color:#5c7a8a;margin-bottom:6px}
</style>
</head><body>
<header class="admin-bar">
  <span class="admin-bar__title"><a href="/admin/">← ダッシュボード</a>　お問い合わせ受信・解析</span>
  <a href="/admin/logout.php" class="admin-bar__logout">ログアウト</a>
</header>
<main class="admin-main">
  <h1>お問い合わせ受信・解析</h1>
  <p style="font-size:.88rem;color:#667;margin-bottom:16px">フォームから送信された内容の記録と、Web運営に役立つ集計です。属性（お住まい・年代・性別）は任意回答のため、未回答が含まれます。</p>
  <?php if ($fs_error): ?><p style="background:#fdecea;color:#c0392b;padding:10px 16px;border-radius:8px;margin-bottom:14px">データ取得エラー: <?= h($fs_error) ?></p><?php endif; ?>

  <div class="iq-tools">
    <?php foreach ($ranges as $rk => $rl): ?>
      <a href="?range=<?= h($rk) ?>" class="admin-btn <?= $range === $rk ? '' : 'admin-btn--outline' ?>" style="padding:6px 14px;font-size:.8rem"><?= h($rl) ?></a>
    <?php endforeach; ?>
    <a href="?range=<?= h($range) ?>&amp;export=csv" class="admin-btn admin-btn--outline" style="padding:6px 14px;font-size:.8rem;margin-left:auto">CSVダウンロード</a>
  </div>

  <div class="iq-summary">
    <div class="iq-sum"><b><?= $total ?>件</b><span><?= h($ranges[$range]) ?>の受信</span></div>
    <div class="iq-sum"><b><?= $thisMonth ?>件</b><span>今月</span></div>
    <div class="iq-sum"><b><?= $lastMonth ?>件</b><span>先月</span></div>
    <div class="iq-sum"><b style="color:#c0392b"><?= $stCount['未対応'] ?>件</b><span>未対応</span></div>
    <div class="iq-sum"><b style="color:#b07a1e"><?= $stCount['対応中'] ?>件</b><span>対応中</span></div>
    <div class="iq-sum"><b style="color:#1d7a3e"><?= $stCount['対応済み'] ?>件</b><span>対応済み</span></div>
    <?php if ($staleCount): ?><div class="iq-sum" style="outline:2px solid #e74c3c"><b style="color:#c0392b"><?= $staleCount ?>件</b><span>3日以上動きなし（要対応）</span></div><?php endif; ?>
  </div>

  <div class="iq-grid">
    <div class="iq-panel" style="grid-column:1/-1">
      <h2>月別の受信件数（直近12か月）</h2>
      <div class="iq-month">
        <?php foreach ($byMonth as $ym => $c): ?>
          <div><b><?= (int)$c ?></b><i style="height:<?= max(2, (int)round($c / $barMax * 80)) ?>px"></i><small><?= h(substr($ym, 2)) ?></small></div>
        <?php endforeach; ?>
      </div>
    </div>
    <div class="iq-panel"><h2>お問い合わせ種別</h2><?= iq_bars($byCat) ?></div>
    <div class="iq-panel"><h2>地域（お住まい）</h2><?= iq_bars($byRegion) ?></div>
    <div class="iq-panel"><h2>年代</h2><?= iq_bars($byAge) ?></div>
    <div class="iq-panel"><h2>性別</h2><?= iq_bars($byGender) ?></div>
    <div class="iq-panel" style="grid-column:1/-1"><h2>送信元ページ（どのページから相談が来たか）</h2><?= iq_bars($bySource, 10) ?></div>
  </div>

  <h2 style="font-size:1rem;color:#0a3852;margin-bottom:10px">受信一覧（新しい順・最大100件表示）</h2>
  <div style="overflow-x:auto">
    <table class="iq-table">
      <tr><th>受信日時</th><th>お名前</th><th>種別</th><th>属性</th><th>連絡先</th><th>内容ほか</th><th>対応ステータス</th></tr>
      <?php foreach (array_slice($items, 0, 100) as $i): $st = $iq_status($i); $stale = $iq_is_stale($i); ?>
        <tr class="iq-row iq-row--<?= $st === '未対応' ? 'todo' : ($st === '対応中' ? 'doing' : 'done') ?><?= $stale ? ' iq-row--stale' : '' ?>">
          <td style="white-space:nowrap"><?= h($i['received_at'] ?? '') ?></td>
          <td style="font-weight:700"><?= h($i['name'] ?? '') ?><br><span style="font-weight:400;color:#89a;font-size:.76rem"><?= h($i['kana'] ?? '') ?></span></td>
          <td><?= h($i['category'] ?? '') ?><?= !empty($i['goudou_date']) ? '<br><span style="font-size:.76rem;color:#567">希望日 ' . h($i['goudou_date']) . '</span>' : '' ?></td>
          <td style="font-size:.8rem;color:#456"><?= h(implode('／', array_filter([$i['pref'] ?? '', $i['age_group'] ?? '', $i['gender'] ?? '']))) ?: '—' ?></td>
          <td style="font-size:.8rem"><?= h($i['email'] ?? '') ?><br><?= h($i['tel'] ?? '') ?></td>
          <td>
            <?php if (!empty($i['message'])): ?>
              <button type="button" class="iq-msg-toggle">▼ 内容を見る</button>
            <?php endif; ?>
            <?php if (!empty($i['shindan'])): ?><p style="font-size:.76rem;color:#567">診断: <?= h($i['shindan']) ?></p><?php endif; ?>
            <?php if (!empty($i['source'])): ?><p style="font-size:.72rem;color:#9ab"><?= h((string)(parse_url((string)$i['source'], PHP_URL_PATH) ?? '')) ?></p><?php endif; ?>
          </td>
          <td class="iq-status" data-id="<?= h((string)($i['id'] ?? '')) ?>">
            <?php if ($stale): ?><span class="iq-stale-badge">3日以上動きなし</span><?php endif; ?>
            <select class="iq-status__sel">
              <?php foreach (INQUIRY_STATUSES as $s): ?>
                <option value="<?= h($s) ?>"<?= $s === $st ? ' selected' : '' ?>><?= h($s) ?></option>
              <?php endforeach; ?>
            </select>
            <input type="text" class="iq-status__staff" placeholder="担当者名" value="<?= h((string)($i['staff'] ?? '')) ?>" maxlength="40">
            <span class="iq-status__note"><?= !empty($i['status_updated_at']) ? h(substr((string)$i['status_updated_at'], 0, 16)) . ' 更新' : '' ?></span>
            <?php
              $histRaw = array_values(array_filter((array)($i['history'] ?? []), 'is_string'));
              $histCnt = count($histRaw);
              $iqData = json_encode([
                'id'      => (string)($i['id'] ?? ''),
                'name'    => (string)($i['name'] ?? ''),
                'email'   => (string)($i['email'] ?? ''),
                'tel'     => (string)($i['tel'] ?? ''),
                'message' => (string)($i['message'] ?? ''),
                'history' => $histRaw,
              ], JSON_UNESCAPED_UNICODE);
            ?>
            <button type="button" class="iq-open admin-btn admin-btn--outline" data-iq="<?= h($iqData) ?>" style="width:100%;padding:6px 8px;font-size:.78rem">✉ 返信・対応履歴<?= $histCnt ? '（' . $histCnt . '）' : '' ?></button>
          </td>
        </tr>
        <?php if (!empty($i['message'])): ?>
        <tr class="iq-msgrow" hidden><td colspan="7"><div class="iq-msgbox"><b><?= h($i['name'] ?? '') ?> 様のお問い合わせ内容</b><?= h($i['message']) ?></div></td></tr>
        <?php endif; ?>
      <?php endforeach; ?>
      <?php if (!$items && !$fs_error): ?><tr><td colspan="6" style="color:#888">まだ受信データがありません。フォームから送信があると自動で記録されます。</td></tr><?php endif; ?>
    </table>
  </div>
  <p style="font-size:.76rem;color:#99a;margin-top:14px">※ 個人情報を含むため、取り扱いにご注意ください。表示・CSVのデータは管理画面にログインした方のみ閲覧できます。<br>
  ※ ステータスか担当者を変更すると自動保存されます。「対応済み」以外のまま3日以上変更がない案件は、毎日の自動チェックで管理者宛にメール通知されます。</p>
</main>
<script>
  // 「内容を見る」→ 直下の全幅行を開閉（細い列で読みにくい問題の対策）
  document.querySelectorAll('.iq-msg-toggle').forEach(function (b) {
    b.addEventListener('click', function () {
      var row = b.closest('tr') && b.closest('tr').nextElementSibling;
      if (!row || !row.classList.contains('iq-msgrow')) return;
      row.hidden = !row.hidden;
      b.textContent = row.hidden ? '▼ 内容を見る' : '▲ 閉じる';
    });
  });
</script>
<style>
  .iq-row--todo{background:#fff}
  .iq-row--doing{background:#fffdf4}
  .iq-row--done{background:#f6fbf7}
  .iq-row--done td{color:#7a8a80}
  .iq-row--stale td:first-child{box-shadow:inset 4px 0 0 #e74c3c}
  .iq-status{min-width:170px}
  .iq-status select,.iq-status input{width:100%;box-sizing:border-box;padding:6px 8px;border:1px solid #cdd8de;border-radius:7px;font-size:.8rem;margin-bottom:6px;background:#fff}
  .iq-status__note{display:block;font-size:.68rem;color:#9ab;min-height:1em}
  .iq-stale-badge{display:inline-block;background:#fdecea;color:#c0392b;font-size:.68rem;font-weight:700;border-radius:99px;padding:2px 8px;margin-bottom:6px}
  .iq-status.is-saving select,.iq-status.is-saving input{opacity:.5;pointer-events:none}
  .iq-status.is-saved .iq-status__note{color:#1d7a3e;font-weight:700}
  .iq-status.is-error .iq-status__note{color:#c0392b;font-weight:700}
</style>
<script>
(function () {
  var CSRF = <?= json_encode(csrf_token()) ?>;
  var lastStaff = '';
  document.querySelectorAll('.iq-status').forEach(function (cell) {
    var sel = cell.querySelector('.iq-status__sel');
    var staff = cell.querySelector('.iq-status__staff');
    var note = cell.querySelector('.iq-status__note');
    async function save() {
      var status = sel.value;
      var name = staff.value.trim();
      if (status !== '未対応' && !name) {
        if (lastStaff) { staff.value = name = lastStaff; }  // 直前に入力した担当者名を自動補完
        else { note.textContent = '担当者名を入力してください'; cell.classList.add('is-error'); staff.focus(); return; }
      }
      cell.classList.remove('is-saved', 'is-error');
      cell.classList.add('is-saving');
      note.textContent = '保存中…';
      try {
        var j = await iqPost('/admin/inquiries/status.php', { id: cell.dataset.id, status: status, staff: name }, CSRF);
        if (name) lastStaff = name;
        note.textContent = j.at + ' 更新';
        cell.classList.add('is-saved');
        var row = cell.closest('tr');
        row.className = row.className.replace(/iq-row--(todo|doing|done|stale)/g, '').trim();
        row.classList.add('iq-row', status === '未対応' ? 'iq-row--todo' : (status === '対応中' ? 'iq-row--doing' : 'iq-row--done'));
        var badge = cell.querySelector('.iq-stale-badge');
        if (badge) badge.remove();
      } catch (err) {
        note.textContent = err.message;
        cell.classList.add('is-error');
      } finally {
        cell.classList.remove('is-saving');
      }
    }
    sel.addEventListener('change', save);
    staff.addEventListener('change', function () { if (sel.value !== '未対応' || staff.value.trim()) save(); });
  });
})();
</script>

<!-- 返信・対応履歴モーダル -->
<dialog id="iq-dialog">
  <div class="iqd-head">
    <div>
      <b id="iqd-name"></b>
      <span id="iqd-contact"></span>
    </div>
    <button type="button" id="iqd-close" aria-label="閉じる">×</button>
  </div>

  <!-- お問い合わせ内容 -->
  <section class="iqd-sec">
    <h3 class="iqd-sec__title">お問い合わせ内容</h3>
    <p id="iqd-message" class="iqd-msgbox"></p>
  </section>

  <!-- 対応履歴（主役） -->
  <section class="iqd-sec">
    <h3 class="iqd-sec__title">対応履歴 <span id="iqd-hcount" class="iqd-hcount"></span></h3>
    <div id="iqd-history" class="iqd-history"></div>
  </section>

  <!-- タブ：メール返信／電話メモ -->
  <section class="iqd-sec">
    <div class="iqd-tabs">
      <button type="button" class="iqd-tab iqd-tab--mail is-active" data-pane="mail">✉ メール返信</button>
      <button type="button" class="iqd-tab iqd-tab--tel" data-pane="tel">☎ 電話メモ</button>
    </div>

    <!-- メール返信タブ -->
    <div class="iqd-pane" id="iqd-pane-mail">
      <p class="iqd-note iqd-note--warn" id="iqd-mail-na" hidden>この方はメールアドレス未記入のため、メール返信はできません。「☎ 電話メモ」タブをご利用ください。</p>
      <div id="iqd-mail-form">
        <label class="iqd-f">宛先（自動入力・変更不可）<input type="email" id="iqd-to" readonly></label>
        <label class="iqd-f">件名<input type="text" id="iqd-subject" maxlength="200"></label>
        <label class="iqd-f">本文（あいさつ文と署名は入力済み。◆の行を書き換えてください）<textarea id="iqd-body" rows="12"></textarea></label>
        <label class="iqd-f">担当者名<input type="text" id="iqd-staff-mail" maxlength="40" placeholder="例：田中"></label>
        <button type="button" class="iqd-submit iqd-submit--mail" id="iqd-send">✉ この内容でメールを送信する</button>
        <p class="iqd-note">送信すると上の対応履歴に自動で記録されます。控えは info@en1150.co.jp にも届き、お客様からの返信も info@en1150.co.jp に届きます。</p>
      </div>
    </div>

    <!-- 電話メモタブ -->
    <div class="iqd-pane" id="iqd-pane-tel" hidden>
      <label class="iqd-f">電話で伝えた内容<textarea id="iqd-memo" rows="7" placeholder="例：お電話にて合同散骨の日程と費用をご案内。資料送付を希望されたため本日発送。"></textarea></label>
      <label class="iqd-f">担当者名<input type="text" id="iqd-staff-tel" maxlength="40" placeholder="例：田中"></label>
      <button type="button" class="iqd-submit iqd-submit--tel" id="iqd-save-note">☎ 電話メモを保存する</button>
      <p class="iqd-note">保存すると上の対応履歴に記録されます。メールは送信されません。</p>
    </div>

    <p id="iqd-result" class="iqd-result"></p>
  </section>
</dialog>

<style>
  #iq-dialog{border:none;border-radius:14px;padding:0;width:min(680px,94vw);max-height:90vh;overflow-y:auto;box-shadow:0 20px 60px rgba(0,0,0,.3);margin:auto;inset:0;position:fixed;background:#f7f9fa}
  #iq-dialog::backdrop{background:rgba(10,30,40,.45)}
  .iqd-head{display:flex;justify-content:space-between;align-items:center;background:#0a3852;color:#fff;padding:14px 18px;position:sticky;top:0;z-index:2}
  .iqd-head b{font-size:1.05rem}
  .iqd-head span{display:block;font-size:.76rem;color:#bcd;margin-top:2px}
  #iqd-close{background:none;border:none;color:#fff;font-size:1.6rem;cursor:pointer;line-height:1;padding:0 4px}

  .iqd-sec{background:#fff;margin:12px 14px;border-radius:12px;padding:14px 16px;border:1px solid #e3eaee}
  .iqd-sec:last-of-type{margin-bottom:16px}
  .iqd-sec__title{font-size:.85rem;color:#0a3852;margin:0 0 10px;padding-bottom:8px;border-bottom:2px solid #eef3f6}
  .iqd-hcount{color:#889;font-weight:400;font-size:.76rem}
  .iqd-msgbox{white-space:pre-wrap;font-size:.86rem;line-height:1.8;background:#f7f9fa;border-radius:8px;padding:12px;max-height:140px;overflow-y:auto;margin:0}

  .iqd-history{display:grid;gap:8px;max-height:260px;overflow-y:auto}
  .iqd-entry{border:1px solid #e3eaee;border-radius:10px;padding:10px 12px;font-size:.82rem;background:#fafcfd}
  .iqd-entry--email{border-left:4px solid #15709e}
  .iqd-entry--tel{border-left:4px solid #b07a1e}
  .iqd-entry__meta{color:#678;font-size:.74rem;margin-bottom:4px}
  .iqd-entry__meta b{color:#0a3852}
  .iqd-entry__body{white-space:pre-wrap;color:#334}
  .iqd-empty{font-size:.8rem;color:#99a;margin:0}

  .iqd-tabs{display:grid;grid-template-columns:1fr 1fr;gap:0;margin:-14px -16px 14px;border-bottom:1px solid #e3eaee}
  .iqd-tab{padding:13px 8px;border:none;background:#f2f6f8;font-size:.92rem;font-weight:700;cursor:pointer;color:#789;font-family:inherit;border-bottom:3px solid transparent}
  .iqd-tab:first-child{border-radius:12px 0 0 0}
  .iqd-tab:last-child{border-radius:0 12px 0 0}
  .iqd-tab--mail.is-active{background:#fff;color:#15709e;border-bottom-color:#15709e}
  .iqd-tab--tel.is-active{background:#fff;color:#8a5f14;border-bottom-color:#b07a1e}

  .iqd-f{display:block;font-size:.76rem;color:#456;font-weight:700;margin-bottom:10px}
  .iqd-f input,.iqd-f textarea{display:block;width:100%;box-sizing:border-box;margin-top:5px;padding:10px;border:1px solid #cdd8de;border-radius:8px;font-size:.9rem;font-weight:400;font-family:inherit;background:#fff}
  .iqd-f input[readonly]{background:#f2f6f8;color:#567}

  .iqd-submit{display:block;width:100%;padding:13px;border:none;border-radius:10px;font-size:.95rem;font-weight:700;color:#fff;cursor:pointer;font-family:inherit}
  .iqd-submit--mail{background:#15709e}
  .iqd-submit--mail:hover{background:#125e85}
  .iqd-submit--tel{background:#b07a1e}
  .iqd-submit--tel:hover{background:#96680f}
  .iqd-submit:disabled{opacity:.55;cursor:default}

  .iqd-note{font-size:.74rem;color:#889;line-height:1.7;font-weight:400;margin-top:8px}
  .iqd-note--warn{background:#fdecea;color:#c0392b;border-radius:8px;padding:10px;font-weight:700}
  .iqd-result{margin:10px 0 0;font-size:.86rem;font-weight:700;min-height:1.2em}
  .iqd-result.ok{color:#1d7a3e}
  .iqd-result.ng{color:#c0392b}
</style>
<script>
async function iqPost(url, payload, csrf) {
  var res;
  try {
    res = await fetch(url, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-Token': csrf },
      body: JSON.stringify(payload)
    });
  } catch (e) {
    throw new Error('通信に失敗しました。ネットワークをご確認ください。');
  }
  var text = await res.text();
  var j = null;
  try { j = JSON.parse(text); } catch (e) {
    if (res.status === 404) throw new Error('保存用プログラムが見つかりません（HTTP 404）。最新版のデプロイが完了しているかご確認ください。');
    if (res.redirected || /login/.test(res.url)) throw new Error('ログインの有効期限が切れています。ページを再読み込みして、もう一度ログインしてください。');
    throw new Error('サーバーの応答が不正です（HTTP ' + res.status + '）。ページを再読み込みしてお試しください。');
  }
  if (!res.ok || !j.ok) throw new Error((j && j.error) || '処理に失敗しました（HTTP ' + res.status + '）');
  return j;
}
(function () {
  var CSRF = <?= json_encode(csrf_token()) ?>;
  var SIGNATURE = <?= json_encode("\n\n――――――――――――――――\n有限会社 縁\n鹿児島県鹿児島市坂之上7丁目7-3\nTEL 099-801-3637（月〜土 9:00〜18:00）\nhttps://en1150.co.jp") ?>;
  var dlg = document.getElementById('iq-dialog');
  var cur = null;
  var lastStaff = '';
  var $ = function (id) { return document.getElementById(id); };

  function renderHistory(list) {
    var wrap = $('iqd-history');
    wrap.innerHTML = '';
    $('iqd-hcount').textContent = list.length ? '（' + list.length + '件）' : '';
    if (!list.length) { wrap.innerHTML = '<p class="iqd-empty">まだ対応履歴がありません。下のタブから最初の対応を記録してください。</p>'; return; }
    // 新しい順に表示
    list.slice().reverse().forEach(function (s) {
      var e; try { e = JSON.parse(s); } catch (_) { return; }
      var div = document.createElement('div');
      div.className = 'iqd-entry iqd-entry--' + (e.t === 'email' ? 'email' : 'tel');
      var kind = e.t === 'email' ? '✉ メール返信' : '☎ 電話メモ';
      div.innerHTML = '<div class="iqd-entry__meta"><b>' + kind + '</b>　' + (e.at || '') + '　担当: ' + (e.staff || '—')
        + (e.subject ? '<br>件名: ' + e.subject : '') + '</div>';
      var body = document.createElement('div');
      body.className = 'iqd-entry__body';
      body.textContent = e.body || '';
      div.appendChild(body);
      wrap.appendChild(div);
    });
  }

  function switchTab(pane) {
    document.querySelectorAll('.iqd-tab').forEach(function (t) { t.classList.toggle('is-active', t.dataset.pane === pane); });
    $('iqd-pane-mail').hidden = pane !== 'mail';
    $('iqd-pane-tel').hidden = pane !== 'tel';
  }
  document.querySelectorAll('.iqd-tab').forEach(function (t) {
    t.addEventListener('click', function () { switchTab(t.dataset.pane); });
  });

  document.querySelectorAll('.iq-open').forEach(function (btn) {
    btn.addEventListener('click', function () {
      cur = JSON.parse(btn.dataset.iq);
      cur._btn = btn;
      $('iqd-name').textContent = (cur.name || '（お名前なし）') + ' 様';
      $('iqd-contact').textContent = [cur.email, cur.tel].filter(Boolean).join('　');
      $('iqd-message').textContent = cur.message || '（本文なし）';
      renderHistory(cur.history || []);
      var hasMail = !!cur.email;
      $('iqd-mail-na').hidden = hasMail;
      $('iqd-mail-form').style.display = hasMail ? '' : 'none';
      $('iqd-to').value = cur.email || '';
      $('iqd-subject').value = 'お問い合わせありがとうございます｜有限会社 縁';
      $('iqd-body').value = (cur.name ? cur.name + ' 様\n\n' : '')
        + 'この度はお問い合わせいただき、誠にありがとうございます。\n有限会社 縁でございます。\n\n◆ ここに返信内容をご記入ください ◆\n'
        + SIGNATURE;
      $('iqd-memo').value = '';
      if (lastStaff) { $('iqd-staff-mail').value = lastStaff; $('iqd-staff-tel').value = lastStaff; }
      $('iqd-result').textContent = '';
      $('iqd-result').className = 'iqd-result';
      switchTab(hasMail ? 'mail' : 'tel');
      dlg.showModal();
    });
  });
  $('iqd-close').addEventListener('click', function () { dlg.close(); });

  function markResult(ok, msg) {
    var r = $('iqd-result');
    r.textContent = msg;
    r.className = 'iqd-result ' + (ok ? 'ok' : 'ng');
  }
  function afterSaved(entry) {
    cur.history.push(JSON.stringify(entry));
    renderHistory(cur.history);
    cur._btn.dataset.iq = JSON.stringify({ id: cur.id, name: cur.name, email: cur.email, tel: cur.tel, message: cur.message, history: cur.history });
    cur._btn.textContent = '✉ 返信・対応履歴（' + cur.history.length + '）';
    var row = cur._btn.closest('tr');
    var sel = row.querySelector('.iq-status__sel');
    if (sel && sel.value === '未対応') {
      sel.value = '対応中';
      row.className = 'iq-row iq-row--doing';
      var badge = row.querySelector('.iq-stale-badge');
      if (badge) badge.remove();
    }
  }

  $('iqd-send').addEventListener('click', async function () {
    var staff = $('iqd-staff-mail').value.trim();
    if (!staff) { markResult(false, '担当者名を入力してください'); $('iqd-staff-mail').focus(); return; }
    var body = $('iqd-body').value;
    if (!body.trim()) { markResult(false, '本文を入力してください'); return; }
    if (body.indexOf('◆ ここに返信内容をご記入ください ◆') !== -1) {
      markResult(false, '本文の「◆ ここに返信内容をご記入ください ◆」の行を、実際の返信内容に書き換えてください');
      $('iqd-body').focus();
      return;
    }
    if (!confirm($('iqd-to').value + ' 宛にメールを送信します。よろしいですか？')) return;
    lastStaff = staff;
    this.disabled = true; this.textContent = '送信中…';
    try {
      var j = await iqPost('/admin/inquiries/reply.php', { id: cur.id, to: $('iqd-to').value, subject: $('iqd-subject').value, body: body, staff: staff }, CSRF);
      markResult(true, j.warn || 'メールを送信しました。対応履歴に記録済みです。');
      afterSaved({ t: 'email', at: j.at || '', staff: staff, to: $('iqd-to').value, subject: $('iqd-subject').value, body: body });
    } catch (err) {
      markResult(false, err.message);
    } finally {
      this.disabled = false; this.textContent = '✉ この内容でメールを送信する';
    }
  });

  $('iqd-save-note').addEventListener('click', async function () {
    var staff = $('iqd-staff-tel').value.trim();
    if (!staff) { markResult(false, '担当者名を入力してください'); $('iqd-staff-tel').focus(); return; }
    var memo = $('iqd-memo').value.trim();
    if (!memo) { markResult(false, '電話で伝えた内容を入力してください'); $('iqd-memo').focus(); return; }
    lastStaff = staff;
    this.disabled = true; this.textContent = '保存中…';
    try {
      var j = await iqPost('/admin/inquiries/note.php', { id: cur.id, memo: memo, staff: staff }, CSRF);
      markResult(true, '電話メモを保存しました。対応履歴に記録済みです。');
      afterSaved({ t: 'tel', at: j.at || '', staff: staff, body: memo });
      $('iqd-memo').value = '';
    } catch (err) {
      markResult(false, err.message);
    } finally {
      this.disabled = false; this.textContent = '☎ 電話メモを保存する';
    }
  });
})();
</script>
</body></html>