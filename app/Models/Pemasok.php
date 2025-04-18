<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemasok extends Model
{
    use HasFactory;

    protected $table = 'pemasok';

    protected $fillable = [
        'nama_pemasok',
        'alamat',
        'telepon',
        'email',
        'catatan'
    ];

    public function pembelian()
    {
        return $this->hasMany(Pembelian::class);
    }
}