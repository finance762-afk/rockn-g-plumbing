<?php
/**
 * includes/partner-badge.php — "Verified Local Partner" badge (Page One Partner profile). v1, 2026-09-17.
 * Canonical copy: ~/crm/references/partner-badge.php. NEVER edit a site's copy by hand: the scaffold and
 * scripts/partner-badge-fleet.mjs write it (they fill the two placeholders below) and overwrite it on upgrade.
 *
 * Include it ONCE in includes/footer.php, inside <footer>, after the bottom bar:
 *     <?php include __DIR__ . '/partner-badge.php'; ?>
 *
 * The badge image is served by pageonepartner.com and shows the business's live Google rating. It exists only
 * for CLAIMED profiles: an empty slug renders nothing, and if the profile is ever unclaimed or hidden the image
 * 404s and the block hides itself. The link is a plain followed link to the business's own profile (same code an
 * owner gets in the "profile claimed" email). Variables are $pp-prefixed (shared-include variable rule).
 */
$ppSlug = isset($partnerProfileSlug) && $partnerProfileSlug !== '' ? (string) $partnerProfileSlug : '__PP_SLUG__';
$ppName = isset($partnerProfileName) && $partnerProfileName !== '' ? (string) $partnerProfileName : '__PP_NAME__';
if (strpos($ppSlug, '__') === false && preg_match('/^[a-z0-9-]{3,160}$/', $ppSlug)):
  $ppNameEsc = htmlspecialchars(strpos($ppName, '__') === false ? $ppName : 'This business', ENT_QUOTES, 'UTF-8');
?>
<div class="poi-partner-seal" style="text-align:center;padding:4px 16px 24px">
  <a href="https://pageonepartner.com/<?php echo $ppSlug; ?>/" target="_blank" rel="nofollow noopener" title="<?php echo $ppNameEsc; ?> on Page One Partner" style="display:inline-block;line-height:0;border-radius:14px">
    <img src="https://pageonepartner.com/badge/<?php echo $ppSlug; ?>.svg" alt="<?php echo $ppNameEsc; ?> — Verified Local Partner on Page One Partner" width="240" height="67" loading="lazy" decoding="async" style="border:0;max-width:100%;height:auto" onerror="this.closest('.poi-partner-seal').style.display='none'">
  </a>
</div>
<?php endif; ?>
