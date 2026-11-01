</main>

<footer class="site-footer">
    <div class="container footer-grid">
        <div>
            <p class="footer-brand"><?= e(site('name')) ?></p>
            <p><?= e(site('tagline')) ?></p>
            <?php if (has_social()): ?>
                <ul class="social-list">
                    <?php foreach (site('social') as $net => $link): if (!$link) continue; ?>
                        <li><a href="<?= e($link) ?>" rel="noopener" target="_blank"><?= e(ucfirst($net)) ?></a></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>

        <div>
            <h2 class="footer-h">Explore</h2>
            <ul class="footer-links">
                <?php foreach ($nav as $slug => [$label, $file]): ?>
                    <li><a href="<?= e(url($file)) ?>"><?= e($label) ?></a></li>
                <?php endforeach; ?>
                <li><a href="<?= e(url('trial.php')) ?>">Book a Trial</a></li>
            </ul>
        </div>

        <div>
            <h2 class="footer-h">Find us</h2>
            <address>
                <?= e(site('address_line')) ?>,<br>
                <?= e(site('city')) ?>, <?= e(site('state')) ?>
            </address>
            <p><a href="<?= e(tel_link()) ?>"><?= e(site('phone_display')) ?></a></p>
            <?php if (site('email')): ?>
                <p><a href="mailto:<?= e(site('email')) ?>"><?= e(site('email')) ?></a></p>
            <?php endif; ?>
            <p><a href="<?= e(directions_link()) ?>" target="_blank" rel="noopener">Get directions</a></p>
        </div>
    </div>
    <div class="container footer-bottom">
        <p>&copy; <?= date('Y') ?> <?= e(site('name')) ?>, <?= e(site('city')) ?>. All rights reserved.</p>
    </div>
</footer>

<!-- Floating WhatsApp button -->
<aside aria-label="Chat on WhatsApp">
<a class="wa-float" href="<?= e(wa_link()) ?>" target="_blank" rel="noopener" aria-label="Chat with Fitness Phyre on WhatsApp">
    <svg viewBox="0 0 24 24" width="28" height="28" aria-hidden="true" focusable="false">
        <path fill="currentColor" d="M12 2a10 10 0 0 0-8.6 15.1L2 22l5-1.3A10 10 0 1 0 12 2zm0 18.2a8.2 8.2 0 0 1-4.2-1.2l-.3-.2-3 .8.8-2.9-.2-.3A8.2 8.2 0 1 1 12 20.200zm4.500-6.100c-.2-.1-1.500-.7-1.700-.8s-.4-.1-.6.100l-.8 1c-.1.200-.3.200-.5.100a6.700 6.700 0 0 1-3.300-2.900c-.2-.4.200-.4.700-1.300.1-.2 0-.3 0-.5l-.8-1.800c-.2-.5-.4-.4-.6-.4h-.5c-.2 0-.5.100-.7.300-.3.300-1 1-1 2.400s1 2.800 1.200 3c.1.200 2.100 3.200 5.100 4.500 1.900.8 2.600.7 3.100.6.500-.1 1.500-.6 1.700-1.200.2-.6.200-1.100.2-1.200-.1-.1-.3-.2-.6-.3z"/>
    </svg>
</a>
</aside>

<script src="<?= e(asset('js/main.js')) ?>" defer></script>
<?php if (!empty($needs_map)): ?>
<script src="<?= e(asset('vendor/leaflet/leaflet.js')) ?>" defer></script>
<script src="<?= e(asset('js/map.js')) ?>" defer></script>
<?php endif; ?>
<?= $extra_scripts ?? '' ?>
</body>
</html>
