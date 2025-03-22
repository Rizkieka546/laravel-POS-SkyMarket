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
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            return $this->authenticated($request, $user);
        }

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
        } elseif ($user->role === 'pelanggan') {
            return redirect('/dashboard-pelanggan');
        }

        return redirect('/home');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('login')->with('success', 'Anda telah logout.');
    }
}