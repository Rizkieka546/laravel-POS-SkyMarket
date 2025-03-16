<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        // Ambil data yang diinputkan
        $credentials = $request->only('email', 'password');

        // Coba autentikasi pengguna
        if (Auth::attempt($credentials)) {
            // Ambil user yang sedang login
            $user = Auth::user();

            // Redirect berdasarkan role
            return $this->authenticated($request, $user);
        }

        // Jika login gagal, kembalikan ke halaman login dengan pesan error
        return back()->withErrors(['email' => 'Email atau password salah.'])->withInput();
    }

    public function authenticated(Request $request, $user)
    {
        if ($user->role === 'admin') {
            return redirect('/dashboard-admin');
        } elseif ($user->role === 'kasir') {
            return redirect('/dashboard-kasir');
        } elseif ($user->role === 'manajer') {
            return redirect('/dashboard-manajer');
        }

        return redirect('/home');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('login')->with('success', 'Anda telah logout.');
    }
}