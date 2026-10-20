<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/functions.php';
?>
<?php
/**
 * blog/index.php — Blog index (Phase 6). Registry-driven: every card comes from $blogPosts in includes/blog-data.php.
 * Never a placeholder page — adding a post to the registry adds its card here, on the homepage and in sitemap.php.
 * Schema: Blog + BreadcrumbList only (SEO brief: no FAQ schema, no aggregateRating).
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/blog-data.php';

$pageTitle       = "San Angelo Plumbing Guides & Tips | Rock'n G Plumbing";
$pageDescription = "Plumbing guides for San Angelo, TX homes from Rock'n G Plumbing: water heater costs, freeze protection and more • Master Plumber M-44822 • Call (325) 650-7000.";
$canonicalUrl    = $siteUrl . '/blog/';
$currentPage     = 'blog';
$breadcrumbs     = [['name' => 'Blog', 'url' => '/blog/']];
$ogImage         = '/assets/images/' . $blogPosts[0]['photo'] . '.jpg';

$telHref  = 'tel:' . $phoneE164;
$phoneFmt = formatPhone($phone);
$weekday  = hoursGroups($hours)[0];
$bgFeatured = $blogPosts[0];
$bgRest     = array_slice($blogPosts, 1);
$bgCategories = array_values(array_unique(array_column($blogPosts, 'category')));

$bgItems = [];
foreach ($blogPosts as $bp) {
    $bgItems[] = [
        '@type' => 'BlogPosting', 'headline' => $bp['title'], 'url' => absUrl('/blog/' . $bp['slug'] . '/'),
        'datePublished' => $bp['dateISO'], 'image' => absUrl($bp['image']),
        'author' => ['@type' => 'Organization', 'name' => 'Page One Insights LLC', 'url' => 'https://pageoneinsights.com'],
    ];
}
$schemaMarkup = [[
    "@context" => 'https://schema.org', '@type' => 'Blog', '@id' => $canonicalUrl . '#blog',
    'name' => "Rock'n G Plumbing blog", 'url' => $canonicalUrl, 'description' => $pageDescription,
    'publisher' => ['@id' => orgId()], 'blogPost' => $bgItems,
]];

ob_start();
?>
<style>
/* =====================================================================
   Blog index — page-only CSS (bg- prefix)
   Tokens from framework.css; no raw colors, shadows or spacing.
   Techniques: layered type hero (gradient + noise), featured split card, tinted card grid, slant + torn dividers.
   ===================================================================== */
.bg-hero {
  padding-block-end: var(--space-16);
  background:
    radial-gradient(ellipse 55% 90% at 0% 0%, color-mix(in srgb, var(--color-accent) 20%, transparent), transparent 62%),
    linear-gradient(170deg, var(--color-primary-deep), var(--color-dark));
}
.bg-hero::after {
  content: "";
  position: absolute;
  inset: 0;
  pointer-events: none;
  opacity: .16;
  mix-blend-mode: overlay;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='160' height='160'%3E%3Cfilter id='g'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.8' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23g)'/%3E%3C/svg%3E");
}
.bg-hero .container {
  position: relative;
  z-index: 1;
}
.bg-hero .breadcrumb {
  color: color-mix(in srgb, var(--color-surface) 72%, transparent);
}
.bg-hero .breadcrumb li {
  display: inline-flex;
  align-items: center;
}
.bg-hero .breadcrumb [aria-current="page"] {
  color: var(--color-surface);
}
.bg-hero .hero-title {
  max-width: 18ch;
}
.bg-hero .hero-answer {
  max-width: 60ch;
}
.bg-cats {
  display: flex;
  flex-wrap: wrap;
  gap: var(--space-2);
  margin: var(--space-4) 0 0;
  padding: 0;
  list-style: none;
}
.bg-cats li {
  padding: var(--space-1) var(--space-4);
  border-radius: var(--radius-full);
  border: 1px solid color-mix(in srgb, var(--color-surface) 28%, transparent);
  background: color-mix(in srgb, var(--color-surface) 8%, transparent);
  font-family: var(--font-accent);
  font-size: var(--fs-eyebrow);
  letter-spacing: .12em;
  text-transform: uppercase;
  color: var(--color-surface);
}

/* ---------- Featured post ---------- */
.bg-featured {
  background:
    radial-gradient(ellipse 45% 40% at 100% 100%, color-mix(in srgb, var(--color-accent) 9%, transparent), transparent 70%),
    var(--color-paper);
}
.bg-feature {
  display: grid;
  grid-template-columns: minmax(0, 7fr) minmax(0, 5fr);
  border-radius: var(--radius-xl);
  overflow: hidden;
  background: var(--color-surface);
  border: 1px solid var(--color-line);
  box-shadow: var(--shadow-lg);
  text-decoration: none;
  color: inherit;
  transition: transform var(--transition-slow), box-shadow var(--transition-slow);
}
.bg-feature:hover {
  transform: translateY(calc(-1 * var(--space-1)));
}
.bg-feature__image {
  min-height: 320px;
  overflow: hidden;
}
.bg-feature__image picture,
.bg-feature__image img {
  width: 100%;
  height: 100%;
}
.bg-feature__image img {
  object-fit: cover;
  transition: transform var(--transition-slow);
}
.bg-feature:hover .bg-feature__image img {
  transform: scale(1.04);
}
.bg-feature__body {
  display: grid;
  gap: var(--space-3);
  align-content: center;
  padding: clamp(var(--space-6), 4vw, var(--space-12));
}
.bg-feature__tag {
  justify-self: start;
  padding: var(--space-1) var(--space-4);
  border-radius: var(--radius-full);
  background: var(--color-primary);
  color: var(--color-surface);
  font-family: var(--font-accent);
  font-size: var(--fs-eyebrow);
  letter-spacing: .14em;
  text-transform: uppercase;
}
.bg-feature h3 {
  font-size: var(--fs-h2);
}
.bg-feature p {
  margin: 0;
  color: var(--color-ink-2);
}
.bg-feature__meta {
  font-size: var(--fs-small);
  color: var(--color-muted);
}
.bg-feature__cta {
  justify-self: start;
  font-weight: 700;
  color: var(--color-primary);
  border-block-end: 2px solid var(--color-accent);
}

/* ---------- Card grid ---------- */
.bg-more .blog-grid {
  gap: var(--space-6);
}
.bg-more .blog-card {
  transition: transform var(--transition), box-shadow var(--transition);
}
.bg-more .blog-card:nth-child(3n+1) {
  background: var(--color-card-tint-1);
}
.bg-more .blog-card:nth-child(3n+2) {
  background: var(--color-card-tint-2);
}
.bg-more .blog-card:nth-child(3n) {
  background: var(--color-card-tint-3);
}
.bg-more .blog-card:hover {
  transform: translateY(calc(-1 * var(--space-1)));
  box-shadow: var(--shadow);
}
.bg-more .blog-card__category {
  justify-self: start;
  padding: var(--space-1) var(--space-3);
  border-radius: var(--radius-full);
  background: var(--color-surface);
}
.bg-soon {
  margin-block-start: var(--space-8);
  padding: var(--space-6);
  border-radius: var(--radius-lg);
  border: 1px dashed var(--color-line);
  background: var(--color-surface);
}
.bg-soon p {
  margin: 0;
  color: var(--color-ink-2);
}

/* ---------- Topics + CTA ---------- */
.bg-topics .grid-3 {
  gap: var(--space-5);
}
.bg-topic {
  display: grid;
  gap: var(--space-2);
  padding: var(--space-6);
  border-radius: var(--radius-lg);
  border: 1px solid var(--color-line);
  text-decoration: none;
  color: inherit;
  transition: transform var(--transition), box-shadow var(--transition);
}
.bg-topic:hover {
  transform: translateY(calc(-1 * var(--space-1)));
  box-shadow: var(--shadow);
}
.bg-topic svg {
  color: var(--color-primary);
}
.bg-topic h3 {
  margin: 0;
}
.bg-topic p {
  margin: 0;
  font-size: var(--fs-small);
  color: var(--color-ink-2);
}
.bg-cta .container {
  align-items: center;
}
.bg-cta .link-call {
  color: color-mix(in srgb, var(--color-surface) 88%, transparent);
}
.bg-cta .link-call svg {
  color: var(--color-accent-bright);
}
.bg-cta__ring {
  inset-block-start: calc(-1 * var(--space-10));
  inset-inline-end: calc(-1 * var(--space-10));
}

@media (max-width: 900px) {
  .bg-feature {
    grid-template-columns: 1fr;
  }
  .bg-feature__image {
    min-height: 220px;
    aspect-ratio: 16 / 10;
  }
}
@media (prefers-reduced-motion: reduce) {
  .bg-feature,
  .bg-feature__image img,
  .bg-more .blog-card,
  .bg-topic {
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
<section class="hero hero--interior bg-hero" aria-labelledby="bg-h1">
  <div class="container">
    <nav class="breadcrumb" aria-label="Breadcrumb">
      <ol>
        <li><a href="/">Home</a></li>
        <li class="breadcrumb-sep" aria-hidden="true">/</li>
        <li><span aria-current="page">Blog</span></li>
      </ol>
    </nav>
    <span class="eyebrow">Plumbing guides</span>
    <h1 class="hero-title" id="bg-h1">San Angelo Plumbing Guides from Rock'n G</h1>
    <p class="hero-answer">The Rock'n G Plumbing blog answers plumbing questions for San Angelo, Texas homes: what drives a water heater price, how to protect pipes before a freeze, and what to ask a plumber before work starts. Each guide is written for homes in San Angelo and Tom Green County.</p>
    <ul class="bg-cats" aria-label="Topics">
      <?php foreach ($bgCategories as $cat): ?><li><?php echo h($cat); ?></li><?php endforeach; ?>
    </ul>
  </div>
</section>

<article class="bg-article" aria-labelledby="bg-h1">

  <!-- ============ FEATURED (newest registry post) ============ -->
  <section class="section bg-featured" aria-labelledby="bg-featured-title">
    <div class="container-wide">
      <div class="section-head reveal-up">
        <span class="eyebrow">Newest guide</span>
        <h2 id="bg-featured-title">What is the newest plumbing guide for San Angelo homes?</h2>
        <p class="answer-block">The newest guide, published <?php echo h($bgFeatured['date']); ?> by Page One Insights LLC for Rock'n G Plumbing, is &ldquo;<?php echo h($bgFeatured['title']); ?>&rdquo; (<?php echo h($bgFeatured['readtime']); ?>).</p>
      </div>
      <a class="bg-feature reveal-scale" href="/blog/<?php echo h($bgFeatured['slug']); ?>/">
        <div class="bg-feature__image"><?php echo pictureTag($bgFeatured['photo'], $bgFeatured['alt'], '(max-width: 533px) calc(100vw - 2rem), (max-width: 900px) 94vw, (max-width: 1464px) 56vw, 817px', ['loading' => 'eager']); ?></div>
        <div class="bg-feature__body">
          <span class="bg-feature__tag"><?php echo h($bgFeatured['category']); ?></span>
          <h3><?php echo h($bgFeatured['title']); ?></h3>
          <p><?php echo h($bgFeatured['excerpt']); ?></p>
          <span class="bg-feature__meta"><?php echo h($bgFeatured['date']); ?> · <?php echo h($bgFeatured['readtime']); ?></span>
          <span class="bg-feature__cta">Read article</span>
        </div>
      </a>
    </div>
  </section>

  <!-- ============ ALL OTHER POSTS ============ -->
  <section class="section slant-top bg-more" aria-labelledby="bg-more-title">
    <div class="container">
      <div class="section-head reveal-up">
        <span class="eyebrow">More guides</span>
        <h2 id="bg-more-title">Which other plumbing questions do these guides answer?</h2>
        <p class="answer-block">The other guides cover <?php echo h(implode(' and ', array_map('strtolower', $bgCategories))); ?> for homes in San Angelo. More guides will be added over time, and every one links to the Rock'n G service page that does the work.</p>
      </div>
      <?php if ($bgRest): ?>
      <div class="blog-grid">
        <?php foreach ($bgRest as $bp) echo renderBlogCard($bp, '(max-width: 400px) calc(100vw - 2rem), (max-width: 600px) 92vw, (max-width: 800px) calc(46vw - .75rem), (max-width: 900px) calc(50vw - 2.75rem), (max-width: 1200px) calc(33.3vw - 2.33rem), 363px'); ?>
      </div>
      <?php endif; ?>
      <div class="bg-soon reveal-up">
        <p>Looking for something that is not here yet? Ask the office at <a href="<?php echo h($telHref); ?>"><?php echo h($phoneFmt); ?></a>, <?php echo h($weekday[0]); ?> <?php echo h($weekday[1]); ?>, or browse the <a href="/faqs/">plumbing FAQs</a>.</p>
      </div>
    </div>
  </section>

  <!-- ============ HOW THE GUIDES ARE WRITTEN ============ -->
  <section class="section bg-method" aria-labelledby="bg-method-title">
    <div class="container">
      <div class="section-head reveal-up">
        <span class="eyebrow">About these guides</span>
        <h2 id="bg-method-title">How are the Rock'n G Plumbing guides written?</h2>
        <p class="answer-block">The guides are published by Page One Insights LLC for Rock'n G Plumbing and give general plumbing guidance for San Angelo, Texas homes. Prices, permit rules and warranty terms appear only when they can be confirmed, so each guide points you to the estimate or to the City of San Angelo for the specifics.</p>
      </div>
      <div class="grid-3">
        <div class="bg-topic card-tint-2 reveal-up"><?php echo icon('map-pin', 26); ?><h3>Local first</h3><p>Each guide is written around San Angelo and Tom Green County conditions, such as hard water and West Texas freezes.</p></div>
        <div class="bg-topic card-tint-3 reveal-up reveal-delay-1"><?php echo icon('clipboard-list', 26); ?><h3>No guessed prices</h3><p>Dollar figures appear only when Rock'n G Plumbing confirms them. Until then, a guide lists what moves the price.</p></div>
        <div class="bg-topic card-tint-1 reveal-up reveal-delay-2"><?php echo icon('badge-check', 26); ?><h3>A license you can check</h3><p>Rock'n G's work runs under Texas Master Plumber License <?php echo h($license['number']); ?>, searchable with the Texas State Board of Plumbing Examiners.</p></div>
      </div>
    </div>
  </section>

  <!-- ============ SERVICE TOPICS ============ -->
  <section class="section bg-topics" aria-labelledby="bg-topics-title">
    <div class="container">
      <div class="section-head reveal-up">
        <span class="eyebrow">From guide to job</span>
        <h2 id="bg-topics-title">Which Rock'n G Plumbing service goes with each guide?</h2>
        <p class="answer-block">Each guide points to the service page that handles the work. Water heater questions go to the water heater page, freeze and leak questions go to leak detection, and anything involving gas piping goes to the gas line page.</p>
      </div>
      <div class="grid-3">
        <a class="bg-topic card-tint-1 reveal-up" href="/services/water-heaters/"><?php echo icon('flame', 26); ?><h3>Water heaters</h3><p>Repair, replacement and tankless installation.</p></a>
        <a class="bg-topic card-tint-2 reveal-up reveal-delay-1" href="/services/leak-detection-repair/"><?php echo icon('layers', 26); ?><h3>Leak detection</h3><p>Hidden and under-slab leaks, plus water line repair.</p></a>
        <a class="bg-topic card-tint-3 reveal-up reveal-delay-2" href="/services/gas-line-services/"><?php echo icon('wrench', 26); ?><h3>Gas lines</h3><p>New gas piping and repairs for appliances and generators.</p></a>
      </div>
    </div>
  </section>

  <!-- ============ FINAL CTA ============ -->
  <section class="closing-cta texture-grain edge-torn-top bg-cta" aria-labelledby="bg-cta-title">
    <span class="grain-layer" aria-hidden="true"></span>
    <span class="floating-ring bg-cta__ring" aria-hidden="true"></span>
    <div class="container">
      <div class="reveal-left">
        <span class="eyebrow">Skip the reading</span>
        <h2 id="bg-cta-title">Ready for a free plumbing estimate in San Angelo?</h2>
        <p>Call Rock'n G Plumbing at <?php echo h($phoneFmt); ?>, <?php echo h($weekday[0]); ?> <?php echo h($weekday[1]); ?>, or send the form and the office will follow up during business hours.</p>
      </div>
      <div class="actions reveal-right">
        <a class="btn btn-accent btn-lg" href="/contact/" data-open-estimate>Get a Free Estimate</a>
        <a class="link-call" href="<?php echo h($telHref); ?>"><?php echo icon('phone', 18); ?>Call <?php echo h($phoneFmt); ?></a>
      </div>
    </div>
  </section>

</article>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>
