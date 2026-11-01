<?php
$page_slug  = 'membership';
$page_title = 'Membership';
$page_desc  = 'Membership at Fitness Phyre, Ludhiana. Contact us for current membership plans and pricing.';
include __DIR__ . '/includes/header.php';
$plans = membership_plans();
?>
<section class="page-banner">
    <div class="container">
        <h1>Membership</h1>
        <p>Join Fitness Phyre and make training part of your routine.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-head">
            <span class="eyebrow">What membership gives you</span>
            <h2>Benefits</h2>
        </div>
        <div class="grid grid-3">
            <article class="card"><h3>Training access</h3><p>Regular access to the gym for your workouts.</p></article>
            <article class="card"><h3>Facility access</h3><p>Use of the gym floor and facilities. Ask us for the current list.</p></article>
            <article class="card"><h3>Guidance</h3><p>Ask the team for help getting started or planning your routine.</p></article>
            <article class="card"><h3>Variety</h3><p>Strength, cardio and group options to keep training interesting.</p></article>
        </div>
    </div>
</section>

<section class="section section-alt">
    <div class="container">
        <div class="section-head">
            <span class="eyebrow">Plans &amp; pricing</span>
            <h2>Membership options</h2>
        </div>
        <?php if ($plans): ?>
            <div class="grid grid-3">
            <?php foreach ($plans as $plan): ?>
                <article class="card plan-card">
                    <h3><?= e($plan['name']) ?></h3>
                    <p class="price"><?= e($plan['price']) ?></p>
                    <p class="period"><?= e($plan['period'] ?? '') ?></p>
                    <ul class="check-list">
                        <?php foreach (($plan['includes'] ?? []) as $item): ?><li><?= e($item) ?></li><?php endforeach; ?>
                    </ul>
                </article>
            <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="card pricing-pending">
                <h3>Contact us for current membership plans and pricing.</h3>
                <p>Plans and prices can change, so we share the latest directly. Call or message us and the team will help.</p>
                <div class="btn-row" style="justify-content:center">
                    <a class="btn btn-accent" href="<?= e(wa_link('Hi Fitness Phyre, I would like to know the current membership plans and pricing.')) ?>" target="_blank" rel="noopener">Get Membership Details</a>
                    <a class="btn btn-outline" href="<?= e(tel_link()) ?>">Call Now</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<section class="section">
    <div class="container prose faq">
        <h2>Frequently asked questions</h2>
        <details>
            <summary>How do I find out the membership fees?</summary>
            <p>Call us, message on WhatsApp or send an enquiry from the Contact page. We will share the current plans and pricing.</p>
        </details>
        <details>
            <summary>Can I try the gym before joining?</summary>
            <p>You can request a trial session through the Book a Trial page. Our team will contact you to confirm the time.</p>
        </details>
        <details>
            <summary>I am a beginner. Can I join?</summary>
            <p>Yes, you can ask about getting started. Tell the team about your goals and experience when you get in touch.</p>
        </details>
        <details>
            <summary>Which programs are running right now?</summary>
            <p>Programs and class timings can change, so please call or WhatsApp us to confirm what is currently available.</p>
        </details>
        <details>
            <summary>What are the gym timings?</summary>
            <p>Please contact us to confirm the current timings before you visit.</p>
        </details>
    </div>
</section>

<?php include __DIR__ . '/includes/cta.php'; ?>
<?php include __DIR__ . '/includes/footer.php'; ?>
