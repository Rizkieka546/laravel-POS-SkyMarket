@extends('layouts.admin')

@section('content')
<div class="flex justify-center items-center min-h-screen bg-gray-100 p-6">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-xl">
        <h2 class="text-3xl font-semibold text-gray-800 text-center mb-6">Edit Pemasok</h2>

        @if ($errors->any())
        <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 shadow-sm rounded-lg">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('pemasok.update', $pemasok->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Nama Pemasok</label>
                <input type="text" name="nama_pemasok" placeholder="Masukkan nama pemasok"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:outline-none"
                    value="{{ old('nama_pemasok', $pemasok->nama_pemasok) }}" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Alamat</label>
                <input type="text" name="alamat" placeholder="Masukkan alamat pemasok"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:outline-none"
                    value="{{ old('alamat', $pemasok->alamat) }}">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Telepon</label>
                <input type="text" name="telepon" placeholder="Masukkan nomor telepon"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:outline-none"
                    value="{{ old('telepon', $pemasok->telepon) }}">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Email</label>
                <input type="email" name="email" placeholder="Masukkan email pemasok"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:outline-none"
                    value="{{ old('email', $pemasok->email) }}">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Catatan</label>
                <textarea name="catatan" placeholder="Tambahkan catatan (opsional)"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:outline-none">{{ old('catatan', $pemasok->catatan) }}</textarea>
            </div>

            <div class="flex justify-between">
                <button type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-6 rounded-lg shadow-md transition duration-300">
                    Update
                </button>
                <a href="{{ route('pemasok.index') }}"
                    class="bg-gray-500 hover:bg-gray-700 text-white font-medium py-2 px-6 rounded-lg shadow-md transition duration-300">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection