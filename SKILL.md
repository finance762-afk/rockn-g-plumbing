---
name: pageone-web-builder
description: Build production-grade local business websites for Page One Insights clients. Use this skill whenever building, editing, auditing, or generating prompts for client websites — including static HTML/CSS/JS sites and PHP-include sites deployed via GitHub to Hostinger. Covers the full build pipeline (tiered site structures, PHP component architecture, CSS design system, premium visual techniques, logo analysis, animations), plus cutting-edge 2026 local SEO and Answer Engine Optimization (AEO). Trigger this skill for any mention of client sites, website builds, CLAUDE.md standards, SEO audits, AEO optimization, schema markup, llms.txt, entity blocks, service area pages, or the Page One Insights web design workflow — even if the user doesn't explicitly name the skill.
---

# Page One Web Builder — Orientation

This skill is the entry point for client website builds. It is intentionally thin — all specifications live in reference files and in CLAUDE.md. This file tells Claude Code what to read, in what order, before writing any code.

---

## Read Order (MANDATORY)

Before writing a single line of code, read these files in this order. Each builds on the previous.

1. **`~/crm/CLAUDE-websites.md`** — Enforcement rules. What every build must contain. What's forbidden. Tier visual quality matrix. Lead form endpoint. Non-negotiable.

2. **`~/crm/references/design-system.md`** — Visual architecture. CSS token system, logo analysis process, premium technique library (12 categories of reusable patterns), 5 visual vocabulary archetypes. This is the largest and most important reference — every visual decision flows from here.

3. **`~/crm/references/seo-aeo-2026.md`** — SEO + AEO specifications. Schema markup for every page type, llms.txt / llms-full.txt templates, entity blocks, answer blocks, conversational keyword integration.

4. **`~/crm/references/build-phases.md`** — PHP component structure. Build phase order. File organization. Deployment pipeline.

If CLAUDE.md and a reference file conflict, CLAUDE.md wins. If two reference files conflict, flag the conflict and stop — do not guess.

---

## Build Input Contract

Every build prompt should specify:

- **Client slug** — matches build directory name and `site_build_intakes.client_slug`
- **Tier** — Basic, Standard, or Premium (determines CSS line bar + required technique count — see CLAUDE.md)
- **Archetype** — Editorial/Premium, Bold/Industrial, Warm/Human, Minimalist/Tech, or Rustic/Handmade (see design-system.md Part D). If not specified, analyze logo and industry, propose an archetype, get confirmation.
- **Brand colors** — primary, accent, and optionally secondary. Pulled from logo analysis if not supplied.
- **Industry + services list** — drives content generation and schema markup
- **Service areas** — drives area page structure (Standard tier: one area page; Premium tier: main + individual area pages)

---

## Phase Structure (summary — full detail in build-phases.md)

| Phase | Scope |
|---|---|
| 1 | Intake + logo analysis + token extraction |
| 2 | CSS architecture (styles.css complete with all tokens) + PHP includes (head, nav, footer) |
| 3 | Homepage (index.php) with inline `<style>` block hitting tier line bar |
| 4 | Inner pages (about, services, service areas, contact) each with their own inline `<style>` block |
| 5 | SEO/AEO files (sitemap.xml, sitemap-images.xml, robots.txt, llms.txt, llms-full.txt, 404.php, thank-you.php) + QA pass |
| 6 | Enhance (Premium only — auto-applies missing techniques if QA failed) |

Each phase commits to git before the next runs. Git history is the audit trail.

---

## Deployment

Builds are triggered from the Design Portal run-phase API. Claude Code runs on the VPS with `--dangerously-skip-permissions` in each build's directory. After each phase: `git add -A && git commit && git push`. Hostinger auto-deploys from the `main` branch.

Do not suggest Mac-local deploy scripts (`./new-site.sh`, `./deploy.sh`). Those don't exist on the VPS — deployment is orchestrated by the Design Portal, not by Claude Code.

---

## Critical Reminders

- Every page gets its own inline `<style>` block for page-specific CSS. Shared styles go in `/assets/css/styles.css`. A page without a `<style>` block is an automatic QA fail regardless of tier.
- All includes use `$_SERVER['DOCUMENT_ROOT']` — never relative paths.
- All CSS values use `var()` tokens — never hardcoded colors, shadows, or spacing.
- Every heading uses `text-wrap: balance`.
- Form action URL: use `form_action` from build-plan.json verbatim — `https://db.pageone.cloud/functions/v1/leads/{slug}` (Formsubmit retired Sep 2026; never emit formsubmit.co).
- No meta keywords tag. No Twitter/X card tags. (Both are forbidden in CLAUDE.md.)

Read CLAUDE.md for the full list of enforceable rules.
