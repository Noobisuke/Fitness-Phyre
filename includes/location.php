<?php
/* "Find Us" two-column section: details left, map right (stacks on mobile).
   Setting $needs_map makes footer.php load Leaflet + map.js (self-hosted, no API key). */
$needs_map = true;
?>
<link rel="stylesheet" href="<?= e(asset('vendor/leaflet/leaflet.css')) ?>">
<section class="section section-alt" id="find-us" aria-labelledby="find-us-h">
    <div class="container find-grid">
        <div>
            <span class="eyebrow">Location</span>
            <h2 id="find-us-h">Find Us</h2>
            <address class="find-address">
                <strong><?= e(site('name')) ?></strong><br>
                <?= e(site('address_line')) ?>,<br>
                <?= e(site('city')) ?>, <?= e(site('state')) ?>, <?= e(site('country')) ?>
            </address>
            <p>Phone: <a href="<?= e(tel_link()) ?>"><?= e(site('phone_display')) ?></a></p>
            <?php include __DIR__ . '/hours.php'; ?>
            <div class="btn-row">
                <a class="btn btn-accent" href="<?= e(tel_link()) ?>">Call Now</a>
                <a class="btn btn-wa" href="<?= e(wa_link()) ?>" target="_blank" rel="noopener">WhatsApp</a>
                <a class="btn btn-outline" href="<?= e(directions_link()) ?>" target="_blank" rel="noopener">Get Directions</a>
            </div>
        </div>
        <div>
            <!-- map.js turns this box into an interactive OpenStreetMap. The content inside is the no-JavaScript fallback. -->
            <div id="map" class="map-box" role="region" aria-label="Map showing the location of <?= e(site('name')) ?>"
                 data-lat="<?= e(site('lat')) ?>" data-lng="<?= e(site('lng')) ?>" data-name="<?= e(site('name')) ?>"
                 data-address="<?= e(full_address()) ?>" data-maps-url="<?= e(site('maps_url')) ?>">
                <div class="map-fallback">
                    <p><strong><?= e(site('name')) ?></strong><br><?= e(site('address_line')) ?>, <?= e(site('city')) ?></p>
                    <a class="btn btn-dark" href="<?= e(site('maps_url')) ?>" target="_blank" rel="noopener">View on Google Maps</a>
                </div>
            </div>
        </div>
    </div>
</section>
