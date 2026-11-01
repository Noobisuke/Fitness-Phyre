<?php
/**
 * Create an admin account from the command line:
 *     php database/create_admin.php
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

echo "Username: ";
$user = trim((string)fgets(STDIN));
echo "Password (10+ characters, shown as you type): ";
$pass = rtrim((string)fgets(STDIN), "\r\n");

$err = create_admin($pdo, $user, $pass);
if ($err) {
    fwrite(STDERR, $err . "\n");
    exit(1);
}
echo "Admin '{$user}' created.\n";
