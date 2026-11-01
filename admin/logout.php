<?php
require_once __DIR__ . '/../includes/admin_auth.php';
admin_start();

// Logging out needs a POST with a valid token, so another website cannot log you out by linking here.
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && csrf_check()) {
    admin_logout();
}
redirect(url('admin/login.php'));
