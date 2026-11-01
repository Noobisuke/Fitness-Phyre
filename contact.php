<?php
require_once __DIR__ . '/includes/bootstrap.php';
$form = handle_form('enquiry');   // handles the POST (and redirects on success)
$old = $form['old']; $errors = $form['errors'];
$extra_scripts = '<script src="' . e(asset('js/forms.js')) . '" defer></script>';
$page_slug  = 'contact';
$page_title = 'Contact Fitness Phyre, Ludhiana';
$page_desc  = 'Contact Fitness Phyre in Karnail Singh Nagar Phase-II, Ludhiana. Call, WhatsApp, get directions or send an enquiry.';
include __DIR__ . '/includes/header.php';
?>
<section class="page-banner">
    <div class="container">
        <h1>Contact Us</h1>
        <p>Call, message on WhatsApp or visit us in Karnail Singh Nagar, Ludhiana.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <h2 class="sr-only">Ways to reach us</h2>
        <div class="contact-grid">
            <article class="card contact-card">
                <h3>Call</h3>
                <a class="big" href="<?= e(tel_link()) ?>"><?= e(site('phone_display')) ?></a>
                <a class="btn btn-accent" href="<?= e(tel_link()) ?>">Call Now</a>
            </article>
            <article class="card contact-card">
                <h3>WhatsApp</h3>
                <p>Pick a topic and we will open a pre-filled message.</p>
                <div class="quick-links">
                    <a class="btn btn-wa btn-sm" href="<?= e(wa_link('Hi Fitness Phyre, I would like to know more about membership plans.')) ?>" target="_blank" rel="noopener">Membership</a>
                    <a class="btn btn-wa btn-sm" href="<?= e(wa_link('Hi Fitness Phyre, I would like to book a trial session.')) ?>" target="_blank" rel="noopener">Trial session</a>
                    <a class="btn btn-wa btn-sm" href="<?= e(wa_link('Hi Fitness Phyre, I would like to know about personal training.')) ?>" target="_blank" rel="noopener">Personal training</a>
                    <a class="btn btn-wa btn-sm" href="<?= e(wa_link('Hi Fitness Phyre, I have a general enquiry.')) ?>" target="_blank" rel="noopener">General enquiry</a>
                </div>
            </article>
            <article class="card contact-card">
                <h3>Visit</h3>
                <address><?= e(site('address_line')) ?>,<br><?= e(site('city')) ?>, <?= e(site('state')) ?></address>
                <a class="btn btn-outline" href="<?= e(directions_link()) ?>" target="_blank" rel="noopener">Get Directions</a>
            </article>
            <?php if (site('email')): ?>
            <article class="card contact-card">
                <h3>Email</h3>
                <a class="big" href="mailto:<?= e(site('email')) ?>"><?= e(site('email')) ?></a>
            </article>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="section section-alt" aria-labelledby="enq-h">
    <div class="container">
        <div class="section-head">
            <span class="eyebrow">Send an enquiry</span>
            <h2 id="enq-h">Ask us anything</h2>
            <p class="lead">Leave your details and our team will get back to you.</p>
        </div>
        <div id="form-status" tabindex="-1" class="form-wrap">
            <?php if ($form['sent']): ?>
                <div class="alert alert-success" role="status">Thanks! Your enquiry has been received. Our team will get back to you soon.</div>
            <?php else: ?>
                <?php if ($form['message']): ?>
                    <div class="alert alert-error" role="alert"><?= e($form['message']) ?></div>
                <?php endif; ?>

                <form method="post" action="<?= e(url('contact.php')) ?>#form-status" id="enquiryForm" data-validate="enquiry" novalidate>
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
                        <label for="interested_in">Interested in <span class="req" aria-hidden="true">*</span></label>
                        <select id="interested_in" name="interested_in" required<?= field_aria($errors, 'interested_in') ?>>
                            <option value="">Choose one</option>
                            <?php foreach (FORM_INTERESTS as $i): ?>
                                <option value="<?= e($i) ?>"<?= ($old['interested_in'] ?? '') === $i ? ' selected' : '' ?>><?= e($i) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?= field_error($errors, 'interested_in') ?>
                    </div>
                    <div class="form-row">
                        <label for="preferred_time">Preferred time to contact you (optional)</label>
                        <select id="preferred_time" name="preferred_time"<?= field_aria($errors, 'preferred_time') ?>>
                            <option value="">Choose a time</option>
                            <?php foreach (FORM_TIMES as $t): ?>
                                <option value="<?= e($t) ?>"<?= ($old['preferred_time'] ?? '') === $t ? ' selected' : '' ?>><?= e($t) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?= field_error($errors, 'preferred_time') ?>
                    </div>
                    <div class="form-row">
                        <label for="message">Message (optional)</label>
                        <textarea id="message" name="message" maxlength="1000"<?= field_aria($errors, 'message') ?>><?= old($old, 'message') ?></textarea>
                        <?= field_error($errors, 'message') ?>
                    </div>
                    <button type="submit" class="btn btn-accent">Send Enquiry</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php include __DIR__ . '/includes/location.php'; ?>
<?php include __DIR__ . '/includes/footer.php'; ?>
