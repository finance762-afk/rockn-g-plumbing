<?php
/**
 * includes/header.php — skip link, fixed glass header, mobile overlay menu; opens <main id="main-content">.
 * Included after head.php (which loads config.php + functions.php). Loop variables use the $nav prefix.
 *
 * Links to pages that may not be built yet (areas, projects, reviews, blog) are gated with pageExists() — a link only
 * renders once its page is on disk. Core pages (services, about, contact) are always linked.
 */
$navLogoSrc = '/assets/images/logo-mark-v1.png';
[$navLogoW, $navLogoH] = imgDims($navLogoSrc, 88);          // square emblem (≈1.01:1) — width/height match the true ratio
$navAreas    = areaNavLinks();
$navProjects = pageExists('/projects/');
$navBlog     = pageExists('/blog/');
$navTel      = 'tel:' . $phoneE164;
$navPhone    = formatPhone($phone);
?>
<a href="#main-content" class="skip-link">Skip to main content</a>

<header class="site-header" data-header>
  <nav class="navbar" aria-label="Main navigation">
    <div class="navbar-inner container">
      <a class="site-logo" href="/" aria-label="<?php echo h($siteName); ?> — home">
        <img class="logo--square" src="<?php echo h($navLogoSrc); ?>" alt="<?php echo h($siteName); ?> logo" width="<?php echo $navLogoW; ?>" height="<?php echo $navLogoH; ?>" decoding="async">
      </a>

      <ul class="navbar-links">
        <li><a href="/"<?php echo ariaCurrent('home'); ?>>Home</a></li>
        <li class="has-dropdown">
          <a href="/services/"<?php echo ariaCurrentGroup('services'); ?>>Services <?php echo icon('chevron-down', 14, 'nav-chevron'); ?></a>
          <ul class="dropdown" style="display:none">
            <?php foreach ($services as $navSvc): ?>
            <li><a href="<?php echo h(serviceUrl($navSvc)); ?>"<?php echo ariaCurrent('services/' . $navSvc['slug']); ?>><?php echo h($navSvc['name']); ?></a></li>
            <?php endforeach; ?>
            <li><a class="dropdown-all" href="/services/">View all <?php echo count($services); ?> services</a></li>
          </ul>
        </li>
        <?php if ($navAreas['hub'] !== ''): ?>
        <li class="has-dropdown">
          <a href="<?php echo h($navAreas['hub']); ?>"<?php echo (isActiveGroup('service-area') || isActiveGroup('areas-we-serve')) ? ' aria-current="true"' : ''; ?>>Service Areas <?php echo icon('chevron-down', 14, 'nav-chevron'); ?></a>
          <ul class="dropdown" style="display:none">
            <?php foreach ($navAreas['items'] as $navArea): ?>
            <li><a href="<?php echo h($navArea['url']); ?>"><?php echo h($navArea['name']); ?></a></li>
            <?php endforeach; ?>
            <?php if (pageExists('/areas-we-serve/')): ?>
            <li><a class="dropdown-all" href="/areas-we-serve/"<?php echo ariaCurrent('areas-we-serve'); ?>>All areas we serve</a></li>
            <?php endif; ?>
          </ul>
        </li>
        <?php endif; ?>
        <li><a href="/about/"<?php echo ariaCurrent('about'); ?>>About</a></li>
        <?php if ($navProjects): ?><li><a href="/projects/"<?php echo ariaCurrent('projects'); ?>>Projects</a></li><?php endif; ?>
        <?php if ($navBlog): ?><li><a href="/blog/"<?php echo ariaCurrentGroup('blog'); ?>>Blog</a></li><?php endif; ?>
        <li><a href="/contact/"<?php echo ariaCurrent('contact'); ?>>Contact</a></li>
      </ul>

      <div class="navbar-cta">
        <a class="btn btn-outline navbar-phone" href="<?php echo h($navTel); ?>" aria-label="Call <?php echo h($siteName); ?> at <?php echo h($navPhone); ?>"><?php echo icon('phone', 18); ?><span class="phone-num"><?php echo h($navPhone); ?></span></a>
        <a class="btn btn-primary" href="/contact/" data-open-estimate>Free Estimate</a>
      </div>

      <button class="hamburger" type="button" aria-label="Menu" aria-expanded="false" aria-controls="mobile-menu">
        <span class="hamburger-line"></span>
        <span class="hamburger-line"></span>
        <span class="hamburger-line"></span>
      </button>
    </div>
  </nav>
</header>

<!-- Mobile overlay lives OUTSIDE the header: the header's backdrop-filter would shrink a fixed child to bar height -->
<div class="mobile-menu" id="mobile-menu" aria-hidden="true">
  <div class="mobile-menu-inner">
    <nav aria-label="Mobile navigation">
      <ul class="mobile-menu-links">
        <li><a href="/"<?php echo ariaCurrent('home'); ?>>Home</a></li>
        <li>
          <a href="/services/"<?php echo ariaCurrent('services'); ?>>Services</a>
          <ul class="mobile-submenu">
            <?php foreach ($services as $navSvc): ?>
            <li><a href="<?php echo h(serviceUrl($navSvc)); ?>"<?php echo ariaCurrent('services/' . $navSvc['slug']); ?>><?php echo h($navSvc['name']); ?></a></li>
            <?php endforeach; ?>
          </ul>
        </li>
        <?php if ($navAreas['hub'] !== ''): ?>
        <li>
          <a href="<?php echo h($navAreas['hub']); ?>">Service Areas</a>
          <ul class="mobile-submenu">
            <?php foreach ($navAreas['items'] as $navArea): ?>
            <li><a href="<?php echo h($navArea['url']); ?>"><?php echo h($navArea['name']); ?></a></li>
            <?php endforeach; ?>
            <?php if (pageExists('/areas-we-serve/')): ?><li><a href="/areas-we-serve/">All areas we serve</a></li><?php endif; ?>
          </ul>
        </li>
        <?php endif; ?>
        <li><a href="/about/"<?php echo ariaCurrent('about'); ?>>About</a></li>
        <?php if ($navProjects): ?><li><a href="/projects/"<?php echo ariaCurrent('projects'); ?>>Projects</a></li><?php endif; ?>
        <?php if ($navBlog): ?><li><a href="/blog/"<?php echo ariaCurrent('blog'); ?>>Blog</a></li><?php endif; ?>
        <li><a href="/contact/"<?php echo ariaCurrent('contact'); ?>>Contact</a></li>
      </ul>
    </nav>
    <div class="mobile-menu-cta">
      <a class="btn btn-accent btn-lg" href="<?php echo h($navTel); ?>"><?php echo icon('phone', 20); ?>Call <?php echo h($navPhone); ?></a>
      <a class="btn btn-outline-white btn-lg" href="/contact/" data-open-estimate>Get a Free Estimate</a>
    </div>
    <p class="mobile-menu-meta"><?php echo h($businessHours); ?></p>
  </div>
</div>

<main id="main-content">
