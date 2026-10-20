<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/functions.php';
?>
<?php
/**
 * about/index.php — About Rock'n G Plumbing (Phase 5).
 * Every business fact traces to build-plan.json / includes/config.php: three generations, 20+ years, Eli Gomez as
 * Responsible Master Plumber on Texas license M-44822, residential/commercial/industrial, free estimates, military
 * discounts, Google 5.0 from 3 reviews (visible text only). NOT stated anywhere on this page because intake did not
 * supply it: founding year, family history, crew size, insurance, discount amount, equipment ownership. Those
 * questions are in /content-confirmations.json.
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/service-cards.php';

$pageType        = 'about';
$pageTitle       = "About Rock'n G Plumbing | San Angelo Plumber, TX";
$pageDescription = "Meet Rock'n G Plumbing, a San Angelo, TX plumber from three generations of plumbers • Master Plumber Lic. M-44822 • Free estimates. Call (325) 650-7000.";
$canonicalUrl    = $siteUrl . '/about/';
$ogImage         = '/assets/images/og-default-v1.jpg';
$currentPage     = 'about';
$breadcrumbs     = [['name' => 'About', 'url' => '/about/']];

$telHref  = 'tel:' . $phoneE164;
$phoneFmt = formatPhone($phone);
$weekday  = hoursGroups($hours)[0];      // ['Mon–Fri', '8:00 AM – 6:00 PM']
$licenseExpires = date('F j, Y', strtotime($license['expires']));
$townList = implode(', ', array_slice($areasWeServeTowns, 0, -1)) . ' and ' . end($areasWeServeTowns);

// Principles — each one restates a verified intake fact, nothing aspirational
$abPrinciples = [
    ['icon' => 'badge-check', 'title' => "A master plumber's license behind the work",
     'body'  => "Eli Gomez is the Responsible Master Plumber for Rock'n G Plumbing on Texas Master Plumber License M-44822. Anyone can look the license up with the Texas State Board of Plumbing Examiners before they book."],
    ['icon' => 'clipboard-list', 'title' => 'Free estimates before any work',
     'body'  => "Rock'n G Plumbing looks at the problem and tells you the price before any work begins, at no charge. You decide whether to go ahead once you know what the job involves."],
    ['icon' => 'building-2', 'title' => 'Homes, businesses and industrial sites',
     'body'  => "The company takes residential, commercial and industrial plumbing work, from a kitchen remodel to a gas line run to a standby generator."],
    ['icon' => 'shield-check', 'title' => 'Military discounts',
     'body'  => "Rock'n G Plumbing offers a military discount. Mention your service when you call or add it to the estimate form, and ask how it applies to your job."],
];

// Milestones — only what is on record; no invented dates
$abMilestones = [
    ['label' => 'A family trade', 'title' => 'Three generations of plumbers',
     'body'  => "Plumbing runs in the Gomez family. Rock'n G Plumbing is the family's plumbing company in San Angelo, Texas."],
    ['label' => '20+ years', 'title' => 'Two decades of plumbing experience',
     'body'  => "Eli Gomez and the Rock'n G crew bring 20+ years of experience to San Angelo homes and businesses, across all eight services the company offers."],
    ['label' => 'Licensed', 'title' => 'Texas Master Plumber License M-44822',
     'body'  => "The Texas State Board of Plumbing Examiners lists Jerry Eli Gomez as Responsible Master Plumber for Rock'n G Plumbing, with the license on record through " . $licenseExpires . '.'],
    ['label' => 'Today', 'title' => 'San Angelo, Christoval and the towns around them',
     'body'  => "Rock'n G Plumbing serves San Angelo and Christoval, and also takes jobs in " . $townList . '. The office is open ' . $weekday[0] . ', ' . $weekday[1] . '.'],
];

// Real job photos (all from /assets/images/, same alt text as the service cards)
$abJobs = [
    ['photo' => 'rockn-water-softener-filtration', 'alt' => 'Water softener, brine tank and whole-house filter housings installed in a garage',
     'cap'   => 'A softener and whole-house filter install', 'slug' => 'water-filtration-softeners'],
    ['photo' => 'rockn-gas-line-generator', 'alt' => 'Gas line run along the brick wall of a home to a standby generator',
     'cap'   => 'Gas piping run to a standby generator', 'slug' => 'gas-line-services'],
    ['photo' => 'rockn-kitchen-remodel', 'alt' => 'Remodeled kitchen with a farmhouse sink in the island and a gas cooktop',
     'cap'   => 'Plumbing for a finished kitchen remodel', 'slug' => 'new-construction-remodel-plumbing'],
];

$schemaMarkup = [
    [
        "@context" => 'https://schema.org',
        '@type'    => 'AboutPage',
        '@id'      => $canonicalUrl . '#webpage',
        'url'      => $canonicalUrl,
        'name'     => $pageTitle,
        'description' => $pageDescription,
        'isPartOf' => ['@id' => $siteUrl . '/#website'],
        'about'    => ['@id' => orgId()],
        'mainEntity' => ['@id' => $canonicalUrl . '#eli-gomez'],
        'inLanguage' => 'en-US',
    ],
    [
        "@context" => 'https://schema.org',
        '@type'    => 'Person',
        '@id'      => $canonicalUrl . '#eli-gomez',
        'name'     => $ownerName,
        'alternateName' => $license['holder'],
        'jobTitle' => $license['role'],
        'worksFor' => ['@id' => orgId()],
        'hasCredential' => [
            '@type' => 'EducationalOccupationalCredential',
            'credentialCategory' => 'license',
            'name'  => $license['label'],
            'recognizedBy' => ['@type' => 'Organization', 'name' => $license['issuer']],
        ],
    ],
];

ob_start();
?>
<style>
/* =====================================================================
   About — page-only CSS (ab- prefix). Tokens from framework.css; no raw
   colors, shadows or spacing.
   ===================================================================== */

/* ---------- Hero: compact interior, type-led (C1 layered) ---------- */
.ab-hero {
  position: relative;
  isolation: isolate;
  overflow: clip;
  padding-block-end: var(--space-16);
}
.ab-hero::before {
  content: "";
  position: absolute;
  inset: 0;
  z-index: -1;
  pointer-events: none;
  background:
    radial-gradient(ellipse 55% 80% at 100% 0%, color-mix(in srgb, var(--color-accent) 24%, transparent), transparent 65%),
    linear-gradient(115deg, var(--color-dark) 0%, var(--color-dark-alt) 60%, var(--color-primary-deep) 100%);
}
.ab-hero::after {
  content: "";
  position: absolute;
  inset: 0;
  z-index: -1;
  pointer-events: none;
  opacity: .16;
  mix-blend-mode: overlay;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='160' height='160'%3E%3Cfilter id='g'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.8' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23g)'/%3E%3C/svg%3E");
}
.ab-hero .breadcrumb {
  color: color-mix(in srgb, var(--color-surface) 72%, transparent);
  margin-block-end: var(--space-2);
}
.ab-hero .breadcrumb li {
  display: inline-flex;
  align-items: center;
}
.ab-hero .breadcrumb a:hover {
  color: var(--color-accent-bright);
}
.ab-hero .breadcrumb [aria-current="page"] {
  color: var(--color-surface);
}
.ab-hero__grid {
  display: grid;
  grid-template-columns: minmax(0, 1.25fr) minmax(0, .75fr);
  gap: clamp(2rem, 5vw, 4rem);
  align-items: end;
}
.ab-hero .link-call,
.ab-hero .link-call svg {
  color: color-mix(in srgb, var(--color-surface) 92%, transparent);
}
.ab-hero .link-call:hover {
  color: var(--color-surface);
}
.ab-hero h1 {
  max-width: 20ch;
  text-wrap: balance;
}
.ab-hero .hero-answer {
  max-width: 58ch;
  margin-block: var(--space-4) var(--space-6);
}
.ab-hero__facts {
  display: grid;
  gap: var(--space-3);
  margin: 0;
  padding: var(--space-6);
  list-style: none;
  border: 1px solid color-mix(in srgb, var(--color-surface) 18%, transparent);
  border-radius: var(--radius-lg);
  background: color-mix(in srgb, var(--color-surface) 7%, transparent);
  backdrop-filter: blur(6px);
}
.ab-hero__facts li {
  display: grid;
  grid-template-columns: auto 1fr;
  gap: var(--space-3);
  align-items: center;
  color: color-mix(in srgb, var(--color-surface) 90%, transparent);
  font-size: var(--fs-small);
}
.ab-hero__facts b {
  display: block;
  font-family: var(--font-accent);
  font-size: 1.5rem;
  line-height: 1.1;
  letter-spacing: .02em;
  color: var(--color-surface);
}
.ab-hero__facts svg {
  color: var(--color-accent-bright);
}

/* ---------- Story: offset photo frame + long copy (C11 composition) ---------- */
.ab-story__grid {
  display: grid;
  grid-template-columns: minmax(0, .8fr) minmax(0, 1.2fr);
  gap: clamp(2rem, 6vw, 5rem);
  align-items: start;
}
.ab-frame {
  position: relative;
  margin: 0 var(--space-6) var(--space-8) 0;
}
.ab-frame::before {
  content: "";
  position: absolute;
  inset: var(--space-6) calc(var(--space-6) * -1) calc(var(--space-6) * -1) var(--space-6);
  z-index: 0;
  border-radius: var(--radius-lg);
  background: var(--color-card-tint-3);
  border: 1px solid var(--color-line);
}
.ab-frame picture,
.ab-frame img {
  position: relative;
  z-index: 1;
  display: block;
  width: 100%;
  height: auto;
  border-radius: var(--radius-lg);
  box-shadow: var(--shadow-lg);
  filter: var(--photo-grade);
}
.ab-frame figcaption {
  position: relative;
  z-index: 1;
  margin-block-start: var(--space-3);
  font-size: var(--fs-small);
  color: var(--color-muted);
}
.ab-story__copy h2 {
  text-wrap: balance;
  margin-block-end: var(--space-4);
}
.ab-story__copy p {
  max-width: 62ch;
}
.ab-story__copy p + p {
  margin-block-start: var(--space-4);
}
.ab-story__copy .answer-block {
  margin-block-end: var(--space-6);
}

/* ---------- Mid-page lead form band ---------- */
.ab-band {
  background: var(--color-card-tint-3);
}
.ab-band__grid {
  display: grid;
  grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
  gap: clamp(1.5rem, 5vw, 4rem);
  align-items: center;
}
.ab-band__copy h2 {
  margin-block-end: var(--space-3);
  text-wrap: balance;
}
.ab-band__copy p {
  max-width: 48ch;
  color: var(--color-ink-2);
}
.ab-band__form {
  padding: var(--space-6);
  border: 1px solid var(--color-line);
  border-radius: var(--radius-lg);
  background: var(--color-surface);
  box-shadow: var(--shadow);
}
.ab-band__form .form-row + .form-row {
  margin-block-start: var(--space-3);
}
.ab-band__form .field > span {
  font-size: var(--fs-small);
  font-weight: 600;
  color: var(--color-ink-2);
}
.ab-band__form .btn {
  margin-block-start: var(--space-2);
}

/* ---------- Principles: tinted card grid, one asymmetric lead card (C5) ---------- */
.ab-principles {
  background: var(--color-paper-2);
}
.ab-principles__grid {
  display: grid;
  grid-template-columns: repeat(6, minmax(0, 1fr));
  gap: var(--space-5);
  margin-block-start: var(--space-10);
}
.ab-card {
  grid-column: span 3;
  display: grid;
  align-content: start;
  gap: var(--space-3);
  padding: var(--space-8);
  border: 1px solid var(--color-line);
  border-radius: var(--radius-lg);
  box-shadow: var(--shadow-sm);
  transition: transform var(--transition), box-shadow var(--transition);
}
.ab-card:hover {
  transform: translateY(-4px);
  box-shadow: var(--shadow);
}
.ab-card:first-child {
  grid-column: span 4;
}
.ab-card:nth-child(2) {
  grid-column: span 2;
}
.ab-card__icon {
  display: grid;
  place-items: center;
  width: 3rem;
  height: 3rem;
  border-radius: var(--radius);
  background: var(--color-surface);
  color: var(--color-primary);
  box-shadow: var(--shadow-sm);
}
.ab-card h3 {
  text-wrap: balance;
}
.ab-card p {
  color: var(--color-ink-2);
  font-size: .95rem;
}

/* ---------- Milestones: timeline (C8) ---------- */
.ab-miles__grid {
  display: grid;
  grid-template-columns: minmax(0, .8fr) minmax(0, 1.2fr);
  gap: clamp(2rem, 6vw, 5rem);
  align-items: start;
}
.ab-miles__intro {
  position: sticky;
  top: calc(var(--nav-height) + var(--space-8));
}
.ab-miles__intro p {
  max-width: 44ch;
  margin-block-start: var(--space-4);
}
.ab-miles .timeline {
  margin: 0;
  gap: var(--space-8);
}
.ab-miles .timeline li b {
  display: block;
  margin-block-end: var(--space-1);
  font-family: var(--font-accent);
  font-size: var(--fs-eyebrow);
  letter-spacing: .12em;
  text-transform: uppercase;
  color: var(--color-accent-dark);
}
.ab-miles .timeline li h3 {
  margin-block-end: var(--space-2);
}
.ab-miles .timeline li p {
  color: var(--color-ink-2);
  max-width: 54ch;
}

/* ---------- Job photo strip ---------- */
.ab-jobs {
  background: var(--color-paper);
}
.ab-jobs__row {
  display: grid;
  grid-template-columns: repeat(3, minmax(0, 1fr));
  gap: var(--space-5);
  margin-block-start: var(--space-8);
}
.ab-job {
  margin: 0;
  overflow: hidden;
  border-radius: var(--radius-lg);
  background: var(--color-surface);
  border: 1px solid var(--color-line);
  box-shadow: var(--shadow-sm);
}
.ab-job picture,
.ab-job img {
  display: block;
  width: 100%;
  height: auto;
  aspect-ratio: 4 / 3;
  object-fit: cover;
  filter: var(--photo-grade);
  transition: transform var(--transition-slow);
}
.ab-job:hover img {
  transform: scale(1.04);
}
.ab-job figcaption {
  padding: var(--space-4) var(--space-5);
  font-size: var(--fs-small);
  color: var(--color-ink-2);
}
.ab-job figcaption a {
  display: block;
  margin-block-start: var(--space-1);
  font-weight: 700;
  color: var(--color-primary);
}

/* ---------- Credentials band (dark) ---------- */
.ab-creds__grid {
  display: grid;
  grid-template-columns: repeat(2, minmax(0, 1fr));
  gap: var(--space-6);
  margin-block-start: var(--space-8);
}
.ab-creds__card {
  padding: var(--space-8);
  border: 1px solid color-mix(in srgb, var(--color-surface) 16%, transparent);
  border-radius: var(--radius-lg);
  background: color-mix(in srgb, var(--color-surface) 6%, transparent);
}
.ab-creds__card h3 {
  margin-block-end: var(--space-3);
}
.ab-creds__card dl {
  display: grid;
  grid-template-columns: auto 1fr;
  gap: var(--space-2) var(--space-5);
  margin: 0;
}
.ab-creds__card dt {
  font-family: var(--font-accent);
  font-size: var(--fs-eyebrow);
  letter-spacing: .12em;
  text-transform: uppercase;
  color: var(--color-accent-bright);
}
.ab-creds__card dd {
  margin: 0;
  color: var(--color-surface);
}
.ab-creds__card .actions {
  display: flex;
  flex-wrap: wrap;
  gap: var(--space-3);
  margin-block-start: var(--space-5);
}
.ab-creds__reviews {
  margin-block-start: var(--space-8);
}

/* ---------- Closing CTA ---------- */
.ab-creds .section-head {
  max-width: 64ch;
}
.ab-creds .answer-block {
  background: color-mix(in srgb, var(--color-surface) 6%, transparent);
  color: color-mix(in srgb, var(--color-surface) 88%, transparent);
}
.ab-cta .link-call {
  color: color-mix(in srgb, var(--color-surface) 90%, transparent);
}
.ab-cta .link-call svg {
  color: var(--color-accent-bright);
}
.ab-cta__ring {
  position: absolute;
  inset-block-start: -6rem;
  inset-inline-end: -4rem;
  width: 18rem;
  aspect-ratio: 1;
  border: 2px solid color-mix(in srgb, var(--color-accent) 30%, transparent);
  border-radius: 50%;
  opacity: .6;
  pointer-events: none;
}

@media (max-width: 900px) {
  .ab-hero__grid,
  .ab-band__grid,
  .ab-story__grid,
  .ab-miles__grid,
  .ab-creds__grid {
    grid-template-columns: 1fr;
  }
  .ab-miles__intro {
    position: static;
  }
  .ab-principles__grid,
  .ab-jobs__row {
    grid-template-columns: 1fr;
  }
  .ab-card,
  .ab-card:first-child,
  .ab-card:nth-child(2) {
    grid-column: auto;
  }
  .ab-frame {
    max-width: 26rem;
    margin-inline: auto var(--space-6);
  }
}
@media (max-width: 767px) {
  .ab-card p {
    font-size: var(--fs-body);
  }
}
@media (prefers-reduced-motion: reduce) {
  .ab-card,
  .ab-job img {
    transition: none;
  }
  .ab-card:hover,
  .ab-job:hover img {
    transform: none;
  }
}
</style>
<?php
$extraHead = ob_get_clean();

include $_SERVER['DOCUMENT_ROOT'] . '/includes/head.php';
include $_SERVER['DOCUMENT_ROOT'] . '/includes/header.php';
?>

<!-- ============ HERO ============ -->
<section class="hero hero--interior ab-hero" aria-labelledby="ab-h1">
  <span class="grain" aria-hidden="true"></span>
  <div class="container">
    <nav class="breadcrumb" aria-label="Breadcrumb">
      <ol>
        <li><a href="/">Home</a></li>
        <li class="breadcrumb-sep" aria-hidden="true">/</li>
        <li><span aria-current="page">About</span></li>
      </ol>
    </nav>
    <div class="ab-hero__grid">
      <div>
        <span class="eyebrow">About Rock'n G Plumbing</span>
        <h1 id="ab-h1">A San Angelo Plumber from a Family of Plumbers</h1>
        <p class="hero-answer">Rock'n G Plumbing is a San Angelo, Texas plumbing company led by Eli Gomez, Responsible Master Plumber on Texas Master Plumber License M-44822. Three generations of plumbers and 20+ years of experience stand behind every estimate, and looking at your problem costs nothing.</p>
        <div class="hero-actions">
          <a class="btn btn-accent btn-lg" href="/contact/" data-open-estimate>Get a free estimate</a>
          <a class="link-call" href="<?php echo h($telHref); ?>"><?php echo icon('phone', 18); ?>or call <?php echo h($phoneFmt); ?></a>
        </div>
      </div>
      <ul class="ab-hero__facts" aria-label="Rock'n G Plumbing at a glance">
        <li><?php echo icon('users', 28); ?><span><b>3 generations</b>of plumbers</span></li>
        <li><?php echo icon('award', 28); ?><span><b><?php echo (int) $yearsInBusiness; ?>+ years</b>of experience</span></li>
        <li><?php echo icon('badge-check', 28); ?><span><b>M-44822</b>Texas Master Plumber License</span></li>
      </ul>
    </div>
  </div>
</section>

<article aria-labelledby="ab-h1">

  <!-- ============ STORY ============ -->
  <section class="section ab-story" aria-labelledby="ab-story-title">
    <div class="container">
      <div class="ab-story__grid">
        <figure class="ab-frame reveal-left">
          <?php echo pictureTag('rockn-crew-excavation', "Rock'n G Plumbing crew member standing in an excavated trench beside a buried line while other workers look on", '(max-width: 900px) 90vw, 440px'); ?>
          <figcaption>A Rock'n G crew member in the trench on a job to reach a buried line.</figcaption>
        </figure>
        <div class="ab-story__copy reveal-right">
          <span class="eyebrow">Our story</span>
          <h2 id="ab-story-title">Who is behind Rock'n G Plumbing in San Angelo?</h2>
          <p class="answer-block">Rock'n G Plumbing is run by Eli Gomez, a Texas Responsible Master Plumber from a family with three generations in the trade. The company brings 20+ years of plumbing experience to homes, businesses and industrial sites in San Angelo and Christoval.</p>
          <p>Plumbing is the family trade, and Rock'n G Plumbing is how the Gomez family does it in San Angelo. Eli Gomez holds the Texas Master Plumber License M-44822 that every Rock'n G job runs under, so the person whose name is on the license is the person responsible for the work.</p>
          <p>The company takes residential, commercial and industrial jobs, and the work covers the whole system: <a href="/services/water-heaters/">water heaters</a>, <a href="/services/water-filtration-softeners/">water softeners and filtration</a>, <a href="/services/drain-cleaning/">drain cleaning</a>, <a href="/services/sewer-line-repair/">sewer line repair and camera inspection</a>, <a href="/services/leak-detection-repair/">leak detection and slab leak repair</a>, <a href="/services/gas-line-services/">gas lines</a>, <a href="/services/irrigation-sprinkler-repair/">sprinkler and irrigation repair</a>, and <a href="/services/new-construction-remodel-plumbing/">new construction and remodel plumbing</a>.</p>
          <p>When a repair means opening the ground, you can see what that looks like in the job photos on this site: a crew member down in the trench with the line exposed. Every job starts the same way, with an estimate at no charge, so you know what the work involves before you decide.</p>
          <p>Rock'n G Plumbing serves San Angelo and Christoval, and also takes jobs in <?php echo h($townList); ?>. If you have served, mention it when you call, because the company offers a military discount.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- ============ MID-PAGE LEAD FORM ============ -->
  <section class="section cta-band ab-band" aria-labelledby="ab-band-title">
    <div class="container">
      <div class="ab-band__grid">
        <div class="ab-band__copy reveal-left">
          <span class="eyebrow">Talk to the family</span>
          <h2 id="ab-band-title">Have a plumbing problem in San Angelo or Christoval?</h2>
          <p>Tell Rock'n G Plumbing what the plumbing is doing and which service fits. The office follows up during business hours, <?php echo h($weekday[0]); ?> <?php echo h($weekday[1]); ?>, or you can call <a href="<?php echo h($telHref); ?>"><?php echo h($phoneFmt); ?></a> now.</p>
        </div>
        <form class="ab-band__form reveal-right" action="<?php echo h($formAction); ?>" method="POST">
          <input type="text" name="_honey" style="display:none !important" tabindex="-1" autocomplete="off" aria-hidden="true">
          <input type="hidden" name="_next" value="<?php echo htmlspecialchars($siteUrl); ?>/thank-you">
          <input type="hidden" name="form_location" value="about-band">
          <input type="hidden" name="consent_version" value="v2.1">
          <input type="hidden" name="consent_page" value="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>">
          <input type="hidden" name="_consent_version" value="v2.1">
          <input type="hidden" name="_consent_page" value="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>">
          <?php echo p1_attribution_fields('about-band'); ?>
          <div class="form-row"><label class="field"><span>Your name</span><input type="text" name="name" autocomplete="name" required></label></div>
          <div class="form-row"><label class="field"><span>Phone</span><input type="tel" name="phone" autocomplete="tel" inputmode="tel" required></label></div>
          <div class="form-row"><label class="field"><span>Email</span><input type="email" name="email" autocomplete="email" required></label></div>
          <div class="form-row"><label class="field"><span>What do you need?</span><select name="service"><?php echo p1_service_options(); ?></select></label></div>
          <label class="consent" for="about-band-email-opt"><input id="about-band-email-opt" type="checkbox" name="email_opt_in" value="yes"><span><strong>Email updates (optional):</strong> I agree to receive emails from <?php echo h($siteName); ?>. I can unsubscribe at any time.</span></label>
          <label class="consent" for="about-band-sms-opt"><input id="about-band-sms-opt" type="checkbox" name="sms_opt_in" value="yes"><span><strong>Text messages (optional):</strong> I agree to receive text messages from <?php echo h($siteName); ?> about my request. Consent is not a condition of purchase. Message and data rates may apply. Reply STOP to unsubscribe, HELP for help.</span></label>
          <?php echo p1_consent_row('about-band'); ?>
          <button type="submit" class="btn btn-primary btn-lg btn-block">Request My Free Estimate</button>
        </form>
      </div>
    </div>
  </section>

  <!-- ============ PRINCIPLES (team + values) ============ -->
  <section class="section slant-top ab-principles" aria-labelledby="ab-principles-title">
    <div class="container">
      <div class="section-head reveal-up">
        <span class="eyebrow">How we work</span>
        <h2 id="ab-principles-title">What can you count on when Rock'n G Plumbing comes to your property?</h2>
        <p class="answer-block">You can count on four things: a Texas master plumber's license behind the work, an up-front estimate before anything starts, experience with homes, businesses and industrial sites, and a military discount for those who served.</p>
      </div>
      <div class="ab-principles__grid">
        <?php foreach ($abPrinciples as $i => $abP):
          $tint = [1, 3, 2, 'neutral'][$i];
          $dir  = ['reveal-left', 'reveal-right', 'reveal-left', 'reveal-right'][$i]; ?>
        <div class="ab-card card-tint-<?php echo $tint; ?> <?php echo $dir; ?>">
          <span class="ab-card__icon"><?php echo icon($abP['icon'], 24); ?></span>
          <h3><?php echo h($abP['title']); ?></h3>
          <p><?php echo h($abP['body']); ?></p>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- ============ MILESTONES ============ -->
  <section class="section ab-miles" aria-labelledby="ab-miles-title">
    <div class="container">
      <div class="ab-miles__grid">
        <div class="ab-miles__intro reveal-up">
          <span class="eyebrow">On the record</span>
          <h2 id="ab-miles-title">How long has the Gomez family been plumbing in San Angelo?</h2>
          <p>Rock'n G Plumbing is three generations of plumbers with 20+ years of experience, and Eli Gomez's master license is on record with the State of Texas. These are the milestones the company can back up.</p>
        </div>
        <ol class="timeline">
          <?php foreach ($abMilestones as $i => $abM): ?>
          <li class="<?php echo $i % 2 ? 'reveal-right' : 'reveal-left'; ?>">
            <b><?php echo h($abM['label']); ?></b>
            <h3><?php echo h($abM['title']); ?></h3>
            <p><?php echo h($abM['body']); ?></p>
          </li>
          <?php endforeach; ?>
        </ol>
      </div>
    </div>
  </section>

  <!-- ============ REAL JOB PHOTOS ============ -->
  <section class="section ab-jobs" aria-labelledby="ab-jobs-title">
    <div class="container">
      <div class="section-head reveal-up">
        <span class="eyebrow">Recent work</span>
        <h2 id="ab-jobs-title">What does Rock'n G Plumbing work look like?</h2>
        <p>These are Rock'n G Plumbing's own job photos, not stock images: a softener and filter install, gas piping run to a standby generator, and the plumbing in a finished kitchen remodel. Each photo links to the service it shows.</p>
      </div>
      <div class="ab-jobs__row">
        <?php foreach ($abJobs as $i => $abJ):
          $abSvc = serviceBySlug($abJ['slug']); ?>
        <figure class="ab-job <?php echo ['reveal-left', 'reveal-up', 'reveal-right'][$i]; ?>">
          <?php echo pictureTag($abJ['photo'], $abJ['alt'], '(max-width: 900px) calc(100vw - 2rem), 380px'); ?>
          <figcaption><?php echo h($abJ['cap']); ?><a href="<?php echo h(serviceUrl($abSvc)); ?>"><?php echo h($abSvc['name']); ?> &rarr;</a></figcaption>
        </figure>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- ============ CERTIFICATIONS AND CREDENTIALS ============ -->
  <section class="section on-dark edge-wave-top ab-creds" aria-labelledby="ab-creds-title">
    <span class="grain-layer" aria-hidden="true"></span>
    <div class="container">
      <div class="section-head reveal-up">
        <span class="eyebrow">Credentials</span>
        <h2 id="ab-creds-title">How can you check Rock'n G Plumbing before you call?</h2>
        <p class="answer-block">You can check Rock'n G Plumbing two ways: look up Texas Master Plumber License M-44822 with the Texas State Board of Plumbing Examiners, and read the company's Google reviews. Rock'n G is rated <?php echo h(number_format((float) $gbp['rating'], 1)); ?> out of 5 from <?php echo (int) $gbp['reviews_count']; ?> Google reviews.</p>
      </div>
      <div class="ab-creds__grid">
        <div class="ab-creds__card reveal-left">
          <h3>License on record</h3>
          <dl>
            <dt>License</dt><dd><?php echo h($license['label']); ?></dd>
            <dt>Holder</dt><dd><?php echo h($license['holder']); ?></dd>
            <dt>Role</dt><dd><?php echo h($license['role']); ?></dd>
            <dt>Issued by</dt><dd><?php echo h($license['issuer']); ?></dd>
            <dt>Current through</dt><dd><?php echo h($licenseExpires); ?></dd>
          </dl>
        </div>
        <div class="ab-creds__card reveal-right">
          <h3>Google reviews</h3>
          <p>Reviews on Rock'n G Plumbing's Google Business Profile are written by customers, not by us. If the crew has worked on your plumbing, your review helps the next San Angelo homeowner pick a plumber.</p>
          <div class="actions">
            <a class="btn btn-accent" href="<?php echo h($gbp['profile_url']); ?>" target="_blank" rel="noopener">Read reviews on Google</a>
            <a class="btn btn-outline-white" href="<?php echo h($gbp['review_url']); ?>" target="_blank" rel="noopener">Leave a review</a>
          </div>
        </div>
      </div>
      <?php
      require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/google-reviews.php';
      $abReviews = p1_google_reviews($slug, ['limit' => 3, 'heading' => '', 'summary' => false]);
      if ($abReviews !== ''): ?>
      <div class="ab-creds__reviews"><?php echo $abReviews; ?></div>
      <?php endif; ?>
    </div>
  </section>

  <!-- ============ FINAL CTA ============ -->
  <section class="closing-cta texture-grain edge-torn-top ab-cta" aria-labelledby="ab-cta-title">
    <span class="grain-layer" aria-hidden="true"></span>
    <span class="ab-cta__ring" aria-hidden="true"></span>
    <div class="container">
      <div class="reveal-left">
        <span class="eyebrow">No charge to look</span>
        <h2 id="ab-cta-title">Ready to meet your San Angelo plumber?</h2>
        <p>Call Rock'n G Plumbing at <?php echo h($phoneFmt); ?>, <?php echo h($weekday[0]); ?> <?php echo h($weekday[1]); ?>, or send the estimate form and the office will follow up during business hours. Military discounts are available.</p>
      </div>
      <div class="actions reveal-right">
        <a class="btn btn-accent btn-lg" href="/contact/" data-open-estimate>Get a Free Estimate</a>
        <a class="link-call" href="<?php echo h($telHref); ?>"><?php echo icon('phone', 18); ?>Call <?php echo h($phoneFmt); ?></a>
      </div>
    </div>
  </section>

</article>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>
