<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengajuan;

class PengajuanController extends Controller
{
    public function index()
    {
        $pengajuans = Pengajuan::all();
        return view('pelanggan.pengajuan.index', compact('pengajuans'));
    }


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
    public function update(Request $request, $id)
    {
        $pengajuan = Pengajuan::findOrFail($id);

        if ($pengajuan->nama_pengaju !== auth()->user()->name) {
            return redirect()->route('pengajuan.index')->with('error', 'Anda tidak memiliki izin untuk mengedit pengajuan ini.');
        }

        $request->validate([
            'nama_barang' => 'required|string|max:100',
            'tanggal_pengajuan' => 'required|date',
            'qty' => 'required|integer|min:1',
        ]);

        $pengajuan->update([
            'nama_barang' => $request->nama_barang,
            'tanggal_pengajuan' => $request->tanggal_pengajuan,
            'qty' => $request->qty,
        ]);

        return redirect()->route('pengajuan.index')->with('success', 'Pengajuan berhasil diperbarui');
    }


    public function destroy($id)
    {
        $pengajuan = Pengajuan::findOrFail($id);
        $pengajuan->delete();

        return redirect()->route('pengajuan.index')->with('success', 'Pengajuan berhasil dihapus');
    }
}