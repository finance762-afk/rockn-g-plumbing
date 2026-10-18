<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/functions.php';
?>
<?php
/**
 * 404.php — friendly not-found page (Phase 5). Served by .htaccess ErrorDocument 404 /404.php; sends a real 404 status
 * and stays noindex. Links only to pages that exist (pageExists) so the error page never adds a second broken link.
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/service-cards.php';

http_response_code(404);

$pageType        = 'other';
$noindex         = true;
$pageTitle       = "Page Not Found | Rock'n G Plumbing, San Angelo";
$pageDescription = "That page was not found. Rock'n G Plumbing, your San Angelo, TX plumber, can still help: browse plumbing services or call (325) 650-7000 for a free estimate.";
$canonicalUrl    = $siteUrl . '/404';
$ogImage         = '/assets/images/og-default-v1.jpg';
$currentPage     = '404';

$telHref  = 'tel:' . $phoneE164;
$phoneFmt = formatPhone($phone);
$weekday  = hoursGroups($hours)[0];

// "What were you looking for?" — plumbing problems people search, each pointing at the page that handles it
$nfSearches = [
    ['text' => 'water heater repair or replacement', 'slug' => 'water-heaters'],
    ['text' => 'hard water, softeners and filters',  'slug' => 'water-filtration-softeners'],
    ['text' => 'a clogged or slow drain',            'slug' => 'drain-cleaning'],
    ['text' => 'sewer line repair',                  'slug' => 'sewer-line-repair'],
    ['text' => 'a hidden or slab leak',              'slug' => 'leak-detection-repair'],
    ['text' => 'gas line work',                      'slug' => 'gas-line-services'],
    ['text' => 'sprinkler and irrigation repair',    'slug' => 'irrigation-sprinkler-repair'],
    ['text' => 'new construction or remodel plumbing','slug' => 'new-construction-remodel-plumbing'],
];
$nfPopular = [['Home', '/', 'home'], ['All plumbing services', '/services/', 'wrench'], ['About Rock\'n G Plumbing', '/about/', 'users'], ['Plumbing FAQs', '/faqs/', 'info'], ['Contact and free estimates', '/contact/', 'phone']];

ob_start();
?>
<style>
/* =====================================================================
   404 — page-only CSS (nf- prefix). Tokens from framework.css; no raw
   colors, shadows or spacing.
   ===================================================================== */
.nf-hero {
  position: relative;
  isolation: isolate;
  overflow: clip;
  padding-block-end: var(--space-16);
}
.nf-hero::before {
  content: "";
  position: absolute;
  inset: 0;
  z-index: -1;
  pointer-events: none;
  background:
    radial-gradient(ellipse 50% 90% at 100% 0%, color-mix(in srgb, var(--color-accent) 24%, transparent), transparent 65%),
    linear-gradient(115deg, var(--color-dark) 0%, var(--color-dark-alt) 60%, var(--color-primary-deep) 100%);
}
.nf-hero::after {
  content: "404";
  position: absolute;
  inset-block-end: -.18em;
  inset-inline-end: .05em;
  z-index: -1;
  font-family: var(--font-heading);
  font-weight: 800;
  font-size: clamp(8rem, 26vw, 20rem);
  line-height: 1;
  color: color-mix(in srgb, var(--color-surface) 6%, transparent);
  pointer-events: none;
}
.nf-hero .link-call,
.nf-hero .link-call svg {
  color: color-mix(in srgb, var(--color-surface) 92%, transparent);
}
.nf-hero .link-call:hover {
  color: var(--color-surface);
}
.nf-hero h1 {
  max-width: 20ch;
  text-wrap: balance;
}
.nf-hero .hero-answer {
  max-width: 58ch;
  margin-block: var(--space-4) var(--space-6);
}

.nf-body {
  background: var(--color-paper);
}
.nf-grid {
  display: grid;
  grid-template-columns: repeat(2, minmax(0, 1fr));
  gap: var(--space-6);
}
.nf-card {
  padding: var(--space-8);
  border: 1px solid var(--color-line);
  border-radius: var(--radius-lg);
  box-shadow: var(--shadow-sm);
}
.nf-card h2 {
  margin-block-end: var(--space-2);
  font-size: var(--fs-h3);
  text-wrap: balance;
}
.nf-card > p {
  margin: 0 0 var(--space-4);
  color: var(--color-ink-2);
}
.nf-list {
  display: grid;
  gap: var(--space-1);
  margin: 0;
  padding: 0;
  list-style: none;
}
.nf-list a {
  display: flex;
  align-items: center;
  gap: var(--space-3);
  min-height: 44px;
  padding: var(--space-2) var(--space-3);
  border-radius: var(--radius);
  font-weight: 600;
  color: var(--color-ink);
  transition: background var(--transition), transform var(--transition);
}
.nf-list a:hover,
.nf-list a:focus-visible {
  background: var(--color-surface);
  transform: translateX(4px);
}
.nf-list svg {
  color: var(--color-accent-dark);
  flex: 0 0 auto;
}

.nf-cta .link-call {
  color: color-mix(in srgb, var(--color-surface) 90%, transparent);
}
.nf-cta .link-call svg {
  color: var(--color-accent-bright);
}
@media (max-width: 800px) {
  .nf-grid {
    grid-template-columns: 1fr;
  }
}
@media (prefers-reduced-motion: reduce) {
  .nf-list a {
    transition: none;
  }
  .nf-list a:hover {
    transform: none;
  }
}
</style>
<?php
$extraHead = ob_get_clean();

include $_SERVER['DOCUMENT_ROOT'] . '/includes/head.php';
include $_SERVER['DOCUMENT_ROOT'] . '/includes/header.php';
?>

<!-- ============ HERO ============ -->
<section class="hero hero--interior nf-hero" aria-labelledby="nf-h1">
  <span class="grain" aria-hidden="true"></span>
  <div class="container">
    <span class="eyebrow">Error 404</span>
    <h1 id="nf-h1">We Can't Find That Page, but We Can Still Fix Your Plumbing</h1>
    <p class="hero-answer">The page you tried to open does not exist or may have moved. Rock'n G Plumbing, your San Angelo plumber, is still just a phone call away at <a href="<?php echo h($telHref); ?>"><?php echo h($phoneFmt); ?></a>, <?php echo h($weekday[0]); ?> <?php echo h($weekday[1]); ?>.</p>
    <div class="hero-actions">
      <a class="btn btn-accent btn-lg" href="/">Back to the homepage</a>
      <a class="link-call" href="<?php echo h($telHref); ?>"><?php echo icon('phone', 18); ?>or call <?php echo h($phoneFmt); ?></a>
    </div>
  </div>
</section>

<article aria-labelledby="nf-h1">

  <!-- ============ SEARCH SUGGESTIONS + POPULAR PAGES ============ -->
  <section class="section nf-body" aria-label="Where to go next">
    <div class="container">
      <div class="nf-grid">
        <div class="nf-card card-tint-3 reveal-left">
          <h2>Looking for help with a plumbing problem?</h2>
          <p>Pick the plumbing problem closest to yours and we will take you to the right service page.</p>
          <ul class="nf-list">
            <?php foreach ($nfSearches as $nfS):
              $nfSvc = serviceBySlug($nfS['slug']);
              if ($nfSvc === null || !pageExists(serviceUrl($nfSvc))) continue; ?>
            <li><a href="<?php echo h(serviceUrl($nfSvc)); ?>"><?php echo icon('arrow-right', 18); ?><?php echo h(ucfirst($nfS['text'])); ?></a></li>
            <?php endforeach; ?>
          </ul>
        </div>
        <div class="nf-card card-tint-1 reveal-right">
          <h2>Which pages do people visit most?</h2>
          <p>Or start from one of the pages people visit most.</p>
          <ul class="nf-list">
            <?php foreach ($nfPopular as $nfP):
              if ($nfP[1] !== '/' && !pageExists($nfP[1])) continue; ?>
            <li><a href="<?php echo h($nfP[1]); ?>"><?php echo icon($nfP[2], 18); ?><?php echo h($nfP[0]); ?></a></li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>
    </div>
  </section>

  <!-- ============ CTA ============ -->
  <section class="closing-cta texture-grain edge-torn-top nf-cta" aria-labelledby="nf-cta-title">
    <span class="grain-layer" aria-hidden="true"></span>
    <div class="container">
      <div class="reveal-left">
        <span class="eyebrow">No charge to look</span>
        <h2 id="nf-cta-title">Need a plumber in San Angelo?</h2>
        <p>Call Rock'n G Plumbing at <?php echo h($phoneFmt); ?>, <?php echo h($weekday[0]); ?> <?php echo h($weekday[1]); ?>, or send the estimate form. Estimates are free and military discounts are available.</p>
      </div>
      <div class="actions reveal-right">
        <a class="btn btn-accent btn-lg" href="/contact/" data-open-estimate>Get a Free Estimate</a>
        <a class="link-call" href="<?php echo h($telHref); ?>"><?php echo icon('phone', 18); ?>Call <?php echo h($phoneFmt); ?></a>
      </div>
    </div>
  </section>

</article>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>
