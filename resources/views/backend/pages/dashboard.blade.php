@extends('backend.layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4>Dashboard</h4>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:inline;">
                            @csrf
                            <button class="btn btn-sm btn-outline-secondary">Logout</button>
                        </form>
                    </div>

                    <div class="card-body">
                        @php($user = Auth::user())
                        @php($roleName = $user?->loadMissing('role')?->role?->name)
                        <h5>Selamat datang, {{ Auth::user()?->username ?? 'User' }} -
                            {{ $roleName ?: 'role' }}!
                        </h5>
                        <p>Ini halaman dashboard. Tempat untuk menampilkan ringkasan, statistik, dan tautan navigasi.</p>

                        {{-- Contoh container sederhana --}}
                        <div class="mt-4">
                            <div class="row">
                                <div class="col-sm-6 mb-3">
                                    <div class="rounded border p-3">
                                        <strong>Total Karyawan</strong>
                                        <div class="display-6">--</div>
                                    </div>
                                </div>
                                <div class="col-sm-6 mb-3">
                                    <div class="rounded border p-3">
                                        <strong>Aktivitas Terbaru</strong>
                                        <div class="small text-muted">Belum ada</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
