<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    public function dashboard()
    {
        $barang = Barang::all();
        return view('dashboard.dashboard-pelanggan', compact('barang'));
    }
}