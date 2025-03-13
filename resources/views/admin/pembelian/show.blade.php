@extends('layouts.admin')

@section('content')
<div class="container mx-auto p-8 bg-gray-50 min-h-screen">
    <div class="bg-white shadow-md rounded-lg overflow-hidden p-6">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6">Detail Pembelian</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="bg-gray-100 p-4 rounded-lg">
                <h3 class="text-gray-700 font-medium">Kode Masuk</h3>
                <p class="text-gray-900 font-semibold">{{ $pembelian->kode_masuk }}</p>
            </div>
            <div class="bg-gray-100 p-4 rounded-lg">
                <h3 class="text-gray-700 font-medium">Pemasok</h3>
                <p class="text-gray-900 font-semibold">{{ $pembelian->pemasok->nama_pemasok }}</p>
            </div>
            <div class="bg-gray-100 p-4 rounded-lg">
                <h3 class="text-gray-700 font-medium">Total</h3>
                <p class="text-gray-900 font-semibold">Rp {{ number_format($pembelian->total, 0, ',', '.') }}</p>
            </div>
            <div class="bg-gray-100 p-4 rounded-lg">
                <h3 class="text-gray-700 font-medium">Tanggal Masuk</h3>
                <p class="text-gray-900 font-semibold">{{ $pembelian->tanggal_masuk }}</p>
            </div>
        </div>

        <h4 class="text-lg font-semibold text-gray-800 mb-4">Detail Barang</h4>
        <div class="overflow-x-auto">
            <table class="w-full border-collapse shadow-md rounded-lg overflow-hidden">
                <thead>
                    <tr class="bg-teal-500 text-white uppercase text-sm">
                        <th class="px-6 py-3 text-left">Nama Barang</th>
                        <th class="px-6 py-3 text-left">Satuan</th>
                        <th class="px-6 py-3 text-left">Harga Beli</th>
                        <th class="px-6 py-3 text-left">Jumlah</th>
                        <th class="px-6 py-3 text-left">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($pembelian->detailPembelian as $detail)
                    <tr class="hover:bg-gray-100 transition duration-300">
                        <td class="px-6 py-4">{{ $detail->barang->nama_barang }}</td>
                        <td class="px-6 py-4">{{ $detail->barang->satuan }}</td>
                        <td class="px-6 py-4">Rp {{ number_format($detail->harga_beli, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-center">{{ $detail->jumlah }}</td>
                        <td class="px-6 py-4">Rp {{ number_format($detail->sub_total, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6 flex justify-end">
            <a href="{{ route('pembelian.index') }}"
                class="bg-teal-600 hover:bg-teal-700 text-white font-medium py-2 px-5 rounded-lg shadow-md transition duration-300">Kembali</a>
        </div>
    </div>
</div>
@endsection