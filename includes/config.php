<?php
/**
 * includes/config.php — single source of site-wide variables (Rock'n G Plumbing).
 * Every value below traces to build-plan.json (incl. the P1 SEO BOT brief in content.description, Oct 18 2026).
 * Nothing here is invented; unknowns stay empty and the templates omit anything empty.
 * UNKNOWN: street address (service-area business — never published), ZIP, tagline, social links, GA4 ID,
 * founding year, business entity type, state of formation.
 */

// ---- Identity ---------------------------------------------------------------------------------
$slug      = 'rockn-g-plumbing';                         // exact build directory name
$siteName  = "Rock'n G Plumbing";                        // build-plan company_name
$tagline   = '';                                         // none in build-plan
$industry  = 'plumbing';
$ownerName = 'Eli Gomez';

// ---- Contact (public NAP — GBP is the source of truth) ------------------------------------------
$phone          = '(325) 650-7000';
$phoneRaw       = '3256507000';
$phoneE164      = '+13256507000';
$phoneSecondary = '';
$email          = 'gomez.eli21@gmail.com';
$contactEmail   = $email;
// Lead routing only (server-side, never rendered)
$leadNotificationEmail = 'gomez.eli21@gmail.com';
$leadNotificationPhone = '325-650-7000';

// Service-area business: NO street address anywhere on the site or in schema (SEO brief; build-plan address_public false)
$addressPublic = false;
$address = ['street' => '', 'city' => 'San Angelo', 'state' => 'TX', 'zip' => ''];

// ---- Domain / URLs ----------------------------------------------------------------------------
// Launch domain (build-plan "domain"). seo-aeo-2026.md Part H: $siteUrl is the launch domain as soon as it is known, even while the
// site is parked on preview — canonicals/OG/schema/sitemap/robots all point here; the preview host stays noindex via nginx.
// $domain stays bare (display text on legal pages); the live site serves from www (bare 301s to www), so URLs use www.
$domain  = 'rockngplumbing.com';
$siteUrl = 'https://www.' . $domain;
// $canonicalUrl is NOT set here — each page sets its own from $siteUrl + path before head.php.

// ---- SEO --------------------------------------------------------------------------------------
$primaryKeyword    = 'plumber san angelo tx';            // homepage cluster (V2.6)
$secondaryKeywords = [
    'plumber san angelo',
    'plumbing san angelo',
    'water heater repair san angelo',
    'drain cleaning san angelo',
    'christoval plumber',
];
$targetRadiusMiles = 25;                                 // build-plan seo.target_radius
$tradeNoun         = 'plumber';                          // V2.1 trade noun for titles/H1s

// ---- Services (8 — one page each under /services/{slug}/) --------------------------------------
// Slugs follow the SEO brief's SITE MAP (the 301 targets for the old site's URLs), not the longer
// build-plan pages[].slug values. All 8 are competitive_tier "standard".
$services = [
    ['name' => 'Water Heater Repair & Installation', 'slug' => 'water-heaters',
     'description' => 'repair, replacement, tankless',
     'competitive_tier' => 'standard',
     'keywords' => ['water heater repair & installation', 'water heater installation San Angelo TX', 'water heater replacement San Angelo', 'tankless water heater San Angelo TX', 'water heater repair San Angelo', 'hot water heater plumber San Angelo']],
    ['name' => 'Water Softeners & Filtration', 'slug' => 'water-filtration-softeners',
     'description' => 'softeners, reverse osmosis, whole-house filters, San Angelo hard water',
     'competitive_tier' => 'standard',
     'keywords' => ['water softeners & filtration', 'water softener San Angelo TX', 'water filtration San Angelo TX', 'whole house water filter San Angelo', 'reverse osmosis San Angelo', 'hard water San Angelo']],
    ['name' => 'Drain Cleaning & Clog Removal', 'slug' => 'drain-cleaning',
     'description' => '',
     'competitive_tier' => 'standard',
     'keywords' => ['drain cleaning & clog removal', 'drain cleaning San Angelo', 'drain unclogging San Angelo TX', 'clogged drain plumber San Angelo TX', 'kitchen drain cleaning San Angelo']],
    ['name' => 'Sewer Line Repair & Camera Inspection', 'slug' => 'sewer-line-repair',
     'description' => '',
     'competitive_tier' => 'standard',
     'keywords' => ['sewer line repair & camera inspection', 'sewer line repair San Angelo', 'sewer camera inspection San Angelo TX', 'sewer cleaning San Angelo TX', 'main line sewer repair San Angelo']],
    ['name' => 'Leak Detection & Slab Leak Repair', 'slug' => 'leak-detection-repair',
     'description' => 'water line repair/replacement',
     'competitive_tier' => 'standard',
     'keywords' => ['leak detection & slab leak repair', 'leak detection San Angelo TX', 'slab leak detection San Angelo TX', 'slab leak repair San Angelo', 'water line repair San Angelo', 'hidden leak repair San Angelo']],
    ['name' => 'Gas Line Installation & Repair', 'slug' => 'gas-line-services',
     'description' => '',
     'competitive_tier' => 'standard',
     'keywords' => ['gas line installation & repair', 'gas line repair San Angelo TX', 'gas line installation San Angelo', 'gas plumber San Angelo']],
    ['name' => 'Sprinkler & Irrigation Repair', 'slug' => 'irrigation-sprinkler-repair',
     'description' => '',
     'competitive_tier' => 'standard',
     'keywords' => ['sprinkler & irrigation repair', 'sprinkler repair San Angelo TX', 'irrigation repair San Angelo TX', 'lawn sprinkler repair San Angelo', 'irrigation system San Angelo TX']],
    ['name' => 'New Construction & Remodel Plumbing', 'slug' => 'new-construction-remodel-plumbing',
     'description' => 'residential and commercial',
     'competitive_tier' => 'standard',
     'keywords' => ['new construction & remodel plumbing', 'new construction plumbing San Angelo TX', 'remodel plumbing San Angelo', 'commercial plumbing San Angelo TX', 'residential plumbing contractor San Angelo TX', 'plumbing san angelo']],
];
// SEO brief DO NOT BUILD / DO NOT MARKET (unconfirmed): no emergency/24-hour page, no rainwater harvesting,
// no backflow testing (no WSP endorsement). No llms.txt, no FAQ schema, no aggregateRating.
$doNotMarket = ['emergency / 24-hour plumbing', 'rainwater harvesting', 'backflow testing'];

// ---- Service areas ----------------------------------------------------------------------------
// $serviceAreas = build-plan service_areas. San Angelo is served by the homepage; Christoval gets the ONE town page.
$serviceAreas = ['San Angelo, TX', 'Christoval, TX'];
$serviceAreaDetails = [
    ['city' => 'San Angelo', 'state' => 'TX', 'zip' => '76901', 'primary' => true,  'url' => '/'],
    ['city' => 'Christoval', 'state' => 'TX', 'zip' => '76935', 'primary' => false, 'url' => '/service-area/christoval-tx/'],
];
// SEO brief: listed on the single /areas-we-serve/ page (map + town list) — NO templated town pages.
$areasWeServeTowns = ['Wall', 'Veribest', 'Carlsbad', 'Grape Creek', 'Water Valley'];

// ---- Business facts (build-plan content — the ONLY source for these claims) ----------------------
$usps = [
    'Three generations of plumbers',
    '20+ years experience',
    'Residential, commercial and industrial',
    'Free estimates',
    'Military discounts',
];
$certifications = ['Texas Master Plumber License M-44822'];
// Verified TSBPE (SEO brief): show in footer and on About
$license = [
    'label'      => 'Texas Master Plumber License M-44822',
    'number'     => 'M-44822',
    'holder'     => 'Jerry Eli Gomez',
    'role'       => 'Responsible Master Plumber',
    'expires'    => '2027-08-31',
    'issuer'     => 'Texas State Board of Plumbing Examiners',
];
$insuranceSummary = '';                                  // build-plan content.insurance is empty — do not claim "insured"

// ---- Hours (build-plan business_hours: Mon–Fri 8:00 AM–6:00 PM, Sat–Sun Closed) -----------------
$hours = [
    ['day' => 'Monday',    'short' => 'Mon', 'opens' => '08:00', 'closes' => '18:00', 'label' => '8:00 AM – 6:00 PM'],
    ['day' => 'Tuesday',   'short' => 'Tue', 'opens' => '08:00', 'closes' => '18:00', 'label' => '8:00 AM – 6:00 PM'],
    ['day' => 'Wednesday', 'short' => 'Wed', 'opens' => '08:00', 'closes' => '18:00', 'label' => '8:00 AM – 6:00 PM'],
    ['day' => 'Thursday',  'short' => 'Thu', 'opens' => '08:00', 'closes' => '18:00', 'label' => '8:00 AM – 6:00 PM'],
    ['day' => 'Friday',    'short' => 'Fri', 'opens' => '08:00', 'closes' => '18:00', 'label' => '8:00 AM – 6:00 PM'],
    ['day' => 'Saturday',  'short' => 'Sat', 'opens' => null,    'closes' => null,    'label' => 'Closed'],
    ['day' => 'Sunday',    'short' => 'Sun', 'opens' => null,    'closes' => null,    'label' => 'Closed'],
];
// Compact one-line form for the v8 components that expect a string
$businessHours = 'Mon–Fri 8:00 AM–6:00 PM, Sat–Sun Closed';

// ---- Brand ------------------------------------------------------------------------------------
// build-plan design.colors (extracted_from_logo: true) — mirrors framework.css :root. Style: bold.
$colors = ['primary' => '#2a5c34', 'secondary' => '#3a3a3a', 'accent' => '#b30e02'];
$designStyle = 'bold';
// Intake says "20+ years experience" (three generations) — a founding year was NOT provided.
// Never print an "Est. YYYY" line; use "20+ years" wording only.
$yearEstablished = null;
$yearsInBusiness = 20;

// Factual description for schema / AEO (build-plan content + SEO brief)
$businessDescription = "Rock'n G Plumbing is a San Angelo, TX plumbing company led by Eli Gomez, Responsible Master Plumber (Texas Master Plumber License M-44822), from three generations of plumbers with 20+ years of experience, serving residential, commercial and industrial customers in San Angelo and Christoval. Free estimates and military discounts.";

// ---- Google Business Profile ------------------------------------------------------------------
$gbp = [
    'place_id'      => 'ChIJY4n-xr4zoKURYDZm0rhWN8I',
    'cid'           => '13994749718880990816',
    'url'           => 'https://maps.google.com/maps?cid=13994749718880990816',   // schema hasMap (SEO brief)
    'profile_url'   => 'https://www.google.com/maps/place/?q=place_id:ChIJY4n-xr4zoKURYDZm0rhWN8I',
    'directions'    => 'https://www.google.com/maps/dir/?api=1&destination=place_id:ChIJY4n-xr4zoKURYDZm0rhWN8I',
    'review_url'    => 'https://search.google.com/local/writereview?placeid=ChIJY4n-xr4zoKURYDZm0rhWN8I',
    'embed_src'     => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d113621.35813329648!2d-100.4462461!3d31.431059049999998!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xa5a033bec6fe8963%3A0xc23756b8d2663660!2sRock%27n%20G%20Plumbing!5e1!3m2!1sen!2sus!4v1792338463931!5m2!1sen!2sus',
    'rating'        => 5.0,                               // SEO brief: 5.0 stars from 3 reviews — visible text only, NEVER AggregateRating
    'reviews_count' => 3,
];
// integrations.geo (from the GBP embed: !2d = longitude, !3d = latitude)
$geo = ['lat' => 31.431059049999998, 'lng' => -100.4462461];

// ---- Social / analytics -----------------------------------------------------------------------
$schemaSameAs = ['https://maps.google.com/maps?cid=13994749718880990816'];
// Indexing switch. true = no site-level noindex (seo-aeo-2026.md Part H: never noindex in site code — it would ship to the launch
// domain). The preview host (preview-*.pageone.cloud) is kept out of the index by the nginx X-Robots-Tag header instead.
$allowIndexing = true;

$socialLinks       = [];                                  // none provided — footer renders icons only for platforms the client uses
$googleAnalyticsId = 'G-XXXXXXXXXX';                      // placeholder until the client's GA4 measurement ID is set

// ---- Forms ------------------------------------------------------------------------------------
$formAction      = 'https://db.pageone.cloud/functions/v1/leads/rockn-g-plumbing';   // build-plan form_action, verbatim
// HMAC secret for the spam-shield `_ft` field. Blank in the repo: injected by ~/crm/scripts/patch-leads-spamshield.py
// at promote time. p1-form-helpers.php skips the shield when empty.
$leadsFormSecret = '';

// ---- v8 components (written exactly as build-plan integrations holds them) ---------------------
$estimateTool   = [];   // integrations.estimate_tool.authorized is false
$bookingWindows = [];   // integrations.booking.authorized is false
$textUsNumber   = '';   // integrations.text_us_number is null (accepts_sms false) -> two-button sticky bar
$financing      = [];   // integrations.financing is null
$serviceZips    = [];   // integrations.service_zips not provided

$cssVersion = '5';      // the SINGLE framework.css cache-bust; bump here on every framework.css / critical.css change

// ---- Legal pages ------------------------------------------------------------------------------
// Intake did not collect business entity type or state of formation. The entity suffix stays empty (legal pages omit it
// rather than guess); governing law uses the state the business operates in (San Angelo, Tom Green County, TX).
$companyEntityType = '';          // UNKNOWN — set once confirmed
$companyState      = 'Texas';
$companyCounty     = 'Tom Green';

// ---- Lead attribution (first-touch cookie + p1_attribution_fields()) — must stay last, before any output ----
require_once __DIR__ . '/attribution.php';
