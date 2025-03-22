@extends('layouts.pelanggan')

@section('content')
    <div class="flex items-center justify-center min-h-screen bg-gray-100">
        <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
            <h2 class="text-2xl font-bold text-center text-teal-600 mb-6">Daftar Membership</h2>

            @if (session('success'))
                <div class="bg-green-100 text-green-700 p-3 rounded-md mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('membership.proses') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="block text-gray-700">Nama Lengkap</label>
                    <input type="text" value="{{ Auth::user()->name }}"
                        class="w-full p-2 border border-gray-300 rounded-lg bg-gray-200" readonly>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700">Email</label>
                    <input type="email" value="{{ Auth::user()->email }}"
                        class="w-full p-2 border border-gray-300 rounded-lg bg-gray-200" readonly>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700">Alamat</label>
                    <input type="text" name="alamat"
                        class="w-full p-2 border border-gray-300 rounded-lg focus:ring focus:ring-teal-300" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700">No Telepon</label>
                    <input type="text" name="no_telp"
                        class="w-full p-2 border border-gray-300 rounded-lg focus:ring focus:ring-teal-300" required>
                </div>

                <button type="submit"
                    class="w-full bg-teal-500 hover:bg-teal-600 text-white font-semibold py-2 rounded-lg">Daftar</button>
            </form>

            <p class="text-gray-600 text-sm text-center mt-4">
                Sudah menjadi anggota? <a href="{{ route('dashboard.pelanggan') }}"
                    class="text-teal-500 hover:underline">Kembali ke Dashboard</a>
            </p>
        </div>
    </div>
@endsection
