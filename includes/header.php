<?php
/**
 * Shared header. Every page sets these BEFORE including this file:
 *   $page_slug   (e.g. 'about')   - highlights the menu item
 *   $page_title  (short title)    - becomes "<title> | Fitness Phyre"
 *   $page_desc   (meta description, ~150 characters)
 */
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/content.php';
require_once __DIR__ . '/schema.php';
require_once __DIR__ . '/security.php';
send_security_headers();

$page_slug  = $page_slug  ?? '';
$page_title = $page_title ?? site('name') . ' - ' . site('tagline');
$page_desc  = $page_desc  ?? 'Fitness Phyre is a gym and fitness centre in Karnail Singh Nagar, Ludhiana, near Pakhowal Road.';
$full_title = ($page_slug === 'home') ? $page_title : $page_title . ' | ' . site('name');

// Share image: add assets/images/og-default.jpg (1200 x 630) AND set site_url in config/site.php
$og_image = '';
if (site('site_url') && is_file(__DIR__ . '/../assets/images/og-default.jpg')) {
    $og_image = rtrim(site('site_url'), '/') . '/assets/images/og-default.jpg';
}

$nav = [
    'home'       => ['Home',        'index.php'],
    'about'      => ['About',       'about.php'],
    'programs'   => ['Programs',    'programs.php'],
    'membership' => ['Membership',  'membership.php'],
    'gallery'    => ['Gallery',     'gallery.php'],
    'reviews'    => ['Reviews',     'reviews.php'],
    'contact'    => ['Contact',     'contact.php'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($full_title) ?></title>
    <meta name="description" content="<?= e($page_desc) ?>">
    <link rel="canonical" href="<?= e(canonical($page_slug === 'home' ? '' : basename($_SERVER['SCRIPT_NAME']))) ?>">

    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="<?= e(site('name')) ?>">
    <meta property="og:title" content="<?= e($full_title) ?>">
    <meta property="og:description" content="<?= e($page_desc) ?>">
    <meta property="og:locale" content="en_IN">
    <meta property="og:url" content="<?= e(canonical($page_slug === 'home' ? '' : basename($_SERVER['SCRIPT_NAME']))) ?>">
<?php if ($og_image): ?>
    <meta property="og:image" content="<?= e($og_image) ?>">
    <meta name="twitter:card" content="summary_large_image">
<?php endif; ?>
<?php if (!empty($noindex)): ?>
    <meta name="robots" content="noindex, follow">
<?php endif; ?>
    <link rel="icon" href="<?= e(asset('images/favicon.svg')) ?>" type="image/svg+xml">

    <meta name="theme-color" content="#17211f">
    <link rel="stylesheet" href="<?= e(asset('css/style.css')) ?>">
    <link rel="stylesheet" href="<?= e(asset('css/sections.css')) ?>">
    <?php if (in_array($page_slug, ['home', 'contact', 'about'], true)): ?>
    <script type="application/ld+json">
<?= local_business_schema() ?>

    </script>
    <?php endif; ?>
    <?= $extra_head ?? '' ?>
</head>
<body>
<a class="skip-link" href="#main">Skip to main content</a>

<header class="site-header" id="top">
    <div class="container header-inner">
        <a class="brand" href="<?= e(url('index.php')) ?>" aria-label="<?= e(site('name')) ?> - home">
            <?php if (site('logo')): ?>
                <img src="<?= e(url(site('logo'))) ?>" alt="<?= e(site('name')) ?> logo" height="40">
            <?php else: ?>
                <span class="brand-mark" aria-hidden="true">FP</span>
                <span class="brand-text"><?= e(site('name')) ?></span>
            <?php endif; ?>
        </a>

        <button class="nav-toggle" id="navToggle" aria-expanded="false" aria-controls="siteNav">
            <span class="sr-only">Menu</span>
            <span class="bars" aria-hidden="true"></span>
        </button>

        <nav class="site-nav" id="siteNav" aria-label="Main navigation">
            <ul>
                <?php foreach ($nav as $slug => [$label, $file]): ?>
                    <li>
                        <a href="<?= e(url($file)) ?>"<?= $slug === $page_slug ? ' class="active" aria-current="page"' : '' ?>><?= e($label) ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
            <a class="btn btn-accent nav-cta" href="<?= e(url('trial.php')) ?>">Book a Trial</a>
        </nav>
    </div>
</header>

<main id="main">
