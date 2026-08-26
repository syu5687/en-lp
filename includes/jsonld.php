<?php
/**
 * サイト共通の構造化データ（LLMO/SEO）。
 * Organization + LocalBusiness + WebSite を全ページの<head>で出力。
 * head.php から読み込む（index.php は自前のJSON-LDを保持）。
 */
require_once __DIR__ . '/config.php';

$org = [
  '@context' => 'https://schema.org',
  '@type'    => ['Organization', 'LocalBusiness'],
  '@id'      => SITE['url'] . '/#organization',
  'name'     => SITE['name'],
  'alternateName' => '縁（えん）',
  'url'      => SITE['url'] . '/',
  'logo'     => SITE['url'] . SITE['logo'],
  'image'    => SITE['url'] . SITE['logo'],
  'telephone'=> SITE['tel'],
  'email'    => SITE['email'],
  'priceRange' => '¥¥',
  'description' => '鹿児島を拠点に、海洋散骨・粉骨・お墓じまい・樹木葬・お手元供養・ペット供養を全国対応で提供する、ご供養のトータルサポート事業者。',
  'address'  => [
    '@type' => 'PostalAddress',
    'postalCode' => SITE['zip'],
    'addressRegion' => '鹿児島県',
    'addressLocality' => '鹿児島市',
    'streetAddress' => '坂之上7丁目7-3',
    'addressCountry' => 'JP',
  ],
  'areaServed' => [
    ['@type' => 'State', 'name' => '鹿児島県'],
    ['@type' => 'State', 'name' => '福岡県'],
    ['@type' => 'AdministrativeArea', 'name' => '九州'],
    ['@type' => 'Country', 'name' => '日本'],
  ],
  'department' => [[
    '@type' => 'LocalBusiness',
    'name'  => SITE['name'] . ' ' . SITE['fukuoka']['name'],
    'hasMap' => 'https://maps.google.com/?cid=1235913108976072113',
    'telephone' => SITE['fukuoka']['tel'],
    'address' => [
      '@type' => 'PostalAddress',
      'postalCode' => SITE['fukuoka']['zip'],
      'addressRegion' => '福岡県',
      'addressLocality' => '福岡市中央区',
      'streetAddress' => '春吉2丁目1-3 2F',
      'addressCountry' => 'JP',
    ],
  ]],
  'openingHoursSpecification' => [[
    '@type' => 'OpeningHoursSpecification',
    'dayOfWeek' => ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'],
    'opens' => '09:00', 'closes' => '18:00',
  ]],
  'hasMap' => 'https://maps.google.com/?cid=2494401172745547436',
  'sameAs' => ['https://www.instagram.com/en1150en/'],
  'memberOf' => ['@type' => 'Organization', 'name' => '一般社団法人日本海洋散骨協会'],
  'knowsAbout' => ['海洋散骨','粉骨','洗骨','お墓じまい','改葬','樹木葬','お手元供養','ペット供養','遺品整理','終活'],
  'founder' => ['@type' => 'Person', 'name' => '堤 裕加里'],
  'hasOfferCatalog' => [
    '@type' => 'OfferCatalog',
    'name'  => 'ご供養サービス',
    'itemListElement' => array_map(fn($s) => [
      '@type' => 'Offer',
      'itemOffered' => ['@type' => 'Service', 'name' => $s['title'], 'url' => SITE['url'] . '/' . $s['slug'] . '/'],
    ], SERVICES),
  ],
];

$website = [
  '@context' => 'https://schema.org',
  '@type'    => 'WebSite',
  '@id'      => SITE['url'] . '/#website',
  'url'      => SITE['url'] . '/',
  'name'     => SITE['name'] . '｜' . SITE['tagline'],
  'inLanguage' => 'ja',
  'publisher' => ['@id' => SITE['url'] . '/#organization'],
];
?>
<script type="application/ld+json"><?= json_encode($org, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?></script>
<script type="application/ld+json"><?= json_encode($website, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?></script>
