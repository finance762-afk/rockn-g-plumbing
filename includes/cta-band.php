<?php
/**
 * includes/cta-band.php — compact mid-page lead form band (blog posts; reusable on About/FAQ).
 * Set before including (all optional):
 *   $ctaBandId       string  form id for attribution + element ids (default 'cta-band')
 *   $ctaBandTitle    string  heading (default 'Want a price for your house?')
 *   $ctaBandText     string  one-line lead-in
 *   $ctaBandService  string  service name preselected in the dropdown
 * Same endpoint, hidden fields and three unbundled consent boxes as the other forms (p1-form-helpers.php).
 * Variables use the $cb prefix (shared-include variable rule).
 */
$cbId      = !empty($ctaBandId) ? preg_replace('/[^a-z0-9-]/', '', strtolower($ctaBandId)) : 'cta-band';
$cbTitle   = !empty($ctaBandTitle) ? $ctaBandTitle : 'Want a price for your house?';
$cbText    = !empty($ctaBandText) ? $ctaBandText : 'Tell Rock\'n G Plumbing what you need. The estimate is free, and the office replies during business hours, ' . ($businessHours ?? '') . '.';
$cbService = !empty($ctaBandService) ? $ctaBandService : '';
?>
<style>
/* cta-band — compact mid-page lead form (tokens only; emitted once per page that includes this partial) */
.cta-band {
  margin-block: var(--space-10);
  padding: clamp(var(--space-6), 4vw, var(--space-10));
  border-radius: var(--radius-xl);
  color: var(--color-surface);
  background:
    radial-gradient(ellipse 60% 90% at 100% 0%, color-mix(in srgb, var(--color-accent) 24%, transparent), transparent 65%),
    linear-gradient(150deg, var(--color-primary), var(--color-primary-deep));
  box-shadow: var(--shadow-lg);
}
.cta-band__inner {
  display: grid;
  grid-template-columns: minmax(0, 5fr) minmax(0, 7fr);
  gap: clamp(var(--space-6), 4vw, var(--space-12));
  align-items: start;
}
.cta-band h2 {
  color: var(--color-surface);
  font-size: var(--fs-h3);
  margin-block-end: var(--space-2);
}
.cta-band p {
  color: color-mix(in srgb, var(--color-surface) 86%, transparent);
}
.cta-band__call {
  display: flex;
  gap: var(--space-2);
  align-items: center;
  font-weight: 700;
}
.cta-band__call a {
  color: var(--color-surface);
}
.cta-band__form {
  display: grid;
  gap: var(--space-3);
}
.cta-band__row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: var(--space-3);
}
.cta-band input:not([type="checkbox"]),
.cta-band select {
  width: 100%;
  min-height: 46px;
  padding: var(--space-2) var(--space-3);
  font: inherit;
  font-size: 16px;
  color: var(--color-ink);
  background: var(--color-paper);
  border: 1px solid var(--color-line);
  border-radius: var(--radius);
}
.cta-band .consent {
  color: color-mix(in srgb, var(--color-surface) 80%, transparent);
  font-size: .76rem;
}
.cta-band .consent a {
  color: var(--color-accent-bright);
}
@media (max-width: 800px) {
  .cta-band__inner,
  .cta-band__row {
    grid-template-columns: 1fr;
  }
}
</style>
<section class="cta-band" aria-labelledby="<?php echo h($cbId); ?>-title">
  <div class="cta-band__inner">
    <div class="cta-band__copy">
      <h2 id="<?php echo h($cbId); ?>-title"><?php echo h($cbTitle); ?></h2>
      <p><?php echo h($cbText); ?></p>
      <p class="cta-band__call"><?php echo icon('phone', 18); ?><a href="tel:<?php echo h($phoneE164); ?>"><?php echo h(formatPhone($phone)); ?></a></p>
    </div>
    <form class="cta-band__form" action="<?php echo h($formAction); ?>" method="POST">
      <?php echo p1_lead_hidden_fields($cbId); ?>
      <div class="cta-band__row">
        <label class="sr-only" for="<?php echo h($cbId); ?>-name">Name</label>
        <input id="<?php echo h($cbId); ?>-name" type="text" name="name" placeholder="Name" autocomplete="name" required>
        <label class="sr-only" for="<?php echo h($cbId); ?>-phone">Phone</label>
        <input id="<?php echo h($cbId); ?>-phone" type="tel" name="phone" placeholder="Phone" autocomplete="tel" inputmode="tel" required>
      </div>
      <div class="cta-band__row">
        <label class="sr-only" for="<?php echo h($cbId); ?>-email">Email</label>
        <input id="<?php echo h($cbId); ?>-email" type="email" name="email" placeholder="Email" autocomplete="email" required>
        <label class="sr-only" for="<?php echo h($cbId); ?>-service">Service</label>
        <select id="<?php echo h($cbId); ?>-service" name="service"><?php echo p1_service_options($cbService); ?></select>
      </div>
      <label class="consent" for="<?php echo h($cbId); ?>-email-opt"><input id="<?php echo h($cbId); ?>-email-opt" type="checkbox" name="email_opt_in" value="yes"><span><strong>Email (optional):</strong> I agree to receive emails from <?php echo h($siteName); ?>. Unsubscribe any time.</span></label>
      <label class="consent" for="<?php echo h($cbId); ?>-sms-opt"><input id="<?php echo h($cbId); ?>-sms-opt" type="checkbox" name="sms_opt_in" value="yes"><span><strong>Texts (optional):</strong> I agree to receive texts from <?php echo h($siteName); ?> about my request. Consent is not a condition of purchase. Message and data rates may apply. Reply STOP to unsubscribe.</span></label>
      <?php echo p1_consent_row($cbId); ?>
      <button type="submit" class="btn btn-primary btn-block">Get my free estimate</button>
    </form>
  </div>
</section>
