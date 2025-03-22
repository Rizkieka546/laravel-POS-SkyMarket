<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pelanggan;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class MembershipController extends Controller
{
    public function showFormMember()
    {
        return view('pelanggan.membership.register');
    }

    public function membership(Request $request)
    {
        // Pastikan user sudah login
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = auth()->user();

        // Cek apakah user sudah menjadi member
        if ($user->membership) {
            return redirect()->route('dashboard.pelanggan')->with('error', 'Anda sudah terdaftar sebagai member.');
        }

        // Validasi input
        $request->validate([
            'alamat' => 'required|string|max:200',
            'no_telp' => 'required|string|max:20'
        ]);

        // Generate kode pelanggan
        $lastPelanggan = Pelanggan::latest()->first();
        $nextNumber = $lastPelanggan ? ((int) substr($lastPelanggan->kode_pelanggan, -5)) + 1 : 1;
        $kodePelanggan = 'PLG-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

        // Simpan pelanggan baru
        Pelanggan::create([
            'kode_pelanggan' => $kodePelanggan,
            'nama' => $user->name,
            'alamat' => $request->alamat,
            'no_telp' => $request->no_telp,
            'email' => $user->email,
            'user_id' => $user->id,
            'membership' => true,
        ]);

        // Update status membership di tabel users
        $user->update(['membership' => true]);

        return redirect()->route('dashboard.pelanggan')->with('success', 'Pendaftaran berhasil! Anda sekarang adalah anggota.');
    }
}