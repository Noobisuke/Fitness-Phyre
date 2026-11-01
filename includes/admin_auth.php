<?php
/**
 * Admin login, session checks and record actions.
 * Every admin page calls require_admin() first.
 */
require_once __DIR__ . '/bootstrap.php';

const ADMIN_IDLE_SECONDS = 3600;     // log out after 1 hour of no activity
const ADMIN_MAX_FAILS    = 5;        // failed logins allowed per IP...
const ADMIN_FAIL_WINDOW  = 900;      // ...within 15 minutes

/** Start the session and tell browsers/search engines not to cache or index admin pages. */
function admin_start(): void
{
    start_secure_session();
    send_security_headers();
    header('Cache-Control: no-store, max-age=0');
    header('X-Robots-Tag: noindex, nofollow');
}

function admin_logged_in(): bool
{
    return !empty($_SESSION['admin_id'])
        && (time() - (int)($_SESSION['admin_seen'] ?? 0)) < ADMIN_IDLE_SECONDS;
}

/** Stop here and send visitors to the login page unless an admin is logged in. */
function require_admin(): void
{
    admin_start();
    if (!admin_logged_in()) {
        $_SESSION = [];
        redirect(url('admin/login.php'));
    }
    $_SESSION['admin_seen'] = time();
}

/** Try to log in. Returns an error message, or null on success. */
function admin_login(string $username, string $password): ?string
{
    $pdo = db();
    if (!$pdo) {
        return 'The database is not available. Check config/db.php.';
    }
    $ip    = client_ip();
    $since = date('Y-m-d H:i:s', time() - ADMIN_FAIL_WINDOW);

    // Tidy: forget failed attempts older than a day
    $pdo->prepare('DELETE FROM login_attempts WHERE attempted_at < ?')->execute([date('Y-m-d H:i:s', time() - 86400)]);

    $stmt = $pdo->prepare('SELECT COUNT(*) FROM login_attempts WHERE ip_address = ? AND attempted_at >= ?');
    $stmt->execute([$ip, $since]);
    if ((int)$stmt->fetchColumn() >= ADMIN_MAX_FAILS) {
        return 'Too many failed attempts. Please wait 15 minutes and try again.';
    }

    $stmt = $pdo->prepare('SELECT id, password_hash FROM admins WHERE username = ?');
    $stmt->execute([$username]);
    $row = $stmt->fetch();

    // Always run password_verify (even for unknown users) so timing does not reveal valid usernames.
    $hash = $row['password_hash'] ?? '$2y$10$LXOkBeF7gTBhdeaDjkBG0OUDYDgSwHMvFfuTLRRRyYSPjwX5GDJPi';
    $ok = password_verify($password, $hash) && $row;

    if (!$ok) {
        $pdo->prepare('INSERT INTO login_attempts (ip_address, attempted_at) VALUES (?, ?)')
            ->execute([$ip, date('Y-m-d H:i:s')]);
        return 'Incorrect username or password.';   // same message for both cases on purpose
    }

    $pdo->prepare('DELETE FROM login_attempts WHERE ip_address = ?')->execute([$ip]);
    if (password_needs_rehash($hash, PASSWORD_DEFAULT)) {
        $pdo->prepare('UPDATE admins SET password_hash = ? WHERE id = ?')
            ->execute([password_hash($password, PASSWORD_DEFAULT), $row['id']]);
    }
    $pdo->prepare('UPDATE admins SET last_login_at = ? WHERE id = ?')->execute([date('Y-m-d H:i:s'), $row['id']]);

    session_regenerate_id(true);          // new session id after login (stops session fixation)
    $_SESSION['admin_id']   = (int)$row['id'];
    $_SESSION['admin_user'] = $username;
    $_SESSION['admin_seen'] = time();
    return null;
}

function admin_logout(): void
{
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', time() - 3600, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
    }
    session_destroy();
}

/** Create an admin account. Returns an error message, or null on success. */
function create_admin(PDO $pdo, string $username, string $password): ?string
{
    if (!preg_match('/^[A-Za-z0-9_.-]{3,50}$/', $username)) {
        return 'Username must be 3-50 characters: letters, numbers, dot, dash or underscore.';
    }
    if (strlen($password) < 10) {
        return 'Password must be at least 10 characters.';
    }
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM admins WHERE username = ?');
    $stmt->execute([$username]);
    if ((int)$stmt->fetchColumn() > 0) {
        return 'That username already exists.';
    }
    $pdo->prepare('INSERT INTO admins (username, password_hash, created_at) VALUES (?, ?, ?)')
        ->execute([$username, password_hash($password, PASSWORD_DEFAULT), date('Y-m-d H:i:s')]);
    return null;
}

/** Which table each admin list page works on (whitelist, never taken from the request). */
function admin_table(string $key): string
{
    return $key === 'trials' ? 'trial_requests' : 'enquiries';
}

/**
 * Handle "mark contacted" and "delete" buttons. Call before any output.
 * Only POST + valid CSRF token + logged-in admin can reach the database change.
 */
function admin_handle_record_action(string $key, string $self): void
{
    if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
        return;
    }
    $show = in_array($_POST['show'] ?? '', ['new', 'contacted'], true) ? $_POST['show'] : 'all';
    $back = url('admin/' . $self) . ($show === 'all' ? '' : '?show=' . $show);

    if (!csrf_check()) {
        $_SESSION['admin_flash'] = 'Session expired. Please try again.';
        redirect($back);
    }
    $id = filter_var($_POST['id'] ?? '', FILTER_VALIDATE_INT);
    $action = $_POST['action'] ?? '';
    $pdo = db();
    if ($pdo && $id && in_array($action, ['contacted', 'new', 'delete'], true)) {
        $table = admin_table($key);
        if ($action === 'delete') {
            $pdo->prepare("DELETE FROM {$table} WHERE id = ?")->execute([$id]);
            $_SESSION['admin_flash'] = 'Record deleted.';
        } else {
            $pdo->prepare("UPDATE {$table} SET is_contacted = ? WHERE id = ?")->execute([$action === 'contacted' ? 1 : 0, $id]);
            $_SESSION['admin_flash'] = $action === 'contacted' ? 'Marked as contacted.' : 'Marked as new.';
        }
    }
    redirect($back);
}
