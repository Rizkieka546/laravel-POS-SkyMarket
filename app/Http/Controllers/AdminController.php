<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Pemasok;
use App\Models\Pembelian;
use App\Models\Pengajuan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminController extends Controller
{
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
        $stokPerKategori = Barang::leftJoin('kategori as k', 'barang.kategori_id', '=', 'k.id')
            ->selectRaw('k.nama_kategori, COALESCE(SUM(barang.stok), 0) as total_stok')
            ->groupBy('k.nama_kategori')
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

    public function indexPengajuan()
    {
        $pengajuan = Pengajuan::all();
        return view('admin.pengajuan.index', compact('pengajuan'));
    }

    public function confirmPengajuan($id)
    {
        $pengajuan = Pengajuan::findOrFail($id);
        $pengajuan->update(['status' => 'terpenuhi']);

        return redirect()->route('pengajuan.admin')->with('success', 'Pengajuan berhasil diterima.');
    }
}