@php
    $title = trim($__env->yieldContent('title')) ?: $title ?? config('app.name', 'My App');

    $menuMap = [
        'Dashboard' => ['Dashboard', ''],
        'Kelola Pengguna' => ['Employees', ''],
        'Kelola Tunjangan Transport' => ['HR', 'Transport Allowances'],
        'Setting Tunjangan' => ['HR', 'Settings'],
    ];

    $pageTitle ??= $title;

    if (!isset($pageMenu) || $pageMenu === $title) {
        $pageMenu = $menuMap[$title][0] ?? $title;
        $pageSubMenu = $menuMap[$title][1] ?? ($pageSubMenu ?? '');
    }

    $pageSubMenu ??= '';
@endphp

@extends('backend/layouts/main', get_defined_vars())

@section('styles')
    @stack('styles')
@endsection

@section('content')
    @yield('content')
@endsection

@section('scripts')
    @stack('scripts')
@endsection
