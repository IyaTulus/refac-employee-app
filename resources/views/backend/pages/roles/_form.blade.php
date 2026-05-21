@csrf
@if ($isEdit)
    @method('PUT')
@endif

<div class="mb-3">
    <label for="name" class="form-label">Nama Role</label>
    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
        value="{{ old('name', $role->name) }}" placeholder="Contoh: Super Admin" required>
    @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-4">
    <div class="d-flex justify-content-between align-items-end mb-2">
        <div>
            <h2 class="h6 mb-1">Permission Matrix</h2>
            <p class="text-muted small mb-0">Pilih nilai akses untuk setiap menu: <strong>All</strong>,
                <strong>None</strong>, atau <strong>Only</strong>.</p>
        </div>
    </div>
    @error('accesses')
        <div class="text-danger small mb-2">{{ $message }}</div>
    @enderror
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
