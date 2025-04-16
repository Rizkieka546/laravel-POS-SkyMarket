<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengajuan;

class PengajuanController extends Controller
{
    /**
     * Menampilkan daftar pengajuan.
     *
     * Fungsi ini mengambil semua data pengajuan dan menampilkannya di halaman index pengajuan.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $pengajuans = Pengajuan::all();
        return view('pelanggan.pengajuan.index', compact('pengajuans'));
    }

    /**
     * Menyimpan pengajuan baru.
     *
     * Fungsi ini akan memvalidasi input dari pengguna dan menyimpan pengajuan barang baru
     * ke dalam database dengan status 'pending'.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:100',
            'tanggal_pengajuan' => 'required|date',
            'qty' => 'required|integer|min:1',
        ]);

        Pengajuan::create([
            'nama_pengaju' => auth()->user()->name,
            'nama_barang' => $request->nama_barang,
            'tanggal_pengajuan' => $request->tanggal_pengajuan,
            'qty' => $request->qty,
            'status' => 'pending'
        ]);

        return redirect()->route('pengajuan.index')->with('success', 'Pengajuan berhasil ditambahkan');
    }

    /**
     * Memperbarui pengajuan yang sudah ada.
     *
     * Fungsi ini akan memvalidasi input dan memperbarui pengajuan yang ada.
     * Hanya pengguna yang membuat pengajuan tersebut yang dapat melakukan update.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $pengajuan = Pengajuan::findOrFail($id);

        // Memeriksa apakah pengajuan ini milik pengguna yang sedang login
        if ($pengajuan->nama_pengaju !== auth()->user()->name) {
            return redirect()->route('pengajuan.index')->with('error', 'Anda tidak memiliki izin untuk mengedit pengajuan ini.');
        }

        $request->validate([
            'nama_barang' => 'required|string|max:100',
            'tanggal_pengajuan' => 'required|date',
            'qty' => 'required|integer|min:1',
        ]);

        // Memperbarui data pengajuan
        $pengajuan->update([
            'nama_barang' => $request->nama_barang,
            'tanggal_pengajuan' => $request->tanggal_pengajuan,
            'qty' => $request->qty,
        ]);

        return redirect()->route('pengajuan.index')->with('success', 'Pengajuan berhasil diperbarui');
    }

    /**
     * Menghapus pengajuan.
     *
     * Fungsi ini akan menghapus pengajuan berdasarkan ID yang diberikan.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $pengajuan = Pengajuan::findOrFail($id);
        $pengajuan->delete();

        return redirect()->route('pengajuan.index')->with('success', 'Pengajuan berhasil dihapus');
    }
}