<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/functions.php';
?>
<?php
/**
 * accessibility/index.php — Accessibility Statement (Phase 5, legal-compliance.md template adapted to this site).
 * Facts come from includes/config.php only. Entity type and state of formation were not collected at intake:
 * $companyEntityType stays empty (no suffix is printed) and governing law uses the state the business operates in.
 * Plumbing-specific edits to the shared template: no insurance-claim clause, no "insured" or workers' comp claim
 * (insurance was not provided), and the form processor is the Page One lead system, not Formsubmit.
 */
$pageType        = 'other';
$pageTitle       = "Accessibility Statement | Rock'n G Plumbing";
$pageDescription = "Accessibility Statement for the Rock'n G Plumbing website in San Angelo, TX: our WCAG 2.1 AA goal and known issues. Call (325) 650-7000 to report a barrier.";
$canonicalUrl    = $siteUrl . '/accessibility/';
$ogImage         = '/assets/images/og-default-v1.jpg';
$currentPage     = 'accessibility';
$breadcrumbs     = [['name' => 'Accessibility Statement', 'url' => '/accessibility/']];

// Legal-page variables (all from config.php)
$companyName       = $siteName . ($companyEntityType !== '' ? ', ' . $companyEntityType : '');
$companyEmail      = $email;
$companyPhone      = formatPhone($phone);
$companyPhoneE164  = $phoneE164;
$companyAddress    = $address['city'] . ', ' . $address['state'] . ' (service-area business; no public storefront)';
$lastUpdated       = 'October 18, 2026';
$weekday           = hoursGroups($hours)[0];

$schemaMarkup = [[
    "@context"    => 'https://schema.org',
    '@type'       => 'WebPage',
    '@id'         => $canonicalUrl . '#webpage',
    'url'         => $canonicalUrl,
    'name'        => $pageTitle,
    'description' => $pageDescription,
    'isPartOf'    => ['@id' => $siteUrl . '/#website'],
    'about'       => ['@id' => orgId()],
    'inLanguage'  => 'en-US',
]];

ob_start();
?>
<style>
/* =====================================================================
   Legal page — page-only CSS (lg- prefix). Tokens from framework.css; no
   raw colors, shadows or spacing. 40vh solid hero, single narrow column.
   ===================================================================== */
.lg-hero {
  display: flex;
  align-items: flex-end;
  min-height: 40vh;
  position: relative;
  isolation: isolate;
  overflow: clip;
}
.lg-hero::before {
  content: "";
  position: absolute;
  inset: 0;
  z-index: -1;
  pointer-events: none;
  background: linear-gradient(115deg, var(--color-dark) 0%, var(--color-dark-alt) 60%, var(--color-primary-deep) 100%);
}
.lg-hero .container {
  width: 100%;
}
.lg-hero h1 {
  max-width: 20ch;
  text-wrap: balance;
}
.lg-hero p {
  margin: var(--space-3) 0 0;
  color: color-mix(in srgb, var(--color-surface) 82%, transparent);
}
.lg-hero__meta {
  display: inline-flex;
  align-items: center;
  gap: var(--space-2);
  padding: var(--space-1) var(--space-3);
  border: 1px solid color-mix(in srgb, var(--color-surface) 22%, transparent);
  border-radius: var(--radius-full);
  font-size: var(--fs-small);
}
.lg-crumbs {
  padding-block: var(--space-3);
  background: var(--color-surface);
  border-bottom: 1px solid var(--color-line);
}
.lg-crumbs .breadcrumb {
  margin: 0;
}
.lg-crumbs .breadcrumb > .container {
  width: 100%;
}
.lg-body {
  padding-block: var(--space-12) var(--space-16);
  background: var(--color-paper);
}
.lg-body .legal-prose {
  margin-inline: auto;
}
.lg-body .legal-prose p,
.lg-body .legal-prose li {
  color: var(--color-ink-2);
}
.lg-body .legal-prose ul,
.lg-body .legal-prose ol {
  margin-block: 0 var(--space-4);
  padding-inline-start: var(--space-6);
}
.lg-body .legal-prose li + li {
  margin-block-start: var(--space-2);
}
.lg-body .legal-prose a {
  color: var(--color-primary);
  font-weight: 600;
  text-decoration: underline;
  text-underline-offset: .2em;
}
.lg-body .legal-prose h2,
.lg-body .legal-prose h3 {
  scroll-margin-top: calc(var(--nav-height) + var(--space-4));
}
.lg-note {
  margin-block: var(--space-5);
  padding: var(--space-4) var(--space-5);
  border-left: 3px solid var(--color-accent);
  border-radius: 0 var(--radius) var(--radius) 0;
  background: var(--color-card-tint-3);
}
.lg-note p:last-child {
  margin-bottom: 0;
}
.lg-contact {
  padding: var(--space-5);
  border: 1px solid var(--color-line);
  border-radius: var(--radius-lg);
  background: var(--color-surface);
  box-shadow: var(--shadow-sm);
}
.lg-contact p {
  margin: 0;
}
.lg-updated {
  margin-block-start: var(--space-8);
  padding-block-start: var(--space-4);
  border-top: 1px solid var(--color-line);
  font-size: var(--fs-small);
  color: var(--color-muted);
}
@media (max-width: 760px) {
  .lg-hero {
    min-height: 0;
  }
}
</style>
<?php
$extraHead = ob_get_clean();

include $_SERVER['DOCUMENT_ROOT'] . '/includes/head.php';
include $_SERVER['DOCUMENT_ROOT'] . '/includes/header.php';
?>

<section class="hero hero--legal lg-hero" aria-labelledby="lg-h1">
  <div class="container">
    <span class="eyebrow">Legal</span>
    <h1 id="lg-h1">Accessibility Statement</h1>
    <p>Our goal of a website everyone can use, and how to tell us when it falls short.</p>
    <p><span class="lg-hero__meta">Last Updated: <?php echo h($lastUpdated); ?></span></p>
  </div>
</section>

<div class="lg-crumbs">
  <nav class="breadcrumb" aria-label="Breadcrumb">
    <div class="container">
      <ol>
        <li><a href="/">Home</a></li>
        <li class="breadcrumb-sep" aria-hidden="true">/</li>
        <li><span aria-current="page">Accessibility Statement</span></li>
      </ol>
    </div>
  </nav>
</div>

<div class="lg-body">
  <div class="container">
    <article class="legal-prose content-narrow">
      <h2>1. Our Commitment</h2>
      <p><?php echo h($companyName); ?> wants everyone to be able to use this website, including people with disabilities. We keep improving the experience and apply recognized accessibility standards to <?php echo h($domain); ?>.</p>

      <h2>2. Conformance Status</h2>
      <p>This site is designed to meet the Web Content Accessibility Guidelines (WCAG) 2.1 Level AA. It partially conforms, meaning some content may not yet fully meet the standard, and we are working to fix known issues.</p>

      <h2>3. Accessibility Features</h2>
      <ul>
        <li>Semantic HTML5 with landmark regions (header, navigation, main, footer)</li>
        <li>A skip-to-content link at the top of every page</li>
        <li>Visible keyboard focus indicators on links, buttons and form fields</li>
        <li>Menu buttons that report whether the menu is open or closed to screen readers</li>
        <li>Descriptive alt text on meaningful images</li>
        <li>Text and interactive elements designed to meet AA color contrast</li>
        <li>A responsive layout that works across screen sizes and zoom levels</li>
        <li>Reduced motion: animations are turned off for visitors whose device asks for reduced motion</li>
        <li>Labels associated with every form field</li>
      </ul>

      <h2>4. Known Issues</h2>
      <ul>
        <li>The Google Map on the contact page is third-party content and may not fully meet WCAG. You can reach us by phone or email instead, as listed below.</li>
        <li>Photographs of job sites may show equipment or conditions that are hard to describe fully in alt text. Call us if you want a photo described.</li>
      </ul>

      <h2>5. Feedback and Reporting Issues</h2>
      <p>If you run into an accessibility barrier on this site, please tell us which page and what happened. We aim to respond within 5 business days.</p>

      <h2>6. Alternative Ways to Reach Us</h2>
      <p>If the website is not accessible to you, call <a href="tel:<?php echo h($companyPhoneE164); ?>"><?php echo h($companyPhone); ?></a> during business hours or email <!--email_off--><a href="mailto:<?php echo h($companyEmail); ?>"><?php echo h($companyEmail); ?></a><!--/email_off-->. We can give you service information by phone or in another format on request.</p>

      <h2>7. Changes to This Statement</h2>
      <p>We update this statement as the site changes. The "Last Updated" date shows the most recent version.</p>

      <h2>8. Contact Us</h2>
      <div class="lg-contact">
        <p><strong><?php echo h($companyName); ?></strong><br>
        Email: <!--email_off--><a href="mailto:<?php echo h($companyEmail); ?>"><?php echo h($companyEmail); ?></a><!--/email_off--><br>
        Phone: <a href="tel:<?php echo h($companyPhoneE164); ?>"><?php echo h($companyPhone); ?></a><br>
        Hours: <?php echo h($weekday[0]); ?>, <?php echo h($weekday[1]); ?><br>
        Location: <?php echo h($companyAddress); ?></p>
      </div>
      <p class="lg-updated">Last Updated: <?php echo h($lastUpdated); ?></p>
    </article>
  </div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>
