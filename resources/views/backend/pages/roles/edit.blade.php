@extends('backend.layouts.admin')

@section('title', 'Edit Role')

@section('content')
    <div class="d-flex mb-4">
        <div>
            <h4 class="fw-bold text-dark mb-0">Edit Role</h4>
            <p class="text-muted small mb-0">Atur nama role dan perbarui matrix akses RBAC.</p>
        </div>
    </div>

    <div class="card card-enterprise border-0 shadow-sm">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('role-permission.update', $role->id) }}" novalidate>
                @include('backend.pages.roles._form', ['isEdit' => true])
            </form>
        </div>
    </div>
@endsection
