@extends('layouts.admin')

@section('content')
<div class="container mx-auto p-8 bg-gray-50 min-h-screen flex justify-center items-center">
    <div class="bg-white shadow-lg rounded-lg w-full max-w-lg p-8 border border-gray-200">
        <h2 class="text-3xl font-semibold text-gray-800 text-center mb-6">Edit User</h2>

        <form action="{{ route('user.update', $user->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block text-gray-700 font-medium">Nama</label>
                <input type="text" id="name" name="name"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-teal-500 focus:outline-none"
                    value="{{ old('name', $user->name) }}" required>
            </div>

            <div>
                <label for="email" class="block text-gray-700 font-medium">Email</label>
                <input type="email" id="email" name="email"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-teal-500 focus:outline-none"
                    value="{{ old('email', $user->email) }}" required>
            </div>

            <div>
                <label for="password" class="block text-gray-700 font-medium">Password (Kosongkan jika tidak ingin
                    mengubah)</label>
                <input type="password" id="password" name="password"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-teal-500 focus:outline-none">
            </div>

            <div>
                <label for="password_confirmation" class="block text-gray-700 font-medium">Konfirmasi Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-teal-500 focus:outline-none">
            </div>

            <div>
                <label for="role" class="block text-gray-700 font-medium">Role</label>
                <select id="role" name="role"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-teal-500 focus:outline-none"
                    required>
                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User</option>
                    <option value="gudang" {{ $user->role == 'gudang' ? 'selected' : '' }}>Gudang</option>
                    <option value="manajer" {{ $user->role == 'manajer' ? 'selected' : '' }}>Manajer</option>
                </select>
            </div>

            <div class="flex justify-between">
                <button type="submit"
                    class="bg-teal-600 hover:bg-teal-700 text-white font-bold py-2 px-6 rounded-lg shadow-md transition duration-300">
                    Update
                </button>
                <a href="{{ route('user.index') }}"
                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded-lg shadow-md transition duration-300">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection