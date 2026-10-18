<?php
/**
 * zip-check.php — "Do you serve my address?" checker (Web Builder v8 Flagship, 2026-10-09).
 * Canonical copy: ~/crm/references/components/zip-check.php
 *
 * Matches a typed ZIP or city against config.php:
 *   $serviceAreas = ['Ringgold', 'Fort Oglethorpe', ...]        (city names — already standard)
 *   $serviceZips  = ['30736', '30742', ...]                      (optional; from build-plan integrations.service_zips)
 * Yes → shows a short lead form with the city prefilled (form_location=zip-check). No → an honest "we may still be
 * able to help" line with the phone number, never a fake yes. No JS → the input is a link to /contact/.
 *
 * Use (service-areas hub on Flagship):
 *   <?php require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/zip-check.php'; echo p1_zip_check(); ?>
 * Options: ['heading' => 'Do we serve your address?', 'id' => 'zip-check']
 */
require_once __DIR__ . '/p1-form-helpers.php';

if (!function_exists('p1_zip_check')) {
    function p1_zip_check(array $opt = []): string {
        global $serviceAreas, $serviceZips, $formAction, $phone, $phoneRaw, $siteName;
        $cities = array_values(array_filter(array_map(fn($c) => is_array($c) ? (string) ($c['name'] ?? '') : (string) $c, (array) ($serviceAreas ?? []))));
        $zips = array_values(array_filter(array_map(fn($z) => preg_replace('/\D/', '', (string) $z), (array) ($serviceZips ?? [])), fn($z) => strlen($z) === 5));
        if (!$cities && !$zips) return '';
        $h  = fn($v) => htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8');
        $id = preg_replace('/[^a-z0-9-]/', '', strtolower($opt['id'] ?? 'zip-check')) ?: 'zip-check';
        $heading = $opt['heading'] ?? 'Do we serve your address?';
        $data = htmlspecialchars(json_encode(['cities' => $cities, 'zips' => $zips]), ENT_QUOTES, 'UTF-8');
        $tel = $phoneRaw ?: preg_replace('/\D/', '', (string) $phone);
        ob_start(); ?>
<section class="p1-zip" id="<?= $h($id) ?>" aria-labelledby="<?= $h($id) ?>-h" data-p1-component="zip-check" data-areas="<?= $data ?>">
<style>
.p1-zip{padding:var(--space-xl,4rem) 0;background:var(--color-paper-2,var(--bg-alt,#f6f6f4))}
.p1-zip__inner{max-width:720px;margin:0 auto;padding:0 var(--space-lg,1.5rem);text-align:center}
.p1-zip__inner h2{margin:0 0 .5rem;font-family:var(--font-heading,inherit)}
.p1-zip__inner>p{color:var(--color-muted,#666);margin:0 0 1rem}
.p1-zip__row{display:flex;gap:.5rem;max-width:460px;margin:0 auto}
.p1-zip__row input{flex:1;min-height:48px;padding:.6rem .9rem;border:1px solid var(--color-line,rgba(0,0,0,.18));border-radius:var(--radius-md,10px);font:inherit;font-size:16px}
.p1-zip__result{margin-top:1rem;padding:1rem;border-radius:var(--radius-md,10px);background:var(--color-surface,#fff);border:1px solid var(--color-line,rgba(0,0,0,.1));text-align:left}
.p1-zip__result[hidden]{display:none}
.p1-zip__result h3{margin:0 0 .5rem;font-size:1.1rem}
.p1-zip .form-grid{display:grid;grid-template-columns:1fr 1fr;gap:.6rem}
.p1-zip .form-row--full{grid-column:1/-1}
.p1-zip label:not(.consent){display:block;font-weight:600;margin-bottom:.25rem;font-size:.9rem}
.p1-zip input:not([type=checkbox]),.p1-zip select{width:100%;min-height:44px;padding:.5rem .8rem;border:1px solid var(--color-line,rgba(0,0,0,.18));border-radius:var(--radius-md,10px);font:inherit;font-size:16px}
.p1-zip .consent{display:flex;gap:.5rem;align-items:flex-start;font-size:.85rem;margin:.6rem 0}
.p1-zip__nojs a{font-weight:600}
@media (max-width:600px){.p1-zip__row{flex-direction:column}.p1-zip .form-grid{grid-template-columns:1fr}}
</style>
  <div class="p1-zip__inner">
    <h2 id="<?= $h($id) ?>-h"><?= $h($heading) ?></h2>
    <p>Enter your ZIP code or town and we will tell you straight away.</p>
    <noscript><p class="p1-zip__nojs"><a href="/contact/">Tell us your address on the contact page</a> and we will confirm.</p></noscript>
    <div class="p1-zip__row">
      <label class="sr-only" for="<?= $h($id) ?>-q">ZIP code or town</label>
      <input id="<?= $h($id) ?>-q" type="text" inputmode="text" autocomplete="postal-code" placeholder="ZIP or town" data-zip-input>
      <button type="button" class="btn btn-primary" data-zip-check>Check</button>
    </div>
    <div class="p1-zip__result" role="status" aria-live="polite" hidden data-zip-yes>
      <h3>Yes, we serve <span data-zip-city></span>.</h3>
      <form action="<?= $h($formAction) ?>" method="POST">
        <?= p1_lead_hidden_fields('zip-check') ?>
        <input type="hidden" name="city" value="" data-zip-city-field>
        <div class="form-grid">
          <div class="form-row"><label for="<?= $h($id) ?>-name">Name</label><input id="<?= $h($id) ?>-name" type="text" name="name" autocomplete="name" required></div>
          <div class="form-row"><label for="<?= $h($id) ?>-phone">Phone</label><input id="<?= $h($id) ?>-phone" type="tel" name="phone" autocomplete="tel" required></div>
          <div class="form-row"><label for="<?= $h($id) ?>-email">Email</label><input id="<?= $h($id) ?>-email" type="email" name="email" autocomplete="email" required></div>
          <div class="form-row"><label for="<?= $h($id) ?>-service">Service</label><select id="<?= $h($id) ?>-service" name="service"><?= p1_service_options() ?></select></div>
        </div>
        <?= p1_consent_row($id) ?>
        <button type="submit" class="btn btn-primary btn-block">Get my free estimate</button>
      </form>
    </div>
    <div class="p1-zip__result" role="status" aria-live="polite" hidden data-zip-no>
      <h3>That is outside our usual area, but we may still be able to help.</h3>
      <p>Call <a href="tel:<?= $h($tel) ?>"><?= $h($phone) ?></a> and <?= $h($siteName) ?> will tell you honestly whether the trip makes sense.</p>
    </div>
  </div>
<script defer>
(function(){
  var root=document.getElementById(<?= json_encode($id) ?>); if(!root) return;
  var areas; try{areas=JSON.parse(root.getAttribute('data-areas')||'{}');}catch(e){areas={};}
  var cities=(areas.cities||[]).map(function(c){return {raw:c,key:String(c).toLowerCase().replace(/[^a-z0-9]/g,'')};}), zips=areas.zips||[];
  var input=root.querySelector('[data-zip-input]'), btn=root.querySelector('[data-zip-check]'), yes=root.querySelector('[data-zip-yes]'), no=root.querySelector('[data-zip-no]');
  function check(){var q=String(input.value||'').trim(); if(!q) {input.focus();return;}
    var hit=null, digits=q.replace(/\D/g,'');
    if(digits.length===5 && zips.indexOf(digits)>=0) hit=digits;
    if(!hit){var k=q.toLowerCase().replace(/[^a-z0-9]/g,''); for(var i=0;i<cities.length;i++){ if(cities[i].key===k || (k.length>=4 && cities[i].key.indexOf(k)===0)) {hit=cities[i].raw;break;} }}
    if(hit){ root.querySelector('[data-zip-city]').textContent=hit; root.querySelector('[data-zip-city-field]').value=hit; no.hidden=true; yes.hidden=false; var n=yes.querySelector('[name=name]'); n&&n.focus(); }
    else { yes.hidden=true; no.hidden=false; } }
  btn.addEventListener('click',check); input.addEventListener('keydown',function(e){ if(e.key==='Enter'){e.preventDefault();check();} });
})();
</script>
</section>
<?php   return ob_get_clean();
    }
}
