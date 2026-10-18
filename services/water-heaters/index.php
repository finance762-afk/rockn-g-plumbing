<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/functions.php';
?>
<?php
/**
 * services/water-heaters/index.php — Water Heater Repair & Installation (Phase 4, premium editorial layout).
 * Every business fact traces to build-plan.json / includes/config.php. General water-heater knowledge (how tanks and
 * tankless units behave) is written as general knowledge, never as a Rock'n G promise. Facts this page wanted but
 * intake did not supply (prices, brands, haul-away, permits, warranty, gas vs electric) are in /content-confirmations.json.
 * SEO brief: no FAQ section/schema on this site's service pages (no recorded customer questions yet), no emergency or
 * 24-hour wording.
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/service-cards.php';

$svc         = serviceBySlug('water-heaters');
$pageType    = 'service';
$serviceSlug = $svc['slug'];
$pageTitle       = "Water Heater Plumber in San Angelo, TX | Rock'n G";
$pageDescription = "Water heater plumber in San Angelo, TX • Repairs, tank replacement & tankless installs • Master Plumber M-44822, free estimates. Call (325) 650-7000.";
$canonicalUrl    = $siteUrl . '/services/water-heaters/';
$currentPage     = 'services/water-heaters';
$breadcrumbs     = [['name' => 'Services', 'url' => '/services/'], ['name' => $svc['name'], 'url' => '/services/water-heaters/']];

$heroImage   = 'rockn-water-treatment-garage';
$heroAlt     = 'Water softener tank, brine tank and blue whole-house filter housings plumbed into a garage utility corner';
$heroPreload = ['srcset' => imageVariantSrcset($heroImage, 'avif'), 'sizes' => '100vw'];
$ogImage     = '/assets/images/' . $heroImage . '.jpg';

$telHref  = 'tel:' . $phoneE164;
$phoneFmt = formatPhone($phone);
$weekday  = hoursGroups($hours)[0];      // ['Mon–Fri', '8:00 AM – 6:00 PM']
$licenseExpires = date('F j, Y', strtotime($license['expires']));

// Telltale signs (general water-heater knowledge — what a homeowner notices and what it usually means)
$whSigns = [
    ['icon' => 'clock',          'title' => 'Hot water runs out early',
     'body'  => "Sediment from hard water fills the bottom of the tank, or one of an electric heater's two elements has burned out. Either one shrinks the hot water you get from a full tank."],
    ['icon' => 'waves',          'title' => 'Rumbling or popping from the tank',
     'body'  => 'Water trapped under a layer of mineral sediment boils and pops as the burner fires. Flushing can help a younger tank; on an older one it often means the end is near.'],
    ['icon' => 'droplets',       'title' => 'Rusty, cloudy or rotten-egg hot water',
     'body'  => 'Rust-colored hot water can mean the inside of the tank is corroding. A sulfur smell usually comes from a reaction at the anode rod, a part that can be replaced.'],
    ['icon' => 'info', 'title' => 'Water around the base',
     'body'  => 'Check the fittings and the relief-valve line first. If the water is coming from the tank body itself, the steel has rusted through and the heater needs replacing.'],
];

// The three kinds of water heater work in build-plan.json ("repair, replacement, tankless")
$whOptions = [
    ['icon' => 'wrench', 'title' => 'Repair',
     'body'  => 'For a heater that is otherwise sound. Common water heater repairs include:',
     'items' => ['Heating elements and thermostats (electric)', 'Thermocouples and gas controls (gas)', 'Anode rods and relief valves']],
    ['icon' => 'hammer', 'title' => 'Replacement',
     'body'  => 'For a tank that leaks or a heater where repairs no longer add up. A replacement means:',
     'items' => ['Draining and disconnecting the old heater', 'Setting and connecting the new one', 'Checking venting and the relief line']],
    ['icon' => 'flame', 'title' => 'Tankless installation',
     'body'  => 'Heats water as it flows instead of keeping a tank hot. Switching from a tank often involves:',
     'items' => ['A larger gas supply line', 'New venting for the unit', 'A wall-mounted location']],
];

// Service schema (provider = the Plumber node head.php prints on every page)
$serviceNode = generateServiceSchema($svc);
$serviceNode['@id']         = absUrl(serviceUrl($svc)) . '#service-' . $svc['slug'];
$serviceNode['serviceType'] = 'Water heater repair and installation';
$serviceNode['description'] = "Water heater repair, tank water heater replacement and tankless water heater installation in San Angelo, TX and nearby Tom Green County towns, by Rock'n G Plumbing under Texas Master Plumber License M-44822.";
$serviceNode['image']       = absUrl('/assets/images/' . $heroImage . '.jpg');
$serviceNode['hasOfferCatalog'] = [
    '@type' => 'OfferCatalog', 'name' => 'Water heater services',
    'itemListElement' => array_map(fn($o) => ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Water heater ' . strtolower($o['title'])]], $whOptions),
];
$serviceNode["@context"] = 'https://schema.org';   // explicit (generateServiceSchema sets it too)
$schemaMarkup = [$serviceNode];

ob_start();
?>
<style>
/* =====================================================================
   Water Heater Repair & Installation — page-only CSS (wh- prefix)
   Tokens from framework.css; no raw colors, shadows or spacing.
   ===================================================================== */
@property --wh-heat {
  syntax: '<percentage>';
  inherits: false;
  initial-value: 0%;
}

/* ---------- Hero: layered photo (C1) ---------- */
.wh-hero {
  padding-block-end: var(--space-16);
}
.wh-hero .hero-bg {
  overflow: hidden;
}
.wh-hero .hero-bg picture,
.wh-hero .hero-bg img {
  width: 100%;
  height: 100%;
}
.wh-hero .hero-bg img {
  object-fit: cover;
  object-position: 40% 45%;
  animation: wh-drift 26s var(--ease) both;
}
.wh-hero::before {
  content: "";
  position: absolute;
  inset: 0;
  z-index: -1;
  pointer-events: none;
  background:
    radial-gradient(ellipse 60% 70% at 100% 0%, color-mix(in srgb, var(--color-warning) 22%, transparent), transparent 65%),
    linear-gradient(180deg, transparent 50%, color-mix(in srgb, var(--color-primary-deep) 80%, transparent) 100%);
}
.wh-hero::after {
  content: "";
  position: absolute;
  inset: 0;
  z-index: -1;
  pointer-events: none;
  opacity: .2;
  mix-blend-mode: overlay;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='160' height='160'%3E%3Cfilter id='g'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.8' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23g)'/%3E%3C/svg%3E");
}
.wh-hero .breadcrumb {
  color: color-mix(in srgb, var(--color-surface) 72%, transparent);
  margin-block-end: var(--space-2);
}
.wh-hero .breadcrumb li {
  display: inline-flex;
  align-items: center;
}
.wh-hero .breadcrumb a:hover {
  color: var(--color-accent-bright);
}
.wh-hero .breadcrumb [aria-current="page"] {
  color: var(--color-surface);
}
.wh-hero .hero-title {
  max-width: 18ch;
  text-wrap: balance;
}
.wh-hero .hero-answer {
  max-width: 36rem;
}
.wh-hero .hero-chips li {
  backdrop-filter: blur(6px);
  -webkit-backdrop-filter: blur(6px);
}
.wh-hero .hero-form-open {
  background: var(--color-accent);
  color: var(--color-white);
}
.wh-hero .hero-form-card {
  border-block-start: 4px solid var(--color-warning);
}
.wh-hero .hero-form-card h2 {
  text-wrap: balance;
}
.wh-hero .hero-form .consent {
  margin-block: var(--space-1);
}
@keyframes wh-drift {
  from { transform: scale(1.1) translateX(-2%); }
  to   { transform: scale(1) translateX(0); }
}

/* ---------- Article wrapper ---------- */
.wh-article > section h2 {
  text-wrap: balance;
}
.wh-article .answer-block {
  font-size: var(--fs-lead);
  line-height: 1.55;
}

/* ---------- Signs: lede tile + bento (C5) ---------- */
.wh-signs {
  background: var(--color-paper);
}
.wh-signs .section-head {
  max-width: 62ch;
}
.wh-bento {
  display: grid;
  grid-template-columns: repeat(6, minmax(0, 1fr));
  gap: var(--space-4);
}
.wh-lede {
  grid-column: span 2;
  grid-row: span 2;
  position: relative;
  isolation: isolate;
  overflow: hidden;
  display: grid;
  align-content: end;
  gap: var(--space-3);
  padding: var(--space-8) var(--space-6);
  border-radius: var(--radius-lg);
  color: var(--color-surface);
  background: linear-gradient(160deg, var(--color-dark) 0%, var(--color-primary-deep) 100%);
  box-shadow: var(--shadow-lg);
}
.wh-lede::before {
  content: "";
  position: absolute;
  inset-inline: 0;
  inset-block-end: 0;
  height: var(--wh-heat);
  z-index: -1;
  background: linear-gradient(0deg, color-mix(in srgb, var(--color-warning) 38%, transparent), transparent);
  animation: wh-fill 6s var(--ease) infinite alternate;
}
.wh-lede p {
  margin: 0;
  font-family: var(--font-heading);
  font-weight: 700;
  font-size: clamp(1.3rem, 1rem + 1vw, 1.75rem);
  line-height: 1.25;
  color: var(--color-surface);
  text-wrap: balance;
}
.wh-lede small {
  font-family: var(--font-accent);
  font-size: var(--fs-eyebrow);
  letter-spacing: .14em;
  text-transform: uppercase;
  color: var(--color-accent-bright);
}
@keyframes wh-fill {
  from { --wh-heat: 18%; }
  to   { --wh-heat: 72%; }
}
.wh-sign {
  grid-column: span 2;
  display: grid;
  align-content: start;
  gap: var(--space-2);
  padding: var(--space-6);
  border-radius: var(--radius-lg);
  background: var(--color-surface);
  border: 1px solid var(--color-line);
  box-shadow: var(--shadow-sm);
  transition: transform var(--transition), box-shadow var(--transition), border-color var(--transition);
}
.wh-sign:hover {
  transform: translateY(-3px);
  box-shadow: var(--shadow);
  border-color: var(--color-warning);
}
.wh-sign svg {
  color: var(--color-warning);
}
.wh-sign h3 {
  font-size: var(--fs-h3);
  text-wrap: balance;
}
.wh-sign p {
  margin: 0;
  color: var(--color-ink-2);
  font-size: var(--fs-small);
}
.wh-gas-note {
  grid-column: 1 / -1;
  display: flex;
  gap: var(--space-3);
  align-items: flex-start;
  padding: var(--space-4) var(--space-5);
  border-radius: var(--radius);
  background: color-mix(in srgb, var(--color-warning) 12%, var(--color-surface));
  border-inline-start: 4px solid var(--color-warning);
}
.wh-gas-note svg {
  flex: 0 0 auto;
  color: var(--color-warning);
  margin-block-start: var(--space-1);
}
.wh-gas-note p {
  margin: 0;
  color: var(--color-ink);
}
.wh-gas-note a {
  color: var(--color-primary);
  font-weight: 600;
}

/* ---------- Expert positioning: big credential + framed photo (60/40) ---------- */
.wh-expert {
  background: var(--color-surface);
}
.wh-expert__grid {
  display: grid;
  grid-template-columns: minmax(0, 3fr) minmax(0, 2fr);
  gap: var(--space-12);
  align-items: start;
}
.wh-expert__copy {
  display: grid;
  gap: var(--space-4);
}
.wh-expert__copy > p {
  margin: 0;
  color: var(--color-ink-2);
  max-width: var(--measure);
}
.wh-credential {
  display: flex;
  align-items: baseline;
  flex-wrap: wrap;
  gap: var(--space-2) var(--space-4);
  padding-block: var(--space-4);
  border-block: 1px solid var(--color-line);
}
.wh-credential b {
  font-family: var(--font-accent);
  font-size: clamp(3rem, 2rem + 4vw, 5.5rem);
  line-height: .9;
  letter-spacing: -.01em;
  color: var(--color-primary);
}
.wh-credential span {
  font-size: var(--fs-small);
  color: var(--color-muted);
  max-width: 24ch;
}
.wh-points {
  list-style: none;
  margin: 0;
  padding: 0;
  display: grid;
  gap: var(--space-4);
  counter-reset: wh-point;
}
.wh-points li {
  counter-increment: wh-point;
  display: grid;
  grid-template-columns: auto 1fr;
  gap: var(--space-1) var(--space-4);
}
.wh-points li::before {
  content: counter(wh-point, decimal-leading-zero);
  grid-row: span 2;
  font-family: var(--font-accent);
  font-size: 1.4rem;
  line-height: 1;
  color: var(--color-accent-dark);
}
.wh-points b {
  font-family: var(--font-heading);
  color: var(--color-ink);
}
.wh-points span {
  color: var(--color-ink-2);
  font-size: var(--fs-small);
}
.wh-points a {
  color: var(--color-primary);
  font-weight: 600;
}
.wh-figure {
  position: relative;
  margin: 0;
  margin-block-start: var(--space-8);
}
.wh-figure::before {
  content: "";
  position: absolute;
  inset: calc(-1 * var(--space-4)) var(--space-4) var(--space-12) calc(-1 * var(--space-4));
  border: 2px solid var(--color-warning);
  border-radius: var(--radius-lg);
  opacity: .5;
}
.wh-figure .img-reveal {
  aspect-ratio: 3 / 4;
}
.wh-figure .img-reveal picture,
.wh-figure .img-reveal img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}
.wh-figure figcaption {
  position: relative;
  margin-block-start: var(--space-3);
  font-size: var(--fs-small);
  color: var(--color-muted);
  border-inline-start: 3px solid var(--color-accent);
  padding-inline-start: var(--space-3);
}

/* ---------- Scope: option cards + sticky process (§2.6) ---------- */
.wh-scope {
  background: var(--color-paper-2);
}
.wh-options {
  display: grid;
  grid-template-columns: repeat(3, minmax(0, 1fr));
  gap: var(--space-4);
  margin-block: var(--space-8) var(--space-16);
}
.wh-option {
  position: relative;
  display: grid;
  align-content: start;
  gap: var(--space-3);
  padding: var(--space-6);
  border-radius: var(--radius-lg);
  border: 1px solid var(--color-line);
  box-shadow: var(--shadow-sm);
  transition: transform var(--transition), box-shadow var(--transition);
}
.wh-option:hover {
  transform: translateY(-3px);
  box-shadow: var(--shadow);
}
.wh-option__icon {
  width: 46px;
  height: 46px;
  display: grid;
  place-items: center;
  border-radius: var(--radius);
  color: var(--color-surface);
  background: var(--color-primary);
}
.wh-option:nth-child(3) .wh-option__icon {
  background: var(--color-warning);
  color: var(--color-ink);
}
.wh-option h3 {
  font-size: var(--fs-h3);
}
.wh-option p {
  margin: 0;
  color: var(--color-ink-2);
  font-size: var(--fs-small);
}
.wh-option ul {
  margin: 0;
  padding: 0;
  list-style: none;
  display: grid;
  gap: var(--space-2);
  border-block-start: 1px dashed var(--color-line);
  padding-block-start: var(--space-3);
}
.wh-option li {
  display: flex;
  gap: var(--space-2);
  align-items: flex-start;
  font-size: var(--fs-small);
  color: var(--color-ink);
}
.wh-option li svg {
  flex: 0 0 auto;
  color: var(--color-accent-dark);
  margin-block-start: var(--space-1);
}
.wh-process {
  display: grid;
  grid-template-columns: minmax(0, 2fr) minmax(0, 3fr);
  gap: var(--space-12);
  align-items: start;
}
.wh-process__aside {
  position: sticky;
  inset-block-start: calc(var(--nav-height) + var(--space-6));
  display: grid;
  gap: var(--space-3);
}
.wh-process__aside h3 {
  font-size: var(--fs-h2);
  text-wrap: balance;
}
.wh-process__aside p {
  margin: 0;
  color: var(--color-ink-2);
}
.wh-process__aside .link-call svg {
  color: var(--color-accent-dark);
}
.wh-steps {
  list-style: none;
  margin: 0;
  padding: 0;
  display: grid;
  gap: var(--space-4);
  counter-reset: wh-step;
}
.wh-steps li {
  counter-increment: wh-step;
  position: relative;
  display: grid;
  gap: var(--space-2);
  padding: var(--space-6) var(--space-6) var(--space-6) var(--space-16);
  border-radius: var(--radius-lg);
  background: var(--color-surface);
  border: 1px solid var(--color-line);
}
.wh-steps li::before {
  content: counter(wh-step);
  position: absolute;
  inset-inline-start: var(--space-5);
  inset-block-start: var(--space-5);
  width: 40px;
  height: 40px;
  display: grid;
  place-items: center;
  border-radius: var(--radius-full);
  font-family: var(--font-accent);
  font-size: 1.2rem;
  color: var(--color-surface);
  background: var(--color-primary);
}
.wh-steps li:not(:last-child)::after {
  content: "";
  position: absolute;
  inset-inline-start: calc(var(--space-5) + 19px);
  inset-block-start: calc(var(--space-5) + 44px);
  inset-block-end: calc(-1 * var(--space-4));
  width: 2px;
  background: repeating-linear-gradient(180deg, var(--color-accent) 0 6px, transparent 6px 12px);
}
.wh-steps b {
  font-family: var(--font-heading);
  font-size: 1.05rem;
  color: var(--color-ink);
}
.wh-steps span {
  color: var(--color-ink-2);
  font-size: var(--fs-small);
}
@supports (animation-timeline: view()) {
  .wh-steps li {
    animation: wh-step-in linear both;
    animation-timeline: view();
    animation-range: entry 5% cover 35%;
  }
  @keyframes wh-step-in {
    from { opacity: .6; transform: translateX(var(--space-4)); border-color: var(--color-line); }
    to   { opacity: 1; transform: none; border-color: var(--color-accent); }
  }
}

/* ---------- Comparison table ---------- */
.wh-compare {
  background: var(--color-surface);
}
.wh-compare__wrap {
  container-type: inline-size;
  container-name: whtable;
  margin-block-start: var(--space-8);
}
.wh-table {
  width: 100%;
  border-collapse: separate;
  border-spacing: 0;
  font-size: var(--fs-small);
  border: 1px solid var(--color-line);
  border-radius: var(--radius-lg);
  overflow: hidden;
  box-shadow: var(--shadow-sm);
}
.wh-table caption {
  caption-side: bottom;
  text-align: start;
  padding-block-start: var(--space-3);
  color: var(--color-muted);
  font-size: var(--fs-small);
}
.wh-table th,
.wh-table td {
  padding: var(--space-4) var(--space-5);
  text-align: start;
  vertical-align: top;
  border-block-end: 1px solid var(--color-line);
}
.wh-table tbody tr:last-child th,
.wh-table tbody tr:last-child td {
  border-block-end: 0;
}
.wh-table thead th {
  font-family: var(--font-accent);
  font-size: var(--fs-eyebrow);
  letter-spacing: .14em;
  text-transform: uppercase;
  color: var(--color-surface);
  background: var(--color-dark);
}
.wh-table thead th:last-child {
  background: var(--color-primary);
  box-shadow: inset 0 -4px 0 var(--color-warning);
}
.wh-table tbody th {
  width: 24%;
  font-family: var(--font-heading);
  font-weight: 700;
  color: var(--color-ink);
  background: var(--color-paper);
}
.wh-table tbody td {
  color: var(--color-ink-2);
}
.wh-table tbody tr {
  transition: background-color var(--transition);
}
.wh-table tbody tr:hover td {
  background: color-mix(in srgb, var(--color-accent) 6%, var(--color-surface));
}
@container whtable (max-width: 640px) {
  .wh-table thead {
    position: absolute;
    width: 1px;
    height: 1px;
    overflow: hidden;
    clip-path: inset(50%);
  }
  .wh-table,
  .wh-table tbody,
  .wh-table tr,
  .wh-table th,
  .wh-table td {
    display: block;
    width: auto;
  }
  .wh-table tbody th {
    width: auto;
    border-block-end: 0;
  }
  .wh-table caption {
    display: block;
  }
  .wh-table tbody td {
    border-block-end: 0;
    padding-block: var(--space-2);
  }
  .wh-table tbody tr {
    border-block-end: 1px solid var(--color-line);
    padding-block-end: var(--space-3);
  }
  .wh-table tbody td::before {
    content: attr(data-label) ": ";
    font-family: var(--font-accent);
    font-size: var(--fs-eyebrow);
    letter-spacing: .1em;
    text-transform: uppercase;
    color: var(--color-muted);
  }
}

/* ---------- Proof: Google reviews + license card (dark, wave edge) ---------- */
.wh-proof .section-head {
  max-width: 64ch;
}
.wh-proof .answer-block {
  background: color-mix(in srgb, var(--color-surface) 6%, transparent);
  color: color-mix(in srgb, var(--color-surface) 88%, transparent);
}
.wh-proof__cards {
  display: grid;
  grid-template-columns: minmax(0, 1.4fr) minmax(0, 1fr);
  gap: var(--space-6);
  margin-block-start: var(--space-8);
}
.wh-proof__card {
  display: grid;
  align-content: start;
  gap: var(--space-3);
  padding: var(--space-6);
  border-radius: var(--radius-lg);
  background: color-mix(in srgb, var(--color-surface) 7%, transparent);
  border: 1px solid color-mix(in srgb, var(--color-surface) 16%, transparent);
  backdrop-filter: blur(8px);
  -webkit-backdrop-filter: blur(8px);
}
.wh-proof__card h3 {
  color: var(--color-surface);
}
.wh-proof__card p {
  margin: 0;
}
.wh-proof__card .actions {
  display: flex;
  flex-wrap: wrap;
  gap: var(--space-3);
  margin-block-start: var(--space-2);
}
.wh-proof__card dl {
  margin: 0;
  display: grid;
  grid-template-columns: auto 1fr;
  gap: var(--space-2) var(--space-4);
  font-size: var(--fs-small);
}
.wh-proof__card dt {
  color: color-mix(in srgb, var(--color-surface) 62%, transparent);
}
.wh-proof__card dd {
  margin: 0;
  color: var(--color-surface);
  font-weight: 600;
}
.wh-proof__reviews {
  margin-block-start: var(--space-8);
}
.wh-proof__reviews .p1gr-text {
  color: var(--color-ink-2);
}

/* ---------- Related services ---------- */
.wh-related {
  background: var(--color-paper);
}
.wh-related .services-grid {
  grid-template-columns: repeat(3, minmax(0, 1fr));
}
.wh-related .service-card-with-image h3 {
  text-wrap: balance;
}
.wh-related__foot {
  display: flex;
  justify-content: center;
  margin-block-start: var(--space-8);
}

/* ---------- Final CTA ---------- */
.wh-cta .container {
  align-items: center;
}
.wh-cta h2 {
  text-wrap: balance;
  max-width: 22ch;
}
.wh-cta .link-call {
  color: color-mix(in srgb, var(--color-surface) 88%, transparent);
}
.wh-cta .link-call svg {
  color: var(--color-accent-bright);
}
.wh-cta__ring {
  inset-inline-start: -140px;
  inset-block-end: -160px;
  border-color: var(--color-warning);
}
.wh-updated {
  margin-block-start: var(--space-6);
  font-size: var(--fs-small);
  color: var(--color-muted);
}

/* ---------- Responsive ---------- */
@media (max-width: 1000px) {
  .wh-bento {
    grid-template-columns: repeat(4, minmax(0, 1fr));
  }
  .wh-lede {
    grid-column: 1 / -1;
    grid-row: auto;
  }
  .wh-options {
    grid-template-columns: 1fr;
  }
  .wh-related .services-grid {
    grid-template-columns: repeat(2, minmax(0, 1fr));
  }
}
@media (max-width: 900px) {
  .wh-expert__grid,
  .wh-process,
  .wh-proof__cards {
    grid-template-columns: 1fr;
  }
  .wh-process__aside {
    position: static;
  }
  .wh-figure {
    margin-inline: var(--space-4);
  }
  .wh-figure .img-reveal {
    aspect-ratio: 4 / 3;
  }
}
@media (max-width: 640px) {
  .wh-bento {
    grid-template-columns: 1fr;
  }
  .wh-sign,
  .wh-lede {
    grid-column: auto;
  }
  .wh-steps li {
    padding-inline-start: var(--space-12);
  }
  .wh-steps li::before {
    inset-inline-start: var(--space-3);
  }
  .wh-steps li:not(:last-child)::after {
    inset-inline-start: calc(var(--space-3) + 19px);
  }
  .wh-related .services-grid {
    grid-template-columns: 1fr;
  }
}
@media (prefers-reduced-motion: reduce) {
  .wh-hero .hero-bg img,
  .wh-lede::before,
  .wh-steps li {
    animation: none;
  }
  .wh-sign,
  .wh-option,
  .wh-table tbody tr {
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
<section class="hero hero--photo hero--interior wh-hero" aria-labelledby="wh-h1">
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
            <li><span aria-current="page">Water Heaters</span></li>
          </ol>
        </nav>
        <span class="eyebrow">Water Heaters · <?php echo h($address['city'] . ', ' . $address['state']); ?></span>
        <h1 class="hero-title" id="wh-h1">Water Heater Repair &amp; Installation by a San Angelo Plumber</h1>
        <p class="hero-answer">Rock'n G Plumbing repairs water heaters, replaces worn-out tanks and installs tankless units for homes and businesses in San Angelo, Christoval and nearby Tom Green County towns. Every job runs under master plumber Eli Gomez's Texas license M-44822, and the estimate is free.</p>
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
        <h2 id="hero-form-title">Need your water heater looked at?</h2>
        <p class="hero-form-tagline">Tell us what it's doing. No obligation.</p>
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

<article class="wh-article" aria-labelledby="wh-h1">

  <!-- ============ PROBLEM: SIGNS ============ -->
  <section class="section wh-signs" aria-labelledby="wh-signs-title">
    <div class="container-wide">
      <div class="section-head reveal-up">
        <span class="eyebrow">Know the signs</span>
        <h2 id="wh-signs-title">How can you tell a water heater needs repair or replacement?</h2>
        <p class="answer-block">Hot water that runs out early, rusty or smelly hot water, rumbling from the tank, and water pooling at its base are the signs a water heater needs attention. Some point to a repairable part, like a heating element or gas control. A leak from the tank body usually means replacement, since the steel has rusted through.</p>
      </div>

      <div class="wh-bento">
        <div class="wh-lede reveal-left">
          <small>San Angelo hard water</small>
          <p>Minerals in hard water settle on the bottom of a tank heater, and the heater works harder every month to heat through them.</p>
        </div>
        <?php foreach ($whSigns as $i => $sign): ?>
        <div class="wh-sign reveal-up reveal-delay-<?php echo ($i % 3) + 1; ?>">
          <?php echo icon($sign['icon'], 26); ?>
          <h3><?php echo h($sign['title']); ?></h3>
          <p><?php echo h($sign['body']); ?></p>
        </div>
        <?php endforeach; ?>
        <div class="wh-gas-note reveal-up" role="note">
          <?php echo icon('flame', 22); ?>
          <p><strong>Pilot keeps going out on a gas heater?</strong> That often traces to a thermocouple or the gas control. If you smell gas (a rotten-egg odor), leave the house and call 911 or your gas utility from outside before anything else. Rock'n G also handles <a href="/services/gas-line-services/">gas line installation and repair</a> once it is safe.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- ============ EXPERT POSITIONING ============ -->
  <section class="section slant-top wh-expert" aria-labelledby="wh-expert-title">
    <div class="container">
      <div class="wh-expert__grid">
        <div class="wh-expert__copy reveal-left">
          <span class="eyebrow">Who does the work</span>
          <h2 id="wh-expert-title">Why have a master plumber handle your water heater?</h2>
          <p class="answer-block">Because a water heater connects to your water supply, a relief line and either gas venting or a high-amp electrical circuit, it should be installed by a licensed plumber. Rock'n G Plumbing's water heater work is done under Eli Gomez's Texas Master Plumber License M-44822, issued by the Texas State Board of Plumbing Examiners.</p>
          <div class="wh-credential">
            <b><?php echo h($license['number']); ?></b>
            <span><?php echo h($license['holder']); ?>, <?php echo h($license['role']); ?> — current through <?php echo h($licenseExpires); ?></span>
          </div>
          <ol class="wh-points">
            <li><b>A license you can look up</b><span>Search M-44822 on the Texas State Board of Plumbing Examiners license lookup and you will find Eli Gomez listed as the Responsible Master Plumber. Ask any plumber for that number before work starts.</span></li>
            <li><b>Three generations in the trade</b><span>Plumbing is the Gomez family's trade. Rock'n G brings 20+ years of experience to the repair-or-replace question, and serves residential, commercial and industrial customers.</span></li>
            <li><b>Hard water handled at the source</b><span>The same crew installs <a href="/services/water-filtration-softeners/">water softeners and whole-house filtration</a>, so a new heater does not have to fight San Angelo's hard water from its first day.</span></li>
          </ol>
        </div>
        <figure class="wh-figure reveal-right">
          <div class="img-reveal">
            <?php echo pictureTag('rockn-water-softener-filtration', "Water softener, brine tank and two blue whole-house filter housings installed by Rock'n G Plumbing in a garage", '(max-width: 900px) 90vw, 420px'); ?>
          </div>
          <figcaption>A Rock'n G softener, brine tank and whole-house filters in a customer's garage. Treating hard water before it reaches the water heater slows scale buildup in the tank.</figcaption>
        </figure>
      </div>
    </div>
  </section>

  <!-- ============ SERVICE BREAKDOWN + PROCESS ============ -->
  <section class="section wh-scope" aria-labelledby="wh-scope-title">
    <div class="container">
      <div class="section-head reveal-up">
        <span class="eyebrow">What's included</span>
        <h2 id="wh-scope-title">What does water heater service from Rock'n G include?</h2>
        <p class="answer-block">Rock'n G Plumbing handles three kinds of water heater work: repairing a heater that still has life in it, replacing a tank that has failed, and installing tankless units. The free estimate covers which of the three makes sense for your heater's age, condition and the way your household uses hot water.</p>
      </div>

      <div class="wh-options">
        <?php foreach ($whOptions as $i => $opt): ?>
        <div class="wh-option card-tint-<?php echo $i + 1; ?> reveal-up reveal-delay-<?php echo $i + 1; ?>">
          <span class="wh-option__icon"><?php echo icon($opt['icon'], 22); ?></span>
          <h3><?php echo h($opt['title']); ?></h3>
          <p><?php echo h($opt['body']); ?></p>
          <ul>
            <?php foreach ($opt['items'] as $item): ?>
            <li><?php echo icon('check', 16); ?><?php echo h($item); ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
        <?php endforeach; ?>
      </div>

      <div class="wh-process">
        <div class="wh-process__aside reveal-left">
          <span class="eyebrow">Step by step</span>
          <h3>How a Rock'n G water heater job runs</h3>
          <p>Four steps from the first call to hot water. The model plate on the side of the tank lists its size and whether it runs on gas or electric, which is useful to have when you call.</p>
          <a class="link-call" href="<?php echo h($telHref); ?>"><?php echo icon('phone', 18); ?><?php echo h($phoneFmt); ?></a>
        </div>
        <ol class="wh-steps">
          <li><b>Call or send the form</b><span>Reach the office at <?php echo h($phoneFmt); ?>, <?php echo h($weekday[0]); ?> <?php echo h($weekday[1]); ?>, or send the form any time. Say what the heater is doing and roughly how old it is.</span></li>
          <li><b>Estimate at no charge</b><span>Rock'n G looks at the symptoms and prices the right fix: a repair, a replacement tank, or a tankless upgrade.</span></li>
          <li><b>You choose</b><span>Go ahead with the option that fits your home and your budget. Mention military service and ask how the discount applies.</span></li>
          <li><b>The work</b><span>The repair or new install is done under <?php echo h($ownerName); ?>'s Texas Master Plumber license.</span></li>
        </ol>
      </div>
    </div>
  </section>

  <!-- ============ COMPARISON ============ -->
  <section class="section wh-compare" aria-labelledby="wh-compare-title">
    <div class="container">
      <div class="section-head reveal-up">
        <span class="eyebrow">Compare</span>
        <h2 id="wh-compare-title">Tank or tankless: which water heater fits a San Angelo home?</h2>
        <p class="answer-block">A tank heater is the simpler swap in most homes, while a tankless heater gives continuous hot water but usually takes more work to install. With San Angelo's hard water, either type lasts longer with a softener ahead of it, and tankless units need periodic descaling.</p>
      </div>
      <div class="wh-compare__wrap reveal-scale">
        <table class="wh-table">
          <caption>A general comparison. Your estimate covers the specifics for your home, gas line and venting.</caption>
          <thead>
            <tr><th scope="col"><span class="sr-only">Feature</span></th><th scope="col">Tank water heater</th><th scope="col">Tankless water heater</th></tr>
          </thead>
          <tbody>
            <tr><th scope="row">How it heats</th><td data-label="Tank">Keeps a full tank of water hot all day</td><td data-label="Tankless">Heats water only while a hot tap is open</td></tr>
            <tr><th scope="row">Hot water supply</th><td data-label="Tank">Runs out when the tank is used up, then needs time to recover</td><td data-label="Tankless">Continuous, limited by how many fixtures run at once</td></tr>
            <tr><th scope="row">Space</th><td data-label="Tank">Floor-standing tank in a garage, closet or attic</td><td data-label="Tankless">Compact unit mounted on a wall</td></tr>
            <tr><th scope="row">Installing in an existing home</th><td data-label="Tank">Usually a like-for-like swap on the existing connections</td><td data-label="Tankless">Gas models often need a larger gas line and new venting</td></tr>
            <tr><th scope="row">Hard water care</th><td data-label="Tank">Periodic flushing to clear sediment</td><td data-label="Tankless">Periodic descaling of the heat exchanger</td></tr>
            <tr><th scope="row">Usual fit</th><td data-label="Tank">Straight replacement of a failed tank</td><td data-label="Tankless">Back-to-back showers, or freeing up floor space</td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </section>

  <!-- ============ PROOF ============ -->
  <section class="section on-dark edge-wave-top wh-proof" aria-labelledby="wh-proof-title">
    <span class="grain-layer" aria-hidden="true"></span>
    <div class="container">
      <div class="section-head reveal-up">
        <span class="eyebrow">Proof</span>
        <h2 id="wh-proof-title">How can you check Rock'n G Plumbing before you call?</h2>
        <p class="answer-block">You can check Rock'n G Plumbing two ways: read its Google reviews, and look up license M-44822 with the Texas State Board of Plumbing Examiners. Rock'n G is rated <?php echo h(number_format((float) $gbp['rating'], 1)); ?> out of 5 from <?php echo (int) $gbp['reviews_count']; ?> Google reviews.</p>
      </div>
      <div class="wh-proof__cards">
        <div class="wh-proof__card reveal-left">
          <h3>Google reviews</h3>
          <p>Reviews on Rock'n G Plumbing's Google Business Profile are written by customers, not by us. If Rock'n G has worked on your water heater, your review helps the next San Angelo homeowner pick a plumber.</p>
          <div class="actions">
            <a class="btn btn-accent" href="<?php echo h($gbp['profile_url']); ?>" target="_blank" rel="noopener">Read reviews on Google</a>
            <a class="btn btn-outline-white" href="<?php echo h($gbp['review_url']); ?>" target="_blank" rel="noopener">Leave a review</a>
          </div>
        </div>
        <div class="wh-proof__card reveal-right">
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
      $whReviews = p1_google_reviews($slug, ['limit' => 3, 'heading' => '', 'summary' => false]);
      if ($whReviews !== ''): ?>
      <div class="wh-proof__reviews"><?php echo $whReviews; ?></div>
      <?php endif; ?>
    </div>
  </section>

  <!-- ============ RELATED SERVICES (required services-grid) ============ -->
  <section class="section wh-related" aria-label="Related plumbing services">
    <div class="container">
      <div class="section-title reveal-up">
        <span class="eyebrow-label">What We Do</span>
        <h2>Which other <span class="text-accent">plumbing services</span> go with water heater work?</h2>
        <p class="hero-answer">Water heater calls often lead to three related jobs. Gas heaters and tankless upgrades depend on sound gas piping, a leak near the heater can be a supply line rather than the tank, and a remodel or new build is the time to put the heater where it serves the house best. Rock'n G Plumbing handles all three.</p>
        <span class="section-subtitle">Gas lines · Leaks · Remodels</span>
        <p class="prose">See the full list of <a href="/services/">plumbing services Rock'n G offers in San Angelo</a>.</p>
      </div>
      <div class="services-grid">
        <?php echo renderServiceCards(['gas-line-services', 'leak-detection-repair', 'new-construction-remodel-plumbing'], '(max-width: 640px) 100vw, (max-width: 1000px) 50vw, 380px'); ?>
      </div>
    </div>
  </section>

  <!-- ============ FINAL CTA ============ -->
  <section class="closing-cta texture-grain edge-torn-top wh-cta" aria-labelledby="wh-cta-title">
    <span class="grain-layer" aria-hidden="true"></span>
    <span class="floating-ring wh-cta__ring" aria-hidden="true"></span>
    <div class="container">
      <div class="reveal-left">
        <span class="eyebrow">No charge to look</span>
        <h2 id="wh-cta-title">Ready for a free water heater estimate in San Angelo?</h2>
        <p>Call Rock'n G Plumbing at <?php echo h($phoneFmt); ?>, <?php echo h($weekday[0]); ?> <?php echo h($weekday[1]); ?>, or send the form tonight and the office will follow up during business hours. A heater that is already rumbling or leaking rarely gets cheaper to deal with.</p>
        <p class="wh-updated">Last updated: <?php echo date('F Y'); ?></p>
      </div>
      <div class="actions reveal-right">
        <a class="btn btn-accent btn-lg" href="/contact/" data-open-estimate>Get a Free Water Heater Estimate</a>
        <a class="link-call" href="<?php echo h($telHref); ?>"><?php echo icon('phone', 18); ?>Call <?php echo h($phoneFmt); ?></a>
      </div>
    </div>
  </section>

</article>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>
