<?php
$page_slug  = 'home';
$page_title = 'Fitness Phyre - Gym & Fitness Centre in Ludhiana';
$page_desc  = 'Fitness Phyre is a gym and fitness centre in Karnail Singh Nagar Phase-II, Ludhiana, near Pakhowal Road. Call, WhatsApp or request a trial.';
include __DIR__ . '/includes/header.php';
?>

<!-- HERO -->
<section class="hero" aria-labelledby="hero-h">
    <div class="container hero-grid">
        <div>
            <span class="eyebrow">Gym &amp; Fitness Centre, Ludhiana</span>
            <h1 id="hero-h">Fitness Phyre <span>Train. Stay consistent.</span></h1>
            <p class="lead">Train with the right equipment, guidance and environment to stay consistent with your fitness goals. Find us in Karnail Singh Nagar, near Pakhowal Road.</p>
            <div class="btn-row">
                <a class="btn btn-accent" href="<?= e(url('contact.php')) ?>">Join Now</a>
                <a class="btn btn-outline" href="<?= e(url('trial.php')) ?>">Book a Trial</a>
                <a class="btn btn-outline" href="<?= e(tel_link()) ?>">Call Us</a>
            </div>
        </div>
        <div class="hero-panel" role="group" aria-label="Quick details">
            <h2>Visit us</h2>
            <address>
                <?= e(site('address_line')) ?>,<br><?= e(site('city')) ?>, <?= e(site('state')) ?>
            </address>
            <p><a href="<?= e(tel_link()) ?>"><?= e(site('phone_display')) ?></a></p>
            <p class="rating-line">
                <span class="rating-big"><?= e(site('rating')) ?>/5</span> on Google<br>
                <a href="<?= e(site('maps_url')) ?>" target="_blank" rel="noopener">View on Google</a>
            </p>
        </div>
    </div>
</section>

<!-- ABOUT PREVIEW -->
<section class="section" aria-labelledby="about-h">
    <div class="container split">
        <div>
            <span class="eyebrow">About us</span>
            <h2 id="about-h">A local gym for regular training</h2>
            <p>Fitness Phyre is a gym and fitness centre in Karnail Singh Nagar Phase-II, Ludhiana. Whether you are starting out or already training regularly, it is a place to work on your fitness routine close to home.</p>
            <p>Get in touch to find out what is on offer and how to get started.</p>
            <a class="btn btn-dark" href="<?= e(url('about.php')) ?>">Learn More</a>
        </div>
        <?= photo_or_placeholder('about/gym-floor.jpg', 'Inside the Fitness Phyre gym, Ludhiana', 'Gym floor photo', 1200, 900) ?>
    </div>
</section>

<!-- PROGRAMS -->
<section class="section section-alt" aria-labelledby="prog-h">
    <div class="container">
        <div class="section-head">
            <span class="eyebrow">Training</span>
            <h2 id="prog-h">Ways to train</h2>
            <p class="lead">Strength, cardio and group fitness. Ask the team which programs are running right now.</p>
        </div>
        <?php include __DIR__ . '/includes/programs_grid.php'; ?>
        <p style="margin-top:2rem"><a class="btn btn-outline" href="<?= e(url('programs.php')) ?>">See all programs</a></p>
    </div>
</section>

<!-- WHY -->
<section class="section section-dark" aria-labelledby="why-h">
    <div class="container">
        <div class="section-head">
            <span class="eyebrow">Why Fitness Phyre</span>
            <h2 id="why-h">What you can expect</h2>
        </div>
        <ul class="feature-list">
            <li><h3>A proper training environment</h3><p>Space and equipment to train with focus.</p></li>
            <li><h3>Variety of workouts</h3><p>From strength and cardio to group sessions.</p></li>
            <li><h3>Guidance when you need it</h3><p>Ask the team about getting started or adjusting your routine.</p></li>
            <li><h3>Easy to reach</h3><p>Close to Pakhowal Road in Karnail Singh Nagar.</p></li>
            <li><h3>A fitness community</h3><p>Train alongside others working toward their own goals.</p></li>
            <li><h3>Flexible options</h3><p>Talk to us about training options that suit your routine.</p></li>
        </ul>
    </div>
</section>

<!-- GALLERY PREVIEW -->
<section class="section" aria-labelledby="gal-h">
    <div class="container">
        <div class="section-head">
            <span class="eyebrow">Gallery</span>
            <h2 id="gal-h">Inside the gym</h2>
        </div>
        <?php $gallery_limit = 6; include __DIR__ . '/includes/gallery_grid.php'; ?>
        <p style="margin-top:2rem"><a class="btn btn-outline" href="<?= e(url('gallery.php')) ?>">View gallery</a></p>
    </div>
</section>

<!-- REVIEWS PREVIEW -->
<section class="section section-alt" aria-labelledby="rev-h">
    <div class="container">
        <div class="card rating-card">
            <span class="eyebrow">Reviews</span>
            <h2 id="rev-h" class="sr-only">Google rating</h2>
            <p class="rating-num"><?= e(site('rating')) ?><small>/5</small></p>
            <p class="stars" aria-hidden="true">&#9733;&#9733;&#9733;&#9733;&#9733;</p>
            <p>Publicly listed rating on Google. Read what members say in their own words.</p>
            <a class="btn btn-dark" href="<?= e(site('maps_url')) ?>" target="_blank" rel="noopener">View on Google</a>
        </div>
    </div>
</section>

<!-- LOCATION + HOURS -->
<?php include __DIR__ . '/includes/location.php'; ?>

<!-- FINAL CTA -->
<?php include __DIR__ . '/includes/cta.php'; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>
