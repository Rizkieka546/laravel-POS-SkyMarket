<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Absensi Kerja</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px 8px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>

    <h2>Laporan Absensi Kerja</h2>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Karyawan</th>
                <th>Tanggal Masuk</th>
                <th>Status</th>
                <th>Waktu Masuk</th>
                <th>Waktu Kerja Selesai</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($absensis as $index => $absensi)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $absensi->nama_karyawan }}</td>
                    <td>{{ \Carbon\Carbon::parse($absensi->tanggal_masuk)->format('d-m-Y') }}</td>
                    <td>{{ ucfirst($absensi->status) }}</td>
                    <td>{{ $absensi->waktu_masuk }}</td>
                    <td>{{ $absensi->waktu_kerja_selesai }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
