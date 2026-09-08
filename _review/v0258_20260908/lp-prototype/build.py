# -*- coding: utf-8 -*-
import json, io

SVG = {
 'phone':'<svg viewBox="0 0 24 24"><path d="M6.6 10.8a15.1 15.1 0 0 0 6.6 6.6l2.2-2.2c.3-.3.7-.4 1-.2 1.2.4 2.4.6 3.7.6.6 0 1 .4 1 1V20c0 .6-.4 1-1 1C10.7 21 3 13.3 3 3.9c0-.6.4-1 1-1h3.4c.6 0 1 .4 1 1 0 1.3.2 2.5.6 3.7.1.3 0 .7-.2 1l-2.2 2.2z"/></svg>',
 'heart':'<svg viewBox="0 0 24 24"><path d="M12 20.5S3.5 15.3 3.5 9.4A4.6 4.6 0 0 1 12 6.9a4.6 4.6 0 0 1 8.5 2.5c0 5.9-8.5 11.1-8.5 11.1z"/></svg>',
 'ship':'<svg viewBox="0 0 24 24"><path d="M3.2 15.4 5 10.6h14l1.8 4.8"/><path d="M2.6 15.4h18.8c-.6 2.9-2.4 4.7-5.2 4.7H7.8c-2.8 0-4.6-1.8-5.2-4.7z"/><path d="M8.5 10.6V6.6h7v4"/><path d="M12 6.6V3.8"/></svg>',
 'doc':'<svg viewBox="0 0 24 24"><path d="M6 2.8h8l4.2 4.2v14.2H6z"/><path d="M14 2.8V7h4.2"/><path d="M8.6 12h6.8M8.6 15.2h6.8M8.6 18.4h4.2"/></svg>',
 'family':'<svg viewBox="0 0 24 24"><circle cx="12" cy="6.6" r="2.4"/><circle cx="5.4" cy="8.6" r="2"/><circle cx="18.6" cy="8.6" r="2"/><path d="M8.2 18.6c0-2.5 1.7-4.3 3.8-4.3s3.8 1.8 3.8 4.3"/><path d="M2 18.6c0-2.1 1.4-3.6 3.4-3.6.6 0 1.2.1 1.7.4"/><path d="M22 18.6c0-2.1-1.4-3.6-3.4-3.6-.6 0-1.2.1-1.7.4"/></svg>',
 'powder':'<svg viewBox="0 0 24 24"><path d="M3.6 6.4h16.8v11.2H3.6z"/><path d="m3.6 6.4 8.4 6 8.4-6"/></svg>',
 'chrom':'<svg viewBox="0 0 24 24"><path d="M10 3.2h4M11 3.2v5.1L6.4 17c-.7 1.3.2 2.8 1.7 2.8h7.8c1.5 0 2.4-1.5 1.7-2.8L13 8.3V3.2"/><path d="M4 4 20 20"/></svg>',
 'photo':'<svg viewBox="0 0 24 24"><path d="M6 2.8h8l4.2 4.2v14.2H6z"/><path d="M14 2.8V7h4.2"/><path d="M8.6 13.4h6.8M8.6 16.6h4.6"/></svg>',
 'grave':'<svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="8.6"/><path d="M12 3.4v17.2M3.4 12h17.2"/></svg>',
 'mail':'<svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.5"><rect x="2.6" y="5.2" width="18.8" height="13.6" rx="1.4"/><path d="m2.6 6.4 9.4 6.6 9.4-6.6"/></svg>',
 'line':'<svg viewBox="0 0 40 40"><circle cx="20" cy="20" r="19" fill="#fff"/><path fill="#1a8a3c" d="M20 8.6c-6.7 0-12.2 4.4-12.2 9.8 0 4.8 4.3 8.9 10.2 9.7.4.1.9.3 1.1.6.1.3.1.7 0 1l-.2 1.1c0 .3-.3 1.3 1.1.7 1.4-.6 7.6-4.5 10.3-7.7 1.9-2 2.8-4.1 2.8-5.4 0-5.4-5.5-9.8-13.1-9.8z"/><g fill="#fff"><rect x="14.2" y="15.6" width="1.5" height="5.6" rx=".4"/><rect x="17" y="15.6" width="1.5" height="5.6" rx=".4"/><path d="M20.4 15.6h1.3l2.2 3.1v-3.1h1.4v5.6h-1.3l-2.2-3.1v3.1h-1.4z"/><path d="M26.6 15.6h3.4v1.4h-2v.8h1.9v1.4h-1.9v.7h2v1.3h-3.4z"/><path d="M11 15.6h1.5v4.2h2.1v1.4H11z"/></g></svg>',
 'arrow':'<svg viewBox="0 0 24 24" fill="none" stroke="#16375c" stroke-width="1.4"><path d="m9 5 7 7-7 7"/></svg>',
}

SCHED = json.load(open('schedule.json', encoding='utf-8'))

D = {
 'kg': dict(
   slug='kagoshima', area='kagoshima', tag='kg',
   title='鹿児島の海洋散骨｜錦江湾で心に残るお見送り｜en1150（有限会社縁）',
   desc='鹿児島・錦江湾の海洋散骨。委託・合同・チャーターの明朗な料金、六価クロム検査、緯度経度入りの散骨証明書。墓じまいから散骨まで一括対応します。',
   tel='099-801-3637', telraw='0998013637',
   h1a='鹿児島の海に、', h1b='大切な想いを。',
   sub='錦江湾で、心に残る海洋散骨を',
   whisper='ありがとう、<br>また、きっと。',
   pill2='錦江湾での<br>安心の散骨',
   schedttl='鹿児島・錦江湾 合同海洋散骨',
   leadh='想いは、海へ、そして未来へ',
   leadp='美しい錦江湾の海に、<br>故人様の想いをそっとお還しするお手伝いをいたします。<br>ご家族の気持ちに寄り添い、安心と信頼の海洋散骨をお約束します。',
   striptxt='鹿児島の美しい海で、<br>大切な想いを未来へ',
 ),
 'fk': dict(
   slug='fukuoka', area='fukuoka', tag='fk',
   title='福岡の海洋散骨｜博多湾で心に残るお見送り｜en1150（有限会社縁）',
   desc='福岡・博多湾の海洋散骨。委託・合同・チャーターの明朗な料金、六価クロム検査、緯度経度入りの散骨証明書。粉骨・洗骨・分骨にも対応します。',
   tel='090-5000-4825', telraw='09050004825',
   h1a='福岡の海に、', h1b='変わらぬ想いを。',
   sub='博多湾の、やすらかな海へ',
   whisper='いつまでも、<br>あなたとこの海で。',
   pill2='博多湾での<br>安心の散骨',
   schedttl='福岡・博多湾 合同海洋散骨',
   leadh='大切な想いを、海とともに',
   leadp='博多湾の美しい海に、<br>故人様の想いをお還しするお手伝いをいたします。<br>ご家族の気持ちに寄り添い、安心と信頼の海洋散骨をお約束します。',
   striptxt='福岡の海で、<br>大切な想いを未来へ',
 ),
}

NAV = [('海洋散骨とは','#about'),('選ばれる理由','#reason'),('料金プラン','#price'),
       ('実施予定日','#schedule'),('散骨レポート','#report'),('よくあるご質問','#faq')]

PLANS = [
 ('kg-plan1.jpg','fk-plan1.jpg','委託海洋葬','ご遺骨をお預かりし、<br>弊社にて散骨を行います','54,450','円（税込）〜','※期間限定価格（通常 66,000円）'),
 ('kg-plan2.jpg','fk-plan2.jpg','合同海洋葬','他のご家族と一緒に行う<br>定期の散骨です','148,500','円（税込）〜',''),
 ('kg-plan3.jpg','fk-plan3.jpg','チャーター海洋葬','ご家族だけでゆっくりと<br>お見送りいただけます','176,000','円（税込）〜',''),
]

FEATS = [
 ('powder','粉骨対応','24,200円（税込）〜'),
 ('chrom','六価クロム検査・無害化','追加料金なし／2019年から実施'),
 ('photo','散骨写真・散骨証明書','緯度・経度入りでお届け'),
 ('grave','墓じまいから散骨まで','一括対応'),
]

LINE_URL = 'https://line.me/R/ti/p/%40bkx9825r'

def pills(d):
    items = [('heart','追加費用なし<br>明朗な料金'), ('ship', d['pill2']),
             ('doc','散骨証明書<br>を発行'), ('family','ご家族に寄り添う<br>丁寧なサポート')]
    return '\n'.join(
      '      <div class="pill">%s<span>%s</span></div>' % (SVG[i], t) for i,t in items)

def build(k):
    d = D[k]; t = d['tag']
    ev = [e for e in SCHED['events'] if e['area']==d['area']]
    ev = sorted(ev, key=lambda e:e['date'])
    nav = '\n'.join('        <a href="%s">%s</a>'%(u,n) for n,u in NAV)
    plan_html = '\n'.join("""        <div class="plan">
          <img src="assets/img/%s" alt="%s">
          <h3 class="plan__nm">%s</h3>
          <p class="plan__ds">%s</p>
          <p class="plan__pr">%s<small>%s</small></p>
          %s
        </div>""" % (p[0] if k=='kg' else p[1], p[2], p[2], p[3], p[4], p[5],
                     ('<p class="plan__note">%s</p>'%p[6]) if p[6] else '')
        for p in PLANS)
    feat_html = '\n'.join("""        <div class="feat__i"><span class="ic">%s</span><div>%s<small>%s</small></div></div>"""
        % (SVG[i], a, b) for i,a,b in FEATS)
    sched_js = json.dumps(ev, ensure_ascii=False)

    return """<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex,nofollow">
<title>{title}</title>
<meta name="description" content="{desc}">
<link rel="stylesheet" href="assets/lp.css">
</head>
<body>

<header class="hd">
  <div class="hd__inner">
    <div class="brand">
      <div class="brand__name">en1150</div>
      <div class="brand__tag">海に還る、想いをつなぐ</div>
    </div>
    <div class="hd__right">
      <div class="hd__tel">
        <a href="tel:{telraw}">{phone}{tel}</a>
        <span class="hd__hours">（月〜土 9:00〜18:00）</span>
      </div>
      <nav class="hd__nav">
{nav}
        <a class="btn-cta" href="https://en1150.co.jp/contact/">お問い合わせ</a>
      </nav>
    </div>
  </div>
</header>

<section class="fv" style="background-image:url('assets/img/{tag}-hero.jpg')">
  <div class="fv__in">
    <div class="fv__copy">
      <h1>{h1a}<span class="l2">{h1b}</span></h1>
      <p class="fv__sub">{sub}</p>
    </div>
    <div class="medal">
      <span class="medal__lb">海洋散骨<br>対応実績</span>
      <span class="medal__num">3,800<small>件超</small></span>
      <span class="medal__note">（全国・累計）</span>
    </div>
    <p class="fv__whisper">{whisper}</p>
    <div class="fv__pills">
{pills}
    </div>
  </div>
</section>

<div class="schedwrap" id="schedule">
  <div class="sched" id="schedBox"></div>
</div>

<section class="lead" id="about">
  <div class="wrap">
    <h2>{leadh}</h2>
    <p>{leadp}</p>
  </div>
</section>

<section class="plans" id="price">
  <div class="wrap">
    <h2>選べる3つのプラン</h2>
    <div class="plans__grid">
{plans}
    </div>
    <div class="feat" id="reason">
{feats}
    </div>
  </div>
</section>

<div class="strip" id="report">
  <figure style="background-image:url('assets/img/{tag}-s1.jpg')"></figure>
  <figure style="background-image:url('assets/img/{tag}-s2.jpg')"></figure>
  <figure class="strip__txt"><span>{striptxt}</span></figure>
  <figure style="background-image:url('assets/img/{tag}-s4.jpg')"></figure>
  <figure style="background-image:url('assets/img/{tag}-s5.jpg')"></figure>
</div>

<div class="cta" id="faq">
  <a class="cta__mail" href="https://en1150.co.jp/contact/">
    {mail}<span><b>資料請求・お問い合わせ</b><small>（24時間受付）</small></span>
  </a>
  <a class="cta__line" href="{lineurl}">
    {line}<span><b>LINEで相談する</b><small>（お気軽にご相談ください）</small></span>
  </a>
</div>

<footer class="ft">
  <div class="ft__nm">有限会社縁（en1150）</div>
  <div>一般社団法人 日本海洋散骨協会 加盟</div>
</footer>

<script>
(function(){{
  var EV = {schedjs};
  var box = document.getElementById('schedBox');
  if(!EV.length){{
    box.className = 'sched sched--empty';
    box.textContent = '次回開催日は現在調整中です。決まり次第、こちらでお知らせいたします。';
    return;
  }}
  var e = EV[0];
  var m = e.label.match(/(\\d+)年(\\d+)月(\\d+)日（(.)）/);
  var dateHtml = m ? (m[1] + '年<b>' + m[2] + '</b>月<b>' + m[3] + '</b>日（' + m[4] + '）') : e.label;
  box.innerHTML =
    '<div class="sched__img" style="background-image:url(assets/img/{tag}-boat.jpg)"></div>' +
    '<div class="sched__body">' +
      '<div class="sched__top"><span class="tag-next">次回開催予定</span>' +
      '<span class="sched__ttl">{schedttl}</span></div>' +
      '<div class="sched__date">' + dateHtml + '</div>' +
      '<div class="sched__meta">出航地：{port}<em>※天候により変更・中止となる場合があります</em></div>' +
    '</div>' +
    '<a class="sched__btn" href="https://en1150.co.jp/kaiyou-sou/">今後の開催予定を見る {arrow}</a>';
}})();
</script>

<!-- ================= 制作メモ（公開前に削除） =================
  ・本ファイルはGPTデザイン案を再現した実装版です。写真は同デザイン画像から書き出した「仮画像」です。
    テキストなしの元画像（またはは実写）をいただき次第、assets/img/ を差し替えてください。
  ・数値は 2026-09-07 時点で en1150.co.jp 上で確認できた値に合わせています。
    デザイン案の 33,000 / 55,000 / 220,000 円、0120-1150-38、開催日、福岡800件 は
    サイト上で確認できなかったため採用していません。確認後に差し替えます。
  ・福岡版の「関門」表記は、確認が取れるまで記載していません（ヒーロー画像の橋も差し替え対象）。
  ・GA4イベントは既存名のみを使用する方針のため、本ファイルには計測タグを入れていません。
    計測正常化の完了後に、既存の floating-cta.js / gtag 設定へ合流させます。
============================================================ -->
</body>
</html>
""".format(title=d['title'], desc=d['desc'], telraw=d['telraw'], tel=d['tel'],
           phone=SVG['phone'], nav=nav, tag=t, h1a=d['h1a'], h1b=d['h1b'], sub=d['sub'],
           whisper=d['whisper'], pills=pills(d), leadh=d['leadh'], leadp=d['leadp'],
           plans=plan_html, feats=feat_html, striptxt=d['striptxt'],
           mail=SVG['mail'], line=SVG['line'], lineurl=LINE_URL,
           schedjs=sched_js, schedttl=d['schedttl'],
           port=SCHED['ports'][d['area']]['name']+'（'+SCHED['ports'][d['area']]['address']+'）', arrow=SVG['arrow'])

for k in ('kg','fk'):
    fn = 'lp-%s.html' % D[k]['slug']
    open(fn,'w',encoding='utf-8').write(build(k))
    print(fn, 'ok')
