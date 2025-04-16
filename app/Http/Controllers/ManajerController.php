<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\DetailPenjualan;
use App\Models\Pelanggan;
use App\Models\Penjualan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ManajerController extends Controller
{
    /**
     * Menampilkan dashboard manajer dengan informasi statistik dan laporan.
     *
     * Fungsi ini mengambil berbagai informasi dan statistik terkait penjualan, barang, pelanggan,
     * dan transaksi untuk ditampilkan di dashboard manajer. Informasi yang ditampilkan mencakup
     * total transaksi, omzet, profit, jumlah barang terjual, barang hampir habis, barang tidak laku,
     * barang terlaris, pelanggan baru dan aktif, serta statistik pembayaran dan penjualan per kategori.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Menghitung total transaksi yang ada
        $total_transaksi = Penjualan::count();

        // Menghitung total omzet berdasarkan penjualan
        $omzet = Penjualan::sum('total_bayar');

        // Menghitung profit yang diambil 20% dari omzet
        $profit = $omzet * 0.2;

        // Menghitung jumlah barang yang terjual
        $barang_terjual = DetailPenjualan::sum('jumlah');

        // Menghitung jumlah barang yang hampir habis (stok <= stok_minimal)
        $barang_hampir_habis = Barang::whereColumn('stok', '<=', 'stok_minimal')->count();

        // Menghitung jumlah barang yang tidak laku (tidak ada transaksi terkait barang ini)
        $barang_tidak_laku = Barang::doesntHave('detailPenjualan')->count();

        // Mengambil 5 barang terlaris berdasarkan jumlah penjualan
        $barang_terlaris = Barang::withCount('detailPenjualan')
            ->orderByDesc('detail_penjualan_count')
            ->limit(5)
            ->get();

        // Menghitung jumlah pelanggan baru yang terdaftar pada bulan ini
        $pelanggan_baru = Pelanggan::whereMonth('created_at', Carbon::now()->month)->count();

        // Menghitung jumlah pelanggan aktif (berdasarkan transaksi yang dilakukan)
        $pelanggan_aktif = Penjualan::distinct('pelanggan_id')->count();

        // Menyiapkan grafik penjualan untuk 7 hari terakhir berdasarkan total bayar
        $grafik_penjualan = Penjualan::selectRaw('DATE(tgl_faktur) as tanggal, SUM(total_bayar) as total')
            ->where('tgl_faktur', '>=', Carbon::now()->subDays(7))
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        // Mengambil data transaksi pada bulan ini dengan informasi jumlah transaksi dan total pendapatan per tanggal
        $data_transaksi = Penjualan::selectRaw('DATE(tgl_faktur) as tanggal, COUNT(*) as jumlah_transaksi, SUM(total_bayar) as total_pendapatan')
            ->whereBetween('tgl_faktur', [Carbon::now()->startOfMonth(), Carbon::now()])
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get()
            ->map(function ($item) {
                // Mengubah format tanggal menjadi 'd-m-Y'
                $item->tanggal = Carbon::parse($item->tanggal)->format('d-m-Y');
                return $item;
            });

        // Menghitung jumlah transaksi berdasarkan status pembayaran
        $transaksi_lunas = Penjualan::where('status_pembayaran', 'lunas')->count();
        $transaksi_tunda = Penjualan::where('status_pembayaran', 'tertunda')->count();
        $transaksi_gagal = Penjualan::where('status_pembayaran', 'gagal')->count();

        // Mengambil statistik penjualan per kategori barang
        $penjualanPerKategori = Barang::join('kategori', 'barang.kategori_id', '=', 'kategori.id')
            ->join('detail_penjualan', 'barang.id', '=', 'detail_penjualan.barang_id')
            ->selectRaw('kategori.nama_kategori as kategori, SUM(detail_penjualan.jumlah) as total')
            ->groupBy('kategori.nama_kategori')
            ->get();

        // Mengembalikan tampilan dashboard manajer dengan semua data yang telah dihitung
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
            'data_transaksi',
            'transaksi_lunas',
            'transaksi_tunda',
            'transaksi_gagal',
            'penjualanPerKategori'
        ));
    }
}