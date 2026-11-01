/* Admin: ask before deleting */
document.querySelectorAll('form[data-confirm]').forEach(function (f) {
    f.addEventListener('submit', function (ev) {
        if (!window.confirm(f.getAttribute('data-confirm'))) { ev.preventDefault(); }
    });
});
