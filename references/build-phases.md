# Build Phases — PHP Architecture, Workflow & Deployment

> Canonical copy: `~/crm/references/build-phases.md`. The copy under
> `~/.claude/skills/pageone-web-builder/references/` is a symlink to this file.

Builds run in focused phases, orchestrated by the Design Portal run-phase API. Never attempt a full site in one session — phased builds produce better output and each phase commits to git, making the history the audit trail.

---

## Phase Pipeline (v6.1.1)

| Phase | Scope |
|---|---|
| 1 | Intake + logo analysis + **image analysis (image-analyst → /content/image-manifest.md)** + token extraction |
| 2 | CSS architecture (framework.css complete with all tokens) + PHP includes (head, nav, footer, content.php scaffold) |
| 3 | Homepage (index.php) with inline `<style>` block hitting the tier line bar |
| 4 | Inner pages (about, services, service areas, contact, legal) each with their own inline `<style>` block |
| 4B | **Blog (Premium only — REQUIRED)** — blog registry + index + first topic cluster. See `blog-standard.md`. |
| 5 | SEO/AEO files (sitemap.php, robots.txt, llms.txt, llms-full.txt, 404.php, thank-you.php) + QA pass |
| 6 | Enhance (Premium only — auto-applies missing techniques if QA failed) |

Each phase ends with `git add -A && git commit`. The Design Portal handles GitHub sync — see Deployment below.

---

## Quality Gates (v6.1 — HARD STOPS, never bypass)

- **GREP VERIFICATION:** every phase ends with grep/lint commands proving each checklist item, with real terminal output shown. "Complete" without output is not complete.
- **BROWSER REVIEW GATE:** inner pages (Phase 4) must not begin until CM confirms he has visually reviewed the homepage in a browser.
- **CLIENT PHOTO GATE:** the polish/enhance phase must not run until real client job-site photos are in place. Polishing stock/reused imagery is wasted work and a rejection risk.
- **AREA PAGES — ONE-THEN-BATCH:** build ONE area page first, stop for review, then batch remaining pages in groups of 3. Every area page must contain at least 3 verifiable local specifics (named neighborhoods, landmarks, housing stock age, soil/terrain/weather conditions) that would be FALSE if the city name were swapped. City-swap templates are a firing offense.

---

## PHP Architecture (applies to every phase)

```
/includes/
  head.php        ← doctype, <head>, meta, OG, schema, CSS, preloads. Computes the
                     self-referencing canonical from the request URI — never hand-set per page.
  nav.php         ← fixed navbar, logo-analysis sizing, aria, mobile overlay menu
  footer.php      ← entity block, dofollow link, legal row, contact, social, scripts
  content.php     ← ALL editable copy: single $content array keyed by page slug + 'global'
  blog-data.php   ← $blogPosts registry (Premium — see blog-standard.md)
/assets/css/framework.css, /assets/js/, /assets/images/, /assets/svg/
index.php
/about/index.php, /contact/index.php, /faq/index.php
/services/index.php, /services/{slug}/index.php
/service-area/index.php, /areas/{city-slug}/index.php
/blog/index.php, /blog/{slug}/index.php          (Premium)
/privacy-policy/, /terms/, /cookie-policy/, /accessibility/   (each directory/index.php)
.htaccess
sitemap.php       ← dynamic; /sitemap.xml rewrites to it
robots.txt, llms.txt, llms-full.txt
404.php, thank-you.php
```

**Include rule:** every include uses `$_SERVER['DOCUMENT_ROOT']` absolute paths. Relative paths break on Hostinger.

**URL shape rule (CRITICAL):** ALL pages — including service pages — are built as `directory/index.php` and linked with trailing-slash URLs (`/services/roof-repair/`, `/about/`, `/blog/{slug}/`). NEVER use the dual pattern of a flat `services/roof-repair.php` plus a directory stub: it creates two resolvable URLs for one page and guarantees canonical/internal-link mismatches.

**content.php contract:**
- Every piece of editable content (headlines, body copy, FAQ pairs, stats, CTAs) lives in the `$content` array in `includes/content.php`, keyed by page slug, plus a `'global'` key for NAP, hours, entity block, and social URLs.
- Framework files (head.php, nav.php, footer.php, framework.css, sitemap.php, .htaccess) are **locked** — content edits (CMS or AI) touch `content.php` only.
- head.php computes `$canonicalUrl` from the request URI, so canonical tags can never drift from real URLs.

---

## Phase 1 — Intake + Logo Analysis

Analyze the client's logo before any design decision.

**Deliverables:**
- Color palette: `--color-primary`, `--color-secondary`, `--color-accent` derived from logo
- Tinted card colors: brand colors at 6-10% opacity
- Logo sizing class based on aspect ratio (wordmark / square / combination / tall)
- Nav recommendation (light / dark / transparent) from logo edge colors
- **Logo variants flag (v6.1):** whether to request an SVG/vector version (raster blurs under shrink-on-scroll) and whether a white/knockout variant is needed for dark navs/footer; recommend the TWO-TIER HEADER pattern (design-system.md F10) for wide wordmarks (>3:1)
- Font temperature suggestion + archetype proposal (see design-system.md Part D)
- Intake data verified: entity type, state of formation, service list, service areas, GBP NAP, **DataForSEO SERP scan** (keyword gaps, service areas, recommended schema type — see seo-aeo-2026.md "SERP Context")

### Intake Structure (v6.1 — REQUIRED vs CONDITIONAL)

**REQUIRED (every build):**
1. Company name  2. Owner name  3. Phone(s) — and does the client accept SMS texts? (drives sticky-bar Text button)  4. Email  5. Location (city, state)  6. Service area  7. Hours  8. Services list  9. About/story/years/values  10. Logo (+ SVG version if available)  11. Work photos (Imgur URLs + descriptions) — REAL job-site photos; flag if missing, photo gate applies before polish. **Request ORIGINAL photo files when possible (v6.1.1):** EXIF GPS enables city-matched placement on area pages; Imgur strips EXIF on upload, so the image-analyst extracts GPS/date locally from the originals before upload  12. Domain  13. Tier (BASIC/STANDARD/PREMIUM/FLAGSHIP)  14. GBP Map Embed iframe (retrieval instructions in design-system.md F4; "none" if no GBP)  15. Google place_id (`ChIJ...` — from Place ID Finder or GBP dashboard; separate from the embed link; powers review link + directions link)

### Image Intelligence (v6.1.1 — runs in Phase 1, before any copy or assembly)

The **image-analyst** sub-agent runs ONCE per client build, immediately after intake. It downloads every client photo, records dimensions/orientation (`identify`), extracts EXIF GPS + capture date from originals when provided (`exiftool`), reads each image with vision, and writes `/content/image-manifest.md`: per-image subject, service match, city, orientation, quality score, placement suitability (hero/split/card/gallery), `max_uses` budget (hero-quality: 1, others: 2), a `used_on` ledger, and a `suggested_alt`. It ends with a GAPS section — services or page types with no matching photo — which becomes the client photo request list.

Downstream contract: the copywriter reviews GAPS before writing and specifies slots as `[IMAGE: subject/mood/orientation need]` (never URLs); the page-assembler fills every slot from the manifest (subject fit → orientation fit → lowest usage) and appends the page path to `used_on` after placing — it stops and reports if no in-budget match exists; the qa-auditor cross-greps ledgers vs. actual usage (no image over `max_uses`, none on 3+ pages, alts consistent with the manifest).

> **TEMPORARY DATA-AVAILABILITY NOTE (2026-07-10 — remove when the v6.1 intake fields flow end-to-end):**
> Items 3 (SMS acceptance), 7 (hours), 14-15 (GBP map embed, place_id) and conditional items 16-22 below arrive ONLY via the `integrations` block (and `business_hours`) in `build-plan.json`. If a value is absent there, it is UNAVAILABLE for this build — omit the dependent section honestly (skip F4/F5/F7/F8/F9; sticky bar gets two buttons, not three). Do NOT search Google Maps for embed links (F4's retrieval steps are suspended), do NOT guess place_ids, do NOT fabricate hours, badges, or review counts. Treat SMS as NOT accepted unless `integrations.accepts_sms` is `true`.

**CONDITIONAL (ask; skip section if none):**
16. BBB profile URL (+ accreditation status)  17. Manufacturer/certification profile links (GAF, CertainTeed, ISA arborist, etc. — the client's page on the issuer's site)  18. Trade association memberships (+ member-directory links)  19. License number(s)  20. (retired 2026-10-02: Elfsight embed — reviews now come from the Page One reviews block, F7)  21. Financing provider + prequalify link (+ optional client-supplied example: amount, months, APR, monthly)  22. Online scheduling link (Google Appointment Schedules / Calendly)  23. Video assets (YouTube links or Cloudinary files)  24. Social links

**FLAGSHIP CONDITIONAL (v8, 2026-10-09 — asked on every Flagship build; specs in `conversion-components-v8.md`):**
25. **Estimate-tool authorization** — the client's explicit yes (onboarding checkbox + typed name + timestamp, or a verbal authorization recorded by staff with name + date) AND their price ranges per service (service, low, high, unit, note). Stored as `integrations.estimate_tool`. No authorization = no estimator.
26. **Booking authorization** — the client's explicit yes (same two capture paths) AND available weekdays, time-window labels, lead time, and the confirmation promise in their words. Stored as `integrations.booking`. No authorization = no booking form.
27. Owner's own mobile number for texts (`integrations.text_us_number`) — never our toll-free number
28. Service-area ZIP codes per city (`integrations.service_zips`) for the serve-my-address checker
29. Before/after photo pairs (marked as pairs in the image manifest)
30. Photo/video day: requested or declined, preferred dates, shop and job addresses for the photographer
31. Spanish pages: whether the client serves Spanish-speaking customers and who takes a Spanish call or text (`integrations.spanish`, reviewer name)

---

## Phase 2 — CSS Architecture + Includes

**Deliverables:**
- `framework.css` complete: all tokens, resets, utilities, component classes, ALL premium technique classes
- `head.php` (meta, OG, schema injection, preloads, request-URI canonical computation), `nav.php`, `footer.php` (entity block, dofollow link, **footer legal row**, mobile sticky CTA bar)
- `content.php` scaffold with `'global'` key populated
- `animations.js` (multi-directional reveals), `effects.js` (nav scroll, hamburger→X, mobile menu, counters, cookie banner)
- `.htaccess` — subdirectory-safe rules per CLAUDE.md, including `/sitemap.xml → /sitemap.php` rewrite
- **Cache rule (v6.1):** CSS/JS cache lifetime max 1 week (`ExpiresByType text/css "access plus 7 days"`), never months. Images may cache longer. The `?v=` cache-bust on styles.css remains mandatory.
- **Sticky CTA bar:** includes the third `sms:+1XXXXXXXXXX` "Text Us" button when the client accepts texts (two buttons if declined) — see design-system.md F6
- **Font loading rule (v6.2):** fonts are SELF-HOSTED variable woff2 in `/assets/fonts/` with `@font-face` in framework.css — never a Google Fonts CDN URL/preconnect. The scaffold stages the chosen pairing's woff2 (sources in `references/fonts/`); preload only the above-the-fold heading face. Max ~6 font files total.

**Review gate:** includes resolve, tokens render, nav transitions, mobile menu animates.

---

## Phase 3 — Homepage

index.php with full inline `<style>` block meeting the tier CSS bar (see CLAUDE.md Tier Visual Quality Matrix). All copy from `$content['home']`.

- Hero: layered (C1), transparent nav overlay, accent font emphasis, SVG divider
- **Hero lead form (v6.1 default):** Name, Phone, Service Needed, TCPA checkbox — see design-system.md F1. Button-only hero is the fallback, not the default.
- **Credentials carousel (v6.1):** linked third-party credential badges — see design-system.md F2. Only badges the client actually holds.
- **Named numbered process (v6.1):** branded "[Company-Name N-Point Process]" replaces generic "How It Works" — see design-system.md F3
- Required sections per CLAUDE.md (services grid with exact class names, stats, testimonials, entity block in first 100 words)
- **Premium:** "From the Blog" preview section auto-pulling the latest post from `$blogPosts` (added/wired in Phase 4B if blog doesn't exist yet)
- Schema: LocalBusiness (industry subtype from SERP scan, with `geo` + `hasMap`) + WebSite + FAQPage (AI comprehension aid only). **NEVER AggregateRating** — see seo-aeo-2026.md.

**Review gate (HARD STOP — v6.1):** Phase 4 (inner pages) must not begin until CM confirms he has visually reviewed the homepage in a browser. A design-critic pass supplements but never replaces this. Also check against the Visual Quality Checklist before any inner page is built.

---

## Phase 4 — Inner Pages

### Session A: Services
- `/services/index.php` + `/services/{slug}/index.php` for each service (directory/index.php — never flat .php)
- Each page: unique title/description, Service schema, FAQPage schema, answer-first copy, answer blocks, Related Services cards

### Session B: Service Areas
- `/service-area/index.php` (Premium: main + `/areas/{city}/index.php` per city)
- **ONE-THEN-BATCH (v6.1):** build ONE area page first, stop for review, then batch remaining pages in groups of 3.
- **Every area page: 400+ words of UNIQUE content** with at least 3 verifiable local specifics (named neighborhoods, landmarks, housing stock age, soil/terrain/weather conditions) that would be FALSE if the city name were swapped. City-swap templates are a firing offense — automatic QA fail.
- Geo-specific LocalBusiness schema, internal links to service pages

### Session C: Supporting Pages
- About (Organization + Person schema, overlapping image layout)
- Contact (Formsubmit.co form — action comes from build-plan.json `form_action`; TCPA consent checkboxes per legal-compliance.md canonical partial) + **GBP Map Embed section with "Get Directions" button (v6.1 standard — see design-system.md F4)**
- FAQ (Premium), 404.php, thank-you.php (noindex) — **thank-you.php carries the Review Request Link: `https://search.google.com/local/writereview?placeid=[PLACE_ID]` behind a "Happy with our work? Leave us a Google review" button (design-system.md F5)**
- Google reviews block on the homepage (F7 — `includes/google-reviews.php`, no Elfsight); conditional sections per intake: financing (F8), scheduling (F9)
- Four legal page directories per legal-compliance.md

### Session D: Flagship components (Flagship only — authorization-gated, v8)
Read `conversion-components-v8.md` first. Build in this order, each from `build-plan.json` `integrations`:
- **Estimate tool** (§1) — ONLY if `integrations.estimate_tool.authorized === true` with `authorized_by`, `authorized_at`, `method`. `includes/estimate-tool.php`, `$estimateTool` in config.php with the client's ranges verbatim, `/estimate/` page, links from the hero forms. Absent authorization: do not build it, do not stub it, do not mention it.
- **Book a time window** (§2) — ONLY if `integrations.booking.authorized === true` (same three fields). `includes/booking-window.php`, `/book/` page, sticky-bar link, thank-you copy for booking leads.
- **Text-first contact** (§3) — when `integrations.accepts_sms`; `includes/text-us.php` with the owner's own number.
- **Financing block** (§4) — when a partner and URL exist; figures only with a client-supplied example.
- **Recent-work feed** (§5) — `includes/recent-work.php` on the homepage and the service-areas hub, wrapped in the empty-result `if`.
- **Serve-my-address checker** (§6) — `includes/zip-check.php` on the service-areas hub with the server-side `?zip=` fallback.
- **Before/after** (§7) — at least three real pairs from the manifest on visual trades.
- **Spanish pages** (§9) — when `integrations.spanish`; `/es/` mirror with hreflang pairs; the human review is scheduled before Phase 5.
- Photo/video day files (§8) must be in `/incoming/` and through the image-analyst before this session uses them; the CLIENT PHOTO GATE applies.

**Review gate:** every Session D component present on the site has its authorization or intake condition in `build-plan.json`; qa_audit blocks an estimator or booking form without one.

**Internal link hierarchy (mandatory):** homepage links to all service pages → service pages link to relevant area pages → area pages link back to services → footer carries city links. No orphan pages.

---

## Phase 4B — Blog (Premium only, REQUIRED)

Full specification: `~/crm/references/blog-standard.md`.

**Deliverables:**
- `includes/blog-data.php` — the `$blogPosts` registry (single source of truth)
- `/blog/index.php` — listing page with category badges and editorial cards
- First **topic cluster**: 1 pillar post + 4-7 supporting posts at `/blog/{slug}/index.php`, targeting keyword gaps from the DataForSEO scan
- Homepage "From the Blog" preview wired to the registry
- Posts auto-appear in sitemap.php (registry-driven — verify, don't hand-add)

---

## Phase 5 — SEO/AEO Files + QA

**Deliverables:**
- `sitemap.php` — dynamic sitemap enumerating the page registry AND `$blogPosts` (see seo-aeo-2026.md Part D). NO static sitemap.xml or sitemap-images.xml files.
- `robots.txt` — with the mandatory `Sitemap:` line pointing at `https://domain.com/sitemap.xml`
- `llms.txt` + `llms-full.txt` — **OPTIONAL (v6.1):** negligible measured impact on AI crawler behavior; generate only if trivial, never spend prompt budget on it
- Verify: no two pages share a title or description; NAP consistency; all images on own domain or client Supabase bucket; width/height/loading/alt on every image
- Run site-qa-agent (qa_audit.py + render checks + narrative rubric)
- **Flagship (v8):** `sitemap.php` lists `/es/` URLs with hreflang alternates when Spanish pages exist; verify every Session D component against its `integrations` condition (estimator/booking authorization records, financing partner, sms number, zip list); the **launch report** (`conversion-components-v8.md` §10) is generated by `lib/launch-report.ts` when the Launch-on-Domain flow completes, saved to `/content/launch-report.html` + PDF, emailed to the client, and attached to the deal feed. A Flagship launch without the report is blocked in the launch flow.

---

## Phase 6 — Enhance (Premium only)

If Premium QA fails, Phase 6 applies missing techniques page-by-page, then full QA re-runs. If still failing, the build halts for manual review.

**CLIENT PHOTO GATE (v6.1):** Phase 6 / any polish pass must not run until real client job-site photos are in place. Polishing stock or reused imagery is wasted work and a rejection risk.

---

## Deployment (VPS Pipeline)

```
Design Portal (VPS) → Claude Code builds in ~/client-sites/{slug}/
  → design-portal sync-github API (repo create + push, account: finance762-afk)
  → staging branch
  → QA passes
  → promote staging → main
  → Hostinger auto-deploys main (webhook)
```

- All builds live in `~/client-sites/{slug}/` on the VPS. There are no Mac-local scripts — `./new-site.sh` and `./deploy.sh` do not exist. Do not suggest them.
- GitHub sync is handled by the Design Portal's `sync-github` endpoint — Claude Code commits locally; the portal pushes.
- Work lands on the **staging** branch. Promotion to **main** happens only after QA passes. Hostinger's Git panel deploys `main` automatically (repo `git@github.com:finance762-afk/{slug}.git`, root directory, webhook configured).
- **Since 2026-08-23 the portal does not wait on that webhook.** After every `git push origin main` (promote, sync-github promote, push-to-production, blog publish on main, website-lock) it runs `~/crm/scripts/hostinger-deploy.sh {slug}`, which SSHes to the Hostinger box (`u286184502@srv1854.hstgr.io:65002`), `git fetch` + `reset --hard origin/main` in `~/domains/{domain}/public_html`, and returns the on-disk commit + HTTP status. Manual use: `bash ~/crm/scripts/hostinger-deploy.sh {slug} [--expect <sha>]`. The hourly `hostinger-sweep.sh` also reconciles every docroot to its branch tip, so a plain push lands within the hour even if the webhook never fires. Only the 45 git docroots on srv1854 are reachable this way — 28 sites sit on the other Hostinger plan (hcdn edge) and still rely on the webhook.
- Local preview: `php -S 127.0.0.1:8090` (PHP required — see CLAUDE.md). NEVER port 8000 — that is Supabase/Kong; a stray `php -S localhost:8000` hijacks `localhost` for every app on the box. Kill the preview server when done.

### VPS-Hosted Sites (v6.1)

Sites hosted directly on this VPS do **NOT** auto-deploy from GitHub. Deploy = rsync to the web root with `--no-perms --no-group --omit-dir-times`, preserve the setgid pattern (calvin:www-data, dirs 2755, files 0644), then purge Cloudflare cache. Every VPS post-deploy checklist includes the Cloudflare AI-bot verification from seo-aeo-2026.md "Technical GEO" (dashboard → Security/Bots → AI crawlers not blocked; spot-check `curl -A "GPTBot" -I https://domain.com` expecting 200, not 403).

---

## Launch on Domain (automated, 2026-08-23)

The build page's **Launch on Domain** card (`POST /api/builds/[id]/launch-domain`, `lib/domain-launch.ts`) takes an approved build live: Hostinger website entity (API) → DNS (our zone: A @ → 167.88.41.184 + CNAME www; client-owned: exact records shown, re-checked every 15 min by cron) → SSH deploy of `main` → SSL wait → `refresh-domain` + cache purge → Search Console + GA4 (`post-deploy` chain, META fallback) → `domain_checklist` flips (fires the SEO launch lifecycle) → sweep map. Works for domains registered via Hostinger AND client-owned domains.

**POLICY:** nothing launches, and no domain is purchased, before `site_build_intakes.approval_status = 'approved'`. The intake "Register" button 409s until then and auto-launches after the purchase. Admin/manager can force a launch (logged).

## Post-Deploy Verification

1. Visit every page — nav, footer, links, images, forms; confirm every URL is the trailing-slash directory form
2. `domain.com/sitemap.xml` returns valid XML from sitemap.php and includes all pages + blog posts
3. `domain.com/robots.txt` contains the `Sitemap:` line
4. `domain.com/llms.txt` resolves
5. Submit test form → lead appears in Portal; thank-you redirect works
6. Click-to-call + mobile CTA bar on a real phone-width viewport
7. Lighthouse: Performance 90+, Accessibility 95+, SEO 95+
8. Validate schema at Google Rich Results Test; submit sitemap in GSC
