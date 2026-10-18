# Performance Standard — 2026 (v6.3 — 2026-09-04 additions marked)

Mobile performance, asset discipline, and the JS diet for client sites. Companion to `design-system.md` (visual standard) and `seo-aeo-2026.md` (Core Web Vitals are a ranking tiebreaker; mobile is the measured experience).

**Load this file for:** Phase 1 (head.php, fonts), Phase 2 (CSS/animation architecture), image work in any phase, Phase 7 QA, performance AI edits.

---

## Part A — Performance Budget (QA-enforceable)

Every build ships within this budget. The first two columns are enforced by `qa_audit.py` statically; the render-time metrics are measured in the Puppeteer QA phase on a throttled mobile profile.

| Metric | Budget | Where enforced |
|---|---|---|
| LCP (mobile) | ≤ 2.0s | render phase (Puppeteer) |
| INP | < 200ms | render phase (Puppeteer) |
| CLS | < 0.1 | render phase (Puppeteer) |
| Total JS shipped | ≤ 100KB (uncompressed, sum of all .js + inline scripts) | qa_audit.py (static) |
| Largest hero image file | ≤ 150KB | qa_audit.py (static) |

The static budget is a **blocker on new builds**. Pre-v6.2 sites audit these as warnings (`--legacy` flag) until backported.

---

## Part B — Responsive Images (REQUIRED)

Hero and card images are generated at **480 / 960 / 1600 widths** as webp (sharp at build time) and served via `srcset` + `sizes`:

```html
<img src="/assets/images/hero-960.webp"
     srcset="/assets/images/hero-480.webp 480w,
             /assets/images/hero-960.webp 960w,
             /assets/images/hero-1600.webp 1600w"
     sizes="100vw"
     width="1600" height="900"
     alt="..." loading="eager" fetchpriority="high">
```

- Card/grid images: `sizes` reflects the rendered slot (e.g. `(max-width: 700px) 100vw, 33vw`).
- `<picture>` is reserved for **art direction** (different crops per breakpoint) — not for format/width switching, which `srcset` handles.
- Explicit `width`/`height` on every image, always (existing rule — prevents CLS).
- CSS `background-image` heroes use `image-set()` with the same three widths.

---

## Part C — JavaScript Diet

### Icons: inline SVG at build time

Icons are **inlined as SVG markup during the build** (the lucide-icons set lives in `~/crm/references/lucide-icons/`). **NEVER runtime icon injection** — no `<i data-lucide="...">` + `lucide.createIcons()`, no synchronous icon scripts. Runtime injection costs a render-blocking script, a layout shift per icon, and breaks no-JS rendering (see `aeo-crawlability.md`).

### Third-party JS: every external script must justify itself

- **No vanilla-tilt CDN.** If the card-tilt technique (design-system C10.1) is used, inline a ~30-line equivalent in the page's script block.
- **Simple carousels use CSS scroll-snap**, not Swiper. Swiper is permitted only when the build genuinely needs its features (free-mode momentum, complex pagination, synced galleries) — and then self-hosted, not CDN.
- Analytics/embeds load `defer` or after interaction; nothing third-party loads synchronously in `<head>`.

### Reveal animations: CSS first

Prefer **CSS scroll-driven animations** behind `@supports`, with the existing IntersectionObserver reveal system as the fallback:

```css
@supports (animation-timeline: view()) {
  .reveal {
    animation: reveal-up 0.6s ease both;
    animation-timeline: view();
    animation-range: entry 0% entry 40%;
  }
}
/* IO fallback (.reveal + .visible classes) stays for non-supporting browsers */
```

Both paths respect `prefers-reduced-motion` (existing rule).

---

## Part D — Fonts

- **Self-hosted woff2 subsets** in `/assets/fonts/`, declared via `@font-face` with `font-display: swap`.
- Preload the two above-the-fold faces (heading + body regular):
  `<link rel="preload" href="/assets/fonts/[file].woff2" as="font" type="font/woff2" crossorigin>`
- **No Google Fonts CDN on new builds** (`fonts.googleapis.com` / `fonts.gstatic.com`). Pre-v6.2 sites keep it until backported; their preconnect rule still applies.
- **Max 6 font files** total. Variable fonts preferred — one file covers the weight range.

---

## Part E — Enforcement Summary (v6.2 additions to qa_audit.py)

| Check | Severity (new build) | Severity (--legacy) |
|---|---|---|
| srcset+sizes on hero/card images | blocker | warning |
| Runtime icon injection (data-lucide / createIcons) | blocker | warning |
| Sync third-party scripts in head | blocker | warning |
| Google Fonts CDN | blocker | warning |
| Total JS > 100KB | blocker | warning |
| Hero image > 150KB | blocker | warning |
| Bare 100vh hero (no svh fallback) | warning | warning |
| LCP / INP / CLS | render phase (Puppeteer) | render phase |

---

*Canonical copy: `~/crm/references/performance-2026.md`. The pageone-web-builder skill references it via symlink.*

---

## v6.3 additions (2026-09-04) — every item below is visually invisible

Measured motivation: godscountrytree.com (v6.2/pipeline-v2, Jul 14) scored Lighthouse
mobile 72 with an 8.1 s LCP because its hero was a 978 KB 2048px camera original served
cross-origin from Supabase storage as a CSS background; sloanunderground.com after the
Sep 4 pass scores 90 with 347 KB transferred. The fleet snapshot (100 repos): 40 hotlink
storage originals, 61 still load Google Fonts, 52 ship Swiper, 45 have at least one
blocking `<script src>`.

### Targets (homepage, mobile, cold, cache-busted)
| Metric | Target |
|---|---|
| DOMContentLoaded | < 600 ms |
| load | < 1 s |
| LCP | < 2.0 s |
| CLS | < 0.05 |
| Lighthouse mobile Performance | ≥ 90 (QA FAIL below) |
| Lighthouse Accessibility / Best Practices / SEO | ≥ 95 (QA WARN below) |
| Hero image | ≤ 150 KB (unchanged) |
| Any single image file | ≤ 250 KB (QA FAIL above) |
| Homepage image payload (what the mobile viewport actually downloads) | ≤ 600 KB |
| Total page weight | ≤ 1.5 MB (QA WARN above) |

### Part F — Scripts
- `defer` on EVERY `<script src>`, first-party and third-party alike. `main.js`,
  `animations.js`, `effects.js` initialise on DOMContentLoaded (or add `html.js-anim` at
  parse, which still runs before DCL under defer), so defer changes nothing functionally.
  `async` is NOT a substitute: it breaks the `js-anim` → reveal ordering.
- No sitewide libraries. If a page genuinely needs Swiper (a real carousel, not a
  scroll-snap track), include the self-hosted script on THAT page only, `defer`, and
  initialise it from an IntersectionObserver in `main.js` when the container comes within
  `rootMargin: 200px` of the viewport — never at load. Same rule for any tilt/parallax
  effect. Snippet:
  ```js
  const lazyInit = (sel, init) => { const els = document.querySelectorAll(sel); if (!els.length) return;
    const io = new IntersectionObserver((es, o) => es.forEach(e => { if (e.isIntersecting) { init(e.target); o.unobserve(e.target); } }), { rootMargin: '200px' });
    els.forEach(el => io.observe(el)); };
  lazyInit('.swiper', el => typeof Swiper !== 'undefined' && new Swiper(el, {/* … */}));
  ```
- Third-party widgets (Elfsight reviews, map iframes, booking embeds) are injected only when
  their container approaches the viewport — Elfsight's `platform.js` alone is 15 KB gzip +
  the widget payload, and it is the largest DCL cost on sites that carry it:
  ```html
  <script>(function(){var s=document.currentScript,f=function(){var t=document.createElement("script");t.src="https://static.elfsight.com/platform/platform.js";t.defer=true;document.head.appendChild(t)};if(!("IntersectionObserver" in window)){f();return}var o=new IntersectionObserver(function(e){if(e[0].isIntersecting){o.disconnect();f()}},{rootMargin:"600px"});o.observe(s.parentElement)})();</script>
  <div class="elfsight-app-…" data-elfsight-app-lazy></div>
  ```

### Part B (extended) — Images
- Formats: the pipeline writes `<name>-480/-960/-1600.webp` AND `.avif` for every on-disk
  photo (`lib/responsive-images.ts`). The original `.jpg` stays as the `<img src>` fallback.
- Markup for photos:
  ```html
  <picture>
    <source type="image/avif" srcset="/assets/images/x-480.avif 480w, /assets/images/x-960.avif 960w, /assets/images/x-1600.avif 1600w" sizes="(max-width: 768px) 100vw, 600px">
    <img src="/assets/images/x.jpg" srcset="/assets/images/x-480.webp 480w, /assets/images/x-960.webp 960w, /assets/images/x-1600.webp 1600w"
         sizes="(max-width: 768px) 100vw, 600px" width="1600" height="1200" alt="…" loading="lazy" decoding="async">
  </picture>
  ```
- Hero / LCP image: `loading="eager" fetchpriority="high"`, explicit `width`/`height`,
  a `<picture>` (never a CSS `background-image` — backgrounds get no srcset, no priority
  and no LCP candidate), plus a responsive preload in head.php:
  `<link rel="preload" as="image" type="image/avif" imagesrcset="…" imagesizes="…" fetchpriority="high">`.
  Exactly ONE element per page carries `fetchpriority="high"` — never the nav logo.
- Below the fold: `loading="lazy" decoding="async"`.
- Every photo `src` is a local `/assets/images/` path. Hotlinking Supabase storage
  (`db.pageone.cloud/storage/...`) is as forbidden as imgur: those are camera originals
  (0.9–3 MB), a second origin, and a 4-hour CDN TTL.

### Part D (confirmed) — Fonts
Already self-hosted since v6.2: variable woff2 in `/assets/fonts/`, `font-display: swap`,
latin subset (the css2 `unicode-range` blocks), preload only the heading face. Nothing to
change; do NOT ship a separate `fonts.css` — `@font-face` lives in framework.css (a
second stylesheet is one more render-blocking request and delays every font by a full
round trip, as on godscountrytree.com).

### Part G — Critical CSS
- The scaffold writes `includes/critical.css` (above-the-fold subset of framework.css:
  tokens, base, header/nav, hero family, form card, buttons, badges, mobile bars, the
  reveal gate). head.php inlines it and loads framework.css non-blocking:
  ```html
  <style><?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/critical.css'; ?></style>
  <link rel="preload" href="/assets/css/framework.css?v=<?php echo $cssVersion; ?>" as="style" onload="this.onload=null;this.rel='stylesheet'">
  <noscript><link rel="stylesheet" href="/assets/css/framework.css?v=<?php echo $cssVersion; ?>"></noscript>
  ```
- Per-page unique CSS stays in that page's inline `<style>` (already the v7 rule).
- framework.css is locked and shared by every page, so it does ship rules a given page
  doesn't use (~10 KB brotli). Inlining the critical subset removes that from the render
  path; per-page scoping of the shared file is NOT worth the build complexity.
- Regenerate critical.css whenever framework.css changes (the scaffold does it; manual
  framework edits must re-run the extractor), then bump `$cssVersion`.

### Part H — Server (.htaccess template, Phase 1)
- Brotli first (`mod_brotli`), gzip fallback (`mod_deflate`). Hostinger's CDN (`server:
  hcdn`) already brotli-compresses text and serves HTTP/2 + HTTP/3 (`alt-svc: h3`),
  confirmed 2026-09-04 on godscountrytree.com / sloanunderground.com — the directive is
  for hosts without the CDN layer.
- `Cache-Control: public, max-age=31536000, immutable` on css/js/woff2/webp/avif/jpg/png/svg
  (css/js are safe at one year because `?v=$cssVersion` cache-busts them).
- HTML/PHP: `Cache-Control: no-cache, must-revalidate` (revalidate every visit; the CDN
  edge still serves it fast).

### Part E (extended) — Enforcement
qa_audit.py section "PERFORMANCE v6.3" (blocker on new builds, warning under `--legacy`):
every `<script src>` deferred · LCP image not lazy · no image file > 250 KB · every
`<img>` has width+height · head.php has the inline critical block and no blocking
stylesheet · FAQPage schema wherever a FAQ section exists · sitemap.xml reachable (with
`--url`) · page weight ≤ 1.5 MB (warn) · Lighthouse mobile ≥ 90 / other categories ≥ 95
(with `--url`, via `qa/lighthouse-mobile.mjs`).
