@extends('layouts.admin')

@section('content')
<div class="container mx-auto p-8 bg-gray-50 min-h-screen flex justify-center items-center">

    <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-2xl">
        <h2 class="text-3xl font-semibold text-gray-800 text-center mb-6">Tambah Pemasok</h2>

        <form action="{{ route('pemasok.store') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label class="block text-gray-700 font-medium mb-2">Nama Pemasok</label>
                <input type="text" name="nama_pemasok" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:outline-none">
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-2">Alamat</label>
                <input type="text" name="alamat"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:outline-none">
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-2">Telepon</label>
                <input type="text" name="telepon"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:outline-none">
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-2">Email</label>
                <input type="email" name="email"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:outline-none">
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-2">Catatan</label>
                <textarea name="catatan" rows="3"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:outline-none"></textarea>
            </div>

            <div class="flex justify-between">
                <button type="submit"
                    class="bg-teal-600 hover:bg-teal-700 text-white font-bold py-3 px-6 rounded-lg transition duration-300">
                    Simpan
                </button>
                <a href="{{ route('pemasok.index') }}"
                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-3 px-6 rounded-lg transition duration-300">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection