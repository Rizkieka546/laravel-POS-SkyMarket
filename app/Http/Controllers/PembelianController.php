<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembelian;
use App\Models\DetailPembelian;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Pemasok;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Exports\PembelianExport;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class PembelianController extends Controller
{
    /**
     * Menampilkan daftar pembelian.
     *
     * Fungsi ini mengambil semua data pembelian dengan relasi pemasok dan user,
     * dan menampilkannya di halaman index pembelian.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $pembelian = Pembelian::with('pemasok', 'user')->orderBy('tanggal_masuk', 'desc')->paginate(10);
        return view('admin.pembelian.index', compact('pembelian'));
    }

    /**
     * Menampilkan form untuk menambah pembelian baru.
     *
     * Fungsi ini akan menampilkan form pembelian baru dengan pilihan pemasok dan kategori.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $pemasok = Pemasok::all();
        $kategori = Kategori::all();
        return view('admin.pembelian.create', compact('pemasok', 'kategori'));
    }

    /**
     * Menyimpan pembelian baru.
     *
     * Fungsi ini akan memvalidasi input dan menyimpan data pembelian baru
     * beserta barang yang dibeli ke dalam database dalam sebuah transaksi.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'pemasok_id' => 'required|exists:pemasok,id',
            'kategori_id' => 'required|exists:kategori,id',
            'nama_barang' => 'required|string|max:255',
            'satuan' => 'required|string',
            'harga_beli' => 'required|numeric|min:1',
            'jumlah' => 'required|integer|min:1',
        ]);

        // Menjalankan transaksi untuk menyimpan data pembelian dan barang
        DB::transaction(function () use ($request) {
            // Membuat kode barang baru jika belum ada
            $kode_barang = 'BRG-' . mt_rand(100000, 999999);

            $barang = Barang::where('nama_barang', $request->nama_barang)
                ->where('kategori_id', $request->kategori_id)
                ->first();

            if (!$barang) {
                // Jika barang belum ada, buat barang baru
                $barang = Barang::create([
                    'kode_barang' => $kode_barang,
                    'nama_barang' => $request->nama_barang,
                    'satuan' => $request->satuan,
                    'kategori_id' => $request->kategori_id,
                    'stok' => 0,
                    'harga_beli' => $request->harga_beli,
                    'harga_jual' => $request->harga_beli * 1.2,
                    'user_id' => Auth::id(),
                ]);
            } else {
                // Jika barang sudah ada, update harga beli dan harga jual
                $barang->update([
                    'satuan' => $request->satuan,
                    'harga_beli' => $request->harga_beli,
                    'harga_jual' => $request->harga_beli * 1.2,
                ]);
            }

            // Membuat pembelian baru
            $pembelian = Pembelian::create([
                'kode_masuk' => 'PB-' . str_pad(Pembelian::count() + 1, 3, '0', STR_PAD_LEFT),
                'tanggal_masuk' => now(),
                'total' => $request->harga_beli * $request->jumlah,
                'user_id' => Auth::id(),
                'pemasok_id' => $request->pemasok_id
            ]);

            // Menyimpan detail pembelian
            DetailPembelian::create([
                'pembelian_id' => $pembelian->id,
                'barang_id' => $barang->id,
                'harga_beli' => $request->harga_beli,
                'jumlah' => $request->jumlah,
                'sub_total' => $request->harga_beli * $request->jumlah,
            ]);

            // Menambah stok barang
            $barang->increment('stok', $request->jumlah);
        });

        // Redirect ke halaman daftar pembelian dengan pesan sukses
        return redirect()->route('pembelian.index')->with('success', 'Pembelian berhasil disimpan.');
    }

    /**
     * Mencari barang berdasarkan nama.
     *
     * Fungsi ini akan mencari barang berdasarkan input query nama barang
     * dan mengembalikan hasil pencarian berupa 5 barang yang ditemukan.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchBarang(Request $request)
    {
        $query = $request->input('query');
        $barang = Barang::where('nama_barang', 'LIKE', "%{$query}%")
            ->limit(5)
            ->get();

        return response()->json($barang);
    }

    /**
     * Menampilkan detail pembelian.
     *
     * Fungsi ini akan menampilkan detail pembelian berdasarkan id pembelian yang dipilih.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $pembelian = Pembelian::with('pemasok', 'user', 'detailPembelian.barang')->findOrFail($id);
        return view('admin.pembelian.show', compact('pembelian'));
    }

    /**
     * Mengekspor data pembelian ke dalam format Excel.
     *
     * Fungsi ini akan mengunduh file pembelian dalam format Excel.
     *
     * @return \Maatwebsite\Excel\Excel
     */
    public function exportExcel()
    {
        return Excel::download(new PembelianExport, 'pembelian.xlsx');
    }

    /**
     * Mengekspor data pembelian ke dalam format PDF.
     *
     * Fungsi ini akan mengunduh file pembelian dalam format PDF.
     *
     * @return \Barryvdh\DomPDF\PDF
     */
    public function exportPdf()
    {
        $pembelian = Pembelian::with('pemasok')->get();

        $pdf = PDF::loadView('admin.pembelian.export_pdf', compact('pembelian'));
        return $pdf->download('pembelian.pdf');
    }
}