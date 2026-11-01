<?php
/**
 * ONE-TIME admin account creation (for hosting without command-line access).
 *  - Works only while the admins table is EMPTY.
 *  - Needs the 'setup_key' you wrote in config/db.php.
 *  - DELETE THIS FILE from the server after creating your admin.
 * (On your own computer you can use:  php database/create_admin.php )
 */
require_once __DIR__ . '/../includes/admin_auth.php';
admin_start();

$pdo = db();
$key = (string)db_setting('setup_key', '');
$message = '';
$done = false;
$locked = false;

if (!$pdo) {
    $message = 'Database not available. Check config/db.php.';
    $locked = true;
} elseif ($key === '' || strlen($key) < 12) {
    $message = 'Setup is disabled. Add a setup_key (12+ characters) to config/db.php first.';
    $locked = true;
} else {
    try {
        if ((int)$pdo->query('SELECT COUNT(*) FROM admins')->fetchColumn() > 0) {
            $message = 'An admin already exists. For safety this page is switched off. Please delete admin/setup.php.';
            $locked = true;
        }
    } catch (PDOException $e) {
        error_log('Setup check failed: ' . $e->getMessage());
        $message = 'Database tables are missing. Import database/schema.sql first.';
        $locked = true;
    }
}

if (!$locked && ($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    $sent = is_string($_POST['setup_key'] ?? null) ? $_POST['setup_key'] : '';
    if (!csrf_check()) {
        $message = 'Session expired. Please try again.';
    } elseif (!hash_equals($key, $sent)) {
        $message = 'Wrong setup key.';
    } else {
        $err = create_admin($pdo, trim((string)($_POST['username'] ?? '')), (string)($_POST['password'] ?? ''));
        if ($err) { $message = $err; } else { $done = true; }
    }
}

$admin_title = 'Create admin';
include __DIR__ . '/../includes/admin_header.php';
?>
<div class="login-box card">
    <h1 class="admin-h1">Create admin account</h1>
    <?php if ($done): ?>
        <div class="alert alert-success" role="status">Admin created. Now DELETE admin/setup.php from the server, then <a href="<?= e(url('admin/login.php')) ?>">log in</a>.</div>
    <?php else: ?>
        <?php if ($message): ?><div class="alert alert-error" role="alert"><?= e($message) ?></div><?php endif; ?>
        <?php if (!$locked): ?>
        <form method="post" action="<?= e(url('admin/setup.php')) ?>">
            <?= csrf_field() ?>
            <div class="form-row"><label for="setup_key">Setup key (from config/db.php)</label>
                <input type="password" id="setup_key" name="setup_key" required autocomplete="off"></div>
            <div class="form-row"><label for="username">Choose a username</label>
                <input type="text" id="username" name="username" required maxlength="50" autocomplete="off"></div>
            <div class="form-row"><label for="password">Choose a password (10+ characters)</label>
                <input type="password" id="password" name="password" required minlength="10" autocomplete="new-password"></div>
            <button type="submit" class="btn btn-accent">Create admin</button>
        </form>
        <?php endif; ?>
    <?php endif; ?>
</div>
<?php include __DIR__ . '/../includes/admin_footer.php'; ?>
