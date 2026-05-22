@php
    use jeemce\helpers\AuthHelper;
    use Illuminate\Support\Facades\Route;

    $viewHref = Route::has('backend.user.show')
        ? route('backend.user.show', ['user' => AuthHelper::id()])
        : (Route::has('users.show')
            ? route('users.show', ['id' => AuthHelper::id()])
            : '#');

    $changePasswordHref = Route::has('backend.user.changePassword') ? route('backend.user.changePassword') : '#';
@endphp

<header class="navbar navbar-expand-lg d-print-none sticky-top" id="navbar">
    <div class="container-xl justify-content">
        <button class="sidebar-toggler d-none d-lg-block" type="button">
            <span class="sidebar-icon"></span>
        </button>
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar-menu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="navbar-nav order-md-last ms-md-auto flex-row">
            <button class="nav-link btn-toggle-theme hide-theme-dark px-0" title="Enable dark mode"
                data-bs-toggle="tooltip" data-bs-placement="bottom" type="button">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24"
                    stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M12 3c.132 0 .263 0 .393 0a7.5 7.5 0 0 0 7.92 12.446a9 9 0 1 1 -8.313 -12.454z" />
                </svg>
            </button>

            <button class="nav-link btn-toggle-theme hide-theme-light px-0" title="Enable light mode"
                data-bs-toggle="tooltip" data-bs-placement="bottom" type="button">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24"
                    stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M12 12m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0"></path>
                    <path
                        d="M3 12h1m8 -9v1m8 8h1m-9 8v1m-6.4 -15.4l.7 .7m12.1 -.7l-.7 .7m0 11.4l.7 .7m-12.1 -.7l-.7 .7">
                    </path>
                </svg>
            </button>

            <div class="nav-item dropdown">
                @include('backend.layouts.main_navbar_notify')
            </div>

            <div class="nav-item dropdown">
                <button type="button" class="navbar-profile-toggle dropdown-toggle gap-2" data-bs-toggle="dropdown"
                    data-bs-display="static" aria-expanded="false" aria-label="User menu">

                    <span class="avatar-circle bg-primary fw-semibold text-white">
                        {{ strtoupper(substr(AuthHelper::user()?->name ?? 'AD', 0, 2)) }}
                    </span>

                    <span class="d-none d-md-inline fw-medium">
                        {{ AuthHelper::user()?->name ?? 'Admin' }}
                    </span>
                </button>
                <div
                    class="dropdown-menu dropdown-menu-end dropdown-menu-arrow navbar-profile-menu border-0 p-1 shadow-lg">
                    <a class="dropdown-item" href="{{ $viewHref }}">
                        <i class="bi bi-person me-2"></i> My Profile
                    </a>
                    <a class="dropdown-item" href="{{ $changePasswordHref }}">
                        <i class="bi bi-key me-2"></i> Change Password
                    </a>
                    <div class="dropdown-divider"></div>
                    <form method="POST" action="{{ route('logout') }}" class="dropdown-item p-0">
                        @csrf
                        <button type="submit" class="d-block btn btn-danger w-100">
                            <i class="bi bi-box-arrow-right me-2"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
