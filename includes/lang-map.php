<?php
/**
 * 日本語ページ ↔ 英語ページの1:1対応表（hreflang・相互リンク用）
 *
 * 使い方：英語ページを新設したら、この配列に1行追加するだけで
 *  ・日本語側 <head> に hreflang ja/en/x-default が自動出力（head.php）
 *  ・英語側は en_lang_tags('/en/xxx/') を <head> で呼ぶ
 *
 * Phase 2 予定（英語ページ完成後にコメントを外す）:
 *   '/powder-cleaning/' => '/en/ash-powdering-japan/',
 *   '/temoto-kuyou/'    => '/en/keepsakes-memorial-jewelry/',
 */
const LANG_MAP = [
  '/'            => '/en/',
  '/kaiyou-sou/' => '/en/sea-burial-japan/',
];

/** 現在の日本語パスに対応する英語URLを返す（なければ null） */
function en_counterpart(string $ja_path): ?string {
    return LANG_MAP[$ja_path] ?? null;
}

/** 英語ページ側で使う：対応する日本語URLを返す（なければ null） */
function ja_counterpart(string $en_path): ?string {
    $flip = array_flip(LANG_MAP);
    return $flip[$en_path] ?? null;
}

/** 英語ページの<head>用：hreflangタグ一式を出力 */
function en_lang_tags(string $en_path): void {
    $ja = ja_counterpart($en_path);
    $base = SITE['url'];
    echo '<link rel="alternate" hreflang="en" href="' . h($base . $en_path) . '">' . "\n";
    if ($ja) {
        echo '<link rel="alternate" hreflang="ja" href="' . h($base . $ja) . '">' . "\n";
        echo '<link rel="alternate" hreflang="x-default" href="' . h($base . $ja) . '">' . "\n";
    }
}
