<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/functions.php';
?>
<?php
/**
 * services/index.php — Plumbing services hub (Phase 4). Lists ALL 8 services in the required services-grid component,
 * then explains how the services fit together, which one fits a given situation, and who does the work.
 * Every business fact traces to build-plan.json / includes/config.php. No FAQ section or schema (SEO brief),
 * no emergency/24-hour wording, no prices.
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/service-cards.php';

$pageType        = 'other';
$pageTitle       = "Plumbing Services from a San Angelo, TX Plumber | Rock'n G";
$pageDescription = "Plumber in San Angelo, TX for water heaters, drains, sewer, slab leaks & gas lines • Master Plumber M-44822 • Free estimates. Call (325) 650-7000.";
$canonicalUrl    = $siteUrl . '/services/';
$currentPage     = 'services';
$breadcrumbs     = [['name' => 'Services', 'url' => '/services/']];

$heroImage   = 'rockn-crew-excavation';
$heroAlt     = "Rock'n G Plumbing crew in red company shirts with a mini excavator, digging out a buried line";
$heroPreload = ['srcset' => imageVariantSrcset($heroImage, 'avif'), 'sizes' => '100vw'];
$ogImage     = '/assets/images/' . $heroImage . '.jpg';

$telHref  = 'tel:' . $phoneE164;
$phoneFmt = formatPhone($phone);
$weekday  = hoursGroups($hours)[0];      // ['Mon–Fri', '8:00 AM – 6:00 PM']

$svBySlug = [];
foreach ($services as $svSvc) $svBySlug[$svSvc['slug']] = $svSvc;

// Grid order: water in → water out → gas, yard, construction
// Ordered so cards cut from the same job photo never sit side by side or stacked in the 4-up grid
$svGridOrder = ['water-heaters', 'drain-cleaning', 'gas-line-services', 'sewer-line-repair',
                'leak-detection-repair', 'water-filtration-softeners', 'irrigation-sprinkler-repair', 'new-construction-remodel-plumbing'];

// Signature: how a property's plumbing fits together (general plumbing knowledge → the service that covers each part)
$svFlow = [
    ['icon' => 'route',    'step' => 'Supply line in', 'slug' => 'leak-detection-repair',
     'body' => 'The water line from the meter into the house. A leak here, or under the slab, often shows up first as a jump in the water bill.'],
    ['icon' => 'droplets', 'step' => 'Treatment',      'slug' => 'water-filtration-softeners',
     'body' => "A softener or filter takes out San Angelo's hardness minerals before they coat pipes, fixtures and the water heater."],
    ['icon' => 'flame',    'step' => 'Hot water',      'slug' => 'water-heaters',
     'body' => 'The tank or tankless heater that feeds every hot tap, shower and appliance in the house.'],
    ['icon' => 'waves',    'step' => 'Drains',         'slug' => 'drain-cleaning',
     'body' => 'Sinks, tubs, toilets and the washer empty into branch drains that carry everything toward the main line.'],
    ['icon' => 'search',   'step' => 'Sewer out',      'slug' => 'sewer-line-repair',
     'body' => 'Every branch drain ties into the main sewer line, which runs out of the house toward the street.'],
];
$svBranches = [
    ['icon' => 'wrench', 'title' => 'Gas piping', 'slug' => 'gas-line-services',
     'body' => 'Ranges, gas water heaters, fireplaces and standby generators all run on gas piping that has to be sized and tested.'],
    ['icon' => 'sprout', 'title' => 'Outdoor water', 'slug' => 'irrigation-sprinkler-repair',
     'body' => 'Sprinkler and irrigation systems tap the same supply. A broken head or a cracked line wastes water every time a zone runs.'],
    ['icon' => 'hammer', 'title' => 'New and reworked pipe', 'slug' => 'new-construction-remodel-plumbing',
     'body' => 'New homes lay this whole system out from scratch; remodels rework part of it when fixtures move.'],
];

// Situation guide (by what the customer is doing, not by symptom — the homepage carries the symptom board)
$svSituations = [
    ['situation' => 'Buying an older home',                         'slug' => 'sewer-line-repair',
     'why' => 'A camera inspection shows the condition of the main sewer line before you close, while there is still time to ask questions.'],
    ['situation' => 'Planning a kitchen or bathroom remodel',        'slug' => 'new-construction-remodel-plumbing',
     'why' => 'Moving a sink, tub or range changes drains, vents and gas lines. Price that before the design is final.'],
    ['situation' => 'Water bill jumped with no change in use',       'slug' => 'leak-detection-repair',
     'why' => 'A leak in the supply line or under the slab can run out of sight for a long time.'],
    ['situation' => 'Adding a gas range, fireplace or generator',    'slug' => 'gas-line-services',
     'why' => 'A new gas appliance needs a line sized for it, run to it and tested before it is lit.'],
    ['situation' => 'Moving into a home with spotty dishes and crusty faucets', 'slug' => 'water-filtration-softeners',
     'why' => 'That is hard water. Treating it protects fixtures and the water heater from scale.'],
    ['situation' => 'Showers going lukewarm halfway through',        'slug' => 'water-heaters',
     'why' => 'A heater losing capacity may need a part, a flush or a replacement. The estimate sorts out which.'],
    ['situation' => 'The same drain clogs again and again',          'slug' => 'drain-cleaning',
     'why' => 'A repeat clog in one fixture points to that drain. If several drains back up together, it is usually the sewer line.'],
    ['situation' => 'One zone of the yard stays soggy or dry',       'slug' => 'irrigation-sprinkler-repair',
     'why' => 'A broken head, a leaking line or a valve that will not open is usually behind it.'],
];

// Schema: CollectionPage listing the 8 Service pages; provider = the Plumber node head.php prints on every page
$svItems = [];
foreach ($svGridOrder as $svI => $svSlug) {
    $svSvc = $svBySlug[$svSlug];
    $svUrl = absUrl(serviceUrl($svSvc));
    $svItems[] = ['@type' => 'ListItem', 'position' => $svI + 1, 'item' => [
        '@type' => 'Service', '@id' => $svUrl . '#service-' . $svSlug, 'name' => $svSvc['name'], 'url' => $svUrl,
        'provider' => ['@id' => orgId()],
    ]];
}
$schemaMarkup = [[
    "@context"   => 'https://schema.org',
    '@type'      => 'CollectionPage',
    '@id'        => $canonicalUrl . '#webpage',
    'url'        => $canonicalUrl,
    'name'       => $pageTitle,
    'description'=> $pageDescription,
    'isPartOf'   => ['@id' => $siteUrl . '/#website'],
    'about'      => ['@id' => orgId()],
    'mainEntity' => ['@type' => 'ItemList', 'name' => "Rock'n G Plumbing services", 'numberOfItems' => count($svItems), 'itemListElement' => $svItems],
    'inLanguage' => 'en-US',
]];

ob_start();
?>
<style>
/* =====================================================================
   Services hub — page-only CSS (sv- prefix)
   Tokens from framework.css; no raw colors, shadows or spacing.
   ===================================================================== */
@property --sv-pulse {
  syntax: '<percentage>';
  inherits: false;
  initial-value: 0%;
}

/* ---------- Hero: layered photo (C1) ---------- */
.sv-hero {
  padding-block-end: var(--space-16);
}
.sv-hero .hero-bg {
  overflow: hidden;
}
.sv-hero .hero-bg picture,
.sv-hero .hero-bg img {
  width: 100%;
  height: 100%;
}
.sv-hero .hero-bg img {
  object-fit: cover;
  object-position: 60% 35%;
  animation: sv-zoom 24s var(--ease) both;
}
.sv-hero::before {
  content: "";
  position: absolute;
  inset: 0;
  z-index: -1;
  pointer-events: none;
  background:
    radial-gradient(ellipse 45% 55% at 0% 100%, color-mix(in srgb, var(--color-accent) 26%, transparent), transparent 70%),
    linear-gradient(180deg, color-mix(in srgb, var(--color-primary-deep) 30%, transparent) 0%, transparent 40%, color-mix(in srgb, var(--color-primary-deep) 78%, transparent) 100%);
}
.sv-hero::after {
  content: "";
  position: absolute;
  inset: 0;
  z-index: -1;
  pointer-events: none;
  opacity: .2;
  mix-blend-mode: overlay;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='160' height='160'%3E%3Cfilter id='g'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.9' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23g)'/%3E%3C/svg%3E");
}
.sv-hero .breadcrumb {
  color: color-mix(in srgb, var(--color-surface) 72%, transparent);
  margin-block-end: var(--space-2);
}
.sv-hero .breadcrumb li {
  display: inline-flex;
  align-items: center;
}
.sv-hero .breadcrumb a:hover {
  color: var(--color-accent-bright);
}
.sv-hero .breadcrumb [aria-current="page"] {
  color: var(--color-surface);
}
.sv-hero .hero-title {
  max-width: 17ch;
  text-wrap: balance;
}
.sv-hero .hero-answer {
  max-width: 37rem;
}
.sv-hero .hero-chips li {
  backdrop-filter: blur(6px);
  -webkit-backdrop-filter: blur(6px);
}
.sv-hero .hero-form-open {
  background: var(--color-accent);
  color: var(--color-white);
}
.sv-hero .hero-form-card {
  border-block-start: 4px solid var(--color-accent);
}
.sv-hero .hero-form-card h2 {
  text-wrap: balance;
}
.sv-hero .hero-form .consent {
  margin-block: var(--space-1);
}
@keyframes sv-zoom {
  from { transform: scale(1.1); }
  to   { transform: scale(1); }
}

/* ---------- All services (required component) ---------- */
.sv-all {
  background: var(--color-paper);
}
.sv-all .section-title {
  max-width: none;
  grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
  grid-template-areas:
    "eyebrow eyebrow"
    "title   answer"
    "sub     prose";
  column-gap: var(--space-12);
  row-gap: var(--space-3);
  align-items: end;
}
.sv-all .section-title .eyebrow-label { grid-area: eyebrow; }
.sv-all .section-title h2 { grid-area: title; max-width: 20ch; text-wrap: balance; }
.sv-all .section-title .hero-answer { grid-area: answer; font-size: var(--fs-body); color: var(--color-ink-2); }
.sv-all .section-title .section-subtitle { grid-area: sub; align-self: start; }
.sv-all .section-title .prose { grid-area: prose; font-size: var(--fs-small); color: var(--color-muted); }
.sv-all .service-card-with-image {
  isolation: isolate;
}
.sv-all .service-card-with-image::after {
  content: "";
  position: absolute;
  inset-inline: 0;
  inset-block-end: 0;
  height: 4px;
  background: linear-gradient(90deg, var(--color-accent), var(--color-primary));
  transform: scaleX(0);
  transform-origin: right;
  transition: transform var(--transition-slow);
}
.sv-all .service-card-with-image:hover::after,
.sv-all .service-card-with-image:focus-within::after {
  transform: scaleX(1);
  transform-origin: left;
}
.sv-all .service-card__image img {
  transition: transform var(--transition-slow);
}
.sv-all .service-card-with-image:hover .service-card__image img {
  transform: scale(1.05);
}
.sv-all .service-card-with-image h3 {
  text-wrap: balance;
}
.sv-intro {
  display: grid;
  grid-template-columns: auto minmax(0, 1fr);
  gap: var(--space-5);
  align-items: start;
  margin-block: 0 var(--space-10);
  padding: var(--space-5) var(--space-6);
  border-radius: var(--radius-lg);
  background: var(--color-surface);
  border: 1px solid var(--color-line);
  max-width: 920px;
}
.sv-intro svg {
  color: var(--color-accent-dark);
  margin-block-start: var(--space-1);
}
.sv-intro p {
  margin: 0;
  color: var(--color-ink-2);
}

/* ---------- Signature: plumbing map (dark, slant) ---------- */
.sv-map .section-head {
  max-width: 64ch;
}
.sv-map .answer-block {
  background: color-mix(in srgb, var(--color-surface) 6%, transparent);
  color: color-mix(in srgb, var(--color-surface) 88%, transparent);
}
.sv-flow {
  list-style: none;
  margin: var(--space-10) 0 0;
  padding: 0;
  display: grid;
  grid-template-columns: repeat(5, minmax(0, 1fr));
  gap: var(--space-6);
  counter-reset: sv-flow;
}
.sv-flow li {
  counter-increment: sv-flow;
  position: relative;
}
.sv-flow li:not(:last-child)::after {
  content: "";
  position: absolute;
  inset-block-start: 34px;
  inset-inline-start: calc(100% + var(--space-1));
  width: calc(var(--space-6) - var(--space-2));
  height: 4px;
  border-radius: var(--radius-full);
  background: linear-gradient(90deg, var(--color-accent) var(--sv-pulse), color-mix(in srgb, var(--color-accent) 30%, transparent) var(--sv-pulse));
  animation: sv-pulse 2.2s linear infinite;
}
@keyframes sv-pulse {
  from { --sv-pulse: 0%; }
  to   { --sv-pulse: 100%; }
}
.sv-node {
  display: grid;
  gap: var(--space-3);
  height: 100%;
  padding: var(--space-5);
  border-radius: var(--radius-lg);
  text-decoration: none;
  color: inherit;
  background: color-mix(in srgb, var(--color-surface) 6%, transparent);
  border: 1px solid color-mix(in srgb, var(--color-surface) 14%, transparent);
  transition: transform var(--transition), background-color var(--transition), border-color var(--transition);
}
.sv-node:hover,
.sv-node:focus-visible {
  transform: translateY(-4px);
  background: color-mix(in srgb, var(--color-surface) 11%, transparent);
  border-color: var(--color-accent);
}
.sv-node__icon {
  width: 46px;
  height: 46px;
  display: grid;
  place-items: center;
  border-radius: var(--radius-full);
  color: var(--color-ink);
  background: var(--color-accent);
}
.sv-node small {
  font-family: var(--font-accent);
  font-size: var(--fs-eyebrow);
  letter-spacing: .14em;
  text-transform: uppercase;
  color: var(--color-accent-bright);
}
.sv-node small::before {
  content: counter(sv-flow, decimal-leading-zero) " · ";
}
.sv-node h3 {
  font-size: 1.1rem;
  color: var(--color-surface);
  text-wrap: balance;
}
.sv-node p {
  margin: 0;
  font-size: var(--fs-small);
}
.sv-node__more {
  display: inline-flex;
  align-items: center;
  gap: var(--space-1);
  margin-block-start: auto;
  font-size: var(--fs-small);
  font-weight: 600;
  color: var(--color-accent-bright);
}
.sv-branches {
  list-style: none;
  margin: var(--space-8) 0 0;
  padding: var(--space-6) 0 0;
  display: grid;
  grid-template-columns: repeat(3, minmax(0, 1fr));
  gap: var(--space-4);
  border-block-start: 2px dashed color-mix(in srgb, var(--color-accent) 40%, transparent);
}
.sv-branch a {
  display: grid;
  grid-template-columns: auto 1fr;
  gap: var(--space-1) var(--space-4);
  padding: var(--space-5);
  border-radius: var(--radius);
  text-decoration: none;
  color: inherit;
  border-inline-start: 4px solid var(--color-accent);
  background: color-mix(in srgb, var(--color-surface) 5%, transparent);
  transition: background-color var(--transition);
}
.sv-branch a:hover,
.sv-branch a:focus-visible {
  background: color-mix(in srgb, var(--color-surface) 10%, transparent);
}
.sv-branch svg {
  grid-row: span 2;
  color: var(--color-accent-bright);
}
.sv-branch b {
  font-family: var(--font-heading);
  color: var(--color-surface);
}
.sv-branch span {
  font-size: var(--fs-small);
  color: color-mix(in srgb, var(--color-surface) 78%, transparent);
}

/* ---------- Situation guide table ---------- */
.sv-guide {
  background: var(--color-surface);
}
.sv-guide__wrap {
  container-type: inline-size;
  container-name: svguide;
  margin-block-start: var(--space-8);
}
.sv-table {
  width: 100%;
  border-collapse: collapse;
  font-size: var(--fs-small);
}
.sv-table caption {
  caption-side: bottom;
  text-align: start;
  padding-block-start: var(--space-4);
  color: var(--color-muted);
}
.sv-table th,
.sv-table td {
  padding: var(--space-4) var(--space-5);
  text-align: start;
  vertical-align: top;
}
.sv-table thead th {
  font-family: var(--font-accent);
  font-size: var(--fs-eyebrow);
  letter-spacing: .14em;
  text-transform: uppercase;
  color: var(--color-muted);
  border-block-end: 2px solid var(--color-ink);
}
.sv-table tbody tr {
  border-block-end: 1px solid var(--color-line);
  transition: background-color var(--transition);
}
.sv-table tbody tr:nth-child(even) {
  background: var(--color-paper);
}
.sv-table tbody tr:hover {
  background: color-mix(in srgb, var(--color-accent) 8%, var(--color-surface));
}
.sv-table tbody th {
  width: 32%;
  font-family: var(--font-heading);
  font-weight: 700;
  font-size: 1rem;
  color: var(--color-ink);
}
.sv-table td {
  color: var(--color-ink-2);
}
.sv-table td a {
  display: inline-flex;
  align-items: center;
  gap: var(--space-1);
  font-weight: 600;
  color: var(--color-primary);
  text-decoration: none;
  white-space: nowrap;
}
.sv-table td a:hover {
  color: var(--color-accent-dark);
}
@container svguide (max-width: 720px) {
  .sv-table thead {
    position: absolute;
    width: 1px;
    height: 1px;
    overflow: hidden;
    clip-path: inset(50%);
  }
  .sv-table,
  .sv-table tbody,
  .sv-table tr,
  .sv-table th,
  .sv-table td {
    display: block;
    width: auto;
  }
  .sv-table tbody tr {
    padding-block: var(--space-3);
  }
  .sv-table tbody th,
  .sv-table td {
    padding: var(--space-1) var(--space-3);
  }
  .sv-table td a {
    white-space: normal;
  }
}

/* ---------- Residential / commercial / industrial: typographic rows (wave edge) ---------- */
.sv-sectors {
  background: var(--color-paper-2);
}
.sv-sectors__head {
  display: grid;
  grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
  gap: var(--space-12);
  align-items: end;
}
.sv-sectors__head h2 {
  max-width: 20ch;
}
.sv-rows {
  list-style: none;
  margin: var(--space-10) 0 0;
  padding: 0;
}
.sv-row {
  display: grid;
  grid-template-columns: minmax(0, 1fr) minmax(0, 1.4fr);
  gap: var(--space-8);
  align-items: baseline;
  padding-block: var(--space-6);
  border-block-start: 1px solid var(--color-line);
  transition: padding-inline-start var(--transition);
}
.sv-row:last-child {
  border-block-end: 1px solid var(--color-line);
}
.sv-row:hover {
  padding-inline-start: var(--space-4);
}
.sv-row b {
  font-family: var(--font-accent);
  font-size: clamp(2.2rem, 1.4rem + 3vw, 4.25rem);
  line-height: .95;
  text-transform: uppercase;
  letter-spacing: .01em;
  color: var(--color-primary);
}
.sv-row:nth-child(2) b {
  color: var(--color-accent-dark);
}
.sv-row:nth-child(3) b {
  color: var(--color-secondary);
}
.sv-row p {
  margin: 0;
  color: var(--color-ink-2);
  max-width: var(--measure);
}
.sv-row a {
  color: var(--color-primary);
  font-weight: 600;
}

/* ---------- Expert: framed photo + towns ---------- */
.sv-expert {
  background: var(--color-surface);
}
.sv-expert__grid {
  display: grid;
  grid-template-columns: minmax(0, 2fr) minmax(0, 3fr);
  gap: var(--space-12);
  align-items: center;
}
.sv-expert__photo {
  margin: 0;
  border-radius: var(--radius-lg);
  overflow: hidden;
  aspect-ratio: 5 / 4;
  box-shadow: var(--shadow-lg);
  outline: 2px solid var(--color-accent);
  outline-offset: var(--space-3);
}
.sv-expert__photo picture,
.sv-expert__photo img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}
.sv-expert__copy {
  display: grid;
  gap: var(--space-4);
}
.sv-expert__copy > p:not(.answer-block) {
  margin: 0;
  color: var(--color-ink-2);
  max-width: var(--measure);
}
.sv-towns {
  list-style: none;
  margin: 0;
  padding: 0;
  display: flex;
  flex-wrap: wrap;
  gap: var(--space-2);
}
.sv-towns li {
  display: inline-flex;
  align-items: center;
  gap: var(--space-1);
  padding: var(--space-1) var(--space-3);
  border-radius: var(--radius-full);
  font-size: var(--fs-small);
  color: var(--color-ink-2);
  background: var(--color-paper);
  border: 1px solid var(--color-line);
}
.sv-towns li:first-child {
  color: var(--color-surface);
  background: var(--color-primary);
  border-color: var(--color-primary);
}
.sv-towns a {
  color: inherit;
  font-weight: 600;
}
.sv-towns svg {
  color: var(--color-accent-dark);
}
.sv-towns li:first-child svg {
  color: var(--color-accent-bright);
}

/* ---------- Proof (dark, torn edge) ---------- */
.sv-proof .section-head {
  max-width: 64ch;
}
.sv-proof .answer-block {
  background: color-mix(in srgb, var(--color-surface) 6%, transparent);
  color: color-mix(in srgb, var(--color-surface) 88%, transparent);
}
.sv-proof__row {
  display: grid;
  grid-template-columns: minmax(0, 1.3fr) minmax(0, 1fr);
  gap: var(--space-6);
  margin-block-start: var(--space-8);
}
.sv-proof__card {
  display: grid;
  align-content: start;
  gap: var(--space-3);
  padding: var(--space-6);
  border-radius: var(--radius-lg);
  background: color-mix(in srgb, var(--color-surface) 7%, transparent);
  border: 1px solid color-mix(in srgb, var(--color-surface) 16%, transparent);
}
.sv-proof__card h3 {
  color: var(--color-surface);
}
.sv-proof__card p {
  margin: 0;
}
.sv-proof__card .actions {
  display: flex;
  flex-wrap: wrap;
  gap: var(--space-3);
}
.sv-proof__card dl {
  margin: 0;
  display: grid;
  grid-template-columns: auto 1fr;
  gap: var(--space-2) var(--space-4);
  font-size: var(--fs-small);
}
.sv-proof__card dt {
  color: color-mix(in srgb, var(--color-surface) 62%, transparent);
}
.sv-proof__card dd {
  margin: 0;
  color: var(--color-surface);
  font-weight: 600;
}
.sv-proof__reviews {
  margin-block-start: var(--space-8);
}
.sv-proof__reviews .p1gr-text {
  color: var(--color-ink-2);
}

/* ---------- Final CTA (curved edge) ---------- */
.sv-cta .container {
  align-items: center;
}
.sv-cta h2 {
  max-width: 20ch;
  text-wrap: balance;
}
.sv-cta .link-call {
  color: color-mix(in srgb, var(--color-surface) 88%, transparent);
}
.sv-cta .link-call svg {
  color: var(--color-accent-bright);
}
.sv-cta__ring {
  inset-inline-start: -130px;
  inset-block-start: -110px;
}

/* ---------- Responsive ---------- */
@media (max-width: 1100px) {
  .sv-flow {
    grid-template-columns: repeat(3, minmax(0, 1fr));
  }
  .sv-flow li::after {
    display: none;
  }
}
@media (max-width: 1000px) {
  .sv-sectors__head {
    grid-template-columns: 1fr;
  }
  .sv-all .section-title {
    grid-template-columns: 1fr;
    grid-template-areas:
      "eyebrow"
      "title"
      "answer"
      "sub"
      "prose";
  }
  .sv-branches {
    grid-template-columns: 1fr;
  }
}
@media (max-width: 900px) {
  .sv-expert__grid,
  .sv-proof__row {
    grid-template-columns: 1fr;
  }
  .sv-expert__photo {
    max-width: 520px;
    margin-inline: var(--space-3);
  }
}
@media (max-width: 640px) {
  .sv-flow {
    grid-template-columns: 1fr;
    gap: var(--space-3);
  }
  .sv-row {
    grid-template-columns: 1fr;
    gap: var(--space-2);
  }
  .sv-intro {
    grid-template-columns: 1fr;
  }
}
@media (prefers-reduced-motion: reduce) {
  .sv-hero .hero-bg img,
  .sv-flow li::after {
    animation: none;
  }
  .sv-node,
  .sv-row,
  .sv-table tbody tr,
  .sv-all .service-card-with-image::after,
  .sv-all .service-card__image img {
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
<section class="hero hero--photo hero--interior sv-hero" aria-labelledby="sv-h1">
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
            <li><span aria-current="page">Services</span></li>
          </ol>
        </nav>
        <span class="eyebrow"><?php echo count($services); ?> Services · <?php echo h($address['city'] . ', ' . $address['state']); ?></span>
        <h1 class="hero-title" id="sv-h1">Plumbing Services from Your San Angelo Plumber</h1>
        <p class="hero-answer">Rock'n G Plumbing is a San Angelo, TX plumber handling eight kinds of work: water heaters, softeners and filtration, drain cleaning, sewer lines, leak detection and slab leaks, gas lines, sprinkler and irrigation repair, and new construction and remodel plumbing, for residential, commercial and industrial customers across Tom Green County.</p>
        <div class="hero-actions">
          <button type="button" class="btn btn-primary btn-lg hero-form-open" data-open-estimate>Get a free estimate</button>
          <a class="link-call" href="<?php echo h($telHref); ?>"><?php echo icon('phone', 18); ?>or call <?php echo h($phoneFmt); ?></a>
        </div>
        <ul class="hero-chips">
          <li><?php echo icon('badge-check', 16); ?>Texas Master Plumber <?php echo h($license['number']); ?></li>
          <li><?php echo icon('clipboard-list', 16); ?>Free estimates</li>
          <li><?php echo icon('users', 16); ?>Three generations of plumbers</li>
        </ul>
      </div>

      <aside class="hero-form-card" id="estimate-form" aria-labelledby="hero-form-title">
        <h2 id="hero-form-title">Need a plumber in San Angelo?</h2>
        <p class="hero-form-tagline">Pick the closest service and tell us what's going on.</p>
        <form action="<?php echo h($formAction); ?>" method="POST" class="hero-form">
          <input type="text" name="_honey" style="display:none !important" tabindex="-1" autocomplete="off" aria-hidden="true">
          <input type="hidden" name="_next" value="<?php echo htmlspecialchars($siteUrl); ?>/thank-you">
          <input type="hidden" name="form_location" value="hero">
          <input type="hidden" name="consent_version" value="v2.1">
          <input type="hidden" name="consent_page" value="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>">
          <input type="hidden" name="_consent_version" value="v2.1">
          <input type="hidden" name="_consent_page" value="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>">
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

<!-- ============ ALL SERVICES (required services-grid) ============ -->
<section class="section sv-all" id="all-services" aria-label="Plumbing services">
  <div class="container-wide">
    <div class="sv-intro reveal-up">
      <?php echo icon('info', 24); ?>
      <p>Every service below is handled by the same crew, under one Texas Master Plumber license, for homes and businesses in San Angelo, Christoval, Wall, Veribest, Carlsbad, Grape Creek and Water Valley. Each card links to a page with the details: what the work involves, the signs to watch for, and how a job runs.</p>
    </div>
    <div class="section-title reveal-up">
      <span class="eyebrow-label">What We Do</span>
      <h2>Which plumbing services does <span class="text-accent">Rock'n G</span> offer in San Angelo?</h2>
      <p class="hero-answer">Rock'n G Plumbing offers eight plumbing services in San Angelo and Christoval, and each one has its own page below. Water heaters and softeners cover the water you use, drain, sewer and leak work covers the lines that fail, and gas, irrigation and construction plumbing cover the rest of the property.</p>
      <span class="section-subtitle">Water · Drains · Sewer · Gas · Irrigation · Construction</span>
      <p class="prose">Not sure which card fits? The guide further down matches common situations to the right service.</p>
    </div>
    <div class="services-grid">
      <?php echo renderServiceCards($svGridOrder); ?>
    </div>
  </div>
</section>

<!-- ============ SIGNATURE: HOW YOUR PLUMBING FITS TOGETHER ============ -->
<section class="section on-dark slant-top sv-map" aria-labelledby="sv-map-title">
  <span class="grain-layer" aria-hidden="true"></span>
  <div class="container-wide">
    <div class="section-head reveal-up">
      <span class="eyebrow">One system, eight services</span>
      <h2 id="sv-map-title">How do the parts of your home's plumbing fit together?</h2>
      <p class="answer-block">Your home's plumbing is one system: water comes in through the supply line, gets treated and heated, feeds the fixtures, and leaves through the drains and sewer line. Gas piping and irrigation branch off it, so a problem at one point can show up somewhere else in the house.</p>
    </div>
    <ol class="sv-flow">
      <?php foreach ($svFlow as $i => $node): $nodeSvc = $svBySlug[$node['slug']]; ?>
      <li class="reveal-up reveal-delay-<?php echo ($i % 4) + 1; ?>">
        <a class="sv-node" href="<?php echo h(serviceUrl($nodeSvc)); ?>">
          <span class="sv-node__icon"><?php echo icon($node['icon'], 22); ?></span>
          <small><?php echo h($node['step']); ?></small>
          <h3><?php echo h($nodeSvc['name']); ?></h3>
          <p><?php echo h($node['body']); ?></p>
          <span class="sv-node__more">See the service <?php echo icon('arrow-right', 16); ?></span>
        </a>
      </li>
      <?php endforeach; ?>
    </ol>
    <ul class="sv-branches">
      <?php foreach ($svBranches as $branch): $branchSvc = $svBySlug[$branch['slug']]; ?>
      <li class="sv-branch reveal-up">
        <a href="<?php echo h(serviceUrl($branchSvc)); ?>">
          <?php echo icon($branch['icon'], 24); ?>
          <b><?php echo h($branch['title']); ?>: <?php echo h($branchSvc['name']); ?></b>
          <span><?php echo h($branch['body']); ?></span>
        </a>
      </li>
      <?php endforeach; ?>
    </ul>
  </div>
</section>

<!-- ============ SITUATION GUIDE ============ -->
<section class="section sv-guide" aria-labelledby="sv-guide-title">
  <div class="container">
    <div class="section-head reveal-up">
      <span class="eyebrow">Where to start</span>
      <h2 id="sv-guide-title">Which Rock'n G service fits your situation?</h2>
      <p class="answer-block">The right Rock'n G service depends on what you are doing as much as what is broken. Buying a house, planning a remodel or adding a gas appliance each point to a different starting service, and the table below matches eight common situations to the page that covers them.</p>
    </div>
    <div class="sv-guide__wrap reveal-scale">
      <table class="sv-table">
        <caption>A starting point, not a diagnosis. Call <?php echo h($phoneFmt); ?> if your situation is not listed.</caption>
        <thead>
          <tr><th scope="col">Your situation</th><th scope="col">Start with</th><th scope="col">Why</th></tr>
        </thead>
        <tbody>
          <?php foreach ($svSituations as $row): $rowSvc = $svBySlug[$row['slug']]; ?>
          <tr>
            <th scope="row"><?php echo h($row['situation']); ?></th>
            <td><a href="<?php echo h(serviceUrl($rowSvc)); ?>"><?php echo h($rowSvc['name']); ?> <?php echo icon('arrow-right', 14); ?></a></td>
            <td><?php echo h($row['why']); ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</section>

<!-- ============ RESIDENTIAL / COMMERCIAL / INDUSTRIAL ============ -->
<section class="section edge-wave-top sv-sectors" aria-labelledby="sv-sectors-title">
  <div class="container">
    <div class="sv-sectors__head reveal-up">
      <div>
        <span class="eyebrow">Who Rock'n G works for</span>
        <h2 id="sv-sectors-title">Does Rock'n G work on homes, businesses and industrial sites?</h2>
      </div>
      <p class="answer-block">Yes, Rock'n G Plumbing works for residential, commercial and industrial customers. The services on this page apply to all three, from a water heater in a family home to the plumbing in a commercial build-out, and every job runs under the same master license.</p>
    </div>
    <ul class="sv-rows">
      <li class="sv-row reveal-left">
        <b>Residential</b>
        <p>Homes in San Angelo and the surrounding towns: water heaters and softeners, slow drains, slab leaks, sprinkler repairs and kitchen or bathroom remodels.</p>
      </li>
      <li class="sv-row reveal-left reveal-delay-1">
        <b>Commercial</b>
        <p>Businesses and commercial spaces, from a restroom drain that keeps backing up to the plumbing in a space being built out. See <a href="/services/new-construction-remodel-plumbing/">new construction and remodel plumbing</a>.</p>
      </li>
      <li class="sv-row reveal-left reveal-delay-2">
        <b>Industrial</b>
        <p>Industrial customers are part of Rock'n G's work too. Call the office to talk through the scope and access for an industrial job.</p>
      </li>
    </ul>
  </div>
</section>

<!-- ============ EXPERT ============ -->
<section class="section sv-expert" aria-labelledby="sv-expert-title">
  <div class="container">
    <div class="sv-expert__grid">
      <figure class="sv-expert__photo reveal-left">
        <?php echo pictureTag('rockn-trench-dig', "Rock'n G plumber in a company shirt standing in an excavated trench to reach a buried line", '(max-width: 900px) 90vw, 440px'); ?>
      </figure>
      <div class="sv-expert__copy reveal-right">
        <span class="eyebrow">Who does the work</span>
        <h2 id="sv-expert-title">Who is the master plumber behind every Rock'n G service?</h2>
        <p class="answer-block">Eli Gomez is the master plumber behind every Rock'n G Plumbing service, working as Responsible Master Plumber on Texas Master Plumber License M-44822. Eli comes from three generations of plumbers, and Rock'n G brings 20+ years of experience to jobs across San Angelo and Tom Green County.</p>
        <p>The office is open <?php echo h($weekday[0]); ?> <?php echo h($weekday[1]); ?> and closed on weekends. If you have served in the military, ask about the military discount on any service.</p>
        <ul class="sv-towns" aria-label="Towns served">
          <li><?php echo icon('map-pin', 14); ?>San Angelo</li>
          <li><?php echo icon('map-pin', 14); ?><?php if (pageExists('/service-area/christoval-tx/')): ?><a href="/service-area/christoval-tx/">Christoval</a><?php else: ?>Christoval<?php endif; ?></li>
          <?php foreach ($areasWeServeTowns as $town): ?>
          <li><?php echo icon('map-pin', 14); ?><?php echo h($town); ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>
  </div>
</section>

<!-- ============ PROOF ============ -->
<section class="section on-dark edge-torn-top sv-proof" aria-labelledby="sv-proof-title">
  <span class="grain-layer" aria-hidden="true"></span>
  <div class="container">
    <div class="section-head reveal-up">
      <span class="eyebrow">Proof</span>
      <h2 id="sv-proof-title">How can you check Rock'n G Plumbing before you hire?</h2>
      <p class="answer-block">You can check Rock'n G Plumbing before you hire by reading its Google reviews and looking up license M-44822 with the Texas State Board of Plumbing Examiners. Rock'n G is rated <?php echo h(number_format((float) $gbp['rating'], 1)); ?> out of 5 from <?php echo (int) $gbp['reviews_count']; ?> Google reviews.</p>
    </div>
    <div class="sv-proof__row">
      <div class="sv-proof__card reveal-left">
        <h3>Google reviews</h3>
        <p>The reviews on Rock'n G Plumbing's Google Business Profile are posted by customers. If Rock'n G has done any of the work on this page for you, a review helps the next customer in Tom Green County.</p>
        <div class="actions">
          <a class="btn btn-accent" href="<?php echo h($gbp['profile_url']); ?>" target="_blank" rel="noopener">Read reviews on Google</a>
          <a class="btn btn-outline-white" href="<?php echo h($gbp['review_url']); ?>" target="_blank" rel="noopener">Leave a review</a>
        </div>
      </div>
      <div class="sv-proof__card reveal-right">
        <h3>License on record</h3>
        <dl>
          <dt>License</dt><dd><?php echo h($license['label']); ?></dd>
          <dt>Holder</dt><dd><?php echo h($license['holder']); ?></dd>
          <dt>Role</dt><dd><?php echo h($license['role']); ?></dd>
          <dt>Issued by</dt><dd><?php echo h($license['issuer']); ?></dd>
          <dt>Current through</dt><dd><?php echo h(date('F j, Y', strtotime($license['expires']))); ?></dd>
        </dl>
      </div>
    </div>
    <?php
    require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/google-reviews.php';
    $svReviews = p1_google_reviews($slug, ['limit' => 3, 'heading' => '', 'summary' => false]);
    if ($svReviews !== ''): ?>
    <div class="sv-proof__reviews"><?php echo $svReviews; ?></div>
    <?php endif; ?>
  </div>
</section>

<!-- ============ FINAL CTA ============ -->
<section class="closing-cta texture-grain edge-curve-top sv-cta" aria-labelledby="sv-cta-title">
  <span class="grain-layer" aria-hidden="true"></span>
  <span class="floating-ring sv-cta__ring" aria-hidden="true"></span>
  <div class="container">
    <div class="reveal-left">
      <span class="eyebrow">Free estimates</span>
      <h2 id="sv-cta-title">Not sure which plumbing service you need?</h2>
      <p>Describe what is going on when you call Rock'n G Plumbing at <?php echo h($phoneFmt); ?>, <?php echo h($weekday[0]); ?> <?php echo h($weekday[1]); ?>, or put it in the estimate form. You do not need to know the name of the service, just what you are seeing and where.</p>
    </div>
    <div class="actions reveal-right">
      <a class="btn btn-accent btn-lg" href="/contact/" data-open-estimate>Get a Free Estimate</a>
      <a class="link-call" href="<?php echo h($telHref); ?>"><?php echo icon('phone', 18); ?>Call <?php echo h($phoneFmt); ?></a>
    </div>
  </div>
</section>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>
