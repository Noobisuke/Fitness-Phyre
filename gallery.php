<?php
require_once __DIR__ . '/includes/functions.php';
$page_slug  = 'gallery';
$page_title = 'Gallery';
$page_desc  = 'Photos of Fitness Phyre gym and fitness centre in Karnail Singh Nagar, Ludhiana.';
$extra_scripts = '<script src="' . e(asset('js/gallery.js')) . '" defer></script>';
include __DIR__ . '/includes/header.php';
$has_photos = (bool)gallery_images();
?>
<section class="page-banner">
    <div class="container">
        <h1>Gallery</h1>
        <p>A look at Fitness Phyre.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <?php if ($has_photos): ?>
            <div class="filter-bar" role="group" aria-label="Filter photos by category">
                <button type="button" class="filter-btn" data-filter="all" aria-pressed="true">All</button>
                <?php foreach (['gym' => 'Gym', 'equipment' => 'Equipment', 'training' => 'Training', 'classes' => 'Classes', 'community' => 'Community'] as $k => $label): ?>
                    <button type="button" class="filter-btn" data-filter="<?= e($k) ?>" aria-pressed="false"><?= e($label) ?></button>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="notice" style="max-width:720px;margin-bottom:2rem">Real photos of the gym are being added. The boxes below are placeholders.</p>
        <?php endif; ?>
        <?php $gallery_limit = 0; include __DIR__ . '/includes/gallery_grid.php'; ?>
    </div>
</section>

<?php include __DIR__ . '/includes/cta.php'; ?>
<?php include __DIR__ . '/includes/footer.php'; ?>
