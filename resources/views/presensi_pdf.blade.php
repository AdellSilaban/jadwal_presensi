<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Hadir Volunteer</title>
    <style>
        @page {
            size: A4 landscape; /* Orientasi horizontal */
            margin: 20mm;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
        }

        .header {
            text-align: center; /* Rata tengah untuk bagian header */
            margin-bottom: 30px;
        }

        .header h1 {
            margin: 0;
            font-size: 18px;
            text-align: center; /* Hanya judul yang tengah */
        }

        .header p {
            margin: 5px 0;
            font-size: 14px;
            text-align: left; /* Lainnya rata kiri */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            margin-bottom: 20px;
        }

        table th, table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
        }

        table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .footer {
            margin-top: 30px;
            text-align: right; /* Posisikan di kanan */
        }

        /* Untuk membuat bagian total jam kerja ke pojok kanan */
        .total-hours {
            text-align: right;
            margin-right: 20px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>DAFTAR HADIR VOLUNTEER</h1>
        <br>
        <p>Pusat/Unit: Lembaga Pelayanan Kerohanian, Konseling dan Spiritualitas Kampus (LPKKSK)</p>
        <p>Nama Volunteer: {{ $volunteer->nama }}</p>
        <p>NIM: {{ $volunteer->nim }}</p>
        <p>Fakultas/Program Studi: {{ $volunteer->fakultas }} / {{ $volunteer->jurusan }}</p>
        <p>Bulan: {{ \Carbon\Carbon::parse($presensi->first()->tanggal)->format('F Y') }}</p>
        <p>Divisi: {{ $volunteer->divisi->nama_divisi }}</p>
        <p>Bank/No Rekening: {{ $volunteer->no_rek_vlt}}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Hari/Tanggal</th>
                <th>Jam Datang</th>
                <th>Jam Pulang</th>
                <th>Deskripsi Tugas</th>
                <th>Jumlah Jam</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach ($presensi as $p)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ \Carbon\Carbon::parse($p->tanggal)->format('l, d F Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($p->check_in)->format('H:i') }}</td>
                    <td>{{ \Carbon\Carbon::parse($p->check_out)->format('H:i') }}</td>
                    <td>{{ $p->desk_tgs ?? '-' }}</td>
                    <td>
                        @if ($p->check_in && $p->check_out)
                            {{ \Carbon\Carbon::parse($p->check_in)->diffInHours($p->check_out) }} Jam
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer total-hours">
        <p>Total Jam Kerja: {{ $totalHours }} Jam</p>
    </div>

</body>
</html>
