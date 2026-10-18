<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/functions.php';
?>
<?php
/**
 * services/leak-detection-repair/index.php — Leak Detection & Slab Leak Repair (Phase 4, premium editorial layout).
 * Every business fact traces to build-plan.json / includes/config.php ("water line repair/replacement"). How leaks
 * show up and the repair options are written as general trade knowledge, never as a Rock'n G promise. Facts this page
 * wanted but intake did not supply (detection tools, floor/yard restoration, pipe materials, prices, insurance-claim
 * help) are in /content-confirmations.json. SEO brief: no FAQ section/schema, no emergency / 24-hour wording.
 * The self-test checklist is pure CSS (:has + counters) — no JavaScript.
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/service-cards.php';

$svc         = serviceBySlug('leak-detection-repair');
$pageType    = 'service';
$serviceSlug = $svc['slug'];
$pageTitle       = "Leak Detection Plumber in San Angelo, TX | Rock'n G";
$pageDescription = "Leak detection plumber in San Angelo, TX • Hidden & slab leaks found, water lines repaired • Master Plumber M-44822, free estimates. Call (325) 650-7000.";
$canonicalUrl    = $siteUrl . '/services/leak-detection-repair/';
$currentPage     = 'services/leak-detection-repair';
$breadcrumbs     = [['name' => 'Services', 'url' => '/services/'], ['name' => $svc['name'], 'url' => '/services/leak-detection-repair/']];

$heroImage   = 'rockn-pipe-through-slab';
$heroAlt     = "White PVC pipe running down into a trench cut through a concrete-edged opening in a home's floor";
$heroPreload = ['srcset' => imageVariantSrcset($heroImage, 'avif'), 'sizes' => '100vw'];
$ogImage     = '/assets/images/' . $heroImage . '.jpg';

$telHref  = 'tel:' . $phoneE164;
$phoneFmt = formatPhone($phone);
$weekday  = hoursGroups($hours)[0];      // ['Mon–Fri', '8:00 AM – 6:00 PM']
$licenseExpires = date('F j, Y', strtotime($license['expires']));

// Where hidden leaks usually are (general knowledge)
$ldPlaces = [
    ['icon' => 'house',  'title' => 'Inside walls and ceilings',
     'body'  => 'Supply lines to sinks, showers and the water heater run through walls. A slow drip there shows up as a stain, bubbling paint or soft drywall.'],
    ['icon' => 'layers', 'title' => 'Under the slab',
     'body'  => 'In a home built on a concrete slab, the water lines run underneath the concrete. A slab leak can run for a long time before water ever reaches the surface.'],
    ['icon' => 'trees',  'title' => 'The yard water line',
     'body'  => 'The line from the meter to the house is buried. A leak there can leave a soggy strip, a green patch in a dry yard, or a meter that never stops.'],
];

// Service schema (provider = the Plumber node head.php prints on every page)
$serviceNode = generateServiceSchema($svc);
$serviceNode['@id']         = absUrl(serviceUrl($svc)) . '#service-' . $svc['slug'];
$serviceNode['serviceType'] = 'Leak detection, slab leak repair and water line repair';
$serviceNode['description'] = "Hidden leak detection, slab leak repair and water line repair or replacement in San Angelo, TX and nearby Tom Green County towns, by Rock'n G Plumbing under Texas Master Plumber License M-44822.";
$serviceNode['image']       = absUrl('/assets/images/' . $heroImage . '.jpg');
$serviceNode['hasOfferCatalog'] = [
    '@type' => 'OfferCatalog', 'name' => 'Leak detection and repair services',
    'itemListElement' => array_map(fn($n) => ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => $n]],
        ['Leak detection', 'Slab leak repair', 'Water line repair', 'Water line replacement']),
];
$serviceNode["@context"] = 'https://schema.org';   // explicit (generateServiceSchema sets it too)
$schemaMarkup = [$serviceNode];

ob_start();
?>
<style>
/* =====================================================================
   Leak Detection & Slab Leak Repair — page-only CSS (ld- prefix)
   Tokens from framework.css; no raw colors, shadows or spacing.
   ===================================================================== */
@property --ld-ripple {
  syntax: '<length>';
  inherits: false;
  initial-value: 0px;
}

/* ---------- Hero: layered photo (C1) ---------- */
.ld-hero {
  padding-block-end: var(--space-16);
}
.ld-hero .hero-bg {
  overflow: hidden;
}
.ld-hero .hero-bg picture,
.ld-hero .hero-bg img {
  width: 100%;
  height: 100%;
}
.ld-hero .hero-bg img {
  object-fit: cover;
  object-position: 50% 60%;
  animation: ld-settle 24s var(--ease) both;
}
.ld-hero::before {
  content: "";
  position: absolute;
  inset: 0;
  z-index: -1;
  pointer-events: none;
  background:
    radial-gradient(circle at 78% 70%, color-mix(in srgb, var(--color-accent) 26%, transparent) 0, transparent 38%),
    linear-gradient(180deg, transparent 45%, color-mix(in srgb, var(--color-primary-deep) 82%, transparent) 100%);
}
.ld-hero::after {
  content: "";
  position: absolute;
  inset: 0;
  z-index: -1;
  pointer-events: none;
  opacity: .2;
  mix-blend-mode: overlay;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='160' height='160'%3E%3Cfilter id='g'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.85' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23g)'/%3E%3C/svg%3E");
}
.ld-hero .breadcrumb {
  color: color-mix(in srgb, var(--color-surface) 72%, transparent);
  margin-block-end: var(--space-2);
}
.ld-hero .breadcrumb li {
  display: inline-flex;
  align-items: center;
}
.ld-hero .breadcrumb a:hover {
  color: var(--color-accent-bright);
}
.ld-hero .breadcrumb [aria-current="page"] {
  color: var(--color-surface);
}
.ld-hero .hero-title {
  max-width: 19ch;
  text-wrap: balance;
}
.ld-hero .hero-answer {
  max-width: 36rem;
}
.ld-hero .hero-chips li {
  backdrop-filter: blur(6px);
  -webkit-backdrop-filter: blur(6px);
}
.ld-hero .hero-form-open {
  background: var(--color-accent);
  color: var(--color-ink);
}
.ld-hero .hero-form-card {
  border-block-start: 4px solid var(--color-accent-bright);
}
.ld-hero .hero-form-card h2 {
  text-wrap: balance;
}
.ld-hero .hero-form .consent {
  margin-block: var(--space-1);
}
@keyframes ld-settle {
  from { transform: scale(1.1) translateY(-2%); }
  to   { transform: scale(1) translateY(0); }
}

/* ---------- Article wrapper ---------- */
.ld-article > section h2 {
  text-wrap: balance;
}
.ld-article .answer-block {
  font-size: var(--fs-lead);
  line-height: 1.55;
}

/* ---------- Signature: hidden-leak self-test (CSS-only checklist) ---------- */
.ld-test {
  background: var(--color-paper);
}
.ld-test__grid {
  display: grid;
  grid-template-columns: minmax(0, 1fr) minmax(0, 1.5fr);
  gap: var(--space-12);
  align-items: start;
  counter-reset: ld-clue;
}
.ld-test__head {
  display: grid;
  gap: var(--space-4);
}
.ld-check {
  list-style: none;
  margin: 0;
  padding: 0;
  display: grid;
  grid-template-columns: repeat(2, minmax(0, 1fr));
  gap: var(--space-3);
}
.ld-check li {
  display: flex;
}
.ld-check li:first-child {
  grid-column: 1 / -1;
}
.ld-check li:has(input:checked) {
  counter-increment: ld-clue;
}
.ld-clue {
  position: relative;
  flex: 1;
  display: grid;
  grid-template-columns: auto 1fr;
  gap: var(--space-1) var(--space-4);
  align-items: start;
  padding: var(--space-5);
  border-radius: var(--radius-lg);
  background: var(--color-surface);
  border: 2px solid var(--color-line);
  box-shadow: var(--shadow-sm);
  cursor: pointer;
  transition: border-color var(--transition), box-shadow var(--transition), transform var(--transition);
}
.ld-clue:hover {
  transform: translateY(-2px);
  box-shadow: var(--shadow);
}
.ld-clue input {
  position: absolute;
  opacity: 0;
  width: 1px;
  height: 1px;
}
.ld-clue__box {
  grid-row: span 2;
  width: 30px;
  height: 30px;
  display: grid;
  place-items: center;
  border-radius: var(--radius-sm);
  border: 2px solid var(--color-secondary);
  background: var(--color-paper);
  color: transparent;
  transition: background-color var(--transition), border-color var(--transition), color var(--transition);
}
.ld-clue__box svg {
  width: 18px;
  height: 18px;
}
.ld-clue input:focus-visible + .ld-clue__box {
  outline: 3px solid var(--color-accent);
  outline-offset: 3px;
}
.ld-clue input:checked + .ld-clue__box {
  background: var(--color-accent);
  border-color: var(--color-accent-dark);
  color: var(--color-ink);
}
.ld-check li:has(input:checked) .ld-clue {
  border-color: var(--color-accent);
  background: color-mix(in srgb, var(--color-accent) 7%, var(--color-surface));
}
.ld-clue b {
  font-family: var(--font-heading);
  font-size: 1.05rem;
  color: var(--color-ink);
  text-wrap: balance;
}
.ld-clue__text {
  font-size: var(--fs-small);
  color: var(--color-ink-2);
}
.ld-clue__text a {
  color: var(--color-primary);
  font-weight: 600;
}
.ld-check li:first-child .ld-clue {
  background: var(--color-primary);
  border-color: var(--color-primary);
}
.ld-check li:first-child .ld-clue b {
  color: var(--color-surface);
  font-size: var(--fs-h3);
}
.ld-check li:first-child .ld-clue__text {
  color: color-mix(in srgb, var(--color-surface) 82%, transparent);
  font-size: var(--fs-body);
}
.ld-check li:first-child .ld-clue__box {
  background: transparent;
  border-color: var(--color-accent-bright);
}
.ld-check li:first-child:has(input:checked) .ld-clue {
  background: var(--color-primary-dark);
}
.ld-tag {
  display: inline-block;
  margin-inline-end: var(--space-2);
  padding: 0 var(--space-2);
  border-radius: var(--radius-sm);
  font-family: var(--font-accent);
  font-size: var(--fs-eyebrow);
  letter-spacing: .12em;
  text-transform: uppercase;
  color: var(--color-ink);
  background: var(--color-accent-bright);
  vertical-align: middle;
}
.ld-tally {
  grid-column: 2;
  display: grid;
  grid-template-columns: auto 1fr;
  gap: var(--space-4);
  align-items: center;
  padding: var(--space-5) var(--space-6);
  border-radius: var(--radius-lg);
  background: var(--color-dark);
  color: var(--color-surface);
  box-shadow: var(--shadow-lg);
}
.ld-tally__n {
  position: relative;
  width: 64px;
  height: 64px;
  display: grid;
  place-items: center;
  border-radius: var(--radius-full);
  font-family: var(--font-accent);
  font-size: 2rem;
  color: var(--color-ink);
  background: var(--color-accent-bright);
}
.ld-tally__n::after {
  content: counter(ld-clue);
}
.ld-tally__n::before {
  content: "";
  position: absolute;
  inset: calc(-1 * var(--ld-ripple));
  border-radius: var(--radius-full);
  border: 2px solid var(--color-accent-bright);
  opacity: .5;
  animation: ld-ripple 2.6s var(--ease) infinite;
}
@keyframes ld-ripple {
  from { --ld-ripple: 0px; opacity: .6; }
  to   { --ld-ripple: 14px; opacity: 0; }
}
.ld-tally p {
  margin: 0;
  color: color-mix(in srgb, var(--color-surface) 86%, transparent);
}
.ld-tally a {
  color: var(--color-accent-bright);
  font-weight: 700;
}
.ld-tally__msg {
  display: none;
}
.ld-tally__msg--none {
  display: block;
}
.ld-test__grid:has(input:checked) .ld-tally__msg--none {
  display: none;
}
.ld-test__grid:has(input:checked) .ld-tally__msg--one {
  display: block;
}

/* ---------- Scope: where leaks hide + process beside a photo stack ---------- */
.ld-scope {
  background: var(--color-surface);
}
.ld-places {
  display: grid;
  grid-template-columns: repeat(3, minmax(0, 1fr));
  gap: var(--space-4);
  margin-block: var(--space-8) var(--space-16);
}
.ld-place {
  position: relative;
  display: grid;
  gap: var(--space-3);
  align-content: start;
  padding: var(--space-6) var(--space-6) var(--space-8);
  border-radius: var(--radius-lg);
  overflow: hidden;
  isolation: isolate;
}
.ld-place::after {
  content: "";
  position: absolute;
  inset-inline: 0;
  inset-block-end: 0;
  height: 6px;
  z-index: -1;
  background: linear-gradient(90deg, var(--color-accent), color-mix(in srgb, var(--color-accent) 10%, transparent));
  transform: scaleX(.3);
  transform-origin: left;
  transition: transform var(--transition-slow);
}
.ld-place:hover::after {
  transform: scaleX(1);
}
.ld-place svg {
  color: var(--color-primary);
}
.ld-place h3 {
  font-size: var(--fs-h3);
}
.ld-place p {
  margin: 0;
  font-size: var(--fs-small);
  color: var(--color-ink-2);
}
.ld-work {
  display: grid;
  grid-template-columns: minmax(0, 1fr) minmax(0, 1.2fr);
  gap: var(--space-12);
  align-items: center;
}
.ld-stack {
  position: relative;
  aspect-ratio: 4 / 4.8;
  margin: 0;
}
.ld-stack__main {
  position: absolute;
  inset: 0 18% 10% 0;
  border-radius: var(--radius-lg);
  overflow: hidden;
  box-shadow: var(--shadow-lg);
}
.ld-stack__second {
  position: absolute;
  inset-inline-end: 0;
  inset-block-end: 0;
  width: 52%;
  aspect-ratio: 6 / 5;
  border-radius: var(--radius);
  overflow: hidden;
  border: 6px solid var(--color-surface);
  box-shadow: var(--shadow-lg);
}
.ld-stack__main picture,
.ld-stack__main img,
.ld-stack__second picture,
.ld-stack__second img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}
.ld-stack figcaption {
  position: absolute;
  inset-block-start: var(--space-4);
  inset-inline-end: 0;
  max-width: 15rem;
  padding: var(--space-3) var(--space-4);
  border-radius: var(--radius);
  background: var(--color-surface);
  border-inline-start: 4px solid var(--color-accent);
  box-shadow: var(--shadow);
  font-size: var(--fs-small);
  color: var(--color-ink-2);
}
.ld-work__steps h3 {
  font-size: var(--fs-h2);
  text-wrap: balance;
  margin-block-end: var(--space-6);
}
.ld-steps {
  list-style: none;
  margin: 0;
  padding: 0;
  counter-reset: ld-step;
  display: grid;
}
.ld-steps li {
  counter-increment: ld-step;
  display: grid;
  grid-template-columns: 56px 1fr;
  gap: var(--space-1) var(--space-4);
  padding-block: var(--space-4);
  border-block-start: 1px solid var(--color-line);
}
.ld-steps li:last-child {
  border-block-end: 1px solid var(--color-line);
}
.ld-steps li::before {
  content: counter(ld-step, decimal-leading-zero);
  grid-row: span 2;
  font-family: var(--font-accent);
  font-size: 2rem;
  line-height: 1;
  color: var(--color-accent-dark);
}
.ld-steps b {
  font-family: var(--font-heading);
  color: var(--color-ink);
}
.ld-steps span {
  font-size: var(--fs-small);
  color: var(--color-ink-2);
}

/* ---------- Expert positioning (dark) + license stamp ---------- */
.ld-expert .ld-expert__grid {
  display: grid;
  grid-template-columns: minmax(0, 1.5fr) minmax(0, 1fr);
  gap: var(--space-12);
  align-items: center;
}
.ld-expert__copy {
  display: grid;
  gap: var(--space-4);
}
.ld-expert .answer-block {
  background: color-mix(in srgb, var(--color-surface) 6%, transparent);
  color: color-mix(in srgb, var(--color-surface) 88%, transparent);
}
.ld-points {
  list-style: none;
  margin: 0;
  padding: 0;
  display: grid;
  grid-template-columns: repeat(3, minmax(0, 1fr));
  gap: var(--space-4);
}
.ld-points li {
  display: grid;
  gap: var(--space-2);
  align-content: start;
  padding-block-start: var(--space-3);
  border-block-start: 2px solid var(--color-accent-bright);
}
.ld-points b {
  font-family: var(--font-heading);
  color: var(--color-surface);
}
.ld-points span {
  font-size: var(--fs-small);
  color: color-mix(in srgb, var(--color-surface) 76%, transparent);
}
.ld-points a {
  color: var(--color-accent-bright);
  font-weight: 600;
}
.ld-stamp {
  justify-self: center;
  display: grid;
  gap: var(--space-2);
  justify-items: center;
  text-align: center;
  padding: var(--space-8) var(--space-6);
  width: min(100%, 20rem);
  border-radius: var(--radius-lg);
  border: 3px dashed color-mix(in srgb, var(--color-accent-bright) 70%, transparent);
  background: color-mix(in srgb, var(--color-surface) 5%, transparent);
  transform: rotate(-3deg);
  transition: transform var(--transition-slow);
}
.ld-stamp:hover {
  transform: rotate(0);
}
.ld-stamp svg {
  color: var(--color-accent-bright);
}
.ld-stamp small {
  font-family: var(--font-accent);
  font-size: var(--fs-eyebrow);
  letter-spacing: .16em;
  text-transform: uppercase;
  color: color-mix(in srgb, var(--color-surface) 70%, transparent);
}
.ld-stamp b {
  font-family: var(--font-accent);
  font-size: clamp(2.6rem, 2rem + 2vw, 3.6rem);
  line-height: 1;
  color: var(--color-surface);
}
.ld-stamp span {
  font-size: var(--fs-small);
  color: color-mix(in srgb, var(--color-surface) 80%, transparent);
}

/* ---------- Comparison table ---------- */
.ld-compare {
  background: var(--color-paper-2);
}
.ld-compare__wrap {
  container-type: inline-size;
  container-name: ldtable;
  margin-block-start: var(--space-8);
}
.ld-table {
  width: 100%;
  border-collapse: separate;
  border-spacing: var(--space-2) 0;
  font-size: var(--fs-small);
}
.ld-table caption {
  caption-side: bottom;
  text-align: start;
  padding-block-start: var(--space-4);
  color: var(--color-muted);
}
.ld-table th,
.ld-table td {
  padding: var(--space-4);
  text-align: start;
  vertical-align: top;
}
.ld-table thead th[scope="col"]:not(:first-child) {
  background: var(--color-primary);
  color: var(--color-surface);
  font-family: var(--font-heading);
  font-size: 1rem;
  border-start-start-radius: var(--radius);
  border-start-end-radius: var(--radius);
}
.ld-table tbody td {
  background: var(--color-surface);
  color: var(--color-ink-2);
  border-block-end: 1px solid var(--color-line);
}
.ld-table tbody tr:last-child td {
  border-end-start-radius: var(--radius);
  border-end-end-radius: var(--radius);
  border-block-end: 0;
  font-style: italic;
  color: var(--color-ink);
}
.ld-table tbody th {
  width: 18%;
  font-family: var(--font-heading);
  font-weight: 700;
  color: var(--color-ink);
}
.ld-table tbody tr:hover td {
  background: color-mix(in srgb, var(--color-accent) 6%, var(--color-surface));
}
@container ldtable (max-width: 680px) {
  .ld-table thead {
    position: absolute;
    width: 1px;
    height: 1px;
    overflow: hidden;
    clip-path: inset(50%);
  }
  .ld-table,
  .ld-table tbody,
  .ld-table tr,
  .ld-table th,
  .ld-table td {
    display: block;
    width: auto;
  }
  .ld-table tbody tr {
    margin-block-end: var(--space-4);
  }
  .ld-table tbody th {
    padding-block-end: var(--space-2);
  }
  .ld-table tbody td {
    padding-block: var(--space-2);
  }
  .ld-table tbody td::before {
    content: attr(data-label) ": ";
    font-weight: 700;
    color: var(--color-ink);
  }
}

/* ---------- Proof: one wide card ---------- */
.ld-proof {
  background: var(--color-surface);
}
.ld-proof__card {
  display: grid;
  grid-template-columns: minmax(0, 1.3fr) minmax(0, 1fr);
  gap: var(--space-8);
  margin-block-start: var(--space-8);
  padding: var(--space-8);
  border-radius: var(--radius-xl);
  background: var(--color-paper);
  border: 1px solid var(--color-line);
  box-shadow: var(--shadow);
}
.ld-proof__card h3 {
  margin-block-end: var(--space-2);
}
.ld-proof__card p {
  margin: 0 0 var(--space-4);
  color: var(--color-ink-2);
}
.ld-proof__card .actions {
  display: flex;
  flex-wrap: wrap;
  gap: var(--space-3);
}
.ld-proof__card dl {
  margin: 0;
  display: grid;
  grid-template-columns: auto 1fr;
  gap: var(--space-2) var(--space-4);
  font-size: var(--fs-small);
  padding-inline-start: var(--space-6);
  border-inline-start: 1px solid var(--color-line);
}
.ld-proof__card dt {
  color: var(--color-muted);
}
.ld-proof__card dd {
  margin: 0;
  font-weight: 600;
  color: var(--color-ink);
}
.ld-proof__reviews {
  margin-block-start: var(--space-8);
}
.ld-proof__reviews .p1gr-text {
  color: var(--color-ink-2);
}

/* ---------- Related services ---------- */
.ld-related {
  background: var(--color-paper);
}
.ld-related .services-grid {
  grid-template-columns: repeat(3, minmax(0, 1fr));
}
.ld-related .service-card-with-image h3 {
  text-wrap: balance;
}

/* ---------- Final CTA ---------- */
.ld-cta h2 {
  text-wrap: balance;
  max-width: 22ch;
}
.ld-cta .link-call {
  color: color-mix(in srgb, var(--color-surface) 88%, transparent);
}
.ld-cta .link-call svg {
  color: var(--color-accent-bright);
}
.ld-cta__ring {
  inset-inline-start: 40%;
  inset-block-end: -200px;
}
.ld-updated {
  margin-block-start: var(--space-6);
  font-size: var(--fs-small);
}

/* ---------- Responsive ---------- */
@media (max-width: 1000px) {
  .ld-test__grid {
    grid-template-columns: 1fr;
  }
  .ld-tally {
    grid-column: auto;
  }
  .ld-points {
    grid-template-columns: 1fr;
  }
  .ld-related .services-grid {
    grid-template-columns: repeat(2, minmax(0, 1fr));
  }
}
@media (max-width: 900px) {
  .ld-places,
  .ld-work,
  .ld-expert .ld-expert__grid,
  .ld-proof__card {
    grid-template-columns: 1fr;
  }
  .ld-stack {
    aspect-ratio: 4 / 4;
    max-width: 34rem;
  }
  .ld-proof__card dl {
    padding-inline-start: 0;
    border-inline-start: 0;
    padding-block-start: var(--space-4);
    border-block-start: 1px solid var(--color-line);
  }
  .ld-stamp {
    transform: none;
  }
}
@media (max-width: 640px) {
  .ld-check {
    grid-template-columns: 1fr;
  }
  .ld-related .services-grid {
    grid-template-columns: 1fr;
  }
  .ld-proof__card {
    padding: var(--space-5);
  }
  .ld-stack figcaption {
    position: static;
    max-width: none;
    margin-block-start: var(--space-3);
  }
  .ld-stack {
    aspect-ratio: auto;
    display: grid;
  }
  .ld-stack__main {
    position: relative;
    inset: auto;
    aspect-ratio: 4 / 3;
  }
  .ld-stack__second {
    display: none;
  }
}
@media (prefers-reduced-motion: reduce) {
  .ld-hero .hero-bg img,
  .ld-tally__n::before {
    animation: none;
  }
  .ld-clue,
  .ld-place::after,
  .ld-stamp {
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
<section class="hero hero--photo hero--interior ld-hero" aria-labelledby="ld-h1">
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
            <li><span aria-current="page">Leak Detection</span></li>
          </ol>
        </nav>
        <span class="eyebrow">Leaks &amp; Water Lines · <?php echo h($address['city'] . ', ' . $address['state']); ?></span>
        <h1 class="hero-title" id="ld-h1">Leak Detection &amp; Slab Leak Repair from a San Angelo Plumber</h1>
        <p class="hero-answer">Rock'n G Plumbing tracks down hidden leaks, including leaks in water lines under a concrete slab, then repairs or replaces the line for homes and businesses in San Angelo, Christoval and nearby towns. Master plumber Eli Gomez's Texas license M-44822 covers the work, and the estimate is free.</p>
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
        <h2 id="hero-form-title">Need a hidden leak found?</h2>
        <p class="hero-form-tagline">Tell us what you're seeing or hearing. No obligation.</p>
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

<article class="ld-article" aria-labelledby="ld-h1">

  <!-- ============ SIGNATURE: HIDDEN-LEAK SELF-TEST ============ -->
  <section class="section slant-bottom ld-test" aria-labelledby="ld-test-title">
    <div class="container">
      <div class="ld-test__grid">
        <div class="ld-test__head reveal-left">
          <span class="eyebrow">Check before you call</span>
          <h2 id="ld-test-title">How can you check your house for a hidden water leak?</h2>
          <p class="answer-block">The quickest home check is the water meter test: turn off every faucet, appliance and irrigation line, then watch the meter for movement. If it still moves, water is leaving the pipes somewhere. Warm floor spots, running-water sounds and a bill that jumps are the other common clues.</p>
          <p>Tick the clues you have noticed. Nothing is sent anywhere; the list just helps you describe the problem when you call.</p>
        </div>
        <ol class="ld-check reveal-right">
          <li><label class="ld-clue" for="ld-clue-1"><input type="checkbox" id="ld-clue-1"><span class="ld-clue__box"><?php echo icon('check', 18); ?></span><b><span class="ld-tag">Start here</span>The meter keeps moving with everything off</b><span class="ld-clue__text">Shut off every faucet, the washer, dishwasher, ice maker and sprinklers. Read the meter, leave everything off for a while, and read it again. A number that climbed means water is escaping between the meter and your fixtures.</span></label></li>
          <li><label class="ld-clue" for="ld-clue-2"><input type="checkbox" id="ld-clue-2"><span class="ld-clue__box"><?php echo icon('check', 18); ?></span><b>A warm spot on the floor</b><span class="ld-clue__text">A hot-water line leaking under a slab can warm the floor above it. Bare feet on tile often notice it first.</span></label></li>
          <li><label class="ld-clue" for="ld-clue-3"><input type="checkbox" id="ld-clue-3"><span class="ld-clue__box"><?php echo icon('check', 18); ?></span><b>Running water with nothing on</b><span class="ld-clue__text">A hiss or trickle inside a wall or under the floor when every fixture in the house is off.</span></label></li>
          <li><label class="ld-clue" for="ld-clue-4"><input type="checkbox" id="ld-clue-4"><span class="ld-clue__box"><?php echo icon('check', 18); ?></span><b>A water bill that jumps</b><span class="ld-clue__text">Compare against the same month last year. Summer lawn watering in San Angelo can hide a leak, so a winter jump is a stronger clue.</span></label></li>
          <li><label class="ld-clue" for="ld-clue-5"><input type="checkbox" id="ld-clue-5"><span class="ld-clue__box"><?php echo icon('check', 18); ?></span><b>Damp baseboards or a musty smell</b><span class="ld-clue__text">Water from a slab or wall leak wicks into flooring and drywall long before it shows as a puddle.</span></label></li>
          <li><label class="ld-clue" for="ld-clue-6"><input type="checkbox" id="ld-clue-6"><span class="ld-clue__box"><?php echo icon('check', 18); ?></span><b>The water heater runs more than usual</b><span class="ld-clue__text">A leak on the hot side keeps the <a href="/services/water-heaters/">water heater</a> refilling and reheating, even when nobody is using hot water.</span></label></li>
        </ol>
        <div class="ld-tally" aria-live="polite">
          <span class="ld-tally__n" aria-hidden="true"></span>
          <div>
            <p class="ld-tally__msg ld-tally__msg--none">Tick any clue above that matches your house.</p>
            <p class="ld-tally__msg ld-tally__msg--one">One clue on its own can have an ordinary explanation, so run the meter test. Two or more together usually mean water is escaping: call <a href="<?php echo h($telHref); ?>"><?php echo h($phoneFmt); ?></a> to have it tracked down.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ============ SERVICE BREAKDOWN + PROCESS ============ -->
  <section class="section ld-scope" aria-labelledby="ld-scope-title">
    <div class="container">
      <div class="section-head reveal-up">
        <span class="eyebrow">What's included</span>
        <h2 id="ld-scope-title">What does leak detection and slab leak repair from Rock'n G include?</h2>
        <p class="answer-block">Rock'n G Plumbing's leak work has two halves: finding the leak, then fixing the line. Detection narrows down where water is escaping, whether in a wall, under the slab or in the yard. Repair then fixes that spot, reroutes the line around it, or replaces the water line.</p>
      </div>
      <div class="ld-places">
        <?php foreach ($ldPlaces as $i => $place): ?>
        <div class="ld-place card-tint-<?php echo $i + 1; ?> reveal-up reveal-delay-<?php echo $i + 1; ?>">
          <?php echo icon($place['icon'], 28); ?>
          <h3><?php echo h($place['title']); ?></h3>
          <p><?php echo h($place['body']); ?></p>
        </div>
        <?php endforeach; ?>
      </div>

      <div class="ld-work">
        <figure class="ld-stack reveal-left">
          <div class="ld-stack__main"><?php echo pictureTag('rockn-under-floor-drain-lines', "Opened-up floor framing with white PVC lines running down into a trench on a Rock'n G Plumbing job", '(max-width: 640px) 92vw, (max-width: 900px) 80vw, 420px'); ?></div>
          <div class="ld-stack__second"><?php echo pictureTag('rockn-trench-dig', "Rock'n G plumber working in an open trench to reach a buried line", '(max-width: 900px) 45vw, 260px'); ?></div>
          <figcaption>Under-floor and buried lines on Rock'n G jobs. Reaching pipe under a slab or a yard means opening it up, which is why pinning down the leak comes first.</figcaption>
        </figure>
        <div class="ld-work__steps reveal-right">
          <span class="eyebrow">Step by step</span>
          <h3>How a Rock'n G leak job runs</h3>
          <ol class="ld-steps">
            <li><b>Call or send the form</b><span><?php echo h($phoneFmt); ?>, <?php echo h($weekday[0]); ?> <?php echo h($weekday[1]); ?>. Mention what you noticed and whether the meter moved with everything off.</span></li>
            <li><b>Leak check</b><span>Rock'n G works to narrow down where the water is escaping before any floor, wall or yard is opened.</span></li>
            <li><b>Estimate at no charge</b><span>A spot repair, a reroute or a new water line, priced for what was found.</span></li>
            <li><b>You choose</b><span>Pick the fix that fits the house and the budget. Mention military service and ask about the discount.</span></li>
            <li><b>The work</b><span>The repair is done under <?php echo h($ownerName); ?>'s Texas Master Plumber license.</span></li>
          </ol>
        </div>
      </div>
    </div>
  </section>

  <!-- ============ EXPERT POSITIONING ============ -->
  <section class="section on-dark edge-wave-top ld-expert" aria-labelledby="ld-expert-title">
    <span class="grain-layer" aria-hidden="true"></span>
    <div class="container">
      <div class="ld-expert__grid">
        <div class="ld-expert__copy reveal-left">
          <span class="eyebrow">Who does the work</span>
          <h2 id="ld-expert-title">Why call a master plumber to find a slab leak?</h2>
          <p class="answer-block">Finding a slab leak means locating a pipe you cannot see under concrete before anyone breaks the floor, and fixing it means choosing a spot repair or a reroute. Rock'n G Plumbing does leak detection and water line repair under Eli Gomez's Texas Master Plumber License M-44822.</p>
          <ul class="ld-points">
            <li><b>A license you can look up</b><span>Search M-44822 with the Texas State Board of Plumbing Examiners to see <?php echo h($license['holder']); ?> listed as the <?php echo h($license['role']); ?>.</span></li>
            <li><b>Three generations</b><span>Plumbing is the Gomez family's trade, with 20+ years of experience across residential, commercial and industrial work.</span></li>
            <li><b>Drain or supply?</b><span>Water under a floor is not always a supply leak. A broken drain can do it too, and Rock'n G also handles <a href="/services/sewer-line-repair/">sewer line repair and camera inspection</a>.</span></li>
          </ul>
        </div>
        <div class="ld-stamp reveal-scale">
          <?php echo icon('badge-check', 34); ?>
          <small>Texas Master Plumber</small>
          <b><?php echo h($license['number']); ?></b>
          <span><?php echo h($license['holder']); ?><br>Current through <?php echo h($licenseExpires); ?></span>
        </div>
      </div>
    </div>
  </section>

  <!-- ============ COMPARISON ============ -->
  <section class="section ld-compare" aria-labelledby="ld-compare-title">
    <div class="container">
      <div class="section-head reveal-up">
        <span class="eyebrow">Compare the fixes</span>
        <h2 id="ld-compare-title">Spot repair, reroute or new water line: which fix makes sense?</h2>
        <p class="answer-block">A spot repair fixes one leak where it is found. A reroute abandons the leaking section and runs new pipe through walls or the attic instead, and a water line replacement swaps the whole run when the pipe is failing in several places.</p>
      </div>
      <div class="ld-compare__wrap reveal-scale">
        <table class="ld-table">
          <caption>General guidance. Which fix fits depends on where the leak is and the condition of the rest of the line.</caption>
          <thead>
            <tr><th scope="col"><span class="sr-only">Question</span></th><th scope="col">Spot repair</th><th scope="col">Reroute</th><th scope="col">Water line replacement</th></tr>
          </thead>
          <tbody>
            <tr><th scope="row">What happens</th><td data-label="Spot repair">Open the floor or ground at the leak and replace the damaged piece</td><td data-label="Reroute">Leave the leaking line in place and run new pipe a different way</td><td data-label="Replacement">Replace the full run of failing pipe</td></tr>
            <tr><th scope="row">What gets opened up</th><td data-label="Spot repair">Concrete or yard at one spot</td><td data-label="Reroute">Walls or attic instead of the slab</td><td data-label="Replacement">A trench or several openings along the run</td></tr>
            <tr><th scope="row">Usual fit</th><td data-label="Spot repair">One leak in otherwise sound pipe</td><td data-label="Reroute">A slab line that is hard to reach, or a second leak on the same run</td><td data-label="Replacement">Old pipe leaking in more than one place</td></tr>
            <tr><th scope="row">Question to ask</th><td data-label="Spot repair">How old is the rest of this line?</td><td data-label="Reroute">Where will the new line run?</td><td data-label="Replacement">Where does the new line start and end?</td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </section>

  <!-- ============ PROOF ============ -->
  <section class="section edge-curve-top ld-proof" aria-labelledby="ld-proof-title">
    <div class="container">
      <div class="section-head reveal-up">
        <span class="eyebrow">Check us out</span>
        <h2 id="ld-proof-title">How can you check Rock'n G Plumbing before anyone opens a floor?</h2>
        <p class="answer-block">Before anyone cuts into a slab, check Rock'n G Plumbing's Google reviews and look up license M-44822 with the Texas State Board of Plumbing Examiners. Rock'n G is rated <?php echo h(number_format((float) $gbp['rating'], 1)); ?> out of 5 from <?php echo (int) $gbp['reviews_count']; ?> Google reviews on its Google Business Profile.</p>
      </div>
      <div class="ld-proof__card reveal-up">
        <div>
          <h3>Google reviews</h3>
          <p>Customers write the reviews on Rock'n G Plumbing's Google profile. If Rock'n G found and fixed a leak at your place, a short review helps the next homeowner in San Angelo or Christoval.</p>
          <div class="actions">
            <a class="btn btn-primary" href="<?php echo h($gbp['profile_url']); ?>" target="_blank" rel="noopener">Read reviews on Google</a>
            <a class="btn btn-secondary" href="<?php echo h($gbp['review_url']); ?>" target="_blank" rel="noopener">Leave a review</a>
          </div>
        </div>
        <dl>
          <dt>License</dt><dd><?php echo h($license['label']); ?></dd>
          <dt>Holder</dt><dd><?php echo h($license['holder']); ?></dd>
          <dt>Role</dt><dd><?php echo h($license['role']); ?></dd>
          <dt>Issued by</dt><dd><?php echo h($license['issuer']); ?></dd>
        </dl>
      </div>
      <?php
      require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/google-reviews.php';
      $ldReviews = p1_google_reviews($slug, ['limit' => 3, 'heading' => '', 'summary' => false]);
      if ($ldReviews !== ''): ?>
      <div class="ld-proof__reviews"><?php echo $ldReviews; ?></div>
      <?php endif; ?>
    </div>
  </section>

  <!-- ============ RELATED SERVICES (required services-grid) ============ -->
  <section class="section ld-related" aria-label="Related plumbing services">
    <div class="container">
      <div class="section-title reveal-up">
        <span class="eyebrow-label">What We Do</span>
        <h2>Which other <span class="text-accent">plumbing services</span> go with a leak repair?</h2>
        <p class="hero-answer">A leak repair often touches nearby systems. A leak on the hot side keeps a water heater working overtime, water under a floor can come from a broken drain or sewer line rather than a supply pipe, and a slow drain can point to the same aging pipes. Rock'n G Plumbing handles water heaters, sewer lines and drain cleaning too.</p>
        <span class="section-subtitle">Water heaters · Sewer · Drains</span>
        <p class="prose">See every <a href="/services/">plumbing service Rock'n G offers in San Angelo</a>.</p>
      </div>
      <div class="services-grid">
        <?php echo renderServiceCards(['water-heaters', 'sewer-line-repair', 'drain-cleaning'], '(max-width: 640px) 100vw, (max-width: 1000px) 50vw, 380px'); ?>
      </div>
    </div>
  </section>

  <!-- ============ FINAL CTA ============ -->
  <section class="closing-cta texture-grain edge-torn-top ld-cta" aria-labelledby="ld-cta-title">
    <span class="grain-layer" aria-hidden="true"></span>
    <span class="floating-ring ld-cta__ring" aria-hidden="true"></span>
    <div class="container">
      <div class="reveal-left">
        <span class="eyebrow">Stop paying for lost water</span>
        <h2 id="ld-cta-title">Ready for a free leak detection estimate in San Angelo?</h2>
        <p>Call Rock'n G Plumbing at <?php echo h($phoneFmt); ?>, <?php echo h($weekday[0]); ?> <?php echo h($weekday[1]); ?>, or send the form and the office will follow up during business hours. If water is actively spreading, close the main shutoff valve first, then call.</p>
        <p class="ld-updated">Last updated: <?php echo date('F Y'); ?></p>
      </div>
      <div class="actions reveal-right">
        <a class="btn btn-accent btn-lg" href="/contact/" data-open-estimate>Get a Free Leak Detection Estimate</a>
        <a class="link-call" href="<?php echo h($telHref); ?>"><?php echo icon('phone', 18); ?>Call <?php echo h($phoneFmt); ?></a>
      </div>
    </div>
  </section>

</article>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>
