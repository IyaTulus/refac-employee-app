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
                <option value="{{ $employee->id }}" @selected((string) old('employee_id', $user->employee_id) === (string) $employee->id)>
                    {{ $employee->full_name }} ({{ $employee->employee_code }})
                </option>
            @endforeach
        </select>
        <div class="form-text">Cari nama atau kode pegawai, lalu pilih dari dropdown.</div>
        @error('employee_id')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="username" class="form-label">Username</label>
        <input type="text" id="username" name="username"
            class="form-control @error('username') is-invalid @enderror" value="{{ old('username', $user->username) }}"
            required>
        <div id="username_feedback" class="form-text">Minimal 6 karakter, hanya huruf kecil dan angka.</div>
        @error('username')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="email" class="form-label">Email</label>
        <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror"
            value="{{ old('email', $user->email) }}" required>
        @error('email')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="phone" class="form-label">Phone</label>
        <input type="text" id="phone" name="phone" class="form-control @error('phone') is-invalid @enderror"
            value="{{ old('phone', $user->phone) }}" required>
        @error('phone')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="password" class="form-label">Password {{ $isEdit ? '(Kosongkan jika tidak diubah)' : '' }}</label>
        <input type="password" id="password" name="password"
            class="form-control @error('password') is-invalid @enderror" {{ $isEdit ? '' : 'required' }}>
        @error('password')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control"
            {{ $isEdit ? '' : 'required' }}>
    </div>

    <div class="col-md-6">
        <label for="role_id" class="form-label">Role</label>
        <select id="role_id" name="role_id" class="form-select @error('role_id') is-invalid @enderror" required>
            <option value="">-- Pilih Role --</option>
            @foreach ($roles as $role)
                <option value="{{ $role->id }}" @selected((string) old('role_id', $user->role_id) === (string) $role->id)>{{ $role->name }}</option>
            @endforeach
        </select>
        @error('role_id')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" value="1" id="is_active" name="is_active"
                @checked(old('is_active', $user->is_active))>
            <label class="form-check-label" for="is_active">Aktif</label>
        </div>
    </div>
</div>

<div class="d-flex mt-4 gap-2">
    <button type="submit"
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
            const ignoreId = @json($isEdit ? $user->id : '');

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

            form.addEventListener('submit', (event) => {
                if (!employeeIdInput.value) {
                    event.preventDefault();
                    employeeIdInput.classList.add('is-invalid');
                    alert('Silakan pilih pegawai dari dropdown.');
                }
            });
        })();
    </script>
@endpush
