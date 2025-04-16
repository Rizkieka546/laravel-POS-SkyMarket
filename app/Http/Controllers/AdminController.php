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
    /**
     * Menampilkan dashboard admin dengan statistik dan grafik terkait pembelian dan stok barang.
     *
     * Fungsi ini akan mengambil data statistik utama seperti jumlah barang, jumlah pembelian bulan ini, 
     * jumlah pemasok, dan daftar barang dengan stok yang kurang dari atau sama dengan stok minimal. 
     * Selain itu, fungsi ini juga mengambil data pembelian selama 6 bulan terakhir dan stok barang berdasarkan kategori.
     *
     * @return \Illuminate\View\View
     */
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

        // Menampilkan data statistik ke view dashboard-admin
        return view('dashboard.dashboard-admin', compact(
            'totalBarang',
            'totalPembelianBulanIni',
            'totalPemasok',
            'barangKurang',
            'pembelianBulanan',
            'stokPerKategori'
        ));
    }

    /**
     * Menampilkan daftar pengajuan yang ada pada sistem dengan urutan berdasarkan status 'pending'.
     *
     * Fungsi ini mengambil daftar pengajuan yang ada dan menampilkan pengajuan yang statusnya 'pending' 
     * terlebih dahulu. Daftar pengajuan ini akan dipaginasi untuk memudahkan navigasi dalam jumlah data yang besar.
     *
     * @return \Illuminate\View\View
     */
    public function indexPengajuan()
    {
        $pengajuan = Pengajuan::orderByRaw("FIELD(status, 'pending') DESC")->paginate(5);
        return view('admin.pengajuan.index', compact('pengajuan'));
    }

    /**
     * Menampilkan form untuk membuat pengajuan baru.
     *
     * Fungsi ini hanya menampilkan form kosong untuk membuat pengajuan baru 
     * yang berisi kolom-kolom yang diperlukan seperti nama barang, tanggal pengajuan, dan jumlah.
     *
     * @return \Illuminate\View\View
     */
    public function createPengajuan()
    {
        return view('admin.pengajuan.create');
    }

    /**
     * Menyimpan pengajuan baru ke dalam sistem.
     *
     * Fungsi ini menangani penyimpanan pengajuan barang baru dari user 
     * dengan melakukan validasi terlebih dahulu pada form input. Setelah data tervalidasi, pengajuan akan disimpan.
     * Status pengajuan akan diset ke 'pending'.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storePengajuan(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'tanggal_pengajuan' => 'required|date',
            'qty' => 'required|integer|min:1',
        ]);

        // Menyimpan data pengajuan ke database
        Pengajuan::create([
            'nama_pengaju' => auth()->user()->name,
            'nama_barang' => $request->nama_barang,
            'tanggal_pengajuan' => $request->tanggal_pengajuan,
            'qty' => $request->qty,
            'status' => 'pending',
        ]);

        // Redirect ke halaman pengajuan admin dengan pesan sukses
        return redirect()->route('pengajuan.admin')->with('success', 'Pengajuan berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit pengajuan yang sudah ada.
     *
     * Fungsi ini mengambil data pengajuan berdasarkan ID yang diberikan, 
     * dan menampilkan form pengeditan data pengajuan untuk diubah oleh admin.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function editPengajuan($id)
    {
        $pengajuan = Pengajuan::findOrFail($id);
        return view('admin.pengajuan.edit', compact('pengajuan'));
    }

    /**
     * Memperbarui data pengajuan yang sudah ada.
     *
     * Fungsi ini menangani pembaruan data pengajuan yang sudah ada. Setelah 
     * data tervalidasi dan diperbarui, pengajuan yang dimaksud akan disimpan dengan data yang baru.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePengajuan(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'tanggal_pengajuan' => 'required|date',
            'qty' => 'required|integer|min:1',
        ]);

        // Mengupdate data pengajuan
        $pengajuan = Pengajuan::findOrFail($id);
        $pengajuan->update($request->all());

        // Redirect ke halaman pengajuan admin dengan pesan sukses
        return redirect()->route('pengajuan.admin')->with('success', 'Pengajuan berhasil diperbarui.');
    }

    /**
     * Mengubah status pengajuan (terpenuhi atau batal).
     *
     * Fungsi ini memungkinkan admin untuk mengubah status pengajuan menjadi 'terpenuhi' atau 'batal' 
     * bergantung pada status pengajuan sebelumnya. Status ini disimpan setelah perubahan.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus(Request $request, $id)
    {
        $pengajuan = Pengajuan::findOrFail($id);

        // Cek apakah status yang dipilih 'terpenuhi' atau 'batal'
        $newStatus = $pengajuan->status == 'terpenuhi' ? 'batal' : 'terpenuhi';

        // Update status pengajuan
        $pengajuan->status = $newStatus;
        $pengajuan->save();

        // Redirect ke halaman pengajuan admin dengan pesan sukses
        return redirect()->route('pengajuan.admin')->with('success', 'Status pengajuan berhasil diperbarui.');
    }
}