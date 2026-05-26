@extends('backend.layouts.admin')

@section('title', 'Setting Tunjangan')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark mb-1">Pengaturan Tunjangan Transport</h4>
            <p class="text-muted small mb-0">Atur tarif dasar dan profil perhitungan tunjangan transport pegawai.</p>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-6">
            <div class="card card-enterprise h-100 border-0 shadow-sm">
                <div class="card-header border-bottom-0 bg-white pb-0 pt-4">
                    <h6 class="fw-bold mb-0">Konfigurasi Tarif Dasar</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('transport-settings.store') }}" method="POST" novalidate>
                        @csrf
                        <div class="mb-4">
                            <label class="form-label text-muted small fw-semibold text-uppercase letter-spacing-1">Tarif per
                                KM (Rp)</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light text-muted border-end-0">Rp</span>
                                <input type="number" name="base_fare"
                                    class="form-control border-start-0 @error('base_fare') is-invalid @enderror ps-0"
                                    value="{{ old('base_fare', $setting ? $setting->base_fare : 0) }}" step="1"
                                    min="0">
                                @error('base_fare')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-text text-muted mt-2">
                                <i class="bi bi-info-circle me-1"></i> Tarif ini akan digunakan sebagai harga patokan per
                                kilometer untuk semua pegawai.
                            </div>
                        </div>

                        <button type="submit" class="btn btn-enterprise-primary w-100 py-2">Simpan Konfigurasi</button>
                    </form>
                </div>
                @if ($setting && $setting->updater && $setting->updated_at)
                    <div class="card-footer bg-light border-top small text-muted py-3">
                        Terakhir diperbarui oleh <span class="fw-medium text-dark">{{ $setting->updater->username }}</span>
                        pada {{ $setting->updated_at->format('d/m/Y H:i') }}
                    </div>
                @endif
            </div>
        </div>

        <div class="col-md-6">
            <div class="card card-enterprise h-100 bg-primary-subtle text-primary border-primary-subtle shadow-sm">
                <div class="card-body d-flex flex-column justify-content-center p-4">
                    <div class="mb-3">
                        <i class="bi bi-info-circle-fill fs-2"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-3">Informasi Perhitungan</h5>
                    <p class="text-dark mb-2">Tunjangan transport dihitung secara otomatis menggunakan rumus:</p>
                    <div
                        class="font-monospace small text-dark fw-bold mb-3 rounded border bg-white p-3 text-center shadow-sm">
                        Tunjangan = Tarif Dasar × Jarak (KM) × Jumlah Hari Masuk
                    </div>
                    <div class="d-flex text-dark small gap-2 rounded bg-white bg-opacity-50 p-2">
                        <i class="bi bi-exclamation-triangle"></i>
                        <span><strong>Catatan:</strong> Jarak akan dibulatkan ke atas jika desimal >= 0.5, dan maksimal
                            jarak yang dihitung adalah 25 KM.</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
