<?php
/**
 * includes/blog-data.php — single source of truth for blog post metadata (Rock'n G Plumbing).
 * Newest post FIRST. The blog index, the homepage "From the Blog" card, the related-articles blocks and sitemap.php all
 * read this array — never hardcode a post list anywhere else. Adding a post = one entry here + one /blog/{slug}/index.php.
 *
 * 'image' is the -960.webp variant of a client job photo that exists under /assets/images/; 'photo' is that photo's
 * basename, so pictureTag() can serve the avif/webp set. Titles are <= 60 characters with no brand suffix.
 * Launch topics (Oct 18 2026) come from the build-plan keywords: a water heater cost-factors guide and a freeze guide.
 */
$blogPosts = [
    [
        'slug'     => 'protect-san-angelo-plumbing-before-a-hard-freeze',
        'title'    => 'How to Protect San Angelo Plumbing Before a Hard Freeze',
        'excerpt'  => 'The shutoff valve, the pipes that freeze first, which faucets to drip, and what to do if a pipe splits. A short checklist for a San Angelo home before the first hard freeze.',
        'image'    => '/assets/images/rockn-pipe-through-slab-960.webp',
        'photo'    => 'rockn-pipe-through-slab',
        'alt'      => 'Drain pipe exposed in a trench cut through a concrete slab',
        'date'     => 'October 18, 2026',
        'dateISO'  => '2026-10-18',
        'category' => 'Seasonal Plumbing',
        'readtime' => '6 min read',
    ],
    [
        'slug'     => 'water-heater-replacement-cost-san-angelo',
        'title'    => 'What Affects Water Heater Replacement Cost in San Angelo?',
        'excerpt'  => 'Tank or tankless, gas or electric, venting, gas line size and where the heater sits: the factors that decide the price of a San Angelo water heater replacement, and what a free estimate should spell out.',
        'image'    => '/assets/images/rockn-water-treatment-garage-960.webp',
        'photo'    => 'rockn-water-treatment-garage',
        'alt'      => 'Water treatment tank and blue filter housings plumbed into a garage utility corner',
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

    /** One <a class="blog-card"> from a registry entry; $sizes is the img sizes value of the grid it sits in. */
    function renderBlogCard(array $bp, string $sizes = '(max-width: 600px) 100vw, (max-width: 900px) 50vw, 380px'): string {
        return '<a class="blog-card" href="' . h(blogUrl($bp)) . '">'
            . '<div class="blog-card__image">' . pictureTag($bp['photo'], $bp['alt'], $sizes) . '</div>'
            . '<div class="blog-card__body">'
            . '<span class="blog-card__category">' . h($bp['category']) . '</span>'
            . '<h3 class="blog-card__title">' . h($bp['title']) . '</h3>'
            . '<p class="blog-card__excerpt">' . h($bp['excerpt']) . '</p>'
            . '<span class="blog-card__meta">' . h($bp['date']) . ' · ' . h($bp['readtime']) . '</span>'
            . '<span class="blog-card__cta">Read article</span>'
            . '</div></a>' . "\n";
    }
}
