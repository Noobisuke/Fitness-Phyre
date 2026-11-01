<?php
/**
 * Security helpers: sessions, CSRF tokens, client IP.
 */
date_default_timezone_set('Asia/Kolkata');

/** Hide PHP errors from visitors (they are written to the server error log instead). */
ini_set('display_errors', '0');
ini_set('log_errors', '1');

/** Start a session with safe cookie settings. */
function start_secure_session(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }
    ini_set('session.use_strict_mode', '1');   // refuse session ids the server did not create
    ini_set('session.use_only_cookies', '1');  // never accept a session id from the URL
    $https = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
    session_name('fpsid');
    session_set_cookie_params([
        'lifetime' => 0,          // ends when the browser closes
        'path'     => '/',
        'secure'   => $https,     // cookie only over HTTPS when the site uses HTTPS
        'httponly' => true,       // JavaScript cannot read it
        'samesite' => 'Lax',      // helps block cross-site request forgery
    ]);
    session_start();
}

/** CSRF token: a random value stored in the session and sent with every form. */
function csrf_token(): string
{
    start_secure_session();
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

/** Hidden input to place inside every POST form. */
function csrf_field(): string
{
    return '<input type="hidden" name="csrf" value="' . e(csrf_token()) . '">';
}

/** True when the submitted token matches the session token. */
function csrf_check(): bool
{
    start_secure_session();
    $sent = $_POST['csrf'] ?? '';
    return is_string($sent) && !empty($_SESSION['csrf']) && hash_equals($_SESSION['csrf'], $sent);
}

/**
 * The visitor's IP. Only REMOTE_ADDR is trusted: headers such as X-Forwarded-For
 * can be faked by visitors. (If the host puts the site behind a proxy, ask them.)
 */
function client_ip(): string
{
    return substr((string)($_SERVER['REMOTE_ADDR'] ?? ''), 0, 45);
}

/**
 * Security headers, sent from PHP so they work on any host.
 *  - Content-Security-Policy: the browser may only load scripts/styles/images from this site
 *    (plus OpenStreetMap map tiles), which blocks most injected-script (XSS) attacks.
 *  - The rest stop clickjacking, content-type guessing and unnecessary referrer leaks.
 */
function send_security_headers(): void
{
    if (headers_sent()) {
        return;
    }
    header("Content-Security-Policy: default-src 'self'; script-src 'self'; style-src 'self' 'unsafe-inline'; "
         . "img-src 'self' data: https://tile.openstreetmap.org; font-src 'self'; connect-src 'self'; "
         . "object-src 'none'; base-uri 'self'; form-action 'self'; frame-ancestors 'self'");
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: SAMEORIGIN');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header('Permissions-Policy: geolocation=(), camera=(), microphone=(), payment=()');
    if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
        header('Strict-Transport-Security: max-age=15552000');   // 6 months: browser always uses HTTPS
    }
}

/** Redirect and stop. */
function redirect(string $to): void
{
    header('Location: ' . $to);
    exit;
}
