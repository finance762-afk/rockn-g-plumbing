<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/functions.php';
?>
<?php
/**
 * services/gas-line-services/index.php — Gas Line Installation & Repair (Phase 4, premium editorial layout).
 * Every business fact traces to build-plan.json / includes/config.php. How gas piping is tested, permitted and turned
 * back on is written as general knowledge, never as a Rock'n G promise; the open questions (permits, pressure test,
 * utility relight, pipe materials) are in /content-confirmations.json.
 * SEO brief: no FAQ section/schema, no emergency or 24-hour wording.
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/service-cards.php';

$svc         = serviceBySlug('gas-line-services');
$pageType    = 'service';
$serviceSlug = $svc['slug'];
$pageTitle       = "Gas Line Plumber in San Angelo, TX | Rock'n G";
$pageDescription = "Gas line plumber in San Angelo, TX • New lines, repairs & extensions for appliances • Master Plumber M-44822, free estimates. Call (325) 650-7000.";
$canonicalUrl    = $siteUrl . '/services/gas-line-services/';
$currentPage     = 'services/gas-line-services';
$breadcrumbs     = [['name' => 'Services', 'url' => '/services/'], ['name' => $svc['name'], 'url' => '/services/gas-line-services/']];

$heroImage   = 'rockn-gas-line-generator';
$heroAlt     = 'Gas line run along the brick wall of a single-story home to a standby generator on a concrete pad';
$heroPreload = ['srcset' => imageVariantSrcset($heroImage, 'avif'), 'sizes' => '100vw'];
$ogImage     = '/assets/images/' . $heroImage . '.jpg';

$telHref  = 'tel:' . $phoneE164;
$phoneFmt = formatPhone($phone);
$weekday  = hoursGroups($hours)[0];      // ['Mon–Fri', '8:00 AM – 6:00 PM']

// What runs on gas — general household knowledge, each tile says why the gas line matters to it
$glAppliances = [
    ['icon' => 'droplets', 'name' => 'Water heater',
     'body' => 'Tank and tankless gas water heaters each need a gas supply sized for the burner.', 'link' => '/services/water-heaters/'],
    ['icon' => 'home',     'name' => 'Furnace',
     'body' => 'A gas furnace sits on its own branch line, usually in a closet, attic or garage.', 'link' => ''],
    ['icon' => 'flame',    'name' => 'Range or cooktop',
     'body' => 'Switching a kitchen from electric to gas cooking means running a new line to the range.', 'link' => ''],
    ['icon' => 'wind',     'name' => 'Clothes dryer',
     'body' => 'Gas dryers connect with a shutoff valve and flexible connector behind the machine.', 'link' => ''],
    ['icon' => 'building', 'name' => 'Standby generator',
     'body' => 'A whole-house generator needs a dedicated line run outside to its pad, like the job in the photo above.', 'link' => ''],
    ['icon' => 'sun',      'name' => 'Grill or fire pit',
     'body' => 'Outdoor kitchens, built-in grills and fire pits are fed by a line extended from the house.', 'link' => ''],
];

// Kinds of gas line work (build-plan service: Gas Line Installation & Repair)
$glWork = [
    ['icon' => 'plus',   'title' => 'New gas lines',
     'body' => 'A new run from the meter or an existing trunk line to an appliance, a standby generator or a new addition.'],
    ['icon' => 'wrench', 'title' => 'Gas line repair',
     'body' => 'Fixing a leaking fitting, a corroded or damaged section of pipe, or a line hit during digging or remodeling.'],
    ['icon' => 'route',  'title' => 'Extensions',
     'body' => 'Adding a branch to the gas piping you already have, for a range, dryer, grill or fire pit.'],
];

// Service schema (provider = the Plumber node head.php prints on every page)
$serviceNode = generateServiceSchema($svc);
$serviceNode['@id']         = absUrl(serviceUrl($svc)) . '#service-' . $svc['slug'];
$serviceNode['serviceType'] = 'Gas line installation and repair';
$serviceNode['description'] = "New gas line installation, gas line repair and gas line extensions for appliances, standby generators and outdoor kitchens in San Angelo, TX and nearby Tom Green County towns, by Rock'n G Plumbing under Texas Master Plumber License M-44822.";
$serviceNode['image']       = absUrl('/assets/images/' . $heroImage . '.jpg');
$serviceNode['hasOfferCatalog'] = [
    '@type' => 'OfferCatalog', 'name' => 'Gas line services',
    'itemListElement' => array_map(fn($w) => ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => $w['title']]], $glWork),
];
$serviceNode["@context"] = 'https://schema.org';   // explicit (generateServiceSchema sets it too)
$schemaMarkup = [$serviceNode];

ob_start();
?>
<style>
/* =====================================================================
   Gas Line Installation & Repair — page-only CSS (gl- prefix)
   Tokens from framework.css; no raw colors, shadows or spacing.
   ===================================================================== */
@property --gl-flow {
  syntax: '<length>';
  inherits: false;
  initial-value: 0px;
}

/* ---------- Hero: layered photo (C1) ---------- */
.gl-hero {
  padding-block-end: var(--space-16);
}
.gl-hero .hero-bg {
  overflow: hidden;
}
.gl-hero .hero-bg picture,
.gl-hero .hero-bg img {
  width: 100%;
  height: 100%;
}
.gl-hero .hero-bg img {
  object-fit: cover;
  object-position: 70% 60%;
  transform-origin: 80% 70%;
  animation: gl-zoom 24s var(--ease) both;
}
.gl-hero::before {
  content: "";
  position: absolute;
  inset: 0;
  z-index: -1;
  pointer-events: none;
  background:
    radial-gradient(ellipse 50% 60% at 0% 100%, color-mix(in srgb, var(--color-accent) 24%, transparent), transparent 70%),
    linear-gradient(0deg, color-mix(in srgb, var(--color-primary-deep) 70%, transparent) 0%, transparent 45%);
}
.gl-hero::after {
  content: "";
  position: absolute;
  inset: 0;
  z-index: -1;
  pointer-events: none;
  opacity: .2;
  mix-blend-mode: overlay;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='160' height='160'%3E%3Cfilter id='g'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.85' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23g)'/%3E%3C/svg%3E");
}
.gl-hero .breadcrumb {
  color: color-mix(in srgb, var(--color-surface) 72%, transparent);
  margin-block-end: var(--space-2);
}
.gl-hero .breadcrumb li {
  display: inline-flex;
  align-items: center;
}
.gl-hero .breadcrumb a:hover {
  color: var(--color-accent-bright);
}
.gl-hero .breadcrumb [aria-current="page"] {
  color: var(--color-surface);
}
.gl-hero .hero-title {
  max-width: 17ch;
  text-wrap: balance;
}
.gl-hero .hero-answer {
  max-width: 36rem;
}
.gl-hero .hero-chips li {
  backdrop-filter: blur(6px);
  -webkit-backdrop-filter: blur(6px);
}
.gl-hero .hero-form-open {
  background: var(--color-accent);
  color: var(--color-ink);
}
.gl-hero .hero-form-card {
  border-block-start: 4px solid var(--color-accent);
}
.gl-hero .hero-form-card h2 {
  text-wrap: balance;
}
.gl-hero .hero-form .consent {
  margin-block: var(--space-1);
}
@keyframes gl-zoom {
  from { transform: scale(1.12); }
  to   { transform: scale(1); }
}

/* ---------- Article ---------- */
.gl-article > section h2 {
  text-wrap: balance;
}
.gl-article .answer-block {
  font-size: var(--fs-lead);
  line-height: 1.55;
}

/* ---------- Signature 1: safety band (hazard-stripe edge) ---------- */
.gl-safety {
  position: relative;
  isolation: isolate;
  padding-block: var(--space-16);
  background: color-mix(in srgb, var(--color-warning) 14%, var(--color-paper));
}
.gl-safety::before,
.gl-safety::after {
  content: "";
  position: absolute;
  inset-inline: 0;
  height: 10px;
  background: repeating-linear-gradient(-45deg, var(--color-warning) 0 14px, var(--color-ink) 14px 28px);
  background-position: var(--gl-flow) 0;
  animation: gl-march 4s linear infinite;
}
.gl-safety::before {
  inset-block-start: 0;
}
.gl-safety::after {
  inset-block-end: 0;
}
@keyframes gl-march {
  to { --gl-flow: 40px; }
}
.gl-safety__grid {
  display: grid;
  grid-template-columns: minmax(0, 5fr) minmax(0, 7fr);
  gap: var(--space-10);
  align-items: center;
}
.gl-safety__head {
  display: grid;
  gap: var(--space-3);
}
.gl-safety__head .eyebrow {
  color: var(--color-ink);
}
.gl-safety__head .eyebrow::before {
  background: var(--color-warning);
}
.gl-safety__head .answer-block {
  background: var(--color-surface);
  border-inline-start-color: var(--color-warning);
}
.gl-safety__steps {
  list-style: none;
  margin: 0;
  padding: 0;
  display: grid;
  grid-template-columns: repeat(3, minmax(0, 1fr));
  gap: var(--space-4);
  counter-reset: gl-safe;
}
.gl-safety__steps li {
  counter-increment: gl-safe;
  position: relative;
  display: grid;
  align-content: start;
  gap: var(--space-2);
  padding: var(--space-6) var(--space-5) var(--space-5);
  border-radius: var(--radius-lg);
  background: var(--color-surface);
  border: 2px solid var(--color-ink);
  box-shadow: 6px 6px 0 var(--color-ink);
}
.gl-safety__steps li::before {
  content: counter(gl-safe);
  position: absolute;
  inset-block-start: calc(-1 * var(--space-4));
  inset-inline-start: var(--space-4);
  width: 34px;
  height: 34px;
  display: grid;
  place-items: center;
  border-radius: var(--radius-full);
  font-family: var(--font-accent);
  font-size: 1.1rem;
  color: var(--color-ink);
  background: var(--color-warning);
  border: 2px solid var(--color-ink);
}
.gl-safety__steps svg {
  color: var(--color-warning);
}
.gl-safety__steps b {
  font-family: var(--font-heading);
  font-size: 1.05rem;
  color: var(--color-ink);
}
.gl-safety__steps span {
  font-size: var(--fs-small);
  color: var(--color-ink-2);
}
.gl-safety__steps a {
  color: var(--color-primary);
  font-weight: 600;
}

/* ---------- Signature 2: gas manifold map ---------- */
.gl-map {
  background: var(--color-surface);
}
.gl-map__intro {
  display: grid;
  grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
  gap: var(--space-10);
  align-items: center;
  margin-block-end: var(--space-12);
}
.gl-map__intro .section-head {
  margin: 0;
}
.gl-map__photo {
  margin: 0;
  position: relative;
}
.gl-map__photo .img-reveal {
  aspect-ratio: 3 / 2;
  box-shadow: 14px 14px 0 var(--color-accent);
}
.gl-map__photo picture,
.gl-map__photo img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}
.gl-map__photo figcaption {
  margin-block-start: var(--space-4);
  font-size: var(--fs-small);
  color: var(--color-muted);
}
.gl-manifold {
  position: relative;
  padding-block-start: var(--space-12);
}
.gl-manifold__meter {
  position: absolute;
  inset-block-start: 0;
  inset-inline-start: 0;
  display: inline-flex;
  align-items: center;
  gap: var(--space-2);
  padding: var(--space-2) var(--space-4);
  border-radius: var(--radius-full);
  font-family: var(--font-accent);
  font-size: var(--fs-eyebrow);
  letter-spacing: .14em;
  text-transform: uppercase;
  color: var(--color-surface);
  background: var(--color-primary);
  z-index: 2;
}
.gl-manifold__meter svg {
  color: var(--color-accent-bright);
}
.gl-manifold::before {
  content: "";
  position: absolute;
  inset-block-start: calc(var(--space-12) - var(--space-6));
  inset-inline: var(--space-8) 0;
  height: 6px;
  border-radius: var(--radius-full);
  background: linear-gradient(90deg, var(--color-primary), var(--color-accent));
  transform-origin: left;
}
.gl-manifold__list {
  list-style: none;
  margin: 0;
  padding: 0;
  display: grid;
  grid-template-columns: repeat(6, minmax(0, 1fr));
  gap: var(--space-4);
}
.gl-manifold__item {
  position: relative;
  display: grid;
  align-content: start;
  gap: var(--space-2);
  padding: var(--space-5) var(--space-4);
  border-radius: var(--radius);
  background: var(--color-paper);
  border: 1px solid var(--color-line);
  transition: transform var(--transition), border-color var(--transition), box-shadow var(--transition);
}
.gl-manifold__item::before {
  content: "";
  position: absolute;
  inset-block-start: calc(-1 * var(--space-6));
  inset-inline-start: var(--space-6);
  width: 4px;
  height: var(--space-6);
  background: var(--color-accent);
}
.gl-manifold__item:hover {
  transform: translateY(-4px);
  border-color: var(--color-accent);
  box-shadow: var(--shadow);
}
.gl-manifold__item svg {
  color: var(--color-primary);
}
.gl-manifold__item h3 {
  font-size: 1rem;
  text-wrap: balance;
}
.gl-manifold__item p {
  margin: 0;
  font-size: var(--fs-small);
  color: var(--color-ink-2);
}
.gl-manifold__item a {
  font-size: var(--fs-small);
  font-weight: 600;
  color: var(--color-primary);
}
@supports (animation-timeline: view()) {
  .gl-manifold::before {
    animation: gl-pipe linear both;
    animation-timeline: view();
    animation-range: entry 10% cover 40%;
  }
  @keyframes gl-pipe {
    from { transform: scaleX(.08); }
    to   { transform: scaleX(1); }
  }
}

/* ---------- Expert: license strip ---------- */
.gl-expert {
  background: var(--color-paper-2);
}
.gl-expert__grid {
  display: grid;
  grid-template-columns: minmax(0, 2fr) minmax(0, 3fr);
  gap: var(--space-12);
  align-items: start;
}
.gl-expert__copy {
  display: grid;
  gap: var(--space-4);
}
.gl-expert__copy > p {
  margin: 0;
  color: var(--color-ink-2);
  max-width: var(--measure);
}
.gl-strip {
  display: grid;
  grid-template-columns: repeat(2, minmax(0, 1fr));
  gap: 1px;
  border-radius: var(--radius-lg);
  overflow: hidden;
  background: var(--color-line);
  border: 1px solid var(--color-line);
  box-shadow: var(--shadow-sm);
}
.gl-strip > div {
  display: grid;
  gap: var(--space-1);
  padding: var(--space-5);
  background: var(--color-surface);
}
.gl-strip dt {
  font-family: var(--font-accent);
  font-size: var(--fs-eyebrow);
  letter-spacing: .14em;
  text-transform: uppercase;
  color: var(--color-muted);
}
.gl-strip dd {
  margin: 0;
  font-family: var(--font-heading);
  font-weight: 700;
  color: var(--color-ink);
}
.gl-strip > div:first-child {
  grid-column: 1 / -1;
  background: var(--color-primary);
}
.gl-strip > div:first-child dt {
  color: var(--color-accent-bright);
}
.gl-strip > div:first-child dd {
  font-family: var(--font-accent);
  font-size: clamp(2.2rem, 1.6rem + 2.4vw, 3.4rem);
  line-height: 1;
  color: var(--color-surface);
}
.gl-expert__notes {
  margin: var(--space-6) 0 0;
  padding: 0;
  list-style: none;
  display: grid;
  gap: var(--space-3);
}
.gl-expert__notes li {
  display: flex;
  gap: var(--space-3);
  align-items: flex-start;
  color: var(--color-ink-2);
  font-size: var(--fs-small);
}
.gl-expert__notes svg {
  flex: 0 0 auto;
  color: var(--color-accent-dark);
  margin-block-start: var(--space-1);
}
.gl-expert__notes a {
  color: var(--color-primary);
  font-weight: 600;
}

/* ---------- Scope: work types + horizontal pipeline process ---------- */
.gl-scope {
  background: var(--color-surface);
}
.gl-work {
  display: grid;
  grid-template-columns: repeat(3, minmax(0, 1fr));
  gap: var(--space-4);
  margin-block: var(--space-8) var(--space-12);
}
.gl-work__card {
  display: grid;
  grid-template-columns: auto 1fr;
  gap: var(--space-2) var(--space-4);
  align-items: start;
  padding: var(--space-6);
  border-radius: var(--radius-lg);
  border-block-start: 4px solid var(--color-primary);
  box-shadow: var(--shadow-sm);
  transition: box-shadow var(--transition), border-color var(--transition);
}
.gl-work__card:hover {
  box-shadow: var(--shadow);
  border-block-start-color: var(--color-accent);
}
.gl-work__card svg {
  grid-row: span 2;
  color: var(--color-primary);
}
.gl-work__card h3 {
  font-size: var(--fs-h3);
}
.gl-work__card p {
  margin: 0;
  font-size: var(--fs-small);
  color: var(--color-ink-2);
}
.gl-pipeline__title {
  font-size: var(--fs-h3);
  margin-block-end: var(--space-6);
  text-wrap: balance;
}
.gl-pipeline {
  list-style: none;
  margin: 0;
  padding: 0;
  display: grid;
  grid-template-columns: repeat(4, minmax(0, 1fr));
  gap: var(--space-6);
  position: relative;
  counter-reset: gl-step;
}
.gl-pipeline::before {
  content: "";
  position: absolute;
  inset-block-start: 20px;
  inset-inline: 20px;
  height: 4px;
  border-radius: var(--radius-full);
  background: repeating-linear-gradient(90deg, var(--color-accent) 0 18px, color-mix(in srgb, var(--color-accent) 30%, transparent) 18px 30px);
}
.gl-pipeline li {
  counter-increment: gl-step;
  position: relative;
  display: grid;
  gap: var(--space-2);
  padding-block-start: var(--space-12);
}
.gl-pipeline li::before {
  content: counter(gl-step);
  position: absolute;
  inset-block-start: 0;
  inset-inline-start: 0;
  width: 44px;
  height: 44px;
  display: grid;
  place-items: center;
  border-radius: var(--radius-full);
  font-family: var(--font-accent);
  font-size: 1.25rem;
  color: var(--color-surface);
  background: var(--color-primary);
  border: 4px solid var(--color-surface);
  box-shadow: var(--shadow-sm);
}
.gl-pipeline b {
  font-family: var(--font-heading);
  color: var(--color-ink);
}
.gl-pipeline span {
  font-size: var(--fs-small);
  color: var(--color-ink-2);
}
.gl-scope__note {
  margin-block-start: var(--space-8);
  padding: var(--space-4) var(--space-5);
  border-radius: var(--radius);
  background: var(--color-paper);
  border: 1px dashed var(--color-line);
  font-size: var(--fs-small);
  color: var(--color-ink-2);
  max-width: 80ch;
}

/* ---------- Comparison table (three columns) ---------- */
.gl-compare {
  background: var(--color-paper);
}
.gl-compare__wrap {
  container-type: inline-size;
  container-name: gltable;
  margin-block-start: var(--space-8);
}
.gl-table {
  width: 100%;
  border-collapse: separate;
  border-spacing: var(--space-2) 0;
  font-size: var(--fs-small);
}
.gl-table caption {
  caption-side: bottom;
  text-align: start;
  padding-block-start: var(--space-4);
  color: var(--color-muted);
}
.gl-table th,
.gl-table td {
  padding: var(--space-4);
  text-align: start;
  vertical-align: top;
}
.gl-table thead th {
  font-family: var(--font-heading);
  font-size: 1.05rem;
  color: var(--color-surface);
  background: var(--color-primary);
  border-start-start-radius: var(--radius);
  border-start-end-radius: var(--radius);
}
.gl-table thead th:first-child {
  background: transparent;
}
.gl-table tbody th {
  font-family: var(--font-accent);
  font-size: var(--fs-eyebrow);
  letter-spacing: .12em;
  text-transform: uppercase;
  color: var(--color-muted);
  width: 18%;
  border-block-end: 1px solid var(--color-line);
}
.gl-table tbody td {
  background: var(--color-surface);
  color: var(--color-ink-2);
  border-block-end: 1px solid var(--color-line);
}
.gl-table tbody tr:last-child td {
  border-end-start-radius: var(--radius);
  border-end-end-radius: var(--radius);
}
.gl-table tbody tr:hover td {
  background: color-mix(in srgb, var(--color-accent) 7%, var(--color-surface));
}
@container gltable (max-width: 700px) {
  .gl-table tbody th {
    width: auto;
  }
  .gl-table caption {
    display: block;
    padding-block: 0 var(--space-3);
  }
  .gl-table thead {
    position: absolute;
    width: 1px;
    height: 1px;
    overflow: hidden;
    clip-path: inset(50%);
  }
  .gl-table,
  .gl-table tbody,
  .gl-table tr,
  .gl-table th,
  .gl-table td {
    display: block;
    width: auto;
  }
  .gl-table tbody tr {
    margin-block-end: var(--space-4);
    border-radius: var(--radius);
    overflow: hidden;
    border: 1px solid var(--color-line);
  }
  .gl-table tbody th {
    background: var(--color-paper-2);
    border-block-end: 0;
  }
  .gl-table tbody td {
    border-block-end: 0;
    padding-block: var(--space-2);
  }
  .gl-table tbody td::before {
    content: attr(data-label) ": ";
    font-weight: 700;
    color: var(--color-ink);
  }
}

/* ---------- Proof (dark, curved edge) ---------- */
.gl-proof .section-head {
  max-width: 64ch;
}
.gl-proof .answer-block {
  background: color-mix(in srgb, var(--color-surface) 6%, transparent);
  color: color-mix(in srgb, var(--color-surface) 88%, transparent);
}
.gl-proof__row {
  display: grid;
  grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
  gap: var(--space-6);
  margin-block-start: var(--space-8);
}
.gl-proof__card {
  display: grid;
  align-content: start;
  gap: var(--space-3);
  padding: var(--space-6);
  border-radius: var(--radius-lg);
  background: color-mix(in srgb, var(--color-surface) 7%, transparent);
  border: 1px solid color-mix(in srgb, var(--color-surface) 16%, transparent);
}
.gl-proof__card h3 {
  color: var(--color-surface);
}
.gl-proof__card p {
  margin: 0;
}
.gl-proof__card .actions {
  display: flex;
  flex-wrap: wrap;
  gap: var(--space-3);
}
.gl-proof__card dl {
  margin: 0;
  display: grid;
  grid-template-columns: auto 1fr;
  gap: var(--space-2) var(--space-4);
  font-size: var(--fs-small);
}
.gl-proof__card dt {
  color: color-mix(in srgb, var(--color-surface) 62%, transparent);
}
.gl-proof__card dd {
  margin: 0;
  color: var(--color-surface);
  font-weight: 600;
}
.gl-proof__reviews {
  margin-block-start: var(--space-8);
}
.gl-proof__reviews .p1gr-text {
  color: var(--color-ink-2);
}

/* ---------- Related ---------- */
.gl-related {
  background: var(--color-surface);
}
.gl-related .services-grid {
  grid-template-columns: repeat(3, minmax(0, 1fr));
}

/* ---------- Final CTA ---------- */
.gl-cta h2 {
  text-wrap: balance;
  max-width: 22ch;
}
.gl-cta .link-call {
  color: color-mix(in srgb, var(--color-surface) 88%, transparent);
}
.gl-cta .link-call svg {
  color: var(--color-accent-bright);
}
.gl-cta__ring {
  inset-inline-end: -120px;
  inset-block-start: -100px;
}
.gl-updated {
  margin-block-start: var(--space-6);
  font-size: var(--fs-small);
}

/* ---------- Responsive ---------- */
@media (max-width: 1100px) {
  .gl-manifold__list {
    grid-template-columns: repeat(3, minmax(0, 1fr));
    row-gap: var(--space-10);
  }
}
@media (max-width: 1000px) {
  .gl-safety__grid,
  .gl-map__intro,
  .gl-expert__grid {
    grid-template-columns: 1fr;
  }
  .gl-work {
    grid-template-columns: 1fr;
  }
  .gl-pipeline {
    grid-template-columns: repeat(2, minmax(0, 1fr));
  }
  .gl-pipeline::before {
    display: none;
  }
  .gl-related .services-grid {
    grid-template-columns: repeat(2, minmax(0, 1fr));
  }
}
@media (max-width: 760px) {
  .gl-safety__steps {
    grid-template-columns: 1fr;
    gap: var(--space-8);
  }
  .gl-manifold::before {
    display: none;
  }
  .gl-manifold__list {
    grid-template-columns: repeat(2, minmax(0, 1fr));
    row-gap: var(--space-4);
  }
  .gl-manifold__item::before {
    display: none;
  }
  .gl-proof__row {
    grid-template-columns: 1fr;
  }
}
@media (max-width: 560px) {
  .gl-manifold__list,
  .gl-pipeline,
  .gl-related .services-grid {
    grid-template-columns: 1fr;
  }
  .gl-strip {
    grid-template-columns: 1fr;
  }
  .gl-manifold__item {
    grid-template-columns: auto 1fr;
    column-gap: var(--space-3);
    padding: var(--space-4);
  }
  .gl-manifold__item svg {
    grid-row: span 3;
  }
  .gl-map__photo .img-reveal {
    box-shadow: 8px 8px 0 var(--color-accent);
  }
}
@media (prefers-reduced-motion: reduce) {
  .gl-hero .hero-bg img,
  .gl-safety::before,
  .gl-safety::after,
  .gl-manifold::before {
    animation: none;
  }
  .gl-manifold__item,
  .gl-work__card {
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
<section class="hero hero--photo hero--interior gl-hero" aria-labelledby="gl-h1">
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
            <li><a href="/services/">Services</a></li>
            <li class="breadcrumb-sep" aria-hidden="true">/</li>
            <li><span aria-current="page">Gas Lines</span></li>
          </ol>
        </nav>
        <span class="eyebrow">Gas Lines · <?php echo h($address['city'] . ', ' . $address['state']); ?></span>
        <h1 class="hero-title" id="gl-h1">Gas Line Installation &amp; Repair from a San Angelo Plumber</h1>
        <p class="hero-answer">Rock'n G Plumbing installs new gas lines, repairs damaged gas piping and extends lines to appliances and standby generators for homes and businesses in San Angelo, Christoval and nearby towns. The work runs under master plumber Eli Gomez's Texas license M-44822, and the estimate is free.</p>
        <div class="hero-actions">
          <button type="button" class="btn btn-primary btn-lg hero-form-open" data-open-estimate>Get a free estimate</button>
          <a class="link-call" href="<?php echo h($telHref); ?>"><?php echo icon('phone', 18); ?>or call <?php echo h($phoneFmt); ?></a>
        </div>
        <ul class="hero-chips">
          <li><?php echo icon('badge-check', 16); ?>Texas Master Plumber <?php echo h($license['number']); ?></li>
          <li><?php echo icon('clipboard-list', 16); ?>Free estimates</li>
          <li><?php echo icon('shield-check', 16); ?>Military discounts</li>
        </ul>
      </div>

      <aside class="hero-form-card" id="estimate-form" aria-labelledby="hero-form-title">
        <h2 id="hero-form-title">Need a gas line run or repaired?</h2>
        <p class="hero-form-tagline">Tell us what the line is for. No obligation.</p>
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
          <div class="form-row"><label class="sr-only" for="hero-service">Service</label><select id="hero-service" name="service"><?php echo p1_service_options($svc['name']); ?></select></div>
          <label class="consent" for="hero-email-opt"><input id="hero-email-opt" type="checkbox" name="email_opt_in" value="yes"><span><strong>Email (optional):</strong> I agree to receive emails from <?php echo h($siteName); ?>. Unsubscribe any time.</span></label>
          <label class="consent" for="hero-sms-opt"><input id="hero-sms-opt" type="checkbox" name="sms_opt_in" value="yes"><span><strong>Texts (optional):</strong> I agree to receive texts from <?php echo h($siteName); ?> about my request. Consent is not a condition of purchase. Message and data rates may apply. Reply STOP to unsubscribe.</span></label>
          <?php echo p1_consent_row('hero'); ?>
          <button type="submit" class="btn btn-primary btn-block">Get my free estimate</button>
        </form>
      </aside>
    </div>
  </div>
</section>

<article class="gl-article" aria-labelledby="gl-h1">

  <!-- ============ SIGNATURE 1: SAFETY FIRST ============ -->
  <section class="gl-safety" aria-labelledby="gl-safety-title">
    <div class="container">
      <div class="gl-safety__grid">
        <div class="gl-safety__head reveal-left">
          <span class="eyebrow">Safety first</span>
          <h2 id="gl-safety-title">What should you do if you smell gas in your home?</h2>
          <p class="answer-block">If you smell gas, get everyone out of the building first, then call 911 or your gas utility from outside. Don't flip switches, light anything or start a car in the garage on the way out. Repairs come after the utility says it is safe.</p>
        </div>
        <ol class="gl-safety__steps">
          <li class="reveal-up reveal-delay-1"><?php echo icon('footprints', 24); ?><b>Leave the building</b><span>Natural gas carries an added rotten-egg odor so a leak can be noticed. If you smell it, walk everyone outside and leave the door open behind you.</span></li>
          <li class="reveal-up reveal-delay-2"><?php echo icon('phone', 24); ?><b>Call from outside</b><span>Phone 911 or your gas utility once you are well away from the house. A phone, light switch or doorbell inside can spark.</span></li>
          <li class="reveal-up reveal-delay-3"><?php echo icon('wrench', 24); ?><b>Then fix the line</b><span>Once the area is safe, call Rock'n G at <a href="<?php echo h($telHref); ?>"><?php echo h($phoneFmt); ?></a> to find and repair the damaged pipe or fitting.</span></li>
        </ol>
      </div>
    </div>
  </section>

  <!-- ============ SIGNATURE 2: WHAT RUNS ON GAS ============ -->
  <section class="section gl-map" aria-labelledby="gl-map-title">
    <div class="container-wide">
      <div class="gl-map__intro">
        <div class="section-head reveal-left">
          <span class="eyebrow">Your gas system</span>
          <h2 id="gl-map-title">Which appliances in a San Angelo home run on gas?</h2>
          <p class="answer-block">In many San Angelo homes, gas feeds the water heater, the furnace, the range and the clothes dryer, and more homeowners now add standby generators and outdoor grills. Each appliance runs off a branch of the same gas piping, so adding one means checking that the line can supply it.</p>
        </div>
        <figure class="gl-map__photo reveal-right">
          <div class="img-reveal">
            <?php echo pictureTag('rockn-kitchen-remodel', 'Finished kitchen with a gas cooktop on the stone counter, a farmhouse sink in the island and wood cabinets', '(max-width: 1000px) 90vw, 560px'); ?>
          </div>
          <figcaption>A finished kitchen remodel with a gas cooktop. Moving or adding a range during a remodel usually means changing the gas piping behind the wall.</figcaption>
        </figure>
      </div>

      <div class="gl-manifold">
        <span class="gl-manifold__meter"><?php echo icon('flame', 16); ?>From the meter</span>
        <ul class="gl-manifold__list">
          <?php foreach ($glAppliances as $i => $app): ?>
          <li class="gl-manifold__item reveal-up reveal-delay-<?php echo ($i % 3) + 1; ?>">
            <?php echo icon($app['icon'], 24); ?>
            <h3><?php echo h($app['name']); ?></h3>
            <p><?php echo h($app['body']); ?></p>
            <?php if ($app['link'] !== ''): ?><a href="<?php echo h($app['link']); ?>">Water heater repair and installation</a><?php endif; ?>
          </li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>
  </section>

  <!-- ============ EXPERT POSITIONING ============ -->
  <section class="section edge-curve-top gl-expert" aria-labelledby="gl-expert-title">
    <div class="container">
      <div class="gl-expert__grid">
        <div class="gl-expert__copy reveal-left">
          <span class="eyebrow">Who does the work</span>
          <h2 id="gl-expert-title">Who should work on gas piping in a San Angelo home?</h2>
          <p class="answer-block">Gas piping should be worked on by a licensed plumber, because in Texas fuel gas piping falls under the state's plumbing license law. Rock'n G Plumbing's gas work is done under Eli Gomez's Texas Master Plumber License M-44822, issued by the Texas State Board of Plumbing Examiners.</p>
          <p>Plumbing is the Gomez family's trade, three generations deep, and Rock'n G has 20+ years of experience on residential, commercial and industrial jobs around Tom Green County.</p>
        </div>
        <div class="reveal-right">
          <dl class="gl-strip">
            <div><dt>Texas Master Plumber License</dt><dd><?php echo h($license['number']); ?></dd></div>
            <div><dt>Responsible Master Plumber</dt><dd><?php echo h($license['holder']); ?></dd></div>
            <div><dt>Issued by</dt><dd>Texas State Board of Plumbing Examiners</dd></div>
          </dl>
          <ul class="gl-expert__notes">
            <li><?php echo icon('search', 18); ?><span>Search M-44822 on the state board's license lookup before any gas work starts in your home, and ask the same of anyone else you call.</span></li>
            <li><?php echo icon('droplets', 18); ?><span>Adding a gas or tankless water heater? The line size matters as much as the heater. See <a href="/services/water-heaters/">water heater repair and installation</a>.</span></li>
            <li><?php echo icon('hammer', 18); ?><span>Planning a kitchen remodel or new build? Gas runs are part of the rough-in for <a href="/services/new-construction-remodel-plumbing/">new construction and remodel plumbing</a>.</span></li>
          </ul>
        </div>
      </div>
    </div>
  </section>

  <!-- ============ SERVICE BREAKDOWN + PROCESS ============ -->
  <section class="section gl-scope" aria-labelledby="gl-scope-title">
    <div class="container">
      <div class="section-head reveal-up">
        <span class="eyebrow">What's included</span>
        <h2 id="gl-scope-title">What gas line work does Rock'n G Plumbing do?</h2>
        <p class="answer-block">Rock'n G Plumbing does three kinds of gas line work: running new gas lines, repairing damaged or leaking gas piping, and extending existing lines to new appliances. That covers jobs from a single dryer hookup to a dedicated line for a whole-house standby generator.</p>
      </div>
      <div class="gl-work">
        <?php foreach ($glWork as $i => $work): ?>
        <div class="gl-work__card card-tint-<?php echo $i + 1; ?> reveal-up reveal-delay-<?php echo $i + 1; ?>">
          <?php echo icon($work['icon'], 26); ?>
          <h3><?php echo h($work['title']); ?></h3>
          <p><?php echo h($work['body']); ?></p>
        </div>
        <?php endforeach; ?>
      </div>

      <h3 class="gl-pipeline__title reveal-up">How a Rock'n G gas line job runs</h3>
      <ol class="gl-pipeline">
        <li class="reveal-up reveal-delay-1"><b>Call or send the form</b><span>Reach the office at <?php echo h($phoneFmt); ?>, <?php echo h($weekday[0]); ?> <?php echo h($weekday[1]); ?>. Say what the line is for and where the appliance or generator will sit.</span></li>
        <li class="reveal-up reveal-delay-2"><b>Estimate at no charge</b><span>Rock'n G prices the run, the repair or the extension before any work starts.</span></li>
        <li class="reveal-up reveal-delay-3"><b>You decide</b><span>Go ahead when the plan and price make sense. Mention military service and ask how the discount applies.</span></li>
        <li class="reveal-up reveal-delay-4"><b>The work</b><span>The gas piping is installed or repaired under <?php echo h($ownerName); ?>'s Texas Master Plumber license.</span></li>
      </ol>
      <p class="gl-scope__note reveal-up">Good to know: new or repaired gas piping is normally pressure-tested for leaks before it goes into service, and permit, inspection and utility steps vary between the City of San Angelo, Tom Green County and the towns around it. Ask about those steps for your address when you get your estimate.</p>
    </div>
  </section>

  <!-- ============ COMPARISON ============ -->
  <section class="section edge-parallelogram-top gl-compare" aria-labelledby="gl-compare-title">
    <div class="container">
      <div class="section-head reveal-up">
        <span class="eyebrow">Compare</span>
        <h2 id="gl-compare-title">New line, repair or extension: which gas job do you need?</h2>
        <p class="answer-block">You need a repair when existing gas piping leaks or is damaged, an extension when you are adding an appliance near the current piping, and a new line for a far-off spot like a generator pad. The estimate confirms which one fits your gas line in San Angelo.</p>
      </div>
      <div class="gl-compare__wrap reveal-scale">
        <table class="gl-table">
          <caption>A general comparison of gas line jobs. The specifics depend on your meter, your existing piping and the appliance.</caption>
          <thead>
            <tr><th scope="col"><span class="sr-only">Question</span></th><th scope="col">New gas line</th><th scope="col">Gas line repair</th><th scope="col">Extending a line</th></tr>
          </thead>
          <tbody>
            <tr><th scope="row">When it fits</th><td data-label="New line">Nothing runs to the spot yet, or the existing line is too small</td><td data-label="Repair">A fitting or pipe section leaks, is corroded or was damaged</td><td data-label="Extension">Adding an appliance close to gas piping you already have</td></tr>
            <tr><th scope="row">Typical examples</th><td data-label="New line">Standby generator, detached shop, home addition</td><td data-label="Repair">Line hit while digging, leak found at a fitting, rusted outdoor pipe</td><td data-label="Extension">Gas range in place of electric, dryer, grill or fire pit</td></tr>
            <tr><th scope="row">What's involved</th><td data-label="New line">Sizing the line for the appliance, routing it, then testing before use</td><td data-label="Repair">Finding the leak, replacing the bad section, then testing again</td><td data-label="Extension">Tying a new branch into the piping and checking it can supply the load</td></tr>
            <tr><th scope="row">Have ready when you call</th><td data-label="New line">Where the appliance or generator will sit, and its model if known</td><td data-label="Repair">Where the smell or damage is, once the utility has made it safe</td><td data-label="Extension">The appliance you are adding and where it will go</td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </section>

  <!-- ============ PROOF ============ -->
  <section class="section on-dark edge-wave-top gl-proof" aria-labelledby="gl-proof-title">
    <span class="grain-layer" aria-hidden="true"></span>
    <div class="container">
      <div class="section-head reveal-up">
        <span class="eyebrow">Proof</span>
        <h2 id="gl-proof-title">How can you vet a gas plumber in San Angelo before hiring one?</h2>
        <p class="answer-block">You can vet a gas plumber by checking the license number with the Texas State Board of Plumbing Examiners and reading reviews from past customers. Rock'n G Plumbing works under license M-44822 and is rated <?php echo h(number_format((float) $gbp['rating'], 1)); ?> out of 5 from <?php echo (int) $gbp['reviews_count']; ?> Google reviews.</p>
      </div>
      <div class="gl-proof__row">
        <div class="gl-proof__card reveal-left">
          <h3>Google reviews</h3>
          <p>Customers write the reviews on Rock'n G Plumbing's Google Business Profile. If Rock'n G ran or repaired a gas line for you, a short review helps your neighbors choose a plumber.</p>
          <div class="actions">
            <a class="btn btn-accent" href="<?php echo h($gbp['profile_url']); ?>" target="_blank" rel="noopener">Read reviews on Google</a>
            <a class="btn btn-outline-white" href="<?php echo h($gbp['review_url']); ?>" target="_blank" rel="noopener">Leave a review</a>
          </div>
        </div>
        <div class="gl-proof__card reveal-right">
          <h3>License on record</h3>
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
      $glReviews = p1_google_reviews($slug, ['limit' => 3, 'heading' => '', 'summary' => false]);
      if ($glReviews !== ''): ?>
      <div class="gl-proof__reviews"><?php echo $glReviews; ?></div>
      <?php endif; ?>
    </div>
  </section>

  <!-- ============ RELATED SERVICES (required services-grid) ============ -->
  <section class="section gl-related" aria-label="Related plumbing services">
    <div class="container">
      <div class="section-title reveal-up">
        <span class="eyebrow-label">What We Do</span>
        <h2>Which other <span class="text-accent">plumbing jobs</span> connect to gas line work?</h2>
        <p class="hero-answer">Gas line jobs often sit next to three others. Gas and tankless water heaters depend on a properly sized line, remodels and new builds need gas runs planned at rough-in, and a dig to reach buried piping is the same kind of work as finding a hidden water leak. Rock'n G Plumbing handles each of them.</p>
        <span class="section-subtitle">Water heaters · Remodels · Leaks</span>
        <p class="prose">See every <a href="/services/">plumbing service Rock'n G offers around San Angelo</a>.</p>
      </div>
      <div class="services-grid">
        <?php echo renderServiceCards(['water-heaters', 'new-construction-remodel-plumbing', 'leak-detection-repair'], '(max-width: 560px) 100vw, (max-width: 1000px) 50vw, 380px'); ?>
      </div>
    </div>
  </section>

  <!-- ============ FINAL CTA ============ -->
  <section class="closing-cta texture-grain slant-top gl-cta" aria-labelledby="gl-cta-title">
    <span class="grain-layer" aria-hidden="true"></span>
    <span class="floating-ring gl-cta__ring" aria-hidden="true"></span>
    <div class="container">
      <div class="reveal-left">
        <span class="eyebrow">Plan the line</span>
        <h2 id="gl-cta-title">Ready for a free gas line estimate in San Angelo?</h2>
        <p>Call Rock'n G Plumbing at <?php echo h($phoneFmt); ?>, <?php echo h($weekday[0]); ?> <?php echo h($weekday[1]); ?>, or send the form and the office will follow up during business hours. Tell us what the line will feed and where, and the estimate can cover the full run.</p>
        <p class="gl-updated">Last updated: <?php echo date('F Y'); ?></p>
      </div>
      <div class="actions reveal-right">
        <a class="btn btn-accent btn-lg" href="/contact/" data-open-estimate>Get a Free Gas Line Estimate</a>
        <a class="link-call" href="<?php echo h($telHref); ?>"><?php echo icon('phone', 18); ?>Call <?php echo h($phoneFmt); ?></a>
      </div>
    </div>
  </section>

</article>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>
