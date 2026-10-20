<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/functions.php';
?>
<?php
/**
 * blog/protect-san-angelo-plumbing-before-a-hard-freeze/index.php — launch post 2 (Phase 6).
 * Topic: the storm/seasonal default. General freeze-protection practice only; nothing here is a Rock'n G promise.
 * Local specifics: San Angelo / Tom Green County, the February 2021 Texas winter storm as common knowledge (no numbers
 * quoted), irrigation, gas appliances. Business claims are limited to config.php facts. Per the SEO brief there is no
 * emergency / 24-hour wording: the post tells readers to use their own shutoff valve and gives the office hours
 * (Mon–Fri 8–6). No FAQPage schema. Author = Page One Insights LLC (blog-standard.md §7).
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/blog-data.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/service-cards.php';

$bpSlug = 'protect-san-angelo-plumbing-before-a-hard-freeze';
$post   = blogPostBySlug($bpSlug);

$pageTitle       = $post['title'];
$pageDescription = "Find your shutoff, protect exposed pipes, drip the right faucets and know what to do if a line splits. Call Rock'n G Plumbing for a free estimate.";
$canonicalUrl    = $siteUrl . '/blog/' . $bpSlug . '/';
$currentPage     = 'blog/' . $bpSlug;
$ogType          = 'article';
$ogImage         = '/assets/images/' . $post['photo'] . '.jpg';
$breadcrumbs     = [['name' => 'Blog', 'url' => '/blog/'], ['name' => $post['title'], 'url' => '/blog/' . $bpSlug . '/']];

$telHref  = 'tel:' . $phoneE164;
$phoneFmt = formatPhone($phone);
$weekday  = hoursGroups($hours)[0];

$toc = [
    'before-the-freeze' => 'Before the freeze',
    'first-to-freeze'   => 'Which pipes freeze first?',
    'dripping'          => 'Should you drip faucets?',
    'frozen-not-burst'  => 'Frozen but not burst',
    'burst-pipe'        => 'If a pipe bursts',
    'after-the-freeze'  => 'After the freeze',
];

// Where freezes strike first, ranked by exposure (general plumbing knowledge)
$fzRisk = [
    ['level' => 'high', 'label' => 'Highest', 'title' => 'Outdoor spigots and hose connections', 'body' => 'Fully exposed, and a hose left attached traps water against the faucet. Disconnect hoses and cover the spigot.'],
    ['level' => 'high', 'label' => 'Highest', 'title' => 'Irrigation valves and above-ground pipe', 'body' => 'Sprinkler risers and valve assemblies sit in open air. Shut the system off and protect or drain what is above ground.'],
    ['level' => 'mid',  'label' => 'High',    'title' => 'Garage and attic supply lines', 'body' => 'Water heater connections in an unheated garage or attic have no house heat around them. Insulate any exposed line.'],
    ['level' => 'mid',  'label' => 'High',    'title' => 'Pipes in exterior walls', 'body' => 'A supply line on the cold side of the insulation can freeze even with the house warm. Open the cabinet doors beneath those sinks.'],
    ['level' => 'low',  'label' => 'Lower',   'title' => 'Lines under the slab', 'body' => 'Buried and out of reach, so they cannot be wrapped. The protection for them is a house that stays warm.'],
];

$schemaMarkup = [[
    "@context" => 'https://schema.org', '@type' => 'BlogPosting',
    '@id' => $canonicalUrl . '#post',
    'headline' => $post['title'],
    'description' => $pageDescription,
    'image' => absUrl($post['image']),
    'datePublished' => $post['dateISO'], 'dateModified' => $post['dateISO'],
    'keywords' => 'frozen pipes San Angelo, freeze protection plumbing San Angelo TX, burst pipe San Angelo, leak detection San Angelo TX, winterize plumbing San Angelo',
    'inLanguage' => 'en-US',
    'author' => ['@type' => 'Organization', 'name' => 'Page One Insights LLC', 'url' => 'https://pageoneinsights.com'],
    'publisher' => ['@id' => orgId()],
    'mainEntityOfPage' => $canonicalUrl,
    'about' => ['@type' => 'Service', 'name' => 'Leak detection and slab leak repair', 'url' => absUrl('/services/leak-detection-repair/'), 'provider' => ['@id' => orgId()]],
]];

ob_start();
?>
<style>
/* =====================================================================
   Post: hard-freeze checklist — page-only CSS (fz- prefix)
   Tokens from framework.css; no raw colors, shadows or spacing.
   Techniques: layered cold-toned hero (gradient + noise + frost ring), rail-left layout, exposure meter (signature),
   before/during/after tinted columns, dark grain band for the burst-pipe steps.
   ===================================================================== */
.fz-hero {
  padding-block-end: var(--space-12);
  background:
    radial-gradient(ellipse 50% 100% at 0% 0%, color-mix(in srgb, var(--color-accent-bright) 26%, transparent), transparent 62%),
    radial-gradient(ellipse 40% 70% at 100% 100%, color-mix(in srgb, var(--color-secondary) 40%, transparent), transparent 70%),
    linear-gradient(175deg, var(--color-primary-deep), var(--color-dark));
}
.fz-hero::after {
  content: "";
  position: absolute;
  inset: 0;
  pointer-events: none;
  opacity: .16;
  mix-blend-mode: overlay;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='160' height='160'%3E%3Cfilter id='g'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.8' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23g)'/%3E%3C/svg%3E");
}
.fz-hero .container {
  position: relative;
  z-index: 1;
}
.fz-hero .breadcrumb {
  color: color-mix(in srgb, var(--color-surface) 72%, transparent);
}
.fz-hero .breadcrumb li {
  display: inline-flex;
  align-items: center;
}
.fz-hero .breadcrumb [aria-current="page"] {
  color: var(--color-surface);
}
.fz-hero .hero-title {
  max-width: 20ch;
}
.fz-frost {
  position: absolute;
  inset-block-start: calc(-1 * var(--space-10));
  inset-inline-end: var(--space-8);
  width: clamp(140px, 22vw, 260px);
  aspect-ratio: 1;
  border-radius: 50%;
  border: 2px solid color-mix(in srgb, var(--color-surface) 22%, transparent);
  box-shadow: inset 0 0 0 var(--space-4) color-mix(in srgb, var(--color-surface) 5%, transparent), 0 0 0 var(--space-6) color-mix(in srgb, var(--color-surface) 4%, transparent);
  animation: fz-breathe 7s var(--ease) infinite alternate;
}
.fz-meta {
  display: flex;
  flex-wrap: wrap;
  gap: var(--space-2) var(--space-5);
  margin: var(--space-4) 0 0;
  padding: 0;
  list-style: none;
  font-size: var(--fs-small);
  color: color-mix(in srgb, var(--color-surface) 78%, transparent);
}
.fz-meta li {
  display: inline-flex;
  gap: var(--space-2);
  align-items: center;
}
.fz-meta svg {
  color: var(--color-accent-bright);
}
@keyframes fz-breathe {
  from { transform: scale(.94); opacity: .6; }
  to   { transform: scale(1.04); opacity: 1; }
}

/* ---------- Layout: rail left, article right ---------- */
.fz-layout {
  display: grid;
  grid-template-columns: 290px minmax(0, 1fr);
  grid-template-areas: "rail article";
  gap: clamp(var(--space-8), 5vw, var(--space-16));
  align-items: start;
}
.fz-article {
  grid-area: article;
  min-width: 0;
}
.fz-rail {
  grid-area: rail;
  position: sticky;
  inset-block-start: calc(var(--nav-height) + var(--space-4));
  display: grid;
  gap: var(--space-5);
}
.fz-toc,
.fz-sidecta {
  padding: var(--space-5);
  border-radius: var(--radius-lg);
  border: 1px solid var(--color-line);
}
.fz-toc {
  background: var(--color-card-tint-3);
}
.fz-toc h2 {
  font-size: 1rem;
  margin-block-end: var(--space-3);
}
.fz-toc ul {
  display: grid;
  gap: var(--space-1);
  margin: 0;
  padding: 0;
  list-style: none;
}
.fz-toc a {
  display: block;
  padding: var(--space-1) 0 var(--space-1) var(--space-3);
  font-size: var(--fs-small);
  text-decoration: none;
  color: var(--color-ink-2);
  border-inline-start: 2px solid var(--color-line);
  transition: color var(--transition), border-color var(--transition);
}
.fz-toc a:hover {
  color: var(--color-primary);
  border-color: var(--color-accent);
}
.fz-sidecta {
  background: var(--color-primary);
  color: var(--color-surface);
}
.fz-sidecta h2 {
  color: var(--color-surface);
  font-size: 1.1rem;
  margin-block-end: var(--space-2);
}
.fz-sidecta p {
  margin: 0 0 var(--space-3);
  font-size: var(--fs-small);
  color: color-mix(in srgb, var(--color-surface) 84%, transparent);
}
.fz-sidecta .btn {
  width: 100%;
  justify-content: center;
}
.fz-sidecta .link-call {
  margin-block-start: var(--space-3);
  color: var(--color-surface);
}
.fz-sidecta .link-call svg {
  color: var(--color-accent-bright);
}
.fz-featured {
  margin: 0 0 var(--space-8);
}
.fz-featured figcaption {
  margin-block-start: var(--space-2);
  font-size: var(--fs-small);
  color: var(--color-muted);
}

/* ---------- Body ---------- */
.fz-body .answer-block {
  font-size: var(--fs-lead);
}
.fz-body section {
  padding: 0;
  overflow: visible;
  scroll-margin-block-start: calc(var(--nav-height) + var(--space-4));
}
.fz-body section + section {
  margin-block-start: var(--space-12);
}
.fz-body h2 {
  margin-block: 0 var(--space-3);
}
.fz-body p a,
.fz-body li a {
  color: var(--color-primary);
  text-decoration-color: var(--color-accent);
  text-underline-offset: 3px;
}
.fz-checklist {
  display: grid;
  gap: var(--space-3);
  margin: var(--space-5) 0;
  padding: 0;
  list-style: none;
}
.fz-checklist li {
  display: grid;
  grid-template-columns: auto 1fr;
  gap: var(--space-4);
  padding: var(--space-4) var(--space-5);
  border-radius: var(--radius-lg);
  background: var(--color-surface);
  border: 1px solid var(--color-line);
  border-inline-start: 4px solid var(--color-accent);
  box-shadow: var(--shadow-sm);
}
.fz-checklist svg {
  color: var(--color-primary);
  margin-block-start: var(--space-1);
}
.fz-checklist b {
  display: block;
  font-family: var(--font-heading);
  margin-block-end: var(--space-1);
}
.fz-checklist span {
  font-size: var(--fs-small);
  color: var(--color-ink-2);
}

/* ---------- Signature: exposure meter ---------- */
.fz-meter {
  display: grid;
  gap: var(--space-3);
  margin: var(--space-5) 0;
  padding: 0;
  list-style: none;
}
.fz-meter li {
  position: relative;
  display: grid;
  grid-template-columns: 96px minmax(0, 1fr);
  gap: var(--space-4);
  align-items: center;
  padding: var(--space-4) var(--space-5);
  border-radius: var(--radius-lg);
  background: var(--color-card-tint-1);
  overflow: hidden;
}
.fz-meter li::before {
  content: "";
  position: absolute;
  inset-block: 0;
  inset-inline-start: 0;
  width: var(--fz-fill, 100%);
  background: linear-gradient(90deg, color-mix(in srgb, var(--color-accent-bright) 38%, transparent), transparent);
  transform-origin: left;
  animation: fz-fill 1.2s var(--ease) both;
}
.fz-meter li[data-level="high"] { --fz-fill: 100%; }
.fz-meter li[data-level="mid"]  { --fz-fill: 66%; }
.fz-meter li[data-level="low"]  { --fz-fill: 30%; }
.fz-meter li > * {
  position: relative;
}
.fz-meter__tag {
  justify-self: start;
  padding: var(--space-1) var(--space-3);
  border-radius: var(--radius-full);
  background: var(--color-primary);
  color: var(--color-surface);
  font-family: var(--font-accent);
  font-size: var(--fs-eyebrow);
  letter-spacing: .12em;
  text-transform: uppercase;
}
.fz-meter b {
  display: block;
  font-family: var(--font-heading);
}
.fz-meter span.fz-meter__body {
  font-size: var(--fs-small);
  color: var(--color-ink-2);
}
@keyframes fz-fill {
  from { transform: scaleX(0); }
  to   { transform: scaleX(1); }
}

/* ---------- Do / don't ---------- */
.fz-duo {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: var(--space-4);
  margin: var(--space-5) 0;
}
.fz-duo > div {
  padding: var(--space-5);
  border-radius: var(--radius-lg);
}
.fz-duo h3 {
  display: flex;
  gap: var(--space-2);
  align-items: center;
  margin-block-end: var(--space-2);
  font-size: 1.05rem;
}
.fz-duo ul {
  margin: 0;
  padding-inline-start: var(--space-5);
  font-size: var(--fs-small);
}
.fz-duo__do {
  background: var(--color-card-tint-3);
}
.fz-duo__dont {
  background: color-mix(in srgb, var(--color-danger) 8%, var(--color-surface));
}
.fz-duo__dont svg {
  color: var(--color-danger);
}

/* ---------- Dark band: burst pipe ---------- */
.fz-burst {
  margin-block: var(--space-12);
  padding: clamp(var(--space-8), 5vw, var(--space-12));
  border-radius: var(--radius-xl);
  color: var(--color-surface);
  background:
    radial-gradient(ellipse 60% 80% at 100% 0%, color-mix(in srgb, var(--color-accent) 22%, transparent), transparent 65%),
    linear-gradient(155deg, var(--color-primary), var(--color-dark));
  box-shadow: var(--shadow-lg);
}
.fz-burst h2,
.fz-burst h3 {
  color: var(--color-surface);
}
.fz-burst p {
  color: color-mix(in srgb, var(--color-surface) 86%, transparent);
}
.fz-burst .answer-block {
  background: color-mix(in srgb, var(--color-surface) 8%, transparent);
  border-inline-start-color: var(--color-accent-bright);
}
.fz-burst .answer-block p,
.fz-burst .answer-block {
  color: var(--color-surface);
}
.fz-burst p a {
  color: var(--color-accent-bright);
}
.fz-steps {
  counter-reset: fz-step;
  display: grid;
  gap: var(--space-3);
  margin: var(--space-5) 0 0;
  padding: 0;
  list-style: none;
}
.fz-steps li {
  counter-increment: fz-step;
  position: relative;
  padding: var(--space-3) var(--space-4) var(--space-3) var(--space-12);
  border-radius: var(--radius);
  background: color-mix(in srgb, var(--color-surface) 8%, transparent);
}
.fz-steps li::before {
  content: counter(fz-step);
  position: absolute;
  inset-block-start: var(--space-3);
  inset-inline-start: var(--space-4);
  display: grid;
  place-items: center;
  width: 30px;
  height: 30px;
  border-radius: 50%;
  background: var(--color-accent);
  color: var(--color-white);
  font-family: var(--font-accent);
}
.fz-steps b {
  color: var(--color-surface);
}

.fz-safety {
  display: flex;
  gap: var(--space-4);
  align-items: flex-start;
  margin: var(--space-6) 0;
  padding: var(--space-5) var(--space-6);
  border-radius: var(--radius-lg);
  border: 1px dashed var(--color-warning);
  background: color-mix(in srgb, var(--color-warning) 9%, var(--color-surface));
}
.fz-safety svg {
  flex: 0 0 auto;
  color: var(--color-warning);
}
.fz-safety p {
  margin: 0;
  font-size: var(--fs-small);
}

/* ---------- Related ---------- */
.fz-related {
  margin-block-start: var(--space-16);
}
.fz-related h2 {
  margin-block-end: var(--space-4);
}
.fz-related .service-links {
  display: grid;
  gap: var(--space-2);
  margin: 0 0 var(--space-8);
  padding: 0;
  list-style: none;
}
.fz-related .service-links a {
  display: flex;
  justify-content: space-between;
  gap: var(--space-4);
  padding: var(--space-3) var(--space-5);
  border-radius: var(--radius);
  border: 1px solid var(--color-line);
  background: var(--color-surface);
  text-decoration: none;
  font-weight: 600;
  transition: border-color var(--transition), transform var(--transition);
}
.fz-related .service-links a:hover {
  border-color: var(--color-primary);
  transform: translateX(var(--space-1));
}

@media (max-width: 1000px) {
  .fz-layout {
    grid-template-columns: 1fr;
    grid-template-areas: "rail" "article";
  }
  .fz-rail {
    position: static;
    grid-template-columns: 1fr 1fr;
  }
}
@media (max-width: 640px) {
  .fz-rail,
  .fz-duo {
    grid-template-columns: 1fr;
  }
  .fz-meter li {
    grid-template-columns: 1fr;
    gap: var(--space-2);
  }
}
@media (prefers-reduced-motion: reduce) {
  .fz-frost,
  .fz-meter li::before {
    animation: none;
  }
  .fz-toc a,
  .fz-related .service-links a {
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
<section class="hero hero--interior fz-hero" aria-labelledby="fz-h1">
  <span class="fz-frost" aria-hidden="true"></span>
  <div class="container">
    <nav class="breadcrumb" aria-label="Breadcrumb">
      <ol>
        <li><a href="/">Home</a></li>
        <li class="breadcrumb-sep" aria-hidden="true">/</li>
        <li><a href="/blog/">Blog</a></li>
        <li class="breadcrumb-sep" aria-hidden="true">/</li>
        <li><span aria-current="page">Hard freeze checklist</span></li>
      </ol>
    </nav>
    <span class="eyebrow"><?php echo h($post['category']); ?></span>
    <h1 class="hero-title" id="fz-h1"><?php echo h($post['title']); ?></h1>
    <ul class="fz-meta">
      <li><?php echo icon('calendar', 16); ?><time datetime="<?php echo h($post['dateISO']); ?>"><?php echo h($post['date']); ?></time></li>
      <li><?php echo icon('clock', 16); ?><?php echo h($post['readtime']); ?></li>
      <li><?php echo icon('info', 16); ?>Published by Page One Insights LLC</li>
    </ul>
  </div>
</section>

<div class="section fz-wrap">
  <div class="container-wide fz-layout">

    <article class="fz-article" aria-labelledby="fz-h1">
      <figure class="fz-featured blog-post__featured-image">
        <?php echo pictureTag($post['photo'], $post['alt'], '(max-width: 533px) calc(100vw - 2rem), (max-width: 1000px) 94vw, (max-width: 1464px) calc(100vw - 418px), 1046px', ['loading' => 'eager', 'fetchpriority' => 'high']); ?>
        <figcaption>A floor cut open to reach the plumbing under a slab.</figcaption>
      </figure>

      <div class="blog-post__content fz-body">

        <p class="answer-block">Before a hard freeze, find and test your main water shutoff, protect the pipes that are exposed to cold air, disconnect hoses and shut down sprinklers, and let faucets on exterior walls drip on the coldest nights. If a pipe splits, close the main shutoff first, then call a plumber. This checklist is written for San Angelo homes.</p>

        <section id="before-the-freeze" aria-labelledby="h-before">
          <h2 id="h-before">What should you do before a hard freeze reaches San Angelo?</h2>
          <p>Prolonged hard freezes are rare in West Texas, so many Texas homes were built for heat rather than a deep freeze, with supply lines running through attics, garages and exterior walls. The February 2021 winter storm showed across Texas how quickly an unprotected line fails when one does arrive. A few minutes of preparation is the whole defense.</p>
          <ul class="fz-checklist">
            <li class="reveal-up"><?php echo icon('search', 22); ?><div><b>Find the main shutoff and turn it</b><span>It is usually where the line enters the house, and there is a second valve at the meter in a box near the curb. Turn the house valve fully closed and back open once now, so you know it works before you need it.</span></div></li>
            <li class="reveal-up reveal-delay-1"><?php echo icon('droplets', 22); ?><div><b>Disconnect hoses and cover the spigots</b><span>A hose left attached holds water against the faucet. Remove it, then cover each outdoor spigot with an insulated cap.</span></div></li>
            <li class="reveal-up reveal-delay-2"><?php echo icon('sprout', 22); ?><div><b>Shut the sprinkler system off</b><span>Turn off the controller and the supply, and protect or drain the valves and pipe that sit above ground.</span></div></li>
            <li class="reveal-up reveal-delay-3"><?php echo icon('flame', 22); ?><div><b>Wrap lines in the garage and attic</b><span>Foam sleeves on exposed supply lines to the water heater, and a look at anything running along an outside wall.</span></div></li>
            <li class="reveal-up"><?php echo icon('house', 22); ?><div><b>Keep the house warm</b><span>Leave the heat on, and open the cabinet doors under sinks on exterior walls so room air reaches the pipes.</span></div></li>
          </ul>
        </section>

        <section id="first-to-freeze" aria-labelledby="h-first">
          <h2 id="h-first">Which pipes in a San Angelo home freeze first?</h2>
          <p>The pipes most exposed to cold air freeze first: outdoor spigots, irrigation valves, and supply lines in unheated garages and attics. Lines inside exterior walls come next, and lines buried under the slab are the least likely to freeze. The meter below ranks them by exposure.</p>
          <ul class="fz-meter" aria-label="Pipes ranked by freeze exposure">
            <?php foreach ($fzRisk as $risk): ?>
            <li data-level="<?php echo h($risk['level']); ?>"><span class="fz-meter__tag"><?php echo h($risk['label']); ?></span><span><b><?php echo h($risk['title']); ?></b><span class="fz-meter__body"><?php echo h($risk['body']); ?></span></span></li>
            <?php endforeach; ?>
          </ul>
          <p>Sprinkler damage tends to show up as a zone that leaks or will not run once the weather warms. Rock'n G Plumbing handles <a href="/services/irrigation-sprinkler-repair/">sprinkler and irrigation repair</a>, and the heater lines in an unheated garage are one more reason to read <a href="/blog/water-heater-replacement-cost-san-angelo/">what affects water heater replacement cost</a> before a tank fails in the cold.</p>
        </section>

        <section id="dripping" aria-labelledby="h-drip">
          <h2 id="h-drip">Should you let faucets drip during a hard freeze?</h2>
          <p>Yes, on the coldest nights. Moving water is much harder to freeze, and a drip also relieves the pressure that builds between a small ice blockage and a closed faucet, which is where pipes usually split. Choose the faucets on exterior walls or at the far end of the house from where the line enters, and run both hot and cold at a slow, steady drip.</p>
          <div class="fz-duo">
            <div class="fz-duo__do reveal-left">
              <h3><?php echo icon('check', 20); ?>Do</h3>
              <ul>
                <li>Drip hot and cold at faucets on exterior walls.</li>
                <li>Open cabinet doors under those sinks.</li>
                <li>Keep the thermostat steady day and night.</li>
              </ul>
            </div>
            <div class="fz-duo__dont reveal-right">
              <h3><?php echo icon('x', 20); ?>Don't</h3>
              <ul>
                <li>Use an open flame or torch on a frozen pipe.</li>
                <li>Turn the heat down while you are away.</li>
                <li>Assume a quiet pipe is a safe pipe.</li>
              </ul>
            </div>
          </div>
        </section>

        <?php
        $ctaBandId = 'cta-band-freeze';
        $ctaBandTitle = 'Want a plumber to look before the cold?';
        $ctaBandText = "Tell Rock'n G Plumbing which pipes worry you: a garage heater, an exterior wall, a sprinkler system. The estimate is free.";
        include $_SERVER['DOCUMENT_ROOT'] . '/includes/cta-band.php';
        ?>

        <section id="frozen-not-burst" aria-labelledby="h-frozen">
          <h2 id="h-frozen">What should you do if a pipe freezes but has not burst?</h2>
          <p>If a faucet runs dry or trickles in freezing weather, a pipe is probably frozen. Open that faucet, then warm the pipe slowly with a hair dryer on low, working from the faucet back toward the frozen section. Never use a torch or open flame. If you cannot find the frozen spot, or you see any leak, close the main shutoff and call a plumber.</p>
        </section>

        <div class="fz-burst">
          <section id="burst-pipe" aria-labelledby="h-burst">
            <h2 id="h-burst">What should you do if a pipe bursts?</h2>
            <p class="answer-block">If a pipe bursts, close the main water shutoff immediately, then open the faucets to drain what is left in the lines. Turn off the electricity to any area where water is near outlets or appliances. Then call a plumber. Rock'n G Plumbing's office takes calls <?php echo h($weekday[0]); ?>, <?php echo h($weekday[1]); ?>, so the shutoff valve you found earlier is your fastest protection at any other time.</p>
            <ol class="fz-steps">
              <li><b>Shut off the water at the main.</b> That is the valve you tested earlier. Every minute it stays open adds to the damage.</li>
              <li><b>Open faucets to drain the lines</b>, starting with the lowest one in the house.</li>
              <li><b>Cut power where water is near electrical.</b> Use the breaker, and only if reaching it is dry and safe.</li>
              <li><b>Switch off the water heater</b> if the lines may drain, so it does not run dry.</li>
              <li><b>Photograph the damage</b> before you move anything, then call a plumber at <a href="<?php echo h($telHref); ?>"><?php echo h($phoneFmt); ?></a>.</li>
            </ol>
          </section>
        </div>

        <div class="fz-safety" role="note">
          <?php echo icon('flame', 22); ?>
          <p><strong>Smell gas on a freezing night?</strong> Furnaces and gas heaters run harder in a freeze. A rotten-egg odor means leave the house and call 911 or your gas utility from outside before anything else. Rock'n G handles <a href="/services/gas-line-services/">gas line installation and repair</a> once it is safe.</p>
        </div>

        <section id="after-the-freeze" aria-labelledby="h-after">
          <h2 id="h-after">How do you check for hidden damage after the freeze?</h2>
          <p>A pipe can crack when it freezes and only leak once it thaws, sometimes inside a wall or under a floor. After the weather warms, turn every faucet and appliance off and look at the water meter. Many meters have a small triangle or dial that spins when any water is moving. If it turns with everything off, water is going somewhere you cannot see.</p>
          <p>Other signs are a wet or stained patch on a wall or ceiling, a warm spot on the floor, the sound of running water in a quiet house, or a water bill that climbed without a reason. Rock'n G Plumbing finds hidden and under-slab leaks and repairs the water line; see <a href="/services/leak-detection-repair/">leak detection and slab leak repair</a>. If you are out past San Angelo, the <a href="/areas-we-serve/">towns we serve</a> page lists where the crew works, and the office can tell you when it can get to you.</p>
        </section>

      </div>

      <div class="fz-related blog-post__related-services">
        <h2>Which Rock'n G Plumbing services go with this checklist?</h2>
        <p>Leak detection, sprinkler repair and water heater service are the Rock'n G Plumbing services this checklist points to, and each page below explains the work.</p>
        <ul class="service-links">
          <li><a href="/services/leak-detection-repair/">Leak Detection &amp; Slab Leak Repair <span aria-hidden="true">→</span></a></li>
          <li><a href="/services/irrigation-sprinkler-repair/">Sprinkler &amp; Irrigation Repair <span aria-hidden="true">→</span></a></li>
          <li><a href="/services/water-heaters/">Water Heater Repair &amp; Installation <span aria-hidden="true">→</span></a></li>
          <li><a href="/areas-we-serve/">Areas We Serve Around San Angelo <span aria-hidden="true">→</span></a></li>
          <li><a href="/contact/">Contact Rock'n G Plumbing <span aria-hidden="true">→</span></a></li>
        </ul>

        <div class="blog-post__related-articles">
          <h2>What else should you read before calling a plumber?</h2>
          <p>The other Rock'n G Plumbing guides cover plumbing questions for San Angelo homes, starting with the newest below.</p>
          <div class="blog-grid">
            <?php foreach (relatedBlogPosts($bpSlug, 3) as $rel) echo renderBlogCard($rel); ?>
          </div>
        </div>
      </div>
    </article>

    <aside class="fz-rail" aria-label="Guide navigation and contact">
      <nav class="fz-toc" aria-labelledby="fz-toc-title">
        <h2 id="fz-toc-title">In this guide</h2>
        <ul>
          <?php foreach ($toc as $anchor => $label): ?><li><a href="#<?php echo h($anchor); ?>"><?php echo h($label); ?></a></li><?php endforeach; ?>
        </ul>
      </nav>
      <div class="fz-sidecta">
        <h2>Free estimate</h2>
        <p><?php echo h($weekday[0]); ?>, <?php echo h($weekday[1]); ?>. Texas Master Plumber <?php echo h($license['number']); ?>.</p>
        <a class="btn btn-accent" href="/contact/" data-open-estimate>Get a Free Estimate</a>
        <a class="link-call" href="<?php echo h($telHref); ?>"><?php echo icon('phone', 18); ?><?php echo h($phoneFmt); ?></a>
      </div>
    </aside>

  </div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>
