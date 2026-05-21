<!DOCTYPE html>
<html>
<head>
    <title>Daftar Pegawai</title>
    <style>
        body { font-family: sans-serif; font-size: 10pt; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        h2 { text-align: center; }
        .header { margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Daftar Pegawai</h2>
        <p>Tanggal Cetak: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>NIP</th>
                <th>Nama</th>
                <th>Jabatan</th>
                <th>Tanggal Masuk</th>
                <th>Masa Kerja</th>
            </tr>
        </thead>
        <tbody>
            @foreach($employees as $index => $emp)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $emp->employee_code }}</td>
                <td>{{ $emp->full_name }}</td>
                <td>{{ ucfirst($emp->position) }}</td>
                <td>{{ $emp->join_date->format('d/m/Y') }}</td>
                <td>{{ $emp->tenure }} Tahun</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
