# Content Quality — Web Builder v8 (2026-10-09)

> Canonical copy: `~/crm/references/content-quality-2026.md`. Loaded for Phase 3, Phase 4, Phase 4B, AI edits, blog writing, and QA. Companion to `seo-aeo-2026.md` Part C → "Claims accuracy" (what a page may claim) — this file is about whether a page is worth reading.

**Why v8:** Google's 1 Oct 2026 generative-AI content documentation folded the Quality Rater guidance into the AI-content guide. AI assistance is fine; unchecked, thin, templated or scaled output is not, and the review covers metadata. Our writer is an AI, so the standard is not "hide that" — it is "make every page carry information a competitor's page cannot".

---

## 1. Information gain (the test every page must pass)

A page earns its place only if it contains things that cannot be synthesized from the top-10 results for its keyword. For a local service site the proprietary data is the client:

- **Their numbers:** price ranges, minimums, stump sizes, response windows, crew size — from intake only.
- **Their process:** what happens on the estimate visit, what the crew does first, what the customer gets in writing.
- **Their place:** roads, neighborhoods, subdivisions, soil, tree species, storm seasons, permit offices — real local detail for the city on the page, not the metro.
- **Their proof:** the client's own photos (captioned with what the job was), Google reviews through the Page One feed, licence numbers, certification links.
- **Their experience:** the owner's voice from `research_brief` / intake notes ("we see this on every ridge lot in Ringgold").

**Floor:** three such items per page. **Test:** swap the business name and city for another company's — if the page still reads fine, it failed.

**Declarative answers.** Every question H2 is followed by a 40–60 word answer paragraph whose first sentence (under 30 words) is the self-contained answer. AI extraction takes that sentence; the rest of the paragraph earns the click.

**Entity–attribute sentences.** The entity block and the answer blocks state facts as `[Business] [does X] [in place] [under condition]` — plain declarative sentences a knowledge graph can parse. No "we pride ourselves on".

## 2. Thin content — what fails

| Pattern | Why it fails | Rule |
|---|---|---|
| Swapped-city pages | Doorway pages; scaled content abuse | City pages 450+ *unique* words; within-site overlap between two city or service pages ≤ 35% of 8-word shingles |
| Template prose shared with other clients | Our own fleet is the evidence that it is generic | Cross-site overlap with any other client site ≤ 25% on service/city/blog pages |
| Credential refrains | "Licensed, insured, 15 minutes away" in every section is padding | A fact appears once in body copy (hero or entity block) plus once in the footer; never per section |
| Pages for queries alone | A page per AI sub-query is scaled content abuse | A city page exists because the client serves that city and intake names it; a blog post exists because a GSC/DFS gap shows real demand |
| Unanswered question headings | "Why choose us?" followed by slogans | The paragraph after a question H2 is 25+ words and shares a noun with the question |
| Word padding | Length without information | Floors are floors, not targets: service 500, city 450, blog 800, about 300 |
| Phrase filler | "In today's world", "in today's fast-paced world", "look no further", "whether you're … or …", "dedicated team", "peace of mind", "unparalleled", "your trusted partner" | Reported by QA as a trend line; never the reason a page passes or fails |

**Pruning (post-launch).** A blog post or city page with zero impressions after 90 days in Search Console is a prune candidate: merge it into the hub page with a 301, or expand it with real information. The SEO suite surfaces these; nobody deletes a client page without the account manager's approval.

## 3. Proof of experience

- Real photos only, captioned with the job, the place and the outcome. Alt text describes the photo.
- Reviews via the Page One reviews feed (4★+), never hand-typed, never counted in copy unless the GBP count is in intake.
- Blog authorship: `author` = the Organization. When the owner reviewed the post, add `reviewedBy` = Person with `sameAs` to their real GBP / Facebook / LinkedIn from intake. Never a fictional expert.
- "How we do it" sections describe the client's actual process from intake, not an industry-standard list.

## 4. Structure a parser can read

- `<main>` wraps the page body; service, city and blog bodies sit in an `<article>`; sidebars (TOC, CTA rail) in `<aside>`; navigation in `<nav>`; footer in `<footer>`.
- Sequential steps are an `<ol>` (process sections). Unordered facts are a `<ul>`.
- Comparisons with three or more variables (material × cost × upkeep, service × timeframe × price) are an HTML `<table>` with `<th scope>`; never a prose paragraph or a list of lists.
- One JSON-LD `@graph` per page (see `aeo-content-schema.md` §1.2.3). Blog posts: BlogPosting → Organization author, optional `reviewedBy` Person; never `TechArticle`/`Person` author for a business blog.

## 5. Internal linking (topical silos)

```
Home
├── /services/            (hub)  ← every service page links up to it in body copy
│   └── /services/{slug}/ (leaf) ↔ links sideways to 1–2 closely related services
├── /service-areas/       (hub)  ← every city page links up to it
│   └── /service-areas/{city}/   → links to the 2–3 services that city page leads with
└── /blog/                (hub)
    └── /blog/{slug}/     → 2+ posts in the same cluster, 2+ service pages
```

- Links live in body sentences where the surrounding text explains why the target matters ("stump grinding is priced per stump; see what affects the price"). Nav and footer links do not count toward the 2–3 contextual links a page needs.
- Anchors describe the target page, not a keyword: "how we price stump grinding in Ringgold", never "stump grinding Ringgold" three times.
- No orphan pages: every page is reachable from a body link on at least one other page, not only from the footer.
- Footer city links stay (they are navigation for a 5–15 city local site) but every city also gets a body link from the service-areas hub.

## 6. QA (qa_audit.py → CONTENT QUALITY, every mode, warning severity until calibrated)

| Check | Signal | Threshold (initial) |
|---|---|---|
| Within-site similarity | 8-word shingle overlap between pages of the same type | warn > 35% |
| Cross-site similarity | same, against every other site in `~/client-sites` | warn > 25% |
| Specificity density | numbers + place names + intake entities per 100 words | warn < 4 |
| Answer under heading | first paragraph after a question H2: ≥ 25 words and shares a noun | warn |
| Word floors | service 500 / city 450 / blog 800 / about 300 | warn |
| Credential refrain | the same claim phrase ≥ 3 times in one page's body | warn |
| Filler phrases | phrase list hits per page | report only |
| Landmarks | `<main>` present; `<article>` on service/city/blog | warn |

Promotion to blocker happens after a fleet calibration run and two weeks of false-positive review (see the `claims-accuracy` / `content-quality` learnings in the SEO guidebook). The adversarial judge (Haiku 5.5, "find the three paragraphs a customer would skip and say what fact is missing") runs inside the site-qa-agent narrative pass and its scores are written to the QA report.

## 7. Model policy (v8)

Opus 5.5 (effort high) for Phase 1, Phase 3 and Premium Phase 4; Sonnet 5.5 for all other content, QA fixes and edits; Haiku 5.5 only for constrained tasks (topic selection, image captions, the content judge). Haiku is not the writer: on long agentic phases it skips checks, and the writer's job under v8 is to trace every claim to intake. Source of truth: `packages/design-portal/lib/phase-config.ts`.

## 8. Per-page outputs: confirmation list + usefulness note (2026-10-10)

Adopted from the owner's content brief (Oct 10 2026). Google's guidance allows AI-assisted pages; what it penalises is inaccurate, unoriginal or mass-produced copy — and it asks for human fact-checking before publication. These two outputs make that checking cheap.

**8.1 Facts requiring confirmation.** When a page *wants* a fact that intake, the research brief and the owner notes do not supply (a price range, a response window, years in business, a credential, insurance, a guarantee, a service limit, a process step), do not invent it and do not silently drop it. Leave the claim out of the copy and record it in `content-confirmations.json` at the site root, one entry per fact, so the client can confirm it during draft review:

```json
{ "pages": [ { "path": "/services/gutter-guards/", "useful_because": "…", "needs_confirmation": [ { "fact": "Typical install takes one day", "question": "How long does a typical gutter-guard install take?", "where": "process section" } ] } ],
  "consolidate": [ { "path": "/service-areas/riverview/", "reason": "No verified differences from Tampa beyond the name" } ] }
```
Append to the file on every phase (merge by `path`); never rewrite other phases' entries. The draft email reads this file and asks the client the `question` lines; the launch report lists whatever is still unconfirmed. Once the client answers, the fact goes into intake (`research_brief` / build plan) and the page is updated — never pasted in from the email.

**8.2 Why this page is useful.** For every page you write, record one plain paragraph in `useful_because` naming what a customer gets here that the homepage and sibling pages do not (the specific facts, the process, the local detail). If you cannot write that paragraph, the page is thin — fix the page or, for an area page, add it to `consolidate` with the reason. This note is internal: never on the page, never in a meta tag.

**8.3 City-name-swap test.** An area page earns its place only with verified, useful differences (named neighbourhoods and streets from intake, a different service mix, local rules, real jobs there). Same copy with the city swapped is scaled content; recommend consolidating into the parent service page via `consolidate` instead of publishing it.

**8.4 Voice.** Match the owner's voice from the brief. Vary sentence length naturally. Do **not** force slang, quirks, deliberate mistakes or fake personal stories to "sound human" — a knowledgeable person explaining the service to one customer is the target.

**8.5 FAQs.** Only questions a real customer asked (intake, research brief, call notes). Zero is acceptable on any page; FAQPage schema only when the questions exist. No page carries a minimum FAQ count or a fixed word count — the required components per tier and the answer blocks are the structure; everything inside them follows §1–§3.
