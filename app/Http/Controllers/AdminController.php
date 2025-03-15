<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Pemasok;
use App\Models\Pembelian;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        return view('dashboard.dashboard-admin');
    }

    public function dashboard()
    {
        // Statistik Utama
        $totalBarang = Barang::count();
        $totalPembelianBulanIni = Pembelian::whereMonth('tanggal_masuk', Carbon::now()->month)->count();
        $totalPemasok = Pemasok::count();
        $barangKurang = Barang::whereColumn('stok', '<=', 'stok_minimal')->get();

        // Grafik Pembelian 6 Bulan Terakhir
        $pembelianBulanan = Pembelian::selectRaw('MONTH(tanggal_masuk) as bulan, SUM(total) as total')
            ->whereYear('tanggal_masuk', Carbon::now()->year)
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        // Grafik Stok Barang Berdasarkan Kategori
        $stokPerKategori = Barang::selectRaw('kategori_id, SUM(stok) as total_stok')
            ->groupBy('kategori_id')
            ->get();

        return view('dashboard.dashboard-admin', compact(
            'totalBarang',
            'totalPembelianBulanIni',
            'totalPemasok',
            'barangKurang',
            'pembelianBulanan',
            'stokPerKategori'
        ));
    }
}