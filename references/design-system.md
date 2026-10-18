# Design System — Page One Insights

Reference file for all client website builds. This document defines the visual vocabulary, CSS architecture, and premium technique library that every build uses.

**Read this file before writing any CSS.** The patterns here are the minimum bar for Standard tier and the expected bar for Premium tier. If a pattern is not used, the build should explain why a different approach achieves the same quality — not skip it.

---

## Part A — CSS Token Architecture

### The Rule

**Never hardcode a color, spacing value, shadow, radius, transition, or font size inline.** All values are tokens defined in `:root` and consumed via `var()`. This applies to all CSS — global (`styles.css`) and page-level (`<style>` blocks).

Violating this rule produces sites that drift visually across pages. Token consistency is what makes a build feel designed rather than assembled.

### Required Token Set

Every project's `:root` declares every token below. Values are brand-adjusted but the token names never change.

```css
:root {
  /* — Layout — */
  --navbar-height: 80px;
  --max-width: 1200px;
  --content-width: 65ch;
  --section-pad: 80px 20px;
  --section-pad-mobile: 50px 16px;

  /* — Radius — */
  --radius-sm: 4px;
  --radius-md: 8px;
  --radius-lg: 16px;
  --radius-xl: 24px;

  /* — Spacing Scale (8pt grid) — */
  --space-xs:  4px;
  --space-sm:  8px;
  --space-md:  16px;
  --space-lg:  24px;
  --space-xl:  32px;
  --space-2xl: 48px;
  --space-3xl: 64px;
  --space-4xl: 96px;

  /* — Color Tokens (replace hex with brand values) — */
  --color-primary:       #0a2540;
  --color-primary-dark:  #061a2d;
  --color-primary-rgb:   10, 37, 64;  /* legacy — see color-mix() note below */
  --color-secondary:     #0066ff;
  --color-accent:        #ffb800;
  --color-text:          #1a1a1a;
  --color-text-light:    #555555;
  --color-gray:          #777777;
  --color-gray-dark:     #333333;
  --color-bg:            #ffffff;
  --color-bg-alt:        #f7fafc;
  --color-bg-dark:       #0a2540;
  --color-light:         #f0f4f8;

  /* — Elevation (3 tiers — never use arbitrary box-shadow) — */
  --shadow-sm:  0 1px 3px rgba(0,0,0,0.08);
  --shadow-md:  0 4px 12px rgba(0,0,0,0.10);
  --shadow-lg:  0 8px 24px rgba(0,0,0,0.12);
  --shadow-xl:  0 16px 40px rgba(0,0,0,0.16);
  --shadow-card: 0 2px 8px rgba(0,0,0,0.06);

  /* — Typography (self-hosted variable fonts, v6.2 — set by the scaffold from build-plan design.fonts; NO Google Fonts CDN) — */
  --font-heading: 'Bricolage Grotesque', system-ui, sans-serif;
  --font-body:    'Figtree', system-ui, sans-serif;
  --font-size-xs:   0.75rem;
  --font-size-sm:   0.875rem;
  --font-size-base: 1rem;
  --font-size-lg:   1.125rem;
  --font-size-xl:   1.375rem;

  /* — Transitions — */
  --transition-fast:  0.15s ease;
  --transition-base:  0.25s ease;
  --transition-slow:  0.4s ease;
  --transition:       0.25s ease;  /* alias */
}
```

### Alpha variants: color-mix(), not -rgb duplication (v6.2)

New builds derive alpha/tint variants with `color-mix()` instead of maintaining a parallel `--color-*-rgb` token for `rgba()`:

```css
/* old (legacy — existing sites only):
   background: rgba(var(--color-primary-rgb), 0.12);            */

/* v6.2 standard: */
background: color-mix(in srgb, var(--color-primary) 12%, transparent);
border-color: color-mix(in srgb, var(--color-primary) 30%, white);
```

One source of truth per color; tints can't drift from the base token. `--color-primary-rgb` remains in the token set as **legacy** for existing sites — do not add new `-rgb` tokens.

### Spacing Scale Guidance

| Token | Use For |
|---|---|
| `--space-xs` (4px) | Gap between adjacent icons and text, fine-grained badge padding |
| `--space-sm` (8px) | Icon gaps in pills, small vertical rhythm between tight elements |
| `--space-md` (16px) | Standard gap between closely-related items, card internal padding bottom |
| `--space-lg` (24px) | Container padding, gap between cards in a grid, paragraph bottom-margin |
| `--space-xl` (32px) | Card internal padding, major content block separation |
| `--space-2xl` (48px) | Section header bottom-margin, significant vertical rhythm |
| `--space-3xl` (64px) | Between major page sections in prose, before closing CTAs |
| `--space-4xl` (96px) | Feature section breathing room, hero bottom-space |

**Do not use arbitrary pixel values** (e.g. `margin-bottom: 37px`). If the scale doesn't have what's needed, the design is wrong, not the scale.

### Elevation Tier Guidance

| Token | Use For |
|---|---|
| `--shadow-sm` | Static info cards, subtle containers, sticky elements at rest |
| `--shadow-md` | Cards at rest, form containers, non-floating elevated blocks |
| `--shadow-lg` | Hover states on cards, floating elements, modals |
| `--shadow-xl` | High-emphasis feature cards, overlapping composition badges, image showcases |
| `--shadow-card` | Default card shadow (alias to --shadow-sm-ish for semantic clarity) |

**Never invent box-shadow values.** If a design calls for a drop shadow, pick the tier — don't write `box-shadow: 0 7px 19px rgba(0,0,0,0.11)`.

### Typography Scale

Headings use `clamp()` — always. This ensures type scales fluidly from mobile to desktop without breakpoint-specific overrides.

```css
h1 { font-size: clamp(2.4rem, 6vw, 4.5rem); line-height: 1.1;  letter-spacing: -0.025em; }
h2 { font-size: clamp(1.8rem, 4vw, 2.8rem); line-height: 1.15; letter-spacing: -0.02em; }
h3 { font-size: clamp(1.2rem, 2.5vw, 1.6rem); line-height: 1.25; letter-spacing: -0.01em; }
h4 { font-size: 1.05rem; line-height: 1.3; }
```

**Every heading uses `text-wrap: balance;`** — this is the single highest-leverage typographic property available. It prevents orphan words and produces professionally-set headlines without any extra work.

**Body text uses `text-wrap: pretty;`** (v6.2) — set it on `p, li` (or `.prose`). It kills orphan words in paragraphs the way `balance` does for headings, at negligible cost. `balance` stays headings-only (it's expensive on long text).

Body type is a fixed `16px` minimum (`1rem`), line-height `1.6`, max-width `65ch` via `.prose`.

### Accent Face (v6.3 — 2026-08-29, owner-approved on salt-river-steel-llc)

The third font is a **condensed industrial grotesk** by default (Barlow Condensed 700, self-hosted), not a script. It lives in the *chrome* of the page — `.eyebrow-label`, `.section-subtitle`, proof-strip `.stat-number`, gallery tags, featured badges — always uppercase with 0.14–0.18em tracking. It is **never mixed mid-sentence into a heading**: `<span class="text-accent">` emphasis words keep the heading face and take only the accent color. A cursive accent read as a template tell across every trade site; the condensed face reads as signage.

Script/italic accents (Caveat, Instrument Serif italic) are opt-in via `build-plan.design.fonts.accent` for the Warm/Human and Rustic archetypes when the logo itself is script. When a script face is used, the old budget still applies: **~5 uses on the homepage, 1 per inner page**.

---

## Part B — Logo-Driven Design Process

Before a single color or font is chosen, analyze the client's logo. The logo dictates:

1. **Primary color palette** — primary color extracted from dominant logo color, secondary from supporting color, accent from contrast element
2. **Typography direction** — the logo's typographic character suggests the heading font pairing (serif logo → serif or humanist heading; geometric sans logo → geometric sans heading; script logo → pairs with a strong sans)
3. **Visual vocabulary archetype** — the logo's era, weight, and styling point to one of the archetypes in Part D
4. **Navbar background treatment** — nav background color is chosen to match the logo's edge/background color so the logo feels integrated rather than pasted on

### Logo Analysis Checklist

Before opening any CSS file:

- [ ] What are the 2-3 dominant colors? (Extract exact hex values.)
- [ ] Is the logo **geometric** (hard edges, calculated), **organic** (curves, handmade), or **hybrid**?
- [ ] Is it **vintage/heritage** (established, traditional) or **modern/contemporary** (new, innovative)?
- [ ] Is the typography **serif** (formal, editorial), **sans-serif** (clean, modern), **display** (bold, distinctive), or **script/handwritten** (approachable, custom)?
- [ ] What's the aspect ratio? (Wide horizontal logos size differently in nav than square/stacked ones.)
- [ ] What's the logo's background assumption — does it live on light or dark, or does it work on both?

### From Logo to Design

| Logo Signal | Design Direction |
|---|---|
| Geometric + bold + modern sans | Minimalist/Tech archetype — monochrome palette + single accent, precise spacing, strong hierarchy |
| Organic + earth tones + script/handwritten | Rustic/Handmade archetype — warm palette, textured backgrounds, organic dividers |
| Heavy/industrial + saturated + display | Bold/Industrial archetype — compressed type, gritty textures, heavy shadows |
| Elegant + serif + muted | Editorial/Premium archetype — restrained palette, generous whitespace, strong typography |
| Rounded + warm tones + approachable | Warm/Human archetype — soft gradients, rounded elements, friendly typography |

Archetypes are explained fully in Part D.

### Navbar Background from Logo

**Rule 0 — cut boxed logos first (v6.3).** A logo delivered on an opaque rectangle (black square, white card) must be cut to transparency before anything else: `magick logo.png -fuzz 15% -transparent "<bg>" -trim +repage -background none -gravity center -extent 105%x105% PNG32:logo-mark.png`, then *look at it* over the nav color. A boxed logo inside a bar is the single biggest "placeholder" tell. Matching the bar chrome to the box color is the fallback for the rare logo the cut destroys (gradient background, mark color ≈ background).

Match the nav's scrolled-state background to the logo's implied background so the logo floats naturally. Three patterns:

1. **Dark logo, light background** → nav uses `rgba(255,255,255,0.95)` on scroll with backdrop-filter
2. **Light/white logo, dark background** → nav uses `rgba(var(--color-primary-rgb), 0.88)` on scroll
3. **Full-color logo, adaptive** → nav starts transparent over hero, transitions to `rgba(var(--color-primary-rgb), 0.88)` on scroll

Never let the logo's implicit background collide with the nav color. This one detail is the biggest "feels off" signal on an otherwise-polished site.

---

## Part C — Premium Visual Technique Library

This is the core of the design system. Every build consults this library and selects techniques appropriate for the brand. The techniques are grouped by purpose.

**Enforcement bar:**

| Tier | Minimum techniques per page | Minimum custom CSS per page |
|---|---|---|
| **Premium** | 6 distinct techniques from the library | 400+ lines inline `<style>` per page |
| **Standard** | 4 distinct techniques | 200+ lines inline `<style>` per page |
| **Basic** | 2 distinct techniques | 100+ lines inline `<style>` per page |

A page with zero inline `<style>` blocks is an automatic fail at any tier. Page-specific CSS lives in `<style>` blocks within each PHP file so each page gets genuinely unique visual treatment — not copy-pasted layouts.

---

### C1. Hero Treatments

**Purpose:** First impression. The hero determines whether the site feels premium or generic within 500ms of load.

Every hero uses the **layered depth pattern** — background image, gradient overlay pseudo-element, noise texture pseudo-element, content on top.

**Full-height heroes use `min-height: 100svh` with a `100vh` fallback line above it — never bare `100vh`** (v6.2). On mobile, `100vh` includes the collapsed URL bar, so the hero's bottom edge (and the CTA in it) sits below the fold; `svh` measures the *small* viewport. The fallback pair pattern in C1.1 applies to every full-height hero variant.

#### Pattern 1.1 — Full-Bleed Ken Burns Hero (homepage default)

```css
.hero-home {
  min-height: 100vh;   /* fallback for browsers without svh units */
  min-height: 100svh;  /* v6.2 — excludes the mobile URL bar */
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
  overflow: hidden;
  background-image: url('HERO_IMAGE_URL');
  background-size: 110%;
  background-position: center 30%;
  animation: kenburns-hero 22s ease-in-out infinite alternate;
}

@keyframes kenburns-hero {
  from { background-size: 110%; background-position: center 25%; }
  to   { background-size: 122%; background-position: center 40%; }
}

/* Brand-tinted gradient overlay */
.hero-home::before {
  content: '';
  position: absolute;
  inset: 0;
  background: linear-gradient(
    150deg,
    rgba(var(--color-primary-rgb), 0.92) 0%,
    rgba(var(--color-primary-rgb), 0.78) 55%,
    rgba(var(--color-accent-rgb), 0.12) 100%
  );
  z-index: 1;
}

/* SVG noise texture (film-grain depth) */
.hero-home::after {
  content: '';
  position: absolute;
  inset: 0;
  background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.04'/%3E%3C/svg%3E");
  z-index: 1;
  pointer-events: none;
}

.hero-inner {
  position: relative;
  z-index: 2;
  width: 100%;
  padding: 100px 0 60px;
}
```

**Use when:** Industry rewards visual impact (home services, hospitality, outdoor, construction). Images are strong and brand-appropriate.

**Don't use when:** Client has no strong hero imagery (logo-forward or typography-forward hero works better).

**Variations:**
- **3-stop gradient** (dark → mid → accent-tint) for warm feel
- **2-stop diagonal** (primary-dark → primary) for corporate
- **Vertical gradient** (`180deg`) for bottom-weighted CTAs
- **Radial gradient overlay** instead of linear — radial ellipse at 30% 30% for off-center focal point

#### Pattern 1.2 — Split Hero (image + content)

```css
.hero-split {
  min-height: 90vh;
  display: grid;
  grid-template-columns: 1.1fr 1fr;
  align-items: stretch;
  position: relative;
  overflow: hidden;
}
.hero-split-content {
  padding: var(--space-4xl) var(--space-2xl);
  background: var(--color-primary);
  color: #fff;
  display: flex;
  flex-direction: column;
  justify-content: center;
}
.hero-split-image {
  background-image: url('HERO_IMAGE_URL');
  background-size: cover;
  background-position: center;
  position: relative;
}
.hero-split-image::after {
  content: '';
  position: absolute;
  inset: 0;
  background: linear-gradient(to right, rgba(var(--color-primary-rgb), 0.35) 0%, transparent 30%);
}
@media (max-width: 767px) {
  .hero-split { grid-template-columns: 1fr; min-height: auto; }
  .hero-split-image { min-height: 50vh; }
}
```

**Use when:** Brand is content-heavy, benefits from prominent copy placement, or has a single hero image worth featuring large.

#### Pattern 1.3 — Typography-Forward Hero (minimal imagery)

```css
.hero-editorial {
  min-height: 80vh;
  background: var(--color-bg);
  display: flex;
  align-items: center;
  padding: var(--space-4xl) 0;
  position: relative;
  overflow: hidden;
}
.hero-editorial::before {
  content: '';
  position: absolute;
  top: -10%; right: -10%;
  width: 60%; height: 120%;
  background: radial-gradient(circle, rgba(var(--color-accent-rgb), 0.15) 0%, transparent 70%);
  pointer-events: none;
}
.hero-editorial h1 {
  font-size: clamp(3rem, 8vw, 7rem);
  font-weight: 900;
  line-height: 0.95;
  letter-spacing: -0.04em;
  text-wrap: balance;
  max-width: 14ch;
}
```

**Use when:** Brand is editorial/premium, words carry more weight than images (legal, financial, consulting, high-end services).

#### Pattern 1.4 — Page Hero (inner pages — reduced height)

All inner pages use a reduced-height variant of the hero pattern:

```css
.page-hero {
  position: relative;
  min-height: 52vh;
  display: flex;
  align-items: center;
  background-image: url('PAGE_HERO_IMAGE');
  background-size: cover;
  background-position: center;
  overflow: hidden;
}
.page-hero::before {
  content: '';
  position: absolute;
  inset: 0;
  background: linear-gradient(135deg, rgba(var(--color-primary-rgb), 0.85) 0%, rgba(var(--color-primary-dark-rgb), 0.72) 100%);
  z-index: 1;
}
.page-hero::after {
  content: '';
  position: absolute;
  inset: 0;
  background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='1'/%3E%3C/svg%3E");
  opacity: 0.04;
  z-index: 1;
  pointer-events: none;
}
.page-hero-content { position: relative; z-index: 2; color: #fff; width: 100%; }
```

Inner pages use this pattern **always**. Never reuse the homepage hero on inner pages — they feel identical and hurt the homepage's impact.

---

### C2. Hero Content Composition

The hero's content area needs structure, not just a headline. Use staggered entrance animation for cascading reveal:

```css
.hero-eyebrow-badge {
  display: inline-flex;
  align-items: center;
  gap: var(--space-sm);
  background: rgba(var(--color-accent-rgb), 0.12);
  border: 1px solid rgba(var(--color-accent-rgb), 0.35);
  border-radius: 999px;
  padding: 6px 18px;
  font-family: var(--font-heading);
  font-size: 0.7rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 2.5px;
  color: var(--color-accent);
  margin-bottom: var(--space-2xl);
  animation: fadeSlideDown 0.65s ease both;
}

.hero-title {
  font-size: clamp(2.4rem, 6vw, 4.5rem);
  font-weight: 800;
  line-height: 1.1;
  letter-spacing: -0.025em;
  color: #fff;
  text-wrap: balance;
  margin-bottom: var(--space-xl);
  animation: fadeSlideUp 0.65s ease 0.12s both;
}

/* Gradient text on accent word */
.hero-title .gradient-text {
  background: linear-gradient(135deg, #ffffff 0%, var(--color-accent) 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.hero-subtitle { animation: fadeSlideUp 0.65s ease 0.25s both; }
.hero-actions  { animation: fadeSlideUp 0.65s ease 0.38s both; }
.hero-trust-row { animation: fadeSlideUp 0.65s ease 0.50s both; }

@keyframes fadeSlideDown {
  from { opacity: 0; transform: translateY(-14px); }
  to   { opacity: 1; transform: translateY(0); }
}
@keyframes fadeSlideUp {
  from { opacity: 0; transform: translateY(18px); }
  to   { opacity: 1; transform: translateY(0); }
}
```

**Cascade timing:** badge (0s) → title (0.12s) → subtitle (0.25s) → actions (0.38s) → trust row (0.50s). This produces cinematic entrance without JS.

---

### C3. Section Dividers

Every section boundary uses a divider. Never leave two sections flush against each other with a plain color change — that reads as unfinished. A page needs **at least two different divider styles**; the cheapest second style is the scaffold's `.slant-top` / `.slant-bottom` clip-path (v6.3) on a dark band — no SVG, no extra markup, and it pairs with a wave elsewhere on the page.

#### Divider 3.0 — Slanted edge (scaffold class, v6.3)

```html
<section class="stats-band texture-grain slant-top">…</section>
```
```css
.slant-top { clip-path: polygon(0 3vw, 100% 0, 100% 100%, 0 100%); padding-top: calc(clamp(4rem, 10vh, 8rem) + 3vw); }
```

#### Divider 3.1 — Diagonal

```html
<div class="svg-divider">
  <svg viewBox="0 0 1200 60" preserveAspectRatio="none">
    <polygon fill="var(--color-bg-alt)" points="0,0 1200,60 1200,60 0,60"/>
  </svg>
</div>
```

```css
.svg-divider {
  display: block;
  overflow: hidden;
  line-height: 0;
  height: 60px;
}
.svg-divider svg { display: block; width: 100%; height: 100%; }
```

#### Divider 3.2 — Curved Wave

```html
<svg viewBox="0 0 1200 80" preserveAspectRatio="none">
  <path d="M0,40 C300,80 900,0 1200,40 L1200,80 L0,80 Z" fill="var(--color-bg-alt)"/>
</svg>
```

#### Divider 3.3 — Double Wave (premium feel)

```html
<svg viewBox="0 0 1200 100" preserveAspectRatio="none">
  <path d="M0,30 C300,70 900,10 1200,40 L1200,100 L0,100 Z" fill="var(--color-bg-alt)" opacity="0.4"/>
  <path d="M0,50 C300,90 900,20 1200,60 L1200,100 L0,100 Z" fill="var(--color-bg-alt)"/>
</svg>
```

#### Divider 3.4 — Torn Paper / Organic Edge

```html
<svg viewBox="0 0 1200 60" preserveAspectRatio="none">
  <path d="M0,60 L0,40 L60,42 L120,35 L200,45 L280,32 L360,48 L440,38 L540,45 L640,30 L740,42 L840,35 L940,45 L1040,32 L1140,42 L1200,38 L1200,60 Z" fill="var(--color-bg-alt)"/>
</svg>
```

#### Divider 3.5 — Stacked Parallelograms (geometric feel)

```html
<svg viewBox="0 0 1200 80" preserveAspectRatio="none">
  <polygon fill="var(--color-bg-alt)" opacity="0.3" points="0,20 1200,40 1200,80 0,80"/>
  <polygon fill="var(--color-bg-alt)" points="0,40 1200,20 1200,80 0,80"/>
</svg>
```

**Divider selection rule:** never use the same divider style twice in a row on the same page. Rotate through at least 3 variants per page.

---

### C4. Gradient & Overlay Patterns

#### 4.1 — Radial Gradient Glow on Dark Sections

Add depth to dark-background sections with a subtle accent glow:

```css
.stats-band {
  background: var(--color-primary);
  position: relative;
  overflow: hidden;
  padding: var(--space-4xl) 0;
}
.stats-band::before {
  content: '';
  position: absolute;
  inset: 0;
  background: radial-gradient(ellipse at 50% 50%, rgba(var(--color-accent-rgb), 0.14) 0%, transparent 70%);
  pointer-events: none;
}
```

Variations by focal point:
- `at 30% 50%` — left-weighted asymmetric glow
- `at 50% 0%` — top-arc glow (used on closing CTAs)
- `at 20% 100%` — bottom-left anchor glow

#### 4.2 — Diagonal Gradient CTA Banner

```css
.cta-mid {
  position: relative;
  overflow: hidden;
  padding: var(--space-4xl) 0;
  background: linear-gradient(135deg, var(--color-primary-dark) 0%, var(--color-primary) 55%, var(--color-secondary) 100%);
  text-align: center;
}
.cta-mid::before {
  content: '';
  position: absolute;
  inset: 0;
  background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='1'/%3E%3C/svg%3E");
  opacity: 0.06;
}
```

3-stop gradients feel more premium than 2-stop. The middle stop at 55% creates a subtle color fold rather than a linear transition.

#### 4.3 — Duotone Image Treatment

For team photos and trust-building imagery:

```css
.team-photo-wrap img {
  filter: saturate(0.85) sepia(0.1);
}
.team-photo-wrap::after {
  content: '';
  position: absolute;
  inset: 0;
  background: linear-gradient(to top, rgba(var(--color-primary-rgb), 0.45) 0%, transparent 60%);
}
```

Produces branded, editorial-feeling photos without destroying the subject's recognizability.

---

### C5. Typography Treatments

#### 5.1 — Numbered Section Eyebrows

Add journal/chapter feeling to homepage sections:

```css
.section-num {
  display: inline-block;
  font-family: var(--font-heading);
  font-size: 0.7rem;
  font-weight: 700;
  letter-spacing: 3px;
  text-transform: uppercase;
  color: var(--color-accent);
}
```

```html
<div class="section-num">01 / Our Services</div>
<h2>What We Do</h2>
```

#### 5.2 — Eyebrow Badge Variants

Three distinct treatments for different contexts:

**Subtle tinted pill (hero, dark backgrounds):**
```css
.eyebrow-pill {
  display: inline-flex;
  gap: var(--space-sm);
  background: rgba(var(--color-accent-rgb), 0.12);
  border: 1px solid rgba(var(--color-accent-rgb), 0.35);
  border-radius: 999px;
  padding: 6px 18px;
  color: var(--color-accent);
  font-size: 0.7rem;
  letter-spacing: 2.5px;
  text-transform: uppercase;
}
```

**Solid accent pill (light backgrounds, section headers):**
```css
.eyebrow-solid {
  display: inline-block;
  background: var(--color-accent);
  color: var(--color-primary-dark);
  font-size: 0.72rem;
  font-weight: 700;
  letter-spacing: 0.12em;
  text-transform: uppercase;
  padding: var(--space-xs) var(--space-md);
  border-radius: 999px;
  margin-bottom: var(--space-md);
}
```

**Minimal label (editorial feel):**
```css
.eyebrow-minimal {
  display: inline-block;
  color: var(--color-accent);
  font-size: 0.75rem;
  font-weight: 700;
  letter-spacing: 0.18em;
  text-transform: uppercase;
  padding-bottom: var(--space-xs);
  border-bottom: 2px solid var(--color-accent);
  margin-bottom: var(--space-md);
}
```

#### 5.3 — Gradient Text Accent

Reserve for the single most important word on the page:

```css
.gradient-text {
  background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-accent) 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}
```

Applied to a single word or short phrase within the H1. Never to entire paragraphs.

#### 5.4 — Editorial Drop Cap

For long-form About pages or service descriptions:

```css
.drop-cap::first-letter {
  float: left;
  font-family: var(--font-heading);
  font-size: 4.5rem;
  font-weight: 900;
  line-height: 1;
  padding: 4px 10px 0 0;
  color: var(--color-primary);
}
```

#### 5.5 — Balance Everywhere

**Every heading on every page** applies `text-wrap: balance`. This is non-negotiable — it's one line of CSS per heading selector and it fundamentally upgrades every site's typographic feel.

---

### C6. Card & Grid Patterns

#### 6.1 — Asymmetric Featured Grid (homepage services)

One dominant card + supporting cards:

```css
.services-grid-home {
  display: grid;
  grid-template-columns: 2fr 1fr 1fr;
  gap: var(--space-lg);
}
.service-card-featured {
  grid-row: span 2;
  background: linear-gradient(155deg, var(--color-primary) 0%, var(--color-primary-dark) 100%);
  color: #fff;
  border-radius: var(--radius-lg);
  padding: var(--space-2xl);
  position: relative;
  overflow: hidden;
  min-height: 420px;
  box-shadow: var(--shadow-xl);
  transition: transform var(--transition-base);
}
.service-card-featured:hover { transform: translateY(-5px); }

/* Decorative accent corner — subtle branded depth */
.service-card-featured::before {
  content: '';
  position: absolute;
  top: -80px; right: -80px;
  width: 220px; height: 220px;
  border-radius: 50%;
  background: var(--color-accent);
  opacity: 0.07;
}

.service-card-std {
  background: #fff;
  border-radius: var(--radius-lg);
  padding: var(--space-lg);
  box-shadow: var(--shadow-card);
  transition: all var(--transition-base);
  border-top: 3px solid transparent;
}
.service-card-std:hover {
  transform: translateY(-4px);
  box-shadow: var(--shadow-xl);
  border-top-color: var(--color-accent);
}

@media (max-width: 1023px) {
  .services-grid-home { grid-template-columns: 1fr 1fr; }
  .service-card-featured { grid-row: span 1; grid-column: 1 / -1; }
}
@media (max-width: 767px) {
  .services-grid-home { grid-template-columns: 1fr; }
}
```

**The featured card gets visual prominence** — gradient bg, larger padding, decorative corner accent, spanning 2 rows. Supporting cards are simple, white, accent border on hover.

#### 6.2 — Overlapping Composition (About split)

Image + text split where a stat badge overlaps the image boundary:

```css
.about-split {
  display: grid;
  grid-template-columns: 1fr 1.3fr;
  gap: var(--space-4xl);
  align-items: start;
}
.about-image-wrap {
  position: relative;
  padding: 0 var(--space-lg) var(--space-lg) 0;  /* reserves space for overlapping badge */
}
.about-img {
  border-radius: var(--radius-lg);
  width: 100%;
  aspect-ratio: 4/5;
  object-fit: cover;
}
.about-stat-badge {
  position: absolute;
  bottom: 0;
  right: 0;
  background: var(--color-accent);
  color: #fff;
  padding: var(--space-xl) var(--space-2xl);
  border-radius: var(--radius-lg);
  box-shadow: var(--shadow-xl);
  text-align: center;
  min-width: 150px;
}
.about-stat-badge .big-year {
  display: block;
  font-family: var(--font-heading);
  font-size: 3rem;
  font-weight: 900;
  line-height: 1;
}
.about-stat-badge .year-label {
  display: block;
  font-size: 0.65rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 2px;
  opacity: 0.88;
  margin-top: 6px;
}
```

**The overlap is the technique.** The stat badge breaks the image's bounding box, creating the sense that the layout is composed rather than stacked.

#### 6.3 — Stats Band with Internal Dividers

```css
.stats-row {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: var(--space-md);
  text-align: center;
}
.stat-block {
  padding: var(--space-md);
  border-right: 1px solid rgba(255,255,255,0.1);
}
.stat-block:last-child { border-right: none; }
.stat-number-large {
  font-family: var(--font-heading);
  font-size: clamp(2.4rem, 5vw, 3.4rem);
  font-weight: 900;
  color: #fff;
  line-height: 1;
}
.stat-number-large .accent { color: var(--color-accent); }
.stat-label-text {
  font-size: 0.72rem;
  text-transform: uppercase;
  letter-spacing: 1.5px;
  color: rgba(255,255,255,0.55);
  margin-top: var(--space-sm);
}

/* Responsive — handle 2x2 grid keeping the right-border pattern */
@media (max-width: 1023px) {
  .stats-row { grid-template-columns: repeat(2, 1fr); }
  .stat-block {
    border-right: none;
    border-bottom: 1px solid rgba(255,255,255,0.08);
    padding-bottom: var(--space-lg);
  }
  .stat-block:nth-child(odd) { border-right: 1px solid rgba(255,255,255,0.08); }
  .stat-block:nth-last-child(-n+2) { border-bottom: none; }
}
```

#### 6.4 — Glassmorphism Cards (dark sections)

For testimonial cards on dark backgrounds:

```css
.glass-card {
  background: rgba(255,255,255,0.05);
  backdrop-filter: blur(8px);
  -webkit-backdrop-filter: blur(8px);
  border: 1px solid rgba(255,255,255,0.09);
  border-radius: var(--radius-lg);
  padding: var(--space-lg);
}
```

#### 6.5 — Process Steps with Numbered Circles

```css
.process-steps {
  display: flex;
  flex-direction: column;
  gap: var(--space-md);
}
.process-step {
  display: flex;
  align-items: flex-start;
  gap: var(--space-lg);
  padding: var(--space-md);
  background: var(--color-light);
  border-radius: var(--radius-md);
  transition: background var(--transition-fast), box-shadow var(--transition-fast);
}
.process-step:hover {
  background: rgba(var(--color-accent-rgb), 0.06);
  box-shadow: var(--shadow-sm);
}
.step-num {
  flex-shrink: 0;
  width: 38px; height: 38px;
  background: var(--color-primary);
  color: #fff;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-family: var(--font-heading);
  font-weight: 800;
  font-size: 0.8rem;
}
```

---

### C7. Signature Sections (Layout-Unique Moments)

Every page includes **one signature section** — a layout pattern that's not repeated elsewhere on that page. The signature section is where visual work concentrates.

#### 7.0 — Recent-Work Gallery (homepage default when ≥ 8 client photos, v6.3)

For a trade, "look at the work" *is* the premium signal. A full-bleed horizontal scroll-snap strip of 10–12 real job photos with a service tag + one-line caption each, every 3rd–4th tile wider, over a light section with two faint floating rings. Scaffold classes: `.gallery-track` (scroll-snap, hidden scrollbar, keyboard-focusable), `.gallery-item` / `.gallery-item--wide`, `figcaption > .gallery-item__tag + .gallery-item__cap`, `.floating-ring`. Driven by a `$galleryItems` array; `data-p1-dynamic` on the track. Service pages get the 3-photo `.sp-gallery-grid` (one tall tile + two stacked) before the closing CTA. Captions describe what is *in* the photo — never a city or client the manifest does not name.

Photos in the gallery (and everywhere else on the site) should share one color grade so phone shots taken months apart read as one set — recipe in C11.

#### 7.1 — Alternating Timeline

For About pages with company history or milestones:

```css
.timeline { position: relative; max-width: 900px; margin: 0 auto; }
.timeline::before {
  content: '';
  position: absolute;
  left: 50%;
  transform: translateX(-50%);
  top: 0; bottom: 0;
  width: 2px;
  background: linear-gradient(to bottom, var(--color-accent), var(--color-primary-dark));
}
.timeline-item {
  display: grid;
  grid-template-columns: 1fr auto 1fr;
  align-items: center;
  gap: var(--space-xl);
  margin-bottom: var(--space-3xl);
}
.timeline-item:nth-child(odd)  .timeline-card  { grid-column: 1; text-align: right; border-right: 4px solid var(--color-accent); }
.timeline-item:nth-child(even) .timeline-card  { grid-column: 3; text-align: left;  border-left:  4px solid var(--color-accent); }
.timeline-item:nth-child(odd)  .timeline-empty { grid-column: 3; }
.timeline-item:nth-child(even) .timeline-empty { grid-column: 1; }
.timeline-node {
  width: 48px; height: 48px;
  background: var(--color-primary);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  box-shadow: 0 0 0 6px rgba(var(--color-primary-rgb), 0.15);
  grid-column: 2;
  position: relative;
  z-index: 2;
}
.timeline-card {
  background: white;
  border-radius: var(--radius-md);
  padding: var(--space-xl);
  box-shadow: var(--shadow-md);
}
.timeline-year {
  font-family: var(--font-heading);
  font-size: 1.8rem;
  font-weight: 800;
  color: var(--color-primary);
  display: block;
  margin-bottom: var(--space-xs);
}

/* Mobile: collapse to left-aligned single column */
@media (max-width: 767px) {
  .timeline::before { display: none; }
  .timeline-item { grid-template-columns: 1fr; gap: var(--space-md); }
  .timeline-item:nth-child(odd)  .timeline-card,
  .timeline-item:nth-child(even) .timeline-card {
    grid-column: 1;
    text-align: left;
    border-left: 4px solid var(--color-accent);
    border-right: none;
  }
}
```

#### 7.2 — Full-Bleed Photo with Overlay Stat Counters

Image fills section, large stat numbers overlay the image:

```css
.photo-stats-section {
  position: relative;
  min-height: 500px;
  background-image: url('SECTION_IMAGE');
  background-size: cover;
  background-position: center;
}
.photo-stats-section::before {
  content: '';
  position: absolute;
  inset: 0;
  background: linear-gradient(rgba(var(--color-primary-rgb),0.7), rgba(var(--color-primary-rgb),0.85));
}
.photo-stats-grid {
  position: relative;
  z-index: 2;
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  padding: var(--space-4xl) 0;
  color: #fff;
}
```

#### 7.3 — Editorial Pull Quote

```css
.pull-quote {
  max-width: 800px;
  margin: var(--space-4xl) auto;
  padding: var(--space-xl) 0;
  border-top: 3px solid var(--color-accent);
  border-bottom: 3px solid var(--color-accent);
  font-family: var(--font-heading);
  font-size: clamp(1.5rem, 3vw, 2.2rem);
  font-weight: 500;
  line-height: 1.3;
  letter-spacing: -0.01em;
  text-align: center;
  color: var(--color-text);
  font-style: italic;
}
.pull-quote-cite {
  display: block;
  margin-top: var(--space-lg);
  font-size: 0.85rem;
  letter-spacing: 0.15em;
  text-transform: uppercase;
  font-style: normal;
  color: var(--color-text-light);
}
```

#### 7.4 — Staggered Card Layout (alternating image/content)

Rows alternate image-left/image-right for visual rhythm:

```css
.stagger-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: var(--space-3xl);
  align-items: center;
  margin-bottom: var(--space-4xl);
}
.stagger-row:nth-child(even) .stagger-image { order: 2; }
.stagger-row:nth-child(even) .stagger-text  { order: 1; }
```

---

### C8. Ticker Strip (Proof/Trust)

> **Proof strip, not stat counters (v6.3).** The animated "4 numbers" band is only allowed when every number is sourced from intake. The default is a *proof strip*: four verifiable facts typeset in the accent face — `Est. 2022 / Founded in Florence`, `In-House / Cut, welded & finished`, `Pinal County / Delivered & installed locally`, plus a real turnaround, license number or GBP review count when intake supplies one. "Projects completed (estimate)", made-up percentages and unsourced ratings are banned — a stat we cannot source is a stat we do not show. Sits on a `.texture-grain.slant-top` dark band.

Pure-CSS infinite horizontal scroll. Placed between hero and first content section. Pauses on hover.

```css
.ticker-outer {
  background: var(--color-primary);
  overflow: hidden;
  padding: 13px 0;
  border-top: 2px solid var(--color-accent);
  border-bottom: 2px solid rgba(var(--color-accent-rgb), 0.18);
}
.ticker-track-inner {
  display: flex;
  white-space: nowrap;
  animation: ticker-scroll 40s linear infinite;
}
.ticker-track-inner:hover { animation-play-state: paused; }
.ticker-item {
  display: inline-flex;
  align-items: center;
  gap: var(--space-sm);
  padding: 0 var(--space-2xl);
  font-family: var(--font-heading);
  font-size: 0.72rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.1em;
  color: rgba(255,255,255,0.88);
  flex-shrink: 0;
}
.ticker-sep { color: var(--color-accent); font-size: 1rem; }
@keyframes ticker-scroll {
  from { transform: translateX(0); }
  to   { transform: translateX(-50%); }
}
```

Content must be duplicated twice in markup so the `-50%` translate creates a seamless loop.

---

### C9. CTA Banner Patterns

#### 9.1 — Mid-Page CTA with Gradient + Noise

```css
.cta-mid {
  position: relative;
  overflow: hidden;
  padding: var(--space-4xl) 0;
  background: linear-gradient(135deg, var(--color-primary-dark) 0%, var(--color-primary) 55%, var(--color-secondary) 100%);
  text-align: center;
}
.cta-mid::before {
  /* SVG noise overlay — as defined in 4.2 */
}
.cta-phone-display {
  display: block;
  font-family: var(--font-heading);
  font-size: clamp(1.8rem, 4vw, 2.4rem);
  font-weight: 800;
  color: var(--color-accent);
  margin-bottom: var(--space-xl);
  letter-spacing: -0.02em;
  transition: color var(--transition-fast);
}
.cta-phone-display:hover { color: #fff; }
```

#### 9.2 — Closing CTA with Radial Glow

```css
.closing-cta {
  background: var(--color-primary);
  position: relative;
  overflow: hidden;
  text-align: center;
  padding: var(--space-4xl) 0;
}
.closing-cta::before {
  content: '';
  position: absolute;
  inset: 0;
  background: radial-gradient(ellipse at 50% 0%, rgba(var(--color-accent-rgb), 0.22) 0%, transparent 65%);
}
```

The `ellipse at 50% 0%` places the glow at the top of the section, creating an arc of light that draws the eye downward into the CTA.

---

### C10. Micro-interactions

#### 10.1 — Card Tilt with Brand Glare

Apply the tilt effect to cards with brand-specific glare:

```javascript
VanillaTilt.init(document.querySelectorAll(".card"), {
  max: 8,
  speed: 400,
  glare: true,
  "max-glare": 0.15
});
```

**v6.2: no vanilla-tilt CDN.** If this technique is used, inline a ~30-line equivalent in the page's script block (mousemove → `rotateX/rotateY` transform + a glare overlay div). See `performance-2026.md` Part C.

Visual restraint rule: counts as a major effect. Don't stack with typed.js, magnetic, parallax on the same page.

#### 10.0 — Mobile menu & lightboxes: native `<dialog>` (v6.2 standard)

The mobile nav drawer and any lightbox/modal use the native `<dialog>` element with `showModal()` — it ships focus trapping, `Esc`-to-close, and `::backdrop` for free, replacing the manual `overflow: hidden` body-lock JS pattern:

```html
<dialog id="mobile-menu" class="nav-drawer"> ... </dialog>
<script>
  const menu = document.getElementById('mobile-menu');
  document.querySelector('.nav-toggle').addEventListener('click', () => menu.showModal());
  menu.addEventListener('click', (e) => { if (e.target === menu) menu.close(); });
</script>
```

Style open-state via `dialog[open]` and the overlay via `dialog::backdrop`. Scroll-locking the page behind it: `body:has(dialog[open]) { overflow: hidden; }` in CSS — still no JS class juggling.

#### 10.2 — Magnetic CTA

Applied to hero primary CTA only. Desktop only (touch devices skip).

```javascript
const magneticBtn = document.querySelector('.btn-magnetic');
if (magneticBtn && window.matchMedia('(hover: hover)').matches) {
  magneticBtn.addEventListener('mousemove', (e) => {
    const rect = magneticBtn.getBoundingClientRect();
    const x = e.clientX - rect.left - rect.width / 2;
    const y = e.clientY - rect.top - rect.height / 2;
    magneticBtn.style.transform = `translate(${x * 0.3}px, ${y * 0.3}px)`;
  });
  magneticBtn.addEventListener('mouseleave', () => {
    magneticBtn.style.transform = 'translate(0, 0)';
  });
}
```

#### 10.3 — Underline Grow on Nav

```css
.nav-link {
  position: relative;
  padding-bottom: 2px;
}
.nav-link::after {
  content: '';
  position: absolute;
  left: 0; bottom: 0;
  width: 0;
  height: 2px;
  background: var(--color-accent);
  transition: width var(--transition-base);
}
.nav-link:hover::after,
.nav-link[aria-current="page"]::after {
  width: 100%;
}
```

#### 10.4 — Icon Scale + Rotate on Card Hover

Adds liveliness to service cards without animation libraries:

```css
.card:hover .card-icon { transform: scale(1.1) rotate(-5deg); }
```

#### 10.5 — Staggered Scroll Reveals (FAIL-OPEN — v6.1, replaces prior spec)

Data-driven reveals via IntersectionObserver (lives in animations.js, not inline CSS). Variants beyond `fade-up`:

- `data-animate="fade-up"` — default (10% of elements)
- `data-animate="fade-right"` — for left-side content (25%)
- `data-animate="fade-left"` — for right-side content (25%)
- `data-animate="wipe-right"` — clip-path reveal on images (images only)
- `data-animate="scale-in"` — for signature section entrances (5%)

**Don't use fade-up on every element.** Mix at least 3 reveal directions per page.

**FAIL-OPEN GATING (MANDATORY):** all reveal/opacity-zero rules must be gated under `html.js-anim`:

```css
html.js-anim .reveal-up { opacity: 0; transform: translateY(24px); transition: opacity 0.6s ease, transform 0.6s ease; }
html.js-anim [data-animate] { opacity: 0; }
```

animations.js adds `js-anim` to `<html>` as its **first statement**:

```javascript
document.documentElement.classList.add('js-anim');
```

If JS fails to load, every section is visible. **Never write a bare `.reveal-* { opacity: 0 }` rule.**

**Safety-net timeout (MANDATORY):** the IntersectionObserver setup must include a fallback — 2 seconds after DOMContentLoaded, force `.revealed` on any element still hidden:

```javascript
document.addEventListener('DOMContentLoaded', () => {
  setTimeout(() => {
    document.querySelectorAll('[data-animate]:not(.revealed), .reveal-up:not(.revealed), .reveal-down:not(.revealed), .reveal-left:not(.revealed), .reveal-right:not(.revealed), .reveal-scale:not(.revealed)')
      .forEach(el => el.classList.add('revealed'));
  }, 2000);
});
```

**Keep the existing rule: no reveal/data-animate classes on above-fold content** (hero, trust badges, first split if above fold).

---

### C11. Image Composition

**One color grade for the whole set (v6.3).** Client phone photos shot months apart in different light look like a scrapbook next to each other. Before generating variants, pass every photo through the same mild grade — warm lift, a little contrast, light sharpen: `magick in.jpg -auto-orient -strip -modulate 101,88,100 -sigmoidal-contrast 2.4x46% -fill "#d9915a" -colorize 5 -unsharp 0x0.8+0.6+0.02 out.webp`. Subtle on purpose; if a viewer can tell it was graded, it was graded too hard. Re-check the hero after grading — contrast lifts file size (salt-river-steel needed q60 to stay under 150KB).

Raw rectangles are forbidden. Every image gets at least one composition treatment:

#### 11.1 — Framed with Offset Shadow

```css
.img-framed {
  position: relative;
  padding: var(--space-md);
}
.img-framed::before {
  content: '';
  position: absolute;
  inset: 0;
  background: var(--color-accent);
  transform: translate(12px, 12px);
  z-index: 0;
  border-radius: var(--radius-lg);
}
.img-framed img {
  position: relative;
  z-index: 1;
  border-radius: var(--radius-lg);
  box-shadow: var(--shadow-lg);
}
```

#### 11.2 — Clipped/Masked Corners

```css
.img-clipped {
  clip-path: polygon(0 0, 100% 0, 100% 85%, 92% 100%, 0 100%);
}
```

#### 11.3 — Overlay Content Card on Image Edge

Card text sits over the bottom-right corner of an image:

```css
.img-with-overlay {
  position: relative;
}
.img-with-overlay img {
  border-radius: var(--radius-lg);
  aspect-ratio: 16/10;
  object-fit: cover;
  width: 100%;
}
.img-overlay-card {
  position: absolute;
  bottom: -var(--space-xl);
  right: var(--space-xl);
  background: #fff;
  padding: var(--space-xl);
  border-radius: var(--radius-md);
  box-shadow: var(--shadow-xl);
  max-width: 60%;
}
```

#### 11.4 — Duotone Brand Tint

See Pattern 4.3 — team photos and trust imagery.

#### 11.5 — Full-Bleed with Contained Content Inset

Image fills section edge-to-edge; contained content uses `.container` inside an overlay:

```css
.full-bleed-section {
  background-image: url('IMAGE_URL');
  background-size: cover;
  background-attachment: fixed;  /* parallax on desktop */
  position: relative;
}
.full-bleed-section::before {
  content: '';
  position: absolute;
  inset: 0;
  background: rgba(var(--color-primary-rgb), 0.7);
}
@media (max-width: 1023px) {
  .full-bleed-section { background-attachment: scroll; }
}
```

---

### C12. Technique Selection — Per-Page Minimum

Page-level requirements (beyond the global CSS-line minimum):

| Page | Required Techniques |
|---|---|
| **Homepage** | Layered hero (C1.1 or 1.2), hero composition w/ cascade (C2), at least 3 different divider styles (C3), asymmetric services grid (C6.1), one signature section (C7), mid-page CTA (C9.1), closing CTA (C9.2), ticker strip (C8) |
| **About** | Page hero (C1.4), about split w/ overlapping badge (C6.2), signature section (C7 — timeline or pull quote), team photo w/ duotone (C4.3), CTA banner (C9.1 or 9.2) |
| **Services (parent)** | Page hero (C1.4), services grid variation, mid-page CTA, closing CTA |
| **Individual Service Page** | Page hero (C1.4), content with drop cap or editorial treatment, one signature visual, FAQ section, CTA banner |
| **Service Area Page** | Page hero (C1.4), area-specific content, stat/proof block, local trust signals, CTA |
| **Contact** | Page hero (C1.4), split contact layout (form + info), map with custom styling, closing CTA |

**If a page lacks the minimum required techniques, it fails QA regardless of line count.** The QA audit checks for technique usage by CSS class name and pattern match.

### C13. Cross-Document View Transitions (v6.2)

Multi-page PHP sites get SPA-feel page changes with two CSS rules and zero JS. **Counts toward the Premium technique minimums** (C12 / tier counts).

```css
/* Opt both documents into cross-document transitions */
@view-transition {
  navigation: auto;
}

/* Brief fade — keep it under 250ms; respect reduced motion */
::view-transition-old(root) {
  animation: vt-fade-out 0.18s ease both;
}
::view-transition-new(root) {
  animation: vt-fade-in 0.22s ease both;
}
@keyframes vt-fade-out { to { opacity: 0; } }
@keyframes vt-fade-in  { from { opacity: 0; } }

@media (prefers-reduced-motion: reduce) {
  ::view-transition-old(root), ::view-transition-new(root) { animation: none; }
}
```

Progressive enhancement: non-supporting browsers get instant navigation, nothing breaks. Optionally give the site logo/navbar a shared `view-transition-name` so it holds still while content fades.

---

## Part D — Visual Vocabulary Archetypes

Not every site should feel like BBD. The archetype is selected based on logo analysis (Part B) and determines color strategy, typography pairing, divider style preferences, and overall tonal direction.

### D1 — Editorial / Premium

**Feeling:** restrained, confident, sophisticated. Thinking of high-end law firms, boutique consultants, architectural firms, luxury services.

- **Palette:** 1 primary (deep — navy, forest, burgundy, charcoal), 1 accent (warm — ochre, brass, amber, terracotta), generous white/cream
- **Typography:** serif heading (Playfair Display, Cormorant, Fraunces) + clean sans body (Inter, Source Sans, Lato). Large scale — H1 pushes toward `clamp(3rem, 7vw, 6rem)`
- **Hero:** typography-forward (C1.3) or split (C1.2). Avoid full-bleed photography — images are supporting, not leading.
- **Dividers:** minimal or absent — rely on generous whitespace and color shifts
- **Signature sections:** pull quotes (C7.3), alternating stagger (C7.4), editorial drop caps (C5.4)
- **Micro-interactions:** restrained — underline grows (C10.3), subtle scroll reveals. No tilt, no magnetic.
- **Example industries:** legal, accounting, architecture, high-end real estate, boutique hospitality

### D2 — Bold / Industrial

**Feeling:** strong, present, utilitarian, gritty, honest. Thinking of towing, construction, auto repair, heavy equipment, industrial services.

- **Palette:** saturated primary (electric blue, safety yellow, fire red, high-contrast black), aggressive accent (chrome silver or pure white), dark backgrounds dominant
- **Typography:** compressed/condensed heading (Bebas Neue, Oswald, Rajdhani) + workhorse sans body (Roboto, Open Sans). Heavy weights (800-900).
- **Hero:** full-bleed Ken Burns (C1.1) with 3-stop diagonal gradient. Hero H1 uses all-caps.
- **Dividers:** stacked parallelograms (C3.5), sharp diagonals (C3.1). Avoid curves.
- **Signature sections:** full-bleed photo stats (C7.2), big stat bands (C6.3) with accent-colored numbers
- **Micro-interactions:** magnetic CTA (C10.2), card tilt (C10.1) — this archetype welcomes tactile interactions
- **Example industries:** towing, auto repair, HVAC, construction, excavation, plumbing

### D3 — Warm / Human

**Feeling:** approachable, caring, familiar, welcoming. Thinking of family-owned businesses, wellness, hospitality, childcare, senior services.

- **Palette:** warm primary (teal, sage, terracotta, mustard), soft accent (peach, gold, cream), cream/off-white backgrounds
- **Typography:** humanist sans heading (Nunito, Poppins, Quicksand, Work Sans) + rounded body (Nunito Sans, Open Sans). Moderate weights (600-700).
- **Hero:** split hero (C1.2) with real photography of people. Avoid hard gradients — use soft radial overlays instead of diagonals.
- **Dividers:** curved waves (C3.2), double waves (C3.3)
- **Signature sections:** staggered cards (C7.4), timeline (C7.1) for family business history
- **Micro-interactions:** gentle scroll reveals, warm color transitions on hover. Skip magnetic/tilt.
- **Example industries:** wellness/massage, pediatric, senior services, hospitality, family restaurants, spa, salon

### D4 — Minimalist / Tech

**Feeling:** precise, clean, confident, contemporary. Thinking of software services, modern agencies, clean-tech, B2B services.

- **Palette:** monochrome + single saturated accent (pure black/white + electric accent like cyan, magenta, electric green), abundant whitespace
- **Typography:** geometric sans throughout (Inter, Space Grotesk, DM Sans). Tight letter-spacing on headings (-0.03em).
- **Hero:** typography-forward (C1.3) with asymmetric radial gradient glow. Minimal imagery.
- **Dividers:** none or very subtle (1px color lines, not SVG shapes)
- **Signature sections:** editorial pull quotes (C7.3), alternating stagger (C7.4), full-bleed typography moments
- **Micro-interactions:** crisp scroll reveals, underline grows only. No tilt, no magnetic — they conflict with the precision aesthetic.
- **Example industries:** agencies, software/SaaS, consultancies, modern tech services

### D5 — Rustic / Handmade

**Feeling:** authentic, crafted, natural, earthy. Thinking of farms, artisan trades, custom woodwork, landscaping with organic direction, craft services.

- **Palette:** earth tones (deep forest, burnt sienna, clay, stone gray, cream), warm accent (gold, burnt orange)
- **Typography:** display-serif or slab-serif heading (Fraunces, Lora, Bitter, Merriweather) + humanist sans body. Moderate weights.
- **Hero:** full-bleed (C1.1) with warm 3-stop gradient, heavier noise overlay (0.06-0.08 opacity vs 0.04 default)
- **Dividers:** torn paper (C3.4), organic curves (C3.2). SVG divider patterns with hand-drawn feel.
- **Signature sections:** timeline for heritage (C7.1), pull quote for craftsmanship story (C7.3)
- **Micro-interactions:** generous scroll reveals, warm color transitions. Card tilt acceptable at reduced max (3-4°).
- **Example industries:** farms, custom woodworking, landscaping/outdoor, artisan food, heritage trades

### Archetype Assignment in Build Prompts

The build prompt must specify the archetype:

```
ARCHETYPE: Warm / Human
RATIONALE: Logo is a hand-drawn script with rounded edges and warm terracotta tones. Industry is family-owned childcare — caring/approachable positioning is primary.
```

If no archetype is specified in the prompt, analyze the logo and industry, propose an archetype with rationale, and get confirmation before proceeding with the build.

---

## Part E — Accessibility Baseline (v6.2)

Non-negotiable on every tier. These cost nothing visually and fail real users when missing:

- **Visible `:focus-visible` style** on ALL interactive elements (links, buttons, inputs), built from brand tokens — not the UA default ring:
  ```css
  :focus-visible {
    outline: 2px solid var(--color-accent);
    outline-offset: 2px;
    border-radius: var(--radius-sm);
  }
  ```
- **Skip-to-content link** as the first focusable element (`<a class="skip-link" href="#main">Skip to content</a>`), visually hidden until focused.
- **Touch targets minimum 44×44px** — nav links, footer links, mobile CTA bar buttons, form controls. Pad small text links to the minimum; don't shrink the hit area to the glyphs.
- **Form inputs minimum `16px` font-size** (`input, select, textarea { font-size: 1rem; }`) — anything smaller triggers iOS auto-zoom on focus, which users experience as a broken form.

---

## Part F — Conversion & Trust Components (v6.1)

New required and conditional components. Phase placement lives in build-phases.md; specs live here.

### F1 — Hero Lead Form (new DEFAULT hero CTA treatment)

Short embedded form in the hero, right column or card overlay: **Name, Phone, Service Needed (select), TCPA consent checkbox** (single checkbox bundling phone + SMS consent). Formsubmit.co action with all standard hidden fields including `_cc CustomerService@pageoneinsights.com` and an absolute `_next` URL. Mobile: the form sits directly below the hero headline, above the fold where possible. **A button-only hero is now the fallback, not the default.**

### F2 — Credentials Carousel (homepage; reusable strip on inner pages)

Horizontally scrolling strip of credential badges — BBB, manufacturer certifications, association memberships, Google reviews badge. Each badge is an `<a>` linking to the client's actual third-party verification profile (`rel="noopener"`, `target="_blank"`). Grayscale-to-color on hover optional. These outbound links to authoritative profiles double as entity-corroboration signals for AEO. **Only render badges the client actually holds — never fabricate credentials.**

### F3 — Named Numbered Process (homepage + service pages)

Replace generic "How It Works" with a branded, numbered proprietary process: "[The Company-Name N-Point Process]" — e.g. a 12-point checklist grouped into 3-4 categories. Generate the process content from the client's actual workflow details in intake.

### F4 — GBP Map Embed Section (contact page standard; optional on service-areas main)

```html
<div class="map-embed">
  <iframe src="[GBP embed src from intake]" title="[Company] on Google Maps"
    loading="lazy" allowfullscreen
    referrerpolicy="no-referrer-when-downgrade"></iframe>
</div>
```

```css
.map-embed { position: relative; aspect-ratio: 16/10; border-radius: var(--radius-md); overflow: hidden; }
.map-embed iframe { position: absolute; inset: 0; width: 100%; height: 100%; border: 0; }
```

Strip the fixed width/height from the pasted iframe. `loading="lazy"` and `title` are required. Add a "Get Directions" button beside it: `https://www.google.com/maps/dir/?api=1&destination=place_id:[PLACE_ID]` (fall back to URL-encoded name+city if no place_id).

**HOW TO FIND THE GBP EMBED LINK (agent instructions — follow verbatim):**
1. google.com/maps → search the exact GBP business name + city (character-for-character match to the listing).
2. Confirm the result is the claimed Business Profile (shows reviews, category, hours) — not a bare address pin.
3. Share → "Embed a map" tab → COPY HTML.
4. Verify the iframe src contains the business name encoded in the URL (e.g. `!2sSLOAN%20UNDERGROUND`). Address-only = wrong grab; redo from the listing panel.
5. Extract schema coordinates from the src: `!2d` value = LONGITUDE, `!3d` value = LATITUDE. Feed into LocalBusiness `geo`.

### F5 — Review Request Link (thank-you page)

`https://search.google.com/local/writereview?placeid=[PLACE_ID]` behind a "Happy with our work? Leave us a Google review" button. This feeds the GBP stars that actually appear in SERPs. Requires place_id from intake.

### F6 — Sticky Bar Text Button (footer.php mobile CTA bar)

Mobile sticky CTA bar gains a third option where the client accepts texts: `sms:+1XXXXXXXXXX` "Text Us" alongside Call Now + Free Estimate. Two buttons if the client declines SMS.

### F7 — Google Reviews Section (Page One reviews block — replaces Elfsight, 2026-10-02)

Every build whose client has a connected Google Business Profile shows real Google reviews with the shared block `includes/google-reviews.php` (canonical: `~/crm/references/google-reviews.php`; the scaffold copies it in). It reads the Page One reviews feed `https://db.pageone.cloud/functions/v1/site-reviews/{slug}` (edge function `site-reviews`, SQL `site_reviews_feed`, data = `google_reviews`, refreshed nightly from the native GBP API), caches it on the site for 6 hours, and prints: Google rating header, "Review us on Google" button, review cards (scroll-snap row, arrows on desktop), "See all on Google" link.

```php
<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/google-reviews.php';
$homeReviews = p1_google_reviews($slug, ['heading' => 'What {City} customers say on Google']);
if ($homeReviews !== ''): ?>
<section class="section home-reviews" aria-label="Google reviews"><div class="container"><?php echo $homeReviews; ?></div></section>
<?php endif; ?>
```

Options: `limit` (default 12), `min_rating` (4 or 5; the feed never returns reviews under 4 stars), `replies` (owner replies, default off), `heading`, `heading_tag`. Place it on a light band (`--color-paper-2`), **never inside a reveal class or a dark band**. It returns `''` when there are no written reviews or the profile is not connected, so always wrap the section in the `if`. Do not hand-write review cards, do not paste an Elfsight embed on new builds, **never fabricate reviews or review numbers**, and no `aggregateRating` schema. To move an existing site off Elfsight: copy the include in, replace the embed with the snippet above, remove the Elfsight script.

### F8 — Financing Section (conditional)

If the client offers financing, add a section: provider logo, 1-2 sentence pitch, "Check Your Rate" / "Prequalify" button to the client's provider link (Wisetack/Hearth/etc.). No hard numbers unless the client supplies them.

### F9 — Scheduling Embed (conditional)

If the client provides a Google Appointment Schedules or Calendly link, add a "Book Online" button in the hero and contact page (link out or embed — link out is default; embed only on request).

### F10 — Two-Tier Header (optional pattern — logo analysis decides)

Utility topbar above main nav: phone (click-to-call), hours or credential line, social icons. Main nav below gets more room for the logo. Recommend this pattern in Phase 0/1 logo-analysis output when the logo is a wide wordmark (>3:1) that fights nav links for horizontal space. Topbar hides on scroll (nav becomes the sticky element).

### F11 — Logo Variants (logo-analysis addition)

Logo analysis must also flag: whether an SVG/vector version should be requested from the client (raster logos blur under shrink-on-scroll), and whether a white/knockout variant is needed for dark navs and the footer.

### F13–F19 — Flagship conversion components (v8, 2026-10-09 — full specs in `conversion-components-v8.md`)

Part F numbering continues; F5–F12 above are unchanged. Each pointer below is one paragraph; the markup, config contract, no-JS behavior, copy rules, and QA checks live in `conversion-components-v8.md`.

**F13 — Instant estimate tool (Flagship, authorization-gated).** `includes/estimate-tool.php` reads `$estimateTool` from config.php and renders a three-step `<ol>` form (service, quantity, result + lead capture) whose ranges are the client's own `integrations.estimate_tool.pricing` verbatim. Built ONLY when `integrations.estimate_tool.authorized === true` with name, timestamp, and method. See §1.

**F14 — Book a time window (Flagship, authorization-gated).** `includes/booking-window.php` posts `booking=1`, `preferred_date`, `time_window` to the leads endpoint; the owner confirms by text, nothing is auto-booked. Built ONLY when `integrations.booking.authorized === true`. Not a third-party calendar (F9 remains the link-out option for clients who already have one). See §2.

**F15 — Text-first contact.** `includes/text-us.php`: a sticky "Text us" `sms:` link to the OWNER'S OWN number (never our toll-free), plus the third sticky-bar button from F6. Ships when `integrations.accepts_sms` is true; Flagship intake asks for it by default. See §3.

**F16 — Financing block.** `includes/financing-block.php` replaces the loose F8 guidance for Flagship: partner name + prequalify URL required, "from $X/mo" only with a client-supplied example and its terms printed beside it. See §4.

**F17 — Recent-work feed.** `includes/recent-work.php` (canonical `~/crm/references/recent-work.php`) reads `https://db.pageone.cloud/functions/v1/site-recent-work/{slug}` and prints the client's latest GBP photos and posts, cached 6 hours, `''` when nothing is available. Same pattern as F7. See §5.

**F18 — "Do you serve my address?" checker.** `includes/zip-check.php` on the service-areas hub matches a ZIP or town against the intake city list, links to the city page on a match, and falls back to `?zip=` server-side without JS. See §6.

**F19 — Before/after sliders as default for visual trades.** Existing `.ba-*` pattern; Flagship requires at least three real client pairs from the image manifest, captioned with the job and the place. See §7.

Flagship also includes the photo/video day (§8), Spanish pages with hreflang pairs (§9), and the launch report (§10). All Flagship work follows the claims-accuracy rules in `seo-aeo-2026.md` Part C.

### F12 — Dark Section Treatment (optional per DESIGN NOTES)

Strategic dark sections (deep charcoal `#1a1d21`-range with the brand accent) for hero, one mid-page section, and footer. Never the whole page. Strong fit for visually bold trades.

**Every dark band gets depth (v6.3 default):** a brushed gradient (`--color-dark → --color-dark-alt → --color-primary` at 120°), the scaffold grain layer (`section.texture-grain > <span class="grain-layer" aria-hidden="true">` — light streak + fine horizontal lines + SVG noise, `mix-blend-mode: overlay`), and ideally one slanted edge. A flat dark fill reads as unfinished. Dark-surface eyebrows/labels use a brighter accent variant (`--color-accent-bright`) so small uppercase text stays AA on charcoal.

### F13 — Lite YouTube Facade (conditional, when client has videos)

Never embed raw YouTube iframes. Render the thumbnail as a static image with a play button; swap in the `youtube-nocookie.com` iframe on click.

---

## Enforcement Summary

The site-qa-agent skill validates:

1. **Inline `<style>` block exists** on every page (fail otherwise regardless of tier)
2. **CSS line count meets tier minimum** (Premium: 400, Standard: 200, Basic: 100)
3. **Minimum technique count met** (Premium: 6, Standard: 4, Basic: 2)
4. **Required per-page techniques present** (by class name pattern match — see C12)
5. **No hardcoded colors, shadows, spacing** (grep for disallowed patterns)
6. **Text-wrap: balance on heading selectors** (enforcement check)
7. **No two consecutive identical dividers** (structural parse)
8. **v6.2:** `:focus-visible` styling present (warn), form input font-size ≥ 16px, svh on full-height heroes (warn), performance budget per `performance-2026.md` Part E. Touch-target sizing is spot-checked in the Puppeteer render phase (computed boxes need layout).

Failure modes and remediation prompts are generated per-page and piped into the Phase 6 Enhance runner, which re-applies the missing techniques page-by-page.

---

*This file is the canonical visual standard. Update it when patterns prove themselves across multiple builds. Resist the urge to add to it speculatively — every addition becomes a requirement the QA will enforce.*
