<?php
/**
 * Enquiry + trial request handling: validation, spam checks, saving.
 * One function (handle_form) is called at the top of contact.php and trial.php.
 */

const FORM_INTERESTS = ['Membership', 'Trial session', 'Personal training', 'Strength training', 'Cardio', 'Group classes', 'General enquiry'];
const FORM_TIMES     = ['Morning', 'Afternoon', 'Evening', 'No preference'];
const FORM_GOALS     = ['Build strength', 'Lose weight', 'Improve stamina', 'General fitness', 'Other'];

/** Trim, remove control characters. */
function form_text($value): string
{
    $value = is_string($value) ? trim($value) : '';
    return preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $value) ?? '';
}

/** Indian mobile number -> 10 digits, or null if it does not look valid. */
function normalize_phone(string $raw): ?string
{
    $d = preg_replace('/[\s\-\.\(\)]/', '', $raw);
    $d = preg_replace('/^\+/', '', $d);
    if (!ctype_digit($d)) {
        return null;
    }
    if (strlen($d) === 12 && strpos($d, '91') === 0) { $d = substr($d, 2); }
    if (strlen($d) === 11 && $d[0] === '0')          { $d = substr($d, 1); }
    return preg_match('/^[6-9]\d{9}$/', $d) ? $d : null;
}

/** Validate the fields both forms share. Returns [clean data, errors]. */
function validate_common(array $in, array &$errors): array
{
    $d = [];
    $d['name'] = form_text($in['name'] ?? '');
    if ($d['name'] === '') {
        $errors['name'] = 'Please enter your name.';
    } elseif (mb_strlen($d['name']) < 2 || mb_strlen($d['name']) > 100) {
        $errors['name'] = 'Name must be between 2 and 100 characters.';
    }

    $phoneRaw = form_text($in['phone'] ?? '');
    $d['phone'] = normalize_phone($phoneRaw);
    if ($phoneRaw === '') {
        $errors['phone'] = 'Please enter your phone number.';
    } elseif ($d['phone'] === null) {
        $errors['phone'] = 'Enter a valid 10-digit Indian mobile number.';
    }

    $d['email'] = form_text($in['email'] ?? '');
    if ($d['email'] === '') {
        $d['email'] = null;
    } elseif (mb_strlen($d['email']) > 150 || !filter_var($d['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Enter a valid email address or leave this blank.';
    }

    $d['message'] = form_text($in['message'] ?? '');
    if (mb_strlen($d['message']) > 1000) {
        $errors['message'] = 'Message is too long (maximum 1000 characters).';
    }
    if ($d['message'] === '') { $d['message'] = null; }

    return $d;
}

function validate_enquiry(array $in): array
{
    $errors = [];
    $d = validate_common($in, $errors);

    $d['interested_in'] = form_text($in['interested_in'] ?? '');
    if (!in_array($d['interested_in'], FORM_INTERESTS, true)) {
        $errors['interested_in'] = 'Please choose what you are interested in.';
    }

    $d['preferred_time'] = form_text($in['preferred_time'] ?? '');
    if ($d['preferred_time'] === '') {
        $d['preferred_time'] = null;
    } elseif (!in_array($d['preferred_time'], FORM_TIMES, true)) {
        $errors['preferred_time'] = 'Please choose a time from the list.';
    }
    return [$d, $errors];
}

function validate_trial(array $in): array
{
    $errors = [];
    $d = validate_common($in, $errors);

    $dateRaw = form_text($in['preferred_date'] ?? '');
    $d['preferred_date'] = null;
    if ($dateRaw === '') {
        $errors['preferred_date'] = 'Please choose a preferred date.';
    } else {
        $dt = DateTime::createFromFormat('!Y-m-d', $dateRaw);
        $ok = $dt && $dt->format('Y-m-d') === $dateRaw;
        $today = new DateTime('today');
        if (!$ok) {
            $errors['preferred_date'] = 'Please enter a valid date.';
        } elseif ($dt < $today) {
            $errors['preferred_date'] = 'The date cannot be in the past.';
        } elseif ($dt > (clone $today)->modify('+90 days')) {
            $errors['preferred_date'] = 'Please choose a date within the next 90 days.';
        } else {
            $d['preferred_date'] = $dateRaw;
        }
    }

    $d['preferred_time'] = form_text($in['preferred_time'] ?? '');
    if (!in_array($d['preferred_time'], FORM_TIMES, true)) {
        $errors['preferred_time'] = 'Please choose a preferred time.';
    }

    $d['fitness_goal'] = form_text($in['fitness_goal'] ?? '');
    if ($d['fitness_goal'] === '') {
        $d['fitness_goal'] = null;
    } elseif (!in_array($d['fitness_goal'], FORM_GOALS, true)) {
        $errors['fitness_goal'] = 'Please choose a goal from the list.';
    }
    return [$d, $errors];
}

/** Has this IP already sent too many forms this hour? */
function form_rate_limited(PDO $pdo, string $table): bool
{
    $since = date('Y-m-d H:i:s', time() - 3600);
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM {$table} WHERE ip_address = ? AND created_at >= ?");
    $stmt->execute([client_ip(), $since]);
    return (int)$stmt->fetchColumn() >= (int)site('rate_limit_per_hour');
}

/** Same phone number already submitted in the last 10 minutes? (double-click / refresh) */
function form_is_duplicate(PDO $pdo, string $table, string $phone): bool
{
    $since = date('Y-m-d H:i:s', time() - 600);
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM {$table} WHERE phone = ? AND created_at >= ?");
    $stmt->execute([$phone, $since]);
    return (int)$stmt->fetchColumn() > 0;
}

/**
 * Process the form on this page. Call once, before any HTML output.
 *   $type = 'enquiry' or 'trial'
 * Returns ['errors' => [...], 'old' => [...], 'message' => string, 'sent' => bool]
 */
function handle_form(string $type): array
{
    start_secure_session();
    $table = $type === 'trial' ? 'trial_requests' : 'enquiries';
    $page  = $type === 'trial' ? 'trial.php' : 'contact.php';
    $result = ['errors' => [], 'old' => [], 'message' => '', 'sent' => false];

    // Show the success message once after the redirect (prevents re-sending on refresh)
    if (!empty($_SESSION['sent_' . $type])) {
        unset($_SESSION['sent_' . $type]);
        $result['sent'] = true;
        return $result;
    }

    if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
        return $result;
    }

    $result['old'] = array_map(fn($v) => is_string($v) ? $v : '', $_POST);

    if (!csrf_check()) {
        $result['message'] = 'Your session expired. Please reload the page and try again.';
        return $result;
    }

    // Honeypot: real people never see or fill this field. Bots do. Pretend success.
    if (form_text($_POST['website'] ?? '') !== '') {
        $_SESSION['sent_' . $type] = true;
        redirect(url($page) . '#form-status');
    }

    [$data, $errors] = $type === 'trial' ? validate_trial($_POST) : validate_enquiry($_POST);
    if ($errors) {
        $result['errors'] = $errors;
        $result['message'] = 'Please fix the highlighted fields and try again.';
        return $result;
    }

    $pdo = db();
    if (!$pdo) {
        $result['message'] = 'Sorry, we could not save your request right now. Please call or WhatsApp us instead.';
        return $result;
    }

    try {
        if (form_rate_limited($pdo, $table)) {
            $result['message'] = 'You have sent several requests recently. Please call or WhatsApp us, or try again later.';
            return $result;
        }

        if (!form_is_duplicate($pdo, $table, $data['phone'])) {
            $now = date('Y-m-d H:i:s');
            if ($type === 'trial') {
                $sql = 'INSERT INTO trial_requests (name, phone, email, preferred_date, preferred_time, fitness_goal, message, ip_address, created_at) VALUES (?,?,?,?,?,?,?,?,?)';
                $args = [$data['name'], $data['phone'], $data['email'], $data['preferred_date'], $data['preferred_time'], $data['fitness_goal'], $data['message'], client_ip(), $now];
            } else {
                $sql = 'INSERT INTO enquiries (name, phone, email, interested_in, preferred_time, message, ip_address, created_at) VALUES (?,?,?,?,?,?,?,?)';
                $args = [$data['name'], $data['phone'], $data['email'], $data['interested_in'], $data['preferred_time'], $data['message'], client_ip(), $now];
            }
            $pdo->prepare($sql)->execute($args);
        }
    } catch (PDOException $e) {
        error_log('Fitness Phyre form save failed: ' . $e->getMessage());
        $result['message'] = 'Sorry, something went wrong on our side. Please call or WhatsApp us instead.';
        return $result;
    }

    $_SESSION['sent_' . $type] = true;
    redirect(url($page) . '#form-status');
}

/** Print the error line for a field (empty <p> is hidden by CSS). */
function field_error(array $errors, string $name): string
{
    return '<p class="field-error" id="' . e($name) . '-error">' . e($errors[$name] ?? '') . '</p>';
}

/** aria attributes for a field that has a server error. */
function field_aria(array $errors, string $name): string
{
    return ' aria-describedby="' . e($name) . '-error"' . (isset($errors[$name]) ? ' aria-invalid="true" class="input-invalid"' : '');
}

/** Value to refill a field with after a failed submit. */
function old(array $old, string $name): string
{
    return e($old[$name] ?? '');
}
