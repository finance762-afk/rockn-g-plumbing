<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/functions.php';
?>
<?php
/**
 * blog/new-construction-vs-remodel-plumbing-in-san-angelo-tx-what-s-different-and-why-it-matters/index.php — migrated
 * post #5 (Oct 18 2026 blog migration, P1 BLOG BOT drafts). Old root slug kept under /blog/; the root URL 301s here.
 * Body pasted from the approved draft; only service hrefs were remapped to /services/<slug>/.
 * No FAQPage schema (Rock'n G SEO brief). Author = Page One Insights LLC (blog-standard.md §7).
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/blog-data.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/service-cards.php';

$bpSlug = 'new-construction-vs-remodel-plumbing-in-san-angelo-tx-what-s-different-and-why-it-matters';
$post   = blogPostBySlug($bpSlug);

$pageTitle       = $post['title'];
$pageDescription = 'Building new or remodeling a slab home in San Angelo? How plumbing phases, permits, inspections, gas lines and hard water differ between the two jobs.';
$canonicalUrl    = $siteUrl . '/blog/' . $bpSlug . '/';
$currentPage     = 'blog/' . $bpSlug;
$ogType          = 'article';
$ogImage         = '/assets/images/' . $post['photo'] . '.jpg';
$breadcrumbs     = [['name' => 'Blog', 'url' => '/blog/'], ['name' => $post['title'], 'url' => '/blog/' . $bpSlug . '/']];

$telHref  = 'tel:' . $phoneE164;
$phoneFmt = formatPhone($phone);
$weekday  = hoursGroups($hours)[0];

$toc = [
    'new-build-blank-slate-remodel-detective-work-first' => 'New build: blank slate. Remodel: detective work first.',
    'how-new-construction-plumbing-is-sequenced'         => 'How new construction plumbing is sequenced',
    'common-plumbing-changes-in-kitchen-and-bath-remodels' => 'Common plumbing changes in kitchen and bath remodels',
    'permits-and-inspections-in-san-angelo'              => 'Permits and inspections in San Angelo',
    'gas-lines-new-build-vs-remodel'                     => 'Gas lines: new build vs. remodel',
    'planning-for-san-angelo-s-hard-water'               => "Planning for San Angelo's hard water",
    'when-to-bring-a-plumber-in'                         => 'When to bring a plumber in',
    'talk-to-us-early'                                   => 'Talk to us early',
    'frequently-asked-questions'                         => 'Frequently asked questions',
];

$schemaMarkup = [[
    "@context" => 'https://schema.org', '@type' => 'BlogPosting',
    '@id' => $canonicalUrl . '#post',
    'headline' => $post['title'],
    'description' => $pageDescription,
    'image' => absUrl($post['image']),
    'datePublished' => $post['dateISO'], 'dateModified' => $post['dateISO'],
    'keywords' => 'new construction plumbing San Angelo, remodel plumbing, plumbing rough-in, slab plumbing',
    'inLanguage' => 'en-US',
    'author' => ['@type' => 'Organization', 'name' => 'Page One Insights LLC', 'url' => 'https://pageoneinsights.com'],
    'publisher' => ['@id' => orgId()],
    'mainEntityOfPage' => $canonicalUrl,
    'about' => ['@type' => 'Service', 'name' => 'New Construction & Remodel Plumbing', 'url' => absUrl('/services/new-construction-remodel-plumbing/'), 'provider' => ['@id' => orgId()]],
]];

ob_start();
?>
<style>
/* =====================================================================
   Post: new construction vs. remodel — page-only CSS (nc- prefix)
   Tokens from framework.css; no raw colors, shadows or spacing.
   Techniques: layered type hero (gradient + noise), sticky TOC rail, phase-card strip for the three build phases
   (signature), FAQ question cards, tinted source list, related band.
   ===================================================================== */
.nc-hero {
  padding-block-end: var(--space-12);
  background:
    radial-gradient(ellipse 55% 90% at 100% 0%, color-mix(in srgb, var(--color-accent) 22%, transparent), transparent 62%),
    radial-gradient(ellipse 40% 60% at 0% 100%, color-mix(in srgb, var(--color-secondary) 14%, transparent), transparent 70%),
    linear-gradient(165deg, var(--color-primary-deep), var(--color-dark));
}
.nc-hero::before {
  content: "";
  position: absolute;
  inset: auto 0 0 0;
  height: var(--space-2);
  pointer-events: none;
  background: linear-gradient(90deg, var(--color-secondary), var(--color-accent), transparent);
  opacity: .7;
}
.nc-hero::after {
  content: "";
  position: absolute;
  inset: 0;
  pointer-events: none;
  opacity: .16;
  mix-blend-mode: overlay;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='160' height='160'%3E%3Cfilter id='g'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.8' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23g)'/%3E%3C/svg%3E");
}
.nc-hero .container {
  position: relative;
  z-index: 1;
}
.nc-hero .breadcrumb {
  color: color-mix(in srgb, var(--color-surface) 72%, transparent);
}
.nc-hero .breadcrumb li {
  display: inline-flex;
  align-items: center;
}
.nc-hero .breadcrumb [aria-current="page"] {
  color: var(--color-surface);
}
.nc-hero .hero-title {
  max-width: 24ch;
  text-wrap: balance;
}
.nc-meta {
  display: flex;
  flex-wrap: wrap;
  gap: var(--space-2) var(--space-5);
  margin: var(--space-4) 0 0;
  padding: 0;
  list-style: none;
  font-size: var(--fs-small);
  color: color-mix(in srgb, var(--color-surface) 78%, transparent);
}
.nc-meta li {
  display: inline-flex;
  gap: var(--space-2);
  align-items: center;
}
.nc-meta svg {
  color: var(--color-accent-bright);
}

/* ---------- Layout: article + rail ---------- */
.nc-layout {
  display: grid;
  grid-template-columns: minmax(0, 1fr) 300px;
  gap: clamp(var(--space-8), 5vw, var(--space-16));
  align-items: start;
}
.nc-featured {
  margin: 0 0 var(--space-8);
  box-shadow: var(--shadow);
}
.nc-featured img {
  width: 100%;
  aspect-ratio: 16 / 9;
  object-fit: cover;
}
.nc-rail {
  position: sticky;
  inset-block-start: calc(var(--nav-height) + var(--space-4));
  display: grid;
  gap: var(--space-5);
}
.nc-toc,
.nc-sidecta {
  padding: var(--space-5);
  border-radius: var(--radius-lg);
  border: 1px solid var(--color-line);
}
.nc-toc {
  background: var(--color-card-tint-1);
}
.nc-toc h2 {
  font-size: 1rem;
  margin-block-end: var(--space-3);
}
.nc-toc ol {
  display: grid;
  gap: var(--space-1);
  margin: 0;
  padding: 0;
  list-style: none;
  counter-reset: nc-toc;
}
.nc-toc li {
  counter-increment: nc-toc;
}
.nc-toc a {
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
.nc-toc a::before {
  content: counter(nc-toc, decimal-leading-zero);
  font-family: var(--font-accent);
  letter-spacing: .08em;
  color: var(--color-accent-dark);
}
.nc-toc a:hover {
  color: var(--color-primary);
  border-color: var(--color-accent);
  padding-inline-start: var(--space-2);
}
.nc-sidecta {
  background: var(--color-primary);
  color: var(--color-surface);
  box-shadow: var(--shadow);
}
.nc-sidecta h2 {
  color: var(--color-surface);
  font-size: 1.1rem;
  margin-block-end: var(--space-2);
}
.nc-sidecta p {
  margin: 0 0 var(--space-3);
  font-size: var(--fs-small);
  color: color-mix(in srgb, var(--color-surface) 84%, transparent);
}
.nc-sidecta .btn {
  width: 100%;
  justify-content: center;
}
.nc-sidecta .link-call {
  margin-block-start: var(--space-3);
  color: var(--color-surface);
}
.nc-sidecta .link-call svg {
  color: var(--color-accent-bright);
}

/* ---------- Body ---------- */
.nc-body > p:first-child {
  font-size: var(--fs-lead);
  padding-inline-start: var(--space-5);
  border-inline-start: 4px solid var(--color-accent);
}
.nc-body h2 {
  margin-block: var(--space-12) var(--space-3);
  text-wrap: balance;
  scroll-margin-block-start: calc(var(--nav-height) + var(--space-4));
}
.nc-body h3 {
  text-wrap: balance;
}
.nc-body p a,
.nc-body li a {
  color: var(--color-primary);
  text-decoration-color: var(--color-accent);
  text-underline-offset: 3px;
}
.nc-body ul {
  display: grid;
  gap: var(--space-2);
  margin: var(--space-4) 0;
  padding: 0;
  list-style: none;
}
.nc-body ul > li {
  position: relative;
  padding-inline-start: var(--space-6);
}
.nc-body ul > li::before {
  content: "";
  position: absolute;
  inset-block-start: .65em;
  inset-inline-start: var(--space-1);
  width: var(--space-2);
  height: var(--space-2);
  background: var(--color-accent);
  transform: rotate(45deg);
}

/* ---------- Signature: the three build phases as stacked phase cards ---------- */
/* phase 1-3 = the 2nd, 3rd and 4th paragraphs after the sequencing H2 */
.nc-body #how-new-construction-plumbing-is-sequenced + p + p,
.nc-body #how-new-construction-plumbing-is-sequenced + p + p + p,
.nc-body #how-new-construction-plumbing-is-sequenced + p + p + p + p {
  position: relative;
  margin-block: var(--space-3);
  padding: var(--space-4) var(--space-5);
  border-radius: var(--radius-lg);
  background: var(--color-card-tint-1);
  border: 1px solid var(--color-line);
  transition: transform var(--transition), box-shadow var(--transition);
}
.nc-body #how-new-construction-plumbing-is-sequenced + p + p + p {
  background: var(--color-card-tint-3);
  margin-inline-start: var(--space-6);
}
.nc-body #how-new-construction-plumbing-is-sequenced + p + p + p + p {
  background: var(--color-card-tint-2);
  margin-inline-start: var(--space-12);
}
.nc-body #how-new-construction-plumbing-is-sequenced + p + p:hover,
.nc-body #how-new-construction-plumbing-is-sequenced + p + p + p:hover,
.nc-body #how-new-construction-plumbing-is-sequenced + p + p + p + p:hover {
  transform: translateX(var(--space-1));
  box-shadow: var(--shadow);
}

/* ---------- FAQ: question cards ---------- */
.nc-body #frequently-asked-questions ~ h3 {
  margin-block: var(--space-5) 0;
  padding: var(--space-4) var(--space-5) var(--space-2);
  border-radius: var(--radius-lg) var(--radius-lg) 0 0;
  background: var(--color-paper-2);
  border: 1px solid var(--color-line);
  border-block-end: 0;
  font-size: 1.1rem;
}
.nc-body #frequently-asked-questions ~ h3 + p {
  margin: 0;
  padding: 0 var(--space-5) var(--space-4);
  border-radius: 0 0 var(--radius-lg) var(--radius-lg);
  background: var(--color-paper-2);
  border: 1px solid var(--color-line);
  border-block-start: 0;
}

/* ---------- Owner line + sources ---------- */
.nc-body p > em:only-child {
  display: inline-block;
  padding: var(--space-2) var(--space-4);
  border-radius: var(--radius-full);
  background: var(--color-card-tint-neutral);
  font-size: var(--fs-small);
}
.nc-body #sources {
  font-size: 1.1rem;
}
.nc-body #sources + ul {
  padding: var(--space-4) var(--space-5);
  border-radius: var(--radius);
  background: var(--color-paper-2);
  font-size: var(--fs-small);
  color: var(--color-ink-2);
}
.nc-body #sources + ul a {
  word-break: break-word;
}

/* ---------- Related ---------- */
.nc-related {
  margin-block-start: var(--space-16);
  padding-block-start: var(--space-8);
  border-block-start: 1px solid var(--color-line);
}
.nc-related h2 {
  margin-block-end: var(--space-4);
  text-wrap: balance;
}
.nc-related .service-links {
  display: grid;
  gap: var(--space-2);
  margin: 0 0 var(--space-8);
  padding: 0;
  list-style: none;
}
.nc-related .service-links a {
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
.nc-related .service-links a:hover {
  border-color: var(--color-primary);
  transform: translateX(var(--space-1));
}

@media (max-width: 1000px) {
  .nc-layout {
    grid-template-columns: 1fr;
  }
  .nc-rail {
    position: static;
    order: -1;
    grid-template-columns: 1fr 1fr;
  }
}
@media (max-width: 640px) {
  .nc-rail {
    grid-template-columns: 1fr;
  }
  .nc-body #how-new-construction-plumbing-is-sequenced + p + p + p,
  .nc-body #how-new-construction-plumbing-is-sequenced + p + p + p + p {
    margin-inline-start: 0;
  }
}
@media (prefers-reduced-motion: reduce) {
  .nc-body p,
  .nc-toc a,
  .nc-related .service-links a {
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
<section class="hero hero--interior nc-hero" aria-labelledby="nc-h1">
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
    <h1 class="hero-title" id="nc-h1"><?php echo h($post['title']); ?></h1>
    <ul class="nc-meta">
      <li><?php echo icon('calendar', 16); ?><time datetime="<?php echo h($post['dateISO']); ?>"><?php echo h($post['date']); ?></time></li>
      <li><?php echo icon('clock', 16); ?><?php echo h($post['readtime']); ?></li>
      <li><?php echo icon('info', 16); ?>Published by Page One Insights LLC</li>
    </ul>
  </div>
</section>

<div class="section nc-wrap">
  <div class="container-wide nc-layout">

    <article class="nc-article" aria-labelledby="nc-h1">
      <figure class="nc-featured blog-post__featured-image">
        <?php echo pictureTag($post['photo'], $post['alt'], '(max-width: 1000px) 100vw, 780px', ['loading' => 'eager', 'fetchpriority' => 'high']); ?>
      </figure>

      <div class="blog-post__content nc-body">
<p>The core difference: on a new build, every drain under a San Angelo slab goes in before the concrete is poured, in phases the City inspects. In a remodel, the plumber works around what's already there, and moving a drain means cutting the slab. That changes the timing, the cost and the permits.</p>
<p>Knowing which kind of project you have, and what that means for timing, cost and permits, helps you bring a plumber in at the right moment instead of after the decisions are locked in.</p>
<h2 id="new-build-blank-slate-remodel-detective-work-first">New build: blank slate. Remodel: detective work first.</h2>
<p>On a <strong>new build</strong>, the plumber works from plans. No old pipe to work around, no surprises in the walls. Supply lines, drain-waste-vent piping and gas lines go where the plans say they go.</p>
<p>A <strong>remodel</strong> starts with figuring out what's already there. Walls open up, and the plumber has to route new work around framing, ductwork, wiring and whatever the last remodeler left behind. In older San Angelo homes that can include galvanized steel supply lines. Galvanized was common in homes built before about 1960. It rusts from the inside, and once a wall is open it often looks worse than anyone expected. (Our post on <a href="/blog/slab-leaks-pipe-repair-san-angelo/">slab leaks and old pipe</a> covers what failing galvanized looks like.)</p>
<p>Remodels turn up surprises that new builds don't. A good plumber tells you that before the demolition starts, not after.</p>
<h2 id="how-new-construction-plumbing-is-sequenced">How new construction plumbing is sequenced</h2>
<p>New construction plumbing comes in three phases tied to the build schedule. Miss one, and you either wait or pay to undo finished work.</p>
<p><strong>1. Underground (slab) rough-in.</strong> Before the slab is poured, the plumber sets drain lines, supply stubs and any under-slab piping. This is the least forgiving phase. Once concrete covers it, a mistake means a jackhammer. It's inspected before the pour.</p>
<p><strong>2. Top-out (above-slab rough-in).</strong> After framing, supply and drain-waste-vent piping run through the open walls, and gas lines are stubbed to the range, water heater, furnace and any outdoor connections. Piping is pressure-tested and inspected before drywall goes up.</p>
<p><strong>3. Trim-out (final).</strong> After drywall and finishes, fixtures, valves, the water heater and appliances get connected. Then comes the final inspection.</p>
<p>For builders, the date that matters most is the slab pour. A plumber who can't hit that window holds up every trade behind them.</p>
<h2 id="common-plumbing-changes-in-kitchen-and-bath-remodels">Common plumbing changes in kitchen and bath remodels</h2>
<ul>
<li>Moving a sink, toilet or shower drain</li>
<li>Swapping old galvanized supply lines for PEX or copper</li>
<li>Turning a tub into a walk-in shower, which changes drain size and location</li>
<li>Adding a dishwasher, ice maker line or pot filler</li>
<li>Changing fixture heights or valve locations</li>
</ul>
<p>On a slab, any drain move means cutting the floor, trenching, setting the new pipe with the right slope and patching the concrete. That's real labor, and it belongs in the budget from day one.</p>
<h2 id="permits-and-inspections-in-san-angelo">Permits and inspections in San Angelo</h2>
<p>Inside San Angelo city limits, permits and inspections go through the City's Building Permits &amp; Inspections division. The City currently enforces the 2021 International Plumbing Code, International Residential Code and International Fuel Gas Code. Residential remodels and additions have their own permit handout and application, and the City requires a plumbing permit and passing inspection for gas work, water heater replacements and sprinkler systems.</p>
<p>Unpermitted plumbing can come back to bite you when you sell the house, and it can complicate an insurance claim if that area ever leaks.</p>
<p>Outside city limits, in unincorporated Tom Green County, the rules are different. Ask before you start rather than assuming the city process applies.</p>
<p>For new construction, the inspections are built into the schedule: before the pour, before drywall and at final. Missing the pre-pour inspection has no good workaround, so book it early.</p>
<h2 id="gas-lines-new-build-vs-remodel">Gas lines: new build vs. remodel</h2>
<p>Atmos Energy supplies natural gas in San Angelo, and lots of homes use it for water heating, cooking and heat.</p>
<p>In <strong>new construction</strong>, gas piping (black steel, CSST or both) runs from the meter location to each appliance while the walls are open, then gets pressure-tested before drywall. It's the cleanest, least expensive time to run gas. See our <a href="/services/gas-line-services/">gas line services</a>.</p>
<p>In a <strong>remodel</strong>, adding gas, such as converting an electric range to gas or adding a line for a new appliance, means getting pipe through finished walls. Flexible CSST is often the practical choice because it needs fewer fittings. Under the 2021 Fuel Gas Code, standard yellow CSST has to be bonded to the home's electrical grounding system. Either way the work needs a permit, a pressure test and a City inspection before the City releases gas service to Atmos. More in our post on <a href="/blog/gas-line-services-in-san-angelo-tx-safe-installation-and-repair-solutions/">gas lines in San Angelo homes</a>.</p>
<h2 id="planning-for-san-angelo-s-hard-water">Planning for San Angelo's hard water</h2>
<p>San Angelo's water comes from a mix of reservoirs (including O.H. Ivie, Twin Buttes, Lake Nasworthy and O.C. Fisher) and the Hickory Aquifer, and it's hard. A July 2025 City lab test measured 18.4 grains per gallon (312 mg/L). The USGS calls anything over 180 mg/L "very hard."</p>
<p>That affects both kinds of projects:</p>
<ul>
<li><strong>On a new build</strong>, it's easy to rough in a loop for a whole-house water softener or filter near where the water line enters, so one can be added later without opening walls. It's also the time to think about where the water heater goes and how you'll get to it for maintenance, since hard water means sediment that needs flushing (more in our post on <a href="/blog/water-heater-installation-and-repair-services-available-in-christoval-tx/">water heater repair and replacement</a>). A <a href="/services/water-filtration-softeners/">water softener or filter</a> roughed in now saves opening walls later.</li>
<li><strong>In a remodel</strong>, hard water and age work together on old galvanized lines. A planned faucet upgrade can turn into replacing a run of supply line once the wall is open. It's better to put a contingency in the budget than to be surprised mid-project.</li>
</ul>
<h2 id="when-to-bring-a-plumber-in">When to bring a plumber in</h2>
<ul>
<li><strong>New construction:</strong> at the plan stage, before the slab design is final. Drain locations, water heater placement and gas routing are cheapest to change on paper.</li>
<li><strong>Remodel:</strong> before you lock in a layout. If the new design moves a drain across a slab, a plumber can tell you what that costs before you fall in love with it.</li>
</ul>
<h2 id="talk-to-us-early">Talk to us early</h2>
<p>Whether you're building in San Angelo or Christoval or reworking a kitchen in an older home, Rock'n G Plumbing can help you get the sequence, permits and pipe choices right from the start. Call <strong>(325) 650-7000</strong>, Monday through Friday, 8 AM to 6 PM, or see our <a href="/services/new-construction-remodel-plumbing/">new construction and remodel plumbing</a> page.</p>
<p><em>Rock'n G Plumbing is owned by Eli Gomez, Texas Master Plumber License M-44822.</em></p>
<h2 id="frequently-asked-questions">Frequently asked questions</h2>
<h3>What are the phases of new construction plumbing?</h3>
<p>Three: the underground (slab) rough-in before the pour, the top-out after framing and before drywall, and the trim-out, when fixtures and appliances are connected. Each one is inspected.</p>
<h3>Why is moving a drain expensive in a San Angelo remodel?</h3>
<p>Most homes here are built on a slab, so moving a drain means cutting and trenching the concrete, setting new pipe at the right slope and patching the floor.</p>
<h3>Do I need a permit for remodel plumbing in San Angelo?</h3>
<p>Inside city limits, permits go through the City's Building Permits &amp; Inspections division under the 2021 plumbing codes. Gas work, water heater replacements and sprinkler work all require one. For your specific layout change, confirm with Permits &amp; Inspections at 325-657-4210.</p>
<h3>How hard is San Angelo's water?</h3>
<p>A July 2025 City lab test measured 18.4 grains per gallon (312 mg/L), which is in the USGS 'very hard' range.</p>
<h2 id="sources">Sources</h2>
<ul>
<li>City of San Angelo, Codes &amp; Regulations (2021 IPC, IRC, IFGC adopted): <a rel="nofollow noopener" target="_blank" href="https://www.sanangelo.gov/188/Codes-Regulations">https://www.sanangelo.gov/188/Codes-Regulations</a></li>
<li>City of San Angelo, Building Permits &amp; Inspections (residential handouts; gas permit and inspection; Atmos release): <a rel="nofollow noopener" target="_blank" href="https://www.sanangelo.gov/185/Building-Permits-Inspections">https://www.sanangelo.gov/185/Building-Permits-Inspections</a></li>
<li>City of San Angelo, 2025 Water Quality Report (water sources): <a rel="nofollow noopener" target="_blank" href="https://www.sanangelo.gov/DocumentCenter/View/1956/2025-PDF?bidId=">https://www.sanangelo.gov/DocumentCenter/View/1956/2025-PDF?bidId=</a></li>
<li>City of San Angelo, Hickory Aquifer: <a rel="nofollow noopener" target="_blank" href="https://www.sanangelo.gov/571/Hickory-Aquifer">https://www.sanangelo.gov/571/Hickory-Aquifer</a></li>
<li>City of San Angelo Water Quality Laboratory, July 2025 tap sample (18.4 gr/gal, 312 mg/L): <a rel="nofollow noopener" target="_blank" href="https://www.sanangelo.gov/Archive.aspx?ADID=231">https://www.sanangelo.gov/Archive.aspx?ADID=231</a></li>
<li>USGS, Hardness of Water: <a rel="nofollow noopener" target="_blank" href="https://www.usgs.gov/special-topics/water-science-school/science/hardness-water">https://www.usgs.gov/special-topics/water-science-school/science/hardness-water</a></li>
<li>HORIZON home inspection reference, water supply piping (galvanized used almost exclusively to about 1950; typical 40 to 60 year life): <a rel="nofollow noopener" target="_blank" href="https://app-horizon-web-prod.azurewebsites.net/hrb/HHRB.aspx?DROPDOWN=4312&amp;id=1907">https://app-horizon-web-prod.azurewebsites.net/hrb/HHRB.aspx?DROPDOWN=4312&amp;id=1907</a></li>
<li>Structure Tech, Problems with Galvanized Steel Water Pipes (copper replaced galvanized through the 1950s): <a rel="nofollow noopener" target="_blank" href="https://structuretech.com/galvanized-steel-water-pipes/">https://structuretech.com/galvanized-steel-water-pipes/</a></li>
<li>ICC CodeNotes, CSST bonding (2021 IFGC): <a rel="nofollow noopener" target="_blank" href="https://www.iccsafe.org/building-safety-journal/bsj-technical/codenotes-bonding-of-corrugated-stainless-steel-tubing-gas-piping-systems/">https://www.iccsafe.org/building-safety-journal/bsj-technical/codenotes-bonding-of-corrugated-stainless-steel-tubing-gas-piping-systems/</a></li>
</ul>
      </div>

      <div class="nc-related blog-post__related-services">
        <h2>Which Rock'n G Plumbing services go with this guide?</h2>
        <ul class="service-links">
          <li><a href="/services/new-construction-remodel-plumbing/">New Construction &amp; Remodel Plumbing <span aria-hidden="true">→</span></a></li>
          <li><a href="/services/gas-line-services/">Gas Line Installation &amp; Repair <span aria-hidden="true">→</span></a></li>
          <li><a href="/services/water-filtration-softeners/">Water Softeners &amp; Filtration <span aria-hidden="true">→</span></a></li>
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

    <aside class="nc-rail" aria-label="Guide navigation and contact">
      <nav class="nc-toc" aria-labelledby="nc-toc-title">
        <h2 id="nc-toc-title">In this guide</h2>
        <ol>
          <?php foreach ($toc as $anchor => $label): ?><li><a href="#<?php echo h($anchor); ?>"><?php echo h($label); ?></a></li><?php endforeach; ?>
        </ol>
      </nav>
      <div class="nc-sidecta">
        <h2>Free estimate</h2>
        <p><?php echo h($weekday[0]); ?>, <?php echo h($weekday[1]); ?>. Texas Master Plumber <?php echo h($license['number']); ?>.</p>
        <a class="btn btn-accent" href="/contact/" data-open-estimate>Get a Free Estimate</a>
        <a class="link-call" href="<?php echo h($telHref); ?>"><?php echo icon('phone', 18); ?><?php echo h($phoneFmt); ?></a>
      </div>
    </aside>

  </div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>
