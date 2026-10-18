<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/functions.php';
?>
<?php
/**
 * blog/slab-leaks-pipe-repair-san-angelo/index.php — migrated post #6 (Oct 18 2026 blog migration, P1 BLOG BOT drafts).
 * 301 from /pipe-repair-and-replacement-solutions-for-carlsbad-tx-homes-and-businesses (re-targeted to San Angelo).
 * Body pasted from the approved draft; only service hrefs were remapped to /services/<slug>/, and the two
 * "Repair makes sense / Replacement makes sense" lists (markdown that leaked into a <p>) are rendered as real <ul>s.
 * No FAQPage schema (Rock'n G SEO brief). Author = Page One Insights LLC (blog-standard.md §7).
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/blog-data.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/service-cards.php';

$bpSlug = 'slab-leaks-pipe-repair-san-angelo';
$post   = blogPostBySlug($bpSlug);

$pageTitle       = $post['title'];
$pageDescription = 'Warm floors, a spinning meter, low pressure or rusty water? How San Angelo homeowners spot slab leaks and failing pipes, and when to repair or repipe.';
$canonicalUrl    = $siteUrl . '/blog/' . $bpSlug . '/';
$currentPage     = 'blog/' . $bpSlug;
$ogType          = 'article';
$ogImage         = '/assets/images/' . $post['photo'] . '.jpg';
$breadcrumbs     = [['name' => 'Blog', 'url' => '/blog/'], ['name' => $post['title'], 'url' => '/blog/' . $bpSlug . '/']];

$telHref  = 'tel:' . $phoneE164;
$phoneFmt = formatPhone($phone);
$weekday  = hoursGroups($hours)[0];

$toc = [
    'check-for-a-leak-yourself-in-ten-minutes' => 'Check for a leak yourself in ten minutes',
    'signs-of-a-slab-leak'                     => 'Signs of a slab leak',
    'why-pipes-fail-here'                      => 'Why pipes fail here',
    'repair-or-replace'                        => 'Repair or replace?',
    'pipe-materials-briefly'                   => 'Pipe materials, briefly',
    'getting-ready-for-the-next-freeze'        => 'Getting ready for the next freeze',
    'when-to-call'                             => 'When to call',
    'frequently-asked-questions'               => 'Frequently asked questions',
];

$schemaMarkup = [[
    "@context" => 'https://schema.org', '@type' => 'BlogPosting',
    '@id' => $canonicalUrl . '#post',
    'headline' => $post['title'],
    'description' => $pageDescription,
    'image' => absUrl($post['image']),
    'datePublished' => $post['dateISO'], 'dateModified' => $post['dateISO'],
    'keywords' => 'slab leak repair San Angelo, leak detection, repipe, galvanized pipe replacement, frozen pipes',
    'inLanguage' => 'en-US',
    'author' => ['@type' => 'Organization', 'name' => 'Page One Insights LLC', 'url' => 'https://pageoneinsights.com'],
    'publisher' => ['@id' => orgId()],
    'mainEntityOfPage' => $canonicalUrl,
    'about' => ['@type' => 'Service', 'name' => 'Leak Detection & Slab Leak Repair', 'url' => absUrl('/services/leak-detection-repair/'), 'provider' => ['@id' => orgId()]],
]];

ob_start();
?>
<style>
/* =====================================================================
   Post: slab leaks + pipe repair — page-only CSS (sl- prefix)
   Tokens from framework.css; no raw colors, shadows or spacing.
   Techniques: layered type hero (gradient + noise), sticky TOC rail, meter-test step track (signature),
   repair-vs-replace split cards, FAQ question cards, tinted source list, related band.
   ===================================================================== */
.sl-hero {
  padding-block-end: var(--space-12);
  background:
    radial-gradient(ellipse 55% 90% at 100% 0%, color-mix(in srgb, var(--color-secondary) 20%, transparent), transparent 62%),
    linear-gradient(165deg, var(--color-primary-deep), var(--color-dark));
}
.sl-hero::before {
  content: "";
  position: absolute;
  inset: auto 0 0 0;
  height: var(--space-2);
  pointer-events: none;
  background: linear-gradient(90deg, var(--color-secondary), var(--color-accent), transparent);
  opacity: .7;
}
.sl-hero::after {
  content: "";
  position: absolute;
  inset: 0;
  pointer-events: none;
  opacity: .16;
  mix-blend-mode: overlay;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='160' height='160'%3E%3Cfilter id='g'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.8' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23g)'/%3E%3C/svg%3E");
}
.sl-hero .container {
  position: relative;
  z-index: 1;
}
.sl-hero .breadcrumb {
  color: color-mix(in srgb, var(--color-surface) 72%, transparent);
}
.sl-hero .breadcrumb li {
  display: inline-flex;
  align-items: center;
}
.sl-hero .breadcrumb [aria-current="page"] {
  color: var(--color-surface);
}
.sl-hero .hero-title {
  max-width: 24ch;
  text-wrap: balance;
}
.sl-meta {
  display: flex;
  flex-wrap: wrap;
  gap: var(--space-2) var(--space-5);
  margin: var(--space-4) 0 0;
  padding: 0;
  list-style: none;
  font-size: var(--fs-small);
  color: color-mix(in srgb, var(--color-surface) 78%, transparent);
}
.sl-meta li {
  display: inline-flex;
  gap: var(--space-2);
  align-items: center;
}
.sl-meta svg {
  color: var(--color-accent-bright);
}

/* ---------- Layout: article + rail ---------- */
.sl-layout {
  display: grid;
  grid-template-columns: minmax(0, 1fr) 300px;
  gap: clamp(var(--space-8), 5vw, var(--space-16));
  align-items: start;
}
.sl-featured {
  margin: 0 0 var(--space-8);
  box-shadow: var(--shadow);
}
.sl-featured img {
  width: 100%;
  aspect-ratio: 16 / 9;
  object-fit: cover;
}
.sl-rail {
  position: sticky;
  inset-block-start: calc(var(--nav-height) + var(--space-4));
  display: grid;
  gap: var(--space-5);
}
.sl-toc,
.sl-sidecta {
  padding: var(--space-5);
  border-radius: var(--radius-lg);
  border: 1px solid var(--color-line);
}
.sl-toc {
  background: var(--color-card-tint-3);
}
.sl-toc h2 {
  font-size: 1rem;
  margin-block-end: var(--space-3);
}
.sl-toc ol {
  display: grid;
  gap: var(--space-1);
  margin: 0;
  padding: 0;
  list-style: none;
  counter-reset: sl-toc;
}
.sl-toc li {
  counter-increment: sl-toc;
}
.sl-toc a {
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
.sl-toc a::before {
  content: counter(sl-toc, decimal-leading-zero);
  font-family: var(--font-accent);
  letter-spacing: .08em;
  color: var(--color-accent-dark);
}
.sl-toc a:hover {
  color: var(--color-primary);
  border-color: var(--color-accent);
  padding-inline-start: var(--space-2);
}
.sl-sidecta {
  background: var(--color-primary);
  color: var(--color-surface);
  box-shadow: var(--shadow);
}
.sl-sidecta h2 {
  color: var(--color-surface);
  font-size: 1.1rem;
  margin-block-end: var(--space-2);
}
.sl-sidecta p {
  margin: 0 0 var(--space-3);
  font-size: var(--fs-small);
  color: color-mix(in srgb, var(--color-surface) 84%, transparent);
}
.sl-sidecta .btn {
  width: 100%;
  justify-content: center;
}
.sl-sidecta .link-call {
  margin-block-start: var(--space-3);
  color: var(--color-surface);
}
.sl-sidecta .link-call svg {
  color: var(--color-accent-bright);
}

/* ---------- Body ---------- */
.sl-body > p:first-child {
  font-size: var(--fs-lead);
  padding-inline-start: var(--space-5);
  border-inline-start: 4px solid var(--color-secondary);
}
.sl-body h2 {
  margin-block: var(--space-12) var(--space-3);
  text-wrap: balance;
  scroll-margin-block-start: calc(var(--nav-height) + var(--space-4));
}
.sl-body h3 {
  text-wrap: balance;
}
.sl-body p a,
.sl-body li a {
  color: var(--color-primary);
  text-decoration-color: var(--color-accent);
  text-underline-offset: 3px;
}
.sl-body ul {
  display: grid;
  gap: var(--space-2);
  margin: var(--space-4) 0;
  padding: 0;
  list-style: none;
}
.sl-body ul > li {
  position: relative;
  padding-inline-start: var(--space-6);
}
.sl-body ul > li::before {
  content: "";
  position: absolute;
  inset-block-start: .65em;
  inset-inline-start: var(--space-1);
  width: var(--space-2);
  height: var(--space-2);
  border-radius: var(--radius-full);
  background: var(--color-secondary);
}
/* repair vs. replace: each verdict list becomes a tinted card */
.sl-body #repair-or-replace + p + ul,
.sl-body #repair-or-replace + p + ul + p + ul {
  padding: var(--space-4) var(--space-5);
  border-radius: var(--radius-lg);
  border: 1px solid var(--color-line);
  background: var(--color-card-tint-1);
}
.sl-body #repair-or-replace + p + ul + p + ul {
  background: var(--color-card-tint-2);
}

/* ---------- Signature: meter-test step track ---------- */
.sl-body ol {
  counter-reset: sl-step;
  display: grid;
  gap: var(--space-3);
  margin: var(--space-5) 0;
  padding: 0;
  list-style: none;
}
.sl-body ol > li {
  counter-increment: sl-step;
  position: relative;
  padding: var(--space-4) var(--space-5) var(--space-4) var(--space-16);
  border-radius: var(--radius-lg);
  background: var(--color-card-tint-3);
  border: 1px solid var(--color-line);
  transition: transform var(--transition), box-shadow var(--transition);
}
.sl-body ol > li:nth-child(even) {
  background: var(--color-card-tint-1);
  margin-inline-start: var(--space-6);
}
.sl-body ol > li:hover {
  transform: translateX(var(--space-1));
  box-shadow: var(--shadow);
}
.sl-body ol > li::before {
  content: counter(sl-step);
  position: absolute;
  inset-block-start: var(--space-3);
  inset-inline-start: var(--space-4);
  font-family: var(--font-accent);
  font-size: 2.2rem;
  line-height: 1;
  color: var(--color-primary);
}

/* ---------- FAQ: question cards ---------- */
.sl-body #frequently-asked-questions ~ h3 {
  margin-block: var(--space-5) 0;
  padding: var(--space-4) var(--space-5) var(--space-2);
  border-radius: var(--radius-lg) var(--radius-lg) 0 0;
  background: var(--color-paper-2);
  border: 1px solid var(--color-line);
  border-block-end: 0;
  font-size: 1.1rem;
}
.sl-body #frequently-asked-questions ~ h3 + p {
  margin: 0;
  padding: 0 var(--space-5) var(--space-4);
  border-radius: 0 0 var(--radius-lg) var(--radius-lg);
  background: var(--color-paper-2);
  border: 1px solid var(--color-line);
  border-block-start: 0;
}

/* ---------- Owner line + sources ---------- */
.sl-body p > em:only-child {
  display: inline-block;
  padding: var(--space-2) var(--space-4);
  border-radius: var(--radius-full);
  background: var(--color-card-tint-neutral);
  font-size: var(--fs-small);
}
.sl-body #sources {
  font-size: 1.1rem;
}
.sl-body #sources + ul {
  padding: var(--space-4) var(--space-5);
  border-radius: var(--radius);
  background: var(--color-paper-2);
  font-size: var(--fs-small);
  color: var(--color-ink-2);
}
.sl-body #sources + ul a {
  word-break: break-word;
}

/* ---------- Related ---------- */
.sl-related {
  margin-block-start: var(--space-16);
  padding-block-start: var(--space-8);
  border-block-start: 1px solid var(--color-line);
}
.sl-related h2 {
  margin-block-end: var(--space-4);
  text-wrap: balance;
}
.sl-related .service-links {
  display: grid;
  gap: var(--space-2);
  margin: 0 0 var(--space-8);
  padding: 0;
  list-style: none;
}
.sl-related .service-links a {
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
.sl-related .service-links a:hover {
  border-color: var(--color-primary);
  transform: translateX(var(--space-1));
}

@media (max-width: 1000px) {
  .sl-layout {
    grid-template-columns: 1fr;
  }
  .sl-rail {
    position: static;
    order: -1;
    grid-template-columns: 1fr 1fr;
  }
}
@media (max-width: 640px) {
  .sl-rail {
    grid-template-columns: 1fr;
  }
  .sl-body ol > li {
    padding-inline-start: var(--space-12);
  }
  .sl-body ol > li:nth-child(even) {
    margin-inline-start: 0;
  }
}
@media (prefers-reduced-motion: reduce) {
  .sl-body ol > li,
  .sl-toc a,
  .sl-related .service-links a {
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
<section class="hero hero--interior sl-hero" aria-labelledby="sl-h1">
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
    <h1 class="hero-title" id="sl-h1"><?php echo h($post['title']); ?></h1>
    <ul class="sl-meta">
      <li><?php echo icon('calendar', 16); ?><time datetime="<?php echo h($post['dateISO']); ?>"><?php echo h($post['date']); ?></time></li>
      <li><?php echo icon('clock', 16); ?><?php echo h($post['readtime']); ?></li>
      <li><?php echo icon('info', 16); ?>Published by Page One Insights LLC</li>
    </ul>
  </div>
</section>

<div class="section sl-wrap">
  <div class="container-wide sl-layout">

    <article class="sl-article" aria-labelledby="sl-h1">
      <figure class="sl-featured blog-post__featured-image">
        <?php echo pictureTag($post['photo'], $post['alt'], '(max-width: 1000px) 100vw, 780px', ['loading' => 'eager', 'fetchpriority' => 'high']); ?>
      </figure>

      <div class="blog-post__content sl-body">
<p>Signs of a hidden leak in a slab home: a water meter that moves with everything off, a warm spot on the floor, the sound of running water, or a bill that jumped. In San Angelo, shifting clay, aging galvanized pipe, hard water and the occasional hard freeze cause most of them.</p>
<p>This post covers how to tell whether you have a hidden leak, what's usually behind it in our area, and how to think about patching a pipe versus replacing it.</p>
<h2 id="check-for-a-leak-yourself-in-ten-minutes">Check for a leak yourself in ten minutes</h2>
<p>Before calling anyone, try the meter test:</p>
<ol>
<li>Turn off every faucet, appliance and the sprinkler system. Make sure nobody runs water.</li>
<li>Find your water meter (usually in a box near the street) and look at the dial and the small leak indicator, often a little triangle or star.</li>
<li>Watch it for a few minutes, or write down the reading and check again in an hour.</li>
</ol>
<p>If it's moving with everything off, water is going somewhere. Next, shut the main valve at the house. If the meter stops, the leak is inside the house. If it keeps going, the leak is likely in the line between the meter and the house, or in the irrigation.</p>
<h2 id="signs-of-a-slab-leak">Signs of a slab leak</h2>
<ul>
<li>A <strong>warm spot</strong> on the floor (a hot water line leaking under the slab)</li>
<li><strong>Sound of running water</strong> when nothing is on</li>
<li><strong>Damp or bulging flooring</strong>, or a musty smell with no obvious source</li>
<li><strong>Cracks</strong> in tile or the slab that are new or growing</li>
<li>A <strong>water bill</strong> that climbed without a change in habits</li>
<li>A water heater that seems to <strong>run constantly</strong> (our <a href="/blog/water-heater-installation-and-repair-services-available-in-christoval-tx/">water heater guide</a> covers other causes)</li>
</ul>
<p>None of these proves a slab leak on its own, but two or three together are worth a professional look. Leak detection uses tools like acoustic listening equipment, pressure testing of individual lines and thermal imaging to narrow down the spot before anyone breaks concrete.</p>
<h2 id="why-pipes-fail-here">Why pipes fail here</h2>
<p><strong>Ground movement.</strong> Much of Tom Green County sits on clay that swells when wet and shrinks when dry. USDA soil surveys describe the common Angelo series as high shrink-swell clay. That movement puts stress on pipes under and around a slab, especially where pipe passes through concrete or where fittings are rigid.</p>
<p><strong>Old galvanized steel.</strong> Homes built before about 1960 often had galvanized steel supply pipe. It rusts from the inside. Over decades the rust narrows the pipe until pressure drops, and it eventually rusts through, usually at the threaded joints first. Brown water after the tap sits unused for a while is a classic sign. Typical life for galvanized is roughly 40 to 60 years, so any original galvanized in an older San Angelo home is living on borrowed time.</p>
<p><strong>Hard water.</strong> San Angelo's water is very hard, at 18.4 grains per gallon in a July 2025 City lab test. Scale adds to the buildup in old pipe and is tough on valves, fixtures and water heaters. A <a href="/services/water-filtration-softeners/">water softener or filter</a> can help.</p>
<p><strong>Freezes.</strong> Hard freezes aren't common here, but when they come they find every exposed pipe. During the February 2021 winter storm, San Angelo went under a citywide boil-water notice after multiple water main breaks. City crews were shutting off meters for customers with frozen or broken service lines, and residents were asked to drip faucets and open sink cabinets to keep pipes from freezing. Pipes in exterior walls, attics, garages and hose bibs are the usual casualties.</p>
<h2 id="repair-or-replace">Repair or replace?</h2>
<p><strong>Repair makes sense when:</strong></p>
<ul>
<li>It's a single leak in otherwise healthy pipe</li>
<li>The cause is clear and local, like a nail through a line or one failed fitting</li>
<li>The rest of the system is fairly new (PEX or copper in good shape)</li>
</ul>
<p><strong>Replacement (repiping) makes sense when:</strong></p>
<ul>
<li>You've had more than one leak in the same system in a short time</li>
<li>The house still has original galvanized supply pipe</li>
<li>Pressure is poor throughout the house, or water runs brown</li>
<li>A slab leak would mean breaking concrete to fix one spot in a pipe that's failing everywhere</li>
</ul>
<p>With slab leaks there's often a third option: <strong>reroute.</strong> Instead of breaking the floor to repair a line under the slab, the plumber abandons that run and brings new pipe through the walls or attic. It depends on the house, but it can mean less demolition and a line you can reach later.</p>
<h2 id="pipe-materials-briefly">Pipe materials, briefly</h2>
<ul>
<li><strong>PEX</strong> is flexible, needs fewer fittings, and is the most common choice for repipes today. It handles freezing better than rigid pipe, though it isn't freeze-proof. It shouldn't be left exposed to sunlight.</li>
<li><strong>Copper</strong> (Type L is the heavier wall) is durable and heat-tolerant. It's often used where pipe is exposed, like at the water heater.</li>
<li><strong>Galvanized steel</strong> isn't used for new water lines anymore. If you're remodeling anyway, that's the cheapest time to replace it. See <a href="/blog/new-construction-vs-remodel-plumbing-in-san-angelo-tx-what-s-different-and-why-it-matters/">new construction vs. remodel plumbing</a>.</li>
</ul>
<h2 id="getting-ready-for-the-next-freeze">Getting ready for the next freeze</h2>
<ul>
<li>Know where your <strong>main shutoff</strong> is, and make sure it actually turns.</li>
<li><strong>Insulate</strong> pipes in the attic, garage and exterior walls, and cover outdoor hose bibs.</li>
<li><strong>Disconnect hoses</strong> from outdoor faucets before a freeze.</li>
<li>During a hard freeze, follow the City's guidance. In 2021 it first asked residents to drip faucets, then asked them to cut back when pressure got low.</li>
<li>If a pipe bursts, shut off the main and call a plumber. If you can't shut it off at the house, the City can shut off the meter (Water Distribution: 325-657-4295).</li>
</ul>
<h2 id="when-to-call">When to call</h2>
<p>If your meter moves with everything off, you've found a warm spot on the floor, or you're living with old galvanized pipe, call Rock'n G Plumbing at <strong>(325) 650-7000</strong>, Monday through Friday, 8 AM to 6 PM. We serve San Angelo and Christoval. More on our <a href="/services/leak-detection-repair/">leak detection and slab leak repair</a> service.</p>
<p><em>Rock'n G Plumbing is owned by Eli Gomez, Texas Master Plumber License M-44822.</em></p>
<h2 id="frequently-asked-questions">Frequently asked questions</h2>
<h3>How can I tell if I have a slab leak?</h3>
<p>Common signs are a warm spot on the floor, running-water sounds with everything off, damp flooring, new cracks, a jump in the water bill, and a water meter that moves when no water is in use.</p>
<h3>Should I repair one leak or repipe the house?</h3>
<p>Repair a single leak in healthy pipe. Consider repiping after repeated leaks, with original galvanized pipe, or with poor pressure and brown water throughout the house.</p>
<h3>How long do galvanized water pipes last?</h3>
<p>Typically about 40 to 60 years. Homes built before about 1960 often have them, so original galvanized is usually near the end of its life.</p>
<h3>What should I do if a pipe bursts?</h3>
<p>Shut off the main valve at the house and call a plumber. If you can't shut it off, the City of San Angelo's Water Distribution line (325-657-4295) can shut off the meter.</p>
<h2 id="sources">Sources</h2>
<ul>
<li>USDA NRCS Official Series Description, Angelo series (high shrink-swell clay): <a rel="nofollow noopener" target="_blank" href="https://soilseries.sc.egov.usda.gov/OSD_Docs/A/Angelo.html">https://soilseries.sc.egov.usda.gov/OSD_Docs/A/Angelo.html</a></li>
<li>HORIZON home inspection reference (galvanized rusts inside, fails at joints, 40 to 60 year life, brown water): <a rel="nofollow noopener" target="_blank" href="https://app-horizon-web-prod.azurewebsites.net/hrb/HHRB.aspx?DROPDOWN=4312&amp;id=1907">https://app-horizon-web-prod.azurewebsites.net/hrb/HHRB.aspx?DROPDOWN=4312&amp;id=1907</a></li>
<li>Alameda County Water District, Galvanized Service Line Leaks (pre-1960 homes): <a rel="nofollow noopener" target="_blank" href="https://www.acwd.org/888/Galvanized-Service-Line-Leaks">https://www.acwd.org/888/Galvanized-Service-Line-Leaks</a></li>
<li>City of San Angelo Water Quality Laboratory, July 2025 tap sample (18.4 gr/gal): <a rel="nofollow noopener" target="_blank" href="https://www.sanangelo.gov/Archive.aspx?ADID=231">https://www.sanangelo.gov/Archive.aspx?ADID=231</a></li>
<li>San Angelo LIVE!, "Officials Issue City-Wide Boil Notice After Multiple Water Main Breaks," Feb 16, 2021: <a rel="nofollow noopener" target="_blank" href="https://sanangelolive.com/news/health/2021-02-16/officials-issue-city-wide-boil-notice-after-multiple-water-main-breaks">https://sanangelolive.com/news/health/2021-02-16/officials-issue-city-wide-boil-notice-after-multiple-water-main-breaks</a></li>
<li>San Angelo LIVE!, "Upper Plane Residents Could be without Water for Days," Feb 18, 2021 (meter shutoffs for frozen or broken service lines; drip guidance; 325-657-4295): <a rel="nofollow noopener" target="_blank" href="https://sanangelolive.com/news/san-angelo/2021-02-18/update-upper-plane-residents-could-be-without-water-days">https://sanangelolive.com/news/san-angelo/2021-02-18/update-upper-plane-residents-could-be-without-water-days</a></li>
<li>City of San Angelo, Water Distribution: <a rel="nofollow noopener" target="_blank" href="https://www.sanangelo.gov/524/Water-Distribution">https://www.sanangelo.gov/524/Water-Distribution</a></li>
</ul>
      </div>

      <div class="sl-related blog-post__related-services">
        <h2>Which Rock'n G Plumbing services go with this guide?</h2>
        <ul class="service-links">
          <li><a href="/services/leak-detection-repair/">Leak Detection &amp; Slab Leak Repair <span aria-hidden="true">→</span></a></li>
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

    <aside class="sl-rail" aria-label="Guide navigation and contact">
      <nav class="sl-toc" aria-labelledby="sl-toc-title">
        <h2 id="sl-toc-title">In this guide</h2>
        <ol>
          <?php foreach ($toc as $anchor => $label): ?><li><a href="#<?php echo h($anchor); ?>"><?php echo h($label); ?></a></li><?php endforeach; ?>
        </ol>
      </nav>
      <div class="sl-sidecta">
        <h2>Free estimate</h2>
        <p><?php echo h($weekday[0]); ?>, <?php echo h($weekday[1]); ?>. Texas Master Plumber <?php echo h($license['number']); ?>.</p>
        <a class="btn btn-accent" href="/contact/" data-open-estimate>Get a Free Estimate</a>
        <a class="link-call" href="<?php echo h($telHref); ?>"><?php echo icon('phone', 18); ?><?php echo h($phoneFmt); ?></a>
      </div>
    </aside>

  </div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>
