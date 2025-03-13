<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiPembayaran extends Model
{
    use HasFactory;

    protected $table = 'transaksi_pembayaran';

    protected $fillable = [
        'penjualan_id',
        'duitku_reference',
        'payment_method',
        'amount',
        'status',
    ];

    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class);
    }
}