<?php

namespace App\Http\Controllers;

use App\Exports\PembelianExport;
use App\Models\Pemasok;
use App\Models\Pembelian;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class LaporanPembelianController extends Controller
{
    /**
     * Menampilkan daftar pembelian dengan filter tanggal dan pemasok.
     *
     * Metode ini menangani permintaan untuk menampilkan daftar pembelian dengan opsi filter berdasarkan 
     * tanggal mulai, tanggal selesai, dan pemasok. Hasil pencarian dapat dipaginasi untuk kemudahan navigasi.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Pembelian::with('pemasok'); // Mengambil pembelian dengan relasi pemasok

        // Filter berdasarkan rentang tanggal jika ada input dari pengguna
        if ($request->has('tanggal_mulai') && $request->has('tanggal_selesai')) {
            $query->whereBetween('tanggal_masuk', [$request->tanggal_mulai, $request->tanggal_selesai]);
        }

        // Filter berdasarkan ID pemasok jika dipilih
        if ($request->has('pemasok_id') && $request->pemasok_id != '') {
            $query->where('pemasok_id', $request->pemasok_id);
        }

        // Menampilkan hasil pencarian dengan paginasi 10 per halaman
        $pembelians = $query->paginate(10);
        $pemasoks = Pemasok::all(); // Mengambil semua pemasok untuk filter

        return view('manajer.laporan.pembelian.index', compact('pembelians', 'pemasoks'));
    }

    /**
     * Menampilkan detail pembelian berdasarkan ID.
     *
     * Metode ini digunakan untuk menampilkan detail pembelian tertentu, termasuk informasi pemasok
     * dan detail barang yang dibeli dalam transaksi tersebut.
     *
     * @param  int  $id  ID pembelian yang akan ditampilkan
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        // Mengambil pembelian dengan relasi pemasok dan detail pembelian barang berdasarkan ID
        $pembelian = Pembelian::with(['pemasok', 'detailPembelian.barang'])->findOrFail($id);

        return view('manajer.laporan.pembelian.show', compact('pembelian'));
    }

    /**
     * Mengekspor laporan pembelian ke dalam format Excel.
     *
     * Metode ini memungkinkan pengguna untuk mengunduh laporan pembelian dalam format Excel,
     * dengan mempertimbangkan filter tanggal dan pemasok yang diterima dari permintaan.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Maatwebsite\Excel\Excel
     */
    public function exportExcel(Request $request)
    {
        // Mengekspor data pembelian berdasarkan filter tanggal dan pemasok ke dalam file Excel
        return Excel::download(new PembelianExport($request->tanggal_mulai, $request->tanggal_selesai, $request->pemasok_id), 'laporan_pembelian.xlsx');
    }
}