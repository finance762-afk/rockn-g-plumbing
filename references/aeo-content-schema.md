# AEO Content & Schema — 2026

Content structure, schema architecture, entity consistency, llms.txt, FAQ patterns.

**Load this file for:** Phase 3 (service pages), Phase 4 (about/contact), Phase 1 (intake — schema requirements), AI edits to content/headings/FAQ.

---

## §1.1 Inverted Pyramid content structure (AEO)

**The shift:** 44.2% of all LLM citations come from the first 30% of page text. AI engines extract the first direct answer they find under a question heading. Long intros and brand throat-clearing get skipped entirely.

**Rule:** Every H2 must be phrased as a question. Immediately after each H2: a **40-60 word direct answer paragraph** in plain language. Detail comes after.

**Accuracy rule (Oct 2026):** the numbers inside an answer (cost ranges, timeframes, response times) come from intake or the client's own pricing; if the client gave none, write the answer without a number ("depends on size and access; the free on-site estimate gives a firm price") rather than a plausible-sounding range. Fees, permit thresholds and legal rules are cited to the official source or phrased as "confirm with the city/county". See seo-aeo-2026.md Part C → Claims accuracy.

**Pattern template:**

```html
<h2>How long does a roof replacement take in Louisville, KY?</h2>
<p class="answer-block">Most residential roof replacements in Louisville take 1-3 days for a standard asphalt shingle roof on a single-family home. Larger homes, complex rooflines, or metal/tile materials extend the timeline to 3-7 days. Weather delays can add time, especially during storm seasons.</p>
<p>Several factors affect the timeline: existing roof tear-off complexity, deck repairs needed, material delivery scheduling, and crew size. We provide a project-specific timeline with your free estimate.</p>
```

**Bad H2 examples (do not use):**
- "Our Services"
- "Quality Workmanship"
- "Why Choose Us"
- "About [Company]"

**Good H2 patterns:**
- "How much does [service] cost in [city]?"
- "What's included in your [service] estimate?"
- "How long does [service] take?"
- "Do you work with insurance claims after storm damage?"
- "Are you licensed and insured in [state]?"

**Schema reinforcement:** Mark Q&A blocks with `FAQPage` schema even when embedded mid-page. One FAQPage block per page, mainEntity array contains all Q&A pairs on the page.

**Implementation:** Phase 3 and Phase 4 prompts enforce this. Phase 7 QA flags any H2 that isn't a question.

---

## §1.2 Expanded schema architecture

Generic `LocalBusiness` is no longer enough. AI engines look for: specific subtype, entity graph (`@graph` with linked `@id`), Speakable schema, geo coordinates, sameAs to social profiles.

### §1.2.1 Industry → Schema subtype mapping

| Industry (intake value) | Schema type |
|---|---|
| roofing | `RoofingContractor` |
| general contracting / construction | `GeneralContractor` |
| hvac / heating cooling | `HVACBusiness` |
| plumbing | `Plumber` |
| electrical | `Electrician` |
| dental | `Dentist` |
| medical practice | `MedicalBusiness` (or subtype) |
| law firm | `Attorney` / `LegalService` |
| auto repair | `AutoRepair` |
| real estate | `RealEstateAgent` |
| restaurant | `Restaurant` |
| retail store | `Store` (or specific subtype like `ClothingStore`) |
| professional services (consulting, accounting) | `ProfessionalService` |
| painting | `HousePainter` |
| moving company | `MovingCompany` |
| locksmith | `Locksmith` |
| concrete / masonry / fencing / garage doors / remodeling / flooring / windows / siding / decks / gutters | `HomeAndConstructionBusiness` |
| tree service / landscaping / pest control / cleaning | `LocalBusiness` (no specific subtype — use `category` text field) |

If no specific subtype fits perfectly: default to `LocalBusiness` + include descriptive `category` text describing the business.

> This table is mirrored in code at `packages/design-portal/lib/schema-type-map.ts` (SERP scan integration). Update both together. (Rows from `painting` through `concrete/…` were added 2026-06-10 with that integration.)

### §1.2.2 Required schemas per page type

**Homepage:**
- `Organization` (`@id: "#organization"`)
- `LocalBusiness` subtype (`@id: "#localbusiness"`)
- `WebSite` with `potentialAction` for SearchAction
- `BreadcrumbList`
- `FAQPage` if homepage has FAQ section

**Service pages:**
- `Service` with `@id: "#service-{slug}"`, provider linked to `#localbusiness`
- `FAQPage` with mainEntity array of Q&A from the page
- `BreadcrumbList`

**Service area pages:**
- `Service` with `areaServed` set to specific City
- Reference `#localbusiness` via `@id`

**About page:**
- `AboutPage`
- `Organization` referenced via `@id`
- Optional `Person` entries for owner/key staff

**Contact page:**
- `ContactPage`
- `LocalBusiness` referenced via `@id`

### §1.2.3 The @graph pattern (one script, not separate)

ONE `<script type="application/ld+json">` in head.php with all schemas linked via `@graph`:

```json
{
  "@context": "https://schema.org",
  "@graph": [
    {
      "@type": "Organization",
      "@id": "https://{domain}/#organization",
      "name": "{Business Name}",
      "url": "https://{domain}",
      "logo": "https://{domain}/assets/images/logo.png",
      "sameAs": [
        "https://www.facebook.com/{handle}",
        "https://www.instagram.com/{handle}",
        "https://www.google.com/maps/place/?cid={cid}"
      ]
    },
    {
      "@type": "{IndustrySubtype}",
      "@id": "https://{domain}/#localbusiness",
      "name": "{Business Name}",
      "image": "https://{domain}/assets/images/logo.png",
      "telephone": "{phone}",
      "email": "{email}",
      "priceRange": "$$",
      "address": {
        "@type": "PostalAddress",
        "streetAddress": "{street}",
        "addressLocality": "{city}",
        "addressRegion": "{state}",
        "postalCode": "{zip}",
        "addressCountry": "US"
      },
      "geo": {
        "@type": "GeoCoordinates",
        "latitude": {lat},
        "longitude": {lng}
      },
      "areaServed": [{ "@type": "City", "name": "{primary_city}" }],
      "openingHoursSpecification": [
        { "@type": "OpeningHoursSpecification", "dayOfWeek": ["Monday","Tuesday","Wednesday","Thursday","Friday"], "opens": "08:00", "closes": "17:00" }
      ],
      "parentOrganization": { "@id": "https://{domain}/#organization" }
    },
    {
      "@type": "WebSite",
      "@id": "https://{domain}/#website",
      "url": "https://{domain}",
      "name": "{Business Name}",
      "publisher": { "@id": "https://{domain}/#organization" }
    }
  ]
}
```

### §1.2.4 GeoCoordinates

Now required, not optional. Phase 1 intake fetches lat/lng from address using Google Geocoding API OR nominatim.openstreetmap.org (free fallback). Store in `site_build_intakes.geo_lat` and `geo_lng` columns. If geocoding unavailable at build time, OMIT geo from schema — never fake it.

### §1.2.5 Speakable schema

Tells AI assistants which text is suitable for voice synthesis. Apply to hero answer paragraph and FAQ answers:

```json
{
  "@type": "WebPage",
  "@id": "https://{domain}/#webpage",
  "speakable": {
    "@type": "SpeakableSpecification",
    "cssSelector": [".hero-answer", ".faq-answer", "h1", ".service-summary"]
  }
}
```

Apply matching `.hero-answer`, `.faq-answer`, `.service-summary` classes in HTML to the actual answer text.

---

## §1.4 Entity consistency across NAP

AI ranking systems penalize inconsistent NAP across web sources. `sameAs` to social profiles is now an entity signal.

**Rules:**

- **NAP on site must exactly match Google Business Profile** when GBP is linked. Phase 1 intake pulls official GBP listing data (name, exact street format, exact phone format) as canonical source.
- **`sameAs` array** in Organization schema links to ALL social profiles + GBP CID URL + industry directory profiles (BBB, Angi, HomeAdvisor, manufacturer dealer lookups).
- **Footer entity block**: add `accessibilityFeature` listing site accessibility features (keyboard nav, alt text) for inclusivity signals.
- **Canonical NAP format:**
  - Phone display: `(XXX) XXX-XXXX`
  - Phone `tel:` link: `+1XXXXXXXXXX`
  - Street per USPS abbreviation rules (e.g., "5312 Regent Way" not "5312 Regent Way Drive")
  - State as 2-letter abbreviation

**Phase 7 QA check:** Compare site's NAP block against `site_build_intakes.build_plan.phone`, `email`, `address_*` fields. Flag any inconsistency.

---

## §2.1 llms.txt evolution

Sites with both llms.txt AND comprehensive schema are significantly more discoverable in AI search. llms.txt now includes service offerings, key answer-statements, and pointers to citable pages.

**Template (save as `/llms.txt` at site root):**

```
# {Business Name}

> {One-sentence business description with primary keyword and city}

{Business Name} is a {industry subtype} serving {primary city + state}. We specialize in {top 3 services}. ["Licensed and insured." only if intake confirms] ["{Years in business} years serving the community." only if intake gives the year]

## Key Facts

- Business name: {Business Name}
- Phone: {phone}
- Email: {email}
- Address: {full address}
- Service area: {city list}
- License: {license number — omit the line if intake has none}
- Insurance: {only if intake confirms — omit otherwise; never default to "Fully insured"}
- Service hours: {hours from intake/GBP}
- Free estimates: {only if intake says so, and say what is included (on-site visit + written quote)}
- Years in business: {years — omit if intake has no founding year}

## Services Offered

- **{Service Name}** — {one-sentence description}. See: https://{domain}/services/{slug}/
[repeat for each service]

## Service Areas

- {City, State} — {one-sentence about service in this area}. See: https://{domain}/service-areas/{slug}/
[repeat for each]

## Most Citable Pages

- Homepage: https://{domain}/ — Overview of services and contact
- About: https://{domain}/about/ — Company history and team
- FAQ: https://{domain}/faq/ — Common customer questions
- [Top 3 service pages]

## Frequently Asked Questions

[Pull 5-10 most important Q&As across the site]
```

---

## §2.4 Conversational FAQ patterns

Average ChatGPT prompt is 23 words vs 3.37 for Google search. FAQs must mirror conversational query patterns, not keyword fragments.

**Bad question forms (do not use):**
- "Cost of new roof?"
- "Service areas"
- "Licensed and insured?"

**Good question forms:**
- "How much does a new roof installation cost in Louisville, KY?"
- "What cities do you serve in the Louisville metro area?"
- "Are you licensed and insured to do roofing work in Kentucky?"

**FAQ answer rules:**
- Lead with direct answer in first sentence
- 40-80 words ideal length
- Include city/state if location-relevant
- Mention pricing if applicable (range is fine: "$8,000-$25,000")
- One FAQ block per page, 4-8 questions
- FAQPage schema applied
