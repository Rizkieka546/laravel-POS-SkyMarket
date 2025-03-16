@extends('layouts.manajer')

@section('content')
<div class="container mx-auto px-6 py-8">
    <h1 class="text-3xl font-bold text-gray-800">Dashboard Manajer</h1>

    <!-- Ringkasan Penjualan -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
        <div class="bg-white shadow-md rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-700">Total Transaksi</h3>
            <p class="text-2xl font-bold text-blue-500">{{ $total_transaksi }}</p>
        </div>
        <div class="bg-white shadow-md rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-700">Omzet</h3>
            <p class="text-2xl font-bold text-green-500">Rp{{ number_format($omzet, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white shadow-md rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-700">Profit</h3>
            <p class="text-2xl font-bold text-purple-500">Rp{{ number_format($profit, 0, ',', '.') }}</p>
        </div>
    </div>

    <!-- Ringkasan Stok -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
        <div class="bg-white shadow-md rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-700">Barang Terjual</h3>
            <p class="text-2xl font-bold text-orange-500">{{ $barang_terjual }}</p>
        </div>
        <div class="bg-white shadow-md rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-700">Barang Hampir Habis</h3>
            <p class="text-2xl font-bold text-red-500">{{ $barang_hampir_habis }}</p>
        </div>
    </div>

    <!-- Statistik Pelanggan -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
        <div class="bg-white shadow-md rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-700">Pelanggan Baru Bulan Ini</h3>
            <p class="text-2xl font-bold text-indigo-500">{{ $pelanggan_baru }}</p>
        </div>
        <div class="bg-white shadow-md rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-700">Pelanggan Aktif</h3>
            <p class="text-2xl font-bold text-teal-500">{{ $pelanggan_aktif }}</p>
        </div>
    </div>

    <!-- Grafik Penjualan -->
    <div class="bg-white shadow-md rounded-lg p-6 mt-6">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">Grafik Penjualan per Kategori</h3>
        <canvas id="grafikPenjualan"></canvas>
    </div>

    <!-- Status Pembayaran -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
        <div class="bg-green-100 shadow-md rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-700">Lunas</h3>
            <p class="text-2xl font-bold text-green-500">{{ $transaksi_lunas }}</p>
        </div>
        <div class="bg-yellow-100 shadow-md rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-700">Tertunda</h3>
            <p class="text-2xl font-bold text-yellow-500">{{ $transaksi_tunda }}</p>
        </div>
        <div class="bg-red-100 shadow-md rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-700">Gagal</h3>
            <p class="text-2xl font-bold text-red-500">{{ $transaksi_gagal }}</p>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
var penjualanPerKategori = @json($penjualanPerKategori);
const ctx = document.getElementById('grafikPenjualan').getContext('2d');

const labels = penjualanPerKategori.map(item => item.kategori);
const dataValues = penjualanPerKategori.map(item => item.total);

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: labels,
        datasets: [{
            label: 'Total Penjualan',
            data: dataValues,
            backgroundColor: 'rgba(75, 192, 192, 0.5)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 1
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
</script>
@endsection