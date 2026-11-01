/* Gallery page: category filter + simple lightbox (no libraries) */
(function () {
    'use strict';

    // ---- Filter by category ----
    var buttons = document.querySelectorAll('.filter-btn');
    var items = document.querySelectorAll('#galleryGrid li');
    buttons.forEach(function (btn) {
        btn.addEventListener('click', function () {
            var cat = btn.getAttribute('data-filter');
            buttons.forEach(function (b) { b.setAttribute('aria-pressed', b === btn ? 'true' : 'false'); });
            items.forEach(function (li) {
                li.hidden = !(cat === 'all' || li.getAttribute('data-category') === cat);
            });
        });
    });

    // ---- Lightbox ----
    var links = Array.prototype.slice.call(document.querySelectorAll('[data-lightbox]'));
    if (!links.length) { return; }

    var box = document.createElement('div');
    box.className = 'lightbox';
    box.setAttribute('role', 'dialog');
    box.setAttribute('aria-modal', 'true');
    box.setAttribute('aria-label', 'Photo viewer');
    box.innerHTML = '<button class="lb-close" aria-label="Close">&times;</button>' +
        '<button class="lb-prev" aria-label="Previous photo">&lsaquo;</button>' +
        '<img alt=""><button class="lb-next" aria-label="Next photo">&rsaquo;</button>';
    document.body.appendChild(box);

    var img = box.querySelector('img');
    var current = 0;
    var opener = null;

    function visible() {
        return links.filter(function (a) { return !a.closest('li').hidden; });
    }
    function show(list, i) {
        current = (i + list.length) % list.length;
        var a = list[current];
        img.src = a.getAttribute('href');
        img.alt = a.querySelector('img').alt;
    }
    function open(a) {
        var list = visible();
        opener = a;
        show(list, list.indexOf(a));
        box.classList.add('open');
        box.querySelector('.lb-close').focus();
    }
    function close() {
        box.classList.remove('open');
        if (opener) { opener.focus(); }
    }

    links.forEach(function (a) {
        a.addEventListener('click', function (ev) { ev.preventDefault(); open(a); });
    });
    box.querySelector('.lb-close').addEventListener('click', close);
    box.querySelector('.lb-prev').addEventListener('click', function () { show(visible(), current - 1); });
    box.querySelector('.lb-next').addEventListener('click', function () { show(visible(), current + 1); });
    box.addEventListener('click', function (ev) { if (ev.target === box) { close(); } });
    document.addEventListener('keydown', function (ev) {
        if (!box.classList.contains('open')) { return; }
        if (ev.key === 'Escape') { close(); }
        if (ev.key === 'ArrowLeft') { show(visible(), current - 1); }
        if (ev.key === 'ArrowRight') { show(visible(), current + 1); }
    });
})();
