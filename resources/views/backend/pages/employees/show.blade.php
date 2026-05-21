@extends('backend.layouts.admin')

@section('title', 'Kelola Pengguna')

@section('content')
    <div class="container pb-5">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('employees.index') }}">Daftar Pegawai</a></li>
                <li class="breadcrumb-item active">Detail Profil</li>
            </ol>
        </nav>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Profil Pegawai: {{ $employee->full_name }}</h1>
            <div class="d-flex gap-2">
                <a href="{{ route('employees.edit', $employee) }}" class="btn btn-warning"><i class="bi bi-pencil"></i>
                    Edit</a>
                <a href="{{ route('employees.download-pdf', $employee) }}" class="btn btn-danger"><i
                        class="bi bi-file-earmark-pdf"></i> Download PDF</a>
            </div>
        </div>

        <div class="row g-4">
            <!-- Sidebar: Photo & Summary -->
            <div class="col-lg-4">
                <div class="card mb-4 border-0 shadow-sm">
                    <div class="card-body p-4 text-center">
                        <img src="{{ $employee->photo ? asset('storage/' . $employee->photo) : 'https://via.placeholder.com/200x200?text=No+Photo' }}"
                            class="img-thumbnail rounded-circle mb-3"
                            style="width: 150px; height: 150px; object-fit: cover;">
                        <h5 class="fw-bold mb-1">{{ $employee->full_name }}</h5>
                        <p class="text-muted small mb-3">{{ $employee->employee_code }}</p>
                        <span class="badge {{ $employee->is_active ? 'bg-success' : 'bg-danger' }} rounded-pill px-3">
                            {{ $employee->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-header border-0 bg-white py-3">
                        <h6 class="fw-bold mb-0">Kontak & Lokasi</h6>
                    </div>
                    <ul class="list-group list-group-flush border-top">
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="small text-muted">Email</span>
                            <span class="small">{{ $employee->email }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="small text-muted">Telepon</span>
                            <span class="small">{{ $employee->phone }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="small text-muted">Kecamatan</span>
                            <span class="small">{{ $employee->kecamatan }}</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Basic Info Card -->
                <div class="card mb-4 border-0 shadow-sm">
                    <div class="card-header border-0 bg-white py-3">
                        <h6 class="fw-bold mb-0">Detail Informasi</h6>
                    </div>
                    <div class="card-body border-top p-4">
                        <div class="row g-3">
                            <div class="col-6 col-md-4">
                                <p class="small text-muted mb-1">Tempat, Tanggal Lahir</p>
                                <p class="fw-bold">{{ $employee->birth_place }},
                                    {{ $employee->birth_date->format('d/m/Y') }}</p>
                            </div>
                            <div class="col-6 col-md-4">
                                <p class="small text-muted mb-1">Usia</p>
                                <p class="fw-bold">{{ $employee->age }} Tahun</p>
                            </div>
                            <div class="col-6 col-md-4">
                                <p class="small text-muted mb-1">Gender</p>
                                <p class="fw-bold">{{ $employee->gender === 'male' ? 'Laki-laki' : 'Perempuan' }}</p>
                            </div>
                            <div class="col-6 col-md-4">
                                <p class="small text-muted mb-1">Status Kawin</p>
                                <p class="fw-bold text-capitalize">{{ $employee->marital_status }}</p>
                            </div>
                            <div class="col-6 col-md-4">
                                <p class="small text-muted mb-1">Jumlah Anak</p>
                                <p class="fw-bold">{{ $employee->children_count }}</p>
                            </div>
                            <div class="col-12">
                                <p class="small text-muted mb-1">Alamat Lengkap</p>
                                <p class="fw-bold">{{ $employee->address }}, Kec. {{ $employee->kecamatan }},
                                    {{ $employee->kabupaten }}, Prov. {{ $employee->provinsi }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Job Info Card -->
                <div class="card mb-4 border-0 shadow-sm">
                    <div class="card-header border-0 bg-white py-3">
                        <h6 class="fw-bold mb-0">Data Pekerjaan</h6>
                    </div>
                    <div class="card-body border-top p-4">
                        <div class="row g-3">
                            <div class="col-6 col-md-4">
                                <p class="small text-muted mb-1">Jabatan</p>
                                <p class="fw-bold text-capitalize">{{ $employee->position }}</p>
                            </div>
                            <div class="col-6 col-md-4">
                                <p class="small text-muted mb-1">Departemen</p>
                                <p class="fw-bold text-uppercase">{{ $employee->department }}</p>
                            </div>
                            <div class="col-6 col-md-4">
                                <p class="small text-muted mb-1">Tanggal Masuk</p>
                                <p class="fw-bold">{{ $employee->join_date->format('d/m/Y') }}</p>
                            </div>
                            <div class="col-6 col-md-4">
                                <p class="small text-muted mb-1">Masa Kerja</p>
                                <p class="fw-bold">{{ $employee->tenure }} Tahun</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Education Card -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header border-0 bg-white py-3">
                        <h6 class="fw-bold mb-0">Riwayat Pendidikan</h6>
                    </div>
                    <div class="card-body border-top p-0">
                        <div class="table-responsive">
                            <table class="table-hover mb-0 table align-middle">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4">Jenjang</th>
                                        <th>Institusi</th>
                                        <th>Jurusan</th>
                                        <th class="pe-4 text-end">Tahun Lulus</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($employee->educations as $edu)
                                        <tr>
                                            <td class="fw-bold ps-4">{{ $edu->level }}</td>
                                            <td>{{ $edu->institution }}</td>
                                            <td>{{ $edu->major ?? '-' }}</td>
                                            <td class="pe-4 text-end">{{ $edu->graduation_year }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-muted small py-4 text-center italic">Belum ada
                                                riwayat pendidikan.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
