<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembelian;
use App\Models\DetailPembelian;
use App\Models\Barang;
use App\Models\Pemasok;
use Illuminate\Support\Facades\Auth;

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
        $barang = Barang::all();
        return view('admin.pembelian.create', compact('pemasok', 'barang'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_masuk' => 'required|date',
            'pemasok_id' => 'required|exists:pemasok,id',
            'barang_id' => 'required|array',
            'barang_id.*' => 'exists:barang,id',
            'harga_beli' => 'required|array',
            'harga_beli.*' => 'numeric|min:1',
            'jumlah' => 'required|array',
            'jumlah.*' => 'integer|min:1',
        ]);

        // Generate kode_masuk otomatis
        $lastKode = Pembelian::latest()->first()?->kode_masuk ?? 'PB-000';
        $nextKode = 'PB-' . str_pad((int) substr($lastKode, 3) + 1, 3, '0', STR_PAD_LEFT);

        // Simpan pembelian
        $pembelian = Pembelian::create([
            'kode_masuk' => $nextKode,
            'tanggal_masuk' => $request->tanggal_masuk,
            'total' => 0, // Akan dihitung nanti
            'pemasok_id' => $request->pemasok_id,
            'user_id' => Auth::id(),
        ]);

        $total = 0;
        foreach ($request->barang_id as $key => $barangId) {
            $hargaBeli = $request->harga_beli[$key];
            $hargaJual = $hargaBeli + ($hargaBeli * 0.05); // Tambahkan 5% ke harga_beli
            $jumlah = $request->jumlah[$key];
            $subTotal = $hargaBeli * $jumlah;
            $total += $subTotal;

            // Simpan detail pembelian
            DetailPembelian::create([
                'pembelian_id' => $pembelian->id,
                'barang_id' => $barangId,
                'harga_beli' => $hargaBeli,
                'jumlah' => $jumlah,
                'sub_total' => $subTotal,
            ]);

            // Update stok barang dan harga jual
            $barang = Barang::find($barangId);
            $barang->increment('stok', $jumlah);
            $barang->update(['harga_jual' => $hargaJual]); // Update harga jual
        }

        // Update total pembelian
        $pembelian->update(['total' => $total]);

        return redirect()->route('pembelian.index')->with('success', 'Pembelian berhasil ditambahkan dan kode masuk otomatis dibuat');
    }

    public function show($id)
    {
        $pembelian = Pembelian::with('pemasok', 'user', 'detailPembelian.barang')->findOrFail($id);
        return view('admin.pembelian.show', compact('pembelian'));
    }
}