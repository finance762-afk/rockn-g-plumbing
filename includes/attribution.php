<?php
/**
 * includes/attribution.php — lead attribution (v6.3, 2026-09-04). Canonical copy: ~/crm/references/attribution.php
 *
 * Include from includes/config.php (BEFORE any output). On the first pageview of a visit
 * it sets a first-party cookie holding the landing page, referrer, UTM params, gclid, a
 * session id and device type. Every form then echoes p1_attribution_fields('<form id>')
 * as hidden inputs, so the leads endpoint can credit BOTH the submitting page and the
 * first-touch page (a visitor lands on a city page, converts on a service page).
 *
 * page_type / service_slug / city_slug come from the rendering page: set $pageType
 * ('home'|'service'|'city'|'blog'|'about'|'faq'|'contact'|'other'), $serviceSlug and
 * $citySlug before head.php. The path-based fallback below is PHP, never JavaScript.
 */
if (!function_exists('p1_attribution_fields')) {
  function p1_page_type_from_path(string $path): string {
    if ($path === '/' || $path === '/index.php') return 'home';
    if (preg_match('#^/services?/[^/]+#', $path)) return 'service';
    if (preg_match('#^/(service-areas?|areas|locations)/[^/]+#', $path)) return 'city';
    if (str_starts_with($path, '/blog')) return 'blog';
    if (str_starts_with($path, '/about')) return 'about';
    if (str_starts_with($path, '/faq')) return 'faq';
    if (str_starts_with($path, '/contact')) return 'contact';
    return 'other';
  }
  function p1_slug_from_path(string $path, string $kind): string {
    $re = $kind === 'service' ? '#^/services?/([^/]+)#' : '#^/(?:service-areas?|areas|locations)/([^/]+)#';
    return preg_match($re, $path, $m) ? $m[1] : '';
  }
  function p1_first_touch(): array {
    static $ft = null;
    if ($ft !== null) return $ft;
    $raw = $_COOKIE['p1_ft'] ?? '';
    $ft = $raw !== '' ? (json_decode(base64_decode($raw, true) ?: '', true) ?: []) : [];
    if (!$ft || empty($ft['sid'])) {
      $q  = $_GET;
      $ua = strtolower($_SERVER['HTTP_USER_AGENT'] ?? '');
      $ft = [
        'sid'   => bin2hex(random_bytes(8)),
        'lp'    => substr((string)($_SERVER['REQUEST_URI'] ?? '/'), 0, 400),
        'ref'   => substr((string)($_SERVER['HTTP_REFERER'] ?? ''), 0, 400),
        'us'    => substr((string)($q['utm_source'] ?? ''), 0, 100),
        'um'    => substr((string)($q['utm_medium'] ?? ''), 0, 100),
        'uc'    => substr((string)($q['utm_campaign'] ?? ''), 0, 150),
        'ut'    => substr((string)($q['utm_term'] ?? ''), 0, 150),
        'uct'   => substr((string)($q['utm_content'] ?? ''), 0, 150),
        'gclid' => substr((string)($q['gclid'] ?? ''), 0, 120),
        'dev'   => preg_match('/ipad|tablet/', $ua) ? 'tablet' : (preg_match('/mobi|android|iphone/', $ua) ? 'mobile' : 'desktop'),
        'ts'    => time(),
      ];
      if (!headers_sent()) {
        setcookie('p1_ft', base64_encode(json_encode($ft)), [
          'expires' => time() + 30 * 86400, 'path' => '/', 'secure' => !empty($_SERVER['HTTPS']),
          'httponly' => true, 'samesite' => 'Lax',
        ]);
      }
    }
    return $ft;
  }
  /** Hidden inputs for a form. $formId must be unique per form instance on a page (hero, cta-band, dialog, contact). */
  function p1_attribution_fields(string $formId = 'form'): string {
    $ft     = p1_first_touch();
    $host   = $_SERVER['HTTP_HOST'] ?? '';
    $scheme = !empty($_SERVER['HTTPS']) ? 'https' : 'http';
    $path   = strtok((string)($_SERVER['REQUEST_URI'] ?? '/'), '?') ?: '/';
    $lp     = strtok((string)($ft['lp'] ?? ''), '?') ?: '';
    $fields = [
      'form_id'           => $formId,
      'submit_page_url'   => "{$scheme}://{$host}{$path}",
      'submit_page_path'  => $path,
      'page_type'         => $GLOBALS['pageType']    ?? p1_page_type_from_path($path),
      'service_slug'      => $GLOBALS['serviceSlug'] ?? p1_slug_from_path($path, 'service'),
      'city_slug'         => $GLOBALS['citySlug']    ?? p1_slug_from_path($path, 'city'),
      'landing_page_url'  => $lp !== '' ? "{$scheme}://{$host}{$lp}" : '',
      'landing_page_path' => $lp,
      'traffic_referrer'  => $ft['ref'] ?? '',
      'utm_source'        => $ft['us'] ?? '',
      'utm_medium'        => $ft['um'] ?? '',
      'utm_campaign'      => $ft['uc'] ?? '',
      'utm_term'          => $ft['ut'] ?? '',
      'utm_content'       => $ft['uct'] ?? '',
      'gclid'             => $ft['gclid'] ?? '',
      'session_id'        => $ft['sid'] ?? '',
      'device_type'       => $ft['dev'] ?? '',
    ];
    $out = '';
    foreach ($fields as $k => $v) {
      if ((string)$v === '') continue;
      $out .= '<input type="hidden" name="' . $k . '" value="' . htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8') . '">' . "\n";
    }
    return $out;
  }
}
// Set the cookie NOW — this file is included before any output (forms render too late for setcookie).
p1_first_touch();
