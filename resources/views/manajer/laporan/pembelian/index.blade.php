@extends('layouts.manajer')

@section('content')
<div class="max-w-6xl mx-auto bg-white p-6 rounded-lg shadow-lg">
    <h1 class="text-2xl font-bold mb-4">Laporan Pembelian</h1>

    <!-- Filter Laporan -->
    <form method="GET" class="mb-4 flex gap-2">
        <input type="date" name="tanggal_mulai" class="border rounded px-2 py-1" placeholder="Tanggal Mulai">
        <input type="date" name="tanggal_selesai" class="border rounded px-2 py-1" placeholder="Tanggal Selesai">
        <select name="pemasok_id" class="border rounded px-2 py-1">
            <option value="">Semua Pemasok</option>
            @foreach ($pemasoks as $pemasok)
            <option value="{{ $pemasok->id }}">{{ $pemasok->nama }}</option>
            @endforeach
        </select>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Filter</button>
    </form>

    <!-- Tabel Pembelian -->
    <table class="min-w-full bg-white border rounded-lg shadow-md">
        <thead class="bg-gray-200">
            <tr>
                <th class="px-4 py-2">Kode Masuk</th>
                <th class="px-4 py-2">Tanggal</th>
                <th class="px-4 py-2">Pemasok</th>
                <th class="px-4 py-2">Total</th>
                <th class="px-4 py-2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pembelians as $pembelian)
            <tr class="border-t">
                <td class="px-4 py-2">{{ $pembelian->kode_masuk }}</td>
                <td class="px-4 py-2">{{ $pembelian->tanggal_masuk }}</td>
                <td class="px-4 py-2">{{ $pembelian->pemasok->nama_pemasok ?? '-' }}</td>
                <td class="px-4 py-2">Rp {{ number_format($pembelian->total, 0, ',', '.') }}</td>
                <td class="px-4 py-2">
                    <a href="{{ route('laporan.pembelian.show', $pembelian->id) }}"
                        class="bg-blue-500 text-white px-3 py-1 rounded text-sm">Detail</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</div>
@endsection