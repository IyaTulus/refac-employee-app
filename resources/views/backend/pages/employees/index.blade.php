@extends('backend.layouts.admin')

@section('title', 'Kelola Pengguna')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark mb-1">Data Pegawai</h4>
            <p class="text-muted small mb-0">Kelola data seluruh pegawai, posisi, dan penempatan departemen.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('employees.create') }}" class="btn btn-enterprise-primary d-flex align-items-center gap-2">
                <i class="bi bi-plus-lg"></i> <span>Tambah Pegawai</span>
            </a>
            <a href="{{ route('employees.export.excel', request()->query()) }}"
                class="btn btn-light d-flex align-items-center gap-2 border">
                <i class="bi bi-file-earmark-spreadsheet text-success"></i> <span>Ekspor Excel</span>
            </a>
            <a href="{{ route('employees.export.pdf', request()->query()) }}"
                class="btn btn-light d-flex align-items-center gap-2 border">
                <i class="bi bi-file-earmark-pdf text-danger"></i> <span>Ekspor PDF</span>
            </a>
        </div>
    </div>

    <!-- Filter Toolbar -->
    <div class="card card-enterprise mb-4 border-0 shadow-sm">
        <div class="card-body p-3">
            <form action="{{ route('employees.index') }}" method="GET" id="filterForm" class="row g-2 align-items-end">
                <!-- Search -->
                <div class="col-md-4">
                    <label
                        class="form-label text-muted small fw-semibold text-uppercase letter-spacing-1 mb-1">Pencarian</label>
                    <div class="input-group">
                        <span class="input-group-text border-end-0 text-muted bg-white"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control border-start-0 ps-0"
                            placeholder="Ketik NIP, Nama, atau Jabatan..." value="{{ request('search') }}">
                    </div>
                </div>

                <!-- Position Filter -->
                <div class="col-md-3">
                    <label class="form-label text-muted small fw-semibold text-uppercase letter-spacing-1 mb-1">Filter
                        Jabatan</label>
                    <select name="positions[]" class="form-select bg-light" id="positionsSelect">
                        <option value="">-- Semua Jabatan --</option>
                        <option value="manager" @selected(in_array('manager', request('positions', [])))>Manager</option>
                        <option value="staf" @selected(in_array('staf', request('positions', [])))>Staf</option>
                        <option value="magang" @selected(in_array('magang', request('positions', [])))>Magang</option>
                    </select>
                </div>

                <!-- Tenure Filter -->
                <div class="col-md-3">
                    <label class="form-label text-muted small fw-semibold text-uppercase letter-spacing-1 mb-1">Masa Kerja
                        (Tahun)</label>
                    <div class="input-group">
                        <select name="tenure_operator" class="form-select bg-light" style="max-width: 70px;">
                            <option value=">" @selected(request('tenure_operator') === '>')>&gt;</option>
                            <option value="=" @selected(request('tenure_operator') === '=')>=</option>
                            <option value="<" @selected(request('tenure_operator') === '<')>&lt;</option>
                        </select>
                        <input type="number" name="tenure_value" class="form-control" placeholder="Tahun..."
                            value="{{ request('tenure_value') }}">
                    </div>
                </div>

                <!-- Actions -->
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-dark w-100">Cari</button>
                    <a href="{{ route('employees.index') }}" class="btn btn-light w-100 border">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Bulk Action Toolbar (Hidden initial) -->
    <div id="bulkActionToolbar"
        class="alert alert-primary bg-primary-subtle border-primary-subtle d-none align-items-center justify-content-between mb-4 rounded border p-3">
        <div class="d-flex align-items-center text-primary gap-2">
            <i class="bi bi-info-circle-fill"></i>
            <span class="small fw-medium"><strong id="selectedCount">0</strong> baris data terpilih</span>
        </div>
        <form action="{{ route('employees.bulk-action') }}" method="POST" class="d-flex m-0 gap-2">
            @csrf
            <div id="bulkIdsContainer"></div>
            <select name="action" class="form-select border-primary text-primary bg-white"
                style="width: auto; height: 36px; padding-top:0.25rem; padding-bottom:0.25rem;">
                <option value="">-- Aksi Massal --</option>
                <option value="active">Set Aktif</option>
                <option value="inactive">Set Nonaktif</option>
                <option value="delete">Hapus Data Terpilih</option>
            </select>
            <button type="submit" class="btn btn-primary d-flex align-items-center" style="height: 36px; padding: 0 1rem;"
                onclick="return confirm('Apakah Anda yakin menerapkan aksi ini pada data terpilih?')">Terapkan</button>
        </form>
    </div>

    <!-- Data Grid -->
    <div class="card card-enterprise border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table-enterprise mb-0 table align-middle">
                <thead>
                    <tr>
                        <th class="ps-4" style="width: 40px;">
                            <input type="checkbox" class="form-check-input border-secondary" id="selectAll">
                        </th>
                        <th style="width: 60px;">No</th>
                        <th>
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'employee_code', 'order' => request('order') === 'asc' ? 'desc' : 'asc']) }}"
                                class="text-decoration-none text-muted d-flex align-items-center">
                                NIP {!! request('sort') === 'employee_code'
                                    ? (request('order') === 'asc'
                                        ? '<i class="bi bi-sort-up ms-1 text-primary"></i>'
                                        : '<i class="bi bi-sort-down ms-1 text-primary"></i>')
                                    : '<i class="bi bi-arrow-down-up ms-1 text-muted" style="opacity:0.3; font-size:0.65rem;"></i>' !!}
                            </a>
                        </th>
                        <th>
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'full_name', 'order' => request('order') === 'asc' ? 'desc' : 'asc']) }}"
                                class="text-decoration-none text-muted d-flex align-items-center">
                                NAMA PEGAWAI {!! request('sort') === 'full_name'
                                    ? (request('order') === 'asc'
                                        ? '<i class="bi bi-sort-up ms-1 text-primary"></i>'
                                        : '<i class="bi bi-sort-down ms-1 text-primary"></i>')
                                    : '<i class="bi bi-arrow-down-up ms-1 text-muted" style="opacity:0.3; font-size:0.65rem;"></i>' !!}
                            </a>
                        </th>
                        <th>
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'position', 'order' => request('order') === 'asc' ? 'desc' : 'asc']) }}"
                                class="text-decoration-none text-muted d-flex align-items-center">
                                JABATAN {!! request('sort') === 'position'
                                    ? (request('order') === 'asc'
                                        ? '<i class="bi bi-sort-up ms-1 text-primary"></i>'
                                        : '<i class="bi bi-sort-down ms-1 text-primary"></i>')
                                    : '<i class="bi bi-arrow-down-up ms-1 text-muted" style="opacity:0.3; font-size:0.65rem;"></i>' !!}
                            </a>
                        </th>
                        <th>STATUS PEGAWAI</th>
                        <th>
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'join_date', 'order' => request('order') === 'asc' ? 'desc' : 'asc']) }}"
                                class="text-decoration-none text-muted d-flex align-items-center">
                                TGL MASUK {!! request('sort') === 'join_date'
                                    ? (request('order') === 'asc'
                                        ? '<i class="bi bi-sort-up ms-1 text-primary"></i>'
                                        : '<i class="bi bi-sort-down ms-1 text-primary"></i>')
                                    : '<i class="bi bi-arrow-down-up ms-1 text-muted" style="opacity:0.3; font-size:0.65rem;"></i>' !!}
                            </a>
                        </th>
                        <th>
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'tenure', 'order' => request('order') === 'asc' ? 'desc' : 'asc']) }}"
                                class="text-decoration-none text-muted d-flex align-items-center">
                                MASA KERJA {!! request('sort') === 'tenure'
                                    ? (request('order') === 'asc'
                                        ? '<i class="bi bi-sort-up ms-1 text-primary"></i>'
                                        : '<i class="bi bi-sort-down ms-1 text-primary"></i>')
                                    : '<i class="bi bi-arrow-down-up ms-1 text-muted" style="opacity:0.3; font-size:0.65rem;"></i>' !!}
                            </a>
                        </th>
                        <th class="pe-4 text-end">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employees as $index => $employee)
                        <tr>
                            <td class="ps-4">
                                <input type="checkbox" class="form-check-input border-secondary row-checkbox"
                                    value="{{ $employee->id }}">
                            </td>
                            <td class="text-muted small">{{ $employees->firstItem() + $index }}</td>
                            <td><span class="fw-medium text-dark font-monospace"
                                    style="font-size:0.85rem;">{{ $employee->employee_code }}</span></td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    @if ($employee->has_photo)
                                        <img src="{{ $employee->photo_url }}"
                                            class="rounded-circle object-fit-cover border shadow-sm" width="32"
                                            height="32" alt="">
                                    @else
                                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center border shadow-sm"
                                            style="width: 32px; height: 32px;">
                                            <i class="bi bi-person text-muted"></i>
                                        </div>
                                    @endif
                                    <div class="fw-bold text-dark">{{ $employee->full_name }}</div>
                                </div>
                            </td>
                            <td>
                                @php
                                    $posStyle = match (strtolower($employee->position)) {
                                        'manager' => 'bg-primary-subtle text-primary border-primary-subtle',
                                        'staf' => 'bg-info-subtle text-info border-info-subtle',
                                        'magang' => 'bg-warning-subtle text-warning border-warning-subtle',
                                        default => 'bg-light text-dark border',
                                    };
                                @endphp
                                <span
                                    class="badge badge-enterprise {{ $posStyle }} border">{{ ucfirst($employee->position) }}</span>
                            </td>
                            <td>
                                @php
                                    $statusStyle = match ($employee->employment_status) {
                                        'permanent' => 'bg-success-subtle text-success border-success-subtle',
                                        'contract' => 'bg-warning-subtle text-warning border-warning-subtle',
                                        'intern' => 'bg-secondary-subtle text-secondary border-secondary-subtle',
                                        default => 'bg-light text-dark border',
                                    };
                                    $statusLabel = match ($employee->employment_status) {
                                        'permanent' => 'Pegawai Tetap',
                                        'contract' => 'Pegawai Kontrak',
                                        'intern' => 'Pegawai Magang',
                                        default => 'Belum Diisi',
                                    };
                                @endphp
                                <span
                                    class="badge badge-enterprise {{ $statusStyle }} border">{{ $statusLabel }}</span>
                            </td>
                            <td class="text-muted small"><i class="bi bi-calendar3 me-1"></i>
                                {{ $employee->join_date->format('d/m/Y') }}</td>
                            <td><span class="fw-medium text-dark">{{ $employee->tenure }}</span> <span
                                    class="text-muted small">Tahun</span></td>
                            <td class="pe-4 text-end">
                                <div class="d-flex justify-content-end gap-1">
                                    <a href="{{ route('employees.show', $employee) }}"
                                        class="btn btn-sm btn-light text-primary border" title="Detail Riwayat"><i
                                            class="bi bi-eye"></i></a>
                                    <a href="{{ route('employees.edit', $employee) }}"
                                        class="btn btn-sm btn-light text-warning border" title="Edit Profil"><i
                                            class="bi bi-pencil"></i></a>
                                    <a href="{{ route('employees.download-pdf', $employee) }}"
                                        class="btn btn-sm btn-light text-danger border" title="Download CV PDF"><i
                                            class="bi bi-file-earmark-pdf"></i></a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-5 text-center">
                                <div class="empty-state">
                                    <i class="bi bi-inbox d-block fs-2 text-muted mb-3 opacity-50"></i>
                                    <h6 class="text-muted fw-bold">Data Pegawai Kosong</h6>
                                    <p class="text-muted small">Coba sesuaikan filter atau tambahkan pegawai baru.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer border-top d-flex justify-content-between align-items-center bg-white px-4 py-3">
            <div class="small text-muted">
                @php
                    $employeeFrom = $employees->firstItem() ?? ($employees->count() > 0 ? 1 : 0);
                    $employeeTo = $employees->lastItem() ?? $employees->count();
                @endphp
                Menampilkan baris {{ $employeeFrom }} ke {{ $employeeTo }} dari total {{ $employees->total() }} pegawai
            </div>
            @if ($employees->hasPages())
                <div>
                    {{ $employees->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectAll = document.getElementById('selectAll');
            const rowCheckboxes = document.querySelectorAll('.row-checkbox');
            const bulkToolbar = document.getElementById('bulkActionToolbar');
            const selectedCount = document.getElementById('selectedCount');
            const bulkIdsContainer = document.getElementById('bulkIdsContainer');

            function updateBulkToolbar() {
                const checked = Array.from(rowCheckboxes).filter(cb => cb.checked);
                const count = checked.length;

                if (count > 0) {
                    bulkToolbar.classList.remove('d-none');
                    bulkToolbar.classList.add('d-flex');
                    selectedCount.textContent = count;

                    // Sync hidden inputs
                    bulkIdsContainer.innerHTML = '';
                    checked.forEach(cb => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'ids[]';
                        input.value = cb.value;
                        bulkIdsContainer.appendChild(input);
                    });
                } else {
                    bulkToolbar.classList.add('d-none');
                    bulkToolbar.classList.remove('d-flex');
                }
            }

            selectAll.addEventListener('change', function() {
                rowCheckboxes.forEach(cb => cb.checked = selectAll.checked);
                updateBulkToolbar();
            });

            rowCheckboxes.forEach(cb => {
                cb.addEventListener('change', function() {
                    if (!cb.checked) selectAll.checked = false;
                    if (Array.from(rowCheckboxes).every(c => c.checked)) selectAll.checked = true;
                    updateBulkToolbar();
                });
            });
        });
    </script>
@endpush
