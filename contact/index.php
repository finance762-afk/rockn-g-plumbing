<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/functions.php';
?>
<?php
/**
 * contact/index.php — Contact Rock'n G Plumbing (Phase 5).
 * Form posts to $formAction (build-plan form_action, verbatim: the Page One leads endpoint). Hidden fields come from
 * p1_lead_hidden_fields() (_next absolute, _honey honeypot, form_location, consent_version/consent_page, attribution,
 * spam shield). The underscore consent aliases the endpoint also reads (_consent_version/_consent_page) and a
 * _honeypot field are added explicitly below. THREE unbundled consent checkboxes; only terms_accepted is required.
 * Service-area business: NO street address is published (SEO brief), so the page shows the service area, not an address.
 */
$pageType        = 'contact';
$pageTitle       = "Contact a San Angelo Plumber | Rock'n G Plumbing";
$pageDescription = "Contact Rock'n G Plumbing, your San Angelo, TX plumber • Free estimates, military discounts, Mon–Fri 8–6 • Call (325) 650-7000 or send the form today.";
$canonicalUrl    = $siteUrl . '/contact/';
$ogImage         = '/assets/images/og-default-v1.jpg';
$currentPage     = 'contact';
$breadcrumbs     = [['name' => 'Contact', 'url' => '/contact/']];

$telHref  = 'tel:' . $phoneE164;
$phoneFmt = formatPhone($phone);
$weekday  = hoursGroups($hours)[0];
$townList = implode(', ', array_slice($areasWeServeTowns, 0, -1)) . ' and ' . end($areasWeServeTowns);

$schemaMarkup = [[
    "@context"   => 'https://schema.org',
    '@type'      => 'ContactPage',
    '@id'        => $canonicalUrl . '#webpage',
    'url'        => $canonicalUrl,
    'name'       => $pageTitle,
    'description'=> $pageDescription,
    'isPartOf'   => ['@id' => $siteUrl . '/#website'],
    'about'      => ['@id' => orgId()],
    'inLanguage' => 'en-US',
]];

ob_start();
?>
<style>
/* =====================================================================
   Contact — page-only CSS (ct- prefix). Tokens from framework.css; no raw
   colors, shadows or spacing.
   ===================================================================== */

/* ---------- Hero: compact interior (C1 layered) ---------- */
.ct-hero {
  position: relative;
  isolation: isolate;
  overflow: clip;
  padding-block-end: var(--space-12);
}
.ct-hero::before {
  content: "";
  position: absolute;
  inset: 0;
  z-index: -1;
  pointer-events: none;
  background:
    radial-gradient(ellipse 50% 90% at 0% 100%, color-mix(in srgb, var(--color-accent) 22%, transparent), transparent 65%),
    linear-gradient(115deg, var(--color-dark) 0%, var(--color-dark-alt) 60%, var(--color-primary-deep) 100%);
}
.ct-hero::after {
  content: "";
  position: absolute;
  inset: 0;
  z-index: -1;
  pointer-events: none;
  opacity: .16;
  mix-blend-mode: overlay;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='160' height='160'%3E%3Cfilter id='g'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.8' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23g)'/%3E%3C/svg%3E");
}
.ct-hero .breadcrumb {
  color: color-mix(in srgb, var(--color-surface) 72%, transparent);
  margin-block-end: var(--space-2);
}
.ct-hero .breadcrumb li {
  display: inline-flex;
  align-items: center;
}
.ct-hero .breadcrumb a:hover {
  color: var(--color-accent-bright);
}
.ct-hero .breadcrumb [aria-current="page"] {
  color: var(--color-surface);
}
.ct-hero .link-call,
.ct-hero .link-call svg {
  color: color-mix(in srgb, var(--color-surface) 92%, transparent);
}
.ct-hero .link-call:hover {
  color: var(--color-surface);
}
.ct-hero h1 {
  max-width: 22ch;
  text-wrap: balance;
}
.ct-hero .hero-answer {
  max-width: 62ch;
  margin-block-start: var(--space-4);
}
.ct-hero .hero-actions {
  margin-block-start: var(--space-6);
}

/* ---------- Form + details: asymmetric two-column (C5) ---------- */
.ct-main {
  background: var(--color-paper);
}
.ct-main .estimate {
  grid-template-columns: minmax(0, 1.15fr) minmax(0, .85fr);
}
.ct-form {
  padding: clamp(1.25rem, 3vw, 2.25rem);
  border: 1px solid var(--color-line);
  border-radius: var(--radius-lg);
  background: var(--color-surface);
  box-shadow: var(--shadow);
}
.ct-form h2 {
  margin-block-end: var(--space-2);
  text-wrap: balance;
}
.ct-form__lead {
  margin: 0 0 var(--space-6);
  color: var(--color-ink-2);
}
.ct-form .form-grid {
  gap: var(--space-4);
}
.ct-form .form-field > label,
.ct-form .field > label {
  font-size: var(--fs-small);
}
.ct-form textarea {
  min-height: 7.5rem;
  resize: vertical;
}
.ct-form .req {
  color: var(--color-danger);
}
.ct-form .form-consent-fieldset {
  margin-block-start: var(--space-5);
  padding: var(--space-5);
  border: 1px solid var(--color-line);
  border-radius: var(--radius);
  background: var(--color-paper-2);
}
.ct-form .form-consent-legend {
  padding-inline: var(--space-2);
}
.ct-form .form-consent-item:last-child {
  margin-block-end: 0;
}
.ct-form .btn-block {
  margin-block-start: var(--space-5);
}
.ct-form .form-footnote {
  text-align: center;
}

/* ---------- Details column ---------- */
.ct-side {
  display: grid;
  gap: var(--space-5);
  align-content: start;
}
.ct-card {
  padding: var(--space-6);
  border: 1px solid var(--color-line);
  border-radius: var(--radius-lg);
  box-shadow: var(--shadow-sm);
}
.ct-card h2,
.ct-card h3 {
  margin-block-end: var(--space-3);
  font-size: var(--fs-h3);
}
.ct-card .nap {
  margin-block-start: 0;
}
.ct-hours {
  display: grid;
  grid-template-columns: auto 1fr;
  gap: var(--space-2) var(--space-5);
  margin: 0;
}
.ct-hours dt {
  font-weight: 700;
}
.ct-hours dd {
  margin: 0;
  color: var(--color-ink-2);
}
.ct-card .next-steps {
  margin-block-start: 0;
}
.ct-map {
  display: grid;
  gap: var(--space-3);
}
.ct-map .map-embed {
  box-shadow: var(--shadow);
  border: 1px solid var(--color-line);
}
.ct-map p {
  margin: 0;
  font-size: var(--fs-small);
  color: var(--color-muted);
}
.ct-map .actions {
  display: flex;
  flex-wrap: wrap;
  gap: var(--space-3);
}

/* ---------- Closing CTA ---------- */
.ct-cta .link-call {
  color: color-mix(in srgb, var(--color-surface) 90%, transparent);
}
.ct-cta .link-call svg {
  color: var(--color-accent-bright);
}
.ct-cta__ring {
  position: absolute;
  inset-block-end: -7rem;
  inset-inline-start: -5rem;
  width: 18rem;
  aspect-ratio: 1;
  border: 2px solid color-mix(in srgb, var(--color-accent) 30%, transparent);
  border-radius: 50%;
  opacity: .6;
  pointer-events: none;
}

@media (max-width: 900px) {
  .ct-main .estimate {
    grid-template-columns: 1fr;
  }
}
</style>
<?php
$extraHead = ob_get_clean();

include $_SERVER['DOCUMENT_ROOT'] . '/includes/head.php';
include $_SERVER['DOCUMENT_ROOT'] . '/includes/header.php';
?>

<!-- ============ HERO ============ -->
<section class="hero hero--interior ct-hero" aria-labelledby="ct-h1">
  <span class="grain" aria-hidden="true"></span>
  <div class="container">
    <nav class="breadcrumb" aria-label="Breadcrumb">
      <ol>
        <li><a href="/">Home</a></li>
        <li class="breadcrumb-sep" aria-hidden="true">/</li>
        <li><span aria-current="page">Contact</span></li>
      </ol>
    </nav>
    <span class="eyebrow">Contact Rock'n G Plumbing</span>
    <h1 id="ct-h1">Contact Your San Angelo Plumber for a Free Estimate</h1>
    <p class="hero-answer">The fastest way to reach Rock'n G Plumbing is to call <a href="<?php echo h($telHref); ?>"><?php echo h($phoneFmt); ?></a>, <?php echo h($weekday[0]); ?> <?php echo h($weekday[1]); ?>. You can also send the form below with a short description of the problem. Estimates are free, and military discounts are available.</p>
    <div class="hero-actions">
      <a class="btn btn-accent btn-lg" href="<?php echo h($telHref); ?>"><?php echo icon('phone', 18); ?>Call <?php echo h($phoneFmt); ?></a>
      <a class="link-call" href="#contact-form">or send the form</a>
    </div>
  </div>
</section>

<article aria-labelledby="ct-h1">

  <!-- ============ FORM + DETAILS ============ -->
  <section class="section ct-main" aria-label="Contact form and business details">
    <div class="container">
      <div class="estimate">

        <div class="ct-form reveal-left" id="contact-form">
          <h2>What plumbing problem can Rock'n G Plumbing help you with?</h2>
          <p class="ct-form__lead">Tell us what is going on and which service fits. Fields marked <span class="req" aria-hidden="true">*</span> are required.</p>

          <form action="<?php echo h($formAction); ?>" method="POST">
            <?php echo p1_lead_hidden_fields('contact'); ?>
            <input type="hidden" name="_consent_version" value="v2.1">
            <input type="hidden" name="_consent_page" value="<?php echo htmlspecialchars($_SERVER['REQUEST_URI'] ?? ''); ?>">
            <input type="text" name="_honeypot" style="display:none" tabindex="-1" autocomplete="off" aria-hidden="true">

            <div class="form-grid">
              <div class="form-field">
                <label for="contact-name">Your name <span class="req" aria-hidden="true">*</span></label>
                <input id="contact-name" type="text" name="name" autocomplete="name" required>
              </div>
              <div class="form-field">
                <label for="contact-phone">Phone <span class="req" aria-hidden="true">*</span></label>
                <input id="contact-phone" type="tel" name="phone" autocomplete="tel" inputmode="tel" required>
              </div>
              <div class="form-field">
                <label for="contact-email">Email <span class="req" aria-hidden="true">*</span></label>
                <input id="contact-email" type="email" name="email" autocomplete="email" required>
              </div>
              <div class="form-field">
                <label for="contact-service">Service needed</label>
                <select id="contact-service" name="service"><?php echo p1_service_options(); ?></select>
              </div>
              <div class="form-field full">
                <label for="contact-message">How can we help?</label>
                <textarea id="contact-message" name="message" rows="5" placeholder="What is the plumbing doing, and where in the house or building?"></textarea>
              </div>
            </div>

            <fieldset class="form-consent-fieldset">
              <legend class="form-consent-legend">Communication Consent</legend>

              <label class="form-consent-item" for="contact-email-opt">
                <input id="contact-email-opt" type="checkbox" name="email_opt_in" value="yes">
                <span class="consent-label"><strong>Email updates (optional):</strong> I agree to receive emails from <?php echo h($siteName); ?> about my inquiry and its services. I can unsubscribe at any time using the link in any email or by emailing <?php echo h($email); ?>. Message frequency varies.</span>
              </label>

              <label class="form-consent-item" for="contact-sms-opt">
                <input id="contact-sms-opt" type="checkbox" name="sms_opt_in" value="yes">
                <span class="consent-label"><strong>Text messages (optional):</strong> I agree to receive text messages from <?php echo h($siteName); ?> at the phone number I provided, such as updates about my request and scheduling. Message frequency varies. Message and data rates may apply. Reply STOP to unsubscribe, HELP for help. <strong>Consent is not a condition of purchase.</strong></span>
              </label>

              <label class="form-consent-item form-consent-required" for="contact-terms">
                <input id="contact-terms" type="checkbox" name="terms_accepted" value="yes" required>
                <span class="consent-label">I have read and agree to the <a href="/terms/" target="_blank" rel="noopener">Terms of Service</a> and <a href="/privacy-policy/" target="_blank" rel="noopener">Privacy Policy</a>, and I consent to be contacted about my request. <span class="req" aria-hidden="true">*</span></span>
              </label>
            </fieldset>

            <button type="submit" class="btn btn-primary btn-lg btn-block">Send My Free Estimate Request</button>
            <p class="form-footnote">Prefer to talk? Call <a href="<?php echo h($telHref); ?>"><?php echo h($phoneFmt); ?></a>, <?php echo h($weekday[0]); ?> <?php echo h($weekday[1]); ?>.</p>
          </form>
        </div>

        <aside class="ct-side" aria-label="Business details">
          <div class="ct-card card-tint-1 reveal-right">
            <h2>Call or email the office</h2>
            <div class="nap">
              <div><?php echo icon('phone', 18); ?><a href="<?php echo h($telHref); ?>"><?php echo h($phoneFmt); ?></a></div>
              <div><?php echo icon('mail', 18); ?><a href="mailto:<?php echo h($email); ?>"><?php echo h($email); ?></a></div>
              <div><?php echo icon('map-pin', 18); ?><span>Serving <?php echo h($address['city']); ?> and Christoval, <?php echo h($address['state']); ?>, plus <?php echo h($townList); ?></span></div>
            </div>
          </div>

          <div class="ct-card card-tint-3 reveal-right reveal-delay-1">
            <h3>Business hours</h3>
            <dl class="ct-hours">
              <?php foreach (hoursGroups($hours) as $ctHours): ?>
              <dt><?php echo h($ctHours[0]); ?></dt><dd><?php echo h($ctHours[1]); ?></dd>
              <?php endforeach; ?>
            </dl>
          </div>

          <div class="ct-card card-tint-2 reveal-right reveal-delay-2">
            <h3>What happens after you reach out</h3>
            <ol class="next-steps">
              <li><strong>The office gets your request.</strong> Calls and form messages are read during business hours.</li>
              <li><strong>You get a free estimate.</strong> Rock'n G Plumbing looks at the problem and tells you what the job involves before any work starts.</li>
              <li><strong>You decide.</strong> There is no obligation. If you go ahead, the work runs under Texas Master Plumber License <?php echo h($license['number']); ?>.</li>
            </ol>
          </div>

          <div class="ct-map reveal-up">
            <div class="map-embed">
              <iframe src="<?php echo h($gbp['embed_src']); ?>" title="Map of the Rock'n G Plumbing service area around San Angelo, Texas" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
            </div>
            <p>Rock'n G Plumbing is a service-area business: the crew comes to you, so there is no storefront to visit.</p>
            <div class="actions">
              <a class="btn btn-secondary btn-sm" href="<?php echo h($gbp['profile_url']); ?>" target="_blank" rel="noopener">View on Google Maps</a>
              <a class="btn btn-secondary btn-sm" href="<?php echo h($gbp['review_url']); ?>" target="_blank" rel="noopener">Leave a Google review</a>
            </div>
          </div>
        </aside>

      </div>
    </div>
  </section>

  <!-- ============ FINAL CTA ============ -->
  <section class="closing-cta texture-grain edge-torn-top ct-cta" aria-labelledby="ct-cta-title">
    <span class="grain-layer" aria-hidden="true"></span>
    <span class="ct-cta__ring" aria-hidden="true"></span>
    <div class="container">
      <div class="reveal-left">
        <span class="eyebrow">No charge to look</span>
        <h2 id="ct-cta-title">Rather talk to a plumber than fill out a form?</h2>
        <p>Call Rock'n G Plumbing at <?php echo h($phoneFmt); ?>, <?php echo h($weekday[0]); ?> <?php echo h($weekday[1]); ?>. Describe what the plumbing is doing and ask for your free estimate.</p>
      </div>
      <div class="actions reveal-right">
        <a class="btn btn-accent btn-lg" href="<?php echo h($telHref); ?>"><?php echo icon('phone', 18); ?>Call <?php echo h($phoneFmt); ?></a>
        <a class="link-call" href="/services/">Browse all <?php echo count($services); ?> services</a>
      </div>
    </div>
  </section>

</article>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>
