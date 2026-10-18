<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/functions.php';
?>
<?php
/**
 * blog/water-heater-replacement-cost-san-angelo/index.php — launch post 1 (Phase 6).
 * Topic: the cost-guide default from the build-plan keywords ("water heater replacement San Angelo", "tankless water
 * heater San Angelo TX"). Per blog-standard.md §7 there are NO dollar figures here: the client gave no pricing
 * (content-confirmations.json asks for ranges), so the post explains what moves the price and what an estimate should
 * list. Business claims are limited to config.php facts (license M-44822, 3 generations, 20+ years, free estimates,
 * military discounts). Permit / haul-away / warranty are written as questions to ask any plumber, never as Rock'n G promises.
 * No FAQPage schema (SEO brief). Author = Page One Insights LLC (blog-standard.md §7).
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/blog-data.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/service-cards.php';

$bpSlug = 'water-heater-replacement-cost-san-angelo';
$post   = blogPostBySlug($bpSlug);

$pageTitle       = $post['title'];
$pageDescription = "What sets San Angelo water heater replacement cost: tank or tankless, gas line, venting and access. Call Rock'n G Plumbing for a free estimate: (325) 650-7000.";
$canonicalUrl    = $siteUrl . '/blog/' . $bpSlug . '/';
$currentPage     = 'blog/' . $bpSlug;
$ogType          = 'article';
$ogImage         = '/assets/images/' . $post['photo'] . '.jpg';
$breadcrumbs     = [['name' => 'Blog', 'url' => '/blog/'], ['name' => $post['title'], 'url' => '/blog/' . $bpSlug . '/']];

$telHref  = 'tel:' . $phoneE164;
$phoneFmt = formatPhone($phone);
$weekday  = hoursGroups($hours)[0];
$licenseExpires = date('F j, Y', strtotime($license['expires']));

$toc = [
    'what-decides-cost'   => 'What decides the cost?',
    'tankless-vs-tank'    => 'Why does tankless cost more to install?',
    'hard-water'          => 'Does hard water change the math?',
    'repair-or-replace'   => 'Repair or replace?',
    'estimate-checklist'  => 'What should the estimate list?',
    'get-a-price'         => 'How do you get a price?',
];

$schemaMarkup = [[
    "@context" => 'https://schema.org', '@type' => 'BlogPosting',
    '@id' => $canonicalUrl . '#post',
    'headline' => $post['title'],
    'description' => $pageDescription,
    'image' => absUrl($post['image']),
    'datePublished' => $post['dateISO'], 'dateModified' => $post['dateISO'],
    'keywords' => 'water heater replacement San Angelo, tankless water heater San Angelo TX, water heater installation San Angelo TX, hot water heater plumber San Angelo',
    'inLanguage' => 'en-US',
    'author' => ['@type' => 'Organization', 'name' => 'Page One Insights LLC', 'url' => 'https://pageoneinsights.com'],
    'publisher' => ['@id' => orgId()],
    'mainEntityOfPage' => $canonicalUrl,
    'about' => ['@type' => 'Service', 'name' => 'Water heater repair and installation', 'url' => absUrl('/services/water-heaters/'), 'provider' => ['@id' => orgId()]],
]];

ob_start();
?>
<style>
/* =====================================================================
   Post: water heater replacement cost — page-only CSS (wc- prefix)
   Tokens from framework.css; no raw colors, shadows or spacing.
   Techniques: layered type hero (gradient + noise), sticky TOC rail, cost-driver ladder (signature), tinted
   callouts, wave divider on the related band.
   ===================================================================== */
.wc-hero {
  padding-block-end: var(--space-12);
  background:
    radial-gradient(ellipse 55% 90% at 100% 0%, color-mix(in srgb, var(--color-warning) 22%, transparent), transparent 62%),
    linear-gradient(165deg, var(--color-primary-deep), var(--color-dark));
}
.wc-hero::after {
  content: "";
  position: absolute;
  inset: 0;
  pointer-events: none;
  opacity: .16;
  mix-blend-mode: overlay;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='160' height='160'%3E%3Cfilter id='g'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.8' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23g)'/%3E%3C/svg%3E");
}
.wc-hero .container {
  position: relative;
  z-index: 1;
}
.wc-hero .breadcrumb {
  color: color-mix(in srgb, var(--color-surface) 72%, transparent);
}
.wc-hero .breadcrumb li {
  display: inline-flex;
  align-items: center;
}
.wc-hero .breadcrumb [aria-current="page"] {
  color: var(--color-surface);
}
.wc-hero .hero-title {
  max-width: 22ch;
}
.wc-meta {
  display: flex;
  flex-wrap: wrap;
  gap: var(--space-2) var(--space-5);
  margin: var(--space-4) 0 0;
  padding: 0;
  list-style: none;
  font-size: var(--fs-small);
  color: color-mix(in srgb, var(--color-surface) 78%, transparent);
}
.wc-meta li {
  display: inline-flex;
  gap: var(--space-2);
  align-items: center;
}
.wc-meta svg {
  color: var(--color-accent-bright);
}

/* ---------- Layout: article + rail ---------- */
.wc-layout {
  display: grid;
  grid-template-columns: minmax(0, 1fr) 300px;
  gap: clamp(var(--space-8), 5vw, var(--space-16));
  align-items: start;
}
.wc-featured {
  margin: 0 0 var(--space-8);
}
.wc-featured figcaption {
  margin-block-start: var(--space-2);
  font-size: var(--fs-small);
  color: var(--color-muted);
}
.wc-rail {
  position: sticky;
  inset-block-start: calc(var(--nav-height) + var(--space-4));
  display: grid;
  gap: var(--space-5);
}
.wc-toc,
.wc-sidecta {
  padding: var(--space-5);
  border-radius: var(--radius-lg);
  border: 1px solid var(--color-line);
}
.wc-toc {
  background: var(--color-card-tint-1);
}
.wc-toc h2 {
  font-size: 1rem;
  margin-block-end: var(--space-3);
}
.wc-toc ol {
  display: grid;
  gap: var(--space-1);
  margin: 0;
  padding: 0;
  list-style: none;
  counter-reset: wc-toc;
}
.wc-toc li {
  counter-increment: wc-toc;
}
.wc-toc a {
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
.wc-toc a::before {
  content: counter(wc-toc, decimal-leading-zero);
  font-family: var(--font-accent);
  letter-spacing: .08em;
  color: var(--color-accent-dark);
}
.wc-toc a:hover {
  color: var(--color-primary);
  border-color: var(--color-accent);
  padding-inline-start: var(--space-2);
}
.wc-sidecta {
  background: var(--color-primary);
  color: var(--color-surface);
}
.wc-sidecta h2 {
  color: var(--color-surface);
  font-size: 1.1rem;
  margin-block-end: var(--space-2);
}
.wc-sidecta p {
  margin: 0 0 var(--space-3);
  font-size: var(--fs-small);
  color: color-mix(in srgb, var(--color-surface) 84%, transparent);
}
.wc-sidecta .btn {
  width: 100%;
  justify-content: center;
}
.wc-sidecta .link-call {
  margin-block-start: var(--space-3);
  color: var(--color-surface);
}
.wc-sidecta .link-call svg {
  color: var(--color-accent-bright);
}

/* ---------- Body ---------- */
.wc-body .answer-block {
  font-size: var(--fs-lead);
}
.wc-body section {
  padding: 0;
  overflow: visible;
  scroll-margin-block-start: calc(var(--nav-height) + var(--space-4));
}
.wc-body section + section {
  margin-block-start: var(--space-12);
}
.wc-body h2 {
  margin-block: 0 var(--space-3);
}
.wc-body p a,
.wc-body li a {
  color: var(--color-primary);
  text-decoration-color: var(--color-accent);
  text-underline-offset: 3px;
}

/* ---------- Signature: cost-driver ladder ---------- */
.wc-ladder {
  counter-reset: wc-step;
  display: grid;
  gap: var(--space-3);
  margin: var(--space-5) 0;
  padding: 0;
  list-style: none;
}
.wc-ladder li {
  counter-increment: wc-step;
  position: relative;
  display: grid;
  gap: var(--space-1);
  padding: var(--space-4) var(--space-5) var(--space-4) var(--space-16);
  border-radius: var(--radius-lg);
  background: var(--color-card-tint-1);
  border: 1px solid var(--color-line);
  transition: transform var(--transition), box-shadow var(--transition);
}
.wc-ladder li:nth-child(even) {
  background: var(--color-card-tint-3);
  margin-inline-start: var(--space-6);
}
.wc-ladder li:hover {
  transform: translateX(var(--space-1));
  box-shadow: var(--shadow);
}
.wc-ladder li::before {
  content: counter(wc-step);
  position: absolute;
  inset-block-start: var(--space-3);
  inset-inline-start: var(--space-4);
  font-family: var(--font-accent);
  font-size: 2.2rem;
  line-height: 1;
  color: var(--color-primary);
}
.wc-ladder b {
  font-family: var(--font-heading);
  font-size: 1.05rem;
}
.wc-ladder span {
  font-size: var(--fs-small);
  color: var(--color-ink-2);
}
.wc-callout {
  display: flex;
  gap: var(--space-4);
  align-items: flex-start;
  margin: var(--space-6) 0;
  padding: var(--space-5) var(--space-6);
  border-radius: var(--radius-lg);
  border-inline-start: 4px solid var(--color-accent);
  background: var(--color-paper-2);
}
.wc-callout svg {
  flex: 0 0 auto;
  color: var(--color-primary);
}
.wc-callout p {
  margin: 0;
  font-size: var(--fs-small);
}
.wc-check {
  display: grid;
  gap: var(--space-2);
  margin: var(--space-4) 0;
  padding: 0;
  list-style: none;
}
.wc-check li {
  display: flex;
  gap: var(--space-3);
  align-items: flex-start;
}
.wc-check svg {
  flex: 0 0 auto;
  margin-block-start: var(--space-1);
  color: var(--color-accent-dark);
}
.wc-license {
  display: inline-flex;
  flex-wrap: wrap;
  gap: var(--space-2) var(--space-4);
  align-items: center;
  margin: var(--space-3) 0;
  padding: var(--space-2) var(--space-5);
  border-radius: var(--radius-full);
  background: var(--color-primary);
  color: var(--color-surface);
  font-size: var(--fs-small);
}
.wc-license b {
  font-family: var(--font-accent);
  letter-spacing: .08em;
}

/* ---------- Related ---------- */
.wc-related {
  margin-block-start: var(--space-16);
}
.wc-related h2 {
  margin-block-end: var(--space-4);
}
.wc-related .service-links {
  display: grid;
  gap: var(--space-2);
  margin: 0 0 var(--space-8);
  padding: 0;
  list-style: none;
}
.wc-related .service-links a {
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
.wc-related .service-links a:hover {
  border-color: var(--color-primary);
  transform: translateX(var(--space-1));
}

@media (max-width: 1000px) {
  .wc-layout {
    grid-template-columns: 1fr;
  }
  .wc-rail {
    position: static;
    order: -1;
    grid-template-columns: 1fr 1fr;
  }
}
@media (max-width: 640px) {
  .wc-rail {
    grid-template-columns: 1fr;
  }
  .wc-ladder li:nth-child(even) {
    margin-inline-start: 0;
  }
}
@media (prefers-reduced-motion: reduce) {
  .wc-ladder li,
  .wc-toc a,
  .wc-related .service-links a {
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
<section class="hero hero--interior wc-hero" aria-labelledby="wc-h1">
  <div class="container">
    <nav class="breadcrumb" aria-label="Breadcrumb">
      <ol>
        <li><a href="/">Home</a></li>
        <li class="breadcrumb-sep" aria-hidden="true">/</li>
        <li><a href="/blog/">Blog</a></li>
        <li class="breadcrumb-sep" aria-hidden="true">/</li>
        <li><span aria-current="page">Water heater cost</span></li>
      </ol>
    </nav>
    <span class="eyebrow"><?php echo h($post['category']); ?></span>
    <h1 class="hero-title" id="wc-h1"><?php echo h($post['title']); ?></h1>
    <ul class="wc-meta">
      <li><?php echo icon('calendar', 16); ?><time datetime="<?php echo h($post['dateISO']); ?>"><?php echo h($post['date']); ?></time></li>
      <li><?php echo icon('clock', 16); ?><?php echo h($post['readtime']); ?></li>
      <li><?php echo icon('info', 16); ?>Published by Page One Insights LLC</li>
    </ul>
  </div>
</section>

<div class="section wc-wrap">
  <div class="container-wide wc-layout">

    <article class="wc-article" aria-labelledby="wc-h1">
      <figure class="wc-featured blog-post__featured-image">
        <?php echo pictureTag($post['photo'], $post['alt'], '(max-width: 1000px) 100vw, 780px', ['loading' => 'eager', 'fetchpriority' => 'high']); ?>
        <figcaption>A Rock'n G water treatment install in a garage utility corner. Equipment that shares a wall with the water heater is one more thing a replacement has to fit around.</figcaption>
      </figure>

      <div class="blog-post__content wc-body">

        <p class="answer-block">A water heater replacement in San Angelo costs what the job takes: the heater itself, any gas line, venting or electrical work it needs, and the labor to fit it into your garage, closet or attic. Rock'n G Plumbing writes the estimate at no charge, so you get a price for your house instead of an average.</p>

        <section id="what-decides-cost" aria-labelledby="h-what">
          <h2 id="h-what">What decides the cost of a water heater replacement in San Angelo?</h2>
          <p>Five things move the price, and the first two usually matter most. No plumber can quote a real number without knowing them, which is why this guide lists factors instead of dollar figures. Rock'n G Plumbing's <a href="/services/water-heaters/">water heater service</a> starts with a written estimate for the same reason.</p>
          <ol class="wc-ladder">
            <li class="reveal-up"><b>Tank or tankless</b><span>A tank heater is usually the simpler swap. A tankless unit costs more to buy and more to install, for reasons covered below.</span></li>
            <li class="reveal-up reveal-delay-1"><b>Gas or electric</b><span>A gas heater needs a gas supply and a vent to the outside. An electric one needs a circuit that can carry its load. Swapping from one fuel to the other adds work.</span></li>
            <li class="reveal-up reveal-delay-2"><b>Capacity</b><span>A bigger household needs a bigger tank or a larger tankless unit, and the size changes the price of the unit and sometimes the connections.</span></li>
            <li class="reveal-up reveal-delay-3"><b>What the connections need</b><span>Water lines, the relief-valve line, shutoff valves, a gas line that is the right size, venting that meets code, an electrical circuit. Old connections that are corroded or undersized are replaced along with the heater.</span></li>
            <li class="reveal-up"><b>Where it sits</b><span>A heater in an open garage corner is quick to reach. One in a tight closet or an attic takes longer to remove and set, and the time is part of the price.</span></li>
          </ol>
          <p>Beyond those five, ask every plumber about three things and get the answers in writing: whether a permit and inspection apply (confirm with the City of San Angelo), whether the old heater is removed and hauled away, and what the parts and labor warranty covers.</p>
        </section>

        <section id="tankless-vs-tank" aria-labelledby="h-tankless">
          <h2 id="h-tankless">Why does a tankless water heater usually cost more to install than a tank?</h2>
          <p>A tankless water heater is rarely a like-for-like swap. Gas models often need a larger gas supply line and new venting, and the unit mounts on a wall instead of standing on the floor, so the plumber is changing the house, not just replacing an appliance. The unit itself also costs more than a comparable tank.</p>
          <p>In return, a tankless heater gives continuous hot water and frees floor space. It is a good fit for back-to-back showers or a crowded utility closet. If you are leaning that way, the gas line is the piece to price first, and Rock'n G Plumbing handles that too through its <a href="/services/gas-line-services/">gas line installation and repair</a> work.</p>
          <div class="wc-callout" role="note">
            <?php echo icon('info', 22); ?>
            <p><strong>Not sure which fits?</strong> The <a href="/services/water-heaters/">water heater page</a> has a side-by-side tank and tankless table, including the hard-water upkeep each one needs.</p>
          </div>
        </section>

        <section id="hard-water" aria-labelledby="h-hard">
          <h2 id="h-hard">Does San Angelo's hard water change what a water heater costs over time?</h2>
          <p>Yes. Minerals in hard water settle on the bottom of a tank heater, the burner or element works harder to heat through them, and the tank rumbles, loses hot water and wears out sooner. Tankless units build up scale inside the heat exchanger and need periodic descaling. Treating the water before it reaches the heater slows both problems.</p>
          <p>That is why a replacement is a good moment to ask about a softener. Rock'n G Plumbing installs <a href="/services/water-filtration-softeners/">water softeners, reverse osmosis and whole-house filters</a>, and the photo at the top of this page shows one of its garage installs: control valve, resin tank, brine tank and two filter housings on a single wall. A garage utility corner fills up quickly, so tell the plumber what is already there before the heater is sized and placed.</p>
        </section>

        <?php
        $ctaBandId = 'cta-band-cost';
        $ctaBandTitle = 'Want a price for your water heater?';
        $ctaBandText = "Tell Rock'n G Plumbing what the heater is doing and roughly how old it is. The estimate is free.";
        $ctaBandService = 'Water Heater Repair & Installation';
        include $_SERVER['DOCUMENT_ROOT'] . '/includes/cta-band.php';
        ?>

        <section id="repair-or-replace" aria-labelledby="h-repair">
          <h2 id="h-repair">Is it cheaper to repair a water heater than to replace it?</h2>
          <p>A repair costs less today when the heater is otherwise sound. Heating elements, thermostats, thermocouples, gas controls, anode rods and relief valves are all parts a plumber can replace without replacing the tank. If the water is coming from the tank body itself, the steel has rusted through and no repair will hold.</p>
          <p>The harder call is an older heater with a part that has failed. A heater that needs a second repair in a short time is usually heading toward replacement, and putting money into it can mean paying twice. The honest answer comes from looking at the unit, which is part of the estimate visit. For the warning signs, see the repair-or-replace section of the <a href="/services/water-heaters/">water heater page</a>.</p>
        </section>

        <section id="estimate-checklist" aria-labelledby="h-list">
          <h2 id="h-list">What should a water heater estimate in San Angelo spell out?</h2>
          <p>A useful water heater estimate lists the work in enough detail that two quotes from different San Angelo plumbers can be compared line by line. Look for these items:</p>
          <ul class="wc-check">
            <li><?php echo icon('check', 18); ?><span><strong>The heater:</strong> type (tank or tankless), fuel, capacity and model.</span></li>
            <li><?php echo icon('check', 18); ?><span><strong>The connections:</strong> gas line, venting, electrical circuit, water lines, shutoff valves and the relief line, and which of them are being replaced.</span></li>
            <li><?php echo icon('check', 18); ?><span><strong>Removal:</strong> whether the old heater is taken away.</span></li>
            <li><?php echo icon('check', 18); ?><span><strong>Permit and inspection:</strong> whether they apply and who handles them.</span></li>
            <li><?php echo icon('check', 18); ?><span><strong>Warranty:</strong> what the manufacturer covers and what the plumber covers on labor.</span></li>
            <li><?php echo icon('check', 18); ?><span><strong>Who does the work:</strong> a Texas plumbing license number you can look up.</span></li>
          </ul>
          <p>Rock'n G Plumbing's work runs under <?php echo h($ownerName); ?>'s license:</p>
          <p class="wc-license"><b><?php echo h($license['number']); ?></b><span><?php echo h($license['role']); ?>, current through <?php echo h($licenseExpires); ?></span></p>
          <p>Search that number on the Texas State Board of Plumbing Examiners license lookup to confirm it. Military service? Ask the office how the Rock'n G military discount applies to your estimate.</p>
        </section>

        <section id="get-a-price" aria-labelledby="h-price">
          <h2 id="h-price">How do you get a price for your water heater in San Angelo?</h2>
          <p>Call Rock'n G Plumbing at <a href="<?php echo h($telHref); ?>"><?php echo h($phoneFmt); ?></a>, <?php echo h($weekday[0]); ?> <?php echo h($weekday[1]); ?>, or use the form above. Say whether the heater is gas or electric, where it sits, and what it has been doing. The model plate on the side of the tank lists its size and fuel.</p>
          <p>If your heater lives in an unheated garage or an attic, read <a href="/blog/protect-san-angelo-plumbing-before-a-hard-freeze/">how to protect San Angelo plumbing before a hard freeze</a> before the cold arrives. Exposed supply lines to the heater are among the first places a freeze finds.</p>
        </section>

      </div>

      <div class="wc-related blog-post__related-services">
        <h2>Which Rock'n G Plumbing services go with this guide?</h2>
        <p>Three Rock'n G Plumbing services connect to water heater pricing: the water heater work itself, the softener that protects it, and the gas line a tankless unit may need.</p>
        <ul class="service-links">
          <li><a href="/services/water-heaters/">Water Heater Repair &amp; Installation <span aria-hidden="true">→</span></a></li>
          <li><a href="/services/water-filtration-softeners/">Water Softeners &amp; Filtration <span aria-hidden="true">→</span></a></li>
          <li><a href="/services/gas-line-services/">Gas Line Installation &amp; Repair <span aria-hidden="true">→</span></a></li>
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

    <aside class="wc-rail" aria-label="Guide navigation and contact">
      <nav class="wc-toc" aria-labelledby="wc-toc-title">
        <h2 id="wc-toc-title">In this guide</h2>
        <ol>
          <?php foreach ($toc as $anchor => $label): ?><li><a href="#<?php echo h($anchor); ?>"><?php echo h($label); ?></a></li><?php endforeach; ?>
        </ol>
      </nav>
      <div class="wc-sidecta">
        <h2>Free estimate</h2>
        <p><?php echo h($weekday[0]); ?>, <?php echo h($weekday[1]); ?>. Texas Master Plumber <?php echo h($license['number']); ?>.</p>
        <a class="btn btn-accent" href="/contact/" data-open-estimate>Get a Free Estimate</a>
        <a class="link-call" href="<?php echo h($telHref); ?>"><?php echo icon('phone', 18); ?><?php echo h($phoneFmt); ?></a>
      </div>
    </aside>

  </div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>
