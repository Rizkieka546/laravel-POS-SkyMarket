<?php

namespace App\Http\Controllers;

use App\Exports\PembelianExport;
use App\Models\Pemasok;
use App\Models\Pembelian;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class LaporanPembelianController extends Controller
{
    public function index(Request $request)
    {
        $query = Pembelian::with('pemasok');

        if ($request->has('tanggal_mulai') && $request->has('tanggal_selesai')) {
            $query->whereBetween('tanggal_masuk', [$request->tanggal_mulai, $request->tanggal_selesai]);
        }

        if ($request->has('pemasok_id') && $request->pemasok_id != '') {
            $query->where('pemasok_id', $request->pemasok_id);
        }

        $pembelians = $query->paginate(10);
        $pemasoks = Pemasok::all();

        return view('manajer.laporan.pembelian.index', compact('pembelians', 'pemasoks'));
    }

    // Menampilkan detail pembelian
    public function show($id)
    {
        $pembelian = Pembelian::with(['pemasok', 'detailPembelian.barang'])->findOrFail($id);

        return view('manajer.laporan.pembelian.show', compact('pembelian'));
    }

    // Ekspor laporan pembelian ke Excel
    public function exportExcel(Request $request)
    {
        return Excel::download(new PembelianExport($request->tanggal_mulai, $request->tanggal_selesai, $request->pemasok_id), 'laporan_pembelian.xlsx');
    }

    // Ekspor laporan pembelian ke PDF
    // public function exportPDF(Request $request)
    // {
    //     $query = Pembelian::with('pemasok');

    //     if ($request->has('tanggal_mulai') && $request->has('tanggal_selesai')) {
    //         $query->whereBetween('tanggal_masuk', [$request->tanggal_mulai, $request->tanggal_selesai]);
    //     }

    //     if ($request->has('pemasok_id') && $request->pemasok_id != '') {
    //         $query->where('pemasok_id', $request->pemasok_id);
    //     }

    //     $pembelians = $query->get();
    //     $pdf = PDF::loadView('laporan.export_pembelian', compact('pembelians'));

    //     return $pdf->download('laporan_pembelian.pdf');
    // }
}