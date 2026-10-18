<?php
/**
 * sitemap.php — dynamic sitemap, served at /sitemap.xml by the .htaccess rewrite. Never hand-edit URL lists here:
 *   - pages come from the site's own directories (every /something/index.php that is not a system folder),
 *   - blog posts come from $blogPosts in includes/blog-data.php (lastmod = dateISO), with the post photo attached,
 *   - service pages carry their card photo, so the separate sitemap-images.xml is not needed.
 * Noindex pages (thank-you, 404) and the build folders (includes, assets, references) are never listed.
 *
 * STATIC COPY: /sitemap.xml at the repo root is a snapshot of this script's output for nginx hosting (Apache keeps
 * rewriting /sitemap.xml here). It must be regenerated whenever a blog post, service page or other page is added,
 * removed or renamed — otherwise nginx keeps serving the old URL list.
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/functions.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/blog-data.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/service-cards.php';

header('Content-Type: application/xml; charset=utf-8');

$smRoot = rtrim($_SERVER['DOCUMENT_ROOT'], '/');
$smBase = rtrim($siteUrl, '/');
$smSkip = ['includes', 'assets', 'references', 'blog', 'thank-you'];     // blog/ is added explicitly below; its posts come from the registry
$smCards = serviceCardData();

$smPages = [['path' => '/', 'file' => $smRoot . '/index.php', 'priority' => '1.0', 'changefreq' => 'weekly']];
foreach ((array) glob($smRoot . '/*/index.php') as $smFile) {
    $smDir = basename(dirname($smFile));
    if (in_array($smDir, $smSkip, true) || $smDir[0] === '.') continue;
    $smPriority = '0.6'; $smFreq = 'monthly';
    if ($smDir === 'services')                         { $smPriority = '0.8'; }
    elseif (in_array($smDir, ['privacy-policy', 'terms', 'cookie-policy', 'accessibility'], true)) { $smPriority = '0.3'; $smFreq = 'yearly'; }
    $smPages[] = ['path' => '/' . $smDir . '/', 'file' => $smFile, 'priority' => $smPriority, 'changefreq' => $smFreq];
}
// Nested pages: /services/{slug}/ and /service-area/{town}/
foreach ((array) glob($smRoot . '/services/*/index.php') as $smFile) {
    $smSlug = basename(dirname($smFile));
    $smImg = isset($smCards[$smSlug]) ? ['src' => '/assets/images/' . $smCards[$smSlug]['photo'] . '.jpg', 'caption' => $smCards[$smSlug]['alt']] : null;
    $smPages[] = ['path' => '/services/' . $smSlug . '/', 'file' => $smFile, 'priority' => '0.8', 'changefreq' => 'monthly', 'image' => $smImg];
}
foreach ((array) glob($smRoot . '/service-area/*/index.php') as $smFile) {
    $smPages[] = ['path' => '/service-area/' . basename(dirname($smFile)) . '/', 'file' => $smFile, 'priority' => '0.6', 'changefreq' => 'monthly'];
}
if (is_file($smRoot . '/blog/index.php')) {
    $smPages[] = ['path' => '/blog/', 'file' => $smRoot . '/blog/index.php', 'priority' => '0.6', 'changefreq' => 'weekly'];
}

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
<?php foreach ($smPages as $smPage): ?>
  <url>
    <loc><?php echo h($smBase . $smPage['path']); ?></loc>
    <lastmod><?php echo date('Y-m-d', (int) @filemtime($smPage['file'])); ?></lastmod>
    <changefreq><?php echo h($smPage['changefreq']); ?></changefreq>
    <priority><?php echo h($smPage['priority']); ?></priority>
<?php if (!empty($smPage['image'])): ?>
    <image:image>
      <image:loc><?php echo h($smBase . $smPage['image']['src']); ?></image:loc>
      <image:caption><?php echo h($smPage['image']['caption']); ?></image:caption>
    </image:image>
<?php endif; ?>
  </url>
<?php endforeach; ?>
<?php foreach ($blogPosts as $smPost): ?>
  <url>
    <loc><?php echo h($smBase . '/blog/' . $smPost['slug'] . '/'); ?></loc>
    <lastmod><?php echo h($smPost['dateISO']); ?></lastmod>
    <changefreq>monthly</changefreq>
    <priority>0.6</priority>
    <image:image>
      <image:loc><?php echo h($smBase . '/assets/images/' . $smPost['photo'] . '.jpg'); ?></image:loc>
      <image:caption><?php echo h($smPost['alt']); ?></image:caption>
    </image:image>
  </url>
<?php endforeach; ?>
</urlset>
