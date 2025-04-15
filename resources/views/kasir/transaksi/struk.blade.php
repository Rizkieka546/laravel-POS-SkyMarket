<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pembelian</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 300px;
            margin: 0 auto;
            padding: 10px;
        }

        .center {
            text-align: center;
        }

        .text-left {
            text-align: left;
        }

        .bold {
            font-weight: bold;
        }

        .line {
            border-top: 1px solid #000;
            margin: 10px 0;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="center">
            <h3>TOKO ABC</h3>
            <p>Jl. Contoh Alamat</p>
            <p class="line"></p>
            <p class="bold">No Faktur: {{ $penjualan->no_faktur }}</p>
            <p>Tanggal: {{ $penjualan->tgl_faktur->format('d-m-Y H:i') }}</p>
            <p class="line"></p>

            @foreach ($penjualan->detailPenjualan as $item)
                <p class="text-left">{{ $item->barang->nama_barang }} ({{ $item->jumlah }} x
                    Rp{{ number_format($item->harga_jual, 0, ',', '.') }})</p>
                <p class="text-left">Subtotal: Rp{{ number_format($item->sub_total, 0, ',', '.') }}</p>
            @endforeach

            <p class="line"></p>
            <p class="bold">Total: Rp{{ number_format($penjualan->total_bayar, 0, ',', '.') }}</p>
            <p class="bold">Bayar: Rp{{ number_format($penjualan->total_bayar, 0, ',', '.') }}</p>
            <p class="bold">Kembalian: Rp0</p> <!-- Ganti dengan logika kembalian kalau perlu -->
            <p class="line"></p>
            <p class="center">TERIMA KASIH</p>
        </div>
    </div>

</body>

</html>
