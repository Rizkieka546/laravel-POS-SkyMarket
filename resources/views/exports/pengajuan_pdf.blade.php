<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pengajuan Barang</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #04AA6D;
            color: white;
        }
    </style>
</head>

<body>
    <h2 style="text-align: center;">Daftar Pengajuan Barang</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Pengaju</th>
                <th>Nama Barang</th>
                <th>Tanggal Pengajuan</th>
                <th>Jumlah</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pengajuans as $key => $pengajuan)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $pengajuan->nama_pengaju }}</td>
                    <td>{{ $pengajuan->nama_barang }}</td>
                    <td>{{ $pengajuan->tanggal_pengajuan }}</td>
                    <td>{{ $pengajuan->qty }}</td>
                    <td>{{ ucfirst($pengajuan->status) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
