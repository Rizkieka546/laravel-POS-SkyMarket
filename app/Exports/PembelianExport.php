<?php

namespace App\Exports;

use App\Models\Pembelian;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PembelianExport implements FromCollection, WithHeadings
{
    protected $tanggal_mulai;
    protected $tanggal_selesai;
    protected $pemasok_id;

    public function __construct($tanggal_mulai, $tanggal_selesai, $pemasok_id)
    {
        $this->tanggal_mulai = $tanggal_mulai;
        $this->tanggal_selesai = $tanggal_selesai;
        $this->pemasok_id = $pemasok_id;
    }

    public function collection()
    {
        $query = Pembelian::with('pemasok');

        if ($this->tanggal_mulai && $this->tanggal_selesai) {
            $query->whereBetween('tanggal_masuk', [$this->tanggal_mulai, $this->tanggal_selesai]);
        }

        if ($this->pemasok_id) {
            $query->where('pemasok_id', $this->pemasok_id);
        }

        return $query->get()->map(function ($pembelian) {
            return [
                $pembelian->kode_masuk,
                $pembelian->tanggal_masuk,
                $pembelian->pemasok->nama ?? '-',
                $pembelian->total_bayar,
            ];
        });
    }

    public function headings(): array
    {
        return ["Kode Masuk", "Tanggal Masuk", "Pemasok", "Total Bayar"];
    }
}