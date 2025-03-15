@extends('layouts.kasir')

@section('content')
<div class="max-w-4xl mx-auto p-8">
    <h3 class="text-3xl font-extrabold mb-6 text-center text-gray-800">Halaman Pembayaran</h3>

    <div class="bg-white w-full shadow-lg rounded-xl p-6 border border-gray-300">
        <div class="text-gray-600 text-lg font-medium mb-4">
            <span class="font-bold text-gray-900">Tanggal Faktur: {{ $penjualan->tgl_faktur }}</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full border-collapse rounded-lg shadow-sm overflow-hidden">
                <thead>
                    <tr class="bg-teal-400 text-white text-left">
                        <th class="px-6 py-3">Barang</th>
                        <th class="px-6 py-3 text-center">Jumlah</th>
                        <th class="px-6 py-3 text-right">Harga</th>
                        <th class="px-6 py-3 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="bg-gray-50 divide-y divide-gray-200">
                    @foreach ($penjualan->detailPenjualan as $detail)
                    <tr>
                        <td class="px-6 py-4 text-gray-800 font-medium">{{ $detail->barang->nama_barang }}</td>
                        <td class="px-6 py-4 text-center text-gray-700">{{ $detail->jumlah }}</td>
                        <td class="px-6 py-4 text-right text-gray-700">
                            Rp{{ number_format($detail->harga_jual, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-right text-gray-700">
                            Rp{{ number_format($detail->sub_total, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <form action="{{ route('penjualan.prosesPembayaran', $penjualan->id) }}" method="POST" class="mt-6">
            @csrf
            <div class="mb-6">
                <label for="uang_diterima" class="block text-gray-800 font-semibold mb-2">Uang Diterima</label>
                <input type="number"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                    id="uang_diterima" name="uang_diterima" required>
            </div>
            <button type="submit"
                class="w-full bg-teal-400 text-white py-3 rounded-lg hover:from-blue-700 hover:to-green-700 transition duration-200 font-bold text-lg shadow-md">
                Bayar: Rp{{ number_format($penjualan->total_bayar, 0, ',', '.') }}
            </button>
        </form>
    </div>
</div>
@endsection