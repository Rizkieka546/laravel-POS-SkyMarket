<?php

namespace App\Http\Controllers;

use App\Exports\AbsensiKerjaExport;
use App\Exports\PengajuanExport;
use App\Imports\AbsensiKerjaImport;
use Illuminate\Http\Request;
use App\Models\AbsensiKerja;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

/**
 * @class AbsensiKerjaController
 * @brief Mengelola semua logika terkait absensi kerja karyawan.
 *
 * Controller ini menangani input absensi, update status absensi, waktu masuk dan selesai kerja,
 * serta ekspor dan impor data dalam format Excel dan PDF.
 */
class AbsensiKerjaController extends Controller
{
    /**
     * @brief Menampilkan daftar absensi kerja.
     * 
     * @param Request $request Berisi parameter pencarian seperti nama karyawan dan tanggal.
     * @return \Illuminate\View\View Mengembalikan tampilan daftar absensi dengan pagination.
     */
    public function index(Request $request)
    {
        $query = AbsensiKerja::query();

        // Filter berdasarkan nama karyawan
        if ($request->filled('search')) {
            $query->where('nama_karyawan', 'like', '%' . $request->search . '%');
        }

        // Filter berdasarkan tanggal masuk
        if ($request->filled('tanggal')) {
            $query->where('tanggal_masuk', $request->tanggal);
        }

        // Ambil data absensi yang sudah difilter, urutkan berdasarkan tanggal masuk, dan paginate 4 data per halaman
        $absensis = $query->orderBy('tanggal_masuk', 'desc')->paginate(4);

        return view('admin.absensi.index', compact('absensis'));
    }

    /**
     * @brief Menyimpan data absensi baru.
     *
     * @param Request $request Data dari form input absensi.
     * @return \Illuminate\Http\RedirectResponse Redirect kembali ke halaman sebelumnya dengan pesan sukses atau gagal.
     */
    public function store(Request $request)
    {
        // Validasi inputan dari form
        $request->validate([
            'nama_karyawan' => 'required|string',
            'tanggal_masuk' => 'required|date',
            'status' => 'required|in:masuk,sakit,cuti',
        ]);

        $status = $request->status;

        // Simpan absensi ke database
        AbsensiKerja::create([
            'nama_karyawan' => $request->nama_karyawan,
            'user_id' => Auth::id(), // ID user yang sedang login
            'tanggal_masuk' => $request->tanggal_masuk,
            'status' => $status,
            'waktu_masuk' => ($status === 'masuk') ? Carbon::now()->format('H:i:s') : '00:00:00',
            'waktu_kerja_selesai' => '00:00:00',
        ]);

        return redirect()->back()->with('success', 'Absensi berhasil disimpan.');
    }

    /**
     * @brief Mencatat waktu selesai kerja untuk absensi.
     *
     * @param int $id ID dari absensi yang akan diperbarui.
     * @return \Illuminate\Http\RedirectResponse Redirect dengan pesan sukses atau gagal.
     */
    public function selesaiKerja($id)
    {
        $absensi = AbsensiKerja::findOrFail($id);

        if ($absensi->status === 'masuk') {
            $absensi->waktu_kerja_selesai = Carbon::now()->format('H:i:s');
            $absensi->save();

            return redirect()->back()->with('success', 'Waktu selesai kerja berhasil dicatat.');
        }

        return redirect()->back()->with('error', 'Tidak bisa update, status bukan masuk.');
    }

    /**
     * @brief Memperbarui status dari absensi.
     *
     * @param Request $request Data yang dikirim untuk update (status).
     * @param int $id ID dari data absensi yang ingin diubah.
     * @return \Illuminate\Http\RedirectResponse Redirect dengan pesan.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:masuk,sakit,cuti',
        ]);

        $absensi = AbsensiKerja::findOrFail($id);
        $status = $request->status;

        $absensi->status = $status;

        if ($status === 'masuk') {
            $absensi->waktu_masuk = Carbon::now()->format('H:i:s');
            $absensi->waktu_kerja_selesai = '00:00:00';
        } else {
            $absensi->waktu_masuk = '00:00:00';
            $absensi->waktu_kerja_selesai = '00:00:00';
        }

        $absensi->save();

        return redirect()->back()->with('success', 'Status absensi berhasil diperbarui.');
    }

    /**
     * @brief Menghapus data absensi berdasarkan ID.
     *
     * @param int $id ID dari data absensi yang ingin dihapus.
     * @return \Illuminate\Http\RedirectResponse Redirect dengan pesan sukses.
     */
    public function destroy($id)
    {
        $absensi = AbsensiKerja::findOrFail($id);
        $absensi->delete();

        return redirect()->back()->with('success', 'Data absensi berhasil dihapus.');
    }

    /**
     * @brief Mengekspor seluruh data absensi kerja ke file Excel.
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse File Excel untuk diunduh.
     */
    public function exportExcel()
    {
        return Excel::download(new AbsensiKerjaExport, 'absensi_kerja.xlsx');
    }

    /**
     * @brief Mengekspor seluruh data absensi kerja ke file PDF.
     *
     * @return \Illuminate\Http\Response File PDF yang dapat diunduh.
     */
    public function exportPDF()
    {
        $absensis = AbsensiKerja::all();

        $pdf = PDF::loadView('admin.absensi.absensi_pdf', compact('absensis'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('absensi_kerja.pdf');
    }

    /**
     * @brief Mengimpor data absensi dari file Excel atau CSV.
     *
     * @param Request $request Berisi file Excel/CSV yang akan diimport.
     * @return \Illuminate\Http\RedirectResponse Redirect dengan pesan sukses.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        Excel::import(new AbsensiKerjaImport, $request->file('file'));

        return redirect()->back()->with('success', 'Data absensi berhasil diimport.');
    }
}