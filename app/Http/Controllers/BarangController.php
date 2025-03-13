<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BarangController extends Controller
{
    public function index()
    {
        $barangs = Barang::with('kategori', 'user')->latest()->get();
        return view('admin.barang.index', compact('barangs'));
    }

    public function create()
    {
        $kategoris = Kategori::all();
        return view('admin.barang.create', compact('kategoris'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'kategori_id' => 'required|exists:kategori,id',
            'nama_barang' => 'required|string|max:255',
            'satuan' => 'nullable|string|max:50',
            'harga_beli' => 'nullable|numeric|min:0',
            'harga_jual' => 'nullable|numeric|min:0',
            'stok' => 'nullable|integer|min:0',
            'stok_minimal' => 'nullable|integer|min:1',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'ditarik' => 'boolean',
        ]);

        $gambarPath = null;
        if ($request->hasFile('gambar')) {
            $gambarPath = $request->file('gambar')->store('barang', 'public');
        }

        Barang::create([
            'kode_barang' => 'BRG-' . str_pad(Barang::count() + 1, 4, '0', STR_PAD_LEFT),
            'kategori_id' => $request->kategori_id,
            'nama_barang' => $request->nama_barang,
            'satuan' => $request->satuan ?? 'pcs',
            'harga_beli' => $request->harga_beli ?? 0,
            'harga_jual' => $request->harga_jual ?? 0,
            'stok' => $request->stok ?? 0,
            'stok_minimal' => $request->stok_minimal ?? 1,
            'gambar' => $gambarPath,
            'ditarik' => $request->ditarik ?? 0,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('barang.index')->with('success', 'Barang baru berhasil ditambahkan');
    }

    public function edit($id)
    {
        $barang = Barang::findOrFail($id);
        $kategoris = Kategori::all();
        return view('admin.barang.edit', compact('barang', 'kategoris'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori,id',
            'harga_jual' => 'required|numeric|min:1',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $barang = Barang::findOrFail($id);

        $data = $request->only(['nama_barang', 'kategori_id', 'harga_jual', 'stok', 'satuan']);

        if ($request->hasFile('gambar')) {
            if ($barang->gambar) {
                Storage::delete('public/' . $barang->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('barang', 'public');
        }

        $barang->update($data);

        return redirect()->route('barang.index')->with('success', 'Data barang berhasil diperbarui!');
    }



    public function destroy($id)
    {
        $barang = Barang::findOrFail($id);
        $barang->delete();

        return redirect()->route('barang.index')->with('success', 'Data Barang berhasil dihapus');
    }
}