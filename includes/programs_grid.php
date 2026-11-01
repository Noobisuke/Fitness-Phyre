<?php
/* Program cards. Set $detailed = true before including to show "suited for" + enquiry button. */
$detailed = $detailed ?? false;
?>
<div class="grid grid-3 program-grid">
<?php foreach (site('programs') as $p): ?>
    <article class="card program-card" id="<?= e($p['slug']) ?>">
        <h3><?= e($p['name']) ?></h3>
        <p><?= e($p['text']) ?></p>
        <?php if ($detailed): ?>
            <p class="suited"><strong>Suited for:</strong> <?= e($p['suited']) ?></p>
        <?php endif; ?>
        <?php if (empty($p['confirmed'])): ?>
            <p class="program-note">Ask us if this is running now.</p>
        <?php endif; ?>
        <?php if ($detailed): ?>
            <a class="btn btn-outline btn-sm" href="<?= e(wa_link('Hi Fitness Phyre, I would like to know about ' . $p['name'] . ' at your gym.')) ?>" target="_blank" rel="noopener">Ask about <?= e($p['name']) ?></a>
        <?php endif; ?>
    </article>
<?php endforeach; ?>
</div>
