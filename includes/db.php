<?php
/**
 * Database connection (PDO). Credentials come from config/db.php,
 * which is NOT in the repository (copy config/db.sample.php to create it).
 * Returns null if the database is not set up or not reachable, so public pages
 * can show a friendly message instead of crashing.
 */
function db(): ?PDO
{
    static $pdo = null;
    static $tried = false;
    if ($tried) {
        return $pdo;
    }
    $tried = true;

    $file = __DIR__ . '/../config/db.php';
    if (!is_file($file)) {
        error_log('Fitness Phyre: config/db.php is missing.');
        return null;
    }
    $c = require $file;

    // 'dsn' is only for automated testing; real sites use the host/name settings.
    $dsn = $c['dsn'] ?? sprintf('mysql:host=%s;dbname=%s;charset=%s', $c['host'], $c['name'], $c['charset'] ?? 'utf8mb4');

    try {
        $pdo = new PDO($dsn, $c['user'] ?? null, $c['pass'] ?? null, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,  // errors become exceptions
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,                   // real prepared statements
        ]);
    } catch (PDOException $e) {
        error_log('Fitness Phyre DB connection failed: ' . $e->getMessage()); // logged, never shown to visitors
        $pdo = null;
    }
    return $pdo;
}

/** Other values from config/db.php (e.g. setup_key). */
function db_setting(string $key, $default = null)
{
    static $c = null;
    if ($c === null) {
        $file = __DIR__ . '/../config/db.php';
        $c = is_file($file) ? (require $file) : [];
    }
    return $c[$key] ?? $default;
}
