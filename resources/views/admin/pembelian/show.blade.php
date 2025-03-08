@extends('layouts.admin')

@section('content')
<div class="container">
    <h2 class="mb-4">Detail Pembelian</h2>

    <table class="table">
        <tr>
            <th>Kode Masuk</th>
            <td>{{ $pembelian->kode_masuk }}</td>
        </tr>
        <tr>
            <th>Pemasok</th>
            <td>{{ $pembelian->pemasok->nama_pemasok }}</td>
        </tr>
        <tr>
            <th>Total</th>
            <td>Rp {{ number_format($pembelian->total, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <th>Tanggal Masuk</th>
            <td>{{ $pembelian->tanggal_masuk }}</td>
        </tr>
    </table>

    <h4>Detail Barang</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nama Barang</th>
                <th>Harga Beli</th>
                <th>Jumlah</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pembelian->detailPembelian as $detail)
            <tr>
                <td>{{ $detail->barang->nama_barang }}</td>
                <td>Rp {{ number_format($detail->harga_beli, 0, ',', '.') }}</td>
                <td>{{ $detail->jumlah }}</td>
                <td>Rp {{ number_format($detail->sub_total, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <a href="{{ route('pembelian.index') }}" class="btn btn-secondary">Kembali</a>
</div>
@endsection