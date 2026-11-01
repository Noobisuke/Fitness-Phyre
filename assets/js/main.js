/* Fitness Phyre - global JavaScript (no libraries needed) */
(function () {
    'use strict';

    // Mobile menu toggle
    var toggle = document.getElementById('navToggle');
    var nav = document.getElementById('siteNav');
    if (toggle && nav) {
        toggle.addEventListener('click', function () {
            var open = nav.classList.toggle('open');
            toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        });
        // Close menu with the Escape key
        document.addEventListener('keydown', function (ev) {
            if (ev.key === 'Escape' && nav.classList.contains('open')) {
                nav.classList.remove('open');
                toggle.setAttribute('aria-expanded', 'false');
                toggle.focus();
            }
        });
    }
})();
