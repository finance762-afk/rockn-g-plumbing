<?php
/**
 * includes/head.php — doctype, <head>, SEO, OG, fonts, critical CSS, schema. Opens <body>.
 *
 * Page contract — set any of these BEFORE including this file (all optional):
 *   $pageTitle        string  ≤60 chars. Default: "Plumber in San Angelo, TX | Rock'n G Plumbing" (from $primaryKeyword)
 *   $pageDescription  string  140–160 chars. Default built from config.php facts ($metaDescription is accepted as an alias)
 *   $canonicalUrl     string  absolute. Default computed from the request URI (never hand-set per page)
 *   $ogImage          string  path or absolute URL, 1200×630. Default /assets/images/og-default-v1.jpg
 *   $ogType           string  default 'website' ('article' on blog posts)
 *   $currentPage      string  drives nav active state: 'home', 'about', 'services', 'services/{slug}', …
 *   $noindex          bool    true on thank-you / 404
 *   $heroPreload      array   ['srcset' => '… 480w, … 960w, … 1600w', 'sizes' => '100vw'] for a photo hero (avif)
 *   $schemaMarkup     string|array  page JSON-LD (one node, a JSON string, or a list of either)
 *   $breadcrumbs      array   [['name'=>'Services','url'=>'/services/'], …] → BreadcrumbList (Home prepended)
 *   $extraHead        string  raw HTML appended to <head> (page-level <style> etc.)
 * Variables created here use the $hd prefix (shared-include variable rule).
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/functions.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/p1-form-helpers.php';

$hdTitle = !empty($pageTitle) ? $pageTitle : defaultPageTitle();
$hdDesc  = !empty($pageDescription) ? $pageDescription : (!empty($metaDescription) ? $metaDescription : defaultMetaDescription());
$canonicalUrl = !empty($canonicalUrl) ? $canonicalUrl : rtrim($siteUrl, '/') . computeCanonicalPath();
$hdOgImage = absUrl(!empty($ogImage) ? $ogImage : defaultOgImage());
$hdOgType  = !empty($ogType) ? $ogType : 'website';
$hdIsHome  = (isset($currentPage) && $currentPage === 'home');
$hdNoindex = !empty($noindex) || empty($allowIndexing);   // staging stays noindex until config.php $allowIndexing = true at launch
if ($hdNoindex && !headers_sent()) header('X-Robots-Tag: noindex, nofollow');
$hdBodyClass = 'has-sticky-bar' . (isset($currentPage) && $currentPage !== '' ? ' page-' . preg_replace('/[^a-z0-9]+/', '-', strtolower($currentPage)) : '');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php echo generateMetaTags($hdTitle, $hdDesc, $canonicalUrl), "\n"; ?>
<?php if ($hdNoindex): ?>
<meta name="robots" content="noindex, nofollow">
<?php endif; ?>
<meta name="theme-color" content="<?php echo h($colors['primary']); ?>">

<!-- Open Graph -->
<meta property="og:type" content="<?php echo h($hdOgType); ?>">
<meta property="og:title" content="<?php echo h($hdTitle); ?>">
<meta property="og:description" content="<?php echo h($hdDesc); ?>">
<meta property="og:url" content="<?php echo h($canonicalUrl); ?>">
<meta property="og:image" content="<?php echo h($hdOgImage); ?>">
<meta property="og:site_name" content="<?php echo h($siteName); ?>">
<meta property="og:locale" content="en_US">

<!-- Fonts: self-hosted (declared in framework.css). Preload only the above-the-fold heading face. -->
<link rel="preload" href="/assets/fonts/bricolage-grotesque.woff2" as="font" type="font/woff2" crossorigin>

<!-- CSS: critical subset inlined, full framework loaded non-blocking -->
<style><?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/critical.css'; ?></style>
<link rel="preload" href="/assets/css/framework.css?v=<?php echo $cssVersion; ?>" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="/assets/css/framework.css?v=<?php echo $cssVersion; ?>"></noscript>
<?php if (!empty($heroPreload) && !empty($heroPreload['srcset'])): ?>
<link rel="preload" as="image" type="image/avif" imagesrcset="<?php echo h($heroPreload['srcset']); ?>" imagesizes="<?php echo h($heroPreload['sizes'] ?? '100vw'); ?>" fetchpriority="high">
<?php endif; ?>

<!-- Favicons -->
<link rel="icon" type="image/svg+xml" href="/assets/images/favicon.svg">
<link rel="icon" type="image/png" sizes="32x32" href="/assets/images/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/assets/images/favicon-16x16.png">

<?php if (preg_match('/^G-[A-Z0-9]{8,}$/', (string) $googleAnalyticsId) && $googleAnalyticsId !== 'G-XXXXXXXXXX'): ?>
<!-- Google Analytics 4 -->
<script defer src="https://www.googletagmanager.com/gtag/js?id=<?php echo h($googleAnalyticsId); ?>"></script>
<script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','<?php echo h($googleAnalyticsId); ?>');</script>
<?php else: /* GA4 not wired yet: set $googleAnalyticsId (G-XXXXXXXXXX) in includes/config.php and the tag below prints automatically. Nothing is emitted until then. */ ?>
<?php endif; ?>
<?php if (!empty($gscVerification)): ?>
<meta name="google-site-verification" content="<?php echo h($gscVerification); ?>">
<?php endif; ?>

<?php
// JSON-LD — Plumber/LocalBusiness on every page (NAP identical to the GBP listing; no street address, no self-serving rating markup)
echo schemaScript(buildBusinessSchema()), "\n";
if (!empty($breadcrumbs) && !$hdIsHome) echo schemaScript(generateBreadcrumbSchema($breadcrumbs)), "\n";
if (!empty($schemaMarkup)) {
    $hdBlocks = (is_array($schemaMarkup) && array_is_list($schemaMarkup)) ? $schemaMarkup : [$schemaMarkup];
    foreach ($hdBlocks as $hdBlock) echo schemaScript($hdBlock), "\n";
}
if (!empty($extraHead)) echo $extraHead, "\n";
?>
</head>
<body class="<?php echo h($hdBodyClass); ?>">
