<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/functions.php';
?>
<?php
/**
 * faqs/index.php — Plumbing FAQs (Phase 5). URL is /faqs/ per the SEO brief SITE MAP, and includes/footer.php already
 * links /faqs/ once this page exists.
 * SEO brief (Oct 18 2026): FAQs are VISIBLE CONTENT ONLY — no FAQPage JSON-LD on this site. Every answer restates an
 * intake fact (license, hours, free estimates, military discount, services, towns) or is general plumbing knowledge
 * written as such. No prices, response times, insurance, warranty, emergency/24-hour or backflow claims: intake has none.
 * Questions customers really ask on the phone are requested in /content-confirmations.json.
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/service-cards.php';

$pageType        = 'faq';
$pageTitle       = "San Angelo Plumbing FAQs | Rock'n G Plumbing";
$pageDescription = "Answers from Rock'n G Plumbing, a San Angelo, TX plumber • Master Plumber Lic. M-44822, free estimates, military discounts • Call (325) 650-7000 with questions.";
$canonicalUrl    = $siteUrl . '/faqs/';
$ogImage         = '/assets/images/og-default-v1.jpg';
$currentPage     = 'faqs';
$breadcrumbs     = [['name' => 'FAQs', 'url' => '/faqs/']];

$telHref  = 'tel:' . $phoneE164;
$phoneFmt = formatPhone($phone);
$weekday  = hoursGroups($hours)[0];
$licenseExpires = date('F j, Y', strtotime($license['expires']));
$townList = implode(', ', array_slice($areasWeServeTowns, 0, -1)) . ' and ' . end($areasWeServeTowns);

// <a> to a service page
$faqSvc = function (string $slug, string $text = ''): string {
    $s = serviceBySlug($slug);
    return "<a href='" . h(serviceUrl($s)) . "'>" . h($text !== '' ? $text : $s['name']) . '</a>';
};
$serviceLinks = implode(', ', array_map(fn($s) => $faqSvc($s['slug']), array_slice($services, 0, -1))) . ' and ' . $faqSvc(end($services)['slug']);
$areaHubNote  = pageExists('/areas-we-serve/') ? " See the <a href='/areas-we-serve/'>areas we serve</a> for the full list." : '';
$christovalNote = pageExists('/service-area/christoval-tx/') ? " The <a href='/service-area/christoval-tx/'>Christoval plumbing page</a> has the details for that town." : '';

// Categories → questions. Answers may contain links (author-controlled HTML); each opens with the direct answer.
$faqCategories = [
    ['id' => 'general', 'nav' => 'General', 'icon' => 'info', 'title' => "General questions about Rock'n G Plumbing",
     'lead' => 'Who the company is, what the license means and when the office is open.',
     'items' => [
        ['q' => "Who is Rock'n G Plumbing?",
         'a' => "Rock'n G Plumbing is a San Angelo, Texas plumbing company run by Eli Gomez, a Responsible Master Plumber from a family with three generations in the trade. The company brings 20+ years of experience to residential, commercial and industrial jobs. The <a href='/about/'>About page</a> tells the full story."],
        ['q' => "Is Rock'n G Plumbing licensed in Texas?",
         'a' => "Yes. Eli Gomez is the Responsible Master Plumber for Rock'n G Plumbing on Texas Master Plumber License {$license['number']}, issued by the Texas State Board of Plumbing Examiners and current through {$licenseExpires}. You can look the license up with the Board before you book."],
        ['q' => "What hours is Rock'n G Plumbing open?",
         'a' => "Rock'n G Plumbing's office is open {$weekday[0]}, {$weekday[1]}, and closed Saturday and Sunday. Call <a href='" . h($telHref) . "'>" . h($phoneFmt) . "</a> during those hours, or send the estimate form at any time and the office will follow up during business hours."],
        ['q' => "Does Rock'n G Plumbing work on commercial and industrial properties?",
         'a' => "Yes. Rock'n G Plumbing takes residential, commercial and industrial plumbing work, including " . $faqSvc('new-construction-remodel-plumbing', 'new construction and remodel plumbing') . '. Tell the office what kind of property it is when you call so the estimate fits the job.'],
     ]],
    ['id' => 'services', 'nav' => 'Services', 'icon' => 'wrench', 'title' => 'Plumbing service questions',
     'lead' => 'What the company repairs and installs, and what the common warning signs mean.',
     'items' => [
        ['q' => "Which plumbing services does Rock'n G Plumbing offer in San Angelo?",
         'a' => "Rock'n G Plumbing offers eight plumbing services in San Angelo: {$serviceLinks}. Each service page explains what the work involves and what to check first."],
        ['q' => 'Do you install tankless water heaters?',
         'a' => "Yes. Rock'n G Plumbing repairs and replaces tank water heaters and installs tankless units. When a heater is failing, the estimate covers whether a repair still makes sense or whether it is time to replace it. See " . $faqSvc('water-heaters', 'water heater repair and installation') . '.'],
        ['q' => "Can you help with San Angelo's hard water?",
         'a' => "Yes. Rock'n G Plumbing installs water softeners, reverse osmosis drinking-water systems and whole-house filters for homes dealing with San Angelo's hard water. White scale on fixtures and spotty dishes are the usual first signs. See " . $faqSvc('water-filtration-softeners', 'water softeners and filtration') . '.'],
        ['q' => 'How do I know if I have a slab leak?',
         'a' => "A water bill that jumps with no change in use, a warm spot on the floor, or the sound of running water with everything turned off usually points to a hidden leak, often in a line under the slab. Rock'n G Plumbing handles " . $faqSvc('leak-detection-repair', 'leak detection and slab leak repair') . ', including repairing or replacing the water line.'],
        ['q' => 'Why are several drains in my house slow or gurgling at the same time?',
         'a' => "When several drains slow down or gurgle together, the blockage is usually in the main sewer line rather than in one fixture's drain. A sewer camera inspection shows what is wrong before anyone digs. Rock'n G Plumbing handles " . $faqSvc('sewer-line-repair', 'sewer line repair and camera inspection') . ' and ' . $faqSvc('drain-cleaning', 'drain cleaning') . '.'],
        ['q' => 'What should I do if I smell gas?',
         'a' => "Leave the building first, then call 911 or your gas utility from outside. Do not flip switches, use open flames or call from inside. Once it is safe, Rock'n G Plumbing repairs and installs gas lines. See " . $faqSvc('gas-line-services', 'gas line installation and repair') . '.'],
        ['q' => 'Do you repair sprinkler and irrigation systems?',
         'a' => "Yes. Rock'n G Plumbing repairs lawn sprinkler and irrigation systems, including broken heads and leaking lines. Soggy patches in the yard or heads that will not pop up are the usual signs. See " . $faqSvc('irrigation-sprinkler-repair', 'sprinkler and irrigation repair') . '.'],
     ]],
    ['id' => 'pricing', 'nav' => 'Pricing', 'icon' => 'clipboard-list', 'title' => 'Pricing and estimate questions',
     'lead' => 'What a free estimate covers and what moves the price of common jobs.',
     'items' => [
        ['q' => "Does Rock'n G Plumbing charge for estimates?",
         'a' => "No. Rock'n G Plumbing gives free estimates on plumbing work in San Angelo and Christoval. Call <a href='" . h($telHref) . "'>" . h($phoneFmt) . "</a>, {$weekday[0]} {$weekday[1]}, or send the <a href='/contact/'>estimate form</a> with a short description of the problem."],
        ['q' => 'Is there a military discount?',
         'a' => "Yes. Rock'n G Plumbing offers a military discount. Mention your service when you call or add it to the message on the estimate form, and ask how the discount applies to your job."],
        ['q' => 'How much does a water heater replacement cost in San Angelo?',
         'a' => "The cost depends on whether you choose a tank or a tankless heater, its size, and what the install needs, such as gas supply, venting and location. Rock'n G Plumbing gives a free estimate instead of a one-size price. Switching to tankless usually involves more work than swapping a tank for a tank."],
        ['q' => 'What affects the cost of a sewer line repair?',
         'a' => "The length and depth of the damaged section, how hard it is to reach, and whether the line can be cleaned or has to be dug up and repaired. A camera inspection shows which one you are facing, and the estimate from Rock'n G Plumbing is free."],
     ]],
    ['id' => 'process', 'nav' => 'Process', 'icon' => 'route', 'title' => 'How working with Rock\'n G Plumbing goes',
     'lead' => 'How to get started and what to have ready when you call.',
     'items' => [
        ['q' => "How do I get an estimate from Rock'n G Plumbing?",
         'a' => "Call <a href='" . h($telHref) . "'>" . h($phoneFmt) . "</a> or send the <a href='/contact/'>contact form</a>. Tell the office what the plumbing is doing and which service fits, and Rock'n G Plumbing follows up with a free estimate."],
        ['q' => 'What happens after I send the form?',
         'a' => "The office reads your request during business hours and follows up with you about the estimate. There is no obligation, and the work does not start until you decide to go ahead."],
        ['q' => 'What should I tell the plumber when I call?',
         'a' => "Say what the plumbing is doing, where in the building it happens and when it started. If water is leaking, shut it off at the fixture or the main valve if you can do that safely, and mention it on the call."],
     ]],
    ['id' => 'area', 'nav' => 'Service area', 'icon' => 'map-pin', 'title' => 'Service area questions',
     'lead' => 'Which towns around San Angelo the company drives to.',
     'items' => [
        ['q' => "Which towns outside San Angelo does Rock'n G Plumbing serve?",
         'a' => "Rock'n G Plumbing serves Christoval as well as San Angelo, and also takes jobs in {$townList}." . $christovalNote . $areaHubNote],
     ]],
];
$faqTotal = array_sum(array_map(fn($c) => count($c['items']), $faqCategories));

$schemaMarkup = [[
    "@context"   => 'https://schema.org',
    '@type'      => 'WebPage',
    '@id'        => $canonicalUrl . '#webpage',
    'url'        => $canonicalUrl,
    'name'       => $pageTitle,
    'description'=> $pageDescription,
    'isPartOf'   => ['@id' => $siteUrl . '/#website'],
    'about'      => ['@id' => orgId()],
    'inLanguage' => 'en-US',
]];

ob_start();
?>
<style>
/* =====================================================================
   FAQs — page-only CSS (fq- prefix). Tokens from framework.css; no raw
   colors, shadows or spacing.
   ===================================================================== */

/* ---------- Hero: compact interior (C1 layered) ---------- */
.fq-hero {
  position: relative;
  isolation: isolate;
  overflow: clip;
  padding-block-end: var(--space-12);
}
.fq-hero::before {
  content: "";
  position: absolute;
  inset: 0;
  z-index: -1;
  pointer-events: none;
  background:
    radial-gradient(ellipse 45% 90% at 100% 100%, color-mix(in srgb, var(--color-accent) 22%, transparent), transparent 65%),
    linear-gradient(115deg, var(--color-dark) 0%, var(--color-dark-alt) 60%, var(--color-primary-deep) 100%);
}
.fq-hero::after {
  content: "";
  position: absolute;
  inset: 0;
  z-index: -1;
  pointer-events: none;
  opacity: .16;
  mix-blend-mode: overlay;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='160' height='160'%3E%3Cfilter id='g'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.8' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23g)'/%3E%3C/svg%3E");
}
.fq-hero .breadcrumb {
  color: color-mix(in srgb, var(--color-surface) 72%, transparent);
  margin-block-end: var(--space-2);
}
.fq-hero .breadcrumb li {
  display: inline-flex;
  align-items: center;
}
.fq-hero .breadcrumb a:hover {
  color: var(--color-accent-bright);
}
.fq-hero .breadcrumb [aria-current="page"] {
  color: var(--color-surface);
}
.fq-hero .link-call,
.fq-hero .link-call svg {
  color: color-mix(in srgb, var(--color-surface) 92%, transparent);
}
.fq-hero .link-call:hover {
  color: var(--color-surface);
}
.fq-hero h1 {
  max-width: 22ch;
  text-wrap: balance;
}
.fq-hero .hero-answer {
  max-width: 62ch;
  margin-block-start: var(--space-4);
}
.fq-hero .hero-actions {
  margin-block-start: var(--space-6);
}

/* ---------- Body: sticky category rail + question lists (C5) ---------- */
.fq-body {
  background: var(--color-paper);
}
.fq-layout {
  display: grid;
  grid-template-columns: minmax(0, .32fr) minmax(0, 1fr);
  gap: clamp(2rem, 5vw, 4rem);
  align-items: start;
}
.fq-rail {
  position: sticky;
  top: calc(var(--nav-height) + var(--space-6));
  padding: var(--space-6);
  border: 1px solid var(--color-line);
  border-radius: var(--radius-lg);
  background: var(--color-surface);
  box-shadow: var(--shadow-sm);
}
.fq-rail h2 {
  margin-block-end: var(--space-3);
  font-size: var(--fs-h3);
}
.fq-rail ul {
  display: grid;
  gap: var(--space-1);
  margin: 0;
  padding: 0;
  list-style: none;
}
.fq-rail a {
  display: flex;
  align-items: center;
  gap: var(--space-3);
  min-height: 44px;
  padding: var(--space-2) var(--space-3);
  border-radius: var(--radius);
  color: var(--color-ink-2);
  font-weight: 600;
  font-size: var(--fs-small);
  text-decoration: none;
  transition: background var(--transition), color var(--transition);
}
.fq-rail a:hover,
.fq-rail a:focus-visible {
  background: var(--color-card-tint-3);
  color: var(--color-primary);
}
.fq-rail svg {
  color: var(--color-accent-dark);
  flex: 0 0 auto;
}
.fq-rail__call {
  margin-block-start: var(--space-4);
  padding-block-start: var(--space-4);
  border-top: 1px solid var(--color-line);
  font-size: var(--fs-small);
  color: var(--color-muted);
}
.fq-rail__call a {
  display: inline;
  min-height: 0;
  padding: 0;
  color: var(--color-primary);
  font-weight: 700;
}
.fq-cat {
  padding: 0;
  scroll-margin-top: calc(var(--nav-height) + var(--space-6));
}
.fq-cat + .fq-cat {
  margin-block-start: var(--space-12);
}
.fq-cat__head {
  display: grid;
  grid-template-columns: auto 1fr;
  gap: var(--space-1) var(--space-4);
  align-items: center;
  margin-block-end: var(--space-4);
}
.fq-cat__icon {
  grid-row: span 2;
  display: grid;
  place-items: center;
  width: 3rem;
  height: 3rem;
  border-radius: var(--radius);
  background: var(--color-card-tint-3);
  color: var(--color-primary);
}
.fq-cat__head h2 {
  font-size: var(--fs-h3);
  text-wrap: balance;
}
.fq-cat__head p {
  margin: 0;
  font-size: var(--fs-small);
  color: var(--color-muted);
}
.fq-list {
  border: 1px solid var(--color-line);
  border-radius: var(--radius-lg);
  background: var(--color-surface);
  box-shadow: var(--shadow-sm);
  overflow: hidden;
}
.fq-list .faq {
  padding: var(--space-4) var(--space-6);
  transition: background var(--transition);
}
.fq-list .faq:first-child {
  border-top: 0;
}
.fq-list .faq:hover,
.fq-list .faq[open] {
  background: var(--color-paper-2);
}
.fq-list .faq summary {
  min-height: 44px;
}
.fq-list .faq p {
  max-width: 64ch;
}
.fq-list .faq p a {
  color: var(--color-primary);
  font-weight: 600;
  text-decoration: underline;
  text-underline-offset: .2em;
}

/* ---------- Still-have-a-question card ---------- */
.fq-ask {
  display: grid;
  grid-template-columns: minmax(0, 1fr) auto;
  gap: var(--space-6);
  align-items: center;
  margin-block-start: var(--space-12);
  padding: var(--space-8);
  border-radius: var(--radius-lg);
  background: var(--color-card-tint-1);
  border: 1px solid var(--color-line);
}
.fq-ask h2 {
  margin-block-end: var(--space-2);
  font-size: var(--fs-h3);
}
.fq-ask p {
  margin: 0;
  color: var(--color-ink-2);
}
.fq-ask .actions {
  display: flex;
  flex-wrap: wrap;
  gap: var(--space-3);
}

/* ---------- Closing CTA ---------- */
.fq-cta .link-call {
  color: color-mix(in srgb, var(--color-surface) 90%, transparent);
}
.fq-cta .link-call svg {
  color: var(--color-accent-bright);
}
.fq-cta__ring {
  position: absolute;
  inset-block-start: -6rem;
  inset-inline-end: -4rem;
  width: 18rem;
  aspect-ratio: 1;
  border: 2px solid color-mix(in srgb, var(--color-accent) 30%, transparent);
  border-radius: 50%;
  opacity: .6;
  pointer-events: none;
}

@media (max-width: 900px) {
  .fq-layout,
  .fq-ask {
    grid-template-columns: 1fr;
  }
  .fq-rail {
    position: static;
  }
  .fq-rail ul {
    grid-template-columns: repeat(2, minmax(0, 1fr));
  }
}
@media (max-width: 480px) {
  .fq-rail ul {
    grid-template-columns: 1fr;
  }
  .fq-list .faq {
    padding-inline: var(--space-4);
  }
}
@media (prefers-reduced-motion: reduce) {
  .fq-rail a,
  .fq-list .faq {
    transition: none;
  }
}
</style>
<?php
$extraHead = ob_get_clean();

include $_SERVER['DOCUMENT_ROOT'] . '/includes/head.php';
include $_SERVER['DOCUMENT_ROOT'] . '/includes/header.php';
?>

<!-- ============ HERO ============ -->
<section class="hero hero--interior fq-hero" aria-labelledby="fq-h1">
  <span class="grain" aria-hidden="true"></span>
  <div class="container">
    <nav class="breadcrumb" aria-label="Breadcrumb">
      <ol>
        <li><a href="/">Home</a></li>
        <li class="breadcrumb-sep" aria-hidden="true">/</li>
        <li><span aria-current="page">FAQs</span></li>
      </ol>
    </nav>
    <span class="eyebrow">Plumbing FAQs</span>
    <h1 id="fq-h1">San Angelo Plumbing Questions, Answered by Rock'n G Plumbing</h1>
    <p class="hero-answer">Rock'n G Plumbing answers <?php echo (int) $faqTotal; ?> common questions here: licensing, hours, free estimates, the military discount, what each plumbing service covers and which towns around San Angelo the crew serves. If your question is not here, call <a href="<?php echo h($telHref); ?>"><?php echo h($phoneFmt); ?></a>.</p>
    <div class="hero-actions">
      <a class="btn btn-accent btn-lg" href="/contact/" data-open-estimate>Get a free estimate</a>
      <a class="link-call" href="<?php echo h($telHref); ?>"><?php echo icon('phone', 18); ?>or call <?php echo h($phoneFmt); ?></a>
    </div>
  </div>
</section>

<article aria-labelledby="fq-h1">

  <!-- ============ QUESTIONS ============ -->
  <section class="section fq-body" aria-label="Frequently asked questions">
    <div class="container">
      <div class="fq-layout">

        <aside class="fq-rail reveal-left" aria-label="FAQ categories">
          <h2>Jump to a topic</h2>
          <ul>
            <?php foreach ($faqCategories as $fqCat): ?>
            <li><a href="#<?php echo h($fqCat['id']); ?>"><?php echo icon($fqCat['icon'], 18); ?><?php echo h($fqCat['nav']); ?></a></li>
            <?php endforeach; ?>
          </ul>
          <p class="fq-rail__call">Question not listed? Call <a href="<?php echo h($telHref); ?>"><?php echo h($phoneFmt); ?></a>, <?php echo h($weekday[0]); ?> <?php echo h($weekday[1]); ?>.</p>
        </aside>

        <div class="fq-main">
          <?php foreach ($faqCategories as $fqCat): ?>
          <section class="fq-cat" id="<?php echo h($fqCat['id']); ?>" aria-labelledby="fq-<?php echo h($fqCat['id']); ?>-title">
            <div class="fq-cat__head reveal-up">
              <span class="fq-cat__icon"><?php echo icon($fqCat['icon'], 24); ?></span>
              <h2 id="fq-<?php echo h($fqCat['id']); ?>-title"><?php echo h($fqCat['title']); ?></h2>
              <p><?php echo h($fqCat['lead']); ?></p>
            </div>
            <div class="fq-list reveal-up">
              <?php foreach ($fqCat['items'] as $ii => $fqItem): ?>
              <details class="faq"<?php echo $ii === 0 ? ' open' : ''; ?>>
                <summary><?php echo h($fqItem['q']); ?></summary>
                <p><?php echo $fqItem['a']; ?></p>
              </details>
              <?php endforeach; ?>
            </div>
          </section>
          <?php endforeach; ?>

          <div class="fq-ask reveal-scale">
            <div>
              <h2>Still have a plumbing question?</h2>
              <p>Describe what the plumbing is doing and Rock'n G Plumbing will tell you what the job involves. The estimate is free.</p>
            </div>
            <div class="actions">
              <a class="btn btn-primary" href="/contact/">Send us your question</a>
              <a class="btn btn-secondary" href="<?php echo h($telHref); ?>"><?php echo icon('phone', 18); ?>Call <?php echo h($phoneFmt); ?></a>
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>

  <!-- ============ FINAL CTA ============ -->
  <section class="closing-cta texture-grain edge-torn-top fq-cta" aria-labelledby="fq-cta-title">
    <span class="grain-layer" aria-hidden="true"></span>
    <span class="fq-cta__ring" aria-hidden="true"></span>
    <div class="container">
      <div class="reveal-left">
        <span class="eyebrow">No charge to look</span>
        <h2 id="fq-cta-title">Ready for a free plumbing estimate in San Angelo?</h2>
        <p>Call Rock'n G Plumbing at <?php echo h($phoneFmt); ?>, <?php echo h($weekday[0]); ?> <?php echo h($weekday[1]); ?>, or send the estimate form. Military discounts are available.</p>
      </div>
      <div class="actions reveal-right">
        <a class="btn btn-accent btn-lg" href="/contact/" data-open-estimate>Get a Free Estimate</a>
        <a class="link-call" href="<?php echo h($telHref); ?>"><?php echo icon('phone', 18); ?>Call <?php echo h($phoneFmt); ?></a>
      </div>
    </div>
  </section>

</article>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>
