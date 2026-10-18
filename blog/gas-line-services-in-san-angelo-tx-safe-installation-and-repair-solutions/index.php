<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/functions.php';
?>
<?php
/**
 * blog/gas-line-services-in-san-angelo-tx-safe-installation-and-repair-solutions/index.php — migrated post #3
 * (Oct 18 2026 blog migration, P1 BLOG BOT drafts). Old root slug kept under /blog/; the root URL 301s here.
 * Body pasted from the approved draft; only service hrefs were remapped to /services/<slug>/.
 * No FAQPage schema (Rock'n G SEO brief). Author = Page One Insights LLC (blog-standard.md §7).
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/blog-data.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/service-cards.php';

$bpSlug = 'gas-line-services-in-san-angelo-tx-safe-installation-and-repair-solutions';
$post   = blogPostBySlug($bpSlug);

$pageTitle       = $post['title'];
$pageDescription = 'Smell gas? Adding a gas range? A San Angelo plumber explains leak signs, which gas pipes you own, permits, Atmos service releases and clay soil.';
$canonicalUrl    = $siteUrl . '/blog/' . $bpSlug . '/';
$currentPage     = 'blog/' . $bpSlug;
$ogType          = 'article';
$ogImage         = '/assets/images/' . $post['photo'] . '.jpg';
$breadcrumbs     = [['name' => 'Blog', 'url' => '/blog/'], ['name' => $post['title'], 'url' => '/blog/' . $bpSlug . '/']];

$telHref  = 'tel:' . $phoneE164;
$phoneFmt = formatPhone($phone);
$weekday  = hoursGroups($hours)[0];

$toc = [
    'if-you-smell-gas-do-this-first'                => 'If you smell gas, do this first',
    'quieter-warning-signs'                         => 'Quieter warning signs',
    'the-pipe-you-didn-t-know-you-owned'            => "The pipe you didn't know you owned",
    'adding-a-gas-appliance'                        => 'Adding a gas appliance',
    'who-s-allowed-to-work-on-gas-piping-in-texas'  => "Who's allowed to work on gas piping in Texas?",
    'how-often-should-gas-lines-be-checked'         => 'How often should gas lines be checked?',
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
    'keywords' => 'gas line repair San Angelo, gas line installation, gas leak signs, buried gas line, CSST bonding',
    'inLanguage' => 'en-US',
    'author' => ['@type' => 'Organization', 'name' => 'Page One Insights LLC', 'url' => 'https://pageoneinsights.com'],
    'publisher' => ['@id' => orgId()],
    'mainEntityOfPage' => $canonicalUrl,
    'about' => ['@type' => 'Service', 'name' => 'Gas Line Installation & Repair', 'url' => absUrl('/services/gas-line-services/'), 'provider' => ['@id' => orgId()]],
]];

ob_start();
?>
<style>
/* =====================================================================
   Post: gas line safety — page-only CSS (gl- prefix)
   Tokens from framework.css; no raw colors, shadows or spacing.
   Techniques: layered type hero (gradient + noise), sticky TOC rail, numbered step track (signature),
   FAQ question cards, tinted source list, related band.
   ===================================================================== */
.gl-hero {
  padding-block-end: var(--space-12);
  background:
    radial-gradient(ellipse 55% 90% at 0% 0%, color-mix(in srgb, var(--color-accent-bright) 22%, transparent), transparent 62%),
    linear-gradient(165deg, var(--color-primary-deep), var(--color-dark));
}
.gl-hero::before {
  content: "";
  position: absolute;
  inset: auto 0 0 0;
  height: var(--space-2);
  pointer-events: none;
  background: linear-gradient(90deg, transparent, var(--color-accent-bright), var(--color-accent));
  opacity: .7;
}
.gl-hero::after {
  content: "";
  position: absolute;
  inset: 0;
  pointer-events: none;
  opacity: .16;
  mix-blend-mode: overlay;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='160' height='160'%3E%3Cfilter id='g'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.8' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23g)'/%3E%3C/svg%3E");
}
.gl-hero .container {
  position: relative;
  z-index: 1;
}
.gl-hero .breadcrumb {
  color: color-mix(in srgb, var(--color-surface) 72%, transparent);
}
.gl-hero .breadcrumb li {
  display: inline-flex;
  align-items: center;
}
.gl-hero .breadcrumb [aria-current="page"] {
  color: var(--color-surface);
}
.gl-hero .hero-title {
  max-width: 24ch;
  text-wrap: balance;
}
.gl-meta {
  display: flex;
  flex-wrap: wrap;
  gap: var(--space-2) var(--space-5);
  margin: var(--space-4) 0 0;
  padding: 0;
  list-style: none;
  font-size: var(--fs-small);
  color: color-mix(in srgb, var(--color-surface) 78%, transparent);
}
.gl-meta li {
  display: inline-flex;
  gap: var(--space-2);
  align-items: center;
}
.gl-meta svg {
  color: var(--color-accent-bright);
}

/* ---------- Layout: article + rail ---------- */
.gl-layout {
  display: grid;
  grid-template-columns: minmax(0, 1fr) 300px;
  gap: clamp(var(--space-8), 5vw, var(--space-16));
  align-items: start;
}
.gl-featured {
  margin: 0 0 var(--space-8);
  box-shadow: var(--shadow);
}
.gl-featured img {
  width: 100%;
  aspect-ratio: 16 / 9;
  object-fit: cover;
}
.gl-rail {
  position: sticky;
  inset-block-start: calc(var(--nav-height) + var(--space-4));
  display: grid;
  gap: var(--space-5);
}
.gl-toc,
.gl-sidecta {
  padding: var(--space-5);
  border-radius: var(--radius-lg);
  border: 1px solid var(--color-line);
}
.gl-toc {
  background: var(--color-card-tint-3);
}
.gl-toc h2 {
  font-size: 1rem;
  margin-block-end: var(--space-3);
}
.gl-toc ol {
  display: grid;
  gap: var(--space-1);
  margin: 0;
  padding: 0;
  list-style: none;
  counter-reset: gl-toc;
}
.gl-toc li {
  counter-increment: gl-toc;
}
.gl-toc a {
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
.gl-toc a::before {
  content: counter(gl-toc, decimal-leading-zero);
  font-family: var(--font-accent);
  letter-spacing: .08em;
  color: var(--color-accent-dark);
}
.gl-toc a:hover {
  color: var(--color-primary);
  border-color: var(--color-accent);
  padding-inline-start: var(--space-2);
}
.gl-sidecta {
  background: var(--color-primary);
  color: var(--color-surface);
  box-shadow: var(--shadow);
}
.gl-sidecta h2 {
  color: var(--color-surface);
  font-size: 1.1rem;
  margin-block-end: var(--space-2);
}
.gl-sidecta p {
  margin: 0 0 var(--space-3);
  font-size: var(--fs-small);
  color: color-mix(in srgb, var(--color-surface) 84%, transparent);
}
.gl-sidecta .btn {
  width: 100%;
  justify-content: center;
}
.gl-sidecta .link-call {
  margin-block-start: var(--space-3);
  color: var(--color-surface);
}
.gl-sidecta .link-call svg {
  color: var(--color-accent-bright);
}

/* ---------- Body ---------- */
.gl-body > p:first-child {
  font-size: var(--fs-lead);
  padding: var(--space-4) var(--space-5);
  border-radius: var(--radius-lg);
  border-inline-start: 4px solid var(--color-secondary);
  background: color-mix(in srgb, var(--color-secondary) 8%, var(--color-surface));
}
.gl-body h2 {
  margin-block: var(--space-12) var(--space-3);
  text-wrap: balance;
  scroll-margin-block-start: calc(var(--nav-height) + var(--space-4));
}
.gl-body h3 {
  text-wrap: balance;
}
.gl-body p a,
.gl-body li a {
  color: var(--color-primary);
  text-decoration-color: var(--color-accent);
  text-underline-offset: 3px;
}
.gl-body ul {
  display: grid;
  gap: var(--space-2);
  margin: var(--space-4) 0;
  padding: 0;
  list-style: none;
}
.gl-body ul > li {
  position: relative;
  padding-inline-start: var(--space-6);
}
.gl-body ul > li::before {
  content: "";
  position: absolute;
  inset-block-start: .65em;
  inset-inline-start: var(--space-1);
  width: var(--space-2);
  height: var(--space-2);
  border-radius: var(--radius-full);
  background: var(--color-accent-dark);
}

/* ---------- Signature: numbered step track (the "smell gas" steps) ---------- */
.gl-body ol {
  counter-reset: gl-step;
  display: grid;
  gap: var(--space-3);
  margin: var(--space-5) 0;
  padding: 0;
  list-style: none;
}
.gl-body ol > li {
  counter-increment: gl-step;
  position: relative;
  padding: var(--space-4) var(--space-5) var(--space-4) var(--space-16);
  border-radius: var(--radius-lg);
  background: var(--color-card-tint-3);
  border: 1px solid var(--color-line);
  border-inline-start: 4px solid var(--color-secondary);
  transition: transform var(--transition), box-shadow var(--transition);
}
.gl-body ol > li:nth-child(even) {
  background: var(--color-card-tint-1);
}
.gl-body ol > li:hover {
  transform: translateX(var(--space-1));
  box-shadow: var(--shadow);
}
.gl-body ol > li::before {
  content: counter(gl-step);
  position: absolute;
  inset-block-start: var(--space-3);
  inset-inline-start: var(--space-4);
  font-family: var(--font-accent);
  font-size: 2.2rem;
  line-height: 1;
  color: var(--color-secondary);
}

/* ---------- FAQ: question cards ---------- */
.gl-body #frequently-asked-questions ~ h3 {
  margin-block: var(--space-5) 0;
  padding: var(--space-4) var(--space-5) var(--space-2);
  border-radius: var(--radius-lg) var(--radius-lg) 0 0;
  background: var(--color-paper-2);
  border: 1px solid var(--color-line);
  border-block-end: 0;
  font-size: 1.1rem;
}
.gl-body #frequently-asked-questions ~ h3 + p {
  margin: 0;
  padding: 0 var(--space-5) var(--space-4);
  border-radius: 0 0 var(--radius-lg) var(--radius-lg);
  background: var(--color-paper-2);
  border: 1px solid var(--color-line);
  border-block-start: 0;
}

/* ---------- Owner line + sources ---------- */
.gl-body p > em:only-child {
  display: inline-block;
  padding: var(--space-2) var(--space-4);
  border-radius: var(--radius-full);
  background: var(--color-card-tint-neutral);
  font-size: var(--fs-small);
}
.gl-body #sources {
  font-size: 1.1rem;
}
.gl-body #sources + ul {
  padding: var(--space-4) var(--space-5);
  border-radius: var(--radius);
  background: var(--color-paper-2);
  font-size: var(--fs-small);
  color: var(--color-ink-2);
}
.gl-body #sources + ul a {
  word-break: break-word;
}

/* ---------- Related ---------- */
.gl-related {
  margin-block-start: var(--space-16);
  padding-block-start: var(--space-8);
  border-block-start: 1px solid var(--color-line);
}
.gl-related h2 {
  margin-block-end: var(--space-4);
  text-wrap: balance;
}
.gl-related .service-links {
  display: grid;
  gap: var(--space-2);
  margin: 0 0 var(--space-8);
  padding: 0;
  list-style: none;
}
.gl-related .service-links a {
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
.gl-related .service-links a:hover {
  border-color: var(--color-primary);
  transform: translateX(var(--space-1));
}

@media (max-width: 1000px) {
  .gl-layout {
    grid-template-columns: 1fr;
  }
  .gl-rail {
    position: static;
    order: -1;
    grid-template-columns: 1fr 1fr;
  }
}
@media (max-width: 640px) {
  .gl-rail {
    grid-template-columns: 1fr;
  }
  .gl-body ol > li {
    padding-inline-start: var(--space-12);
  }
}
@media (prefers-reduced-motion: reduce) {
  .gl-body ol > li,
  .gl-toc a,
  .gl-related .service-links a {
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
<section class="hero hero--interior gl-hero" aria-labelledby="gl-h1">
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
    <h1 class="hero-title" id="gl-h1"><?php echo h($post['title']); ?></h1>
    <ul class="gl-meta">
      <li><?php echo icon('calendar', 16); ?><time datetime="<?php echo h($post['dateISO']); ?>"><?php echo h($post['date']); ?></time></li>
      <li><?php echo icon('clock', 16); ?><?php echo h($post['readtime']); ?></li>
      <li><?php echo icon('info', 16); ?>Published by Page One Insights LLC</li>
    </ul>
  </div>
</section>

<div class="section gl-wrap">
  <div class="container-wide gl-layout">

    <article class="gl-article" aria-labelledby="gl-h1">
      <figure class="gl-featured blog-post__featured-image">
        <?php echo pictureTag($post['photo'], $post['alt'], '(max-width: 1000px) calc(100vw - 2rem), 780px', ['loading' => 'eager', 'fetchpriority' => 'high']); ?>
      </figure>

      <div class="blog-post__content gl-body">
<p>If you smell gas, leave the house and call 911 and Atmos Energy from outside. For everything else, from quieter warning signs to adding a gas appliance to a buried line in your yard, start with this: Atmos owns the pipe up to the meter, and you own everything after it.</p>
<h2 id="if-you-smell-gas-do-this-first">If you smell gas, do this first</h2>
<p>Natural gas has no smell on its own. Utilities add an odorant (mercaptan) so a leak smells like rotten eggs or sulfur. If you smell it inside:</p>
<ol>
<li><strong>Leave.</strong> Get everyone out, pets included.</li>
<li><strong>Don't create a spark.</strong> Don't flip light switches, unplug anything, use the phone inside, start a car in the garage or open an electric garage door.</li>
<li><strong>Call from outside, at a safe distance.</strong> Call 911 and Atmos Energy's emergency line.</li>
<li><strong>Don't hunt for the leak</strong> or try to fix it yourself.</li>
</ol>
<p>A gas odor is a 911 and Atmos call first, every time. A plumber comes <em>after</em> the utility has made the area safe, to find and repair the problem in your piping.</p>
<h2 id="quieter-warning-signs">Quieter warning signs</h2>
<p>Not every gas problem announces itself with a strong smell. Things to watch for, based on utility and pipeline-safety guidance:</p>
<ul>
<li>A <strong>hissing</strong> sound near a gas line, meter or appliance</li>
<li>A <strong>yellow or orange flame</strong> on the stove, water heater or furnace instead of a steady blue. That points to poor combustion, which can mean carbon monoxide</li>
<li>A <strong>pilot light that keeps going out</strong></li>
<li>Outside: a <strong>patch of dead grass</strong> over a buried line, <strong>bubbling</strong> in a puddle, or <strong>dirt blowing</strong> up from the ground</li>
</ul>
<p>Any of these is worth a call. Put a carbon monoxide alarm near the bedrooms if you have gas appliances, too.</p>
<h2 id="the-pipe-you-didn-t-know-you-owned">The pipe you didn't know you owned</h2>
<p>This surprises a lot of people. Atmos Energy owns and maintains the gas system <strong>up to the meter</strong>. Everything after the meter is yours: the pipe into the house, the pipe to each appliance, and any buried line running to a detached garage, a pool heater, a shop or an outdoor grill.</p>
<p>Atmos is required by federal rule (49 CFR 192.16) to tell customers this, and its notice says buried customer piping should be checked periodically for leaks and, if it's metal, for corrosion. When you call 811 before digging, the markers mark the <em>utility's</em> lines. They don't mark your private line from the meter to the shop. A plumber can locate it. If a drain repair already has your yard open, that's a good time to check it. See <a href="/blog/drain-repair-and-gas-line-work-in-san-angelo-tx-what-to-expect-when-you-need-both/">drain repair and gas line work in the same job</a>.</p>
<p>That matters here because of what's under us. A large share of Tom Green County is clay soil that swells when wet and shrinks when dry. USDA soil descriptions of the common Angelo series note high shrink-swell potential. Year after year that movement stresses buried pipe, especially older steel lines with threaded joints. Caliche layers make digging harder and can leave sharp rock against a pipe if the trench isn't bedded right.</p>
<h2 id="adding-a-gas-appliance">Adding a gas appliance</h2>
<p>A lot of gas work isn't about leaks at all. It's about projects, often as part of a <a href="/services/new-construction-remodel-plumbing/">remodel or new build</a> (our post on <a href="/blog/new-construction-vs-remodel-plumbing-in-san-angelo-tx-what-s-different-and-why-it-matters/">new construction vs. remodel plumbing</a> covers how gas fits each one): a new gas range, moving a dryer, a line out to an outdoor kitchen, or a backup generator.</p>
<p>What a proper job involves:</p>
<ul>
<li><strong>Sizing.</strong> Every appliance has a BTU rating. The pipe has to carry enough gas for everything that might run at once, over the full distance from the meter. Undersized pipe starves appliances, and you get weak flames and pilots that drop out.</li>
<li><strong>Material.</strong> Black steel pipe is the long-time standard. Corrugated stainless steel tubing (CSST) is flexible and easier to run through finished walls. Under the 2021 International Fuel Gas Code, which San Angelo has adopted, standard yellow-jacketed CSST has to be bonded to the home's electrical grounding system, to protect against arcing from nearby lightning. Arc-resistant (black-jacketed) CSST has its own rule. Ask which one you're getting and how it's bonded.</li>
<li><strong>Pressure test.</strong> New piping gets tested under pressure before it's put in service, to prove it's tight.</li>
<li><strong>Permit and inspection.</strong> In San Angelo, a plumbing permit and a passing City inspection are required for all gas work. The City won't release gas service to Atmos until the inspection passes.</li>
</ul>
<h2 id="who-s-allowed-to-work-on-gas-piping-in-texas">Who's allowed to work on gas piping in Texas?</h2>
<p>In Texas, natural gas piping in homes falls under the plumbing license law, overseen by the Texas State Board of Plumbing Examiners (TSBPE). A company that advertises plumbing work has to have a Responsible Master Plumber, and that license number should be in its ads. Master plumber numbers start with "M." You can look one up on the TSBPE website. (Homeowners can do some work on their own homestead, but in San Angelo you'd still need a homeowner permit and inspection.)</p>
<p>The Railroad Commission of Texas oversees gas <em>utility</em> and pipeline safety, meaning the distribution system up to your meter. It also licenses LP-gas (propane) work, which is a separate trade. If your home runs on propane, a different set of rules and licenses applies.</p>
<h2 id="how-often-should-gas-lines-be-checked">How often should gas lines be checked?</h2>
<p>No rule says every few years for a typical house, but it's smart to have gas piping looked at:</p>
<ul>
<li>When you <strong>buy an older home</strong></li>
<li>When you're <strong>adding or moving</strong> an appliance</li>
<li>If you have a <strong>buried line</strong> to an outbuilding or pool heater that's never been checked</li>
<li>After <strong>foundation work</strong> or major settling</li>
</ul>
<h2 id="when-to-call">When to call</h2>
<p>For anything involving gas piping in San Angelo or Christoval, from a new range line to a buried line you're not sure about, call Rock'n G Plumbing at <strong>(325) 650-7000</strong>, Monday through Friday, 8 AM to 6 PM. If you smell gas right now, leave the house and call 911 and Atmos Energy first. More on our <a href="/services/gas-line-services/">gas line services</a>.</p>
<p><em>Rock'n G Plumbing is owned by Eli Gomez, Texas Master Plumber License M-44822.</em></p>
<h2 id="frequently-asked-questions">Frequently asked questions</h2>
<h3>What should I do if I smell gas in my house?</h3>
<p>Leave right away, don't create a spark (no light switches, phones or garage doors), and call 911 and Atmos Energy from a safe distance outside. A plumber repairs your piping after the area is safe.</p>
<h3>Who owns the gas line between the meter and my house?</h3>
<p>You do. Atmos Energy maintains the system up to the meter. Piping after the meter, including buried lines to outbuildings, pool heaters or grills, is the customer's to maintain.</p>
<h3>Do I need a permit to add a gas line in San Angelo?</h3>
<p>Yes. The City requires a plumbing permit and a passing inspection for all gas work, and it won't release gas service to Atmos until the inspection passes.</p>
<h3>Does CSST gas tubing need to be bonded?</h3>
<p>Under the 2021 International Fuel Gas Code, which San Angelo has adopted, standard yellow-jacketed CSST must be bonded to the home's electrical grounding system. Arc-resistant CSST has its own rule.</p>
<h2 id="sources">Sources</h2>
<ul>
<li>Atmos Energy, Home Safety (customer-owned lines; 49 CFR 192.16 notice; 811): <a rel="nofollow noopener" target="_blank" href="https://www.atmosenergy.com/safety/home-safety/">https://www.atmosenergy.com/safety/home-safety/</a></li>
<li>Railroad Commission of Texas, Gas Leaks Public Education (rotten-egg smell, hissing, what to do): <a rel="nofollow noopener" target="_blank" href="https://www.rrc.texas.gov/media/c3eprnwz/gasleakspubliceducation.pdf">https://www.rrc.texas.gov/media/c3eprnwz/gasleakspubliceducation.pdf</a></li>
<li>Railroad Commission of Texas, Pipeline Safety (jurisdiction over distribution lines): <a rel="nofollow noopener" target="_blank" href="https://www.rrc.state.tx.us/pipeline-safety/">https://www.rrc.state.tx.us/pipeline-safety/</a></li>
<li>PHMSA, Pipeline Leak Recognition and What to Do (mercaptan, dead vegetation, bubbling, no ignition sources): <a rel="nofollow noopener" target="_blank" href="https://www.phmsa.dot.gov/safety-awareness/pipeline/pipeline-leak-recognition-and-what-do">https://www.phmsa.dot.gov/safety-awareness/pipeline/pipeline-leak-recognition-and-what-do</a></li>
<li>Texas Gas Service, Detecting a Leak (yellow flame warning sign): <a rel="nofollow noopener" target="_blank" href="https://www.texasgasservice.com/safety/how-to-detect-aleak">https://www.texasgasservice.com/safety/how-to-detect-aleak</a></li>
<li>City of San Angelo, Building Permits &amp; Inspections (permit and inspection required for all gas work; release to Atmos): <a rel="nofollow noopener" target="_blank" href="https://www.sanangelo.gov/185/Building-Permits-Inspections">https://www.sanangelo.gov/185/Building-Permits-Inspections</a></li>
<li>City of San Angelo, Codes &amp; Regulations (2021 IFGC): <a rel="nofollow noopener" target="_blank" href="https://www.sanangelo.gov/188/Codes-Regulations">https://www.sanangelo.gov/188/Codes-Regulations</a></li>
<li>ICC CodeNotes, Bonding of CSST Gas Piping Systems (2021 IFGC 310.2/310.3): <a rel="nofollow noopener" target="_blank" href="https://www.iccsafe.org/building-safety-journal/bsj-technical/codenotes-bonding-of-corrugated-stainless-steel-tubing-gas-piping-systems/">https://www.iccsafe.org/building-safety-journal/bsj-technical/codenotes-bonding-of-corrugated-stainless-steel-tubing-gas-piping-systems/</a></li>
<li>TSBPE, Consumer Information (RMP license in ads, "M" numbers, license lookup): <a rel="nofollow noopener" target="_blank" href="https://tsbpe.texas.gov/consumer-information/">https://tsbpe.texas.gov/consumer-information/</a></li>
<li>TSBPE, Plumbing License Law (gas piping within plumbing; LP-gas exemption): <a rel="nofollow noopener" target="_blank" href="https://tsbpe.texas.gov/wp-content/uploads/documents/TSBPE_PlumbingLicenseLaw%28PlainView%29_Sept2025.pdf">https://tsbpe.texas.gov/wp-content/uploads/documents/TSBPE_PlumbingLicenseLaw%28PlainView%29_Sept2025.pdf</a></li>
<li>USDA NRCS Official Series Description, Angelo series: <a rel="nofollow noopener" target="_blank" href="https://soilseries.sc.egov.usda.gov/OSD_Docs/A/Angelo.html">https://soilseries.sc.egov.usda.gov/OSD_Docs/A/Angelo.html</a></li>
</ul>
      </div>

      <div class="gl-related blog-post__related-services">
        <h2>Which Rock'n G Plumbing services go with this guide?</h2>
        <ul class="service-links">
          <li><a href="/services/gas-line-services/">Gas Line Installation &amp; Repair <span aria-hidden="true">→</span></a></li>
          <li><a href="/services/new-construction-remodel-plumbing/">New Construction &amp; Remodel Plumbing <span aria-hidden="true">→</span></a></li>
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

    <aside class="gl-rail" aria-label="Guide navigation and contact">
      <nav class="gl-toc" aria-labelledby="gl-toc-title">
        <h2 id="gl-toc-title">In this guide</h2>
        <ol>
          <?php foreach ($toc as $anchor => $label): ?><li><a href="#<?php echo h($anchor); ?>"><?php echo h($label); ?></a></li><?php endforeach; ?>
        </ol>
      </nav>
      <div class="gl-sidecta">
        <h2>Free estimate</h2>
        <p><?php echo h($weekday[0]); ?>, <?php echo h($weekday[1]); ?>. Texas Master Plumber <?php echo h($license['number']); ?>.</p>
        <a class="btn btn-accent" href="/contact/" data-open-estimate>Get a Free Estimate</a>
        <a class="link-call" href="<?php echo h($telHref); ?>"><?php echo icon('phone', 18); ?><?php echo h($phoneFmt); ?></a>
      </div>
    </aside>

  </div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>
