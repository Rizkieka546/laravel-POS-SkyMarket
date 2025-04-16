<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pelanggan;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class MembershipController extends Controller
{
    /**
     * Menampilkan form pendaftaran member.
     *
     * Fungsi ini akan menampilkan form pendaftaran untuk pelanggan yang ingin menjadi member.
     * Form ini hanya dapat diakses oleh pengguna yang sudah login.
     *
     * @return \Illuminate\View\View
     */
    public function showFormMember()
    {
        return view('pelanggan.membership.register');
    }

    /**
     * Mendaftar pelanggan untuk menjadi member.
     *
     * Fungsi ini akan memproses pendaftaran pelanggan sebagai member.
     * Jika pengguna belum login, mereka akan diarahkan untuk login terlebih dahulu.
     * Jika pengguna sudah menjadi member, maka mereka akan diarahkan kembali ke dashboard.
     * Setelah validasi berhasil, data pelanggan akan disimpan dan status membership akan diperbarui.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function membership(Request $request)
    {
        // Pastikan user sudah login
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Ambil data user yang sedang login
        $user = auth()->user();

        // Cek apakah user sudah menjadi member
        if ($user->membership) {
            return redirect()->route('dashboard.pelanggan')->with('error', 'Anda sudah terdaftar sebagai member.');
        }

        // Validasi input yang diberikan oleh pengguna
        $request->validate([
            'alamat' => 'required|string|max:200',
            'no_telp' => 'required|string|max:20'
        ]);

        // Generate kode pelanggan baru berdasarkan urutan terakhir
        $lastPelanggan = Pelanggan::latest()->first();
        $nextNumber = $lastPelanggan ? ((int) substr($lastPelanggan->kode_pelanggan, -5)) + 1 : 1;
        $kodePelanggan = 'PLG-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

        // Simpan data pelanggan baru yang telah terdaftar sebagai member
        Pelanggan::create([
            'kode_pelanggan' => $kodePelanggan,
            'nama' => $user->name,
            'alamat' => $request->alamat,
            'no_telp' => $request->no_telp,
            'email' => $user->email,
            'user_id' => $user->id,
            'membership' => true,
        ]);

        // Update status membership di tabel users menjadi member
        $user->update(['membership' => true]);

        // Redirect ke dashboard dengan pesan sukses
        return redirect()->route('dashboard.pelanggan')->with('success', 'Pendaftaran berhasil! Anda sekarang adalah anggota.');
    }
}