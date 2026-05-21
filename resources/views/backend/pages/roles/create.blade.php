@extends('backend.layouts.admin')

@section('title', 'Tambah Role')

@section('content')
    <div class="d-flex mb-4">
        <div>
            <h4 class="fw-bold text-dark mb-0">Tambah Role</h4>
            <p class="text-muted small mb-0">Buat role baru dan atur akses RBAC menu di bawah.</p>
        </div>
    </div>

    <div class="card card-enterprise border-0 shadow-sm">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('role-permission.store') }}">
                @include('backend.pages.roles._form', ['isEdit' => false])
            </form>
        </div>
    </div>
@endsection
