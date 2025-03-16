@extends('layouts.manajer')

@section('content')
<div class="max-w-6xl mx-auto bg-white p-6 rounded-lg shadow-lg">
    <h1 class="text-2xl font-bold mb-4">Laporan Penjualan</h1>

    <!-- Form Filter -->
    <form action="{{ route('laporan.penjualan') }}" method="GET" class="mb-4 flex flex-wrap gap-4">
        <div class="flex flex-col">
            <label for="tanggal_mulai" class="text-sm font-semibold">Tanggal Mulai</label>
            <input type="date" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}"
                class="border p-2 rounded w-40">
        </div>

        <div class="flex flex-col">
            <label for="tanggal_selesai" class="text-sm font-semibold">Tanggal Selesai</label>
            <input type="date" name="tanggal_selesai" value="{{ request('tanggal_selesai') }}"
                class="border p-2 rounded w-40">
        </div>

        <div class="flex flex-col">
            <label for="status_pembayaran" class="text-sm font-semibold">Status Pembayaran</label>
            <select name="status_pembayaran" class="border p-2 rounded w-40">
                <option value="">Semua</option>
                <option value="lunas" {{ request('status_pembayaran') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                <option value="pending" {{ request('status_pembayaran') == 'pending' ? 'selected' : '' }}>Pending
                </option>
            </select>
        </div>

        <div class="self-end">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Filter</button>
        </div>
    </form>

    <!-- Tabel Laporan Penjualan -->
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border rounded-lg shadow-md">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-4 py-2 text-left">No Faktur</th>
                    <th class="px-4 py-2 text-left">Tanggal</th>
                    <th class="px-4 py-2 text-left">Pelanggan</th>
                    <th class="px-4 py-2 text-left">Total Bayar</th>
                    <th class="px-4 py-2 text-left">Status</th>
                    <th class="px-4 py-2 text-left">aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($penjualans as $penjualan)
                <tr class="border-t">
                    <td class="px-4 py-2">{{ $penjualan->no_faktur }}</td>
                    <td class="px-4 py-2">{{ $penjualan->tgl_faktur }}</td>
                    <td class="px-4 py-2">{{ $penjualan->pelanggan->nama ?? '-' }}</td>
                    <td class="px-4 py-2">Rp {{ number_format($penjualan->total_bayar, 0, ',', '.') }}</td>
                    <td class="px-4 py-2">
                        <span
                            class="px-2 py-1 text-xs font-bold rounded 
                                    {{ $penjualan->status_pembayaran == 'lunas' ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800' }}">
                            {{ ucfirst($penjualan->status_pembayaran) }}
                        </span>
                    </td>
                    <td class="px-4 py-2">
                        <a href="{{ route('laporan.penjualan.show', $penjualan->id) }}"
                            class="bg-blue-500 text-white px-3 py-1 rounded text-sm">
                            Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-2 text-center text-gray-500">Tidak ada data penjualan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $penjualans->links('vendor.pagination.simple-tailwind') }}

    </div>
</div>
@endsection