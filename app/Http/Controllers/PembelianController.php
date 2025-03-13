<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembelian;
use App\Models\DetailPembelian;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Pemasok;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PembelianController extends Controller
{
    public function index()
    {
        $pembelian = Pembelian::with('pemasok', 'user')->orderBy('tanggal_masuk', 'desc')->get();
        return view('admin.pembelian.index', compact('pembelian'));
    }

    public function create()
    {
        $pemasok = Pemasok::all();
        $kategori = Kategori::all();
        return view('admin.pembelian.create', compact('pemasok', 'kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pemasok_id' => 'required|exists:pemasok,id',
            'kategori_id' => 'required|exists:kategori,id',
            'nama_barang' => 'required|string|max:255',
            'satuan' => 'required|string',
            'harga_beli' => 'required|numeric|min:1',
            'jumlah' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($request) {
            $kode_barang = 'BRG-' . mt_rand(100000, 999999);

            $barang = Barang::where('nama_barang', $request->nama_barang)
                ->where('kategori_id', $request->kategori_id)
                ->first();

            if (!$barang) {
                $barang = Barang::create([
                    'kode_barang' => $kode_barang,
                    'nama_barang' => $request->nama_barang,
                    'satuan' => $request->satuan,
                    'kategori_id' => $request->kategori_id,
                    'stok' => 0,
                    'harga_beli' => $request->harga_beli,
                    'harga_jual' => $request->harga_beli * 1.2,
                    'user_id' => Auth::id(),
                ]);
            } else {
                $barang->update([
                    'satuan' => $request->satuan,
                    'harga_beli' => $request->harga_beli,
                    'harga_jual' => $request->harga_beli * 1.2,
                ]);
            }

            $pembelian = Pembelian::create([
                'kode_masuk' => 'PB-' . str_pad(Pembelian::count() + 1, 3, '0', STR_PAD_LEFT),
                'tanggal_masuk' => now(),
                'total' => $request->harga_beli * $request->jumlah,
                'user_id' => Auth::id(),
                'pemasok_id' => $request->pemasok_id
            ]);

            DetailPembelian::create([
                'pembelian_id' => $pembelian->id,
                'barang_id' => $barang->id,
                'harga_beli' => $request->harga_beli,
                'jumlah' => $request->jumlah,
                'sub_total' => $request->harga_beli * $request->jumlah,
            ]);

            $barang->increment('stok', $request->jumlah);
        });

        return redirect()->route('pembelian.index')->with('success', 'Pembelian berhasil disimpan.');
    }

    public function searchBarang(Request $request)
    {
        $query = $request->input('query');
        $barang = Barang::where('nama_barang', 'LIKE', "%{$query}%")
            ->limit(5)
            ->get();

        return response()->json($barang);
    }

    public function show($id)
    {
        $pembelian = Pembelian::with('pemasok', 'user', 'detailPembelian.barang')->findOrFail($id);
        return view('admin.pembelian.show', compact('pembelian'));
    }
}