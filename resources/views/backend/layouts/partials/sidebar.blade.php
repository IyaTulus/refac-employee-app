<style>
    /* Sidebar scoped styles */
    .app-sidebar {
        background-color: #f8f9fa;
        color: #212529;
        width: 280px;
        min-height: 100vh;
        border-right: 1px solid #dee2e6;
    }

    .app-sidebar h5 {
        color: #212529;
        font-weight: 600
    }

    .app-sidebar .nav-link {
        color: #495057;
        padding: .5rem .75rem;
        border-radius: .375rem;
        display: flex;
        align-items: center;
        border: 1px solid transparent;
        background-color: transparent;
    }

    .app-sidebar .nav-link:hover {
        background-color: #e9ecef;
        color: #212529;
        text-decoration: none
    }

    .app-sidebar .nav-link.active {
        background-color: #0d6efd;
        color: #fff;
        border-color: #0d6efd;
    }

    .app-sidebar .submenu {
        margin-top: .25rem;
        list-style: none;
        padding-left: 0;
        margin-left: .5rem;
        border-left: 1px solid #dee2e6;
    }

    .app-sidebar .caret.rotate {
        transform: rotate(180deg);
        transition: transform .18s ease
    }

    .app-sidebar .fa-fw {
        width: 1.25rem;
        text-align: center
    }

    .app-sidebar .collapse-inner {
        padding-left: .5rem
    }
</style>

<aside class="app-sidebar p-3">
    <h5 class="mb-3">Menu</h5>
    <nav id="sidebar-nav"></nav>
</aside>

<script>
    // inject FontAwesome if not present (ensures icons show)
    (function ensureFA() {
        const hrefs = Array.from(document.querySelectorAll('link[rel="stylesheet"]')).map(l => l.href || '');
        const hasFA = hrefs.some(u => /font-awesome|fontawesome|cdnjs.cloudflare.com\/ajax\/libs\/font-awesome/i
            .test(u));
        if (!hasFA) {
            const l = document.createElement('link');
            l.rel = 'stylesheet';
            l.href = 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css';
            l.crossOrigin = 'anonymous';
            document.head.appendChild(l);
        }
    })();

    (async function() {
        const nav = document.getElementById('sidebar-nav');

        try {
            const response = await fetch('{{ url('/api/admin/menus') }}', {
                credentials: 'include',
                headers: {
                    'Accept': 'application/json',
                },
            });

            if (!response.ok) {
                throw new Error(`Gagal memuat menu: ${response.status}`);
            }

            const menus = await response.json();

            function buildList(items, depth = 0) {
                const ul = document.createElement('ul');
                ul.className = depth === 0 ? 'list-unstyled mb-0 nav nav-pills flex-column gap-1' : 'submenu';

                items.forEach(item => {
                    const li = document.createElement('li');
                    li.className = '';

                    const hasChildren = Array.isArray(item.children) && item.children.length > 0;

                    if (hasChildren) {
                        const collapseId = `menu-collapse-${item.id}`;

                        const toggle = document.createElement('button');
                        toggle.type = 'button';
                        toggle.className =
                            'nav-link d-flex align-items-center justify-content-between w-100';
                        toggle.setAttribute('data-bs-toggle', 'collapse');
                        toggle.setAttribute('data-bs-target', `#${collapseId}`);
                        toggle.setAttribute('aria-expanded', 'false');
                        toggle.setAttribute('aria-controls', collapseId);

                        const label = document.createElement('span');
                        label.className = 'd-inline-flex align-items-center';

                        if (item.icon) {
                            const i = document.createElement('i');
                            i.className = 'fa ' + item.icon + ' fa-fw me-2';
                            label.appendChild(i);
                        }

                        const text = document.createElement('span');
                        text.textContent = item.name;
                        label.appendChild(text);

                        const caret = document.createElement('i');
                        caret.className = 'fa fa-chevron-down ms-2 small';

                        toggle.appendChild(label);
                        toggle.appendChild(caret);

                        const collapse = document.createElement('div');
                        collapse.className = 'collapse';
                        collapse.id = collapseId;

                        const inner = document.createElement('div');
                        inner.className = 'collapse-inner';
                        inner.appendChild(buildList(item.children, depth + 1));
                        collapse.appendChild(inner);

                        // toggle caret rotation on show/hide
                        collapse.addEventListener('show.bs.collapse', () => caret.classList.add(
                            'rotate'));
                        collapse.addEventListener('hide.bs.collapse', () => caret.classList.remove(
                            'rotate'));

                        li.appendChild(toggle);
                        li.appendChild(collapse);
                    } else {
                        const a = document.createElement('a');
                        a.href = item.href || '{{ url('/admin') }}';
                        a.className = 'nav-link d-flex align-items-center';

                        if (item.icon) {
                            const i = document.createElement('i');
                            i.className = 'fa ' + item.icon + ' fa-fw me-2';
                            a.appendChild(i);
                        }

                        const text = document.createElement('span');
                        text.textContent = item.name;
                        a.appendChild(text);

                        li.appendChild(a);
                    }

                    ul.appendChild(li);
                });

                return ul;
            }

            nav.appendChild(buildList(menus.data ?? menus));

            // mark active link and open parents
            const links = nav.querySelectorAll('a.nav-link');
            const current = location.pathname + location.hash;
            links.forEach(a => {
                try {
                    const hrefUrl = new URL(a.href, location.href);
                    const hrefPath = hrefUrl.pathname + (hrefUrl.hash || '');
                    if (hrefPath === current || a.href === location.href || location.href.startsWith(a
                            .href)) {
                        a.classList.add('active');
                        const parentCollapse = a.closest('.collapse');
                        if (parentCollapse) {
                            const bs = bootstrap.Collapse.getOrCreateInstance(parentCollapse);
                            bs.show();
                            const caret = parentCollapse.previousElementSibling && parentCollapse
                                .previousElementSibling.querySelector('i.fa-chevron-down');
                            if (caret) caret.classList.add('rotate');
                        }
                    }
                } catch (e) {}
            });

        } catch (error) {
            console.error(error);
            nav.innerHTML = '<div class="text-warning small">Menu gagal dimuat.</div>';
        }
    })();
</script>
