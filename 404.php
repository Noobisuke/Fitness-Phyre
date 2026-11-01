<?php
http_response_code(404);
$page_slug  = '404';
$page_title = 'Page not found';
$page_desc  = 'This page could not be found on the Fitness Phyre website.';
$noindex    = true;
include __DIR__ . '/includes/header.php';
?>
<section class="page-banner">
    <div class="container">
        <h1>Page not found</h1>
        <p>The page you were looking for does not exist or has moved.</p>
    </div>
</section>
<section class="section">
    <div class="container">
        <p>Try one of these instead:</p>
        <div class="btn-row">
            <a class="btn btn-dark" href="<?= e(url('index.php')) ?>">Home</a>
            <a class="btn btn-outline" href="<?= e(url('programs.php')) ?>">Programs</a>
            <a class="btn btn-outline" href="<?= e(url('contact.php')) ?>">Contact</a>
            <a class="btn btn-wa" href="<?= e(wa_link()) ?>" target="_blank" rel="noopener">WhatsApp</a>
        </div>
    </div>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
