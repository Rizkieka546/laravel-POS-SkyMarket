<?php

namespace App\Imports;

use App\Models\AbsensiKerja;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;

class AbsensiKerjaImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new AbsensiKerja([
            'nama_karyawan'       => $row['nama_karyawan'],
            'tanggal_masuk'       => $row['tanggal_masuk'],
            'status'              => $row['status'],
            'waktu_masuk'         => $row['status'] === 'masuk' ? Carbon::now()->format('H:i:s') : '00:00:00',
            'waktu_kerja_selesai' => '00:00:00',
            'user_id'             => auth()->id(),
        ]);
    }
}