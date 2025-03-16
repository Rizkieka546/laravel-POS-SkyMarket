@extends('layouts.manajer')

@section('content')
<div class="max-w-6xl mx-auto bg-white p-6 rounded-lg shadow-lg">
    <h1 class="text-2xl font-bold mb-4">Laporan Stok Barang</h1>

    <!-- Filter Form -->
    <form action="{{ route('laporan.stok') }}" method="GET" class="mb-4 flex gap-4">
        <select name="kategori" class="border p-2 rounded">
            <option value="">Semua Kategori</option>
            @foreach ($kategori as $kat)
            <option value="{{ $kat->id }}" {{ request('kategori') == $kat->id ? 'selected' : '' }}>
                {{ $kat->nama_kategori }}
            </option>
            @endforeach
        </select>

        <select name="stok" class="border p-2 rounded">
            <option value="">Semua Stok</option>
            <option value="hampir_habis" {{ request('stok') == 'hampir_habis' ? 'selected' : '' }}>Hampir Habis</option>
            <option value="tidak_laku" {{ request('stok') == 'tidak_laku' ? 'selected' : '' }}>Tidak Laku</option>
        </select>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Filter</button>
    </form>

    <!-- Tabel Stok Barang -->
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border rounded-lg shadow-md">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-4 py-2 text-left">Kode Barang</th>
                    <th class="px-4 py-2 text-left">Nama Barang</th>
                    <th class="px-4 py-2 text-left">Stok Saat Ini</th>
                    <th class="px-4 py-2 text-left">Stok Minimal</th>
                    <th class="px-4 py-2 text-left">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($barang as $item)
                <tr class="border-t">
                    <td class="px-4 py-2">{{ $item->kode_barang }}</td>
                    <td class="px-4 py-2">{{ $item->nama_barang }}</td>
                    <td class="px-4 py-2">{{ $item->stok }}</td>
                    <td class="px-4 py-2">{{ $item->stok_minimal }}</td>
                    <td class="px-4 py-2">
                        @if ($item->stok == 0)
                        <span class="px-2 py-1 text-xs font-bold rounded bg-red-200 text-red-800">Habis</span>
                        @elseif ($item->stok <= $item->stok_minimal)
                            <span
                                class="px-2 py-1 text-xs font-bold rounded bg-yellow-200 text-yellow-800">Menipis</span>
                            @else
                            <span class="px-2 py-1 text-xs font-bold rounded bg-green-200 text-green-800">Aman</span>
                            @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection