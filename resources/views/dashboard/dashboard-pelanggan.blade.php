@extends('layouts.pelanggan')

@section('content')
    <div class="container mx-auto p-6">
        <x-notification />

        <h1 class="text-3xl font-bold text-center text-gray-800 mb-8">Daftar Barang</h1>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach ($barang as $item)
                <div class="bg-white p-4 rounded-xl shadow-lg transition-transform transform hover:scale-105">
                    <div class="w-full h-40 bg-gray-200 rounded-lg overflow-hidden">
                        <img src="{{ asset($item->gambar ? 'storage/' . $item->gambar : 'images/default.jpg') }}"
                            class="w-full h-full object-cover">
                    </div>
                    <h2 class="text-lg font-semibold mt-3 text-gray-800">{{ $item->nama_barang }}</h2>
                    <p class="text-gray-600 text-sm">Stok: <span class="font-medium">{{ $item->stok }}</span></p>
                    <p class="text-gray-900 font-bold text-lg">Rp {{ number_format($item->harga_jual, 0, ',', '.') }}</p>
                </div>
            @endforeach
        </div>
    </div>
@endsection
