@extends('backend.layouts.admin')

@section('title', 'Tambah User')

@section('content')
    <div class="d-flex mb-4">
        <div>
            <h4 class="fw-bold text-dark mb-0">Tambah User</h4>
            <p class="text-muted small mb-0">Buat akun user baru dan tentukan role RBAC-nya.</p>
        </div>
    </div>

    <div class="card card-enterprise border-0 shadow-sm">
        <div class="card-body p-4">
            <form id="user-form" action="{{ route('users.store') }}" method="POST" novalidate>
                @include('backend.pages.users._form', ['isEdit' => false])
            </form>
        </div>
    </div>
@endsection
