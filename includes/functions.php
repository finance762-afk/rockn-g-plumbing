<?php
/**
 * includes/functions.php — shared helpers (Rock'n G Plumbing, Phase 2).
 * Loaded by head.php (require_once) after config.php. Every function is guarded so a page may also
 * require it directly. Helpers read site data through `global` from config.php; none of them invents a value.
 *
 * NOTE (SEO brief, Oct 18 2026): this site publishes FAQs as visible content only — do NOT emit
 * generateFAQSchema() output on its pages. The helper exists because the shared include contract asks for it.
 */

if (!function_exists('h')) {
    /** HTML-escape for text and attribute context. */
    function h($value): string {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('slugify')) {
    function slugify(string $text): string {
        $text = strtolower(trim($text));
        $text = str_replace('&', ' and ', $text);
        $text = preg_replace('/[^a-z0-9]+/', '-', $text);
        return trim((string) $text, '-');
    }
}

/* ---- Navigation state -------------------------------------------------------------------------- */

if (!function_exists('isActivePage')) {
    /** Exact match against the page's $currentPage ('home', 'about', 'contact', 'services', 'services/water-heaters' …). */
    function isActivePage(string $page): bool {
        global $currentPage;
        return isset($currentPage) && $currentPage === $page;
    }
}

if (!function_exists('isActiveGroup')) {
    /** True when $currentPage is the hub or any child of it: isActiveGroup('services') matches 'services' and 'services/…'. */
    function isActiveGroup(string $prefix): bool {
        global $currentPage;
        if (!isset($currentPage)) return false;
        return $currentPage === $prefix || strpos((string) $currentPage, $prefix . '/') === 0;
    }
}

if (!function_exists('ariaCurrentGroup')) {
    /** Hub link of a nav group: aria-current="page" on the hub itself, "true" on any child page, else ''. */
    function ariaCurrentGroup(string $prefix): string {
        if (isActivePage($prefix)) return ' aria-current="page"';
        return isActiveGroup($prefix) ? ' aria-current="true"' : '';
    }
}

if (!function_exists('ariaCurrent')) {
    /** ' aria-current="page"' when the page matches, else ''. */
    function ariaCurrent(string $page): string {
        return isActivePage($page) ? ' aria-current="page"' : '';
    }
}

/* ---- Formatting + slugs ------------------------------------------------------------------------ */

if (!function_exists('formatPhone')) {
    /** '3256507000' / '+1 325-650-7000' → '(325) 650-7000'. Anything that is not 10 digits comes back unchanged. */
    function formatPhone($phone): string {
        $digits = preg_replace('/\D+/', '', (string) $phone);
        if (strlen($digits) === 11 && $digits[0] === '1') $digits = substr($digits, 1);
        if (strlen($digits) !== 10) return (string) $phone;
        return '(' . substr($digits, 0, 3) . ') ' . substr($digits, 3, 3) . '-' . substr($digits, 6);
    }
}

if (!function_exists('getServiceSlug')) {
    /** Service name → URL slug. Uses the slug configured in config.php $services (brief-mandated), else slugifies the name. */
    function getServiceSlug(string $name): string {
        global $services;
        foreach ((array) ($services ?? []) as $svc) {
            if (isset($svc['name'], $svc['slug']) && strcasecmp($svc['name'], $name) === 0) return $svc['slug'];
        }
        return slugify($name);
    }
}

if (!function_exists('getAreaSlug')) {
    /** 'Christoval' / 'Christoval, TX' → 'christoval-tx' (matches /service-area/christoval-tx/). */
    function getAreaSlug(string $city): string {
        global $address;
        $city = trim($city);
        if (strpos($city, ',') === false) {
            $state = isset($address['state']) ? (string) $address['state'] : '';
            if ($state !== '') $city .= ' ' . $state;
        }
        return slugify($city);
    }
}

if (!function_exists('serviceUrl')) {
    function serviceUrl(array $service): string {
        return '/services/' . ($service['slug'] ?? getServiceSlug((string) ($service['name'] ?? ''))) . '/';
    }
}

if (!function_exists('absUrl')) {
    /** '/path/' → 'https://domain/path/'; absolute URLs pass through. */
    function absUrl(string $path): string {
        global $siteUrl;
        if (preg_match('#^https?://#i', $path)) return $path;
        return rtrim((string) $siteUrl, '/') . '/' . ltrim($path, '/');
    }
}

/* ---- Link integrity ---------------------------------------------------------------------------- */

if (!function_exists('pageExists')) {
    /** True when '/some/path/' resolves to a real directory page (index.php) or a root-level .php page on disk. */
    function pageExists(string $path): bool {
        $root = rtrim((string) ($_SERVER['DOCUMENT_ROOT'] ?? ''), '/');
        $path = '/' . trim($path, '/');
        if ($path === '/') return is_file($root . '/index.php');
        return is_file($root . $path . '/index.php') || is_file($root . $path . '.php');
    }
}

if (!function_exists('areaNavLinks')) {
    /**
     * Service-area links for nav + footer. San Angelo is served by the homepage ('/'); every other URL is gated
     * with pageExists() so no link ever points at a page that has not been built.
     * @return array{items: array<int,array{name:string,url:string}>, hub: string}  hub = '' until an area page exists
     */
    function areaNavLinks(): array {
        global $serviceAreaDetails;
        $items = [];
        foreach ((array) ($serviceAreaDetails ?? []) as $a) {
            $url = $a['url'] ?? '/';
            if ($url !== '/' && !pageExists($url)) continue;
            $items[] = ['name' => $a['city'] . ', ' . $a['state'], 'url' => $url];
        }
        $hub = pageExists('/areas-we-serve/') ? '/areas-we-serve/' : '';
        $anyBuilt = $hub !== '' || count($items) > 1;
        return ['items' => $items, 'hub' => $anyBuilt ? ($hub !== '' ? $hub : $items[1]['url']) : ''];
    }
}

/* ---- Hours ------------------------------------------------------------------------------------- */

if (!function_exists('hoursGroups')) {
    /** Collapse config $hours into consecutive runs: [['Mon–Fri','8:00 AM – 6:00 PM'], ['Sat–Sun','Closed']]. */
    function hoursGroups(array $hours): array {
        $groups = [];
        foreach ($hours as $row) {
            $last = count($groups) - 1;
            if ($last >= 0 && $groups[$last]['label'] === $row['label']) {
                $groups[$last]['end'] = $row['short'];
            } else {
                $groups[] = ['start' => $row['short'], 'end' => $row['short'], 'label' => $row['label']];
            }
        }
        return array_map(function ($g) {
            $days = $g['start'] === $g['end'] ? $g['start'] : $g['start'] . '–' . $g['end'];
            return [$days, $g['label']];
        }, $groups);
    }
}

/* ---- Icons (inline SVG at build time — never runtime injection) --------------------------------- */

if (!function_exists('icon')) {
    /** Echo-able inline <svg> from references/lucide-icons/<name>.svg with aria-hidden + explicit size. */
    function icon(string $name, int $size = 20, string $class = ''): string {
        static $cache = [];
        $name = preg_replace('/[^a-z0-9-]/', '', strtolower($name));
        if (!isset($cache[$name])) {
            $file = dirname(__DIR__) . '/references/lucide-icons/' . $name . '.svg';
            $svg = is_file($file) ? (string) file_get_contents($file) : '';
            $svg = preg_replace('/<!--.*?-->\s*/s', '', $svg);
            $svg = preg_replace('/\s+/', ' ', trim((string) $svg));
            $cache[$name] = $svg;
        }
        $svg = $cache[$name];
        if ($svg === '') return '';
        $svg = preg_replace('/\swidth="24"/', ' width="' . $size . '"', $svg, 1);
        $svg = preg_replace('/\sheight="24"/', ' height="' . $size . '"', $svg, 1);
        $svg = preg_replace('/<svg\b/', '<svg aria-hidden="true" focusable="false"', $svg, 1);
        if ($class !== '') $svg = preg_replace('/class="([^"]*)"/', 'class="$1 ' . h($class) . '"', $svg, 1);
        return $svg;
    }
}

if (!function_exists('imgDims')) {
    /** [width, height] to render an on-disk image at $height px tall, from its true aspect ratio. Falls back to square. */
    function imgDims(string $webPath, int $height): array {
        $file = rtrim((string) ($_SERVER['DOCUMENT_ROOT'] ?? ''), '/') . $webPath;
        $info = is_file($file) ? @getimagesize($file) : false;
        if (!$info || empty($info[0]) || empty($info[1])) return [$height, $height];
        return [(int) round($info[0] / $info[1] * $height), $height];
    }
}

if (!function_exists('assetVer')) {
    /** '/assets/images/x.webp' → '/assets/images/x.webp?v=<filemtime>' (cache-bust past Cloudflare); unchanged if the file is missing. */
    function assetVer(string $webPath): string {
        $file = rtrim((string) ($_SERVER['DOCUMENT_ROOT'] ?? ''), '/') . $webPath;
        $mtime = is_file($file) ? @filemtime($file) : false;
        return $mtime ? $webPath . '?v=' . $mtime : $webPath;
    }
}

if (!function_exists('imageVariantSrcset')) {
    /** '/assets/images/x-320.avif?v=… 320w, …' for the -320/-480/-640/-960/-1600 variants of $name that exist on disk ('' if none). */
    function imageVariantSrcset(string $name, string $ext): string {
        $root = rtrim((string) ($_SERVER['DOCUMENT_ROOT'] ?? ''), '/');
        $out = [];
        foreach ([320, 480, 640, 960, 1600] as $w) {
            $web = '/assets/images/' . $name . '-' . $w . '.' . $ext;
            if (is_file($root . $web)) $out[] = assetVer($web) . ' ' . $w . 'w';
        }
        return implode(', ', $out);
    }
}

if (!function_exists('pictureTag')) {
    /**
     * <picture> for an on-disk photo: AVIF source first, WebP srcset on the <img>, the .jpg as src.
     * $name is the basename in /assets/images/ without extension. Only variants present on disk are listed.
     * $opts: class (img), loading ('lazy'|'eager'), fetchpriority, width/height (default: true size of the .jpg).
     */
    function pictureTag(string $name, string $alt, string $sizes, array $opts = []): string {
        $src = '/assets/images/' . $name . '.jpg';
        $file = rtrim((string) ($_SERVER['DOCUMENT_ROOT'] ?? ''), '/') . $src;
        $info = is_file($file) ? @getimagesize($file) : false;
        $w   = (int) ($opts['width']  ?? ($info[0] ?? 1200));
        $hgt = (int) ($opts['height'] ?? ($info[1] ?? 800));
        $eager = ($opts['loading'] ?? 'lazy') === 'eager';
        $avif = imageVariantSrcset($name, 'avif');
        $webp = imageVariantSrcset($name, 'webp');
        $out = '<picture>';
        if ($avif !== '') $out .= '<source type="image/avif" srcset="' . h($avif) . '" sizes="' . h($sizes) . '">';
        $out .= '<img src="' . h(assetVer($src)) . '"';
        if ($webp !== '') $out .= ' srcset="' . h($webp) . '" sizes="' . h($sizes) . '"';
        $out .= sprintf(' alt="%s" width="%d" height="%d"', h($alt), $w, $hgt);
        if (!empty($opts['class'])) $out .= ' class="' . h($opts['class']) . '"';
        $out .= $eager ? ' loading="eager"' : ' loading="lazy" decoding="async"';
        if (!empty($opts['fetchpriority'])) $out .= ' fetchpriority="' . h($opts['fetchpriority']) . '"';
        return $out . '></picture>';
    }
}

if (!function_exists('logoPictureTag')) {
    /**
     * <picture> for a logo PNG with its -160/-320 WebP variants (only those on disk are listed); the PNG stays the fallback src.
     * $sizes is the rendered CSS width of the logo; $attrs are extra <img> attributes (class, loading, decoding …).
     */
    function logoPictureTag(string $pngPath, string $alt, int $w, int $h, string $sizes, array $attrs = []): string {
        $root = rtrim((string) ($_SERVER['DOCUMENT_ROOT'] ?? ''), '/');
        $base = preg_replace('/\.png$/i', '', $pngPath);
        $set = [];
        foreach ([160, 320] as $vw) {
            if (is_file($root . $base . '-' . $vw . '.webp')) $set[] = assetVer($base . '-' . $vw . '.webp') . ' ' . $vw . 'w';
        }
        $out = '<picture>';
        if ($set) $out .= '<source type="image/webp" srcset="' . h(implode(', ', $set)) . '" sizes="' . h($sizes) . '">';
        $out .= sprintf('<img src="%s" alt="%s" width="%d" height="%d"', h(assetVer($pngPath)), h($alt), $w, $h);
        foreach ($attrs as $k => $v) $out .= ' ' . h($k) . '="' . h($v) . '"';
        return $out . '></picture>';
    }
}

/* ---- SEO: canonical + meta + title defaults ---------------------------------------------------- */

if (!function_exists('computeCanonicalPath')) {
    /** Self-referencing canonical path from the request: '/', '/about/', '/services/drain-cleaning/', '/thank-you'. */
    function computeCanonicalPath(): string {
        $uri = parse_url((string) ($_SERVER['REQUEST_URI'] ?? '/'), PHP_URL_PATH) ?: '/';
        $uri = preg_replace('#/index\.php$#', '/', $uri);
        $uri = preg_replace('#\.php$#', '', $uri);
        if ($uri === '' || $uri === null) $uri = '/';
        if (substr($uri, -1) !== '/' && is_dir(rtrim((string) $_SERVER['DOCUMENT_ROOT'], '/') . $uri)) $uri .= '/';
        return $uri;
    }
}

if (!function_exists('defaultPageTitle')) {
    /** "Plumber in San Angelo, TX | Rock'n G Plumbing" — the trade noun comes from $primaryKeyword minus city/state (V2.6). */
    function defaultPageTitle(): string {
        global $primaryKeyword, $address, $siteName, $tradeNoun;
        $noun = trim(preg_replace('/\s+/', ' ', str_ireplace([(string) $address['city'], (string) $address['state']], '', (string) $primaryKeyword)));
        if ($noun === '') $noun = (string) ($tradeNoun ?? 'plumber');
        return ucfirst($noun) . ' in ' . $address['city'] . ', ' . $address['state'] . ' | ' . $siteName;
    }
}

if (!function_exists('defaultMetaDescription')) {
    /**
     * [trade noun + city] + [differentiators that config.php states] + [CTA]. Every claim traces to build-plan content:
     * 20+ years, three generations, Texas Master Plumber License, free estimates. No "insured", no emergency/24-hour.
     */
    function defaultMetaDescription(): string {
        global $address, $siteName, $phone, $license, $yearsInBusiness;
        $parts = ['Plumber in ' . $address['city'] . ', ' . $address['state'] . ': ' . $siteName];
        $parts[] = ($yearsInBusiness ? $yearsInBusiness . '+ years, ' : '') . 'three generations of plumbers';
        if (!empty($license['number'])) $parts[] = 'Master Plumber Lic. ' . $license['number'];
        return implode(' • ', $parts) . '. Call ' . $phone . ' for a free estimate.';
    }
}

if (!function_exists('generateMetaTags')) {
    /** <title> + meta description + canonical, escaped. */
    function generateMetaTags(string $title, string $description, string $canonical): string {
        return '<title>' . h($title) . "</title>\n"
             . '<meta name="description" content="' . h($description) . "\">\n"
             . '<link rel="canonical" href="' . h($canonical) . '">';
    }
}

/* ---- Schema ------------------------------------------------------------------------------------ */

if (!function_exists('schemaJson')) {
    function schemaJson($data): string {
        return json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
    }
}

if (!function_exists('schemaScript')) {
    /** <script type="application/ld+json"> for an array, or for an already-encoded JSON string. */
    function schemaScript($data): string {
        $json = is_string($data) ? trim($data) : schemaJson($data);
        return '<script type="application/ld+json">' . "\n" . $json . "\n</script>";
    }
}

if (!function_exists('orgId')) {
    function orgId(): string {
        global $siteUrl;
        return rtrim((string) $siteUrl, '/') . '/#organization';
    }
}

if (!function_exists('defaultOgImage')) {
    /** Site-wide 1200×630 share image (assets/images/og-default-v1.jpg). */
    function defaultOgImage(): string {
        return '/assets/images/og-default-v1.jpg';
    }
}

if (!function_exists('serviceAreaEntities')) {
    /** areaServed entries: configured cities + the towns listed on /areas-we-serve/. */
    function serviceAreaEntities(): array {
        global $serviceAreaDetails, $areasWeServeTowns, $companyState;
        $state = ['@type' => 'State', 'name' => (string) ($companyState ?: 'Texas')];
        $out = [];
        foreach ((array) ($serviceAreaDetails ?? []) as $a) {
            $out[] = ['@type' => 'City', 'name' => $a['city'], 'containedInPlace' => $state];
        }
        foreach ((array) ($areasWeServeTowns ?? []) as $town) {
            $out[] = ['@type' => 'City', 'name' => $town, 'containedInPlace' => $state];
        }
        return $out;
    }
}

if (!function_exists('buildBusinessSchema')) {
    /**
     * Plumber (LocalBusiness subtype) node from config.php. Service-area business: streetAddress is emitted ONLY when
     * $addressPublic is true. No star-rating markup of any kind — the GBP rating lives in visible page content only.
     */
    function buildBusinessSchema(): array {
        global $siteName, $siteUrl, $phoneE164, $email, $businessDescription, $address, $addressPublic, $geo, $gbp,
               $hours, $services, $license, $schemaSameAs;

        $addr = ['@type' => 'PostalAddress'];
        if (!empty($addressPublic) && !empty($address['street'])) $addr['streetAddress'] = $address['street'];
        $addr['addressLocality'] = $address['city'];
        $addr['addressRegion']   = $address['state'];
        if (!empty($address['zip'])) $addr['postalCode'] = $address['zip'];
        $addr['addressCountry']  = 'US';

        // Group identical open/close windows into one OpeningHoursSpecification each.
        $byWindow = [];
        foreach ((array) $hours as $row) {
            if (empty($row['opens'])) continue;
            $byWindow[$row['opens'] . '|' . $row['closes']][] = $row['day'];
        }
        $hoursSpec = [];
        foreach ($byWindow as $win => $days) {
            [$opens, $closes] = explode('|', $win);
            $hoursSpec[] = ['@type' => 'OpeningHoursSpecification', 'dayOfWeek' => $days, 'opens' => $opens, 'closes' => $closes];
        }

        $offers = [];
        foreach ((array) $services as $svc) {
            $offers[] = ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => $svc['name'], 'url' => absUrl(serviceUrl($svc))]];
        }

        $node = [
            '@context'    => 'https://schema.org',
            '@type'       => 'Plumber',
            '@id'         => orgId(),
            'name'        => $siteName,
            'url'         => rtrim((string) $siteUrl, '/') . '/',
            'telephone'   => $phoneE164,
            'email'       => $email,
            'description' => $businessDescription,
            'image'       => absUrl(defaultOgImage()),
            'logo'        => absUrl('/assets/images/logo-mark-v1.png'),
            'address'     => $addr,
            'geo'         => ['@type' => 'GeoCoordinates', 'latitude' => $geo['lat'], 'longitude' => $geo['lng']],
            'hasMap'      => $gbp['url'],
            'openingHoursSpecification' => $hoursSpec,
            'areaServed'  => serviceAreaEntities(),
            'hasOfferCatalog' => ['@type' => 'OfferCatalog', 'name' => 'Plumbing services', 'itemListElement' => $offers],
        ];
        if (!empty($license['label'])) {
            $node['hasCredential'] = [
                '@type' => 'EducationalOccupationalCredential',
                'credentialCategory' => 'license',
                'name' => $license['label'],
                'recognizedBy' => ['@type' => 'Organization', 'name' => $license['issuer']],
            ];
        }
        if (!empty($schemaSameAs)) $node['sameAs'] = array_values($schemaSameAs);
        return $node;
    }
}

if (!function_exists('generateServiceSchema')) {
    /** Service node for a config.php $services entry (or a bare service name). Provider references the #organization node. */
    function generateServiceSchema($service): array {
        global $services;
        if (is_string($service)) {
            $name = $service;
            $service = ['name' => $name, 'slug' => getServiceSlug($name)];
            foreach ((array) $services as $s) if (strcasecmp($s['name'], $name) === 0) { $service = $s; break; }
        }
        $url = absUrl(serviceUrl($service));
        $node = [
            '@context'    => 'https://schema.org',
            '@type'       => 'Service',
            '@id'         => $url . '#service',
            'name'        => $service['name'],
            'serviceType' => $service['name'],
            'url'         => $url,
            'provider'    => ['@id' => orgId()],
            'areaServed'  => serviceAreaEntities(),
        ];
        if (!empty($service['description'])) $node['description'] = ucfirst((string) $service['description']) . '.';
        return $node;
    }
}

if (!function_exists('generateFAQSchema')) {
    /** FAQPage node from [['q' => '…', 'a' => '…'], …] (also accepts 'question'/'answer'). See file header: unused on this site. */
    function generateFAQSchema(array $faqs): array {
        $items = [];
        foreach ($faqs as $f) {
            $q = $f['q'] ?? ($f['question'] ?? '');
            $a = $f['a'] ?? ($f['answer'] ?? '');
            if ($q === '' || $a === '') continue;
            $items[] = ['@type' => 'Question', 'name' => $q, 'acceptedAnswer' => ['@type' => 'Answer', 'text' => $a]];
        }
        return ['@context' => 'https://schema.org', '@type' => 'FAQPage', 'mainEntity' => $items];
    }
}

if (!function_exists('generateBreadcrumbSchema')) {
    /** BreadcrumbList from [['name' => 'Services', 'url' => '/services/'], …]; Home is prepended automatically. */
    function generateBreadcrumbSchema(array $crumbs): array {
        $list = [['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => absUrl('/')]];
        $pos = 2;
        foreach ($crumbs as $c) {
            $item = ['@type' => 'ListItem', 'position' => $pos++, 'name' => $c['name']];
            if (!empty($c['url'])) $item['item'] = absUrl($c['url']);
            $list[] = $item;
        }
        return ['@context' => 'https://schema.org', '@type' => 'BreadcrumbList', 'itemListElement' => $list];
    }
}
