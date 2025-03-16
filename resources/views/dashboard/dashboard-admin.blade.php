@extends('layouts.admin')

@section('content')
<div class="container">
    <!-- Statistik Utama -->
    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="bg-[#3D8D7A] text-white p-4 rounded-lg shadow">
            <h3 class="text-lg font-semibold">Total Barang</h3>
            <p class="text-2xl font-bold">{{ $totalBarang }}</p>
        </div>
        <div class="bg-[#B3D8A8] text-white p-4 rounded-lg shadow">
            <h3 class="text-lg font-semibold">Pembelian Bulan Ini</h3>
            <p class="text-2xl font-bold">{{ $totalPembelianBulanIni }}</p>
        </div>
        <div class="bg-[#A3D1C6] text-white p-4 rounded-lg shadow">
            <h3 class="text-lg font-semibold">Total Pemasok</h3>
            <p class="text-2xl font-bold">{{ $totalPemasok }}</p>
        </div>
    </div>

    <!-- Grafik -->
    <div class="grid grid-cols-2 gap-4">
        <div class="chart-container">
            <canvas id="stokChart"></canvas>
        </div>
        <div class="chart-container">
            <canvas id="pembelianChart"></canvas>
        </div>
    </div>

    <!-- Tabel Barang dengan Stok Rendah -->
    <div class="bg-white p-6 rounded-lg shadow mt-6">
        <h3 class="text-lg font-semibold mb-2">Barang dengan Stok Rendah</h3>
        <div class="overflow-x-auto">
            <table class="w-full border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border p-2">Nama Barang</th>
                        <th class="border p-2">Stok</th>
                        <th class="border p-2">Stok Minimal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($barangKurang as $barang)
                    <tr>
                        <td class="border p-2">{{ $barang->nama_barang }}</td>
                        <td class="border p-2 text-red-500">{{ $barang->stok }}</td>
                        <td class="border p-2">{{ $barang->stok_minimal }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Chart.js untuk Grafik -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Ambil data dari PHP
var stokPerKategori = @json($stokPerKategori);
var pembelianBulanan = @json($pembelianBulanan);

// Grafik Stok Barang per Kategori
new Chart(document.getElementById('stokChart'), {
    type: 'bar',
    data: {
        labels: stokPerKategori.map(item => item.nama_kategori),
        datasets: [{
            label: 'Total Stok',
            data: stokPerKategori.map(item => item.total_stok),
            backgroundColor: 'rgba(0, 206, 137, 0.53)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Grafik Pembelian 6 Bulan Terakhir
new Chart(document.getElementById('pembelianChart'), {
    type: 'line',
    data: {
        labels: pembelianBulanan.map(item => item.bulan),
        datasets: [{
            label: 'Total Pembelian',
            data: pembelianBulanan.map(item => item.total),
            backgroundColor: 'rgba(255, 99, 132, 0.2)',
            borderColor: 'rgba(255, 99, 132, 1)',
            borderWidth: 2,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>

<!-- CSS untuk Grafik -->
<style>
.chart-container {
    width: 100%;
    max-width: 600px;
    height: 350px;
}
</style>

@endsection