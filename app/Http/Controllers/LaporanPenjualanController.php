<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use Illuminate\Http\Request;

class LaporanPenjualanController extends Controller
{
    /**
     * Menampilkan daftar penjualan dengan filter tanggal dan status pembayaran.
     *
     * Metode ini menangani permintaan untuk menampilkan daftar penjualan dengan filter berdasarkan
     * tanggal mulai, tanggal selesai, dan status pembayaran. Hasil pencarian dipaginasi untuk kemudahan navigasi.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Ambil parameter filter jika ada
        $tanggalMulai = $request->input('tanggal_mulai');
        $tanggalSelesai = $request->input('tanggal_selesai');
        $statusPembayaran = $request->input('status_pembayaran');

        // Query data penjualan dengan filter
        $query = Penjualan::with('detailPenjualan.barang', 'pelanggan');

        // Filter berdasarkan rentang tanggal jika ada input dari pengguna
        if ($tanggalMulai && $tanggalSelesai) {
            $query->whereBetween('tgl_faktur', [$tanggalMulai, $tanggalSelesai]);
        }

        // Filter berdasarkan status pembayaran jika dipilih
        if ($statusPembayaran) {
            $query->where('status_pembayaran', $statusPembayaran);
        }

        // Mengurutkan berdasarkan tanggal faktur dan mempaginasikan hasil pencarian
        $penjualans = $query->orderBy('tgl_faktur', 'desc')->paginate(10);

        return view('manajer.laporan.penjualan.index', compact('penjualans', 'tanggalMulai', 'tanggalSelesai', 'statusPembayaran'));
    }

    /**
     * Menampilkan detail penjualan berdasarkan ID.
     *
     * Metode ini digunakan untuk menampilkan detail penjualan tertentu, termasuk informasi pelanggan
     * dan detail barang yang dibeli dalam transaksi tersebut.
     *
     * @param  int  $id  ID penjualan yang akan ditampilkan
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        // Mengambil penjualan dengan relasi pelanggan dan detail penjualan barang berdasarkan ID
        $penjualan = Penjualan::with(['pelanggan', 'detailPenjualan.barang'])->findOrFail($id);

        return view('manajer.laporan.penjualan.show', compact('penjualan'));
    }
}