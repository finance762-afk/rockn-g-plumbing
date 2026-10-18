<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/functions.php';
?>
<?php
/**
 * privacy-policy/index.php — Privacy Policy (Phase 5, legal-compliance.md template adapted to this site).
 * Facts come from includes/config.php only. Entity type and state of formation were not collected at intake:
 * $companyEntityType stays empty (no suffix is printed) and governing law uses the state the business operates in.
 * Plumbing-specific edits to the shared template: no insurance-claim clause, no "insured" or workers' comp claim
 * (insurance was not provided), and the form processor is the Page One lead system, not Formsubmit.
 */
$pageType        = 'other';
$pageTitle       = "Privacy Policy | Rock'n G Plumbing";
$pageDescription = "How Rock'n G Plumbing, a San Angelo, TX plumber, collects, uses and protects your information. Read our privacy practices, call (325) 650-7000 with questions.";
$canonicalUrl    = $siteUrl . '/privacy-policy/';
$ogImage         = '/assets/images/og-default-v1.jpg';
$currentPage     = 'privacy-policy';
$breadcrumbs     = [['name' => 'Privacy Policy', 'url' => '/privacy-policy/']];

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
    <h1 id="lg-h1">Privacy Policy</h1>
    <p>What we collect when you contact Rock'n G Plumbing, how we use it and the choices you have.</p>
    <p><span class="lg-hero__meta">Last Updated: <?php echo h($lastUpdated); ?></span></p>
  </div>
</section>

<div class="lg-crumbs">
  <nav class="breadcrumb" aria-label="Breadcrumb">
    <div class="container">
      <ol>
        <li><a href="/">Home</a></li>
        <li class="breadcrumb-sep" aria-hidden="true">/</li>
        <li><span aria-current="page">Privacy Policy</span></li>
      </ol>
    </div>
  </nav>
</div>

<div class="lg-body">
  <div class="container">
    <article class="legal-prose content-narrow">
      <h2>1. Introduction</h2>
      <p>This Privacy Policy explains how <?php echo h($companyName); ?> ("we", "us", "our") collects, uses and shares personal information when you visit <?php echo h($domain); ?>, call us, or send us a request through this website. <?php echo h($companyName); ?> is a plumbing company based in San Angelo, Texas that serves San Angelo, Christoval and nearby Tom Green County towns.</p>

      <h2>2. Information We Collect</h2>
      <ul>
        <li><strong>Information you give us:</strong> your name, phone number, email address, the service you need, your message, and any other details you include when you submit an estimate request on the contact page, in the free-estimate pop-up or in a service-page form.</li>
        <li><strong>Consent records:</strong> with each submission we record which consent boxes you checked, the version of the consent wording, and the page you submitted from.</li>
        <li><strong>Where your visit came from:</strong> the first page you landed on, the site or ad that referred you, campaign tags in the link (such as UTM parameters or a Google click ID), your device type (mobile, tablet or desktop) and a random session ID. These are saved in a first-party cookie for up to 30 days and sent along with any form you submit, so we know which page brought the request.</li>
        <li><strong>Automatically collected:</strong> IP address, browser and device information, pages visited and timestamps, through our hosting provider and, when enabled, Google Analytics 4.</li>
        <li><strong>Cookies and similar technologies:</strong> see our <a href="/cookie-policy/">Cookie Policy</a>.</li>
      </ul>

      <h2>3. How We Use Your Information</h2>
      <ul>
        <li>Respond to your request and prepare your free estimate</li>
        <li>Schedule visits and perform plumbing work you ask for</li>
        <li>Contact you about your request by phone, email or text, according to the consent you gave</li>
        <li>Understand which pages and sources bring requests, and improve this website</li>
        <li>Keep records required for licensing, tax and legal purposes</li>
      </ul>

      <h2>4. How We Share Your Information</h2>
      <ul>
        <li>We do <strong>NOT</strong> sell personal information, and we do not share it for cross-context behavioral advertising.</li>
        <li><strong>Service providers:</strong> Page One Insights, LLC, our website provider, operates the system that receives your form submission and emails it to us, and may see submissions while supporting and troubleshooting the site. Our hosting provider stores the website. Google provides analytics (when enabled) and the map on the contact page.</li>
        <li><strong>Suppliers and subcontractors:</strong> only the details needed to complete your job.</li>
        <li><strong>Legal compliance:</strong> where required by Texas or federal law, or to protect our rights.</li>
        <li><strong>Business transfers:</strong> if the business is merged, acquired or sold.</li>
        <li>We do not share mobile phone numbers or text-message consent with third parties or affiliates for their marketing purposes.</li>
      </ul>

      <h2>5. Your Privacy Rights</h2>

      <h3 id="state-rights"><?php echo h($companyState); ?> Residents</h3>
      <p>If you are a <?php echo h($companyState); ?> resident, you may ask us to confirm whether we process your personal data, and to give you access to it, correct it or delete it. You may also appeal a decision we make about your request. Contact us using the details below and we will respond within 45 days.</p>

      <h3 id="ccpa-rights">California Residents (CCPA / CPRA)</h3>
      <p>If you are a California resident, you have these rights under the California Consumer Privacy Act (CCPA) and California Privacy Rights Act (CPRA):</p>
      <ul>
        <li><strong>Right to know</strong> what personal information we collect, use and disclose.</li>
        <li><strong>Right to delete</strong> personal information we collected from you, subject to legal exceptions.</li>
        <li><strong>Right to correct</strong> inaccurate personal information.</li>
        <li><strong>Right to opt out of the sale or sharing</strong> of personal information. We do not sell or share personal information, and you may still send an opt-out request for our records.</li>
        <li><strong>Right to limit use</strong> of sensitive personal information.</li>
        <li><strong>Right to non-discrimination</strong> for exercising your rights.</li>
      </ul>
      <div class="lg-note">
        <p><strong>Do Not Sell or Share My Personal Information:</strong> <?php echo h($companyName); ?> does not sell or share personal information. To make an opt-out or any other privacy request, email <a href="mailto:<?php echo h($companyEmail); ?>"><?php echo h($companyEmail); ?></a> or call <a href="tel:<?php echo h($companyPhoneE164); ?>"><?php echo h($companyPhone); ?></a>. We will confirm in writing once your request has been honored.</p>
      </div>

      <h3>Other State Residents</h3>
      <p>Residents of Colorado, Virginia, Connecticut, Utah and other states with privacy laws have similar rights. Contact us the same way to exercise them.</p>

      <h2>6. Email, Text Messages and Phone Calls (TCPA)</h2>
      <p>Our forms ask for three separate, unbundled choices, and none of them is pre-checked:</p>
      <ul>
        <li><strong>Email updates (optional):</strong> if you check this box, <?php echo h($companyName); ?> may email you about your inquiry and its services. Unsubscribe at any time with the link in any email or by emailing <a href="mailto:<?php echo h($companyEmail); ?>"><?php echo h($companyEmail); ?></a>.</li>
        <li><strong>Text messages (optional):</strong> if you check this box, <?php echo h($companyName); ?> may text you at the number you provided, such as updates about your request and scheduling. Message frequency varies. Message and data rates may apply. Reply STOP to unsubscribe, HELP for help. Consent is not a condition of purchase.</li>
        <li><strong>Terms and Privacy Policy (required to submit):</strong> by checking this box you agree to our Terms of Service and this Privacy Policy and consent to be contacted about your request, including by phone.</li>
      </ul>
      <p>You may opt out of texts, calls or emails at any time by any reasonable method: reply STOP to a text, tell us on a call, or email <a href="mailto:<?php echo h($companyEmail); ?>"><?php echo h($companyEmail); ?></a>. We will confirm that your opt-out was honored.</p>

      <h2>7. Data Retention</h2>
      <p>We keep form submissions, consent records and job records for as long as needed to provide services, answer follow-up questions, and meet legal, tax and licensing obligations. After that we delete them or remove identifying details.</p>

      <h2>8. Data Security</h2>
      <p>We use reasonable administrative, technical and physical safeguards, including encrypted (HTTPS) form submissions and secure hosting. No system is 100% secure, and we cannot guarantee absolute security.</p>

      <h2>9. Children's Privacy</h2>
      <p>This site is not directed to children under 13, and we do not knowingly collect their information. If you believe a child has sent us information, contact us and we will delete it.</p>

      <h2>10. Third-Party Links</h2>
      <p>This site links to third-party sites such as Google Maps and our Google Business Profile. We are not responsible for their privacy practices; review their policies separately.</p>

      <h2>11. Changes to This Policy</h2>
      <p>We may update this Privacy Policy from time to time. The "Last Updated" date at the top and bottom shows the most recent change.</p>

      <h2>12. Contact Us</h2>
      <p>For privacy questions or to exercise your rights:</p>
      <div class="lg-contact">
        <p><strong><?php echo h($companyName); ?></strong><br>
        Email: <a href="mailto:<?php echo h($companyEmail); ?>"><?php echo h($companyEmail); ?></a><br>
        Phone: <a href="tel:<?php echo h($companyPhoneE164); ?>"><?php echo h($companyPhone); ?></a><br>
        Hours: <?php echo h($weekday[0]); ?>, <?php echo h($weekday[1]); ?><br>
        Location: <?php echo h($companyAddress); ?></p>
      </div>
      <p class="lg-updated">Last Updated: <?php echo h($lastUpdated); ?></p>
    </article>
  </div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>
