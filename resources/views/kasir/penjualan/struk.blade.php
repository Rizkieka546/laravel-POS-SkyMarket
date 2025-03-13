@extends('layouts.kasir')

@section('content')
<div
    class="w-full max-w-md mx-auto bg-white shadow-md rounded-lg p-6 text-center font-mono border border-gray-300 min-h-screen">
    <h3 class="text-2xl font-bold text-gray-800 mb-4 uppercase">SHOPNAME</h3>
    <p class="text-md text-gray-600">{{ date('D, d/m/Y h:i A', strtotime($penjualan->tgl_faktur)) }}</p>
    <hr class="my-3 border-gray-300">

    <div class="text-left text-md">
        @foreach ($penjualan->detailPenjualan as $index => $detail)
        <div class="flex justify-between py-2">
            <span>{{ $index + 1 }}. {{ $detail->barang->nama_barang }}</span>
            <span>Rp{{ number_format($detail->sub_total, 0, ',', '.') }}</span>
        </div>
        @endforeach
    </div>

    <hr class="my-3 border-gray-300">
    <div class="text-md font-semibold text-left">
        <div class="flex justify-between py-2">
            <span>Total</span>
            <span>Rp{{ number_format($penjualan->total_bayar, 0, ',', '.') }}</span>
        </div>
        <div class="flex justify-between py-2">
            <span>Uang Diterima</span>
            <span>Rp{{ number_format($uangDiterima, 0, ',', '.') }}</span>
        </div>
        <div class="flex justify-between py-2">
            <span>Kembalian</span>
            <span>Rp{{ number_format($kembalian, 0, ',', '.') }}</span>
        </div>
    </div>

    <hr class="my-3 border-gray-300">
    <p class="text-md text-gray-500">#TERIMAKASIH</p>

    <a href="{{ route('penjualan.index') }}"
        class="mt-6 inline-block bg-blue-600 text-white px-6 py-3 rounded-lg shadow-md hover:bg-blue-700 transition duration-200 font-semibold">
        Selesai
    </a>
</div>
@endsection