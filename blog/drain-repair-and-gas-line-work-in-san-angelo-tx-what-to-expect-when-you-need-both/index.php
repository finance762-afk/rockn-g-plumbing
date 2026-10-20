<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/functions.php';
?>
<?php
/**
 * blog/drain-repair-and-gas-line-work-in-san-angelo-tx-what-to-expect-when-you-need-both/index.php — migrated post #2
 * (Oct 18 2026 blog migration, P1 BLOG BOT drafts). Old root slug kept under /blog/; the root URL 301s here.
 * Body pasted from the approved draft; only service hrefs were remapped to /services/<slug>/.
 * No FAQPage schema (Rock'n G SEO brief). Author = Page One Insights LLC (blog-standard.md §7).
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/blog-data.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/service-cards.php';

$bpSlug = 'drain-repair-and-gas-line-work-in-san-angelo-tx-what-to-expect-when-you-need-both';
$post   = blogPostBySlug($bpSlug);

$pageTitle       = $post['title'];
$pageDescription = 'Spot repair, reroute or full replacement? How San Angelo plumbers decide on drain lines under slabs, and how gas work and City permits fit into the job.';
$canonicalUrl    = $siteUrl . '/blog/' . $bpSlug . '/';
$currentPage     = 'blog/' . $bpSlug;
$ogType          = 'article';
$ogImage         = '/assets/images/' . $post['photo'] . '.jpg';
$breadcrumbs     = [['name' => 'Blog', 'url' => '/blog/'], ['name' => $post['title'], 'url' => '/blog/' . $bpSlug . '/']];

$telHref  = 'tel:' . $phoneE164;
$phoneFmt = formatPhone($phone);
$weekday  = hoursGroups($hours)[0];

$toc = [
    'start-with-what-the-camera-actually-shows'   => 'Start with what the camera actually shows',
    'the-three-real-options'                      => 'The three real options',
    'why-slab-homes-change-the-math'              => 'Why slab homes change the math',
    'when-gas-is-part-of-the-same-job'            => 'When gas is part of the same job',
    'questions-worth-asking-before-you-sign-off'  => 'Questions worth asking before you sign off',
    'when-to-call'                                => 'When to call',
    'frequently-asked-questions'                  => 'Frequently asked questions',
];

$schemaMarkup = [[
    "@context" => 'https://schema.org', '@type' => 'BlogPosting',
    '@id' => $canonicalUrl . '#post',
    'headline' => $post['title'],
    'description' => $pageDescription,
    'image' => absUrl($post['image']),
    'datePublished' => $post['dateISO'], 'dateModified' => $post['dateISO'],
    'keywords' => 'drain repair San Angelo, drain line replacement, cast iron drain under slab, gas line permit San Angelo',
    'inLanguage' => 'en-US',
    'author' => ['@type' => 'Organization', 'name' => 'Page One Insights LLC', 'url' => 'https://pageoneinsights.com'],
    'publisher' => ['@id' => orgId()],
    'mainEntityOfPage' => $canonicalUrl,
    'about' => ['@type' => 'Service', 'name' => 'Sewer Line Repair & Camera Inspection', 'url' => absUrl('/services/sewer-line-repair/'), 'provider' => ['@id' => orgId()]],
]];

ob_start();
?>
<style>
/* =====================================================================
   Post: drain repair + gas line work — page-only CSS (dg- prefix)
   Tokens from framework.css; no raw colors, shadows or spacing.
   Techniques: layered type hero (gradient + noise), sticky TOC rail, numbered step track (signature),
   FAQ question cards, tinted source list, related band.
   ===================================================================== */
.dg-hero {
  padding-block-end: var(--space-12);
  background:
    radial-gradient(ellipse 55% 90% at 100% 0%, color-mix(in srgb, var(--color-secondary) 22%, transparent), transparent 62%),
    linear-gradient(165deg, var(--color-primary-deep), var(--color-dark));
}
.dg-hero::before {
  content: "";
  position: absolute;
  inset: auto 0 0 0;
  height: var(--space-2);
  pointer-events: none;
  background: linear-gradient(90deg, var(--color-accent), var(--color-secondary), transparent);
  opacity: .7;
}
.dg-hero::after {
  content: "";
  position: absolute;
  inset: 0;
  pointer-events: none;
  opacity: .16;
  mix-blend-mode: overlay;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='160' height='160'%3E%3Cfilter id='g'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.8' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23g)'/%3E%3C/svg%3E");
}
.dg-hero .container {
  position: relative;
  z-index: 1;
}
.dg-hero .breadcrumb {
  color: color-mix(in srgb, var(--color-surface) 72%, transparent);
}
.dg-hero .breadcrumb li {
  display: inline-flex;
  align-items: center;
}
.dg-hero .breadcrumb [aria-current="page"] {
  color: var(--color-surface);
}
.dg-hero .hero-title {
  max-width: 24ch;
  text-wrap: balance;
}
.dg-meta {
  display: flex;
  flex-wrap: wrap;
  gap: var(--space-2) var(--space-5);
  margin: var(--space-4) 0 0;
  padding: 0;
  list-style: none;
  font-size: var(--fs-small);
  color: color-mix(in srgb, var(--color-surface) 78%, transparent);
}
.dg-meta li {
  display: inline-flex;
  gap: var(--space-2);
  align-items: center;
}
.dg-meta svg {
  color: var(--color-accent-bright);
}

/* ---------- Layout: article + rail ---------- */
.dg-layout {
  display: grid;
  grid-template-columns: minmax(0, 1fr) 300px;
  gap: clamp(var(--space-8), 5vw, var(--space-16));
  align-items: start;
}
.dg-featured {
  margin: 0 0 var(--space-8);
  box-shadow: var(--shadow);
}
.dg-featured img {
  width: 100%;
  aspect-ratio: 16 / 9;
  object-fit: cover;
}
.dg-rail {
  position: sticky;
  inset-block-start: calc(var(--nav-height) + var(--space-4));
  display: grid;
  gap: var(--space-5);
}
.dg-toc,
.dg-sidecta {
  padding: var(--space-5);
  border-radius: var(--radius-lg);
  border: 1px solid var(--color-line);
}
.dg-toc {
  background: var(--color-card-tint-2);
}
.dg-toc h2 {
  font-size: 1rem;
  margin-block-end: var(--space-3);
}
.dg-toc ol {
  display: grid;
  gap: var(--space-1);
  margin: 0;
  padding: 0;
  list-style: none;
  counter-reset: dg-toc;
}
.dg-toc li {
  counter-increment: dg-toc;
}
.dg-toc a {
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
.dg-toc a::before {
  content: counter(dg-toc, decimal-leading-zero);
  font-family: var(--font-accent);
  letter-spacing: .08em;
  color: var(--color-accent-dark);
}
.dg-toc a:hover {
  color: var(--color-primary);
  border-color: var(--color-accent);
  padding-inline-start: var(--space-2);
}
.dg-sidecta {
  background: var(--color-primary);
  color: var(--color-surface);
  box-shadow: var(--shadow);
}
.dg-sidecta h2 {
  color: var(--color-surface);
  font-size: 1.1rem;
  margin-block-end: var(--space-2);
}
.dg-sidecta p {
  margin: 0 0 var(--space-3);
  font-size: var(--fs-small);
  color: color-mix(in srgb, var(--color-surface) 84%, transparent);
}
.dg-sidecta .btn {
  width: 100%;
  justify-content: center;
}
.dg-sidecta .link-call {
  margin-block-start: var(--space-3);
  color: var(--color-surface);
}
.dg-sidecta .link-call svg {
  color: var(--color-accent-bright);
}

/* ---------- Body ---------- */
.dg-body > p:first-child {
  font-size: var(--fs-lead);
  padding-inline-start: var(--space-5);
  border-inline-start: 4px solid var(--color-secondary);
}
.dg-body h2 {
  margin-block: var(--space-12) var(--space-3);
  text-wrap: balance;
  scroll-margin-block-start: calc(var(--nav-height) + var(--space-4));
}
.dg-body h3 {
  text-wrap: balance;
}
.dg-body p a,
.dg-body li a {
  color: var(--color-primary);
  text-decoration-color: var(--color-accent);
  text-underline-offset: 3px;
}
.dg-body ul {
  display: grid;
  gap: var(--space-2);
  margin: var(--space-4) 0;
  padding: 0;
  list-style: none;
}
.dg-body ul > li {
  position: relative;
  padding-inline-start: var(--space-6);
}
.dg-body ul > li::before {
  content: "";
  position: absolute;
  inset-block-start: .65em;
  inset-inline-start: var(--space-1);
  width: var(--space-2);
  height: var(--space-2);
  border-radius: var(--radius-sm);
  background: var(--color-secondary);
}

/* ---------- Signature: numbered step track ---------- */
.dg-body ol {
  counter-reset: dg-step;
  display: grid;
  gap: var(--space-3);
  margin: var(--space-5) 0;
  padding: 0;
  list-style: none;
}
.dg-body ol > li {
  counter-increment: dg-step;
  position: relative;
  padding: var(--space-4) var(--space-5) var(--space-4) var(--space-16);
  border-radius: var(--radius-lg);
  background: var(--color-card-tint-2);
  border: 1px solid var(--color-line);
  transition: transform var(--transition), box-shadow var(--transition);
}
.dg-body ol > li:nth-child(3n + 2) {
  background: var(--color-card-tint-3);
}
.dg-body ol > li:nth-child(3n) {
  background: var(--color-card-tint-1);
}
.dg-body ol > li:hover {
  transform: translateX(var(--space-1));
  box-shadow: var(--shadow);
}
.dg-body ol > li::before {
  content: counter(dg-step);
  position: absolute;
  inset-block-start: var(--space-3);
  inset-inline-start: var(--space-4);
  font-family: var(--font-accent);
  font-size: 2.2rem;
  line-height: 1;
  color: var(--color-secondary);
}

/* ---------- FAQ: question cards ---------- */
.dg-body #frequently-asked-questions ~ h3 {
  margin-block: var(--space-5) 0;
  padding: var(--space-4) var(--space-5) var(--space-2);
  border-radius: var(--radius-lg) var(--radius-lg) 0 0;
  background: var(--color-paper-2);
  border: 1px solid var(--color-line);
  border-block-end: 0;
  font-size: 1.1rem;
}
.dg-body #frequently-asked-questions ~ h3 + p {
  margin: 0;
  padding: 0 var(--space-5) var(--space-4);
  border-radius: 0 0 var(--radius-lg) var(--radius-lg);
  background: var(--color-paper-2);
  border: 1px solid var(--color-line);
  border-block-start: 0;
}

/* ---------- Owner line + sources ---------- */
.dg-body p > em:only-child {
  display: inline-block;
  padding: var(--space-2) var(--space-4);
  border-radius: var(--radius-full);
  background: var(--color-card-tint-neutral);
  font-size: var(--fs-small);
}
.dg-body #sources {
  font-size: 1.1rem;
}
.dg-body #sources + ul {
  padding: var(--space-4) var(--space-5);
  border-radius: var(--radius);
  background: var(--color-paper-2);
  font-size: var(--fs-small);
  color: var(--color-ink-2);
}
.dg-body #sources + ul a {
  word-break: break-word;
}

/* ---------- Related ---------- */
.dg-related {
  margin-block-start: var(--space-16);
  padding-block-start: var(--space-8);
  border-block-start: 1px solid var(--color-line);
}
.dg-related h2 {
  margin-block-end: var(--space-4);
  text-wrap: balance;
}
.dg-related .service-links {
  display: grid;
  gap: var(--space-2);
  margin: 0 0 var(--space-8);
  padding: 0;
  list-style: none;
}
.dg-related .service-links a {
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
.dg-related .service-links a:hover {
  border-color: var(--color-primary);
  transform: translateX(var(--space-1));
}

@media (max-width: 1000px) {
  .dg-layout {
    grid-template-columns: 1fr;
  }
  .dg-rail {
    position: static;
    order: -1;
    grid-template-columns: 1fr 1fr;
  }
}
@media (max-width: 640px) {
  .dg-rail {
    grid-template-columns: 1fr;
  }
  .dg-body ol > li {
    padding-inline-start: var(--space-12);
  }
}
@media (prefers-reduced-motion: reduce) {
  .dg-body ol > li,
  .dg-toc a,
  .dg-related .service-links a {
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
<section class="hero hero--interior dg-hero" aria-labelledby="dg-h1">
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
    <h1 class="hero-title" id="dg-h1"><?php echo h($post['title']); ?></h1>
    <ul class="dg-meta">
      <li><?php echo icon('calendar', 16); ?><time datetime="<?php echo h($post['dateISO']); ?>"><?php echo h($post['date']); ?></time></li>
      <li><?php echo icon('clock', 16); ?><?php echo h($post['readtime']); ?></li>
      <li><?php echo icon('info', 16); ?>Published by Page One Insights LLC</li>
    </ul>
  </div>
</section>

<div class="section dg-wrap">
  <div class="container-wide dg-layout">

    <article class="dg-article" aria-labelledby="dg-h1">
      <figure class="dg-featured blog-post__featured-image">
        <?php echo pictureTag($post['photo'], $post['alt'], '(max-width: 533px) calc(100vw - 2rem), (max-width: 1000px) 94vw, (max-width: 1464px) calc(100vw - 428px), 1036px', ['loading' => 'eager', 'fetchpriority' => 'high']); ?>
      </figure>

      <div class="blog-post__content dg-body">
<p>Whether a damaged drain should be repaired or replaced depends on what a camera shows. One defect in sound pipe usually means a spot repair. Damage along the whole run means replacement. When the same job touches gas, add a City permit, an inspection and an Atmos service release to the schedule.</p>
<p>(If you're still at the stage of repeat clogs, start with <a href="/blog/recurring-drain-clogs-san-angelo/">why drains keep clogging</a>.) Once there's real damage, there's no single right answer, but there is a sensible way to decide. Here's how we walk people through it in San Angelo, where most homes sit on a concrete slab and the drains run underneath.</p>
<h2 id="start-with-what-the-camera-actually-shows">Start with what the camera actually shows</h2>
<p>Decisions should come from footage, not from how many times you've called a plumber. The things we look for:</p>
<ul>
<li><strong>One defect in otherwise sound pipe.</strong> A single cracked joint, one root entry point, one offset. This is usually a spot repair.</li>
<li><strong>Several defects spread along the run.</strong> Cracks every few feet, scale and rust throughout, more than one sag. Fixing one spot just moves the failure to the next one.</li>
<li><strong>Material and age.</strong> Cast iron drains were standard in many older homes. They corrode from the inside, and the bottom of the pipe often goes first, which a camera can catch before it collapses. PVC lines from later decades usually fail at a joint or a sag, not along the whole length.</li>
<li><strong>Location.</strong> A break in the yard is a shovel job. A break under the kitchen slab is a much bigger one.</li>
</ul>
<h2 id="the-three-real-options">The three real options</h2>
<p><strong>1. Spot repair.</strong> Dig or cut to the bad section, replace a few feet of pipe, and reconnect. It makes sense when the rest of the line is in good shape. It's the least disruptive and usually the least expensive fix today.</p>
<p><strong>2. Section replacement or reroute.</strong> When the trouble is under the slab, sometimes the smarter move is to abandon the bad run and route new pipe a different way, through an exterior wall, the attic or around the foundation, instead of breaking up flooring. Whether that's possible depends on the house layout and the fall the drain needs.</p>
<p><strong>3. Full replacement.</strong> When the camera shows decay along most of the line, replacing it once costs less than paying for three spot repairs over five years. It's also the option that lets you stop worrying about it.</p>
<p>Ask whoever quotes the job to show you the footage and tell you which option they'd pick for their own house, and why.</p>
<h2 id="why-slab-homes-change-the-math">Why slab homes change the math</h2>
<p>On a crawlspace house, a plumber can reach most drain lines from underneath. On a slab there are only two ways to reach a drain under the floor: cut through the concrete from inside, or tunnel under the foundation from outside. Both take more time than a crawlspace repair, and inside cuts mean pulling flooring.</p>
<p>San Angelo's clay soils add another wrinkle. Clay that swells and shrinks with moisture is hard on rigid pipe and hard on foundations. When a plumber backfills a trench or a tunnel, compacting it properly matters, so the repair doesn't settle and create a new low spot.</p>
<h2 id="when-gas-is-part-of-the-same-job">When gas is part of the same job</h2>
<p>This happens more often than you'd think, for two reasons.</p>
<p><strong>The water heater closet.</strong> In a lot of homes the gas water heater, its relief valve discharge line, a drain pan and a floor drain all share one small space. Work on a drain there puts a plumber right next to the gas connection, and corroded fittings or an outdated flex connector get noticed.</p>
<p><strong>The trench.</strong> If a broken drain runs near a buried gas line, opening the ground for one exposes the other. Fixing both while the trench is open saves digging twice.</p>
<p>When gas work comes into the picture, the order of work changes:</p>
<ol>
<li><strong>Safety first.</strong> If anyone smells gas, the gas gets shut off and the area gets aired out before any other work continues.</li>
<li><strong>Permit and inspection.</strong> In San Angelo, a plumbing permit and a passing City inspection are required for gas work. The City only releases gas service to Atmos Energy after the inspection passes.</li>
<li><strong>Atmos restores service.</strong> After the City sends the gas release, you contact Atmos to have service turned back on.</li>
</ol>
<p>That inspection and release is the step that can push a job into a second day. It's not the plumber stalling. It's the process the City uses to make sure gas piping is tight before anyone turns it back on. A good plumber will tell you up front if your job needs it.</p>
<p>Any digging, even in your own yard, also requires a call to Texas 811 at least two business days ahead so utility-owned lines can be marked. One catch: 811 marks the utility's lines, not the private gas or water lines between the meter and your house. Your plumber has to account for those.</p>
<p>For more on gas lines themselves, including warning signs and who owns which pipe, see our post on <a href="/blog/gas-line-services-in-san-angelo-tx-safe-installation-and-repair-solutions/">gas line safety in San Angelo</a>.</p>
<h2 id="questions-worth-asking-before-you-sign-off">Questions worth asking before you sign off</h2>
<ul>
<li>Can I see the camera footage, and where exactly is the problem?</li>
<li>Does this need a City permit? (In San Angelo, gas work, sprinkler work and water heater replacements all need one. For drain work, ask your plumber or call the City's Permits &amp; Inspections office at 325-657-4210.)</li>
<li>If you're cutting the slab, who repairs the concrete and flooring?</li>
<li>If gas is involved, how long do you expect between the inspection and Atmos restoring service?</li>
<li>What's the warranty on the repair?</li>
</ul>
<h2 id="when-to-call">When to call</h2>
<p>If a camera has already shown damage, or you keep hearing "it's clear for now," it's time for a real answer on repair vs. replacement. Rock'n G Plumbing serves San Angelo and Christoval, Monday through Friday, 8 AM to 6 PM. Call <strong>(325) 650-7000</strong>, or read more about our <a href="/services/sewer-line-repair/">sewer line repair</a> and <a href="/services/gas-line-services/">gas line services</a>.</p>
<p><em>Rock'n G Plumbing is owned by Eli Gomez, Texas Master Plumber License M-44822.</em></p>
<h2 id="frequently-asked-questions">Frequently asked questions</h2>
<h3>How do I know if a drain line needs repair or replacement?</h3>
<p>Go by camera footage. One defect in otherwise sound pipe usually calls for a spot repair. Defects spread along the line, or corrosion throughout, usually mean replacement.</p>
<h3>Why does drain repair cost more in a slab home?</h3>
<p>The only way to reach a drain under a slab is to cut the concrete from inside or tunnel under the foundation from outside. Both take more labor than a crawlspace repair.</p>
<h3>Does gas line work need a permit in San Angelo?</h3>
<p>Yes. The City requires a plumbing permit and a passing inspection for all gas work, and it releases gas service to Atmos Energy only after the inspection passes.</p>
<h3>Do I need to call 811 before a plumber digs in my yard?</h3>
<p>Texas law requires a call to 811 at least two business days before digging. Note that 811 marks the utilities' lines, not your private lines between the meter and the house.</p>
<h2 id="sources">Sources</h2>
<ul>
<li>City of San Angelo, Building Permits &amp; Inspections (plumbing permit and passing inspection required for all gas work; gas service released to Atmos only after inspection): <a rel="nofollow noopener" target="_blank" href="https://www.sanangelo.gov/185/Building-Permits-Inspections">https://www.sanangelo.gov/185/Building-Permits-Inspections</a></li>
<li>City of San Angelo, Codes &amp; Regulations (2021 IPC and IFGC adopted): <a rel="nofollow noopener" target="_blank" href="https://www.sanangelo.gov/188/Codes-Regulations">https://www.sanangelo.gov/188/Codes-Regulations</a></li>
<li>Texas811, I Want to Dig (homeowners must call two business days before digging): <a rel="nofollow noopener" target="_blank" href="https://texas811.org/i-want-to-dig/">https://texas811.org/i-want-to-dig/</a></li>
<li>Texas Utilities Code ch. 251, §251.151: <a rel="nofollow noopener" target="_blank" href="https://statutes.capitol.texas.gov/Docs/UT/htm/UT.251.htm">https://statutes.capitol.texas.gov/Docs/UT/htm/UT.251.htm</a></li>
<li>Atmos Energy, Home Safety (customer-owned lines beyond the meter; 811 marks utility-owned lines): <a rel="nofollow noopener" target="_blank" href="https://www.atmosenergy.com/safety/home-safety/">https://www.atmosenergy.com/safety/home-safety/</a></li>
<li>USDA NRCS Official Series Description, Angelo series: <a rel="nofollow noopener" target="_blank" href="https://soilseries.sc.egov.usda.gov/OSD_Docs/A/Angelo.html">https://soilseries.sc.egov.usda.gov/OSD_Docs/A/Angelo.html</a></li>
</ul>
      </div>

      <div class="dg-related blog-post__related-services">
        <h2>Which Rock'n G Plumbing services go with this guide?</h2>
        <ul class="service-links">
          <li><a href="/services/sewer-line-repair/">Sewer Line Repair &amp; Camera Inspection <span aria-hidden="true">→</span></a></li>
          <li><a href="/services/gas-line-services/">Gas Line Installation &amp; Repair <span aria-hidden="true">→</span></a></li>
          <li><a href="/services/drain-cleaning/">Drain Cleaning &amp; Clog Removal <span aria-hidden="true">→</span></a></li>
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

    <aside class="dg-rail" aria-label="Guide navigation and contact">
      <nav class="dg-toc" aria-labelledby="dg-toc-title">
        <h2 id="dg-toc-title">In this guide</h2>
        <ol>
          <?php foreach ($toc as $anchor => $label): ?><li><a href="#<?php echo h($anchor); ?>"><?php echo h($label); ?></a></li><?php endforeach; ?>
        </ol>
      </nav>
      <div class="dg-sidecta">
        <h2>Free estimate</h2>
        <p><?php echo h($weekday[0]); ?>, <?php echo h($weekday[1]); ?>. Texas Master Plumber <?php echo h($license['number']); ?>.</p>
        <a class="btn btn-accent" href="/contact/" data-open-estimate>Get a Free Estimate</a>
        <a class="link-call" href="<?php echo h($telHref); ?>"><?php echo icon('phone', 18); ?><?php echo h($phoneFmt); ?></a>
      </div>
    </aside>

  </div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>
