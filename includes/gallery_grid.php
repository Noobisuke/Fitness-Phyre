<?php
/* Gallery tiles. Set $gallery_limit (int) to show only the first N. Real photos if present, else labelled placeholders. */
$images = gallery_images();
$gallery_limit = $gallery_limit ?? 0;
if ($gallery_limit > 0) { $images = array_slice($images, 0, $gallery_limit); }
$placeholders = ['Gym floor', 'Equipment', 'Training', 'Classes', 'Community', 'Gym floor'];
?>
<?php if ($images): ?>
    <ul class="gallery-grid" id="galleryGrid">
    <?php foreach ($images as $img): ?>
        <li data-category="<?= e($img['category']) ?>">
            <a href="<?= e(asset($img['src'])) ?>" class="gallery-item" data-lightbox>
                <img src="<?= e(asset($img['src'])) ?>" alt="<?= e($img['alt']) ?>" width="<?= (int)$img['w'] ?>" height="<?= (int)$img['h'] ?>" loading="lazy">
            </a>
        </li>
    <?php endforeach; ?>
    </ul>
<?php else: ?>
    <ul class="gallery-grid" id="galleryGrid">
    <?php foreach (array_slice($placeholders, 0, $gallery_limit ?: 6) as $label): ?>
        <li><div class="placeholder-box gallery-ph"><span>Placeholder<br><strong><?= e($label) ?></strong><br><small>Real photo to be added</small></span></div></li>
    <?php endforeach; ?>
    </ul>
<?php endif; ?>
