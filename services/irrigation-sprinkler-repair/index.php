<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/functions.php';
?>
<?php
/**
 * services/irrigation-sprinkler-repair/index.php — Sprinkler & Irrigation Repair (Phase 4, premium editorial layout).
 * Every business fact traces to build-plan.json / includes/config.php. Build plan lists REPAIR only: no system design or
 * installation, no irrigator-license claim, no backflow testing (SEO brief). No irrigation job photo exists yet, so the
 * page uses the client's trench/excavation photos with alt text that says exactly what they show.
 * Open questions (install vs repair, TCEQ irrigator, controllers/wiring, photos) are in /content-confirmations.json.
 * SEO brief: no FAQ section/schema, no emergency or 24-hour wording.
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/service-cards.php';

$svc         = serviceBySlug('irrigation-sprinkler-repair');
$pageType    = 'service';
$serviceSlug = $svc['slug'];
$pageTitle       = "Sprinkler Repair Plumber in San Angelo, TX | Rock'n G";
$pageDescription = "Sprinkler repair plumber in San Angelo, TX • Broken heads, leaking lines & zone problems • Master Plumber M-44822, free estimates. Call (325) 650-7000.";
$canonicalUrl    = $siteUrl . '/services/irrigation-sprinkler-repair/';
$currentPage     = 'services/irrigation-sprinkler-repair';
$breadcrumbs     = [['name' => 'Services', 'url' => '/services/'], ['name' => $svc['name'], 'url' => '/services/irrigation-sprinkler-repair/']];

$heroImage   = 'rockn-trench-dig';
$heroAlt     = "Rock'n G plumber in a red company shirt standing in an excavated trench to reach a buried line";
$heroPreload = ['name' => $heroImage];
$ogImage     = '/assets/images/' . $heroImage . '.jpg';

$telHref  = 'tel:' . $phoneE164;
$phoneFmt = formatPhone($phone);
$weekday  = hoursGroups($hours)[0];      // ['Mon–Fri', '8:00 AM – 6:00 PM']

// What wears sprinkler systems down around San Angelo (general conditions; the hard water is in build-plan.json)
$irConditions = [
    ['icon' => 'sun', 'tag' => 'Summer', 'title' => 'Long, hot summers',
     'body' => 'Systems run hardest in the heat. A broken head or split line in July shows up fast as a dry brown patch, and the water it wastes comes at the time of year it matters most.'],
    ['icon' => 'droplets', 'tag' => 'Year-round', 'title' => 'Hard water scale',
     'body' => "San Angelo's hard water leaves mineral scale on nozzles and inside small spray heads. The spray pattern narrows, and you get dry arcs between heads that used to overlap."],
    ['icon' => 'snowflake', 'tag' => 'Winter', 'title' => 'Hard freezes',
     'body' => 'Water left in above-ground piping, valves and shallow fittings can crack in a hard freeze. The damage often stays hidden until the system is turned back on in spring.'],
    ['icon' => 'landmark', 'tag' => 'Any season', 'title' => 'City watering rules',
     'body' => "Before resetting a controller after a repair, check the City of San Angelo's current outdoor watering rules. Allowed days and times can change with drought conditions."],
];

// Zone troubleshooting (general irrigation knowledge)
$irZones = [
    ['icon' => 'waves', 'symptom' => 'Soggy patch or water bubbling up', 'cause' => 'A cracked buried line or a broken fitting under the turf', 'check' => 'Run only that zone and watch where water surfaces'],
    ['icon' => 'sun', 'symptom' => 'Dry brown patch inside a watered area', 'cause' => 'A clogged nozzle, a head knocked out of line, or low pressure on the zone', 'check' => 'Look for a head that is tilted, buried or spraying short'],
    ['icon' => 'minus', 'symptom' => "Head won't pop up", 'cause' => 'Debris or scale jamming the riser, or a broken head body', 'check' => 'Clear grass and soil away from the head and run the zone'],
    ['icon' => 'droplets', 'symptom' => "Zone won't shut off", 'cause' => 'A valve stuck open by debris or a worn diaphragm', 'check' => 'Turn the system off at the controller; if water still runs, it is the valve'],
    ['icon' => 'wind', 'symptom' => 'Weak spray on one zone only', 'cause' => 'A leak somewhere on that zone, or a partly closed or failing valve', 'check' => 'Compare it with the other zones run one at a time'],
    ['icon' => 'clock', 'symptom' => "Zone won't come on at all", 'cause' => 'A valve that will not open, a wiring fault, or a controller setting', 'check' => 'Try running the zone manually from the controller'],
];

// Repair types (build plan: broken heads and leaking lines, irrigation system repairs, lawn sprinkler troubleshooting)
$irRepairs = [
    ['icon' => 'sprout', 'title' => 'Heads and nozzles', 'body' => 'Replacing broken, stuck or scaled-up spray heads and rotors, and resetting heads that were knocked out of line.'],
    ['icon' => 'route',  'title' => 'Leaking lines and fittings', 'body' => 'Digging out and repairing cracked buried lines and broken fittings that leave soggy ground or run up the water bill.'],
    ['icon' => 'wrench', 'title' => 'Zone valves', 'body' => 'Fixing valves that stick open, will not open, or leak, so each zone runs and stops when it should.'],
    ['icon' => 'search', 'title' => 'Troubleshooting', 'body' => 'Tracing a zone that will not run, or a system that acts up, back to the valve, the line or the controller setting.'],
];

// Service schema (provider = the Plumber node head.php prints on every page)
$serviceNode = generateServiceSchema($svc);
$serviceNode['@id']         = absUrl(serviceUrl($svc)) . '#service-' . $svc['slug'];
$serviceNode['serviceType'] = 'Sprinkler and irrigation system repair';
$serviceNode['description'] = "Lawn sprinkler and irrigation system repair in San Angelo, TX and nearby Tom Green County towns: broken heads, leaking buried lines, zone valves and troubleshooting, by Rock'n G Plumbing under Texas Master Plumber License M-44822.";
$serviceNode['image']       = absUrl('/assets/images/' . $heroImage . '.jpg');
$serviceNode['hasOfferCatalog'] = [
    '@type' => 'OfferCatalog', 'name' => 'Sprinkler and irrigation repairs',
    'itemListElement' => array_map(fn($r) => ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Sprinkler repair: ' . strtolower($r['title'])]], $irRepairs),
];
$serviceNode["@context"] = 'https://schema.org';   // explicit (generateServiceSchema sets it too)
$schemaMarkup = [$serviceNode];

ob_start();
?>
<style>
/* =====================================================================
   Sprinkler & Irrigation Repair — page-only CSS (ir- prefix)
   Tokens from framework.css; no raw colors, shadows or spacing.
   ===================================================================== */
@property --ir-drop {
  syntax: '<percentage>';
  inherits: false;
  initial-value: 0%;
}

/* ---------- Hero: layered photo (C1) ---------- */
.ir-hero {
  padding-block-end: var(--space-16);
}
.ir-hero .hero-bg {
  overflow: hidden;
}
.ir-hero .hero-bg picture,
.ir-hero .hero-bg img {
  width: 100%;
  height: 100%;
}
.ir-hero .hero-bg img {
  object-fit: cover;
  object-position: 65% 35%;
  animation: ir-pan 28s var(--ease) both;
}
.ir-hero::before {
  content: "";
  position: absolute;
  inset: 0;
  z-index: -1;
  pointer-events: none;
  background:
    radial-gradient(ellipse 55% 60% at 100% 100%, color-mix(in srgb, var(--color-success) 26%, transparent), transparent 70%),
    linear-gradient(180deg, color-mix(in srgb, var(--color-primary-deep) 40%, transparent) 0%, transparent 35%);
}
.ir-hero::after {
  content: "";
  position: absolute;
  inset: 0;
  z-index: -1;
  pointer-events: none;
  opacity: .2;
  mix-blend-mode: overlay;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='160' height='160'%3E%3Cfilter id='g'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.9' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23g)'/%3E%3C/svg%3E");
}
.ir-hero .breadcrumb {
  color: color-mix(in srgb, var(--color-surface) 72%, transparent);
  margin-block-end: var(--space-2);
}
.ir-hero .breadcrumb li {
  display: inline-flex;
  align-items: center;
}
.ir-hero .breadcrumb a:hover {
  color: var(--color-accent-bright);
}
.ir-hero .breadcrumb [aria-current="page"] {
  color: var(--color-surface);
}
.ir-hero .hero-title {
  max-width: 18ch;
  text-wrap: balance;
}
.ir-hero .hero-answer {
  max-width: 36rem;
}
.ir-hero .hero-chips li {
  backdrop-filter: blur(6px);
  -webkit-backdrop-filter: blur(6px);
}
.ir-hero .hero-form-open {
  background: var(--color-accent);
  color: var(--color-white);
}
.ir-hero .hero-form-card {
  border-block-start: 4px solid var(--color-success);
}
.ir-hero .hero-form-card h2 {
  text-wrap: balance;
}
.ir-hero .hero-form .consent {
  margin-block: var(--space-1);
}
@keyframes ir-pan {
  from { transform: scale(1.1) translateY(2%); }
  to   { transform: scale(1) translateY(0); }
}

/* ---------- Article ---------- */
.ir-article > section h2 {
  text-wrap: balance;
}
.ir-article .answer-block {
  font-size: var(--fs-lead);
  line-height: 1.55;
}

/* ---------- Signature A: San Angelo yards almanac (turf-edge bottom) ---------- */
.ir-yards {
  background: color-mix(in srgb, var(--color-success) 8%, var(--color-paper));
  clip-path: polygon(0 0, 100% 0, 100% calc(100% - 22px), 96% 100%, 92% calc(100% - 22px), 88% 100%, 84% calc(100% - 22px), 80% 100%, 76% calc(100% - 22px), 72% 100%, 68% calc(100% - 22px), 64% 100%, 60% calc(100% - 22px), 56% 100%, 52% calc(100% - 22px), 48% 100%, 44% calc(100% - 22px), 40% 100%, 36% calc(100% - 22px), 32% 100%, 28% calc(100% - 22px), 24% 100%, 20% calc(100% - 22px), 16% 100%, 12% calc(100% - 22px), 8% 100%, 4% calc(100% - 22px), 0 100%);
  padding-block-end: calc(var(--section-pad) + 22px);
}
.ir-yards__head {
  display: grid;
  grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
  gap: var(--space-10);
  align-items: end;
  margin-block-end: var(--space-10);
}
.ir-yards__head .section-head {
  margin: 0;
}
.ir-yards__head > p {
  margin: 0;
  color: var(--color-ink-2);
  max-width: var(--measure);
}
.ir-almanac {
  list-style: none;
  margin: 0;
  padding: 0;
  display: grid;
  grid-template-columns: repeat(4, minmax(0, 1fr));
  gap: var(--space-4);
  align-items: start;
}
.ir-almanac__item {
  position: relative;
  display: grid;
  gap: var(--space-3);
  padding: var(--space-6) var(--space-5);
  border-radius: var(--radius-lg) var(--radius-lg) var(--radius) var(--radius);
  background: var(--color-surface);
  border: 1px solid var(--color-line);
  box-shadow: var(--shadow-sm);
  overflow: hidden;
  transition: transform var(--transition), box-shadow var(--transition);
}
.ir-almanac__item:nth-child(even) {
  margin-block-start: var(--space-10);
}
.ir-almanac__item::before {
  content: "";
  position: absolute;
  inset-inline: 0;
  inset-block-start: 0;
  height: 6px;
  background: var(--ir-band, var(--color-accent));
}
.ir-almanac__item:nth-child(1) { --ir-band: var(--color-warning); }
.ir-almanac__item:nth-child(2) { --ir-band: var(--color-accent); }
.ir-almanac__item:nth-child(3) { --ir-band: var(--color-primary); }
.ir-almanac__item:nth-child(4) { --ir-band: var(--color-success); }
.ir-almanac__item:hover {
  transform: translateY(-4px);
  box-shadow: var(--shadow);
}
.ir-almanac__icon {
  width: 52px;
  height: 52px;
  display: grid;
  place-items: center;
  border-radius: var(--radius-full);
  color: var(--ir-band, var(--color-accent));
  background: color-mix(in srgb, var(--ir-band, var(--color-accent)) 14%, var(--color-surface));
}
.ir-almanac__tag {
  font-family: var(--font-accent);
  font-size: var(--fs-eyebrow);
  letter-spacing: .14em;
  text-transform: uppercase;
  color: var(--color-muted);
}
.ir-almanac__item h3 {
  font-size: var(--fs-h3);
  text-wrap: balance;
}
.ir-almanac__item p {
  margin: 0;
  font-size: var(--fs-small);
  color: var(--color-ink-2);
}

/* ---------- Signature B: zone troubleshooting board ---------- */
.ir-zones {
  background: var(--color-surface);
}
.ir-zones__wrap {
  container-type: inline-size;
  container-name: irzones;
  margin-block-start: var(--space-8);
  padding: var(--space-4);
  border-radius: var(--radius-xl);
  background: linear-gradient(160deg, var(--color-dark) 0%, var(--color-dark-alt) 100%);
  box-shadow: var(--shadow-lg);
}
.ir-zones__bar {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: var(--space-4);
  padding: var(--space-2) var(--space-3) var(--space-4);
  font-family: var(--font-accent);
  font-size: var(--fs-eyebrow);
  letter-spacing: .14em;
  text-transform: uppercase;
  color: var(--color-accent-bright);
}
.ir-zones__bar span:last-child {
  display: inline-flex;
  align-items: center;
  gap: var(--space-2);
  color: color-mix(in srgb, var(--color-surface) 70%, transparent);
}
.ir-zones__bar span:last-child::before {
  content: "";
  width: 10px;
  height: 10px;
  border-radius: var(--radius-full);
  background: var(--color-success);
  box-shadow: 0 0 0 4px color-mix(in srgb, var(--color-success) 25%, transparent);
  animation: ir-blink 2.4s ease-in-out infinite;
}
@keyframes ir-blink {
  50% { opacity: .35; }
}
.ir-table {
  width: 100%;
  border-collapse: separate;
  border-spacing: 0 var(--space-2);
  font-size: var(--fs-small);
}
.ir-table caption {
  caption-side: bottom;
  text-align: start;
  padding: var(--space-3) var(--space-3) 0;
  color: color-mix(in srgb, var(--color-surface) 62%, transparent);
}
.ir-table thead th {
  padding: var(--space-2) var(--space-4);
  text-align: start;
  font-family: var(--font-accent);
  font-size: var(--fs-eyebrow);
  letter-spacing: .12em;
  text-transform: uppercase;
  color: color-mix(in srgb, var(--color-surface) 62%, transparent);
}
.ir-table tbody th,
.ir-table tbody td {
  padding: var(--space-4);
  text-align: start;
  vertical-align: top;
  background: color-mix(in srgb, var(--color-surface) 6%, transparent);
  color: color-mix(in srgb, var(--color-surface) 86%, transparent);
  transition: background-color var(--transition);
}
.ir-table tbody th {
  width: 30%;
  font-family: var(--font-heading);
  font-weight: 700;
  color: var(--color-surface);
  border-start-start-radius: var(--radius);
  border-end-start-radius: var(--radius);
}
.ir-table tbody td:last-child {
  border-start-end-radius: var(--radius);
  border-end-end-radius: var(--radius);
}
.ir-table tbody th span {
  display: flex;
  gap: var(--space-3);
  align-items: flex-start;
}
.ir-table tbody th svg {
  flex: 0 0 auto;
  color: var(--color-accent-bright);
  margin-block-start: var(--space-1);
}
.ir-table tbody tr:hover th,
.ir-table tbody tr:hover td {
  background: color-mix(in srgb, var(--color-accent) 16%, transparent);
}
@container irzones (max-width: 720px) {
  .ir-table tbody th {
    width: auto;
  }
  .ir-table caption {
    display: block;
    padding-block: 0 var(--space-3);
  }
  .ir-table thead {
    position: absolute;
    width: 1px;
    height: 1px;
    overflow: hidden;
    clip-path: inset(50%);
  }
  .ir-table,
  .ir-table tbody,
  .ir-table tr,
  .ir-table th,
  .ir-table td {
    display: block;
    width: auto;
  }
  .ir-table tbody tr {
    margin-block-end: var(--space-3);
  }
  .ir-table tbody th {
    border-radius: var(--radius) var(--radius) 0 0;
  }
  .ir-table tbody td {
    padding-block: var(--space-2);
  }
  .ir-table tbody td:last-child {
    border-radius: 0 0 var(--radius) var(--radius);
    padding-block-end: var(--space-4);
  }
  .ir-table tbody td::before {
    content: attr(data-label) ": ";
    font-family: var(--font-accent);
    font-size: var(--fs-eyebrow);
    letter-spacing: .1em;
    text-transform: uppercase;
    color: var(--color-accent-bright);
  }
}
.ir-zones__tip {
  display: flex;
  gap: var(--space-3);
  align-items: flex-start;
  margin-block-start: var(--space-6);
  max-width: 80ch;
  color: var(--color-ink-2);
}
.ir-zones__tip svg {
  flex: 0 0 auto;
  color: var(--color-accent-dark);
  margin-block-start: var(--space-1);
}
.ir-zones__tip p {
  margin: 0;
}

/* ---------- Expert: magazine-spread photo, 40/60 reversed ---------- */
.ir-expert {
  background: var(--color-paper-2);
}
.ir-expert__grid {
  display: grid;
  grid-template-columns: minmax(0, 2fr) minmax(0, 3fr);
  gap: var(--space-12);
  align-items: center;
}
.ir-spread {
  margin: 0;
  position: relative;
}
.ir-spread__img {
  position: relative;
  aspect-ratio: 3 / 4;
  border-radius: var(--radius-lg);
  overflow: hidden;
  box-shadow: var(--shadow-lg);
  clip-path: polygon(0 0, 100% 0, 100% 100%, 12% 100%, 0 90%);
}
.ir-spread__img picture,
.ir-spread__img img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}
.ir-spread figcaption {
  position: absolute;
  inset-inline-end: calc(-1 * var(--space-6));
  inset-block-end: var(--space-8);
  max-width: 260px;
  padding: var(--space-4);
  border-radius: var(--radius);
  font-size: var(--fs-small);
  color: var(--color-ink-2);
  background: var(--color-surface);
  border-inline-start: 4px solid var(--color-success);
  box-shadow: var(--shadow-lg);
}
.ir-expert__copy {
  display: grid;
  gap: var(--space-4);
}
.ir-expert__copy > p {
  margin: 0;
  color: var(--color-ink-2);
  max-width: var(--measure);
}
.ir-expert__copy a {
  color: var(--color-primary);
  font-weight: 600;
}
.ir-facts {
  display: flex;
  flex-wrap: wrap;
  gap: var(--space-3);
  margin: 0;
  padding: 0;
  list-style: none;
}
.ir-facts li {
  display: inline-flex;
  align-items: center;
  gap: var(--space-2);
  padding: var(--space-2) var(--space-4);
  border-radius: var(--radius-full);
  font-size: var(--fs-small);
  font-weight: 600;
  color: var(--color-ink);
  background: var(--color-surface);
  border: 1px solid var(--color-line);
}
.ir-facts svg {
  color: var(--color-success);
}

/* ---------- Scope: repair grid + zone-walk process ---------- */
.ir-scope {
  background: var(--color-surface);
}
.ir-repairs {
  display: grid;
  grid-template-columns: repeat(2, minmax(0, 1fr));
  gap: var(--space-4);
  margin-block: var(--space-8) var(--space-12);
}
.ir-repair {
  display: grid;
  grid-template-columns: auto 1fr;
  gap: var(--space-1) var(--space-4);
  padding: var(--space-6);
  border-radius: var(--radius-lg);
  border: 1px solid var(--color-line);
  transition: border-color var(--transition), transform var(--transition);
}
.ir-repair:hover {
  border-color: var(--color-success);
  transform: translateX(4px);
}
.ir-repair__icon {
  grid-row: span 2;
  width: 48px;
  height: 48px;
  display: grid;
  place-items: center;
  border-radius: var(--radius);
  color: var(--color-surface);
  background: var(--color-success);
}
.ir-repair h3 {
  font-size: var(--fs-h3);
}
.ir-repair p {
  margin: 0;
  font-size: var(--fs-small);
  color: var(--color-ink-2);
}
.ir-walk {
  display: grid;
  grid-template-columns: minmax(0, 2fr) minmax(0, 3fr);
  gap: var(--space-10);
  align-items: start;
}
.ir-walk__aside {
  display: grid;
  gap: var(--space-3);
}
.ir-walk__aside h3 {
  font-size: var(--fs-h2);
  text-wrap: balance;
}
.ir-walk__aside p {
  margin: 0;
  color: var(--color-ink-2);
}
.ir-walk__aside .link-call svg {
  color: var(--color-success);
}
.ir-steps {
  position: relative;
  list-style: none;
  margin: 0;
  padding: 0 0 0 var(--space-12);
  display: grid;
  gap: var(--space-8);
  counter-reset: ir-step;
}
.ir-steps::before {
  content: "";
  position: absolute;
  inset-block: var(--space-2);
  inset-inline-start: 17px;
  width: 4px;
  border-radius: var(--radius-full);
  background: linear-gradient(180deg, var(--color-accent) var(--ir-drop), var(--color-line) var(--ir-drop));
}
.ir-steps li {
  counter-increment: ir-step;
  position: relative;
  display: grid;
  gap: var(--space-1);
}
.ir-steps li::before {
  content: counter(ir-step);
  position: absolute;
  inset-inline-start: calc(-1 * var(--space-12));
  inset-block-start: 0;
  width: 38px;
  height: 38px;
  display: grid;
  place-items: center;
  border-radius: 50% 0 50% 50%;
  font-family: var(--font-accent);
  font-size: 1.1rem;
  color: var(--color-surface);
  background: var(--color-accent-dark);
}
.ir-steps b {
  font-family: var(--font-heading);
  font-size: 1.05rem;
  color: var(--color-ink);
}
.ir-steps span {
  color: var(--color-ink-2);
  font-size: var(--fs-small);
}
@supports (animation-timeline: view()) {
  .ir-steps::before {
    animation: ir-fill linear both;
    animation-timeline: view();
    animation-range: entry 20% cover 60%;
  }
  @keyframes ir-fill {
    from { --ir-drop: 0%; }
    to   { --ir-drop: 100%; }
  }
}
@supports not (animation-timeline: view()) {
  .ir-steps::before {
    --ir-drop: 100%;
  }
}

/* ---------- Proof (dark, torn edge) ---------- */
.ir-proof .section-head {
  max-width: 64ch;
}
.ir-proof .answer-block {
  background: color-mix(in srgb, var(--color-surface) 6%, transparent);
  color: color-mix(in srgb, var(--color-surface) 88%, transparent);
}
.ir-proof__row {
  display: grid;
  grid-template-columns: minmax(0, 1fr) minmax(0, 1.2fr);
  gap: var(--space-6);
  margin-block-start: var(--space-8);
}
.ir-proof__card {
  display: grid;
  align-content: start;
  gap: var(--space-3);
  padding: var(--space-6);
  border-radius: var(--radius-lg);
  background: color-mix(in srgb, var(--color-surface) 7%, transparent);
  border: 1px solid color-mix(in srgb, var(--color-surface) 16%, transparent);
}
.ir-proof__card h3 {
  color: var(--color-surface);
}
.ir-proof__card p {
  margin: 0;
}
.ir-proof__card .actions {
  display: flex;
  flex-wrap: wrap;
  gap: var(--space-3);
}
.ir-proof__card dl {
  margin: 0;
  display: grid;
  grid-template-columns: auto 1fr;
  gap: var(--space-2) var(--space-4);
  font-size: var(--fs-small);
}
.ir-proof__card dt {
  color: color-mix(in srgb, var(--color-surface) 62%, transparent);
}
.ir-proof__card dd {
  margin: 0;
  color: var(--color-surface);
  font-weight: 600;
}
.ir-proof__reviews {
  margin-block-start: var(--space-8);
}
.ir-proof__reviews .p1gr-text {
  color: var(--color-ink-2);
}

/* ---------- Related ---------- */
.ir-related {
  background: var(--color-paper);
}
.ir-related .services-grid {
  grid-template-columns: repeat(3, minmax(0, 1fr));
}

/* ---------- Final CTA ---------- */
.ir-cta h2 {
  text-wrap: balance;
  max-width: 22ch;
}
.ir-cta .link-call {
  color: color-mix(in srgb, var(--color-surface) 88%, transparent);
}
.ir-cta .link-call svg {
  color: var(--color-accent-bright);
}
.ir-cta__ring {
  inset-inline-start: 55%;
  inset-block-end: -200px;
  border-color: var(--color-success);
}
.ir-updated {
  margin-block-start: var(--space-6);
  font-size: var(--fs-small);
}

/* ---------- Responsive ---------- */
@media (max-width: 1100px) {
  .ir-almanac {
    grid-template-columns: repeat(2, minmax(0, 1fr));
  }
  .ir-almanac__item:nth-child(even) {
    margin-block-start: 0;
  }
}
@media (max-width: 1000px) {
  .ir-yards__head,
  .ir-walk {
    grid-template-columns: 1fr;
  }
  .ir-related .services-grid {
    grid-template-columns: repeat(2, minmax(0, 1fr));
  }
}
@media (max-width: 900px) {
  .ir-expert__grid,
  .ir-proof__row {
    grid-template-columns: 1fr;
  }
  .ir-spread {
    margin-inline-end: var(--space-4);
  }
  .ir-spread__img {
    aspect-ratio: 4 / 3;
  }
  .ir-spread figcaption {
    position: static;
    max-width: none;
    margin-block-start: var(--space-3);
  }
  .ir-repairs {
    grid-template-columns: 1fr;
  }
}
@media (max-width: 600px) {
  .ir-almanac,
  .ir-related .services-grid {
    grid-template-columns: 1fr;
  }
  .ir-zones__wrap {
    padding: var(--space-3);
  }
  .ir-zones__bar {
    flex-wrap: wrap;
  }
}
@media (prefers-reduced-motion: reduce) {
  .ir-hero .hero-bg img,
  .ir-zones__bar span:last-child::before,
  .ir-steps::before {
    animation: none;
  }
  .ir-steps::before {
    --ir-drop: 100%;
  }
  .ir-almanac__item,
  .ir-repair,
  .ir-table tbody th,
  .ir-table tbody td {
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
<section class="hero hero--photo hero--interior ir-hero" aria-labelledby="ir-h1">
  <div class="hero-bg"><?php echo heroPictureTag($heroImage, $heroAlt); ?></div>
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
            <li><span aria-current="page">Sprinkler &amp; Irrigation Repair</span></li>
          </ol>
        </nav>
        <span class="eyebrow">Sprinklers · <?php echo h($address['city'] . ', ' . $address['state']); ?></span>
        <h1 class="hero-title" id="ir-h1">Sprinkler &amp; Irrigation Repair from a San Angelo Plumber</h1>
        <p class="hero-answer">Rock'n G Plumbing repairs lawn sprinkler and irrigation systems in San Angelo, Christoval and nearby towns: broken heads, leaking buried lines, valves that stick and zones that won't run. The work is done under master plumber Eli Gomez's Texas license M-44822, and the estimate is free.</p>
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
        <h2 id="hero-form-title">Need a sprinkler zone fixed?</h2>
        <p class="hero-form-tagline">Tell us which zones act up. No obligation.</p>
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

<article class="ir-article" aria-labelledby="ir-h1">

  <!-- ============ SIGNATURE A: SAN ANGELO YARDS ============ -->
  <section class="section ir-yards" aria-labelledby="ir-yards-title">
    <div class="container-wide">
      <div class="ir-yards__head">
        <div class="section-head reveal-left">
          <span class="eyebrow">San Angelo yards</span>
          <h2 id="ir-yards-title">What is hard on irrigation systems in San Angelo yards?</h2>
          <p class="answer-block">Heat, hard water and the occasional hard freeze are what wear irrigation systems down around San Angelo. Summer puts the most hours on every zone, mineral scale clogs nozzles year-round, and a freeze can crack piping that shows no damage until spring.</p>
        </div>
        <p class="reveal-right">The Concho Valley's long summers mean a sprinkler system in San Angelo, Christoval or Grape Creek does its heaviest work in the months when a leak costs the most. Knowing what usually goes wrong, and when, makes it easier to spot a problem early.</p>
      </div>
      <ul class="ir-almanac">
        <?php foreach ($irConditions as $i => $cond): ?>
        <li class="ir-almanac__item reveal-up reveal-delay-<?php echo ($i % 4) + 1; ?>">
          <span class="ir-almanac__icon"><?php echo icon($cond['icon'], 26); ?></span>
          <span class="ir-almanac__tag"><?php echo h($cond['tag']); ?></span>
          <h3><?php echo h($cond['title']); ?></h3>
          <p><?php echo h($cond['body']); ?></p>
        </li>
        <?php endforeach; ?>
      </ul>
    </div>
  </section>

  <!-- ============ SIGNATURE B: ZONE TROUBLESHOOTING ============ -->
  <section class="section ir-zones" aria-labelledby="ir-zones-title">
    <div class="container">
      <div class="section-head reveal-up">
        <span class="eyebrow">Troubleshoot</span>
        <h2 id="ir-zones-title">Why is one lawn sprinkler zone acting up?</h2>
        <p class="answer-block">When only one sprinkler zone misbehaves, the problem is usually inside that zone: a broken head, a leaking line, a clogged nozzle or a sticking valve. If every zone acts up at once, look at the water supply, the main line or the controller instead.</p>
      </div>
      <div class="ir-zones__wrap reveal-scale">
        <div class="ir-zones__bar" aria-hidden="true"><span>Zone check</span><span>Run one zone at a time</span></div>
        <table class="ir-table">
          <caption>General sprinkler troubleshooting. Turn the water off at the controller before digging around any head or line.</caption>
          <thead>
            <tr><th scope="col">What the zone does</th><th scope="col">Likely cause</th><th scope="col">What you can check first</th></tr>
          </thead>
          <tbody>
            <?php foreach ($irZones as $zone): ?>
            <tr>
              <th scope="row"><span><?php echo icon($zone['icon'], 18); ?><?php echo h($zone['symptom']); ?></span></th>
              <td data-label="Likely cause"><?php echo h($zone['cause']); ?></td>
              <td data-label="Check first"><?php echo h($zone['check']); ?></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <div class="ir-zones__tip reveal-up">
        <?php echo icon('info', 20); ?>
        <p>A water bill that jumps while the yard looks normal may not be the sprinklers at all. A leak under the house slab or in the main water line shows up the same way on the bill; see <a href="/services/leak-detection-repair/">leak detection and slab leak repair</a>.</p>
      </div>
    </div>
  </section>

  <!-- ============ EXPERT POSITIONING ============ -->
  <section class="section slant-top ir-expert" aria-labelledby="ir-expert-title">
    <div class="container">
      <div class="ir-expert__grid">
        <figure class="ir-spread reveal-left">
          <div class="ir-spread__img">
            <?php echo pictureTag('rockn-crew-excavation', "Rock'n G crew with a mini excavator, shovels and a pickaxe digging out a buried line in a paved lot", '(max-width: 900px) 90vw, 440px'); ?>
          </div>
          <figcaption>A Rock'n G crew digging out a buried line with a mini excavator and hand tools. Reaching a pipe underground takes the same careful digging whatever the line carries.</figcaption>
        </figure>
        <div class="ir-expert__copy reveal-right">
          <span class="eyebrow">Who does the work</span>
          <h2 id="ir-expert-title">Why call a plumber for sprinkler repair in San Angelo?</h2>
          <p class="answer-block">A sprinkler system runs on your home's water supply, so a cracked buried line is a water leak that keeps running up the bill until someone digs it out and repairs it. Rock'n G Plumbing does that digging and pipe repair as everyday plumbing work.</p>
          <p>Rock'n G is <?php echo h($ownerName); ?>'s company, and plumbing has been his family's trade for three generations. The crew works on residential, commercial and industrial properties around Tom Green County, from a single broken head in a Christoval yard to a leaking line under a commercial lawn.</p>
          <p>Every repair runs under Texas Master Plumber License M-44822, with <?php echo h($license['holder']); ?> as Responsible Master Plumber. Hard water scale on your heads is also a sign of what it does to fixtures and water heaters inside; <a href="/services/water-filtration-softeners/">water softeners and filtration</a> deal with it at the source.</p>
          <ul class="ir-facts">
            <li><?php echo icon('badge-check', 16); ?>License <?php echo h($license['number']); ?></li>
            <li><?php echo icon('users', 16); ?>Three generations</li>
            <li><?php echo icon('shield-check', 16); ?>Military discounts</li>
          </ul>
        </div>
      </div>
    </div>
  </section>

  <!-- ============ SERVICE BREAKDOWN + PROCESS ============ -->
  <section class="section ir-scope" aria-labelledby="ir-scope-title">
    <div class="container">
      <div class="section-head reveal-up">
        <span class="eyebrow">What's included</span>
        <h2 id="ir-scope-title">What sprinkler and irrigation repairs does Rock'n G handle?</h2>
        <p class="answer-block">Rock'n G Plumbing repairs the parts of an irrigation system that fail most: spray heads and rotors, buried lines and fittings, and zone valves. When the cause is not obvious, the repair starts with troubleshooting the zone back to the valve, the line or the controller setting.</p>
      </div>
      <div class="ir-repairs">
        <?php foreach ($irRepairs as $i => $rep): ?>
        <div class="ir-repair card-tint-<?php echo ($i % 3) + 1; ?> reveal-up reveal-delay-<?php echo ($i % 3) + 1; ?>">
          <span class="ir-repair__icon"><?php echo icon($rep['icon'], 22); ?></span>
          <h3><?php echo h($rep['title']); ?></h3>
          <p><?php echo h($rep['body']); ?></p>
        </div>
        <?php endforeach; ?>
      </div>

      <div class="ir-walk">
        <div class="ir-walk__aside reveal-left">
          <span class="eyebrow">Step by step</span>
          <h3>How a Rock'n G sprinkler repair runs</h3>
          <p>Before you call, run each zone for a minute and note which ones misbehave and what you see. That list makes the first conversation much shorter.</p>
          <a class="link-call" href="<?php echo h($telHref); ?>"><?php echo icon('phone', 18); ?><?php echo h($phoneFmt); ?></a>
        </div>
        <ol class="ir-steps">
          <li class="reveal-right reveal-delay-1"><b>Call or send the form</b><span>Reach the office at <?php echo h($phoneFmt); ?>, <?php echo h($weekday[0]); ?> <?php echo h($weekday[1]); ?>. Say which zones act up and what each one is doing.</span></li>
          <li class="reveal-right reveal-delay-2"><b>Estimate at no charge</b><span>Rock'n G prices the repair before any digging starts.</span></li>
          <li class="reveal-right reveal-delay-3"><b>You decide</b><span>Go ahead when the price and the plan make sense. Mention military service and ask how the discount applies.</span></li>
          <li class="reveal-right reveal-delay-4"><b>The repair</b><span>The heads, lines or valves are repaired under <?php echo h($ownerName); ?>'s Texas Master Plumber license.</span></li>
        </ol>
      </div>
    </div>
  </section>

  <!-- ============ PROOF ============ -->
  <section class="section on-dark edge-torn-top ir-proof" aria-labelledby="ir-proof-title">
    <span class="grain-layer" aria-hidden="true"></span>
    <div class="container">
      <div class="section-head reveal-up">
        <span class="eyebrow">Proof</span>
        <h2 id="ir-proof-title">How can you check Rock'n G before hiring a plumber for sprinkler work?</h2>
        <p class="answer-block">Check Rock'n G Plumbing by reading its Google reviews and looking up license M-44822 with the Texas State Board of Plumbing Examiners. On Google, Rock'n G is rated <?php echo h(number_format((float) $gbp['rating'], 1)); ?> out of 5 from <?php echo (int) $gbp['reviews_count']; ?> customer reviews.</p>
      </div>
      <div class="ir-proof__row">
        <div class="ir-proof__card reveal-left">
          <h3>License on record</h3>
          <dl>
            <dt>License</dt><dd><?php echo h($license['label']); ?></dd>
            <dt>Holder</dt><dd><?php echo h($license['holder']); ?></dd>
            <dt>Role</dt><dd><?php echo h($license['role']); ?></dd>
            <dt>Issued by</dt><dd><?php echo h($license['issuer']); ?></dd>
          </dl>
        </div>
        <div class="ir-proof__card reveal-right">
          <h3>Google reviews</h3>
          <p>The reviews on Rock'n G Plumbing's Google Business Profile come from customers. If Rock'n G fixed your sprinklers, a few lines about the job help the next homeowner in your neighborhood.</p>
          <div class="actions">
            <a class="btn btn-accent" href="<?php echo h($gbp['profile_url']); ?>" target="_blank" rel="noopener">Read reviews on Google</a>
            <a class="btn btn-outline-white" href="<?php echo h($gbp['review_url']); ?>" target="_blank" rel="noopener">Leave a review</a>
          </div>
        </div>
      </div>
      <?php
      require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/google-reviews.php';
      $irReviews = p1_google_reviews($slug, ['limit' => 3, 'heading' => '', 'summary' => false]);
      if ($irReviews !== ''): ?>
      <div class="ir-proof__reviews"><?php echo $irReviews; ?></div>
      <?php endif; ?>
    </div>
  </section>

  <!-- ============ RELATED SERVICES (required services-grid) ============ -->
  <section class="section ir-related" aria-label="Related plumbing services">
    <div class="container">
      <div class="section-title reveal-up">
        <span class="eyebrow-label">What We Do</span>
        <h2>Which other <span class="text-accent">plumbing services</span> go with sprinkler repair?</h2>
        <p class="hero-answer">Sprinkler problems often point to three related jobs. A high water bill with a dry yard can be a hidden leak in the main line, the scale on your heads is the same hard water hitting your fixtures, and water pooling near the house can be a drain problem rather than a sprinkler. Rock'n G Plumbing handles all three.</p>
        <span class="section-subtitle">Leaks · Hard water · Drains</span>
        <p class="prose">See the complete list of <a href="/services/">plumbing services Rock'n G offers in San Angelo</a>.</p>
      </div>
      <div class="services-grid">
        <?php echo renderServiceCards(['leak-detection-repair', 'water-filtration-softeners', 'drain-cleaning'], relatedServiceSizes(600)); ?>
      </div>
    </div>
  </section>

  <!-- ============ FINAL CTA ============ -->
  <section class="closing-cta texture-grain edge-curve-top ir-cta" aria-labelledby="ir-cta-title">
    <span class="grain-layer" aria-hidden="true"></span>
    <span class="floating-ring ir-cta__ring" aria-hidden="true"></span>
    <div class="container">
      <div class="reveal-left">
        <span class="eyebrow">Stop the soggy spot</span>
        <h2 id="ir-cta-title">Ready for a free sprinkler repair estimate in San Angelo?</h2>
        <p>Call Rock'n G Plumbing at <?php echo h($phoneFmt); ?>, <?php echo h($weekday[0]); ?> <?php echo h($weekday[1]); ?>, or send the form and the office will follow up during business hours. A leaking zone keeps running every time the controller turns it on.</p>
        <p class="ir-updated">Last updated: <?php echo date('F Y'); ?></p>
      </div>
      <div class="actions reveal-right">
        <a class="btn btn-accent btn-lg" href="/contact/" data-open-estimate>Get a Free Sprinkler Repair Estimate</a>
        <a class="link-call" href="<?php echo h($telHref); ?>"><?php echo icon('phone', 18); ?>Call <?php echo h($phoneFmt); ?></a>
      </div>
    </div>
  </section>

</article>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>
