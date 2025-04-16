<?php

namespace App\Http\Controllers;

use App\Models\Pemasok;
use Illuminate\Http\Request;

class PemasokController extends Controller
{
    /**
     * Menampilkan daftar pemasok.
     *
     * Fungsi ini akan mengambil semua data pemasok dari database dan menampilkannya di halaman index pemasok.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Ambil semua data pemasok
        $pemasoks = Pemasok::all();

        // Kembalikan tampilan index dengan data pemasok
        return view('admin.pemasok.index', compact('pemasoks'));
    }

    /**
     * Menampilkan form untuk menambah pemasok baru.
     *
     * Fungsi ini akan menampilkan form pendaftaran pemasok baru.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Tampilkan form untuk membuat pemasok baru
        return view('admin.pemasok.create');
    }

    /**
     * Menyimpan pemasok baru.
     *
     * Fungsi ini akan memvalidasi input dan menyimpan data pemasok baru ke dalam database.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama_pemasok' => 'required|string|max:255',
            'alamat'       => 'nullable|string',
            'telepon'      => 'nullable|string|max:15',
            'email'        => 'nullable|email|max:255',
            'catatan'      => 'nullable|string'
        ]);

        // Simpan data pemasok baru
        Pemasok::create($request->all());

        // Redirect ke halaman daftar pemasok dengan pesan sukses
        return redirect()->route('pemasok.index')->with('success', 'Pemasok berhasil ditambahkan!');
    }

    /**
     * Menampilkan detail pemasok.
     *
     * Fungsi ini akan menampilkan detail dari pemasok yang dipilih.
     *
     * @param \App\Models\Pemasok $pemasok
     * @return \Illuminate\View\View
     */
    public function show(Pemasok $pemasok)
    {
        // Tampilkan detail pemasok
        return view('admin.pemasok.show', compact('pemasok'));
    }

    /**
     * Menampilkan form untuk mengedit pemasok yang ada.
     *
     * Fungsi ini akan menampilkan form untuk mengedit data pemasok yang sudah ada.
     *
     * @param \App\Models\Pemasok $pemasok
     * @return \Illuminate\View\View
     */
    public function edit(Pemasok $pemasok)
    {
        // Tampilkan form untuk mengedit pemasok
        return view('admin.pemasok.edit', compact('pemasok'));
    }

    /**
     * Memperbarui data pemasok yang ada.
     *
     * Fungsi ini akan memvalidasi input dan memperbarui data pemasok yang ada di database.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Pemasok $pemasok
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Pemasok $pemasok)
    {
        // Validasi input
        $request->validate([
            'nama_pemasok' => 'required|string|max:255',
            'alamat'       => 'nullable|string',
            'telepon'      => 'nullable|string|max:15',
            'email'        => 'nullable|email|max:255',
            'catatan'      => 'nullable|string'
        ]);

        // Perbarui data pemasok
        $pemasok->update($request->all());

        // Redirect ke halaman daftar pemasok dengan pesan sukses
        return redirect()->route('pemasok.index')->with('success', 'Pemasok berhasil diperbarui!');
    }

    /**
     * Menghapus pemasok.
     *
     * Fungsi ini akan menghapus pemasok yang dipilih dari database.
     *
     * @param \App\Models\Pemasok $pemasok
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Pemasok $pemasok)
    {
        // Hapus pemasok
        $pemasok->delete();

        // Redirect ke halaman daftar pemasok dengan pesan sukses
        return redirect()->route('pemasok.index')->with('success', 'Pemasok berhasil dihapus!');
    }
}