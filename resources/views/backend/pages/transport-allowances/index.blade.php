@extends('backend.layouts.admin')

@section('title', 'Kelola Tunjangan Transport')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark mb-1">Tunjangan Transport Pegawai</h4>
            <p class="text-muted small mb-0">Kalkulasi dan riwayat tunjangan transport bulanan pegawai.</p>
        </div>
        <a href="{{ route('transport-allowances.create') }}"
            class="btn btn-enterprise-primary d-flex align-items-center gap-2">
            <i class="bi bi-calculator"></i> <span>Hitung Tunjangan</span>
        </a>
    </div>

    <!-- Filter Bar -->
    <div class="card card-enterprise mb-4 border-0 shadow-sm">
        <div class="card-body p-3">
            <form action="{{ route('transport-allowances.index') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-light text-muted border-end-0"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control bg-light border-start-0 ps-0"
                            placeholder="Cari catatan, bulan, atau tahun..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="month" class="form-select bg-light">
                        <option value="">-- Pilih Bulan --</option>
                        @foreach (range(1, 12) as $m)
                            <option value="{{ $m }}" @selected(request('month') == $m)>
                                {{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="year" class="form-select bg-light">
                        <option value="">-- Pilih Tahun --</option>
                        @foreach (range(date('Y'), date('Y') - 5) as $y)
                            <option value="{{ $y }}" @selected(request('year', date('Y')) == $y)>{{ $y }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-dark px-4">Filter</button>
                </div>
                <div class="col-auto">
                    <a href="{{ route('transport-allowances.index') }}" class="btn btn-light border px-4">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- List -->
    <div class="card card-enterprise border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table-enterprise mb-0 table align-middle">
                <thead>
                    <tr>
                        <th class="ps-4" style="width: 50px;">No.</th>
                        <th>Pegawai</th>
                        <th>Periode</th>
                        <th>Detail (KM x Hari)</th>
                        <th>Subtotal (Fare)</th>
                        <th>Total Tunjangan</th>
                        <th>Status</th>
                        <th class="pe-4 text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($allowances as $index => $item)
                        <tr>
                            <td class="text-muted ps-4">{{ $allowances->firstItem() + $index }}</td>
                            <td>
                                <div class="fw-medium text-dark">{{ $item->employee->full_name }}</div>
                                <div class="small text-muted">{{ $item->employee->employee_code }}</div>
                            </td>
                            <td>
                                <span class="badge badge-enterprise bg-light text-dark border">
                                    <i class="bi bi-calendar3 me-1"></i> {{ date('M', mktime(0, 0, 0, $item->month, 1)) }}
                                    {{ $item->year }}
                                </span>
                            </td>
                            <td>
                                <span class="text-muted small">
                                    {{ (float) $item->distance_km }} KM <i class="bi bi-x text-muted"></i>
                                    {{ $item->work_days }} Hari
                                </span>
                            </td>
                            <td class="text-muted small">Rp {{ number_format($item->base_fare, 0, ',', '.') }}/hari</td>
                            <td class="fw-bold text-primary">Rp {{ number_format($item->total_amount, 0, ',', '.') }}</td>
                            <td>
                                @if ($item->total_amount > 0)
                                    <span
                                        class="badge badge-enterprise bg-success-subtle text-success border-success-subtle border">Layak</span>
                                @else
                                    <span
                                        class="badge badge-enterprise bg-danger-subtle text-danger border-danger-subtle mb-1 border"
                                        title="{{ $item->notes }}">Tidak Layak</span>
                                    <div style="font-size: 0.7rem;" class="text-danger fst-italic lh-sm">
                                        {{ $item->notes }}</div>
                                @endif
                            </td>
                            <td class="pe-4 text-end">
                                <form action="{{ route('transport-allowances.destroy', $item) }}" method="POST"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light text-danger border" title="Hapus"><i
                                            class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-5 text-center">
                                <div class="empty-state">
                                    <i class="bi bi-wallet2 d-block fs-2 text-muted mb-3 opacity-50"></i>
                                    <h6 class="text-muted fw-bold">Belum Ada Tunjangan</h6>
                                    <p class="text-muted small">Data tunjangan untuk periode ini tidak ditemukan.</p>
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
                    $allowanceFrom = $allowances->firstItem() ?? ($allowances->count() > 0 ? 1 : 0);
                    $allowanceTo = $allowances->lastItem() ?? $allowances->count();
                @endphp
                Menampilkan {{ $allowanceFrom }} sampai {{ $allowanceTo }} dari {{ $allowances->total() }} data
            </div>
            @if ($allowances->hasPages())
                <div>
                    {{ $allowances->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
@endsection
