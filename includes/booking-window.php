<?php
/**
 * booking-window.php — "Book an estimate visit" time-window request (Web Builder v8 Flagship, 2026-10-09).
 * Canonical copy: ~/crm/references/components/booking-window.php
 *
 * AUTHORIZATION-GATED. Renders ONLY when config.php sets
 *   $bookingWindows = ['authorized' => true, 'days' => ['Mon','Tue','Wed','Thu','Fri'],
 *                      'windows' => ['8–10 AM','10 AM–12 PM','1–3 PM','3–5 PM'], 'lead_days' => 1];
 * mirrored from build-plan.json integrations.booking (authorized_by / authorized_at recorded by onboarding).
 * It is a REQUEST, not a confirmed appointment: the lead lands in the portal/CRM with booking=1,
 * preferred_date and time_window, the owner gets the SMS/email alert and confirms. Copy must never say
 * "booked" or "confirmed" before the owner replies.
 *
 * Use:  <?php require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/booking-window.php'; echo p1_booking_window(); ?>
 * Options: ['heading' => '...', 'id' => 'book-visit', 'compact' => false]
 */
require_once __DIR__ . '/p1-form-helpers.php';

if (!function_exists('p1_booking_window')) {
    function p1_booking_window(array $opt = []): string {
        global $bookingWindows, $formAction, $siteName, $businessHours;
        if (empty($bookingWindows) || empty($bookingWindows['authorized']) || empty($bookingWindows['windows'])) return '';
        $h  = fn($v) => htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8');
        $id = preg_replace('/[^a-z0-9-]/', '', strtolower($opt['id'] ?? 'book-visit')) ?: 'book-visit';
        $heading = $opt['heading'] ?? 'Request an estimate visit';
        $dayMap = ['sun' => 0, 'mon' => 1, 'tue' => 2, 'wed' => 3, 'thu' => 4, 'fri' => 5, 'sat' => 6];
        $days = [];
        foreach ((array) ($bookingWindows['days'] ?? ['Mon','Tue','Wed','Thu','Fri']) as $d) {
            $k = strtolower(substr(trim((string) $d), 0, 3));
            if (isset($dayMap[$k])) $days[$dayMap[$k]] = true;
        }
        if (!$days) $days = [1 => true, 2 => true, 3 => true, 4 => true, 5 => true];
        $lead = max(0, (int) ($bookingWindows['lead_days'] ?? 1));
        $dateOpts = '';
        $tz = new DateTimeZone(date_default_timezone_get());
        $d = new DateTime('today', $tz); $d->modify('+' . $lead . ' day');
        for ($i = 0, $n = 0; $i < 21 && $n < 10; $i++) {
            if (!empty($days[(int) $d->format('w')])) { $dateOpts .= '<option value="' . $d->format('Y-m-d') . '">' . $d->format('D, M j') . '</option>'; $n++; }
            $d->modify('+1 day');
        }
        $winOpts = '';
        foreach ((array) $bookingWindows['windows'] as $w) { $w = trim((string) $w); if ($w !== '') $winOpts .= '<option value="' . $h($w) . '">' . $h($w) . '</option>'; }
        ob_start(); ?>
<section class="p1-booking<?= !empty($opt['compact']) ? ' p1-booking--compact' : '' ?>" id="<?= $h($id) ?>" aria-labelledby="<?= $h($id) ?>-h" data-p1-component="booking-window">
<style>
.p1-booking{padding:var(--space-xl,4rem) 0}
.p1-booking__inner{max-width:var(--container-max,1180px);margin:0 auto;padding:0 var(--space-lg,1.5rem);display:grid;gap:var(--space-lg,1.5rem);grid-template-columns:minmax(0,.9fr) minmax(0,1.1fr);align-items:start}
.p1-booking--compact .p1-booking__inner{grid-template-columns:1fr;max-width:640px}
.p1-booking__copy h2{margin:0 0 .5rem;font-family:var(--font-heading,inherit)}
.p1-booking__copy p{color:var(--color-muted,var(--text-light,#555))}
.p1-booking__card{background:var(--color-surface,#fff);border:1px solid var(--color-line,rgba(0,0,0,.1));border-radius:var(--radius-lg,16px);padding:var(--space-lg,1.5rem);box-shadow:var(--shadow-md,0 8px 24px rgba(0,0,0,.08))}
.p1-booking .form-grid{display:grid;grid-template-columns:1fr 1fr;gap:.75rem}
.p1-booking .form-row{margin-bottom:.75rem}
.p1-booking .form-row--full{grid-column:1/-1}
.p1-booking label:not(.consent){display:block;font-weight:600;margin-bottom:.3rem}
.p1-booking input:not([type=checkbox]),.p1-booking select,.p1-booking textarea{width:100%;min-height:46px;padding:.6rem .8rem;border:1px solid var(--color-line,rgba(0,0,0,.18));border-radius:var(--radius-md,10px);font:inherit;font-size:16px;background:var(--color-surface,#fff)}
.p1-booking textarea{min-height:90px}
.p1-booking .consent{display:flex;gap:.5rem;align-items:flex-start;font-size:.85rem;margin:.75rem 0}
.p1-booking .consent input{margin-top:.2rem}
.p1-booking__note{font-size:.85rem;color:var(--color-muted,#666);margin-top:.5rem}
@media (max-width:700px){.p1-booking__inner,.p1-booking .form-grid{grid-template-columns:1fr}}
</style>
  <div class="p1-booking__inner">
    <div class="p1-booking__copy">
      <h2 id="<?= $h($id) ?>-h"><?= $h($heading) ?></h2>
      <p>Pick a day and a time window that suits you. <?= $h($siteName) ?> confirms by text or call before anyone comes out.</p>
      <?php if (!empty($businessHours)): ?><p class="p1-booking__note">Hours: <?= $h($businessHours) ?></p><?php endif; ?>
    </div>
    <div class="p1-booking__card">
      <form action="<?= $h($formAction) ?>" method="POST" class="p1-booking__form">
        <?= p1_lead_hidden_fields('booking', ['booking' => '1']) ?>
        <div class="form-grid">
          <div class="form-row"><label for="<?= $h($id) ?>-date">Preferred day</label><select id="<?= $h($id) ?>-date" name="preferred_date" required><option value="">Choose a day</option><?= $dateOpts ?></select></div>
          <div class="form-row"><label for="<?= $h($id) ?>-win">Time window</label><select id="<?= $h($id) ?>-win" name="time_window" required><option value="">Choose a window</option><?= $winOpts ?></select></div>
          <div class="form-row"><label for="<?= $h($id) ?>-name">Name</label><input id="<?= $h($id) ?>-name" type="text" name="name" autocomplete="name" required></div>
          <div class="form-row"><label for="<?= $h($id) ?>-phone">Phone</label><input id="<?= $h($id) ?>-phone" type="tel" name="phone" autocomplete="tel" required></div>
          <div class="form-row"><label for="<?= $h($id) ?>-email">Email</label><input id="<?= $h($id) ?>-email" type="email" name="email" autocomplete="email" required></div>
          <div class="form-row"><label for="<?= $h($id) ?>-service">Service</label><select id="<?= $h($id) ?>-service" name="service"><?= p1_service_options() ?></select></div>
          <div class="form-row form-row--full"><label for="<?= $h($id) ?>-addr">Job address</label><input id="<?= $h($id) ?>-addr" type="text" name="job_address" autocomplete="street-address" required></div>
          <div class="form-row form-row--full"><label for="<?= $h($id) ?>-notes">Anything we should know? (optional)</label><textarea id="<?= $h($id) ?>-notes" name="message"></textarea></div>
        </div>
        <?= p1_consent_row($id) ?>
        <button type="submit" class="btn btn-primary btn-block">Request this time</button>
        <p class="p1-booking__note">This sends a request. You will get a text or call to confirm the visit.</p>
      </form>
    </div>
  </div>
</section>
<?php   return ob_get_clean();
    }
}
