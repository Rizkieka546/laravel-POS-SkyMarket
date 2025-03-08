<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penjualan;
use App\Models\DetailPenjualan;
use App\Models\Barang;
use App\Models\Pelanggan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PenjualanController extends Controller
{
    public function index()
    {
        $penjualans = Penjualan::with('detailPenjualan.barang')->orderBy('created_at', 'desc')->get();
        return view('admin.penjualan.index', compact('penjualans'));
    }

    public function create()
    {
        $pelanggans = Pelanggan::all();
        $barangs = Barang::where('stok', '>', 1)->get();

        return view('admin.penjualan.create', compact('pelanggans', 'barangs'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'keranjang_data' => 'required',
        ]);

        $keranjang = json_decode($request->keranjang_data, true);
        if (!$keranjang) {
            return back()->with('error', 'Keranjang tidak boleh kosong.');
        }

        DB::beginTransaction();
        try {
            $noFaktur = 'INV-' . now()->format('Ymd') . '-' . str_pad(Penjualan::count() + 1, 4, '0', STR_PAD_LEFT);

            $penjualan = Penjualan::create([
                'no_faktur' => $noFaktur,
                'tgl_faktur' => now(),
                'total_bayar' => 0,
                'pelanggan_id' => $request->pelanggan_id ?? null,
                'user_id' => Auth::id(),
            ]);

            $totalHarga = 0;
            foreach ($keranjang as $barangId => $item) {
                $barang = Barang::findOrFail($barangId);
                $jumlah = $item['jumlah'];
                $subTotal = $barang->harga_jual * $jumlah;

                DetailPenjualan::create([
                    'penjualan_id' => $penjualan->id,
                    'barang_id' => $barangId,
                    'harga_jual' => $barang->harga_jual,
                    'jumlah' => $jumlah,
                    'sub_total' => $subTotal,
                ]);

                $barang->decrement('stok', $jumlah);
                $totalHarga += $subTotal;
            }

            $penjualan->update(['total_bayar' => $totalHarga]);

            DB::commit();
            return redirect()->route('penjualan.index')->with('success', 'Transaksi berhasil disimpan');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }


    public function show($id)
    {
        $penjualan = Penjualan::with('detailPenjualan.barang')->findOrFail($id);
        return view('admin.penjualan.show', compact('penjualan'));
    }
}