<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/functions.php';
?>
<?php
/**
 * services/sewer-line-repair/index.php — Sewer Line Repair & Camera Inspection (Phase 4, premium editorial layout).
 * Every business fact traces to build-plan.json / includes/config.php. How sewer lines fail and what a camera shows is
 * written as general trade knowledge, never as a Rock'n G promise. Facts this page wanted but intake did not supply
 * (camera charge, footage, excavation equipment, permits, restoration, prices) are in /content-confirmations.json.
 * SEO brief: no FAQ section/schema, no emergency / 24-hour wording, no trenchless claims.
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/service-cards.php';

$svc         = serviceBySlug('sewer-line-repair');
$pageType    = 'service';
$serviceSlug = $svc['slug'];
$pageTitle       = "Sewer Line Repair Plumber in San Angelo, TX | Rock'n G";
$pageDescription = "Sewer line plumber in San Angelo, TX • Camera inspection, main line cleaning & repair • Master Plumber M-44822, free estimates. Call (325) 650-7000.";
$canonicalUrl    = $siteUrl . '/services/sewer-line-repair/';
$currentPage     = 'services/sewer-line-repair';
$breadcrumbs     = [['name' => 'Services', 'url' => '/services/'], ['name' => $svc['name'], 'url' => '/services/sewer-line-repair/']];

$heroImage   = 'rockn-crew-excavation';
$heroAlt     = "Rock'n G Plumbing crew in red shirts beside a mini excavator and an open trench cut through pavement";
$heroPreload = ['name' => $heroImage];
$ogImage     = '/assets/images/' . $heroImage . '.jpg';

$telHref  = 'tel:' . $phoneE164;
$phoneFmt = formatPhone($phone);
$weekday  = hoursGroups($hours)[0];      // ['Mon–Fri', '8:00 AM – 6:00 PM']
$licenseExpires = date('F j, Y', strtotime($license['expires']));

// Main-line symptoms (general sewer knowledge — what a homeowner notices and why)
$slSigns = [
    ['icon' => 'droplets', 'title' => 'Several drains slow at once',
     'body'  => 'A tub, a sink and a toilet all draining slowly points past the fixtures to the one line they share.'],
    ['icon' => 'waves',    'title' => 'Gurgling from drains or toilets',
     'body'  => 'Air gets pushed back up through the water in a trap as wastewater struggles past a partial blockage further down the line.'],
    ['icon' => 'wind',     'title' => 'A sewage smell inside or in the yard',
     'body'  => 'A cracked or separated section of pipe can let sewer gas escape where it never should.'],
    ['icon' => 'layers',   'title' => 'Backups in the lowest drain',
     'body'  => 'Water comes up first at the lowest opening on the line, such as a shower, a floor drain or a ground-floor toilet, often right when the washer drains.'],
    ['icon' => 'trees',    'title' => 'A soggy or sunken patch over the line',
     'body'  => 'A wet spot or a dip in the yard along the sewer route can mean broken pipe underneath.'],
];

// What a sewer camera typically finds (general knowledge)
$slFindings = [
    ['code' => 'RT', 'title' => 'Roots',          'body' => 'Fine roots work into joints and small cracks, then grow into a mat that catches everything.'],
    ['code' => 'CR', 'title' => 'Cracks & breaks', 'body' => 'Shifting soil and age crack pipe walls; a break lets soil in and wastewater out.'],
    ['code' => 'OJ', 'title' => 'Offset joints',  'body' => 'Two pipe sections pushed out of line leave a lip that snags paper and waste.'],
    ['code' => 'SG', 'title' => 'Sags (bellies)', 'body' => 'A low spot holds standing water and sludge instead of letting it flow downhill.'],
    ['code' => 'GR', 'title' => 'Grease buildup', 'body' => 'Kitchen grease cools and coats the pipe, narrowing it a little more every month.'],
    ['code' => 'BK', 'title' => 'Blockages',      'body' => 'Wipes, debris or a foreign object lodged in the line, often at a joint or a sag.'],
];

// Service schema (provider = the Plumber node head.php prints on every page)
$serviceNode = generateServiceSchema($svc);
$serviceNode['@id']         = absUrl(serviceUrl($svc)) . '#service-' . $svc['slug'];
$serviceNode['serviceType'] = 'Sewer line repair and sewer camera inspection';
$serviceNode['description'] = "Sewer camera inspection, sewer cleaning and main line sewer repair in San Angelo, TX and nearby Tom Green County towns, by Rock'n G Plumbing under Texas Master Plumber License M-44822.";
$serviceNode['image']       = absUrl('/assets/images/' . $heroImage . '.jpg');
$serviceNode['hasOfferCatalog'] = [
    '@type' => 'OfferCatalog', 'name' => 'Sewer line services',
    'itemListElement' => array_map(fn($n) => ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => $n]],
        ['Sewer camera inspection', 'Sewer cleaning', 'Main line sewer repair']),
];
$serviceNode["@context"] = 'https://schema.org';   // explicit (generateServiceSchema sets it too)
$schemaMarkup = [$serviceNode];

ob_start();
?>
<style>
/* =====================================================================
   Sewer Line Repair & Camera Inspection — page-only CSS (sl- prefix)
   Tokens from framework.css; no raw colors, shadows or spacing.
   ===================================================================== */
@property --sl-scan {
  syntax: '<percentage>';
  inherits: false;
  initial-value: 0%;
}

/* ---------- Hero: layered photo (C1) ---------- */
.sl-hero {
  padding-block-end: var(--space-16);
}
.sl-hero .hero-bg {
  overflow: hidden;
}
.sl-hero .hero-bg picture,
.sl-hero .hero-bg img {
  width: 100%;
  height: 100%;
}
.sl-hero .hero-bg img {
  object-fit: cover;
  object-position: 55% 38%;
  transform-origin: 70% 30%;
  animation: sl-push 24s var(--ease) both;
}
.sl-hero::before {
  content: "";
  position: absolute;
  inset: 0;
  z-index: -1;
  pointer-events: none;
  background:
    radial-gradient(ellipse 50% 60% at 0% 100%, color-mix(in srgb, var(--color-accent) 24%, transparent), transparent 70%),
    linear-gradient(180deg, color-mix(in srgb, var(--color-primary-deep) 30%, transparent) 0%, transparent 35%, color-mix(in srgb, var(--color-primary-deep) 85%, transparent) 100%);
}
.sl-hero::after {
  content: "";
  position: absolute;
  inset: 0;
  z-index: -1;
  pointer-events: none;
  opacity: .22;
  mix-blend-mode: overlay;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='160' height='160'%3E%3Cfilter id='g'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.9' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23g)'/%3E%3C/svg%3E");
}
.sl-hero .breadcrumb {
  color: color-mix(in srgb, var(--color-surface) 72%, transparent);
  margin-block-end: var(--space-2);
}
.sl-hero .breadcrumb li {
  display: inline-flex;
  align-items: center;
}
.sl-hero .breadcrumb a:hover {
  color: var(--color-accent-bright);
}
.sl-hero .breadcrumb [aria-current="page"] {
  color: var(--color-surface);
}
.sl-hero .hero-title {
  max-width: 19ch;
  text-wrap: balance;
}
.sl-hero .hero-answer {
  max-width: 36rem;
}
.sl-hero .hero-chips li {
  backdrop-filter: blur(6px);
  -webkit-backdrop-filter: blur(6px);
}
.sl-hero .hero-form-open {
  background: var(--color-accent);
  color: var(--color-white);
}
.sl-hero .hero-form-card {
  border-block-start: 4px solid var(--color-accent);
}
.sl-hero .hero-form-card h2 {
  text-wrap: balance;
}
.sl-hero .hero-form .consent {
  margin-block: var(--space-1);
}
@keyframes sl-push {
  from { transform: scale(1.08); }
  to   { transform: scale(1); }
}

/* ---------- Article wrapper ---------- */
.sl-article > section h2 {
  text-wrap: balance;
}
.sl-article .answer-block {
  font-size: var(--fs-lead);
  line-height: 1.55;
}

/* ---------- Signs: pipe-run list (signs drop down to the main line) ---------- */
.sl-signs {
  background: var(--color-paper);
}
.sl-signs__grid {
  display: grid;
  grid-template-columns: minmax(0, 2fr) minmax(0, 3fr);
  gap: var(--space-12);
  align-items: start;
}
.sl-signs__head {
  position: sticky;
  inset-block-start: calc(var(--nav-height) + var(--space-6));
  display: grid;
  gap: var(--space-4);
}
.sl-signs__head h2 {
  text-wrap: balance;
}
.sl-run {
  list-style: none;
  margin: 0;
  padding: 0;
  position: relative;
  display: grid;
  gap: var(--space-4);
}
.sl-run::before {
  content: "";
  position: absolute;
  inset-block: var(--space-6) var(--space-16);
  inset-inline-start: 27px;
  width: 6px;
  border-radius: var(--radius-full);
  background: linear-gradient(180deg, var(--color-line) 0%, var(--color-secondary) 100%);
}
.sl-run li {
  position: relative;
  display: grid;
  grid-template-columns: 60px 1fr;
  gap: var(--space-1) var(--space-4);
  align-items: start;
  padding: var(--space-4) var(--space-5) var(--space-4) 0;
}
.sl-run__node {
  grid-row: span 2;
  width: 60px;
  height: 60px;
  display: grid;
  place-items: center;
  border-radius: var(--radius-full);
  background: var(--color-surface);
  border: 3px solid var(--color-secondary);
  color: var(--color-primary);
  position: relative;
  z-index: 1;
  transition: background-color var(--transition), color var(--transition), border-color var(--transition);
}
.sl-run li:hover .sl-run__node {
  background: var(--color-primary);
  color: var(--color-surface);
  border-color: var(--color-accent);
}
.sl-run h3 {
  font-size: var(--fs-h3);
  text-wrap: balance;
  padding-block-start: var(--space-2);
}
.sl-run p {
  margin: 0;
  color: var(--color-ink-2);
}
.sl-run__main {
  margin-block-start: var(--space-2);
  display: flex;
  align-items: center;
  gap: var(--space-4);
  padding: var(--space-4) var(--space-5);
  border-radius: var(--radius-lg);
  background: var(--color-primary);
  color: var(--color-surface);
  font-family: var(--font-accent);
  letter-spacing: .12em;
  text-transform: uppercase;
  font-size: var(--fs-eyebrow);
  box-shadow: var(--shadow);
}
.sl-run__main svg {
  color: var(--color-accent-bright);
}
.sl-onedrain {
  display: flex;
  gap: var(--space-3);
  align-items: flex-start;
  padding: var(--space-4) var(--space-5);
  border-radius: var(--radius);
  background: var(--color-surface);
  border: 1px dashed var(--color-secondary);
}
.sl-onedrain svg {
  flex: 0 0 auto;
  color: var(--color-accent-dark);
  margin-block-start: var(--space-1);
}
.sl-onedrain p {
  margin: 0;
  font-size: var(--fs-small);
  color: var(--color-ink);
}
.sl-onedrain a {
  color: var(--color-primary);
  font-weight: 600;
}

/* ---------- Signature: camera viewfinder ---------- */
.sl-camera {
  isolation: isolate;
}
.sl-camera .section-head {
  max-width: 64ch;
}
.sl-camera .answer-block {
  background: color-mix(in srgb, var(--color-surface) 6%, transparent);
  color: color-mix(in srgb, var(--color-surface) 88%, transparent);
}
.sl-viewfinder {
  position: relative;
  margin-block-start: var(--space-8);
  padding: var(--space-12) var(--space-8) var(--space-8);
  border-radius: var(--radius-xl);
  background:
    radial-gradient(ellipse 70% 80% at 50% 50%, color-mix(in srgb, var(--color-accent) 10%, transparent), transparent 75%),
    color-mix(in srgb, var(--color-black) 55%, var(--color-dark));
  border: 1px solid color-mix(in srgb, var(--color-accent) 30%, transparent);
  box-shadow: inset 0 0 0 1px color-mix(in srgb, var(--color-surface) 6%, transparent), var(--shadow-xl);
  overflow: hidden;
}
.sl-viewfinder::before,
.sl-viewfinder::after {
  content: "";
  position: absolute;
  width: 56px;
  height: 56px;
  border: 3px solid var(--color-accent-bright);
  pointer-events: none;
}
.sl-viewfinder::before {
  inset-block-start: var(--space-4);
  inset-inline-start: var(--space-4);
  border-inline-end: 0;
  border-block-end: 0;
}
.sl-viewfinder::after {
  inset-block-end: var(--space-4);
  inset-inline-end: var(--space-4);
  border-inline-start: 0;
  border-block-start: 0;
}
.sl-scanline {
  position: absolute;
  inset-inline: 0;
  inset-block-start: var(--sl-scan);
  height: 2px;
  background: linear-gradient(90deg, transparent, var(--color-accent-bright), transparent);
  opacity: .55;
  pointer-events: none;
  animation: sl-scan 5s linear infinite;
}
@keyframes sl-scan {
  from { --sl-scan: 0%; }
  to   { --sl-scan: 100%; }
}
.sl-hud {
  position: absolute;
  inset-block-start: var(--space-4);
  inset-inline: var(--space-16);
  display: flex;
  justify-content: space-between;
  gap: var(--space-4);
  font-family: var(--font-accent);
  font-size: var(--fs-eyebrow);
  letter-spacing: .16em;
  text-transform: uppercase;
  color: var(--color-accent-bright);
}
.sl-hud__rec {
  display: inline-flex;
  align-items: center;
  gap: var(--space-2);
}
.sl-hud__rec::before {
  content: "";
  width: 10px;
  height: 10px;
  border-radius: var(--radius-full);
  background: var(--color-danger);
  animation: sl-blink 1.4s steps(2, jump-none) infinite;
}
@keyframes sl-blink {
  50% { opacity: .15; }
}
.sl-findings {
  list-style: none;
  margin: 0;
  padding: 0;
  display: grid;
  grid-template-columns: repeat(3, minmax(0, 1fr));
  gap: var(--space-4);
}
.sl-finding {
  display: grid;
  grid-template-columns: auto 1fr;
  gap: var(--space-1) var(--space-3);
  padding: var(--space-5);
  border-radius: var(--radius);
  background: color-mix(in srgb, var(--color-surface) 5%, transparent);
  border: 1px solid color-mix(in srgb, var(--color-surface) 12%, transparent);
  transition: border-color var(--transition), background-color var(--transition);
}
.sl-finding:hover {
  border-color: var(--color-accent-bright);
  background: color-mix(in srgb, var(--color-accent) 10%, transparent);
}
.sl-finding b {
  grid-row: span 2;
  display: grid;
  place-items: center;
  width: 44px;
  height: 44px;
  border-radius: var(--radius-sm);
  font-family: var(--font-accent);
  font-size: 1.05rem;
  letter-spacing: .08em;
  color: var(--color-ink);
  background: var(--color-accent-bright);
}
.sl-finding h3 {
  font-size: 1.05rem;
  color: var(--color-surface);
}
.sl-finding p {
  margin: 0;
  font-size: var(--fs-small);
  color: color-mix(in srgb, var(--color-surface) 76%, transparent);
}
.sl-camera__why {
  display: grid;
  grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
  gap: var(--space-8);
  margin-block-start: var(--space-10);
}
.sl-camera__why p {
  margin: 0;
  max-width: var(--measure);
}

/* ---------- Scope + process (horizontal rail) ---------- */
.sl-scope {
  background: var(--color-surface);
}
.sl-scope__grid {
  display: grid;
  grid-template-columns: minmax(0, 3fr) minmax(0, 2fr);
  gap: var(--space-12);
  align-items: start;
}
.sl-scope__copy {
  display: grid;
  gap: var(--space-4);
}
.sl-scope__copy > p {
  margin: 0;
  color: var(--color-ink-2);
  max-width: var(--measure);
}
.sl-trio {
  display: grid;
  grid-template-columns: repeat(3, minmax(0, 1fr));
  gap: var(--space-3);
}
.sl-trio div {
  display: grid;
  gap: var(--space-2);
  align-content: start;
  padding: var(--space-4);
  border-radius: var(--radius);
  border-block-start: 4px solid var(--color-secondary);
}
.sl-trio div:nth-child(2) {
  border-block-start-color: var(--color-accent);
}
.sl-trio div:nth-child(3) {
  border-block-start-color: var(--color-primary);
}
.sl-trio h3 {
  font-size: 1.05rem;
}
.sl-trio p {
  margin: 0;
  font-size: var(--fs-small);
  color: var(--color-ink-2);
}
.sl-shot {
  margin: 0;
  position: relative;
}
.sl-shot .img-reveal {
  aspect-ratio: 4 / 3.6;
  clip-path: polygon(0 0, 100% 0, 100% 100%, 12% 100%, 0 88%);
}
.sl-shot .img-reveal picture,
.sl-shot .img-reveal img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}
.sl-shot figcaption {
  margin-block-start: var(--space-3);
  font-size: var(--fs-small);
  color: var(--color-muted);
}
.sl-rail {
  list-style: none;
  margin: var(--space-12) 0 0;
  padding: 0;
  display: grid;
  grid-template-columns: repeat(5, minmax(0, 1fr));
  gap: var(--space-4);
  counter-reset: sl-step;
  position: relative;
}
.sl-rail::before {
  content: "";
  position: absolute;
  inset-inline: var(--space-6);
  inset-block-start: 21px;
  height: 3px;
  background: repeating-linear-gradient(90deg, var(--color-accent) 0 18px, transparent 18px 28px);
  opacity: .7;
}
.sl-rail li {
  counter-increment: sl-step;
  position: relative;
  display: grid;
  gap: var(--space-2);
  align-content: start;
}
.sl-rail li::before {
  content: counter(sl-step, decimal-leading-zero);
  width: 44px;
  height: 44px;
  display: grid;
  place-items: center;
  border-radius: var(--radius-sm);
  font-family: var(--font-accent);
  font-size: 1.15rem;
  color: var(--color-surface);
  background: var(--color-primary);
  box-shadow: 0 0 0 6px var(--color-surface);
  position: relative;
  z-index: 1;
}
.sl-rail b {
  font-family: var(--font-heading);
  color: var(--color-ink);
}
.sl-rail span {
  font-size: var(--fs-small);
  color: var(--color-ink-2);
}

/* ---------- Comparison table (three repair paths) ---------- */
.sl-compare {
  background: var(--color-paper-2);
}
.sl-compare__wrap {
  container-type: inline-size;
  container-name: sltable;
  margin-block-start: var(--space-8);
  overflow-x: auto;
}
.sl-table {
  width: 100%;
  border-collapse: collapse;
  font-size: var(--fs-small);
  background: var(--color-surface);
  border-radius: var(--radius-lg);
  overflow: hidden;
  box-shadow: var(--shadow);
}
.sl-table caption {
  caption-side: bottom;
  text-align: start;
  padding-block-start: var(--space-3);
  color: var(--color-muted);
}
.sl-table th,
.sl-table td {
  padding: var(--space-4) var(--space-5);
  text-align: start;
  vertical-align: top;
}
.sl-table thead th {
  font-family: var(--font-heading);
  font-size: 1rem;
  color: var(--color-ink);
  background: var(--color-surface);
  border-block-end: 3px solid var(--color-line);
}
.sl-table thead th:nth-child(2) { border-block-end-color: var(--color-secondary); }
.sl-table thead th:nth-child(3) { border-block-end-color: var(--color-accent); }
.sl-table thead th:nth-child(4) { border-block-end-color: var(--color-primary); }
.sl-table tbody th {
  width: 20%;
  font-family: var(--font-accent);
  font-size: var(--fs-eyebrow);
  letter-spacing: .12em;
  text-transform: uppercase;
  color: var(--color-muted);
}
.sl-table tbody tr:nth-child(odd) {
  background: color-mix(in srgb, var(--color-paper) 60%, var(--color-surface));
}
.sl-table tbody td {
  color: var(--color-ink-2);
  border-inline-start: 1px solid var(--color-line);
}
.sl-table tbody tr:hover td {
  color: var(--color-ink);
}
@container sltable (max-width: 680px) {
  .sl-table thead {
    position: absolute;
    width: 1px;
    height: 1px;
    overflow: hidden;
    clip-path: inset(50%);
  }
  .sl-table,
  .sl-table tbody,
  .sl-table tr,
  .sl-table th,
  .sl-table td {
    display: block;
    width: auto;
  }
  .sl-table tbody td {
    border-inline-start: 0;
    padding-block: var(--space-2);
  }
  .sl-table tbody tr {
    padding-block-end: var(--space-3);
  }
  .sl-table tbody td::before {
    content: attr(data-label) ": ";
    font-weight: 700;
    color: var(--color-ink);
  }
}

/* ---------- Expert positioning: magazine spread over the trench photo ---------- */
.sl-expert {
  background: var(--color-surface);
}
.sl-spread {
  display: grid;
  grid-template-columns: minmax(0, 1.1fr) minmax(0, 1fr);
  align-items: stretch;
  border-radius: var(--radius-xl);
  overflow: hidden;
  box-shadow: var(--shadow-lg);
  background: var(--color-paper);
}
.sl-spread__photo {
  position: relative;
  margin: 0;
  min-height: 420px;
}
.sl-spread__photo picture,
.sl-spread__photo img {
  position: absolute;
  inset: 0;
  width: 100%;
  height: 100%;
  object-fit: cover;
}
.sl-spread__photo figcaption {
  position: absolute;
  inset-inline: 0;
  inset-block-end: 0;
  padding: var(--space-12) var(--space-5) var(--space-4);
  font-size: var(--fs-small);
  color: var(--color-surface);
  background: linear-gradient(0deg, color-mix(in srgb, var(--color-black) 78%, transparent), transparent);
}
.sl-spread__copy {
  display: grid;
  align-content: start;
  gap: var(--space-4);
  padding: var(--space-10) var(--space-8);
}
.sl-spread__copy h2 {
  text-wrap: balance;
}
.sl-licence {
  display: inline-flex;
  align-items: center;
  gap: var(--space-3);
  justify-self: start;
  padding: var(--space-2) var(--space-4);
  border-radius: var(--radius-full);
  background: var(--color-primary);
  color: var(--color-surface);
  font-size: var(--fs-small);
}
.sl-licence svg {
  color: var(--color-accent-bright);
}
.sl-licence b {
  font-family: var(--font-accent);
  letter-spacing: .06em;
  font-size: 1.1rem;
}
.sl-why {
  list-style: none;
  margin: 0;
  padding: 0;
  display: grid;
  gap: var(--space-3);
}
.sl-why li {
  padding-inline-start: var(--space-4);
  border-inline-start: 3px solid var(--color-accent);
}
.sl-why b {
  display: block;
  font-family: var(--font-heading);
  color: var(--color-ink);
}
.sl-why span {
  font-size: var(--fs-small);
  color: var(--color-ink-2);
}
.sl-why a {
  color: var(--color-primary);
  font-weight: 600;
}

/* ---------- Proof (light band, parallelogram edge) ---------- */
.sl-proof {
  background: var(--color-paper-2);
}
.sl-proof__grid {
  display: grid;
  grid-template-columns: repeat(2, minmax(0, 1fr));
  gap: var(--space-6);
  margin-block-start: var(--space-8);
}
.sl-proof__card {
  display: grid;
  align-content: start;
  gap: var(--space-3);
  padding: var(--space-6);
  border-radius: var(--radius-lg);
  background: var(--color-surface);
  border: 1px solid var(--color-line);
  box-shadow: var(--shadow-sm);
}
.sl-proof__card p {
  margin: 0;
  color: var(--color-ink-2);
}
.sl-proof__card .actions {
  display: flex;
  flex-wrap: wrap;
  gap: var(--space-3);
}
.sl-proof__card dl {
  margin: 0;
  display: grid;
  grid-template-columns: auto 1fr;
  gap: var(--space-2) var(--space-4);
  font-size: var(--fs-small);
}
.sl-proof__card dt {
  color: var(--color-muted);
}
.sl-proof__card dd {
  margin: 0;
  font-weight: 600;
  color: var(--color-ink);
}
.sl-proof__reviews {
  margin-block-start: var(--space-8);
}
.sl-proof__reviews .p1gr-text {
  color: var(--color-ink-2);
}

/* ---------- Related services ---------- */
.sl-related {
  background: var(--color-paper);
}
.sl-related .services-grid {
  grid-template-columns: repeat(3, minmax(0, 1fr));
}
.sl-related .service-card-with-image h3 {
  text-wrap: balance;
}

/* ---------- Final CTA ---------- */
.sl-cta h2 {
  text-wrap: balance;
  max-width: 22ch;
}
.sl-cta .link-call {
  color: color-mix(in srgb, var(--color-surface) 88%, transparent);
}
.sl-cta .link-call svg {
  color: var(--color-accent-bright);
}
.sl-cta__ring {
  inset-inline-end: -120px;
  inset-block-start: -100px;
}
.sl-updated {
  margin-block-start: var(--space-6);
  font-size: var(--fs-small);
}

/* ---------- Responsive ---------- */
@media (max-width: 1000px) {
  .sl-findings {
    grid-template-columns: repeat(2, minmax(0, 1fr));
  }
  .sl-rail {
    grid-template-columns: 1fr;
    gap: var(--space-5);
  }
  .sl-rail::before {
    inset-inline: auto;
    inset-inline-start: 20px;
    inset-block: 0;
    width: 3px;
    height: auto;
    background: repeating-linear-gradient(180deg, var(--color-accent) 0 18px, transparent 18px 28px);
  }
  .sl-rail li {
    grid-template-columns: 44px 1fr;
    column-gap: var(--space-4);
  }
  .sl-rail li::before {
    grid-row: span 2;
  }
  .sl-related .services-grid {
    grid-template-columns: repeat(2, minmax(0, 1fr));
  }
}
@media (max-width: 900px) {
  .sl-signs__grid,
  .sl-scope__grid,
  .sl-spread,
  .sl-proof__grid,
  .sl-camera__why {
    grid-template-columns: 1fr;
  }
  .sl-signs__head {
    position: static;
  }
  .sl-spread__photo {
    min-height: 300px;
  }
  .sl-spread__copy {
    padding: var(--space-8) var(--space-5);
  }
}
@media (max-width: 640px) {
  .sl-findings,
  .sl-trio,
  .sl-related .services-grid {
    grid-template-columns: 1fr;
  }
  .sl-viewfinder {
    padding: var(--space-12) var(--space-4) var(--space-6);
  }
  .sl-hud {
    inset-inline: var(--space-12);
  }
  .sl-run li {
    grid-template-columns: 48px 1fr;
    padding-inline-end: 0;
  }
  .sl-run__node {
    width: 48px;
    height: 48px;
  }
  .sl-run::before {
    inset-inline-start: 21px;
  }
}
@media (prefers-reduced-motion: reduce) {
  .sl-hero .hero-bg img,
  .sl-scanline,
  .sl-hud__rec::before {
    animation: none;
  }
  .sl-run__node,
  .sl-finding {
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
<section class="hero hero--photo hero--interior sl-hero" aria-labelledby="sl-h1">
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
            <li><span aria-current="page">Sewer Line Repair</span></li>
          </ol>
        </nav>
        <span class="eyebrow">Sewer Lines · <?php echo h($address['city'] . ', ' . $address['state']); ?></span>
        <h1 class="hero-title" id="sl-h1">Sewer Line Repair &amp; Camera Inspection from a San Angelo Plumber</h1>
        <p class="hero-answer">Rock'n G Plumbing runs a camera down your sewer line to find the problem, then cleans, repairs or replaces the main line for homes and businesses in San Angelo, Christoval and nearby towns. The work runs under master plumber Eli Gomez's Texas license M-44822, and the estimate is free.</p>
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
        <h2 id="hero-form-title">Need your sewer line checked?</h2>
        <p class="hero-form-tagline">Tell us which drains are acting up. No obligation.</p>
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

<article class="sl-article" aria-labelledby="sl-h1">

  <!-- ============ PROBLEM: MAIN-LINE SIGNS ============ -->
  <section class="section sl-signs" aria-labelledby="sl-signs-title">
    <div class="container">
      <div class="sl-signs__grid">
        <div class="sl-signs__head reveal-left">
          <span class="eyebrow">Know the signs</span>
          <h2 id="sl-signs-title">How do you know the problem is your main sewer line?</h2>
          <p class="answer-block">When several drains slow down at once, gurgle, or back up into the lowest fixture in the house, the problem is usually the main sewer line, not a single drain. One slow sink points to that sink's drain; trouble everywhere points to the line all of them share.</p>
          <div class="sl-onedrain" role="note">
            <?php echo icon('info', 20); ?>
            <p><strong>Only one slow drain?</strong> That is usually a clog in that fixture's own line. See <a href="/services/drain-cleaning/">drain cleaning and clog removal</a> instead.</p>
          </div>
        </div>
        <ol class="sl-run">
          <?php foreach ($slSigns as $i => $sign): ?>
          <li class="reveal-right reveal-delay-<?php echo ($i % 3) + 1; ?>">
            <span class="sl-run__node"><?php echo icon($sign['icon'], 24); ?></span>
            <h3><?php echo h($sign['title']); ?></h3>
            <p><?php echo h($sign['body']); ?></p>
          </li>
          <?php endforeach; ?>
          <li class="sl-run__main" aria-hidden="true"><?php echo icon('route', 20); ?>All of them drain into one main line</li>
        </ol>
      </div>
    </div>
  </section>

  <!-- ============ SIGNATURE: CAMERA INSPECTION ============ -->
  <section class="section on-dark edge-parallelogram-top sl-camera" aria-labelledby="sl-camera-title">
    <span class="grain-layer" aria-hidden="true"></span>
    <div class="container">
      <div class="section-head reveal-up">
        <span class="eyebrow">Camera inspection</span>
        <h2 id="sl-camera-title">What does a sewer camera inspection show you?</h2>
        <p class="answer-block">A sewer camera inspection shows what is actually inside your main line: roots, cracks, offset joints, sags that hold water, and grease or debris buildup. It also shows where along the line the trouble sits, so the repair can target that spot instead of guessing.</p>
      </div>
      <div class="sl-viewfinder reveal-scale">
        <span class="sl-scanline" aria-hidden="true"></span>
        <div class="sl-hud" aria-hidden="true"><span class="sl-hud__rec">Rec</span><span>Main line</span></div>
        <ul class="sl-findings">
          <?php foreach ($slFindings as $finding): ?>
          <li class="sl-finding">
            <b aria-hidden="true"><?php echo h($finding['code']); ?></b>
            <h3><?php echo h($finding['title']); ?></h3>
            <p><?php echo h($finding['body']); ?></p>
          </li>
          <?php endforeach; ?>
        </ul>
      </div>
      <div class="sl-camera__why">
        <p class="reveal-left">Digging up a yard, a driveway or a parking lot on a guess is the expensive way to fix a sewer line. Looking first tells you whether the line needs a cleaning, a repair at one spot, or a longer replacement, and camera footage lets you see the problem instead of taking it on faith.</p>
        <p class="reveal-right">Older sewer lines around San Angelo can be clay, cast iron or plastic, and each fails in its own way: clay joints let roots in, cast iron scales and cracks, and plastic can sag where the soil under it settles. The camera shows which of those you are dealing with.</p>
      </div>
    </div>
  </section>

  <!-- ============ SERVICE BREAKDOWN + PROCESS ============ -->
  <section class="section sl-scope" aria-labelledby="sl-scope-title">
    <div class="container">
      <div class="sl-scope__grid">
        <div class="sl-scope__copy reveal-left">
          <span class="eyebrow">What's included</span>
          <h2 id="sl-scope-title">What does sewer line repair from Rock'n G include?</h2>
          <p class="answer-block">Rock'n G Plumbing's sewer work covers camera inspection, sewer cleaning and main line repair. The camera finds the problem, cleaning clears blockages like grease and roots, and repair replaces a section of pipe that is broken, sagging or separated. Your estimate is based on what the camera shows.</p>
          <div class="sl-trio">
            <div class="card-tint-1"><h3>Camera inspection</h3><p>A look inside the main line to find what is wrong and where.</p></div>
            <div class="card-tint-2"><h3>Sewer cleaning</h3><p>Clearing grease, roots and debris from pipe that is still sound.</p></div>
            <div class="card-tint-3"><h3>Main line repair</h3><p>Digging to the damaged section and replacing the bad pipe.</p></div>
          </div>
        </div>
        <figure class="sl-shot reveal-right">
          <div class="img-reveal">
            <?php echo pictureTag('rockn-pipe-through-slab', 'White PVC drain pipe running down into a trench cut through the floor of a home during a Rock\'n G Plumbing job', '(max-width: 900px) 90vw, 440px'); ?>
          </div>
          <figcaption>New white PVC drain pipe run down into a trench opened through a home's floor on a Rock'n G job. Drain lines like this one carry the house's wastewater out to the main sewer line.</figcaption>
        </figure>
      </div>

      <ol class="sl-rail">
        <li class="reveal-up reveal-delay-1"><b>Call or send the form</b><span><?php echo h($phoneFmt); ?>, <?php echo h($weekday[0]); ?> <?php echo h($weekday[1]); ?>. Say which drains are acting up and when it started.</span></li>
        <li class="reveal-up reveal-delay-2"><b>Camera inspection</b><span>The line is inspected with a camera to see what is wrong and where along the run.</span></li>
        <li class="reveal-up reveal-delay-3"><b>Estimate at no charge</b><span>Cleaning, a section repair or a longer replacement, priced from what the camera found.</span></li>
        <li class="reveal-up reveal-delay-1"><b>You choose</b><span>Go ahead with the fix that makes sense. Mention military service and ask about the discount.</span></li>
        <li class="reveal-up reveal-delay-2"><b>The work</b><span>Cleaning or repair is done under <?php echo h($ownerName); ?>'s Texas Master Plumber license.</span></li>
      </ol>
    </div>
  </section>

  <!-- ============ COMPARISON ============ -->
  <section class="section slant-top sl-compare" aria-labelledby="sl-compare-title">
    <div class="container">
      <div class="section-head reveal-up">
        <span class="eyebrow">Compare the fixes</span>
        <h2 id="sl-compare-title">Clean, repair or replace: which sewer fix does your line need?</h2>
        <p class="answer-block">The right sewer fix depends on what the camera finds. A blockage in sound pipe needs cleaning. One broken or sagging section needs that section dug up and repaired. A line failing along much of its length is usually better replaced as a run.</p>
      </div>
      <div class="sl-compare__wrap reveal-scale">
        <table class="sl-table">
          <caption>General guidance. The camera inspection decides which path fits your line.</caption>
          <thead>
            <tr><th scope="col"><span class="sr-only">Question</span></th><th scope="col">Sewer cleaning</th><th scope="col">Section repair</th><th scope="col">Line replacement</th></tr>
          </thead>
          <tbody>
            <tr><th scope="row">What it fixes</th><td data-label="Cleaning">Grease, debris and root blockages in pipe that is still intact</td><td data-label="Section repair">One cracked, broken, separated or sagging section</td><td data-label="Replacement">Pipe that is cracked, collapsed or root-damaged in many places</td></tr>
            <tr><th scope="row">The camera shows</th><td data-label="Cleaning">Buildup or roots, with sound joints and pipe walls</td><td data-label="Section repair">Damage confined to one spot</td><td data-label="Replacement">Damage repeated along the run</td></tr>
            <tr><th scope="row">Digging</th><td data-label="Cleaning">None: the line is cleared from a cleanout</td><td data-label="Section repair">Excavation at the damaged section</td><td data-label="Replacement">Excavation along the length being replaced</td></tr>
            <tr><th scope="row">Usual sign it fits</th><td data-label="Cleaning">A first backup, or a line not cleaned in years</td><td data-label="Section repair">Backups that keep returning to the same spot</td><td data-label="Replacement">Repeat backups after cleaning, or several bad sections</td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </section>

  <!-- ============ EXPERT POSITIONING ============ -->
  <section class="section sl-expert" aria-labelledby="sl-expert-title">
    <div class="container-wide">
      <div class="sl-spread reveal-up">
        <figure class="sl-spread__photo">
          <?php echo pictureTag('rockn-trench-dig', "Rock'n G plumber in a red company shirt standing in an open trench dug through pavement", '(max-width: 900px) calc(100vw - 2rem), 640px'); ?>
          <figcaption>A Rock'n G plumber down in the trench on the same dig shown at the top of this page.</figcaption>
        </figure>
        <div class="sl-spread__copy">
          <span class="eyebrow">Who does the work</span>
          <h2 id="sl-expert-title">Why does a master plumber matter on a sewer repair?</h2>
          <p class="answer-block">A sewer repair changes the line that carries every drain in the building, so slope, joints and connections have to be right or the backups come back. Rock'n G Plumbing does sewer work under Eli Gomez's Texas Master Plumber License M-44822, issued by the Texas State Board of Plumbing Examiners.</p>
          <span class="sl-licence"><?php echo icon('badge-check', 18); ?><b><?php echo h($license['number']); ?></b>current through <?php echo h($licenseExpires); ?></span>
          <ul class="sl-why">
            <li><b>A license you can look up</b><span>Search M-44822 on the Texas State Board of Plumbing Examiners lookup to see <?php echo h($license['holder']); ?> listed as the <?php echo h($license['role']); ?>.</span></li>
            <li><b>Homes, businesses and industrial sites</b><span>Plumbing has been the Gomez family's trade for three generations, with 20+ years of experience behind Rock'n G. The dig in these photos runs through pavement beside commercial buildings.</span></li>
            <li><b>Not the sewer after all?</b><span>If the camera rules out the main line, Rock'n G also handles <a href="/services/leak-detection-repair/">leak detection and slab leak repair</a> for water under a floor that turns out to be a supply line.</span></li>
          </ul>
        </div>
      </div>
    </div>
  </section>

  <!-- ============ PROOF ============ -->
  <section class="section edge-curve-top sl-proof" aria-labelledby="sl-proof-title">
    <div class="container">
      <div class="section-head reveal-up">
        <span class="eyebrow">Check us out</span>
        <h2 id="sl-proof-title">How can you check out Rock'n G Plumbing before a sewer job?</h2>
        <p class="answer-block">Check two things before any sewer work: Rock'n G Plumbing's Google reviews, and its license, M-44822, on the Texas State Board of Plumbing Examiners lookup. Rock'n G is rated <?php echo h(number_format((float) $gbp['rating'], 1)); ?> out of 5 from <?php echo (int) $gbp['reviews_count']; ?> Google reviews, and you can read each one on Google.</p>
      </div>
      <div class="sl-proof__grid">
        <div class="sl-proof__card reveal-left">
          <h3>Google reviews</h3>
          <p>Customers write the reviews on Rock'n G Plumbing's Google Business Profile. If Rock'n G cleared or repaired your sewer line, a short review helps your neighbors in Tom Green County pick a plumber.</p>
          <div class="actions">
            <a class="btn btn-primary" href="<?php echo h($gbp['profile_url']); ?>" target="_blank" rel="noopener">Read reviews on Google</a>
            <a class="btn btn-secondary" href="<?php echo h($gbp['review_url']); ?>" target="_blank" rel="noopener">Leave a review</a>
          </div>
        </div>
        <div class="sl-proof__card reveal-right">
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
      $slReviews = p1_google_reviews($slug, ['limit' => 3, 'heading' => '', 'summary' => false]);
      if ($slReviews !== ''): ?>
      <div class="sl-proof__reviews"><?php echo $slReviews; ?></div>
      <?php endif; ?>
    </div>
  </section>

  <!-- ============ RELATED SERVICES (required services-grid) ============ -->
  <section class="section sl-related" aria-label="Related plumbing services">
    <div class="container">
      <div class="section-title reveal-up">
        <span class="eyebrow-label">What We Do</span>
        <h2>Which other <span class="text-accent">plumbing services</span> connect to your sewer line?</h2>
        <p class="hero-answer">Sewer trouble rarely stands alone. A single slow fixture is a drain cleaning job, a remodel or addition means new drain lines tying into the sewer, and water under a slab can be a supply leak rather than a drain. Rock'n G Plumbing handles drain cleaning, leak detection and new construction and remodel plumbing.</p>
        <span class="section-subtitle">Drains · Leaks · Remodels</span>
        <p class="prose">See every <a href="/services/">plumbing service Rock'n G offers in San Angelo</a>.</p>
      </div>
      <div class="services-grid">
        <?php echo renderServiceCards(['drain-cleaning', 'leak-detection-repair', 'new-construction-remodel-plumbing'], relatedServiceSizes(640)); ?>
      </div>
    </div>
  </section>

  <!-- ============ FINAL CTA ============ -->
  <section class="closing-cta texture-grain edge-torn-top sl-cta" aria-labelledby="sl-cta-title">
    <span class="grain-layer" aria-hidden="true"></span>
    <span class="floating-ring sl-cta__ring" aria-hidden="true"></span>
    <div class="container">
      <div class="reveal-left">
        <span class="eyebrow">Find it before you dig</span>
        <h2 id="sl-cta-title">Ready for a free sewer line estimate in San Angelo?</h2>
        <p>Call Rock'n G Plumbing at <?php echo h($phoneFmt); ?>, <?php echo h($weekday[0]); ?> <?php echo h($weekday[1]); ?>, or send the form and the office will follow up during business hours. If drains are already backing up, hold off on laundry, showers and flushing until the line is cleared.</p>
        <p class="sl-updated">Last updated: <?php echo date('F Y'); ?></p>
      </div>
      <div class="actions reveal-right">
        <a class="btn btn-accent btn-lg" href="/contact/" data-open-estimate>Get a Free Sewer Line Estimate</a>
        <a class="link-call" href="<?php echo h($telHref); ?>"><?php echo icon('phone', 18); ?>Call <?php echo h($phoneFmt); ?></a>
      </div>
    </div>
  </section>

</article>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>
