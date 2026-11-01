<?php
/** Admin layout top. Set $admin_title before including. */
$admin_title = $admin_title ?? 'Admin';
$admin_nav = ['index.php' => 'Dashboard', 'enquiries.php' => 'Enquiries', 'trials.php' => 'Trial requests', 'gallery.php' => 'Gallery'];
$here = basename($_SERVER['SCRIPT_NAME']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title><?= e($admin_title) ?> | Fitness Phyre admin</title>
    <link rel="stylesheet" href="<?= e(asset('css/style.css')) ?>">
    <link rel="stylesheet" href="<?= e(asset('css/admin.css')) ?>">
</head>
<body class="admin">
<header class="admin-bar">
    <div class="container admin-bar-inner">
        <strong>Fitness Phyre admin</strong>
        <?php if (admin_logged_in()): ?>
        <nav aria-label="Admin">
            <?php foreach ($admin_nav as $file => $label): ?>
                <a href="<?= e(url('admin/' . $file)) ?>"<?= $file === $here ? ' aria-current="page"' : '' ?>><?= e($label) ?></a>
            <?php endforeach; ?>
            <a href="<?= e(url('index.php')) ?>" target="_blank" rel="noopener">View site</a>
        </nav>
        <form method="post" action="<?= e(url('admin/logout.php')) ?>">
            <?= csrf_field() ?>
            <button type="submit" class="btn btn-sm btn-outline">Log out (<?= e($_SESSION['admin_user'] ?? '') ?>)</button>
        </form>
        <?php endif; ?>
    </div>
</header>
<main class="container admin-main" id="main">
<?php if (!empty($_SESSION['admin_flash'])): ?>
    <div class="alert alert-success" role="status"><?= e($_SESSION['admin_flash']) ?></div>
    <?php unset($_SESSION['admin_flash']); ?>
<?php endif; ?>
