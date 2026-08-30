<?php
/**
 * ブログ記事 → 関連サービスへの誘導（判定ロジックと本文中インライン導線）
 *
 * 背景：流入の大半は旧WordPress記事だが、記事末の導線までスクロールされずに離脱している。
 *   ・判定はカテゴリ＋タイトルだけでなく本文も見る（旧記事はカテゴリが「未分類」「ブログ」で
 *     判定に使えないものが多く、最大流入の記事が汎用フォールバックに落ちていた）
 *   ・スコアリング方式にして、最も関係の深いサービスを1〜2件だけ出す
 *   ・記事の途中にも1つだけ、静かなトーンで導線を置く（押し付けない）
 *
 * 使い方（記事詳細ページ）:
 *   require __DIR__ . '/../includes/blog-related.php';
 *   $rel = en_related_services($post);                 // 記事末ブロック用
 *   $body_html = en_inject_inline_cta($body_html, $rel); // 本文中に1つ挿入
 */

/** 記事から関連サービスを判定して返す（最大2件） */
function en_related_services(array $post): array
{
    // 判定に使う文字列。カテゴリ・タイトルを厚めに、本文は先頭のみ（記事の主題を拾う）
    $title = (string)($post['title'] ?? '');
    $cat   = (string)($post['category'] ?? '');
    $body  = (string)($post['body_html'] ?? $post['body'] ?? '');
    $body  = mb_substr(strip_tags($body), 0, 1200);

    // 定義：kw = 判定語、weight は タイトル/カテゴリ命中=3点、本文命中=1点
    $map = [
        'kaiyou' => [
            'kw'    => ['海洋散骨', '海洋葬', '散骨', '船酔い', '乗船', '出港', 'クルーズ', '錦江湾', '博多湾', '献花'],
            'href'  => '/kaiyou-sou/',
            'label' => '海洋葬（海洋散骨）',
            'desc'  => '委託海洋葬 54,450円〜（税込）。鹿児島・福岡・九州の海域に対応し、全国からご利用いただけます。',
            'inline'=> ['海洋散骨をお考えでしたら', '委託海洋葬は54,450円（税込）から。粉骨・散骨証明書・当日の写真まで含んだ料金です。'],
        ],
        'powder' => [
            'kw'    => ['粉骨', 'パウダー', '洗骨', '再火葬', 'カビ', '湿気', '骨の重さ', '骨 重さ', '重量', '骨壷が', '骨壺が', '六価クロム', '真空パック'],
            'href'  => '/powder-cleaning/',
            'label' => '粉骨・洗骨',
            'desc'  => 'すべて手作業で一件ずつ丁寧に。粉骨24,200円〜、ご遺骨の郵送で全国対応します。',
            'inline'=> ['ご遺骨のことでお困りでしたら', '粉骨は24,200円（税込）から。洗骨・乾燥・真空パックにも対応し、ご遺骨の郵送でご依頼いただけます。'],
        ],
        'grave' => [
            'kw'    => ['墓じまい', 'お墓じまい', '改葬', '離檀', '墓石', '撤去', '土葬', '納骨堂', '永代供養', '無縁墓', '庭に埋め', '墓地'],
            'href'  => '/grave/',
            'label' => 'お墓じまい',
            'desc'  => '撤去から納骨・改葬手続きの代行まで一括対応。取り出したご遺骨の行き先もご相談いただけます。',
            'inline'=> ['お墓のことでお悩みでしたら', '墓じまいは撤去から納骨まで基本プラン33万円（税込）。改葬許可の手続きもお手伝いします。'],
        ],
        'temoto' => [
            'kw'    => ['手元供養', 'お手元供養', 'ミニ骨壷', 'ミニ骨壺', 'ジュエリー', 'ペンダント', '分骨'],
            'href'  => '/temoto-kuyou/',
            'label' => 'お手元供養',
            'desc'  => 'ミニ骨壷・ミニ仏壇・メモリアルジュエリーで、いつも身近にご供養を。',
            'inline'=> ['ご遺骨を手元に残したい方へ', 'ミニ骨壷やメモリアルジュエリーでのご供養も承ります。お持ち込み品への分骨は5,500円（税込）です。'],
        ],
        'pet' => [
            'kw'    => ['ペット', '愛犬', '愛猫', '動物'],
            'href'  => '/pet-kaiyou-sou/',
            'label' => 'ペット海洋葬',
            'desc'  => '鹿児島・錦江湾にて、半年に一度おこなうペット専用の委託海洋葬です。',
            'inline'=> ['ペットのご供養をお考えの方へ', '鹿児島・錦江湾で、半年に一度ペット専用の委託海洋葬をおこなっています。'],
        ],
        'teien' => [
            'kw'    => ['樹木葬', '庭苑葬', '自然葬'],
            'href'  => '/teien-sou/',
            'label' => '樹木葬（庭苑葬）',
            'desc'  => '草花に囲まれて眠る自然葬。個別墓・永代供養墓もご提案します。',
            'inline'=> ['樹木葬をご検討の方へ', '草花に囲まれて眠る自然葬です。個別墓・永代供養墓もあわせてご提案します。'],
        ],
        'ihin' => [
            'kw'    => ['遺品', '形見', 'お焚き上げ', '仏壇じまい'],
            'href'  => '/ihinseiri/',
            'label' => '遺品整理',
            'desc'  => '形見の仕分けからご供養・お焚き上げまで、心を込めて対応します。',
            'inline'=> ['遺品の整理でお困りでしたら', '仕分けからご供養・お焚き上げまで承ります。'],
        ],
        'seizen' => [
            'kw'    => ['生前契約', '終活', 'エンディングノート', '生前予約'],
            'href'  => '/seizen/',
            'label' => '海洋散骨 生前契約',
            'desc'  => '「海に還りたい」という想いを、お元気なうちに契約して託せます。',
            'inline'=> ['ご自身のことをお考えの方へ', '「海に還りたい」という希望を、お元気なうちに契約として残しておけます。'],
        ],
    ];

    $scored = [];
    foreach ($map as $key => $m) {
        $score = 0;
        foreach ($m['kw'] as $kw) {
            if (mb_strpos($title, $kw) !== false) $score += 3;
            if (mb_strpos($cat, $kw) !== false)   $score += 3;
            if (mb_strpos($body, $kw) !== false)  $score += 1;
        }
        if ($score > 0) $scored[$key] = $score;
    }
    arsort($scored);

    $rel = [];
    foreach (array_slice(array_keys($scored), 0, 2) as $key) $rel[] = $map[$key];

    // どのサービスにも寄らない記事（雑学・お知らせなど）は、押し売りにならない診断へ
    if (!$rel) {
        $rel[] = [
            'href'  => '/shindan/',
            'label' => '供養の選び方（かんたん診断）',
            'desc'  => 'いくつかの質問に答えるだけで、今のご状況に合うご供養の形を整理できます。',
            'inline'=> null,
        ];
    }
    return $rel;
}

/** 本文中に置く、静かなトーンの導線1件分のHTML（該当なしなら空文字） */
function en_inline_cta_html(array $rel): string
{
    $top = $rel[0] ?? null;
    if (!$top || empty($top['inline'])) return '';
    [$lead, $text] = $top['inline'];

    return '<aside class="post-inline-cta">'
         . '<p class="post-inline-cta__lead">' . h($lead) . '</p>'
         . '<p class="post-inline-cta__text">' . h($text) . '</p>'
         . '<p class="post-inline-cta__link"><a href="' . h($top['href']) . '">' . h($top['label']) . 'について見る →</a>'
         . '<span class="post-inline-cta__sub">ご相談・お見積りは無料です</span></p>'
         . '</aside>';
}

/**
 * 本文HTMLの「N番目の段落の直後」に導線を挿入する。
 * 段落が少ない記事（＝すぐ記事末の導線に届く記事）には入れない。
 */
function en_inject_inline_cta(string $html, array $rel, int $after = 3, int $min_paragraphs = 6): string
{
    $cta = en_inline_cta_html($rel);
    if ($cta === '') return $html;

    // 段落の終わりを数える（</p> が基本。旧WordPress記事は <br> 主体のものもあるため保険で <h2>/<h3> も見る）
    if (preg_match_all('#</p>#i', $html, $m, PREG_OFFSET_CAPTURE) && count($m[0]) >= $min_paragraphs) {
        $pos = $m[0][$after - 1][1] + strlen($m[0][$after - 1][0]);
        return substr($html, 0, $pos) . $cta . substr($html, $pos);
    }
    // 見出しが複数ある記事なら、2つ目の見出しの直前に置く
    if (preg_match_all('#<h[23][^>]*>#i', $html, $m2, PREG_OFFSET_CAPTURE) && count($m2[0]) >= 2) {
        $pos = $m2[0][1][1];
        return substr($html, 0, $pos) . $cta . substr($html, $pos);
    }
    return $html;
}
