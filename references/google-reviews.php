<?php
/**
 * google-reviews.php — Page One Google reviews block (canonical: ~/crm/references/google-reviews.php)
 *
 * Shows a client's real Google reviews from the Page One reviews feed
 * (https://db.pageone.cloud/functions/v1/site-reviews/{slug}), which is filled nightly
 * from the client's Google Business Profile. Replaces third-party review widgets.
 *
 * Use (any page, after config.php):
 *     require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/google-reviews.php';
 *     echo p1_google_reviews($slug);                       // defaults
 *     echo p1_google_reviews($slug, ['limit' => 6]);       // options below
 *
 * Options: limit (1-30, default 12) · min_rating (4 or 5, default 4: sites never show reviews under 4 stars) · replies (bool, show the
 * owner's reply, default false) · heading (string, default "What customers say on Google";
 * '' = no heading) · heading_tag (default h2) · summary (bool, the Google rating bar above the cards, default true;
 * pass false when the page already shows the rating next to the block) · ttl (cache seconds, default 21600).
 *
 * Rules:
 *  - Server-side only: PHP fetches the feed and caches it on disk, so the visitor's browser
 *    makes no extra request and the reviews are in the HTML for search and AI crawlers.
 *  - Fail-quiet: feed down and no cache, or a client with no written reviews → returns ''
 *    (the section simply does not render). Never invent or hard-code reviews.
 *  - Review text is shown exactly as written; names and text are escaped.
 *  - No AggregateRating schema is emitted (self-serving ratings are a QA blocker).
 *  - Styles use the site's tokens (with fallbacks), so it matches each build.
 */

if (!function_exists('p1_google_reviews_data')) {

    function p1_google_reviews_data($slug, $limit = 12, $minRating = 4, $ttl = 21600) {
        $slug = strtolower(preg_replace('/[^a-z0-9-]/i', '', (string) $slug));
        if ($slug === '') return null;
        $limit = max(1, min(30, (int) $limit));
        $minRating = max(4, min(5, (int) $minRating));

        $cacheFile = rtrim(sys_get_temp_dir(), '/') . '/p1-reviews-' . md5($slug . '|' . $limit . '|' . $minRating) . '.json';
        $cached = null;
        if (is_readable($cacheFile)) {
            $cached = json_decode((string) @file_get_contents($cacheFile), true);
            if (is_array($cached) && (time() - (int) @filemtime($cacheFile)) < $ttl) return $cached;
        }

        $url = 'https://db.pageone.cloud/functions/v1/site-reviews/' . $slug . '?limit=' . $limit . '&min_rating=' . $minRating;
        $body = false;
        if (function_exists('curl_init')) {
            $ch = curl_init($url);
            curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER => true, CURLOPT_CONNECTTIMEOUT => 2, CURLOPT_TIMEOUT => 4, CURLOPT_FOLLOWLOCATION => false]);
            $body = curl_exec($ch);
            if ((int) curl_getinfo($ch, CURLINFO_HTTP_CODE) !== 200) $body = false;
            curl_close($ch);
        } elseif (ini_get('allow_url_fopen')) {
            $body = @file_get_contents($url, false, stream_context_create(['http' => ['timeout' => 4]]));
        }

        $fresh = $body ? json_decode($body, true) : null;
        if (is_array($fresh) && isset($fresh['reviews']) && is_array($fresh['reviews'])) {
            @file_put_contents($cacheFile, json_encode($fresh), LOCK_EX);
            return $fresh;
        }
        // Feed unreachable: keep serving the last good copy, and do not retry on every pageview.
        if (is_array($cached)) { @touch($cacheFile, time() - $ttl + 600); return $cached; }
        return null;
    }

    function p1_google_reviews_ago($date) {
        $t = strtotime((string) $date);
        if (!$t) return '';
        $days = (int) floor((time() - $t) / 86400);
        if ($days < 1)   return 'today';
        if ($days < 7)   return $days === 1 ? 'a day ago' : $days . ' days ago';
        if ($days < 30)  { $w = (int) floor($days / 7);   return $w === 1 ? 'a week ago' : $w . ' weeks ago'; }
        if ($days < 365) { $m = (int) floor($days / 30);  return $m === 1 ? 'a month ago' : $m . ' months ago'; }
        $y = (int) floor($days / 365);
        return $y === 1 ? 'a year ago' : $y . ' years ago';
    }

    function p1_google_reviews_stars($rating) {
        $star = '<svg aria-hidden="true" width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.5l2.9 6 6.6.9-4.8 4.6 1.2 6.5L12 17.4l-5.9 3.1 1.2-6.5L2.5 9.4l6.6-.9z"/></svg>';
        $full = max(0, min(5, (int) round((float) $rating)));
        return '<span class="p1gr-stars" role="img" aria-label="' . $full . ' out of 5 stars">'
             . str_repeat($star, $full)
             . str_repeat(str_replace('<svg ', '<svg class="is-off" ', $star), 5 - $full) . '</span>';
    }

    function p1_google_reviews($slug, $opts = []) {
        static $instance = 0;
        $o = array_merge(['limit' => 12, 'min_rating' => 4, 'replies' => false, 'heading' => 'What customers say on Google', 'heading_tag' => 'h2', 'summary' => true, 'ttl' => 21600], (array) $opts);
        $d = p1_google_reviews_data($slug, $o['limit'], $o['min_rating'], $o['ttl']);
        if (!$d || empty($d['reviews'])) return '';

        $instance++;
        $e = function ($s) { return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8'); };
        $tag = preg_match('/^h[2-4]$/', (string) $o['heading_tag']) ? $o['heading_tag'] : 'h2';
        $gLogo = '<svg class="p1gr-g" aria-hidden="true" width="22" height="22" viewBox="0 0 48 48"><path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/><path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/><path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/><path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/></svg>';
        $rating = isset($d['rating']) ? number_format((float) $d['rating'], 1) : null;
        $count  = isset($d['review_count']) ? (int) $d['review_count'] : count($d['reviews']);

        ob_start();
        if ($instance === 1): ?>
<style>
  .p1gr { --p1gr-card: var(--color-surface, #fff); --p1gr-line: var(--color-line, #dfe3ea); --p1gr-ink: var(--color-ink, #1b2333); --p1gr-ink2: var(--color-ink-2, #4a5468); --p1gr-star: var(--color-star, #f2b84b); --p1gr-accent: var(--color-primary, #1a56db); display: grid; gap: var(--space-lg, 1.5rem); }
  .p1gr > * { min-width: 0; }
  .p1gr-head { display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: var(--space-md, 1rem); padding: var(--space-md, 1rem) var(--space-lg, 1.5rem); background: var(--p1gr-card); border: 1px solid var(--p1gr-line); border-radius: var(--radius-lg, 14px); }
  .p1gr-title { margin: 0; font-size: var(--fs-h2, 1.9rem); }
  .p1gr-summary { display: flex; flex-wrap: wrap; align-items: center; gap: .6rem .9rem; color: var(--p1gr-ink); }
  .p1gr-summary .p1gr-label { display: inline-flex; align-items: center; gap: .5rem; font-weight: 700; }
  .p1gr-score { font-size: 1.6rem; font-weight: 800; line-height: 1; font-variant-numeric: tabular-nums; }
  .p1gr-count { color: var(--p1gr-ink2); font-size: .92rem; }
  .p1gr-stars { display: inline-flex; gap: 1px; color: var(--p1gr-star); vertical-align: middle; }
  .p1gr-stars .is-off { opacity: .28; }
  .p1gr-summary .p1gr-stars svg { width: 20px; height: 20px; }
  .p1gr-write { display: inline-flex; align-items: center; min-height: 44px; padding: .55rem 1.1rem; border-radius: var(--radius, 10px); background: var(--p1gr-accent); color: #fff; font-weight: 700; text-decoration: none; white-space: nowrap; }
  .p1gr-write:hover { filter: brightness(1.1); color: #fff; }
  .p1gr-wrap { position: relative; }
  .p1gr-track { list-style: none; margin: 0; padding: .25rem .1rem 1rem; display: grid; grid-auto-flow: column; grid-auto-columns: minmax(280px, calc((100% - 2 * var(--space-md, 1rem)) / 3)); gap: var(--space-md, 1rem); overflow-x: auto; scroll-snap-type: x mandatory; scrollbar-width: thin; overscroll-behavior-x: contain; }
  .p1gr-track.is-few { grid-auto-flow: row; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); overflow: visible; }
  .p1gr-card { scroll-snap-align: start; display: grid; grid-template-rows: auto auto 1fr; gap: .7rem; align-content: start; padding: var(--space-lg, 1.5rem); background: var(--p1gr-card); border: 1px solid var(--p1gr-line); border-radius: var(--radius-lg, 14px); box-shadow: var(--shadow-sm, 0 1px 3px rgba(16, 24, 40, .08)); color: var(--p1gr-ink); }
  .p1gr-who { display: grid; grid-template-columns: auto 1fr auto; align-items: center; gap: .7rem; }
  .p1gr-avatar { display: grid; place-items: center; width: 42px; height: 42px; border-radius: 50%; color: #fff; font-weight: 700; font-size: 1.05rem; }
  .p1gr-name { display: block; font-weight: 700; line-height: 1.25; }
  .p1gr-when { display: block; font-size: .85rem; color: var(--p1gr-ink2); }
  .p1gr-text { margin: 0; line-height: 1.6; color: var(--p1gr-ink); overflow-wrap: anywhere; }
  .p1gr-more summary { list-style: none; cursor: pointer; }
  .p1gr-more summary::-webkit-details-marker { display: none; }
  .p1gr-more summary .p1gr-text { display: -webkit-box; -webkit-box-orient: vertical; -webkit-line-clamp: 5; overflow: hidden; }
  .p1gr-more summary .p1gr-toggle { display: inline-block; margin-top: .35rem; font-size: .9rem; font-weight: 600; color: var(--p1gr-ink2); text-decoration: underline; }
  .p1gr-more[open] summary { display: none; }
  .p1gr-reply { margin: .6rem 0 0; padding: .6rem .8rem; border-left: 3px solid var(--p1gr-line); background: var(--color-paper-2, #f4f6f9); border-radius: 0 8px 8px 0; font-size: .9rem; color: var(--p1gr-ink2); }
  .p1gr-reply strong { display: block; color: var(--p1gr-ink); }
  .p1gr-nav { position: absolute; top: 42%; transform: translateY(-50%); z-index: 2; display: grid; place-items: center; width: 44px; height: 44px; border-radius: 50%; border: 1px solid var(--p1gr-line); background: var(--p1gr-card); color: var(--p1gr-ink); box-shadow: var(--shadow, 0 4px 14px rgba(16, 24, 40, .12)); cursor: pointer; }
  .p1gr-nav[hidden] { display: none; }
  .p1gr-nav--prev { left: -14px; } .p1gr-nav--next { right: -14px; }
  .p1gr-foot { margin: 0; font-size: .85rem; color: var(--p1gr-ink2); }
  .p1gr-foot a { color: inherit; font-weight: 600; }
  @media (max-width: 700px) { .p1gr-track { grid-auto-columns: 86%; } .p1gr-nav { display: none; } .p1gr-head { padding: var(--space-md, 1rem); } .p1gr-write { width: 100%; justify-content: center; } }
</style>
<?php   endif; ?>
<div class="p1gr" id="p1gr-<?php echo $instance; ?>">
<?php   if ($o['heading'] !== ''): ?>
    <<?php echo $tag; ?> class="p1gr-title"><?php echo $e($o['heading']); ?></<?php echo $tag; ?>>
<?php   endif; ?>
<?php   if ($o['summary']): ?>
    <div class="p1gr-head">
        <div class="p1gr-summary">
            <span class="p1gr-label"><?php echo $gLogo; ?> Google Reviews</span>
<?php   if ($rating !== null): ?>
            <span class="p1gr-score"><?php echo $rating; ?></span>
            <?php echo p1_google_reviews_stars($d['rating']); ?>
<?php   endif; ?>
            <span class="p1gr-count">Based on <?php echo $count; ?> review<?php echo $count === 1 ? '' : 's'; ?></span>
        </div>
<?php   if (!empty($d['write_review_url'])): ?>
        <a class="p1gr-write" href="<?php echo $e($d['write_review_url']); ?>" target="_blank" rel="noopener nofollow">Review us on Google</a>
<?php   endif; ?>
    </div>
<?php   endif; ?>
    <div class="p1gr-wrap">
        <button type="button" class="p1gr-nav p1gr-nav--prev" aria-label="Previous reviews" hidden><svg aria-hidden="true" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg></button>
        <ul class="p1gr-track<?php echo count($d['reviews']) <= 3 ? ' is-few' : ''; ?>" data-p1-dynamic>
<?php   $palette = ['#1a73e8', '#d93025', '#188038', '#e37400', '#9334e6', '#00796b', '#c2185b', '#5f6368'];
        foreach ($d['reviews'] as $r):
            $name = trim((string) ($r['name'] ?? 'Google user'));
            $initial = function_exists('mb_substr') ? mb_strtoupper(mb_substr($name, 0, 1)) : strtoupper(substr($name, 0, 1));
            $text = (string) ($r['text'] ?? '');
            $long = (function_exists('mb_strlen') ? mb_strlen($text) : strlen($text)) > 230; ?>
            <li class="p1gr-card">
                <div class="p1gr-who">
                    <span class="p1gr-avatar" aria-hidden="true" style="background:<?php echo $palette[abs(crc32($name)) % count($palette)]; ?>"><?php echo $e($initial); ?></span>
                    <span><span class="p1gr-name"><?php echo $e($name); ?></span><span class="p1gr-when"><?php echo $e(p1_google_reviews_ago($r['date'] ?? '')); ?></span></span>
                    <?php echo str_replace('width="22" height="22"', 'width="18" height="18"', $gLogo); ?>
                </div>
                <?php echo p1_google_reviews_stars($r['rating'] ?? 5); ?>
                <div>
<?php       if ($long): ?>
                    <details class="p1gr-more">
                        <summary><p class="p1gr-text"><?php echo nl2br($e($text)); ?></p><span class="p1gr-toggle">Read more</span></summary>
                        <p class="p1gr-text"><?php echo nl2br($e($text)); ?></p>
                    </details>
<?php       else: ?>
                    <p class="p1gr-text"><?php echo nl2br($e($text)); ?></p>
<?php       endif; ?>
<?php       if ($o['replies'] && !empty($r['reply'])): ?>
                    <p class="p1gr-reply"><strong>Response from the owner</strong><?php echo nl2br($e($r['reply'])); ?></p>
<?php       endif; ?>
                </div>
            </li>
<?php   endforeach; ?>
        </ul>
        <button type="button" class="p1gr-nav p1gr-nav--next" aria-label="More reviews" hidden><svg aria-hidden="true" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg></button>
    </div>
<?php   if (!empty($d['reviews_url'])): ?>
    <p class="p1gr-foot">Reviews are shown as written on Google. <a href="<?php echo $e($d['reviews_url']); ?>" target="_blank" rel="noopener nofollow">See all <?php echo $count; ?> on Google</a></p>
<?php   endif; ?>
</div>
<?php   if ($instance === 1): ?>
<script>
(function () {
    document.querySelectorAll('.p1gr-wrap').forEach(function (w) {
        var t = w.querySelector('.p1gr-track'), p = w.querySelector('.p1gr-nav--prev'), n = w.querySelector('.p1gr-nav--next');
        if (!t || !p || !n || t.classList.contains('is-few')) return;
        function sync() { p.hidden = t.scrollLeft < 8; n.hidden = t.scrollLeft + t.clientWidth > t.scrollWidth - 8; }
        function go(dir) { t.scrollBy({ left: dir * t.clientWidth * 0.9, behavior: 'smooth' }); }
        p.addEventListener('click', function () { go(-1); }); n.addEventListener('click', function () { go(1); });
        t.addEventListener('scroll', sync, { passive: true }); window.addEventListener('resize', sync); sync();
    });
})();
</script>
<?php   endif;
        return ob_get_clean();
    }
}
