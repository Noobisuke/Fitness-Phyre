/* Client-side form validation (the server checks everything again). */
(function () {
    'use strict';

    var form = document.querySelector('form[data-validate]');
    if (!form) { return; }
    var type = form.getAttribute('data-validate');

    function phoneOk(v) {
        var d = v.replace(/[\s\-.()]/g, '').replace(/^\+/, '');
        if (!/^\d+$/.test(d)) { return false; }
        if (d.length === 12 && d.indexOf('91') === 0) { d = d.slice(2); }
        if (d.length === 11 && d.charAt(0) === '0') { d = d.slice(1); }
        return /^[6-9]\d{9}$/.test(d);
    }
    function emailOk(v) { return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v); }

    var rules = {
        name: function (v) {
            if (!v) { return 'Please enter your name.'; }
            if (v.length < 2 || v.length > 100) { return 'Name must be between 2 and 100 characters.'; }
        },
        phone: function (v) {
            if (!v) { return 'Please enter your phone number.'; }
            if (!phoneOk(v)) { return 'Enter a valid 10-digit Indian mobile number.'; }
        },
        email: function (v) { if (v && !emailOk(v)) { return 'Enter a valid email address or leave this blank.'; } },
        message: function (v) { if (v.length > 1000) { return 'Message is too long (maximum 1000 characters).'; } }
    };
    if (type === 'enquiry') {
        rules.interested_in = function (v) { if (!v) { return 'Please choose what you are interested in.'; } };
    } else {
        rules.preferred_date = function (v, el) {
            if (!v) { return 'Please choose a preferred date.'; }
            if (el.min && v < el.min) { return 'The date cannot be in the past.'; }
            if (el.max && v > el.max) { return 'Please choose a date within the next 90 days.'; }
        };
        rules.preferred_time = function (v) { if (!v) { return 'Please choose a preferred time.'; } };
    }

    function check(name) {
        var el = form.elements[name];
        if (!el) { return true; }
        var msg = rules[name](el.value.trim(), el);
        var out = document.getElementById(name + '-error');
        if (msg) {
            el.setAttribute('aria-invalid', 'true');
            el.classList.add('input-invalid');
            if (out) { out.textContent = msg; }
            return false;
        }
        el.removeAttribute('aria-invalid');
        el.classList.remove('input-invalid');
        if (out) { out.textContent = ''; }
        return true;
    }

    // New errors appear only when the form is submitted. (Showing an error on blur
    // moves the layout, so the Submit button can slip away from under a tap.)
    // Once a field is marked invalid, it re-checks live so the message clears as soon as it is fixed.
    Object.keys(rules).forEach(function (name) {
        var el = form.elements[name];
        if (!el) { return; }
        function recheck() { if (el.getAttribute('aria-invalid') === 'true') { check(name); } }
        el.addEventListener('input', recheck);
        el.addEventListener('change', recheck);
    });

    form.addEventListener('submit', function (ev) {
        var firstBad = null;
        Object.keys(rules).forEach(function (name) {
            if (!check(name) && !firstBad) { firstBad = form.elements[name]; }
        });
        if (firstBad) {
            ev.preventDefault();
            firstBad.focus();
            return;
        }
        // Stop double submissions
        var btn = form.querySelector('button[type="submit"]');
        if (btn) { btn.disabled = true; btn.textContent = 'Sending...'; }
    });
})();
