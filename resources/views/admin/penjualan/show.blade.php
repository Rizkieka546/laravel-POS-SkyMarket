@extends('layouts.kasir')

@section('content')
<div class="container">
    <h2 class="mb-4">Detail Transaksi</h2>

    <table class="table">
        <tr>
            <th>No Faktur</th>
            <td>{{ $penjualan->no_faktur }}</td>
        </tr>
        <tr>
            <th>Tanggal</th>
            <td>{{ \Carbon\Carbon::parse($penjualan->tgl_faktur)->format('d-m-Y H:i') }}</td>
        </tr>
        <tr>
            <th>Pelanggan</th>
            <td>{{ $penjualan->pelanggan->nama ?? '-' }}</td>
        </tr>
        <tr>
            <th>Total Bayar</th>
            <td>Rp {{ number_format($penjualan->total_bayar, 0, ',', '.') }}</td>
        </tr>
    </table>

    <h4 class="mt-4">Detail Barang</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nama Barang</th>
                <th>Harga Jual</th>
                <th>Jumlah</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($penjualan->detailPenjualan as $detail)
            <tr>
                <td>{{ $detail->barang->nama_barang }}</td>
                <td>Rp {{ number_format($detail->harga_jual, 0, ',', '.') }}</td>
                <td>{{ $detail->jumlah }}</td>
                <td>Rp {{ number_format($detail->sub_total, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <a href="{{ route('penjualan.index') }}" class="btn btn-secondary mt-3">Kembali</a>
</div>
@endsection