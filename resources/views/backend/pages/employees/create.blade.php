@extends('backend.layouts.admin')

@section('title', 'Kelola Pengguna')

@section('content')
    <div class="container pb-5">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('employees.index') }}">Daftar Pegawai</a></li>
                <li class="breadcrumb-item active">Tambah Data Baru</li>
            </ol>
        </nav>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Tambah Pegawai Baru</h1>
        </div>

        <form action="{{ route('employees.store') }}" method="POST" enctype="multipart/form-data" novalidate>
            @include('backend.pages.employees._form')
        </form>
    </div>
@endsection
