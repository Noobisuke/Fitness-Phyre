/* Interactive map: Leaflet + OpenStreetMap tiles (free, no API key). */
(function () {
    'use strict';

    var el = document.getElementById('map');
    if (!el || typeof L === 'undefined') { return; } // no Leaflet = keep the fallback link

    var lat = parseFloat(el.getAttribute('data-lat'));
    var lng = parseFloat(el.getAttribute('data-lng'));
    if (isNaN(lat) || isNaN(lng)) { return; }

    // Remove the fallback content; Leaflet fills the box instead.
    el.innerHTML = '';
    el.classList.add('map-live');

    // Phones: one finger should scroll the page, so dragging is switched off there.
    var map = L.map(el, {
        center: [lat, lng],
        zoom: 16,
        scrollWheelZoom: false,
        dragging: !L.Browser.mobile,
        tap: false,
        zoomControl: false
    });
    // Zoom buttons bottom-left: clear of the popup (above the marker) and the attribution (bottom-right).
    L.control.zoom({ position: 'bottomleft' }).addTo(map);

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Marker image paths: Leaflet cannot guess them when self-hosted.
    // (strip the ?v=... cache-busting part first, or the image paths break)
    var base = document.querySelector('script[src*="leaflet.js"]').getAttribute('src').split('?')[0].replace('leaflet.js', 'images/');
    var icon = L.icon({
        iconUrl: base + 'marker-icon.png',
        iconRetinaUrl: base + 'marker-icon-2x.png',
        shadowUrl: base + 'marker-shadow.png',
        iconSize: [25, 41], iconAnchor: [12, 41], popupAnchor: [1, -34], shadowSize: [41, 41]
    });

    // Build popup with DOM methods (not innerHTML) so values are never treated as HTML.
    var pop = document.createElement('div');
    var title = document.createElement('strong');
    title.textContent = el.getAttribute('data-name') || 'Fitness Phyre';
    var addr = document.createElement('div');
    addr.textContent = el.getAttribute('data-address') || '';
    var link = document.createElement('a');
    link.href = el.getAttribute('data-maps-url') || '#';
    link.target = '_blank';
    link.rel = 'noopener';
    link.textContent = 'View on Google Maps';
    pop.appendChild(title);
    pop.appendChild(addr);
    pop.appendChild(link);

    var marker = L.marker([lat, lng], { icon: icon, title: title.textContent, alt: title.textContent })
        .addTo(map)
        .bindPopup(pop, { maxWidth: 190, autoPanPadding: L.point(24, 24) });

    // On a narrow phone map the popup would crowd the zoom buttons, so it opens only when tapped there.
    if (el.clientWidth >= 480) { marker.openPopup(); }
})();
