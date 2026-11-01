<?php
/**
 * Gallery management for the admin panel.
 * Photos are plain files in assets/images/gallery/ (no database needed).
 * File name format:  category-short-description-abc123.jpg
 */

const GALLERY_DIR       = __DIR__ . '/../assets/images/gallery/';
const GALLERY_CATEGORIES = ['gym' => 'Gym', 'equipment' => 'Equipment', 'training' => 'Training', 'classes' => 'Classes', 'community' => 'Community'];
const GALLERY_MAX_BYTES = 5 * 1024 * 1024;   // 5 MB per photo
const GALLERY_MAX_FILES = 60;                // keeps the page light
const GALLERY_MAX_WIDTH = 1600;              // bigger photos are shrunk

/** Turn typed text into a safe file-name piece: "Free Weights!" -> "free-weights". */
function gallery_slug(string $text): string
{
    $text = strtolower(trim($text));
    $text = preg_replace('/[^a-z0-9]+/', '-', $text);
    $text = trim((string)$text, '-');
    return substr($text, 0, 40) !== '' ? substr($text, 0, 40) : 'photo';
}

/** Reads the camera's "rotate" flag so phone photos are not sideways. */
function gallery_fix_orientation($img, string $path)
{
    if (!function_exists('exif_read_data')) {
        return $img;
    }
    $exif = @exif_read_data($path);
    $o = $exif['Orientation'] ?? 1;
    $angle = [3 => 180, 6 => -90, 8 => 90][$o] ?? 0;
    return $angle ? (imagerotate($img, $angle, 0) ?: $img) : $img;
}

/**
 * Validate and save one uploaded photo. Returns an error message, or null on success.
 * Checks: upload ok, size, real image type (not just the extension), dimensions.
 * The image is re-encoded with GD, which removes hidden data (EXIF GPS, embedded code).
 */
function gallery_save_upload(array $file, string $category, string $caption): ?string
{
    if (!isset(GALLERY_CATEGORIES[$category])) {
        return 'Please choose a category.';
    }
    $err = $file['error'] ?? UPLOAD_ERR_NO_FILE;
    if ($err === UPLOAD_ERR_NO_FILE) {
        return 'Please choose a photo to upload.';
    }
    if ($err === UPLOAD_ERR_INI_SIZE || $err === UPLOAD_ERR_FORM_SIZE) {
        return 'That photo is too large. Maximum size is 5 MB (your host may allow less).';
    }
    if ($err !== UPLOAD_ERR_OK || !is_uploaded_file($file['tmp_name'])) {
        return 'The upload failed. Please try again.';
    }
    if ($file['size'] > GALLERY_MAX_BYTES) {
        return 'That photo is too large. Maximum size is 5 MB.';
    }
    if (count(gallery_images()) >= GALLERY_MAX_FILES) {
        return 'The gallery is full (' . GALLERY_MAX_FILES . ' photos). Delete an old photo first.';
    }

    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
    $types = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
    if (!isset($types[$mime])) {
        return 'Only JPG, PNG or WebP photos are allowed.';
    }
    $info = @getimagesize($file['tmp_name']);
    if (!$info || $info[0] < 400 || $info[1] < 300) {
        return 'The photo is too small (minimum 400 x 300 pixels).';
    }
    if ($info[0] > 8000 || $info[1] > 8000) {
        return 'The photo is too large in pixels (maximum 8000 x 8000).';
    }

    $name = $category . '-' . gallery_slug($caption) . '-' . bin2hex(random_bytes(3)) . '.' . $types[$mime];
    $dest = GALLERY_DIR . $name;
    if (!is_dir(GALLERY_DIR) && !mkdir(GALLERY_DIR, 0755, true)) {
        return 'Could not create the gallery folder.';
    }

    // Re-encode (and shrink) so only pixel data is kept.
    $img = @imagecreatefromstring((string)file_get_contents($file['tmp_name']));
    if (!$img) {
        return 'That file could not be read as a photo.';
    }
    if ($mime === 'image/jpeg') {
        $img = gallery_fix_orientation($img, $file['tmp_name']);
    }
    $w = imagesx($img);
    if ($w > GALLERY_MAX_WIDTH) {
        $img = imagescale($img, GALLERY_MAX_WIDTH);
    }
    $ok = match ($mime) {
        'image/jpeg' => imagejpeg($img, $dest, 82),
        'image/png'  => imagepng($img, $dest, 7),
        default      => imagewebp($img, $dest, 80),
    };
    imagedestroy($img);
    if (!$ok) {
        return 'Could not save the photo.';
    }
    @chmod($dest, 0644);
    return null;
}

/** Delete one photo. Only names that exist in the gallery folder are accepted. */
function gallery_delete(string $filename): bool
{
    $filename = basename($filename);
    foreach (gallery_images() as $img) {
        if (basename($img['src']) === $filename) {
            return @unlink(GALLERY_DIR . $filename);
        }
    }
    return false;
}
