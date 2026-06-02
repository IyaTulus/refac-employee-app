@php
    $formAction ??= route('users.store');
    $formMethod ??= 'POST';
    $isEdit = ($formMethod ?? 'POST') === 'PUT';
@endphp

<form id="form" method="POST" action="{{ $formAction }}" enctype="multipart/form-data" novalidate>
    @csrf
    @if ($formMethod === 'PUT')
        @method('PUT')
    @endif

    <div class="row g-4">
        <!-- Left Column: Account & Identity -->
        <div class="col-lg-8">
            <!-- User Account Info -->
            <div class="card card-enterprise mb-4 border-0 shadow-sm">
                <div class="card-header border-bottom-0 bg-white pb-0 pt-4">
                    <h6 class="fw-bold mb-0">Informasi Akun</h6>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label required small fw-bold">Nama Pengguna (Pegawai)</label>
                            <select id="employee_id" name="employee_id" class="form-select @error('employee_id') is-invalid @enderror">
                                <option value="">-- Pilih Pegawai --</option>
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->id }}" @selected((string) $user->employee_id === (string) $employee->id)>
                                        {{ $employee->full_name }} ({{ $employee->employee_code }})
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text">Cari nama atau kode pegawai, lalu pilih dari dropdown.</div>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required small fw-bold">Role</label>
                            <select id="role_id" name="role_id" class="form-select @error('role_id') is-invalid @enderror">
                                <option value="">-- Pilih Role --</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}" @selected((string) $user->role_id === (string) $role->id)>{{ $role->name }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required small fw-bold">Username</label>
                            <input type="text" id="username" name="username"
                                class="form-control @error('username') is-invalid @enderror" value="{{ $user->username }}">
                            <div id="username_feedback" class="form-text">Minimal 6 karakter, hanya huruf kecil dan angka.</div>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required small fw-bold">Email</label>
                            <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                value="{{ $user->email }}">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required small fw-bold">Phone</label>
                            <input type="text" id="phone" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                value="{{ $user->phone }}">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="1" id="is_active" name="is_active"
                                    @checked((bool) $user->is_active)>
                                <label class="form-check-label" for="is_active">Aktif</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Password -->
        <div class="col-lg-4">
            <div class="card card-enterprise mb-4 border-0 shadow-sm">
                <div class="card-header border-bottom-0 bg-white pb-0 pt-4">
                    <h6 class="fw-bold mb-0">Kata Sandi</h6>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label class="form-label required small fw-bold">Password {{ $isEdit ? '(Kosongkan jika tidak diubah)' : '' }}</label>
                        <input type="password" id="password" name="password"
                            class="form-control @error('password') is-invalid @enderror" {{ $isEdit ? '' : 'required' }}>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label required small fw-bold">Konfirmasi Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control"
                            {{ $isEdit ? '' : 'required' }}>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" id="user-submit-button"
                    class="btn btn-enterprise-primary btn-lg fs-6 py-3">{{ $isEdit ? 'Simpan Perubahan' : 'Simpan User' }}</button>
                <a href="{{ route('users.index') }}" class="btn btn-light btn-lg fs-6 border py-3">Kembali</a>
            </div>
        </div>
    </div>
</form>

@push('scripts')
    <script>
        /**
         * User Form Module
         */
        (() => {
            'use strict';

            const form = document.getElementById('form');
            const submitButton = document.getElementById('user-submit-button');
            const originalSubmitText = submitButton?.innerHTML ?? '';

            const setLoadingState = (loading) => {
                if (!submitButton) return;
                submitButton.disabled = loading;
                submitButton.innerHTML = loading ? 'Menyimpan...' : originalSubmitText;
            };

            // Select2 Init
            if (window.jQuery && window.jQuery.fn && window.jQuery.fn.select2) {
                window.jQuery('#employee_id').select2({
                    theme: 'bootstrap-5',
                    width: '100%',
                    placeholder: '-- Pilih Pegawai --',
                    allowClear: true,
                    dropdownParent: window.jQuery('#employee_id').parent(),
                });

                window.jQuery('#role_id').select2({
                    theme: 'bootstrap-5',
                    width: '100%',
                    placeholder: '-- Pilih Role --',
                    allowClear: true,
                });
            }

            // Handle vendor plugin or native fetch
            let handledByVendor = false;

            if (window.jQuery && window.jQuery.fn && window.jQuery.fn.formAjaxSubmit) {
                handledByVendor = true;
                window.jQuery(form).formAjaxSubmit({
                    doneCallback: (context) => {
                        setLoadingState(false);
                        const payload = context?.[0] ?? {};
                        if (payload?.message) window.alert(payload.message);
                        if (payload?.redirect_url) window.location.href = payload.redirect_url;
                    },
                    failCallback: (context) => {
                        setLoadingState(false);
                        const jqXHR = context?.[0];
                        if (jqXHR?.status !== 422) {
                            window.alert(jqXHR?.responseJSON?.message ??
                                'Terjadi kesalahan saat menyimpan data.');
                        }
                    },
                });
            }

            form.addEventListener('submit', async (event) => {
                setLoadingState(true);

                if (handledByVendor) {
                    return;
                }

                event.preventDefault();

                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: new FormData(form),
                    });
                    const payload = await response.json();

                    if (!response.ok) {
                        if (response.status === 422 && payload?.errors) {
                            Object.keys(payload.errors).forEach((name) => {
                                const field = form.querySelector(`[name="${name}"]`);
                                if (field) {
                                    field.classList.add('is-invalid');
                                    const errorElem = field.parentElement?.querySelector('.invalid-feedback');
                                    if (errorElem) {
                                        errorElem.textContent = Array.isArray(payload.errors[name]) ? payload.errors[name][0] : String(payload.errors[name]);
                                    }
                                }
                            });
                            setLoadingState(false);
                            return;
                        }
                        throw new Error(payload?.message ?? 'Terjadi kesalahan saat menyimpan data.');
                    }

                    setLoadingState(false);
                    if (payload?.message) window.alert(payload.message);
                    if (payload?.redirect_url) window.location.href = payload.redirect_url;
                } catch (error) {
                    setLoadingState(false);
                    window.alert(error?.message ?? 'Terjadi kesalahan saat menyimpan data.');
                }
            });
        })();
    </script>
@endpush