<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/functions.php';
?>
<?php
/**
 * service-area/christoval-tx/index.php — Plumber in Christoval, TX (Phase 6).
 * SEO brief (Oct 18 2026): this is the ONE town page on the site (title/H1 given by the brief); the other towns live on
 * /areas-we-serve/. No emergency/24-hour wording, no frequency-of-past-work claims, no FAQ schema, no aggregateRating.
 *
 * ---- RESEARCH (Oct 18 2026) — town facts only; company facts come from build-plan.json / config.php -----------------
 * https://en.wikipedia.org/wiki/Christoval,_Texas   — CDP in Tom Green Co., ~20 mi south of San Angelo; Loop 110, US 277,
 *     RM 2084; 1.3 sq mi; elevation 2,037 ft (sources differ by a few feet — shown as "about 2,000 ft"); ZIP 76935;
 *     2020 census 482; Pugh River Park (~3/4 mile of riverfront); historic low-water crossing on Loop 110;
 *     Baptist encampment destroyed in the Sept 17, 1936 flood; Old Toenail Trail Days each Memorial Day weekend.
 * https://www.tshaonline.org/handbook/entries/christoval-tx — Handbook of Texas: Loop 110 / US 277 / Farm Road 2084 and the
 *     South Concho; post office 1889; mohair industry; 1987 US 277 rerouting; ~20 miles south of San Angelo.
 * https://en.wikipedia.org/wiki/South_Concho_River — rises at Anson Springs ~4 mi south of Christoval, flows south to north
 *     (one of the few Texas rivers that does), ~13 mi north to Twin Buttes Reservoir in southwest San Angelo; pecan-covered
 *     banks; photo caption "2007 flood of South Concho River".
 * NOT verified, so left out: Christoval's water utility/hardness, its USDA zone, soil, any municipal plumbing rules.
 * ---------------------------------------------------------------------------------------------------------------------
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/service-cards.php';

$pageTitle       = "Plumber in Christoval, TX | Rock'n G Plumbing";
$pageDescription = "Plumber in Christoval, TX: Rock'n G Plumbing • Texas Master Plumber M-44822, 3 generations, military discounts • Call (325) 650-7000 for a free estimate.";
$canonicalUrl    = $siteUrl . '/service-area/christoval-tx/';
$currentPage     = 'service-area/christoval-tx';
$breadcrumbs     = [['name' => 'Service Areas', 'url' => '/areas-we-serve/'], ['name' => 'Christoval, TX', 'url' => '/service-area/christoval-tx/']];

$heroImage   = 'rockn-gas-line-generator';
$heroAlt     = 'Gas line run along the brick wall of a home to a standby generator';
$heroPreload = ['srcset' => imageVariantSrcset($heroImage, 'avif'), 'sizes' => '100vw'];
$ogImage     = '/assets/images/' . $heroImage . '.jpg';

$telHref  = 'tel:' . $phoneE164;
$phoneFmt = formatPhone($phone);
$weekday  = hoursGroups($hours)[0];      // ['Mon–Fri', '8:00 AM – 6:00 PM']
$licenseExpires = date('F j, Y', strtotime($license['expires']));

// At-a-glance town facts (all from the sources above)
$cvFacts = [
    ['num' => '≈20 mi', 'label' => 'south of San Angelo, off US 277'],
    ['num' => '≈2,000 ft', 'label' => 'elevation above sea level'],
    ['num' => '482', 'label' => 'residents, 2020 census'],
    ['num' => '76935', 'label' => 'Christoval ZIP code'],
];

// Symptom → service rows. Plumbing symptoms are general knowledge; nothing says Rock'n G has done a given job in town.
$cvProblems = [
    ['slug' => 'water-heaters', 'icon' => 'flame', 'sign' => 'Hot water runs out, rumbles or leaks at the base',
     'body' => 'Repair, replacement or a tankless switch, priced at the estimate.'],
    ['slug' => 'water-filtration-softeners', 'icon' => 'droplets', 'sign' => 'White crust on faucets, spotty glass, stiff laundry',
     'body' => 'Softeners, reverse osmosis and whole-house filters. Tell the office whether the house runs on a utility, a shared system or a private well.'],
    ['slug' => 'drain-cleaning', 'icon' => 'waves', 'sign' => 'One drain runs slow or backs up',
     'body' => 'Kitchen, bathroom and laundry drains cleared at the clog instead of treated with cleaner.'],
    ['slug' => 'sewer-line-repair', 'icon' => 'search', 'sign' => 'Several drains gurgle, or a wet patch shows in the yard',
     'body' => 'Camera inspection of the line, then cleaning or repair. The setup (sewer main or septic) is the first question.'],
    ['slug' => 'leak-detection-repair', 'icon' => 'layers', 'sign' => 'A water bill that climbed, or a warm spot on the floor',
     'body' => 'Hidden and under-slab leaks located, then the water line repaired or replaced.'],
    ['slug' => 'gas-line-services', 'icon' => 'wrench', 'sign' => 'A new range, dryer, heater or standby generator',
     'body' => 'New gas lines and repairs to existing piping. If you smell gas, leave first and call 911 or the gas utility.'],
    ['slug' => 'irrigation-sprinkler-repair', 'icon' => 'sprout', 'sign' => 'A sprinkler zone that leaks or will not run',
     'body' => 'Broken heads, leaking lines and irrigation systems that stopped working.'],
    ['slug' => 'new-construction-remodel-plumbing', 'icon' => 'hammer', 'sign' => 'Building, adding on or remodeling',
     'body' => 'Plumbing for new homes, kitchen and bath remodels and commercial build-outs.'],
];

// Page schema: a Service node that points back at the site-wide Plumber node (head.php prints it on every page),
// plus a WebPage node about the place. Not a second LocalBusiness block (duplicate @id would conflict).
$cvPlace = [
    '@type' => 'City', 'name' => 'Christoval',
    'sameAs' => 'https://en.wikipedia.org/wiki/Christoval,_Texas',
    'geo' => ['@type' => 'GeoCoordinates', 'latitude' => 31.1975, 'longitude' => -100.4933],
    'containedInPlace' => ['@type' => 'AdministrativeArea', 'name' => 'Tom Green County, Texas'],
];
$cvService = [
    "@context" => 'https://schema.org', '@type' => 'Service',
    '@id' => $canonicalUrl . '#service',
    'name' => 'Plumbing services in Christoval, TX',
    'serviceType' => 'Plumbing',
    'description' => "Water heater, water softener and filtration, drain cleaning, sewer line, leak detection, gas line, sprinkler repair and new-construction plumbing for homes and businesses in Christoval, TX, under Texas Master Plumber License M-44822.",
    'url' => $canonicalUrl,
    'provider' => ['@type' => 'Plumber', '@id' => orgId()],
    'areaServed' => $cvPlace,
];
$cvPage = [
    "@context" => 'https://schema.org', '@type' => 'WebPage',
    '@id' => $canonicalUrl . '#webpage',
    'url' => $canonicalUrl,
    'name' => $pageTitle,
    'description' => $pageDescription,
    'about' => ['@id' => $canonicalUrl . '#service'],
    'primaryImageOfPage' => ['@type' => 'ImageObject', 'url' => absUrl('/assets/images/' . $heroImage . '.jpg')],
    'isPartOf' => ['@type' => 'WebSite', 'name' => $siteName, 'url' => rtrim($siteUrl, '/') . '/'],
];
$schemaMarkup = [$cvService, $cvPage];

ob_start();
?>
<style>
/* =====================================================================
   Plumber in Christoval, TX — page-only CSS (cv- prefix)
   Tokens from framework.css; no raw colors, shadows or spacing.
   Techniques: layered photo hero (gradient + noise), slant / wave / torn dividers,
   flowing-river signature timeline, asymmetric bento, tinted cards, dark grain band.
   ===================================================================== */

/* ---------- Hero: layered photo ---------- */
.cv-hero {
  padding-block-end: var(--space-16);
}
.cv-hero .hero-bg {
  overflow: hidden;
}
.cv-hero .hero-bg picture,
.cv-hero .hero-bg img {
  width: 100%;
  height: 100%;
}
.cv-hero .hero-bg img {
  object-fit: cover;
  object-position: 50% 55%;
  animation: cv-drift 26s var(--ease) both;
}
.cv-hero::before {
  content: "";
  position: absolute;
  inset: 0;
  z-index: -1;
  pointer-events: none;
  background:
    radial-gradient(ellipse 55% 70% at 0% 100%, color-mix(in srgb, var(--color-accent) 24%, transparent), transparent 65%),
    linear-gradient(180deg, transparent 45%, color-mix(in srgb, var(--color-primary-deep) 82%, transparent) 100%);
}
.cv-hero::after {
  content: "";
  position: absolute;
  inset: 0;
  z-index: -1;
  pointer-events: none;
  opacity: .2;
  mix-blend-mode: overlay;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='160' height='160'%3E%3Cfilter id='g'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.8' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23g)'/%3E%3C/svg%3E");
}
.cv-hero .breadcrumb {
  color: color-mix(in srgb, var(--color-surface) 72%, transparent);
  margin-block-end: var(--space-2);
}
.cv-hero .breadcrumb li {
  display: inline-flex;
  align-items: center;
}
.cv-hero .breadcrumb a:hover {
  color: var(--color-accent-bright);
}
.cv-hero .breadcrumb [aria-current="page"] {
  color: var(--color-surface);
}
.cv-hero .hero-title {
  max-width: 16ch;
  text-wrap: balance;
}
.cv-hero .hero-answer {
  max-width: 58ch;
}
@keyframes cv-drift {
  from { transform: scale(1.06); }
  to   { transform: scale(1); }
}

/* ---------- Town facts band ---------- */
.cv-facts {
  position: relative;
  z-index: 1;
  margin-block-start: calc(-1 * var(--space-8));
}
.cv-facts__label {
  display: block;
  margin-block-end: var(--space-3);
  font-family: var(--font-accent);
  font-size: var(--fs-eyebrow);
  letter-spacing: .14em;
  text-transform: uppercase;
  color: var(--color-muted);
}
.cv-facts__row {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  background: var(--color-surface);
  border: 1px solid var(--color-line);
  border-radius: var(--radius-lg);
  box-shadow: var(--shadow-lg);
  overflow: hidden;
}
.cv-fact {
  display: grid;
  gap: var(--space-1);
  padding: var(--space-5) var(--space-6);
  border-inline-start: 1px solid var(--color-line);
  transition: background var(--transition);
}
.cv-fact:first-child {
  border-inline-start: 0;
}
.cv-fact:hover {
  background: var(--color-card-tint-3);
}
.cv-fact b {
  font-family: var(--font-accent);
  font-size: 1.9rem;
  line-height: 1;
  letter-spacing: .01em;
  color: var(--color-primary);
  font-variant-numeric: tabular-nums;
}
.cv-fact span {
  font-size: var(--fs-small);
  color: var(--color-muted);
}

/* ---------- Local conditions: river signature + bento ---------- */
.cv-local {
  background:
    radial-gradient(ellipse 50% 40% at 100% 0%, color-mix(in srgb, var(--color-accent) 9%, transparent), transparent 70%),
    var(--color-paper);
}
.cv-local__grid {
  display: grid;
  grid-template-columns: minmax(0, 5fr) minmax(0, 7fr);
  gap: clamp(var(--space-8), 5vw, var(--space-16));
  align-items: start;
}
.cv-river {
  position: relative;
  display: flex;
  flex-direction: column-reverse;     /* map orientation: north (Twin Buttes) on top, Anson Springs at the bottom */
  gap: var(--space-10);
  margin: 0;
  padding: var(--space-4) 0 var(--space-4) var(--space-12);
  list-style: none;
}
.cv-river::before {
  content: "";
  position: absolute;
  inset-block: 0;
  inset-inline-start: var(--space-3);
  width: 10px;
  border-radius: var(--radius-full);
  background:
    repeating-linear-gradient(0deg,
      color-mix(in srgb, var(--color-accent) 85%, transparent) 0 18px,
      color-mix(in srgb, var(--color-accent-bright) 55%, transparent) 18px 34px,
      color-mix(in srgb, var(--color-accent) 60%, transparent) 34px 52px);
  background-size: 100% 52px;
  animation: cv-flow 3.2s linear infinite;
}
.cv-river li {
  position: relative;
  margin: 0;
  padding: var(--space-4) var(--space-5);
  border-radius: var(--radius-lg);
  background: var(--color-surface);
  border: 1px solid var(--color-line);
  box-shadow: var(--shadow);
}
.cv-river li::before {
  content: "";
  position: absolute;
  inset-inline-start: calc(-1 * var(--space-10) + 2px);
  inset-block-start: var(--space-5);
  width: 24px;
  height: 24px;
  border-radius: 50%;
  background: var(--color-surface);
  border: 4px solid var(--color-primary);
  box-shadow: 0 0 0 4px var(--color-paper);
}
.cv-river li.is-town {
  background: var(--color-primary);
  border-color: var(--color-primary);
}
.cv-river li.is-town::before {
  background: var(--color-accent);
  border-color: var(--color-surface);
}
.cv-river li.is-town h3,
.cv-river li.is-town p {
  color: var(--color-surface);
}
.cv-river h3 {
  font-size: var(--fs-h3);
  margin-block-end: var(--space-1);
}
.cv-river p {
  margin: 0;
  font-size: var(--fs-small);
  color: var(--color-ink-2);
}
.cv-river small {
  display: block;
  margin-block-end: var(--space-1);
  font-family: var(--font-accent);
  font-size: var(--fs-eyebrow);
  letter-spacing: .14em;
  text-transform: uppercase;
  color: var(--color-accent-dark);
}
.cv-river li.is-town small {
  color: var(--color-accent-bright);
}
.cv-river__note {
  margin-block-start: var(--space-5);
  font-size: var(--fs-small);
  color: var(--color-muted);
}
@keyframes cv-flow {
  from { background-position: 0 0; }
  to   { background-position: 0 -52px; }     /* background moves up the bar: the river runs north */
}
.cv-bento {
  display: grid;
  grid-template-columns: repeat(2, minmax(0, 1fr));
  gap: var(--space-5);
}
.cv-card {
  display: grid;
  gap: var(--space-2);
  align-content: start;
  padding: var(--space-6);
  border-radius: var(--radius-lg);
  border: 1px solid var(--color-line);
  box-shadow: var(--shadow-sm);
  transition: transform var(--transition), box-shadow var(--transition);
}
.cv-card:hover {
  transform: translateY(calc(-1 * var(--space-1)));
  box-shadow: var(--shadow);
}
.cv-card--wide {
  grid-column: 1 / -1;
}
.cv-card svg {
  color: var(--color-primary);
}
.cv-card h3 {
  margin: 0;
}
.cv-card p {
  margin: 0;
  color: var(--color-ink-2);
}
.cv-card ul {
  margin: var(--space-2) 0 0;
  padding: 0;
  list-style: none;
  display: grid;
  gap: var(--space-2);
}
.cv-card li {
  display: flex;
  gap: var(--space-2);
  align-items: flex-start;
  font-size: var(--fs-small);
}
.cv-card li svg {
  flex: 0 0 auto;
  margin-block-start: var(--space-1);
  color: var(--color-accent-dark);
}
.cv-card--wide p + p {
  margin-block-start: var(--space-2);
}
.cv-safety {
  grid-column: 1 / -1;
  display: flex;
  gap: var(--space-4);
  align-items: flex-start;
  padding: var(--space-5) var(--space-6);
  border-radius: var(--radius-lg);
  border: 1px dashed var(--color-warning);
  background: color-mix(in srgb, var(--color-warning) 9%, var(--color-surface));
}
.cv-safety svg {
  flex: 0 0 auto;
  color: var(--color-warning);
}
.cv-safety p {
  margin: 0;
  font-size: var(--fs-small);
}

/* ---------- Problem → service rows ---------- */
.cv-problems {
  background: var(--color-paper-2);
}
.cv-problems__grid {
  display: grid;
  grid-template-columns: minmax(0, 7fr) minmax(0, 4fr);
  gap: clamp(var(--space-8), 4vw, var(--space-12));
  align-items: start;
}
.cv-rows {
  display: grid;
  gap: var(--space-3);
  margin: 0;
  padding: 0;
  list-style: none;
}
.cv-row {
  margin: 0;
}
.cv-row a {
  position: relative;
  display: grid;
  grid-template-columns: auto minmax(0, 1fr) auto;
  gap: var(--space-4);
  align-items: center;
  padding: var(--space-4) var(--space-5);
  border-radius: var(--radius-lg);
  background: var(--color-surface);
  border: 1px solid var(--color-line);
  text-decoration: none;
  transition: transform var(--transition), border-color var(--transition), box-shadow var(--transition);
}
.cv-row a::before {
  content: "";
  position: absolute;
  inset-block: var(--space-3);
  inset-inline-start: 0;
  width: 4px;
  border-radius: var(--radius-full);
  background: var(--color-accent);
  transform: scaleY(0);
  transition: transform var(--transition);
}
.cv-row a:hover,
.cv-row a:focus-visible {
  transform: translateX(var(--space-1));
  border-color: var(--color-primary);
  box-shadow: var(--shadow);
}
.cv-row a:hover::before,
.cv-row a:focus-visible::before {
  transform: scaleY(1);
}
.cv-row__icon {
  display: grid;
  place-items: center;
  width: 44px;
  height: 44px;
  border-radius: var(--radius);
  background: var(--color-card-tint-3);
  color: var(--color-primary);
}
.cv-row b {
  display: block;
  font-family: var(--font-heading);
  font-size: 1.02rem;
  line-height: 1.25;
  color: var(--color-ink);
}
.cv-row span.cv-row__body {
  display: block;
  margin-block-start: var(--space-1);
  font-size: var(--fs-small);
  color: var(--color-ink-2);
}
.cv-row__go {
  font-family: var(--font-accent);
  font-size: var(--fs-eyebrow);
  letter-spacing: .12em;
  text-transform: uppercase;
  color: var(--color-accent-dark);
  white-space: nowrap;
}
.cv-figure {
  position: sticky;
  inset-block-start: calc(var(--nav-height) + var(--space-4));
  margin: 0;
}
.cv-figure .img-reveal {
  border-radius: var(--radius-lg);
  overflow: hidden;
  box-shadow: var(--shadow-lg);
}
.cv-figure figcaption {
  margin-block-start: var(--space-3);
  font-size: var(--fs-small);
  color: var(--color-muted);
}

/* ---------- Who you are hiring: dark grain band ---------- */
.cv-who .section-head {
  max-width: 68ch;
}
.cv-who .answer-block {
  background: color-mix(in srgb, var(--color-surface) 6%, transparent);
  color: color-mix(in srgb, var(--color-surface) 88%, transparent);
}
.cv-who__cards {
  display: grid;
  grid-template-columns: minmax(0, 7fr) minmax(0, 5fr);
  gap: var(--space-6);
}
.cv-who__card {
  padding: var(--space-6);
  border-radius: var(--radius-lg);
  border: 1px solid color-mix(in srgb, var(--color-surface) 18%, transparent);
  background: color-mix(in srgb, var(--color-surface) 7%, transparent);
  backdrop-filter: blur(6px);
}
.cv-who__card h3 {
  margin-block-end: var(--space-3);
}
.cv-who__card .actions {
  display: flex;
  flex-wrap: wrap;
  gap: var(--space-3);
  margin-block-start: var(--space-4);
}
.cv-who__card dl {
  display: grid;
  grid-template-columns: auto 1fr;
  gap: var(--space-2) var(--space-4);
  margin: 0;
}
.cv-who__card dt {
  font-family: var(--font-accent);
  font-size: var(--fs-eyebrow);
  letter-spacing: .14em;
  text-transform: uppercase;
  color: var(--color-accent-bright);
}
.cv-who__card dd {
  margin: 0;
  color: var(--color-surface);
}
.cv-who__reviews {
  margin-block-start: var(--space-8);
}
.cv-credit {
  display: inline-flex;
  gap: var(--space-3);
  align-items: center;
  margin-block-end: var(--space-4);
  padding: var(--space-2) var(--space-4);
  border-radius: var(--radius-full);
  background: color-mix(in srgb, var(--color-accent) 18%, transparent);
  color: var(--color-surface);
  font-size: var(--fs-small);
}
.cv-credit b {
  font-family: var(--font-accent);
  letter-spacing: .08em;
}

/* ---------- Scheduling steps + map ---------- */
.cv-book h2 {
  margin-block-end: var(--space-4);
}
.cv-book__grid {
  display: grid;
  grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
  gap: clamp(var(--space-8), 4vw, var(--space-12));
  align-items: start;
}
.cv-steps {
  counter-reset: cv-step;
  display: grid;
  gap: var(--space-4);
  margin: 0;
  padding: 0;
  list-style: none;
}
.cv-steps li {
  counter-increment: cv-step;
  position: relative;
  display: grid;
  gap: var(--space-1);
  padding: var(--space-4) var(--space-5) var(--space-4) var(--space-16);
  border-radius: var(--radius-lg);
  background: var(--color-card-tint-1);
}
.cv-steps li::before {
  content: counter(cv-step);
  position: absolute;
  inset-block-start: var(--space-4);
  inset-inline-start: var(--space-4);
  display: grid;
  place-items: center;
  width: 36px;
  height: 36px;
  border-radius: 50%;
  background: var(--color-primary);
  color: var(--color-surface);
  font-family: var(--font-accent);
  font-size: 1.1rem;
}
.cv-steps li:nth-child(2) {
  background: var(--color-card-tint-2);
}
.cv-steps li:nth-child(3) {
  background: var(--color-card-tint-3);
}
.cv-steps b {
  font-family: var(--font-heading);
}
.cv-steps span {
  font-size: var(--fs-small);
  color: var(--color-ink-2);
}
.cv-map {
  display: grid;
  gap: var(--space-3);
}
.cv-map .map-embed {
  box-shadow: var(--shadow-lg);
  border: 1px solid var(--color-line);
}
.cv-map p {
  margin: 0;
  font-size: var(--fs-small);
  color: var(--color-muted);
}

/* ---------- Nearby towns ---------- */
.cv-nearby {
  display: flex;
  flex-wrap: wrap;
  gap: var(--space-2);
  margin: var(--space-4) 0 0;
  padding: 0;
  list-style: none;
}
.cv-nearby li {
  padding: var(--space-1) var(--space-4);
  border-radius: var(--radius-full);
  border: 1px solid var(--color-line);
  background: var(--color-surface);
  font-size: var(--fs-small);
}

/* ---------- Related services + closing CTA ---------- */
.cv-related .prose {
  margin-block-start: var(--space-2);
}
.cv-related .services-grid {
  grid-template-columns: repeat(3, minmax(0, 1fr));
}
.cv-cta .container {
  align-items: center;
}
.cv-cta h2 {
  text-wrap: balance;
  max-width: 22ch;
}
.cv-cta .link-call {
  color: color-mix(in srgb, var(--color-surface) 88%, transparent);
}
.cv-cta .link-call svg {
  color: var(--color-accent-bright);
}
.cv-cta__ring {
  inset-block-start: calc(-1 * var(--space-10));
  inset-inline-end: calc(-1 * var(--space-10));
}
.cv-updated {
  margin: var(--space-4) 0 0;
  font-size: var(--fs-small);
  opacity: .8;
}

/* ---------- Responsive ---------- */
@media (max-width: 1000px) {
  .cv-local__grid,
  .cv-problems__grid,
  .cv-who__cards,
  .cv-book__grid {
    grid-template-columns: 1fr;
  }
  .cv-figure {
    position: static;
  }
  .cv-facts__row {
    grid-template-columns: repeat(2, 1fr);
  }
  .cv-fact:nth-child(3) {
    border-inline-start: 0;
  }
  .cv-fact:nth-child(n+3) {
    border-block-start: 1px solid var(--color-line);
  }
}
@media (max-width: 1000px) {
  .cv-related .services-grid {
    grid-template-columns: repeat(2, minmax(0, 1fr));
  }
}
@media (max-width: 640px) {
  .cv-related .services-grid {
    grid-template-columns: 1fr;
  }
  .cv-bento {
    grid-template-columns: 1fr;
  }
  .cv-row a {
    grid-template-columns: auto minmax(0, 1fr);
  }
  .cv-row__go {
    display: none;
  }
  .cv-river {
    padding-inline-start: var(--space-10);
  }
  .cv-river li::before {
    inset-inline-start: calc(-1 * var(--space-8) + 2px);
  }
}
@media (prefers-reduced-motion: reduce) {
  .cv-hero .hero-bg img,
  .cv-river::before {
    animation: none;
  }
  .cv-card,
  .cv-fact,
  .cv-row a,
  .cv-row a::before {
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
<section class="hero hero--photo hero--interior cv-hero" aria-labelledby="cv-h1">
  <div class="hero-bg"><?php echo pictureTag($heroImage, $heroAlt, '100vw', ['loading' => 'eager', 'fetchpriority' => 'high']); ?></div>
  <div class="hero-overlay"></div>
  <span class="grain" aria-hidden="true"></span>
  <div class="container">
    <div class="hero-grid hero-grid--form">
      <div class="hero-copy">
        <nav class="breadcrumb" aria-label="Breadcrumb">
          <ol>
            <li><a href="/">Home</a></li>
            <li class="breadcrumb-sep" aria-hidden="true">/</li>
            <li><a href="/areas-we-serve/">Service Areas</a></li>
            <li class="breadcrumb-sep" aria-hidden="true">/</li>
            <li><span aria-current="page">Christoval, TX</span></li>
          </ol>
        </nav>
        <span class="eyebrow">Plumber in Christoval, TX</span>
        <h1 class="hero-title" id="cv-h1">Your Christoval Plumber</h1>
        <p class="hero-answer">Rock'n G Plumbing is a San Angelo, Texas plumbing company that serves homes and businesses in Christoval, about 20 miles south on the South Concho River. Eli Gomez, Responsible Master Plumber on Texas license M-44822, leads the work for a family with three generations in the trade. The estimate is free.</p>
        <div class="hero-actions">
          <button type="button" class="btn btn-primary btn-lg hero-form-open" data-open-estimate>Request an estimate</button>
          <a class="link-call" href="<?php echo h($telHref); ?>"><?php echo icon('phone', 18); ?>or call <?php echo h($phoneFmt); ?></a>
        </div>
        <ul class="hero-chips">
          <li><?php echo icon('badge-check', 16); ?>Texas Master Plumber <?php echo h($license['number']); ?></li>
          <li><?php echo icon('clipboard-list', 16); ?>Free estimates</li>
          <li><?php echo icon('shield-check', 16); ?>Military discounts</li>
        </ul>
      </div>

      <aside class="hero-form-card" id="estimate-form" aria-labelledby="hero-form-title">
        <h2 id="hero-form-title">Free estimate in Christoval</h2>
        <p class="hero-form-tagline">Tell us what's going on. No obligation.</p>
        <form action="<?php echo h($formAction); ?>" method="POST" class="hero-form">
          <input type="text" name="_honey" style="display:none !important" tabindex="-1" autocomplete="off" aria-hidden="true">
          <input type="hidden" name="_next" value="<?php echo htmlspecialchars($siteUrl); ?>/thank-you">
          <input type="hidden" name="form_location" value="hero">
          <input type="hidden" name="consent_version" value="v2.1">
          <input type="hidden" name="consent_page" value="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>">
          <?php echo p1_attribution_fields('hero'); ?>
          <div class="form-row"><label class="sr-only" for="hero-name">Name</label><input id="hero-name" type="text" name="name" placeholder="Name" autocomplete="name" required></div>
          <div class="form-row"><label class="sr-only" for="hero-phone">Phone</label><input id="hero-phone" type="tel" name="phone" placeholder="Phone" autocomplete="tel" inputmode="tel" required></div>
          <div class="form-row"><label class="sr-only" for="hero-email">Email</label><input id="hero-email" type="email" name="email" placeholder="Email" autocomplete="email" required></div>
          <div class="form-row"><label class="sr-only" for="hero-service">Service</label><select id="hero-service" name="service"><?php echo p1_service_options(); ?></select></div>
          <label class="consent" for="hero-email-opt"><input id="hero-email-opt" type="checkbox" name="email_opt_in" value="yes"><span><strong>Email (optional):</strong> I agree to receive emails from <?php echo h($siteName); ?>. Unsubscribe any time.</span></label>
          <label class="consent" for="hero-sms-opt"><input id="hero-sms-opt" type="checkbox" name="sms_opt_in" value="yes"><span><strong>Texts (optional):</strong> I agree to receive texts from <?php echo h($siteName); ?> about my request. Consent is not a condition of purchase. Message and data rates may apply. Reply STOP to unsubscribe.</span></label>
          <?php echo p1_consent_row('hero'); ?>
          <button type="submit" class="btn btn-primary btn-block">Get my free estimate</button>
        </form>
      </aside>
    </div>
  </div>
</section>

<article class="cv-article" aria-labelledby="cv-h1">

  <!-- ============ TOWN FACTS ============ -->
  <section class="cv-facts" aria-label="Christoval at a glance">
    <div class="container reveal-up">
      <span class="cv-facts__label">Christoval at a glance</span>
      <div class="cv-facts__row">
        <?php foreach ($cvFacts as $fact): ?>
        <div class="cv-fact"><b><?php echo h($fact['num']); ?></b><span><?php echo h($fact['label']); ?></span></div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- ============ LOCAL CONDITIONS ============ -->
  <section class="section cv-local" aria-labelledby="cv-local-title">
    <div class="container-wide">
      <div class="section-head reveal-up">
        <span class="eyebrow">Plumbing in a river town</span>
        <h2 id="cv-local-title">What does a plumber have to plan around in Christoval?</h2>
        <p class="answer-block">A Christoval plumber plans around three local things: a river that has flooded before, a mix of town and rural properties where the water source and the waste line vary from one address to the next, and a drive of about 20 miles from San Angelo. Rock'n G Plumbing asks about each on the first call so the estimate starts from your actual situation.</p>
      </div>

      <div class="cv-local__grid">
        <div class="reveal-left">
          <ol class="cv-river" aria-label="The South Concho River from Anson Springs to Twin Buttes Reservoir">
            <li>
              <small>Starts about 4 miles south</small>
              <h3>Anson Springs</h3>
              <p>The South Concho rises here and runs south to north, one of the few Texas rivers that does.</p>
            </li>
            <li class="is-town">
              <small>Christoval, TX 76935</small>
              <h3>The river runs through town</h3>
              <p>Loop 110 crosses it on a historic low-water crossing, and Pugh River Park gives about three-quarters of a mile of public riverfront under oak trees.</p>
            </li>
            <li>
              <small>About 13 miles north</small>
              <h3>Twin Buttes Reservoir</h3>
              <p>The river meets the Middle Concho in southwest San Angelo, the same direction the road runs from Christoval to the city.</p>
            </li>
          </ol>
          <p class="cv-river__note">Drawn with north at the top. The water in the bar flows the same way the river does.</p>
        </div>

        <div class="cv-bento">
          <div class="cv-card card-tint-1 reveal-right">
            <?php echo icon('waves', 26); ?>
            <h3>A river that has flooded</h3>
            <p>The Sept. 17, 1936 flood destroyed the Baptist encampment on the South Concho and parts of Christoval, and the river flooded again in 2007. A house near the river that has taken on water should have its drains, water heater and shutoff valves checked before everything is switched back on.</p>
          </div>
          <div class="cv-card card-tint-2 reveal-right reveal-delay-1">
            <?php echo icon('house', 26); ?>
            <h3>Town lots and rural ground</h3>
            <p>Christoval covers about 1.3 square miles, and the ranch and river-lot properties around it are more spread out. The water may come from a utility, a shared system or a private well, and the waste line may go to a sewer main or a septic system. Say which you have when you call.</p>
          </div>
          <div class="cv-card card-tint-3 cv-card--wide reveal-up">
            <?php echo icon('route', 26); ?>
            <h3>About 20 miles from San Angelo</h3>
            <p>Rock'n G Plumbing is based in San Angelo, and Christoval sits south of the city on US 277, Loop 110 and RM 2084. A call from Christoval is scheduled as its own trip, so describe what you are seeing and ask the office when the crew can get there.</p>
            <ul>
              <li><?php echo icon('check', 16); ?>Office hours: <?php echo h($weekday[0]); ?>, <?php echo h($weekday[1]); ?></li>
              <li><?php echo icon('check', 16); ?>The estimate is free and written for your property</li>
              <li><?php echo icon('check', 16); ?>Military service? Ask how the discount applies</li>
            </ul>
          </div>
          <div class="cv-safety reveal-up" role="note">
            <?php echo icon('flame', 22); ?>
            <p><strong>Smell gas?</strong> A rotten-egg odor means leave the house and call 911 or your gas utility from outside before anything else. Rock'n G handles <a href="/services/gas-line-services/">gas line installation and repair</a> once it is safe, including lines to standby generators like the one in the photo above.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ============ PROBLEMS → SERVICES ============ -->
  <section class="section slant-top cv-problems" aria-labelledby="cv-problems-title">
    <div class="container">
      <div class="section-head reveal-up">
        <span class="eyebrow">What we fix</span>
        <h2 id="cv-problems-title">Which plumbing problems does Rock'n G Plumbing fix in Christoval?</h2>
        <p class="answer-block">Rock'n G Plumbing takes all eight of its plumbing services to Christoval: water heaters, water softeners and filtration, drain cleaning, sewer line repair, leak detection, gas lines, sprinkler and irrigation repair, and new-construction and remodel plumbing. Match what you are noticing below to the service page that explains it.</p>
      </div>

      <div class="cv-problems__grid">
        <ul class="cv-rows">
          <?php foreach ($cvProblems as $i => $prob):
            $probSvc = serviceBySlug($prob['slug']);
            if ($probSvc === null) continue; ?>
          <li class="cv-row reveal-up reveal-delay-<?php echo ($i % 3) + 1; ?>">
            <a href="<?php echo h(serviceUrl($probSvc)); ?>" aria-label="<?php echo h($probSvc['name']); ?>: <?php echo h($prob['sign']); ?>">
              <span class="cv-row__icon"><?php echo icon($prob['icon'], 22); ?></span>
              <span>
                <b><?php echo h($prob['sign']); ?></b>
                <span class="cv-row__body"><?php echo h($prob['body']); ?></span>
              </span>
              <span class="cv-row__go"><?php echo h($probSvc['name']); ?></span>
            </a>
          </li>
          <?php endforeach; ?>
        </ul>

        <figure class="cv-figure reveal-right">
          <div class="img-reveal">
            <?php echo pictureTag('rockn-pipe-through-slab', 'Drain pipe exposed in a trench cut through a concrete slab', '(max-width: 1000px) 90vw, 380px'); ?>
          </div>
          <figcaption>A Rock'n G job photo: a drain line reached through a trench in the slab. Lines under a floor cannot be seen from the surface, so the estimate starts with what you notice and where.</figcaption>
        </figure>
      </div>
    </div>
  </section>

  <!-- ============ WHO YOU ARE HIRING ============ -->
  <section class="section on-dark edge-wave-top cv-who" aria-labelledby="cv-who-title">
    <span class="grain-layer" aria-hidden="true"></span>
    <div class="container">
      <div class="section-head reveal-up">
        <span class="eyebrow">Who shows up</span>
        <h2 id="cv-who-title">Who are you hiring when you call Rock'n G Plumbing from Christoval?</h2>
        <p class="answer-block">You are hiring a family plumbing company with three generations in the trade and 20+ years of experience, working under Eli Gomez's Texas Master Plumber License M-44822. Rock'n G serves residential, commercial and industrial customers, writes every estimate at no charge and offers military discounts. Rated <?php echo h(number_format((float) $gbp['rating'], 1)); ?> out of 5 from <?php echo (int) $gbp['reviews_count']; ?> Google reviews.</p>
      </div>
      <div class="cv-who__cards">
        <div class="cv-who__card reveal-left">
          <h3>Google reviews and the license on record</h3>
          <p class="cv-credit"><b><?php echo h($license['number']); ?></b><span><?php echo h($license['holder']); ?>, <?php echo h($license['role']); ?>, current through <?php echo h($licenseExpires); ?></span></p>
          <p>Search M-44822 on the Texas State Board of Plumbing Examiners license lookup and you will find the license before anyone arrives at your door. The Google reviews are written by customers, not by us.</p>
          <div class="actions">
            <a class="btn btn-accent" href="<?php echo h($gbp['profile_url']); ?>" target="_blank" rel="noopener">Read reviews on Google</a>
            <a class="btn btn-outline-white" href="<?php echo h($gbp['review_url']); ?>" target="_blank" rel="noopener">Leave a review</a>
          </div>
        </div>
        <div class="cv-who__card reveal-right">
          <h3>License details</h3>
          <dl>
            <dt>License</dt><dd><?php echo h($license['label']); ?></dd>
            <dt>Holder</dt><dd><?php echo h($license['holder']); ?></dd>
            <dt>Role</dt><dd><?php echo h($license['role']); ?></dd>
            <dt>Issued by</dt><dd><?php echo h($license['issuer']); ?></dd>
          </dl>
        </div>
      </div>
      <?php
      require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/google-reviews.php';
      $cvReviews = p1_google_reviews($slug, ['limit' => 3, 'heading' => '', 'summary' => false]);
      if ($cvReviews !== ''): ?>
      <div class="cv-who__reviews"><?php echo $cvReviews; ?></div>
      <?php endif; ?>
    </div>
  </section>

  <!-- ============ BOOKING + MAP ============ -->
  <section class="section cv-book" aria-labelledby="cv-book-title">
    <div class="container">
      <div class="cv-book__grid">
        <div class="reveal-left">
          <span class="eyebrow">Getting on the schedule</span>
          <h2 id="cv-book-title">How do you book a plumber in Christoval with Rock'n G?</h2>
          <p class="answer-block">Call Rock'n G Plumbing at <?php echo h($phoneFmt); ?> between <?php echo h($weekday[1]); ?>, <?php echo h($weekday[0]); ?>, or send the form on this page and the office follows up during business hours. Tell them you are in Christoval and what the plumbing is doing.</p>
          <ol class="cv-steps">
            <li><b>Call or send the form</b><span>Say what you are seeing, which town you are in, and whether the house is on a utility, a shared system or a well.</span></li>
            <li><b>Estimate at no charge</b><span>Rock'n G prices the right fix for your property and tells you what it covers.</span></li>
            <li><b>You decide</b><span>Go ahead when it suits you. Mention military service and ask how the discount applies. The work then runs under <?php echo h($ownerName); ?>'s master license.</span></li>
          </ol>
        </div>
        <div class="cv-map reveal-right">
          <div class="map-embed">
            <iframe src="<?php echo h($gbp['embed_src']); ?>" title="Google Maps listing for Rock'n G Plumbing, a San Angelo, Texas plumber serving Christoval" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
          </div>
          <p>Rock'n G Plumbing is a service-area business, so this map shows its Google listing rather than a storefront. <a href="<?php echo h($gbp['profile_url']); ?>" target="_blank" rel="noopener">Open the listing on Google</a>.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- ============ RELATED SERVICES (required services-grid) ============ -->
  <section class="section cv-related" aria-label="Related plumbing services">
    <div class="container">
      <div class="section-title reveal-up">
        <span class="eyebrow-label">What We Do</span>
        <h2>Which <span class="text-accent">plumbing services</span> do Christoval properties ask about first?</h2>
        <p class="hero-answer">A river-town property and the ranch ground around it can raise three plumbing questions: where a hidden leak is hiding, what is wrong with the sewer or septic line, and how to run gas to a generator or appliance. Rock'n G Plumbing handles all three, and the cards below link each one.</p>
        <span class="section-subtitle">Leaks · Sewer lines · Gas lines</span>
        <p class="prose">See the full list of <a href="/services/">plumbing services Rock'n G offers</a>, or read about <a href="/areas-we-serve/">the other towns we serve around San Angelo</a>.</p>
        <?php if (!empty($areasWeServeTowns)): ?>
        <ul class="cv-nearby" aria-label="Other towns served">
          <?php foreach ($areasWeServeTowns as $nearTown): ?><li><?php echo h($nearTown); ?></li><?php endforeach; ?>
        </ul>
        <?php endif; ?>
      </div>
      <div class="services-grid">
        <?php echo renderServiceCards(['leak-detection-repair', 'sewer-line-repair', 'gas-line-services'], '(max-width: 640px) 100vw, (max-width: 1000px) 50vw, 380px'); ?>
      </div>
    </div>
  </section>

  <!-- ============ FINAL CTA ============ -->
  <section class="closing-cta texture-grain edge-torn-top cv-cta" aria-labelledby="cv-cta-title">
    <span class="grain-layer" aria-hidden="true"></span>
    <span class="floating-ring cv-cta__ring" aria-hidden="true"></span>
    <div class="container">
      <div class="reveal-left">
        <span class="eyebrow">No charge to look</span>
        <h2 id="cv-cta-title">Ready to get a plumber out to Christoval?</h2>
        <p>Call Rock'n G Plumbing at <?php echo h($phoneFmt); ?>, <?php echo h($weekday[0]); ?> <?php echo h($weekday[1]); ?>, or send the form and the office will follow up during business hours. Tell them you are in Christoval and what the plumbing is doing.</p>
        <p class="cv-updated">Last updated: <?php echo date('F Y'); ?></p>
      </div>
      <div class="actions reveal-right">
        <a class="btn btn-accent btn-lg" href="/contact/" data-open-estimate>Request a Christoval Estimate</a>
        <a class="link-call" href="<?php echo h($telHref); ?>"><?php echo icon('phone', 18); ?>Call <?php echo h($phoneFmt); ?></a>
      </div>
    </div>
  </section>

</article>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>
