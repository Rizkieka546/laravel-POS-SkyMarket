<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penjualan;
use App\Models\Pembelian;
use Carbon\Carbon;

class LaporanKeuanganController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->input('bulan', Carbon::now()->month);
        $tahun = $request->input('tahun', Carbon::now()->year);

        $totalPemasukan = Penjualan::whereYear('tgl_faktur', $tahun)
            ->whereMonth('tgl_faktur', $bulan)
            ->sum('total_bayar');

        $totalPengeluaran = Pembelian::whereYear('tanggal_masuk', $tahun)
            ->whereMonth('tanggal_masuk', $bulan)
            ->sum('total');

        $labaBersih = $totalPemasukan - $totalPengeluaran;

        $penjualan = Penjualan::whereYear('tgl_faktur', $tahun)
            ->whereMonth('tgl_faktur', $bulan)
            ->get();

        $pembelian = Pembelian::whereYear('tanggal_masuk', $tahun)
            ->whereMonth('tanggal_masuk', $bulan)
            ->get();

        $keuanganchart = [
            'totalPemasukan' => $totalPemasukan,
            'totalPengeluaran' => $totalPengeluaran,
            'labaBersih' => $labaBersih,
        ];

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