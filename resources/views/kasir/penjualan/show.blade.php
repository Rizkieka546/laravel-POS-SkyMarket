@extends('layouts.kasir')

@section('content')
<div class="container mx-auto p-5">
    <div class="bg-white shadow-md rounded-lg p-6">
        <h2 class="text-xl font-semibold text-gray-700 mb-4">Detail Penjualan</h2>

        <table class="w-full border-collapse border border-gray-300">
            <tr class="border-b border-gray-300">
                <th class="text-left p-3">No Faktur</th>
                <td class="p-3">{{ $penjualan->no_faktur }}</td>
            </tr>
            <tr class="border-b border-gray-300">
                <th class="text-left p-3">Tanggal</th>
                <td class="p-3">{{ \Carbon\Carbon::parse($penjualan->tgl_faktur)->format('d-m-Y H:i') }}</td>
            </tr>
            <tr>
                <th class="text-left p-3">Total Bayar</th>
                <td class="p-3 font-bold text-green-600">Rp {{ number_format($penjualan->total_bayar, 0, ',', '.') }}
                </td>
            </tr>
        </table>

        <h4 class="mt-6 text-lg font-semibold text-gray-700">Detail Barang</h4>
        <table class="w-full mt-2 border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-200 text-gray-700">
                    <th class="p-3">Nama Barang</th>
                    <th class="p-3">Harga Jual</th>
                    <th class="p-3">Jumlah</th>
                    <th class="p-3">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($penjualan->detailPenjualan as $detail)
                <tr class="border-b border-gray-300">
                    <td class="p-3">{{ $detail->barang->nama_barang }}</td>
                    <td class="p-3">Rp {{ number_format($detail->harga_jual, 0, ',', '.') }}</td>
                    <td class="p-3">{{ $detail->jumlah }}</td>
                    <td class="p-3 text-right font-bold">Rp {{ number_format($detail->sub_total, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <a href="{{ route('penjualan.index') }}"
            class="inline-block mt-4 bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">Kembali</a>
    </div>
</div>
@endsection