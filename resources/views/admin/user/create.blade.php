@extends('layouts.admin')

@section('content')
<div class="container mx-auto p-8 bg-gray-50 min-h-screen flex items-center justify-center">

    <form action="{{ route('user.store') }}" method="POST" class="bg-white p-8 rounded-lg shadow-md w-full max-w-lg">
        @csrf

        <h2 class="text-3xl font-semibold text-gray-800 text-center mb-6">Tambah User</h2>

        <div class="mb-4">
            <label for="name" class="block text-gray-700 font-medium mb-2">Nama</label>
            <input type="text" id="name" name="name"
                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-teal-500 focus:outline-none"
                value="{{ old('name') }}" required>
        </div>

        <div class="mb-4">
            <label for="email" class="block text-gray-700 font-medium mb-2">Email</label>
            <input type="email" id="email" name="email"
                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-teal-500 focus:outline-none"
                value="{{ old('email') }}" required>
        </div>

        <div class="mb-4">
            <label for="password" class="block text-gray-700 font-medium mb-2">Password</label>
            <input type="password" id="password" name="password"
                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-teal-500 focus:outline-none" required>
        </div>

        <div class="mb-4">
            <label for="password_confirmation" class="block text-gray-700 font-medium mb-2">Konfirmasi Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation"
                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-teal-500 focus:outline-none" required>
        </div>

        <div class="mb-6">
            <label for="role" class="block text-gray-700 font-medium mb-2">Role</label>
            <select id="role" name="role"
                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-teal-500 focus:outline-none" required>
                <option value="admin">Admin</option>
                <option value="kasir">Kasir</option>
                <option value="gudang">Gudang</option>
                <option value="manajer">Manajer</option>
            </select>
        </div>

        <div class="flex justify-between">
            <button type="submit"
                class="bg-teal-600 hover:bg-teal-700 text-white font-medium py-2 px-6 rounded-lg shadow-md transition duration-300">
                Simpan
            </button>
            <a href="{{ route('user.index') }}"
                class="bg-gray-500 hover:bg-gray-700 text-white font-medium py-2 px-6 rounded-lg shadow-md transition duration-300">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection