<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Data Volunteer</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: center; font-size: 12px; }
    </style>
</head>
<body>
    <h2 style="text-align: center;">Data Volunteer di LPKKSK UKDW</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>NIM</th>
                <th>Fakultas</th>
                <th>Jurusan</th>
                <th>Email</th>
                <th>Periode</th>
                <th>Divisi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($volunteer as $v)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $v->nama }}</td>
                    <td>{{ $v->nim }}</td>
                    <td>{{ $v->fakultas }}</td>
                    <td>{{ $v->jurusan }}</td>
                    <td>{{ $v->email }}</td>
                    <td>{{ $v->mulai_aktif }} - {{ $v->akhir_aktif }}</td>
                    <td>{{ $v->divisi->nama_divisi ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
