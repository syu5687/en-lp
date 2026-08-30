// 迷っている人向け：同一セッションで3ページ閲覧後、供養の選び方診断への案内を表示
(function(){
  try{
    var path=location.pathname;
    if(path.indexOf('/shindan')===0||path.indexOf('/admin')===0||path.indexOf('/contact')===0)return;
    var hide=localStorage.getItem('en_sd_hide');
    if(hide&&(Date.now()-(+hide))<7*24*3600*1000)return;
    var pv=+(sessionStorage.getItem('en_sd_pv')||0)+1;
    sessionStorage.setItem('en_sd_pv',pv);
    if(pv<3)return;
    var show=function(){
      if(document.getElementById('sd-nudge'))return;
      var b=document.createElement('div');
      b.id='sd-nudge';
      b.innerHTML='<button id="sd-nudge-x" aria-label="閉じる">×</button>'
        +'<p class="t">どれを選べばいいか、迷っていませんか？</p>'
        +'<p class="s">いくつかの質問で、今の状況に合う供養方法を整理できます（約3分・入力不要）</p>'
        +'<a href="/shindan/" class="b">診断してみる</a>';
      var css=document.createElement('style');
      css.textContent='#sd-nudge{position:fixed;left:14px;bottom:16px;z-index:9000;max-width:300px;background:#fff;border:1px solid #d8e4ea;border-radius:14px;box-shadow:0 12px 34px rgba(10,56,82,.22);padding:16px 16px 14px;animation:sdn .4s ease}'
        +'@keyframes sdn{from{opacity:0;transform:translateY(14px)}to{opacity:1;transform:none}}'
        +'#sd-nudge .t{font-weight:700;color:#12597a;font-size:.95rem;margin:0 22px 6px 0;line-height:1.5}'
        +'#sd-nudge .s{font-size:.78rem;color:#5c6b73;line-height:1.7;margin:0 0 10px}'
        +'#sd-nudge .b{display:block;text-align:center;background:#15709e;color:#fff;font-weight:700;font-size:.9rem;padding:10px 12px;border-radius:999px;text-decoration:none}'
        +'#sd-nudge-x{position:absolute;top:6px;right:8px;background:none;border:none;font-size:1.15rem;color:#9aa7ad;cursor:pointer;line-height:1;padding:4px}'
        +'@media(max-width:900px){#sd-nudge{left:10px;right:10px;max-width:none;bottom:72px}}';
      document.head.appendChild(css);
      document.body.appendChild(b);
      try{if(window.gtag)gtag('event','shindan_nudge_show',{page_path:path});}catch(e){}
      document.getElementById('sd-nudge-x').addEventListener('click',function(){
        localStorage.setItem('en_sd_hide',String(Date.now()));
        b.remove();
      });
      b.querySelector('.b').addEventListener('click',function(){
        try{if(window.gtag)gtag('event','shindan_nudge_click',{page_path:path});}catch(e){}
        localStorage.setItem('en_sd_hide',String(Date.now()));
      });
    };
    setTimeout(show,1500);
  }catch(e){}
})();
