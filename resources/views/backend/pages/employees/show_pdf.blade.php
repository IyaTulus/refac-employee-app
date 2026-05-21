<!DOCTYPE html>
<html>
<head>
    <title>Profil Pegawai - {{ $employee->full_name }}</title>
    <style>
        body { font-family: sans-serif; font-size: 10pt; }
        .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 15px; margin-bottom: 20px; }
        .photo { float: right; width: 120px; height: 120px; border: 1px solid #ddd; margin-bottom: 
        15px; }
        .info-table { width: 100%; margin-bottom: 20px; }
        .info-table td { padding: 5px; vertical-align: top; }
        .section-title { background: #f2f2f2; padding: 5px 10px; font-weight: bold; margin-bottom: 10px; clear: both; }
        .edu-table { width: 100%; border-collapse: collapse; }
        .edu-table th, .edu-table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
    </style>
</head>
<body>
    <div class="header">
        <h1>PROFIL PEGAWAI</h1>
        <p>{{ $employee->employee_code }}</p>
    </div>

    @if($employee->photo)
    <img src="{{ public_path('storage/' . $employee->photo) }}" class="photo">
    @endif

    <div class="section-title">Informasi Pribadi</div>
    <table class="info-table">
        <tr><td width="150">Nama Lengkap</td><td>: {{ $employee->full_name }}</td></tr>
        <tr><td>NIP</td><td>: {{ $employee->employee_code }}</td></tr>
        <tr><td>Tempat, Tgl Lahir</td><td>: {{ $employee->birth_place }}, {{ $employee->birth_date->format('d M Y') }}</td></tr>
        <tr><td>Gender</td><td>: {{ $employee->gender === 'male' ? 'Laki-laki' : 'Perempuan' }}</td></tr>
        <tr><td>Status Kawin</td><td>: {{ ucfirst($employee->marital_status) }}</td></tr>
        <tr><td>Email</td><td>: {{ $employee->email }}</td></tr>
        <tr><td>Telepon</td><td>: {{ $employee->phone }}</td></tr>
        <tr><td>Alamat</td><td>: {{ $employee->address }}, Kec. {{ $employee->kecamatan }}, {{ $employee->kabupaten }}, {{ $employee->provinsi }}</td></tr>
    </table>

    <div class="section-title">Informasi Pekerjaan</div>
    <table class="info-table">
        <tr><td width="150">Jabatan</td><td>: {{ ucfirst($employee->position) }}</td></tr>
        <tr><td>Departemen</td><td>: {{ strtoupper($employee->department) }}</td></tr>
        <tr><td>Tanggal Masuk</td><td>: {{ $employee->join_date->format('d M Y') }}</td></tr>
        <tr><td>Masa Kerja</td><td>: {{ $employee->tenure }} Tahun</td></tr>
        <tr><td>Status</td><td>: {{ $employee->is_active ? 'Aktif' : 'Nonaktif' }}</td></tr>
    </table>

    <div class="section-title">Riwayat Pendidikan</div>
    <table class="edu-table">
        <thead>
            <tr><th>Jenjang</th><th>Institusi</th><th>Jurusan</th><th>Tahun</th></tr>
        </thead>
        <tbody>
            @foreach($employee->educations as $edu)
            <tr>
                <td>{{ $edu->level }}</td>
                <td>{{ $edu->institution }}</td>
                <td>{{ $edu->major ?? '-' }}</td>
                <td>{{ $edu->graduation_year }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
