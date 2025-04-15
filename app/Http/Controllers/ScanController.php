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
    public function create()
    {
        $keranjang = Session::get('keranjang', []);
        return view('kasir.transaksi.create', compact('keranjang'));
    }

    public function tambahBarang(Request $request)
    {
        $request->validate([
            'kode_barang' => 'required|string',
            'jumlah' => 'required|integer|min:1'
        ]);

        $barang = Barang::where('kode_barang', $request->kode_barang)->first();

        if (!$barang) {
            return redirect()->back()->with('error', 'Barang tidak ditemukan!');
        }

        $keranjang = Session::get('keranjang', []);
        $key = $barang->id;

        if (isset($keranjang[$key])) {
            $keranjang[$key]['jumlah'] += $request->jumlah;
            $keranjang[$key]['sub_total'] = $keranjang[$key]['jumlah'] * $barang->harga_jual;
        } else {
            $keranjang[$key] = [
                'id' => $barang->id,
                'kode_barang' => $barang->kode_barang,
                'nama_barang' => $barang->nama_barang,
                'harga_jual' => $barang->harga_jual,
                'jumlah' => $request->jumlah,
                'sub_total' => $barang->harga_jual * $request->jumlah
            ];
        }

        Session::put('keranjang', $keranjang);

        return redirect()->back()->with('success', 'Barang ditambahkan ke keranjang!');
    }

    public function simpan(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        $keranjang = Session::get('keranjang', []);
        if (empty($keranjang)) {
            return redirect()->back()->with('error', 'Keranjang masih kosong!');
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
            Session::forget('keranjang');
            DB::commit();

            return redirect()->route('transaksi.pembayaran', ['id' => $penjualan->id])
                ->with('success', 'Transaksi berhasil disimpan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menyimpan transaksi: ' . $e->getMessage());
        }
    }

    public function pembayaran($id)
    {
        $penjualan = Penjualan::with('detailPenjualan.barang')->findOrFail($id);
        return view('kasir.transaksi.pembayaran', compact('penjualan'));
    }

    public function prosesPembayaran(Request $request, $id)
    {
        $request->validate([
            'uang_diterima' => 'required|numeric|min:1',
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

            return redirect()->route('transaksi.create')->with('success', 'Pembayaran berhasil dan struk dicetak.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mencetak struk: ' . $e->getMessage());
        }
    }
}