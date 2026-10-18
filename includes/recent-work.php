<?php
/**
 * recent-work.php — "Recent work" feed from the client's Google Business Profile photos + posts (v8, 2026-10-09).
 * Canonical copy: ~/crm/references/components/recent-work.php
 *
 * Server-side fetch (10-minute file cache) of
 *   https://db.pageone.cloud/functions/v1/site-recent-work/{slug}   → {"items":[{"type":"photo"|"post","url":"...","caption":"...","date":"YYYY-MM-DD"}]}
 * Fail-quiet: feed down and no cache, or no items → returns '' (section does not render). Captions are shown as
 * provided (escaped), never invented. Keeps a site changing without anyone editing it (information gain + freshness).
 *
 * Use (homepage on Flagship; Premium when build-plan integrations.recent_work = true):
 *   <?php require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/recent-work.php'; echo p1_recent_work($slug); ?>
 * Options: ['limit' => 8, 'heading' => 'Recent work', 'ttl' => 600]
 */
if (!function_exists('p1_recent_work_data')) {
    function p1_recent_work_data(string $slug, int $limit = 8, int $ttl = 600): array {
        $slug = strtolower(preg_replace('/[^a-z0-9-]/i', '', $slug));
        if ($slug === '') return [];
        $limit = max(1, min(12, $limit));
        $cacheFile = rtrim(sys_get_temp_dir(), '/') . '/p1-recent-work-' . md5($slug . '|' . $limit) . '.json';
        $cached = null;
        if (is_readable($cacheFile)) {
            $cached = json_decode((string) @file_get_contents($cacheFile), true);
            if (is_array($cached) && (time() - (int) @filemtime($cacheFile)) < $ttl) return $cached;
        }
        $url = 'https://db.pageone.cloud/functions/v1/site-recent-work/' . $slug . '?limit=' . $limit;
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
        if (is_array($fresh) && isset($fresh['items']) && is_array($fresh['items'])) {
            @file_put_contents($cacheFile, json_encode($fresh), LOCK_EX);
            return $fresh;
        }
        return is_array($cached) ? $cached : [];
    }

    function p1_recent_work(string $slug, array $opt = []): string {
        $limit = (int) ($opt['limit'] ?? 8);
        $data = p1_recent_work_data($slug, $limit, (int) ($opt['ttl'] ?? 600));
        // GBP posts usually have no link; a post card prints its caption and date, so posts are kept
        // without a URL. Photos still need an https URL (2026-10-09, surfaced on River City).
        $items = array_values(array_filter((array) ($data['items'] ?? []), fn($i) => is_array($i) && (
            (($i['type'] ?? 'photo') === 'post' && trim((string) ($i['caption'] ?? '')) !== '')
            || (!empty($i['url']) && preg_match('#^https://#i', (string) $i['url'])))));
        if (!$items) return '';
        $items = array_slice($items, 0, $limit);
        $h = fn($v) => htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8');
        $heading = $opt['heading'] ?? 'Recent work';
        ob_start(); ?>
<section class="p1-recent" aria-labelledby="p1-recent-h" data-p1-component="recent-work">
<style>
.p1-recent{padding:var(--space-xl,4rem) 0}
.p1-recent__inner{max-width:var(--container-max,1180px);margin:0 auto;padding:0 var(--space-lg,1.5rem)}
.p1-recent__head{display:flex;justify-content:space-between;align-items:baseline;gap:1rem;margin-bottom:var(--space-md,1rem)}
.p1-recent__head h2{margin:0;font-family:var(--font-heading,inherit)}
.p1-recent__head p{margin:0;color:var(--color-muted,#666);font-size:.9rem}
.p1-recent__grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:var(--space-md,1rem);list-style:none;margin:0;padding:0}
.p1-recent__grid li{border-radius:var(--radius-md,12px);overflow:hidden;background:var(--color-paper-2,#f4f4f1);border:1px solid var(--color-line,rgba(0,0,0,.08))}
.p1-recent__grid img{display:block;width:100%;height:auto;aspect-ratio:4/3;object-fit:cover}
.p1-recent__cap{padding:.6rem .75rem;font-size:.85rem;line-height:1.35}
.p1-recent__cap time{display:block;color:var(--color-muted,#666);font-size:.75rem;margin-top:.2rem}
.p1-recent__post{padding:.9rem;font-size:.9rem;line-height:1.45;display:flex;flex-direction:column;gap:.4rem;min-height:100%}
.p1-recent__post strong{font-family:var(--font-heading,inherit)}
@media (max-width:900px){.p1-recent__grid{grid-template-columns:repeat(2,minmax(0,1fr))}}
</style>
  <div class="p1-recent__inner">
    <div class="p1-recent__head"><h2 id="p1-recent-h"><?= $h($heading) ?></h2><p>From our Google Business Profile</p></div>
    <ul class="p1-recent__grid" data-p1-dynamic>
<?php foreach ($items as $i): $cap = trim((string) ($i['caption'] ?? '')); $date = (string) ($i['date'] ?? '');
      $ts = $date !== '' ? strtotime($date) : false; ?>
      <li>
<?php if (($i['type'] ?? 'photo') === 'post'): ?>
        <div class="p1-recent__post"><strong>Update</strong><span><?= $h(mb_strimwidth($cap, 0, 220, '…')) ?></span><?php if ($ts): ?><time datetime="<?= $h(date('Y-m-d', $ts)) ?>"><?= $h(date('M j, Y', $ts)) ?></time><?php endif; ?></div>
<?php else: ?>
        <img src="<?= $h($i['url']) ?>" alt="<?= $h($cap !== '' ? $cap : 'Recent job photo') ?>" width="800" height="600" loading="lazy" decoding="async">
        <?php if ($cap !== '' || $ts): ?><div class="p1-recent__cap"><?= $h(mb_strimwidth($cap, 0, 120, '…')) ?><?php if ($ts): ?><time datetime="<?= $h(date('Y-m-d', $ts)) ?>"><?= $h(date('M j, Y', $ts)) ?></time><?php endif; ?></div><?php endif; ?>
<?php endif; ?>
      </li>
<?php endforeach; ?>
    </ul>
  </div>
</section>
<?php   return ob_get_clean();
    }
}
