@extends('layouts.admin')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-semibold mb-6 text-gray-800">Daftar Pengajuan</h1>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @php
            // Cek apakah ada data yang statusnya belum "terpenuhi"
            $adaAksi = $pengajuan->contains(fn($item) => $item->status !== 'terpenuhi');
        @endphp

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
                <thead class="bg-teal-500 text-white">
                    <tr>
                        <th class="py-3 px-6 text-left">Nama Pengaju</th>
                        <th class="py-3 px-6 text-left">Nama Barang</th>
                        <th class="py-3 px-6 text-left">Tanggal Pengajuan</th>
                        <th class="py-3 px-6 text-left">Qty</th>
                        <th class="py-3 px-6 text-left">Status</th>
                        @if ($adaAksi)
                            <th class="py-3 px-6 text-center">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pengajuan as $item)
                        <tr class="border-b hover:bg-gray-100">
                            <td class="py-3 px-6">{{ $item->nama_pengaju }}</td>
                            <td class="py-3 px-6">{{ $item->nama_barang }}</td>
                            <td class="py-3 px-6">{{ $item->tanggal_pengajuan }}</td>
                            <td class="py-3 px-6">{{ $item->qty }}</td>
                            <td class="py-3 px-6">
                                <span
                                    class="px-3 py-1 rounded-lg text-white 
                                    {{ $item->status == 'terpenuhi' ? 'bg-green-500' : 'bg-yellow-500' }}">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </td>
                            @if ($adaAksi)
                                <td class="py-3 px-6 text-center">
                                    @if ($item->status != 'terpenuhi')
                                        <form action="{{ route('pengajuan.terima', $item->id) }}" method="POST"
                                            class="status-form">
                                            @csrf
                                            @method('PUT')
                                            <label class="flex items-center cursor-pointer">
                                                <input type="checkbox" class="hidden status-toggle"
                                                    data-form-id="form-{{ $item->id }}"
                                                    {{ $item->status == 'terpenuhi' ? 'checked' : '' }}>
                                                <div class="w-12 h-6 bg-gray-300 rounded-full relative transition-all">
                                                    <div
                                                        class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full shadow-md transition-all 
                                                        {{ $item->status == 'terpenuhi' ? 'translate-x-6 bg-green-500' : '' }}">
                                                    </div>
                                                </div>
                                            </label>
                                        </form>
                                    @endif
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggles = document.querySelectorAll('.status-toggle');

            toggles.forEach(toggle => {
                toggle.addEventListener('change', function() {
                    this.closest('form').submit();
                });
            });
        });
    </script>
@endsection
