<?php

namespace App\Http\Controllers;

use App\Exports\PengajuanExport;
use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ExportController extends Controller
{
    public function exportExcel()
    {
        return Excel::download(new PengajuanExport, 'pengajuan.xlsx');
    }

    public function exportPDF()
    {
        $pengajuans = Pengajuan::all();
        $pdf = PDF::loadView('exports.pengajuan_pdf', compact('pengajuans'))->setPaper('a4', 'landscape');
        return $pdf->download('pengajuan.pdf');
    }
}