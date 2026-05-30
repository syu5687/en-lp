# LP（ohaka/・pet/）にアクセス解析を入れる手順

LP本体は Cloud Run 上の静的HTMLですが、公開URLは WordPress プロキシ経由
（`https://en1150.co.jp/lp/ohaka/`）のため、HPと同じ相対パスの計測タグは届きません。
そこで **Cloud Runの絶対URLへ直接送る自己完結型ビーコン** をLPのHTMLに埋め込みます。

## 手順
各LPの `index.html` の `</body>` 直前に、次のスニペットを貼り付けてください。
`CLOUD_RUN_URL` を、en-lp の Cloud Run 直URLに置き換えます。

```html
<!-- アクセス解析ビーコン（縁・自前計測） -->
<script>
(function(){try{
  var EP = 'https://en-lp-412102088439.asia-northeast1.run.app/api/track.php';   // （en-lpのCloud Run直URL・確定済み）
  var data = JSON.stringify({ path: location.pathname, ref: document.referrer });
  if (navigator.sendBeacon) {
    navigator.sendBeacon(EP, new Blob([data], { type: 'text/plain' }));
  } else {
    fetch(EP, { method:'POST', body:data, headers:{'Content-Type':'text/plain'}, keepalive:true, mode:'no-cors' });
  }
}catch(e){}})();
</script>
```

## ポイント
- `text/plain` で送るためCORSプリフライトが発生せず、クロスオリジンでも確実に届きます
- 受信側 `/api/track.php` はクロスオリジン対応済み（`Access-Control-Allow-Origin: *`）
- 計測される `path` は公開URL（例 `/lp/ohaka/`）になるので、解析画面でHPと区別できます
- Cookie・IP・個人情報は保存しません

## 注意
- `pet/` は未実装のため、LP実装後に同スニペットを入れてください
- LPを再デプロイ（push → Cloud Build）すれば反映されます
