<?php
/**
 * financing-block.php — financing availability block (Web Builder v8, 2026-10-09).
 * Canonical copy: ~/crm/references/components/financing-block.php
 *
 * Renders ONLY when config.php sets
 *   $financing = ['partner' => 'Wisetack', 'url' => 'https://...', 'monthly_note' => 'as low as $89/mo on approved credit'];
 * from build-plan.json integrations.financing. partner + url are required; monthly_note is optional and is
 * printed verbatim ONLY when the client supplied it (claims-accuracy rule: no invented payment figures).
 *
 * Use (service pages, after the pricing/answer block):
 *   <?php require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/financing-block.php'; echo p1_financing_block(); ?>
 * Options: ['heading' => 'Financing available', 'cta' => 'See financing options']
 */
if (!function_exists('p1_financing_block')) {
    function p1_financing_block(array $opt = []): string {
        global $financing;
        if (empty($financing) || empty($financing['partner']) || empty($financing['url']) || !preg_match('#^https://#i', (string) $financing['url'])) return '';
        $h = fn($v) => htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8');
        $heading = $opt['heading'] ?? 'Financing available';
        $cta = $opt['cta'] ?? ('See financing options through ' . $financing['partner']);
        $note = trim((string) ($financing['monthly_note'] ?? ''));
        ob_start(); ?>
<aside class="p1-financing" aria-label="Financing" data-p1-component="financing-block">
<style>
.p1-financing{display:flex;gap:1rem;align-items:center;flex-wrap:wrap;margin:var(--space-lg,1.5rem) 0;padding:1rem 1.25rem;border:1px solid var(--color-line,rgba(0,0,0,.1));border-left:4px solid var(--color-accent,var(--color-primary,#1a4ed8));border-radius:var(--radius-md,10px);background:var(--color-surface,#fff)}
.p1-financing svg{width:28px;height:28px;flex:none;color:var(--color-accent,var(--color-primary,#1a4ed8))}
.p1-financing__text{flex:1;min-width:220px}
.p1-financing__text strong{display:block;font-family:var(--font-heading,inherit)}
.p1-financing__text span{color:var(--color-muted,#666);font-size:.95rem}
.p1-financing .btn{white-space:nowrap}
</style>
  <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
  <div class="p1-financing__text">
    <strong><?= $h($heading) ?></strong>
    <span>Financing available through <?= $h($financing['partner']) ?><?= $note !== '' ? ', ' . $h($note) : '' ?>.</span>
  </div>
  <a class="btn btn-outline" href="<?= $h($financing['url']) ?>" target="_blank" rel="noopener nofollow"><?= $h($cta) ?></a>
</aside>
<?php   return ob_get_clean();
    }
}
