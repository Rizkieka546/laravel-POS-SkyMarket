<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Penjualan;
use App\Models\DetailPenjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Auth;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;

class ScanController extends Controller
{
    /**
     * Menampilkan halaman form transaksi dan menampilkan keranjang belanja.
     *
     * Fungsi ini akan mengambil data keranjang belanja yang ada di sesi (Session)
     * dan menampilkan halaman `kasir.transaksi.create`, yang berisi daftar barang
     * yang ada di dalam keranjang belanja sementara.
     *
     * @return \Illuminate\View\View Tampilan halaman transaksi.
     */
    public function create()
    {
        // Mendapatkan data keranjang dari sesi
        $keranjang = Session::get('keranjang', []);

        // Mengembalikan tampilan form transaksi dengan data keranjang
        return view('kasir.transaksi.create', compact('keranjang'));
    }

    /**
     * Menambahkan barang ke dalam keranjang belanja.
     *
     * Fungsi ini akan memvalidasi input berupa kode barang dan jumlah yang ingin
     * dibeli. Jika barang ditemukan, barang tersebut akan ditambahkan atau jumlah
     *nya akan diperbarui di keranjang belanja yang ada di sesi.
     * 
     * @param \Illuminate\Http\Request $request Data input yang diterima dari form.
     * @return \Illuminate\Http\RedirectResponse Redirect kembali dengan pesan sukses atau error.
     */
    public function tambahBarang(Request $request)
    {
        // Validasi input untuk kode barang dan jumlah
        $request->validate([
            'kode_barang' => 'required|string',
            'jumlah' => 'required|integer|min:1'
        ]);

        // Mencari barang berdasarkan kode barang
        $barang = Barang::where('kode_barang', $request->kode_barang)->first();

        // Jika barang tidak ditemukan, tampilkan pesan error
        if (!$barang) {
            return redirect()->back()->with('error', 'Barang tidak ditemukan!');
        }

        // Mendapatkan data keranjang dari sesi
        $keranjang = Session::get('keranjang', []);
        $key = $barang->id;

        // Jika barang sudah ada di keranjang, update jumlah dan subtotal
        if (isset($keranjang[$key])) {
            $keranjang[$key]['jumlah'] += $request->jumlah;
            $keranjang[$key]['sub_total'] = $keranjang[$key]['jumlah'] * $barang->harga_jual;
        } else {
            // Jika barang belum ada, tambahkan barang baru ke keranjang
            $keranjang[$key] = [
                'id' => $barang->id,
                'kode_barang' => $barang->kode_barang,
                'nama_barang' => $barang->nama_barang,
                'harga_jual' => $barang->harga_jual,
                'jumlah' => $request->jumlah,
                'sub_total' => $barang->harga_jual * $request->jumlah
            ];
        }

        // Simpan kembali keranjang ke sesi
        Session::put('keranjang', $keranjang);

        // Kembali ke halaman dengan pesan sukses
        return redirect()->back()->with('success', 'Barang ditambahkan ke keranjang!');
    }

    /**
     * Menyimpan transaksi penjualan dan detailnya ke dalam database.
     *
     * Fungsi ini akan memvalidasi apakah ada pengguna yang login dan memastikan
     * keranjang belanja tidak kosong. Setelah itu, transaksi akan disimpan dalam
     * database dalam tabel `penjualan` dan `detail_penjualan`. Stok barang akan
     * dikurangi sesuai dengan jumlah yang dibeli.
     * 
     * @param \Illuminate\Http\Request $request Data input yang diterima dari form.
     * @return \Illuminate\Http\RedirectResponse Redirect ke halaman pembayaran setelah transaksi berhasil.
     */
    public function simpan(Request $request)
    {
        // Validasi apakah ada user yang login
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        // Mendapatkan data keranjang dari sesi
        $keranjang = Session::get('keranjang', []);

        // Jika keranjang kosong, tampilkan pesan error
        if (empty($keranjang)) {
            return redirect()->back()->with('error', 'Keranjang masih kosong!');
        }

        // Memulai transaksi database
        DB::beginTransaction();

        try {
            // Membuat nomor faktur berdasarkan tanggal dan urutan transaksi
            $noFaktur = 'INV-' . now()->format('Ymd') . '-' . str_pad(Penjualan::count() + 1, 4, '0', STR_PAD_LEFT);

            // Membuat entri transaksi penjualan baru
            $penjualan = Penjualan::create([
                'no_faktur' => $noFaktur,
                'tgl_faktur' => now(),
                'total_bayar' => 0,
                'pelanggan_id' => $request->pelanggan_id ?? null,
                'user_id' => Auth::id(),
                'status_pembayaran' => 'pending',
            ]);

            $totalHarga = 0;

            // Memproses setiap barang dalam keranjang
            foreach ($keranjang as $barangId => $item) {
                $barang = Barang::findOrFail($barangId);
                $jumlah = $item['jumlah'];
                $subTotal = $barang->harga_jual * $jumlah;

                // Menyimpan detail penjualan
                DetailPenjualan::create([
                    'penjualan_id' => $penjualan->id,
                    'barang_id' => $barangId,
                    'harga_jual' => $barang->harga_jual,
                    'jumlah' => $jumlah,
                    'sub_total' => $subTotal,
                ]);

                // Mengurangi stok barang
                $barang->decrement('stok', $jumlah);

                // Menambahkan subtotal ke total harga
                $totalHarga += $subTotal;
            }

            // Memperbarui total bayar pada penjualan
            $penjualan->update(['total_bayar' => $totalHarga]);

            // Menghapus keranjang setelah transaksi disimpan
            Session::forget('keranjang');

            // Menyimpan transaksi ke database dan commit transaksi
            DB::commit();

            // Mengarahkan pengguna ke halaman pembayaran
            return redirect()->route('transaksi.pembayaran', ['id' => $penjualan->id])
                ->with('success', 'Transaksi berhasil disimpan!');
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi error
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menyimpan transaksi: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan halaman pembayaran untuk transaksi tertentu.
     *
     * Fungsi ini akan menampilkan halaman pembayaran untuk transaksi yang
     * memiliki ID tertentu dan memuat detail barang yang dibeli.
     * 
     * @param int $id ID transaksi yang akan diproses.
     * @return \Illuminate\View\View Tampilan halaman pembayaran dengan detail transaksi.
     */
    public function pembayaran($id)
    {
        // Mengambil data penjualan beserta detail barang yang dibeli
        $penjualan = Penjualan::with('detailPenjualan.barang')->findOrFail($id);

        // Menampilkan halaman pembayaran dengan data transaksi
        return view('kasir.transaksi.pembayaran', compact('penjualan'));
    }

    /**
     * Memproses pembayaran dan mencetak struk transaksi.
     *
     * Fungsi ini memvalidasi jumlah uang yang diterima dan jika cukup,
     * transaksi akan diperbarui menjadi lunas. Setelah itu, struk transaksi
     * akan dicetak menggunakan printer POS.
     * 
     * @param \Illuminate\Http\Request $request Data input yang diterima dari form pembayaran.
     * @param int $id ID transaksi yang akan diproses.
     * @return \Illuminate\Http\RedirectResponse Redirect ke halaman transaksi setelah pembayaran berhasil.
     */
    public function prosesPembayaran(Request $request, $id)
    {
        // Validasi input uang yang diterima
        $request->validate([
            'uang_diterima' => 'required|numeric|min:1',
        ]);

        // Mengambil data penjualan dan total bayar
        $penjualan = Penjualan::with('detailPenjualan.barang')->findOrFail($id);
        $uangDiterima = $request->uang_diterima;
        $totalBayar = $penjualan->total_bayar;

        // Cek apakah uang yang diterima cukup
        if ($uangDiterima < $totalBayar) {
            return back()->with('error', 'Uang yang diberikan kurang.');
        }

        // Menghitung kembalian
        $kembalian = $uangDiterima - $totalBayar;

        // Memperbarui status pembayaran menjadi lunas
        $penjualan->update([
            'status_pembayaran' => 'lunas',
        ]);

        try {
            // Mengonfigurasi koneksi dan printer
            $connector = new WindowsPrintConnector("POS-58");
            $printer = new Printer($connector);

            // Menampilkan header struk
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("SKYMARKET\n");
            $printer->text("Jl. Siliwangi No.666\n");
            $printer->text("-----------------------------\n");

            // Menampilkan detail transaksi
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text("No Faktur: {$penjualan->no_faktur}\n");
            $printer->text("Tanggal  : " . $penjualan->tgl_faktur->format('d-m-Y H:i') . "\n");
            $printer->text("-----------------------------\n");

            // Menampilkan setiap barang yang dibeli
            foreach ($penjualan->detailPenjualan as $item) {
                $nama = $item->barang->nama_barang;
                $qty = $item->jumlah;
                $harga = number_format($item->harga_jual, 0, ',', '.');
                $subtotal = number_format($item->sub_total, 0, ',', '.');
                $printer->text("{$nama}\n");
                $printer->text("Qty: {$qty} x {$harga} = {$subtotal}\n");
            }

            // Menampilkan total bayar dan kembalian
            $printer->text("-----------------------------\n");
            $printer->text("Total Bayar: " . number_format($totalBayar, 0, ',', '.') . "\n");
            $printer->text("Uang Diterima: " . number_format($uangDiterima, 0, ',', '.') . "\n");
            $printer->text("Kembalian: " . number_format($kembalian, 0, ',', '.') . "\n");

            // Menutup koneksi dan printer
            $printer->cut();
            $printer->close();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error saat mencetak struk: ' . $e->getMessage());
        }

        // Mengarahkan kembali setelah pembayaran sukses
        return redirect()->route('transaksi.index')->with('success', 'Pembayaran berhasil! Kembalian: ' . number_format($kembalian, 0, ',', '.'));
    }
}