<?php

namespace App\Http\Controllers;

use App\Models\DetailPenjualan;
use App\Models\Penjualan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KasirController extends Controller
{
    /**
     * Menampilkan dashboard kasir dengan berbagai informasi terkait penjualan.
     *
     * Metode ini mengumpulkan dan menghitung beberapa data terkait transaksi penjualan,
     * termasuk total penjualan hari ini, total pendapatan bulan ini, jumlah transaksi hari ini,
     * penjualan per kategori, tren penjualan selama 7 hari terakhir, dan barang terlaris.
     * Semua data ini akan dikirimkan ke tampilan `dashboard.dashboard-kasir` untuk ditampilkan.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Menghitung total penjualan hari ini
        $totalPenjualanHariIni = Penjualan::whereDate('tgl_faktur', Carbon::today())->sum('total_bayar');

        // Menghitung total pendapatan bulan ini
        $totalPendapatanBulanIni = Penjualan::whereMonth('tgl_faktur', Carbon::now()->month)
            ->whereYear('tgl_faktur', Carbon::now()->year)
            ->sum('total_bayar');

        // Menghitung jumlah transaksi yang terjadi hari ini
        $jumlahTransaksiHariIni = Penjualan::whereDate('tgl_faktur', Carbon::today())->count();

        // Menghitung penjualan per kategori barang
        $penjualanPerKategori = DetailPenjualan::join('barang', 'detail_penjualan.barang_id', '=', 'barang.id')
            ->join('kategori', 'barang.kategori_id', '=', 'kategori.id')
            ->select('kategori.nama_kategori', DB::raw('SUM(detail_penjualan.jumlah) as total_terjual'))
            ->groupBy('kategori.nama_kategori')
            ->get();

        // Menghitung tren penjualan selama 7 hari terakhir
        $trenPenjualan = Penjualan::select(
            DB::raw('DATE(tgl_faktur) as tanggal'),
            DB::raw('SUM(total_bayar) as total_penjualan')
        )
            ->where('tgl_faktur', '>=', Carbon::now()->subDays(7))
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'ASC')
            ->get();

        // Mengambil barang terlaris (5 barang teratas berdasarkan jumlah penjualan)
        $barangTerlaris = DetailPenjualan::join('barang', 'detail_penjualan.barang_id', '=', 'barang.id')
            ->select('barang.nama_barang', DB::raw('SUM(detail_penjualan.jumlah) as total_dibeli'))
            ->groupBy('barang.nama_barang')
            ->orderByDesc('total_dibeli')
            ->limit(5)
            ->get();

        // Mengirim data yang telah dihitung ke tampilan dashboard kasir
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