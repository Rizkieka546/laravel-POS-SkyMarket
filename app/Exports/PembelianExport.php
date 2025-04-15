<?php

namespace App\Exports;

use App\Models\Pembelian;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PembelianExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Pembelian::with('pemasok')->get()->map(function ($item) {
            return [
                'kode_masuk' => $item->kode_masuk,
                'pemasok' => $item->pemasok->nama_pemasok ?? '',
                'total' => $item->total,
                'tanggal_masuk' => $item->tanggal_masuk,
            ];
        });
    }

    public function headings(): array
    {
        return ['Kode Masuk', 'Pemasok', 'Total', 'Tanggal Masuk'];
    }
}