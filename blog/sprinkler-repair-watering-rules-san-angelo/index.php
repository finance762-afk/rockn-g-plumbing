<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/functions.php';
?>
<?php
/**
 * blog/sprinkler-repair-watering-rules-san-angelo/index.php — migrated post #4 (Oct 18 2026 blog migration, P1 BLOG BOT drafts).
 * 301 from /irrigation-system-installation-and-repair-near-paint-rock-tx-properties (re-targeted to San Angelo).
 * Body pasted from the approved draft; only service hrefs were remapped to /services/<slug>/.
 * No FAQPage schema (Rock'n G SEO brief). Author = Page One Insights LLC (blog-standard.md §7).
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/blog-data.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/service-cards.php';

$bpSlug = 'sprinkler-repair-watering-rules-san-angelo';
$post   = blogPostBySlug($bpSlug);

$pageTitle       = $post['title'];
$pageDescription = "Broken heads, stuck valves, soggy spots and high bills: common sprinkler fixes in San Angelo, plus the City's watering days, hours and permit rules.";
$canonicalUrl    = $siteUrl . '/blog/' . $bpSlug . '/';
$currentPage     = 'blog/' . $bpSlug;
$ogType          = 'article';
$ogImage         = '/assets/images/' . $post['photo'] . '.jpg';
$breadcrumbs     = [['name' => 'Blog', 'url' => '/blog/'], ['name' => $post['title'], 'url' => '/blog/' . $bpSlug . '/']];

$telHref  = 'tel:' . $phoneE164;
$phoneFmt = formatPhone($phone);
$weekday  = hoursGroups($hours)[0];

$toc = [
    'the-city-s-watering-rules-in-plain-terms'      => "The City's watering rules, in plain terms",
    'problems-we-see-most-often'                    => 'Problems we see most often',
    'watering-smarter-under-a-twice-a-week-limit'   => 'Watering smarter under a twice-a-week limit',
    'repairs-need-a-permit-in-san-angelo'           => 'Repairs need a permit in San Angelo',
    'before-you-call-check-these'                   => 'Before you call, check these',
    'when-to-call'                                  => 'When to call',
    'frequently-asked-questions'                    => 'Frequently asked questions',
];

$schemaMarkup = [[
    "@context" => 'https://schema.org', '@type' => 'BlogPosting',
    '@id' => $canonicalUrl . '#post',
    'headline' => $post['title'],
    'description' => $pageDescription,
    'image' => absUrl($post['image']),
    'datePublished' => $post['dateISO'], 'dateModified' => $post['dateISO'],
    'keywords' => 'sprinkler repair San Angelo, irrigation repair, sprinkler leak, San Angelo watering schedule',
    'inLanguage' => 'en-US',
    'author' => ['@type' => 'Organization', 'name' => 'Page One Insights LLC', 'url' => 'https://pageoneinsights.com'],
    'publisher' => ['@id' => orgId()],
    'mainEntityOfPage' => $canonicalUrl,
    'about' => ['@type' => 'Service', 'name' => 'Sprinkler & Irrigation Repair', 'url' => absUrl('/services/irrigation-sprinkler-repair/'), 'provider' => ['@id' => orgId()]],
]];

ob_start();
?>
<style>
/* =====================================================================
   Post: sprinkler repair + watering rules — page-only CSS (sp- prefix)
   Tokens from framework.css; no raw colors, shadows or spacing.
   Techniques: layered type hero (gradient + noise), sticky TOC rail, numbered step track (signature),
   rules list as tinted chips, FAQ question cards, tinted source list, related band.
   ===================================================================== */
.sp-hero {
  padding-block-end: var(--space-12);
  background:
    radial-gradient(ellipse 60% 80% at 100% 100%, color-mix(in srgb, var(--color-success) 24%, transparent), transparent 62%),
    linear-gradient(165deg, var(--color-primary-deep), var(--color-dark));
}
.sp-hero::before {
  content: "";
  position: absolute;
  inset: auto 0 0 0;
  height: var(--space-2);
  pointer-events: none;
  background: linear-gradient(90deg, var(--color-success), var(--color-accent), transparent);
  opacity: .7;
}
.sp-hero::after {
  content: "";
  position: absolute;
  inset: 0;
  pointer-events: none;
  opacity: .16;
  mix-blend-mode: overlay;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='160' height='160'%3E%3Cfilter id='g'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.8' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23g)'/%3E%3C/svg%3E");
}
.sp-hero .container {
  position: relative;
  z-index: 1;
}
.sp-hero .breadcrumb {
  color: color-mix(in srgb, var(--color-surface) 72%, transparent);
}
.sp-hero .breadcrumb li {
  display: inline-flex;
  align-items: center;
}
.sp-hero .breadcrumb [aria-current="page"] {
  color: var(--color-surface);
}
.sp-hero .hero-title {
  max-width: 24ch;
  text-wrap: balance;
}
.sp-meta {
  display: flex;
  flex-wrap: wrap;
  gap: var(--space-2) var(--space-5);
  margin: var(--space-4) 0 0;
  padding: 0;
  list-style: none;
  font-size: var(--fs-small);
  color: color-mix(in srgb, var(--color-surface) 78%, transparent);
}
.sp-meta li {
  display: inline-flex;
  gap: var(--space-2);
  align-items: center;
}
.sp-meta svg {
  color: var(--color-accent-bright);
}

/* ---------- Layout: article + rail ---------- */
.sp-layout {
  display: grid;
  grid-template-columns: minmax(0, 1fr) 300px;
  gap: clamp(var(--space-8), 5vw, var(--space-16));
  align-items: start;
}
.sp-featured {
  margin: 0 0 var(--space-8);
  box-shadow: var(--shadow);
}
.sp-featured img {
  width: 100%;
  aspect-ratio: 16 / 9;
  object-fit: cover;
}
.sp-rail {
  position: sticky;
  inset-block-start: calc(var(--nav-height) + var(--space-4));
  display: grid;
  gap: var(--space-5);
}
.sp-toc,
.sp-sidecta {
  padding: var(--space-5);
  border-radius: var(--radius-lg);
  border: 1px solid var(--color-line);
}
.sp-toc {
  background: var(--color-card-tint-2);
}
.sp-toc h2 {
  font-size: 1rem;
  margin-block-end: var(--space-3);
}
.sp-toc ol {
  display: grid;
  gap: var(--space-1);
  margin: 0;
  padding: 0;
  list-style: none;
  counter-reset: sp-toc;
}
.sp-toc li {
  counter-increment: sp-toc;
}
.sp-toc a {
  display: flex;
  gap: var(--space-3);
  align-items: baseline;
  padding: var(--space-1) 0;
  font-size: var(--fs-small);
  text-decoration: none;
  color: var(--color-ink-2);
  border-inline-start: 2px solid transparent;
  transition: color var(--transition), border-color var(--transition), padding var(--transition);
}
.sp-toc a::before {
  content: counter(sp-toc, decimal-leading-zero);
  font-family: var(--font-accent);
  letter-spacing: .08em;
  color: var(--color-accent-dark);
}
.sp-toc a:hover {
  color: var(--color-primary);
  border-color: var(--color-accent);
  padding-inline-start: var(--space-2);
}
.sp-sidecta {
  background: var(--color-primary);
  color: var(--color-surface);
  box-shadow: var(--shadow);
}
.sp-sidecta h2 {
  color: var(--color-surface);
  font-size: 1.1rem;
  margin-block-end: var(--space-2);
}
.sp-sidecta p {
  margin: 0 0 var(--space-3);
  font-size: var(--fs-small);
  color: color-mix(in srgb, var(--color-surface) 84%, transparent);
}
.sp-sidecta .btn {
  width: 100%;
  justify-content: center;
}
.sp-sidecta .link-call {
  margin-block-start: var(--space-3);
  color: var(--color-surface);
}
.sp-sidecta .link-call svg {
  color: var(--color-accent-bright);
}

/* ---------- Body ---------- */
.sp-body > p:first-child {
  font-size: var(--fs-lead);
  padding-inline-start: var(--space-5);
  border-inline-start: 4px solid var(--color-success);
}
.sp-body h2 {
  margin-block: var(--space-12) var(--space-3);
  text-wrap: balance;
  scroll-margin-block-start: calc(var(--nav-height) + var(--space-4));
}
.sp-body h3 {
  text-wrap: balance;
}
.sp-body p a,
.sp-body li a {
  color: var(--color-primary);
  text-decoration-color: var(--color-accent);
  text-underline-offset: 3px;
}
.sp-body ul {
  display: grid;
  gap: var(--space-2);
  margin: var(--space-4) 0;
  padding: 0;
  list-style: none;
}
.sp-body ul > li {
  position: relative;
  padding-inline-start: var(--space-6);
}
.sp-body ul > li::before {
  content: "";
  position: absolute;
  inset-block-start: .6em;
  inset-inline-start: var(--space-1);
  width: var(--space-2);
  height: var(--space-3);
  border-radius: var(--radius-full) var(--radius-full) var(--radius-full) 0;
  background: var(--color-success);
}
/* the City's watering rules: tinted chips */
.sp-body #the-city-s-watering-rules-in-plain-terms + p + ul > li {
  padding: var(--space-3) var(--space-4) var(--space-3) var(--space-8);
  border-radius: var(--radius);
  background: var(--color-card-tint-2);
  border: 1px solid var(--color-line);
}
.sp-body #the-city-s-watering-rules-in-plain-terms + p + ul > li::before {
  inset-block-start: 1.15em;
  inset-inline-start: var(--space-3);
}

/* ---------- Signature: numbered step track ---------- */
.sp-body ol {
  counter-reset: sp-step;
  display: grid;
  gap: var(--space-3);
  margin: var(--space-5) 0;
  padding: 0;
  list-style: none;
}
.sp-body ol > li {
  counter-increment: sp-step;
  position: relative;
  padding: var(--space-4) var(--space-5) var(--space-4) var(--space-16);
  border-radius: var(--radius-lg);
  background: var(--color-card-tint-1);
  border: 1px solid var(--color-line);
  transition: transform var(--transition), box-shadow var(--transition);
}
.sp-body ol > li:nth-child(3n + 2) {
  background: var(--color-card-tint-2);
}
.sp-body ol > li:nth-child(3n) {
  background: var(--color-card-tint-3);
}
.sp-body ol > li:hover {
  transform: translateX(var(--space-1));
  box-shadow: var(--shadow);
}
.sp-body ol > li::before {
  content: counter(sp-step);
  position: absolute;
  inset-block-start: var(--space-3);
  inset-inline-start: var(--space-4);
  font-family: var(--font-accent);
  font-size: 2.2rem;
  line-height: 1;
  color: var(--color-primary);
}

/* ---------- FAQ: question cards ---------- */
.sp-body #frequently-asked-questions ~ h3 {
  margin-block: var(--space-5) 0;
  padding: var(--space-4) var(--space-5) var(--space-2);
  border-radius: var(--radius-lg) var(--radius-lg) 0 0;
  background: var(--color-paper-2);
  border: 1px solid var(--color-line);
  border-block-end: 0;
  font-size: 1.1rem;
}
.sp-body #frequently-asked-questions ~ h3 + p {
  margin: 0;
  padding: 0 var(--space-5) var(--space-4);
  border-radius: 0 0 var(--radius-lg) var(--radius-lg);
  background: var(--color-paper-2);
  border: 1px solid var(--color-line);
  border-block-start: 0;
}

/* ---------- Owner line + sources ---------- */
.sp-body p > em:only-child {
  display: inline-block;
  padding: var(--space-2) var(--space-4);
  border-radius: var(--radius-full);
  background: var(--color-card-tint-neutral);
  font-size: var(--fs-small);
}
.sp-body #sources {
  font-size: 1.1rem;
}
.sp-body #sources + ul {
  padding: var(--space-4) var(--space-5);
  border-radius: var(--radius);
  background: var(--color-paper-2);
  font-size: var(--fs-small);
  color: var(--color-ink-2);
}
.sp-body #sources + ul a {
  word-break: break-word;
}

/* ---------- Related ---------- */
.sp-related {
  margin-block-start: var(--space-16);
  padding-block-start: var(--space-8);
  border-block-start: 1px solid var(--color-line);
}
.sp-related h2 {
  margin-block-end: var(--space-4);
  text-wrap: balance;
}
.sp-related .service-links {
  display: grid;
  gap: var(--space-2);
  margin: 0 0 var(--space-8);
  padding: 0;
  list-style: none;
}
.sp-related .service-links a {
  display: flex;
  justify-content: space-between;
  gap: var(--space-4);
  padding: var(--space-3) var(--space-5);
  border-radius: var(--radius);
  border: 1px solid var(--color-line);
  background: var(--color-surface);
  text-decoration: none;
  font-weight: 600;
  transition: border-color var(--transition), transform var(--transition);
}
.sp-related .service-links a:hover {
  border-color: var(--color-primary);
  transform: translateX(var(--space-1));
}

@media (max-width: 1000px) {
  .sp-layout {
    grid-template-columns: 1fr;
  }
  .sp-rail {
    position: static;
    order: -1;
    grid-template-columns: 1fr 1fr;
  }
}
@media (max-width: 640px) {
  .sp-rail {
    grid-template-columns: 1fr;
  }
  .sp-body ol > li {
    padding-inline-start: var(--space-12);
  }
}
@media (prefers-reduced-motion: reduce) {
  .sp-body ol > li,
  .sp-toc a,
  .sp-related .service-links a {
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
<section class="hero hero--interior sp-hero" aria-labelledby="sp-h1">
  <div class="container">
    <nav class="breadcrumb" aria-label="Breadcrumb">
      <ol>
        <li><a href="/">Home</a></li>
        <li class="breadcrumb-sep" aria-hidden="true">/</li>
        <li><a href="/blog/">Blog</a></li>
        <li class="breadcrumb-sep" aria-hidden="true">/</li>
        <li><span aria-current="page"><?php echo h($post['title']); ?></span></li>
      </ol>
    </nav>
    <span class="eyebrow"><?php echo h($post['category']); ?></span>
    <h1 class="hero-title" id="sp-h1"><?php echo h($post['title']); ?></h1>
    <ul class="sp-meta">
      <li><?php echo icon('calendar', 16); ?><time datetime="<?php echo h($post['dateISO']); ?>"><?php echo h($post['date']); ?></time></li>
      <li><?php echo icon('clock', 16); ?><?php echo h($post['readtime']); ?></li>
      <li><?php echo icon('info', 16); ?>Published by Page One Insights LLC</li>
    </ul>
  </div>
</section>

<div class="section sp-wrap">
  <div class="container-wide sp-layout">

    <article class="sp-article" aria-labelledby="sp-h1">
      <figure class="sp-featured blog-post__featured-image">
        <?php echo pictureTag($post['photo'], $post['alt'], '(max-width: 1000px) 100vw, 780px', ['loading' => 'eager', 'fetchpriority' => 'high']); ?>
      </figure>

      <div class="blog-post__content sp-body">
<p>Most sprinkler trouble in San Angelo comes down to broken heads, stuck valves, underground leaks, clogged nozzles or a controller on the wrong schedule. Fix them quickly. The City allows outdoor watering only twice a week from April through October, and never between noon and 6 p.m.</p>
<p>This post covers the problems we see most and the City rules your system has to work within.</p>
<h2 id="the-city-s-watering-rules-in-plain-terms">The City's watering rules, in plain terms</h2>
<p>San Angelo is currently in "standard conservation," the level that applies when the City has more than 24 months of water supply available. Under it:</p>
<ul>
<li><strong>April 1 to October 31:</strong> outdoor watering twice every seven days, and never between <strong>noon and 6 p.m.</strong></li>
<li><strong>November 1 to March 31:</strong> once every seven days.</li>
<li><strong>All year:</strong> no more than <strong>1 inch of water per week.</strong></li>
<li><strong>Drip irrigation</strong> (soaker hoses, drip line, bubblers, deep-root watering) can run any day, as long as you stay under 1 inch per week.</li>
<li><strong>Runoff</strong> that travels more than 150 feet down a street, gutter or alley is prohibited. So is failing to fix a controllable leak.</li>
</ul>
<p>Rules tighten when lake levels drop, so check the City's drought-level page if you haven't in a while. The point for your sprinklers: with only two days a week in summer, every zone has to work right on the days it runs. A broken head or a stuck valve can cost you half a week.</p>
<h2 id="problems-we-see-most-often">Problems we see most often</h2>
<p><strong>Broken or tilted heads.</strong> Mowers, trucks and foot traffic snap heads off or knock them crooked. A broken head can gush water into the street, which is exactly the runoff the City fines for. Tilted heads water the sidewalk and leave the lawn dry.</p>
<p><strong>Leaking or stuck valves.</strong> A worn diaphragm or debris in a valve can keep a zone from shutting off, or keep it from opening at all. If one zone runs when nothing should be running, or water seeps near the valve box all the time, the valve is the first suspect.</p>
<p><strong>Underground leaks.</strong> Signs: a spot that's always soggy, a sunken patch, unusually green grass in one strip, or a water bill that jumped without a reason. Clay soil can shift and stress buried lines, and the joints at fittings are the usual weak point.</p>
<p><strong>Clogged nozzles and drip emitters.</strong> San Angelo's water is very hard (the City lab measured 18.4 grains per gallon in July 2025). Mineral scale can build up in small openings like drip emitters and fine spray nozzles, cutting flow until plants downstream start struggling.</p>
<p><strong>Controller problems.</strong> A dead backup battery, a power blip or an outdated schedule can have the system running on the wrong days or at banned hours. It's worth checking the clock and the program at the start of each season, especially when the schedule switches on April 1 and November 1.</p>
<h2 id="watering-smarter-under-a-twice-a-week-limit">Watering smarter under a twice-a-week limit</h2>
<ul>
<li><strong>Water early.</strong> Before sunrise, less is lost to heat and wind, and you stay well clear of the noon-to-6 window.</li>
<li><strong>Water deeper, less often.</strong> Roots follow water. Deep soakings on your allowed days encourage deeper roots that handle heat better than shallow daily sprinkling.</li>
<li><strong>Cycle and soak.</strong> Clay soil takes water slowly. Splitting a zone into two or three shorter runs on the same morning lets water soak in instead of running off.</li>
<li><strong>Match the zone to the plants.</strong> Turf, shrubs and beds have different needs. Beds and trees are good candidates for drip, which the City allows on any day within the 1-inch limit.</li>
<li><strong>Consider a weather-aware controller.</strong> Controllers that skip watering after rain can cut waste. Savings vary by yard, so be wary of anyone promising a specific percentage.</li>
</ul>
<h2 id="repairs-need-a-permit-in-san-angelo">Repairs need a permit in San Angelo</h2>
<p>People are often surprised by this one. The City's Permits &amp; Inspections division says installing, replacing <strong>or repairing</strong> a sprinkler system requires a permit and a plumbing inspection. Some of the City's requirements:</p>
<ul>
<li>The pressure line has to be buried at least 12 inches deep (or an inspector-approved alternative).</li>
<li>Pipe under constant pressure must be Schedule 40 PVC, with pressure fittings.</li>
<li>A cutoff valve in a valve box goes as close to the water meter as practical.</li>
<li>An approved <strong>backflow prevention assembly</strong> is required, and it has to be tested after installation by a certified backflow prevention assembly tester.</li>
</ul>
<p>Homeowners who own, live in and have a homestead exemption on the home can pull a homeowner's permit and do the work themselves. Otherwise the City requires a licensed plumber or licensed irrigator, who handles the permit and inspection. Under Texas law, plumbers licensed by the Texas State Board of Plumbing Examiners are exempt from the separate irrigator license, but still have to follow the state's irrigation standards.</p>
<p>If you're wondering what that backflow assembly does and why the City cares so much, we wrote a separate explainer: <a href="/blog/what-is-backflow-san-angelo/">What is backflow, and why San Angelo requires protection on sprinklers</a>.</p>
<h2 id="before-you-call-check-these">Before you call, check these</h2>
<ol>
<li>Run each zone manually and walk the yard. Note which heads are broken, tilted or not popping up.</li>
<li>Look for water at the valve box when the system is off.</li>
<li>Check the controller's date, time and program.</li>
<li>Read your water meter at night with everything off, then again in the morning. If it moved, you may have a leak in the irrigation or the house. Our <a href="/services/leak-detection-repair/">leak detection</a> page and our post on <a href="/blog/slab-leaks-pipe-repair-san-angelo/">spotting a hidden leak</a> cover the next steps.</li>
</ol>
<h2 id="when-to-call">When to call</h2>
<p>If a zone won't shut off, a spot in the yard is always wet, or your bill jumped and you can't find why, give us a call. Rock'n G Plumbing handles sprinkler repair in San Angelo and Christoval, Monday through Friday, 8 AM to 6 PM, at <strong>(325) 650-7000</strong>. More on our <a href="/services/irrigation-sprinkler-repair/">sprinkler and irrigation repair</a> service.</p>
<p><em>Rock'n G Plumbing is owned by Eli Gomez, Texas Master Plumber License M-44822.</em></p>
<h2 id="frequently-asked-questions">Frequently asked questions</h2>
<h3>What are San Angelo's watering days?</h3>
<p>Under standard conservation: twice every seven days from April 1 to October 31 and once every seven days from November 1 to March 31. Always 1 inch per week or less, and never between noon and 6 p.m. in the summer months. Check the City's drought-level page for changes.</p>
<h3>Do I need a permit to repair my sprinkler system in San Angelo?</h3>
<p>Yes. The City's Permits &amp; Inspections division says installing, replacing or repairing a sprinkler system requires a permit and a plumbing inspection.</p>
<h3>Can a plumber repair a sprinkler system in Texas?</h3>
<p>Yes. Plumbers licensed by the Texas State Board of Plumbing Examiners are exempt from the separate irrigator license but must follow the state's irrigation standards, and San Angelo accepts a licensed plumber or licensed irrigator for sprinkler permits.</p>
<h3>How can I tell if my sprinkler system is leaking?</h3>
<p>Look for a spot that's always soggy, a sunken patch, an extra-green strip, water at the valve box when the system is off, or a water meter that moves overnight with everything off.</p>
<h2 id="sources">Sources</h2>
<ul>
<li>City of San Angelo, Water Conservation (standard conservation rules): <a rel="nofollow noopener" target="_blank" href="https://www.sanangelo.gov/501/Water-Conservation">https://www.sanangelo.gov/501/Water-Conservation</a></li>
<li>City of San Angelo, Drought Levels: <a rel="nofollow noopener" target="_blank" href="https://www.sanangelo.gov/520/Drought-Levels">https://www.sanangelo.gov/520/Drought-Levels</a></li>
<li>City of San Angelo Ordinance 2024-079, Article 11.05 Water Conservation and Drought Contingency Plan: <a rel="nofollow noopener" target="_blank" href="https://mcclibraryfunctions-stage.azurewebsites.us/api/ordinanceDownload/15660/1337963/pdf?forceDownload=true">https://mcclibraryfunctions-stage.azurewebsites.us/api/ordinanceDownload/15660/1337963/pdf?forceDownload=true</a></li>
<li>City of San Angelo Permits &amp; Inspections, Sprinkler System Installation handout (permit for install, replace or repair; 12-inch depth; Schedule 40 PVC; backflow assembly and test; homeowner permit criteria): <a rel="nofollow noopener" target="_blank" href="https://www.sanangelo.gov/DocumentCenter/View/209">https://www.sanangelo.gov/DocumentCenter/View/209</a></li>
<li>Texas Occupations Code §1903.002 (TSBPE-licensed plumbers exempt from irrigator licensing; must follow standards): <a rel="nofollow noopener" target="_blank" href="https://statutes.capitol.texas.gov/Docs/OC/htm/OC.1903.htm">https://statutes.capitol.texas.gov/Docs/OC/htm/OC.1903.htm</a></li>
<li>City of San Angelo Water Quality Laboratory, July 2025 tap sample (18.4 gr/gal): <a rel="nofollow noopener" target="_blank" href="https://www.sanangelo.gov/Archive.aspx?ADID=231">https://www.sanangelo.gov/Archive.aspx?ADID=231</a></li>
</ul>
      </div>

      <div class="sp-related blog-post__related-services">
        <h2>Which Rock'n G Plumbing services go with this guide?</h2>
        <ul class="service-links">
          <li><a href="/services/irrigation-sprinkler-repair/">Sprinkler &amp; Irrigation Repair <span aria-hidden="true">→</span></a></li>
          <li><a href="/services/leak-detection-repair/">Leak Detection &amp; Slab Leak Repair <span aria-hidden="true">→</span></a></li>
          <li><a href="/contact/">Contact Rock'n G Plumbing <span aria-hidden="true">→</span></a></li>
        </ul>

        <div class="blog-post__related-articles">
          <h2>What else should you read before calling a plumber?</h2>
          <p>The other Rock'n G Plumbing guides cover plumbing questions for San Angelo homes, starting with the newest below.</p>
          <div class="blog-grid">
            <?php foreach (relatedBlogPosts($bpSlug, 3) as $rel) echo renderBlogCard($rel); ?>
          </div>
        </div>
      </div>
    </article>

    <aside class="sp-rail" aria-label="Guide navigation and contact">
      <nav class="sp-toc" aria-labelledby="sp-toc-title">
        <h2 id="sp-toc-title">In this guide</h2>
        <ol>
          <?php foreach ($toc as $anchor => $label): ?><li><a href="#<?php echo h($anchor); ?>"><?php echo h($label); ?></a></li><?php endforeach; ?>
        </ol>
      </nav>
      <div class="sp-sidecta">
        <h2>Free estimate</h2>
        <p><?php echo h($weekday[0]); ?>, <?php echo h($weekday[1]); ?>. Texas Master Plumber <?php echo h($license['number']); ?>.</p>
        <a class="btn btn-accent" href="/contact/" data-open-estimate>Get a Free Estimate</a>
        <a class="link-call" href="<?php echo h($telHref); ?>"><?php echo icon('phone', 18); ?><?php echo h($phoneFmt); ?></a>
      </div>
    </aside>

  </div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>
