<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Menampilkan tampilan login.
     *
     * Fungsi ini mengembalikan tampilan halaman login untuk pengguna yang belum login.
     *
     * @return \Illuminate\View\View
     */
    public function showLogin()
    {
        // Menampilkan halaman login
        return view('auth.login');
    }

    /**
     * Menangani proses login pengguna.
     *
     * Fungsi ini memvalidasi input dari pengguna (email dan password), kemudian mencoba untuk
     * melakukan autentikasi dengan menggunakan kredensial yang diberikan. Jika login berhasil,
     * pengguna akan dialihkan ke halaman yang sesuai berdasarkan peran mereka (role).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        // Validasi input email dan password
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        // Mengambil kredensial email dan password dari request
        $credentials = $request->only('email', 'password');

        // Mencoba untuk login dengan kredensial yang diberikan
        if (Auth::attempt($credentials)) {
            // Mendapatkan data pengguna yang berhasil login
            $user = Auth::user();

            // Menangani aksi setelah login berhasil
            return $this->authenticated($request, $user);
        }

        // Jika login gagal, mengarahkan kembali dengan pesan error
        return back()->withErrors(['email' => 'Email atau password salah.'])->withInput();
    }

    /**
     * Mengarahkan pengguna ke halaman dashboard berdasarkan peran mereka.
     *
     * Fungsi ini mengarahkan pengguna ke halaman yang sesuai berdasarkan role mereka setelah login.
     * Halaman yang bisa dipilih adalah dashboard admin, kasir, manajer, atau pelanggan.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function authenticated(Request $request, $user)
    {
        // Menentukan tujuan pengalihan berdasarkan role pengguna
        if ($user->role === 'admin') {
            return redirect('/dashboard-admin');
        } elseif ($user->role === 'kasir') {
            return redirect('/dashboard-kasir');
        } elseif ($user->role === 'manajer') {
            return redirect('/dashboard-manajer');
        } elseif ($user->role === 'pelanggan') {
            return redirect('/dashboard-pelanggan');
        }

        // Jika tidak ada role yang cocok, redirect ke halaman utama
        return redirect('/home');
    }

    /**
     * Menangani proses logout pengguna.
     *
     * Fungsi ini akan mengeluarkan pengguna yang sedang login, kemudian mengalihkan mereka
     * kembali ke halaman login dengan pesan sukses.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        // Proses logout pengguna
        Auth::logout();

        // Mengarahkan pengguna ke halaman login dengan pesan sukses
        return redirect()->route('login')->with('success', 'Anda telah logout.');
    }
}