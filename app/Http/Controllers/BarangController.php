<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BarangController extends Controller
{
    /**
     * Menampilkan daftar barang
     * Mengambil semua barang dengan relasi kategori dan user
     */
    public function index()
    {
        // Mengambil data barang dengan relasi kategori dan user
        $barangs = Barang::with('kategori', 'user')->latest()->paginate(1);

        // Mengembalikan tampilan daftar barang
        return view('admin.barang.index', compact('barangs'));
    }

    /**
     * Menampilkan form untuk menambahkan barang baru
     * Mengambil semua kategori untuk ditampilkan dalam form
     */
    public function create()
    {
        // Mengambil semua kategori yang tersedia
        $kategoris = Kategori::all();

        // Mengembalikan tampilan form tambah barang dengan kategori
        return view('admin.barang.create', compact('kategoris'));
    }

    /**
     * Menyimpan barang baru ke database
     * Melakukan validasi data dan menyimpan barang ke tabel 'barang'
     */
    public function store(Request $request)
    {
        // Melakukan validasi input data
        $request->validate([
            'kategori_id' => 'required|exists:kategori,id', // Validasi kategori_id harus ada di tabel kategori
            'nama_barang' => 'required|string|max:255',     // Validasi nama_barang harus berupa string
            'satuan' => 'nullable|string|max:50',            // Validasi satuan bisa null dan maksimal 50 karakter
            'stok_minimal' => 'nullable|integer|min:1',      // Validasi stok_minimal minimal 1
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi gambar jika ada
        ]);

        // Menyimpan path gambar jika ada file gambar yang diunggah
        $gambarPath = null;
        if ($request->hasFile('gambar')) {
            $gambarPath = $request->file('gambar')->store('barang', 'public');
        }

        // Membuat data barang baru
        Barang::create([
            'kode_barang' => 'BRG-' . str_pad(Barang::count() + 1, 4, '0', STR_PAD_LEFT), // Menghasilkan kode barang unik
            'kategori_id' => $request->kategori_id,    // Menyimpan kategori_id yang dipilih
            'nama_barang' => $request->nama_barang,    // Menyimpan nama barang
            'satuan' => $request->satuan ?? 'pcs',     // Menyimpan satuan (default 'pcs' jika null)
            'harga_beli' => 0,                         // Menyimpan harga beli, default 0
            'harga_jual' => 0,                         // Menyimpan harga jual, default 0
            'stok' => 0,                               // Menyimpan stok, default 0
            'stok_minimal' => $request->stok_minimal ?? 1, // Menyimpan stok minimal, default 1
            'gambar' => $gambarPath,                   // Menyimpan path gambar jika ada
            'user_id' => Auth::id(),                   // Menyimpan ID user yang menambahkan barang
        ]);

        // Redirect ke halaman daftar barang dengan pesan sukses
        return redirect()->route('barang.index')->with('success', 'Barang baru berhasil ditambahkan');
    }

    /**
     * Menampilkan form untuk mengedit barang yang sudah ada
     * Mengambil data barang dan kategori untuk ditampilkan di form
     */
    public function edit($id)
    {
        // Mengambil data barang berdasarkan ID
        $barang = Barang::findOrFail($id);

        // Mengambil semua kategori untuk pilihan kategori
        $kategoris = Kategori::all();

        // Mengembalikan tampilan form edit barang
        return view('admin.barang.edit', compact('barang', 'kategoris'));
    }

    /**
     * Memperbarui data barang di database
     * Melakukan validasi dan update data barang berdasarkan ID
     */
    public function update(Request $request, $id)
    {
        // Melakukan validasi input data
        $request->validate([
            'nama_barang' => 'required|string|max:255',   // Validasi nama_barang
            'kategori_id' => 'required|exists:kategori,id', // Validasi kategori_id harus ada di tabel kategori
            'harga_jual' => 'required|numeric|min:1',     // Validasi harga_jual harus numerik dan lebih dari 0
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi gambar jika ada
        ]);

        // Mengambil data barang berdasarkan ID
        $barang = Barang::findOrFail($id);

        // Mengambil data yang ingin diupdate
        $data = $request->only(['nama_barang', 'kategori_id', 'harga_jual', 'stok', 'satuan']);

        // Jika ada gambar baru, hapus gambar lama dan simpan gambar baru
        if ($request->hasFile('gambar')) {
            if ($barang->gambar) {
                Storage::delete('public/' . $barang->gambar); // Menghapus gambar lama
            }
            // Menyimpan gambar baru
            $data['gambar'] = $request->file('gambar')->store('barang', 'public');
        }

        // Update data barang dengan data yang baru
        $barang->update($data);

        // Redirect ke halaman daftar barang dengan pesan sukses
        return redirect()->route('barang.index')->with('success', 'Data barang berhasil diperbarui!');
    }

    /**
     * Menghapus barang dari database
     * Menghapus gambar terkait dan data barang berdasarkan ID
     */
    public function destroy($id)
    {
        // Mengambil data barang berdasarkan ID
        $barang = Barang::findOrFail($id);

        // Jika ada gambar, hapus gambar dari penyimpanan
        if ($barang->gambar) {
            Storage::delete('public/' . $barang->gambar);
        }

        // Menghapus barang dari database
        $barang->delete();

        // Redirect ke halaman daftar barang dengan pesan sukses
        return redirect()->route('barang.index')->with('success', 'Barang berhasil dihapus');
    }
}