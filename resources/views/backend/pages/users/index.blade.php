@extends('backend.layouts.admin')

@section('title', 'Kelola Pengguna')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark mb-1">Kelola Pengguna (Users)</h4>
            <p class="text-muted small mb-0">Atur hak akses login bagi pegawai untuk masuk ke sistem HRIS.</p>
        </div>
        <a href="{{ route('users.create') }}" class="btn btn-enterprise-primary d-flex align-items-center gap-2">
            <i class="bi bi-person-plus"></i> Tambah Pengguna
        </a>
    </div>

    <!-- Filter Card -->
    <div class="card card-enterprise mb-4 border-0 shadow-sm">
        <div class="card-body p-3">
            <form action="{{ route('users.index') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-light text-muted border-end-0"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control bg-light border-start-0 ps-0"
                            placeholder="Cari email atau username..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="role" class="form-select bg-light">
                        <option value="">Semua Role</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}" @selected(request('role') == $role->id)>{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-dark px-4">Cari</button>
                    <a href="{{ route('users.index') }}" class="btn btn-light border px-4">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card card-enterprise border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table-enterprise mb-0 table align-middle">
                <thead>
                    <tr>
                        <th class="ps-4" style="width: 50px;">No.</th>
                        <th>User Akun</th>
                        <th>Ditautkan ke Pegawai</th>
                        <th>Roles</th>
                        <th>Status Login</th>
                        <th>Terakhir Login</th>
                        <th class="pe-4 text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $index => $user)
                        <tr>
                            <td class="text-muted small ps-4">{{ $users->firstItem() + $index }}</td>
                            <td>
                                <div class="fw-bold text-dark">{{ $user->username }}</div>
                                <div class="small text-muted">{{ $user->email }}</div>
                            </td>
                            <td>
                                @if ($user->employee)
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bi bi-person-badge text-primary"></i>
                                        <span class="fw-medium text-dark">{{ $user->employee->full_name }}</span>
                                    </div>
                                @else
                                    <span class="text-muted small fst-italic">Belum ditautkan</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex flex-wrap gap-1">
                                    @if ($user->role)
                                        <span
                                            class="badge badge-enterprise bg-light text-secondary border">{{ $user->role->name }}</span>
                                    @else
                                        <span class="text-muted small fst-italic">Belum ditetapkan</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <form action="{{ route('users.toggle-status', $user) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn border-0 bg-transparent p-0 text-start"
                                        title="Klik untuk mengubah status">
                                        @if ($user->is_active)
                                            <span
                                                class="badge badge-enterprise bg-success-subtle text-success border-success-subtle border">
                                                <i class="bi bi-check-circle me-1"></i> Aktif
                                            </span>
                                        @else
                                            <span
                                                class="badge badge-enterprise bg-danger-subtle text-danger border-danger-subtle border">
                                                <i class="bi bi-x-circle me-1"></i> Nonaktif
                                            </span>
                                        @endif
                                    </button>
                                </form>
                            </td>
                            <td class="text-muted small">
                                {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Belum pernah' }}
                            </td>
                            <td class="pe-4 text-end">
                                <div class="d-flex justify-content-end gap-1">
                                    <a href="{{ route('users.edit', $user) }}"
                                        class="btn btn-sm btn-light text-warning border" title="Edit Akses"><i
                                            class="bi bi-key"></i></a>
                                    @if (auth()->id() !== $user->id)
                                        <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun ini secara permanen?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-light text-danger border"
                                                title="Hapus Akun"><i class="bi bi-trash"></i></button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-5 text-center">
                                <div class="empty-state">
                                    <i class="bi bi-person-dash d-block fs-2 text-muted mb-3 opacity-50"></i>
                                    <h6 class="text-muted fw-bold">Data Pengguna Kosong</h6>
                                    <p class="text-muted small">Kata kunci / filter pencarian Anda tidak menemukan hasil.
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($users->hasPages())
            <div class="card-footer border-top d-flex justify-content-between align-items-center bg-white px-4 py-3">
                <div class="small text-muted">
                    Menampilkan {{ $users->firstItem() }} ke {{ $users->lastItem() }} dari {{ $users->total() }} akun
                </div>
                <div>
                    {{ $users->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @endif
    </div>
@endsection
