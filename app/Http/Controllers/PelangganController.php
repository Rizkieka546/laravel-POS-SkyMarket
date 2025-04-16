<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    /**
     * Menampilkan dashboard pelanggan.
     *
     * Fungsi ini akan menampilkan halaman dashboard pelanggan dengan daftar barang yang tersedia.
     * Daftar barang akan diambil dari model Barang dan dikirim ke view untuk ditampilkan.
     *
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        // Ambil semua data barang dari database
        $barang = Barang::all();

        // Kembalikan tampilan dashboard dengan data barang
        return view('dashboard.dashboard-pelanggan', compact('barang'));
    }
}