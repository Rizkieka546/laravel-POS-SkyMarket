@extends('layouts.manajer')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-lg">
    <h1 class="text-2xl font-bold mb-4">Detail Pembelian</h1>

    <!-- Informasi Pembelian -->
    <div class="mb-4 p-4 bg-gray-100 rounded-lg">
        <p><strong>Kode Masuk:</strong> {{ $pembelian->kode_masuk }}</p>
        <p><strong>Tanggal Masuk:</strong> {{ $pembelian->tanggal_masuk }}</p>
        <p><strong>Pemasok:</strong> {{ $pembelian->pemasok->nama_pemasok ?? '-' }}</p>
        <p><strong>Total Pembelian:</strong> Rp {{ number_format($pembelian->total, 0, ',', '.') }}</p>
    </div>

    <!-- Tabel Detail Barang -->
    <h2 class="text-xl font-bold mb-2">Barang yang Dibeli</h2>
    <table class="min-w-full bg-white border rounded-lg shadow-md">
        <thead class="bg-gray-200">
            <tr>
                <th class="px-4 py-2">Nama Barang</th>
                <th class="px-4 py-2">Jumlah</th>
                <th class="px-4 py-2">Harga Satuan</th>
                <th class="px-4 py-2">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pembelian->detailPembelian as $detail)
            <tr class="border-t">
                <td class="px-4 py-2">{{ $detail->barang->nama_barang ?? '-' }}</td>
                <td class="px-4 py-2">{{ $detail->jumlah }}</td>
                <td class="px-4 py-2">Rp {{ number_format($detail->harga_beli, 0, ',', '.') }}</td>
                <td class="px-4 py-2">Rp {{ number_format($detail->jumlah * $detail->harga_beli, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Tombol Kembali -->
    <div class="mt-4">
        <a href="{{ route('laporan.pembelian') }}" class="bg-blue-500 text-white px-4 py-2 rounded">Kembali</a>
    </div>
</div>
@endsection