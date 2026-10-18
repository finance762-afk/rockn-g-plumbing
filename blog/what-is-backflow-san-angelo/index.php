<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/functions.php';
?>
<?php
/**
 * blog/what-is-backflow-san-angelo/index.php — migrated post #7 (Oct 18 2026 blog migration, P1 BLOG BOT drafts).
 * 301 from /understanding-backflow-prevention-for-miles-tx-water-systems. Educational explainer only: Rock'n G has no
 * WSP endorsement. For backflow testing, we'll point you to a certified tester. So the schema carries no 'about' Service node.
 * Body pasted from the approved draft; only service hrefs were remapped to /services/<slug>/.
 * No FAQPage schema (Rock'n G SEO brief). Author = Page One Insights LLC (blog-standard.md §7).
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/blog-data.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/service-cards.php';

$bpSlug = 'what-is-backflow-san-angelo';
$post   = blogPostBySlug($bpSlug);

$pageTitle       = $post['title'];
$pageDescription = "What backflow is, why San Angelo and TCEQ require a backflow assembly on lawn sprinklers, the 2021 lesson, and who's licensed to test the device.";
$canonicalUrl    = $siteUrl . '/blog/' . $bpSlug . '/';
$currentPage     = 'blog/' . $bpSlug;
$ogType          = 'article';
$ogImage         = '/assets/images/' . $post['photo'] . '.jpg';
$breadcrumbs     = [['name' => 'Blog', 'url' => '/blog/'], ['name' => $post['title'], 'url' => '/blog/' . $bpSlug . '/']];

$telHref  = 'tel:' . $phoneE164;
$phoneFmt = formatPhone($phone);
$weekday  = hoursGroups($hours)[0];

$toc = [
    'how-water-ends-up-running-backward'  => 'How water ends up running backward',
    'san-angelo-learned-this-the-hard-way' => 'San Angelo learned this the hard way',
    'why-sprinklers-need-protection'      => 'Why sprinklers need protection',
    'the-common-device-types-briefly'     => 'The common device types, briefly',
    'who-can-test-a-backflow-assembly'    => 'Who can test a backflow assembly?',
    'what-homeowners-should-do'           => 'What homeowners should do',
    'where-we-fit-in'                     => 'Where we fit in',
    'frequently-asked-questions'          => 'Frequently asked questions',
];

$schemaMarkup = [[
    "@context" => 'https://schema.org', '@type' => 'BlogPosting',
    '@id' => $canonicalUrl . '#post',
    'headline' => $post['title'],
    'description' => $pageDescription,
    'image' => absUrl($post['image']),
    'datePublished' => $post['dateISO'], 'dateModified' => $post['dateISO'],
    'keywords' => 'backflow prevention San Angelo, sprinkler backflow, backflow test, cross-connection',
    'inLanguage' => 'en-US',
    'author' => ['@type' => 'Organization', 'name' => 'Page One Insights LLC', 'url' => 'https://pageoneinsights.com'],
    'publisher' => ['@id' => orgId()],
    'mainEntityOfPage' => $canonicalUrl,
]];

ob_start();
?>
<style>
/* =====================================================================
   Post: what is backflow — page-only CSS (bf- prefix)
   Tokens from framework.css; no raw colors, shadows or spacing.
   Techniques: layered type hero (gradient + noise), sticky TOC rail, homeowner step track (signature),
   device-type definition cards, 2021 case-study callout, FAQ question cards, tinted source list, related band.
   ===================================================================== */
.bf-hero {
  padding-block-end: var(--space-12);
  background:
    radial-gradient(ellipse 50% 80% at 0% 100%, color-mix(in srgb, var(--color-accent-bright) 20%, transparent), transparent 62%),
    linear-gradient(195deg, var(--color-primary-deep), var(--color-dark));
}
.bf-hero::before {
  content: "";
  position: absolute;
  inset: auto 0 0 0;
  height: var(--space-2);
  pointer-events: none;
  background: linear-gradient(270deg, var(--color-accent-bright), var(--color-accent), transparent);
  opacity: .7;
}
.bf-hero::after {
  content: "";
  position: absolute;
  inset: 0;
  pointer-events: none;
  opacity: .16;
  mix-blend-mode: overlay;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='160' height='160'%3E%3Cfilter id='g'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.8' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23g)'/%3E%3C/svg%3E");
}
.bf-hero .container {
  position: relative;
  z-index: 1;
}
.bf-hero .breadcrumb {
  color: color-mix(in srgb, var(--color-surface) 72%, transparent);
}
.bf-hero .breadcrumb li {
  display: inline-flex;
  align-items: center;
}
.bf-hero .breadcrumb [aria-current="page"] {
  color: var(--color-surface);
}
.bf-hero .hero-title {
  max-width: 24ch;
  text-wrap: balance;
}
.bf-meta {
  display: flex;
  flex-wrap: wrap;
  gap: var(--space-2) var(--space-5);
  margin: var(--space-4) 0 0;
  padding: 0;
  list-style: none;
  font-size: var(--fs-small);
  color: color-mix(in srgb, var(--color-surface) 78%, transparent);
}
.bf-meta li {
  display: inline-flex;
  gap: var(--space-2);
  align-items: center;
}
.bf-meta svg {
  color: var(--color-accent-bright);
}

/* ---------- Layout: article + rail ---------- */
.bf-layout {
  display: grid;
  grid-template-columns: minmax(0, 1fr) 300px;
  gap: clamp(var(--space-8), 5vw, var(--space-16));
  align-items: start;
}
.bf-featured {
  margin: 0 0 var(--space-8);
  box-shadow: var(--shadow);
}
.bf-featured img {
  width: 100%;
  aspect-ratio: 16 / 9;
  object-fit: cover;
}
.bf-rail {
  position: sticky;
  inset-block-start: calc(var(--nav-height) + var(--space-4));
  display: grid;
  gap: var(--space-5);
}
.bf-toc,
.bf-sidecta {
  padding: var(--space-5);
  border-radius: var(--radius-lg);
  border: 1px solid var(--color-line);
}
.bf-toc {
  background: var(--color-card-tint-1);
}
.bf-toc h2 {
  font-size: 1rem;
  margin-block-end: var(--space-3);
}
.bf-toc ol {
  display: grid;
  gap: var(--space-1);
  margin: 0;
  padding: 0;
  list-style: none;
  counter-reset: bf-toc;
}
.bf-toc li {
  counter-increment: bf-toc;
}
.bf-toc a {
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
.bf-toc a::before {
  content: counter(bf-toc, decimal-leading-zero);
  font-family: var(--font-accent);
  letter-spacing: .08em;
  color: var(--color-accent-dark);
}
.bf-toc a:hover {
  color: var(--color-primary);
  border-color: var(--color-accent);
  padding-inline-start: var(--space-2);
}
.bf-sidecta {
  background: var(--color-primary);
  color: var(--color-surface);
  box-shadow: var(--shadow);
}
.bf-sidecta h2 {
  color: var(--color-surface);
  font-size: 1.1rem;
  margin-block-end: var(--space-2);
}
.bf-sidecta p {
  margin: 0 0 var(--space-3);
  font-size: var(--fs-small);
  color: color-mix(in srgb, var(--color-surface) 84%, transparent);
}
.bf-sidecta .btn {
  width: 100%;
  justify-content: center;
}
.bf-sidecta .link-call {
  margin-block-start: var(--space-3);
  color: var(--color-surface);
}
.bf-sidecta .link-call svg {
  color: var(--color-accent-bright);
}

/* ---------- Body ---------- */
.bf-body > p:first-child {
  font-size: var(--fs-lead);
  padding-inline-start: var(--space-5);
  border-inline-start: 4px solid var(--color-accent-bright);
}
.bf-body h2 {
  margin-block: var(--space-12) var(--space-3);
  text-wrap: balance;
  scroll-margin-block-start: calc(var(--nav-height) + var(--space-4));
}
.bf-body h3 {
  text-wrap: balance;
}
.bf-body p a,
.bf-body li a {
  color: var(--color-primary);
  text-decoration-color: var(--color-accent);
  text-underline-offset: 3px;
}
.bf-body ul {
  display: grid;
  gap: var(--space-2);
  margin: var(--space-4) 0;
  padding: 0;
  list-style: none;
}
.bf-body ul > li {
  position: relative;
  padding-inline-start: var(--space-6);
}
.bf-body ul > li::before {
  content: "";
  position: absolute;
  inset-block-start: .65em;
  inset-inline-start: var(--space-1);
  width: var(--space-2);
  height: var(--space-2);
  border-radius: var(--radius-full);
  border: 2px solid var(--color-accent-dark);
}
/* 2021 PaulAnn case study: the two paragraphs under that H2 read as one callout */
.bf-body #san-angelo-learned-this-the-hard-way + p,
.bf-body #san-angelo-learned-this-the-hard-way + p + p {
  margin: 0;
  padding: var(--space-4) var(--space-6);
  background: var(--color-paper-2);
  border-inline-start: 4px solid var(--color-secondary);
}
.bf-body #san-angelo-learned-this-the-hard-way + p {
  border-radius: var(--radius-lg) var(--radius-lg) 0 0;
}
.bf-body #san-angelo-learned-this-the-hard-way + p + p {
  border-radius: 0 0 var(--radius-lg) var(--radius-lg);
  margin-block-end: var(--space-4);
}
/* device types as a 2-up definition grid */
.bf-body #the-common-device-types-briefly + ul {
  grid-template-columns: repeat(2, minmax(0, 1fr));
  gap: var(--space-3);
}
.bf-body #the-common-device-types-briefly + ul > li {
  padding: var(--space-4) var(--space-5);
  border-radius: var(--radius-lg);
  border: 1px solid var(--color-line);
  background: var(--color-card-tint-1);
  font-size: var(--fs-small);
}
.bf-body #the-common-device-types-briefly + ul > li:nth-child(2) {
  background: var(--color-card-tint-2);
}
.bf-body #the-common-device-types-briefly + ul > li:nth-child(3) {
  background: var(--color-card-tint-3);
}
.bf-body #the-common-device-types-briefly + ul > li:nth-child(4) {
  background: var(--color-card-tint-neutral);
}
.bf-body #the-common-device-types-briefly + ul > li::before {
  display: none;
}

/* ---------- Signature: homeowner step track ---------- */
.bf-body ol {
  counter-reset: bf-step;
  display: grid;
  gap: var(--space-3);
  margin: var(--space-5) 0;
  padding: 0;
  list-style: none;
}
.bf-body ol > li {
  counter-increment: bf-step;
  position: relative;
  padding: var(--space-4) var(--space-5) var(--space-4) var(--space-16);
  border-radius: var(--radius-lg);
  background: var(--color-card-tint-1);
  border: 1px solid var(--color-line);
  transition: transform var(--transition), box-shadow var(--transition);
}
.bf-body ol > li:nth-child(3n + 2) {
  background: var(--color-card-tint-2);
}
.bf-body ol > li:nth-child(3n) {
  background: var(--color-card-tint-3);
}
.bf-body ol > li:hover {
  transform: translateX(var(--space-1));
  box-shadow: var(--shadow);
}
.bf-body ol > li::before {
  content: counter(bf-step);
  position: absolute;
  inset-block-start: var(--space-3);
  inset-inline-start: var(--space-4);
  font-family: var(--font-accent);
  font-size: 2.2rem;
  line-height: 1;
  color: var(--color-primary);
}

/* ---------- FAQ: question cards ---------- */
.bf-body #frequently-asked-questions ~ h3 {
  margin-block: var(--space-5) 0;
  padding: var(--space-4) var(--space-5) var(--space-2);
  border-radius: var(--radius-lg) var(--radius-lg) 0 0;
  background: var(--color-paper-2);
  border: 1px solid var(--color-line);
  border-block-end: 0;
  font-size: 1.1rem;
}
.bf-body #frequently-asked-questions ~ h3 + p {
  margin: 0;
  padding: 0 var(--space-5) var(--space-4);
  border-radius: 0 0 var(--radius-lg) var(--radius-lg);
  background: var(--color-paper-2);
  border: 1px solid var(--color-line);
  border-block-start: 0;
}

/* ---------- Owner line + sources ---------- */
.bf-body p > em:only-child {
  display: inline-block;
  padding: var(--space-2) var(--space-4);
  border-radius: var(--radius-full);
  background: var(--color-card-tint-neutral);
  font-size: var(--fs-small);
}
.bf-body #sources {
  font-size: 1.1rem;
}
.bf-body #sources + ul {
  padding: var(--space-4) var(--space-5);
  border-radius: var(--radius);
  background: var(--color-paper-2);
  font-size: var(--fs-small);
  color: var(--color-ink-2);
}
.bf-body #sources + ul a {
  word-break: break-word;
}

/* ---------- Related ---------- */
.bf-related {
  margin-block-start: var(--space-16);
  padding-block-start: var(--space-8);
  border-block-start: 1px solid var(--color-line);
}
.bf-related h2 {
  margin-block-end: var(--space-4);
  text-wrap: balance;
}
.bf-related .service-links {
  display: grid;
  gap: var(--space-2);
  margin: 0 0 var(--space-8);
  padding: 0;
  list-style: none;
}
.bf-related .service-links a {
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
.bf-related .service-links a:hover {
  border-color: var(--color-primary);
  transform: translateX(var(--space-1));
}

@media (max-width: 1000px) {
  .bf-layout {
    grid-template-columns: 1fr;
  }
  .bf-rail {
    position: static;
    order: -1;
    grid-template-columns: 1fr 1fr;
  }
}
@media (max-width: 640px) {
  .bf-rail {
    grid-template-columns: 1fr;
  }
  .bf-body ol > li {
    padding-inline-start: var(--space-12);
  }
  .bf-body #the-common-device-types-briefly + ul {
    grid-template-columns: 1fr;
  }
}
@media (prefers-reduced-motion: reduce) {
  .bf-body ol > li,
  .bf-toc a,
  .bf-related .service-links a {
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
<section class="hero hero--interior bf-hero" aria-labelledby="bf-h1">
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
    <h1 class="hero-title" id="bf-h1"><?php echo h($post['title']); ?></h1>
    <ul class="bf-meta">
      <li><?php echo icon('calendar', 16); ?><time datetime="<?php echo h($post['dateISO']); ?>"><?php echo h($post['date']); ?></time></li>
      <li><?php echo icon('clock', 16); ?><?php echo h($post['readtime']); ?></li>
      <li><?php echo icon('info', 16); ?>Published by Page One Insights LLC</li>
    </ul>
  </div>
</section>

<div class="section bf-wrap">
  <div class="container-wide bf-layout">

    <article class="bf-article" aria-labelledby="bf-h1">
      <figure class="bf-featured blog-post__featured-image">
        <?php echo pictureTag($post['photo'], $post['alt'], '(max-width: 1000px) calc(100vw - 2rem), 780px', ['loading' => 'eager', 'fetchpriority' => 'high']); ?>
      </figure>

      <div class="blog-post__content bf-body">
<p>Backflow is water flowing the wrong way: out of your property's plumbing and back into the public water lines. On a lawn sprinkler system, that water can carry fertilizer, pesticide, pet waste or stagnant water. That's why the City of San Angelo and the state require an approved backflow prevention assembly on irrigation systems, tested by a licensed tester.</p>
<p>That's the short answer. Here's the longer one, in plain English.</p>
<h2 id="how-water-ends-up-running-backward">How water ends up running backward</h2>
<p>Water in the City's mains is normally under pressure, so it only flows one way, into your house. Backflow happens when that changes. There are two ways it happens:</p>
<ul>
<li><strong>Back-siphonage:</strong> pressure in the City's main drops suddenly, from a main break or firefighters pulling a lot of water from a hydrant. The drop can pull water backward out of homes and businesses, like sucking on a straw.</li>
<li><strong>Back-pressure:</strong> pressure on your side gets higher than the supply side, from a pump, a boiler or an elevated tank, and pushes water back into the main.</li>
</ul>
<p>The connection that makes it possible is called a <strong>cross-connection</strong>: any point where drinking water touches something that isn't drinking water. A hose lying in a bucket of weed killer is a cross-connection. So is a sprinkler line sitting in wet, fertilized soil.</p>
<h2 id="san-angelo-learned-this-the-hard-way">San Angelo learned this the hard way</h2>
<p>In February 2021, just as the winter storm hit, residents in and near the PaulAnn area reported water that smelled like chemicals or mothballs. State testing found benzene, acetone and naphthalene in parts of the distribution system. The City issued a do-not-use order. City and state officials said the likely cause was backflow from an industrial customer connected to the system, not the lakes, the river or the treatment plant.</p>
<p>The exact source was never confirmed. The City said the amount of chemical involved may have been less than a gallon. Afterward, state-sent inspectors checked 85 facilities in the PaulAnn and northern industrial areas, sites with inadequate protection were required to add or upgrade backflow devices, and the City said it would hire more customer service inspectors and tighten its cross-connection program citywide.</p>
<p>A home sprinkler is a much smaller risk than an industrial plant. But the principle is the same, and it's why the City takes these devices seriously.</p>
<h2 id="why-sprinklers-need-protection">Why sprinklers need protection</h2>
<p>A sprinkler system sits in the ground. When it shuts off, water sits in the pipes and heads, mixing with soil, lawn chemicals and whatever else is in the yard. If pressure in the main drops, that water could be drawn back toward the meter.</p>
<p>The City's backflow FAQ puts it simply: a lawn irrigation system requires a backflow prevention assembly because it can backflow contaminated water into the drinking water system. The City's sprinkler permit handout lists the approved assemblies for residential sprinkler systems: an atmospheric vacuum breaker, a pressure vacuum breaker or a double check valve assembly.</p>
<p>State rules (30 TAC Chapter 344) add stricter requirements in some situations:</p>
<ul>
<li>If <strong>chemicals are injected</strong> into the irrigation system, or there's <strong>more than one water source</strong> connected, the connection requires a reduced pressure principle assembly (RPZ) or an air gap.</li>
<li>If the property has an <strong>on-site septic system</strong>, the irrigation system is classed as a health hazard and needs an RPZ.</li>
<li>Pressure vacuum breakers have to sit at least 12 inches above the highest downstream sprinkler head or piping.</li>
</ul>
<h2 id="the-common-device-types-briefly">The common device types, briefly</h2>
<ul>
<li><strong>Atmospheric vacuum breaker (AVB):</strong> simple, protects against back-siphonage only. No shutoff valve can be installed after it.</li>
<li><strong>Pressure vacuum breaker (PVB):</strong> testable, protects against back-siphonage. Common on residential sprinklers.</li>
<li><strong>Double check valve assembly (DCVA):</strong> two check valves in a row. Used where there's no health hazard, if local rules allow.</li>
<li><strong>Reduced pressure principle assembly (RPZ):</strong> the highest level of mechanical protection, with a relief valve that dumps water if the checks fail.</li>
</ul>
<p>Which one a property needs depends on the hazard. That's a call for the City or the licensed professional doing the installation, not a guess.</p>
<h2 id="who-can-test-a-backflow-assembly">Who can test a backflow assembly?</h2>
<p>Only a <strong>Backflow Prevention Assembly Tester (BPAT)</strong> licensed by the Texas Commission on Environmental Quality. TCEQ requires every assembly to be tested at installation, and again after any repair, replacement or relocation. Assemblies protecting against a health hazard have to be tested at least once a year after that.</p>
<p>The tester uses calibrated gauges, fills out the TCEQ test report, and the results go to the water utility. The City's sprinkler handout notes that some licensed plumbers and irrigators also hold the tester certification. Others don't, so ask to see the BPAT license.</p>
<p>Good to know: the City of San Angelo's Cross-Connection Control Program includes free inspections of homes and businesses by City Customer Service Inspectors. The inspection is free, but if it finds a problem, fixing it is the owner's cost.</p>
<h2 id="what-homeowners-should-do">What homeowners should do</h2>
<ol>
<li><strong>Find your device.</strong> On most sprinkler systems it's a brass assembly sticking up from the ground near the house or the meter, or in a valve box.</li>
<li><strong>Protect it.</strong> Don't let it get buried by landscaping, and cover it during hard freezes. Exposed brass and valves can crack.</li>
<li><strong>Keep your paperwork.</strong> Save the test report from installation and any later tests.</li>
<li><strong>Use hose-bib vacuum breakers</strong> on outdoor faucets, and don't leave a hose sitting in a pool, bucket or chemical sprayer.</li>
<li><strong>Questions?</strong> Call the City's Water Utilities or Permits &amp; Inspections office (325-657-4210) about what your property needs.</li>
</ol>
<h2 id="where-we-fit-in">Where we fit in</h2>
<p>For backflow testing, we'll point you to a certified tester. For the test itself, you need a TCEQ-licensed BPAT. What we can help with is the rest of your system. If you're dealing with a broken head, a stuck zone or a soggy spot, our <a href="/services/irrigation-sprinkler-repair/">sprinkler repair service</a> and our post on <a href="/blog/sprinkler-repair-watering-rules-san-angelo/">sprinkler repair and San Angelo's watering rules</a> are a good place to start. And if your water meter keeps spinning with everything off, read <a href="/blog/slab-leaks-pipe-repair-san-angelo/">how to spot a hidden leak</a> or see our <a href="/services/leak-detection-repair/">leak detection</a> page.</p>
<p>Questions about other plumbing in San Angelo or Christoval? Call Rock'n G Plumbing at <strong>(325) 650-7000</strong>, Monday through Friday, 8 AM to 6 PM.</p>
<p><em>Rock'n G Plumbing is owned by Eli Gomez, Texas Master Plumber License M-44822.</em></p>
<h2 id="frequently-asked-questions">Frequently asked questions</h2>
<h3>Does my home sprinkler system need a backflow device in San Angelo?</h3>
<p>Yes. The City says lawn irrigation systems require an approved backflow prevention assembly, because they can backflow contaminated water into the drinking water system.</p>
<h3>How often does a residential backflow assembly have to be tested?</h3>
<p>TCEQ requires a test at installation and after any repair, replacement or relocation. Assemblies protecting against a health hazard must be tested at least once a year. Ask the City whether your assembly falls in that group.</p>
<h3>Who is allowed to test a backflow device in Texas?</h3>
<p>Only a Backflow Prevention Assembly Tester licensed by the Texas Commission on Environmental Quality. Some plumbers and irrigators hold that license, but not all of them do.</p>
<h3>Does the City of San Angelo inspect for cross-connections?</h3>
<p>Yes. The City's Cross-Connection Control Program offers free inspections by Customer Service Inspectors. Any repairs they call for are the owner's cost.</p>
<h2 id="sources">Sources</h2>
<ul>
<li>City of San Angelo, Backflow Prevention (Cross-Connection Control Program; TCEQ testing rules): <a rel="nofollow noopener" target="_blank" href="https://www.sanangelo.gov/489/Backflow-Prevention">https://www.sanangelo.gov/489/Backflow-Prevention</a></li>
<li>City of San Angelo, Backflow Prevention FAQs: <a rel="nofollow noopener" target="_blank" href="https://www.sanangelo.gov/m/faq?cat=29">https://www.sanangelo.gov/m/faq?cat=29</a></li>
<li>City of San Angelo Permits &amp; Inspections, Sprinkler System Installation handout: <a rel="nofollow noopener" target="_blank" href="https://www.sanangelo.gov/DocumentCenter/View/209">https://www.sanangelo.gov/DocumentCenter/View/209</a></li>
<li>30 Tex. Admin. Code §344.50 (backflow methods; testing by licensed tester): <a rel="nofollow noopener" target="_blank" href="https://www.law.cornell.edu/regulations/texas/30-Tex-Admin-Code-SS-344-50">https://www.law.cornell.edu/regulations/texas/30-Tex-Admin-Code-SS-344-50</a></li>
<li>30 Tex. Admin. Code §344.51 (chemicals, multiple sources, OSSF): <a rel="nofollow noopener" target="_blank" href="https://www.law.cornell.edu/regulations/texas/30-Tex-Admin-Code-SS-344-51">https://www.law.cornell.edu/regulations/texas/30-Tex-Admin-Code-SS-344-51</a></li>
<li>TCEQ, Backflow Prevention Assembly Tester license: <a rel="nofollow noopener" target="_blank" href="https://www.tceq.texas.gov/licensing/licenses/bpatlic">https://www.tceq.texas.gov/licensing/licenses/bpatlic</a></li>
<li>TCEQ, RG-478 Establishing and Managing an Effective Cross-Connection Control Program: <a rel="nofollow noopener" target="_blank" href="https://www.tceq.texas.gov/downloads/drinking-water/plan-technical-review/prevention/rg-478.pdf">https://www.tceq.texas.gov/downloads/drinking-water/plan-technical-review/prevention/rg-478.pdf</a></li>
<li>Texas Tribune, "Industrial pollutants leave San Angelo residents without water," Feb 12, 2021: <a rel="nofollow noopener" target="_blank" href="https://www.texastribune.org/2021/02/12/san-angelo-water-contamination/">https://www.texastribune.org/2021/02/12/san-angelo-water-contamination/</a></li>
<li>San Angelo LIVE!, "An Update on the San Angelo Water Investigation," Mar 16, 2021: <a rel="nofollow noopener" target="_blank" href="https://sanangelolive.com/news/san-angelo/2021-03-16/update-san-angelo-water-investigation">https://sanangelolive.com/news/san-angelo/2021-03-16/update-san-angelo-water-investigation</a></li>
</ul>
      </div>

      <div class="bf-related blog-post__related-services">
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

    <aside class="bf-rail" aria-label="Guide navigation and contact">
      <nav class="bf-toc" aria-labelledby="bf-toc-title">
        <h2 id="bf-toc-title">In this guide</h2>
        <ol>
          <?php foreach ($toc as $anchor => $label): ?><li><a href="#<?php echo h($anchor); ?>"><?php echo h($label); ?></a></li><?php endforeach; ?>
        </ol>
      </nav>
      <div class="bf-sidecta">
        <h2>Free estimate</h2>
        <p><?php echo h($weekday[0]); ?>, <?php echo h($weekday[1]); ?>. Texas Master Plumber <?php echo h($license['number']); ?>.</p>
        <a class="btn btn-accent" href="/contact/" data-open-estimate>Get a Free Estimate</a>
        <a class="link-call" href="<?php echo h($telHref); ?>"><?php echo icon('phone', 18); ?><?php echo h($phoneFmt); ?></a>
      </div>
    </aside>

  </div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>
