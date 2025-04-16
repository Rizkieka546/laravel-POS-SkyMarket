<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AbsensiKerja extends Model
{

    protected $table = 'absensi_kerja';

    protected $fillable = [
        'nama_karyawan',
        'user_id',
        'tanggal_masuk',
        'waktu_masuk',
        'status',
        'waktu_kerja_selesai'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}