<?php
/**
 * Reset an admin's password from the command line (forgot the password?):
 *     php database/reset_admin_password.php
 * Refuses to run in a web browser.
 */
if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}
require_once __DIR__ . '/../includes/admin_auth.php';

$pdo = db();
if (!$pdo) {
    fwrite(STDERR, "Cannot connect to the database. Check config/db.php.\n");
    exit(1);
}
echo "Username to reset: ";
$user = trim((string)fgets(STDIN));
echo "New password (10+ characters): ";
$pass = rtrim((string)fgets(STDIN), "\r\n");
if (strlen($pass) < 10) {
    fwrite(STDERR, "Password must be at least 10 characters.\n");
    exit(1);
}
$stmt = $pdo->prepare('UPDATE admins SET password_hash = ? WHERE username = ?');
$stmt->execute([password_hash($pass, PASSWORD_DEFAULT), $user]);
if ($stmt->rowCount() === 0) {
    fwrite(STDERR, "No admin with that username.\n");
    exit(1);
}
$pdo->prepare('DELETE FROM login_attempts')->execute();
echo "Password updated for '{$user}'.\n";
