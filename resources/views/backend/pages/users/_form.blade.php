@php
    $formAction = $isEdit ? route('users.update', $user->id) : route('users.store');

    $formMethod = $isEdit ? 'PUT' : 'POST';
    $isEdit = ($formMethod ?? 'POST') === 'PUT';
    $title = $isEdit ? 'Edit User' : 'Tambah User';
    $indexHref = route('users.index');

    $roleOptions = jeemce\models\Role::options('id', 'name');
    $employeeClass = 'form-select' . ($errors->has('employee_id') ? ' is-invalid' : '');
    $roleClass = 'form-select' . ($errors->has('role_id') ? ' is-invalid' : '');

    $employeeOptions = ['' => '-- Pilih Pegawai --'];
    foreach ($employees as $employee) {
        $employeeOptions[$employee->id] = $employee->full_name . ' (' . $employee->employee_code . ')';
    }
@endphp

@extends('backend.layouts.admin')

@section('content')
    <div class="container pb-5">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Daftar User</a></li>
                <li class="breadcrumb-item active">{{ $title }}</li>
            </ol>
        </nav>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">{{ $title }}</h1>
        </div>

        <form id="form" method="POST" action="{{ $formAction }}" enctype="multipart/form-data" novalidate>
            @csrf
            @if ($formMethod === 'PUT')
                @method('PUT')
            @endif

            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card card-enterprise mb-4 border-0 shadow-sm">
                        <div class="card-header border-bottom-0 bg-white pb-0 pt-4">
                            <h6 class="fw-bold mb-0">Informasi Akun</h6>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label required small fw-bold">Nama Pengguna (Pegawai)</label>
                                    {{ HtmlHelper::select([
                                        'name' => 'employee_id',
                                        'options' => $employeeOptions,
                                        'class' => $employeeClass,
                                    ]) }}
                                    <div class="form-text">Cari nama atau kode pegawai, lalu pilih dari dropdown.</div>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label required small fw-bold">Role</label>
                                    {{ HtmlHelper::select([
                                        'name' => 'role_id',
                                        'options' => $roleOptions,
                                        'class' => $roleClass,
                                    ]) }}
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label required small fw-bold">Username</label>
                                    <input type="text" name="username"
                                        class="form-control @error('username') is-invalid @enderror"
                                        value="{{ old('username', $user->username ?? '') }}">
                                    <div class="form-text">Minimal 6 karakter, hanya huruf kecil dan angka.</div>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label required small fw-bold">Email</label>
                                    <input type="email" name="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        value="{{ old('email', $user->email ?? '') }}">
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label required small fw-bold">Phone</label>
                                    <input type="text" name="phone"
                                        class="form-control @error('phone') is-invalid @enderror"
                                        value="{{ old('phone', $user->phone ?? '') }}">
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="col-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" id="is_active"
                                            name="is_active" @checked(old('is_active', $user->is_active ?? false))>
                                        <label class="form-check-label" for="is_active">Aktif</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card card-enterprise mb-4 border-0 shadow-sm">
                        <div class="card-header border-bottom-0 bg-white pb-0 pt-4">
                            <h6 class="fw-bold mb-0">Kata Sandi</h6>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <label class="form-label required small fw-bold">
                                    Password {{ $isEdit ? '(Kosongkan jika tidak diubah)' : '' }}
                                </label>
                                <input type="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    {{ $isEdit ? '' : 'required' }}>
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label required small fw-bold">Konfirmasi Password</label>
                                <input type="password" name="password_confirmation" class="form-control"
                                    {{ $isEdit ? '' : 'required' }}>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" id="user-submit-button" class="btn btn-enterprise-primary btn-lg fs-6 py-3">
                            {{ $isEdit ? 'Simpan Perubahan' : 'Simpan User' }}
                        </button>
                        <a href="{{ $indexHref }}" class="btn btn-light btn-lg fs-6 border py-3">Kembali</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script id="data" type="application/json">
    {!! $user->toJson(JSON_FORCE_OBJECT) !!}
</script>

    <script type="module">
        jsonScriptToFormFields('#form', '#data');
        $('#form').formAjaxSubmit();
    </script>
@endpush
