<?php

namespace App\Http\Controllers;

use App\Models\DetailPenjualan;
use App\Models\Penjualan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KasirController extends Controller
{
    public function index()
    {
        // Total penjualan hari ini
        $totalPenjualanHariIni = Penjualan::whereDate('tgl_faktur', Carbon::today())->sum('total_bayar');

        // Total pendapatan bulan ini
        $totalPendapatanBulanIni = Penjualan::whereMonth('tgl_faktur', Carbon::now()->month)
            ->whereYear('tgl_faktur', Carbon::now()->year)
            ->sum('total_bayar');

        // Jumlah transaksi hari ini
        $jumlahTransaksiHariIni = Penjualan::whereDate('tgl_faktur', Carbon::today())->count();

        // Grafik penjualan per kategori
        $penjualanPerKategori = DetailPenjualan::join('barang', 'detail_penjualan.barang_id', '=', 'barang.id')
            ->join('kategori', 'barang.kategori_id', '=', 'kategori.id')
            ->select('kategori.nama_kategori', DB::raw('SUM(detail_penjualan.jumlah) as total_terjual'))
            ->groupBy('kategori.nama_kategori')
            ->get();

        // Tren penjualan dalam 7 hari terakhir
        $trenPenjualan = Penjualan::select(
            DB::raw('DATE(tgl_faktur) as tanggal'),
            DB::raw('SUM(total_bayar) as total_penjualan')
        )
            ->where('tgl_faktur', '>=', Carbon::now()->subDays(7))
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'ASC')
            ->get();

        // Barang yang sering dibeli pelanggan
        $barangTerlaris = DetailPenjualan::join('barang', 'detail_penjualan.barang_id', '=', 'barang.id')
            ->select('barang.nama_barang', DB::raw('SUM(detail_penjualan.jumlah) as total_dibeli'))
            ->groupBy('barang.nama_barang')
            ->orderByDesc('total_dibeli')
            ->limit(5)
            ->get();

        return view('dashboard.dashboard-kasir', compact(
            'totalPenjualanHariIni',
            'totalPendapatanBulanIni',
            'jumlahTransaksiHariIni',
            'penjualanPerKategori',
            'trenPenjualan',
            'barangTerlaris'
        ));
    }
}