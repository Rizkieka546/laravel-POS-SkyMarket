<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use Illuminate\Http\Request;

class LaporanPenjualanController extends Controller
{
    public function index(Request $request)
    {
        // Ambil parameter filter jika ada
        $tanggalMulai = $request->input('tanggal_mulai');
        $tanggalSelesai = $request->input('tanggal_selesai');
        $statusPembayaran = $request->input('status_pembayaran');

        // Query data penjualan dengan filter
        $query = Penjualan::with('detailPenjualan.barang', 'pelanggan');

        if ($tanggalMulai && $tanggalSelesai) {
            $query->whereBetween('tgl_faktur', [$tanggalMulai, $tanggalSelesai]);
        }

        if ($statusPembayaran) {
            $query->where('status_pembayaran', $statusPembayaran);
        }

        // Paginate data
        $penjualans = $query->orderBy('tgl_faktur', 'desc')->paginate(10);

        return view('manajer.laporan.penjualan.index', compact('penjualans', 'tanggalMulai', 'tanggalSelesai', 'statusPembayaran'));
    }

    public function show($id)
    {
        $penjualan = Penjualan::with(['pelanggan', 'detailPenjualan.barang'])->findOrFail($id);

        return view('manajer.laporan.penjualan.show', compact('penjualan'));
    }
}