@extends('layouts.manajer')

@section('content')
    <div class="container mx-auto px-6 py-8">
        <h1 class="text-3xl font-bold text-gray-800">Dashboard Manajer</h1>

        <!-- Ringkasan Penjualan -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
            @foreach ([['label' => 'Total Transaksi', 'value' => $total_transaksi, 'color' => 'blue-500'], ['label' => 'Omzet', 'value' => 'Rp' . number_format($omzet, 0, ',', '.'), 'color' => 'green-500'], ['label' => 'Profit', 'value' => 'Rp' . number_format($profit, 0, ',', '.'), 'color' => 'purple-500']] as $item)
                <div class="bg-white shadow-md rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-700">{{ $item['label'] }}</h3>
                    <p class="text-2xl font-bold text-{{ $item['color'] }}">{{ $item['value'] }}</p>
                </div>
            @endforeach
        </div>

        <!-- Ringkasan Stok -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
            @foreach ([['label' => 'Barang Terjual', 'value' => $barang_terjual, 'color' => 'orange-500'], ['label' => 'Barang Hampir Habis', 'value' => $barang_hampir_habis, 'color' => 'red-500']] as $item)
                <div class="bg-white shadow-md rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-700">{{ $item['label'] }}</h3>
                    <p class="text-2xl font-bold text-{{ $item['color'] }}">{{ $item['value'] }}</p>
                </div>
            @endforeach
        </div>

        <!-- Statistik Pelanggan -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
            @foreach ([['label' => 'Pelanggan Baru Bulan Ini', 'value' => $pelanggan_baru, 'color' => 'indigo-500'], ['label' => 'Pelanggan Aktif', 'value' => $pelanggan_aktif, 'color' => 'teal-500']] as $item)
                <div class="bg-white shadow-md rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-700">{{ $item['label'] }}</h3>
                    <p class="text-2xl font-bold text-{{ $item['color'] }}">{{ $item['value'] }}</p>
                </div>
            @endforeach
        </div>

        <!-- Grafik Penjualan -->
        @foreach ([['id' => 'transaksiChart', 'title' => 'Grafik Penjualan per Hari'], ['id' => 'grafikPenjualan', 'title' => 'Grafik Penjualan per Kategori']] as $chart)
            <div class="bg-white shadow-md rounded-lg p-6 mt-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">{{ $chart['title'] }}</h3>
                <canvas id="{{ $chart['id'] }}"></canvas>
            </div>
        @endforeach

        <!-- Status Pembayaran -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
            @foreach ([['label' => 'Lunas', 'value' => $transaksi_lunas, 'bg' => 'green-100', 'text' => 'green-500'], ['label' => 'Tertunda', 'value' => $transaksi_tunda, 'bg' => 'yellow-100', 'text' => 'yellow-500'], ['label' => 'Gagal', 'value' => $transaksi_gagal, 'bg' => 'red-100', 'text' => 'red-500']] as $item)
                <div class="bg-{{ $item['bg'] }} shadow-md rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-700">{{ $item['label'] }}</h3>
                    <p class="text-2xl font-bold text-{{ $item['text'] }}">{{ $item['value'] }}</p>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var ctxKategori = document.getElementById('grafikPenjualan').getContext('2d');
            var ctxTransaksi = document.getElementById('transaksiChart').getContext('2d');

            var penjualanPerKategori = @json($penjualanPerKategori);
            var dataTransaksi = @json($data_transaksi);

            new Chart(ctxKategori, {
                type: 'bar',
                data: {
                    labels: penjualanPerKategori.map(item => item.kategori),
                    datasets: [{
                        label: 'Total Penjualan',
                        data: penjualanPerKategori.map(item => item.total),
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

            new Chart(ctxTransaksi, {
                type: 'line',
                data: {
                    labels: dataTransaksi.map(item => item.tanggal),
                    datasets: [{
                            label: 'Jumlah Transaksi',
                            data: dataTransaksi.map(item => item.jumlah_transaksi),
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 2,
                            fill: true
                        },
                        {
                            label: 'Total Pendapatan',
                            data: dataTransaksi.map(item => item.total_pendapatan),
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 2,
                            fill: true
                        }
                    ]
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
