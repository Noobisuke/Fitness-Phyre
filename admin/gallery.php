<?php
require_once __DIR__ . '/../includes/admin_auth.php';
require_once __DIR__ . '/../includes/content.php';
require_once __DIR__ . '/../includes/gallery_admin.php';
require_admin();

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    if (!csrf_check()) {
        $_SESSION['admin_flash_error'] = 'Session expired. Please try again.';
    } elseif (($_POST['action'] ?? '') === 'upload') {
        $err = gallery_save_upload($_FILES['photo'] ?? [], (string)($_POST['category'] ?? ''), (string)($_POST['caption'] ?? ''));
        if ($err) { $_SESSION['admin_flash_error'] = $err; } else { $_SESSION['admin_flash'] = 'Photo added to the gallery.'; }
    } elseif (($_POST['action'] ?? '') === 'delete') {
        $_SESSION['admin_flash'] = gallery_delete((string)($_POST['file'] ?? '')) ? 'Photo deleted.' : 'Photo not found.';
    }
    redirect(url('admin/gallery.php'));
}

$images = gallery_images();
$admin_title = 'Gallery';
include __DIR__ . '/../includes/admin_header.php';
?>
<h1 class="admin-h1">Gallery photos</h1>

<?php if (!empty($_SESSION['admin_flash_error'])): ?>
    <div class="alert alert-error" role="alert"><?= e($_SESSION['admin_flash_error']) ?></div>
    <?php unset($_SESSION['admin_flash_error']); ?>
<?php endif; ?>

<section class="card" aria-labelledby="up-h">
    <h2 id="up-h" style="font-size:1.2rem">Add a photo</h2>
    <p class="muted">Only upload photos of Fitness Phyre that you have the right to use. JPG, PNG or WebP, up to 5 MB. Large photos are resized automatically. Best shape: landscape, about 1200 x 900 px.</p>
    <form method="post" action="<?= e(url('admin/gallery.php')) ?>" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <input type="hidden" name="action" value="upload">
        <div class="form-row">
            <label for="photo">Photo</label>
            <input type="file" id="photo" name="photo" accept="image/jpeg,image/png,image/webp" required>
        </div>
        <div class="form-row">
            <label for="category">Category</label>
            <select id="category" name="category" required>
                <option value="">Choose a category</option>
                <?php foreach (GALLERY_CATEGORIES as $k => $label): ?>
                    <option value="<?= e($k) ?>"><?= e($label) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-row">
            <label for="caption">Short description (used as the photo's alt text)</label>
            <input type="text" id="caption" name="caption" maxlength="60" placeholder="e.g. Free weights area">
        </div>
        <button type="submit" class="btn btn-accent">Upload photo</button>
    </form>
</section>

<h2 style="margin-top:2rem;font-size:1.2rem"><?= count($images) ?> photo<?= count($images) === 1 ? '' : 's' ?> in the gallery</h2>
<?php if (!$images): ?>
    <p>No photos yet. The public gallery shows placeholders until you add some.</p>
<?php else: ?>
<ul class="admin-gallery">
    <?php foreach ($images as $img): ?>
        <li class="card">
            <img src="<?= e(asset($img['src'])) ?>" alt="<?= e($img['alt']) ?>" width="200" height="150" loading="lazy">
            <p class="muted"><?= e(GALLERY_CATEGORIES[$img['category']] ?? '') ?>: <?= e($img['label']) ?></p>
            <form method="post" action="<?= e(url('admin/gallery.php')) ?>" data-confirm="Delete this photo permanently?">
                <?= csrf_field() ?>
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="file" value="<?= e(basename($img['src'])) ?>">
                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
            </form>
        </li>
    <?php endforeach; ?>
</ul>
<?php endif; ?>
<?php include __DIR__ . '/../includes/admin_footer.php'; ?>
