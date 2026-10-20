<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/functions.php';
?>
<?php
/**
 * areas-we-serve/index.php — Areas We Serve: the ONE hub page with a map and the town list (Phase 6).
 * SEO brief (Oct 18 2026): "ONE /areas-we-serve/ page with map and town list (Wall, Veribest, Carlsbad, Grape Creek,
 * Water Valley). No templated town pages." Christoval has its own page; every other town is a card here.
 * (The generic Phase 6 prompt names /service-areas/ — the brief's URL wins, and config.php/header/footer already use it.)
 *
 * ---- RESEARCH (Oct 18 2026) — town facts only; company facts come from build-plan.json / config.php -----------------
 * https://en.wikipedia.org/wiki/Wall,_Texas        — unincorporated, 31°22′27″N 100°18′27″W; settled mid-1880s; named for James
 *     Madison Wall Sr., postmaster when the post office opened in 1906; "trading center for the fertile Lipan Flat region";
 *     ZIP 76957; Wall ISD serves surrounding rural areas of southeastern Tom Green County.
 * https://en.wikipedia.org/wiki/Veribest,_Texas    — unincorporated, 31°28′36″N 100°15′34″W; about 11 miles east of San Angelo on
 *     FM 380; community formed as Mullins, renamed Veribest with the 1926 post office request; ZIP 76886; Veribest ISD (PK-12).
 * https://en.wikipedia.org/wiki/Carlsbad,_Texas    — CDP, 31°36′41″N 100°38′26″W, NW Tom Green County on US 87 and the North Concho
 *     River; founded 1907 as Hughes, renamed for Karlsbad, Bohemia, after local wells produced mineral water; ZIP 76934;
 *     2,024 ft; 622 people (2020).
 * https://en.wikipedia.org/wiki/Grape_Creek,_Texas — CDP, 31°34′54″N 100°32′51″W, just north of US 87; Butterfield Overland Mail
 *     station on Grape Creek in 1858; Grape Creek ISD; 3,594 people (2020); 1,988 ft.
 * https://en.wikipedia.org/wiki/Water_Valley,_Texas — unincorporated, 31°40′04″N 100°43′01″W, on US 87, about 22 miles northwest of
 *     San Angelo; settlers came after Phelin and Glynn dammed the North Concho and dug an irrigation ditch; renamed Water
 *     Valley in 1889; ZIP 76958; Water Valley ISD.
 * https://en.wikipedia.org/wiki/South_Concho_River — Twin Buttes Reservoir in southwest San Angelo; San Angelo is the county seat.
 * Elevations differ between sources by a few feet — shown only where one source states it, as "about".
 * NOT verified, so left out: which utility or well field serves each town, water hardness, any town's plumbing rules.
 * ---------------------------------------------------------------------------------------------------------------------
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/service-cards.php';

$pageTitle       = "Areas We Serve Near San Angelo, TX | Rock'n G Plumbing";
$pageDescription = "Plumber serving San Angelo, Christoval, Wall, Veribest, Carlsbad, Grape Creek & Water Valley • Master Plumber M-44822, free estimates • Call (325) 650-7000.";
$canonicalUrl    = $siteUrl . '/areas-we-serve/';
$currentPage     = 'areas-we-serve';
$breadcrumbs     = [['name' => 'Service Areas', 'url' => '/areas-we-serve/']];
$ogImage         = defaultOgImage();

$telHref  = 'tel:' . $phoneE164;
$phoneFmt = formatPhone($phone);
$weekday  = hoursGroups($hours)[0];      // ['Mon–Fri', '8:00 AM – 6:00 PM']

// One entry per place. 'lat'/'lng' come from the Wikipedia infoboxes (San Angelo = the GBP listing's map centre in config.php).
// 'url' is set only where a page exists on disk; the five towns without a page of their own stay plain cards.
$arPlaces = [
    ['name' => 'San Angelo', 'zip' => '76901', 'lat' => $geo['lat'], 'lng' => $geo['lng'], 'url' => '/', 'cta' => 'See San Angelo plumbing services',
     'tag' => 'Home base', 'icon' => 'landmark', 'tint' => 1, 'feature' => true,
     'body' => "The Tom Green County seat and the city Rock'n G Plumbing is based in. The South Concho and Middle Concho rivers meet at Twin Buttes Reservoir in the southwest corner of town.",
     'facts' => ['County seat of Tom Green County', 'Primary service area', 'ZIP 76901']],
    ['name' => 'Christoval', 'zip' => '76935', 'lat' => 31.1975, 'lng' => -100.4933, 'url' => '/service-area/christoval-tx/', 'cta' => 'Plumber in Christoval, TX',
     'tag' => 'South on US 277', 'icon' => 'waves', 'tint' => 3, 'feature' => true,
     'body' => 'A small river town about 20 miles south of San Angelo, on the South Concho. It has its own page covering the river, the mix of town and rural properties, and how to book from there.',
     'facts' => ['About 20 miles south', 'South Concho River', 'ZIP 76935']],
    ['name' => 'Wall', 'zip' => '76957', 'lat' => 31.3742, 'lng' => -100.3075, 'tag' => 'Lipan Flat', 'icon' => 'sprout', 'tint' => 2,
     'body' => 'An unincorporated community southeast of San Angelo, long a trading center for the fertile Lipan Flat farmland and named for shopkeeper and postmaster James Madison Wall Sr. Wall ISD covers the rural ground around it.',
     'facts' => ['Post office opened 1906', 'Wall ISD', 'ZIP 76957']],
    ['name' => 'Veribest', 'zip' => '76886', 'lat' => 31.4767, 'lng' => -100.2594, 'tag' => 'East on FM 380', 'icon' => 'route', 'tint' => 1,
     'body' => 'About 11 miles east of San Angelo on FM 380. The settlement began as Mullins and took the name Veribest with its 1926 post office request, because the Texas community of Mullin had a similar name.',
     'facts' => ['About 11 miles east', 'Veribest ISD, PK-12', 'ZIP 76886']],
    ['name' => 'Carlsbad', 'zip' => '76934', 'lat' => 31.6114, 'lng' => -100.6406, 'tag' => 'North Concho', 'icon' => 'droplets', 'tint' => 3,
     'body' => 'Founded in 1907 as Hughes and renamed for the Bohemian spa town of Karlsbad after local wells produced mineral water. It sits on US 87 along the North Concho River, northwest of San Angelo.',
     'facts' => ['US 87 and the North Concho', 'About 2,024 ft', 'ZIP 76934']],
    ['name' => 'Grape Creek', 'lat' => 31.5817, 'lng' => -100.5475, 'tag' => 'Butterfield stop', 'icon' => 'mountain', 'tint' => 2,
     'body' => 'The largest of the towns beyond San Angelo, with 3,594 people in 2020, just north of US 87. A Butterfield Overland Mail station stood on Grape Creek in 1858, and Grape Creek ISD serves the area today.',
     'facts' => ['3,594 people in 2020', 'Grape Creek ISD', 'About 1,988 ft']],
    ['name' => 'Water Valley', 'zip' => '76958', 'lat' => 31.6678, 'lng' => -100.7169, 'tag' => 'Farthest northwest', 'icon' => 'map-pin', 'tint' => 1,
     'body' => 'About 22 miles northwest of San Angelo on US 87. Settlers followed an irrigation ditch dug from the North Concho, and the community took the name Water Valley in 1889. Water Valley ISD reaches into Coke County.',
     'facts' => ['About 22 miles northwest', 'Water Valley ISD', 'ZIP 76958']],
];

// Schematic map: equirectangular plot of the published coordinates (longitude scaled by cos(latitude) so shapes are not stretched).
$arLats = array_column($arPlaces, 'lat');
$arLngs = array_column($arPlaces, 'lng');
$arMidLat = (min($arLats) + max($arLats)) / 2;
$arKx = cos(deg2rad($arMidLat));
$arSpanX = (max($arLngs) - min($arLngs)) * $arKx;
$arSpanY = max($arLats) - min($arLats);
$arW = 460; $arH = 520; $arPad = 56;
$arScale = min(($arW - 2 * $arPad) / $arSpanX, ($arH - 2 * $arPad) / $arSpanY);
$arOffX = ($arW - $arSpanX * $arScale) / 2;
$arOffY = ($arH - $arSpanY * $arScale) / 2;
foreach ($arPlaces as $i => $pl) {
    $arPlaces[$i]['x'] = round($arOffX + ($pl['lng'] - min($arLngs)) * $arKx * $arScale, 1);
    $arPlaces[$i]['y'] = round($arOffY + (max($arLats) - $pl['lat']) * $arScale, 1);
}
$arHome = $arPlaces[0];

// Structured data: Service across the whole area + a WebPage whose main entity is the list of places
$arListItems = [];
foreach ($arPlaces as $i => $pl) {
    $arListItems[] = ['@type' => 'ListItem', 'position' => $i + 1, 'item' => [
        '@type' => 'City', 'name' => $pl['name'],
        'geo' => ['@type' => 'GeoCoordinates', 'latitude' => $pl['lat'], 'longitude' => $pl['lng']],
        'containedInPlace' => ['@type' => 'AdministrativeArea', 'name' => 'Tom Green County, Texas'],
    ]];
}
$schemaMarkup = [
    [
        "@context" => 'https://schema.org', '@type' => 'Service', '@id' => $canonicalUrl . '#service',
        'name' => 'Plumbing services in San Angelo, TX and surrounding towns', 'serviceType' => 'Plumbing',
        'url' => $canonicalUrl, 'provider' => ['@type' => 'Plumber', '@id' => orgId()],
        'areaServed' => serviceAreaEntities(),
    ],
    [
        "@context" => 'https://schema.org', '@type' => 'CollectionPage', '@id' => $canonicalUrl . '#webpage',
        'url' => $canonicalUrl, 'name' => $pageTitle, 'description' => $pageDescription,
        'about' => ['@id' => $canonicalUrl . '#service'],
        'mainEntity' => ['@type' => 'ItemList', 'name' => "Towns served by Rock'n G Plumbing", 'itemListElement' => $arListItems],
        'isPartOf' => ['@type' => 'WebSite', 'name' => $siteName, 'url' => rtrim($siteUrl, '/') . '/'],
    ],
];

ob_start();
?>
<style>
/* =====================================================================
   Areas We Serve — page-only CSS (ar- prefix)
   Tokens from framework.css; no raw colors, shadows or spacing.
   Techniques: layered hero (gradient + noise), plotted schematic map in the hero, asymmetric card grid
   with two feature tiles, slant / wave / torn dividers, dark grain band, tinted cards.
   ===================================================================== */

/* ---------- Hero ---------- */
.ar-hero {
  padding-block-end: var(--space-16);
  background:
    radial-gradient(ellipse 60% 80% at 100% 0%, color-mix(in srgb, var(--color-accent) 20%, transparent), transparent 62%),
    linear-gradient(160deg, var(--color-primary-deep), var(--color-dark));
}
.ar-hero::after {
  content: "";
  position: absolute;
  inset: 0;
  z-index: 0;
  pointer-events: none;
  opacity: .16;
  mix-blend-mode: overlay;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='160' height='160'%3E%3Cfilter id='g'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.8' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23g)'/%3E%3C/svg%3E");
}
.ar-hero .container {
  position: relative;
  z-index: 1;
}
.ar-hero .breadcrumb {
  color: color-mix(in srgb, var(--color-surface) 72%, transparent);
  margin-block-end: var(--space-2);
}
.ar-hero .breadcrumb li {
  display: inline-flex;
  align-items: center;
}
.ar-hero .breadcrumb a:hover {
  color: var(--color-accent-bright);
}
.ar-hero .breadcrumb [aria-current="page"] {
  color: var(--color-surface);
}
.ar-hero .hero-title {
  max-width: 17ch;
  text-wrap: balance;
}
.ar-hero .hero-answer {
  max-width: 58ch;
}
.ar-hero .link-call {
  color: color-mix(in srgb, var(--color-surface) 88%, transparent);
}
.ar-hero .link-call svg {
  color: var(--color-accent-bright);
}
.ar-hero .hero-chips li {
  background: color-mix(in srgb, var(--color-surface) 8%, transparent);
  border-color: color-mix(in srgb, var(--color-surface) 22%, transparent);
  color: var(--color-surface);
}
.ar-hero .hero-chips svg {
  color: var(--color-accent-bright);
}

/* ---------- Schematic map ---------- */
.ar-map {
  margin: 0;
  padding: var(--space-4);
  border-radius: var(--radius-lg);
  border: 1px solid color-mix(in srgb, var(--color-surface) 20%, transparent);
  background: color-mix(in srgb, var(--color-surface) 7%, transparent);
  backdrop-filter: blur(8px);
  box-shadow: var(--shadow-lg);
}
.ar-map svg {
  width: 100%;
  height: auto;
  max-height: 560px;
}
.ar-map__grid line {
  stroke: color-mix(in srgb, var(--color-surface) 12%, transparent);
  stroke-width: 1;
}
.ar-map__link {
  stroke: color-mix(in srgb, var(--color-accent-bright) 70%, transparent);
  stroke-width: 1.5;
  stroke-dasharray: 5 6;
  animation: ar-march 1.6s linear infinite;
}
.ar-map__dot {
  fill: var(--color-accent);
  stroke: var(--color-surface);
  stroke-width: 2;
}
.ar-map__dot--home {
  fill: var(--color-surface);
  stroke: var(--color-accent);
  stroke-width: 4;
}
.ar-map__pulse {
  fill: none;
  stroke: var(--color-accent-bright);
  stroke-width: 2;
  transform-box: fill-box;
  transform-origin: center;
  animation: ar-pulse 2.6s var(--ease) infinite;
}
.ar-map text {
  font-family: var(--font-body);
  font-size: 14px;
  font-weight: 600;
  fill: var(--color-surface);
  paint-order: stroke;
  stroke: var(--color-primary-deep);
  stroke-width: 4px;
  stroke-linejoin: round;
}
.ar-map text.is-home {
  font-family: var(--font-heading);
  font-size: 16px;
}
.ar-map figcaption {
  margin-block-start: var(--space-3);
  font-size: var(--fs-small);
  color: color-mix(in srgb, var(--color-surface) 75%, transparent);
}
@keyframes ar-march {
  to { stroke-dashoffset: -22; }
}
@keyframes ar-pulse {
  from { transform: scale(.6); opacity: .9; }
  to   { transform: scale(2.2); opacity: 0; }
}

/* ---------- Town cards ---------- */
.ar-towns {
  background:
    radial-gradient(ellipse 45% 35% at 0% 100%, color-mix(in srgb, var(--color-accent) 8%, transparent), transparent 70%),
    var(--color-paper);
}
.ar-grid {
  display: grid;
  grid-template-columns: repeat(6, minmax(0, 1fr));
  gap: var(--space-5);
}
.ar-card {
  position: relative;
  grid-column: span 2;
  display: grid;
  gap: var(--space-3);
  align-content: start;
  padding: var(--space-6);
  border-radius: var(--radius-lg);
  border: 1px solid var(--color-line);
  box-shadow: var(--shadow-sm);
  transition: transform var(--transition), box-shadow var(--transition);
}
.ar-card:hover {
  transform: translateY(calc(-1 * var(--space-1)));
  box-shadow: var(--shadow);
}
.ar-card:nth-last-child(-n+2):not(.ar-card--feature) {
  grid-column: span 3;
}
.ar-card--feature {
  grid-column: span 3;
  padding: var(--space-8);
}
.ar-card--feature h3 {
  font-size: var(--fs-h2);
}
.ar-card__top {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: var(--space-3);
}
.ar-card__icon {
  display: grid;
  place-items: center;
  width: 44px;
  height: 44px;
  border-radius: var(--radius);
  background: var(--color-surface);
  color: var(--color-primary);
  box-shadow: var(--shadow-sm);
}
.ar-card__tag {
  font-family: var(--font-accent);
  font-size: var(--fs-eyebrow);
  letter-spacing: .14em;
  text-transform: uppercase;
  color: var(--color-accent-dark);
}
.ar-card h3 {
  margin: 0;
}
.ar-card p {
  margin: 0;
  color: var(--color-ink-2);
}
.ar-card ul {
  display: flex;
  flex-wrap: wrap;
  gap: var(--space-2);
  margin: 0;
  padding: 0;
  list-style: none;
}
.ar-card li {
  padding: var(--space-1) var(--space-3);
  border-radius: var(--radius-full);
  background: color-mix(in srgb, var(--color-surface) 78%, transparent);
  border: 1px solid var(--color-line);
  font-size: var(--font-size-xs);
}
.ar-card__link {
  display: inline-flex;
  gap: var(--space-2);
  align-items: center;
  font-weight: 700;
  color: var(--color-primary);
  text-decoration: none;
}
.ar-card__link::after {
  content: "";
  position: absolute;
  inset: 0;
}
.ar-card__link:hover {
  color: var(--color-accent-dark);
}

/* ---------- What to tell the office ---------- */
.ar-tell__grid {
  display: grid;
  grid-template-columns: minmax(0, 5fr) minmax(0, 7fr);
  gap: clamp(var(--space-8), 5vw, var(--space-16));
  align-items: start;
}
.ar-tell__list {
  display: grid;
  gap: var(--space-4);
  margin: 0;
  padding: 0;
  list-style: none;
}
.ar-tell__list li {
  display: grid;
  grid-template-columns: auto 1fr;
  gap: var(--space-4);
  padding: var(--space-5);
  border-radius: var(--radius-lg);
  background: var(--color-surface);
  border: 1px solid var(--color-line);
  box-shadow: var(--shadow-sm);
}
.ar-tell__list svg {
  color: var(--color-primary);
}
.ar-tell__list b {
  display: block;
  font-family: var(--font-heading);
  margin-block-end: var(--space-1);
}
.ar-tell__list span {
  font-size: var(--fs-small);
  color: var(--color-ink-2);
}

/* ---------- Map embed band ---------- */
.ar-embed .answer-block {
  background: color-mix(in srgb, var(--color-surface) 6%, transparent);
  color: color-mix(in srgb, var(--color-surface) 88%, transparent);
}
.ar-embed__grid {
  display: grid;
  grid-template-columns: minmax(0, 7fr) minmax(0, 5fr);
  gap: clamp(var(--space-8), 4vw, var(--space-12));
  align-items: center;
}
.ar-embed .map-embed {
  border: 1px solid color-mix(in srgb, var(--color-surface) 20%, transparent);
  box-shadow: var(--shadow-lg);
}
.ar-embed__copy .actions {
  display: flex;
  flex-wrap: wrap;
  gap: var(--space-3);
  margin-block-start: var(--space-4);
}

/* ---------- Related services + CTA ---------- */
.ar-related .prose {
  margin-block-start: var(--space-2);
}
.ar-related .services-grid {
  grid-template-columns: repeat(3, minmax(0, 1fr));
}
.ar-cta .container {
  align-items: center;
}
.ar-cta h2 {
  text-wrap: balance;
  max-width: 22ch;
}
.ar-cta .link-call {
  color: color-mix(in srgb, var(--color-surface) 88%, transparent);
}
.ar-cta .link-call svg {
  color: var(--color-accent-bright);
}
.ar-cta__ring {
  inset-block-start: calc(-1 * var(--space-10));
  inset-inline-end: calc(-1 * var(--space-10));
}
.ar-updated {
  margin: var(--space-4) 0 0;
  font-size: var(--fs-small);
  opacity: .8;
}

/* ---------- Responsive ---------- */
@media (max-width: 1000px) {
  .ar-grid {
    grid-template-columns: repeat(2, minmax(0, 1fr));
  }
  .ar-card,
  .ar-card--feature {
    grid-column: span 1;
  }
  .ar-card--feature {
    grid-column: 1 / -1;
  }
  .ar-tell__grid,
  .ar-embed__grid {
    grid-template-columns: 1fr;
  }
}
@media (max-width: 1000px) {
  .ar-related .services-grid {
    grid-template-columns: repeat(2, minmax(0, 1fr));
  }
}
@media (max-width: 640px) {
  .ar-related .services-grid {
    grid-template-columns: 1fr;
  }
  .ar-grid {
    grid-template-columns: 1fr;
  }
  .ar-card--feature {
    padding: var(--space-6);
  }
  .ar-map text {
    font-size: 13px;
  }
}
@media (prefers-reduced-motion: reduce) {
  .ar-map__link,
  .ar-map__pulse {
    animation: none;
  }
  .ar-card {
    transition: none;
  }
}
</style>
<?php
$extraHead = ob_get_clean();

include $_SERVER['DOCUMENT_ROOT'] . '/includes/head.php';
include $_SERVER['DOCUMENT_ROOT'] . '/includes/header.php';
?>

<!-- ============ HERO ============ -->
<section class="hero hero--interior ar-hero" aria-labelledby="ar-h1">
  <div class="container">
    <div class="hero-grid hero-grid--visual">
      <div class="hero-copy">
        <nav class="breadcrumb" aria-label="Breadcrumb">
          <ol>
            <li><a href="/">Home</a></li>
            <li class="breadcrumb-sep" aria-hidden="true">/</li>
            <li><span aria-current="page">Service Areas</span></li>
          </ol>
        </nav>
        <span class="eyebrow">Areas we serve</span>
        <h1 class="hero-title" id="ar-h1">Plumber in San Angelo, TX &amp; Surrounding Communities</h1>
        <p class="hero-answer">Rock'n G Plumbing is a San Angelo, Texas plumbing company led by Eli Gomez, Responsible Master Plumber on Texas license M-44822. It serves San Angelo and Christoval, plus the Tom Green County towns of Wall, Veribest, Carlsbad, Grape Creek and Water Valley, for residential, commercial and industrial customers.</p>
        <div class="hero-actions">
          <a class="btn btn-accent btn-lg" href="/contact/" data-open-estimate>Get a free estimate</a>
          <a class="link-call" href="<?php echo h($telHref); ?>"><?php echo icon('phone', 18); ?>or call <?php echo h($phoneFmt); ?></a>
        </div>
        <ul class="hero-chips">
          <li><?php echo icon('badge-check', 16); ?>Texas Master Plumber <?php echo h($license['number']); ?></li>
          <li><?php echo icon('clipboard-list', 16); ?>Free estimates</li>
          <li><?php echo icon('shield-check', 16); ?>Military discounts</li>
        </ul>
      </div>

      <figure class="ar-map" aria-labelledby="ar-map-caption">
        <svg viewBox="0 0 <?php echo $arW; ?> <?php echo $arH; ?>" role="img" aria-label="Schematic map: San Angelo with Christoval to the south, Wall and Veribest to the east, and Carlsbad, Grape Creek and Water Valley to the northwest">
          <g class="ar-map__grid" aria-hidden="true">
            <?php for ($gx = $arPad; $gx <= $arW - $arPad; $gx += 68): ?><line x1="<?php echo $gx; ?>" y1="<?php echo $arPad / 2; ?>" x2="<?php echo $gx; ?>" y2="<?php echo $arH - $arPad / 2; ?>"/><?php endfor; ?>
            <?php for ($gy = $arPad; $gy <= $arH - $arPad; $gy += 68): ?><line x1="<?php echo $arPad / 2; ?>" y1="<?php echo $gy; ?>" x2="<?php echo $arW - $arPad / 2; ?>" y2="<?php echo $gy; ?>"/><?php endfor; ?>
          </g>
          <?php foreach (array_slice($arPlaces, 1) as $pl): ?>
          <line class="ar-map__link" x1="<?php echo $arHome['x']; ?>" y1="<?php echo $arHome['y']; ?>" x2="<?php echo $pl['x']; ?>" y2="<?php echo $pl['y']; ?>"/>
          <?php endforeach; ?>
          <?php foreach (array_slice($arPlaces, 1) as $pl):
            $lblRight = $pl['x'] < $arW - 150; ?>
          <circle class="ar-map__dot" cx="<?php echo $pl['x']; ?>" cy="<?php echo $pl['y']; ?>" r="7"/>
          <text x="<?php echo $lblRight ? $pl['x'] + 14 : $pl['x'] - 14; ?>" y="<?php echo $pl['y'] + 5; ?>" text-anchor="<?php echo $lblRight ? 'start' : 'end'; ?>"><?php echo h($pl['name']); ?></text>
          <?php endforeach; ?>
          <circle class="ar-map__pulse" cx="<?php echo $arHome['x']; ?>" cy="<?php echo $arHome['y']; ?>" r="12"/>
          <circle class="ar-map__dot ar-map__dot--home" cx="<?php echo $arHome['x']; ?>" cy="<?php echo $arHome['y']; ?>" r="11"/>
          <text class="is-home" x="<?php echo $arHome['x'] + 20; ?>" y="<?php echo $arHome['y'] - 14; ?>">San Angelo</text>
        </svg>
        <figcaption id="ar-map-caption">Schematic only: each town is plotted from its published coordinates, with north at the top. The dashed lines show direction from San Angelo, not roads or driving distance.</figcaption>
      </figure>
    </div>
  </div>
</section>

<article class="ar-article" aria-labelledby="ar-h1">

  <!-- ============ TOWN CARDS ============ -->
  <section class="section ar-towns" aria-labelledby="ar-towns-title">
    <div class="container-wide">
      <div class="section-head reveal-up">
        <span class="eyebrow">Seven places, one crew</span>
        <h2 id="ar-towns-title">Which towns does Rock'n G Plumbing serve around San Angelo?</h2>
        <p class="answer-block">Rock'n G Plumbing serves San Angelo, Christoval, Wall, Veribest, Carlsbad, Grape Creek and Water Valley in Tom Green County, Texas. San Angelo is the home base and Christoval has its own page; the other five towns are described below. A job in any of them starts with a call to <?php echo h($phoneFmt); ?> and a free estimate.</p>
      </div>

      <div class="ar-grid">
        <?php foreach ($arPlaces as $i => $pl): ?>
        <div class="ar-card card-tint-<?php echo (int) $pl['tint']; ?><?php echo !empty($pl['feature']) ? ' ar-card--feature' : ''; ?> reveal-<?php echo ['up', 'left', 'right', 'up', 'scale', 'left', 'right'][$i % 7]; ?> reveal-delay-<?php echo ($i % 3) + 1; ?>">
          <div class="ar-card__top">
            <span class="ar-card__icon"><?php echo icon($pl['icon'], 22); ?></span>
            <span class="ar-card__tag"><?php echo h($pl['tag']); ?></span>
          </div>
          <h3><?php echo h($pl['name']); ?>, TX</h3>
          <p><?php echo h($pl['body']); ?></p>
          <ul aria-label="<?php echo h($pl['name']); ?> facts">
            <?php foreach ($pl['facts'] as $fact): ?><li><?php echo h($fact); ?></li><?php endforeach; ?>
          </ul>
          <?php if (!empty($pl['url']) && pageExists($pl['url'])): ?>
          <a class="ar-card__link" href="<?php echo h($pl['url']); ?>"><?php echo h($pl['cta']); ?> <?php echo icon('arrow-right', 16); ?></a>
          <?php endif; ?>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- ============ WHAT TO TELL THE OFFICE ============ -->
  <section class="section slant-top ar-tell" aria-labelledby="ar-tell-title">
    <div class="container">
      <div class="ar-tell__grid">
        <div class="reveal-left">
          <span class="eyebrow">Before you call</span>
          <h2 id="ar-tell-title">What should you tell the office when you call from outside San Angelo?</h2>
          <p class="answer-block">Give the office your town, what the plumbing is doing, and how the property is set up: where the water comes from and where the waste line goes. Those answers let Rock'n G Plumbing write a free estimate for your address instead of an average, and they are the ones that change from town to town.</p>
          <p>Office hours are <?php echo h($weekday[0]); ?>, <?php echo h($weekday[1]); ?>. Weekends are closed, so the form on any page is the way to reach the office outside those hours.</p>
        </div>
        <ul class="ar-tell__list">
          <li class="reveal-right"><?php echo icon('map-pin', 24); ?><div><b>Your town and cross-street or road</b><span>Wall, Veribest and Water Valley are small, spread-out communities, so the road name tells the office where you are.</span></div></li>
          <li class="reveal-right reveal-delay-1"><?php echo icon('droplets', 24); ?><div><b>Where your water comes from</b><span>A city or utility line, a shared rural system or a private well. It decides what a softener or filter has to handle.</span></div></li>
          <li class="reveal-right reveal-delay-2"><?php echo icon('waves', 24); ?><div><b>Sewer main or septic</b><span>It changes how a slow or backed-up drain is diagnosed, and where a sewer camera can go.</span></div></li>
          <li class="reveal-right reveal-delay-3"><?php echo icon('clock', 24); ?><div><b>What it is doing, and since when</b><span>Cold water only, a damp floor, a gas appliance that will not light. A plain description is enough.</span></div></li>
        </ul>
      </div>
    </div>
  </section>

  <!-- ============ GBP MAP ============ -->
  <section class="section on-dark edge-wave-top ar-embed" aria-labelledby="ar-embed-title">
    <span class="grain-layer" aria-hidden="true"></span>
    <div class="container">
      <div class="ar-embed__grid">
        <div class="map-embed reveal-left">
          <iframe src="<?php echo h($gbp['embed_src']); ?>" title="Google Maps listing for Rock'n G Plumbing, a San Angelo, Texas plumber" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
        </div>
        <div class="ar-embed__copy reveal-right">
          <span class="eyebrow">On Google Maps</span>
          <h2 id="ar-embed-title">Where is Rock'n G Plumbing on Google Maps?</h2>
          <p class="answer-block">Rock'n G Plumbing is listed on Google Maps as a San Angelo, TX service-area business. There is no storefront to visit; the crew comes to your property in any of the towns above. Rated <?php echo h(number_format((float) $gbp['rating'], 1)); ?> out of 5 from <?php echo (int) $gbp['reviews_count']; ?> Google reviews.</p>
          <div class="actions">
            <a class="btn btn-accent" href="<?php echo h($gbp['profile_url']); ?>" target="_blank" rel="noopener">Open the Google listing</a>
            <a class="btn btn-outline-white" href="<?php echo h($gbp['review_url']); ?>" target="_blank" rel="noopener">Leave a review</a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ============ SERVICES (required services-grid) ============ -->
  <section class="section ar-related" aria-label="Plumbing services in every town">
    <div class="container">
      <div class="section-title reveal-up">
        <span class="eyebrow-label">What We Do</span>
        <h2>Which <span class="text-accent">plumbing services</span> can you book in any of these towns?</h2>
        <p class="hero-answer">Rock'n G Plumbing offers the same eight services in every town on this page: water heaters, water softeners and filtration, drain cleaning, sewer line repair, leak detection, gas lines, sprinkler repair, and new-construction and remodel plumbing. The three below are good starting points for rural and small-town properties.</p>
        <span class="section-subtitle">Water treatment · Drains · Leaks</span>
        <p class="prose">See the full list of <a href="/services/">plumbing services Rock'n G offers</a> or read the <a href="/service-area/christoval-tx/">Christoval plumbing page</a>.</p>
      </div>
      <div class="services-grid">
        <?php echo renderServiceCards(['water-filtration-softeners', 'drain-cleaning', 'leak-detection-repair'], relatedServiceSizes(640)); ?>
      </div>
    </div>
  </section>

  <!-- ============ FINAL CTA ============ -->
  <section class="closing-cta texture-grain edge-torn-top ar-cta" aria-labelledby="ar-cta-title">
    <span class="grain-layer" aria-hidden="true"></span>
    <span class="floating-ring ar-cta__ring" aria-hidden="true"></span>
    <div class="container">
      <div class="reveal-left">
        <span class="eyebrow">No charge to look</span>
        <h2 id="ar-cta-title">Ready for a free plumbing estimate near San Angelo?</h2>
        <p>Call Rock'n G Plumbing at <?php echo h($phoneFmt); ?>, <?php echo h($weekday[0]); ?> <?php echo h($weekday[1]); ?>, or send the form and the office will follow up during business hours. Tell them which town you are in and what the plumbing is doing.</p>
        <p class="ar-updated">Last updated: <?php echo date('F Y'); ?></p>
      </div>
      <div class="actions reveal-right">
        <a class="btn btn-accent btn-lg" href="/contact/" data-open-estimate>Get a Free Estimate</a>
        <a class="link-call" href="<?php echo h($telHref); ?>"><?php echo icon('phone', 18); ?>Call <?php echo h($phoneFmt); ?></a>
      </div>
    </div>
  </section>

</article>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>
