/**
 * フローティング導線コントローラ（PC専用）
 *
 * 目的：「必要な人に、必要なタイミングで、1つだけ表示する」
 *   従来は「次回合同海洋散骨」バッジが全ページ無条件で即時表示され、
 *   さらに3PV後に「供養方法診断」が別に出るため、2つが同時に画面へ残っていた。
 *   本ファイルで表示可否・優先順位・頻度を一元管理し、同時表示を構造的に禁止する。
 *
 * 判定の骨子（詳しくは decide() を参照）
 *   1. ページ文脈が最優先（散骨系ページ→日程／墓じまい・粉骨系→診断）
 *   2. 文脈が中立なページ（トップ・ブログ）は、そのセッションの行動で決める
 *   3. どちらも条件を満たさなければ「出さない」が正しい状態
 *
 * SPは対象外。SPでは日程は固定フッターバーに常設済みで、診断のみ別条件で扱う。
 */
(function () {
  'use strict';

  var CFG = window.EN_FCTA_CONFIG || {};
  var PC_MIN_WIDTH = 961;        // これ未満はSP扱い（sticky-cta と競合させない）
  var DISMISS_DAYS = 7;          // 閉じられたら7日間出さない
  var CLICKED_DAYS = 30;         // 一度クリック（目的達成）したら30日間出さない
  var AB_ENABLED = false;        // A/Bテストの有効化（既定OFF・理由はドキュメント参照）

  // 表示条件のしきい値。A/B時のみ variant B の値を使う。
  var TH = {
    A: { shindanPv: 2, shindanDwell: 20, shindanScroll: 30, schedScroll: 35, schedDwell: 12 },
    B: { shindanPv: 1, shindanDwell: 30, shindanScroll: 30, schedScroll: 55, schedDwell: 12 }
  };

  var EXCLUDE = ['/shindan', '/contact', '/admin', '/en/', '/privacy', '/policy', '/dl/'];

  // ---------- 小道具 ----------
  function ls(k, v) {
    try { if (v === undefined) return localStorage.getItem(k); localStorage.setItem(k, v); } catch (e) {}
    return null;
  }
  function ss(k, v) {
    try { if (v === undefined) return sessionStorage.getItem(k); sessionStorage.setItem(k, v); } catch (e) {}
    return null;
  }
  function fresh(key, days) {                       // key に記録した時刻が days 以内か
    var t = +(ls(key) || 0);
    return t > 0 && (Date.now() - t) < days * 86400000;
  }
  function ga(ev, params) {
    try { if (window.gtag) window.gtag('event', ev, params || {}); } catch (e) {}
  }

  // ---------- ページ種別 ----------
  function pageType(path) {
    if (path === '/' ) return 'top';
    if (/^\/(kaiyou-sou|pet-kaiyou-sou|seizen)\//.test(path)) return 'sea';
    if (/^\/fukuoka\//.test(path)) return 'sea';
    if (/^\/grave\//.test(path)) return 'grave';
    if (/^\/powder-cleaning\//.test(path)) return 'powder';
    if (/^\/(temoto-kuyou|teien-sou)\//.test(path)) return 'temoto';
    if (/^\/blog\//.test(path)) return 'blog';
    if (/^\/(voice|gokuyou|service|company|staff|area|about|onayami|flow|glossary|kuyou)\//.test(path)) return 'info';
    return 'other';
  }

  // ---------- セッションの行動記録 ----------
  var path = location.pathname;
  var type = pageType(path);

  for (var i = 0; i < EXCLUDE.length; i++) {
    if (path.indexOf(EXCLUDE[i]) === 0) return;     // 診断中・問い合わせ中などは邪魔をしない
  }

  var pv = +(ss('en_pv') || 0) + 1;
  ss('en_pv', pv);
  if (type === 'sea') ss('en_seen_sea', '1');       // 散骨系ページを見た＝関心あり
  if (type === 'grave' || type === 'powder' || type === 'temoto') ss('en_seen_svc', '1');

  var seenSea = ss('en_seen_sea') === '1';
  var seenSvc = ss('en_seen_svc') === '1';

  // ---------- A/B 振り分け ----------
  var variant = 'A';
  if (AB_ENABLED) {
    variant = ls('en_ab') || (Math.random() < 0.5 ? 'A' : 'B');
    ls('en_ab', variant);
  }
  var th = TH[variant] || TH.A;

  // ---------- 状態 ----------
  var started = Date.now();
  var maxScroll = 0;
  var shown = false;
  /* 画像の読み込み前はスクロール率も要素位置も当てにならず、誤った判定で
     バナーが出てしまう。load 完了（または3秒）まで判定を保留する。 */
  var settled = false;

  /**
   * 同一ページ内に実施予定日セクションがあり、それが画面に入っている（＝すでに読める）なら
   * 日程バッジは不要。IntersectionObserver は判定が非同期でスクロール時に取りこぼすため、
   * 位置を同期的に見る。
   */
  function scheduleSectionReached() {
    // 予定日セクションを指すアンカーで来た場合は、目的地に着いているので出さない
    if (location.hash === '#goudou-schedule') return true;
    var sec = document.getElementById('goudou-schedule');
    if (!sec) return false;
    return sec.getBoundingClientRect().top < window.innerHeight * 0.9;
  }

  function dwell() { return Math.round((Date.now() - started) / 1000); }
  function scrollPct() {
    var h = document.documentElement.scrollHeight - window.innerHeight;
    if (h <= 0) return 100;
    return Math.min(100, Math.round((window.pageYOffset / h) * 100));
  }

  // ---------- 表示可否の判定 ----------
  // 戻り値: {banner:'schedule'|'shindan', trigger:'...'} または null
  function decide() {
    if (window.innerWidth < PC_MIN_WIDTH) return null;          // PC専用
    if (!settled) return null;                                  // レイアウト確定前は判定しない
    if (ss('en_fcta_shown') === '1') return null;               // 1セッション1回まで
    var s = scrollPct(), d = dwell();

    var schedOk = CFG.hasSchedule && !scheduleSectionReached()
      && !fresh('en_hide_schedule', DISMISS_DAYS) && !fresh('en_done_schedule', CLICKED_DAYS);
    var shindanOk = !fresh('en_hide_shindan', DISMISS_DAYS) && !fresh('en_done_shindan', CLICKED_DAYS);

    // 1) ページ文脈が最優先 ------------------------------------------------
    if (type === 'sea') {
      if (schedOk && (s >= th.schedScroll || d >= th.schedDwell)) {
        return { banner: 'schedule', trigger: s >= th.schedScroll ? 'scroll' : 'dwell' };
      }
      return null;                       // 散骨ページで診断を出すと文脈がずれるため出さない
    }
    if (type === 'grave' || type === 'powder' || type === 'temoto') {
      if (shindanOk && (pv >= th.shindanPv || d >= th.shindanDwell)) {
        return { banner: 'shindan', trigger: pv >= th.shindanPv ? 'pageview' : 'dwell' };
      }
      return null;                       // これらのページで散骨日程は唐突なので出さない
    }

    // 2) 文脈が中立なページ（トップ・ブログ・その他）は行動で決める --------
    if (type === 'top' || type === 'blog' || type === 'info' || type === 'other') {
      // 散骨に関心を示している人が優先（より具体的な次の一歩を出せるため）
      if (seenSea && schedOk && (s >= th.schedScroll || d >= th.schedDwell)) {
        return { banner: 'schedule', trigger: s >= th.schedScroll ? 'scroll' : 'dwell' };
      }
      // 迷っている人（複数ページ or 複数サービスを見ている）に診断
      if (shindanOk && (pv >= th.shindanPv || seenSvc) && (s >= th.shindanScroll || d >= th.shindanDwell)) {
        return { banner: 'shindan', trigger: pv >= th.shindanPv ? 'pageview' : 'dwell' };
      }
    }
    return null;
  }

  // ---------- 表示 ----------
  function render(kind, trigger) {
    if (shown) return;
    var tpl = document.getElementById('en-fcta-tpl-' + kind);
    if (!tpl) return;
    shown = true;
    ss('en_fcta_shown', '1');

    var wrap = document.createElement('div');
    wrap.className = 'en-fcta en-fcta--' + kind;
    wrap.setAttribute('role', 'complementary');
    wrap.appendChild(tpl.content.cloneNode(true));
    document.body.appendChild(wrap);
    requestAnimationFrame(function () { wrap.classList.add('is-in'); });

    var params = {
      banner_type: kind, page_type: type, trigger_type: trigger,
      session_pv: pv, scroll_pct: scrollPct(), dwell_sec: dwell(),
      variant: variant, page_path: path
    };
    ga('banner_impression', params);

    var close = wrap.querySelector('.en-fcta__close');
    if (close) close.addEventListener('click', function () {
      ls('en_hide_' + kind, String(Date.now()));
      params.dwell_sec = dwell();
      ga('banner_dismiss', params);
      wrap.classList.remove('is-in');
      setTimeout(function () { wrap.remove(); }, 200);
    });

    var link = wrap.querySelector('.en-fcta__link');
    if (link) link.addEventListener('click', function () {
      ls('en_done_' + kind, String(Date.now()));
      params.dwell_sec = dwell();
      ga('banner_click', params);
      // 遷移先（診断・予定日）でのイベントに出所を引き継ぎ、CVまで追えるようにする
      ss('en_fcta_src', kind + '/' + type + '/' + trigger);
    });
  }

  // ---------- 監視 ----------
  function tick() {
    if (shown) return;
    var s = scrollPct();
    if (s > maxScroll) maxScroll = s;
    var r = decide();
    if (r) render(r.banner, r.trigger);
  }

  function boot() {
    var settle = function () { settled = true; tick(); };
    // load 後さらに少し待つ。アンカー付きURLのスクロール確定と遅延画像の反映を待つため。
    if (document.readyState === 'complete') setTimeout(settle, 900);
    else window.addEventListener('load', function () { setTimeout(settle, 900); });
    setTimeout(function () { settled = true; }, 4000);   // load が来ない場合の保険

    window.addEventListener('scroll', tick, { passive: true });
    window.addEventListener('resize', tick, { passive: true });
    var iv = setInterval(function () { tick(); if (shown) clearInterval(iv); }, 1000);
  }

  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', boot);
  else boot();
})();
