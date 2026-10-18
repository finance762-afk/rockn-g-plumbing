<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/functions.php';
?>
<?php
/**
 * services/water-filtration-softeners/index.php — Water Softeners & Filtration (Phase 4, premium editorial layout).
 * Every business fact traces to build-plan.json / includes/config.php ("softeners, reverse osmosis, whole-house filters,
 * San Angelo hard water"). How softeners, RO and filters work is written as general knowledge, never as a Rock'n G
 * promise. No hardness figures, prices or brands (not in intake) — those are asked in /content-confirmations.json.
 * Signature section: the annotated breakdown of the client's own garage install (photo hotspots + legend).
 * SEO brief: no FAQ section/schema, no emergency/24-hour wording, no backflow testing.
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/service-cards.php';

$svc         = serviceBySlug('water-filtration-softeners');
$pageType    = 'service';
$serviceSlug = $svc['slug'];
$pageTitle       = "Water Softener Plumber in San Angelo, TX | Rock'n G";
$pageDescription = "Water softener plumber in San Angelo, TX • Softeners, reverse osmosis & whole-house filters • Master Plumber M-44822, free estimates. Call (325) 650-7000.";
$canonicalUrl    = $siteUrl . '/services/water-filtration-softeners/';
$currentPage     = 'services/water-filtration-softeners';
$breadcrumbs     = [['name' => 'Services', 'url' => '/services/'], ['name' => $svc['name'], 'url' => '/services/water-filtration-softeners/']];

$heroImage   = 'rockn-water-softener-filtration';
$heroAlt     = "Water softener tank, brine tank and two blue whole-house filter housings installed by Rock'n G Plumbing in a garage";
$heroPreload = ['srcset' => imageVariantSrcset($heroImage, 'avif'), 'sizes' => '100vw'];
$ogImage     = '/assets/images/' . $heroImage . '.jpg';

$telHref  = 'tel:' . $phoneE164;
$phoneFmt = formatPhone($phone);
$weekday  = hoursGroups($hours)[0];      // ['Mon–Fri', '8:00 AM – 6:00 PM']
$licenseExpires = date('F j, Y', strtotime($license['expires']));

// Where hard-water scale shows up (general knowledge)
$wsScale = [
    ['icon' => 'droplets', 'title' => 'Faucets and showerheads',
     'body'  => 'White, chalky crust around aerators and spray holes, and showerheads that lose pressure as the holes close up.'],
    ['icon' => 'sun',      'title' => 'Dishes and glass',
     'body'  => 'Spots and a cloudy film on glasses and shower doors that come back no matter how often they are wiped down.'],
    ['icon' => 'wind',     'title' => 'Laundry and soap',
     'body'  => 'Towels that dry stiff and soap that will not lather, because hardness minerals tie up the soap before it can clean.'],
    ['icon' => 'flame',    'title' => 'Water heaters',
     'body'  => 'Mineral sediment settles in the bottom of a tank heater, which is why a rumbling tank is so common in hard-water towns.'],
];

// The three systems in build-plan.json — what an install generally involves
$wsSystems = [
    ['icon' => 'droplets', 'title' => 'Water softeners',
     'body'  => 'Removes the calcium and magnesium that make water hard, for every tap in the house. A softener install generally involves:',
     'items' => ['A resin tank and control valve on the main line', 'A brine tank for the salt', 'A drain line for the regeneration cycle']],
    ['icon' => 'waves', 'title' => 'Reverse osmosis',
     'body'  => 'Pushes water through a fine membrane for cleaner-tasting drinking and cooking water, usually at the kitchen sink. An RO install generally involves:',
     'items' => ['Filters, membrane and a small storage tank under the sink', 'A dedicated drinking-water faucet', 'A connection to the sink drain']],
    ['icon' => 'layers', 'title' => 'Whole-house filtration',
     'body'  => 'Catches sediment and, depending on the cartridge, chlorine taste and odor before water reaches any fixture. A filter install generally involves:',
     'items' => ['Filter housings where the supply enters', 'Shutoff valves for cartridge changes', 'Room to reach the housings later']],
];

// "Read the photo" hotspots — positions in % of rockn-water-treatment-garage (1600×1250), parts visible in the client's photo
$wsParts = [
    ['x' => 40, 'y' => 24, 'title' => 'Control valve',
     'body'  => 'The head on top of the softener tank. It runs the regeneration cycle that rinses the collected minerals out of the resin and down the drain.'],
    ['x' => 41, 'y' => 66, 'title' => 'Resin tank',
     'body'  => 'The tall black tank holds resin beads. As water passes through, the beads trade hardness minerals for sodium, and softened water leaves the top.'],
    ['x' => 57, 'y' => 78, 'title' => 'Brine tank',
     'body'  => 'The square tank beside it holds the salt. The softener draws brine from here to recharge the resin, so keeping it topped up is the main upkeep.'],
    ['x' => 51, 'y' => 40, 'title' => 'Whole-house filter housings',
     'body'  => 'The two big blue housings on the wall hold the filter cartridges. They catch sediment for the whole house, and the cartridges swap out without tools beyond a housing wrench.'],
    ['x' => 31, 'y' => 52, 'title' => 'Shutoff valves',
     'body'  => 'Valves on the blue supply lines let the equipment be isolated for a cartridge change or service without leaving the whole house dry.'],
];

// Service schema (provider = the Plumber node head.php prints on every page)
$serviceNode = generateServiceSchema($svc);
$serviceNode['@id']         = absUrl(serviceUrl($svc)) . '#service-' . $svc['slug'];
$serviceNode['serviceType'] = 'Water softener and water filtration installation';
$serviceNode['description'] = "Water softener installation, reverse osmosis drinking-water systems and whole-house water filtration for San Angelo's hard water, in San Angelo, TX and nearby Tom Green County towns, by Rock'n G Plumbing under Texas Master Plumber License M-44822.";
$serviceNode['image']       = absUrl('/assets/images/' . $heroImage . '.jpg');
$serviceNode['hasOfferCatalog'] = [
    '@type' => 'OfferCatalog', 'name' => 'Water treatment services',
    'itemListElement' => array_map(fn($s) => ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => $s['title']]], $wsSystems),
];
$serviceNode["@context"] = 'https://schema.org';   // explicit (generateServiceSchema sets it too)
$schemaMarkup = [$serviceNode];

ob_start();
?>
<style>
/* =====================================================================
   Water Softeners & Filtration — page-only CSS (ws- prefix)
   Tokens from framework.css; no raw colors, shadows or spacing.
   ===================================================================== */
@property --ws-scale {
  syntax: '<length>';
  inherits: false;
  initial-value: 6px;
}

/* ---------- Hero: layered photo (C1) ---------- */
.ws-hero {
  padding-block-end: var(--space-16);
}
.ws-hero .hero-bg {
  overflow: hidden;
}
.ws-hero .hero-bg picture,
.ws-hero .hero-bg img {
  width: 100%;
  height: 100%;
}
.ws-hero .hero-bg img {
  object-fit: cover;
  object-position: 50% 58%;
  animation: ws-rise 24s var(--ease) both;
}
.ws-hero::before {
  content: "";
  position: absolute;
  inset: 0;
  z-index: -1;
  pointer-events: none;
  background:
    radial-gradient(ellipse 55% 60% at 0% 100%, color-mix(in srgb, var(--color-accent) 30%, transparent), transparent 70%),
    linear-gradient(200deg, transparent 40%, color-mix(in srgb, var(--color-primary-deep) 78%, transparent) 100%);
}
.ws-hero::after {
  content: "";
  position: absolute;
  inset: 0;
  z-index: -1;
  pointer-events: none;
  opacity: .18;
  mix-blend-mode: overlay;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='160' height='160'%3E%3Cfilter id='g'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.9' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23g)'/%3E%3C/svg%3E");
}
.ws-hero .breadcrumb {
  color: color-mix(in srgb, var(--color-surface) 72%, transparent);
  margin-block-end: var(--space-2);
}
.ws-hero .breadcrumb li {
  display: inline-flex;
  align-items: center;
}
.ws-hero .breadcrumb a:hover {
  color: var(--color-accent-bright);
}
.ws-hero .breadcrumb [aria-current="page"] {
  color: var(--color-surface);
}
.ws-hero .hero-title {
  max-width: 17ch;
  text-wrap: balance;
}
.ws-hero .hero-answer {
  max-width: 36rem;
}
.ws-hero .hero-chips li {
  backdrop-filter: blur(6px);
  -webkit-backdrop-filter: blur(6px);
}
.ws-hero .hero-form-open {
  background: var(--color-accent);
  color: var(--color-white);
}
.ws-hero .hero-form-card {
  border-block-start: 4px solid var(--color-accent);
}
.ws-hero .hero-form-card h2 {
  text-wrap: balance;
}
.ws-hero .hero-form .consent {
  margin-block: var(--space-1);
}
@keyframes ws-rise {
  from { transform: scale(1.08) translateY(2%); }
  to   { transform: scale(1) translateY(0); }
}

/* ---------- Article ---------- */
.ws-article > section h2 {
  text-wrap: balance;
}
.ws-article .answer-block {
  font-size: var(--fs-lead);
  line-height: 1.55;
}

/* ---------- Hard water explainer: pipe cross-section + scale list ---------- */
.ws-hard {
  background: var(--color-paper);
}
.ws-hard__grid {
  display: grid;
  grid-template-columns: minmax(0, 5fr) minmax(0, 4fr);
  gap: var(--space-12);
  align-items: center;
}
.ws-hard__copy {
  display: grid;
  gap: var(--space-4);
}
.ws-hard__copy > p {
  margin: 0;
  color: var(--color-ink-2);
  max-width: var(--measure);
}
.ws-hard__copy a {
  color: var(--color-primary);
  font-weight: 600;
}
.ws-pipe {
  position: relative;
  justify-self: center;
  width: min(100%, 360px);
  aspect-ratio: 1;
  display: grid;
  place-items: center;
}
.ws-pipe__ring {
  position: absolute;
  inset: 0;
  border-radius: var(--radius-full);
  background: var(--color-secondary);
  box-shadow: var(--shadow-lg), inset 0 0 0 10px var(--color-primary);
}
.ws-pipe__scale {
  position: absolute;
  inset: 22px;
  border-radius: var(--radius-full);
  background:
    repeating-radial-gradient(circle, color-mix(in srgb, var(--color-paper) 92%, var(--color-warning)) 0 3px, color-mix(in srgb, var(--color-paper-2) 80%, var(--color-muted)) 3px 6px);
  box-shadow: inset 0 0 0 1px var(--color-line);
}
.ws-pipe__bore {
  position: absolute;
  inset: calc(22px + var(--ws-scale) * 5);
  border-radius: var(--radius-full);
  background: radial-gradient(circle at 35% 35%, var(--color-accent-bright), var(--color-accent-dark));
  box-shadow: inset 0 0 0 3px color-mix(in srgb, var(--color-ink) 18%, transparent);
  animation: ws-choke 7s var(--ease) infinite alternate;
}
@keyframes ws-choke {
  from { --ws-scale: 4px; }
  to   { --ws-scale: 13px; }
}
.ws-pipe__label {
  position: absolute;
  inset-block-end: calc(-1 * var(--space-6));
  inset-inline: 0;
  text-align: center;
  font-family: var(--font-accent);
  font-size: var(--fs-eyebrow);
  letter-spacing: .14em;
  text-transform: uppercase;
  color: var(--color-muted);
}
.ws-scale-list {
  list-style: none;
  margin: var(--space-4) 0 0;
  padding: 0;
  display: grid;
  grid-template-columns: repeat(2, minmax(0, 1fr));
  gap: var(--space-3);
}
.ws-scale-list li {
  display: grid;
  grid-template-columns: auto 1fr;
  gap: var(--space-1) var(--space-3);
  padding: var(--space-4);
  border-radius: var(--radius);
  background: var(--color-surface);
  border-inline-start: 3px solid var(--color-accent);
  box-shadow: var(--shadow-sm);
}
.ws-scale-list svg {
  grid-row: span 2;
  color: var(--color-accent-dark);
}
.ws-scale-list b {
  font-family: var(--font-heading);
  color: var(--color-ink);
}
.ws-scale-list span {
  font-size: var(--fs-small);
  color: var(--color-ink-2);
}

/* ---------- Systems: three stacked columns with a shared waterline ---------- */
.ws-systems {
  background: var(--color-surface);
}
.ws-systems__row {
  position: relative;
  display: grid;
  grid-template-columns: repeat(3, minmax(0, 1fr));
  gap: var(--space-6);
  margin-block-start: var(--space-10);
  padding-block-start: var(--space-8);
}
.ws-systems__row::before {
  content: "";
  position: absolute;
  inset-inline: 0;
  inset-block-start: 0;
  height: 6px;
  border-radius: var(--radius-full);
  background: linear-gradient(90deg, var(--color-accent-dark), var(--color-accent), var(--color-accent-bright));
}
.ws-system {
  position: relative;
  display: grid;
  align-content: start;
  gap: var(--space-3);
  padding: var(--space-8) var(--space-6) var(--space-6);
  border-radius: 0 0 var(--radius-lg) var(--radius-lg);
  border: 1px solid var(--color-line);
  border-block-start: 0;
  transition: transform var(--transition), box-shadow var(--transition);
}
.ws-system::before {
  content: "";
  position: absolute;
  inset-block-start: calc(-1 * var(--space-8) - 3px);
  inset-inline-start: var(--space-6);
  width: 3px;
  height: calc(var(--space-8) + 3px);
  background: var(--color-accent);
}
.ws-system:hover {
  transform: translateY(-4px);
  box-shadow: var(--shadow);
}
.ws-system__icon {
  width: 48px;
  height: 48px;
  display: grid;
  place-items: center;
  border-radius: var(--radius-full);
  color: var(--color-primary);
  background: var(--color-surface);
  border: 2px solid var(--color-accent);
}
.ws-system h3 {
  font-size: var(--fs-h3);
}
.ws-system p {
  margin: 0;
  font-size: var(--fs-small);
  color: var(--color-ink-2);
}
.ws-system ul {
  margin: 0;
  padding: var(--space-3) 0 0;
  list-style: none;
  display: grid;
  gap: var(--space-2);
  border-block-start: 1px solid var(--color-line);
}
.ws-system li {
  display: flex;
  gap: var(--space-2);
  align-items: flex-start;
  font-size: var(--fs-small);
  color: var(--color-ink);
}
.ws-system li svg {
  flex: 0 0 auto;
  margin-block-start: var(--space-1);
  color: var(--color-accent-dark);
}
.ws-systems__note {
  margin: var(--space-8) 0 0;
  color: var(--color-ink-2);
  max-width: var(--measure);
}
.ws-systems__note a {
  color: var(--color-primary);
  font-weight: 600;
}

/* ---------- Comparison table (three systems) ---------- */
.ws-compare {
  background: var(--color-paper-2);
}
.ws-compare__wrap {
  container-type: inline-size;
  container-name: wstable;
  margin-block-start: var(--space-8);
  overflow-x: auto;
}
.ws-table {
  width: 100%;
  min-width: 0;
  border-collapse: collapse;
  font-size: var(--fs-small);
  background: var(--color-surface);
  border-radius: var(--radius-lg);
  overflow: hidden;
  box-shadow: var(--shadow);
}
.ws-table caption {
  caption-side: bottom;
  text-align: start;
  padding-block-start: var(--space-3);
  color: var(--color-muted);
}
.ws-table th,
.ws-table td {
  padding: var(--space-4) var(--space-5);
  text-align: start;
  vertical-align: top;
}
.ws-table thead th {
  font-family: var(--font-heading);
  font-size: 1rem;
  color: var(--color-ink);
  background: var(--color-surface);
  border-block-end: 3px solid var(--color-accent);
}
.ws-table thead th:first-child {
  background: var(--color-paper);
}
.ws-table tbody th {
  width: 20%;
  font-family: var(--font-accent);
  font-size: var(--fs-eyebrow);
  font-weight: 700;
  letter-spacing: .12em;
  text-transform: uppercase;
  color: var(--color-muted);
  background: var(--color-paper);
}
.ws-table tbody td {
  color: var(--color-ink-2);
  border-inline-start: 1px solid var(--color-line);
}
.ws-table tbody tr + tr th,
.ws-table tbody tr + tr td {
  border-block-start: 1px solid var(--color-line);
}
.ws-table tbody td:hover {
  background: color-mix(in srgb, var(--color-accent) 7%, var(--color-surface));
}
.ws-yes,
.ws-no {
  display: inline-flex;
  align-items: center;
  gap: var(--space-1);
  font-weight: 700;
}
.ws-yes {
  color: var(--color-success);
}
.ws-no {
  color: var(--color-muted);
}
@container wstable (max-width: 680px) {
  .ws-table thead {
    position: absolute;
    width: 1px;
    height: 1px;
    overflow: hidden;
    clip-path: inset(50%);
  }
  .ws-table,
  .ws-table tbody,
  .ws-table tr,
  .ws-table th,
  .ws-table td {
    display: block;
    width: auto;
  }
  .ws-table tbody th {
    width: auto;
  }
  .ws-table caption {
    display: block;
    padding-block: 0 var(--space-3);
  }
  .ws-table tbody tr {
    padding-block: var(--space-2);
  }
  .ws-table tbody td {
    border-inline-start: 0;
    padding-block: var(--space-2);
  }
  .ws-table tbody td::before {
    content: attr(data-label) ": ";
    font-weight: 700;
    color: var(--color-ink);
  }
}

/* ---------- Signature: read the photo (dark, parallelogram edge) ---------- */
.ws-anatomy .section-head {
  max-width: 64ch;
}
.ws-anatomy .answer-block {
  background: color-mix(in srgb, var(--color-surface) 6%, transparent);
  color: color-mix(in srgb, var(--color-surface) 88%, transparent);
}
.ws-anatomy__grid {
  display: grid;
  grid-template-columns: minmax(0, 3fr) minmax(0, 2fr);
  gap: var(--space-10);
  align-items: start;
  margin-block-start: var(--space-8);
}
.ws-anatomy__figure {
  position: relative;
  margin: 0;
}
.ws-anatomy__photo {
  position: relative;
  aspect-ratio: 1600 / 1250;
  border-radius: var(--radius-lg);
  overflow: hidden;
  box-shadow: var(--shadow-xl);
  outline: 1px solid color-mix(in srgb, var(--color-surface) 18%, transparent);
  outline-offset: var(--space-2);
}
.ws-anatomy__photo picture,
.ws-anatomy__photo img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}
.ws-pin {
  position: absolute;
  left: calc(var(--x) * 1%);
  top: calc(var(--y) * 1%);
  translate: -50% -50%;
  width: 34px;
  height: 34px;
  display: grid;
  place-items: center;
  border-radius: var(--radius-full);
  font-family: var(--font-accent);
  font-size: 1.05rem;
  font-weight: 700;
  color: var(--color-ink);
  background: var(--color-accent);
  box-shadow: 0 0 0 4px color-mix(in srgb, var(--color-accent) 35%, transparent), var(--shadow);
}
.ws-pin::after {
  content: "";
  position: absolute;
  inset: -4px;
  border-radius: var(--radius-full);
  border: 2px solid var(--color-accent-bright);
  animation: ws-ping 2.8s var(--ease) infinite;
}
@keyframes ws-ping {
  from { transform: scale(1); opacity: .8; }
  to   { transform: scale(1.8); opacity: 0; }
}
.ws-anatomy__figure figcaption {
  margin-block-start: var(--space-4);
  font-size: var(--fs-small);
  color: color-mix(in srgb, var(--color-surface) 70%, transparent);
}
.ws-legend {
  list-style: none;
  margin: 0;
  padding: 0;
  display: grid;
  gap: var(--space-3);
  counter-reset: ws-part;
}
.ws-legend li {
  counter-increment: ws-part;
  display: grid;
  grid-template-columns: 34px 1fr;
  gap: var(--space-1) var(--space-3);
  padding: var(--space-3) var(--space-4);
  border-radius: var(--radius);
  background: color-mix(in srgb, var(--color-surface) 5%, transparent);
  border: 1px solid color-mix(in srgb, var(--color-surface) 12%, transparent);
  transition: background-color var(--transition), border-color var(--transition);
}
.ws-legend li:hover {
  background: color-mix(in srgb, var(--color-accent) 12%, transparent);
  border-color: var(--color-accent);
}
.ws-legend li::before {
  content: counter(ws-part);
  grid-row: span 2;
  width: 34px;
  height: 34px;
  display: grid;
  place-items: center;
  border-radius: var(--radius-full);
  font-family: var(--font-accent);
  font-weight: 700;
  color: var(--color-ink);
  background: var(--color-accent);
}
.ws-legend b {
  font-family: var(--font-heading);
  color: var(--color-surface);
}
.ws-legend span {
  font-size: var(--fs-small);
  color: color-mix(in srgb, var(--color-surface) 78%, transparent);
}

/* ---------- Expert: license stamp + ribbons (curved edge) ---------- */
.ws-expert {
  background: var(--color-surface);
}
.ws-expert__grid {
  display: grid;
  grid-template-columns: minmax(0, 2fr) minmax(0, 3fr);
  gap: var(--space-12);
  align-items: center;
}
.ws-stamp {
  position: relative;
  justify-self: center;
  display: grid;
  gap: var(--space-2);
  text-align: center;
  padding: var(--space-10) var(--space-8);
  border-radius: var(--radius-lg);
  border: 3px dashed var(--color-primary);
  background:
    radial-gradient(circle at 50% 0%, color-mix(in srgb, var(--color-accent) 14%, transparent), transparent 70%),
    var(--color-paper);
  rotate: -3deg;
  box-shadow: var(--shadow-lg);
  transition: rotate var(--transition-slow);
}
.ws-stamp:hover {
  rotate: 0deg;
}
.ws-stamp small {
  font-family: var(--font-accent);
  font-size: var(--fs-eyebrow);
  letter-spacing: .16em;
  text-transform: uppercase;
  color: var(--color-muted);
}
.ws-stamp b {
  font-family: var(--font-accent);
  font-size: clamp(2.6rem, 1.8rem + 3vw, 4.25rem);
  line-height: 1;
  color: var(--color-primary);
}
.ws-stamp span {
  font-size: var(--fs-small);
  color: var(--color-ink-2);
}
.ws-expert__copy {
  display: grid;
  gap: var(--space-4);
}
.ws-ribbons {
  list-style: none;
  margin: 0;
  padding: 0;
  display: grid;
  gap: var(--space-2);
}
.ws-ribbons li {
  display: flex;
  gap: var(--space-3);
  align-items: flex-start;
  padding: var(--space-3) var(--space-4);
  background: linear-gradient(90deg, var(--color-paper-2), transparent);
  border-inline-start: 4px solid var(--color-primary);
  border-radius: 0 var(--radius) var(--radius) 0;
}
.ws-ribbons svg {
  flex: 0 0 auto;
  color: var(--color-accent-dark);
  margin-block-start: var(--space-1);
}
.ws-ribbons p {
  margin: 0;
  color: var(--color-ink-2);
  font-size: var(--fs-small);
}
.ws-ribbons b {
  color: var(--color-ink);
}
.ws-ribbons a {
  color: var(--color-primary);
  font-weight: 600;
}

/* ---------- Process: horizontal stepper ---------- */
.ws-process {
  background: var(--color-paper);
}
.ws-steps {
  list-style: none;
  margin: var(--space-10) 0 0;
  padding: 0;
  display: grid;
  grid-template-columns: repeat(4, minmax(0, 1fr));
  gap: var(--space-6);
  counter-reset: ws-step;
  position: relative;
}
.ws-steps::before {
  content: "";
  position: absolute;
  inset-inline: var(--space-6);
  inset-block-start: 22px;
  height: 2px;
  background: repeating-linear-gradient(90deg, var(--color-accent) 0 10px, transparent 10px 18px);
}
.ws-steps li {
  counter-increment: ws-step;
  position: relative;
  display: grid;
  gap: var(--space-2);
  align-content: start;
}
.ws-steps li::before {
  content: counter(ws-step);
  position: relative;
  width: 46px;
  height: 46px;
  display: grid;
  place-items: center;
  border-radius: var(--radius-full);
  font-family: var(--font-accent);
  font-size: 1.25rem;
  color: var(--color-surface);
  background: var(--color-primary);
  box-shadow: 0 0 0 6px var(--color-paper);
}
.ws-steps b {
  font-family: var(--font-heading);
  color: var(--color-ink);
}
.ws-steps span {
  font-size: var(--fs-small);
  color: var(--color-ink-2);
}

/* ---------- Proof: reviews + dark license card (light band) ---------- */
.ws-proof {
  background: var(--color-paper-2);
}
.ws-proof__grid {
  display: grid;
  grid-template-columns: minmax(0, 3fr) minmax(0, 2fr);
  gap: var(--space-8);
  align-items: start;
}
.ws-proof__copy {
  display: grid;
  gap: var(--space-4);
}
.ws-proof__copy .actions {
  display: flex;
  flex-wrap: wrap;
  gap: var(--space-3);
}
.ws-license {
  display: grid;
  gap: var(--space-3);
  padding: var(--space-6);
  border-radius: var(--radius-lg);
  color: var(--color-surface);
  background: linear-gradient(150deg, var(--color-dark), var(--color-primary-deep));
  box-shadow: var(--shadow-lg);
}
.ws-license h3 {
  color: var(--color-surface);
}
.ws-license dl {
  margin: 0;
  display: grid;
  grid-template-columns: auto 1fr;
  gap: var(--space-2) var(--space-4);
  font-size: var(--fs-small);
}
.ws-license dt {
  color: color-mix(in srgb, var(--color-surface) 62%, transparent);
}
.ws-license dd {
  margin: 0;
  font-weight: 600;
}
.ws-proof__reviews {
  margin-block-start: var(--space-8);
}
.ws-proof__reviews .p1gr-text {
  color: var(--color-ink-2);
}

/* ---------- Related services ---------- */
.ws-related {
  background: var(--color-surface);
}
.ws-related .services-grid {
  grid-template-columns: repeat(3, minmax(0, 1fr));
}
.ws-related .service-card-with-image h3 {
  text-wrap: balance;
}

/* ---------- Final CTA: chevron edge ---------- */
.ws-cta {
  clip-path: polygon(0 0, 50% 3vw, 100% 0, 100% 100%, 0 100%);
  padding-block-start: calc(var(--section-pad) + 3vw);
}
.ws-cta .container {
  align-items: center;
}
.ws-cta h2 {
  text-wrap: balance;
  max-width: 22ch;
}
.ws-cta .link-call {
  color: color-mix(in srgb, var(--color-surface) 88%, transparent);
}
.ws-cta .link-call svg {
  color: var(--color-accent-bright);
}
.ws-cta__ring {
  inset-inline-end: -120px;
  inset-block-start: -60px;
}
.ws-updated {
  margin-block-start: var(--space-6);
  font-size: var(--fs-small);
}

/* ---------- Responsive ---------- */
@media (max-width: 1000px) {
  .ws-systems__row,
  .ws-related .services-grid {
    grid-template-columns: repeat(2, minmax(0, 1fr));
  }
  .ws-steps {
    grid-template-columns: repeat(2, minmax(0, 1fr));
  }
  .ws-steps::before {
    display: none;
  }
}
@media (max-width: 900px) {
  .ws-hard__grid,
  .ws-anatomy__grid,
  .ws-expert__grid,
  .ws-proof__grid {
    grid-template-columns: 1fr;
  }
  .ws-pipe {
    width: min(60%, 240px);
    margin-block-start: var(--space-6);
  }
  .ws-stamp {
    rotate: 0deg;
  }
}
@media (max-width: 640px) {
  .ws-scale-list,
  .ws-systems__row,
  .ws-steps,
  .ws-related .services-grid {
    grid-template-columns: 1fr;
  }
  .ws-pin {
    width: 28px;
    height: 28px;
    font-size: .9rem;
  }
}
@media (prefers-reduced-motion: reduce) {
  .ws-hero .hero-bg img,
  .ws-pipe__bore,
  .ws-pin::after {
    animation: none;
  }
  .ws-system,
  .ws-stamp,
  .ws-legend li {
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
<section class="hero hero--photo hero--interior ws-hero" aria-labelledby="ws-h1">
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
            <li><span aria-current="page">Water Softeners &amp; Filtration</span></li>
          </ol>
        </nav>
        <span class="eyebrow">Hard Water · <?php echo h($address['city'] . ', ' . $address['state']); ?></span>
        <h1 class="hero-title" id="ws-h1">Water Softeners and Filtration from a San Angelo Plumber</h1>
        <p class="hero-answer">Rock'n G Plumbing installs water softeners, reverse osmosis drinking-water systems and whole-house filters for homes and businesses dealing with San Angelo's hard water, from Christoval to Grape Creek. Master plumber Eli Gomez holds the Texas license the work runs under, and estimates are free.</p>
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
        <h2 id="hero-form-title">Need help with hard water?</h2>
        <p class="hero-form-tagline">Tell us what you're seeing. No obligation.</p>
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

<article class="ws-article" aria-labelledby="ws-h1">

  <!-- ============ PROBLEM: HARD WATER ============ -->
  <section class="section ws-hard" aria-labelledby="ws-hard-title">
    <div class="container">
      <div class="ws-hard__grid">
        <div class="ws-hard__copy reveal-left">
          <span class="eyebrow">Hard water in San Angelo</span>
          <h2 id="ws-hard-title">Why is San Angelo's water so hard on plumbing?</h2>
          <p class="answer-block">San Angelo's water is hard, meaning it carries dissolved calcium and magnesium that leave scale wherever water is heated or dries. Over time that scale crusts faucets and showerheads, spots dishes and glass, and builds up inside water heaters and fixtures, which shortens their working life.</p>
          <p>Scale is not a safety problem, but it is a steady cost. It narrows the openings in aerators and valves, makes soap work harder, and settles in the bottom of a tank heater, where it is behind a lot of the rumbling and short hot water covered on the <a href="/services/water-heaters/">water heater repair and installation</a> page.</p>
          <ul class="ws-scale-list">
            <?php foreach ($wsScale as $i => $spot): ?>
            <li class="reveal-up reveal-delay-<?php echo ($i % 3) + 1; ?>"><?php echo icon($spot['icon'], 22); ?><b><?php echo h($spot['title']); ?></b><span><?php echo h($spot['body']); ?></span></li>
            <?php endforeach; ?>
          </ul>
        </div>
        <div class="ws-pipe reveal-scale" aria-hidden="true">
          <span class="ws-pipe__ring"></span>
          <span class="ws-pipe__scale"></span>
          <span class="ws-pipe__bore"></span>
          <span class="ws-pipe__label">Scale narrows the pipe</span>
        </div>
      </div>
    </div>
  </section>

  <!-- ============ SERVICE BREAKDOWN ============ -->
  <section class="section ws-systems" aria-labelledby="ws-systems-title">
    <div class="container">
      <div class="section-head reveal-up">
        <span class="eyebrow">What's included</span>
        <h2 id="ws-systems-title">Which water treatment systems does Rock'n G Plumbing install?</h2>
        <p class="answer-block">Rock'n G Plumbing installs three kinds of water treatment: water softeners for whole-house hard water, reverse osmosis systems for drinking water, and whole-house filters for sediment and taste. Some homes pair a softener with one of the other two, and the estimate sorts out which mix fits yours.</p>
      </div>
      <div class="ws-systems__row">
        <?php foreach ($wsSystems as $i => $sys): ?>
        <div class="ws-system card-tint-<?php echo $i + 1; ?> reveal-up reveal-delay-<?php echo $i + 1; ?>">
          <span class="ws-system__icon"><?php echo icon($sys['icon'], 22); ?></span>
          <h3><?php echo h($sys['title']); ?></h3>
          <p><?php echo h($sys['body']); ?></p>
          <ul>
            <?php foreach ($sys['items'] as $item): ?>
            <li><?php echo icon('check', 16); ?><?php echo h($item); ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
        <?php endforeach; ?>
      </div>
      <p class="ws-systems__note reveal-up">Planning a kitchen or bath remodel, or building new? That is the easiest time to leave room and connections for a softener, filter housings or an RO faucet. See <a href="/services/new-construction-remodel-plumbing/">new construction and remodel plumbing</a>.</p>
    </div>
  </section>

  <!-- ============ COMPARISON ============ -->
  <section class="section edge-curve-top ws-compare" aria-labelledby="ws-compare-title">
    <div class="container">
      <div class="section-head reveal-up">
        <span class="eyebrow">Compare</span>
        <h2 id="ws-compare-title">Softener, reverse osmosis or whole-house filter: which one do you need?</h2>
        <p class="answer-block">A water softener fixes hard water, a reverse osmosis system improves drinking water at one tap, and a whole-house filter catches sediment and taste problems everywhere. They solve different problems, so the right answer for hard water in San Angelo can be a softener plus one of the others.</p>
      </div>
      <div class="ws-compare__wrap reveal-scale">
        <table class="ws-table">
          <caption>General comparison of the three systems. Your estimate covers what fits your water and your home.</caption>
          <thead>
            <tr><th scope="col"><span class="sr-only">Question</span></th><th scope="col">Water softener</th><th scope="col">Reverse osmosis</th><th scope="col">Whole-house filter</th></tr>
          </thead>
          <tbody>
            <tr><th scope="row">Main job</th><td data-label="Softener">Removes hardness minerals (calcium and magnesium)</td><td data-label="Reverse osmosis">Removes most dissolved solids from drinking water</td><td data-label="Whole-house filter">Catches sediment; carbon cartridges reduce chlorine taste and odor</td></tr>
            <tr><th scope="row">Softens water?</th><td data-label="Softener"><span class="ws-yes"><?php echo icon('check', 16); ?>Yes</span></td><td data-label="Reverse osmosis"><span class="ws-no"><?php echo icon('minus', 16); ?>At one tap only</span></td><td data-label="Whole-house filter"><span class="ws-no"><?php echo icon('minus', 16); ?>No</span></td></tr>
            <tr><th scope="row">Where it goes</th><td data-label="Softener">On the main line, often in the garage</td><td data-label="Reverse osmosis">Under the kitchen sink, with its own faucet</td><td data-label="Whole-house filter">Where the supply enters the house</td></tr>
            <tr><th scope="row">Water it treats</th><td data-label="Softener">Every tap in the house</td><td data-label="Reverse osmosis">Drinking and cooking water</td><td data-label="Whole-house filter">Every tap in the house</td></tr>
            <tr><th scope="row">Upkeep</th><td data-label="Softener">Keep salt in the brine tank</td><td data-label="Reverse osmosis">Replace pre-filters and the membrane on the maker's schedule</td><td data-label="Whole-house filter">Swap cartridges when flow drops or on schedule</td></tr>
            <tr><th scope="row">Protects appliances?</th><td data-label="Softener">Yes, slows scale in heaters and fixtures</td><td data-label="Reverse osmosis">No, it only serves the faucet it feeds</td><td data-label="Whole-house filter">Keeps grit out of valves and aerators</td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </section>

  <!-- ============ SIGNATURE: READ THE PHOTO ============ -->
  <section class="section on-dark edge-parallelogram-top ws-anatomy" aria-labelledby="ws-anatomy-title">
    <span class="grain-layer" aria-hidden="true"></span>
    <div class="container">
      <div class="section-head reveal-up">
        <span class="eyebrow">A real Rock'n G install</span>
        <h2 id="ws-anatomy-title">What does a finished water softener and filter install look like?</h2>
        <p class="answer-block">The photo shows a finished Rock'n G install in a customer's garage: a water softener, its brine tank and two whole-house filter housings. Each numbered point explains what that part does, so the names make sense when you talk through your own system during the estimate.</p>
      </div>
      <div class="ws-anatomy__grid">
        <figure class="ws-anatomy__figure reveal-left">
          <div class="ws-anatomy__photo">
            <?php echo pictureTag('rockn-water-treatment-garage', "Rock'n G Plumbing install in a garage: black softener resin tank with control valve, square brine tank, and two blue whole-house filter housings on blue supply lines", '(max-width: 900px) 92vw, 680px'); ?>
            <?php foreach ($wsParts as $i => $part): ?>
            <span class="ws-pin" style="--x: <?php echo (int) $part['x']; ?>; --y: <?php echo (int) $part['y']; ?>" aria-hidden="true"><?php echo $i + 1; ?></span>
            <?php endforeach; ?>
          </div>
          <figcaption>Same job as the photo at the top of this page, shot wider. Numbers match the list.</figcaption>
        </figure>
        <ol class="ws-legend reveal-right">
          <?php foreach ($wsParts as $part): ?>
          <li><b><?php echo h($part['title']); ?></b><span><?php echo h($part['body']); ?></span></li>
          <?php endforeach; ?>
        </ol>
      </div>
    </div>
  </section>

  <!-- ============ EXPERT POSITIONING ============ -->
  <section class="section ws-expert" aria-labelledby="ws-expert-title">
    <div class="container">
      <div class="ws-expert__grid">
        <div class="ws-stamp reveal-scale">
          <small>Texas Master Plumber</small>
          <b><?php echo h($license['number']); ?></b>
          <span><?php echo h($license['holder']); ?> · current through <?php echo h($licenseExpires); ?></span>
        </div>
        <div class="ws-expert__copy reveal-right">
          <span class="eyebrow">Who does the work</span>
          <h2 id="ws-expert-title">Who installs your water softener when you call Rock'n G?</h2>
          <p class="answer-block">Water softener and filtration installs at Rock'n G Plumbing run under Eli Gomez's Texas Master Plumber License M-44822. Eli is the Responsible Master Plumber on that license and comes from three generations of plumbers, with 20+ years of experience across residential, commercial and industrial work.</p>
          <ul class="ws-ribbons">
            <li><?php echo icon('badge-check', 20); ?><p><b>Tied into your main line.</b> A softener or whole-house filter is cut into the water supply for the whole house, so the connections, shutoffs and drain line are plumbing work, not an appliance hookup.</p></li>
            <li><?php echo icon('map-pin', 20); ?><p><b>Local water, local towns.</b> Rock'n G works across Tom Green County, from San Angelo and Christoval to Wall, Veribest, Carlsbad, Grape Creek and Water Valley. If your home runs on a private well rather than city water, say so when you call.</p></li>
            <li><?php echo icon('flame', 20); ?><p><b>Heater and softener in one visit.</b> Replacing a scaled-up tank? Rock'n G also handles <a href="/services/water-heaters/">water heater replacement</a>, so the new heater can start on softened water.</p></li>
          </ul>
        </div>
      </div>
    </div>
  </section>

  <!-- ============ PROCESS ============ -->
  <section class="section slant-top ws-process" aria-labelledby="ws-process-title">
    <div class="container">
      <div class="section-head reveal-up">
        <span class="eyebrow">Step by step</span>
        <h2 id="ws-process-title">How does a water treatment install with Rock'n G work?</h2>
        <p class="answer-block">A water treatment install with Rock'n G Plumbing takes four steps, from your first call to a working system. You describe what you are seeing, Rock'n G prices the system at no charge, you choose, and the install is done under the master license.</p>
      </div>
      <ol class="ws-steps">
        <li class="reveal-up reveal-delay-1"><b>Tell us what you see</b><span>Call <?php echo h($phoneFmt); ?>, <?php echo h($weekday[0]); ?> <?php echo h($weekday[1]); ?>, or send the form. Crust on fixtures, spotty dishes, a taste or smell, and city water or a well all help.</span></li>
        <li class="reveal-up reveal-delay-2"><b>Estimate at no charge</b><span>Rock'n G prices the system that fits the problem: a softener, an RO system, a whole-house filter, or a combination.</span></li>
        <li class="reveal-up reveal-delay-3"><b>You choose</b><span>Go ahead when the plan makes sense. Mention military service and ask how the discount applies.</span></li>
        <li class="reveal-up reveal-delay-1"><b>The install</b><span>The system is installed under <?php echo h($ownerName); ?>'s Texas Master Plumber license.</span></li>
      </ol>
    </div>
  </section>

  <!-- ============ PROOF ============ -->
  <section class="section ws-proof" aria-labelledby="ws-proof-title">
    <div class="container">
      <div class="ws-proof__grid">
        <div class="ws-proof__copy reveal-left">
          <span class="eyebrow">Proof</span>
          <h2 id="ws-proof-title">What do customers say about Rock'n G Plumbing on Google?</h2>
          <p class="answer-block">Customers post their reviews of Rock'n G Plumbing on its Google Business Profile, where it is rated <?php echo h(number_format((float) $gbp['rating'], 1)); ?> out of 5 from <?php echo (int) $gbp['reviews_count']; ?> reviews. Read them there, and if Rock'n G has installed your softener or filters, your own review helps the next San Angelo homeowner choose.</p>
          <div class="actions">
            <a class="btn btn-primary" href="<?php echo h($gbp['profile_url']); ?>" target="_blank" rel="noopener">Read reviews on Google</a>
            <a class="btn btn-secondary" href="<?php echo h($gbp['review_url']); ?>" target="_blank" rel="noopener">Leave a review</a>
          </div>
        </div>
        <div class="ws-license reveal-right">
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
      $wsReviews = p1_google_reviews($slug, ['limit' => 3, 'heading' => '', 'summary' => false]);
      if ($wsReviews !== ''): ?>
      <div class="ws-proof__reviews"><?php echo $wsReviews; ?></div>
      <?php endif; ?>
    </div>
  </section>

  <!-- ============ RELATED SERVICES (required services-grid) ============ -->
  <section class="section ws-related" aria-label="Related plumbing services">
    <div class="container">
      <div class="section-title reveal-up">
        <span class="eyebrow-label">What We Do</span>
        <h2>Which other <span class="text-accent">plumbing services</span> help a home with hard water?</h2>
        <p class="hero-answer">Hard water reaches more than the faucets. Scale shortens the life of a water heater, a pinhole leak in a supply line can hide under a slab, and a remodel is the easiest time to plan room for a softener and filters. Rock'n G Plumbing handles all three alongside water treatment.</p>
        <span class="section-subtitle">Water heaters · Leaks · Remodels</span>
        <p class="prose">See every <a href="/services/">plumbing service Rock'n G offers in San Angelo</a>.</p>
      </div>
      <div class="services-grid">
        <?php echo renderServiceCards(['water-heaters', 'leak-detection-repair', 'new-construction-remodel-plumbing'], '(max-width: 640px) 100vw, (max-width: 1000px) 50vw, 380px'); ?>
      </div>
    </div>
  </section>

  <!-- ============ FINAL CTA ============ -->
  <section class="closing-cta texture-grain ws-cta" aria-labelledby="ws-cta-title">
    <span class="grain-layer" aria-hidden="true"></span>
    <span class="floating-ring ws-cta__ring" aria-hidden="true"></span>
    <div class="container">
      <div class="reveal-left">
        <span class="eyebrow">No charge to look</span>
        <h2 id="ws-cta-title">Ready for a free water softener estimate in San Angelo?</h2>
        <p>Call Rock'n G Plumbing at <?php echo h($phoneFmt); ?>, <?php echo h($weekday[0]); ?> <?php echo h($weekday[1]); ?>, or send the form and the office will follow up during business hours. Every month of hard water adds a little more scale to the heater and fixtures you already own.</p>
        <p class="ws-updated">Last updated: <?php echo date('F Y'); ?></p>
      </div>
      <div class="actions reveal-right">
        <a class="btn btn-accent btn-lg" href="/contact/" data-open-estimate>Get a Free Water Softener Estimate</a>
        <a class="link-call" href="<?php echo h($telHref); ?>"><?php echo icon('phone', 18); ?>Call <?php echo h($phoneFmt); ?></a>
      </div>
    </div>
  </section>

</article>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>
