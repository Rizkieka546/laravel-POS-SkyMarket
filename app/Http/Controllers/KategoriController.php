<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    /**
     * Menampilkan semua kategori yang ada dalam database.
     *
     * Metode ini mengambil semua data kategori dari model `Kategori` dan mengirimkannya ke tampilan `admin.kategori.index`
     * untuk ditampilkan kepada pengguna.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Mengambil semua kategori dari database
        $kategori = Kategori::all();
        // Menampilkan tampilan kategori dengan data kategori yang diambil
        return view('admin.kategori.index', compact('kategori'));
    }

    /**
     * Menyimpan kategori baru ke dalam database.
     *
     * Metode ini melakukan validasi terhadap input dari pengguna, memastikan bahwa nama kategori tidak kosong dan
     * memiliki panjang maksimal 255 karakter. Setelah itu, kategori baru akan disimpan ke dalam tabel `kategori`.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validasi input dari pengguna
        $request->validate([
            'nama_kategori' => 'required|string|max:255'
        ]);

        // Membuat kategori baru di database
        Kategori::create([
            'nama_kategori' => $request->nama_kategori
        ]);

        // Mengarahkan kembali ke halaman kategori dengan pesan sukses
        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil ditambahkan');
    }

    /**
     * Memperbarui kategori yang ada di database.
     *
     * Metode ini mencari kategori berdasarkan ID yang diberikan, kemudian melakukan validasi terhadap input nama kategori.
     * Jika valid, data kategori akan diperbarui di database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        // Mencari kategori berdasarkan ID
        $kategori = Kategori::findOrFail($id);

        // Validasi input nama kategori
        $request->validate([
            'nama_kategori' => 'required|string|max:255'
        ]);

        // Memperbarui data kategori di database
        $kategori->update([
            'nama_kategori' => $request->nama_kategori
        ]);

        // Mengarahkan kembali ke halaman kategori dengan pesan sukses
        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil diperbarui');
    }

    /**
     * Menghapus kategori dari database.
     *
     * Metode ini mencari kategori berdasarkan ID yang diberikan dan kemudian menghapusnya dari database.
     * Setelah itu, pengguna akan diarahkan kembali ke halaman kategori dengan pesan sukses.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        // Mencari kategori berdasarkan ID
        $kategori = Kategori::findOrFail($id);
        // Menghapus kategori dari database
        $kategori->delete();
        // Mengarahkan kembali ke halaman kategori dengan pesan sukses
        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil dihapus');
    }
}