@extends('layouts.kasir')

@section('content')
<div class="container mx-auto p-6">
    <!-- Statistik Utama -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-teal-500 text-white p-6 rounded-xl shadow-lg">
            <h3 class="text-lg font-semibold">Total Penjualan Hari Ini</h3>
            <p class="text-3xl font-bold mt-2">Rp {{ number_format($totalPenjualanHariIni, 0, ',', '.') }}</p>
        </div>
        <div class="bg-green-500 text-white p-6 rounded-xl shadow-lg">
            <h3 class="text-lg font-semibold">Total Pendapatan Bulan Ini</h3>
            <p class="text-3xl font-bold mt-2">Rp {{ number_format($totalPendapatanBulanIni, 0, ',', '.') }}</p>
        </div>
        <div class="bg-yellow-500 text-white p-6 rounded-xl shadow-lg">
            <h3 class="text-lg font-semibold">Jumlah Transaksi Hari Ini</h3>
            <p class="text-3xl font-bold mt-2">{{ $jumlahTransaksiHariIni }}</p>
        </div>
    </div>


    <div class="grid grid-cols-2 gap-4 mb-4 p-2">
        <div class="chart-container bg-white rounded-xl shadow-lg">
            <canvas id="penjualanKategoriChart"></canvas>
        </div>
        <div class="chart-container bg-white rounded-xl shadow-lg">
            <canvas id="trenPenjualanChart"></canvas>
        </div>
    </div>

    <!-- Tabel Barang Terlaris -->
    <div class="bg-white p-6 rounded-xl shadow-lg">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Barang yang Sering Dibeli Pelanggan</h3>
        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-teal-500 text-white">
                    <th class="border p-3 text-left">Nama Barang</th>
                    <th class="border p-3 text-left">Total Dibeli</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($barangTerlaris as $barang)
                <tr class="hover:bg-gray-100">
                    <td class="border p-3">{{ $barang->nama_barang }}</td>
                    <td class="border p-3">{{ $barang->total_dibeli }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Chart.js untuk Grafik -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Data dari Laravel
var penjualanPerKategori = @json($penjualanPerKategori);
var trenPenjualan = @json($trenPenjualan);

// Grafik Penjualan per Kategori
var kategoriLabels = penjualanPerKategori.map(item => item.nama_kategori);
var kategoriData = penjualanPerKategori.map(item => item.total_terjual);

var ctxKategori = document.getElementById('penjualanKategoriChart').getContext('2d');
new Chart(ctxKategori, {
    type: 'doughnut',
    data: {
        labels: kategoriLabels,
        datasets: [{
            label: 'Jumlah Terjual',
            data: kategoriData,
            backgroundColor: ['#008080', '#FF6384', '#36A2EB', '#FFCE56', '#4CAF50'],
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});

// Grafik Tren Penjualan 7 Hari Terakhir
var trenLabels = trenPenjualan.map(item => item.tanggal);
var trenData = trenPenjualan.map(item => item.total_penjualan);

var ctxTren = document.getElementById('trenPenjualanChart').getContext('2d');
new Chart(ctxTren, {
    type: 'bar',
    data: {
        labels: trenLabels,
        datasets: [{
            label: 'Total Penjualan',
            data: trenData,
            backgroundColor: '#008080',
            borderColor: '#005f5f',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});
</script>
@endsection