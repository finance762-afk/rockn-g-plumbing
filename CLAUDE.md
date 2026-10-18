# CLAUDE.md — Page One Insights Build Standards

> Claude Code reads this file automatically on every build. All rules here are **mandatory** unless explicitly overridden by the build prompt.

---

## How This File Works

CLAUDE.md is the **enforcement layer** — it defines what a build must contain and what's forbidden. The **how** lives in reference files. Read the references before writing code:

- `~/crm/references/design-system.md` — visual architecture, CSS tokens, premium technique library, visual vocabulary archetypes (**READ FIRST**)
- `~/crm/references/seo-aeo-2026.md` — SEO + AEO specifications, schema markup, sitemap.php, llms.txt patterns
- `~/crm/references/build-phases.md` — PHP architecture, build phase order, deployment pipeline
- `~/crm/references/blog-standard.md` — blog registry, post anatomy, topic clusters (Premium builds)

Rules below win when they conflict with anything in references.

---

## MODEL POLICY (which Claude model runs each task)

The pipeline picks a model **per task** — it must never inherit the CLI's
default model (that once let Fable 5 burn ~$49 of credits on a single premium
phase). Opus is reserved for design-critical, first-impression, and vision
work; the bulk of a site — inner pages, structure, QA, fixes, edits — runs on
Sonnet at roughly one-fifth the per-token cost.

**Authoritative source: `packages/design-portal/lib/phase-config.ts`**
(`getPhaseModel()`, `BUILD_MODELS`, `TASK_MODELS`, `BUILD_EFFORT`). Generation bumped to Opus 5.5 / Sonnet 5.5 / Haiku 5.5 on 2026-10-09; "Sonnet" below = Sonnet 5.5, "Haiku" = Haiku 5.5. Haiku is never the page writer. This table documents that
config — change both together.

| Task | Model | Why |
|---|---|---|
| Phase 1 — Intake + Logo Analysis | **Opus 5.5** (effort high) | Logo vision + design-token derivation sets the whole site's look |
| Phase 2 — CSS Architecture + PHP Includes | Sonnet | Mechanical scaffolding; framework.css already exists |
| Phase 3 — Homepage | **Opus 5.5** (effort high) | The hero and highest-traffic page — the first impression |
| Phase 4 — Service Pages | **Opus 5.5 (Premium only)** / Sonnet 5.5 (Basic, Standard) | Premium buys editorial depth across many pages; other tiers are fine on Sonnet |
| Phase 5 — About / Contact / FAQ | Sonnet | Straightforward content pages |
| Phase 6 — Service Area(s) | Sonnet | Templated area pages |
| Phase 7 — SEO/AEO + QA generation | Sonnet | Tag/schema generation; QA scoring is a Python script |
| QA narrative audit (`qa/start`) | Sonnet | Analytical, not generative |
| QA fixes (`qa-check`, run-phase fix loop, `fix-qa`) | Sonnet | Mechanical corrections |
| AI edits (`ai-edit`) | Sonnet | Small ad-hoc client/staff edits |
| Personalization Layer (Phase 8) | Sonnet | Content swaps on an existing build |
| Blog post writer (Blogs tab, `blogs/[postId]/write`) | Sonnet | Client-facing long-form prose + schema/registry edits — Sonnet is the quality floor |
| Blog topic scan (`blogs/scan-topics`) | Haiku 5.5 | Constrained JSON selection over pre-computed GSC/keyword signals |

Net effect: Opus runs on **2 phases** for Basic/Standard (1 + 3) and **3** for
Premium and Flagship (1 + 3 + 4); everything else is Sonnet. Flagship (v8) runs
Opus 5.5 on Phases 1, 3, and 4, including Phase 4 Session D (the authorization-
gated conversion components). To shift a phase's tier→model
mapping, edit `OPUS_PHASES` in `phase-config.ts`. Sonnet here is
`claude-sonnet-5-5` (since 2026-10-09); bump `BUILD_MODELS.sonnet` to
upgrade all Sonnet tasks at once.

---

## Performance & Asset Standards (v6.2 — MANDATORY, ENFORCED BY `qa_audit.py`)

Orthogonal to the GSC/keyword "V2 STANDARDS" below — these are the performance-v6.2 output rules, hard QA blockers on every new build. Full spec: `references/performance-2026.md`.

- **Self-hosted fonts — NO Google Fonts CDN.** The scaffold copies the chosen variable woff2 into `/assets/fonts/` and declares `@font-face` (with `font-display: swap`) in `framework.css`. Never add `fonts.googleapis.com` / `fonts.gstatic.com` preconnect or `<link>`. Preload only the above-the-fold heading face. Sources in `references/fonts/`; filenames are the lowercased-hyphenated family name.
- **Inline SVG icons — NO runtime injection.** Paste the raw `<svg>` from `references/lucide-icons/<name>.svg` at build time. Never `<i data-lucide>`, `lucide.createIcons()`, or a Lucide/unpkg CDN `<script>`.
- **Responsive images.** Every hero/card `<img>` needs `srcset` + `sizes`; the pipeline pre-generates `/assets/images/<name>-480.webp`, `-960.webp`, `-1600.webp` for on-disk photos. Explicit `width`/`height`; hero `loading="eager" fetchpriority="high"`.
- **No CDN JS toys** (no VanillaTilt CDN; CSS scroll-snap carousels). **Forbidden tags:** `<meta name="keywords">`, `twitter:*`, and `aggregateRating` in JSON-LD.

> Pre-v6.2 sites audit with `qa_audit.py --legacy`; new builds are graded against v6.2 by default.

---

## Tier Visual Quality Matrix (MANDATORY — ENFORCED BY QA)

Every build is assigned a tier in the build prompt. The tier determines the visual bar.

| Tier | CSS lines/page (inline `<style>`) | Distinct techniques/page | QA enforcement on failure |
|---|---|---|---|
| **Flagship** (v8, 2026-10-09) | **≥ 400** | **≥ 6** from technique library | **FAIL HARD** — build cannot deploy. Plus: blog REQUIRED (12-post cluster target), 40+ URLs with city pages, all four legal pages, and the Flagship components in `references/conversion-components-v8.md` (estimate tool + booking ONLY with the client's recorded authorization in `build-plan.json` `integrations`; text-first contact, financing block, recent-work feed, serve-my-address checker, ≥ 3 before/after pairs on visual trades, photo/video day, Spanish pages where warranted, launch report) |
| **Premium** | **≥ 400** | **≥ 6** from technique library | **FAIL HARD** — build cannot deploy |
| **Standard** | **≥ 200** | **≥ 4** from technique library | WARN — logged but build proceeds |
| **Basic** | **≥ 100** | **≥ 2** from technique library | WARN — logged but build proceeds |

### Automatic-fail conditions (ALL tiers)

- A page with **zero** inline `<style>` blocks fails regardless of tier
- A page that reuses CSS class names from another page's `<style>` block without visual differentiation (just copying) fails
- A page using only default/global `styles.css` classes with no page-specific work fails
- A page where `<style>` contains hardcoded colors, shadows, or spacing values (not `var()` tokens) fails

### Required techniques (per-page minimum)

Each page must use techniques from `design-system.md` Part C. The per-page requirements in C12 define which techniques are mandatory on which page types. At a minimum, every page includes:

- Layered hero (C1) with `::before` gradient overlay + `::after` noise texture
- At least 2 different section divider styles (C3)
- Text-wrap: balance on every heading
- No two consecutive sections with identical layout or background treatment
- One signature section (C7) that doesn't repeat elsewhere on the page

### Why this exists

Without quantitative enforcement, builds produced by AI agents drift toward the minimum — pages with zero custom CSS technically meet qualitative "use premium techniques" rules. The line count and technique count are the floor. Hitting them does not guarantee quality, but falling below them guarantees failure.

---

## Legal & Compliance Requirements (REQUIRED — EVERY BUILD)

Every Page One Insights client website MUST include the following legal and compliance elements. These are non-negotiable and required for TCPA 2025/2026, CCPA/CPRA, ADA WCAG 2.1 AA, and multi-state privacy law compliance. Missing any of these items is an automatic QA fail.

### Four Required Legal Pages (All Tiers)

All pages use subdirectory/index.php pattern:

- `/privacy-policy/index.php` — CCPA/CPRA + 19 other state rights, SMS terms, data processor disclosure
- `/terms/index.php` — Governing law from client's state of formation
- `/cookie-policy/index.php` — GA4, Fonts, Maps, CDN cookies disclosed
- `/accessibility/index.php` — WCAG 2.1 AA conformance statement

Legal pages are linked ONLY from the footer legal row. Never in top nav or body CTAs.

### Footer Legal Row (Every Page)

Above the copyright line in `includes/footer.php`:

```
Privacy Policy | Terms of Service | Cookie Policy | Accessibility | Do Not Sell or Share My Personal Information | Sitemap
```

"Do Not Sell or Share" links to `/privacy-policy/#ccpa-rights` anchor.

### Contact Form: Dual Consent Checkboxes (TCPA 2025/2026)

Contact forms MUST include THREE separate checkboxes:

1. **Email opt-in (optional)** — receives marketing emails from company
2. **SMS opt-in (optional)** — receives text messages; includes "Consent is not a condition of purchase," "Message and data rates may apply," "Reply STOP to unsubscribe"
3. **Terms acceptance (REQUIRED)** — agreement to Privacy Policy and Terms of Service

These must be SEPARATE, UNBUNDLED, NOT pre-checked. This is a Texas TCPA requirement (Sept 2025) and industry best practice everywhere.

Hidden form fields required:

- `consent_version` — currently `"v2.1"`
- `consent_page` — PHP: `<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>`

These arrive in the Formsubmit.co notification email (which is CC'd to Customer Service), preserving a consent record per submission. (Legacy sites that post to the Page One lead endpoint use `_consent_version`/`_consent_page` and get server-side capture into `consent_records`.)

### Required Intake Questions

Intake MUST collect before building:

- Business entity type (LLC, Sole Proprietorship, Corporation, Partnership)
- State of formation (for Terms governing law)
- Does client run paid advertising (Google Ads, Meta Ads)?
- States where business operates

### Why These Are Non-Negotiable

- **TCPA violations** = $500–$1,500 per text. Settlements in 2024–2025 averaged $7–15M. Class actions common.
- **CCPA/CPRA** — $2,500–$7,500 per violation. Applies if any California resident visits.
- **ADA Title III lawsuits** — up 25% YoY. Small businesses targeted.
- **Texas TCPA (Sept 2025)** — requires separate unbundled checkbox specifically.
- **FCC Opt-Out Rule (April 2025)** — businesses must honor any reasonable opt-out method.
- **CCPA 2026 updates** — mandatory confirmation that opt-out was honored.

### Full Templates

Complete page templates, consent HTML/CSS, footer legal row, sitemap entries, Phase 4 audit checklist, retrofit prompt, and agency disclaimer are documented at:

`/home/calvin/crm/references/legal-compliance.md`

- Phase 2 (Header/Footer/Head) must include the footer legal row.
- Phase 4 (Inner Pages) must generate all 4 legal page subdirectories.
- Phase 5 (SEO/Final Polish) must ensure legal pages are in the sitemap.php page registry.
- QA must verify the compliance items in legal-compliance.md: four legal pages, footer legal row, cookie banner, TCPA consent checkboxes, sitemap entries.

---

## Contact Form Submission (REQUIRED — Formsubmit.co, 2026-07-11 standard)

New builds submit contact forms via **Formsubmit.co** to the client's email. Use the exact `form_action` URL from `build-plan.json` verbatim (`https://formsubmit.co/{client email}`). Customer Service is CC'd on every submission via `_cc`.

> **Legacy note:** sites migrated in June 2026 post to a Page One lead endpoint (`db.pageone.cloud/functions/v1/leads/{slug}` or `design.pageone.cloud/api/leads/{slug}`). Those are still valid — do NOT rewrite an existing site's form action in either direction unless explicitly instructed.

**READ `references/contact-form-standard.md` BEFORE writing any form — it contains the full REQUIRED markup to copy verbatim.** Hard rules QA enforces:

- Field names `name`, `email`, `phone` REQUIRED, exact lowercase strings; `service` dropdown + `message` recommended.
- THREE separate consent checkboxes (TCPA 2025/2026): email opt-in (optional), SMS opt-in (optional), terms acceptance (REQUIRED) — unbundled, never pre-checked.
- `_honey` honeypot (hidden + `tabindex="-1"` + `autocomplete="off"`), `_next` ABSOLUTE URL to `/thank-you`, `_captcha=false`, `_template=table`, `_subject`, `_cc=CustomerService@pageoneinsights.com`, plus `consent_version`/`consent_page` hidden fields.
- NO JavaScript submission, NO reCAPTCHA/hCaptcha, NO `mailto:` actions.
- Every build ships `/thank-you.php` (noindexed via `$noindex = true`, branded message, phone CTA, link home).
- First submission to a new client email triggers Formsubmit.co's one-time activation email — submit a test lead at launch and confirm activation.

---


## Footer Dofollow Link (REQUIRED — EVERY BUILD)

Every page must include via footer.php:

```html
<a href="https://pageoneinsights.com" rel="dofollow" target="_blank">Web Design & Hosting by Page One Insights, LLC</a>
```

No exceptions. Do not alter the anchor text. Do not add `rel="nofollow"`.

---

## PHP Include Path Rule (CRITICAL)

Every include across ALL .php files — root level and subdirectories — uses `$_SERVER['DOCUMENT_ROOT']`:

```php
include $_SERVER['DOCUMENT_ROOT'] . '/includes/head.php';
include $_SERVER['DOCUMENT_ROOT'] . '/includes/nav.php';
include $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php';
```

**NEVER use relative paths** like `include 'includes/head.php'` or `include '../includes/head.php'`. Relative paths break on Hostinger when mod_rewrite rewrites the URL but PHP's working directory stays at the document root. Pages in real subdirectories (like `/services/`) will 404 or render broken.

`$_SERVER['DOCUMENT_ROOT']` is the only reliable method.

---

## .htaccess (Subdirectory-Safe)

The rewrite rules MUST exclude `/assets/` and `/includes/`, and MUST NOT use `!-d` (which breaks subdirectory pages on Hostinger):

```apache
RewriteEngine On

# Dynamic sitemap — /sitemap.xml is served by sitemap.php
RewriteRule ^sitemap\.xml$ /sitemap.php [L]

RewriteCond %{REQUEST_URI} !^/assets/
RewriteCond %{REQUEST_URI} !^/includes/
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{DOCUMENT_ROOT}/$1.php -f
RewriteRule ^([^\.]+)$ $1.php [NC,L]
RewriteCond %{THE_REQUEST} /([^.]+)\.php [NC]
RewriteRule ^ /%1 [NC,L,R=301]
```

The `RewriteCond %{DOCUMENT_ROOT}/$1.php -f` target-existence condition is MANDATORY. Without it, a request for a real directory (`/services/`, `/about/`) is rewritten to `dir/.php` and 404s sitewide on Apache/Hostinger (confirmed live on god-s-country-tree-service-llc and xtreme-construction-llc, 2026-07-17); nginx previews mask the bug. With the condition, directories fall through to DirectoryIndex on every host.

**URL shape rule:** ALL pages — including service pages — are built as `directory/index.php` with trailing-slash URLs (`/services/roof-repair/`). Never the dual flat-`.php` + directory-stub pattern (canonical/internal-link mismatch). See build-phases.md.

---

## Accessibility Baseline (REQUIRED)

Every build includes:

- **Skip-to-content link** as first element in nav.php:
  ```html
  <a href="#main-content" class="skip-link">Skip to main content</a>
  ```
  Visually hidden by default, visible on `:focus-visible`, positioned above navbar.
- `<main id="main-content">` wraps page content on every page
- All interactive elements: visible `:focus-visible` outline (2px solid `var(--color-accent)`, 2px offset)
- ARIA landmarks: `<nav aria-label="Main navigation">`, `<main>`, `<footer>`
- All form inputs: associated `<label>` element (floating label pattern satisfies this)
- Color contrast: minimum WCAG AA (4.5:1 body text, 3:1 large text) — verify brand colors meet this before finalizing palette
- Keyboard navigation: all interactive elements reachable and operable via keyboard
- `aria-expanded` toggled on mobile menu toggle button
- `aria-current="page"` on active nav link
- `prefers-reduced-motion` respected in CSS reset (see design-system.md Part A)

---

## Copy Quality Standards (REQUIRED)

### Banned phrases (never use)

- "quality service" / "quality workmanship"
- "trusted professionals"
- "contact us today" (as a headline — fine as button text)
- "your satisfaction is our priority"
- "we go above and beyond"
- "one-stop shop"
- "second to none"

### Required copy characteristics

- **Benefit-driven headlines** — what the customer gets, not what the company claims
- **Specific service language with local context** — reference neighborhoods, landmarks, regional conditions
- **Confident, direct tone** — no hedging, no marketing filler
- **Clear CTAs with urgency or specificity** — "Get Your Free Estimate" / "Call Now — Same-Day Available" (not "Contact Us")
- **Real numbers where possible** — cost ranges, timeframes, years in business, jobs completed — **only from intake/config/GBP**; if the client gave no number, write the sentence without one. Never invent a figure, credential or response time to sound specific (Google Oct 2026 AI-content guidance; applies to titles, metas, schema and alt text too)
- **Answer-first paragraphs** — every service/area page opens with a direct answer in the first 50 words, not marketing fluff
- **Written for this specific business** — not templated. If the same copy could appear on a competitor's site, rewrite it.

---

## Design Anti-Patterns (STRICTLY FORBIDDEN)

- No generic centered text blocks repeated section after section
- No equal-height bland sections stacked with no visual variation
- No default/unstyled buttons — every button uses the button pattern from design-system.md (C9 and button patterns)
- No stock layout repetition across pages — each page uniquely composed
- No decorative-only animations — every animation serves UX
- No more than 2 fonts — one heading + one body (plus optional accent script/italic for specific use cases only)
- No weak hero sections — hero must feel premium immediately
- No flat depthless designs — use elevation system on all elements
- No unstyled form inputs — floating labels with animated focus states required
- No paragraphs wider than 65ch — all prose width-constrained via `.prose` or `.prose-centered`
- No raw rectangle images — all images use a composition treatment from design-system.md C11
- No meta keywords tag — Google has ignored it since 2009
- No Twitter/X card tags — no discovery value for local home service businesses
- No hardcoded colors, shadows, or spacing values in CSS — use `var()` tokens only

---

## Visual Restraint Rule (CRITICAL)

- Not every technique in the library should be used on every site
- Maximum of **3–4 major visual effects** per page (VanillaTilt, magnetic CTA, typed text, parallax, before/after slider each count as a major effect)
- Standard effects that do NOT count toward the limit: scroll fade-up reveals, image wipe reveal on scroll, ripple on click, card hover shift, Ken Burns hero, ticker strip
- If an effect does not enhance clarity or UX for this specific page, do not include it
- Prefer simplicity over stacking features
- Each site must feel intentionally designed, not feature-loaded

---

## Adaptive Design Flexibility

Claude Code MAY adjust:
- Spacing values within the token scale (choose appropriate `--space-*` per context)
- Section ordering (sequence of content sections on a page)
- Grid ratios (e.g. `2fr 1fr 1fr` vs `1fr 1fr 1fr` when appropriate)
- Visual accent placement (where floating elements, decorative corners, etc. land)
- Divider style selection (choose from design-system.md C3 based on archetype fit)

Claude Code MUST NOT skip or omit:
- PHP includes architecture (head.php, nav.php, footer.php)
- CSS variable system and token scale
- Schema markup requirements
- 3 CTAs per page
- Entity blocks and AEO content
- Accessibility features (skip-to-content, prefers-reduced-motion, alt tags, semantic HTML, focus-visible)
- Footer dofollow link
- Lead form endpoint configuration
- Tier-minimum CSS line count and technique count

This rule permits refinement of visual execution, not removal of structural requirements.

---

## Local Preview — PHP Required

Preview command is always:

```bash
php -S 127.0.0.1:8090
```

**Port 8000 is Supabase (Kong) on this box — NEVER `php -S localhost:8000`** (it binds `[::1]:8000`, hijacks every `localhost:8000` API call from the CRM/portals and takes the CRM down; Sep 8 2026 incident). Always bind `127.0.0.1` on a port ≥ 8090, and `kill %1` / `pkill -f 'php -S 127.0.0.1:8090'` when you are done — never leave a preview server running.

Before running, verify PHP is installed:

```bash
which php && php -v
```

If PHP is not found, do NOT suggest `npx serve` or `python3 -m http.server` — these cannot process PHP includes and will render broken pages. Instead:

1. Offer to generate a single temporary static file (`index-preview.html`) with all includes inlined so the homepage can be visually verified
2. Inform the user to install PHP via Homebrew for full preview: `brew install php`

---

## Enforcement Pipeline

Builds pass through these checks in order:

1. **Phase 5 QA** — site-qa-agent skill runs 62+ checks + tier-specific CSS validation (file exists + line counts + technique detection). Generates structured JSON report.
2. **Fix All remediation (Premium only)** — if Premium build fails, Phase 6 auto-runs to bring failing pages up to standard by applying missing techniques page-by-page.
3. **Re-QA** — after Phase 6, full QA runs again. If still failing, build halts for manual review.
4. **Deployment** — only builds passing QA deploy to production.

See site-qa-agent/SKILL.md for validator implementation.

---

## Blog Standard (Premium — REQUIRED)

Every Premium build ships a blog. Full spec: `~/crm/references/blog-standard.md`. Enforcement summary:

- **Registry:** `includes/blog-data.php` — single `$blogPosts` array (slug, title, excerpt, image, alt, date, dateISO, category, readtime). The blog index, homepage preview, related-articles blocks, and sitemap.php ALL read from this registry. Hardcoded post lists anywhere = fail.
- **Structure:** `/blog/index.php` (editorial cards, category badges) + `/blog/{slug}/index.php` per post.
- **Homepage:** "From the Blog" preview section auto-pulling the latest registry post (featured card: image, category badge, date, read time, excerpt, CTA) + View All button.
- **Post anatomy:** answer-first intro (direct answer in first 50 words), TOC sidebar with anchors, sidebar CTA, Related Services block, Related Articles block (2-3 registry cards, same category first), ≥2 inline links to other posts and ≥2 to service pages in body copy.
- **Schema per post:** @graph — BlogPosting (author = Organization @id, datePublished/dateModified, keywords) + BreadcrumbList + FAQPage mirroring the visible FAQ.
- **SEO:** post titles ≤60 chars (no full brand suffix), unique descriptions, self-referencing canonical, auto-included in sitemap.php.
- **Content strategy:** topic clusters — 1 pillar + 4-7 supporting posts answering related long-tail questions, targeting DataForSEO keyword gaps.

---

## Pointers to Reference Files

For anything not covered above:

- **Visual architecture, CSS tokens, technique library, archetypes** → `~/crm/references/design-system.md`
- **SEO schema, meta tags, llms.txt, AEO content rules** → `~/crm/references/seo-aeo-2026.md`
- **PHP component structure, build phases, file organization, deployment** → `~/crm/references/build-phases.md`
- **Blog registry, post anatomy, topic clusters** → `~/crm/references/blog-standard.md`
- **Legal pages, TCPA consent, cookie banner, footer legal row** → `~/crm/references/legal-compliance.md`
- **Services section full HTML + CSS (copy verbatim)** → `references/required-components.md`
- **Contact form full markup + field rules (copy verbatim)** → `references/contact-form-standard.md`

If a requirement is not explicitly in this file and not in a reference file, it does not exist. Do not invent requirements.

---

## Required Components

Reusable component patterns that MUST appear on every build, identical in structure across tiers. Brand colors, fonts, and copy are tier/client specific. Structure, class names, and HTML pattern are NOT. QA validates by class name — builds missing these classes auto-fail.

### Services Section (REQUIRED — All Tiers, All Pages)

Appears on: home page (services overview), `/services/index.php`, and the bottom of each individual service page ("Other Services You May Need" — 3 cards). Never on legal pages, contact, or thank-you.

**READ `references/required-components.md` BEFORE building any services section — it contains the full REQUIRED HTML pattern and CSS to copy verbatim.** Hard rules QA enforces:

- Class names are non-negotiable: `services-grid`, `service-card-with-image`, `card-tint-1/2/3`, `service-card__image/body/icon/desc/cta`. Legacy classes `service-card`, `service-card-image`, `service-card-content`, `service-card-cta` auto-fail.
- Tint rotation `card-tint-1 → 2 → 3 → 1…` — never the same tint on adjacent cards; `reveal-delay-1/2/3` follows the same rotation.
- EXACTLY 3 bullets per card, each 3-6 words, benefit-driven.
- Section H2 is a CONVERSATIONAL QUESTION in customer-search language with 1-3 keywords in `<span class="text-accent">`, followed by a 40-60 word `hero-answer` paragraph. Generic "Our Services" / "What We Do" / "Services Overview" headings are banned (the eyebrow stays the literal "What We Do").
- Icons are inline SVG from `references/lucide-icons/*.svg` (never `data-lucide`, `createIcons`, or a CDN); adjacent cards use different icons.
- Every card has a real client photo from the AVAILABLE CLIENT IMAGES manifest (responsive srcset, explicit width/height); no gradient placeholders or blank divs; stock only as last resort when no client photo exists.
- 9+ services: first 8 on the home grid + `View All {N} Services →` button; ALL services render on `/services/index.php`.


---

# V2 STANDARDS (GSC-DRIVEN — PIPELINE V2 BUILDS ONLY)

> This section exists because real Google Search Console data from deployed Page One sites
> exposed systematic blind spots in the v1 templates. Each rule below is traced to that
> evidence. These rules are ADDITIVE to everything above — where they conflict with a v1
> rule, the V2 rule wins on v2 builds. Enforced by `qa_audit.py --v2`.

## V2.1 Trade-noun language in titles and H1s (REQUIRED)

**Evidence:** searchers use trade nouns ("carpenter near me", "handyman El Cajon"); v1 templates
used service-category language ("Carpentry & Construction"). Those pages earned impressions only
on category terms and missed the trade-noun queries entirely.

- Every service page `<title>` and H1 MUST include the trade noun a searcher would use ALONGSIDE
  the category name: `Carpenter in San Diego, CA | Carpentry & Construction Services`.
- Map categories to trade nouns at plan time: carpentry → carpenter, plumbing → plumber,
  tile installation → tile installer, electrical → electrician, landscaping → landscaper,
  gutters → gutter installer, roofing → roofer, painting → painter, handyman stays handyman.
- The trade noun MUST also appear naturally in the first 100 words of body copy.

## V2.2 CTR-engineered titles and metas (REQUIRED)

**Evidence:** pages at positions 8–15 with near-zero CTR — the snippet gave no reason to click.

- Meta description formula: **[trade noun + city] + [differentiator] + [CTA]**.
- Differentiator = at least one concrete USP from the intake: Same-Day Service, Licensed & Insured,
  ★4.9 Google Rating, 25+ Years, Free Estimates, Financing Available. Use separators (• or |) for
  scannability.
- CTA = an action verb phrase: "Call for a free estimate today", "Get your quote in 24 hours".
- Titles ≤ 60 chars, metas 140–160 chars. NO keyword-only metas; every meta must contain a USP
  and a CTA — `qa_audit.py --v2` fails the build otherwise.

## V2.3 Competitive-category service tiers (REQUIRED)

**Evidence:** one high-competition page (home theater / TV mounting — specialist companies compete
for it) earned the most impressions at the worst positions because every service got equal-weight
treatment.

- Each service in build-plan.json carries `competitive_tier: standard | high_competition`.
- `high_competition` services get: a DEDICATED page (never grouped), 2× content depth
  (800+ words), FAQPage schema with 4+ questions, and internal links from EVERY area page.
- `standard` services follow the normal v1 grouping rules.

## V2.4 Question-format AEO blocks (REQUIRED on every service page)

**Evidence:** a question-format query ranked position 5 for the pilot client — the only
top-5 ranking the site had.

- Every service page includes at least one H2 phrased as the question a searcher would ask
  ("How much does seamless gutter installation cost in Madison, MS?") followed by a direct,
  40–60 word answer paragraph (speakable/snippet-ready), consistent with the AEO standards
  in aeo-content-schema.md.

## V2.5 Keyword-to-page coverage map (REQUIRED)

**Evidence:** several intake keywords (IKEA, patio, pool table) earned zero impressions despite
being "on the page" — nothing actually targeted them.

- The build MUST maintain `keyword-map.json` at the repo root: an array of
  `{ "keyword": "...", "page": "services/x.php", "heading": "the exact H1/H2 that targets it" }`.
- EVERY keyword in build-plan.json (primary, secondary, per-service) appears exactly once.
- The referenced page file must exist and the heading text must appear in it.
- This file is the contract the nightly GSC rules engine checks reality against
  (zero-impression targets); the 30-day post-launch check runs automatically once the
  property is registered.

## V2.6 Homepage focus (REQUIRED)

**Evidence:** the pilot homepage sat at position ~97 on mismatched broad terms because it
targeted generic positioning instead of one cluster.

- The homepage targets ONE primary keyword cluster (the build plan's `primary_keyword` and its
  close variants). Title, H1, and hero copy all commit to that cluster.
- Generic positioning ("Your Trusted Local Contractor") may appear only AFTER the
  cluster-targeted hero, never as the H1/title.

## V2.7 What carries over unchanged

Tinted Image Card pattern, dual-mode navbar, logo analysis Phase 0, the PHP-include
architecture, the tier system (Basic/Standard/Premium), Formsubmit.co forms, all legal/
compliance requirements, the performance budget, and the deploy flow are UNCHANGED from the
standards above.
