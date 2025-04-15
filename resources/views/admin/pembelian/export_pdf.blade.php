<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Export PDF</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 8px;
            font-size: 12px;
        }

        th {
            background-color: #eee;
        }
    </style>
</head>

<body>
    <h2>Data Pembelian</h2>
    <table>
        <thead>
            <tr>
                <th>Kode Masuk</th>
                <th>Pemasok</th>
                <th>Total</th>
                <th>Tanggal Masuk</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pembelian as $item)
                <tr>
                    <td>{{ $item->kode_masuk }}</td>
                    <td>{{ $item->pemasok->nama_pemasok }}</td>
                    <td>Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                    <td>{{ $item->tanggal_masuk }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
