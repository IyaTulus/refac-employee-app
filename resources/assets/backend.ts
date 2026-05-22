import '../../vendor/jeemce/laravel/assets/vendor.js'
import 'select2/dist/css/select2.min.css'
import 'select2-bootstrap-5-theme/dist/select2-bootstrap-5-theme.min.css'
import 'select2'
import '../../vendor/jeemce/laravel-theme-admin-v5/assets/vendor.ts'
import '../../vendor/jeemce/laravel-theme-admin-v5/assets/theme.ts'
import '../../vendor/jeemce/laravel/assets/main.js'

// Ensure Bootstrap dropdown instances are initialized reliably for all toggles.
if (globalThis.bootstrap?.Dropdown) {
    document.querySelectorAll('[data-bs-toggle="dropdown"]').forEach((toggle) => {
        globalThis.bootstrap.Dropdown.getOrCreateInstance(toggle);
    });
}

// Fallback: if for any reason Bootstrap's automatic dropdown handling isn't active
// (old bundle, conflicting imports, or event propagation blocked), use a delegated
// click handler to toggle dropdown menus. This keeps UI responsive while we
// ensure the build pipeline delivers the proper vendor JS.
document.addEventListener('click', (e) => {
    const toggle = /** @type {HTMLElement|null} */ (e.target instanceof Element ? e.target.closest('[data-bs-toggle="dropdown"]') : null);
    if (!toggle) return;

    // Prefer Bootstrap API when available
    try {
        if (globalThis.bootstrap?.Dropdown) {
            globalThis.bootstrap.Dropdown.getOrCreateInstance(toggle).toggle();
            return;
        }
    } catch (err) {
        // continue to manual fallback
        console.debug('bootstrap dropdown API unavailable, using fallback', err);
    }

    // Manual fallback: find the next sibling dropdown-menu and toggle `.show`
    const menu = toggle.nextElementSibling;
    if (menu && menu.classList.contains('dropdown-menu')) {
        // Close other open dropdowns
        document.querySelectorAll('.dropdown-menu.show').forEach((m) => {
            if (m !== menu) m.classList.remove('show');
        });

        menu.classList.toggle('show');
        const expanded = menu.classList.contains('show');
        toggle.setAttribute('aria-expanded', String(expanded));
        e.preventDefault();
        e.stopPropagation();
    }
});