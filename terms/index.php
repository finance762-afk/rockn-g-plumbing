<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/functions.php';
?>
<?php
/**
 * terms/index.php — Terms of Service (Phase 5, legal-compliance.md template adapted to this site).
 * Facts come from includes/config.php only. Entity type and state of formation were not collected at intake:
 * $companyEntityType stays empty (no suffix is printed) and governing law uses the state the business operates in.
 * Plumbing-specific edits to the shared template: no insurance-claim clause, no "insured" or workers' comp claim
 * (insurance was not provided), and the form processor is the Page One lead system, not Formsubmit.
 */
$pageType        = 'other';
$pageTitle       = "Terms of Service | Rock'n G Plumbing";
$pageDescription = "Terms of Service for the Rock'n G Plumbing website and plumbing services in San Angelo, TX: estimates, work, payment, text messages. Call (325) 650-7000.";
$canonicalUrl    = $siteUrl . '/terms/';
$ogImage         = '/assets/images/og-default-v1.jpg';
$currentPage     = 'terms';
$breadcrumbs     = [['name' => 'Terms of Service', 'url' => '/terms/']];

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
    <h1 id="lg-h1">Terms of Service</h1>
    <p>The terms for using this website and hiring Rock'n G Plumbing.</p>
    <p><span class="lg-hero__meta">Last Updated: <?php echo h($lastUpdated); ?></span></p>
  </div>
</section>

<div class="lg-crumbs">
  <nav class="breadcrumb" aria-label="Breadcrumb">
    <div class="container">
      <ol>
        <li><a href="/">Home</a></li>
        <li class="breadcrumb-sep" aria-hidden="true">/</li>
        <li><span aria-current="page">Terms of Service</span></li>
      </ol>
    </div>
  </nav>
</div>

<div class="lg-body">
  <div class="container">
    <article class="legal-prose content-narrow">
      <h2>1. Agreement to Terms</h2>
      <p>By using <?php echo h($domain); ?> or asking <?php echo h($companyName); ?> for plumbing services, you agree to these Terms of Service. If you do not agree, please do not use this site or our services.</p>

      <h2>2. Use of This Website</h2>
      <ul>
        <li>You may use this site for personal, non-commercial purposes to learn about our services and contact us.</li>
        <li>You may not use the site for unlawful purposes, try to access non-public systems, copy or scrape content without written permission, send false information through our forms, or use automated tools to extract data.</li>
      </ul>

      <h2>3. Estimates and Quotes</h2>
      <p><?php echo h($companyName); ?> gives free estimates. An estimate is based on the information you give us and the conditions visible when we look at the job. The final price can differ if the scope changes, hidden conditions are found once walls, floors or the ground are opened, material costs change, or code requirements differ from what was assumed. Verbal estimates are non-binding. Only a written agreement that both sides accept sets a final price.</p>

      <h2>4. Plumbing Work</h2>
      <ul>
        <li>Work is governed by the estimate or written agreement for your specific job.</li>
        <li>Plumbing work is performed under <?php echo h($license["label"]); ?>, issued by the Texas State Board of Plumbing Examiners.</li>
        <li>We perform work in line with applicable Texas plumbing law and the codes of the city or county where the job is located.</li>
      </ul>

      <h2>5. Warranties</h2>
      <p>Any warranty on our work is stated in writing for the specific job. Manufacturer warranties on equipment and materials come from those manufacturers. Warranties do not cover damage from neglect, misuse or changes made by others, or conditions that existed before our work and were disclosed to you.</p>

      <h2>6. Payment Terms</h2>
      <p>Payment terms are stated in your estimate or written agreement. Past-due balances may accrue interest as permitted by <?php echo h($companyState); ?> law.</p>

      <h2>7. Cancellation and Rescheduling</h2>
      <p>Cancellation and rescheduling terms are stated in your estimate or written agreement. If you need to cancel or move a visit, please call us as early as you can at <a href="tel:<?php echo h($companyPhoneE164); ?>"><?php echo h($companyPhone); ?></a>.</p>

      <h2>8. Text Message Terms</h2>
      <p>If you opt in to text messages on our forms, <?php echo h($companyName); ?> may send you texts about your request, such as updates and scheduling. Message frequency varies. Message and data rates may apply. Reply STOP to unsubscribe, HELP for help. Consent is not a condition of purchase. Carriers are not liable for delayed or undelivered messages. See our <a href="/privacy-policy/">Privacy Policy</a> for how we handle your information.</p>

      <h2>9. Limitation of Liability</h2>
      <p>To the maximum extent permitted by <?php echo h($companyState); ?> law, the total liability of <?php echo h($companyName); ?> for any claim related to this site or our services will not exceed the amount you paid for the specific service that gave rise to the claim. We are not liable for indirect, incidental, special or consequential damages.</p>

      <h2>10. Intellectual Property</h2>
      <p>The text, graphics, photographs and logos on this site belong to <?php echo h($companyName); ?> or are used with permission and are protected by copyright. You may not reproduce, distribute or create derivative works from them without written permission.</p>

      <h2>11. Governing Law and Disputes</h2>
      <p>These Terms are governed by the laws of the State of <?php echo h($companyState); ?>, without regard to conflict-of-laws rules. Any dispute will be resolved in the state or federal courts located in <?php echo h($companyCounty); ?> County, <?php echo h($companyState); ?>.</p>

      <h2>12. Changes to These Terms</h2>
      <p>We may update these Terms at any time. The "Last Updated" date shows the most recent version, and continued use of the site after an update means you accept the revised Terms.</p>

      <h2>13. Contact Us</h2>
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
