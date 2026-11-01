<?php
/**
 * Small helper functions used by every page.
 * Kept deliberately simple so each one can be explained in a viva.
 */

/** Load the central business config once and return it. */
function site(?string $key = null)
{
    static $cfg = null;
    if ($cfg === null) {
        $cfg = require __DIR__ . '/../config/site.php';
    }
    return $key === null ? $cfg : ($cfg[$key] ?? null);
}

/** Escape text for safe output in HTML (prevents XSS). Use on ALL dynamic output. */
function e($value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

/** Build a URL to a file/page inside the site, respecting base_url. */
function url(string $path = ''): string
{
    return rtrim((string)site('base_url'), '/') . '/' . ltrim($path, '/');
}

/** Link for assets (css/js/images). */
function asset(string $path): string
{
    $path = ltrim($path, '/');
    $file = __DIR__ . '/../assets/' . $path;
    // ?v=<file time> makes browsers fetch a changed file even though files are cached for a month.
    $v = is_file($file) ? '?v=' . filemtime($file) : '';
    return url('assets/' . $path) . $v;
}

/** tel: link for the call buttons. */
function tel_link(): string
{
    return 'tel:' . site('phone_e164');
}

/** WhatsApp link with a pre-filled message. */
function wa_link(string $message = 'Hi Fitness Phyre, I would like to know more about membership plans.'): string
{
    return 'https://wa.me/' . site('whatsapp_number') . '?text=' . rawurlencode($message);
}

/** Google Maps "directions" link to the gym's coordinates. */
function directions_link(): string
{
    return 'https://www.google.com/maps/dir/?api=1&destination=' . site('lat') . ',' . site('lng');
}

/** Full one-line address for display. */
function full_address(): string
{
    return site('address_line') . ', ' . site('city') . ', ' . site('state');
}

/** Absolute URL of a page (needs site_url set at deployment); falls back to relative. */
function canonical(string $path = ''): string
{
    $root = rtrim((string)site('site_url'), '/');
    return $root === '' ? url($path) : $root . '/' . ltrim($path, '/');
}

/** Returns true when a social link has really been configured. */
function has_social(): bool
{
    return (bool)array_filter(site('social'));
}

/** Convert "06:00" to "6:00 AM". */
function fmt_time(string $t): string
{
    $d = DateTime::createFromFormat('H:i', $t);
    return $d ? $d->format('g:i A') : $t;
}
