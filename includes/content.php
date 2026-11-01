<?php
/**
 * Content helpers: opening hours, gallery photos, plans, reviews.
 * Everything here reads from config files or folders, so nothing is invented.
 */

/** True when verified opening hours with real times exist. */
function hours_available(): bool
{
    if (!site('hours_verified')) {
        return false;
    }
    foreach (site('hours') as $h) {
        if (!empty($h['open']) && !empty($h['close'])) {
            return true;
        }
    }
    return false;
}

/**
 * Gallery photos are read from assets/images/gallery/.
 * Name files like  category-description.jpg  e.g.  equipment-free-weights.jpg
 * Categories: gym, equipment, training, classes, community.
 * Returns [] while the folder has no real photos (placeholders are shown instead).
 */
function gallery_images(): array
{
    $dir   = __DIR__ . '/../assets/images/gallery/';
    $files = glob($dir . '*.{jpg,jpeg,png,webp,JPG,JPEG,PNG,WEBP}', GLOB_BRACE) ?: [];
    sort($files);
    $out = [];
    foreach ($files as $f) {
        $base = pathinfo($f, PATHINFO_FILENAME);
        $parts = explode('-', $base, 2);
        $cat = strtolower($parts[0]);
        if (!in_array($cat, ['gym', 'equipment', 'training', 'classes', 'community'], true)) {
            $cat = 'gym';
            $label = $base;
        } else {
            $label = $parts[1] ?? $base;
        }
        $label = preg_replace('/-[0-9a-f]{6}$/', '', $label);   // drop the random suffix added on upload
        $size = @getimagesize($f);
        $out[] = [
            'label'    => ucfirst(str_replace(['-', '_'], ' ', $label)),
            'src'      => 'images/gallery/' . basename($f),
            'category' => $cat,
            'alt'      => 'Fitness Phyre, Ludhiana: ' . ucfirst(str_replace(['-', '_'], ' ', $label)),
            'w'        => $size[0] ?? 800,
            'h'        => $size[1] ?? 600,
        ];
    }
    return $out;
}

/** Trainers from config/trainers.php (empty until real details are supplied). */
function site_trainers(): array
{
    return require __DIR__ . '/../config/trainers.php';
}

/** Membership plans from config/plans.php (empty until the gym confirms them). */
function membership_plans(): array
{
    return require __DIR__ . '/../config/plans.php';
}

/** Genuine reviews from config/reviews.php (empty by default). */
function site_reviews(): array
{
    return require __DIR__ . '/../config/reviews.php';
}

/**
 * A real photo if the file exists in assets/images/, otherwise a clearly labelled placeholder.
 * Drop the real file in with the exact name and it replaces the placeholder automatically.
 */
function photo_or_placeholder(string $relPath, string $alt, string $label, int $w, int $h): string
{
    $file = __DIR__ . '/../assets/images/' . $relPath;
    if (is_file($file)) {
        $size = @getimagesize($file);
        $rw = $size[0] ?? $w;
        $rh = $size[1] ?? $h;
        return '<img class="photo" src="' . e(asset('images/' . $relPath)) . '" alt="' . e($alt) . '" width="' . (int)$rw . '" height="' . (int)$rh . '" loading="lazy">';
    }
    return '<div class="placeholder-box" role="img" aria-label="Placeholder: ' . e($alt) . ' (photo to be added)"><span>Placeholder<br><strong>'
         . e($label) . '</strong><br><small>Recommended ' . $w . ' x ' . $h . ' px.<br>Save as assets/images/' . e($relPath) . '</small></span></div>';
}
