<?php
$page_slug  = 'reviews';
$page_title = 'Reviews';
$page_desc  = 'Fitness Phyre, Ludhiana, has a publicly listed rating of 4.6 out of 5 on Google. Read the reviews on Google Maps.';
include __DIR__ . '/includes/header.php';
$reviews = site_reviews();
?>
<section class="page-banner">
    <div class="container">
        <h1>Reviews</h1>
        <p>What members say about Fitness Phyre.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="card rating-card">
            <p class="rating-num"><?= e(site('rating')) ?><small>/5</small></p>
            <p class="stars" aria-hidden="true">&#9733;&#9733;&#9733;&#9733;&#9733;</p>
            <p>Publicly listed rating on Google Maps. Reviews are written by real members and shown on Google, so you can read them there.</p>
            <a class="btn btn-dark" href="<?= e(site('maps_url')) ?>" target="_blank" rel="noopener">View on Google</a>
        </div>

        <?php if ($reviews): ?>
            <div class="grid grid-3" style="margin-top:3rem">
            <?php foreach ($reviews as $r): ?>
                <article class="card review-card">
                    <p class="stars" aria-label="<?= (int)$r['rating'] ?> out of 5 stars"><?= str_repeat('&#9733;', (int)$r['rating']) ?></p>
                    <blockquote><p><?= e($r['text']) ?></p></blockquote>
                    <p class="mb-0"><strong><?= e($r['name']) ?></strong> <span class="tag"><?= e($r['source'] ?? 'Google') ?></span></p>
                </article>
            <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include __DIR__ . '/includes/cta.php'; ?>
<?php include __DIR__ . '/includes/footer.php'; ?>
