<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Http\Request;

class LaporanStokController extends Controller
{
    public function index(Request $request)
    {
        $kategori = Kategori::all();

        $query = Barang::query();

        if ($request->has('kategori') && $request->kategori != '') {
            $query->where('kategori_id', $request->kategori);
        }

        if ($request->stok == 'hampir_habis') {
            $query->whereColumn('stok', '<=', 'stok_minimal');
        } elseif ($request->stok == 'tidak_laku') {
        }

        $barang = $query->get();

        return view('manajer.laporan.stok.index', compact('barang', 'kategori'));
    }
}