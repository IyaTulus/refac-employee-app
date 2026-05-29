@extends('backend.layouts.admin')

@section('title', 'Edit User')

@section('content')
    <div class="d-flex mb-4">
        <div>
            <h4 class="fw-bold text-dark mb-0">Edit User</h4>
            <p class="text-muted small mb-0">Perbarui akun user dan role RBAC yang ditetapkan.</p>
        </div>
    </div>

    <div class="card card-enterprise border-0 shadow-sm">
        <div class="card-body p-4">
            <form id="user-form" action="{{ route('users.update', $user->id) }}" method="POST" novalidate>
                @include('backend.pages.users._form', ['isEdit' => true])
            </form>
        </div>
    </div>
@endsection
