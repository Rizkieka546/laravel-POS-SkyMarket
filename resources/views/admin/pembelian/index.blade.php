@extends('layouts.admin')

@section('content')
    <div class="container mx-auto p-8 bg-gray-50 min-h-screen">

        <x-notification />

        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-semibold text-gray-800">Daftar Pembelian</h2>
            <a href="{{ route('pembelian.create') }}"
                class="bg-teal-600 hover:bg-teal-700 text-white font-medium py-2 px-5 rounded-lg shadow-md transition duration-300">
                + Tambah Pembelian
            </a>
        </div>

        {{-- <div class="mb-4 space-x-2">
            <a href="{{ route('pembelian.export.excel') }}"
                class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">Export Excel</a>
            <a href="{{ route('pembelian.export.pdf') }}"
                class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition">Export PDF</a>
        </div> --}}

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-teal-500 text-white uppercase text-sm">
                        <th class="px-6 py-3 text-left">Kode Masuk</th>
                        <th class="px-6 py-3 text-left">Pemasok</th>
                        <th class="px-6 py-3 text-left">Total</th>
                        <th class="px-6 py-3 text-left">Tanggal Masuk</th>
                        <th class="px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($pembelian as $item)
                        <tr class="hover:bg-gray-100 transition duration-300">
                            <td class="px-6 py-4 text-gray-800">{{ $item->kode_masuk }}</td>
                            <td class="px-6 py-4">{{ $item->pemasok->nama_pemasok }}</td>
                            <td class="px-6 py-4">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                            <td class="px-6 py-4">{{ $item->tanggal_masuk }}</td>
                            <td class="px-6 py-4 flex justify-center space-x-2">
                                <a href="{{ route('pembelian.show', $item->id) }}"
                                    class="bg-gray-300 hover:bg-white hover:text-gray-300 text-white text-sm font-medium py-2 px-4 rounded-lg shadow-md transition duration-300">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="px-6 py-4">
                {{ $pembelian->links('vendor.pagination.tailwind') }}
            </div>
        </div>
    </div>
@endsection
