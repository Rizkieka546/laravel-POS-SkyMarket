@extends('layouts.manajer')

@section('content')
<div class="max-w-6xl mx-auto bg-white p-6 rounded-lg shadow-lg">
    <h1 class="text-2xl font-bold mb-4">Detail Penjualan</h1>

    <div class="mb-4">
        <p><strong>No Faktur:</strong> {{ $penjualan->no_faktur }}</p>
        <p><strong>Tanggal:</strong> {{ $penjualan->tgl_faktur }}</p>
        <p><strong>Pelanggan:</strong> {{ $penjualan->pelanggan->nama ?? '-' }}</p>
        <p><strong>Total Bayar:</strong> Rp {{ number_format($penjualan->total_bayar, 0, ',', '.') }}</p>
        <p><strong>Status Pembayaran:</strong>
            <span
                class="px-2 py-1 text-xs font-bold rounded 
                    {{ $penjualan->status_pembayaran == 'lunas' ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800' }}">
                {{ ucfirst($penjualan->status_pembayaran) }}
            </span>
        </p>
    </div>

    <!-- Tabel Detail Barang -->
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border rounded-lg shadow-md">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-4 py-2 text-left">Nama Barang</th>
                    <th class="px-4 py-2 text-left">Harga Satuan</th>
                    <th class="px-4 py-2 text-left">Jumlah</th>
                    <th class="px-4 py-2 text-left">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($penjualan->detailPenjualan as $detail)
                <tr class="border-t">
                    <td class="px-4 py-2">{{ $detail->barang->nama_barang }}</td>
                    <td class="px-4 py-2">Rp {{ number_format($detail->harga_jual, 0, ',', '.') }}</td>
                    <td class="px-4 py-2">{{ $detail->jumlah }}</td>
                    <td class="px-4 py-2">Rp {{ number_format($detail->sub_total, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        <a href="{{ route('laporan.penjualan') }}" class="bg-gray-500 text-white px-4 py-2 rounded">Kembali</a>
    </div>
</div>
@endsection