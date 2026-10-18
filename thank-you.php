<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/functions.php';
?>
<?php
/**
 * thank-you.php — form confirmation (Phase 5). Every lead form's _next points here (absolute $siteUrl . '/thank-you').
 * Noindex. The leads endpoint stores the lead and notifies the office; no response-time promise is made because intake
 * has not confirmed one (see /content-confirmations.json).
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/service-cards.php';

$pageType        = 'other';
$noindex         = true;
$pageTitle       = "Thank You | Rock'n G Plumbing, San Angelo";
$pageDescription = "Thank you for contacting Rock'n G Plumbing, your San Angelo, TX plumber. The office follows up on every request; call (325) 650-7000 for anything urgent.";
$canonicalUrl    = $siteUrl . '/thank-you';
$ogImage         = '/assets/images/og-default-v1.jpg';
$currentPage     = 'thank-you';

$telHref  = 'tel:' . $phoneE164;
$phoneFmt = formatPhone($phone);
$weekday  = hoursGroups($hours)[0];

ob_start();
?>
<style>
/* =====================================================================
   Thank-you — page-only CSS (ty- prefix). Tokens from framework.css; no
   raw colors, shadows or spacing.
   ===================================================================== */
.ty-hero {
  position: relative;
  isolation: isolate;
  overflow: clip;
  padding-block-end: var(--space-16);
}
.ty-hero::before {
  content: "";
  position: absolute;
  inset: 0;
  z-index: -1;
  pointer-events: none;
  background:
    radial-gradient(ellipse 50% 90% at 0% 0%, color-mix(in srgb, var(--color-success) 28%, transparent), transparent 65%),
    linear-gradient(115deg, var(--color-dark) 0%, var(--color-dark-alt) 60%, var(--color-primary-deep) 100%);
}
.ty-hero__check {
  display: grid;
  place-items: center;
  width: 4rem;
  height: 4rem;
  margin-block-end: var(--space-4);
  border-radius: 50%;
  background: color-mix(in srgb, var(--color-success) 85%, var(--color-surface));
  color: var(--color-surface);
  box-shadow: var(--shadow);
}
.ty-hero .link-call,
.ty-hero .link-call svg {
  color: color-mix(in srgb, var(--color-surface) 92%, transparent);
}
.ty-hero .link-call:hover {
  color: var(--color-surface);
}
.ty-hero h1 {
  max-width: 20ch;
  text-wrap: balance;
}
.ty-hero .hero-answer {
  max-width: 58ch;
  margin-block: var(--space-4) var(--space-6);
}

.ty-body {
  background: var(--color-paper);
}
.ty-grid {
  display: grid;
  grid-template-columns: minmax(0, 1.1fr) minmax(0, .9fr);
  gap: clamp(1.5rem, 4vw, 3rem);
  align-items: start;
}
.ty-card {
  padding: var(--space-8);
  border: 1px solid var(--color-line);
  border-radius: var(--radius-lg);
  box-shadow: var(--shadow-sm);
}
.ty-card h2 {
  margin-block-end: var(--space-3);
  font-size: var(--fs-h3);
  text-wrap: balance;
}
.ty-card .next-steps {
  margin-block-start: 0;
}
.ty-card > p {
  margin: 0 0 var(--space-4);
  color: var(--color-ink-2);
}
.ty-links {
  display: grid;
  gap: var(--space-1);
  margin: 0;
  padding: 0;
  list-style: none;
}
.ty-links a {
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
.ty-links a:hover,
.ty-links a:focus-visible {
  background: var(--color-surface);
  transform: translateX(4px);
}
.ty-links svg {
  color: var(--color-accent-dark);
  flex: 0 0 auto;
}
.ty-card .actions {
  display: flex;
  flex-wrap: wrap;
  gap: var(--space-3);
  margin-block-start: var(--space-4);
}

@media (max-width: 800px) {
  .ty-grid {
    grid-template-columns: 1fr;
  }
}
@media (prefers-reduced-motion: reduce) {
  .ty-links a {
    transition: none;
  }
  .ty-links a:hover {
    transform: none;
  }
}
</style>
<?php
$extraHead = ob_get_clean();

include $_SERVER['DOCUMENT_ROOT'] . '/includes/head.php';
include $_SERVER['DOCUMENT_ROOT'] . '/includes/header.php';
?>

<!-- ============ CONFIRMATION ============ -->
<section class="hero hero--interior ty-hero" aria-labelledby="ty-h1">
  <span class="grain" aria-hidden="true"></span>
  <div class="container">
    <span class="ty-hero__check"><?php echo icon('check', 32); ?></span>
    <span class="eyebrow">Request received</span>
    <h1 id="ty-h1">Thank You: Rock'n G Plumbing Got Your Request</h1>
    <p class="hero-answer">Your message reached the Rock'n G Plumbing office in San Angelo, and someone will follow up with you about your free estimate. If it cannot wait, call <a href="<?php echo h($telHref); ?>"><?php echo h($phoneFmt); ?></a>, <?php echo h($weekday[0]); ?> <?php echo h($weekday[1]); ?>.</p>
    <div class="hero-actions">
      <a class="btn btn-accent btn-lg" href="<?php echo h($telHref); ?>"><?php echo icon('phone', 18); ?>Call <?php echo h($phoneFmt); ?></a>
      <a class="link-call" href="/">Back to the homepage</a>
    </div>
  </div>
</section>

<article aria-labelledby="ty-h1">
  <section class="section ty-body" aria-label="What happens next">
    <div class="container">
      <div class="ty-grid">
        <div class="ty-card card-tint-3 reveal-left">
          <h2>What to expect next</h2>
          <ol class="next-steps">
            <li><strong>The office reviews your request.</strong> Requests are read during business hours, <?php echo h($weekday[0]); ?> <?php echo h($weekday[1]); ?>.</li>
            <li><strong>You hear back about your free estimate.</strong> Rock'n G Plumbing looks at the problem and tells you what the job involves before any work starts.</li>
            <li><strong>You decide.</strong> There is no obligation, and if you ask for the military discount the office will explain how it applies.</li>
          </ol>
          <div class="actions">
            <a class="btn btn-secondary" href="<?php echo h($gbp['review_url']); ?>" target="_blank" rel="noopener">Already a customer? Leave a Google review</a>
          </div>
        </div>
        <div class="ty-card card-tint-1 reveal-right">
          <h2>While you wait, browse our services</h2>
          <p>Rock'n G Plumbing handles these jobs for San Angelo and Christoval homes and businesses.</p>
          <ul class="ty-links">
            <?php foreach ($services as $tySvc):
              if (!pageExists(serviceUrl($tySvc))) continue; ?>
            <li><a href="<?php echo h(serviceUrl($tySvc)); ?>"><?php echo icon('arrow-right', 18); ?><?php echo h($tySvc['name']); ?></a></li>
            <?php endforeach; ?>
            <?php if (pageExists('/faqs/')): ?>
            <li><a href="/faqs/"><?php echo icon('info', 18); ?>Plumbing FAQs</a></li>
            <?php endif; ?>
          </ul>
        </div>
      </div>
    </div>
  </section>
</article>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>
