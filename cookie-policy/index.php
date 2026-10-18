<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/functions.php';
?>
<?php
/**
 * cookie-policy/index.php — Cookie Policy (Phase 5, legal-compliance.md template adapted to this site).
 * Facts come from includes/config.php only. Entity type and state of formation were not collected at intake:
 * $companyEntityType stays empty (no suffix is printed) and governing law uses the state the business operates in.
 * Plumbing-specific edits to the shared template: no insurance-claim clause, no "insured" or workers' comp claim
 * (insurance was not provided), and the form processor is the Page One lead system, not Formsubmit.
 */
$pageType        = 'other';
$pageTitle       = "Cookie Policy | Rock'n G Plumbing";
$pageDescription = "Cookie Policy for the Rock'n G Plumbing website in San Angelo, TX: the cookies we use, why we use them and how to control them. Call (325) 650-7000.";
$canonicalUrl    = $siteUrl . '/cookie-policy/';
$ogImage         = '/assets/images/og-default-v1.jpg';
$currentPage     = 'cookie-policy';
$breadcrumbs     = [['name' => 'Cookie Policy', 'url' => '/cookie-policy/']];

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
    <h1 id="lg-h1">Cookie Policy</h1>
    <p>The cookies this website uses, why, and how to control them.</p>
    <p><span class="lg-hero__meta">Last Updated: <?php echo h($lastUpdated); ?></span></p>
  </div>
</section>

<div class="lg-crumbs">
  <nav class="breadcrumb" aria-label="Breadcrumb">
    <div class="container">
      <ol>
        <li><a href="/">Home</a></li>
        <li class="breadcrumb-sep" aria-hidden="true">/</li>
        <li><span aria-current="page">Cookie Policy</span></li>
      </ol>
    </div>
  </nav>
</div>

<div class="lg-body">
  <div class="container">
    <article class="legal-prose content-narrow">
      <h2>1. What Are Cookies?</h2>
      <p>Cookies are small text files stored on your device when you visit a website. They help sites work, remember choices and show owners how the site is used.</p>

      <h2>2. Cookies We Use</h2>

      <h3>Strictly Necessary and Site Operation</h3>
      <ul>
        <li><strong>p1_ft (first-party, up to 30 days):</strong> saved the first time you visit. It holds the first page you landed on, the referring site, campaign tags in the link (UTM parameters, Google click ID), your device type and a random session ID. It is sent with any form you submit so <?php echo h($companyName); ?> knows which page brought the request. It is not used to identify you across other sites.</li>
        <li><strong>Cookie notice choice:</strong> when you tap "Got it" on the cookie notice, your browser remembers that in its local storage so the notice does not return. It is not sent to us.</li>
      </ul>

      <h3>Analytics (Google Analytics 4)</h3>
      <p>We use Google Analytics 4 to understand how visitors use this site, such as which pages are viewed. When active, it sets cookies whose names begin with _ga. Google Analytics collects usage data and an IP-based approximate location, and Google processes it under its own privacy policy.</p>

      <h3>Third-Party Content</h3>
      <p>The contact page shows a Google Map, and links lead to our Google Business Profile. Google may set its own cookies when that content loads, subject to Google's privacy policy. Our fonts are hosted on this site, so no font provider sets cookies.</p>

      <h2>3. How to Control Cookies</h2>
      <p>Most browsers let you view, delete or block cookies. You can block third-party cookies or all cookies, though parts of the site, such as source tracking on forms, may then work less well. Instructions are available from Google, Mozilla, Apple and Microsoft.</p>

      <h2>4. Opt Out of Google Analytics</h2>
      <p>You can opt out of Google Analytics on all sites by installing the <a href="https://tools.google.com/dlpage/gaoptout" target="_blank" rel="noopener">Google Analytics Opt-out Browser Add-on</a>.</p>

      <h2>5. Our Cookie Notice</h2>
      <p>We show a short notice that this site uses cookies for analytics. Once you dismiss it, it stays hidden in that browser. Clearing your browser's site data brings it back.</p>

      <h2>6. Changes to This Policy</h2>
      <p>We may update this Cookie Policy as the site changes. The "Last Updated" date shows the most recent version. See our <a href="/privacy-policy/">Privacy Policy</a> for how we handle personal information.</p>

      <h2>7. Contact Us</h2>
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
