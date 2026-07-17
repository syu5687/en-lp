<?php
require_once __DIR__ . '/../includes/config.php';
$page_title     = '供養さがし｜あなたに合ったご供養が見つかる、かんたん診断｜' . SITE['name'];
$page_desc      = 'いくつかの質問に答えるだけで、あなたに合ったご供養（海洋葬・樹木葬・お墓じまい・お手元供養など）をご提案します。迷ったときはお気軽にご相談ください。' . SITE['name'] . '。';
$page_canonical = SITE['url'] . '/shindan/';
require __DIR__ . '/../includes/head.php';
?>
<body>
<?php require __DIR__ . '/../includes/header.php'; ?>
<section class="page-hero">
  <h1>供養さがし</h1>
  <p>いくつかの質問に答えるだけ。あなたに寄り添うご供養を、ご提案します。</p>
</section>
<nav class="breadcrumb"><a href="/">ホーム</a> ＞ 供養さがし</nav>

<style>
.shindan{--sd-ink:#23201b;--sd-blue:#15709e;--sd-teal:#2b7d76;}
.shindan-lead{max-width:720px;margin:0 auto 8px;text-align:center;font-size:1rem;line-height:2;color:var(--text)}
.shindan-note{max-width:720px;margin:0 auto 32px;text-align:center;font-size:.82rem;color:var(--text-light)}
.shindan-card{max-width:760px;margin:0 auto;background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);box-shadow:var(--shadow);padding:34px 30px 30px}
.sd-progress{display:flex;align-items:center;justify-content:center;gap:8px;margin-bottom:22px}
.sd-dot{width:9px;height:9px;border-radius:50%;background:var(--cream-dark);transition:.3s}
.sd-dot.on{background:var(--green);transform:scale(1.15)}
.sd-q{font-family:var(--serif);font-size:1.3rem;color:var(--green-mid);text-align:center;line-height:1.6;margin-bottom:6px}
.sd-q::before{content:'';display:block;width:52px;height:11px;margin:0 auto 14px;background:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='58' height='12' viewBox='0 0 58 12' fill='none' stroke='%232e9fd4' stroke-width='1.7' stroke-linecap='round'%3E%3Cpath d='M1 7 C 8 1.5, 15 1.5, 21 7 C 27 12.5, 34 12.5, 40 7 C 46 1.5, 52 1.5, 57 6'/%3E%3C/svg%3E") no-repeat center/contain}
.sd-qsub{text-align:center;font-size:.86rem;color:var(--text-light);margin-bottom:24px}
.sd-options{display:grid;gap:12px;margin-top:20px}
.sd-opt{display:flex;flex-direction:column;gap:3px;text-align:left;background:var(--sea-light);border:1px solid var(--border);border-radius:12px;padding:16px 20px;cursor:pointer;transition:.2s;font-family:inherit;color:var(--text);width:100%}
.sd-opt:hover{background:#fff;border-color:var(--green);transform:translateY(-2px);box-shadow:0 8px 20px rgba(21,112,158,.12)}
.sd-opt b{font-size:1rem;font-weight:600;color:var(--green-mid)}
.sd-opt span{font-size:.82rem;color:var(--text-light)}
.sd-back{display:inline-flex;align-items:center;gap:6px;margin-top:22px;background:none;border:none;color:var(--text-light);font-size:.85rem;cursor:pointer;font-family:inherit}
.sd-back:hover{color:var(--green)}
/* result */
.sd-result-label{text-align:center;font-size:.8rem;letter-spacing:.16em;color:var(--green);font-weight:600;margin-bottom:6px}
.sd-result-name{font-family:var(--serif);font-size:1.5rem;color:var(--green-mid);text-align:center;line-height:1.5;margin-bottom:12px}
.sd-result-lead{text-align:center;font-size:.95rem;line-height:1.9;color:var(--text);max-width:560px;margin:0 auto 22px}
.sd-result-actions{display:flex;flex-wrap:wrap;gap:12px;justify-content:center;margin-bottom:8px}
.sd-alt{margin-top:26px;padding-top:22px;border-top:1px solid var(--line)}
.sd-alt h4{font-size:.9rem;color:var(--text-light);text-align:center;margin-bottom:12px;font-weight:600}
.sd-alt-list{display:flex;flex-wrap:wrap;gap:10px;justify-content:center}
.sd-alt-list a{font-size:.86rem;color:var(--green);border:1px solid var(--border);border-radius:999px;padding:7px 16px;background:#fff}
.sd-alt-list a:hover{background:var(--green);color:#fff;border-color:var(--green)}
.sd-reassure{max-width:620px;margin:26px auto 0;background:var(--sea-light);border-radius:12px;padding:18px 22px;font-size:.86rem;line-height:1.9;color:var(--text);text-align:center}
.sd-contact{margin-top:34px;background:linear-gradient(135deg,var(--green),var(--green-mid));border-radius:var(--radius-lg);color:#fff;text-align:center;padding:30px 24px}
.sd-contact h3{color:#fff;font-family:var(--serif);font-size:1.2rem;margin-bottom:8px}
.sd-contact p{opacity:.94;font-size:.9rem;margin-bottom:18px}
.sd-contact .btns{display:flex;flex-wrap:wrap;gap:10px;justify-content:center}
.sd-tel{display:block;margin-top:14px;color:#fff;font-weight:700;font-size:1.15rem}
@media(max-width:560px){.shindan-card{padding:26px 18px}.sd-q{font-size:1.12rem}.sd-result-name{font-size:1.28rem}}
</style>

<main class="section shindan">
  <div class="container">
    <p class="shindan-lead">「どのご供養が合うのかわからない」「相談していいのか迷う」——そんな方へ。<br>いくつかの質問にお答えいただくだけで、あなたのお気持ちに寄り添うご供養をご提案します。</p>
    <p class="shindan-note">※ 診断はあくまで目安です。ご事情やご希望に合わせて最適なご供養をご案内しますので、どうぞご安心ください。</p>

    <div class="shindan-card">
      <div id="sd-quiz"><!-- JSで描画 --></div>
    </div>

    <div style="text-align:center;margin-top:24px">
      <p style="font-size:.9rem;color:var(--text-light)">迷ったら、質問に答えずそのままご相談いただいても大丈夫です。</p>
      <a href="/contact/" class="btn btn--outline" style="margin-top:10px">まずは無料で相談する</a>
    </div>
  </div>
</main>

<script>
(function(){
  var SITE_TEL = <?= json_encode(SITE['tel']) ?>;
  var SITE_LINE = <?= json_encode(SITE['line_url']) ?>;
  var SV = {
    'kaiyou-sou':{name:'海洋葬（海洋散骨）',href:'/kaiyou-sou/',lead:'亡くなられた方のご遺骨を、広く自由な海へお還しするご供養です。「自然に還りたい」「海が好きだった」という想いに寄り添います。'},
    'teien-sou':{name:'樹木葬（庭苑葬）',href:'/teien-sou/',lead:'美しい草花に囲まれて眠る、自然の中の新しいご供養のかたち。継承者がいなくても安心の永代供養にも対応します。'},
    'grave':{name:'お墓じまい',href:'/grave/',lead:'お墓の管理・継承のご負担に寄り添い、改葬から撤去・その後のご供養まで一貫してサポートします。'},
    'hikkoshi':{name:'お墓のお引越し（改葬）',href:'/hikkoshi/',lead:'ご遺骨を別の場所へ移す「改葬」。必要な手続きの流れから、まるごとお手伝いいたします。'},
    'temoto-kuyou':{name:'お手元供養',href:'/temoto-kuyou/',lead:'ご自宅で、いつもそばに感じられるご供養のかたち。ご遺骨の一部を手元に残すこともできます。'},
    'powder-cleaning':{name:'粉骨・洗骨',href:'/powder-cleaning/',lead:'ご遺骨をパウダー状にする「粉骨」、汚れたご遺骨をきれいにする「洗骨」。散骨や手元供養の前の準備にも。'},
    'pet-kaiyou-sou':{name:'ペット供養（ペット海洋葬）',href:'/pet-kaiyou-sou/',lead:'大切なご家族であるペットを、鹿児島・錦江湾から自然の海へお見送りするご供養です。'},
    'jewelry-reform':{name:'JEWELRYリフォーム',href:'/jewelry-reform/',lead:'ご遺骨や思い出の宝石・貴金属を、身につけられるメモリアルジュエリーへ。いつもそばに感じられます。'}
  };
  var TREE = {
    start:{q:'どなたのご供養をお考えですか？',opts:[
      {b:'ご家族・大切な方',next:'concern'},
      {b:'ペット',res:'pet-kaiyou-sou'}
    ]},
    concern:{q:'いま、いちばん近いお気持ちは？',sub:'直感でお選びください',opts:[
      {b:'自然に還してあげたい',s:'海や緑が好きだった',next:'natural'},
      {b:'お墓の管理・継承が負担',s:'お墓を整理したい',res:'grave',alt:['kaiyou-sou','teien-sou']},
      {b:'お墓を別の場所へ移したい',s:'改葬を考えている',res:'hikkoshi'},
      {b:'いつもそばに置いて供養したい',s:'手元で偲びたい',res:'temoto-kuyou',alt:['jewelry-reform']},
      {b:'ご遺骨をきれいに・粉にしたい',s:'粉骨・洗骨',res:'powder-cleaning'}
    ]},
    natural:{q:'どちらのイメージが近いですか？',opts:[
      {b:'海へ還したい',s:'広い海で眠りたい',res:'kaiyou-sou',alt:['powder-cleaning']},
      {b:'緑・樹木のそばで',s:'草花に囲まれて眠りたい',res:'teien-sou',alt:['grave']}
    ]}
  };
  var STEPS = 3; // 進捗ドットの目安
  var history = ['start'];

  var quiz = document.getElementById('sd-quiz');
  function esc(s){return String(s).replace(/[&<>"]/g,function(c){return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;'}[c];});}

  function progress(depth){
    var h='<div class="sd-progress" aria-hidden="true">';
    for(var i=0;i<STEPS;i++){h+='<span class="sd-dot'+(i<=depth?' on':'')+'"></span>';}
    return h+'</div>';
  }

  function renderNode(key){
    var node=TREE[key], depth=history.length-1;
    var h=progress(depth);
    h+='<div class="sd-q">'+esc(node.q)+'</div>';
    if(node.sub){h+='<div class="sd-qsub">'+esc(node.sub)+'</div>';}
    h+='<div class="sd-options">';
    node.opts.forEach(function(o,idx){
      h+='<button class="sd-opt" data-idx="'+idx+'"><b>'+esc(o.b)+'</b>'+(o.s?'<span>'+esc(o.s)+'</span>':'')+'</button>';
    });
    h+='</div>';
    if(history.length>1){h+='<button class="sd-back" data-back="1">← 前の質問にもどる</button>';}
    quiz.innerHTML=h;
    quiz.querySelectorAll('.sd-opt').forEach(function(btn){
      btn.addEventListener('click',function(){
        var o=node.opts[+btn.getAttribute('data-idx')];
        if(o.next){history.push(o.next);renderNode(o.next);scrollCard();}
        else if(o.res){renderResult(o.res,o.alt||[]);scrollCard();}
      });
    });
    var back=quiz.querySelector('[data-back]');
    if(back){back.addEventListener('click',function(){history.pop();renderNode(history[history.length-1]);scrollCard();});}
  }

  function renderResult(slug,alt){
    var s=SV[slug];
    var h='<p class="sd-result-label">あなたにおすすめのご供養</p>';
    h+='<h2 class="sd-result-name">'+esc(s.name)+'</h2>';
    h+='<p class="sd-result-lead">'+esc(s.lead)+'</p>';
    h+='<div class="sd-result-actions"><a href="'+s.href+'" class="btn">詳しく見る</a><a href="/contact/" class="btn btn--outline">このご供養を相談する</a></div>';
    if(alt&&alt.length){
      h+='<div class="sd-alt"><h4>あわせて検討される方が多いご供養</h4><div class="sd-alt-list">';
      alt.forEach(function(a){ if(SV[a]) h+='<a href="'+SV[a].href+'">'+esc(SV[a].name)+'</a>'; });
      h+='</div></div>';
    }
    h+='<p class="sd-reassure">「これで合っているのかな」と迷われても大丈夫です。ご家族それぞれに事情や想いがあります。専門のスタッフが、おひとりおひとりのお気持ちに合わせて、いちばん安心できるご供養を一緒に考えます。宗教・宗派は問いません。</p>';
    h+='<div class="sd-contact"><h3>まずはお気軽にご相談ください</h3><p>ご相談・お見積りは無料です。しつこい勧誘は一切いたしません。</p><div class="btns"><a href="/contact/" class="btn" style="background:#fff;color:var(--green-mid)">お問い合わせフォーム</a><a href="'+SITE_LINE+'" target="_blank" rel="noopener" class="btn" style="background:#06C755">LINEで相談</a></div><a href="tel:'+SITE_TEL+'" class="sd-tel">'+SITE_TEL+'</a></div>';
    h+='<div style="text-align:center"><button class="sd-back" data-restart="1">↺ もう一度診断する</button></div>';
    quiz.innerHTML=h;
    var r=quiz.querySelector('[data-restart]');
    if(r){r.addEventListener('click',function(){history=['start'];renderNode('start');scrollCard();});}
  }

  function scrollCard(){var c=document.querySelector('.shindan-card');if(c){var y=c.getBoundingClientRect().top+window.pageYOffset-90;window.scrollTo({top:y,behavior:'smooth'});}}

  renderNode('start');
})();
</script>
<?php require __DIR__ . '/../includes/footer.php'; ?>
