@extends('layouts.admin')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-semibold mb-6 text-gray-800">Tambah Pengajuan</h1>

        <x-notification />

        <form action="{{ route('pengajuan.admin.store') }}" method="POST" class="bg-white p-8 rounded-lg shadow-lg">
            @csrf

            <div class="mb-6">
                <label for="nama_barang" class="block text-sm font-medium text-gray-700">Nama Barang</label>
                <input type="text" name="nama_barang" id="nama_barang"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500"
                    required>
            </div>

            <div class="mb-6">
                <label for="tanggal_pengajuan" class="block text-sm font-medium text-gray-700">Tanggal Pengajuan</label>
                <input type="date" name="tanggal_pengajuan" id="tanggal_pengajuan"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500"
                    required>
            </div>

            <div class="mb-6">
                <label for="qty" class="block text-sm font-medium text-gray-700">Jumlah (Qty)</label>
                <input type="number" name="qty" id="qty"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500"
                    required min="1">
            </div>

            <div class="mt-8">
                <button type="submit"
                    class="w-full bg-teal-600 hover:bg-teal-700 text-white py-3 px-6 rounded-lg shadow-md transition duration-300">
                    Simpan Pengajuan
                </button>
            </div>
        </form>
    </div>
@endsection
