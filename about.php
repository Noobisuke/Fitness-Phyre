<?php
$page_slug  = 'about';
$page_title = 'About Fitness Phyre, Gym in Ludhiana';
$page_desc  = 'About Fitness Phyre, a gym and fitness centre in Karnail Singh Nagar Phase-II, Ludhiana, near Pakhowal Road.';
include __DIR__ . '/includes/header.php';
?>
<section class="page-banner">
    <div class="container">
        <h1>About Fitness Phyre</h1>
        <p>A gym and fitness centre in Karnail Singh Nagar, Ludhiana.</p>
    </div>
</section>

<section class="section">
    <div class="container split">
        <div class="prose">
            <h2>Introduction</h2>
            <p>Fitness Phyre is a gym and fitness centre in Karnail Singh Nagar Phase-II, near Pakhowal Road, Ludhiana. It is a place for people in the neighbourhood and around the city to train regularly and build a routine they can stick to.</p>

            <h2>Our approach</h2>
            <p>Consistency matters more than anything else in fitness. The idea is simple: give members the equipment, guidance and environment they need to turn up and keep going, at a pace that suits them.</p>

            <h2>The training environment</h2>
            <p>The focus is on training. You can work on strength, cardio and general fitness, and group sessions are part of the mix. For the exact equipment, facilities and which sessions are running now, visit or ask the team on call or WhatsApp.</p>

            <h2>Community</h2>
            <p>A gym works best when members feel comfortable turning up. People of different ages and fitness levels train side by side, each working on their own goals.</p>
        </div>
        <?= photo_or_placeholder('about/about-main.jpg', 'Training area at Fitness Phyre, Ludhiana', 'Gym photo', 1200, 900) ?>
    </div>
</section>

<section class="section section-alt">
    <div class="container grid grid-2">
        <div>
            <h2>Who it is for</h2>
            <ul class="check-list">
                <li>First-time gym-goers who want a place to start</li>
                <li>Regular lifters looking for a place to train</li>
                <li>Anyone who wants to work on cardio, stamina or general fitness</li>
                <li>People who enjoy group workouts</li>
                <li>Members working on weight or fitness goals</li>
            </ul>
        </div>
        <div>
            <h2>Why people choose us</h2>
            <ul class="check-list">
                <li>Convenient location near Pakhowal Road</li>
                <li>A mix of workout styles under one roof</li>
                <li>Guidance available when you ask for it</li>
                <li>Easy to contact by phone or WhatsApp</li>
                <li>Try it first by requesting a trial</li>
            </ul>
        </div>
    </div>
</section>

<?php $trainers = site_trainers(); if ($trainers): ?>
<section class="section" aria-labelledby="trainers-h">
    <div class="container">
        <div class="section-head"><span class="eyebrow">Our team</span><h2 id="trainers-h">Trainers</h2></div>
        <div class="grid grid-3">
        <?php foreach ($trainers as $t): ?>
            <article class="card">
                <?php if (!empty($t['photo'])): ?>
                    <img src="<?= e(asset('images/trainers/' . basename($t['photo']))) ?>" alt="<?= e($t['name']) ?>" width="600" height="600" loading="lazy" style="margin-bottom:1rem;border-radius:4px">
                <?php endif; ?>
                <h3><?= e($t['name']) ?></h3>
                <?php if (!empty($t['role'])): ?><p class="tag"><?= e($t['role']) ?></p><?php endif; ?>
                <?php if (!empty($t['bio'])): ?><p><?= e($t['bio']) ?></p><?php endif; ?>
            </article>
        <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php include __DIR__ . '/includes/cta.php'; ?>
<?php include __DIR__ . '/includes/footer.php'; ?>
