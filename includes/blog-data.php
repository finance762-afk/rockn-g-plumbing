<?php
/**
 * includes/blog-data.php — single source of truth for blog post metadata (Rock'n G Plumbing).
 * Newest post FIRST. The blog index, the homepage "From the Blog" card, the related-articles blocks and sitemap.php all
 * read this array — never hardcode a post list anywhere else. Adding a post = one entry here + one /blog/{slug}/index.php.
 *
 * 'image' is the -960.webp variant of a client job photo that exists under /assets/images/; 'photo' is that photo's
 * basename, so pictureTag() can serve the avif/webp set. Titles are <= 60 characters with no brand suffix.
 * Launch topics (Oct 18 2026) come from the build-plan keywords: a water heater cost-factors guide and a freeze guide.
 * The first 8 entries are the old site's 8 root-level posts, rewritten (P1 BLOG BOT, Oct 18 2026) and 301'd here from
 * their old root URLs in .htaccess. Title/excerpt/category/readtime are copied verbatim from the migration plan §3.
 */
$blogPosts = [
    [
        'slug'     => 'slab-leaks-pipe-repair-san-angelo',
        'title'    => 'Slab Leaks and Pipe Repair in San Angelo: Signs and Fixes',
        'excerpt'  => 'A ten-minute meter test, the signs of a slab leak, why pipes fail in San Angelo, and how to decide between a repair, a reroute and a repipe.',
        'image'    => '/assets/images/rockn-under-floor-drain-lines-960.webp',
        'photo'    => 'rockn-under-floor-drain-lines',
        'alt'      => 'Plumbing lines exposed in an opened slab floor during a Rock\'n G Plumbing repair in San Angelo',
        'date'     => 'October 18, 2026',
        'dateISO'  => '2026-10-18',
        'category' => 'Leaks & Pipes',
        'readtime' => '7 min read',
    ],
    [
        'slug'     => 'recurring-drain-clogs-san-angelo',
        'title'    => 'Why Your Drains Keep Clogging in San Angelo',
        'excerpt'  => 'A drain that keeps clogging has a cause the last cleaning didn\'t fix. Here\'s how to tell a branch clog from a main-line problem, and what actually solves it.',
        'image'    => '/assets/images/rockn-drain-pipe-buildup-960.webp',
        'photo'    => 'rockn-drain-pipe-buildup',
        'alt'      => 'Inside of a cut-open drain pipe heavily lined with buildup',
        'date'     => 'October 18, 2026',
        'dateISO'  => '2026-10-18',
        'category' => 'Drains & Sewer',
        'readtime' => '6 min read',
    ],
    [
        'slug'     => 'gas-line-services-in-san-angelo-tx-safe-installation-and-repair-solutions',
        'title'    => 'Gas Line Safety, Repair and Installation in San Angelo',
        'excerpt'  => 'What to do if you smell gas, the quieter warning signs, which gas pipes you own, and how new gas lines get permitted and inspected in San Angelo.',
        'image'    => '/assets/images/rockn-gas-line-generator-960.webp',
        'photo'    => 'rockn-gas-line-generator',
        'alt'      => 'Standby generator on a pad beside a brick home in San Angelo',
        'date'     => 'October 18, 2026',
        'dateISO'  => '2026-10-18',
        'category' => 'Gas Lines',
        'readtime' => '7 min read',
    ],
    [
        'slug'     => 'new-construction-vs-remodel-plumbing-in-san-angelo-tx-what-s-different-and-why-it-matters',
        'title'    => 'New Construction vs. Remodel Plumbing in San Angelo',
        'excerpt'  => 'On a new build, every drain goes in before the slab is poured. In a remodel, moving one means cutting concrete. Here\'s how that changes timing, cost and permits.',
        'image'    => '/assets/images/rockn-kitchen-remodel-960.webp',
        'photo'    => 'rockn-kitchen-remodel',
        'alt'      => 'Remodeled kitchen with an island sink and range in a West Texas home',
        'date'     => 'October 18, 2026',
        'dateISO'  => '2026-10-18',
        'category' => 'Remodel & New Construction',
        'readtime' => '8 min read',
    ],
    [
        'slug'     => 'sprinkler-repair-watering-rules-san-angelo',
        'title'    => 'Sprinkler Repair and Watering Rules in San Angelo',
        'excerpt'  => 'The sprinkler problems we see most in San Angelo yards, the City\'s twice-a-week watering rules, and why even a repair needs a City permit.',
        'image'    => '/assets/images/rockn-irrigation-line-foundation-960.webp',
        'photo'    => 'rockn-irrigation-line-foundation',
        'alt'      => 'Old irrigation pipe and control wire exposed in the soil beside a home\'s foundation',
        'date'     => 'October 18, 2026',
        'dateISO'  => '2026-10-18',
        'category' => 'Irrigation',
        'readtime' => '7 min read',
    ],
    [
        'slug'     => 'water-heater-installation-and-repair-services-available-in-christoval-tx',
        'title'    => 'Water Heater Repair and Replacement in Christoval, TX',
        'excerpt'  => 'How to tell whether a Christoval water heater needs a repair or a replacement, what local water does to tanks, and the maintenance that actually extends tank life.',
        'image'    => '/assets/images/rockn-water-softener-filtration-960.webp',
        'photo'    => 'rockn-water-softener-filtration',
        'alt'      => 'Water softener and whole-house filter installed by Rock\'n G Plumbing to protect plumbing from hard water',
        'date'     => 'October 18, 2026',
        'dateISO'  => '2026-10-18',
        'category' => 'Water Heaters',
        'readtime' => '7 min read',
    ],
    [
        'slug'     => 'drain-repair-and-gas-line-work-in-san-angelo-tx-what-to-expect-when-you-need-both',
        'title'    => 'Drain Repair and Gas Line Work in San Angelo, TX',
        'excerpt'  => 'How to decide between repairing and replacing a damaged drain under a San Angelo slab, and what changes when the same job also touches gas.',
        'image'    => '/assets/images/rockn-sewer-pipe-excavated-960.webp',
        'photo'    => 'rockn-sewer-pipe-excavated',
        'alt'      => 'Buried sewer pipe opening exposed in red soil during an excavation',
        'date'     => 'October 18, 2026',
        'dateISO'  => '2026-10-18',
        'category' => 'Drains & Sewer',
        'readtime' => '6 min read',
    ],
    [
        'slug'     => 'what-is-backflow-san-angelo',
        'title'    => 'What Is Backflow? San Angelo\'s Sprinkler Rules Explained',
        'excerpt'  => 'Backflow is water running the wrong way, from your yard back into the public supply. Here\'s why San Angelo requires protection on sprinklers and who\'s allowed to test it.',
        'image'    => '/assets/images/rockn-irrigation-valve-repair-960.webp',
        'photo'    => 'rockn-irrigation-valve-repair',
        'alt'      => 'Exposed irrigation valve and PVC piping in a dug-out pit beside a house',
        'date'     => 'October 18, 2026',
        'dateISO'  => '2026-10-18',
        'category' => 'Water Safety',
        'readtime' => '6 min read',
    ],
    [
        'slug'     => 'protect-san-angelo-plumbing-before-a-hard-freeze',
        'title'    => 'How to Protect San Angelo Plumbing Before a Hard Freeze',
        'excerpt'  => 'The shutoff valve, the pipes that freeze first, which faucets to drip, and what to do if a pipe splits. A short checklist for a San Angelo home before the first hard freeze.',
        'image'    => '/assets/images/rockn-slab-trench-repair-960.webp',
        'photo'    => 'rockn-slab-trench-repair',
        'alt'      => 'Floor cut open in a home laundry area to reach the plumbing under the slab',
        'date'     => 'October 18, 2026',
        'dateISO'  => '2026-10-18',
        'category' => 'Seasonal Plumbing',
        'readtime' => '6 min read',
    ],
    [
        'slug'     => 'water-heater-replacement-cost-san-angelo',
        'title'    => 'What Affects Water Heater Replacement Cost in San Angelo?',
        'excerpt'  => 'Tank or tankless, gas or electric, venting, gas line size and where the heater sits: the factors that decide the price of a San Angelo water heater replacement, and what a free estimate should spell out.',
        'image'    => '/assets/images/rockn-tankless-water-heater-960.webp',
        'photo'    => 'rockn-tankless-water-heater',
        'alt'      => 'Wall-mounted tankless water heater with copper and PEX supply manifolds in a utility room',
        'date'     => 'October 18, 2026',
        'dateISO'  => '2026-10-18',
        'category' => 'Water Heaters',
        'readtime' => '6 min read',
    ],
];

if (!function_exists('blogPostBySlug')) {
    /** Registry entry for a slug (null when unknown). */
    function blogPostBySlug(string $slug): ?array {
        global $blogPosts;
        foreach ((array) $blogPosts as $bp) {
            if (($bp['slug'] ?? '') === $slug) return $bp;
        }
        return null;
    }

    /** Up to $limit other posts: same category first, then the newest of the rest. */
    function relatedBlogPosts(string $slug, int $limit = 3): array {
        global $blogPosts;
        $self = blogPostBySlug($slug);
        $same = $rest = [];
        foreach ((array) $blogPosts as $bp) {
            if ($bp['slug'] === $slug) continue;
            if ($self && $bp['category'] === $self['category']) $same[] = $bp; else $rest[] = $bp;
        }
        return array_slice(array_merge($same, $rest), 0, $limit);
    }

    /** '/blog/{slug}/' for a registry entry. */
    function blogUrl(array $bp): string {
        return '/blog/' . $bp['slug'] . '/';
    }

    /**
     * One <a class="blog-card"> from a registry entry; $sizes is the img sizes value of the grid it sits in.
     * Default = the related-articles .blog-grid in a post's article column (container-wide minus the 300px rail):
     * 1 col ≤600, 2 ≤900, else 3; gap 1.25rem.
     */
    function renderBlogCard(array $bp, string $sizes = '(max-width: 533px) calc(100vw - 2rem), (max-width: 600px) 94vw, (max-width: 900px) calc(47vw - .625rem), (max-width: 1000px) calc(31.3vw - .85rem), (max-width: 1464px) calc(33.3vw - 156px), 332px'): string {
        return '<a class="blog-card" href="' . h(blogUrl($bp)) . '">'
            . '<div class="blog-card__image">' . pictureTag($bp['photo'], $bp['alt'], $sizes, ['card' => true]) . '</div>'
            . '<div class="blog-card__body">'
            . '<span class="blog-card__category">' . h($bp['category']) . '</span>'
            . '<h3 class="blog-card__title">' . h($bp['title']) . '</h3>'
            . '<p class="blog-card__excerpt">' . h($bp['excerpt']) . '</p>'
            . '<span class="blog-card__meta">' . h($bp['date']) . ' · ' . h($bp['readtime']) . '</span>'
            . '<span class="blog-card__cta">Read article</span>'
            . '</div></a>' . "\n";
    }
}
