# Page One Insights — Legal Compliance Reference

> **RULE (Sep 29 2026):** never print a "provided as a general template / review with a licensed attorney before publication" disclaimer on a published page — it tells visitors the page is an unfinished draft (removed from 46 live client sites Sep 29). Only include an "Insurance Claim Work" clause for trades that actually do insurance work (roofing, restoration, tree/storm, auto glass, contracting).

# legal-compliance.md v6.1

> This is the canonical reference for all legal/compliance page generation, TCPA consent patterns, cookie banner implementation, and footer legal row markup. Loaded by the `pageone-web-builder` skill during Phase 1 (footer template setup) and Phase 3D (compliance pages).

---

## OVERVIEW

Every Page One site collects PII via Formsubmit.co contact forms. The following compliance assets are required per the tier system defined in CLAUDE.md v6.1:

| Tier | Required Compliance Pages | Cookie Banner | TCPA Consent |
|------|--------------------------|---------------|--------------|
| BASIC | Privacy Policy | Yes (light) | Yes |
| STANDARD | Privacy Policy + Terms of Service | Yes (light) | Yes |
| PREMIUM | Privacy Policy + Terms of Service + Cookie Policy + Accessibility Statement | Yes (light) | Yes |

**TCPA scope (v6.1 standard):** Single checkbox bundling phone + SMS consent.
**Cookie banner (v6.1 standard):** Light dismissible banner with single "Got it" button, localStorage-suppressed on subsequent visits.
**State default:** Templates default to the client's primary state. Privacy Policy includes a CCPA section for California residents and a generic multi-state rights section. Adjust per client risk profile.

---

## PHASE 3D PROMPT TEMPLATE

Use this as the build prompt for Phase 3D on every Premium build:

```
Build the 4 compliance pages for [Company Name]. All other pages complete.

IMPORTANT: Use --dangerously-skip-permissions or auto-approve all file operations.

CRITICAL FILE STRUCTURE — all subdirectory/index.php:
- privacy-policy/index.php
- terms/index.php
- cookie-policy/index.php
- accessibility/index.php

BRAND CONTEXT:
- Company: [name + entity type, e.g., "Tim Cole Contracting LLC"]
- Entity type: [LLC / Corporation / Sole Proprietorship]
- State: [primary operating state]
- Address: [full address]
- Phone: [phone] (tel link: +[E.164])
- Email: [email]
- Domain: https://[domain]
- Brand colors: [primary/secondary]
- Fonts: [heading + body + accent]

LEGAL TONE NOTES:
- Plainspoken, not jargon-heavy
- General contractor templates — recommend attorney review
- Effective date: <?php echo date('F j, Y'); ?>
- These are LEGAL pages — clean and functional. NO premium visual fireworks. NO image hero.

CSS — Add to styles.css (then increment $cssVersion):
[See "Legal Page CSS" section below — paste full block]

UPDATE footer.php — Add footer-legal-links nav element [See "Footer Legal Row" section below]

ADD includes/cookie-banner.php as a new partial, include it in footer.php [See "Cookie Banner" section below]

UPDATE both contact forms (hero + contact page) to include TCPA consent checkbox [See "TCPA Consent" section below]

[Then 4 page-specific templates per below]

POST-BUILD:
- Add 4 URLs to sitemap.xml (priority 0.3, changefreq yearly)
- Add Legal section to llms.txt with all 4 page URLs
- Update form disclaimers to reference Privacy Policy
- Increment $cssVersion site-wide

VERIFICATION:
- find privacy-policy terms cookie-policy accessibility -type f -name "index.php"  → 4 files
- php -l on all 4 files → no syntax errors
- grep -c "footer-legal-links" includes/footer.php → 1
- grep -c "cookie-banner" includes/footer.php → 1
- grep -n "tcpa_consent" index.php contact/index.php → 2 matches
- grep -c "privacy-policy" sitemap.xml → 4 URLs (or more if referenced elsewhere)
- php -S 127.0.0.1:8090 → all 4 pages return HTTP 200 (never port 8000 — Supabase; kill the server after)
```

---

## LEGAL PAGE CSS

Add this complete block to `assets/css/styles.css` during Phase 3D:

```css
/* --------------------------------------------------------------------------
   LEGAL PAGE STYLES (v6.1)
   -------------------------------------------------------------------------- */

.hero--legal {
  background: var(--color-bg-alt);
  min-height: 40vh;
  padding-top: calc(var(--nav-height) + var(--space-2xl));
  padding-bottom: var(--space-2xl);
  display: flex;
  align-items: center;
}
.hero--legal .hero__copy {
  max-width: var(--max-width);
  margin: 0 auto;
  padding: 0 var(--space-xl);
  width: 100%;
}
.hero--legal h1 { color: var(--color-primary); }
.hero--legal .eyebrow-label { color: var(--color-primary); }
.hero--legal .section-subtitle { color: var(--color-primary); }
.hero--legal .hero__phone { opacity: 0.8; }

.legal-prose {
  max-width: 65ch;
  margin: 0 auto;
  padding: var(--space-2xl) var(--space-xl);
}
.legal-prose h2 {
  color: var(--color-primary);
  font-family: var(--font-heading);
  margin-top: var(--space-xl);
  margin-bottom: var(--space-sm);
  font-size: 1.5rem;
  scroll-margin-top: calc(var(--nav-height) + 20px);
}
.legal-prose h3 {
  color: var(--color-primary);
  font-family: var(--font-heading);
  margin-top: var(--space-md);
  margin-bottom: var(--space-xs);
  font-size: 1.15rem;
}
.legal-prose p {
  margin-bottom: var(--space-sm);
  line-height: 1.7;
}
.legal-prose ul,
.legal-prose ol {
  margin-left: var(--space-md);
  margin-bottom: var(--space-md);
}
.legal-prose li {
  margin-bottom: 6px;
  line-height: 1.6;
}
.legal-prose a {
  color: var(--color-primary);
  border-bottom: 1px solid rgba(var(--color-primary-rgb), 0.2);
  transition: border-color var(--transition);
}
.legal-prose a:hover {
  color: var(--color-primary-dark);
  border-color: var(--color-primary-dark);
}
.legal-disclaimer {
  background: var(--color-card-tint-3, rgba(0,0,0,0.03));
  border-left: 4px solid var(--color-primary);
  padding: var(--space-md);
  margin: var(--space-xl) 0;
  font-size: 0.92rem;
  font-style: italic;
  border-radius: var(--radius);
}

/* Breadcrumb (used site-wide, defined here once) */
.breadcrumb {
  background: #fff;
  border-bottom: 1px solid var(--color-border);
  padding: var(--space-sm) 0;
  font-size: 0.88rem;
}
.breadcrumb .container { display: flex; }
.breadcrumb ol {
  display: flex;
  flex-wrap: wrap;
  gap: 6px 8px;
  align-items: center;
  list-style: none;
  margin: 0;
  padding: 0;
}
.breadcrumb li { display: flex; align-items: center; gap: 6px; }
.breadcrumb a {
  color: var(--color-text-light);
  transition: color var(--transition);
}
.breadcrumb a:hover { color: var(--color-primary); }
.breadcrumb li[aria-current="page"] {
  color: var(--color-primary);
  font-weight: 600;
}
.breadcrumb .breadcrumb-sep {
  color: rgba(0,0,0,0.25);
  font-size: 1rem;
}

/* Footer legal utility row */
.footer-legal-links {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  align-items: center;
  gap: var(--space-xs) var(--space-sm);
  font-size: 0.85rem;
  list-style: none;
  padding: var(--space-md) 0 0;
  margin: 0;
}
.footer-legal-links a {
  color: rgba(255, 255, 255, 0.7);
  text-decoration: none;
  transition: color var(--transition);
}
.footer-legal-links a:hover,
.footer-legal-links a:focus-visible {
  color: #fff;
  text-decoration: underline;
}
.footer-legal-divider {
  color: rgba(255, 255, 255, 0.3);
  font-size: 0.75rem;
}
@media (max-width: 767px) {
  .footer-legal-divider { display: none; }
  .footer-legal-links { gap: var(--space-xs) var(--space-md); }
}

/* TCPA consent checkbox styling */
.form-field--checkbox {
  display: flex;
  align-items: flex-start;
  gap: var(--space-sm);
  margin-bottom: var(--space-md);
  padding: var(--space-sm) 0;
}
.form-field--checkbox input[type="checkbox"] {
  width: 18px;
  height: 18px;
  margin-top: 3px;
  flex-shrink: 0;
  accent-color: var(--color-primary);
  cursor: pointer;
}
.form-field--checkbox label {
  font-size: 0.85rem;
  line-height: 1.5;
  color: var(--color-text-light);
  cursor: pointer;
  position: static;
  pointer-events: auto;
  top: auto;
  left: auto;
  background: transparent;
  padding: 0;
}
.form-field--checkbox label a {
  color: var(--color-primary);
  text-decoration: underline;
}
.form-field--checkbox.tcpa-consent {
  background: rgba(0,0,0,0.02);
  padding: var(--space-md);
  border-radius: var(--radius);
  border: 1px solid var(--color-border);
}

/* Cookie banner */
.cookie-banner {
  position: fixed;
  left: var(--space-md);
  right: var(--space-md);
  bottom: 76px; /* sits above sticky mobile CTA bar */
  z-index: 990;
  background: var(--color-bg-dark, #1a1a1a);
  color: #fff;
  border-radius: var(--radius);
  padding: var(--space-md) var(--space-lg);
  box-shadow: 0 8px 32px rgba(0,0,0,0.25);
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  gap: var(--space-md);
  max-width: 800px;
  margin: 0 auto;
  transform: translateY(120%);
  transition: transform 0.4s ease;
}
.cookie-banner.is-visible { transform: translateY(0); }
.cookie-banner__text {
  flex: 1;
  font-size: 0.88rem;
  line-height: 1.5;
  margin: 0;
}
.cookie-banner__text a {
  color: var(--color-secondary, #ffd54f);
  text-decoration: underline;
}
.cookie-banner__dismiss {
  background: var(--color-primary);
  color: #fff;
  border: none;
  padding: 10px 20px;
  border-radius: var(--radius);
  font-family: var(--font-body);
  font-weight: 700;
  font-size: 0.88rem;
  cursor: pointer;
  white-space: nowrap;
  transition: background var(--transition), transform var(--transition);
}
.cookie-banner__dismiss:hover {
  background: var(--color-primary-dark);
  transform: translateY(-1px);
}
@media (max-width: 600px) {
  .cookie-banner {
    flex-direction: column;
    align-items: stretch;
    bottom: 70px;
    padding: var(--space-md);
  }
  .cookie-banner__dismiss { width: 100%; }
}
```

---

## FOOTER LEGAL UTILITY ROW

Add this INSIDE the footer's bottom section in `includes/footer.php`. Pattern follows Dun-Rite reference build.

### BASIC tier markup:
```php
<nav class="footer-legal-links" aria-label="Legal">
  <a href="/privacy-policy/" target="_blank" rel="noopener">Privacy Policy</a>
  <span class="footer-legal-divider">|</span>
  <a href="/sitemap.xml">Sitemap</a>
</nav>
```

### STANDARD tier markup:
```php
<nav class="footer-legal-links" aria-label="Legal">
  <a href="/privacy-policy/" target="_blank" rel="noopener">Privacy Policy</a>
  <span class="footer-legal-divider">|</span>
  <a href="/terms/" target="_blank" rel="noopener">Terms of Service</a>
  <span class="footer-legal-divider">|</span>
  <a href="/sitemap.xml">Sitemap</a>
</nav>
```

### PREMIUM tier markup:
```php
<nav class="footer-legal-links" aria-label="Legal">
  <a href="/privacy-policy/" target="_blank" rel="noopener">Privacy Policy</a>
  <span class="footer-legal-divider">|</span>
  <a href="/terms/" target="_blank" rel="noopener">Terms of Service</a>
  <span class="footer-legal-divider">|</span>
  <a href="/cookie-policy/">Cookie Policy</a>
  <span class="footer-legal-divider">|</span>
  <a href="/accessibility/">Accessibility</a>
  <span class="footer-legal-divider">|</span>
  <a href="/privacy-policy/#ccpa-rights" target="_blank" rel="noopener">Do Not Sell or Share My Personal Information</a>
  <span class="footer-legal-divider">|</span>
  <a href="/sitemap.xml">Sitemap</a>
</nav>
```

Place the nav element above the existing copyright/Page One Insights credit line inside `.footer-bottom`.

---

## COOKIE BANNER

### Markup (add to `includes/footer.php` just before `</body>` close)

```php
<div class="cookie-banner" id="cookie-banner" role="region" aria-label="Cookie notice">
  <p class="cookie-banner__text">
    We use cookies to improve your experience and analyze site usage. By continuing, you agree to our use of cookies. <a href="/cookie-policy/">Learn more</a>.
  </p>
  <button type="button" class="cookie-banner__dismiss" id="cookie-banner-dismiss" aria-label="Dismiss cookie notice">Got it</button>
</div>
```

### JavaScript (add to `assets/js/effects.js`, inside the main IIFE)

```javascript
/* ---------- COOKIE BANNER ---------- */
(function () {
  var banner = document.getElementById('cookie-banner');
  var dismissBtn = document.getElementById('cookie-banner-dismiss');
  if (!banner || !dismissBtn) return;

  var storageKey = 'cookieBannerDismissed_v1';
  var dismissed = false;
  try { dismissed = localStorage.getItem(storageKey) === 'true'; } catch (e) {}

  if (dismissed) return;

  // Show banner after slight delay so it doesn't compete with first paint
  setTimeout(function () { banner.classList.add('is-visible'); }, 800);

  dismissBtn.addEventListener('click', function () {
    banner.classList.remove('is-visible');
    setTimeout(function () { banner.remove(); }, 500);
    try { localStorage.setItem(storageKey, 'true'); } catch (e) {}
  });
})();
```

### Behavior
- Banner appears 800ms after page load (slides up from bottom)
- Sits above the sticky mobile CTA bar (`bottom: 76px` desktop, `70px` mobile)
- "Got it" button slides banner down and removes from DOM
- LocalStorage flag `cookieBannerDismissed_v1` suppresses banner on subsequent visits
- If localStorage unavailable, banner shows on every visit (privacy/incognito mode behavior)
- Banner is dismissible only — no opt-out toggle (v6.1 scope is "light banner")

---

## TCPA CONSENT CHECKBOX

> **Consent links MUST open in a new tab** — every Privacy/Terms `<a>` inside a consent label or submit-disclaimer needs `target="_blank" rel="noopener"`. The links sit inside the checkbox `<label>`: a same-tab link means a customer tapping the consent sentence navigates away and loses the half-filled form (A&S Contracting complaint, 2026-08-31). The snippets below already include it; never strip it.

**The leads edge function HARD-REJECTS (HTTP 400) any submission where
`terms_accepted !== "yes"`.** A form without this checkbox does not degrade
gracefully — it loses every real lead, silently, forever. (This is exactly what
happened to 102 forms across 43 sites before the 2026-07-22 audit; the old
`tcpa_consent` / Formsubmit-era markup documented here previously is DEAD and
must not be used.)

Field contract expected by the endpoint:

| field | required | value |
|---|---|---|
| `terms_accepted` | **YES — 400 without it** | `"yes"` |
| `email_opt_in` | optional | `"yes"` (gates the customer confirmation email) |
| `sms_opt_in` | optional | `"yes"` |
| `_consent_version` | optional (defaults `v2.1`) | `"v2.1"` |
| `_consent_page` | optional | `$_SERVER['REQUEST_URI']` |

Two-tier pattern — full fieldset on dedicated contact pages, compact single
line on inline/hero/lp forms (keeps short-form conversion intact):

**Contact page (full):**
```html
<fieldset class="p1-consent-set">
  <legend class="p1-consent-legend">Communication Consent</legend>
  <label class="p1-consent-item"><input type="checkbox" name="email_opt_in" value="yes">
    <span><strong>Email updates (optional):</strong> …unsubscribe any time.</span></label>
  <label class="p1-consent-item"><input type="checkbox" name="sms_opt_in" value="yes">
    <span><strong>SMS/Text (optional):</strong> …Message and data rates may apply. Reply STOP to
    unsubscribe, HELP for help. <strong>Consent is not a condition of purchase.</strong></span></label>
  <label class="p1-consent-item"><input type="checkbox" name="terms_accepted" value="yes" required>
    <span>I have read and agree to the Terms of Service and Privacy Policy *</span></label>
</fieldset>
<input type="hidden" name="_consent_version" value="v2.1">
<input type="hidden" name="_consent_page" value="<?php echo htmlspecialchars($_SERVER['REQUEST_URI'] ?? ''); ?>">
```

**Inline / hero / lp form (compact):**
```html
<label class="p1-consent-line">
  <input type="checkbox" name="terms_accepted" value="yes" required>
  <span>I agree to the Terms of Service and Privacy Policy and consent to be contacted
  about my request. *</span>
</label>
```

**Key requirements:**
- `required` on `terms_accepted` — affirmative action only. NEVER satisfy the
  endpoint with a hidden/pre-checked `terms_accepted`: that is not valid TCPA
  consent and defeats the purpose of the field.
- Consent must be **separate from** the submit action (its own checkbox), and
  the SMS line must carry: message types, frequency-varies, rates-may-apply,
  STOP/HELP, and the not-a-condition-of-purchase disclosure.
- Link `/privacy-policy/` and `/terms/` **only if those pages exist** on the
  site — an unlinked mention beats a 404. Every site should have both.
- Retrofit across all repos: `python3 ~/crm/scripts/patch-leads-consent.py --apply`
  (self-contained `p1-consent-*` styles are injected once per page, so it renders
  correctly on sites whose CSS predates the consent block).

---

## HERO FORM PARTIAL (v6.1 PATTERN)

Extract the hero contact form to a reusable partial at `includes/hero-form.php`. This pattern allows the same form to appear on the homepage hero, service pages, and city pages without duplication.

### `includes/hero-form.php`

```php
<?php
/**
 * Hero contact form partial — v6.1
 *
 * Variables (set before include):
 *   $formId          → unique id prefix for inputs (e.g., 'hero', 'svc-roofrep')
 *   $formSubject     → optional, override Formsubmit subject line
 *   $formTitle       → optional, heading shown above form (default: "Get a Free Estimate")
 *   $formSubtitle    → optional, accent-font subtitle (default: "No pressure. No upsells.")
 *   $companyEmail    → required, Formsubmit recipient
 *   $companyName     → required, used in TCPA consent text
 *   $thankYouUrl     → required, absolute URL to /thank-you/
 */
$formId       = $formId       ?? 'hero';
$formSubject  = $formSubject  ?? 'New estimate request from ' . ($_SERVER['HTTP_HOST'] ?? 'website');
$formTitle    = $formTitle    ?? 'Get a Free Estimate';
$formSubtitle = $formSubtitle ?? 'No pressure. No upsells.';
?>
<div class="hero__form-card">
  <h3><?php echo htmlspecialchars($formTitle); ?></h3>
  <span class="form-subtitle"><?php echo htmlspecialchars($formSubtitle); ?></span>
  <p class="form-intro">Tell us about your project — we'll get back to you within 1 business day.</p>

  <form action="https://formsubmit.co/<?php echo htmlspecialchars($companyEmail); ?>" method="POST">
    <input type="hidden" name="_next" value="<?php echo htmlspecialchars($thankYouUrl); ?>">
    <input type="hidden" name="_captcha" value="false">
    <input type="hidden" name="_template" value="table">
    <input type="hidden" name="_subject" value="<?php echo htmlspecialchars($formSubject); ?>">
    <input type="hidden" name="_cc" value="CustomerService@pageoneinsights.com">
    <input type="text" name="_honey" style="display:none">

    <div class="form-field">
      <input type="text" name="name" id="<?php echo $formId; ?>-name" placeholder=" " required>
      <label for="<?php echo $formId; ?>-name">Your Name *</label>
    </div>

    <div class="form-field">
      <input type="tel" name="phone" id="<?php echo $formId; ?>-phone" placeholder=" " required>
      <label for="<?php echo $formId; ?>-phone">Phone *</label>
    </div>

    <div class="form-field">
      <input type="email" name="email" id="<?php echo $formId; ?>-email" placeholder=" ">
      <label for="<?php echo $formId; ?>-email">Email (optional)</label>
    </div>

    <div class="form-field">
      <select name="service" id="<?php echo $formId; ?>-service" required>
        <option value="">Select a service...</option>
        <!-- Services injected per build in Phase 2 -->
      </select>
    </div>

    <div class="form-field form-field--checkbox tcpa-consent">
      <input type="checkbox" name="tcpa_consent" id="<?php echo $formId; ?>-tcpa" required>
      <label for="<?php echo $formId; ?>-tcpa">
        By checking this box, I consent to receive phone calls and SMS text messages from
        <?php echo htmlspecialchars($companyName); ?> about my project request. Consent is not a
        condition of purchase. Message and data rates may apply. Reply STOP to opt out at any time.
        <a href="/privacy-policy/" target="_blank" rel="noopener">See Privacy Policy</a>.
      </label>
    </div>

    <button type="submit" class="btn-primary">Request My Free Estimate</button>
    <p class="form-disclaimer">By submitting, you agree to our <a href="/privacy-policy/" target="_blank" rel="noopener">Privacy Policy</a>.</p>
  </form>
</div>
```

### Usage in index.php hero
```php
<?php
$companyName  = 'Tim Cole Contracting LLC';
$companyEmail = 'timcolecontracting@gmail.com';
$thankYouUrl  = 'https://timcolecontracting.com/thank-you/';
$formId       = 'hero';
include $_SERVER['DOCUMENT_ROOT'] . '/includes/hero-form.php';
?>
```

Service dropdown options are injected by editing the partial per build during Phase 2 (each client has different services).

---

## BOT PROTECTION — IN-HOUSE SPAM SHIELD (REQUIRED ON LEADS-ENDPOINT FORMS)

Every form that POSTs to the leads edge function (`https://db.pageone.cloud/functions/v1/leads/{slug}`) MUST carry, in addition to the `_honey` honeypot: a signed render-timestamp field and a JS-interaction field. No third-party dependency (decision 2026-07-22 — the Cloudflare Turnstile rollout is shelved; its edge-fn code stays dormant at `TURNSTILE_MODE=off`). The edge fn also runs content heuristics on every submission (`SPAM_MODE` env: off/log/enforce) — links, HTML, marketing-spam phrases, non-Latin floods.

Insert directly before the submit button, inside the `<form>`:

```php
<!-- spam shield: signed render timestamp + JS interaction signal -->
<?php $__ft_ts = (string) time(); ?>
<input type="hidden" name="_ft" value="<?php echo $__ft_ts . '.' . hash_hmac('sha256', $__ft_ts, $leadsFormSecret); ?>">
<input type="hidden" name="_js" value="" class="js-shield-field">
<?php if (empty($GLOBALS['__js_shield'])) { $GLOBALS['__js_shield'] = 1; ?>
<script>(function(){var d=document,f=function(){var i,e=d.querySelectorAll('.js-shield-field');for(i=0;i<e.length;i++)e[i].value='1';d.removeEventListener('pointerdown',f);d.removeEventListener('keydown',f);};d.addEventListener('pointerdown',f);d.addEventListener('keydown',f);})();</script>
<?php } ?>
```

- `$leadsFormSecret` lives in `includes/config.php` next to `$formAction` — the shared HMAC secret matching the edge fn's `LEADS_FORM_SECRET` (source of truth: `~/supabase-project/leads-form-secret.txt`; server-side PHP only, never rendered to the browser).
- The edge fn rejects `_ft` values younger than 4 s (bot-speed fill), older than 24 h (replay), or with a bad signature. A **missing** `_ft`/`_js` only counts against the lead once `SPAM_TOKEN_REQUIRED=true` — NEVER flip that before every live site is deployed with these fields, or unpatched sites' real leads are silently dropped.
- The `$GLOBALS['__js_shield']` guard keeps the script single-load when a page renders multiple forms (hero partial + contact form).
- Retrofits across all repos: `python3 ~/crm/scripts/patch-leads-spamshield.py --secret-file ~/supabase-project/leads-form-secret.txt --apply`.

---

## LEGAL PAGE TEMPLATE — PRIVACY POLICY

Page: `privacy-policy/index.php`

### PHP Header Block
```php
<?php
$pageTitle       = "Privacy Policy | [Company Name]";
$pageDescription = "How [Company Name] collects, uses, and protects your information. Privacy practices for our website and contact forms.";
$canonicalUrl    = "https://[domain]/privacy-policy/";
$ogImage         = "[logo URL]";
$currentPage     = "privacy-policy";
$companyName       = "[Company Name + Entity Type]";
$companyEntityType = "[Limited Liability Company / Corporation / etc.]";
$companyState      = "[State]";
$companyEmail      = "[email]";
$companyPhone      = "[phone display format]";
$companyPhoneE164  = "[E.164 format]";
$companyAddress    = "[full address]";
$lastUpdated       = date('F j, Y');
$cssVersion        = '[increment]';

$schemaGraph = [
  "@context" => "https://schema.org",
  "@graph" => [
    ["@type" => "WebPage", "@id" => $canonicalUrl . "#webpage", "url" => $canonicalUrl, "name" => $pageTitle, "description" => $pageDescription],
    ["@type" => "BreadcrumbList", "itemListElement" => [
      ["@type" => "ListItem", "position" => 1, "name" => "Home", "item" => "https://[domain]/"],
      ["@type" => "ListItem", "position" => 2, "name" => "Privacy Policy", "item" => $canonicalUrl],
    ]],
  ]
];
$schemaMarkup = '<script type="application/ld+json">' . json_encode($schemaGraph, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>';

include $_SERVER['DOCUMENT_ROOT'] . '/includes/head.php';
?>
```

### Page Structure
```php
<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/nav.php'; ?>

<main id="main-content">

  <section class="hero hero--legal" aria-label="Privacy Policy">
    <div class="hero__copy">
      <span class="eyebrow-label">Legal</span>
      <h1>Privacy Policy</h1>
      <span class="section-subtitle">your data, our commitments</span>
      <p class="hero__phone">Last Updated: <?php echo $lastUpdated; ?></p>
    </div>
  </section>

  <nav class="breadcrumb" aria-label="Breadcrumb">
    <div class="container">
      <ol>
        <li><a href="/">Home</a></li>
        <li class="breadcrumb-sep" aria-hidden="true">›</li>
        <li aria-current="page">Privacy Policy</li>
      </ol>
    </div>
  </nav>

  <article class="legal-prose">

    <h2>1. Introduction</h2>
    <p>This Privacy Policy explains how <?php echo $companyName; ?> ("we", "us", "our") collects, uses, and protects your personal information when you visit [domain] or interact with our services.</p>

    <h2>2. Information We Collect</h2>
    <ul>
      <li><strong>Information you provide:</strong> name, email, phone, service address, project details (via contact forms, phone, or in-person estimates)</li>
      <li><strong>Photo uploads:</strong> if you submit damage photos or project reference images through our forms</li>
      <li><strong>Automatically collected:</strong> IP address, browser type, device info, pages visited, referring URL, timestamps (via Google Analytics 4)</li>
      <li><strong>Cookies and similar technologies:</strong> see our <a href="/cookie-policy/">Cookie Policy</a></li>
    </ul>

    <h2>3. How We Use Your Information</h2>
    <ul>
      <li>Respond to inquiries and provide requested services</li>
      <li>Schedule estimates, inspections, and project work</li>
      <li>Communicate during active projects</li>
      <li>Send service-related communications (including phone calls and SMS messages where you have consented)</li>
      <li>Improve our website and services</li>
      <li>Comply with legal obligations (licensing, insurance, tax)</li>
    </ul>

    <h2>4. How We Share Your Information</h2>
    <ul>
      <li>We do <strong>NOT</strong> sell personal information.</li>
      <li><strong>Service providers:</strong> Google Analytics (analytics), Formsubmit.co (contact form processor), our hosting provider, and Page One Insights, LLC (our web design partner — receives copies of contact form submissions via _cc field for lead-tracking purposes).</li>
      <li><strong>Insurance carriers:</strong> when working on insurance restoration projects, with your explicit consent.</li>
      <li><strong>Subcontractors and material suppliers:</strong> as necessary to complete your project.</li>
      <li><strong>Legal compliance:</strong> if required by [State] or federal law.</li>
      <li><strong>Business transfers:</strong> in the event of a merger, acquisition, or sale of business assets.</li>
    </ul>

    <h2>5. Your Privacy Rights</h2>

    <h3 id="state-rights">[State] Residents</h3>
    <p>You may request access to or deletion of personal information we hold about you. Contact us using the methods below.</p>

    <h3 id="ccpa-rights">California Residents (CCPA / CPRA)</h3>
    <p>If you are a California resident, you have the following rights under the California Consumer Privacy Act (CCPA) and California Privacy Rights Act (CPRA):</p>
    <ul>
      <li><strong>Right to know</strong> what personal information we collect, use, disclose, and sell.</li>
      <li><strong>Right to delete</strong> personal information we have collected from you, subject to certain exceptions.</li>
      <li><strong>Right to correct</strong> inaccurate personal information.</li>
      <li><strong>Right to opt-out of sale or sharing</strong> of personal information. (We do not sell personal information, but you may still submit an opt-out request for our records.)</li>
      <li><strong>Right to limit use</strong> of sensitive personal information.</li>
      <li><strong>Right to non-discrimination</strong> — we will not deny you services or charge different prices based on exercising your rights.</li>
    </ul>
    <p><strong>How to exercise your rights:</strong> Email <a href="mailto:<?php echo $companyEmail; ?>"><?php echo $companyEmail; ?></a> or call <a href="tel:<?php echo $companyPhoneE164; ?>"><?php echo $companyPhone; ?></a>. We will respond within 45 days of receipt.</p>

    <h3>Other State Residents</h3>
    <p>Residents of Colorado, Virginia, Connecticut, Utah, and Texas have similar rights under their respective state privacy laws. Contact us using the same methods above to exercise your rights.</p>

    <h2>6. SMS and Phone Communications (TCPA)</h2>
    <p>When you submit our contact form and check the consent box, you agree to receive phone calls and SMS text messages from us about your project request. Standard message and data rates may apply. Consent is not a condition of purchase. You can opt out of SMS communications at any time by replying STOP to any text message. You can opt out of phone communications at any time by telling our representative or emailing us at <a href="mailto:<?php echo $companyEmail; ?>"><?php echo $companyEmail; ?></a>.</p>

    <h2>7. Data Retention</h2>
    <p>We retain contact form submissions and service records for as long as necessary to provide services and comply with legal obligations, typically 5–7 years for business and warranty records. Photos uploaded via contact forms are deleted after the related project is closed unless retained for warranty or legal purposes.</p>

    <h2>8. Data Security</h2>
    <p>We use reasonable administrative, technical, and physical safeguards including SSL encryption on all form submissions and secure hosting infrastructure. No system is 100% secure. We cannot guarantee absolute security, but we work to minimize risks.</p>

    <h2>9. Children's Privacy</h2>
    <p>This site is not directed to children under 13. We do not knowingly collect information from children. If you believe a child has provided us information, contact us and we will delete it.</p>

    <h2>10. Third-Party Links</h2>
    <p>Our website may link to third-party sites (Facebook, LinkedIn, Yelp, Google Business Profile, manufacturer sites, etc.). We are not responsible for the privacy practices of these sites. Review their privacy policies separately.</p>

    <h2>11. Changes to This Policy</h2>
    <p>We may update this Privacy Policy from time to time. The "Last Updated" date at the top will reflect the most recent change. Material changes will be prominently posted on the site.</p>

    <h2>12. Contact Us</h2>
    <p>For privacy questions or to exercise your rights:</p>
    <p>
      <strong><?php echo $companyName; ?></strong><br>
      Email: <a href="mailto:<?php echo $companyEmail; ?>"><?php echo $companyEmail; ?></a><br>
      Phone: <a href="tel:<?php echo $companyPhoneE164; ?>"><?php echo $companyPhone; ?></a><br>
      Address: <?php echo $companyAddress; ?>
    </p>

  </article>

</main>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>
```

---

## LEGAL PAGE TEMPLATE — TERMS OF SERVICE

Page: `terms/index.php`

Same PHP header structure as Privacy Policy. Same hero/breadcrumb pattern. Body sections:

```
## 1. Agreement to Terms
By accessing or using [domain] or engaging [Company Name] for services, you agree to these Terms of Service. If you do not agree, do not use this site or our services.

## 2. Use of This Website
- You may use this Site for personal, non-commercial purposes to learn about our services and contact us.
- You may not use the Site for unlawful purposes, attempt to access non-public systems, scrape or copy content without written permission, submit false information through our contact form, or use automated systems to extract data.

## 3. Service Estimates and Quotes
All estimates are based on information provided and conditions visible at the time of inspection. Final pricing may differ if project scope changes, hidden damage is discovered, material costs change between estimate and project start, or code requirements differ from initial assumptions. Verbal quotes are non-binding. Only written, signed contracts constitute a final agreement.

## 4. Project Work
- Work is governed by a written contract specific to each job.
- We comply with applicable [State] state and local building codes.
- Work is performed by [Company Name] employees and qualified subcontractors.
- All workers carry workers' compensation insurance as required by [State] law.
- We are licensed and insured to operate in the state of [State].

## 5. Warranties
Workmanship warranties are detailed in your project contract. Manufacturer warranties on materials are provided by those manufacturers and pass through to you upon project completion. Warranties exclude acts of God beyond manufacturer ratings, damage from neglect or alteration by others, and pre-existing conditions disclosed prior to work.

## 6. Payment Terms
Payment terms are specified in your project contract. Standard terms include a deposit at contract signing, progress payments at milestones where applicable, and final balance due upon project completion. We accept check, electronic transfer, and financing through approved third-party providers. Past-due balances may accrue interest as permitted by [State] law.

## 7. Cancellation
Cancellation terms are specified in your contract. Generally:
- Cancellation prior to materials ordered: deposit refunded minus administrative costs
- Cancellation after materials ordered: deposit forfeited; materials become customer property
- Cancellation after work begins: payment due for work completed plus materials

## 8. Insurance Claim Work
For insurance restoration projects, payment terms are typically structured around your insurance carrier's payment schedule. We do NOT serve as a public adjuster or legal representative. We provide repair estimates and complete approved repairs only. Negotiation of claim values and policy interpretation is the homeowner's responsibility.

## 9. Limitation of Liability
To the maximum extent permitted by [State] law, [Company Name]'s total liability for any claim related to the Site or our services shall not exceed the amount you paid for the specific service giving rise to the claim. We are not liable for indirect, incidental, special, or consequential damages.

## 10. Intellectual Property
All content on this Site — text, graphics, photographs, logos — is owned by [Company Name] or used with permission, and is protected by copyright. You may not reproduce, distribute, or create derivative works without written permission.

## 11. Governing Law and Disputes
These Terms are governed by the laws of the State of [State] without regard to conflict-of-laws principles. Any disputes shall be resolved in the state or federal courts located in [County], [State].

## 12. Changes to These Terms
We may update these Terms at any time. The "Last Updated" date will reflect the most recent version. Continued use of the Site after updates constitutes acceptance of revised Terms.

## 13. Contact Us
[Standard contact block]

```

---

## LEGAL PAGE TEMPLATE — COOKIE POLICY

Page: `cookie-policy/index.php`

Standard hero/breadcrumb. Body sections:

```
## 1. What Are Cookies?
Cookies are small text files stored on your device when you visit a website. They are used to make websites work more efficiently and provide information to site owners about how visitors use the site.

## 2. Cookies We Use

### Strictly Necessary
Essential for site functionality (form submission, security). These cannot be disabled. Example: session cookies during form submission.

### Analytics (Google Analytics 4)
We use Google Analytics 4 to understand how visitors use our site. GA4 sets cookies prefixed with _ga and _gid. Data is anonymized via IP truncation.

### Third-Party Embeds
Our site may embed tools and content from third parties (industry partners, review widgets, maps, etc.). These services may set their own cookies subject to their own privacy policies.

## 3. How to Control Cookies
Most browsers allow you to view, delete, or block cookies. You can block third-party cookies or block all cookies (note: site functionality may break). Browser-specific instructions are available from Google, Mozilla, Apple, and Microsoft.

## 4. Opt Out of Google Analytics
You can opt out of GA4 tracking site-wide by installing the Google Analytics Opt-out Browser Add-on at https://tools.google.com/dlpage/gaoptout.

## 5. Our Cookie Notice
We display a brief banner notifying visitors of our cookie use. Once dismissed, the banner is suppressed for future visits via localStorage. You can re-enable the banner by clearing your browser's site data.

## 6. Changes to This Policy
Same pattern as Privacy Policy.

## 7. Contact Us
[Standard contact block]

## Disclaimer
General template; recommend attorney review.
```

---

## LEGAL PAGE TEMPLATE — ACCESSIBILITY STATEMENT

Page: `accessibility/index.php`

Standard hero/breadcrumb. Body sections:

```
## 1. Our Commitment
[Company Name] is committed to ensuring digital accessibility for people with disabilities. We continually improve the user experience for everyone and apply relevant accessibility standards to [domain].

## 2. Conformance Status
This site is designed to conform with Web Content Accessibility Guidelines (WCAG) 2.1 Level AA. WCAG defines requirements for designers and developers to improve accessibility for people with disabilities. Our site partially conforms with WCAG 2.1 Level AA, meaning some content does not yet fully meet the standard. We are working to address all known issues.

## 3. Accessibility Features
- Semantic HTML5 markup with proper landmark regions (header, nav, main, footer)
- Skip-to-content link at the top of every page
- Visible keyboard focus indicators on all interactive elements
- Alt text on all meaningful images
- Sufficient color contrast for body text and interactive elements
- Responsive design that works across screen sizes and zoom levels
- prefers-reduced-motion support — animations disabled for users who request reduced motion
- ARIA labels on navigation and form elements
- Form field labels associated with inputs

## 4. Known Issues
We are aware of these areas needing improvement:
- Some third-party embeds may not fully meet WCAG standards. We provide alternative ways to access this information (call us, email us).
- Some PDF documents may not be fully accessible. Contact us for alternative formats.

## 5. Feedback and Reporting Issues
If you encounter an accessibility barrier on this site, please tell us. We aim to respond to accessibility feedback within 5 business days.

## 6. Alternative Contact Methods
If our website is not accessible to you, you can reach us by phone or mail. We will provide service information in alternative formats on request.

## 7. Changes to This Statement
[Standard]

## 8. Contact Us
[Standard contact block]

## Disclaimer
General template; recommend attorney review.
```

---

## SITEMAP ENTRIES FOR COMPLIANCE PAGES

Add these URLs to `sitemap.xml` during Phase 3D:

```xml
<url>
  <loc>https://[domain]/privacy-policy/</loc>
  <lastmod>[YYYY-MM-DD]</lastmod>
  <changefreq>yearly</changefreq>
  <priority>0.3</priority>
</url>
<url>
  <loc>https://[domain]/terms/</loc>
  <lastmod>[YYYY-MM-DD]</lastmod>
  <changefreq>yearly</changefreq>
  <priority>0.3</priority>
</url>
<url>
  <loc>https://[domain]/cookie-policy/</loc>
  <lastmod>[YYYY-MM-DD]</lastmod>
  <changefreq>yearly</changefreq>
  <priority>0.3</priority>
</url>
<url>
  <loc>https://[domain]/accessibility/</loc>
  <lastmod>[YYYY-MM-DD]</lastmod>
  <changefreq>yearly</changefreq>
  <priority>0.3</priority>
</url>
```

Omit Cookie Policy + Accessibility URLs for Basic and Standard tiers (per tier system).

---

## LLMS.TXT LEGAL SECTION

Add this section to `llms.txt` (place near the bottom, after main content sections):

```markdown
## Legal

- [Privacy Policy](https://[domain]/privacy-policy/): How we collect, use, and protect customer information.
- [Terms of Service](https://[domain]/terms/): Terms governing use of our website and engagement of our services.
- [Cookie Policy](https://[domain]/cookie-policy/): How we use cookies and tracking technologies on our website.
- [Accessibility Statement](https://[domain]/accessibility/): Our commitment to WCAG 2.1 AA conformance and how to report barriers.
```

---

## STATE-BY-STATE NOTES

When generating Privacy Policy content for clients outside Missouri (default state), adjust the "[State] Residents" section based on the client's primary operating state:

| State | Privacy Law Notes |
|-------|------------------|
| California | Include full CCPA/CPRA section (already in template); reference Do Not Sell footer link |
| Colorado | Reference Colorado Privacy Act (CPA) — similar rights to CCPA, smaller business thresholds may exclude small contractors |
| Virginia | VCDPA — similar to Colorado, narrower applicability |
| Connecticut | CTDPA — similar framework |
| Utah | UCPA — narrower applicability |
| Texas | TDPSA — narrower applicability |
| Mississippi, Missouri, Kansas, etc. | No comprehensive consumer privacy law currently — generic rights paragraph sufficient |

For multi-state contractors, include CCPA section as the gold standard (most rigorous) and note "Residents of [other states] have similar rights under their respective state privacy laws."

---

## RETROFIT INSTRUCTIONS FOR EXISTING SITES

To add compliance pages to a site already built without them (Phase 3D applied to a completed site):

1. **Add legal-page CSS** to `assets/css/styles.css` (see "Legal Page CSS" section above)
2. **Add cookie-banner CSS** to `assets/css/styles.css` (see "Cookie Banner" section)
3. **Add TCPA-consent CSS** to `assets/css/styles.css` (in `.form-field--checkbox` block)
4. **Add cookie-banner JS** to `assets/js/effects.js`
5. **Update `includes/footer.php`** to include:
   - Footer legal utility row (`.footer-legal-links` nav)
   - Cookie banner markup
6. **Update both contact forms** (hero + contact page) to include TCPA consent checkbox
7. **Create 4 compliance pages** using the templates above (tier-appropriate count)
8. **Update `sitemap.xml`** with new URLs
9. **Update `llms.txt`** with Legal section
10. **Increment `$cssVersion`** site-wide

Run the site-qa-agent skill afterward to verify all changes propagated correctly.

---

## END OF legal-compliance.md v6.1

This file is loaded by the `pageone-web-builder` skill during Phase 1 (footer + form setup) and Phase 3D (compliance page generation). All templates above are general-purpose contractor templates. Clients should review with their attorney before publication.

Updated: May 2026.
