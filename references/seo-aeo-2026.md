# SEO + AEO Specification — Page One Insights

Reference file for all client website builds. Covers classical SEO (for Google ranking) and Answer Engine Optimization (for AI crawlers like Claude, ChatGPT, Perplexity, and Google's AI Overviews).

This file is read by Claude Code during every build. Every rule here is enforceable by the site-qa-agent skill.

---

## Part A — On-Page SEO Fundamentals

### Required per-page meta

Every page declares SEO variables **before** including head.php:

```php
<?php
$pageTitle       = "Page Name | Company | City, State";  // 50-60 chars
$pageDescription = "150-160 char description with primary keyword and CTA";
$canonicalUrl    = "https://domain.com/path-to-page";    // self-referencing, absolute
$ogImage         = "/assets/images/og-[page].jpg";       // absolute path, 1200×630px min
$currentPage     = "page-slug";                           // drives nav active state
$schemaMarkup    = '{...}';                               // page-specific JSON-LD
$noindex         = false;                                 // true for thank-you.php only

include $_SERVER['DOCUMENT_ROOT'] . '/includes/head.php';
?>
```

### Title tag format

**Homepage:** `[Company Name] | [Primary Service] in [City, State]`
  - Example: `BBD Tree Service | Professional Tree Removal in St. Louis, MO`

**Service page:** `[Service Name] | [Company Name] | [City, State]`
  - Example: `Emergency Tree Removal | BBD Tree Service | St. Louis, MO`

**Area page:** `[Primary Service] in [Neighborhood/City] | [Company Name]`
  - Example: `Tree Service in Kirkwood | BBD Tree Service`

**About page:** `About [Company Name] | [City, State] [Primary Service]`

**Contact page:** `Contact [Company Name] | Call [Phone] | [City, State]`

### Meta description format

- 150-160 characters (Google truncates at ~155)
- First 120 chars must stand alone (some SERPs truncate earlier)
- Include primary keyword naturally (not stuffed)
- Include a CTA, plus a distinctive value proposition **only when intake supports it** (licensed, insured, years, certifications, 24/7, same-day, financing, guarantee — each must appear in build-plan.json / config.php or it does not go in the meta)
- Include phone number where space permits
- No quotation marks in the string (breaks some parsers)
- **Title, meta description, OG tags, schema and alt text describe the same offering as the visible page** — never a service, area, credential or timeframe the page itself does not state (Google's Oct 2026 generative-AI guidance reviews metadata for accuracy the same way as body copy)

Example (intake confirms 15 years, licensing, and emergency service): `Expert tree removal in St. Louis. 15+ years, fully licensed, same-day emergency service. Free estimates. Call 314-555-0100.`
Example (intake has none of those): `Tree removal and stump grinding in St. Louis, MO from a local crew. Free written estimates. Call 314-555-0100.` — a meta with no USP is fine; a meta with an invented one is not.

### Heading structure

- **Exactly one `<h1>` per page** (enforced by QA)
- H1 contains primary keyword + location where natural
- H2/H3 hierarchy respected — never skip from H1 to H3
- At least one H2 per service/area page phrased as a **question** (for AEO — see Part C)

### Internal linking

- Every page links to 2-3 other internal pages with descriptive anchor text
- No "click here" / "read more" / "learn more" alone — anchor text must describe destination
- No orphan pages — every page must be reachable from at least one other page
- Footer includes at least: home, services, service areas (if applicable), about, contact
- Breadcrumbs on every page except homepage (visible HTML + BreadcrumbList schema)

### Phone numbers

- Always wrap in `<a href="tel:+1XXXXXXXXXX">` for click-to-call
- Format consistently across site — pick one format and use it everywhere (e.g. always `(314) 555-0100` or always `314-555-0100`)
- Phone number in nav on desktop (visible or reveal-on-scroll)
- Phone number in footer on every page
- Phone number in hero CTA area

### Last Updated

Every service and area page includes a visible "Last Updated: [Month Year]" marker. Updates trust signals for both users and Google.

```html
<p class="last-updated">Last Updated: <?= date('F Y') ?></p>
```

### OG tags (required)

```html
<meta property="og:title" content="<?= htmlspecialchars($pageTitle) ?>">
<meta property="og:description" content="<?= htmlspecialchars($pageDescription) ?>">
<meta property="og:image" content="<?= htmlspecialchars($absoluteOgImage) ?>">
<meta property="og:url" content="<?= htmlspecialchars($canonicalUrl) ?>">
<meta property="og:type" content="website">
<meta property="og:site_name" content="<?= htmlspecialchars($companyName) ?>">
```

### NO Twitter/X card tags

Do NOT include `twitter:card`, `twitter:title`, `twitter:description`, `twitter:image`. These provide zero discovery value for local home service businesses and add bloat. Forbidden.

### NO meta keywords

Do NOT include `<meta name="keywords">`. Google has ignored this tag since 2009. It's a forbidden tag in CLAUDE.md.

### Canonical tag

Every page has a self-referencing canonical:

```html
<link rel="canonical" href="<?= htmlspecialchars($canonicalUrl) ?>">
```

Absolute URL, including protocol. Must match the actual production URL exactly (no trailing slash mismatches, no `www` vs non-`www` inconsistencies).

### robots meta

Standard pages: omit the robots meta tag (default is index,follow — no need to state explicitly).

Exceptions:
- `thank-you.php` — `<meta name="robots" content="noindex,nofollow">` via `$noindex = true`
- `404.php` — `<meta name="robots" content="noindex">` via `$noindex = true`

### Core Web Vitals (v6.2)

CWV is a ranking **tiebreaker**, and mobile is the measured experience — Google evaluates the mobile render, not desktop. The enforceable budget (LCP ≤ 2.0s mobile, INP < 200ms, CLS < 0.1, JS ≤ 100KB, hero image ≤ 150KB) and the techniques that hit it (responsive images, self-hosted fonts, the JS diet) live in **`performance-2026.md`** — required reading alongside this file.

---

## Part B — Schema Markup (JSON-LD)

Schema is the highest-leverage SEO work after meta tags. Google uses it directly for rich results, and AI crawlers use it for fact extraction.

### Universal schema (every page)

**LocalBusiness** schema on every page. NAP must be character-for-character identical to the client's Google Business Profile.

```json
{
  "@context": "https://schema.org",
  "@type": "LocalBusiness",
  "name": "BBD Tree Service",
  "image": "https://bbdtreeservice.com/assets/images/og-home.jpg",
  "url": "https://bbdtreeservice.com",
  "telephone": "+13145550100",
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "123 Main St",
    "addressLocality": "St. Louis",
    "addressRegion": "MO",
    "postalCode": "63101",
    "addressCountry": "US"
  },
  "geo": {
    "@type": "GeoCoordinates",
    "latitude": 38.6270,
    "longitude": -90.1994
  },
  "hasMap": "https://maps.app.goo.gl/XXXXXXXXXXXX",
  "openingHoursSpecification": [{
    "@type": "OpeningHoursSpecification",
    "dayOfWeek": ["Monday","Tuesday","Wednesday","Thursday","Friday"],
    "opens": "07:00",
    "closes": "18:00"
  }],
  "priceRange": "$$",
  "areaServed": [
    {"@type": "City", "name": "St. Louis"},
    {"@type": "City", "name": "Kirkwood"},
    {"@type": "City", "name": "Webster Groves"}
  ],
  "sameAs": [
    "https://www.facebook.com/bbdtreeservice",
    "https://www.google.com/maps/place/..."
  ]
}
```

**`hasMap` + `geo` (required, v6.1):** `hasMap` is the GBP short share link (Share → Copy link). `geo` coordinates are extracted from the GBP map **embed** URL: the `!2d` value is the LONGITUDE, the `!3d` value is the LATITUDE. See the GBP Map Embed retrieval instructions in design-system.md / build-phases.md.

**Schema `@type` by industry** (must be a real schema.org type — the full table lives in `aeo-content-schema.md` §1.2.1 and is mirrored in `packages/design-portal/lib/schema-type-map.ts`; `TreeService`, `LawnMaintenanceService`, `AutoTowing`, `LandscapingBusiness`, `FenceContractor` and `TreeCareProfessional` do NOT exist and fail validation):
- Tree service / lawn care / landscaping / pest control / cleaning: `LocalBusiness` (no specific subtype — describe the trade in `description`/`category` text)
- Towing: `AutoRepair` if the shop also repairs, otherwise `LocalBusiness`
- Plumbing: `Plumber`
- Roofing: `RoofingContractor`
- HVAC: `HVACBusiness`
- Electrician: `Electrician`
- Painting: `HousePainter`; moving: `MovingCompany`; locksmith: `Locksmith`
- General contracting / construction / home building: `GeneralContractor`
- Carpet cleaning: `HousePainter` is wrong — use `LocalBusiness`
- Tile/grout, concrete, masonry, fencing, garage doors, remodeling, flooring, windows, siding, decks, gutters: `HomeAndConstructionBusiness`
- County-level `areaServed` entries: `AdministrativeArea` (there is no `County` type); cities stay `City`
- When in doubt: `LocalBusiness` (generic parent)

`aggregateRating` is never emitted on any of these, on any page — see "AggregateRating — NEVER include" below.

### Page-specific schema stacking

**Homepage**: LocalBusiness + FAQPage (AI comprehension aid — see FAQPage note below)

> `WebSite` + `SearchAction` is no longer required. Google retired the sitelinks search box in 2024, and our sites have no `/search` endpoint for the target URL to point at. A plain `WebSite` node (name + url) is fine if a build already has one; do not add `potentialAction`.

**Service pages**: LocalBusiness + Service + FAQPage (optional — AI comprehension aid) + HowTo (where applicable — AI comprehension aid)

```json
{
  "@context": "https://schema.org",
  "@type": "Service",
  "serviceType": "Emergency Tree Removal",
  "provider": {
    "@type": "LocalBusiness",
    "name": "BBD Tree Service"
  },
  "areaServed": {
    "@type": "City",
    "name": "St. Louis"
  },
  "description": "24/7 emergency tree removal services for storm damage, dangerous trees, and insurance claim support.",
  "offers": {
    "@type": "Offer",
    "priceSpecification": {
      "@type": "PriceSpecification",
      "priceCurrency": "USD",
      "minPrice": "500",
      "maxPrice": "3500"
    }
  }
}
```

**Service area pages**: LocalBusiness + Place

**About page**: LocalBusiness + Organization + Person (for owner if name is public)

```json
{
  "@context": "https://schema.org",
  "@type": "Person",
  "name": "John Smith",
  "jobTitle": "Owner",
  "worksFor": {
    "@type": "LocalBusiness",
    "name": "BBD Tree Service"
  }
}
```

**Contact page**: LocalBusiness + ContactPage

**FAQ page**: FAQPage with every question/answer pair

> **FAQPage/HowTo relabel (v6.1):** include FAQPage and HowTo as **AI comprehension aids only**. FAQ and HowTo rich results were fully deprecated May 2026. Never describe these as rich-result features — in code comments, client communication, or build reports.

```json
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [{
    "@type": "Question",
    "name": "How much does tree removal cost in St. Louis?",
    "acceptedAnswer": {
      "@type": "Answer",
      "text": "Tree removal in St. Louis typically ranges from $500 to $3,500 depending on tree size, location, and difficulty. Small trees under 30 feet: $500-$1,000. Medium trees: $1,000-$2,000. Large or hazardous trees: $2,000-$3,500."
    }
  }]
}
```

### BreadcrumbList (required on all non-homepage pages)

Every non-home page includes both a **visible HTML breadcrumb** AND a **BreadcrumbList schema**:

```json
{
  "@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    {"@type": "ListItem", "position": 1, "name": "Home", "item": "https://bbdtreeservice.com"},
    {"@type": "ListItem", "position": 2, "name": "Services", "item": "https://bbdtreeservice.com/services"},
    {"@type": "ListItem", "position": 3, "name": "Emergency Tree Removal", "item": "https://bbdtreeservice.com/services/emergency-tree-removal"}
  ]
}
```

### ImageObject schema (hero images)

Every page's hero image includes ImageObject schema in the LocalBusiness schema or as a separate graph:

```json
{
  "@type": "ImageObject",
  "url": "https://bbdtreeservice.com/assets/images/og-emergency-removal.jpg",
  "width": 1200,
  "height": 630,
  "caption": "Emergency tree removal after storm damage in St. Louis"
}
```

### Speakable (optional)

`SpeakableSpecification` on the page's `WebPage` node (pattern in `aeo-content-schema.md` §1.2.5) is optional, not a build requirement: add it when the page already carries the matching `.hero-answer` / `.faq-answer` / `.service-summary` classes, skip it otherwise. Its absence is never a QA blocker.

### AggregateRating — NEVER include (v6.1)

Self-serving AggregateRating on LocalBusiness cannot produce SERP stars (those come from GBP only) and risks a manual action. Never include it — on any page, for any client, regardless of how many real reviews they have. Display real GBP review counts as visible page content instead (styled text + link to the GBP listing).

### HowTo schema (where applicable)

**AI comprehension aid only.** FAQ and HowTo rich results were fully deprecated May 2026 — never describe HowTo as a rich-result feature. Use for service pages that describe a step-by-step process:

```json
{
  "@context": "https://schema.org",
  "@type": "HowTo",
  "name": "How We Remove Large Trees Safely",
  "step": [
    {"@type": "HowToStep", "position": 1, "name": "Site assessment", "text": "Our certified arborists assess the tree, surroundings, and risk factors before any work begins."},
    {"@type": "HowToStep", "position": 2, "name": "Equipment setup", "text": "We set up cranes, rigging, or bucket trucks based on the specific removal strategy."},
    {"@type": "HowToStep", "position": 3, "name": "Sectional removal", "text": "For large or hazardous trees, we remove in sections to protect your property."},
    {"@type": "HowToStep", "position": 4, "name": "Stump grinding (optional)", "text": "We grind the stump below grade and remove debris."},
    {"@type": "HowToStep", "position": 5, "name": "Cleanup", "text": "All wood, branches, and debris are removed. Your property is left clean."}
  ]
}
```

---

## Part C — Answer Engine Optimization (AEO)

AEO optimizes for AI crawlers. These weight differently from Google: they favor structured, conversational, direct-answer content over keyword density.

### Chunk-Level Optimization (v6.1)

AI engines retrieve individual page sections ("chunks"), not whole pages. Only 38% of AI Overview citations now come from top-10 ranking pages — well-structured sections win citations independently of rankings. Rules for ALL copy:

- **Every H2/H3 section must stand alone:** a reader (or AI) landing on that section alone must know who, what, and where.
- **Write H2/H3 headings as real user questions where natural** ("How much does stump grinding cost in Greer, SC?" not "Pricing").
- **Open every section with a direct answer of roughly 40 words or fewer,** then elaborate.
- **Entity clarity:** use the full company name (not "we"/"they") at least once per section, ideally in the opening sentence, plus the city where natural. Never open a section with a pronoun.
- **Include specific, verifiable facts** (numbers, materials, timeframes, local references) — factual density increases citation likelihood.
- **E-E-A-T attribution:** About page and footer entity block must carry owner name, license number(s), and years in business. Optional on service pages: "Reviewed by [Owner Name], [credential]".

### Entity Block (required on every page)

Every page includes an **entity block** in the first 100 words of visible body content AND in the footer. Format:

> [Company Name] is a [industry] company based in [City, State], serving [service area]. Founded [year or "with X years of experience" — only if intake gives the year], [Company Name] specializes in [top 3 services]. Contact: [phone] | [email] | [website]. ["Licensed and insured." only if intake confirms both; otherwise omit the sentence — never default to it]

Example:

> BBD Tree Service is a tree care company based in St. Louis, Missouri, serving St. Louis County and the surrounding Metro East area. Founded in 2008, BBD specializes in emergency tree removal, tree trimming, and stump grinding. Contact: (314) 555-0100 | info@bbdtreeservice.com | bbdtreeservice.com. Licensed and insured.

**Why this matters:** AI crawlers use this block as ground truth for the business entity. Consistent phrasing across all pages prevents confusion when the AI cites the business.

**Outward consistency (v6.2):** the entity block, `llms.txt`, the schema NAP (name/address/phone), and the GBP listing must match **character-for-character** — same business name punctuation, same phone formatting, same address abbreviations. Entity resolution across these four surfaces is exact-string sensitive; "Superior Home Builders Corp" vs "Superior Home Builders" reads as two entities.

### Answer Blocks (service and area pages)

Every service and area page includes **answer blocks** — explicit question/answer pairs that AI crawlers can extract directly:

```html
<div class="answer-block">
  <h2>How much does emergency tree removal cost in St. Louis?</h2>
  <p>Emergency tree removal in St. Louis typically ranges from $500 to $3,500. Small trees under 30 feet cost $500-$1,000. Medium trees 30-60 feet cost $1,000-$2,000. Large or hazardous trees over 60 feet cost $2,000-$3,500. Insurance often covers emergency removal after storm damage.</p>
</div>
```

**Answer block rules:**
- The H2 is a complete question (not a topic — "How much does X cost" not "Pricing")
- The answer is 1-3 sentences, direct, with **specific numbers/timeframes** where possible
- Includes cost range, timeframe, scope, or other quantifiable answer
- No marketing fluff ("We're the best" is not an answer)

### Question-H2 coverage (v6.2 refinement)

The question rule applies to **content headings** — answer blocks, FAQ items, educational/explanatory sections. It does NOT apply to CTA, navigational, or section-label headings ("Get Your Free Estimate", "Our Services", "Why Choose Us", "Service Areas", "Testimonials" are doing a different job and forcing them into questions reads as AI-generated).

- **Target: ≥ 40% of content H2s on a page are questions.** (Enforced by qa_audit.py v6.2 — CTA/nav/label headings are exempted from the denominator.)
- Every answer block H2 and FAQ H2/H3 is a question — no exceptions there.
- Legal pages remain fully exempt (numbered-section format).

### Conversational keyword integration

Modern search and AI queries are conversational. Integrate naturally:

- At least one H2 or H3 per service page phrased as a question
- "near me" phrasing in area page body copy (naturally, not stuffed)
- Questions people actually ask — use Search Console "Queries" data or tools like AlsoAsked to find them
- Write for the question, not the keyword

**Examples:**
- Bad: H2 "Tree Removal St. Louis"
- Good: H2 "How do I know if a tree needs to be removed?"

- Bad: H2 "Emergency Services"
- Good: H2 "What qualifies as an emergency tree situation?"

### FAQ Distribution

Every tier includes FAQ content, with density scaled by page:

| Page type | FAQ count |
|---|---|
| Homepage | up to 5-7, only real customer questions from intake |
| Service pages | up to 3-4, only real customer questions from intake (0 is fine) |
| Service area pages | only real customer questions from intake (0 is fine) |
| FAQ page (Premium only) | 10-12 FAQs total |

Every FAQ section has corresponding **FAQPage schema** (see Part B).

### First 50 words

The first 50 words of every section must answer the implied question of that section. No throat-clearing, no "In today's world...", no "Tree service is important because...".

**Bad:** "Tree removal is a service that many homeowners need from time to time. Whether you have an old tree or a new problem, our team can help with a variety of situations..."

**Good:** "Emergency tree removal in St. Louis costs $500-$3,500 depending on tree size and difficulty. We respond within 2 hours for true emergencies and accept most insurance claims. Our certified arborists handle everything from storm damage to hazardous tree assessments."

### Trust indicators (naturally woven)

Include these where they fit — never as a bullet list — and **only from intake / build-plan.json / config.php / the GBP listing**. A trust indicator the client did not give us is not a trust indicator, it is a false claim. If intake is silent on a line, leave it out; do not soften it into "fully insured" or "certified technicians" to fill the slot:

- Licensed and insured (specify state licensing number if available)
- Years in business
- Certifications (ISA Certified Arborist, etc.)
- Cost ranges (not exact prices — "typically $500-$1,500")
- Timeframes ("same-day", "within 2 hours", "48-hour turnaround")
- Warranty/guarantee terms

### Local authority signals

Reference local specifics:
- Named neighborhoods/suburbs (not just "St. Louis" — also "Kirkwood", "Webster Groves", "Clayton")
- Regional landmarks ("near Forest Park", "serving the Central West End")
- Regional conditions ("Midwest storm seasons", "Missouri soil types", "local oak species")
- Nearby cities served

### Claims accuracy (Oct 2026 — Google generative-AI content guidance)

Google's 1 Oct 2026 documentation update folded the Quality Rater guidance into its generative-AI content guide. It is not a new algorithm and AI assistance is not penalised; **unchecked or low-value AI output is**. Every page we ship — and every AI edit afterwards — must pass this before publish:

1. **Identity** — real business name, NAP exactly as GBP, links to the real profiles.
2. **Capability** — name the actual services, tasks and deliverables; never a broad category or "innovative solutions" line.
3. **Fit** — location, service area, hours, payment/financing, languages and availability exactly as intake states them. Do not imply options the client does not offer (shop visits, emergency response, financing, warranties).
4. **Evidence** — reviews via the Page One feed only, real photos, real licence numbers and certification links. Describe evidence accurately; a portfolio shows work, it does not "prove" results.

**Hard rules**
- Specific never means invented: no client counts, revenue, success rates, response times, "certified", "award-winning", "#1", years in business or ratings unless they are in intake/config/GBP. If a claim cannot be supported, remove it or word it as what it is.
- **Metadata is content.** `<title>`, meta description, OG tags, JSON-LD and alt text are checked for accuracy exactly like body copy. Schema states only what the page visibly says (hours, services, areas, credentials); alt text describes the image, no keyword stuffing.
- **Free offers say what is included** — "free estimate" means the on-site visit and written quote are free; say so if the page leans on it, and note any excluded fees.
- **No pages built for search traffic alone.** Area pages exist because the client serves that place and the page says something true and specific about it (see Rule 11 — swapped-city duplicates fail). Never spin pages to cover every possible AI sub-query; that is scaled content abuse whether a person or a model wrote them.
- **llms.txt and special schema are not required by Google** for AI features. Keep them optional (Part D); the returns come from accurate, answer-first pages Google can crawl.
- **AI-written facts are verified before publish**: names, dates, prices, fees, permit rules, laws, qualifications. A blog or FAQ that quotes a fee or legal threshold cites the official source or phrases it as "confirm with the city/county"; it never states a number the model guessed.

**Pre-publish 10-minute check (Phase 5 and every AI edit)**
1. First two sentences of the page: can a stranger tell what is offered and for whom?
2. Capabilities: remove vague or outdated claims; name the work.
3. Conditions: location, availability, scope, pricing, integrations current and true.
4. Proof: does the evidence beside a claim actually support it?
5. Preview vs page: title, meta, OG image/alt promise nothing the page does not.
6. Anything AI wrote: check names, dates, prices, features, credentials against intake and the client's records.

QA: `qa_audit.py` "Metadata claims grounded in intake" flags title/meta claims absent from build-plan.json / config.php, and "Metadata claims match the page" flags metadata promises the visible page does not make.

### SERP Context (DataForSEO scan integration)

Every build intake includes a DataForSEO SERP scan. Its output is **build input, not decoration** — wire it into the site as follows:

| Scan output | Where it goes |
|---|---|
| `keyword_gaps` | H1s and question-format H2s on service pages and blog posts — the gaps define which questions the site answers |
| `service_areas` | `areaServed` entries in LocalBusiness schema + the city/area page list |
| `recommended_schema_type` | The LocalBusiness `@type` subtype on every page |
| Competitor data (reviews, years, claims) | Positioning of trust copy — what to emphasize to compete. **Never fabricated:** competitor stats inform what the client's REAL numbers should highlight; they are never copied onto the client's site |
| `paa` (People-Also-Ask, verbatim) | Question-format H2s / FAQ items on the service page that targets that keyword — answer-first (40–70 words), then detail; FAQPage JSON-LD on any page carrying ≥3. Informational ones may go to the blog |
| `autocomplete` (how locals phrase it) | Exact phrasings for H2s, intros, alt text, blog titles. `kind:"area"` seeds with ≥3 suggestions = the city pages / area sections with real demand |
| `keyword_metrics` (Labs intent × difficulty) | Routing: commercial/transactional → service page; area term → city page; informational/question → blog or FAQ. KD > 50 is not an H1 target on a new domain |
| `parity` (ranking-page anatomy for the primary keyword) | Minimum length (≥ 90% of the ranking average), the H2 topics every ranking page covers, the schema types they carry, whether they answer questions inline |
| `pack_categories` (GBP categories of the pack winners) | Service names in the services section use the category wording; a matching schema.org LocalBusiness subtype overrides the industry default |

If the scan is missing, flag it — do not invent keyword targets. (Enrichment columns were added 2026-08-29 — scans older than that carry only the first four rows.)

---

## Part D — AEO Files

### llms.txt (root-level — OPTIONAL as of v6.1)

`/llms.txt` is a structured business summary for AI crawlers, placed at the domain root. **Optional deliverable:** generate only if trivial; never spend prompt budget on it (see the note at the end of this Part). Format:

```
# [Company Name]

[One-sentence description — "X company in [city] specializing in [top services]"]

## Business Information
- Owner: [Owner name]
- Phone: [phone]
- Email: [email]
- Address: [full address]
- Hours: [Mon-Fri 7am-6pm, etc.]
- Service Area: [list of cities/regions served]
- Licensed: [yes/no + state license # if applicable]
- Insured: [yes]
- Founded: [year]

## Services
- [Service 1]
- [Service 2]
- [Service 3]
- ...

## Pages
- Home: https://domain.com/ — Main landing page, services overview
- About: https://domain.com/about — Company history and team
- Services: https://domain.com/services — All services listing
- [Individual service]: https://domain.com/services/[slug] — [one-line description]
- ...

## About
[2-3 sentence description of the business. Plain prose. No marketing fluff.]
```

### llms-full.txt (root-level)

Expanded version of llms.txt. Same structure plus:

```
## Full About
[3-5 sentences about the business, founding story, and positioning.]

## Service Details
### [Service 1]
[One paragraph describing the service, what's included, typical cost range, typical timeframe.]

### [Service 2]
[One paragraph...]

## Frequently Asked Questions

Q: [Question 1]
A: [Answer 1]

Q: [Question 2]
A: [Answer 2]

[Include 8-10 FAQ pairs covering the most common questions across the business.]

## Certifications & Trust Signals
- [Certification 1]
- [Certification 2]
- [Insurance details]
- [Years in business]
- [Notable projects or partnerships]

## Online Presence
- Website: https://domain.com
- Google Business Profile: [URL]
- Facebook: [URL]
- Instagram: [URL]
- Yelp: [URL]
- BBB: [URL]
- [Other platforms]
```

Both files are plain text (not markdown rendering — though they use markdown-style headers for readability). AI crawlers parse them directly.

**llms.txt / llms-full.txt are OPTIONAL (v6.1 — demoted from required):** negligible measured impact on AI crawler behavior. Generate only if trivial; never spend prompt budget on it. AI crawlers predominantly read the HTML directly. The work that actually earns AI citations is on-page: answer-first content, complete schema, and character-for-character entity consistency across pages.

### robots.txt

```
User-agent: *
Allow: /

Sitemap: https://domain.com/sitemap.xml
```

**The `Sitemap:` line is MANDATORY** — a robots.txt without it is an automatic QA fail. Verify it resolves (it rewrites to sitemap.php — see below).

No `Disallow` rules unless the client has specific pages to exclude. Don't add `Disallow: /wp-admin/` or other CMS-specific rules — these sites aren't WordPress.

**Do NOT block AI crawlers in robots.txt.** These sites WANT to be crawled by Claude, ChatGPT, Perplexity, and Google's AI Overviews — that's the point of AEO. Blocking them defeats the strategy.

### Technical GEO (v6.1)

- **robots.txt:** `User-agent: * / Allow: /` remains standard. NEVER add Disallow rules targeting AI crawlers (GPTBot, OAI-SearchBot, ClaudeBot, PerplexityBot, Google-Extended, Applebot-Extended, meta-externalagent).
- **CLOUDFLARE WARNING (VPS-hosted sites):** Cloudflare has AI-bot blocking features. These MUST be off for client sites — AI-bot blocking silently destroys AEO visibility. On the post-deploy checklist for every Cloudflare-fronted site: verify AI crawler access (Cloudflare dashboard → Security/Bots → confirm AI crawlers are not blocked; spot-check with `curl -A "GPTBot" -I https://domain.com` expecting 200, not 403).

### sitemap.php (dynamic — replaces static sitemap.xml AND sitemap-images.xml)

Sites do NOT ship static sitemap files. A root-level `sitemap.php` generates the sitemap at request time by enumerating:

1. **The page registry** — the site's pages (from the `$content` array keys in `includes/content.php`, or an explicit page list in sitemap.php)
2. **`$blogPosts`** from `includes/blog-data.php` — every blog post, automatically, with `lastmod` from `dateISO`

`.htaccess` rewrites the public URL:

```apache
RewriteRule ^sitemap\.xml$ /sitemap.php [L]
```

robots.txt and GSC always reference `https://domain.com/sitemap.xml` — the rewrite is invisible to crawlers.

```php
<?php
// sitemap.php — emits XML. Never hand-edit page/post lists into this file:
// pages come from the page registry, posts come from $blogPosts.
header('Content-Type: application/xml; charset=utf-8');
require $_SERVER['DOCUMENT_ROOT'] . '/includes/blog-data.php';
echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
<?php foreach ($pages as $page): /* page registry: loc, lastmod, changefreq, priority, images[] */ ?>
  <url>
    <loc><?= htmlspecialchars($baseUrl . $page['path']) ?></loc>
    <lastmod><?= $page['lastmod'] ?></lastmod>
    <changefreq><?= $page['changefreq'] ?></changefreq>
    <priority><?= $page['priority'] ?></priority>
    <?php foreach ($page['images'] ?? [] as $img): ?>
    <image:image>
      <image:loc><?= htmlspecialchars($baseUrl . $img['src']) ?></image:loc>
      <image:caption><?= htmlspecialchars($img['caption']) ?></image:caption>
    </image:image>
    <?php endforeach; ?>
  </url>
<?php endforeach; ?>
<?php foreach ($blogPosts as $post): ?>
  <url>
    <loc><?= htmlspecialchars($baseUrl . '/blog/' . $post['slug'] . '/') ?></loc>
    <lastmod><?= $post['dateISO'] ?></lastmod>
    <changefreq>monthly</changefreq>
    <priority>0.6</priority>
  </url>
<?php endforeach; ?>
</urlset>
```

**Rules:**
- URLs are the trailing-slash directory form (`/services/emergency-tree-removal/`) — must match canonicals exactly
- Image entries are embedded per-URL (this replaces the separate sitemap-images.xml); captions are LLM-readable — treat them as content
- New pages/posts appear automatically when added to their registry — if you find yourself editing sitemap.php to add a URL, the registry is broken

**Priority guidance:**
- Homepage: 1.0
- Primary services: 0.9
- Service area pages: 0.8
- Secondary services: 0.8
- About: 0.7
- Contact: 0.7
- Blog posts / FAQ: 0.6

---

## Part E — Technical SEO

### Clean URLs

ALL pages — including service pages — are built as `directory/index.php` and use trailing-slash URLs. URLs never contain `.php`:

- ✅ `https://domain.com/services/emergency-tree-removal/`
- ❌ `https://domain.com/services/emergency-tree-removal.php`
- ❌ flat `services/emergency-tree-removal.php` + directory stub (dual pattern → canonical/internal-link mismatch)

Canonicals, internal links, and sitemap.php entries must all use the identical trailing-slash form.

### 404.php

Custom 404 page with:
- Same nav and footer as rest of site (don't strip to bare page)
- Clear message that the page doesn't exist
- Primary CTA (call phone number or visit homepage)
- Links to main sections (services, about, contact)
- `<meta name="robots" content="noindex">` set via `$noindex = true`

### thank-you.php

Thank-you page served after form submission:
- Same nav and footer
- Branded success message with client's business name
- Phone number with click-to-call
- "What happens next" — 2-3 sentence explanation of response time and next steps
- `<meta name="robots" content="noindex,nofollow">` set via `$noindex = true`
- Optional: GTM/GA4 conversion tracking trigger

### Core Web Vitals targets

- Lighthouse Performance: 90+
- Lighthouse Accessibility: 95+
- Lighthouse SEO: 95+
- Total page weight: under 3MB
- LCP (Largest Contentful Paint): under 2.5s
- CLS (Cumulative Layout Shift): under 0.1 (enforced via aspect-ratio CSS on all images)

### GA4 + GSC placeholders

head.php includes conditional placeholders for Google Analytics 4 and Google Search Console verification:

```html
<?php if (!empty($ga4MeasurementId)): ?>
<script async src="https://www.googletagmanager.com/gtag/js?id=<?= htmlspecialchars($ga4MeasurementId) ?>"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', '<?= htmlspecialchars($ga4MeasurementId) ?>');
</script>
<?php endif; ?>

<?php if (!empty($gscVerification)): ?>
<meta name="google-site-verification" content="<?= htmlspecialchars($gscVerification) ?>">
<?php endif; ?>
```

These pull from the client's intake data. Always included conditionally — never hardcoded.

### Generative AI visibility (GSC, Jun 2026)

Search Console's **Generative AI performance report** (`Performance → Generative AI`; also reachable via the pre-filtered "AI visibility" links in the CRM SEO tab and the design-portal Links card) shows impressions inside AI Overviews + AI Mode — the scoreboard for the AEO work in this document (answer blocks, question H2s, chunk-level copy). Impressions only; no queries, no clicks, and **no API yet** (when the API adds the AI type, the nightly sync fills `gsc_page_daily.ai_impressions`).

Launch + audit rules:
1. **Settings → Search generative AI must be INCLUDE** on every client property. Exclusion (including inherited from a parent property) zeroes AI visibility silently and is invisible to our tooling — verify by eye at launch and during any SEO audit.
2. Pages flagged by `v_ai_cannibalization` (impressions up, CTR down, rank stable → being consumed by AI answers) surface as `ai_cannibalization` findings in the SEO pipeline — the fix is stronger answer-first blocks, an explicit next-step CTA near the cited chunk, and entity corroboration, so the citation converts instead of just answering.

### Preload (v6.2 — self-hosted fonts, NO Google Fonts CDN)

Fonts are self-hosted in `/assets/fonts/` (see performance-2026.md) — there is NO `fonts.googleapis.com`/`fonts.gstatic.com` preconnect or `<link>`. Preload the above-the-fold hero image and the heading font face from local paths:

```html
<link rel="preload" as="image" href="/assets/images/hero-home.jpg">
<link rel="preload" as="font" type="font/woff2" href="/assets/fonts/<heading-file>.woff2" crossorigin>
```

Preloading the local heading face and hero image prevents the LCP fallback. Do NOT add any Google Fonts preconnect — it fails the v6.2 QA font check.

---

## Part F — Content Rules

### Answer-first opening

Every service page and area page opens with a direct answer, not marketing fluff.

**Bad opening (marketing fluff):**
> "At BBD Tree Service, we understand that trees are an important part of your property. Our team of experts is dedicated to providing you with the best tree service in St. Louis..."

**Good opening (answer-first):**
> "Emergency tree removal in St. Louis costs $500-$3,500 depending on tree size and urgency. Our certified arborists respond within 2 hours for true emergencies, accept most insurance claims, and handle everything from storm damage to hazardous tree removal. We've been serving the St. Louis metro area for 15+ years."

The answer-first opening is simultaneously good SEO (dense with keywords in context), good AEO (extractable answer), and good UX (respects the reader's time).

### Consistent entity naming

The business name, service terms, and location references must be **character-for-character identical** across all pages. Pick once, use everywhere. AI crawlers flag inconsistencies as potential entity confusion.

Examples:
- If the company is legally "BBD Tree Service LLC" but goes by "BBD Tree Service" — use "BBD Tree Service" on every page. Don't mix in "BBD Tree Service LLC" occasionally.
- If a service is called "Emergency Tree Removal" — don't also call it "Emergency Tree Service" or "24/7 Tree Removal" elsewhere.
- If the location is "St. Louis, Missouri" — don't abbreviate to "STL" or "St. Louis, MO" on some pages.

### Service naming consistency

Every service has one canonical name. Document it in the build intake. Use it in:
- Page title
- H1
- Schema `serviceType`
- Navigation label
- Footer link
- Internal linking anchor text
- llms.txt / llms-full.txt

---

## Part G — Verification Checklist

Before a build can pass QA, every page must satisfy:

- [ ] Unique `<title>` tag, 50-60 chars, correct format
- [ ] Unique `<meta description>`, 150-160 chars
- [ ] Exactly one `<h1>`
- [ ] Self-referencing canonical with absolute URL
- [ ] OG tags (title, description, image, url, type, site_name)
- [ ] NO Twitter/X card tags
- [ ] NO meta keywords tag
- [ ] LocalBusiness JSON-LD schema
- [ ] Page-specific schema (Service / Place / Person / FAQPage / BreadcrumbList as applicable)
- [ ] BreadcrumbList schema on non-homepage pages + visible HTML breadcrumb
- [ ] Entity block in first 100 words
- [ ] Entity block in footer
- [ ] All images have descriptive alt text with keyword + location
- [ ] All images have explicit width + height
- [ ] All images have loading="lazy" except hero
- [ ] All phone numbers wrapped in `<a href="tel:">`
- [ ] Internal links to 2-3 other pages with descriptive anchor text
- [ ] Answer-first opening paragraph
- [ ] At least one H2 phrased as a question (service + area pages)
- [ ] FAQ section with correct density for page type
- [ ] FAQPage schema when FAQs present
- [ ] "Last Updated: [Month Year]" visible (service + area pages)
- [ ] Schema matches NAP from GBP character-for-character
- [ ] Every claim in title / meta / OG / schema / alt is in intake (build-plan.json, config.php, GBP) AND stated on the visible page — none added for CTR
- [ ] No invented numbers, credentials, response times, ratings or "free" offers whose terms are not stated
- [ ] AI-written facts (fees, laws, prices, dates, names) verified or phrased as "confirm with…" with a source

Root-level files:
- [ ] `sitemap.php` present; `/sitemap.xml` rewrites to it and returns valid XML with all pages + blog posts (lastmod, changefreq, priority) + per-URL image entries
- [ ] NO static sitemap.xml / sitemap-images.xml files in the repo
- [ ] `robots.txt` contains the mandatory `Sitemap:` line (automatic QA fail if missing)
- [ ] `llms.txt` / `llms-full.txt` — OPTIONAL (v6.1); if present, populated and consistent with on-page NAP
- [ ] `404.php` with full nav + footer + noindex
- [ ] `thank-you.php` with full nav + footer + noindex

---

## Part H — Preview & Launch

### Preview noindex (server-level, never site-level)

Preview-served sites (`preview-{slug}.pageone.cloud`) are excluded from indexing by the **nginx `X-Robots-Tag: noindex, nofollow` header** in the preview vhost (`/etc/nginx/sites-available/preview`). This is platform-wide and automatic for every preview site.

**Never noindex via the site's robots.txt or a robots meta tag in the site code.** Those files ship to the launch domain unchanged — a site-level noindex would carry over and block indexing after launch. The nginx header stays behind on the preview server; the launch domain never sees it.

### $siteUrl = launch domain, always

As soon as the launch domain is confirmed, set `$domain`/`$siteUrl` in `includes/config.php` to the **launch domain** — even while the site is still parked on preview. Never set it to the preview domain once the launch domain is known. Also update every hardcoded absolute URL: the `Sitemap:` line in robots.txt, all URLs in llms.txt/llms-full.txt, and any `$canonicalUrl`/schema URLs hardcoded in page files (blog posts).

On-page assets are root-relative, so the site still renders correctly on preview; canonicals/OG/schema/sitemap pointing at the not-yet-live launch domain is correct and harmless behind the preview noindex header.

### Launch checklist

1. Point DNS at the server; confirm the launch domain is serving the site.
2. `curl -I https://{launch-domain}/` — verify the `X-Robots-Tag` noindex header is **ABSENT** on the launch domain.
3. Submit the sitemap in Google Search Console.
4. Verify the GBP website field matches the launch domain exactly.

---

*This file is the canonical SEO/AEO standard. Update it when SERP behavior or AI crawler norms shift meaningfully. Do not add requirements speculatively — every addition becomes a QA enforcement point.*
