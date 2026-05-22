<?php
use App\Models\Menus;
use Illuminate\Support\Facades\Route;
use jeemce\helpers\AuthHelper;

$menuTree = Menus::query()->whereNull('id_menu')->where('type', 'main')->where('status', 'active')->orderBy('sort')->get();

$menuTree->load([
    'childrenRecursive' => function ($query) {
        $query->where('status', 'active')->orderBy('sort');
    },
]);

$brandHref = Route::has('admin.dashboard') ? route('admin.dashboard') : url('/admin');

$menuLink = function (Menus $menu): string {
    if (!empty($menu->href)) {
        return $menu->href;
    }

    if (!empty($menu->route_name) && Route::has($menu->route_name)) {
        return route($menu->route_name, json_decode($menu->route_params ?? '[]', true) ?: []);
    }

    if ($menu->route_name === 'dashboard' && Route::has('admin.dashboard')) {
        return route('admin.dashboard');
    }

    return '#';
};
?>

<aside class="navbar navbar-vertical navbar-expand-lg navbar-dark sidebar" data-bs-theme="dark">
    <div class="container-fluid justify-content-start px-0">
        <h1 class="navbar-brand ms-lg-0 ms-3 gap-3 text-white">
            <div class="logo">
                <img src="{{ asset('assets/img/logo.png') }}" alt="" height="30">
            </div>
            <a href="{{ $brandHref }}" target="_blank" class="fw-bold hstack text-decoration-none gap-3">
                <div style="font-size: .9rem;">HRIS EMPLOYEE APP</div>
            </a>
        </h1>
        <div class="offcanvas offcanvas-start px-lg-3" id="sidebar-menu">
            <div class="offcanvas-header">
                <div class="d-flex align-items-center gap-3">
                    <div class="image">
                        <img src="{{ asset('assets/img/logo.png') }}" alt="" height="15">
                    </div>
                    <div class="logo-text grow">
                        <h3 class="m-0"></h3>
                        <div class="fs-4 fw-bold">HRIS EMPLOYEE APP</div>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"
                    aria-label="Close"></button>
            </div>
            <div class="offcanvas-body p-lg-0 flex-column grow overflow-auto p-3">
                <ul class="navbar-nav align-items-start mt-lg-3">
                    <?php foreach ($menuTree as $menu) { ?>
                    @include('backend.layouts.partials.main_sidebar_entry', get_defined_vars())
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
</aside>
