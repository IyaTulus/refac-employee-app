@php
    $isEdit ??= !empty($role->id);
    $formAction = $isEdit ? route('role-permission.update', $role->id) : route('role-permission.store');
    $formMethod = $isEdit ? 'PUT' : 'POST';
    $title = $isEdit ? 'Edit Role' : 'Tambah Role';
    $indexHref = route('role-permission.index');
@endphp

@extends('backend.layouts.admin')

@section('title', $title)

@section('content')
    <div class="d-flex mb-4">
        <div>
            <h4 class="fw-bold text-dark mb-0">{{ $title }}</h4>
            <p class="text-muted small mb-0"> {{ $isEdit ? 'Perbarui' : 'Buat' }} baru dan atur akses RBAC menu di bawah.</p>
        </div>
    </div>

    <div class="card card-enterprise border-0 shadow-sm">
        <div class="card-body p-4">
            <form id="form" method="POST" action="{{ $formAction }}" novalidate>
                @csrf
                @if ($isEdit)
                    @method('PUT')
                @endif

                <div class="mb-3">
                    <label for="name" class="form-label">Nama Role</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                        name="name" value="{{ old('name', $role->name) }}" placeholder="Contoh: Super Admin" required>
                    <div class="invalid-feedback"></div>
                </div>

                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-end mb-2">
                        <div>
                            <h2 class="h6 mb-1">Permission Matrix</h2>
                            <p class="text-muted small mb-0">Pilih nilai akses untuk setiap menu: <strong>All</strong>,
                                <strong>None</strong>, atau <strong>Only</strong>.
                            </p>
                        </div>
                    </div>
                    <div class="invalid-feedback"></div>
                    @include('components.permission-matrix', [
                        'permissions' => $menus,
                        'selectedPermissions' => old('accesses', $selectedPermissions),
                    ])
                </div>

                <div class="d-flex mt-4 gap-2">
                    <button type="submit"
                        class="btn btn-enterprise-primary px-4">{{ $isEdit ? 'Simpan Perubahan' : 'Simpan Role' }}</button>
                    <a href="{{ route('role-permission.index') }}" class="btn btn-light border px-4">Kembali</a>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    <script id="data" type="application/json">
    {!! $role->toJson(JSON_FORCE_OBJECT) !!}
</script>

    <script type="module">
        jsonScriptToFormFields('#form', '#data');
        $('#form').formAjaxSubmit();
    </script>
@endpush
