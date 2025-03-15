@extends('layouts.admin')

@section('content')
<div class="container">
    <h2 class="mb-4 text-lg font-bold">Dashboard Admin</h2>

    <!-- Statistik Utama -->
    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="bg-blue-500 text-white p-4 rounded-lg shadow">
            <h3 class="text-lg font-semibold">Total Barang</h3>
            <p class="text-2xl">{{ $totalBarang }}</p>
        </div>
        <div class="bg-green-500 text-white p-4 rounded-lg shadow">
            <h3 class="text-lg font-semibold">Pembelian Bulan Ini</h3>
            <p class="text-2xl">{{ $totalPembelianBulanIni }}</p>
        </div>
        <div class="bg-yellow-500 text-white p-4 rounded-lg shadow">
            <h3 class="text-lg font-semibold">Total Pemasok</h3>
            <p class="text-2xl">{{ $totalPemasok }}</p>
        </div>
    </div>

    <!-- Grafik -->
    <div class="flex flex-wrap justify-between gap-4">
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

<!-- Chart.js untuk Grafik -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Ambil data dari PHP
var stokPerKategori = @json($stokPerKategori);
var pembelianBulanan = @json($pembelianBulanan);

// Ambil elemen canvas
var stokChartCanvas = document.getElementById('stokChart').getContext('2d');
var pembelianChartCanvas = document.getElementById('pembelianChart').getContext('2d');

// Data untuk Grafik Stok
var stokLabels = stokPerKategori.map(item => `Kategori ${item.kategori_id}`);
var stokData = stokPerKategori.map(item => item.total_stok);

new Chart(stokChartCanvas, {
    type: 'bar',
    data: {
        labels: stokLabels,
        datasets: [{
            label: 'Total Stok',
            data: stokData,
            backgroundColor: 'rgba(255, 99, 132, 0.2)',
            borderColor: 'rgba(255, 99, 132, 1)',
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});

// Data untuk Grafik Pembelian
var pembelianLabels = pembelianBulanan.map(item => item.bulan);
var pembelianData = pembelianBulanan.map(item => item.total);

new Chart(pembelianChartCanvas, {
    type: 'bar',
    data: {
        labels: pembelianLabels,
        datasets: [{
            label: 'Total Pembelian',
            data: pembelianData,
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});
</script>

<!-- CSS untuk Mengatur Ukuran Grafik -->
<style>
.chart-container {
    width: 100%;
    max-width: 500px;
    height: 300px;
}
</style>

@endsection