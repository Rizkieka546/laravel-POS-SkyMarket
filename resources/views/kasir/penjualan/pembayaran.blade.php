@extends('layouts.kasir')

@section('content')
<div class="p-8 w-full mx-auto ">
    <h3 class="text-4xl font-extrabold mb-8 text-center text-gray-800">Halaman Pembayaran</h3>

    <div class="bg-white w-full shadow-xl rounded-xl p-8 border border-gray-200">
        <div class="text-gray-600 text-lg font-medium mb-4"><span
                class="font-bold text-gray-800">{{ $penjualan->tgl_faktur }}</span></div>

        <table class="w-full border-collapse overflow-hidden shadow-md rounded-lg">
            <thead>
                <tr class="bg-teal-600 text-white text-left">
                    <th class="px-6 py-3">Barang</th>
                    <th class="px-6 py-3 text-center">Jumlah</th>
                    <th class="px-6 py-3 text-right">Harga</th>
                    <th class="px-6 py-3 text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody class="bg-gray-50">
                @foreach ($penjualan->detailPenjualan as $detail)
                <tr class="border-b border-gray-300">
                    <td class="px-6 py-4 text-gray-700">{{ $detail->barang->nama_barang }}</td>
                    <td class="px-6 py-4 text-center text-gray-700">{{ $detail->jumlah }}</td>
                    <td class="px-6 py-4 text-right text-gray-700">
                        Rp{{ number_format($detail->harga_jual, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 text-right text-gray-700">
                        Rp{{ number_format($detail->sub_total, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <form action="{{ route('penjualan.prosesPembayaran', $penjualan->id) }}" method="POST" class="mt-6">
            @csrf
            <div class="mb-6">
                <label for="uang_diterima" class="block text-gray-800 font-semibold mb-2">Uang Diterima</label>
                <input type="number"
                    class="w-full px-5 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500"
                    id="uang_diterima" name="uang_diterima" required>
            </div>
            <button type="submit"
                class="w-full bg-teal-600 text-white py-3 rounded-lg hover:bg-teal-800 transition duration-200 font-bold text-lg">
                Bayar: Rp{{ number_format($penjualan->total_bayar, 0, ',', '.') }}
            </button>
        </form>
    </div>
</div>
@endsection