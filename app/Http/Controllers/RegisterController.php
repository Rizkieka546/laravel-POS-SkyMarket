<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    /**
     * Menampilkan tampilan form registrasi pengguna.
     *
     * Fungsi ini akan mengembalikan tampilan `auth.register` yang berisi form
     * untuk registrasi pengguna baru.
     *
     * @return \Illuminate\View\View Tampilan halaman registrasi.
     */
    public function showRegister()
    {
        // Mengembalikan tampilan form registrasi
        return view('auth.register');
    }

    /**
     * Memproses data registrasi pengguna baru.
     *
     * Fungsi ini menerima request dari form registrasi, melakukan validasi,
     * dan menyimpan data pengguna baru ke dalam database. Setelah proses
     * registrasi berhasil, pengguna akan diarahkan ke halaman login.
     *
     * Validasi yang dilakukan antara lain:
     * - Nama pengguna wajib diisi dan berupa string dengan maksimal 255 karakter.
     * - Email wajib diisi, harus berupa email yang valid, dan belum digunakan.
     * - Password wajib diisi, minimal 6 karakter, dan harus terkonfirmasi.
     *
     * @param \Illuminate\Http\Request $request Data request dari form registrasi.
     * @return \Illuminate\Http\RedirectResponse Redirect ke halaman login setelah registrasi berhasil.
     */
    public function register(Request $request)
    {
        // Validasi data yang diterima dari form
        $request->validate([
            'name' => 'required|string|max:255',  // Nama pengguna wajib diisi
            'email' => 'required|string|email|max:255|unique:users',  // Email harus valid dan unik
            'password' => 'required|string|min:6|confirmed',  // Password harus minimal 6 karakter dan terkonfirmasi
        ]);

        // Menyimpan data pengguna baru ke dalam database
        User::create([
            'name' => $request->name,  // Nama pengguna
            'email' => $request->email,  // Email pengguna
            'password' => Hash::make($request->password),  // Enkripsi password menggunakan Hash::make
            'role' => 'pelanggan',  // Set role default menjadi pelanggan
        ]);

        // Mengarahkan pengguna ke halaman login dengan pesan sukses
        return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login.');
    }
}