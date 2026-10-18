<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/functions.php';
?>
<?php
/**
 * blog/water-heater-installation-and-repair-services-available-in-christoval-tx/index.php — migrated post #8
 * (Oct 18 2026 blog migration, P1 BLOG BOT drafts). Old root slug kept under /blog/; the root URL 301s here.
 * Body pasted from the approved draft; only service hrefs were remapped to /services/<slug>/, and the two
 * "Usually worth repairing / Usually time to replace" lists (markdown that leaked into a <p>) are rendered as real <ul>s.
 * No FAQPage schema (Rock'n G SEO brief). Author = Page One Insights LLC (blog-standard.md §7).
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/blog-data.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/service-cards.php';

$bpSlug = 'water-heater-installation-and-repair-services-available-in-christoval-tx';
$post   = blogPostBySlug($bpSlug);

$pageTitle       = $post['title'];
$pageDescription = 'Rumbling tank, rusty water, not enough hot water? Signs your Christoval or San Angelo water heater needs repair or replacement, and upkeep that helps.';
$canonicalUrl    = $siteUrl . '/blog/' . $bpSlug . '/';
$currentPage     = 'blog/' . $bpSlug;
$ogType          = 'article';
$ogImage         = '/assets/images/' . $post['photo'] . '.jpg';
$breadcrumbs     = [['name' => 'Blog', 'url' => '/blog/'], ['name' => $post['title'], 'url' => '/blog/' . $bpSlug . '/']];

$telHref  = 'tel:' . $phoneE164;
$phoneFmt = formatPhone($phone);
$weekday  = hoursGroups($hours)[0];

$toc = [
    'where-christoval-s-water-comes-from-and-why-it-matters' => "Where Christoval's water comes from, and why it matters",
    'signs-it-s-time-to-call'                 => "Signs it's time to call",
    'repair-or-replace'                       => 'Repair or replace?',
    'upkeep-that-actually-extends-tank-life'  => 'Upkeep that actually extends tank life',
    'picking-the-right-replacement'           => 'Picking the right replacement',
    'a-note-on-hidden-leaks'                  => 'A note on hidden leaks',
    'when-to-call'                            => 'When to call',
    'frequently-asked-questions'              => 'Frequently asked questions',
];

$schemaMarkup = [[
    "@context" => 'https://schema.org', '@type' => 'BlogPosting',
    '@id' => $canonicalUrl . '#post',
    'headline' => $post['title'],
    'description' => $pageDescription,
    'image' => absUrl($post['image']),
    'datePublished' => $post['dateISO'], 'dateModified' => $post['dateISO'],
    'keywords' => 'water heater repair Christoval, water heater replacement, water heater San Angelo, hard water water heater',
    'inLanguage' => 'en-US',
    'author' => ['@type' => 'Organization', 'name' => 'Page One Insights LLC', 'url' => 'https://pageoneinsights.com'],
    'publisher' => ['@id' => orgId()],
    'mainEntityOfPage' => $canonicalUrl,
    'about' => ['@type' => 'Service', 'name' => 'Water Heater Repair & Installation', 'url' => absUrl('/services/water-heaters/'), 'provider' => ['@id' => orgId()]],
]];

ob_start();
?>
<style>
/* =====================================================================
   Post: Christoval water heater repair or replace — page-only CSS (wh- prefix)
   Tokens from framework.css; no raw colors, shadows or spacing.
   Techniques: layered type hero (gradient + noise), sticky TOC rail, warning-sign grid (signature),
   repair-vs-replace verdict cards, FAQ question cards, tinted source list, related band.
   ===================================================================== */
.wh-hero {
  padding-block-end: var(--space-12);
  background:
    radial-gradient(ellipse 50% 90% at 100% 0%, color-mix(in srgb, var(--color-secondary) 18%, transparent), transparent 60%),
    radial-gradient(ellipse 45% 70% at 0% 100%, color-mix(in srgb, var(--color-accent) 18%, transparent), transparent 66%),
    linear-gradient(165deg, var(--color-primary-deep), var(--color-dark));
}
.wh-hero::before {
  content: "";
  position: absolute;
  inset: auto 0 0 0;
  height: var(--space-2);
  pointer-events: none;
  background: linear-gradient(90deg, var(--color-accent), var(--color-secondary), transparent);
  opacity: .7;
}
.wh-hero::after {
  content: "";
  position: absolute;
  inset: 0;
  pointer-events: none;
  opacity: .16;
  mix-blend-mode: overlay;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='160' height='160'%3E%3Cfilter id='g'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.8' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23g)'/%3E%3C/svg%3E");
}
.wh-hero .container {
  position: relative;
  z-index: 1;
}
.wh-hero .breadcrumb {
  color: color-mix(in srgb, var(--color-surface) 72%, transparent);
}
.wh-hero .breadcrumb li {
  display: inline-flex;
  align-items: center;
}
.wh-hero .breadcrumb [aria-current="page"] {
  color: var(--color-surface);
}
.wh-hero .hero-title {
  max-width: 24ch;
  text-wrap: balance;
}
.wh-meta {
  display: flex;
  flex-wrap: wrap;
  gap: var(--space-2) var(--space-5);
  margin: var(--space-4) 0 0;
  padding: 0;
  list-style: none;
  font-size: var(--fs-small);
  color: color-mix(in srgb, var(--color-surface) 78%, transparent);
}
.wh-meta li {
  display: inline-flex;
  gap: var(--space-2);
  align-items: center;
}
.wh-meta svg {
  color: var(--color-accent-bright);
}

/* ---------- Layout: article + rail ---------- */
.wh-layout {
  display: grid;
  grid-template-columns: minmax(0, 1fr) 300px;
  gap: clamp(var(--space-8), 5vw, var(--space-16));
  align-items: start;
}
.wh-featured {
  margin: 0 0 var(--space-8);
  box-shadow: var(--shadow);
}
.wh-featured img {
  width: 100%;
  aspect-ratio: 16 / 9;
  object-fit: cover;
}
.wh-rail {
  position: sticky;
  inset-block-start: calc(var(--nav-height) + var(--space-4));
  display: grid;
  gap: var(--space-5);
}
.wh-toc,
.wh-sidecta {
  padding: var(--space-5);
  border-radius: var(--radius-lg);
  border: 1px solid var(--color-line);
}
.wh-toc {
  background: var(--color-card-tint-2);
}
.wh-toc h2 {
  font-size: 1rem;
  margin-block-end: var(--space-3);
}
.wh-toc ol {
  display: grid;
  gap: var(--space-1);
  margin: 0;
  padding: 0;
  list-style: none;
  counter-reset: wh-toc;
}
.wh-toc li {
  counter-increment: wh-toc;
}
.wh-toc a {
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
.wh-toc a::before {
  content: counter(wh-toc, decimal-leading-zero);
  font-family: var(--font-accent);
  letter-spacing: .08em;
  color: var(--color-accent-dark);
}
.wh-toc a:hover {
  color: var(--color-primary);
  border-color: var(--color-accent);
  padding-inline-start: var(--space-2);
}
.wh-sidecta {
  background: var(--color-primary);
  color: var(--color-surface);
  box-shadow: var(--shadow);
}
.wh-sidecta h2 {
  color: var(--color-surface);
  font-size: 1.1rem;
  margin-block-end: var(--space-2);
}
.wh-sidecta p {
  margin: 0 0 var(--space-3);
  font-size: var(--fs-small);
  color: color-mix(in srgb, var(--color-surface) 84%, transparent);
}
.wh-sidecta .btn {
  width: 100%;
  justify-content: center;
}
.wh-sidecta .link-call {
  margin-block-start: var(--space-3);
  color: var(--color-surface);
}
.wh-sidecta .link-call svg {
  color: var(--color-accent-bright);
}

/* ---------- Body ---------- */
.wh-body > p:first-child {
  font-size: var(--fs-lead);
  padding-inline-start: var(--space-5);
  border-inline-start: 4px solid var(--color-accent);
}
.wh-body h2 {
  margin-block: var(--space-12) var(--space-3);
  text-wrap: balance;
  scroll-margin-block-start: calc(var(--nav-height) + var(--space-4));
}
.wh-body h3 {
  text-wrap: balance;
}
.wh-body p a,
.wh-body li a {
  color: var(--color-primary);
  text-decoration-color: var(--color-accent);
  text-underline-offset: 3px;
}
.wh-body ul {
  display: grid;
  gap: var(--space-2);
  margin: var(--space-4) 0;
  padding: 0;
  list-style: none;
}
.wh-body ul > li {
  position: relative;
  padding-inline-start: var(--space-6);
}
.wh-body ul > li::before {
  content: "";
  position: absolute;
  inset-block-start: .65em;
  inset-inline-start: var(--space-1);
  width: var(--space-2);
  height: var(--space-2);
  border-radius: var(--radius-full);
  background: var(--color-accent);
}

/* ---------- Signature: warning-sign grid ---------- */
.wh-body #signs-it-s-time-to-call + ul {
  grid-template-columns: repeat(2, minmax(0, 1fr));
  gap: var(--space-3);
}
.wh-body #signs-it-s-time-to-call + ul > li {
  padding: var(--space-4) var(--space-5) var(--space-4) var(--space-8);
  border-radius: var(--radius-lg);
  border: 1px solid var(--color-line);
  background: var(--color-card-tint-1);
  font-size: var(--fs-small);
  transition: transform var(--transition), box-shadow var(--transition);
}
.wh-body #signs-it-s-time-to-call + ul > li:nth-child(3n + 2) {
  background: var(--color-card-tint-2);
}
.wh-body #signs-it-s-time-to-call + ul > li:nth-child(3n) {
  background: var(--color-card-tint-3);
}
.wh-body #signs-it-s-time-to-call + ul > li:hover {
  transform: translateY(calc(var(--space-1) * -1));
  box-shadow: var(--shadow);
}
.wh-body #signs-it-s-time-to-call + ul > li::before {
  inset-block-start: 1.35em;
  inset-inline-start: var(--space-3);
  background: var(--color-secondary);
}

/* ---------- Repair vs. replace verdict cards ---------- */
.wh-body #repair-or-replace + p + ul,
.wh-body #repair-or-replace + p + ul + p + ul {
  padding: var(--space-4) var(--space-5);
  border-radius: var(--radius-lg);
  border: 1px solid var(--color-line);
  background: var(--color-card-tint-1);
}
.wh-body #repair-or-replace + p + ul + p + ul {
  background: var(--color-card-tint-3);
}

/* ---------- FAQ: question cards ---------- */
.wh-body #frequently-asked-questions ~ h3 {
  margin-block: var(--space-5) 0;
  padding: var(--space-4) var(--space-5) var(--space-2);
  border-radius: var(--radius-lg) var(--radius-lg) 0 0;
  background: var(--color-paper-2);
  border: 1px solid var(--color-line);
  border-block-end: 0;
  font-size: 1.1rem;
}
.wh-body #frequently-asked-questions ~ h3 + p {
  margin: 0;
  padding: 0 var(--space-5) var(--space-4);
  border-radius: 0 0 var(--radius-lg) var(--radius-lg);
  background: var(--color-paper-2);
  border: 1px solid var(--color-line);
  border-block-start: 0;
}

/* ---------- Owner line + sources ---------- */
.wh-body p > em:only-child {
  display: inline-block;
  padding: var(--space-2) var(--space-4);
  border-radius: var(--radius-full);
  background: var(--color-card-tint-neutral);
  font-size: var(--fs-small);
}
.wh-body #sources {
  font-size: 1.1rem;
}
.wh-body #sources + ul {
  padding: var(--space-4) var(--space-5);
  border-radius: var(--radius);
  background: var(--color-paper-2);
  font-size: var(--fs-small);
  color: var(--color-ink-2);
}
.wh-body #sources + ul a {
  word-break: break-word;
}

/* ---------- Related ---------- */
.wh-related {
  margin-block-start: var(--space-16);
  padding-block-start: var(--space-8);
  border-block-start: 1px solid var(--color-line);
}
.wh-related h2 {
  margin-block-end: var(--space-4);
  text-wrap: balance;
}
.wh-related .service-links {
  display: grid;
  gap: var(--space-2);
  margin: 0 0 var(--space-8);
  padding: 0;
  list-style: none;
}
.wh-related .service-links a {
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
.wh-related .service-links a:hover {
  border-color: var(--color-primary);
  transform: translateX(var(--space-1));
}

@media (max-width: 1000px) {
  .wh-layout {
    grid-template-columns: 1fr;
  }
  .wh-rail {
    position: static;
    order: -1;
    grid-template-columns: 1fr 1fr;
  }
}
@media (max-width: 640px) {
  .wh-rail {
    grid-template-columns: 1fr;
  }
  .wh-body #signs-it-s-time-to-call + ul {
    grid-template-columns: 1fr;
  }
}
@media (prefers-reduced-motion: reduce) {
  .wh-body #signs-it-s-time-to-call + ul > li,
  .wh-toc a,
  .wh-related .service-links a {
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
<section class="hero hero--interior wh-hero" aria-labelledby="wh-h1">
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
    <h1 class="hero-title" id="wh-h1"><?php echo h($post['title']); ?></h1>
    <ul class="wh-meta">
      <li><?php echo icon('calendar', 16); ?><time datetime="<?php echo h($post['dateISO']); ?>"><?php echo h($post['date']); ?></time></li>
      <li><?php echo icon('clock', 16); ?><?php echo h($post['readtime']); ?></li>
      <li><?php echo icon('info', 16); ?>Published by Page One Insights LLC</li>
    </ul>
  </div>
</section>

<div class="section wh-wrap">
  <div class="container-wide wh-layout">

    <article class="wh-article" aria-labelledby="wh-h1">
      <figure class="wh-featured blog-post__featured-image">
        <?php echo pictureTag($post['photo'], $post['alt'], '(max-width: 533px) calc(100vw - 2rem), (max-width: 1000px) 94vw, (max-width: 1464px) calc(100vw - 428px), 1036px', ['loading' => 'eager', 'fetchpriority' => 'high']); ?>
      </figure>

      <div class="blog-post__content wh-body">
<p>Most water heater problems come down to a few causes: sediment in the tank, a failed part (a heating element, thermostat, gas control or relief valve), or a tank that's simply worn out. If the tank itself is leaking, it needs replacing. Most other problems can be repaired. Here's how to tell which one you're dealing with, with notes for Christoval and San Angelo homes.</p>
<h2 id="where-christoval-s-water-comes-from-and-why-it-matters">Where Christoval's water comes from, and why it matters</h2>
<p>Homes on the Christoval system get water from the Tom Green County Fresh Water Supply District No. 2. According to the district's water conservation plan, the water comes from three shallow wells in City Park, the TCEQ classifies it as groundwater under the influence of surface water, and well levels rise and fall with South Concho River flows. Plenty of homes outside the district run on private wells, and in Texas the well owner is responsible for testing and maintaining a private well.</p>
<p>What that means for a water heater: well water and groundwater often carry dissolved minerals. Those minerals settle in the bottom of the tank as sediment and coat heating elements as scale. In San Angelo it's the same story: a July 2025 City lab test measured 18.4 grains per gallon, which the USGS scale puts in the "very hard" range. If you don't know how hard your water is, especially on a private well, a water test is cheap and tells you a lot.</p>
<h2 id="signs-it-s-time-to-call">Signs it's time to call</h2>
<ul>
<li><strong>Rumbling or popping</strong> when the heater runs. That's water trapped under a layer of sediment, boiling.</li>
<li><strong>Rusty or discolored hot water</strong> only. Can point to a corroding tank or a used-up anode rod.</li>
<li><strong>Running out of hot water faster</strong> than you used to. Sediment takes up space, or an element has failed.</li>
<li><strong>Water around the base.</strong> Check whether it's a fitting or the relief valve line (often fixable) or the tank itself (not fixable).</li>
<li><strong>Pilot won't stay lit</strong> on a gas unit, or the breaker trips on an electric one.</li>
<li><strong>Rotten-egg smell in the hot water.</strong> Often a reaction involving the anode rod, common with some well water.</li>
</ul>
<p>If you smell gas near a gas water heater, that's different. Leave the house and call 911 and your gas provider first.</p>
<h2 id="repair-or-replace">Repair or replace?</h2>
<p><strong>Usually worth repairing:</strong></p>
<ul>
<li>A failed heating element or thermostat (electric)</li>
<li>A bad thermocouple, igniter or gas control valve (gas)</li>
<li>A leaking fitting, drain valve or relief valve</li>
<li>A tank that's otherwise sound and well within its expected life</li>
</ul>
<p><strong>Usually time to replace:</strong></p>
<ul>
<li>The <strong>tank itself is leaking</strong></li>
<li>The heater is near the end of its expected life. A Pacific Northwest National Laboratory (PNNL) report puts water heater life at generally 8 to 20 years, depending on the type, water quality and maintenance. In the insurance-claim study it cites, the average failed heater was 10.7 years old</li>
<li>Repairs are adding up, or the unit no longer keeps up with your household</li>
</ul>
<h2 id="upkeep-that-actually-extends-tank-life">Upkeep that actually extends tank life</h2>
<p>The PNNL study on water quality and residential water heaters found that hard water builds scale and sediment that can cut efficiency and shorten equipment life. Its recommendations, translated:</p>
<ul>
<li><strong>Flush the tank.</strong> PNNL recommends draining the tank regularly, about once a year, to clear sediment, and calls every six months the conservative interval. With water as hard as ours, the shorter interval is worth considering.</li>
<li><strong>Check the anode rod.</strong> The anode is a sacrificial metal rod that corrodes so the steel tank doesn't. PNNL's guidance is to inspect it every one to two years and replace it as needed, and it cites one manufacturer's advice to check it at three years and every year after. One catch the report points out: softened water can wear anodes out faster, so if you add a softener, keep an eye on the anode.</li>
<li><strong>Test the relief valve.</strong> The temperature and pressure (T&amp;P) relief valve keeps a tank from building dangerous pressure. Lift the lever briefly once a year (watch for hot water) and make sure its discharge line is clear. If it drips constantly, or won't reseat, call a plumber.</li>
<li><strong>Consider treatment.</strong> A water softener or whole-house filter installed upstream cuts down on scale in the tank and everywhere else. See our <a href="/services/water-filtration-softeners/">water softener and filtration</a> page.</li>
</ul>
<p>If your heater is tucked in a closet or attic you can barely reach, maintenance tends not to happen. That's worth keeping in mind on a remodel or new build. Our post on <a href="/blog/new-construction-vs-remodel-plumbing-in-san-angelo-tx-what-s-different-and-why-it-matters/">new construction vs. remodel plumbing</a> covers planning for it.</p>
<h2 id="picking-the-right-replacement">Picking the right replacement</h2>
<ul>
<li><strong>Size by your peak use</strong>, not the old tank's label. How many showers run back to back in the morning matters more than the number of bedrooms.</li>
<li><strong>Match the fuel</strong> you have (natural gas, propane or electric) unless there's a good reason to switch. Switching fuels means new gas piping or a new electrical circuit.</li>
<li><strong>Plan for code items</strong>: the relief valve and its discharge line, a drain pan where required, proper venting on gas units, and the required clearances.</li>
<li><strong>Permits:</strong> inside San Angelo city limits, the City requires a permit and plumbing inspection for a water heater replacement. Christoval is unincorporated, so ask Tom Green County what applies before the job starts.</li>
</ul>
<h2 id="a-note-on-hidden-leaks">A note on hidden leaks</h2>
<p>A water heater that runs constantly with nobody using hot water can be a sign of a hot water leak somewhere else, sometimes under the slab. If that sounds familiar, read <a href="/blog/slab-leaks-pipe-repair-san-angelo/">how to spot a slab leak</a>.</p>
<h2 id="when-to-call">When to call</h2>
<p>Rock'n G Plumbing works on water heaters in Christoval and San Angelo, Monday through Friday, 8 AM to 6 PM. Call <strong>(325) 650-7000</strong>, see our <a href="/services/water-heaters/">water heater repair and replacement</a> page, or read more about <a href="/service-area/christoval-tx/">plumbing service in Christoval</a>.</p>
<p><em>Rock'n G Plumbing is owned by Eli Gomez, Texas Master Plumber License M-44822.</em></p>
<h2 id="frequently-asked-questions">Frequently asked questions</h2>
<h3>How long does a water heater last?</h3>
<p>A Pacific Northwest National Laboratory report puts water heater life at generally 8 to 20 years, depending on type, water quality and maintenance. Hard water and skipped maintenance push it toward the short end.</p>
<h3>How often should I flush my water heater?</h3>
<p>PNNL recommends about once a year and calls every six months the conservative choice. With very hard water like San Angelo's, the shorter interval is worth considering.</p>
<h3>Is a leaking water heater fixable?</h3>
<p>It depends where the leak is. A leaking fitting, drain valve or relief valve can usually be repaired. A leak from the tank itself means the heater needs replacing.</p>
<h3>Do I need a permit to replace a water heater?</h3>
<p>Inside San Angelo city limits, yes. The City requires a permit and plumbing inspection. In unincorporated areas like Christoval, ask Tom Green County.</p>
<h2 id="sources">Sources</h2>
<ul>
<li>Tom Green County FWSD No. 2, Water Conservation and Drought Contingency Plan 2020–2025 (three shallow wells in City Park; groundwater under the influence of surface water; South Concho River): <a rel="nofollow noopener" target="_blank" href="https://tgcfwd2.com/documents/1855/Tom_Green_County_Fresh_Water_Supply_District_Water_Conservation_Plan_Final-2-1.pdf">https://tgcfwd2.com/documents/1855/Tom_Green_County_Fresh_Water_Supply_District_Water_Conservation_Plan_Final-2-1.pdf</a></li>
<li>Texas Water Development Board, Groundwater and Private Water Well FAQ: <a rel="nofollow noopener" target="_blank" href="http://www.twdb.texas.gov/groundwater/faq/faqgwm.asp">http://www.twdb.texas.gov/groundwater/faq/faqgwm.asp</a></li>
<li>City of San Angelo Water Quality Laboratory, July 2025 tap sample (18.4 gr/gal): <a rel="nofollow noopener" target="_blank" href="https://www.sanangelo.gov/Archive.aspx?ADID=231">https://www.sanangelo.gov/Archive.aspx?ADID=231</a></li>
<li>USGS, Hardness of Water: <a rel="nofollow noopener" target="_blank" href="https://www.usgs.gov/special-topics/water-science-school/science/hardness-water">https://www.usgs.gov/special-topics/water-science-school/science/hardness-water</a></li>
<li>PNNL, Impacts of Water Quality on Residential Water Heating Equipment (PNNL-22921): <a rel="nofollow noopener" target="_blank" href="https://www.pnnl.gov/main/publications/external/technical_reports/PNNL-22921.pdf">https://www.pnnl.gov/main/publications/external/technical_reports/PNNL-22921.pdf</a></li>
<li>City of San Angelo Permits &amp; Inspections, water heater handout (permit and inspection required): <a rel="nofollow noopener" target="_blank" href="https://www.sanangelo.gov/DocumentCenter/View/214">https://www.sanangelo.gov/DocumentCenter/View/214</a></li>
</ul>
      </div>

      <div class="wh-related blog-post__related-services">
        <h2>Which Rock'n G Plumbing services go with this guide?</h2>
        <ul class="service-links">
          <li><a href="/services/water-heaters/">Water Heater Repair &amp; Installation <span aria-hidden="true">→</span></a></li>
          <li><a href="/services/water-filtration-softeners/">Water Softeners &amp; Filtration <span aria-hidden="true">→</span></a></li>
          <?php if (pageExists('/service-area/christoval-tx/')): ?><li><a href="/service-area/christoval-tx/">Plumber in Christoval, TX <span aria-hidden="true">→</span></a></li><?php endif; ?>
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

    <aside class="wh-rail" aria-label="Guide navigation and contact">
      <nav class="wh-toc" aria-labelledby="wh-toc-title">
        <h2 id="wh-toc-title">In this guide</h2>
        <ol>
          <?php foreach ($toc as $anchor => $label): ?><li><a href="#<?php echo h($anchor); ?>"><?php echo h($label); ?></a></li><?php endforeach; ?>
        </ol>
      </nav>
      <div class="wh-sidecta">
        <h2>Free estimate</h2>
        <p><?php echo h($weekday[0]); ?>, <?php echo h($weekday[1]); ?>. Texas Master Plumber <?php echo h($license['number']); ?>.</p>
        <a class="btn btn-accent" href="/contact/" data-open-estimate>Get a Free Estimate</a>
        <a class="link-call" href="<?php echo h($telHref); ?>"><?php echo icon('phone', 18); ?><?php echo h($phoneFmt); ?></a>
      </div>
    </aside>

  </div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>
