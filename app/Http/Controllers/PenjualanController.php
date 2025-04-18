<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penjualan;
use App\Models\DetailPenjualan;
use App\Models\Barang;
use App\Models\Pelanggan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

class PenjualanController extends Controller
{
    /**
     * Menampilkan daftar penjualan.
     *
     * Fungsi ini mengambil data penjualan yang telah dilakukan dan menampilkannya di halaman index penjualan.
     * Penjualan yang ditampilkan termasuk detail barang yang dibeli.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $penjualans = Penjualan::with('detailPenjualan.barang')->orderBy('created_at', 'desc')->paginate(10);
        return view('kasir.penjualan.index', compact('penjualans'));
    }

    /**
     * Menampilkan halaman form untuk membuat penjualan baru.
     *
     * Fungsi ini mengambil data pelanggan dan barang yang tersedia (dengan stok lebih dari 1) untuk ditampilkan
     * di halaman pembuatan penjualan.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $pelanggans = Pelanggan::all();
        $barangs = Barang::where('stok', '>', 1)->get();

        return view('kasir.penjualan.create', compact('pelanggans', 'barangs'));
    }

    /**
     * Menyimpan data penjualan baru.
     *
     * Fungsi ini memvalidasi data keranjang yang diterima, menyimpan penjualan ke dalam database, dan mengurangi stok barang
     * yang terjual. Jika terjadi kesalahan, perubahan dibatalkan dan transaksi dibatalkan.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'keranjang_data' => 'required',
        ]);

        $keranjang = json_decode($request->keranjang_data, true);
        if (!$keranjang) {
            return back()->with('error', 'Keranjang tidak boleh kosong.');
        }

        DB::beginTransaction();
        try {
            $noFaktur = 'INV-' . now()->format('Ymd') . '-' . str_pad(Penjualan::count() + 1, 4, '0', STR_PAD_LEFT);

            $penjualan = Penjualan::create([
                'no_faktur' => $noFaktur,
                'tgl_faktur' => now(),
                'total_bayar' => 0,
                'pelanggan_id' => $request->pelanggan_id ?? null,
                'user_id' => Auth::id(),
                'status_pembayaran' => 'pending',
            ]);

            $totalHarga = 0;
            foreach ($keranjang as $barangId => $item) {
                $barang = Barang::findOrFail($barangId);
                $jumlah = $item['jumlah'];
                $subTotal = $barang->harga_jual * $jumlah;

                DetailPenjualan::create([
                    'penjualan_id' => $penjualan->id,
                    'barang_id' => $barangId,
                    'harga_jual' => $barang->harga_jual,
                    'jumlah' => $jumlah,
                    'sub_total' => $subTotal,
                ]);

                $barang->decrement('stok', $jumlah);
                $totalHarga += $subTotal;
            }

            $penjualan->update(['total_bayar' => $totalHarga]);

            DB::commit();

            return redirect()->route('penjualan.pembayaran', ['id' => $penjualan->id]);
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan halaman pembayaran untuk penjualan tertentu.
     *
     * Fungsi ini mengambil data penjualan berdasarkan ID dan menampilkan halaman pembayaran untuk penjualan tersebut.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function pembayaran($id)
    {
        $penjualan = Penjualan::with('detailPenjualan.barang')->findOrFail($id);
        return view('kasir.penjualan.pembayaran', compact('penjualan'));
    }

    /**
     * Memproses pembayaran penjualan.
     *
     * Fungsi ini memproses pembayaran berdasarkan uang yang diterima dari pelanggan dan menghitung kembalian.
     * Jika pembayaran berhasil, status pembayaran diperbarui dan struk dicetak.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function prosesPembayaran(Request $request, $id)
    {
        $request->validate([
            'uang_diterima' => 'required|numeric|min:0',
        ]);

        $penjualan = Penjualan::with('detailPenjualan.barang')->findOrFail($id);
        $uangDiterima = $request->uang_diterima;
        $totalBayar = $penjualan->total_bayar;

        if ($uangDiterima < $totalBayar) {
            return back()->with('error', 'Uang yang diberikan kurang.');
        }

        $kembalian = $uangDiterima - $totalBayar;

        $penjualan->update([
            'status_pembayaran' => 'lunas',
        ]);

        try {
            // Mencetak struk pembayaran menggunakan printer POS
            $connector = new WindowsPrintConnector("POS-58");
            $printer = new Printer($connector);

            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("SKYMARKET\n");
            $printer->text("Jl. Siliwangi No.666\n");
            $printer->text("-----------------------------\n");

            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text("No Faktur: {$penjualan->no_faktur}\n");
            $printer->text("Tanggal  : " . $penjualan->tgl_faktur->format('d-m-Y H:i') . "\n");
            $printer->text("-----------------------------\n");

            foreach ($penjualan->detailPenjualan as $item) {
                $nama = $item->barang->nama_barang;
                $qty = $item->jumlah;
                $harga = number_format($item->harga_jual, 0, ',', '.');
                $subtotal = number_format($item->sub_total, 0, ',', '.');

                $printer->text("{$nama}\n");
                $printer->text("  {$qty} x Rp{$harga} = Rp{$subtotal}\n");
            }

            $printer->text("-----------------------------\n");
            $printer->text("Total     : Rp" . number_format($totalBayar, 0, ',', '.') . "\n");
            $printer->text("Bayar     : Rp" . number_format($uangDiterima, 0, ',', '.') . "\n");
            $printer->text("Kembalian : Rp" . number_format($kembalian, 0, ',', '.') . "\n");
            $printer->text("-----------------------------\n");

            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("TERIMA KASIH\n");
            $printer->pulse();
            $printer->feed(3);
            $printer->cut();

            $printer->close();

            return redirect()->route('penjualan.index')->with('success', 'Pembayaran berhasil & struk dicetak.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mencetak struk: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan detail penjualan berdasarkan ID.
     *
     * Fungsi ini menampilkan detail lengkap dari penjualan tertentu, termasuk barang yang dibeli.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $penjualan = Penjualan::with('detailPenjualan.barang')->findOrFail($id);
        return view('kasir.penjualan.show', compact('penjualan'));
    }
}