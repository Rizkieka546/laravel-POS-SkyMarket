<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Menampilkan daftar semua pengguna (user).
     *
     * Fungsi ini mengambil semua data pengguna yang ada di dalam tabel 'users' dan
     * menampilkan halaman daftar pengguna di view 'admin.user.index'.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $users = User::all();  // Mengambil semua data pengguna
        return view('admin.user.index', compact('users'));  // Menampilkan halaman dengan daftar pengguna
    }

    /**
     * Menampilkan halaman form untuk membuat pengguna baru.
     *
     * Fungsi ini hanya menampilkan halaman form pembuatan pengguna baru tanpa
     * melakukan proses penyimpanan data apa pun.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.user.create');  // Menampilkan form untuk input data pengguna baru
    }

    /**
     * Menyimpan pengguna baru ke dalam database.
     *
     * Fungsi ini menerima data dari request, melakukan validasi terhadap input
     * pengguna baru, dan jika validasi sukses, maka pengguna baru akan disimpan
     * ke dalam database.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Melakukan validasi terhadap input pengguna baru
        $request->validate([
            'name' => 'required|string|max:255',  // Nama pengguna harus diisi dan berbentuk string
            'email' => 'required|email|unique:users,email',  // Email harus valid dan unik
            'password' => 'required|string|min:6|confirmed',  // Password harus memiliki panjang minimal 6 karakter dan terkonfirmasi
            'role' => 'required|string|in:admin,kasir,manajer,gudang'  // Role harus diisi dan memiliki nilai yang valid
        ]);

        // Menyimpan data pengguna baru ke dalam database
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),  // Password di-hash sebelum disimpan
            'role' => $request->role
        ]);

        // Mengalihkan pengguna ke halaman daftar user setelah berhasil menyimpan
        return redirect()->route('user.index')->with('success', 'User baru berhasil di tambahkan');
    }

    /**
     * Menampilkan halaman form untuk mengedit data pengguna.
     *
     * Fungsi ini menerima objek pengguna yang akan diedit, lalu menampilkannya di form edit.
     * Form ini memungkinkan admin untuk mengubah nama, email, password, dan role pengguna.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\View\View
     */
    public function edit(User $user)
    {
        return view('admin.user.edit', compact('user'));  // Menampilkan form edit dengan data pengguna yang ingin diubah
    }

    /**
     * Memperbarui data pengguna yang ada di database.
     *
     * Fungsi ini akan melakukan validasi terhadap input yang dikirim oleh user,
     * kemudian memperbarui data pengguna yang telah ada di database berdasarkan ID pengguna.
     * Jika password diubah, maka password tersebut akan di-hash sebelum disimpan.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, User $user)
    {
        // Melakukan validasi terhadap input yang dikirimkan oleh pengguna
        $request->validate([
            'name'     => 'required|string|max:255',  // Nama pengguna harus diisi dan berbentuk string
            'email'    => 'required|email|unique:users,email,' . $user->id,  // Email harus unik kecuali untuk email pengguna yang sedang diubah
            'password' => 'nullable|string|min:6|confirmed',  // Password bersifat opsional, jika diisi harus valid
            'role'     => 'required|string|in:admin,user'  // Role harus diisi dan salah satu dari nilai yang valid
        ]);

        // Membuat array data yang akan diperbarui
        $data = [
            'name'  => $request->name,
            'email' => $request->email,
            'role'  => $request->role
        ];

        // Jika password diubah, maka password akan di-hash dan ditambahkan ke data yang akan diperbarui
        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        // Memperbarui data pengguna
        $user->update($data);

        // Mengalihkan pengguna ke halaman daftar user setelah berhasil diperbarui
        return redirect()->route('user.index')->with('success', 'User berhasil diperbarui!');
    }

    /**
     * Menghapus pengguna dari database.
     *
     * Fungsi ini akan menghapus pengguna yang dipilih berdasarkan ID dan mengalihkan
     * pengguna kembali ke halaman daftar pengguna setelah penghapusan berhasil.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user)
    {
        $user->delete();  // Menghapus pengguna dari database
        return redirect()->route('user.index')->with('success', 'User berhasil dihapus!');  // Mengalihkan setelah penghapusan
    }
}