@extends('layouts.manajer')

@section('content')
<div class="max-w-7xl mx-auto bg-white p-6 rounded-lg shadow-lg">
    <h1 class="text-2xl font-bold mb-4">Laporan Keuangan</h1>

    <!-- Filter Laporan -->
    <form method="GET" action="{{ route('laporan.keuangan') }}" class="mb-4 flex space-x-4">
        <select name="bulan" class="p-2 border rounded">
            @foreach(range(1, 12) as $m)
            <option value="{{ $m }}" {{ $m == $bulan ? 'selected' : '' }}>
                {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
            </option>
            @endforeach
        </select>

        <select name="tahun" class="p-2 border rounded">
            @foreach(range(now()->year - 5, now()->year) as $y)
            <option value="{{ $y }}" {{ $y == $tahun ? 'selected' : '' }}>{{ $y }}</option>
            @endforeach
        </select>

        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">Filter</button>
    </form>

    <!-- Ringkasan Keuangan -->
    <div class="grid grid-cols-3 gap-4 text-center">
        <div class="p-4 bg-green-200 text-green-800 rounded">
            <h2 class="text-lg font-semibold">Total Pemasukan</h2>
            <p class="text-2xl font-bold">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</p>
        </div>
        <div class="p-4 bg-red-200 text-red-800 rounded">
            <h2 class="text-lg font-semibold">Total Pengeluaran</h2>
            <p class="text-2xl font-bold">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</p>
        </div>
        <div class="p-4 bg-gray-200 text-gray-800 rounded">
            <h2 class="text-lg font-semibold">Laba Bersih</h2>
            <p class="text-2xl font-bold">Rp {{ number_format($labaBersih, 0, ',', '.') }}</p>
        </div>
    </div>

    <!-- Grafik Keuangan -->
    <div class="mt-6">
        <canvas id="keuanganChart"></canvas>
    </div>

    <!-- Detail Transaksi -->
    <h2 class="text-xl font-bold mt-6">Detail Transaksi</h2>

    <!-- Tabel Penjualan -->
    <h3 class="text-lg font-semibold mt-4">Penjualan</h3>
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border rounded-lg shadow-md">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-4 py-2">No Faktur</th>
                    <th class="px-4 py-2">Tanggal</th>
                    <th class="px-4 py-2">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($penjualan as $p)
                <tr class="border-t">
                    <td class="px-4 py-2">{{ $p->no_faktur }}</td>
                    <td class="px-4 py-2">{{ \Carbon\Carbon::parse($p->tgl_faktur)->format('d-m-Y') }}</td>
                    <td class="px-4 py-2">Rp {{ number_format($p->total_bayar, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Tabel Pembelian -->
    <h3 class="text-lg font-semibold mt-4">Pembelian</h3>
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border rounded-lg shadow-md">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-4 py-2">Kode Masuk</th>
                    <th class="px-4 py-2">Tanggal</th>
                    <th class="px-4 py-2">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pembelian as $p)
                <tr class="border-t">
                    <td class="px-4 py-2">{{ $p->kode_masuk }}</td>
                    <td class="px-4 py-2">{{ \Carbon\Carbon::parse($p->tgl_masuk)->format('d-m-Y') }}</td>
                    <td class="px-4 py-2">Rp {{ number_format($p->total_harga, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Script Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('keuanganChart').getContext('2d');

    var keuangan = @json($keuanganchart);

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Pemasukan', 'Pengeluaran', 'Laba Bersih'],
            datasets: [{
                label: 'Laporan Keuangan',
                data: [keuangan.totalPemasukan, keuangan.totalPengeluaran, keuangan.labaBersih],
                backgroundColor: ['#34D399', '#F87171', '#9CA3AF'],
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
</script>
@endsection