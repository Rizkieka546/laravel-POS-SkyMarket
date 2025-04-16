<?php

namespace App\Exports;

use App\Models\AbsensiKerja;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AbsensiKerjaExport implements FromCollection, WithHeadings, WithMapping
{
    private $no = 1; // Inisialisasi nomor urut

    /**
     * Mengambil semua data absensi kerja.
     */
    public function collection()
    {
        return AbsensiKerja::all();
    }

    /**
     * Header kolom pada file Excel.
     */
    public function headings(): array
    {
        return [
            'No',
            'Nama Karyawan',
            'Tanggal Masuk',
            'Waktu Masuk',
            'Status',
            'Waktu Kerja Selesai',
        ];
    }

    /**
     * Memformat isi tiap baris data.
     */
    public function map($absensi): array
    {
        return [
            $this->no++, // Menambahkan nomor urut
            $absensi->nama_karyawan,
            \Carbon\Carbon::parse($absensi->tanggal_masuk)->format('d-m-Y'),
            $absensi->waktu_masuk,
            ucfirst($absensi->status),
            $absensi->waktu_kerja_selesai,
        ];
    }
}