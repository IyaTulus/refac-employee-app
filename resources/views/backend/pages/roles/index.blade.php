@extends('backend.layouts.admin')

@section('title', 'Roles & Permissions')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark mb-1">Roles & Permissions</h4>
            <p class="text-muted small mb-0">Konfigurasi hak akses modul untuk setiap tipe pengguna.</p>
        </div>
        <a href="{{ route('role-permission.create') }}" class="btn btn-enterprise-primary d-flex align-items-center gap-2">
            <i class="bi bi-shield-plus"></i> <span>Buat Role Baru</span>
        </a>
    </div>

    <div class="card card-enterprise border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table-enterprise mb-0 table align-middle">
                <thead>
                    <tr>
                        <th class="ps-4" style="width: 60px;">No.</th>
                        <th>Nama Role</th>
                        <th style="width: 180px;">Dibuat</th>
                        <th class="pe-4 text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($roles as $index => $role)
                        <tr>
                            <td class="text-muted small ps-4">{{ $roles->firstItem() + $index }}</td>
                            <td>
                                <span class="fw-bold text-dark">{{ $role->name }}</span>
                            </td>
                            <td class="text-muted small">
                                {{ optional($role->created_at)->format('d M Y H:i') ?? '-' }}
                            </td>
                            <td class="pe-4 text-end">
                                <div class="d-flex justify-content-end gap-1">
                                    <a href="{{ route('role-permission.edit', $role->id) }}"
                                        class="btn btn-sm btn-light text-warning border" title="Konfigurasi Hak Akses"><i
                                            class="bi bi-shield-check"></i></a>
                                    <form action="{{ route('role-permission.destroy', $role->id) }}" method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus role keamanan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light text-danger border"
                                            title="Hapus Role"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-5 text-center">
                                <div class="empty-state">
                                    <i class="bi bi-shield-slash d-block fs-2 text-muted mb-3 opacity-50"></i>
                                    <h6 class="text-muted fw-bold">Belum Ada Role</h6>
                                    <p class="text-muted small">Silakan tambah pengaturan role baru.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($roles->hasPages())
            <div class="card-footer border-top d-flex justify-content-between align-items-center bg-white px-4 py-3">
                <div class="small text-muted">
                    Menampilkan {{ $roles->firstItem() ?? 0 }} ke {{ $roles->lastItem() ?? 0 }} dari {{ $roles->total() }}
                    data
                </div>
                <div>
                    {{ $roles->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @endif
    </div>

@endsection
