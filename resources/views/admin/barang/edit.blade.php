@extends('layouts.admin')

@section('content')
    <div class="max-w-3xl mx-auto bg-white p-8 rounded-2xl shadow-xl border border-gray-200">
        <h2 class="text-3xl font-semibold mb-6 text-gray-800">Edit Barang</h2>

        <form action="{{ route('barang.update', $barang->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-gray-700 font-medium mb-1">Nama Barang</label>
                <input type="text" name="nama_barang" value="{{ old('nama_barang', $barang->nama_barang) }}"
                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 outline-none"
                    required>
                @error('nama_barang')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 font-medium mb-1">Satuan</label>
                    <select name="satuan"
                        class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400">
                        @php
                            $satuanList = ['pcs', 'kg', 'liter', 'gram', 'dus', 'karung'];
                        @endphp
                        @foreach ($satuanList as $satuan)
                            <option value="{{ $satuan }}"
                                {{ old('satuan', $barang->satuan) == $satuan ? 'selected' : '' }}>
                                {{ ucfirst($satuan) }}
                            </option>
                        @endforeach
                    </select>
                    @error('satuan')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-1">Kategori</label>
                    <select name="kategori_id"
                        class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400">
                        @foreach ($kategoris as $kategori)
                            <option value="{{ $kategori->id }}"
                                {{ old('kategori_id', $barang->kategori_id) == $kategori->id ? 'selected' : '' }}>
                                {{ $kategori->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                    @error('kategori_id')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-1">Harga Jual</label>
                <input type="number" name="harga_jual" value="{{ old('harga_jual', $barang->harga_jual) }}"
                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400">
                @error('harga_jual')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-1">Gambar Barang</label>
                <input type="file" name="gambar"
                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400">
                @if ($barang->gambar)
                    <div class="mt-4">
                        <img src="{{ asset('storage/' . $barang->gambar) }}" alt="{{ $barang->nama_barang }}"
                            class="w-40 h-40 object-cover rounded-lg shadow">
                    </div>
                @endif
                @error('gambar')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                class="w-full bg-blue-500 text-white py-3 rounded-lg text-lg font-semibold shadow-md hover:bg-blue-600 transition">Simpan
                Perubahan</button>
        </form>
    </div>
@endsection
