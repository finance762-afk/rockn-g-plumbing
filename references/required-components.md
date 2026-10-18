# Required Components — Full Spec (HTML + CSS)

> Loaded on demand. CLAUDE.md → "Required Components" carries the hard rules and class names; this file carries the full copy-verbatim HTML pattern and CSS. Read this file BEFORE building any services section.

This section defines reusable component patterns that MUST appear on every build, identical in structure across tiers. Brand colors, fonts, and copy are tier/client specific. Structure, class names, and HTML pattern are NOT.

QA validates these by class name. Builds missing these classes auto-fail.

---

### Services Section (REQUIRED — All Tiers, All Pages)

**Where it appears (mandatory):**
- Home page (the services overview)
- `/services/index.php` (the services listing page)
- Bottom of each individual service page (as "Related Services" — 3 cards)

**Where it does NOT appear:** legal pages, contact, thank-you.

**HTML pattern (exact class names — QA validates these):**

```html
<section class="section" aria-label="{Industry} services">
  <div class="container">
    <div class="section-title reveal-up">
      <span class="eyebrow-label">What We Do</span>
      <h2>{Question-format heading specific to the business}</h2>
      <p class="hero-answer">{40-60 word direct answer paragraph that answers the H2}</p>
      <span class="section-subtitle">{tagline phrase}</span>
      <p class="prose">{1-2 sentence description of the company's service mix}</p>
    </div>

    <div class="services-grid">
      <!-- Repeat this card for each service. Tints rotate 1, 2, 3, 1, 2, 3... -->
      <article class="service-card-with-image card-tint-1 reveal-up reveal-delay-1">
        <div class="service-card__image">
          <img src="/assets/images/{photo}.jpg" srcset="/assets/images/{photo}-480.webp 480w, /assets/images/{photo}-960.webp 960w, /assets/images/{photo}-1600.webp 1600w" sizes="(max-width: 768px) 100vw, 600px" alt="{descriptive alt}" width="600" height="360" loading="lazy">
        </div>
        <div class="service-card__body">
          <div class="service-card__icon"><!-- inline SVG from references/lucide-icons/{icon-name}.svg (NOT data-lucide) --></div>
          <h3>{Service Name}</h3>
          <p class="service-card__desc">{1-sentence description, no fluff}</p>
          <ul>
            <li>{benefit/feature 1 — 3-6 words}</li>
            <li>{benefit/feature 2 — 3-6 words}</li>
            <li>{benefit/feature 3 — 3-6 words}</li>
          </ul>
          <a href="/services/{slug}/" class="service-card__cta">Learn more</a>
        </div>
      </article>
    </div>
  </div>
</section>
```

**Tint rotation rule:** Cards cycle through `card-tint-1` → `card-tint-2` → `card-tint-3` → `card-tint-1` → ... Never place two cards with the same tint class adjacent in the source. The `reveal-delay-1/2/3` modifier follows the same rotation.

**Bullet rule:** EXACTLY 3 bullets per card. Not 2, not 4. Each bullet 3-6 words, scannable, benefit-driven (not feature-only). Examples: "Same-day install on most homes", "Insurance claim support", "20–30 year service life". Avoid: "We use the best materials" (vague), "High-quality professional service" (filler).

**Icon mapping:** Use icons appropriate to the service, inlined as raw `<svg>` from `references/lucide-icons/<name>.svg` at build time (v6.2 — never `data-lucide`, `createIcons`, or a Lucide CDN). Industry guidance (names map to the `.svg` files):
- Roofing: `home`, `shield`, `cloud-rain`, `wrench`, `hammer`, `hard-hat`, `building-2`
- Gutters: `ruler`, `droplets`, `filter`, `wrench`, `shield`, `building-2`, `sparkles`
- Lawn/Landscape: `leaf`, `scissors`, `sprout`, `flower-2`, `tree-pine`, `sun`
- Tree Service: `tree-pine`, `axe`, `chainsaw`, `shovel`, `wrench`
- HVAC: `thermometer`, `wind`, `flame`, `snowflake`, `wrench`, `zap`
- Plumbing: `droplets`, `wrench`, `pipe`, `shower-head`, `bath`
- Electrical: `zap`, `lightbulb`, `plug`, `bolt`, `wrench`
- Cleaning: `sparkles`, `spray-can`, `brush`, `wind`
- Generic: `check-circle`, `star`, `award`, `tool`

Each card's icon must be different from adjacent cards.

**Image rule:** Every card MUST have a real client photo. The build pipeline pre-stages photos in `/assets/images/`. Reference the AVAILABLE CLIENT IMAGES manifest. NO gradient placeholders, NO blank divs. If no client photo exists for a service, fall back to Unsplash Source API as last resort.

**Copy rules:**
- Card title: just the service name (no qualifiers like "Premium" or "Affordable")
- Description: 1 sentence, max 14 words, says what it actually is — not what it does for the customer's emotions
- Section heading: phrase as a CONVERSATIONAL QUESTION using customer-search language. Examples:
  - "What construction services does {company name} offer?"
  - "Which roofing services are available in {city}?"
  - "How does {company name} handle commercial HVAC needs?"
  - DO NOT use generic "Our Services" / "What We Do" / "Services Overview" — these are banned by aeo-content-schema.md §1.1
  - Two-tone treatment: highlight 1-3 keywords with <span class="text-accent">...</span> within the question
- Section eyebrow: "What We Do" (literal, do not vary)

**Required CSS variables (add to `:root` in styles.css):**

```css
:root {
  /* Tinted card backgrounds — recipe: brand colors at 6-9% alpha */
  --color-card-tint-1: rgba({primary-rgb}, 0.08);   /* primary brand color */
  --color-card-tint-2: rgba({primary-dark-rgb}, 0.06); /* darker primary */
  --color-card-tint-3: rgba({accent-rgb}, 0.09);    /* accent color */
  --color-card-tint-neutral: rgba(245, 247, 250, 1);
}
```

**Required CSS rules (add to styles.css — these class names are non-negotiable):**

```css
/* Tint utility classes */
.card-tint-1 { background: var(--color-card-tint-1); box-shadow: none; }
.card-tint-2 { background: var(--color-card-tint-2); box-shadow: none; }
.card-tint-3 { background: var(--color-card-tint-3); box-shadow: none; }
.card-tint-neutral { background: var(--color-card-tint-neutral); box-shadow: none; }

/* Services grid layout */
.services-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: var(--space-md);
}
/* ORPHAN RULE (v6.3): 5 or 9 cards leave a lone card on the last row. Add
   class="services-grid services-grid--featured" and data-featured-label="Most requested"
   on the FIRST card — it becomes a 2x2 tile and the rest fill a clean 2x2.
   The scaffold framework.css ships these rules; they are repeated here for reference. */
.services-grid--featured > :first-child { grid-column: span 2; grid-row: span 2; position: relative; }
.services-grid--featured > :first-child .service-card__image { aspect-ratio: auto; flex: 1 1 auto; min-height: 340px; }
.services-grid--featured > :first-child::after { content: attr(data-featured-label); position: absolute; top: var(--space-md); left: var(--space-md); z-index: 2; font-family: var(--font-accent); font-weight: 700; letter-spacing: 0.14em; text-transform: uppercase; color: var(--color-white); background: var(--color-accent); padding: 0.35rem 0.7rem; border-radius: var(--radius-sm); }
@media (max-width: 1199px) {
  .services-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 600px) {
  .services-grid { grid-template-columns: 1fr; }
}

/* Tinted image card */
.service-card-with-image {
  border-radius: var(--radius-md);
  overflow: hidden;
  display: flex;
  flex-direction: column;
  transition: transform var(--transition-base), box-shadow var(--transition-base);
}
.service-card-with-image:hover {
  transform: translateY(-4px);
  box-shadow: var(--shadow-md);
}
.service-card__image {
  position: relative;
  aspect-ratio: 5 / 3;
  overflow: hidden;
}
.service-card__image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
}
.service-card__body {
  padding: var(--space-lg) var(--space-md) var(--space-md);
  text-align: center;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: var(--space-sm);
  position: relative;
}
.service-card__icon {
  width: 56px;
  height: 56px;
  border-radius: 50%;
  background: #fff;
  box-shadow: var(--shadow-md);
  display: flex;
  align-items: center;
  justify-content: center;
  margin-top: -44px;  /* overlaps the image edge */
  margin-bottom: var(--space-xs);
  color: var(--color-accent);
}
.service-card__icon i,
.service-card__icon svg {
  width: 26px;
  height: 26px;
}
.service-card-with-image h3 {
  font-family: var(--font-heading);
  color: var(--color-primary);
  margin: 0;
  font-size: 1.35rem;
  line-height: 1.2;
}
.service-card__desc {
  color: var(--color-text);
  margin: 0;
  font-size: 0.95rem;
  line-height: 1.55;
}
.service-card-with-image ul {
  list-style: none;
  padding: 0;
  margin: var(--space-xs) 0 0;
  width: 100%;
  text-align: left;
  display: flex;
  flex-direction: column;
  gap: var(--space-xs);
  border-top: 1px solid rgba(0,0,0,0.06);
  padding-top: var(--space-md);
}
.service-card-with-image ul li {
  font-size: 0.9rem;
  color: var(--color-text);
  padding-left: 1.25rem;
  position: relative;
}
.service-card-with-image ul li::before {
  content: "•";
  color: var(--color-accent);
  font-weight: 700;
  position: absolute;
  left: 0.25rem;
  top: 0;
}
.service-card__cta {
  margin-top: auto;
  padding-top: var(--space-sm);
  color: var(--color-accent);
  font-weight: 600;
  text-decoration: none;
  font-size: 0.95rem;
  border-top: 1px solid rgba(0,0,0,0.06);
  width: 100%;
  text-align: center;
  padding: var(--space-sm) 0 0;
  transition: color var(--transition-base);
}
.service-card__cta::after {
  content: " →";
  display: inline-block;
  transition: transform var(--transition-base);
}
.service-card__cta:hover { color: var(--color-primary); }
.service-card__cta:hover::after { transform: translateX(3px); }
```

**Service count handling:**
- 3 services: render 3 cards in a 3-column row at desktop (CSS handles this — first row only fills what exists)
- 4 services: 4-up grid as specified
- 5-7 services: fills first row + partial second row
- 8 services: two clean rows of 4
- 9+ services: render the first 8 in the home grid, then below the grid render `<a href="/services/" class="btn-secondary">View All {N} Services →</a>`. Render ALL services on `/services/index.php`.

**Reuse on individual service pages (Related Services):**
At the bottom of each `/services/{slug}/index.php` page, render 3 randomly-selected OTHER services in the same `services-grid` + `service-card-with-image` pattern. Section heading: "Other Services You May Need". Use the same tint rotation rule.

**DO NOT (these auto-fail QA):**
- Use the legacy class names: `service-card`, `service-card-image`, `service-card-content`, `service-card-cta`
- Render service cards without the image-on-top pattern
- Use icon-only or gradient-only cards
- Use more than 3 bullets per card
- Use the same tint on adjacent cards
- Use stock photos when client photos exist for that service

---

### Verified Local Partner badge (REQUIRED — All Tiers, footer)

Every site we build carries the client's **Page One Partner** badge in the footer: our mark, "Verified Local Partner", the
business name and its live Google rating, linking to the client's own profile on pageonepartner.com (2026-09-17).

- **One file, never hand-written:** `includes/partner-badge.php`, canonical copy `~/crm/references/partner-badge.php`. The
  scaffold writes it with the client's profile slug and name filled in. Do not edit, restyle, wrap in a card, or add `rel`
  attributes to its link (it is a plain followed link, the same code an owner gets in the "profile claimed" email).
- **One include:** the LAST element inside `<footer>`, after `.footer-bottom-bar`:
  `<?php include __DIR__ . '/partner-badge.php'; ?>`
- **Self-gating:** it prints nothing while the profile is unclaimed (placeholders unfilled), and hides itself if the badge
  image ever 404s (profile hidden/unclaimed). So the include ships on every build, claimed or not.
- **After launch / after a claim:** `node ~/crm/scripts/partner-badge-fleet.mjs --apply --only <repo>` fills the slug, commits,
  deploys over SSH and verifies the badge is on the live homepage. The same script (no `--only`) reconciles the fleet.
- The image is 300×84 SVG rendered at 240px, `loading="lazy"`, explicit width/height (no CLS), served and cached by
  pageonepartner.com. It does not count against the JS budget (no script).
- QA: `qa_audit.py` → "Footer: Verified Local Partner badge include" (warning).

---

### Flagship components (REQUIRED on Flagship builds — v8, 2026-10-09)

Full specs, markup, config contracts, and QA rules: `conversion-components-v8.md`. These rows state what QA looks for and when each component may exist.

| Component | Include | Ships when | QA looks for |
|---|---|---|---|
| Instant estimate tool | `includes/estimate-tool.php`, `/estimate/` | Flagship AND `integrations.estimate_tool.authorized === true` (name, timestamp, method) | `data-estimate-tool` form, `#estimate-tool-data` JSON equal to intake pricing; present without authorization = BLOCKER; authorized but missing = warning |
| Book a time window | `includes/booking-window.php`, `/book/` | Flagship AND `integrations.booking.authorized === true` | `name="booking"` form with `preferred_date` + `time_window`; present without authorization = BLOCKER |
| Text-first contact | `includes/text-us.php` + sticky-bar third button | `integrations.accepts_sms === true` | `sms:` link to `integrations.sms_number` (the owner's own number); mismatch = BLOCKER; `sms:` link when texts not accepted = BLOCKER |
| Financing block | `includes/financing-block.php` | `integrations.financing.partner` + `url` present | block without a partner = BLOCKER; dollar figure without `example` = BLOCKER |
| Recent-work feed | `includes/recent-work.php` | GBP connected (same as reviews block) | include on home + service-areas hub (warning); no raw Google CDN image URLs (BLOCKER); `data-p1-dynamic` on the track |
| Serve-my-address checker | `includes/zip-check.php` | Flagship (Premium with 8+ city pages) | `data-zip-check` on the hub; `#zip-check-data` cities ⊆ intake service areas (BLOCKER on a city intake does not list) |
| Before/after sliders | existing `.ba-*` pattern | visual trades; Flagship ≥ 3 pairs | pairs from the image manifest only (stock = BLOCKER); no-JS fallback (existing check) |
| Spanish pages | `/es/...` mirror | `integrations.spanish === true` | hreflang pairs resolve, `lang="es"`, unique Spanish metas (BLOCKERS); reviewer recorded in launch report (warning) |
| Launch report | `/content/launch-report.html` + PDF | every Flagship launch | generated by the launch flow (BLOCKER there, not in qa_audit) |

**The authorization gate is the rule that overrides everything else in this table:** an estimator or booking form on a site with no matching authorization record in `build-plan.json` is a blocker regardless of tier, and nobody on staff may authorize on the client's behalf. Photo/video day is a vendor process (§8), not a component.

