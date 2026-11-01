<?php
/**
 * sitemap.xml generator (the .htaccess maps /sitemap.xml to this file).
 * Uses site_url from config/site.php. Set it before launch.
 */
require_once __DIR__ . '/includes/functions.php';

$root = rtrim((string)site('site_url'), '/');
if ($root === '') {
    // Fallback while site_url is empty: use the address the visitor used (letters/digits/dots/colon only).
    $host = (string)($_SERVER['HTTP_HOST'] ?? 'localhost');
    $host = preg_match('/^[A-Za-z0-9.\-:]+$/', $host) ? $host : 'localhost';
    $https = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
    $root = ($https ? 'https://' : 'http://') . $host . rtrim((string)site('base_url'), '/');
}

$pages = ['' => 'index.php', 'about.php' => 'about.php', 'programs.php' => 'programs.php', 'membership.php' => 'membership.php',
          'gallery.php' => 'gallery.php', 'reviews.php' => 'reviews.php', 'contact.php' => 'contact.php', 'trial.php' => 'trial.php'];

header('Content-Type: application/xml; charset=UTF-8');
echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
foreach ($pages as $path => $file) {
    $mtime = @filemtime(__DIR__ . '/' . $file) ?: time();
    echo '  <url><loc>' . htmlspecialchars($root . '/' . $path, ENT_XML1) . '</loc><lastmod>' . date('Y-m-d', $mtime) . '</lastmod></url>' . "\n";
}
echo '</urlset>' . "\n";
