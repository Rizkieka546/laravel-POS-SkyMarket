<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Pemasok;
use App\Models\Pembelian;
use App\Models\Pengajuan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Statistik Utama
        $totalBarang = Barang::count();
        $totalPembelianBulanIni = Pembelian::whereMonth('tanggal_masuk', Carbon::now()->month)->count();
        $totalPemasok = Pemasok::count();
        $barangKurang = Barang::whereColumn('stok', '<=', 'stok_minimal')->get();

        // Grafik Pembelian 6 Bulan Terakhir
        $pembelianBulanan = Pembelian::selectRaw('MONTH(tanggal_masuk) as bulan, SUM(total) as total')
            ->whereYear('tanggal_masuk', Carbon::now()->year)
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();



        // Grafik Stok Barang Berdasarkan Kategori
        $stokPerKategori = Barang::leftJoin('kategori as k', 'barang.kategori_id', '=', 'k.id')
            ->selectRaw('k.nama_kategori, COALESCE(SUM(barang.stok), 0) as total_stok')
            ->groupBy('k.nama_kategori')
            ->get();

        return view('dashboard.dashboard-admin', compact(
            'totalBarang',
            'totalPembelianBulanIni',
            'totalPemasok',
            'barangKurang',
            'pembelianBulanan',
            'stokPerKategori'
        ));
    }

    public function indexPengajuan()
    {
        $pengajuan = Pengajuan::orderByRaw("FIELD(status, 'pending') DESC")->paginate(5);
        return view('admin.pengajuan.index', compact('pengajuan'));
    }

    public function createPengajuan()
    {
        return view('admin.pengajuan.create');
    }


    public function storePengajuan(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'tanggal_pengajuan' => 'required|date',
            'qty' => 'required|integer|min:1',
        ]);

        Pengajuan::create([
            'nama_pengaju' => auth()->user()->name,
            'nama_barang' => $request->nama_barang,
            'tanggal_pengajuan' => $request->tanggal_pengajuan,
            'qty' => $request->qty,
            'status' => 'pending',
        ]);

        return redirect()->route('pengajuan.admin')->with('success', 'Pengajuan berhasil ditambahkan.');
    }

    public function editPengajuan($id)
    {
        $pengajuan = Pengajuan::findOrFail($id);
        return view('admin.pengajuan.edit', compact('pengajuan'));
    }

    public function updatePengajuan(Request $request, $id)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'tanggal_pengajuan' => 'required|date',
            'qty' => 'required|integer|min:1',
        ]);

        $pengajuan = Pengajuan::findOrFail($id);
        $pengajuan->update($request->all());

        return redirect()->route('pengajuan.admin')->with('success', 'Pengajuan berhasil diperbarui.');
    }


    public function updateStatus(Request $request, $id)
    {
        $pengajuan = Pengajuan::findOrFail($id);

        // Cek apakah status yang dipilih 'terpenuhi' atau 'batal'
        $newStatus = $pengajuan->status == 'terpenuhi' ? 'batal' : 'terpenuhi';

        // Update status pengajuan
        $pengajuan->status = $newStatus;
        $pengajuan->save();

        return redirect()->route('pengajuan.admin')->with('success', 'Status pengajuan berhasil diperbarui.');
    }
}