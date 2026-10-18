<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/functions.php';
?>
<?php
/**
 * blog/recurring-drain-clogs-san-angelo/index.php — migrated post #1 (Oct 18 2026 blog migration, P1 BLOG BOT drafts).
 * 301 from /drain-cleaning-and-sewer-solutions-for-homes-in-ballinger-tx (re-targeted to San Angelo).
 * Body pasted from the approved draft; only service hrefs were remapped to /services/<slug>/.
 * No FAQPage schema (Rock'n G SEO brief). Author = Page One Insights LLC (blog-standard.md §7).
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/blog-data.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/service-cards.php';

$bpSlug = 'recurring-drain-clogs-san-angelo';
$post   = blogPostBySlug($bpSlug);

$pageTitle       = $post['title'];
$pageDescription = 'Same drain backing up again? A San Angelo plumber explains roots, sagging sewer lines, grease and hard water, and when a camera inspection is worth it.';
$canonicalUrl    = $siteUrl . '/blog/' . $bpSlug . '/';
$currentPage     = 'blog/' . $bpSlug;
$ogType          = 'article';
$ogImage         = '/assets/images/' . $post['photo'] . '.jpg';
$breadcrumbs     = [['name' => 'Blog', 'url' => '/blog/'], ['name' => $post['title'], 'url' => '/blog/' . $bpSlug . '/']];

$telHref  = 'tel:' . $phoneE164;
$phoneFmt = formatPhone($phone);
$weekday  = hoursGroups($hours)[0];

$toc = [
    'first-question-one-drain-or-the-whole-house' => 'First question: one drain or the whole house?',
    'is-it-my-line-or-the-city-s'                 => "Is it my line or the city's?",
    'the-usual-suspects-behind-repeat-clogs'      => 'The usual suspects behind repeat clogs',
    'when-a-camera-inspection-is-worth-it'        => 'When a camera inspection is worth it',
    'cleaning-methods-plainly'                    => 'Cleaning methods, plainly',
    'what-you-can-do-between-visits'              => 'What you can do between visits',
    'a-word-on-summer-and-drought'                => 'A word on summer and drought',
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
    'keywords' => 'drain cleaning San Angelo, recurring drain clogs, sewer camera inspection, main line backup',
    'inLanguage' => 'en-US',
    'author' => ['@type' => 'Organization', 'name' => 'Page One Insights LLC', 'url' => 'https://pageoneinsights.com'],
    'publisher' => ['@id' => orgId()],
    'mainEntityOfPage' => $canonicalUrl,
    'about' => ['@type' => 'Service', 'name' => 'Drain Cleaning & Clog Removal', 'url' => absUrl('/services/drain-cleaning/'), 'provider' => ['@id' => orgId()]],
]];

ob_start();
?>
<style>
/* =====================================================================
   Post: recurring drain clogs — page-only CSS (rd- prefix)
   Tokens from framework.css; no raw colors, shadows or spacing.
   Techniques: layered type hero (gradient + noise), sticky TOC rail, numbered step track (signature),
   FAQ question cards, tinted source list, related band.
   ===================================================================== */
.rd-hero {
  padding-block-end: var(--space-12);
  background:
    radial-gradient(ellipse 55% 90% at 100% 0%, color-mix(in srgb, var(--color-accent) 22%, transparent), transparent 62%),
    linear-gradient(165deg, var(--color-primary-deep), var(--color-dark));
}
.rd-hero::before {
  content: "";
  position: absolute;
  inset: auto 0 0 0;
  height: var(--space-2);
  pointer-events: none;
  background: linear-gradient(90deg, var(--color-accent), var(--color-accent-bright), transparent);
  opacity: .7;
}
.rd-hero::after {
  content: "";
  position: absolute;
  inset: 0;
  pointer-events: none;
  opacity: .16;
  mix-blend-mode: overlay;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='160' height='160'%3E%3Cfilter id='g'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.8' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23g)'/%3E%3C/svg%3E");
}
.rd-hero .container {
  position: relative;
  z-index: 1;
}
.rd-hero .breadcrumb {
  color: color-mix(in srgb, var(--color-surface) 72%, transparent);
}
.rd-hero .breadcrumb li {
  display: inline-flex;
  align-items: center;
}
.rd-hero .breadcrumb [aria-current="page"] {
  color: var(--color-surface);
}
.rd-hero .hero-title {
  max-width: 24ch;
  text-wrap: balance;
}
.rd-meta {
  display: flex;
  flex-wrap: wrap;
  gap: var(--space-2) var(--space-5);
  margin: var(--space-4) 0 0;
  padding: 0;
  list-style: none;
  font-size: var(--fs-small);
  color: color-mix(in srgb, var(--color-surface) 78%, transparent);
}
.rd-meta li {
  display: inline-flex;
  gap: var(--space-2);
  align-items: center;
}
.rd-meta svg {
  color: var(--color-accent-bright);
}

/* ---------- Layout: article + rail ---------- */
.rd-layout {
  display: grid;
  grid-template-columns: minmax(0, 1fr) 300px;
  gap: clamp(var(--space-8), 5vw, var(--space-16));
  align-items: start;
}
.rd-featured {
  margin: 0 0 var(--space-8);
  box-shadow: var(--shadow);
}
.rd-featured img {
  width: 100%;
  aspect-ratio: 16 / 9;
  object-fit: cover;
}
.rd-rail {
  position: sticky;
  inset-block-start: calc(var(--nav-height) + var(--space-4));
  display: grid;
  gap: var(--space-5);
}
.rd-toc,
.rd-sidecta {
  padding: var(--space-5);
  border-radius: var(--radius-lg);
  border: 1px solid var(--color-line);
}
.rd-toc {
  background: var(--color-card-tint-1);
}
.rd-toc h2 {
  font-size: 1rem;
  margin-block-end: var(--space-3);
}
.rd-toc ol {
  display: grid;
  gap: var(--space-1);
  margin: 0;
  padding: 0;
  list-style: none;
  counter-reset: rd-toc;
}
.rd-toc li {
  counter-increment: rd-toc;
}
.rd-toc a {
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
.rd-toc a::before {
  content: counter(rd-toc, decimal-leading-zero);
  font-family: var(--font-accent);
  letter-spacing: .08em;
  color: var(--color-accent-dark);
}
.rd-toc a:hover {
  color: var(--color-primary);
  border-color: var(--color-accent);
  padding-inline-start: var(--space-2);
}
.rd-sidecta {
  background: var(--color-primary);
  color: var(--color-surface);
  box-shadow: var(--shadow);
}
.rd-sidecta h2 {
  color: var(--color-surface);
  font-size: 1.1rem;
  margin-block-end: var(--space-2);
}
.rd-sidecta p {
  margin: 0 0 var(--space-3);
  font-size: var(--fs-small);
  color: color-mix(in srgb, var(--color-surface) 84%, transparent);
}
.rd-sidecta .btn {
  width: 100%;
  justify-content: center;
}
.rd-sidecta .link-call {
  margin-block-start: var(--space-3);
  color: var(--color-surface);
}
.rd-sidecta .link-call svg {
  color: var(--color-accent-bright);
}

/* ---------- Body ---------- */
.rd-body > p:first-child {
  font-size: var(--fs-lead);
  padding-inline-start: var(--space-5);
  border-inline-start: 4px solid var(--color-accent);
}
.rd-body h2 {
  margin-block: var(--space-12) var(--space-3);
  text-wrap: balance;
  scroll-margin-block-start: calc(var(--nav-height) + var(--space-4));
}
.rd-body h3 {
  text-wrap: balance;
}
.rd-body p a,
.rd-body li a {
  color: var(--color-primary);
  text-decoration-color: var(--color-accent);
  text-underline-offset: 3px;
}
.rd-body ul {
  display: grid;
  gap: var(--space-2);
  margin: var(--space-4) 0;
  padding: 0;
  list-style: none;
}
.rd-body ul > li {
  position: relative;
  padding-inline-start: var(--space-6);
}
.rd-body ul > li::before {
  content: "";
  position: absolute;
  inset-block-start: .65em;
  inset-inline-start: var(--space-1);
  width: var(--space-2);
  height: var(--space-2);
  border-radius: var(--radius-full);
  background: var(--color-accent);
}

/* ---------- Signature: numbered step track ---------- */
.rd-body ol {
  counter-reset: rd-step;
  display: grid;
  gap: var(--space-3);
  margin: var(--space-5) 0;
  padding: 0;
  list-style: none;
}
.rd-body ol > li {
  counter-increment: rd-step;
  position: relative;
  padding: var(--space-4) var(--space-5) var(--space-4) var(--space-16);
  border-radius: var(--radius-lg);
  background: var(--color-card-tint-1);
  border: 1px solid var(--color-line);
  transition: transform var(--transition), box-shadow var(--transition);
}
.rd-body ol > li:nth-child(3n + 2) {
  background: var(--color-card-tint-2);
}
.rd-body ol > li:nth-child(3n) {
  background: var(--color-card-tint-3);
}
.rd-body ol > li:hover {
  transform: translateX(var(--space-1));
  box-shadow: var(--shadow);
}
.rd-body ol > li::before {
  content: counter(rd-step);
  position: absolute;
  inset-block-start: var(--space-3);
  inset-inline-start: var(--space-4);
  font-family: var(--font-accent);
  font-size: 2.2rem;
  line-height: 1;
  color: var(--color-primary);
}

/* ---------- FAQ: question cards ---------- */
.rd-body #frequently-asked-questions ~ h3 {
  margin-block: var(--space-5) 0;
  padding: var(--space-4) var(--space-5) var(--space-2);
  border-radius: var(--radius-lg) var(--radius-lg) 0 0;
  background: var(--color-paper-2);
  border: 1px solid var(--color-line);
  border-block-end: 0;
  font-size: 1.1rem;
}
.rd-body #frequently-asked-questions ~ h3 + p {
  margin: 0;
  padding: 0 var(--space-5) var(--space-4);
  border-radius: 0 0 var(--radius-lg) var(--radius-lg);
  background: var(--color-paper-2);
  border: 1px solid var(--color-line);
  border-block-start: 0;
}

/* ---------- Owner line + sources ---------- */
.rd-body p > em:only-child {
  display: inline-block;
  padding: var(--space-2) var(--space-4);
  border-radius: var(--radius-full);
  background: var(--color-card-tint-neutral);
  font-size: var(--fs-small);
}
.rd-body #sources {
  font-size: 1.1rem;
}
.rd-body #sources + ul {
  padding: var(--space-4) var(--space-5);
  border-radius: var(--radius);
  background: var(--color-paper-2);
  font-size: var(--fs-small);
  color: var(--color-ink-2);
}
.rd-body #sources + ul a {
  word-break: break-word;
}

/* ---------- Related ---------- */
.rd-related {
  margin-block-start: var(--space-16);
  padding-block-start: var(--space-8);
  border-block-start: 1px solid var(--color-line);
}
.rd-related h2 {
  margin-block-end: var(--space-4);
  text-wrap: balance;
}
.rd-related .service-links {
  display: grid;
  gap: var(--space-2);
  margin: 0 0 var(--space-8);
  padding: 0;
  list-style: none;
}
.rd-related .service-links a {
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
.rd-related .service-links a:hover {
  border-color: var(--color-primary);
  transform: translateX(var(--space-1));
}

@media (max-width: 1000px) {
  .rd-layout {
    grid-template-columns: 1fr;
  }
  .rd-rail {
    position: static;
    order: -1;
    grid-template-columns: 1fr 1fr;
  }
}
@media (max-width: 640px) {
  .rd-rail {
    grid-template-columns: 1fr;
  }
  .rd-body ol > li {
    padding-inline-start: var(--space-12);
  }
}
@media (prefers-reduced-motion: reduce) {
  .rd-body ol > li,
  .rd-toc a,
  .rd-related .service-links a {
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
<section class="hero hero--interior rd-hero" aria-labelledby="rd-h1">
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
    <h1 class="hero-title" id="rd-h1"><?php echo h($post['title']); ?></h1>
    <ul class="rd-meta">
      <li><?php echo icon('calendar', 16); ?><time datetime="<?php echo h($post['dateISO']); ?>"><?php echo h($post['date']); ?></time></li>
      <li><?php echo icon('clock', 16); ?><?php echo h($post['readtime']); ?></li>
      <li><?php echo icon('info', 16); ?>Published by Page One Insights LLC</li>
    </ul>
  </div>
</section>

<div class="section rd-wrap">
  <div class="container-wide rd-layout">

    <article class="rd-article" aria-labelledby="rd-h1">
      <figure class="rd-featured blog-post__featured-image">
        <?php echo pictureTag($post['photo'], $post['alt'], '(max-width: 1000px) calc(100vw - 2rem), 780px', ['loading' => 'eager', 'fetchpriority' => 'high']); ?>
      </figure>

      <div class="blog-post__content rd-body">
<p>Drains that keep clogging almost always have a cause the last cleaning didn't fix: grease coating the pipe, roots in the sewer line, a sagging section, or scale in old cast iron. Start by working out whether it's one drain or the whole house, then use a camera to find the cause before paying for another cleaning.</p>
<p>Here's how we think about repeat clogs in San Angelo homes, and what you can check before you call anybody.</p>
<h2 id="first-question-one-drain-or-the-whole-house">First question: one drain or the whole house?</h2>
<p>This tells you more than anything else.</p>
<ul>
<li><strong>One fixture is slow</strong> (one sink, one tub): the trouble is almost always in that fixture's branch line. Hair in a tub drain, soap and grease in a kitchen line, a trap that's been half-full of gunk for years.</li>
<li><strong>Several fixtures act up at once</strong>, especially the lowest ones: water gurgling up in the tub when the washer drains, or the toilet bubbling when you run the kitchen sink. That points to the main line, the pipe that carries everything from the house out to the city sewer.</li>
</ul>
<p>If it's a branch line, a <a href="/services/drain-cleaning/">drain cleaning</a> usually takes care of it. If it's the main line and sewage is coming up in the lowest drain, stop running water. Every flush and every load of laundry adds to what's backing up.</p>
<h2 id="is-it-my-line-or-the-city-s">Is it my line or the city's?</h2>
<p>In San Angelo, the City maintains its sewer mains. The service line from your house to the main is generally the property owner's responsibility. If you're not sure which side the blockage is on, the City's Water Distribution line (325-657-4295) can tell you whether there's a problem with the main. A good sign it's the City's side: neighbors are backing up too, or you see sewage in the street.</p>
<p>If the City says its main is clear, the problem is on your property, and that's where a plumber comes in.</p>
<h2 id="the-usual-suspects-behind-repeat-clogs">The usual suspects behind repeat clogs</h2>
<p><strong>Grease in kitchen lines.</strong> Hot water and dish soap move grease down the pipe, where it cools and sticks. Over a few years it builds up like plaque, and every clog after that catches on the buildup. Cabling punches a hole through it, but the walls stay coated.</p>
<p><strong>Roots in the sewer line.</strong> Roots go after moisture, and a hairline crack or an old joint in a clay or cast iron line is an easy way in. Once inside they spread into a mat that catches paper and waste. Clear it and the roots grow back, often within months.</p>
<p><strong>Sags (bellies) in the line.</strong> When the soil under a sewer pipe settles, the pipe dips. Water and solids sit in the low spot instead of flowing out. Much of Tom Green County sits on clay soils that swell when wet and shrink when dry, and that movement is hard on buried pipe. (It's hard on supply lines under the slab too, which we cover in our post on <a href="/blog/slab-leaks-pipe-repair-san-angelo/">slab leaks and pipe repair</a>.) USDA soil surveys describe the common Angelo series as a clay with high shrink-swell potential.</p>
<p><strong>Scale and corrosion in older pipe.</strong> San Angelo's water is very hard. A July 2025 City lab test at the treatment plant measured 18.4 grains per gallon (312 mg/L), well above the 180 mg/L line the USGS uses for "very hard." In older cast iron drains, rough interior scale and rust give waste something to snag on.</p>
<p><strong>What goes down the drain.</strong> "Flushable" wipes, paper towels and cooking fat cause a large share of the clogs we see. It's not glamorous, but changing this one habit fixes a lot of repeat calls.</p>
<h2 id="when-a-camera-inspection-is-worth-it">When a camera inspection is worth it</h2>
<p>If a main line has backed up more than once, we recommend a sewer camera inspection before anyone starts digging or quoting a replacement. A waterproof camera on a push cable goes in through the cleanout and records the whole line, with distance marked on screen. You see what we see: roots, a cracked hub, a belly holding water, a separated joint.</p>
<p>That footage changes the conversation from "we think" to "here's the spot, at about 38 feet." It tells you whether cleaning will hold, whether a short section needs repair, or whether the line has reached the end of its life. It's also worth doing before you buy an older home, since the sewer line is one of the most expensive things an inspection can miss.</p>
<h2 id="cleaning-methods-plainly">Cleaning methods, plainly</h2>
<ul>
<li><strong>Cabling (snaking):</strong> a rotating cable with a cutting head. Fast and good for opening a stoppage. It doesn't fully clean the pipe walls.</li>
<li><strong>Repair:</strong> if the camera shows a break or a deep belly, no amount of cleaning will make it go away. Our post on <a href="/blog/drain-repair-and-gas-line-work-in-san-angelo-tx-what-to-expect-when-you-need-both/">drain repair vs. replacement</a> walks through that decision.</li>
</ul>
<h2 id="what-you-can-do-between-visits">What you can do between visits</h2>
<ul>
<li>Keep grease out of the sink. Let it cool in a can and throw it away.</li>
<li>Use strainers in tubs and kitchen sinks.</li>
<li>Only toilet paper goes in the toilet.</li>
<li>Know where your cleanout is. It's usually a capped pipe a few inches out of the ground near the house. If you can't find it, ask us to show you on our next visit.</li>
<li>Don't keep pouring chemical drain openers into a line that's slow again. They rarely fix the cause, and they make the job riskier for whoever opens the line next.</li>
</ul>
<h2 id="a-word-on-summer-and-drought">A word on summer and drought</h2>
<p>When the ground dries out in a long West Texas summer, clay soil pulls back from buried pipe, and roots go looking for water harder than usual. A sewer line with a weak joint is often worse after a dry spell. If your backups seem to come in streaks, that may be why.</p>
<h2 id="when-to-call">When to call</h2>
<p>Call if the same drain has clogged more than twice in a year, if more than one fixture backs up at once, or if you hear gurgling from drains you aren't using. We'll start by figuring out where the trouble is, then tell you what will last, whether that's a cleaning, a camera inspection or a repair.</p>
<p>Rock'n G Plumbing serves San Angelo and Christoval, Monday through Friday, 8 AM to 6 PM. Call <strong>(325) 650-7000</strong>, or see our <a href="/services/drain-cleaning/">drain cleaning service</a> and <a href="/services/sewer-line-repair/">sewer line repair</a> pages.</p>
<p><em>Rock'n G Plumbing is owned by Eli Gomez, Texas Master Plumber License M-44822.</em></p>
<h2 id="frequently-asked-questions">Frequently asked questions</h2>
<h3>Why does my kitchen drain keep clogging?</h3>
<p>Usually grease. It cools and coats the pipe walls, and later clogs catch on the buildup. Cabling opens a hole through it but leaves the walls coated, so the clog comes back.</p>
<h3>Who is responsible for a sewer backup in San Angelo?</h3>
<p>The City maintains its sewer mains. The service line from your house to the main is generally the property owner's responsibility. The City's Water Distribution line (325-657-4295) can tell you whether its main is the problem.</p>
<h3>When is a sewer camera inspection worth it?</h3>
<p>When a main line has backed up more than once, before anyone quotes a dig or a replacement, and before you buy an older home.</p>
<h3>Do chemical drain openers fix recurring clogs?</h3>
<p>Rarely. They seldom touch the cause, and they make the job riskier for whoever opens the line next.</p>
<h2 id="sources">Sources</h2>
<ul>
<li>City of San Angelo, Water Distribution (who to call about sewer backups): <a rel="nofollow noopener" target="_blank" href="https://www.sanangelo.gov/524/Water-Distribution">https://www.sanangelo.gov/524/Water-Distribution</a></li>
<li>City of San Angelo Water Quality Laboratory, tap sample report, July 2025 (total hardness 18.4 gr/gal, 312 mg/L): <a rel="nofollow noopener" target="_blank" href="https://www.sanangelo.gov/Archive.aspx?ADID=231">https://www.sanangelo.gov/Archive.aspx?ADID=231</a></li>
<li>USGS Water Science School, Hardness of Water (very hard is above 180 mg/L): <a rel="nofollow noopener" target="_blank" href="https://www.usgs.gov/special-topics/water-science-school/science/hardness-water">https://www.usgs.gov/special-topics/water-science-school/science/hardness-water</a></li>
<li>USDA NRCS Official Series Description, Angelo series (clay, shrink-swell): <a rel="nofollow noopener" target="_blank" href="https://soilseries.sc.egov.usda.gov/OSD_Docs/A/Angelo.html">https://soilseries.sc.egov.usda.gov/OSD_Docs/A/Angelo.html</a></li>
</ul>
      </div>

      <div class="rd-related blog-post__related-services">
        <h2>Which Rock'n G Plumbing services go with this guide?</h2>
        <ul class="service-links">
          <li><a href="/services/drain-cleaning/">Drain Cleaning &amp; Clog Removal <span aria-hidden="true">→</span></a></li>
          <li><a href="/services/sewer-line-repair/">Sewer Line Repair &amp; Camera Inspection <span aria-hidden="true">→</span></a></li>
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

    <aside class="rd-rail" aria-label="Guide navigation and contact">
      <nav class="rd-toc" aria-labelledby="rd-toc-title">
        <h2 id="rd-toc-title">In this guide</h2>
        <ol>
          <?php foreach ($toc as $anchor => $label): ?><li><a href="#<?php echo h($anchor); ?>"><?php echo h($label); ?></a></li><?php endforeach; ?>
        </ol>
      </nav>
      <div class="rd-sidecta">
        <h2>Free estimate</h2>
        <p><?php echo h($weekday[0]); ?>, <?php echo h($weekday[1]); ?>. Texas Master Plumber <?php echo h($license['number']); ?>.</p>
        <a class="btn btn-accent" href="/contact/" data-open-estimate>Get a Free Estimate</a>
        <a class="link-call" href="<?php echo h($telHref); ?>"><?php echo icon('phone', 18); ?><?php echo h($phoneFmt); ?></a>
      </div>
    </aside>

  </div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>
