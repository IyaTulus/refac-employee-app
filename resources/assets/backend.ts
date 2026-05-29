import '../../vendor/jeemce/laravel/assets/vendor.js'
import './backend.less'
import 'select2/dist/css/select2.min.css'
import 'select2-bootstrap-5-theme/dist/select2-bootstrap-5-theme.min.css'
import 'select2'
import '../../vendor/jeemce/laravel-theme-admin-v5/assets/vendor.ts'
import '../../vendor/jeemce/laravel-theme-admin-v5/assets/theme.ts'
import '../../vendor/jeemce/laravel/assets/main.js'

// Compatibility shim: some environments load jeemce assets without registering
// formAjaxSubmit on $.fn. Keep behavior aligned with vendor implementation.
if (globalThis.$ && globalThis.$.fn && !globalThis.$.fn.formAjaxSubmit) {
    globalThis.$.fn.formAjaxSubmit = function (options) {
        options ??= {};

        const viewErrors = (formElem, errors) => {
            const foundFields = [];
            for (const name in errors) {
                globalThis.$(formElem).find('[name]').each(function () {
                    const fieldElem = this;
                    if (name === fieldElem.name || name === fieldElem.dataset.errorName) {
                        foundFields.push(name);
                        globalThis.$(fieldElem).addClass('is-invalid');
                        const errorElem = globalThis.$(fieldElem).next('.invalid-feedback').get(0);
                        if (errorElem) {
                            globalThis.$(errorElem).html(errors[name]);
                        } else {
                            fieldElem.title = errors[name];
                        }
                    }
                });
            }

            const notFoundFields = Object.keys(errors).filter((e) => !foundFields.includes(e));
            if (notFoundFields.length > 0) {
                const voidErrors = {};
                notFoundFields.forEach((field) => {
                    voidErrors[field] = errors[field];
                });
                alert(JSON.stringify(voidErrors));
            }
        };

        this.on('submit', function (event) {
            event.preventDefault();
            const formElem = this;

            globalThis.$(formElem).find('[name]').each(function () {
                const fieldElem = this;
                globalThis.$(fieldElem).removeClass('is-valid').removeClass('is-invalid').removeAttr('title');
            });

            globalThis.$.ajax({
                url: formElem.action,
                method: formElem.method,
                dataType: 'json',
                data: globalThis.$(formElem).serialize(),
            }).done(function () {
                if (options.doneCallback) {
                    options.doneCallback({
                        ...arguments,
                        formElem: formElem,
                    });
                } else {
                    formElem.submit();
                }
            }).fail((jqXHR) => {
                if (jqXHR.status === 200) {
                    if (options.doneCallback) {
                        options.doneCallback({
                            ...arguments,
                            formElem: formElem,
                        });
                    } else {
                        formElem.submit();
                    }
                    return;
                }

                if (jqXHR.status === 422 && jqXHR.responseJSON && jqXHR.responseJSON.errors) {
                    if (options.failCallback) {
                        options.failCallback({
                            ...arguments,
                            formElem: formElem,
                        });
                    } else {
                        viewErrors(formElem, jqXHR.responseJSON.errors);
                    }
                }
            }).always(() => {
                globalThis.$(formElem).find('[name]:not(.is-invalid)').each(function () {
                    globalThis.$(this).addClass('is-valid');
                });
            });
        });
    };
}

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
