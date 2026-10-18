<?php
/**
 * p1-form-helpers.php — shared hidden fields for every Page One lead form (v8, 2026-10-09).
 * Canonical copy: ~/crm/references/components/p1-form-helpers.php (scaffold copies it to /includes/).
 *
 * Emits, in one call, everything the leads endpoint expects besides the visible fields:
 *   _next (thank-you redirect) · _honey honeypot · form_location · consent_version/consent_page
 *   · p1_attribution_fields($formId) (first-touch + submit-page attribution, from attribution.php)
 *   · the spam shield (_ft signed timestamp + _js interaction flag) exactly as
 *     scripts/patch-leads-spamshield.py renders it on hand-written forms.
 *
 * Needs config.php: $siteUrl, $leadsFormSecret (optional — shield skipped when empty).
 */
if (!function_exists('p1_lead_hidden_fields')) {
    function p1_lead_hidden_fields(string $formId, array $extra = []): string {
        global $siteUrl, $leadsFormSecret;
        $h = fn($v) => htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8');
        $out  = '<input type="hidden" name="_next" value="' . $h(rtrim((string) $siteUrl, '/')) . '/thank-you">' . "\n";
        $out .= '<input type="text" name="_honey" style="display:none !important" tabindex="-1" autocomplete="off" aria-hidden="true">' . "\n";
        $out .= '<input type="hidden" name="form_location" value="' . $h($formId) . '">' . "\n";
        $out .= '<input type="hidden" name="consent_version" value="v2.1">' . "\n";
        $out .= '<input type="hidden" name="consent_page" value="' . $h($_SERVER['REQUEST_URI'] ?? '/') . '">' . "\n";
        foreach ($extra as $k => $v) {
            $out .= '<input type="hidden" name="' . $h($k) . '" value="' . $h($v) . '">' . "\n";
        }
        if (function_exists('p1_attribution_fields')) $out .= p1_attribution_fields($formId) . "\n";
        if (!empty($leadsFormSecret)) {
            $ts = (string) time();
            $out .= '<input type="hidden" name="_ft" value="' . $h($ts . '.' . hash_hmac('sha256', $ts, (string) $leadsFormSecret)) . '">' . "\n";
            $out .= '<input type="hidden" name="_js" value="" class="js-shield-field">' . "\n";
            if (empty($GLOBALS['__js_shield'])) {
                $GLOBALS['__js_shield'] = 1;
                $out .= "<script>(function(){var d=document,f=function(){var i,e=d.querySelectorAll('.js-shield-field');for(i=0;i<e.length;i++)e[i].value='1';d.removeEventListener('pointerdown',f);d.removeEventListener('keydown',f);};d.addEventListener('pointerdown',f);d.addEventListener('keydown',f);})();</script>\n";
            }
        }
        return $out;
    }

    /** The TCPA consent row every form carries (terms_accepted is required by the leads endpoint). */
    function p1_consent_row(string $idPrefix): string {
        return '<label class="consent" for="' . htmlspecialchars($idPrefix, ENT_QUOTES) . '-terms">'
             . '<input id="' . htmlspecialchars($idPrefix, ENT_QUOTES) . '-terms" type="checkbox" name="terms_accepted" value="yes" required>'
             . '<span>I agree to the <a href="/terms/" target="_blank" rel="noopener">Terms</a> and <a href="/privacy-policy/" target="_blank" rel="noopener">Privacy Policy</a> and consent to be contacted. *</span></label>';
    }

    /** <option> list from $services (config.php). */
    function p1_service_options(string $selected = ''): string {
        global $services;
        $out = '<option value="">What do you need?</option>';
        foreach ((array) $services as $s) {
            $name = is_array($s) ? ($s['name'] ?? '') : (string) $s;
            if ($name === '') continue;
            $sel = strcasecmp($name, $selected) === 0 ? ' selected' : '';
            $out .= '<option value="' . htmlspecialchars($name, ENT_QUOTES) . '"' . $sel . '>' . htmlspecialchars($name, ENT_QUOTES) . '</option>';
        }
        return $out;
    }
}
