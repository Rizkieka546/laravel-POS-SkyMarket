@extends('layouts.admin')

@section('content')
    <div class="bg-gray-100 flex justify-center items-center min-h-screen">
        <div class="bg-white p-6 rounded-lg shadow-md w-full max-w-3xl">
            <h2 class="text-xl font-bold mb-4 text-center">Tambah Barang</h2>

            <form action="{{ route('barang.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block mb-2">Kategori</label>
                        <select name="kategori_id" class="w-full p-2 border rounded mb-4">
                            @foreach ($kategoris as $kategori)
                                <option value="{{ $kategori->id }}">{{ $kategori->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block mb-2">Nama Barang</label>
                        <input type="text" name="nama_barang" class="w-full p-2 border rounded mb-4" required>
                    </div>
                    <div>
                        <label class="block mb-2">Satuan</label>
                        <select name="satuan" class="w-full p-2 border rounded mb-4" required>
                            <option value="">Pilih Satuan</option>
                            <option value="pcs">Pcs</option>
                            <option value="kg">Kg</option>
                            <option value="liter">Liter</option>
                            <option value="pack">Pack</option>
                        </select>
                    </div>
                    <div>
                        <label class="block mb-2">Stok Minimal</label>
                        <input type="number" name="stok_minimal" class="w-full p-2 border rounded mb-4">
                    </div>
                    <div>
                        <label class="block mb-2">Gambar</label>
                        <input type="file" name="gambar" class="w-full p-2 border rounded mb-4">
                    </div>
                </div>
                <div class="flex items-center mb-4">
                    <input type="checkbox" name="ditarik" class="mr-2">
                    <label>Barang Ditandai sebagai Ditarik</label>
                </div>
                <button type="submit" class="bg-blue-500 text-white p-2 rounded w-full">Simpan</button>
            </form>
        </div>
    </div>
@endsection
