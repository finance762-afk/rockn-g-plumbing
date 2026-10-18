<?php
/**
 * text-us.php — sticky mobile "Text us" button (Web Builder v8, 2026-10-09).
 * Canonical copy: ~/crm/references/components/text-us.php
 *
 * Renders ONLY when config.php sets $textUsNumber (E.164, the OWNER'S OWN number from
 * build-plan.json integrations.text_us_number). Rule (Oct 8 2026): client texting always goes to the
 * owner's own phone — never our toll-free. Empty → renders nothing.
 *
 * Use (footer.php, once, after the sticky call bar if the site has one):
 *   <?php require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/text-us.php'; echo p1_text_us(); ?>
 * Options: ['body' => 'Hi, I would like a quote for ...', 'label' => 'Text us']
 */
if (!function_exists('p1_text_us')) {
    function p1_text_us(array $opt = []): string {
        global $textUsNumber, $siteName;
        $num = preg_replace('/[^0-9+]/', '', (string) ($textUsNumber ?? ''));
        if ($num === '' || strlen($num) < 10) return '';
        $h = fn($v) => htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8');
        $body = $opt['body'] ?? ('Hi ' . $siteName . ', I found you online and would like a quote.');
        $label = $opt['label'] ?? 'Text us';
        ob_start(); ?>
<a class="p1-text-us" href="sms:<?= $h($num) ?>?&body=<?= rawurlencode($body) ?>" aria-label="<?= $h($label) ?> at <?= $h($num) ?>" data-p1-component="text-us">
<style>
.p1-text-us{position:fixed;right:1rem;bottom:calc(1rem + env(safe-area-inset-bottom));z-index:60;display:none;align-items:center;gap:.5rem;padding:.8rem 1.1rem;border-radius:999px;background:var(--color-accent,var(--color-primary,#1a4ed8));color:var(--color-white,#fff);font-weight:700;text-decoration:none;box-shadow:var(--shadow-lg,0 12px 30px rgba(0,0,0,.2))}
.p1-text-us svg{width:20px;height:20px;flex:none}
.p1-text-us:focus-visible{outline:3px solid var(--color-white,#fff);outline-offset:2px}
body.has-sticky-bar .p1-text-us{bottom:calc(4.5rem + env(safe-area-inset-bottom))}
@media (max-width:900px){.p1-text-us{display:inline-flex}}
@media (prefers-reduced-motion:no-preference){.p1-text-us{transition:transform .15s ease}.p1-text-us:active{transform:scale(.97)}}
</style>
  <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
  <span><?= $h($label) ?></span>
</a>
<?php   return ob_get_clean();
    }
}
