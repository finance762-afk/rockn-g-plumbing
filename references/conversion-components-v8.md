# Conversion Components — Web Builder v8 Flagship (2026-10-09)

> Canonical copy: `~/crm/references/conversion-components-v8.md`. Loaded for Flagship builds (Phase 1 intake, Phase 4 Session D, Phase 5 launch report), for any estimator / booking / Spanish / launch-report work on an existing site, and by QA. Companion files: `content-quality-2026.md` (is the page worth reading), `seo-aeo-2026.md` Part C "Claims accuracy" (what a page may claim), `design-system.md` Part F (pointers F13–F19 land here).

**Why Flagship exists.** Our median website sale is about $949. Agencies charging $5,000–$10,000 are not selling a different visual style; they sell things that look made for one business and things that book jobs. Flagship ($4,995–$7,500 one-time) is Premium plus the components below, a photo/video day, Spanish pages where the market warrants, and a launch report the client can hold.

**Tier bar (same as Premium, enforced by QA):** inline CSS ≥ 400 lines/page, ≥ 6 techniques/page, blog REQUIRED with a 12-post cluster target, 40+ URLs including city pages, all four legal pages.

---

## 0. The authorization gate (HARD RULE)

Two components publish something that belongs to the owner: the **estimate tool** publishes their prices, and **booking** commits their calendar. Neither is built without the owner's explicit yes, captured during the build.

**How authorization is captured (one of two ways):**

1. **Client onboarding form** (`/onboarding/[token]`, "Authorizations" section): a checkbox with the full consent text, the client's typed name, and a server timestamp. The estimate-tool authorization also collects price ranges per service. The booking authorization also collects available days and time windows.
2. **Verbal, recorded by staff** on the CRM website-intake page: the staff member records the client's name, the date, and which component(s) were authorized. Method is stored as `verbal`.

**Where it lives — `build-plan.json`:**

```json
"integrations": {
  "estimate_tool": {
    "authorized": true,
    "authorized_by": "Joseph LaFauci",
    "authorized_at": "2026-10-09T15:12:00Z",
    "method": "onboarding",
    "pricing": [
      { "service": "Stump grinding", "low": 100, "high": 400, "unit": "per stump", "note": "6–12 in. below grade" },
      { "service": "Tree removal", "low": 500, "high": 3500, "unit": "per tree", "note": "size and access" }
    ]
  },
  "booking": {
    "authorized": true,
    "authorized_by": "Joseph LaFauci",
    "authorized_at": "2026-10-09T15:12:00Z",
    "method": "onboarding",
    "days": ["Mon", "Tue", "Wed", "Thu", "Fri"],
    "windows": ["8–10 AM", "10 AM–12 PM", "1–3 PM", "3–5 PM"]
  }
}
```

**Rules:**
- Build prompts include `includes/estimate-tool.php` only when `integrations.estimate_tool.authorized === true`, and `includes/booking-window.php` only when `integrations.booking.authorized === true`. Absent or `false` means the component does not exist on the site. No placeholder, no "coming soon".
- `authorized_by`, `authorized_at`, and `method` are all required for `authorized` to count. A bare `true` is treated as not authorized.
- QA (`qa_audit.py`): an estimator or booking form present on the site without the matching authorization record is a **blocker**. Authorized but missing is a **warning**.
- The estimator shows the client's ranges verbatim. Nobody rounds, widens, or "improves" a range. Changing a price means re-authorizing (new `authorized_at`).
- Staff cannot authorize on the client's behalf. The verbal path records a conversation; it is not a shortcut.

---

## 1. Instant estimate tool (Flagship, authorization-gated)

**Purpose.** A multi-step tool that gives the visitor a price range from the client's own pricing before they call, then captures the lead. It is the most cited conversion feature on contractor sites and the one most often faked with invented numbers. Ours uses only intake pricing.

**Ships when:** Flagship tier AND `integrations.estimate_tool.authorized === true`.

**Intake fields:** `integrations.estimate_tool.pricing[]` (service, low, high, unit, note), plus which services the client wants in the tool (default: all with pricing). Optional modifiers only if the client states them (e.g. "add $150 for crane access").

**Config contract (`includes/config.php`):**

```php
$estimateTool = [
  'enabled'  => true,                          // only true when authorized
  'heading'  => 'What will my job cost?',
  'services' => [
    ['slug' => 'stump-grinding', 'label' => 'Stump grinding', 'low' => 100, 'high' => 400, 'unit' => 'per stump', 'note' => '6–12 in. below grade'],
  ],
  'modifiers' => [],                           // optional, client-stated only
  'disclaimer' => 'Ranges are typical for {City}; your written estimate is free and firm.',
];
```

**Include:** `includes/estimate-tool.php` reads `$estimateTool` and renders:

```html
<section class="section estimate-tool" id="estimate-tool" aria-labelledby="estimate-tool-h2">
  <div class="container">
    <h2 id="estimate-tool-h2">What will my job cost?</h2>
    <p class="answer-block">{first sentence: the lowest and highest range on the page, in plain words} ...</p>
    <form class="estimate-tool__form" method="post" action="{form_action}" data-estimate-tool>
      <ol class="estimate-tool__steps">
        <li class="estimate-tool__step" data-step="1"> <!-- service select (radio cards) --> </li>
        <li class="estimate-tool__step" data-step="2"> <!-- quantity / size, only if the unit needs it --> </li>
        <li class="estimate-tool__step" data-step="3"> <!-- result: "$100–$400 per stump" + name/phone/email + TCPA consent + submit --> </li>
      </ol>
      <?php echo p1_attribution_fields('estimate-tool'); ?>
      <input type="hidden" name="estimate" value="1"> <input type="hidden" name="estimate_low" value=""> <input type="hidden" name="estimate_high" value=""> <input type="hidden" name="estimate_inputs" value="">
    </form>
  </div>
</section>
```

- Step 3 posts to the standard leads endpoint (`form_action`) with `form_location=estimate`, `estimate=1`, `estimate_service`, `estimate_qty`, `estimate_low`, `estimate_high` and `estimate_inputs` (JSON of the answers), so the lead alert says what the visitor priced (leads fn `estimateFields`).
- The visible range on step 3 is computed client-side from `$estimateTool` printed as JSON in a `<script type="application/json" id="estimate-tool-data">` block. No fetch, no third-party script.
- **No-JS:** the three steps render as one stacked form; the service select and quantity field submit normally; the range is not shown without JS but the lead still sends. Nothing is hidden behind JS-only controls.
- **Accessibility:** radio cards are real `<input type="radio">` with `<label>`; steps are an `<ol>`; the live result uses `aria-live="polite"`; focus moves to the step heading on advance.
- **Copy rules:** heading is a question; the answer-block first sentence states the real low and high on the page; the disclaimer names what the free estimate includes (the on-site visit and the written quote). Never "instant quote", never "guaranteed price".
- **Placement:** homepage after the services grid (Flagship), top of each service page that has pricing, and `/estimate/` as its own URL. Link it from the hero form card ("Want a range first?").
- **Claims:** every number on screen exists in `integrations.estimate_tool.pricing`. QA cross-checks.

**QA:** component present ⇒ authorization record required (blocker); `data-estimate-tool` present ⇒ `#estimate-tool-data` JSON present and every low/high in it matches build-plan pricing (blocker); no-JS fallback renders the form (warning); `estimate_service` hidden field present (warning).

---

## 2. Book a time window (Flagship, authorization-gated)

**Purpose.** Visitors who will not phone get a confirmed next step: they ask for an estimate visit on a day and time window. It is a lead with a preferred slot, not a third-party calendar. The owner confirms by text or call; nothing is auto-booked.

**Ships when:** Flagship tier AND `integrations.booking.authorized === true`.

**Intake fields:** `integrations.booking.days[]` (which weekdays), `integrations.booking.windows[]` (the owner's labels, e.g. "8–10 AM"), lead time in days (default 1), and the confirmation promise in the owner's words (e.g. "we confirm by text within a business day").

**Config contract:**

```php
$bookingWindow = [
  'enabled'   => true,                         // only true when authorized
  'days'      => ['Mon','Tue','Wed','Thu','Fri'],
  'windows'   => ['8–10 AM','10 AM–12 PM','1–3 PM','3–5 PM'],
  'lead_days' => 1,
  'confirm'   => 'We confirm by text within one business day.',
];
```

**Include:** `includes/booking-window.php` renders:

```html
<section class="section booking-window" id="book" aria-labelledby="booking-h2">
  <div class="container">
    <h2 id="booking-h2">When can you come out?</h2>
    <p class="answer-block">{first sentence: the days and windows the client offers, in plain words} ...</p>
    <form class="booking-window__form" method="post" action="{form_action}">
      <input type="hidden" name="booking" value="1">
      <label for="preferred_date">Preferred day</label>
      <input id="preferred_date" name="preferred_date" type="date" min="{today + lead_days}" required>
      <fieldset><legend>Time window</legend>
        <!-- one radio per window: <input type="radio" name="time_window" value="8–10 AM" required> -->
      </fieldset>
      <!-- name, phone, email, job address (optional), service select, TCPA consent -->
      <?php echo p1_attribution_fields('booking'); ?>
      <button type="submit" class="btn btn-primary">Request this time</button>
      <p class="booking-window__confirm">{confirm}</p>
    </form>
  </div>
</section>
```

- Posts to the standard leads endpoint with `booking=1`, `preferred_date`, `time_window`. The leads function stores both on the lead and puts them in the subject line of the email alert and the SMS ("Estimate visit requested: Tue Oct 14, 10 AM–12 PM").
- The `<input type="date">` is constrained to the authorized weekdays with a small script; without JS the server accepts any date and the owner handles it in the confirmation.
- **No-JS:** fully functional native form.
- **Accessibility:** native date input, real radios in a `<fieldset>`, visible labels, error text tied by `aria-describedby`.
- **Copy rules:** the heading is a question; the page never says "booked" or "confirmed" before the owner confirms; the confirmation promise is the client's own sentence from intake.
- **Placement:** `/book/` as its own URL; a "Request a visit" link beside every hero form and in the sticky mobile bar; the thank-you page tells booking leads what happens next.

**QA:** form with `name="booking"` present ⇒ authorization record required (blocker); `preferred_date` + `time_window` fields present (blocker when the component exists); confirmation sentence present (warning).

---

## 3. Text-first contact (Flagship default; Premium when the client accepts texts)

**Purpose.** A visible "Text us" path everywhere, because many homeowners will text before they will call.

**Ships when:** `integrations.accepts_sms === true`. Flagship builds ask for it in intake as the default.

**Intake fields:** the owner's own mobile number for texts (`integrations.text_us_number`), which may differ from the main line. **Rule (existing):** the number is the owner's own, never our toll-free number, never a Page One number.

**Include:** `includes/text-us.php` renders a sticky button on desktop (bottom right, above the fold on scroll) and the third button in the mobile sticky bar (design-system.md F6):

```html
<a class="text-us" href="sms:+1XXXXXXXXXX?&body=Hi%20{Company}%2C%20I%27d%20like%20an%20estimate%20for%20" aria-label="Text {Company}">
  <!-- inline lucide message-square svg --> Text us
</a>
```

- The prefilled body is short and ends mid-sentence so the visitor finishes it.
- Hours line under the button when intake has hours ("Texts answered 7 AM–7 PM").
- **No-JS:** it is a link.
- **Accessibility:** 44px minimum target, visible focus ring, `aria-label` with the business name.
- **Copy rules:** "Text us", not "Chat". No bot, no live-chat widget.

**QA:** when `accepts_sms` is true, an `sms:` link exists in the sticky bar and in `includes/text-us.php` (warning); the number matches `integrations.text_us_number` (blocker if different); no `sms:` link when `accepts_sms` is false (blocker).

---

## 4. Financing block (conditional, all tiers; Flagship asks for it)

**Purpose.** Monthly-payment framing on high-ticket services lifts contact rates. It only works when it is true.

**Ships when:** `integrations.financing.partner` and `integrations.financing.url` are both present. No partner, no block, no "financing available" line anywhere.

**Intake fields:** partner name, prequalify URL, and, only if the client supplies it, a representative monthly figure with the terms it came from (`integrations.financing.example` = `{ amount, months, apr, monthly }`).

**Include:** `includes/financing-block.php`:

```html
<aside class="financing-block" aria-labelledby="financing-h3">
  <h3 id="financing-h3">Spread the cost with {Partner}</h3>
  <p>{One or two sentences in plain words. If an example exists: "A $4,500 project at 9.99% APR over 36 months is about $145 a month." Otherwise no numbers.}</p>
  <a class="btn btn-secondary" href="{url}" rel="noopener" target="_blank">Check your rate</a>
  <p class="financing-block__terms">Subject to credit approval through {Partner}. {Company} does not lend.</p>
</aside>
```

- Placed on service pages whose price range in intake exceeds $1,500, and on `/estimate/` under the result step.
- **Copy rules:** "from $X/mo" only when `example` exists and the terms sentence is printed beside it. Never "0% financing", "everyone approved", or any figure the partner did not give.

**QA:** financing text or block present ⇒ `integrations.financing.partner` present (blocker); a dollar figure in the block ⇒ `example` present in intake (blocker).

---

## 5. Recent-work feed (Flagship default; Premium optional)

**Purpose.** A section that changes without anyone touching the site: the client's latest Google Business Profile photos and posts. It is information gain (real jobs, real dates) and freshness in one.

**Ships when:** the client's GBP is connected (same condition as the reviews block, F7). Flagship builds place it on the homepage and the service-areas hub.

**Source:** `GET https://db.pageone.cloud/functions/v1/site-recent-work/{slug}[?limit=8&kind=photos|posts|all]`, edge function `site-recent-work`, data from the nightly GBP sync (`gbp_photos`, `gbp_posts`). Mirrors the `site-reviews` feed: returns `[]` when nothing is available; the include caches on the site for 6 hours; never fabricate items.

**Include:** `includes/recent-work.php`, canonical copy in `~/crm/references/recent-work.php`, copied in by the scaffold:

```php
<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/recent-work.php';
$recent = p1_recent_work($slug, ['heading' => 'Recent jobs around {City}', 'limit' => 8]);
if ($recent !== ''): ?>
<section class="section recent-work" aria-label="Recent work"><div class="container"><?php echo $recent; ?></div></section>
<?php endif; ?>
```

- Renders a scroll-snap row of cards: photo (lazy, explicit width/height, alt from the post text or "Job photo from {Company}, {Month Year}"), date, first 140 characters of the post text, link to the GBP post. Container carries `data-p1-dynamic`.
- Photos are proxied through the feed at 960px max; no hotlinking Google CDN URLs in the page.
- Returns `''` when the profile is not connected or has no photos, so the section is always wrapped in the `if`.
- **No-JS:** cards render server-side; scroll-snap is CSS.

**Copy rules:** the heading names the city; no captions are written by us. Post text is the owner's, shown verbatim and truncated.

**QA:** include present on Flagship homepage (warning); no raw `googleusercontent.com` image URLs in page source (blocker); `data-p1-dynamic` on the track (warning).

---

## 6. "Do you serve my address?" checker (Flagship default)

**Purpose.** The first question a visitor has on a service-areas page, answered in one tap, then a lead on yes.

**Ships when:** Flagship tier (also allowed on Premium when there are 8+ city pages).

**Intake fields:** the service-area city list with ZIP codes (`integrations.service_zips[]` = `{ city, state, zips[] }`), which already drives city pages. If intake has cities but no ZIPs, the checker matches on city name only.

**Include:** `includes/zip-check.php` on `/service-areas/index.php` under the hub answer block:

```html
<form class="zip-check" data-zip-check action="/service-areas/" method="get" aria-labelledby="zip-check-h2">
  <h2 id="zip-check-h2">Do you serve my address?</h2>
  <label for="zip">ZIP code or town</label>
  <input id="zip" name="zip" inputmode="numeric" autocomplete="postal-code" required>
  <button type="submit" class="btn btn-primary">Check</button>
  <p class="zip-check__result" aria-live="polite"></p>
</form>
<script type="application/json" id="zip-check-data">{"areas":[{"city":"Ringgold","state":"GA","slug":"ringgold-ga","zips":["30736"]}]}</script>
```

- Match ⇒ "Yes, we serve {City}." with a link to that city page and the hero form opened with `city_slug` prefilled. No match ⇒ "We may still be able to help" with the phone and text links (never a hard no; the owner decides).
- **No-JS:** the form submits to `/service-areas/?zip=`; the hub page reads `$_GET['zip']` server-side and prints the same result.
- **Accessibility:** `aria-live` result, visible label, no auto-redirect.

**QA:** Flagship hub has `data-zip-check` (warning); `#zip-check-data` cities ⊆ intake service areas (blocker if a city appears that intake does not list).

---

## 7. Before/after sliders (default for visual trades, all tiers; Flagship requires ≥ 3)

**Visual trades:** roofing, painting, landscaping, tree service, pressure washing, remodeling, flooring, fencing, concrete, cleaning, auto detailing. Uses the existing `.ba-*` pattern (`.ba-panel`, `.ba-label`, range-input slider, CSS fallback that shows both images stacked without JS — QA "Before/after no-JS fallback" already exists).

**Intake fields:** paired before/after photos from the client (the image-analyst marks pairs in the manifest). No pair, no slider; never pair unrelated photos or stock.

**Placement:** homepage signature section and the matching service page. Flagship: at least three pairs across the site, each captioned with the job and the place.

**QA:** visual-trade Flagship build with < 3 `.ba-panel` pairs (warning); every pair has two real client images from the manifest (blocker on stock).

---

## 8. Photo/video day (Flagship deliverable, vendor process)

Not code. The account manager books a local photographer or drone operator for about two hours on a working day: crew, trucks, equipment, two or three live jobs, the owner, the shop. Budget $300–$600 per market; the client approves the date; raw files come back within a week.

**Deliverables into the build:** 20+ originals with EXIF (the image-analyst runs on them like any client photo), a 10–20 second hero clip (muted, looped, `<video>` with poster, under 2 MB, `prefers-reduced-motion` shows the poster), headshots for the About page, and a drone establishing shot where the trade suits it.

**Process:** intake flags `photo_day: requested` → account manager creates the CRM task (`photo_day_booking`) and books → shoot → files to the build's `/incoming/` → image-analyst → Phase 3 uses the hero clip, Phase 4 uses the job photos. The build does not reach Phase 6 polish until the photos are in (existing CLIENT PHOTO GATE).

**Copy rule:** every photo caption names the job and the place; no caption claims a result the photo does not show.

---

## 9. Spanish-language pages (Flagship option where the market warrants)

**Ships when:** `integrations.spanish === true`, set when intake confirms the client serves Spanish-speaking customers and can take a call or text in Spanish (or says who does). Without that, no Spanish pages: a Spanish page that leads to an English-only phone call is a broken promise.

**Scope:** `/es/` mirror of home, the services index, each service page, contact, and the thank-you page. City pages and blog stay English unless the client asks.

**Standard:**
- URL shape: `/es/`, `/es/servicios/`, `/es/servicios/{slug}/`, `/es/contacto/`, `/es/gracias/`. Same directory/index.php shape; slugs translated.
- Every English page with a Spanish twin carries `<link rel="alternate" hreflang="es" href="…">` and `hreflang="x-default"` pointing at the English page; every Spanish page carries `hreflang="en"` back. `head.php` computes this from a `$translations` map in `content.php`.
- `<html lang="es">` on Spanish pages; schema `inLanguage: "es"`; NAP identical; titles and metas in Spanish, unique, same claims as the English page and no others.
- Translation is human-reviewed. The build drafts it; a Spanish-speaking reviewer (staff or the client's named person) approves before launch. The review is recorded in the launch report.
- `sitemap.php` lists Spanish URLs with `xhtml:link` alternates.
- Nav: a single "Español / English" toggle in the utility bar, not a flag icon.

**QA:** every `/es/` page has an English twin and both hreflang links resolve 200 (blocker); `lang="es"` on Spanish pages (blocker); Spanish metas unique (blocker); reviewer name and date present in the launch report (warning).

---

## 10. Launch report (Flagship deliverable; Premium gets the short form)

Generated by `packages/design-portal/lib/launch-report.ts` when the Launch-on-Domain flow completes, saved as `/content/launch-report.html` in the repo (not public; `.htaccess` blocks `/content/`) and a PDF, emailed to the client with the portal walkthrough link, and attached to the deal feed.

**Contents (all measured, nothing asserted):**
1. Lighthouse mobile and desktop scores for home, one service page, one city page (Performance, Accessibility, Best Practices, SEO), with the run date.
2. Accessibility statement URL and the WCAG 2.1 AA checks passed (from qa_audit Accessibility section).
3. Search Console: property verified, sitemap submitted and read, no coverage errors on launch day.
4. GA4: property id, live tag confirmed with a real pageview.
5. Lead alert test: timestamp of the test submission, who received the email and the SMS.
6. Components shipped: list with the authorization record for the estimator and booking (name, date, method), photo day date, Spanish reviewer name and date.
7. Page inventory: every URL with its title, grouped by type, and the blog cluster plan.
8. What the client does next: the portal walkthrough date, how to edit text and photos, how review requests work, the 30/60/90-day check-in dates.

**QA:** Flagship launch without `/content/launch-report.html` committed (blocker in the launch flow, not in qa_audit).

---

## 11. Flagship checklist (Phase 4 Session D + Phase 5)

- [ ] Authorizations read from `build-plan.json`; estimator and booking built only when `authorized === true` with name, timestamp, and method
- [ ] `includes/estimate-tool.php` with `#estimate-tool-data` matching intake pricing exactly; `/estimate/` URL; links from hero forms
- [ ] `includes/booking-window.php` posting `booking=1`, `preferred_date`, `time_window`; `/book/` URL; sticky-bar link
- [ ] `includes/text-us.php` with the owner's own number; third sticky-bar button
- [ ] `includes/financing-block.php` only with a named partner and URL; dollar figures only with `example`
- [ ] `includes/recent-work.php` on home and service-areas hub, wrapped in the `if`
- [ ] `includes/zip-check.php` on the service-areas hub with server-side `?zip=` fallback
- [ ] ≥ 3 before/after pairs on visual trades, all from the manifest
- [ ] Photo day booked and files in before Phase 6
- [ ] Spanish pages with hreflang pairs and a named reviewer, when `integrations.spanish`
- [ ] Launch report generated, emailed, and in the deal feed
- [ ] Every number and claim in the new components traces to intake (seo-aeo-2026.md Part C)
