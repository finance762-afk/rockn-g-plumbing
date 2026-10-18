# Web Builder v8 components (`references/components/`)

The scaffold (`design-portal /api/builds/[id]/start`) copies every file here into a new site's `/includes/`.
Pages include a component only when the build plan says so (see the Phase 3/4 prompts, "FLAGSHIP COMPONENTS").
Every component is self-contained: its own `<style>` scoped to a root class using `var()` tokens with fallbacks,
inline SVG icons, deferred `<script>`, explicit image dimensions, WCAG labels, honeypot + spam shield + consent
on every form via `p1-form-helpers.php`.

| Include | Call | config.php contract | Gate |
|---|---|---|---|
| `p1-form-helpers.php` | `p1_lead_hidden_fields($formId, $extra)`, `p1_consent_row($id)`, `p1_service_options()` | `$siteUrl`, `$leadsFormSecret`, `$services` | always available |
| `estimate-tool.php` | `echo p1_estimate_tool(['service' => 'Stump grinding'])` | `$estimateTool = ['authorized' => true, 'pricing' => [['service','slug','low','high','unit','note'], …]]` | **build-plan `integrations.estimate_tool.authorized === true`** (QA blocker otherwise) |
| `booking-window.php` | `echo p1_booking_window()` | `$bookingWindows = ['authorized' => true, 'days' => ['Mon',…], 'windows' => ['8–10 AM',…], 'lead_days' => 1]` | **build-plan `integrations.booking.authorized === true`** (QA blocker otherwise) |
| `text-us.php` | `echo p1_text_us()` (footer, once) | `$textUsNumber` = owner's own number, E.164 (`integrations.text_us_number`) | empty → renders nothing |
| `financing-block.php` | `echo p1_financing_block()` (service pages) | `$financing = ['partner','url','monthly_note'?]` (`integrations.financing`) | partner + https url required; `monthly_note` printed only if supplied |
| `recent-work.php` | `echo p1_recent_work($slug)` (homepage) | `$slug` | Flagship always; Premium when `integrations.recent_work === true`; feed `site-recent-work/{slug}` |
| `zip-check.php` | `echo p1_zip_check()` (service-areas hub) | `$serviceAreas` (standard), `$serviceZips` (optional, `integrations.service_zips`) | Flagship |
| `before-after.php` | `echo p1_before_after('before-base','after-base','alt','alt',['caption'=>''])` | image basenames in `/assets/images/` | service pages with a before/after pair in the manifest |

Lead fields the leads endpoint receives from these forms (all post to `$formAction`):
`form_location` = `estimate` / `booking` / `zip-check`; estimate adds `estimate_service`, `estimate_qty`, `estimate_low`, `estimate_high`;
booking adds `booking=1`, `preferred_date` (YYYY-MM-DD), `time_window`, `job_address`, `message`; zip-check adds `city`.

Config values are written by Phase 2 from `build-plan.json.integrations` — the authorization objects carry
`authorized_by`, `authorized_at`, `method` ("onboarding" | "verbal"); Phase 2 copies only `authorized`, the pricing
rows / days / windows into config.php. Nothing here invents a number: ranges, windows, partner names and the
text number are the client's own.
