<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Http\Request;

class LaporanStokController extends Controller
{
    /**
     * Menampilkan laporan stok barang berdasarkan filter kategori dan stok.
     *
     * Fungsi ini menangani permintaan untuk menampilkan laporan stok barang. Laporan ini dapat difilter
     * berdasarkan kategori dan kondisi stok barang (hampir habis). Jika tidak ada filter yang diberikan,
     * maka seluruh barang akan ditampilkan.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Mengambil semua kategori yang ada di tabel Kategori
        $kategori = Kategori::all();

        // Menyiapkan query untuk mengambil data barang
        $query = Barang::query();

        // Memeriksa apakah ada filter berdasarkan kategori yang dipilih
        if ($request->has('kategori') && $request->kategori != '') {
            // Menambahkan kondisi where pada query untuk filter kategori
            $query->where('kategori_id', $request->kategori);
        }

        // Memeriksa kondisi stok yang dipilih oleh pengguna
        if ($request->stok == 'hampir_habis') {
            // Menambahkan kondisi where untuk barang yang stoknya hampir habis
            $query->whereColumn('stok', '<=', 'stok_minimal');
        } elseif ($request->stok == 'tidak_laku') {
            // Kondisi untuk barang yang tidak laku dapat ditambahkan di sini
            // Saat ini belum ada implementasi untuk kondisi 'tidak_laku'
        }

        // Mengambil data barang berdasarkan query yang sudah difilter
        $barang = $query->get();

        // Mengembalikan tampilan 'manajer.laporan.stok.index' dengan data barang dan kategori
        return view('manajer.laporan.stok.index', compact('barang', 'kategori'));
    }
}