<?php

namespace App\Exports;

use App\Models\Pengajuan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PengajuanExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Pengajuan::select('nama_pengaju', 'nama_barang', 'tanggal_pengajuan', 'qty', 'status')->get();
    }

    public function headings(): array
    {
        return ["Nama Pengaju", "Nama Barang", "Tanggal Pengajuan", "Jumlah", "Status"];
    }
}