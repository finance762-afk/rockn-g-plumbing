<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/functions.php';
?>
<?php
/**
 * index.php — Rock'n G Plumbing homepage (Phase 3).
 * Archetype: bold-industrial → photo hero. Cluster (V2.6): "plumber san angelo tx".
 * Every fact on this page traces to build-plan.json / includes/config.php. Facts the page wanted but intake
 * did not supply are listed in /content-confirmations.json instead of being written into the copy.
 *
 * SEO brief (Oct 18 2026) overrides the generic Phase 3 prompt here: FAQs are visible content only — no FAQPage
 * schema on this site — and nothing mentions emergency/24-hour service or backflow testing.
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/blog-data.php';   // $blogPosts registry → "From the Blog" card

$pageTitle       = defaultPageTitle();   // "Plumber in San Angelo, TX | Rock'n G Plumbing" (SEO brief)
$pageDescription = "Plumber in San Angelo, TX • Texas Master Plumber M-44822 • 3 generations, 20+ years • Free estimates, military discounts. Call (325) 650-7000.";
$canonicalUrl    = $siteUrl . '/';
$ogImage         = defaultOgImage();
$currentPage     = 'home';

// Hero photo (bold-industrial full-bleed). Only the AVIF set is preloaded; it is the one fetchpriority="high" element.
$heroImage        = 'rockn-hero-crew-excavation';
$heroAlt          = "Rock'n G Plumbing crew in red company shirts digging out a buried line beside a mini excavator";
$heroPreloadImage = '/assets/images/' . $heroImage . '.jpg';
$heroPreload      = ['srcset' => imageVariantSrcset($heroImage, 'avif'), 'sizes' => '100vw'];

$telHref  = 'tel:' . $phoneE164;
$phoneFmt = formatPhone($phone);
$weekday  = hoursGroups($hours)[0];      // ['Mon–Fri', '8:00 AM – 6:00 PM']

// Services grid — order leads with the brief's secondary keywords (water heaters, drains). Photos are the client's own
// (assets/images/rockn-*); services with no matching photo reuse the closest job photo, and the alt says what it shows.
$homeServiceCards = [
    'water-heaters' => [
        'photo' => 'rockn-water-treatment-garage', 'icon' => 'flame',
        'alt'   => 'Water treatment tank and blue filter housings plumbed into a garage utility corner',
        'desc'  => 'Water heater repair, full replacement, and tankless water heater installation.',
        'bullets' => ['Repairs on failing tank heaters', 'Worn-out tanks replaced', 'Tankless units installed'],
    ],
    'drain-cleaning' => [
        'photo' => 'rockn-under-floor-drain-lines', 'icon' => 'waves',
        'alt'   => 'White PVC drain lines run down through the floor framing of a home under repair',
        'desc'  => 'Clearing kitchen, bathroom and laundry drains that run slow or back up.',
        'bullets' => ['Kitchen and bathroom drains', 'Slow and repeat clogs', 'Backups cleared, not masked'],
    ],
    'sewer-line-repair' => [
        'photo' => 'rockn-trench-dig', 'icon' => 'search',
        'alt'   => "Rock'n G plumber standing in an excavated trench to reach a buried line",
        'desc'  => 'Camera inspection to find the problem, then sewer line repair or cleaning.',
        'bullets' => ['See the problem on camera', 'Main line repair and cleaning', 'Dig-and-repair when needed'],
    ],
    'gas-line-services' => [
        'photo' => 'rockn-gas-line-generator', 'icon' => 'wrench',
        'alt'   => 'Gas line run along the brick wall of a home to a standby generator',
        'desc'  => 'New gas lines and repairs for appliances, generators and household equipment.',
        'bullets' => ['New lines for appliances', 'Runs to standby generators', 'Repairs on existing gas piping'],
    ],
    'new-construction-remodel-plumbing' => [
        'photo' => 'rockn-kitchen-remodel', 'icon' => 'hammer',
        'alt'   => 'Remodeled kitchen with a farmhouse sink in the island and a gas cooktop',
        'desc'  => 'Plumbing for new builds and remodels, residential and commercial.',
        'bullets' => ['Plumbing for new homes', 'Kitchen and bath remodels', 'Commercial build-outs'],
    ],
    'water-filtration-softeners' => [
        'photo' => 'rockn-water-softener-filtration', 'icon' => 'droplets',
        'alt'   => 'Water softener, brine tank and whole-house filter housings installed in a garage',
        'desc'  => "Softeners, reverse osmosis and whole-house filters for San Angelo's hard water.",
        'bullets' => ['Water softener installation', 'Reverse osmosis drinking water', 'Whole-house filtration'],
    ],
    'leak-detection-repair' => [
        'photo' => 'rockn-pipe-through-slab', 'icon' => 'layers',
        'alt'   => 'Drain pipe exposed in a trench cut through a concrete slab',
        'desc'  => 'Finding hidden and under-slab leaks, then repairing or replacing the water line.',
        'bullets' => ['Hidden leaks tracked down', 'Slab leak repair', 'Water line repair or replacement'],
    ],
    'irrigation-sprinkler-repair' => [
        'photo' => 'rockn-crew-excavation', 'icon' => 'sprout',
        'alt'   => "Rock'n G crew with a mini excavator and hand tools working on a buried line",
        'desc'  => "Repairs to lawn sprinkler and irrigation systems that leak or won't run.",
        'bullets' => ['Broken heads and leaking lines', 'Irrigation system repairs', 'Lawn sprinkler troubleshooting'],
    ],
];
$homeServices = [];
foreach ($homeServiceCards as $cardSlug => $card) {
    foreach ($services as $svc) {
        if ($svc['slug'] === $cardSlug) { $homeServices[] = $svc + $card; break; }
    }
}

// Proof strip — verifiable facts only (build-plan content.usps, config.php). No counts, ratings or percentages invented.
$homeProof = [
    ['num' => '3 <span>Generations</span>',               'label' => 'Plumbing is the family trade'],
    ['num' => h($yearsInBusiness) . '+ <span>Years</span>', 'label' => 'Of plumbing experience'],
    ['num' => h($companyCounty) . ' <span>County</span>', 'label' => 'San Angelo, Christoval and nearby towns'],
    ['num' => 'Mon–Fri <span>8–6</span>',                 'label' => 'Office hours, closed weekends'],
];

// Symptom board (signature section) — general plumbing knowledge mapped to this client's services.
$homeSigns = [
    ['sign' => 'Hot water runs out sooner than it used to',                     'cause' => 'Sediment built up in the tank, or a heating part wearing out',            'slug' => 'water-heaters'],
    ['sign' => 'White crust on faucets, spots on dishes, stiff laundry',       'cause' => 'Hard water minerals coming in with the supply',                             'slug' => 'water-filtration-softeners'],
    ['sign' => 'One sink or tub draining slowly',                               'cause' => "A clog in that fixture's drain line",                                       'slug' => 'drain-cleaning'],
    ['sign' => 'Several drains gurgling or slow at once, a sewage smell',       'cause' => 'A blockage or damage in the main sewer line',                               'slug' => 'sewer-line-repair'],
    ['sign' => 'Water bill jumps, a warm spot on the floor, water running with everything off', 'cause' => 'A hidden leak, often in a line under the slab',              'slug' => 'leak-detection-repair'],
    ['sign' => "Soggy patches in the yard, sprinkler heads that won't pop up", 'cause' => 'A broken head or a leaking irrigation line',                                'slug' => 'irrigation-sprinkler-repair'],
];
$svcBySlug = [];
foreach ($services as $svc) $svcBySlug[$svc['slug']] = $svc;

// FAQs — visible content only (SEO brief: no FAQ schema). Every answer is an intake fact.
$homeFaqs = [
    ['q' => "Does Rock'n G Plumbing charge for estimates?",
     'a' => "No. Rock'n G Plumbing gives free estimates on plumbing work in San Angelo and Christoval. Call " . $phoneFmt . ", Monday through Friday from 8 AM to 6 PM, or send the estimate form with a short description of the problem."],
    ['q' => 'Is there a military discount?',
     'a' => "Yes. Rock'n G Plumbing offers a military discount. Mention your service when you call or add it to the message on the estimate form, and ask how the discount applies to your job."],
    ['q' => 'Do you install tankless water heaters?',
     'a' => "Yes. Rock'n G Plumbing repairs and replaces tank water heaters and installs tankless units. When a heater is failing, the estimate covers whether a repair still makes sense or whether it is time to replace it."],
    ['q' => "Can you help with San Angelo's hard water?",
     'a' => "Yes. Rock'n G Plumbing installs water softeners, reverse osmosis drinking-water systems and whole-house filters for homes dealing with San Angelo's hard water. White scale on fixtures and spotty dishes are the usual first signs."],
    ['q' => 'Can you find a leak under my slab?',
     'a' => "Yes. Rock'n G Plumbing handles leak detection and slab leak repair, including repairing or replacing the water line. A water bill that jumps for no reason or a warm spot on the floor is worth a call."],
    ['q' => 'Which towns outside San Angelo do you serve?',
     'a' => "Rock'n G Plumbing serves Christoval as well as San Angelo, and also takes jobs in " . implode(', ', array_slice($areasWeServeTowns, 0, -1)) . ' and ' . end($areasWeServeTowns) . '.'],
];

// Schema: the Plumber node comes from head.php on every page; the homepage adds WebSite + WebPage.
$schemaMarkup = [
    [
        "@context" => 'https://schema.org',
        '@type'     => 'WebSite',
        '@id'       => $siteUrl . '/#website',
        'url'       => $siteUrl . '/',
        'name'      => $siteName,
        'publisher' => ['@id' => orgId()],
        'inLanguage'=> 'en-US',
    ],
    [
        "@context"  => 'https://schema.org',
        '@type'      => 'WebPage',
        '@id'        => $siteUrl . '/#webpage',
        'url'        => $siteUrl . '/',
        'name'       => $pageTitle,
        'description'=> $pageDescription,
        'isPartOf'   => ['@id' => $siteUrl . '/#website'],
        'about'      => ['@id' => orgId()],
        'primaryImageOfPage' => ['@type' => 'ImageObject', 'url' => absUrl($heroPreloadImage), 'caption' => $heroAlt],
        'inLanguage' => 'en-US',
    ],
];

ob_start();
?>
<style>
/* =====================================================================
   Homepage — Rock'n G Plumbing (bold-industrial)
   Page-only CSS. Tokens from framework.css; no raw colors, shadows or spacing.
   ===================================================================== */
@property --home-flow {
  syntax: '<length>';
  inherits: false;
  initial-value: 0px;
}

/* ---------- Hero: layered photo (C1) ---------- */
.home-hero {
  padding-block-end: var(--space-16);
  min-height: 0;
}
.home-hero .hero-bg {
  z-index: -3;
  overflow: hidden;
}
.home-hero .hero-bg picture,
.home-hero .hero-bg img {
  width: 100%;
  height: 100%;
}
.home-hero .hero-bg img {
  object-fit: cover;
  object-position: 50% 20%;
  transform-origin: 60% 25%;
  animation: home-kenburns 24s var(--ease) both;
}
.home-hero .hero-overlay {
  z-index: -2;
}
.home-hero::before {
  content: "";
  position: absolute;
  inset: 0;
  z-index: -1;
  pointer-events: none;
  background:
    radial-gradient(ellipse 55% 65% at 92% 105%, color-mix(in srgb, var(--color-accent) 26%, transparent), transparent 70%),
    linear-gradient(180deg, transparent 55%, color-mix(in srgb, var(--color-primary-deep) 75%, transparent) 100%);
}
.home-hero::after {
  content: "";
  position: absolute;
  inset: 0;
  z-index: -1;
  pointer-events: none;
  opacity: .22;
  mix-blend-mode: overlay;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='160' height='160'%3E%3Cfilter id='g'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.85' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23g)'/%3E%3C/svg%3E");
}
.home-hero .hero-text {
  position: relative;
  padding-inline-start: var(--space-6);
}
.home-hero .hero-text::before {
  content: "";
  position: absolute;
  inset-block: var(--space-2);
  inset-inline-start: 0;
  width: 4px;
  border-radius: var(--radius-full);
  background: linear-gradient(180deg, var(--color-accent), color-mix(in srgb, var(--color-accent) 10%, transparent));
}
.home-hero .hero-title {
  text-wrap: balance;
  max-width: 20ch;
}
.home-hero .hero-answer {
  max-width: 34rem;
}
.home-hero .hero-chips li {
  backdrop-filter: blur(6px);
  -webkit-backdrop-filter: blur(6px);
}
.home-hero .hero-form-open {
  background: var(--color-accent);
  color: var(--color-ink);
}
.home-hero .hero-form-card {
  border-block-start: 4px solid var(--color-accent);
}
.home-hero .hero-form-card h2 {
  text-wrap: balance;
}
@keyframes home-kenburns {
  from { transform: scale(1.08); }
  to   { transform: scale(1); }
}

/* ---------- Ticker ---------- */
.home-ticker {
  background: var(--color-accent);
  border-block: 0;
  padding-block: var(--space-3);
}
.home-ticker .ticker-track {
  color: var(--color-ink);
  gap: var(--space-10);
}
.home-ticker .ticker-track span {
  gap: var(--space-2);
}
.home-ticker .ticker-track svg {
  color: var(--color-primary-deep);
  flex: 0 0 auto;
}

/* ---------- Services ---------- */
.home-services {
  background: var(--color-paper);
}
.home-services .section-title {
  max-width: none;
  grid-template-columns: minmax(0, 1.05fr) minmax(0, .95fr);
  grid-template-areas:
    "eyebrow eyebrow"
    "title   answer"
    "sub     prose";
  column-gap: var(--space-12);
  row-gap: var(--space-3);
  align-items: end;
}
.home-services .section-title .eyebrow-label { grid-area: eyebrow; }
.home-services .section-title h2 { grid-area: title; text-wrap: balance; max-width: 22ch; }
.home-services .section-title .hero-answer { grid-area: answer; font-size: var(--fs-body); color: var(--color-ink-2); max-width: none; }
.home-services .section-title .section-subtitle { grid-area: sub; align-self: start; }
.home-services .section-title .prose { grid-area: prose; font-size: var(--fs-small); color: var(--color-muted); }
.home-services .service-card-with-image {
  isolation: isolate;
}
.home-services .service-card-with-image::before {
  content: "";
  position: absolute;
  inset-inline: 0;
  inset-block-start: 0;
  height: 4px;
  z-index: 2;
  background: var(--color-accent);
  transform: scaleX(0);
  transform-origin: left;
  transition: transform var(--transition-slow);
}
.home-services .service-card-with-image:hover::before,
.home-services .service-card-with-image:focus-within::before {
  transform: scaleX(1);
}
.home-services .service-card__image img {
  transition: transform var(--transition-slow), filter var(--transition-slow);
}
.home-services .service-card-with-image:hover .service-card__image img {
  transform: scale(1.05);
  filter: saturate(1.12) contrast(1.05);
}
.home-services .service-card-with-image h3 {
  text-wrap: balance;
}
.home-services .service-card__cta:hover {
  color: var(--color-accent-dark);
}
.home-services .services-grid:has(.service-card-with-image:hover) .service-card-with-image:not(:hover) {
  opacity: .86;
}
.home-services__foot {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  align-items: center;
  gap: var(--space-4) var(--space-6);
  margin-block-start: var(--space-10);
}
.home-services__foot .link-call svg {
  color: var(--color-accent-dark);
}

/* ---------- Proof strip (dark, slant) ---------- */
.home-proof.stats-band {
  color: var(--color-surface);
  border-block: 0;
  background: linear-gradient(115deg, var(--color-dark) 0%, var(--color-dark-alt) 55%, var(--color-primary-deep) 130%);
  padding-block-end: var(--space-12);
}
.home-proof .stats-row {
  position: relative;
  padding-block-start: var(--space-6);
}
.home-proof .stats-row::before {
  content: "";
  position: absolute;
  inset-inline: 0;
  inset-block-start: 0;
  height: 3px;
  border-radius: var(--radius-full);
  background: repeating-linear-gradient(90deg, var(--color-accent) 0 28px, transparent 28px 44px);
  background-position: var(--home-flow) 0;
  opacity: .7;
  animation: home-flow 3s linear infinite;
}
.home-proof .stat-item {
  border-inline-start-color: color-mix(in srgb, var(--color-surface) 16%, transparent);
}
.home-proof .stat-number {
  color: var(--color-surface);
  font-size: clamp(1.9rem, 1.3rem + 1.6vw, 2.75rem);
  text-transform: uppercase;
}
.home-proof .stat-number span {
  color: var(--color-accent-bright);
}
.home-proof .stat-label {
  color: color-mix(in srgb, var(--color-surface) 74%, transparent);
}
@keyframes home-flow {
  to { --home-flow: 44px; }
}

/* ---------- About + process (asymmetric) ---------- */
.home-about {
  background: var(--color-surface);
}
.home-about .about-split {
  grid-template-columns: minmax(0, 3fr) minmax(0, 2fr);
  align-items: start;
}
.home-about .about-left {
  display: grid;
  gap: var(--space-4);
}
.home-about .about-left h2 {
  text-wrap: balance;
  max-width: 20ch;
}
.home-about .about-left p {
  margin: 0;
  color: var(--color-ink-2);
  max-width: var(--measure);
}
.home-about .about-left h3 {
  margin-block-start: var(--space-6);
  text-wrap: balance;
}
.home-about .process-steps {
  margin-block-start: var(--space-2);
}
.home-about .process-steps li {
  padding: var(--space-4);
  border-radius: var(--radius);
  background: var(--color-paper);
  border: 1px solid var(--color-line);
  transition: border-color var(--transition), transform var(--transition);
}
.home-about .process-steps li:hover {
  border-color: var(--color-accent);
  transform: translateY(-2px);
}
.home-about .about-right {
  margin-block-start: var(--space-16);
}
.home-about .about-image-primary {
  aspect-ratio: 4 / 4.4;
  clip-path: polygon(0 0, 100% 0, 100% 86%, 84% 100%, 0 100%);
}
.home-about .about-image-primary picture,
.home-about .about-image-primary img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}
.home-about .about-image-secondary {
  inset-inline-end: calc(-1 * var(--space-4));
  inset-block-end: calc(-1 * var(--space-12));
  width: 48%;
  aspect-ratio: 3 / 2;
  border-color: var(--color-surface);
}
.home-about .about-image-secondary picture,
.home-about .about-image-secondary img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}
.home-about .about-stat-card {
  inset-block-end: auto;
  inset-block-start: var(--space-6);
  inset-inline-start: calc(-1 * var(--space-8));
  display: grid;
  gap: var(--space-1);
  border-inline-start: 4px solid var(--color-accent);
}
.home-about .about-stat-card b {
  font-family: var(--font-accent);
  font-size: 1.5rem;
  line-height: 1;
  text-transform: uppercase;
  color: var(--color-primary);
}
.home-about .about-stat-card span {
  font-size: var(--fs-small);
  color: var(--color-muted);
}

/* ---------- Mid-page CTA (dark, torn edge) ---------- */
.home-cta {
  isolation: isolate;
}
.home-cta .container-narrow {
  display: grid;
  grid-template-columns: minmax(0, 1fr) auto;
  gap: var(--space-8);
  align-items: center;
}
.home-cta h2 {
  text-wrap: balance;
}
.home-cta p {
  margin: 0;
}
.home-cta .actions {
  display: grid;
  gap: var(--space-3);
  justify-items: start;
}
.home-cta .link-call {
  color: color-mix(in srgb, var(--color-surface) 88%, transparent);
}
.home-cta .link-call svg {
  color: var(--color-accent-bright);
}
.home-cta .link-call:hover {
  color: var(--color-surface);
}
.home-cta__ring {
  inset-inline-end: -120px;
  inset-block-start: -80px;
}

/* ---------- Signature: symptom board ---------- */
.home-signs {
  background: var(--color-paper-2);
}
.home-signs .section-head h2 {
  text-wrap: balance;
}
.home-signs__board {
  container-type: inline-size;
  container-name: signs;
}
.signs-table {
  width: 100%;
  border-collapse: separate;
  border-spacing: 0 var(--space-2);
  font-size: var(--fs-body);
}
.signs-table caption {
  text-align: start;
  font-size: var(--fs-small);
  color: var(--color-muted);
  padding-block-end: var(--space-2);
}
.signs-table thead th {
  font-family: var(--font-accent);
  font-size: var(--fs-eyebrow);
  font-weight: 700;
  letter-spacing: .14em;
  text-transform: uppercase;
  color: var(--color-muted);
  text-align: start;
  padding: var(--space-2) var(--space-5);
}
.signs-table tbody tr {
  background: var(--color-surface);
  box-shadow: var(--shadow-sm);
  transition: transform var(--transition), box-shadow var(--transition);
}
.signs-table tbody tr:hover {
  transform: translateX(4px);
  box-shadow: var(--shadow);
}
.signs-table tbody th,
.signs-table tbody td {
  padding: var(--space-4) var(--space-5);
  text-align: start;
  vertical-align: top;
}
.signs-table tbody th {
  font-family: var(--font-heading);
  font-weight: 700;
  color: var(--color-ink);
  border-inline-start: 4px solid var(--color-accent);
  border-start-start-radius: var(--radius);
  border-end-start-radius: var(--radius);
  width: 38%;
}
.signs-table tbody td {
  color: var(--color-ink-2);
}
.signs-table tbody td:last-child {
  border-start-end-radius: var(--radius);
  border-end-end-radius: var(--radius);
  white-space: nowrap;
}
.signs-table a {
  display: inline-flex;
  align-items: center;
  gap: var(--space-1);
  font-weight: 600;
  color: var(--color-primary);
  text-decoration: none;
}
.signs-table a:hover {
  color: var(--color-accent-dark);
}
.signs-note {
  display: flex;
  gap: var(--space-3);
  align-items: flex-start;
  margin-block-start: var(--space-6);
  padding: var(--space-4) var(--space-5);
  border-radius: var(--radius);
  background: color-mix(in srgb, var(--color-warning) 12%, var(--color-surface));
  border-inline-start: 4px solid var(--color-warning);
  color: var(--color-ink);
  max-width: 80ch;
}
.signs-note svg {
  color: var(--color-warning);
  flex: 0 0 auto;
  margin-block-start: var(--space-1);
}
.signs-note p {
  margin: 0;
}
@container signs (max-width: 760px) {
  .signs-table thead {
    position: absolute;
    width: 1px;
    height: 1px;
    overflow: hidden;
    clip-path: inset(50%);
  }
  .signs-table,
  .signs-table tbody,
  .signs-table tr,
  .signs-table th,
  .signs-table td {
    display: block;
    width: auto;
  }
  .signs-table tbody tr {
    margin-block-end: var(--space-3);
    border-radius: var(--radius);
    border-inline-start: 4px solid var(--color-accent);
  }
  .signs-table tbody th {
    border-inline-start: 0;
    padding-block-end: var(--space-1);
  }
  .signs-table tbody td {
    padding-block: var(--space-1);
    white-space: normal;
  }
  .signs-table tbody td:last-child {
    padding-block-end: var(--space-4);
  }
  .signs-table tbody td::before {
    content: attr(data-label) ": ";
    font-family: var(--font-accent);
    font-size: var(--fs-eyebrow);
    letter-spacing: .1em;
    text-transform: uppercase;
    color: var(--color-muted);
  }
}

/* ---------- Google reviews band ---------- */
.home-reviews {
  background: var(--color-paper);
}

/* ---------- FAQ (dark, wave edge) ---------- */
.home-faq .section-head h2 {
  text-wrap: balance;
}
.home-faq .faq {
  border-block-start-color: color-mix(in srgb, var(--color-surface) 16%, transparent);
  padding-inline: var(--space-2);
  transition: background-color var(--transition);
}
.home-faq .faq:hover {
  background: color-mix(in srgb, var(--color-surface) 4%, transparent);
}
.home-faq .faq summary {
  color: var(--color-surface);
}
.home-faq .faq summary::after {
  color: var(--color-accent-bright);
}
.home-faq .faq p {
  color: color-mix(in srgb, var(--color-surface) 80%, transparent);
}
.home-faq .faq p a {
  color: var(--color-accent-bright);
}

/* ---------- Estimate section ---------- */
.home-estimate {
  background: var(--color-paper-2);
}
.home-estimate .card {
  border-block-start: 4px solid var(--color-accent);
  display: grid;
  gap: var(--space-4);
}
.home-estimate .card h2 {
  text-wrap: balance;
}
.home-estimate .card > p {
  margin: 0;
  color: var(--color-ink-2);
}
.home-estimate .form-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: var(--space-4);
}
.home-estimate .form-grid .field--full {
  grid-column: 1 / -1;
}
.home-estimate label.field > span {
  display: block;
  font-size: var(--fs-small);
  font-weight: 600;
  color: var(--color-ink-2);
  margin-block-end: var(--space-1);
}
.home-estimate textarea {
  min-height: 7.5rem;
  resize: vertical;
}
.home-estimate .form-consent-fieldset {
  margin-block-start: var(--space-2);
}
.home-estimate .form-consent-item {
  margin-block: var(--space-2);
}
.home-estimate .btn-block {
  margin-block-start: var(--space-2);
}
.home-estimate__aside {
  position: sticky;
  inset-block-start: calc(var(--nav-height) + var(--space-6));
  display: grid;
  gap: var(--space-2);
}
.home-estimate__aside h3 {
  text-wrap: balance;
}
.home-estimate__aside h3 + p {
  margin: 0;
  color: var(--color-ink-2);
}
.home-estimate__area {
  margin-block-start: var(--space-8);
  padding-block-start: var(--space-6);
  border-block-start: 1px solid var(--color-line);
}
.home-estimate .nap a:hover {
  color: var(--color-primary);
}

/* ---------- Responsive ---------- */
@media (max-width: 1000px) {
  .home-services .section-title {
    grid-template-columns: 1fr;
    grid-template-areas:
      "eyebrow"
      "title"
      "answer"
      "sub"
      "prose";
  }
}
@media (max-width: 900px) {
  .home-hero .hero-bg img {
    object-position: 50% 30%;
  }
  .home-hero .hero-text {
    padding-inline-start: var(--space-4);
  }
  .home-about .about-right {
    margin-block-start: var(--space-6);
  }
  .home-about .about-stat-card {
    inset-inline-start: calc(-1 * var(--space-4));
  }
  .home-about .about-image-secondary {
    inset-inline-end: calc(-1 * var(--space-2));
  }
  .home-estimate__aside {
    position: static;
  }
}
@media (max-width: 800px) {
  .home-cta .container-narrow {
    grid-template-columns: 1fr;
  }
}
@media (max-width: 560px) {
  .home-hero {
    padding-block-end: var(--space-10);
  }
  .home-estimate .form-grid {
    grid-template-columns: 1fr;
  }
  .home-about .about-image-secondary {
    display: none;
  }
}
/* ---------- From the Blog (registry-driven featured card) ---------- */
.home-blog {
  background:
    radial-gradient(ellipse 45% 50% at 0% 100%, color-mix(in srgb, var(--color-accent) 9%, transparent), transparent 70%),
    var(--color-paper-2);
}
.home-blog__feature {
  display: grid;
  grid-template-columns: minmax(0, 6fr) minmax(0, 5fr);
  border-radius: var(--radius-xl);
  overflow: hidden;
  background: var(--color-surface);
  border: 1px solid var(--color-line);
  box-shadow: var(--shadow-lg);
  text-decoration: none;
  color: inherit;
  transition: transform var(--transition-slow);
}
.home-blog__feature:hover {
  transform: translateY(calc(-1 * var(--space-1)));
}
.home-blog__image {
  min-height: 300px;
  overflow: hidden;
}
.home-blog__image picture,
.home-blog__image img {
  width: 100%;
  height: 100%;
}
.home-blog__image img {
  object-fit: cover;
  transition: transform var(--transition-slow);
}
.home-blog__feature:hover .home-blog__image img {
  transform: scale(1.04);
}
.home-blog__body {
  display: grid;
  gap: var(--space-3);
  align-content: center;
  padding: clamp(var(--space-6), 4vw, var(--space-12));
}
.home-blog__tag {
  justify-self: start;
  padding: var(--space-1) var(--space-4);
  border-radius: var(--radius-full);
  background: var(--color-primary);
  color: var(--color-surface);
  font-family: var(--font-accent);
  font-size: var(--fs-eyebrow);
  letter-spacing: .14em;
  text-transform: uppercase;
}
.home-blog__body h3 {
  font-size: var(--fs-h2);
}
.home-blog__body p {
  margin: 0;
  color: var(--color-ink-2);
}
.home-blog__meta {
  font-size: var(--fs-small);
  color: var(--color-muted);
}
.home-blog__cta {
  justify-self: start;
  font-weight: 700;
  color: var(--color-primary);
  border-block-end: 2px solid var(--color-accent);
}
.home-blog__all {
  margin-block-start: var(--space-8);
}
@media (max-width: 900px) {
  .home-blog__feature {
    grid-template-columns: 1fr;
  }
  .home-blog__image {
    min-height: 0;
    aspect-ratio: 16 / 10;
  }
}
@media (prefers-reduced-motion: reduce) {
  .home-hero .hero-bg img,
  .home-proof .stats-row::before {
    animation: none;
  }
  .home-blog__feature,
  .home-blog__image img,
  .home-services .service-card-with-image::before,
  .signs-table tbody tr,
  .home-about .process-steps li {
    transition: none;
  }
}
</style>
<?php
$extraHead = ob_get_clean();

include $_SERVER['DOCUMENT_ROOT'] . '/includes/head.php';
include $_SERVER['DOCUMENT_ROOT'] . '/includes/header.php';
?>

<!-- ============ HERO (bold-industrial photo hero) ============ -->
<section class="hero hero--photo home-hero" aria-labelledby="home-h1">
  <div class="hero-bg"><?php echo pictureTag($heroImage, $heroAlt, '100vw', ['loading' => 'eager', 'fetchpriority' => 'high']); ?></div>
  <div class="hero-overlay"></div>
  <span class="grain" aria-hidden="true"></span>
  <div class="container">
    <div class="hero-grid hero-grid--form">
      <div class="hero-text">
        <span class="eyebrow"><?php echo h($address['city'] . ', ' . $address['state']); ?> · <?php echo h($companyCounty); ?> County</span>
        <h1 class="hero-title" id="home-h1">San Angelo Plumbing from a Family of Plumbers</h1>
        <p class="hero-answer">Your San Angelo, TX plumber for water heaters, drains, sewer, gas lines and slab leaks, from San Angelo to Christoval.</p>
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
        <h2 id="hero-form-title">Get a free estimate</h2>
        <p class="hero-form-tagline">Tell us what's going on. No obligation.</p>
        <form action="<?php echo h($formAction); ?>" method="POST" class="hero-form">
          <?php echo p1_lead_hidden_fields('hero'); ?>
          <div class="form-row"><label class="sr-only" for="hero-name">Name</label><input id="hero-name" type="text" name="name" placeholder="Name" autocomplete="name" required></div>
          <div class="form-row"><label class="sr-only" for="hero-phone">Phone</label><input id="hero-phone" type="tel" name="phone" placeholder="Phone" autocomplete="tel" inputmode="tel" required></div>
          <div class="form-row"><label class="sr-only" for="hero-service">Service</label><select id="hero-service" name="service"><?php echo p1_service_options(); ?></select></div>
          <label class="consent" for="hero-terms"><input id="hero-terms" type="checkbox" name="terms_accepted" value="yes" required><span>I agree to the <a href="/terms/" target="_blank" rel="noopener">Terms</a> and <a href="/privacy-policy/" target="_blank" rel="noopener">Privacy Policy</a> and consent to be contacted. *</span></label>
          <button type="submit" class="btn btn-primary btn-block">Get my free estimate</button>
        </form>
      </aside>
    </div>
  </div>
</section>

<!-- ============ TICKER ============ -->
<div class="ticker-strip home-ticker" aria-hidden="true">
  <div class="ticker-track">
    <?php
    $tickerItems = [
        ['flame', 'Water heaters & tankless'], ['droplets', 'Softeners & reverse osmosis'], ['waves', 'Drain cleaning'],
        ['search', 'Sewer camera inspection'], ['layers', 'Slab leak repair'], ['wrench', 'Gas line installation'],
        ['sprout', 'Sprinkler & irrigation repair'], ['hammer', 'New construction & remodels'],
        ['building-2', 'Residential · commercial · industrial'], ['map-pin', 'San Angelo · Christoval'],
    ];
    for ($tickerPass = 0; $tickerPass < 2; $tickerPass++):
        foreach ($tickerItems as $tickerItem): ?>
    <span><?php echo icon($tickerItem[0], 18); ?><?php echo h($tickerItem[1]); ?></span>
    <?php endforeach; endfor; ?>
  </div>
</div>

<!-- ============ SERVICES ============ -->
<section class="section home-services" id="services" aria-label="Plumbing services">
  <div class="container-wide">
    <div class="section-title reveal-up">
      <span class="eyebrow-label">What We Do</span>
      <h2>Which plumbing jobs does <span class="text-accent">Rock'n G</span> handle in San Angelo?</h2>
      <p class="hero-answer">Rock'n G Plumbing handles eight kinds of plumbing work in San Angelo and Christoval. That covers water heaters, water softeners and filtration, drain cleaning, sewer line repair with camera inspection, leak detection and slab leaks, gas lines, sprinkler and irrigation repair, and the plumbing in new construction and remodels.</p>
      <span class="section-subtitle">Water · Drains · Sewer · Gas · Irrigation</span>
      <p class="prose">Each service has its own page with the details of the work.</p>
    </div>

    <div class="services-grid">
      <?php foreach ($homeServices as $i => $svc): $tint = ($i % 3) + 1; ?>
      <article class="service-card-with-image card-tint-<?php echo $tint; ?> reveal-up reveal-delay-<?php echo $tint; ?>">
        <div class="service-card__image">
          <?php echo pictureTag($svc['photo'], $svc['alt'], '(max-width: 440px) 100vw, (max-width: 720px) 50vw, (max-width: 1000px) 33vw, 340px'); ?>
        </div>
        <div class="service-card__body">
          <div class="service-card__icon"><?php echo icon($svc['icon'], 22); ?></div>
          <h3><?php echo h($svc['name']); ?></h3>
          <p class="service-card__desc"><?php echo h($svc['desc']); ?></p>
          <ul>
            <?php foreach ($svc['bullets'] as $bullet): ?>
            <li><?php echo h($bullet); ?></li>
            <?php endforeach; ?>
          </ul>
          <a href="<?php echo h(serviceUrl($svc)); ?>" class="service-card__cta" aria-label="Learn more about <?php echo h($svc['name']); ?>">Learn more</a>
        </div>
      </article>
      <?php endforeach; ?>
    </div>

    <div class="home-services__foot reveal-up">
      <a class="btn btn-secondary" href="/services/">View All <?php echo count($services); ?> Services <?php echo icon('arrow-right', 18); ?></a>
      <a class="link-call" href="<?php echo h($telHref); ?>"><?php echo icon('phone', 18); ?>Not sure which one? Call <?php echo h($phoneFmt); ?></a>
    </div>
  </div>
</section>

<!-- ============ PROOF STRIP ============ -->
<section class="stats-band texture-grain slant-top home-proof" aria-labelledby="home-proof-title">
  <span class="grain-layer" aria-hidden="true"></span>
  <div class="container">
    <h2 class="sr-only" id="home-proof-title"><?php echo h($siteName); ?> at a glance</h2>
    <div class="stats-row">
      <?php foreach ($homeProof as $i => $proof): ?>
      <div class="stat-item reveal-up reveal-delay-<?php echo $i + 1; ?>">
        <span class="stat-number"><?php echo $proof['num']; ?></span>
        <span class="stat-label"><?php echo h($proof['label']); ?></span>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ============ ABOUT + PROCESS (asymmetric) ============ -->
<section class="section home-about" aria-labelledby="home-about-title">
  <div class="container">
    <div class="about-split">
      <div class="about-left reveal-left">
        <span class="eyebrow">About Rock'n G</span>
        <h2 id="home-about-title">Who shows up when you call <span class="text-accent">Rock'n G Plumbing</span>?</h2>
        <p>Rock'n G Plumbing is <?php echo h($ownerName); ?>'s company, and plumbing is his family's trade. The crew takes on residential, commercial and industrial work across San Angelo and Christoval, from a single slow drain to the plumbing in a new build.</p>
        <p>The photos on this page show the range: a trench dug to reach a buried line, PVC drain lines run down through a floor, a softener and whole-house filters in a garage, gas piping run to a standby generator, and a finished kitchen remodel.</p>
        <h3>How a Rock'n G job runs</h3>
        <ol class="process-steps">
          <li><b>Call or send the form</b><span>Reach the office at <?php echo h($phoneFmt); ?>, <?php echo h($weekday[0]); ?> <?php echo h($weekday[1]); ?>, or send the estimate form any time.</span></li>
          <li><b>Estimate</b><span>Describe the problem, and Rock'n G prices the work at no charge.</span></li>
          <li><b>You decide</b><span>Go ahead when the price and the plan make sense to you.</span></li>
          <li><b>The work</b><span>The job is done under <?php echo h($ownerName); ?>'s Texas Master Plumber license.</span></li>
        </ol>
      </div>

      <div class="about-right reveal-right">
        <div class="about-image">
          <div class="about-image-primary img-reveal">
            <?php echo pictureTag('rockn-trench-dig', "Rock'n G plumber in a company shirt working in an excavated trench", '(max-width: 900px) 90vw, 460px'); ?>
          </div>
          <div class="about-image-secondary">
            <?php echo pictureTag('rockn-kitchen-remodel', 'Finished kitchen remodel with a farmhouse sink in the island', '(max-width: 900px) 45vw, 220px'); ?>
          </div>
          <div class="about-stat-card">
            <b><?php echo h($ownerName); ?></b>
            <span><?php echo h($license['role']); ?></span>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ============ MID-PAGE CTA ============ -->
<section class="cta-banner texture-grain edge-torn-top home-cta" aria-labelledby="home-cta-title">
  <span class="grain-layer" aria-hidden="true"></span>
  <span class="floating-ring home-cta__ring" aria-hidden="true"></span>
  <div class="container-narrow">
    <div class="reveal-left">
      <span class="eyebrow">Don't wait on it</span>
      <h2 id="home-cta-title">Small plumbing problems get bigger and more expensive</h2>
      <p>A slow leak under a slab, a water heater that keeps running cold, or a drain that backs up every month rarely fixes itself. Call Rock'n G during business hours or send the form tonight, and get it looked at before it turns into a bigger job.</p>
    </div>
    <div class="actions reveal-right">
      <a class="btn btn-accent btn-lg" href="/contact/" data-open-estimate>Request an estimate</a>
      <a class="link-call" href="<?php echo h($telHref); ?>"><?php echo icon('phone', 18); ?>Call <?php echo h($phoneFmt); ?></a>
    </div>
  </div>
</section>

<!-- ============ SIGNATURE: SYMPTOM BOARD ============ -->
<section class="section home-signs" aria-labelledby="home-signs-title">
  <div class="container-wide">
    <div class="section-head reveal-up">
      <span class="eyebrow">Know the signs</span>
      <h2 id="home-signs-title">What is your plumbing trying to tell you?</h2>
      <p>Most plumbing problems show a symptom before they turn into a repair bill: hot water that runs short, crust on faucets, drains that gurgle, a water bill that jumps. Match what you are seeing to the likely cause below, then call Rock'n G Plumbing to confirm it.</p>
    </div>
    <div class="home-signs__board reveal-scale">
      <table class="signs-table">
        <caption>Common plumbing symptoms, what usually causes them, and which Rock'n G service handles each one.</caption>
        <thead>
          <tr><th scope="col">What you notice</th><th scope="col">What it often means</th><th scope="col">Who handles it</th></tr>
        </thead>
        <tbody>
          <?php foreach ($homeSigns as $sign): $signSvc = $svcBySlug[$sign['slug']]; ?>
          <tr>
            <th scope="row"><?php echo h($sign['sign']); ?></th>
            <td data-label="Likely cause"><?php echo h($sign['cause']); ?></td>
            <td data-label="Service"><a href="<?php echo h(serviceUrl($signSvc)); ?>"><?php echo h($signSvc['name']); ?> <?php echo icon('arrow-right', 16); ?></a></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <div class="signs-note" role="note">
        <?php echo icon('flame', 22); ?>
        <p><strong>Smell gas (a rotten-egg odor)?</strong> Leave the building first and call 911 or your gas utility from outside. Once it is safe, Rock'n G Plumbing repairs and installs gas lines — see <a href="<?php echo h(serviceUrl($svcBySlug['gas-line-services'])); ?>">gas line installation and repair</a>.</p>
      </div>
    </div>
  </div>
</section>

<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/google-reviews.php';
$homeReviews = p1_google_reviews($slug, ['heading' => 'What ' . $address['city'] . ' customers say on Google']);
if ($homeReviews !== ''): ?>
<!-- ============ GOOGLE REVIEWS (Page One feed; light band, never inside a reveal) ============ -->
<section class="section home-reviews" aria-label="Google reviews">
  <div class="container"><?php echo $homeReviews; ?></div>
</section>
<?php endif; ?>

<!-- ============ FAQ ============ -->
<section class="section on-dark edge-wave-top home-faq" aria-labelledby="home-faq-title">
  <span class="grain-layer" aria-hidden="true"></span>
  <div class="container-wide">
    <div class="section-head reveal-up">
      <span class="eyebrow">FAQ</span>
      <h2 id="home-faq-title">What do customers ask their San Angelo plumber?</h2>
      <p>Before booking a plumber, customers want to know what an estimate costs, whether there is a discount, and how Rock'n G Plumbing handles water heaters, hard water, slab leaks and towns outside San Angelo.</p>
    </div>
    <div class="faq-grid reveal-up">
      <?php foreach ($homeFaqs as $i => $faq): ?>
      <details class="faq"<?php echo $i < 2 ? ' open' : ''; ?>>
        <summary><?php echo h($faq['q']); ?></summary>
        <p><?php echo h($faq['a']); ?></p>
      </details>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ============ FROM THE BLOG (registry: newest post) ============ -->
<?php if (!empty($blogPosts)): $homePost = $blogPosts[0]; ?>
<section class="section home-blog" aria-labelledby="home-blog-title">
  <div class="container-wide">
    <div class="section-head reveal-up">
      <span class="eyebrow">From the Blog</span>
      <h2 id="home-blog-title">What should San Angelo homeowners read before calling a plumber?</h2>
      <p>Rock'n G Plumbing's blog answers the questions that come up before a plumbing job: what drives a water heater price, how to protect pipes before a freeze, and what an estimate should spell out.</p>
    </div>
    <a class="home-blog__feature reveal-scale" href="/blog/<?php echo h($homePost['slug']); ?>/">
      <div class="home-blog__image"><?php echo pictureTag($homePost['photo'], $homePost['alt'], '(max-width: 900px) 100vw, 640px'); ?></div>
      <div class="home-blog__body">
        <span class="home-blog__tag"><?php echo h($homePost['category']); ?></span>
        <h3><?php echo h($homePost['title']); ?></h3>
        <p><?php echo h($homePost['excerpt']); ?></p>
        <span class="home-blog__meta"><?php echo h($homePost['date']); ?> · <?php echo h($homePost['readtime']); ?></span>
        <span class="home-blog__cta">Read article</span>
      </div>
    </a>
    <p class="home-blog__all reveal-up"><a class="btn btn-secondary" href="/blog/">View all articles</a></p>
  </div>
</section>
<?php endif; ?>

<!-- ============ ESTIMATE SECTION ============ -->
<section class="section home-estimate" id="estimate" aria-labelledby="home-estimate-title">
  <div class="container estimate">
    <div class="card reveal-up">
      <span class="eyebrow">Estimate request</span>
      <h2 id="home-estimate-title">Tell us about the job</h2>
      <p>Send the details and Rock'n G Plumbing will get back to you during business hours, <?php echo h($weekday[0]); ?> <?php echo h($weekday[1]); ?>.</p>
      <form action="<?php echo h($formAction); ?>" method="POST">
        <input type="text" name="_honey" style="display:none !important" tabindex="-1" autocomplete="off" aria-hidden="true">
        <input type="hidden" name="_next" value="<?php echo htmlspecialchars($siteUrl); ?>/thank-you">
        <input type="hidden" name="form_location" value="estimate-section">
        <?php echo p1_attribution_fields('estimate-section'); ?>
        <input type="hidden" name="consent_version" value="v2.1">
        <input type="hidden" name="consent_page" value="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>">
        <div class="form-grid">
          <label class="field" for="est-name"><span>Your name</span><input id="est-name" type="text" name="name" autocomplete="name" required></label>
          <label class="field" for="est-phone"><span>Phone</span><input id="est-phone" type="tel" name="phone" autocomplete="tel" inputmode="tel" required></label>
          <label class="field" for="est-email"><span>Email</span><input id="est-email" type="email" name="email" autocomplete="email" required></label>
          <label class="field" for="est-service"><span>Service needed</span><select id="est-service" name="service"><?php echo p1_service_options(); ?></select></label>
          <label class="field field--full" for="est-message"><span>What's going on?</span><textarea id="est-message" name="message" rows="4"></textarea></label>
        </div>
        <fieldset class="form-consent-fieldset">
          <legend class="form-consent-legend">Communication consent</legend>
          <label class="form-consent-item" for="est-email-opt"><input id="est-email-opt" type="checkbox" name="email_opt_in" value="yes"><span><strong>Email updates (optional):</strong> I agree to receive emails from <?php echo h($siteName); ?> about my inquiry, services, promotions, and news. I can unsubscribe anytime via the link in any email or by emailing <?php echo h($contactEmail); ?>. Message frequency varies.</span></label>
          <label class="form-consent-item" for="est-sms-opt"><input id="est-sms-opt" type="checkbox" name="sms_opt_in" value="yes"><span><strong>Text messages (optional):</strong> I agree to receive text messages from <?php echo h($siteName); ?> at the phone number I provided, including appointment reminders, service updates and promotional offers. Message frequency varies. Message and data rates may apply. Reply STOP to unsubscribe, HELP for help. <strong>Consent is not a condition of purchase.</strong></span></label>
          <label class="form-consent-item" for="est-terms"><input id="est-terms" type="checkbox" name="terms_accepted" value="yes" required><span>I have read and agree to the <a href="/privacy-policy/">Privacy Policy</a> and <a href="/terms/">Terms of Service</a>. *</span></label>
        </fieldset>
        <button type="submit" class="btn btn-primary btn-lg btn-block">Request my free estimate</button>
      </form>
    </div>

    <aside class="home-estimate__aside reveal-right" aria-labelledby="home-next-title">
      <h3 id="home-next-title">What happens next</h3>
      <ol class="next-steps">
        <li><strong>Your request reaches Rock'n G</strong>The form goes straight to the Rock'n G Plumbing office.</li>
        <li><strong>A call about the job</strong>Rock'n G contacts you during business hours to talk through what is going on.</li>
        <li><strong>Your estimate</strong>You get a price for the work and decide from there.</li>
      </ol>
      <div class="nap">
        <div><?php echo icon('phone', 18); ?><a href="<?php echo h($telHref); ?>"><?php echo h($phoneFmt); ?></a></div>
        <div><?php echo icon('mail', 18); ?><a href="mailto:<?php echo h($email); ?>"><?php echo h($email); ?></a></div>
        <div><?php echo icon('clock', 18); ?><span><?php echo h($businessHours); ?></span></div>
      </div>
      <div class="home-estimate__area">
        <h3>Plumber for San Angelo, Christoval and nearby towns</h3>
        <p>Rock'n G Plumbing is based in <?php echo h($address['city'] . ', ' . $address['state']); ?> and works across Tom Green County, including <?php if (pageExists('/service-area/christoval-tx/')): ?><a href="/service-area/christoval-tx/">Christoval</a><?php else: ?>Christoval<?php endif; ?> and the surrounding towns.</p>
      </div>
    </aside>
  </div>
</section>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>
