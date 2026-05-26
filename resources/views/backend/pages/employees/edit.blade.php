@extends('backend.layouts.admin')

@section('title', 'Kelola Pengguna')
@section('content')
    <div class="container pb-5">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('employees.index') }}">Daftar Pegawai</a></li>
                <li class="breadcrumb-item active">Edit Data</li>
            </ol>
        </nav>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Edit Pegawai: {{ $employee->full_name }}</h1>
        </div>

        <form action="{{ route('employees.update', $employee) }}" method="POST" enctype="multipart/form-data" novalidate>
            @method('PUT')
            @include('backend.pages.employees._form', ['isEdit' => true])
        </form>
    </div>
@endsection
