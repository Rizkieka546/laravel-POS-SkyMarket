@extends('layouts.admin')

@section('content')
    <div class="max-w-xl mx-auto bg-white p-8 rounded-2xl shadow-xl border border-gray-100 mt-10">
        <h1 class="text-2xl font-bold text-gray-800 mb-2">Edit Pengajuan</h1>
        <p class="text-sm text-gray-500 mb-6">Silakan perbarui detail barang yang diajukan di bawah ini.</p>


        <form action="{{ route('pengajuan.admin.update', $pengajuan->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Nama Barang -->
            <div class="mb-5">
                <label for="nama_barang" class="block text-sm font-medium text-gray-700 mb-1">Nama Barang</label>
                <input type="text" name="nama_barang" id="nama_barang"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500"
                    value="{{ old('nama_barang', $pengajuan->nama_barang) }}" required>
            </div>

            <!-- Tanggal Pengajuan -->
            <div class="mb-5">
                <label for="tanggal_pengajuan" class="block text-sm font-medium text-gray-700 mb-1">Tanggal
                    Pengajuan</label>
                <input type="date" name="tanggal_pengajuan" id="tanggal_pengajuan"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500"
                    value="{{ old('tanggal_pengajuan', $pengajuan->tanggal_pengajuan) }}" required>
            </div>

            <!-- Qty -->
            <div class="mb-6">
                <label for="qty" class="block text-sm font-medium text-gray-700 mb-1">Jumlah (Qty)</label>
                <input type="number" name="qty" id="qty" min="1"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500"
                    value="{{ old('qty', $pengajuan->qty) }}" required>
            </div>

            <!-- Tombol Submit -->
            <button type="submit"
                class="w-full bg-teal-600 hover:bg-teal-700 transition text-white font-semibold py-2 px-4 rounded-lg">
                Simpan Perubahan
            </button>
        </form>
    </div>
@endsection
