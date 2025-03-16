<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\DetailPenjualan;
use App\Models\Pelanggan;
use App\Models\Penjualan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ManajerController extends Controller
{
    public function index()
    {
        // Penjualan
        $total_transaksi = Penjualan::count();
        $omzet = Penjualan::sum('total_bayar');
        $profit = $omzet * 0.2;
        $barang_terjual = DetailPenjualan::sum('jumlah');

        // Stok Barang
        $barang_hampir_habis = Barang::whereColumn('stok', '<=', 'stok_minimal')->count();
        $barang_tidak_laku = Barang::doesntHave('detailPenjualan')->count();
        $barang_terlaris = Barang::withCount('detailPenjualan')
            ->orderByDesc('detail_penjualan_count')
            ->limit(5)
            ->get();

        // Statistik Pelanggan
        $pelanggan_baru = Pelanggan::whereMonth('created_at', Carbon::now()->month)->count();
        $pelanggan_aktif = Penjualan::distinct('pelanggan_id')->count();

        // Grafik Penjualan (7 hari terakhir)
        $grafik_penjualan = Penjualan::selectRaw('DATE(tgl_faktur) as tanggal, SUM(total_bayar) as total')
            ->where('tgl_faktur', '>=', Carbon::now()->subDays(7))
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        // Status Pembayaran
        $transaksi_lunas = Penjualan::where('status_pembayaran', 'lunas')->count();
        $transaksi_tunda = Penjualan::where('status_pembayaran', 'tertunda')->count();
        $transaksi_gagal = Penjualan::where('status_pembayaran', 'gagal')->count();

        // Statistik Penjualan per Kategori
        $penjualanPerKategori = Barang::join('kategori', 'barang.kategori_id', '=', 'kategori.id')
            ->join('detail_penjualan', 'barang.id', '=', 'detail_penjualan.barang_id')
            ->selectRaw('kategori.nama_kategori as kategori, SUM(detail_penjualan.jumlah) as total')
            ->groupBy('kategori.nama_kategori')
            ->get();

        return view('dashboard.dashboard-manajer', compact(
            'total_transaksi',
            'omzet',
            'profit',
            'barang_terjual',
            'barang_hampir_habis',
            'barang_tidak_laku',
            'barang_terlaris',
            'pelanggan_baru',
            'pelanggan_aktif',
            'grafik_penjualan',
            'transaksi_lunas',
            'transaksi_tunda',
            'transaksi_gagal',
            'penjualanPerKategori'
        ));
    }
}