<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penjualan;
use App\Models\Pembelian;
use Carbon\Carbon;

class LaporanKeuanganController extends Controller
{
    /**
     * Menampilkan laporan keuangan berdasarkan bulan dan tahun.
     *
     * Metode ini mengambil input bulan dan tahun dari request, kemudian menghitung total pemasukan, total pengeluaran,
     * dan laba bersih untuk bulan dan tahun tersebut. Selain itu, data penjualan dan pembelian juga diambil untuk ditampilkan
     * di halaman laporan keuangan.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Mendapatkan bulan dan tahun dari input request, default ke bulan dan tahun saat ini
        $bulan = $request->input('bulan', Carbon::now()->month);
        $tahun = $request->input('tahun', Carbon::now()->year);

        // Menghitung total pemasukan berdasarkan bulan dan tahun yang dipilih
        $totalPemasukan = Penjualan::whereYear('tgl_faktur', $tahun)
            ->whereMonth('tgl_faktur', $bulan)
            ->sum('total_bayar');

        // Menghitung total pengeluaran berdasarkan bulan dan tahun yang dipilih
        $totalPengeluaran = Pembelian::whereYear('tanggal_masuk', $tahun)
            ->whereMonth('tanggal_masuk', $bulan)
            ->sum('total');

        // Menghitung laba bersih
        $labaBersih = $totalPemasukan - $totalPengeluaran;

        // Mengambil data penjualan berdasarkan bulan dan tahun yang dipilih
        $penjualan = Penjualan::whereYear('tgl_faktur', $tahun)
            ->whereMonth('tgl_faktur', $bulan)
            ->get();

        // Mengambil data pembelian berdasarkan bulan dan tahun yang dipilih
        $pembelian = Pembelian::whereYear('tanggal_masuk', $tahun)
            ->whereMonth('tanggal_masuk', $bulan)
            ->get();

        // Menyusun data keuangan untuk ditampilkan dalam grafik
        $keuanganchart = [
            'totalPemasukan' => $totalPemasukan,
            'totalPengeluaran' => $totalPengeluaran,
            'labaBersih' => $labaBersih,
        ];

        // Mengirim data ke tampilan untuk ditampilkan
        return view('manajer.laporan.keuangan.index', compact(
            'totalPemasukan',
            'totalPengeluaran',
            'labaBersih',
            'penjualan',
            'pembelian',
            'bulan',
            'tahun',
            'keuanganchart'
        ));
    }
}