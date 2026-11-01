<?php
$page_slug  = 'programs';
$page_title = 'Training Programs';
$page_desc  = 'Training at Fitness Phyre, Ludhiana: strength, cardio, functional and group fitness. Ask us which programs are running now.';
include __DIR__ . '/includes/header.php';
?>
<section class="page-banner">
    <div class="container">
        <h1>Programs</h1>
        <p>Strength, cardio, functional and group training.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <p class="notice" style="max-width:720px;margin-bottom:2rem">Programs and class timings can change. Please call or WhatsApp us to confirm what is running now before you visit. Results vary from person to person and depend on your effort, routine and health.</p>
        <h2 class="sr-only">All programs</h2>
        <?php $detailed = true; include __DIR__ . '/includes/programs_grid.php'; ?>
    </div>
</section>

<?php include __DIR__ . '/includes/cta.php'; ?>
<?php include __DIR__ . '/includes/footer.php'; ?>
