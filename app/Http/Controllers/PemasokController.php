<?php

namespace App\Http\Controllers;

use App\Models\Pemasok;
use Illuminate\Http\Request;

class PemasokController extends Controller
{
    public function index()
    {
        $pemasoks = Pemasok::all();
        return view('admin.pemasok.index', compact('pemasoks'));
    }

    public function create()
    {
        return view('admin.pemasok.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pemasok' => 'required|string|max:255',
            'alamat'       => 'nullable|string',
            'telepon'      => 'nullable|string|max:15',
            'email'        => 'nullable|email|max:255',
            'catatan'      => 'nullable|string'
        ]);

        Pemasok::create($request->all());
        return redirect()->route('pemasok.index')->with('success', 'Pemasok berhasil ditambahkan!');
    }

    public function show(Pemasok $pemasok)
    {
        return view('admin.pemasok.show', compact('pemasok'));
    }

    public function edit(Pemasok $pemasok)
    {
        return view('admin.pemasok.edit', compact('pemasok'));
    }

    public function update(Request $request, Pemasok $pemasok)
    {
        $request->validate([
            'nama_pemasok' => 'required|string|max:255',
            'alamat'       => 'nullable|string',
            'telepon'      => 'nullable|string|max:15',
            'email'        => 'nullable|email|max:255',
            'catatan'      => 'nullable|string'
        ]);

        $pemasok->update($request->all());
        return redirect()->route('pemasok.index')->with('success', 'Pemasok berhasil diperbarui!');
    }

    public function destroy(Pemasok $pemasok)
    {
        $pemasok->delete();
        return redirect()->route('pemasok.index')->with('success', 'Pemasok berhasil dihapus!');
    }
}