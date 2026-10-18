<?php
/**
 * includes/footer.php — closes <main>, estimate dialog, site footer (NAP, hours, license, legal row, credit),
 * cookie bar, mobile sticky CTA bar, back-to-top, deferred scripts. Closes </body></html>.
 * Loop variables use the $foot prefix (shared-include variable rule).
 *
 * Service-area business (address_public false): the footer prints city/state only — never a street address —
 * and the PUBLIC phone ($phone, matched to the GBP listing).
 */
$footAreas   = areaNavLinks();
$footTel     = 'tel:' . $phoneE164;
$footPhone   = formatPhone($phone);
$footLogoSrc = '/assets/images/logo-mark-light-v1.png';
[$footLogoW, $footLogoH] = imgDims($footLogoSrc, 150);
$footCompany = [];                                              // [label, url] — core pages always, optional pages only once built
$footCompany[] = ['About', '/about/'];
foreach ([['Reviews', '/reviews/'], ['Projects', '/projects/'], ['FAQs', '/faqs/'], ['Blog', '/blog/']] as $footOpt) {
    if (pageExists($footOpt[1])) $footCompany[] = $footOpt;
}
$footCompany[] = ['Contact', '/contact/'];
$footTexts = !empty($textUsNumber) ? 'sms:' . preg_replace('/[^0-9+]/', '', $textUsNumber) : '';
?>
</main>

<!-- Free-estimate dialog: opened by any [data-open-estimate] (header CTA, mobile bar, hero buttons); the href is the no-JS fallback -->
<dialog id="estimate-dialog" class="estimate-dialog" aria-labelledby="estimate-dialog-title">
  <div class="dialog-head">
    <h2 id="estimate-dialog-title">Get Your Free Estimate</h2>
    <button type="button" class="dialog-close" data-close-estimate aria-label="Close"><?php echo icon('x', 20); ?></button>
  </div>
  <div class="dialog-body">
    <form action="<?php echo h($formAction); ?>" method="POST">
      <?php echo p1_lead_hidden_fields('estimate-dialog'); ?>
      <div class="form-row"><label class="field"><span>Your name</span><input type="text" name="name" autocomplete="name" required></label></div>
      <div class="form-row"><label class="field"><span>Phone</span><input type="tel" name="phone" autocomplete="tel" inputmode="tel" required></label></div>
      <div class="form-row"><label class="field"><span>Email</span><input type="email" name="email" autocomplete="email" required></label></div>
      <div class="form-row"><label class="field"><span>What do you need?</span><select name="service"><?php echo p1_service_options(); ?></select></label></div>
      <label class="consent" for="estimate-dialog-email-opt"><input id="estimate-dialog-email-opt" type="checkbox" name="email_opt_in" value="yes"><span><strong>Email updates (optional):</strong> I agree to receive emails from <?php echo h($siteName); ?>. I can unsubscribe at any time.</span></label>
      <label class="consent" for="estimate-dialog-sms-opt"><input id="estimate-dialog-sms-opt" type="checkbox" name="sms_opt_in" value="yes"><span><strong>Text messages (optional):</strong> I agree to receive text messages from <?php echo h($siteName); ?> about my request. Consent is not a condition of purchase. Message and data rates may apply. Reply STOP to unsubscribe.</span></label>
      <?php echo p1_consent_row('estimate-dialog'); ?>

      <button type="submit" class="btn btn-primary btn-lg btn-block">Request My Free Estimate</button>
      <p class="form-footnote">Or call <a href="<?php echo h($footTel); ?>"><?php echo h($footPhone); ?></a> — <?php echo h($businessHours); ?></p>
    </form>
  </div>
</dialog>

<footer class="site-footer">
  <div class="grain-layer" aria-hidden="true"></div>
  <div class="footer-top">
    <div class="container">
      <div class="footer-grid">

        <div class="footer-brand">
          <a class="footer-logo" href="/" aria-label="<?php echo h($siteName); ?> — home"><?php echo logoPictureTag($footLogoSrc, $siteName . ' logo', $footLogoW, $footLogoH, '150px', ['loading' => 'lazy', 'decoding' => 'async']); ?></a>
          <p>Three generations of plumbers serving residential, commercial and industrial customers in <?php echo h($address['city']); ?> and Christoval, <?php echo h($address['state']); ?>.</p>
          <?php if (!empty($license['label'])): ?>
          <p class="footer-license"><?php echo icon('badge-check', 18); ?><span><?php echo h($license['label']); ?></span></p>
          <?php endif; ?>
          <div class="footer-badges">
            <span><?php echo h($yearsInBusiness); ?>+ years</span>
            <span>Free estimates</span>
            <span>Military discounts</span>
          </div>
        </div>

        <div class="footer-col">
          <h4>Plumbing Services</h4>
          <ul>
            <?php foreach ($services as $footSvc): ?>
            <li><a href="<?php echo h(serviceUrl($footSvc)); ?>"><?php echo h($footSvc['name']); ?></a></li>
            <?php endforeach; ?>
          </ul>
        </div>

        <div class="footer-col">
          <h4>Service Areas</h4>
          <ul>
            <?php foreach ($footAreas['items'] as $footArea): ?>
            <li><a href="<?php echo h($footArea['url']); ?>"><?php echo h($footArea['name']); ?></a></li>
            <?php endforeach; ?>
          </ul>
          <?php if (pageExists('/areas-we-serve/')): ?>
          <p class="footer-more"><a href="/areas-we-serve/">View All Areas <?php echo icon('arrow-right', 16); ?></a></p>
          <?php endif; ?>
          <h4 style="margin-top:1.4rem">Company</h4>
          <ul>
            <?php foreach ($footCompany as $footLink): ?>
            <li><a href="<?php echo h($footLink[1]); ?>"><?php echo h($footLink[0]); ?></a></li>
            <?php endforeach; ?>
          </ul>
        </div>

        <div class="footer-col">
          <h4>Contact</h4>
          <div class="footer-nap">
            <div><?php echo icon('phone', 18); ?><a href="<?php echo h($footTel); ?>"><?php echo h($footPhone); ?></a></div>
            <div><?php echo icon('mail', 18); ?><a href="mailto:<?php echo h($email); ?>"><?php echo h($email); ?></a></div>
            <div><?php echo icon('map-pin', 18); ?><span><?php echo empty($addressPublic) ? 'Serving ' . h($address['city']) . ' and Christoval, ' . h($address['state']) : h($address['city'] . ', ' . $address['state']); ?></span></div>
          </div>
          <h4 class="footer-hours-title" style="margin-top:1.4rem">Hours</h4>
          <dl class="footer-hours">
            <?php foreach (hoursGroups($hours) as $footHours): ?>
            <dt><?php echo h($footHours[0]); ?></dt><dd><?php echo h($footHours[1]); ?></dd>
            <?php endforeach; ?>
          </dl>
          <a class="btn btn-accent footer-cta-btn" href="/contact/" data-open-estimate>Get a Free Estimate</a>
        </div>

      </div>

      <!-- AEO entity block: same @id as the head JSON-LD so crawlers merge the two descriptions of one business -->
      <div class="footer-entity" itemscope itemtype="https://schema.org/Plumber" itemid="<?php echo h(orgId()); ?>">
        <meta itemprop="name" content="<?php echo h($siteName); ?>">
        <meta itemprop="url" content="<?php echo h(rtrim($siteUrl, '/') . '/'); ?>">
        <meta itemprop="telephone" content="<?php echo h($phoneE164); ?>">
        <div itemprop="address" itemscope itemtype="https://schema.org/PostalAddress">
          <meta itemprop="addressLocality" content="<?php echo h($address['city']); ?>">
          <meta itemprop="addressRegion" content="<?php echo h($address['state']); ?>">
          <meta itemprop="addressCountry" content="US">
        </div>
        <p itemprop="description"><?php echo h($businessDescription); ?></p>
      </div>

      <div class="footer-legal-row">
        <a href="/privacy-policy/">Privacy Policy</a>
        <span class="divider" aria-hidden="true">|</span>
        <a href="/terms/">Terms of Service</a>
        <span class="divider" aria-hidden="true">|</span>
        <a href="/cookie-policy/">Cookie Policy</a>
        <span class="divider" aria-hidden="true">|</span>
        <a href="/accessibility/">Accessibility</a>
        <span class="divider" aria-hidden="true">|</span>
        <a href="/privacy-policy/#ccpa-rights">Do Not Sell or Share My Personal Information</a>
        <span class="divider" aria-hidden="true">|</span>
        <a href="/sitemap.xml">Sitemap</a>
      </div>
    </div>
  </div>

  <div class="footer-bottom-bar">
    <div class="container">
      <div class="footer-bottom-inner">
        <span>&copy; <?php echo date('Y'); ?> <?php echo h($siteName); ?>. All rights reserved.</span>
        <span class="footer-credit"><a href="https://pageoneinsights.com" rel="dofollow" target="_blank">Web Design & Hosting by Page One Insights, LLC</a></span>
      </div>
    </div>
  </div>
  <?php include __DIR__ . '/partner-badge.php'; ?>
</footer>

<!-- Cookie bar: slim, shown after first scroll, dismissal remembered in localStorage (main.js) -->
<div class="cookie-bar" id="cookie-bar" role="region" aria-label="Cookie notice">
  <p>We use cookies for site analytics. <a href="/cookie-policy/">Cookie Policy</a></p>
  <button type="button">Got it</button>
</div>

<!-- Mobile sticky CTA bar (below 760px; main.js reveals it once the hero scrolls away) -->
<div class="mobile-cta-bar<?php echo $footTexts !== '' ? ' mobile-cta-bar--3' : ''; ?>" role="region" aria-label="Quick contact">
  <a class="mobile-cta-bar__call" href="<?php echo h($footTel); ?>"><?php echo icon('phone', 18); ?>Call Now</a>
  <a class="mobile-cta-bar__estimate" href="/contact/" data-open-estimate>Free Estimate</a>
  <?php if ($footTexts !== ''): ?>
  <a class="mobile-cta-bar__text" href="<?php echo h($footTexts); ?>">Text Us</a>
  <?php endif; ?>
</div>

<button type="button" class="back-to-top" aria-label="Back to top"><?php echo icon('chevron-down', 20); ?></button>

<script src="/assets/js/main.js?v=<?php echo $cssVersion; ?>" defer></script>
<script src="/assets/js/animations.js?v=<?php echo $cssVersion; ?>" defer></script>
<script src="/assets/js/effects.js?v=<?php echo $cssVersion; ?>" defer></script>
</body>
</html>
