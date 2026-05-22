<?php
$menuTree = \jeemce\models\Menu::tree([
    'id_menu' => null,
    'type' => 'owner_sidebar',
    'status' => 'publish',
]);
?>

<aside class="navbar navbar-vertical navbar-expand-lg navbar-dark sidebar" data-bs-theme="dark">
    <div class="container-fluid justify-content-start px-0">
        <h1 class="navbar-brand ms-lg-0 ms-3 gap-3 text-white">
            <div class="logo">
                <img src="{{ asset('assets/img/logo.png') }}" alt="" height="30">
            </div>
            {{-- <a href="{{ route('backend.home.index') }}" target="_blank" class="fw-bold hstack text-decoration-none gap-3">
                <div style="font-size: .9rem;">JMC CMS Laravel</div>
            </a> --}}
        </h1>
        <div class="offcanvas offcanvas-start px-lg-3" id="sidebar-menu">
            <div class="offcanvas-header">
                <div class="d-flex align-items-center gap-3">
                    <div class="image">
                        <img src="{{ asset('assets/img/logo.png') }}" alt="" height="15">
                    </div>
                    <div class="logo-text flex-grow-1">
                        <h3 class="m-0"></h3>
                        <div class="fs-4 fw-bold">JMC CMS Laravel</div>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"
                    aria-label="Close"></button>
            </div>
            <div class="offcanvas-body p-lg-0 flex-column flex-grow-1 overflow-auto p-3">
                <ul class="navbar-nav align-items-start mt-lg-3">
                    <?php foreach ($menuTree as $menu) { ?>
                    @include('backend/layouts/partials/main_sidebar_entry', get_defined_vars())
                    <?php } ?>
                </ul>

            </div>
        </div>
    </div>
</aside>
