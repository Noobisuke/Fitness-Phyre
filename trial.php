<?php
require_once __DIR__ . '/includes/bootstrap.php';
$form = handle_form('trial');   // handles the POST (and redirects on success)
$old = $form['old']; $errors = $form['errors'];

$page_slug  = 'trial';
$page_title = 'Book a Trial';
$page_desc  = 'Request a trial session at Fitness Phyre, Ludhiana. Our team will contact you to confirm the time.';
$extra_scripts = '<script src="' . e(asset('js/forms.js')) . '" defer></script>';
include __DIR__ . '/includes/header.php';
?>
<section class="page-banner">
    <div class="container">
        <h1>Book a Trial</h1>
        <p>Tell us when you would like to visit. This is a request, and our team will contact you to confirm the time.</p>
    </div>
</section>

<section class="section">
    <div class="container split form-split">
        <div id="form-status" tabindex="-1">
            <?php if ($form['sent']): ?>
                <div class="alert alert-success" role="status">Thanks! Your trial request has been received. Our team will contact you to confirm the time.</div>
                <p><a class="btn btn-outline" href="<?= e(url('index.php')) ?>">Back to home</a></p>
            <?php else: ?>
                <?php if ($form['message']): ?>
                    <div class="alert alert-error" role="alert"><?= e($form['message']) ?></div>
                <?php endif; ?>

                <form method="post" action="<?= e(url('trial.php')) ?>#form-status" id="trialForm" data-validate="trial" novalidate>
                    <?= csrf_field() ?>
                    <div class="hp" aria-hidden="true">
                        <label for="website">Leave this field empty</label>
                        <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
                    </div>

                    <div class="form-row">
                        <label for="name">Name <span class="req" aria-hidden="true">*</span></label>
                        <input type="text" id="name" name="name" maxlength="100" autocomplete="name" required value="<?= old($old, 'name') ?>"<?= field_aria($errors, 'name') ?>>
                        <?= field_error($errors, 'name') ?>
                    </div>
                    <div class="form-row">
                        <label for="phone">Phone <span class="req" aria-hidden="true">*</span></label>
                        <input type="tel" id="phone" name="phone" maxlength="20" inputmode="tel" autocomplete="tel" required placeholder="10-digit mobile number" value="<?= old($old, 'phone') ?>"<?= field_aria($errors, 'phone') ?>>
                        <?= field_error($errors, 'phone') ?>
                    </div>
                    <div class="form-row">
                        <label for="email">Email (optional)</label>
                        <input type="email" id="email" name="email" maxlength="150" autocomplete="email" value="<?= old($old, 'email') ?>"<?= field_aria($errors, 'email') ?>>
                        <?= field_error($errors, 'email') ?>
                    </div>
                    <div class="form-row">
                        <label for="preferred_date">Preferred date <span class="req" aria-hidden="true">*</span></label>
                        <input type="date" id="preferred_date" name="preferred_date" required min="<?= e(date('Y-m-d')) ?>" max="<?= e(date('Y-m-d', strtotime('+90 days'))) ?>" value="<?= old($old, 'preferred_date') ?>"<?= field_aria($errors, 'preferred_date') ?>>
                        <?= field_error($errors, 'preferred_date') ?>
                    </div>
                    <div class="form-row">
                        <label for="preferred_time">Preferred time <span class="req" aria-hidden="true">*</span></label>
                        <select id="preferred_time" name="preferred_time" required<?= field_aria($errors, 'preferred_time') ?>>
                            <option value="">Choose a time</option>
                            <?php foreach (FORM_TIMES as $t): ?>
                                <option value="<?= e($t) ?>"<?= ($old['preferred_time'] ?? '') === $t ? ' selected' : '' ?>><?= e($t) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?= field_error($errors, 'preferred_time') ?>
                    </div>
                    <div class="form-row">
                        <label for="fitness_goal">Fitness goal (optional)</label>
                        <select id="fitness_goal" name="fitness_goal"<?= field_aria($errors, 'fitness_goal') ?>>
                            <option value="">Choose a goal</option>
                            <?php foreach (FORM_GOALS as $g): ?>
                                <option value="<?= e($g) ?>"<?= ($old['fitness_goal'] ?? '') === $g ? ' selected' : '' ?>><?= e($g) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?= field_error($errors, 'fitness_goal') ?>
                    </div>
                    <div class="form-row">
                        <label for="message">Message (optional)</label>
                        <textarea id="message" name="message" maxlength="1000"<?= field_aria($errors, 'message') ?>><?= old($old, 'message') ?></textarea>
                        <?= field_error($errors, 'message') ?>
                    </div>
                    <button type="submit" class="btn btn-accent">Request a Trial</button>
                </form>
            <?php endif; ?>
        </div>

        <div class="card">
            <h2 style="font-size:1.4rem">Prefer to talk?</h2>
            <p>Call or message us and we will help you directly.</p>
            <div class="btn-row">
                <a class="btn btn-outline" href="<?= e(tel_link()) ?>">Call Now</a>
                <a class="btn btn-wa" href="<?= e(wa_link('Hi Fitness Phyre, I would like to book a trial session.')) ?>" target="_blank" rel="noopener">WhatsApp</a>
            </div>
            <p style="margin-top:1rem;font-size:.9rem;color:var(--muted)">Your trial is not confirmed until our team contacts you.</p>
        </div>
    </div>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
