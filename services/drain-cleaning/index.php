<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/functions.php';
?>
<?php
/**
 * services/drain-cleaning/index.php — Drain Cleaning & Clog Removal (Phase 4, premium editorial layout).
 * Every business fact traces to build-plan.json / includes/config.php. What causes clogs and how clearing methods
 * compare is written as general knowledge, never as a Rock'n G promise. No equipment claims (cable machines, jetters,
 * cameras) on this page — camera inspection belongs to the sewer page, which this page links to. Prices, equipment and
 * response times are asked in /content-confirmations.json instead of written here.
 * Signature section: the blueprint "where is the clog?" board (room branches feeding one main line).
 * SEO brief: no FAQ section/schema, no emergency/24-hour wording.
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/service-cards.php';

$svc         = serviceBySlug('drain-cleaning');
$pageType    = 'service';
$serviceSlug = $svc['slug'];
$pageTitle       = "Drain Cleaning Plumber in San Angelo, TX | Rock'n G";
$pageDescription = "Drain cleaning plumber in San Angelo, TX • Kitchen, bath & laundry clogs cleared • Master Plumber M-44822, 3 generations, free estimates. Call (325) 650-7000.";
$canonicalUrl    = $siteUrl . '/services/drain-cleaning/';
$currentPage     = 'services/drain-cleaning';
$breadcrumbs     = [['name' => 'Services', 'url' => '/services/'], ['name' => $svc['name'], 'url' => '/services/drain-cleaning/']];

$heroImage   = 'rockn-under-floor-drain-lines';
$heroAlt     = 'White PVC drain lines running down through the opened floor framing of a home under repair';
$heroPreload = ['srcset' => imageVariantSrcset($heroImage, 'avif'), 'sizes' => '100vw'];
$ogImage     = '/assets/images/' . $heroImage . '.jpg';

$telHref  = 'tel:' . $phoneE164;
$phoneFmt = formatPhone($phone);
$weekday  = hoursGroups($hours)[0];      // ['Mon–Fri', '8:00 AM – 6:00 PM']
$licenseExpires = date('F j, Y', strtotime($license['expires']));
$sewerSvc = serviceBySlug('sewer-line-repair');

// Where is the clog? (general knowledge: which fixture → usual cause)
$dcRooms = [
    ['icon' => 'home', 'title' => 'Kitchen sink drains', 'tag' => 'Grease + scraps',
     'body'  => 'Grease, oil and food scraps cool and stick to the pipe wall, and coffee grounds settle in the trap. A kitchen line that slows a little more every month is building a grease plug.'],
    ['icon' => 'droplets', 'title' => 'Bathroom sinks and tubs', 'tag' => 'Hair + soap',
     'body'  => 'Hair binds with soap scum just below the stopper or in the trap. These clogs sit close to the fixture, which is why they come back when only the top is cleared.'],
    ['icon' => 'waves', 'title' => 'Toilets', 'tag' => 'Wipes + paper',
     'body'  => 'Wipes labeled flushable, paper towels, too much paper at once, or a small object. A toilet that clogs again and again can also point to trouble further down the line.'],
    ['icon' => 'wind', 'title' => 'Laundry drains', 'tag' => 'Lint + volume',
     'body'  => 'Lint from the washer builds up in the standpipe and trap. A standpipe that overflows during the drain cycle is partly blocked or cannot keep up with the flow.'],
];

// Clearing methods (general knowledge)
$dcMethods = [
    ['method' => 'Plunger', 'best' => 'A clog near the opening of a toilet or sink', 'limits' => 'Cannot reach deep or main-line clogs', 'watch' => 'Use a flange plunger on toilets and a cup plunger on sinks'],
    ['method' => 'Chemical drain cleaner', 'best' => 'Light soap or hair buildup', 'limits' => 'Often burns a channel through instead of clearing; does nothing for wipes or roots', 'watch' => 'Hard on older pipes and dangerous if it splashes back; tell the plumber if it is still in the drain'],
    ['method' => 'Boiling water', 'best' => 'Fresh grease right at the drain', 'limits' => 'Grease hardens again further down the line', 'watch' => 'Can soften plastic pipe joints and crack porcelain'],
    ['method' => 'Hand auger (small snake)', 'best' => 'Hair clogs in a sink or tub trap', 'limits' => 'Short reach and easy to scratch a fixture', 'watch' => 'Not meant for a main line'],
    ['method' => "Mechanical clearing by a plumber", 'best' => 'Deep, repeat and main-line blockages', 'limits' => 'A broken or root-filled pipe needs repair, not just clearing', 'watch' => 'Repeat clogs in one line are worth a camera inspection'],
];

// Keep out of drains (general knowledge)
$dcKeepOut = ['Cooking grease, fat and oil', 'Coffee grounds and eggshells', 'Stringy peels like celery, potato and onion skins', 'Wipes, including ones labeled flushable', 'Paper towels, cotton balls and floss', 'Hair (a strainer in the tub catches it)'];

// Service schema (provider = the Plumber node head.php prints on every page)
$serviceNode = generateServiceSchema($svc);
$serviceNode['@id']         = absUrl(serviceUrl($svc)) . '#service-' . $svc['slug'];
$serviceNode['serviceType'] = 'Drain cleaning and clog removal';
$serviceNode['description'] = "Drain cleaning and clog removal for kitchen, bathroom, toilet and laundry drains in San Angelo, TX and nearby Tom Green County towns, by Rock'n G Plumbing under Texas Master Plumber License M-44822.";
$serviceNode['image']       = absUrl('/assets/images/' . $heroImage . '.jpg');
$serviceNode['hasOfferCatalog'] = [
    '@type' => 'OfferCatalog', 'name' => 'Drain cleaning services',
    'itemListElement' => array_map(fn($r) => ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => $r['title'] . ' cleared']], $dcRooms),
];
$serviceNode["@context"] = 'https://schema.org';   // explicit (generateServiceSchema sets it too)
$schemaMarkup = [$serviceNode];

ob_start();
?>
<style>
/* =====================================================================
   Drain Cleaning & Clog Removal — page-only CSS (dc- prefix)
   Tokens from framework.css; no raw colors, shadows or spacing.
   ===================================================================== */
@property --dc-flow {
  syntax: '<length>';
  inherits: false;
  initial-value: 0px;
}

/* ---------- Hero: layered photo (C1) ---------- */
.dc-hero {
  padding-block-end: var(--space-16);
}
.dc-hero .hero-bg {
  overflow: hidden;
}
.dc-hero .hero-bg picture,
.dc-hero .hero-bg img {
  width: 100%;
  height: 100%;
}
.dc-hero .hero-bg img {
  object-fit: cover;
  object-position: 55% 62%;
  animation: dc-sink 22s var(--ease) both;
}
.dc-hero::before {
  content: "";
  position: absolute;
  inset: 0;
  z-index: -1;
  pointer-events: none;
  background:
    linear-gradient(90deg, color-mix(in srgb, var(--color-primary-deep) 55%, transparent) 0%, transparent 60%),
    radial-gradient(ellipse 50% 60% at 85% 110%, color-mix(in srgb, var(--color-accent) 24%, transparent), transparent 70%);
}
.dc-hero::after {
  content: "";
  position: absolute;
  inset: 0;
  z-index: -1;
  pointer-events: none;
  opacity: .22;
  mix-blend-mode: overlay;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='160' height='160'%3E%3Cfilter id='g'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.75' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23g)'/%3E%3C/svg%3E");
}
.dc-hero .breadcrumb {
  color: color-mix(in srgb, var(--color-surface) 72%, transparent);
  margin-block-end: var(--space-2);
}
.dc-hero .breadcrumb li {
  display: inline-flex;
  align-items: center;
}
.dc-hero .breadcrumb a:hover {
  color: var(--color-accent-bright);
}
.dc-hero .breadcrumb [aria-current="page"] {
  color: var(--color-surface);
}
.dc-hero .hero-title {
  max-width: 17ch;
  text-wrap: balance;
}
.dc-hero .hero-answer {
  max-width: 36rem;
}
.dc-hero .hero-chips li {
  backdrop-filter: blur(6px);
  -webkit-backdrop-filter: blur(6px);
}
.dc-hero .hero-form-open {
  background: var(--color-accent);
  color: var(--color-white);
}
.dc-hero .hero-form-card {
  border-block-start: 4px solid var(--color-primary);
}
.dc-hero .hero-form-card h2 {
  text-wrap: balance;
}
.dc-hero .hero-form .consent {
  margin-block: var(--space-1);
}
@keyframes dc-sink {
  from { transform: scale(1.1) translateY(-2%); }
  to   { transform: scale(1) translateY(0); }
}

/* ---------- Article ---------- */
.dc-article > section h2 {
  text-wrap: balance;
}
.dc-article .answer-block {
  font-size: var(--fs-lead);
  line-height: 1.55;
}

/* ---------- Signature: blueprint clog board ---------- */
.dc-board {
  background:
    repeating-linear-gradient(0deg, color-mix(in srgb, var(--color-primary) 6%, transparent) 0 1px, transparent 1px 32px),
    repeating-linear-gradient(90deg, color-mix(in srgb, var(--color-primary) 6%, transparent) 0 1px, transparent 1px 32px),
    var(--color-paper);
}
.dc-board .section-head {
  max-width: 64ch;
}
.dc-plan {
  position: relative;
  display: grid;
  grid-template-columns: repeat(4, minmax(0, 1fr));
  gap: var(--space-5);
  margin-block-start: var(--space-10);
  padding-block-end: var(--space-12);
}
.dc-room {
  position: relative;
  display: grid;
  align-content: start;
  gap: var(--space-2);
  padding: var(--space-6) var(--space-5);
  background: var(--color-surface);
  border: 2px solid var(--color-primary);
  border-radius: var(--radius-sm);
  box-shadow: 6px 6px 0 color-mix(in srgb, var(--color-primary) 14%, transparent);
  transition: transform var(--transition), box-shadow var(--transition);
}
.dc-room:hover {
  transform: translate(-2px, -2px);
  box-shadow: 10px 10px 0 color-mix(in srgb, var(--color-accent) 30%, transparent);
}
.dc-room::after {
  content: "";
  position: absolute;
  inset-block-start: 100%;
  inset-inline-start: 50%;
  width: 6px;
  height: calc(var(--space-12) + 2px);
  translate: -50% 0;
  background: var(--color-secondary);
  border-radius: 0 0 var(--radius-sm) var(--radius-sm);
}
.dc-room__tag {
  justify-self: start;
  font-family: var(--font-accent);
  font-size: var(--fs-eyebrow);
  letter-spacing: .14em;
  text-transform: uppercase;
  color: var(--color-accent-dark);
  border: 1px dashed var(--color-accent-dark);
  border-radius: var(--radius-sm);
  padding: var(--space-1) var(--space-2);
}
.dc-room svg {
  color: var(--color-primary);
}
.dc-room h3 {
  font-size: var(--fs-h3);
  text-wrap: balance;
}
.dc-room p {
  margin: 0;
  font-size: var(--fs-small);
  color: var(--color-ink-2);
}
.dc-main {
  position: relative;
  display: grid;
  grid-template-columns: auto 1fr auto;
  gap: var(--space-4);
  align-items: center;
  padding: var(--space-6) var(--space-8);
  border-radius: var(--radius-full);
  color: var(--color-surface);
  background: linear-gradient(90deg, var(--color-secondary), var(--color-primary) 30%, var(--color-primary-deep));
  box-shadow: var(--shadow-lg);
  overflow: hidden;
  isolation: isolate;
}
.dc-main::before {
  content: "";
  position: absolute;
  inset-inline: 0;
  inset-block-end: var(--space-2);
  height: 3px;
  z-index: -1;
  background: repeating-linear-gradient(90deg, color-mix(in srgb, var(--color-accent-bright) 60%, transparent) 0 18px, transparent 18px 34px);
  background-position: var(--dc-flow) 0;
  opacity: .5;
  animation: dc-flow 2.6s linear infinite;
}
@keyframes dc-flow {
  to { --dc-flow: 34px; }
}
.dc-main svg {
  color: var(--color-accent-bright);
}
.dc-main h3 {
  color: var(--color-surface);
  font-size: var(--fs-h3);
}
.dc-main p {
  margin: var(--space-1) 0 0;
  color: color-mix(in srgb, var(--color-surface) 82%, transparent);
  font-size: var(--fs-small);
}
.dc-main a {
  white-space: nowrap;
}

/* ---------- Methods table (slant edge) ---------- */
.dc-methods {
  background: var(--color-surface);
}
.dc-methods__wrap {
  container-type: inline-size;
  container-name: dctable;
  margin-block-start: var(--space-8);
}
.dc-table {
  width: 100%;
  border-collapse: separate;
  border-spacing: 0 var(--space-2);
  font-size: var(--fs-small);
}
.dc-table caption {
  caption-side: bottom;
  text-align: start;
  color: var(--color-muted);
  padding-block-start: var(--space-2);
}
.dc-table thead th {
  text-align: start;
  padding: var(--space-2) var(--space-4);
  font-family: var(--font-accent);
  font-size: var(--fs-eyebrow);
  letter-spacing: .14em;
  text-transform: uppercase;
  color: var(--color-muted);
}
.dc-table tbody th,
.dc-table tbody td {
  padding: var(--space-4);
  text-align: start;
  vertical-align: top;
  background: var(--color-paper);
}
.dc-table tbody th {
  font-family: var(--font-heading);
  font-weight: 700;
  color: var(--color-ink);
  border-start-start-radius: var(--radius);
  border-end-start-radius: var(--radius);
  width: 22%;
}
.dc-table tbody td {
  color: var(--color-ink-2);
}
.dc-table tbody td:last-child {
  border-start-end-radius: var(--radius);
  border-end-end-radius: var(--radius);
}
.dc-table tbody tr:last-child th,
.dc-table tbody tr:last-child td {
  background: color-mix(in srgb, var(--color-accent) 12%, var(--color-surface));
}
.dc-table tbody tr:last-child th {
  box-shadow: inset 4px 0 0 var(--color-accent);
}
.dc-table tbody tr:nth-child(2) th {
  box-shadow: inset 4px 0 0 var(--color-warning);
}
@container dctable (max-width: 700px) {
  .dc-table thead {
    position: absolute;
    width: 1px;
    height: 1px;
    overflow: hidden;
    clip-path: inset(50%);
  }
  .dc-table,
  .dc-table tbody,
  .dc-table tr,
  .dc-table th,
  .dc-table td {
    display: block;
    width: auto;
  }
  .dc-table tbody th {
    width: auto;
  }
  .dc-table caption {
    display: block;
    padding-block: 0 var(--space-3);
  }
  .dc-table tbody tr {
    margin-block-end: var(--space-3);
    border-radius: var(--radius);
    overflow: hidden;
  }
  .dc-table tbody th,
  .dc-table tbody td {
    border-radius: 0;
    padding-block: var(--space-2);
  }
  .dc-table tbody td::before {
    content: attr(data-label) ": ";
    font-weight: 700;
    color: var(--color-ink);
  }
}

/* ---------- Expert: magazine photo spread ---------- */
.dc-expert {
  background: var(--color-paper-2);
}
.dc-expert__grid {
  display: grid;
  grid-template-columns: minmax(0, 2fr) minmax(0, 3fr);
  gap: var(--space-12);
  align-items: center;
}
.dc-spread {
  position: relative;
  margin: 0;
}
.dc-spread__img {
  aspect-ratio: 4 / 5;
  border-radius: var(--radius-lg);
  overflow: hidden;
  box-shadow: var(--shadow-xl);
  clip-path: polygon(0 0, 100% 0, 100% 92%, 88% 100%, 0 100%);
}
.dc-spread__img picture,
.dc-spread__img img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}
.dc-spread figcaption {
  position: absolute;
  inset-inline-end: calc(-1 * var(--space-6));
  inset-block-end: var(--space-8);
  max-width: 260px;
  padding: var(--space-4);
  border-radius: var(--radius);
  background: var(--color-surface);
  border-inline-start: 4px solid var(--color-accent);
  box-shadow: var(--shadow-lg);
  font-size: var(--fs-small);
  color: var(--color-ink-2);
}
.dc-expert__copy {
  display: grid;
  gap: var(--space-4);
}
.dc-facts {
  list-style: none;
  margin: 0;
  padding: 0;
  display: grid;
  grid-template-columns: repeat(3, minmax(0, 1fr));
  gap: var(--space-4);
}
.dc-facts li {
  display: grid;
  align-content: start;
  gap: var(--space-2);
  padding-block-start: var(--space-4);
  border-block-start: 3px solid var(--color-primary);
}
.dc-facts li:nth-child(2) {
  border-block-start-color: var(--color-accent);
}
.dc-facts b {
  font-family: var(--font-heading);
  color: var(--color-ink);
}
.dc-facts span {
  font-size: var(--fs-small);
  color: var(--color-ink-2);
}
.dc-facts a {
  color: var(--color-primary);
  font-weight: 600;
}
.dc-lic {
  font-family: var(--font-accent);
  font-size: 1.6rem;
  line-height: 1;
  letter-spacing: .02em;
  color: var(--color-primary);
}

/* ---------- Process: vertical timeline + aside (wave edge) ---------- */
.dc-process {
  background: var(--color-surface);
}
.dc-process__grid {
  display: grid;
  grid-template-columns: minmax(0, 3fr) minmax(0, 2fr);
  gap: var(--space-12);
  align-items: start;
  margin-block-start: var(--space-8);
}
.dc-timeline {
  list-style: none;
  margin: 0;
  padding: 0 0 0 var(--space-10);
  display: grid;
  gap: var(--space-8);
  position: relative;
  counter-reset: dc-step;
}
.dc-timeline::before {
  content: "";
  position: absolute;
  inset-block: var(--space-2);
  inset-inline-start: 15px;
  width: 4px;
  border-radius: var(--radius-full);
  background: linear-gradient(180deg, var(--color-accent), var(--color-primary));
}
.dc-timeline li {
  counter-increment: dc-step;
  position: relative;
  display: grid;
  gap: var(--space-1);
}
.dc-timeline li::before {
  content: counter(dc-step);
  position: absolute;
  inset-inline-start: calc(-1 * var(--space-10));
  inset-block-start: 0;
  width: 34px;
  height: 34px;
  display: grid;
  place-items: center;
  border-radius: var(--radius-full);
  font-family: var(--font-accent);
  font-weight: 700;
  color: var(--color-primary);
  background: var(--color-surface);
  border: 3px solid var(--color-accent);
}
.dc-timeline b {
  font-family: var(--font-heading);
  font-size: 1.1rem;
  color: var(--color-ink);
}
.dc-timeline span {
  color: var(--color-ink-2);
  font-size: var(--fs-small);
  max-width: 52ch;
}
.dc-timeline a {
  color: var(--color-primary);
  font-weight: 600;
}
.dc-aside {
  display: grid;
  gap: var(--space-4);
  position: sticky;
  inset-block-start: calc(var(--nav-height) + var(--space-6));
}
.dc-aside__img {
  aspect-ratio: 4 / 3;
  border-radius: var(--radius-lg);
  overflow: hidden;
  box-shadow: var(--shadow);
}
.dc-aside__img picture,
.dc-aside__img img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}
.dc-aside__cap {
  margin: calc(-1 * var(--space-2)) 0 0;
  font-size: var(--fs-small);
  color: var(--color-muted);
}
.dc-keepout {
  padding: var(--space-5);
  border-radius: var(--radius-lg);
  background: color-mix(in srgb, var(--color-warning) 10%, var(--color-surface));
  border: 1px solid color-mix(in srgb, var(--color-warning) 40%, var(--color-line));
}
.dc-keepout h3 {
  font-size: 1.05rem;
  margin-block-end: var(--space-3);
}
.dc-keepout ul {
  list-style: none;
  margin: 0;
  padding: 0;
  display: grid;
  gap: var(--space-2);
}
.dc-keepout li {
  display: flex;
  gap: var(--space-2);
  align-items: flex-start;
  font-size: var(--fs-small);
  color: var(--color-ink);
}
.dc-keepout li svg {
  flex: 0 0 auto;
  color: var(--color-warning);
  margin-block-start: var(--space-1);
}

/* ---------- Proof (dark, torn edge) ---------- */
.dc-proof .answer-block {
  background: color-mix(in srgb, var(--color-surface) 6%, transparent);
  color: color-mix(in srgb, var(--color-surface) 88%, transparent);
}
.dc-proof__bar {
  display: grid;
  grid-template-columns: minmax(0, 1fr) auto;
  gap: var(--space-6);
  align-items: center;
  margin-block-start: var(--space-8);
  padding: var(--space-6);
  border-radius: var(--radius-lg);
  border: 1px solid color-mix(in srgb, var(--color-surface) 16%, transparent);
  background: color-mix(in srgb, var(--color-surface) 6%, transparent);
}
.dc-proof__bar dl {
  margin: 0;
  display: flex;
  flex-wrap: wrap;
  gap: var(--space-3) var(--space-8);
  font-size: var(--fs-small);
}
.dc-proof__bar dl div {
  display: grid;
  gap: var(--space-1);
}
.dc-proof__bar dt {
  font-family: var(--font-accent);
  font-size: var(--fs-eyebrow);
  letter-spacing: .12em;
  text-transform: uppercase;
  color: color-mix(in srgb, var(--color-surface) 60%, transparent);
}
.dc-proof__bar dd {
  margin: 0;
  color: var(--color-surface);
  font-weight: 600;
}
.dc-proof__bar .actions {
  display: flex;
  flex-wrap: wrap;
  gap: var(--space-3);
}
.dc-proof__reviews {
  margin-block-start: var(--space-8);
}
.dc-proof__reviews .p1gr-text {
  color: var(--color-ink-2);
}

/* ---------- Related services ---------- */
.dc-related {
  background: var(--color-paper);
}
.dc-related .services-grid {
  grid-template-columns: repeat(3, minmax(0, 1fr));
}
.dc-related .service-card-with-image h3 {
  text-wrap: balance;
}

/* ---------- Final CTA: stepped edge ---------- */
.dc-cta {
  clip-path: polygon(0 2.5vw, 33% 2.5vw, 33% 0, 67% 0, 67% 2.5vw, 100% 2.5vw, 100% 100%, 0 100%);
  padding-block-start: calc(var(--section-pad) + 2.5vw);
}
.dc-cta .container {
  align-items: center;
}
.dc-cta h2 {
  text-wrap: balance;
  max-width: 22ch;
}
.dc-cta .link-call {
  color: color-mix(in srgb, var(--color-surface) 88%, transparent);
}
.dc-cta .link-call svg {
  color: var(--color-accent-bright);
}
.dc-cta__ring {
  inset-inline-start: 45%;
  inset-block-end: -200px;
}
.dc-updated {
  margin-block-start: var(--space-6);
  font-size: var(--fs-small);
}

/* ---------- Responsive ---------- */
@media (max-width: 1000px) {
  .dc-plan {
    grid-template-columns: repeat(2, minmax(0, 1fr));
    padding-block-end: 0;
  }
  .dc-room::after {
    display: none;
  }
  .dc-main {
    margin-block-start: var(--space-5);
  }
  .dc-related .services-grid {
    grid-template-columns: repeat(2, minmax(0, 1fr));
  }
}
@media (max-width: 900px) {
  .dc-expert__grid,
  .dc-process__grid {
    grid-template-columns: 1fr;
  }
  .dc-spread figcaption {
    position: static;
    max-width: none;
    margin-block-start: var(--space-3);
  }
  .dc-spread__img {
    aspect-ratio: 4 / 3;
  }
  .dc-aside {
    position: static;
  }
  .dc-proof__bar {
    grid-template-columns: 1fr;
  }
}
@media (max-width: 640px) {
  .dc-plan,
  .dc-facts,
  .dc-related .services-grid {
    grid-template-columns: 1fr;
  }
  .dc-main {
    grid-template-columns: 1fr;
    border-radius: var(--radius-lg);
    padding: var(--space-6);
  }
}
@media (prefers-reduced-motion: reduce) {
  .dc-hero .hero-bg img,
  .dc-main::before {
    animation: none;
  }
  .dc-room {
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
<section class="hero hero--photo hero--interior dc-hero" aria-labelledby="dc-h1">
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
            <li><span aria-current="page">Drain Cleaning</span></li>
          </ol>
        </nav>
        <span class="eyebrow">Drains · <?php echo h($address['city'] . ', ' . $address['state']); ?></span>
        <h1 class="hero-title" id="dc-h1">San Angelo Plumber for Drain Cleaning and Clog Removal</h1>
        <p class="hero-answer">As a San Angelo plumber, Rock'n G Plumbing clears slow and backed-up kitchen, bathroom and laundry drains for homes and businesses from Christoval to Grape Creek. When several drains act up at once, the trouble is usually the main line, which Rock'n G also handles. Estimates are free.</p>
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
        <h2 id="hero-form-title">Need a drain cleared?</h2>
        <p class="hero-form-tagline">Tell us which drains are slow. No obligation.</p>
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

<article class="dc-article" aria-labelledby="dc-h1">

  <!-- ============ SIGNATURE: WHERE IS THE CLOG? ============ -->
  <section class="section slant-bottom dc-board" aria-labelledby="dc-board-title">
    <div class="container-wide">
      <div class="section-head reveal-up">
        <span class="eyebrow">Find the clog</span>
        <h2 id="dc-board-title">Where is the clog in your San Angelo home?</h2>
        <p class="answer-block">Which drain is slow tells you where the clog is and what caused it: grease in kitchen sinks, hair and soap in bathrooms, wipes in toilets, lint in laundry lines. If several drains are slow or gurgle at the same time, the blockage is usually further down, in the main sewer line.</p>
      </div>
      <div class="dc-plan">
        <?php foreach ($dcRooms as $i => $room): ?>
        <div class="dc-room reveal-up reveal-delay-<?php echo ($i % 3) + 1; ?>">
          <span class="dc-room__tag"><?php echo h($room['tag']); ?></span>
          <?php echo icon($room['icon'], 26); ?>
          <h3><?php echo h($room['title']); ?></h3>
          <p><?php echo h($room['body']); ?></p>
        </div>
        <?php endforeach; ?>
      </div>
      <div class="dc-main reveal-scale">
        <?php echo icon('route', 30); ?>
        <div>
          <h3>Several drains at once: the main line</h3>
          <p>Gurgling when the washer drains, water rising in the tub when the toilet flushes, or a sewage smell indoors all point past any one fixture to the main sewer line. That needs a camera look, not another plunger.</p>
        </div>
        <a class="btn btn-accent" href="<?php echo h(serviceUrl($sewerSvc)); ?>">Sewer line repair <?php echo icon('arrow-right', 18); ?></a>
      </div>
    </div>
  </section>

  <!-- ============ COMPARISON: CLEARING METHODS ============ -->
  <section class="section dc-methods" aria-labelledby="dc-methods-title">
    <div class="container">
      <div class="section-head reveal-up">
        <span class="eyebrow">Compare</span>
        <h2 id="dc-methods-title">Which drain-clearing method fits which clog?</h2>
        <p class="answer-block">A plunger handles most single-fixture clogs near the drain opening, while chemical cleaners and boiling water rarely clear more than a light buildup. Clogs that keep coming back, sit deep in the line or affect several drains need mechanical clearing by a plumber, and sometimes a camera inspection.</p>
      </div>
      <div class="dc-methods__wrap reveal-up">
        <table class="dc-table">
          <caption>General guidance for common household drains. When in doubt, stop before adding a second product to a drain that already has one in it.</caption>
          <thead>
            <tr><th scope="col">Method</th><th scope="col">Best for</th><th scope="col">Limits</th><th scope="col">Watch for</th></tr>
          </thead>
          <tbody>
            <?php foreach ($dcMethods as $m): ?>
            <tr><th scope="row"><?php echo h($m['method']); ?></th><td data-label="Best for"><?php echo h($m['best']); ?></td><td data-label="Limits"><?php echo h($m['limits']); ?></td><td data-label="Watch for"><?php echo h($m['watch']); ?></td></tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </section>

  <!-- ============ EXPERT POSITIONING ============ -->
  <section class="section edge-wave-top dc-expert" aria-labelledby="dc-expert-title">
    <div class="container">
      <div class="dc-expert__grid">
        <figure class="dc-spread reveal-left">
          <div class="dc-spread__img">
            <?php echo pictureTag('rockn-crew-excavation', "Rock'n G Plumbing crew in red company shirts working a dug-out line, with a mini excavator behind them", '(max-width: 900px) 92vw, 440px'); ?>
          </div>
          <figcaption>The Rock'n G crew digging down to a buried line. When clearing is not enough, the fix is reaching the pipe itself.</figcaption>
        </figure>
        <div class="dc-expert__copy reveal-right">
          <span class="eyebrow">Who does the work</span>
          <h2 id="dc-expert-title">Who clears your drain when you call Rock'n G Plumbing?</h2>
          <p class="answer-block">Drain calls at Rock'n G Plumbing are handled under Eli Gomez's Texas Master Plumber License M-44822, and Eli comes from three generations of plumbers. That matters on a drain call, because a stubborn clog is sometimes a sign of a sagging, cracked or root-filled pipe that has to be found and repaired.</p>
          <ul class="dc-facts">
            <li><span class="dc-lic"><?php echo h($license['number']); ?></span><b>One license on every job</b><span><?php echo h($license['role']); ?> <?php echo h($license['holder']); ?>, on file with the Texas State Board of Plumbing Examiners through <?php echo h($licenseExpires); ?>.</span></li>
            <li><b>When the clog is really the pipe</b><span>A line that clogs again and again may be broken or full of roots. Rock'n G handles <a href="<?php echo h(serviceUrl($sewerSvc)); ?>">sewer line repair and camera inspection</a> too, so the same company takes the next step.</span></li>
            <li><b>Homes and businesses</b><span>Residential, commercial and industrial drains across Tom Green County, backed by 20+ years of experience.</span></li>
          </ul>
        </div>
      </div>
    </div>
  </section>

  <!-- ============ SERVICE BREAKDOWN + PROCESS ============ -->
  <section class="section dc-process" aria-labelledby="dc-process-title">
    <div class="container">
      <div class="section-head reveal-up">
        <span class="eyebrow">What's included</span>
        <h2 id="dc-process-title">What happens when Rock'n G clears a clogged drain?</h2>
        <p class="answer-block">A Rock'n G drain call starts with working out which drains are affected and where the clog sits, because that decides how the line is cleared. A single slow sink is a different job from a main line backing up into a tub, and the estimate covers which one you have.</p>
      </div>
      <div class="dc-process__grid">
        <ol class="dc-timeline">
          <li class="reveal-left"><b>Tell us which drains</b><span>Call <?php echo h($phoneFmt); ?>, <?php echo h($weekday[0]); ?> <?php echo h($weekday[1]); ?>, or send the form. Say which drains are slow, how long it has been going on, and whether anything went down that should not have.</span></li>
          <li class="reveal-left reveal-delay-1"><b>Estimate at no charge</b><span>Rock'n G looks at where the blockage is and prices the clearing, or a repair if the pipe itself turns out to be the problem.</span></li>
          <li class="reveal-left reveal-delay-2"><b>You decide</b><span>Go ahead when the price and the plan make sense. Mention military service and ask how the discount applies.</span></li>
          <li class="reveal-left reveal-delay-3"><b>The drain is cleared</b><span>The work is done under <?php echo h($ownerName); ?>'s Texas Master Plumber license. If chemical drain cleaner is still sitting in the line, say so first; it protects whoever opens the drain.</span></li>
          <li class="reveal-left reveal-delay-1"><b>If it is not a clog</b><span>A slow drain with a damp floor, a musty smell or a water bill that jumped can be a leak instead. See <a href="/services/leak-detection-repair/">leak detection and slab leak repair</a>.</span></li>
        </ol>
        <aside class="dc-aside reveal-right" aria-label="Drain care">
          <div class="dc-aside__img">
            <?php echo pictureTag('rockn-pipe-through-slab', 'White PVC drain pipe dropping into a trench cut through a concrete slab during a Rock\'n G repair', '(max-width: 900px) 92vw, 420px'); ?>
          </div>
          <p class="dc-aside__cap">Drain line exposed at floor level during a Rock'n G repair: what sits under your sink and tub.</p>
          <div class="dc-keepout">
            <h3>Keep these out of your drains</h3>
            <ul>
              <?php foreach ($dcKeepOut as $item): ?>
              <li><?php echo icon('x', 16); ?><?php echo h($item); ?></li>
              <?php endforeach; ?>
            </ul>
          </div>
        </aside>
      </div>
    </div>
  </section>

  <!-- ============ PROOF ============ -->
  <section class="section on-dark edge-torn-top dc-proof" aria-labelledby="dc-proof-title">
    <span class="grain-layer" aria-hidden="true"></span>
    <div class="container">
      <div class="section-head reveal-up">
        <span class="eyebrow">Proof</span>
        <h2 id="dc-proof-title">Where can you read what customers say about Rock'n G Plumbing?</h2>
        <p class="answer-block">Customers review Rock'n G Plumbing on its Google Business Profile, which currently shows <?php echo h(number_format((float) $gbp['rating'], 1)); ?> out of 5 from <?php echo (int) $gbp['reviews_count']; ?> reviews. Read them there, check license M-44822 with the Texas State Board of Plumbing Examiners, and if Rock'n G cleared your drain, add a review of your own.</p>
      </div>
      <div class="dc-proof__bar reveal-up">
        <dl>
          <div><dt>License</dt><dd><?php echo h($license['label']); ?></dd></div>
          <div><dt>Responsible Master Plumber</dt><dd><?php echo h($license['holder']); ?></dd></div>
          <div><dt>Issued by</dt><dd><?php echo h($license['issuer']); ?></dd></div>
        </dl>
        <div class="actions">
          <a class="btn btn-accent" href="<?php echo h($gbp['profile_url']); ?>" target="_blank" rel="noopener">Read reviews on Google</a>
          <a class="btn btn-outline-white" href="<?php echo h($gbp['review_url']); ?>" target="_blank" rel="noopener">Leave a review</a>
        </div>
      </div>
      <?php
      require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/google-reviews.php';
      $dcReviews = p1_google_reviews($slug, ['limit' => 3, 'heading' => '', 'summary' => false]);
      if ($dcReviews !== ''): ?>
      <div class="dc-proof__reviews"><?php echo $dcReviews; ?></div>
      <?php endif; ?>
    </div>
  </section>

  <!-- ============ RELATED SERVICES (required services-grid) ============ -->
  <section class="section dc-related" aria-label="Related plumbing services">
    <div class="container">
      <div class="section-title reveal-up">
        <span class="eyebrow-label">What We Do</span>
        <h2>Which other <span class="text-accent">plumbing services</span> help when a drain keeps clogging?</h2>
        <p class="hero-answer">A drain that keeps clogging is sometimes a pipe problem. Sewer line repair with camera inspection finds breaks and roots in the main line, leak detection finds water escaping under the slab, and a remodel is the time to fix badly sloped drains for good. Rock'n G Plumbing handles all three.</p>
        <span class="section-subtitle">Sewer · Leaks · Remodels</span>
        <p class="prose">See every <a href="/services/">plumbing service Rock'n G offers in San Angelo</a>.</p>
      </div>
      <div class="services-grid">
        <?php echo renderServiceCards(['sewer-line-repair', 'leak-detection-repair', 'new-construction-remodel-plumbing'], '(max-width: 640px) 100vw, (max-width: 1000px) 50vw, 380px'); ?>
      </div>
    </div>
  </section>

  <!-- ============ FINAL CTA ============ -->
  <section class="closing-cta texture-grain dc-cta" aria-labelledby="dc-cta-title">
    <span class="grain-layer" aria-hidden="true"></span>
    <span class="floating-ring dc-cta__ring" aria-hidden="true"></span>
    <div class="container">
      <div class="reveal-left">
        <span class="eyebrow">No charge to look</span>
        <h2 id="dc-cta-title">Ready for a free drain cleaning estimate in San Angelo?</h2>
        <p>Call Rock'n G Plumbing at <?php echo h($phoneFmt); ?>, <?php echo h($weekday[0]); ?> <?php echo h($weekday[1]); ?>, or send the form and the office will follow up during business hours. A slow drain rarely clears itself, and it is easier to deal with before it backs up.</p>
        <p class="dc-updated">Last updated: <?php echo date('F Y'); ?></p>
      </div>
      <div class="actions reveal-right">
        <a class="btn btn-accent btn-lg" href="/contact/" data-open-estimate>Get a Free Drain Cleaning Estimate</a>
        <a class="link-call" href="<?php echo h($telHref); ?>"><?php echo icon('phone', 18); ?>Call <?php echo h($phoneFmt); ?></a>
      </div>
    </div>
  </section>

</article>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>
