@extends('backend.layouts.admin')

@section('title', 'Kelola Tunjangan Transport')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark mb-1">Kalkulasi Tunjangan Transport</h4>
            <p class="text-muted small mb-0">Hitung otomatis tunjangan pegawai berdasarkan jarak dan jumlah hari kerja.</p>
        </div>
        <a href="{{ route('transport-allowances.index') }}" class="btn btn-light border px-4">Kembali</a>
    </div>

    <div class="row g-4">
        <!-- Kalkulator Form -->
        <div class="col-lg-8">
            <div class="card card-enterprise h-100 border-0 shadow-sm">
                <div class="card-header border-bottom-0 bg-white pb-0 pt-4">
                    <h6 class="fw-bold mb-0">Form Parameter Perhitungan</h6>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('transport-allowances.store') }}" method="POST">
                        @csrf

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-semibold text-uppercase letter-spacing-1">Pilih
                                    Pegawai</label>
                                <select name="employee_id" id="employee_id"
                                    class="form-select @error('employee_id') is-invalid @enderror" required>
                                    <option value="">-- Pilih Pegawai --</option>
                                    @foreach ($employees as $emp)
                                        <option value="{{ $emp->id }}" data-distance="{{ $emp->distance_km }}"
                                            data-status="{{ $emp->employment_status }}"
                                            {{ old('employee_id') == $emp->id ? 'selected' : '' }}>
                                            {{ $emp->employee_code }} - {{ $emp->full_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('employee_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label
                                    class="form-label text-muted small fw-semibold text-uppercase letter-spacing-1">Bulan</label>
                                <select name="month" class="form-select @error('month') is-invalid @enderror" required>
                                    @foreach (range(1, 12) as $m)
                                        <option value="{{ $m }}"
                                            {{ old('month', date('n')) == $m ? 'selected' : '' }}>
                                            {{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                                    @endforeach
                                </select>
                                @error('month')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label
                                    class="form-label text-muted small fw-semibold text-uppercase letter-spacing-1">Tahun</label>
                                <select name="year" class="form-select @error('year') is-invalid @enderror" required>
                                    @foreach (range(date('Y'), date('Y') - 5) as $y)
                                        <option value="{{ $y }}"
                                            {{ old('year', date('Y')) == $y ? 'selected' : '' }}>{{ $y }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('year')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label
                                    class="form-label text-muted small fw-semibold text-uppercase letter-spacing-1">Jumlah
                                    Hari Masuk</label>
                                <div class="input-group">
                                    <input type="number" name="work_days" id="work_days"
                                        class="form-control border-end-0 @error('work_days') is-invalid @enderror"
                                        value="{{ old('work_days') }}" min="0" max="31" required>
                                    <span class="input-group-text bg-light text-muted">Hari</span>
                                    @error('work_days')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mt-4 pt-1">
                                    <div class="form-check text-muted small">
                                        <input class="form-check-input" type="checkbox" id="confirmData" required>
                                        <label class="form-check-label" for="confirmData">
                                            Data kehadiran sudah valid dan terkonfirmasi
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="text-muted my-4 opacity-25">

                        <button type="submit" class="btn btn-enterprise-primary btn-lg w-100 fs-6 py-3">
                            Proses Hitung & Simpan Tunjangan
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Preview / Informasi Pegawai -->
        <div class="col-lg-4">
            <div class="card card-enterprise h-100 bg-primary-subtle text-primary border-primary-subtle border shadow-sm">
                <div class="card-body d-flex flex-column justify-content-center p-4 text-center">
                    <i class="bi bi-geo-alt-fill mb-3" style="font-size: 3rem;"></i>
                    <h5 class="fw-bold text-dark mb-3">Data Jarak Pegawai</h5>
                    <p class="text-dark small mb-4">
                        Jarak tempuh pegawai akan dimuat otomatis saat Anda memilih pegawai dari dropdown di samping. Jarak
                        ini digunakan untuk menentukan kelayakan tunjangan transport.
                    </p>

                    <div class="rounded border bg-white p-4 shadow-sm">
                        <div class="small fw-semibold text-muted text-uppercase letter-spacing-1 mb-1" id="empNamePreview">
                            PILIH PEGAWAI</div>
                        <div class="fs-1 fw-bold text-dark d-flex align-items-center justify-content-center gap-2">
                            <span id="distPreview">0</span> <span class="fs-5 text-muted fw-normal mt-2">KM</span>
                        </div>
                        <div class="mt-3">
                            <span class="badge rounded-pill bg-secondary-subtle text-secondary border"
                                id="statusPreview">Belum
                                Dihitung</span>
                        </div>
                        <div class="small text-muted lh-sm mt-2" id="reasonPreview">
                            Pilih pegawai dan isi jumlah hari kerja untuk melihat status kelayakan.
                        </div>
                    </div>

                    <div
                        class="small d-flex justify-content-center align-items-center w-100 text-dark mx-auto mt-4 gap-2 rounded bg-white bg-opacity-50 p-2">
                        <i class="bi bi-info-circle"></i> Tarif dasar: <strong>Rp
                            {{ number_format($baseFare, 0, ',', '.') }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const sel = document.getElementById('employee_id');
                const distPreview = document.getElementById('distPreview');
                const empNamePreview = document.getElementById('empNamePreview');
                const workDaysInput = document.getElementById('work_days');
                const statusPreview = document.getElementById('statusPreview');
                const reasonPreview = document.getElementById('reasonPreview');

                const evaluateStatus = () => {
                    const selectedOpt = sel.options[sel.selectedIndex];
                    const workDays = parseInt(workDaysInput.value || '0', 10);

                    if (!selectedOpt || !selectedOpt.value) {
                        distPreview.textContent = '0';
                        empNamePreview.textContent = 'PILIH PEGAWAI';
                        statusPreview.textContent = 'Belum Dihitung';
                        statusPreview.className = 'badge rounded-pill bg-secondary-subtle text-secondary border';
                        reasonPreview.textContent =
                            'Pilih pegawai dan isi jumlah hari kerja untuk melihat status kelayakan.';
                        return;
                    }

                    const dist = parseFloat(selectedOpt.getAttribute('data-distance') || '0');
                    const countedDistance = Math.min(Math.max(dist, 0), 25);
                    const employmentStatus = selectedOpt.getAttribute('data-status');
                    const text = selectedOpt.text.split('-')[1].trim();
                    const isPermanent = employmentStatus === 'permanent';
                    const meetsWorkDays = workDays >= 19;
                    const meetsDistance = countedDistance > 5;
                    const isEligible = isPermanent && meetsWorkDays && meetsDistance;

                    distPreview.textContent = countedDistance.toFixed(countedDistance % 1 === 0 ? 0 : 2);
                    empNamePreview.textContent = text.toUpperCase();

                    if (isEligible) {
                        statusPreview.textContent = 'Layak';
                        statusPreview.className =
                            'badge rounded-pill bg-success-subtle text-success border border-success-subtle';
                        reasonPreview.textContent = dist > 25 ?
                            'Layak. Jarak dibatasi maksimal 25 km.' :
                            'Layak. Pegawai tetap, minimal 19 hari kerja, dan jarak di atas 5 km.';
                    } else {
                        const reasons = [];
                        if (!isPermanent) reasons.push('pegawai harus tetap');
                        if (!meetsWorkDays) reasons.push('minimal 19 hari kerja');
                        if (!meetsDistance) reasons.push('jarak harus lebih dari 5 km');

                        statusPreview.textContent = 'Tidak Layak';
                        statusPreview.className =
                            'badge rounded-pill bg-danger-subtle text-danger border border-danger-subtle';
                        reasonPreview.textContent = `Tidak layak karena ${reasons.join(', ')}.`;
                    }
                };

                sel.addEventListener('change', function() {
                    evaluateStatus();
                });

                workDaysInput.addEventListener('input', evaluateStatus);

                // Trigger on load for older selection
                sel.dispatchEvent(new Event('change'));
            });
        </script>
    @endpush
@endsection
