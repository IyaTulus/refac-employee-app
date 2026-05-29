{{-- @php

    $title = 'Form User';

    $roleOptions = jeemce\models\Role::options('id', 'name');
@endphp --}}

{{-- @extends('backend.layouts.admin', get_defined_vars()) --}}
{{-- @extends('backend/layouts/main', get_defined_vars()) --}}
@csrf
@if ($isEdit)
    @method('PUT')
@endif

<div class="row g-3">
    <div class="col-md-6">
        <label for="employee_id" class="form-label">Nama Pengguna (Pegawai)</label>
        <select id="employee_id" name="employee_id" class="form-select @error('employee_id') is-invalid @enderror"
            required>
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
        <label for="username" class="form-label">Username</label>
        <input type="text" id="username" name="username"
            class="form-control @error('username') is-invalid @enderror" value="{{ $user->username }}" required>
        <div id="username_feedback" class="form-text">Minimal 6 karakter, hanya huruf kecil dan angka.</div>
        <div class="invalid-feedback"></div>
    </div>

    <div class="col-md-6">
        <label for="email" class="form-label">Email</label>
        <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror"
            value="{{ $user->email }}" required>
        <div class="invalid-feedback"></div>
    </div>

    <div class="col-md-6">
        <label for="phone" class="form-label">Phone</label>
        <input type="text" id="phone" name="phone" class="form-control @error('phone') is-invalid @enderror"
            value="{{ $user->phone }}" required>
        <div class="invalid-feedback"></div>
    </div>

    <div class="col-md-6">
        <label for="password" class="form-label">Password {{ $isEdit ? '(Kosongkan jika tidak diubah)' : '' }}</label>
        <input type="password" id="password" name="password"
            class="form-control @error('password') is-invalid @enderror" {{ $isEdit ? '' : 'required' }}>
        <div class="invalid-feedback"></div>
    </div>

    <div class="col-md-6">
        <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control"
            {{ $isEdit ? '' : 'required' }}>
        <div class="invalid-feedback"></div>
    </div>

    <div class="col-md-6">
        <label for="role_id" class="form-label">Role</label>
        <select id="role_id" name="role_id" class="form-select @error('role_id') is-invalid @enderror" required>
            <option value="">-- Pilih Role --</option>
            @foreach ($roles as $role)
                <option value="{{ $role->id }}" @selected((string) $user->role_id === (string) $role->id)>{{ $role->name }}</option>
            @endforeach
        </select>
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

<div class="d-flex mt-4 gap-2">
    <button type="submit" id="user-submit-button"
        class="btn btn-enterprise-primary px-4">{{ $isEdit ? 'Simpan Perubahan' : 'Simpan User' }}</button>
    <a href="{{ route('users.index') }}" class="btn btn-light border px-4">Kembali</a>
</div>

@push('scripts')
    <script>
        (() => {
            const employeeIdInput = document.getElementById('employee_id');
            const usernameInput = document.getElementById('username');
            const usernameFeedback = document.getElementById('username_feedback');
            const form = employeeIdInput.closest('form');
            const submitButton = document.getElementById('user-submit-button');
            const ignoreId = @json($isEdit ? $user->id : '');
            const originalSubmitText = submitButton?.innerHTML ?? '';

            const initSelect2 = () => {
                if (!window.jQuery || !window.jQuery.fn || !window.jQuery.fn.select2) {
                    return;
                }

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
            };

            initSelect2();

            const setLoadingState = (loading) => {
                if (!submitButton) {
                    return;
                }

                submitButton.disabled = loading;
                submitButton.innerHTML = loading ? 'Menyimpan...' : originalSubmitText;
            };

            const usernameRegex = /^[a-z0-9]{6,}$/;
            usernameInput.addEventListener('keyup', async () => {
                const value = usernameInput.value.trim();

                if (!usernameRegex.test(value)) {
                    usernameInput.classList.add('is-invalid');
                    usernameInput.classList.remove('is-valid');
                    usernameFeedback.textContent =
                        'Format tidak valid. Gunakan huruf kecil + angka, minimal 6 karakter.';
                    return;
                }

                const response = await fetch(
                    `{{ route('users.check-username') }}?username=${encodeURIComponent(value)}&ignore=${encodeURIComponent(ignoreId)}`
                );
                const result = await response.json();

                if (result.valid) {
                    usernameInput.classList.remove('is-invalid');
                    usernameInput.classList.add('is-valid');
                    usernameFeedback.textContent = 'Username tersedia.';
                } else {
                    usernameInput.classList.add('is-invalid');
                    usernameInput.classList.remove('is-valid');
                    usernameFeedback.textContent = result.format_valid ? 'Username sudah digunakan.' :
                        'Format tidak valid.';
                }
            });

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
                            window.alert(jqXHR?.responseJSON?.message ?? 'Terjadi kesalahan saat menyimpan data.');
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
