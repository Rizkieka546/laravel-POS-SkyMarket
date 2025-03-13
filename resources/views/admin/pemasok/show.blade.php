@extends('layouts.admin')

@section('content')
<div class="container mx-auto p-8 bg-gray-50 min-h-screen flex justify-center items-center">
    <div class="bg-white shadow-md rounded-lg p-8 w-full max-w-2xl">

        <h2 class="text-3xl font-semibold text-gray-800 mb-6 text-center">Detail Pemasok</h2>

        <div class="bg-gray-100 rounded-lg overflow-hidden">
            <table class="w-full">
                <tbody>
                    <tr class="border-b border-gray-200">
                        <th class="px-6 py-4 text-left text-gray-600">Nama</th>
                        <td class="px-6 py-4 text-gray-800">{{ $pemasok->nama_pemasok }}</td>
                    </tr>
                    <tr class="border-b border-gray-200">
                        <th class="px-6 py-4 text-left text-gray-600">Alamat</th>
                        <td class="px-6 py-4 text-gray-800">{{ $pemasok->alamat }}</td>
                    </tr>
                    <tr class="border-b border-gray-200">
                        <th class="px-6 py-4 text-left text-gray-600">Telepon</th>
                        <td class="px-6 py-4 text-gray-800">{{ $pemasok->telepon }}</td>
                    </tr>
                    <tr class="border-b border-gray-200">
                        <th class="px-6 py-4 text-left text-gray-600">Email</th>
                        <td class="px-6 py-4 text-gray-800">{{ $pemasok->email }}</td>
                    </tr>
                    <tr>
                        <th class="px-6 py-4 text-left text-gray-600">Catatan</th>
                        <td class="px-6 py-4 text-gray-800">{{ $pemasok->catatan }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="mt-6 text-center">
            <a href="{{ route('pemasok.index') }}"
                class="bg-teal-600 hover:bg-teal-700 text-white font-medium py-2 px-6 rounded-lg shadow-md transition duration-300">
                Kembali
            </a>
        </div>
    </div>
</div>
@endsection