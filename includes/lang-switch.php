<?php
/**
 * 言語切替UI（日本語 → 英語）— PCヘッダー・SPメニュー・フッター共通コンポーネント
 *
 * 設計の前提
 *  ・主CTA（資料請求・ご相談）より必ず一段弱い視覚優先度にする
 *  ・「見つけやすいが、主張しすぎない」— 色だけに頼らず、必ず文字ラベルを出す
 *  ・表記は EN ではなく English に統一する。
 *    社名が「縁（En）」であるため、ロゴの隣の "EN" は社名の略と誤読される。
 *    外国語話者が探しているのは自言語の綴りそのものなので English が最も確実。
 *  ・配色は2種類。日本語サイトのヘッダーはトップ・下層とも青地（#15709e）なので dark、
 *    英語サイトのヘッダーは白地なので light を使う。
 *
 * 使い方:
 *   require_once __DIR__ . '/lang-switch.php';
 *   en_lang_switch_css();                      // <head> か最初の1回だけ
 *   en_lang_switch('header_pc', 'dark');       // PCヘッダー
 *   en_lang_switch('menu_sp',   'dark');       // SPメニュー最上部
 *   en_lang_switch('footer');                  // フッター
 */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/lang-map.php';

/**
 * 現在の日本語ページに対応する英語URLを返す。
 * 対応ページがまだ無いページからは英語トップへ送る（一方通行にしない）。
 */
function en_lang_href(): string
{
    $path = strtok($_SERVER['REQUEST_URI'] ?? '/', '?');
    if ($path !== '/' && substr($path, -1) !== '/') $path .= '/';
    return en_counterpart($path) ?? '/en/';
}

/** 地球アイコン（線のみ・currentColor 追従。装飾ではなく言語切替の目印） */
function en_lang_icon(int $size = 14): string
{
    return '<svg class="lang-switch__icon" width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" '
         . 'stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">'
         . '<circle cx="12" cy="12" r="9"/><path d="M3 12h18"/>'
         . '<path d="M12 3a14 14 0 0 1 0 18a14 14 0 0 1 0-18z"/></svg>';
}

/** スタイル定義（1ページに1回だけ出力する） */
function en_lang_switch_css(): void
{
    static $done = false;
    if ($done) return;
    $done = true;
    ?>
<style>
/* ===== 言語切替 =====
   コントラストは WCAG AA（4.5:1）を満たす値を実測して採用している。
     light 文字 #12597a / チップ #eef3f6 … 6.87:1（英語ページの白ヘッダー用）
     dark  文字 #ffffff / 地 #15709e（塗りつぶさない）… 5.46:1
   dark で半透明の白チップを敷くと 4.33:1 まで落ちるため、枠線だけにしている。 */
.lang-switch.lang-switch{display:inline-flex;align-items:center;gap:6px;
  font-size:.74rem;font-weight:600;letter-spacing:.04em;line-height:1;
  padding:7px 12px;border-radius:999px;text-decoration:none;white-space:nowrap;
  transition:background-color .2s ease,color .2s ease,border-color .2s ease}
.lang-switch__icon{flex:none;opacity:.85}
.lang-switch:focus-visible{outline:2px solid currentColor;outline-offset:2px}
.lang-switch::after{display:none !important}

/* 白地のヘッダー（英語ページ） */
.lang-switch.lang-switch--light{background:#eef3f6;color:#12597a;border:1px solid transparent}
.lang-switch.lang-switch--light:hover{background:#dde9f0;color:#0a3852}

/* 青地のヘッダー（日本語サイト）— 主CTAは枠線 .8、こちらは .45 で一段弱く */
.lang-switch.lang-switch--dark{background:transparent;color:#fff;border:1px solid rgba(255,255,255,.45)}
.lang-switch.lang-switch--dark:hover{background:#fff;color:#15709e;border-color:#fff}

/* フッター（テキストリンク。ボタンにしない） */
.lang-switch.lang-switch--footer{padding:0;background:none;border:0;font-weight:600;
  color:inherit;text-decoration:underline;text-underline-offset:3px}
.lang-switch.lang-switch--footer:hover{text-decoration-thickness:2px}

/* SPメニュー内：「言語切替である」と分かる独立ブロックにする。
   表示・非表示の切り替えは、テンプレートごとにハンバーガーの
   ブレイクポイントが異なる（site-nav は768px／header-nav は1024px）ため、
   このコンポーネントでは行わず、各テンプレート側のラッパークラスで制御する。 */
.lang-switch-sp{margin:4px 0 14px;padding-bottom:14px;border-bottom:1px solid rgba(255,255,255,.22)}
.lang-switch-sp__label{display:block;font-size:.62rem;font-weight:700;letter-spacing:.18em;
  color:rgba(255,255,255,.62);margin:0 0 7px}
.lang-switch-sp .lang-switch.lang-switch{display:flex;justify-content:center;width:100%;
  padding:13px 16px;font-size:.92rem;letter-spacing:.06em;
  background:rgba(255,255,255,.10);border:1px solid rgba(255,255,255,.5);color:#fff}
.lang-switch-sp .lang-switch.lang-switch:hover{background:#fff;color:#15709e;border-color:#fff}
.lang-switch-sp .lang-switch__icon{opacity:1}
@media print{.lang-switch,.lang-switch-sp{display:none}}
</style>
<script>
/* 言語切替のクリックを計測（placement で設置場所を区別する） */
document.addEventListener('click', function (e) {
  var a = e.target.closest ? e.target.closest('[data-lang-switch]') : null;
  if (!a) return;
  try {
    if (window.gtag) window.gtag('event', 'language_switch', {
      from_language: a.getAttribute('data-from') || '',
      to_language:   a.getAttribute('data-to') || '',
      placement:     a.getAttribute('data-lang-switch') || '',
      page_location: location.href,
      page_path:     location.pathname
    });
  } catch (err) {}
}, true);
</script>
    <?php
}

/**
 * 言語切替リンクを出力する。
 * @param string $placement header_pc | menu_sp | footer （GA4の placement と一致させる）
 * @param string $variant   light | dark
 */
function en_lang_switch(string $placement = 'header_pc', string $variant = 'dark'): void
{
    $href = en_lang_href();
    $attrs = ' href="' . h($href) . '" hreflang="en" lang="en"'
           . ' data-lang-switch="' . h($placement) . '" data-from="ja" data-to="en"'
           . ' aria-label="Switch to English / 英語ページへ"';

    if ($placement === 'menu_sp') {
        echo '<div class="lang-switch-sp">'
           . '<span class="lang-switch-sp__label" aria-hidden="true">LANGUAGE</span>'
           . '<a class="lang-switch"' . $attrs . '>' . en_lang_icon(17) . '<span>English</span></a>'
           . '</div>';
        return;
    }
    if ($placement === 'footer') {
        echo '<a class="lang-switch lang-switch--footer"' . $attrs . '>' . en_lang_icon(13) . '<span>English</span></a>';
        return;
    }
    echo '<a class="lang-switch lang-switch--header-pc lang-switch--' . h($variant) . '"' . $attrs . '>'
       . en_lang_icon(14) . '<span>English</span></a>';
}

/**
 * 英語ページ側に置く「日本語へ戻る」切替。一方通行にしないためのペア。
 * @param string $ja_href   戻り先の日本語URL（対応ページがあればそのページ）
 * @param string $placement header_pc | menu_sp | footer
 */
function en_lang_switch_ja(string $ja_href = '/', string $placement = 'header_pc'): void
{
    $attrs = ' href="' . h($ja_href) . '" hreflang="ja" lang="ja"'
           . ' data-lang-switch="' . h($placement) . '" data-from="en" data-to="ja"'
           . ' aria-label="日本語ページへ / Switch to Japanese"';
    $cls = $placement === 'footer' ? 'lang-switch lang-switch--footer' : 'lang-switch lang-switch--light';
    echo '<a class="' . $cls . '"' . $attrs . '>' . en_lang_icon($placement === 'footer' ? 13 : 14)
       . '<span>日本語</span></a>';
}
