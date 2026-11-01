<?php
require_once __DIR__ . '/../includes/admin_auth.php';
admin_start();

if (admin_logged_in()) {
    redirect(url('admin/index.php'));
}

$error = '';
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    if (!csrf_check()) {
        $error = 'Session expired. Please try again.';
    } else {
        $user = is_string($_POST['username'] ?? null) ? trim($_POST['username']) : '';
        $pass = is_string($_POST['password'] ?? null) ? $_POST['password'] : '';
        $error = admin_login($user, $pass) ?? '';
        if ($error === '') {
            redirect(url('admin/index.php'));
        }
    }
}

$admin_title = 'Log in';
include __DIR__ . '/../includes/admin_header.php';
?>
<div class="login-box card">
    <h1 class="admin-h1">Admin login</h1>
    <?php if ($error): ?><div class="alert alert-error" role="alert"><?= e($error) ?></div><?php endif; ?>
    <form method="post" action="<?= e(url('admin/login.php')) ?>">
        <?= csrf_field() ?>
        <div class="form-row">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" autocomplete="username" required maxlength="50">
        </div>
        <div class="form-row">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" autocomplete="current-password" required>
        </div>
        <button type="submit" class="btn btn-accent">Log in</button>
    </form>
</div>
<?php include __DIR__ . '/../includes/admin_footer.php'; ?>
