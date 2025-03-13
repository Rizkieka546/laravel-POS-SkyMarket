@extends('layouts.admin')

@section('content')
<div class="p-6">
    <!-- Tombol Tambah dan Toggle -->
    <div class="flex justify-between items-center mb-6">
        <a href="{{ route('barang.create') }}"
            class="bg-teal-600 text-white px-5 py-2 rounded-lg shadow-md hover:bg-teal-700 transition">
            + Tambah Barang
        </a>
        <div class="flex space-x-2">
            <button id="showTable"
                class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg shadow-md hover:bg-gray-300 transition">Tabel</button>
            <button id="showGrid"
                class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg shadow-md hover:bg-gray-300 transition">Grid</button>
        </div>
    </div>

    <!-- Notifikasi -->
    @if(session('success'))
    <div class="p-4 mb-4 text-green-800 bg-green-100 border-l-4 border-green-500 rounded-lg">
        {{ session('success') }}
    </div>
    @endif

    <!-- Tampilan Tabel -->
    <div id="tableView" class="overflow-x-auto">
        <table class="w-full border-collapse shadow-lg rounded-lg overflow-hidden">
            <thead class="bg-teal-500 text-white text-left">
                <tr>
                    <th class="p-4">Gambar</th>
                    <th class="p-4">Kode Barang</th>
                    <th class="p-4">Nama Barang</th>
                    <th class="p-4">Satuan</th>
                    <th class="p-4">Harga Jual</th>
                    <th class="p-4">Stok</th>
                    <th class="p-4">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($barangs as $barang)
                <tr class="hover:bg-gray-100 transition">
                    <td class="p-4">
                        <img src="{{ asset($barang->gambar ? 'storage/' . $barang->gambar : 'images/default.jpg') }}"
                            class="w-14 h-14 object-cover rounded-lg border shadow">
                    </td>
                    <td class="p-4 text-gray-700">{{ $barang->kode_barang }}</td>
                    <td class="p-4 font-semibold text-gray-900">{{ $barang->nama_barang }}</td>
                    <td class="p-4 text-gray-600">{{ $barang->satuan ?? 'pcs' }}</td>
                    <td class="p-4 text-teal-700 font-semibold">Rp {{ number_format($barang->harga_jual, 0, ',', '.') }}
                    </td>
                    <td class="p-4 text-gray-700">{{ $barang->stok }}</td>
                    <td class="p-4">
                        <a href="{{ route('barang.edit', $barang->id) }}"
                            class="bg-[#66D2CE] text-white px-3 py-1 rounded-md shadow-md hover:bg-[#66D2CE] transition">
                            Edit
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Tampilan Grid -->
    <div id="gridView" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mt-6 hidden">
        @foreach($barangs as $barang)
        <div class="bg-white shadow-lg rounded-lg overflow-hidden transition hover:shadow-xl">
            <img src="{{ asset($barang->gambar ? 'storage/' . $barang->gambar : 'images/default.jpg') }}"
                class="w-full h-40 object-cover">
            <div class="p-5 text-center">
                <h5 class="text-lg font-semibold text-gray-900">{{ $barang->nama_barang }}</h5>
                <p class="text-sm text-gray-500">Satuan: {{ $barang->satuan ?? 'pcs' }}</p>
                <p class="text-teal-700 font-bold">Rp {{ number_format($barang->harga_jual, 0, ',', '.') }}</p>
                <p class="text-sm text-gray-500">Stok: {{ $barang->stok }}</p>
                <a href="{{ route('barang.edit', $barang->id) }}"
                    class="bg-yellow-500 text-white px-4 py-2 rounded-md shadow-md hover:bg-yellow-600 transition mt-3 inline-block">
                    Edit
                </a>
            </div>
        </div>
        @endforeach
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const tableView = document.getElementById("tableView");
    const gridView = document.getElementById("gridView");
    const showTable = document.getElementById("showTable");
    const showGrid = document.getElementById("showGrid");

    showTable.addEventListener("click", function() {
        tableView.classList.remove("hidden");
        gridView.classList.add("hidden");
    });

    showGrid.addEventListener("click", function() {
        gridView.classList.remove("hidden");
        tableView.classList.add("hidden");
    });
});
</script>

@endsection