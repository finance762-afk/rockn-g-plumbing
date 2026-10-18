<?php
/**
 * estimate-tool.php — instant estimate range tool (Web Builder v8 Flagship, 2026-10-09).
 * Canonical copy: ~/crm/references/components/estimate-tool.php
 *
 * AUTHORIZATION-GATED. Renders ONLY when config.php sets
 *   $estimateTool = ['authorized' => true, 'pricing' => [
 *       ['service' => 'Stump grinding', 'slug' => 'stump-grinding', 'low' => 100, 'high' => 400,
 *        'unit' => 'per stump', 'note' => 'depends on diameter and access'], ...
 *   ]];
 * mirrored from build-plan.json integrations.estimate_tool (authorized_by / authorized_at are recorded
 * there by the onboarding authorization step). The numbers are the CLIENT'S OWN ranges — the tool never
 * computes a price from anything the client did not give us. qa_audit "FLAGSHIP COMPONENTS" blocks a build
 * that references this include without the authorization.
 *
 * Use:  <?php require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/estimate-tool.php'; echo p1_estimate_tool(); ?>
 * Options: ['heading' => '...', 'service' => 'Stump grinding' (preselect), 'id' => 'estimate-tool']
 *
 * Steps: 1 service → 2 quantity/size → 3 contact. Range shown live; the form posts to the leads endpoint
 * (form_location=estimate, estimate_service, estimate_qty, estimate_low, estimate_high) so the alert carries
 * the range the visitor saw. No JS → the plain form still submits (quantity + service as fields).
 */
require_once __DIR__ . '/p1-form-helpers.php';

if (!function_exists('p1_estimate_tool')) {
    function p1_estimate_tool(array $opt = []): string {
        global $estimateTool, $formAction, $siteName, $phone, $phoneRaw;
        if (empty($estimateTool) || empty($estimateTool['authorized']) || empty($estimateTool['pricing']) || !is_array($estimateTool['pricing'])) return '';
        $rows = array_values(array_filter($estimateTool['pricing'], fn($r) => is_array($r) && !empty($r['service']) && isset($r['low'], $r['high'])));
        if (!$rows) return '';
        $h  = fn($v) => htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8');
        $id = preg_replace('/[^a-z0-9-]/', '', strtolower($opt['id'] ?? 'estimate-tool')) ?: 'estimate-tool';
        $heading = $opt['heading'] ?? 'Get an instant estimate range';
        $pre = strtolower((string) ($opt['service'] ?? ''));
        $json = htmlspecialchars(json_encode(array_map(fn($r) => [
            'service' => (string) $r['service'], 'low' => (float) $r['low'], 'high' => (float) $r['high'],
            'unit' => (string) ($r['unit'] ?? ''), 'note' => (string) ($r['note'] ?? ''), 'perUnit' => !empty($r['unit']) && stripos((string) $r['unit'], 'per ') === 0,
        ], $rows)), ENT_QUOTES, 'UTF-8');
        $opts = '';
        foreach ($rows as $r) {
            $sel = $pre !== '' && strtolower((string) $r['service']) === $pre ? ' selected' : '';
            $opts .= '<option value="' . $h($r['service']) . '"' . $sel . '>' . $h($r['service']) . '</option>';
        }
        ob_start(); ?>
<section class="p1-estimate" id="<?= $h($id) ?>" aria-labelledby="<?= $h($id) ?>-h" data-p1-component="estimate-tool">
<style>
.p1-estimate{padding:var(--space-xl,4rem) 0;background:var(--color-paper-2,var(--bg-alt,#f6f6f4))}
.p1-estimate__inner{max-width:var(--container-max,1180px);margin:0 auto;padding:0 var(--space-lg,1.5rem);display:grid;gap:var(--space-lg,1.5rem);grid-template-columns:minmax(0,1fr) minmax(0,1.2fr);align-items:start}
.p1-estimate__copy h2{margin:0 0 .5rem;font-family:var(--font-heading,inherit)}
.p1-estimate__copy p{color:var(--color-muted,var(--text-light,#555));margin:0 0 1rem}
.p1-estimate__card{background:var(--color-surface,#fff);border:1px solid var(--color-line,rgba(0,0,0,.1));border-radius:var(--radius-lg,16px);padding:var(--space-lg,1.5rem);box-shadow:var(--shadow-md,0 8px 24px rgba(0,0,0,.08))}
.p1-estimate__steps{display:flex;gap:.5rem;margin:0 0 1rem;padding:0;list-style:none;font-size:.85rem;color:var(--color-muted,#666)}
.p1-estimate__steps li{flex:1;padding:.4rem .6rem;border-radius:999px;background:var(--color-paper-2,#f1f1ee);text-align:center}
.p1-estimate__steps li[aria-current="step"]{background:var(--color-primary,#1a4ed8);color:var(--color-white,#fff)}
.p1-estimate .form-row{margin-bottom:.75rem}
.p1-estimate label:not(.consent){display:block;font-weight:600;margin-bottom:.3rem}
.p1-estimate input:not([type=checkbox]),.p1-estimate select,.p1-estimate textarea{width:100%;min-height:46px;padding:.6rem .8rem;border:1px solid var(--color-line,rgba(0,0,0,.18));border-radius:var(--radius-md,10px);font:inherit;font-size:16px;background:var(--color-surface,#fff)}
.p1-estimate__range{margin:1rem 0;padding:1rem;border-radius:var(--radius-md,10px);background:var(--color-paper-2,#f6f6f4);border-left:4px solid var(--color-accent,var(--color-primary,#1a4ed8))}
.p1-estimate__range strong{font-size:1.5rem;font-family:var(--font-accent,var(--font-heading,inherit))}
.p1-estimate__range small{display:block;color:var(--color-muted,#666);margin-top:.25rem}
.p1-estimate__nav{display:flex;gap:.5rem;justify-content:space-between;margin-top:.75rem}
.p1-estimate .consent{display:flex;gap:.5rem;align-items:flex-start;font-size:.85rem;margin:.75rem 0}
.p1-estimate .consent input{margin-top:.2rem}
.p1-estimate [hidden]{display:none !important}
.p1-estimate__phone{margin-top:1rem;font-size:.95rem}
@media (max-width:900px){.p1-estimate__inner{grid-template-columns:1fr}}
</style>
  <div class="p1-estimate__inner">
    <div class="p1-estimate__copy">
      <h2 id="<?= $h($id) ?>-h"><?= $h($heading) ?></h2>
      <p>Pick the service and the size of the job to see <?= $h($siteName) ?>'s typical price range. Your free on-site estimate gives a firm written price.</p>
      <p class="p1-estimate__phone">Prefer to talk? <a href="tel:<?= $h($phoneRaw ?: preg_replace('/\D/', '', (string) $phone)) ?>"><?= $h($phone) ?></a></p>
    </div>
    <div class="p1-estimate__card">
      <ol class="p1-estimate__steps" aria-label="Estimate steps">
        <li aria-current="step" data-step-label="1">1. Service</li><li data-step-label="2">2. Size</li><li data-step-label="3">3. Contact</li>
      </ol>
      <form action="<?= $h($formAction) ?>" method="POST" class="p1-estimate__form" data-pricing="<?= $json ?>" novalidate>
        <?= p1_lead_hidden_fields('estimate', ['estimate' => '1', 'estimate_low' => '', 'estimate_high' => '', 'estimate_inputs' => '']) ?>
        <fieldset class="p1-estimate__step" data-step="1">
          <legend class="sr-only">Service</legend>
          <div class="form-row"><label for="<?= $h($id) ?>-service">Which service?</label>
            <select id="<?= $h($id) ?>-service" name="estimate_service" required><option value="">Choose a service</option><?= $opts ?></select></div>
          <input type="hidden" name="service" value="" data-mirror="estimate_service">
        </fieldset>
        <fieldset class="p1-estimate__step" data-step="2">
          <legend class="sr-only">Size of the job</legend>
          <div class="form-row"><label for="<?= $h($id) ?>-qty" data-qty-label>How many?</label>
            <input id="<?= $h($id) ?>-qty" type="number" name="estimate_qty" min="1" max="99" step="1" value="1" inputmode="numeric"></div>
          <div class="p1-estimate__range" role="status" aria-live="polite" hidden>
            <span>Typical range</span><br><strong data-range>—</strong>
            <small data-note>Your free on-site estimate gives a firm price.</small>
          </div>
        </fieldset>
        <fieldset class="p1-estimate__step" data-step="3">
          <legend class="sr-only">Your contact details</legend>
          <div class="form-row"><label for="<?= $h($id) ?>-name">Name</label><input id="<?= $h($id) ?>-name" type="text" name="name" autocomplete="name" required></div>
          <div class="form-row"><label for="<?= $h($id) ?>-phone">Phone</label><input id="<?= $h($id) ?>-phone" type="tel" name="phone" autocomplete="tel" required></div>
          <div class="form-row"><label for="<?= $h($id) ?>-email">Email</label><input id="<?= $h($id) ?>-email" type="email" name="email" autocomplete="email" required></div>
          <div class="form-row"><label for="<?= $h($id) ?>-addr">Job address or city</label><input id="<?= $h($id) ?>-addr" type="text" name="job_address" autocomplete="street-address"></div>
          <?= p1_consent_row($id) ?>
        </fieldset>
        <div class="p1-estimate__nav">
          <button type="button" class="btn btn-outline" data-prev hidden>Back</button>
          <button type="button" class="btn btn-primary" data-next>Continue</button>
          <button type="submit" class="btn btn-primary" data-submit>Send me the estimate</button>
        </div>
      </form>
    </div>
  </div>
<script defer>
(function(){
  var root=document.getElementById(<?= json_encode($id) ?>); if(!root) return;
  var form=root.querySelector('form'), steps=[].slice.call(root.querySelectorAll('.p1-estimate__step')), pills=[].slice.call(root.querySelectorAll('.p1-estimate__steps li'));
  var pricing; try{pricing=JSON.parse(form.getAttribute('data-pricing')||'[]');}catch(e){pricing=[];}
  var sel=form.querySelector('[name=estimate_service]'), qty=form.querySelector('[name=estimate_qty]'), mirror=form.querySelector('[data-mirror]');
  var rangeBox=form.querySelector('.p1-estimate__range'), rangeEl=form.querySelector('[data-range]'), noteEl=form.querySelector('[data-note]'), qtyLabel=form.querySelector('[data-qty-label]');
  var lowF=form.querySelector('[name=estimate_low]'), highF=form.querySelector('[name=estimate_high]'), inputsF=form.querySelector('[name=estimate_inputs]');
  var prev=form.querySelector('[data-prev]'), next=form.querySelector('[data-next]'), submit=form.querySelector('[data-submit]');
  var cur=0;
  function money(n){return '$'+Math.round(n).toLocaleString('en-US');}
  function row(){for(var i=0;i<pricing.length;i++) if(pricing[i].service===sel.value) return pricing[i]; return null;}
  function calc(){var r=row(); if(!r){rangeBox.hidden=true;return;} var q=Math.max(1,parseInt(qty.value||'1',10)); var mult=r.perUnit?q:1;
    var lo=r.low*mult, hi=r.high*mult; rangeEl.textContent=money(lo)+' – '+money(hi)+(r.unit&&!r.perUnit?' '+r.unit:'');
    noteEl.textContent=(r.note?r.note+'. ':'')+'Your free on-site estimate gives a firm price.'; lowF.value=lo; highF.value=hi; if(inputsF){try{inputsF.value=JSON.stringify({service:sel.value,qty:Number(qty&&qty.value||1)});}catch(e){}} rangeBox.hidden=false;
    qtyLabel.textContent=r.perUnit?('How many ('+r.unit.replace(/^per\s+/i,'')+')?'):'Roughly how big is the job? (1 = small, 5 = large)';}
  function show(i){cur=i; steps.forEach(function(s,k){s.hidden=k!==i;}); pills.forEach(function(p,k){ if(k===i) p.setAttribute('aria-current','step'); else p.removeAttribute('aria-current');});
    prev.hidden=i===0; next.hidden=i===steps.length-1; submit.hidden=i!==steps.length-1; if(i===1) calc();}
  sel.addEventListener('change',function(){mirror.value=sel.value; calc();}); qty.addEventListener('input',calc);
  next.addEventListener('click',function(){ if(cur===0&&!sel.value){sel.focus();sel.reportValidity&&sel.reportValidity();return;} show(cur+1);});
  prev.addEventListener('click',function(){show(cur-1);});
  form.addEventListener('submit',function(e){ var req=[].slice.call(form.querySelectorAll('[required]')); for(var i=0;i<req.length;i++){ if(!req[i].checkValidity()){e.preventDefault(); show(2); req[i].reportValidity&&req[i].reportValidity(); return;} } });
  show(0); if(sel.value){mirror.value=sel.value;}
})();
</script>
</section>
<?php   return ob_get_clean();
    }
}
