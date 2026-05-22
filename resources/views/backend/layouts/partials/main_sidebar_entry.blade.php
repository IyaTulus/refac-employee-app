@php
    $children = $menu->childrenRecursive ?? collect();
    $visibleChildren = collect($children);

    $isActive = false;
    if (!empty($menu->route_name) && request()->routeIs($menu->route_name)) {
        $isActive = true;
    }

    if (!$isActive) {
        foreach ($visibleChildren as $child) {
            if (!empty($child->route_name) && request()->routeIs($child->route_name)) {
                $isActive = true;
                break;
            }
        }
    }

    $collapseId = 'menu-collapse-' . $menu->id;
@endphp

<?php if ($visibleChildren->isEmpty()) { ?>
<li class="nav-item">
    <a href="{{ $menuLink($menu) }}" class="nav-link {{ $isActive ? 'active' : '' }}">
        @if (!empty($menu->icon))
            <i class="fa-solid {{ $menu->icon }} fa-fw me-2"></i>
        @endif
        {{ $menu->name }}
    </a>
</li>

<?php } else { ?>
<li class="nav-item">
    <button type="button"
        class="nav-link w-100 d-flex align-items-center justify-content-between {{ $isActive ? 'active' : '' }}"
        data-bs-toggle="collapse" data-bs-target="#{{ $collapseId }}" aria-expanded="{{ $isActive ? 'true' : 'false' }}"
        aria-controls="{{ $collapseId }}">
        <span class="d-inline-flex align-items-center">
            @if (!empty($menu->icon))
                <i class="fa-solid {{ $menu->icon }} fa-fw me-2"></i>
            @endif
            {{ $menu->name }}
        </span>
        <i class="fa-solid fa-chevron-down small ms-2"></i>
    </button>

    <div class="{{ $isActive ? 'show' : '' }} collapse" id="{{ $collapseId }}">
        <ul class="navbar-nav border-start ms-3 mt-1 border-2 ps-2">
            <?php foreach ($visibleChildren as $menu) { ?>
            @include('backend.layouts.partials.main_sidebar_entry', get_defined_vars())
            <?php } ?>
        </ul>
    </div>
</li>
<?php } ?>
