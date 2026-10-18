<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/functions.php';
?>
<?php
/**
 * services/new-construction-remodel-plumbing/index.php — New Construction & Remodel Plumbing (Phase 4, premium editorial).
 * Every business fact traces to build-plan.json / includes/config.php ("residential and commercial"; USP "Residential,
 * commercial and industrial"). Construction phases and inspection timing are written as general trade knowledge — the
 * page never promises that Rock'n G pulls permits, schedules inspections or coordinates with builders (asked in
 * /content-confirmations.json). No FAQ section (no recorded customer questions yet), no emergency wording.
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/service-cards.php';

$svc         = serviceBySlug('new-construction-remodel-plumbing');
$pageType    = 'service';
$serviceSlug = $svc['slug'];
$pageTitle       = "New Construction Plumber in San Angelo, TX | Rock'n G";
$pageDescription = "New construction & remodel plumber in San Angelo, TX • Residential, commercial & industrial • Master Plumber M-44822, free estimates. Call (325) 650-7000.";
$canonicalUrl    = $siteUrl . '/services/new-construction-remodel-plumbing/';
$currentPage     = 'services/new-construction-remodel-plumbing';
$breadcrumbs     = [['name' => 'Services', 'url' => '/services/'], ['name' => $svc['name'], 'url' => '/services/new-construction-remodel-plumbing/']];

$heroImage   = 'rockn-kitchen-remodel';
$heroAlt     = 'Finished kitchen with a farmhouse sink set in a granite island, a gas cooktop and wood cabinets';
$heroPreload = ['srcset' => imageVariantSrcset($heroImage, 'avif'), 'sizes' => '100vw'];
$ogImage     = '/assets/images/' . $heroImage . '.jpg';

$telHref  = 'tel:' . $phoneE164;
$phoneFmt = formatPhone($phone);
$weekday  = hoursGroups($hours)[0];      // ['Mon–Fri', '8:00 AM – 6:00 PM']

// Planning checkpoints (general remodel knowledge — the decisions that move pipe)
$ncChecks = [
    ['icon' => 'ruler',       'title' => 'Moving a sink, island or toilet',
     'body'  => 'A fixture that moves takes its drain and vent with it. On a slab home that means cutting concrete along the new route.'],
    ['icon' => 'house',       'title' => 'Adding a bathroom',
     'body'  => 'A new bathroom needs a drain with enough slope to reach the main line, a vent, and hot and cold supply sized for the extra fixtures.'],
    ['icon' => 'flame',       'title' => 'Gas range, cooktop or fireplace',
     'body'  => 'Switching an electric appliance to gas, or adding one, means running and testing a gas line before the cabinets and walls close up.'],
    ['icon' => 'droplets',    'title' => 'Water heater and water treatment',
     'body'  => 'A remodel is the cheapest time to move or upsize a water heater and to leave room and plumbing for a softener on San Angelo hard water.'],
];

// Signature: construction-phase timeline (general trade knowledge; inspection requirements vary by city and county)
$ncPhases = [
    ['icon' => 'layers',      'phase' => 'Underground',
     'what'  => 'Drain and water lines are laid in trenches inside the forms before the slab is poured, with cleanouts and stub-ups where fixtures will sit.',
     'check' => 'Commonly tested and inspected before the concrete goes in.'],
    ['icon' => 'hammer',      'phase' => 'Rough-in',
     'what'  => 'Once the framing is up, drain, waste and vent piping and the hot and cold supply lines are run through the walls and floors. Gas piping goes in at this stage too.',
     'check' => 'Piping is placed so it meets the fixtures exactly where the plans put them.'],
    ['icon' => 'building-2',  'phase' => 'Top-out',
     'what'  => 'Vents are carried up through the roof, tub and shower valves are set, and the whole system is pressure tested while the walls are still open.',
     'check' => 'Commonly inspected before insulation and drywall cover the pipe.'],
    ['icon' => 'check-circle','phase' => 'Trim & finish',
     'what'  => 'After paint and cabinets, sinks, faucets, toilets, the water heater and appliance connections are set, and the system is put into service.',
     'check' => 'Often the final plumbing inspection before move-in.'],
];

// Remodel on a slab: what moving a drain involves (general trade knowledge)
$ncSlabSteps = [
    ['title' => 'Mark the new route', 'body' => 'The new fixture location is tied back to the existing drain and vent, and the cut line is laid out on the floor.'],
    ['title' => 'Cut and trench', 'body' => 'The concrete is saw-cut and broken out, and the trench is dug down to the depth of the line it connects to.'],
    ['title' => 'Lay pipe with fall', 'body' => 'New drain pipe is set with steady downhill slope toward the main line, with the fittings and vent connection the fixture needs.'],
    ['title' => 'Test, inspect, patch', 'body' => 'The new section is tested, inspected where the jurisdiction requires it, then backfilled and the slab patched.'],
];

// Service schema (provider = the Plumber node head.php prints on every page)
$serviceNode = generateServiceSchema($svc);
$serviceNode['@id']         = absUrl(serviceUrl($svc)) . '#service-' . $svc['slug'];
$serviceNode['serviceType'] = 'New construction and remodel plumbing';
$serviceNode['description'] = "Plumbing for new homes, remodels and commercial build-outs in San Angelo, TX and nearby Tom Green County towns, from underground drain lines to finished fixtures, by Rock'n G Plumbing under Texas Master Plumber License M-44822.";
$serviceNode['image']       = absUrl('/assets/images/' . $heroImage . '.jpg');
$serviceNode['audience']    = ['@type' => 'Audience', 'audienceType' => 'Residential, commercial and industrial customers'];
$serviceNode['hasOfferCatalog'] = [
    '@type' => 'OfferCatalog', 'name' => 'Construction plumbing services',
    'itemListElement' => [
        ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'New construction plumbing']],
        ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Remodel plumbing']],
        ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Commercial plumbing']],
    ],
];
$serviceNode["@context"] = 'https://schema.org';   // explicit (generateServiceSchema sets it too)
$schemaMarkup = [$serviceNode];

ob_start();
?>
<style>
/* =====================================================================
   New Construction & Remodel Plumbing — page-only CSS (nc- prefix)
   Tokens from framework.css; no raw colors, shadows or spacing.
   ===================================================================== */
@property --nc-flow {
  syntax: '<length>';
  inherits: false;
  initial-value: 0px;
}

/* ---------- Hero: layered photo (C1) ---------- */
.nc-hero {
  padding-block-end: var(--space-16);
}
.nc-hero .hero-bg {
  overflow: hidden;
}
.nc-hero .hero-bg picture,
.nc-hero .hero-bg img {
  width: 100%;
  height: 100%;
}
.nc-hero .hero-bg img {
  object-fit: cover;
  object-position: 55% 60%;
  animation: nc-pan 28s var(--ease) both;
}
.nc-hero::before {
  content: "";
  position: absolute;
  inset: 0;
  z-index: -1;
  pointer-events: none;
  background:
    linear-gradient(90deg, color-mix(in srgb, var(--color-primary-deep) 55%, transparent) 0%, transparent 60%),
    radial-gradient(ellipse 50% 60% at 85% 100%, color-mix(in srgb, var(--color-accent) 24%, transparent), transparent 70%);
}
.nc-hero::after {
  content: "";
  position: absolute;
  inset: 0;
  z-index: -1;
  pointer-events: none;
  opacity: .18;
  mix-blend-mode: overlay;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='160' height='160'%3E%3Cfilter id='g'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.75' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23g)'/%3E%3C/svg%3E");
}
.nc-hero .breadcrumb {
  color: color-mix(in srgb, var(--color-surface) 72%, transparent);
  margin-block-end: var(--space-2);
}
.nc-hero .breadcrumb li {
  display: inline-flex;
  align-items: center;
}
.nc-hero .breadcrumb a:hover {
  color: var(--color-accent-bright);
}
.nc-hero .breadcrumb [aria-current="page"] {
  color: var(--color-surface);
}
.nc-hero .hero-title {
  max-width: 19ch;
  text-wrap: balance;
}
.nc-hero .hero-answer {
  max-width: 36rem;
}
.nc-hero .hero-chips li {
  backdrop-filter: blur(6px);
  -webkit-backdrop-filter: blur(6px);
}
.nc-hero .hero-form-open {
  background: var(--color-accent);
  color: var(--color-white);
}
.nc-hero .hero-form-card {
  border-block-start: 4px solid var(--color-accent);
}
.nc-hero .hero-form-card h2 {
  text-wrap: balance;
}
.nc-hero .hero-form .consent {
  margin-block: var(--space-1);
}
@keyframes nc-pan {
  from { transform: scale(1.12) translate(2%, 1%); }
  to   { transform: scale(1) translate(0, 0); }
}

/* ---------- Article wrapper ---------- */
.nc-article > section h2 {
  text-wrap: balance;
}
.nc-article .answer-block {
  font-size: var(--fs-lead);
  line-height: 1.55;
}

/* ---------- Planning: split intro + checkpoint rail ---------- */
.nc-plan {
  background: var(--color-paper);
}
.nc-plan__grid {
  display: grid;
  grid-template-columns: minmax(0, 2fr) minmax(0, 3fr);
  gap: var(--space-12);
  align-items: start;
}
.nc-plan__intro {
  position: sticky;
  inset-block-start: calc(var(--nav-height) + var(--space-6));
  display: grid;
  gap: var(--space-4);
}
.nc-plan__intro h2 {
  max-width: 20ch;
}
.nc-plan__intro > p:not(.answer-block) {
  margin: 0;
  color: var(--color-ink-2);
}
.nc-plan__intro a {
  color: var(--color-primary);
  font-weight: 600;
}
.nc-checks {
  list-style: none;
  margin: 0;
  padding: 0;
  display: grid;
  gap: var(--space-3);
}
.nc-check {
  position: relative;
  display: grid;
  grid-template-columns: auto 1fr;
  gap: var(--space-1) var(--space-5);
  padding: var(--space-6);
  border-radius: var(--radius-lg);
  background: var(--color-surface);
  border: 1px solid var(--color-line);
  box-shadow: var(--shadow-sm);
  transition: transform var(--transition), border-color var(--transition), box-shadow var(--transition);
}
.nc-check:hover {
  transform: translateX(6px);
  border-color: var(--color-accent);
  box-shadow: var(--shadow);
}
.nc-check__icon {
  grid-row: span 2;
  width: 52px;
  height: 52px;
  display: grid;
  place-items: center;
  border-radius: var(--radius);
  color: var(--color-primary);
  background: color-mix(in srgb, var(--color-accent) 16%, var(--color-surface));
  border: 1px dashed color-mix(in srgb, var(--color-accent-dark) 45%, transparent);
}
.nc-check h3 {
  font-size: var(--fs-h3);
  text-wrap: balance;
}
.nc-check p {
  margin: 0;
  font-size: var(--fs-small);
  color: var(--color-ink-2);
}

/* ---------- Signature: construction-phase timeline (dark, parallelogram edge) ---------- */
.nc-phases {
  isolation: isolate;
}
.nc-phases .section-head {
  max-width: 66ch;
}
.nc-phases .answer-block {
  background: color-mix(in srgb, var(--color-surface) 6%, transparent);
  color: color-mix(in srgb, var(--color-surface) 88%, transparent);
}
.nc-track {
  position: relative;
  list-style: none;
  margin: var(--space-12) 0 0;
  padding: 0;
  display: grid;
  grid-template-columns: repeat(4, minmax(0, 1fr));
  gap: var(--space-5);
  counter-reset: nc-phase;
}
.nc-track::before {
  content: "";
  position: absolute;
  inset-inline: var(--space-8);
  inset-block-start: 31px;
  height: 6px;
  border-radius: var(--radius-full);
  background:
    repeating-linear-gradient(90deg, var(--color-accent) 0 26px, color-mix(in srgb, var(--color-accent) 25%, transparent) 26px 40px);
  background-position: var(--nc-flow) 0;
  animation: nc-flow 2.4s linear infinite;
  z-index: 0;
}
@keyframes nc-flow {
  to { --nc-flow: 40px; }
}
.nc-phase {
  counter-increment: nc-phase;
  position: relative;
  z-index: 1;
  display: grid;
  align-content: start;
  gap: var(--space-3);
}
.nc-phase__node {
  width: 68px;
  height: 68px;
  display: grid;
  place-items: center;
  border-radius: var(--radius-full);
  color: var(--color-ink);
  background: var(--color-accent);
  border: 6px solid var(--color-dark);
  box-shadow: 0 0 0 2px var(--color-accent);
}
.nc-phase__card {
  display: grid;
  gap: var(--space-2);
  padding: var(--space-5);
  border-radius: var(--radius-lg);
  background: color-mix(in srgb, var(--color-surface) 6%, transparent);
  border: 1px solid color-mix(in srgb, var(--color-surface) 14%, transparent);
  transition: background-color var(--transition), border-color var(--transition);
}
.nc-phase:hover .nc-phase__card {
  background: color-mix(in srgb, var(--color-surface) 10%, transparent);
  border-color: var(--color-accent);
}
.nc-phase__card small {
  font-family: var(--font-accent);
  font-size: var(--fs-eyebrow);
  letter-spacing: .14em;
  text-transform: uppercase;
  color: var(--color-accent-bright);
}
.nc-phase__card small::before {
  content: "Phase " counter(nc-phase) " · ";
}
.nc-phase__card h3 {
  color: var(--color-surface);
  font-size: var(--fs-h3);
}
.nc-phase__card p {
  margin: 0;
  font-size: var(--fs-small);
}
.nc-phase__check {
  display: flex;
  gap: var(--space-2);
  align-items: flex-start;
  margin-block-start: var(--space-1);
  padding-block-start: var(--space-3);
  border-block-start: 1px dashed color-mix(in srgb, var(--color-surface) 22%, transparent);
  font-size: var(--fs-small);
  color: color-mix(in srgb, var(--color-surface) 78%, transparent);
}
.nc-phase__check svg {
  flex: 0 0 auto;
  color: var(--color-accent-bright);
  margin-block-start: var(--space-1);
}
.nc-phases__note {
  margin: var(--space-8) 0 0;
  max-width: 72ch;
  font-size: var(--fs-small);
  color: color-mix(in srgb, var(--color-surface) 70%, transparent);
}

/* ---------- Remodel on a slab: photo pair + steps ---------- */
.nc-slab {
  background: var(--color-surface);
}
.nc-slab__grid {
  display: grid;
  grid-template-columns: minmax(0, 1fr) minmax(0, 1.15fr);
  gap: var(--space-12);
  align-items: center;
}
.nc-photos {
  margin: 0;
}
.nc-photos__stack {
  position: relative;
  padding-block-end: var(--space-16);
}
.nc-photos__main {
  background: var(--color-paper-2);
  width: 78%;
  aspect-ratio: 3 / 4;
  border-radius: var(--radius-lg);
  overflow: hidden;
  box-shadow: var(--shadow-lg);
  clip-path: polygon(0 0, 100% 0, 100% 100%, 12% 100%, 0 90%);
}
.nc-photos__inset {
  background: var(--color-paper-2);
  position: absolute;
  inset-inline-end: 0;
  inset-block-end: 0;
  width: 52%;
  aspect-ratio: 1 / 1;
  border-radius: var(--radius);
  overflow: hidden;
  border: 6px solid var(--color-surface);
  box-shadow: var(--shadow-lg);
}
.nc-photos picture,
.nc-photos img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform var(--transition-slow);
}
.nc-photos__main:hover img,
.nc-photos__inset:hover img {
  transform: scale(1.04);
}
.nc-photos figcaption {
  margin-block-start: var(--space-4);
  max-width: 34ch;
  font-size: var(--fs-small);
  color: var(--color-muted);
  border-inline-start: 3px solid var(--color-accent);
  padding-inline-start: var(--space-3);
}
.nc-slab__copy {
  display: grid;
  gap: var(--space-4);
}
.nc-slab__copy > p:not(.answer-block) {
  margin: 0;
  color: var(--color-ink-2);
  max-width: var(--measure);
}
.nc-slab-steps {
  list-style: none;
  margin: 0;
  padding: 0;
  display: grid;
  grid-template-columns: repeat(2, minmax(0, 1fr));
  gap: var(--space-3);
  counter-reset: nc-cut;
}
.nc-slab-steps li {
  counter-increment: nc-cut;
  display: grid;
  gap: var(--space-1);
  padding: var(--space-4) var(--space-5);
  border-radius: var(--radius);
  background: var(--color-paper);
  border-block-start: 3px solid var(--color-primary);
}
.nc-slab-steps li::before {
  content: counter(nc-cut, decimal-leading-zero);
  font-family: var(--font-accent);
  font-size: 1.3rem;
  line-height: 1;
  color: var(--color-accent-dark);
}
.nc-slab-steps b {
  font-family: var(--font-heading);
  color: var(--color-ink);
}
.nc-slab-steps span {
  font-size: var(--fs-small);
  color: var(--color-ink-2);
}

/* ---------- Comparison table: three job types ---------- */
.nc-compare {
  background: var(--color-paper-2);
}
.nc-compare__wrap {
  container-type: inline-size;
  container-name: nctable;
  margin-block-start: var(--space-8);
}
.nc-table {
  width: 100%;
  border-collapse: separate;
  border-spacing: var(--space-2) 0;
  font-size: var(--fs-small);
}
.nc-table caption {
  caption-side: bottom;
  text-align: start;
  padding-block-start: var(--space-4);
  color: var(--color-muted);
}
.nc-table th,
.nc-table td {
  padding: var(--space-4);
  text-align: start;
  vertical-align: top;
}
.nc-table thead th {
  font-family: var(--font-heading);
  font-size: 1.05rem;
  color: var(--color-surface);
  background: var(--color-primary);
  border-start-start-radius: var(--radius);
  border-start-end-radius: var(--radius);
}
.nc-table thead th:first-child {
  background: transparent;
}
.nc-table thead th:nth-child(3) {
  background: var(--color-dark);
}
.nc-table thead th:nth-child(4) {
  background: var(--color-accent-dark);
}
.nc-table tbody th {
  width: 19%;
  font-family: var(--font-accent);
  font-size: var(--fs-eyebrow);
  letter-spacing: .12em;
  text-transform: uppercase;
  color: var(--color-muted);
  border-block-start: 1px solid var(--color-line);
}
.nc-table tbody td {
  background: var(--color-surface);
  color: var(--color-ink-2);
  border-block-start: 1px solid var(--color-line);
  transition: background-color var(--transition);
}
.nc-table tbody tr:last-child td {
  border-end-start-radius: var(--radius);
  border-end-end-radius: var(--radius);
}
.nc-table tbody tr:hover td {
  background: color-mix(in srgb, var(--color-accent) 7%, var(--color-surface));
}
@container nctable (max-width: 720px) {
  .nc-table thead {
    position: absolute;
    width: 1px;
    height: 1px;
    overflow: hidden;
    clip-path: inset(50%);
  }
  .nc-table,
  .nc-table tbody,
  .nc-table tr,
  .nc-table th,
  .nc-table td {
    display: block;
    width: auto;
  }
  .nc-table tbody tr {
    margin-block-end: var(--space-4);
    padding: var(--space-3);
    border-radius: var(--radius);
    background: var(--color-surface);
    box-shadow: var(--shadow-sm);
  }
  .nc-table tbody th,
  .nc-table tbody td {
    border: 0;
    padding: var(--space-2);
  }
  .nc-table tbody td::before {
    content: attr(data-label) ": ";
    font-weight: 700;
    color: var(--color-ink);
  }
}

/* ---------- Who it's for: residential / commercial / industrial (curved edge) ---------- */
.nc-who {
  background: var(--color-surface);
}
.nc-who__grid {
  display: grid;
  grid-template-columns: minmax(0, 1.1fr) minmax(0, 1.9fr);
  gap: var(--space-12);
  align-items: start;
}
.nc-who__copy {
  display: grid;
  gap: var(--space-4);
}
.nc-who__copy > p:not(.answer-block) {
  margin: 0;
  color: var(--color-ink-2);
}
.nc-sectors {
  display: grid;
  grid-template-columns: repeat(3, minmax(0, 1fr));
  gap: var(--space-4);
}
.nc-sector {
  display: grid;
  align-content: start;
  gap: var(--space-3);
  padding: var(--space-6) var(--space-5);
  border-radius: var(--radius-lg);
  border: 1px solid var(--color-line);
  transition: transform var(--transition), box-shadow var(--transition);
}
.nc-sector:hover {
  transform: translateY(-4px);
  box-shadow: var(--shadow);
}
.nc-sector svg {
  color: var(--color-accent-dark);
}
.nc-sector h3 {
  font-size: var(--fs-h3);
}
.nc-sector p {
  margin: 0;
  font-size: var(--fs-small);
  color: var(--color-ink-2);
}

/* ---------- Expert + process ---------- */
.nc-expert {
  background: var(--color-paper);
}
.nc-expert__grid {
  display: grid;
  grid-template-columns: minmax(0, 3fr) minmax(0, 2fr);
  gap: var(--space-12);
  align-items: start;
}
.nc-expert__copy {
  display: grid;
  gap: var(--space-4);
}
.nc-expert__copy > p:not(.answer-block) {
  margin: 0;
  color: var(--color-ink-2);
  max-width: var(--measure);
}
.nc-stamp {
  position: relative;
  display: grid;
  gap: var(--space-2);
  padding: var(--space-8) var(--space-6);
  border-radius: var(--radius-lg);
  background: var(--color-surface);
  border: 2px solid var(--color-primary);
  box-shadow: var(--shadow-lg);
  text-align: center;
}
.nc-stamp::before {
  content: "";
  position: absolute;
  inset: var(--space-2);
  border: 1px dashed color-mix(in srgb, var(--color-primary) 40%, transparent);
  border-radius: var(--radius);
  pointer-events: none;
}
.nc-stamp small {
  font-family: var(--font-accent);
  font-size: var(--fs-eyebrow);
  letter-spacing: .16em;
  text-transform: uppercase;
  color: var(--color-muted);
}
.nc-stamp b {
  font-family: var(--font-accent);
  font-size: clamp(2.4rem, 1.6rem + 2.6vw, 3.6rem);
  line-height: 1;
  color: var(--color-primary);
}
.nc-stamp span {
  font-size: var(--fs-small);
  color: var(--color-ink-2);
}
.nc-process {
  list-style: none;
  margin: var(--space-4) 0 0;
  padding: 0;
  display: grid;
  grid-template-columns: repeat(2, minmax(0, 1fr));
  gap: var(--space-3);
  counter-reset: nc-step;
}
.nc-process li {
  counter-increment: nc-step;
  display: grid;
  align-content: start;
  gap: var(--space-1);
  padding: var(--space-4);
  border-radius: var(--radius);
  background: var(--color-surface);
  border: 1px solid var(--color-line);
}
.nc-process li::before {
  content: counter(nc-step);
  width: 32px;
  height: 32px;
  display: grid;
  place-items: center;
  border-radius: var(--radius-full);
  font-family: var(--font-accent);
  color: var(--color-surface);
  background: var(--color-primary);
}
.nc-process b {
  font-family: var(--font-heading);
  font-size: 1rem;
  color: var(--color-ink);
}
.nc-process span {
  font-size: var(--fs-small);
  color: var(--color-ink-2);
}

/* ---------- Proof (dark, slant edge) ---------- */
.nc-proof .section-head {
  max-width: 64ch;
}
.nc-proof .answer-block {
  background: color-mix(in srgb, var(--color-surface) 6%, transparent);
  color: color-mix(in srgb, var(--color-surface) 88%, transparent);
}
.nc-proof__row {
  display: grid;
  grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
  gap: var(--space-6);
  margin-block-start: var(--space-8);
}
.nc-proof__card {
  display: grid;
  align-content: start;
  gap: var(--space-3);
  padding: var(--space-6);
  border-radius: var(--radius-lg);
  background: color-mix(in srgb, var(--color-surface) 7%, transparent);
  border: 1px solid color-mix(in srgb, var(--color-surface) 16%, transparent);
}
.nc-proof__card h3 {
  color: var(--color-surface);
}
.nc-proof__card p {
  margin: 0;
}
.nc-proof__card .actions {
  display: flex;
  flex-wrap: wrap;
  gap: var(--space-3);
}
.nc-proof__card dl {
  margin: 0;
  display: grid;
  grid-template-columns: auto 1fr;
  gap: var(--space-2) var(--space-4);
  font-size: var(--fs-small);
}
.nc-proof__card dt {
  color: color-mix(in srgb, var(--color-surface) 62%, transparent);
}
.nc-proof__card dd {
  margin: 0;
  color: var(--color-surface);
  font-weight: 600;
}
.nc-proof__reviews {
  margin-block-start: var(--space-8);
}
.nc-proof__reviews .p1gr-text {
  color: var(--color-ink-2);
}

/* ---------- Related services ---------- */
.nc-related {
  background: var(--color-paper-2);
}
.nc-related .services-grid {
  grid-template-columns: repeat(3, minmax(0, 1fr));
}
.nc-related .service-card-with-image h3 {
  text-wrap: balance;
}

/* ---------- Final CTA (curved edge) ---------- */
.nc-cta {
  margin-block-start: calc(-1 * var(--space-8));
}
.nc-cta .container {
  align-items: center;
}
.nc-cta h2 {
  max-width: 22ch;
  text-wrap: balance;
}
.nc-cta .link-call {
  color: color-mix(in srgb, var(--color-surface) 88%, transparent);
}
.nc-cta .link-call svg {
  color: var(--color-accent-bright);
}
.nc-cta__ring {
  inset-inline-end: -120px;
  inset-block-start: -90px;
}
.nc-updated {
  margin-block-start: var(--space-6);
  font-size: var(--fs-small);
  color: var(--color-muted);
}

/* ---------- Responsive ---------- */
@media (max-width: 1100px) {
  .nc-track {
    grid-template-columns: repeat(2, minmax(0, 1fr));
    row-gap: var(--space-10);
  }
  .nc-track::before {
    display: none;
  }
  .nc-stamp {
    max-width: 420px;
  }
}
@media (max-width: 1000px) {
  .nc-who__grid {
    grid-template-columns: 1fr;
  }
  .nc-related .services-grid {
    grid-template-columns: repeat(2, minmax(0, 1fr));
  }
}
@media (max-width: 900px) {
  .nc-plan__grid,
  .nc-slab__grid,
  .nc-expert__grid,
  .nc-proof__row {
    grid-template-columns: 1fr;
  }
  .nc-plan__intro {
    position: static;
  }
  .nc-photos {
    max-width: 520px;
  }
}
@media (max-width: 640px) {
  .nc-track,
  .nc-sectors,
  .nc-slab-steps,
  .nc-process,
  .nc-related .services-grid {
    grid-template-columns: 1fr;
  }
  .nc-check {
    grid-template-columns: 1fr;
    padding: var(--space-5);
  }
  .nc-check__icon {
    grid-row: auto;
  }
  .nc-phase {
    grid-template-columns: auto 1fr;
    align-items: start;
    gap: var(--space-4);
  }
}
@media (prefers-reduced-motion: reduce) {
  .nc-hero .hero-bg img,
  .nc-track::before {
    animation: none;
  }
  .nc-check,
  .nc-sector,
  .nc-photos img,
  .nc-table tbody td {
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
<section class="hero hero--photo hero--interior nc-hero" aria-labelledby="nc-h1">
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
            <li><span aria-current="page">New Construction &amp; Remodels</span></li>
          </ol>
        </nav>
        <span class="eyebrow">Construction Plumbing · <?php echo h($address['city'] . ', ' . $address['state']); ?></span>
        <h1 class="hero-title" id="nc-h1">New Construction and Remodel Plumbing from a San Angelo Plumber</h1>
        <p class="hero-answer">Rock'n G Plumbing plumbs new homes, remodels and commercial build-outs in San Angelo, Christoval and the rest of Tom Green County, from the drain lines under the slab to the fixtures set at the end. Master plumber Eli Gomez's Texas license M-44822 covers every job, and estimates are free.</p>
        <div class="hero-actions">
          <button type="button" class="btn btn-primary btn-lg hero-form-open" data-open-estimate>Get a free estimate</button>
          <a class="link-call" href="<?php echo h($telHref); ?>"><?php echo icon('phone', 18); ?>or call <?php echo h($phoneFmt); ?></a>
        </div>
        <ul class="hero-chips">
          <li><?php echo icon('badge-check', 16); ?>Texas Master Plumber <?php echo h($license['number']); ?></li>
          <li><?php echo icon('building-2', 16); ?>Residential, commercial &amp; industrial</li>
          <li><?php echo icon('shield-check', 16); ?>Military discounts</li>
        </ul>
      </div>

      <aside class="hero-form-card" id="estimate-form" aria-labelledby="hero-form-title">
        <h2 id="hero-form-title">Need plumbing priced for a build or remodel?</h2>
        <p class="hero-form-tagline">Tell us about the project. No obligation.</p>
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

<article class="nc-article" aria-labelledby="nc-h1">

  <!-- ============ PLANNING (problem statement) ============ -->
  <section class="section nc-plan" aria-labelledby="nc-plan-title">
    <div class="container">
      <div class="nc-plan__grid">
        <div class="nc-plan__intro reveal-left">
          <span class="eyebrow">Plan before you build</span>
          <h2 id="nc-plan-title">When should a plumber get involved in a San Angelo remodel?</h2>
          <p class="answer-block">A plumber should see a remodel before the layout is final, because sinks, tubs and appliances decide where drains, vents and gas lines have to run. Catching a problem on paper costs far less than moving pipe after the concrete is cut or the drywall is hung.</p>
          <p>The four decisions on the right are the ones that move pipe in most San Angelo kitchens and bathrooms. Bring them up when you call Rock'n G, along with the plans or a sketch of the room.</p>
        </div>
        <ul class="nc-checks">
          <?php foreach ($ncChecks as $i => $check): ?>
          <li class="nc-check reveal-right reveal-delay-<?php echo ($i % 3) + 1; ?>">
            <span class="nc-check__icon"><?php echo icon($check['icon'], 24); ?></span>
            <h3><?php echo h($check['title']); ?></h3>
            <p><?php echo h($check['body']); ?></p>
          </li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>
  </section>

  <!-- ============ SIGNATURE: CONSTRUCTION-PHASE TIMELINE ============ -->
  <section class="section on-dark edge-parallelogram-top nc-phases" aria-labelledby="nc-phases-title">
    <span class="grain-layer" aria-hidden="true"></span>
    <div class="container-wide">
      <div class="section-head reveal-up">
        <span class="eyebrow">New construction, phase by phase</span>
        <h2 id="nc-phases-title">What plumbing happens at each stage of new construction in San Angelo?</h2>
        <p class="answer-block">New construction plumbing happens in four stages: underground lines before the slab is poured, rough-in once the framing is up, top-out with vents through the roof, and trim when fixtures are set. Each stage has to be right before the next trade covers it with concrete, drywall or cabinets.</p>
      </div>
      <ol class="nc-track">
        <?php foreach ($ncPhases as $i => $phase): ?>
        <li class="nc-phase reveal-up reveal-delay-<?php echo ($i % 4) + 1; ?>">
          <span class="nc-phase__node"><?php echo icon($phase['icon'], 26); ?></span>
          <div class="nc-phase__card">
            <small><?php echo h($phase['phase']); ?></small>
            <h3><?php echo h($phase['phase']); ?> plumbing</h3>
            <p><?php echo h($phase['what']); ?></p>
            <p class="nc-phase__check"><?php echo icon('clipboard-list', 16); ?><span><?php echo h($phase['check']); ?></span></p>
          </div>
        </li>
        <?php endforeach; ?>
      </ol>
      <p class="nc-phases__note">Which inspections apply, and when, depends on the city or county the build is in. San Angelo, Christoval and the unincorporated parts of Tom Green County do not all work the same way, so ask about inspection timing when the plumbing is priced.</p>
    </div>
  </section>

  <!-- ============ REMODEL ON A SLAB ============ -->
  <section class="section nc-slab" aria-labelledby="nc-slab-title">
    <div class="container">
      <div class="nc-slab__grid">
        <figure class="nc-photos reveal-left">
          <div class="nc-photos__stack">
          <div class="nc-photos__main">
            <?php echo pictureTag('rockn-under-floor-drain-lines', 'White PVC drain lines run down through opened floor framing into a trench during a Rock\'n G drain job', '(max-width: 900px) 70vw, 400px'); ?>
          </div>
          <div class="nc-photos__inset">
            <?php echo pictureTag('rockn-pipe-through-slab', 'Close view of a PVC drain line dropping into a trench below the floor', '(max-width: 900px) 46vw, 270px'); ?>
          </div>
          </div>
          <figcaption>Rough-in style drain work from a Rock'n G job: new PVC drain lines run down through the floor framing and into a trench below.</figcaption>
        </figure>
        <div class="nc-slab__copy reveal-right">
          <span class="eyebrow">Remodels on a slab</span>
          <h2 id="nc-slab-title">What does moving a sink or island involve on a slab home?</h2>
          <p class="answer-block">Moving a sink, island or toilet on a slab home means moving its drain, and that drain runs under the concrete. The slab is cut along the new route, a trench is dug, and new pipe is laid with steady slope and a vent before the floor is patched.</p>
          <p>Many homes around San Angelo sit on concrete slabs, so adding an island sink like the farmhouse sink at the top of this page starts with a saw cut rather than a trip under the house. Here is how that work usually goes.</p>
          <ol class="nc-slab-steps">
            <?php foreach ($ncSlabSteps as $step): ?>
            <li><b><?php echo h($step['title']); ?></b><span><?php echo h($step['body']); ?></span></li>
            <?php endforeach; ?>
          </ol>
          <p>If the remodel also adds a gas cooktop or moves a range, the <a href="/services/gas-line-services/">gas line work</a> is planned at the same time, before the cabinets go in.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- ============ COMPARISON ============ -->
  <section class="section slant-top nc-compare" aria-labelledby="nc-compare-title">
    <div class="container">
      <div class="section-head reveal-up">
        <span class="eyebrow">Compare</span>
        <h2 id="nc-compare-title">How do new construction, remodel and commercial plumbing jobs differ?</h2>
        <p class="answer-block">New construction, remodel and commercial plumbing differ mainly in what is already in place. A new build starts from bare ground, a remodel has to tie into old pipe and work around finished rooms, and a commercial build-out adds more fixtures and a business schedule to plan around.</p>
      </div>
      <div class="nc-compare__wrap reveal-scale">
        <table class="nc-table">
          <caption>A general comparison. The estimate for your project covers its own layout, pipe and schedule.</caption>
          <thead>
            <tr><th scope="col"><span class="sr-only">Topic</span></th><th scope="col">New construction</th><th scope="col">Remodel</th><th scope="col">Commercial build-out</th></tr>
          </thead>
          <tbody>
            <tr><th scope="row">Starting point</th><td data-label="New construction">Bare lot and plans</td><td data-label="Remodel">Existing pipe of whatever age the house is</td><td data-label="Commercial">An empty shell or a space being converted</td></tr>
            <tr><th scope="row">Where pipe runs</th><td data-label="New construction">Laid out fresh under the slab and in open framing</td><td data-label="Remodel">Tied into old drains and supply lines, often through finished walls or floors</td><td data-label="Commercial">Tied into the building's existing mains, often over long runs</td></tr>
            <tr><th scope="row">Biggest planning item</th><td data-label="New construction">Getting stub-ups in the right place before the pour</td><td data-label="Remodel">Whether fixtures move, and what the old pipe looks like once opened</td><td data-label="Commercial">Fixture count and how the space will be used</td></tr>
            <tr><th scope="row">Who you plan with</th><td data-label="New construction">Builder and the other trades on the schedule</td><td data-label="Remodel">Homeowner, contractor and cabinet or tile installers</td><td data-label="Commercial">Owner, general contractor and the business's opening date</td></tr>
            <tr><th scope="row">Common surprise</th><td data-label="New construction">Layout changes after the slab is poured</td><td data-label="Remodel">Corroded or undersized pipe behind the walls</td><td data-label="Commercial">Existing lines that do not match the plans</td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </section>

  <!-- ============ RESIDENTIAL / COMMERCIAL / INDUSTRIAL ============ -->
  <section class="section edge-curve-top nc-who" aria-labelledby="nc-who-title">
    <div class="container">
      <div class="nc-who__grid">
        <div class="nc-who__copy reveal-left">
          <span class="eyebrow">Who Rock'n G works for</span>
          <h2 id="nc-who-title">Does Rock'n G handle commercial plumbing in San Angelo?</h2>
          <p class="answer-block">Yes, Rock'n G Plumbing handles commercial plumbing in San Angelo, along with residential and industrial work. That means the same master license covers a homeowner's bathroom remodel, a new house going up in Christoval and the plumbing in a commercial space being built out.</p>
          <p>Not sure which bucket your project falls into? Start with the <a href="/services/">full list of Rock'n G plumbing services</a> or call the office.</p>
        </div>
        <div class="nc-sectors">
          <div class="nc-sector card-tint-1 reveal-up reveal-delay-1">
            <?php echo icon('home', 28); ?>
            <h3>Residential</h3>
            <p>New homes, additions, and kitchen and bathroom remodels, from slab plumbing to setting the last faucet.</p>
          </div>
          <div class="nc-sector card-tint-2 reveal-up reveal-delay-2">
            <?php echo icon('building', 28); ?>
            <h3>Commercial</h3>
            <p>Build-outs and renovations for business spaces, where fixture count and the opening date shape the plumbing plan.</p>
          </div>
          <div class="nc-sector card-tint-3 reveal-up reveal-delay-3">
            <?php echo icon('hard-hat', 28); ?>
            <h3>Industrial</h3>
            <p>Industrial customers are part of Rock'n G's work too. Call to talk through the scope and access for an industrial job.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ============ EXPERT + PROCESS ============ -->
  <section class="section nc-expert" aria-labelledby="nc-expert-title">
    <div class="container">
      <div class="nc-expert__grid">
        <div class="nc-expert__copy reveal-left">
          <span class="eyebrow">Who does the work</span>
          <h2 id="nc-expert-title">Why hire a master plumber as your residential plumbing contractor in San Angelo?</h2>
          <p class="answer-block">A master plumber can take responsibility for a whole job, from the underground lines to the final fixture, which matters on work that gets covered up. Rock'n G Plumbing's construction work runs under Eli Gomez's Texas Master Plumber License M-44822, issued by the Texas State Board of Plumbing Examiners.</p>
          <p>Plumbing is the Gomez family's trade, three generations deep, and Rock'n G brings 20+ years of experience to new builds and remodels. The same crew also handles <a href="/services/water-heaters/">water heater installation</a>, so the heater a new house needs can be part of the same plan.</p>
          <ol class="nc-process">
            <li><b>Call or send plans</b><span>Reach the office at <?php echo h($phoneFmt); ?>, <?php echo h($weekday[0]); ?> <?php echo h($weekday[1]); ?>, or send the form with a project description.</span></li>
            <li><b>Estimate at no charge</b><span>Rock'n G prices the plumbing for your project.</span></li>
            <li><b>You decide</b><span>Go ahead when the price and the plan work for you. Ask about the military discount.</span></li>
            <li><b>The work</b><span>Each stage is done under <?php echo h($ownerName); ?>'s Texas Master Plumber license.</span></li>
          </ol>
        </div>
        <div class="nc-stamp reveal-right">
          <small>Responsible Master Plumber</small>
          <b><?php echo h($license['number']); ?></b>
          <span><?php echo h($license['holder']); ?> · <?php echo h($license['issuer']); ?></span>
          <span>Current through <?php echo h(date('F j, Y', strtotime($license['expires']))); ?></span>
        </div>
      </div>
    </div>
  </section>

  <!-- ============ PROOF ============ -->
  <section class="section on-dark slant-top nc-proof" aria-labelledby="nc-proof-title">
    <span class="grain-layer" aria-hidden="true"></span>
    <div class="container">
      <div class="section-head reveal-up">
        <span class="eyebrow">Proof</span>
        <h2 id="nc-proof-title">How can builders and homeowners check Rock'n G Plumbing?</h2>
        <p class="answer-block">Builders and homeowners can check Rock'n G Plumbing by reading its Google reviews and looking up license M-44822 with the Texas State Board of Plumbing Examiners. Rock'n G is rated <?php echo h(number_format((float) $gbp['rating'], 1)); ?> out of 5 from <?php echo (int) $gbp['reviews_count']; ?> Google reviews.</p>
      </div>
      <div class="nc-proof__row">
        <div class="nc-proof__card reveal-left">
          <h3>Google reviews</h3>
          <p>Rock'n G Plumbing's Google reviews come from its customers, not from this website. If Rock'n G plumbed your build or remodel, a review helps the next San Angelo homeowner or contractor decide.</p>
          <div class="actions">
            <a class="btn btn-accent" href="<?php echo h($gbp['profile_url']); ?>" target="_blank" rel="noopener">Read reviews on Google</a>
            <a class="btn btn-outline-white" href="<?php echo h($gbp['review_url']); ?>" target="_blank" rel="noopener">Leave a review</a>
          </div>
        </div>
        <div class="nc-proof__card reveal-right">
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
      $ncReviews = p1_google_reviews($slug, ['limit' => 3, 'heading' => '', 'summary' => false]);
      if ($ncReviews !== ''): ?>
      <div class="nc-proof__reviews"><?php echo $ncReviews; ?></div>
      <?php endif; ?>
    </div>
  </section>

  <!-- ============ RELATED SERVICES (required services-grid) ============ -->
  <section class="section nc-related" aria-label="Related plumbing services">
    <div class="container">
      <div class="section-title reveal-up">
        <span class="eyebrow-label">What We Do</span>
        <h2>Which other <span class="text-accent">plumbing services</span> do builders and remodelers need?</h2>
        <p class="hero-answer">New builds and remodels usually pull in three more kinds of plumbing work. A new or relocated water heater, gas lines for ranges, cooktops and generators, and drain cleaning when an older home's lines are opened up and found slow. Rock'n G Plumbing handles each of them under the same license.</p>
        <span class="section-subtitle">Water heaters · Gas lines · Drains</span>
        <p class="prose">See every <a href="/services/">plumbing service Rock'n G offers in San Angelo</a>.</p>
      </div>
      <div class="services-grid">
        <?php echo renderServiceCards(['water-heaters', 'gas-line-services', 'drain-cleaning'], relatedServiceSizes(640)); ?>
      </div>
    </div>
  </section>

  <!-- ============ FINAL CTA ============ -->
  <section class="closing-cta texture-grain edge-curve-top nc-cta" aria-labelledby="nc-cta-title">
    <span class="grain-layer" aria-hidden="true"></span>
    <span class="floating-ring nc-cta__ring" aria-hidden="true"></span>
    <div class="container">
      <div class="reveal-left">
        <span class="eyebrow">Price it before you build</span>
        <h2 id="nc-cta-title">Ready for a free plumbing estimate on your San Angelo project?</h2>
        <p>Call Rock'n G Plumbing at <?php echo h($phoneFmt); ?>, <?php echo h($weekday[0]); ?> <?php echo h($weekday[1]); ?>, or send the form with your plans or a description of the remodel. The earlier the plumbing is priced, the fewer surprises once walls and floors are open.</p>
        <p class="nc-updated">Last updated: <?php echo date('F Y'); ?></p>
      </div>
      <div class="actions reveal-right">
        <a class="btn btn-accent btn-lg" href="/contact/" data-open-estimate>Get a Free Construction Plumbing Estimate</a>
        <a class="link-call" href="<?php echo h($telHref); ?>"><?php echo icon('phone', 18); ?>Call <?php echo h($phoneFmt); ?></a>
      </div>
    </div>
  </section>

</article>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>
