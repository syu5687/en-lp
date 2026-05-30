// 軽量アクセス計測（sendBeacon）。描画をブロックせず、管理画面は除外。
(function () {
  try {
    if (location.pathname.indexOf('/admin') === 0) return;
    var data = JSON.stringify({ path: location.pathname, ref: document.referrer });
    if (navigator.sendBeacon) {
      navigator.sendBeacon('/api/track.php', new Blob([data], { type: 'application/json' }));
    } else {
      fetch('/api/track.php', { method: 'POST', body: data, headers: { 'Content-Type': 'application/json' }, keepalive: true });
    }
  } catch (e) {}
})();
