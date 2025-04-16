<?php

namespace App\Http\Controllers;

use App\Exports\PengajuanExport;
use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ExportController extends Controller
{
    /**
     * Menyediakan fitur untuk mengekspor data pengajuan ke dalam format Excel.
     *
     * Metode ini menggunakan paket `Maatwebsite\Excel` untuk mengunduh file Excel 
     * dengan data dari model `Pengajuan`. File yang diunduh akan bernama 'pengajuan.xlsx'.
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportExcel()
    {
        // Menggunakan kelas PengajuanExport untuk mengekspor data ke Excel
        return Excel::download(new PengajuanExport, 'pengajuan.xlsx');
    }

    /**
     * Menyediakan fitur untuk mengekspor data pengajuan ke dalam format PDF.
     *
     * Metode ini mengambil semua data pengajuan menggunakan model `Pengajuan`,
     * kemudian menggunakan paket `Barryvdh\DomPDF` untuk menghasilkan file PDF.
     * File PDF ini akan berisi data pengajuan dalam format tampilan yang telah ditentukan 
     * dalam view 'exports.pengajuan_pdf'. File yang diunduh akan bernama 'pengajuan.pdf'.
     * Format kertas yang digunakan adalah A4 dengan orientasi landscape.
     *
     * @return \Barryvdh\DomPDF\PDF
     */
    public function exportPDF()
    {
        // Mengambil seluruh data pengajuan
        $pengajuans = Pengajuan::all();

        // Membuat file PDF dari data pengajuan dengan tampilan tertentu
        $pdf = PDF::loadView('exports.pengajuan_pdf', compact('pengajuans'))->setPaper('a4', 'landscape');

        // Mengunduh file PDF dengan nama pengajuan.pdf
        return $pdf->download('pengajuan.pdf');
    }
}