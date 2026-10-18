<?php
/**
 * includes/service-cards.php — data + renderer for the REQUIRED services-grid component
 * (references/required-components.md) on /services/ and in the related-services block of every
 * /services/{slug}/ page. Photos, icons and bullets match the homepage grid in index.php.
 *
 * Photos are the client's own (assets/images/rockn-*). Water heater and sprinkler/irrigation have no job photo yet
 * (asked in content-confirmations.json), so those cards reuse the closest job photo and the alt says what it shows.
 * Loop variables use the $sc prefix (shared-include variable rule).
 */
if (!function_exists('serviceCardData')) {
    /** slug => photo, icon, alt, desc, exactly 3 bullets. Keyed by the config.php $services slugs. */
    function serviceCardData(): array {
        return [
            'water-heaters' => [
                'photo' => 'rockn-water-treatment-garage', 'icon' => 'flame',
                'alt'   => 'Water treatment tank and blue filter housings plumbed into a garage utility corner',
                'desc'  => 'Water heater repair, full replacement, and tankless water heater installation.',
                'bullets' => ['Repairs on failing tank heaters', 'Worn-out tanks replaced', 'Tankless units installed'],
            ],
            'water-filtration-softeners' => [
                'photo' => 'rockn-water-softener-filtration', 'icon' => 'droplets',
                'alt'   => 'Water softener, brine tank and whole-house filter housings installed in a garage',
                'desc'  => "Softeners, reverse osmosis and whole-house filters for San Angelo's hard water.",
                'bullets' => ['Water softener installation', 'Reverse osmosis drinking water', 'Whole-house filtration'],
            ],
            'drain-cleaning' => [
                'photo' => 'rockn-under-floor-drain-lines', 'icon' => 'waves',
                'alt'   => 'White PVC drain lines run down through the floor framing of a home under repair',
                'desc'  => 'Clearing kitchen, bathroom and laundry drains that run slow or back up.',
                'bullets' => ['Kitchen and bathroom drains', 'Slow and repeat clogs', 'Backups cleared, not masked'],
            ],
            'sewer-line-repair' => [
                'photo' => 'rockn-trench-dig', 'icon' => 'search',
                'alt'   => "Rock'n G plumber standing in an excavated trench to reach a buried line",
                'desc'  => 'Camera inspection to find the problem, then sewer line repair or cleaning.',
                'bullets' => ['See the problem on camera', 'Main line repair and cleaning', 'Dig-and-repair when needed'],
            ],
            'leak-detection-repair' => [
                'photo' => 'rockn-pipe-through-slab', 'icon' => 'layers',
                'alt'   => 'Drain pipe exposed in a trench cut through a concrete slab',
                'desc'  => 'Finding hidden and under-slab leaks, then repairing or replacing the water line.',
                'bullets' => ['Hidden leaks tracked down', 'Slab leak repair', 'Water line repair or replacement'],
            ],
            'gas-line-services' => [
                'photo' => 'rockn-gas-line-generator', 'icon' => 'wrench',
                'alt'   => 'Gas line run along the brick wall of a home to a standby generator',
                'desc'  => 'New gas lines and repairs for appliances, generators and household equipment.',
                'bullets' => ['New lines for appliances', 'Runs to standby generators', 'Repairs on existing gas piping'],
            ],
            'irrigation-sprinkler-repair' => [
                'photo' => 'rockn-crew-excavation', 'icon' => 'sprout',
                'alt'   => "Rock'n G crew with a mini excavator and hand tools working on a buried line",
                'desc'  => "Repairs to lawn sprinkler and irrigation systems that leak or won't run.",
                'bullets' => ['Broken heads and leaking lines', 'Irrigation system repairs', 'Lawn sprinkler troubleshooting'],
            ],
            'new-construction-remodel-plumbing' => [
                'photo' => 'rockn-kitchen-remodel', 'icon' => 'hammer',
                'alt'   => 'Remodeled kitchen with a farmhouse sink in the island and a gas cooktop',
                'desc'  => 'Plumbing for new builds and remodels, residential and commercial.',
                'bullets' => ['Plumbing for new homes', 'Kitchen and bath remodels', 'Commercial build-outs'],
            ],
        ];
    }

    /** config.php $services entry for a slug (null when unknown). */
    function serviceBySlug(string $slug): ?array {
        global $services;
        foreach ((array) $services as $scSvc) {
            if (($scSvc['slug'] ?? '') === $slug) return $scSvc;
        }
        return null;
    }

    /**
     * img "sizes" for the 4-up framework grid inside .container-wide (homepage + /services/): 1 col ≤440, 2 ≤720,
     * 3 ≤1000, else 4; gap 1rem; container-wide = 100vw − 2rem ≤533, 94vw ≤1066, 100vw − 4rem up to 1400px.
     */
    function serviceGridSizes(): string {
        return '(max-width: 440px) calc(100vw - 2rem), (max-width: 533px) calc(50vw - 1.5rem), (max-width: 720px) calc(47vw - .5rem), (max-width: 1000px) calc(31.3vw - .7rem), (max-width: 1464px) calc(25vw - 1.75rem), 338px';
    }

    /**
     * img "sizes" for the 3-up related-services grid inside .container (service + area pages): 1 col ≤$oneColMax,
     * 2 ≤1000, else 3; gap 1rem; container = 100vw − 2rem ≤400, 92vw ≤800, 100vw − 4rem up to 1200px.
     */
    function relatedServiceSizes(int $oneColMax = 640): string {
        return '(max-width: 400px) calc(100vw - 2rem), (max-width: ' . $oneColMax . 'px) 92vw, (max-width: 800px) calc(46vw - .5rem), (max-width: 1000px) calc(50vw - 2.5rem), (max-width: 1200px) calc(33.3vw - 2rem), 368px';
    }

    /**
     * <article class="service-card-with-image"> cards for $slugs in the order given. Tints and reveal delays rotate
     * 1 → 2 → 3 so adjacent cards never share a tint. $sizes is the img "sizes" value for the grid the cards sit in.
     */
    function renderServiceCards(array $slugs, string $sizes = ''): string {
        if ($sizes === '') $sizes = serviceGridSizes();
        $scData = serviceCardData();
        $scOut = '';
        $scI = 0;
        foreach ($slugs as $scSlug) {
            $scSvc = serviceBySlug($scSlug);
            if ($scSvc === null || !isset($scData[$scSlug])) continue;
            $scCard = $scData[$scSlug];
            $scTint = ($scI % 3) + 1;
            $scOut .= '<article class="service-card-with-image card-tint-' . $scTint . ' reveal-up reveal-delay-' . $scTint . '">'
                . '<div class="service-card__image">' . pictureTag($scCard['photo'], $scCard['alt'], $sizes) . '</div>'
                . '<div class="service-card__body">'
                . '<div class="service-card__icon">' . icon($scCard['icon'], 22) . '</div>'
                . '<h3>' . h($scSvc['name']) . '</h3>'
                . '<p class="service-card__desc">' . h($scCard['desc']) . '</p>'
                . '<ul>';
            foreach ($scCard['bullets'] as $scBullet) $scOut .= '<li>' . h($scBullet) . '</li>';
            $scOut .= '</ul>'
                . '<a href="' . h(serviceUrl($scSvc)) . '" class="service-card__cta">Learn more<span class="visually-hidden"> about ' . h($scSvc['name']) . '</span></a>'
                . '</div></article>' . "\n";
            $scI++;
        }
        return $scOut;
    }
}
