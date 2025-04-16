<?php

use App\Exports\AbsensiKerjaExport;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\LaporanKeuanganController;
use App\Http\Controllers\LaporanPembelianController;
use App\Http\Controllers\LaporanPenjualanController;
use App\Http\Controllers\LaporanStokController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ManajerController;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PemasokController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ScanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AbsensiKerjaController;
use Maatwebsite\Excel\Facades\Excel;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/register', [RegisterController::class, 'showRegister'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.process');
Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.process');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


// Role -> Admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard-admin', [AdminController::class, 'dashboard'])->name('dashboard.admin');

    // Kategori
    Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori.index');
    Route::get('/kategori/create', [KategoriController::class, 'create'])->name('kategori.create');
    Route::post('/kategori', [KategoriController::class, 'store'])->name('kategori.store');
    Route::get('/kategori/{id}/edit', [KategoriController::class, 'edit'])->name('kategori.edit');
    Route::put('/kategori/{id}', [KategoriController::class, 'update'])->name('kategori.update');
    Route::delete('/kategori/{id}', [KategoriController::class, 'destroy'])->name('kategori.destroy');

    // Barang
    Route::prefix('barang')->group(function () {
        Route::get('/', [BarangController::class, 'index'])->name('barang.index'); // Menampilkan daftar barang
        Route::get('/create', [BarangController::class, 'create'])->name('barang.create'); // Form tambah barang
        Route::post('/', [BarangController::class, 'store'])->name('barang.store'); // Simpan barang baru
        Route::get('/{id}/edit', [BarangController::class, 'edit'])->name('barang.edit'); // Form edit barang
        Route::put('/{id}', [BarangController::class, 'update'])->name('barang.update'); // Update barang
        Route::delete('/{id}', [BarangController::class, 'destroy'])->name('barang.destroy'); // Hapus barang
    });

    // Pembeian Barang
    Route::get('/barang/search', [PembelianController::class, 'searchBarang'])->name('barang.search');
    Route::get('/pembelian', [PembelianController::class, 'index'])->name('pembelian.index');
    Route::get('/pembelian/create', [PembelianController::class, 'create'])->name('pembelian.create');
    Route::post('/pembelian', [PembelianController::class, 'store'])->name('pembelian.store');
    Route::get('/pembelian/{id}', [PembelianController::class, 'show'])->name('pembelian.show');

    Route::get('/pembelian/export-excel', [PembelianController::class, 'exportExcel'])->name('pembelian.export.excel');
    Route::get('/pembelian/export-pdf', [PembelianController::class, 'exportPdf'])->name('pembelian.export.pdf');


    // Pemasok
    Route::get('/pemasok', [PemasokController::class, 'index'])->name('pemasok.index');
    Route::get('/pemasok/create', [PemasokController::class, 'create'])->name('pemasok.create');
    Route::post('/pemasok', [PemasokController::class, 'store'])->name('pemasok.store');
    Route::get('/pemasok/{pemasok}/edit', [PemasokController::class, 'edit'])->name('pemasok.edit');
    Route::put('/pemasok/{pemasok}', [PemasokController::class, 'update'])->name('pemasok.update');
    Route::get('/pemasok/{pemasok}', [PemasokController::class, 'show'])->name('pemasok.show');
    Route::delete('/pemasok/{pemasok}', [PemasokController::class, 'destroy'])->name('pemasok.destroy');

    // User
    Route::get('/user', [UserController::class, 'index'])->name('user.index');
    Route::get('/user/create', [UserController::class, 'create'])->name('user.create');
    Route::post('/user', [UserController::class, 'store'])->name('user.store');
    Route::get('/user/{user}/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::put('/user/{user}', [UserController::class, 'update'])->name('user.update');
    Route::delete('/user/{user}', [UserController::class, 'destroy'])->name('user.destroy');

    Route::get('/pengajuan/admin', [AdminController::class, 'indexPengajuan'])->name('pengajuan.admin');
    Route::get('/pengajuan/create', [AdminController::class, 'createPengajuan'])->name('pengajuan.admin.create');
    Route::post('/pengajuan/store', [AdminController::class, 'storePengajuan'])->name('pengajuan.admin.store');
    Route::get('/pengajuan/edit/{id}', [AdminController::class, 'editPengajuan'])->name('pengajuan.admin.edit');
    Route::put('/pengajuan/update/{id}', [AdminController::class, 'updatePengajuan'])->name('pengajuan.admin.update');

    Route::put('/pengajuan/{id}/update-status', [AdminController::class, 'updateStatus'])->name('pengajuan.updateStatus');

    Route::get('/absensi', [AbsensiKerjaController::class, 'index'])->name('absensi.index');
    Route::post('/absensi', [AbsensiKerjaController::class, 'store'])->name('absensi.store');
    Route::put('/absensi/selesai/{id}', [AbsensiKerjaController::class, 'selesaiKerja'])->name('absensi.selesai');
    Route::put('/absensi/{id}', [AbsensiKerjaController::class, 'update'])->name('absensi.update');
    Route::delete('/absensi/{id}', [AbsensiKerjaController::class, 'destroy'])->name('absensi.destroy');
    Route::get('/absensi/export/excel', [AbsensiKerjaController::class, 'exportExcel'])->name('absensi.export.excel');
    Route::get('/absensi/export/pdf', [AbsensiKerjaController::class, 'exportPDF'])->name('absensi.export.pdf');
    Route::post('/absensi/import', [AbsensiKerjaController::class, 'import'])->name('absensi.import');
});

// Role -> Kasir
Route::middleware(['auth', 'role:kasir'])->group(function () {
    Route::get('/dashboard-kasir', [KasirController::class, 'index'])->name('dashboard.kasir');

    // Penjualan
    Route::get('/penjualan', [PenjualanController::class, 'index'])->name('penjualan.index');
    Route::get('/penjualan/create', [PenjualanController::class, 'create'])->name('penjualan.create');
    Route::get('/penjualan/scan', [PenjualanController::class, 'scan'])->name('penjualan.scan');
    Route::post('/penjualan', [PenjualanController::class, 'store'])->name('penjualan.store');
    Route::get('/penjualan/{id}', [PenjualanController::class, 'show'])->name('penjualan.show');
    Route::get('/penjualan/{id}/pembayaran', [PenjualanController::class, 'pembayaran'])->name('penjualan.pembayaran');
    Route::post('/penjualan/{id}/proses-pembayaran', [PenjualanController::class, 'prosesPembayaran'])->name('penjualan.prosesPembayaran');


    Route::get('/transaksi', [ScanController::class, 'create'])->name('transaksi.create');
    Route::post('/transaksi/tambah-barang', [ScanController::class, 'tambahBarang'])->name('transaksi.tambahBarang');
    Route::post('/transaksi/simpan', [ScanController::class, 'simpan'])->name('transaksi.simpan');
    Route::get('/transaksi/pembayaran/{id}', [ScanController::class, 'pembayaran'])->name('transaksi.pembayaran');
    Route::post('/transaksi/pembayaran/{id}', [ScanController::class, 'prosesPembayaran'])->name('transaksi.prosesPembayaran');
});

// Role -> Manajer
Route::middleware(['auth', 'role:manajer'])->group(function () {
    Route::get('/dashboard-manajer', [ManajerController::class, 'index'])->name('dashboard.manajer');

    Route::get('/laporan/penjualan', [LaporanPenjualanController::class, 'index'])->name('laporan.penjualan');
    Route::get('/laporan/penjualan/{id}', [LaporanPenjualanController::class, 'show'])->name('laporan.penjualan.show');

    Route::get('/laporan/pembelian', [LaporanPembelianController::class, 'index'])->name('laporan.pembelian');
    Route::get('/laporan/pembelian/{id}', [LaporanPembelianController::class, 'show'])->name('laporan.pembelian.show');
    Route::get('/laporan/stok', [LaporanStokController::class, 'index'])->name('laporan.stok');
    Route::get('/laporan/keuangan', [LaporanKeuanganController::class, 'index'])->name('laporan.keuangan');
});

// Role -> Pelanggan
Route::middleware(['auth', 'role:pelanggan'])->group(function () {
    Route::get('/dashboard-pelanggan', [PelangganController::class, 'dashboard'])->name('dashboard.pelanggan');

    Route::get('/membership/register', [MembershipController::class, 'showFormMember'])->name('membership.register');
    Route::post('/membership/register', [MembershipController::class, 'membership'])->name('membership.proses');


    Route::get('/pengajuan', [PengajuanController::class, 'index'])->name('pengajuan.index');
    Route::post('/pengajuan', [PengajuanController::class, 'store'])->name('pengajuan.store');
    Route::put('/pengajuan/{id}', [PengajuanController::class, 'update'])->name('pengajuan.update');
    Route::delete('/pengajuan/{id}', [PengajuanController::class, 'destroy'])->name('pengajuan.destroy');
    Route::patch('/pengajuan/{id}/toggle-status', [PengajuanController::class, 'toggleStatus'])->name('pengajuan.toggle-status');

    // Jika ingin menambahkan update status, aktifkan route ini
    // Route::patch('/pengajuan/{id}/status', [PengajuanController::class, 'updateStatus'])->name('pengajuan.updateStatus');

    Route::get('/export/excel', [ExportController::class, 'exportExcel'])->name('export.excel');
    Route::get('/export/pdf', [ExportController::class, 'exportPDF'])->name('export.pdf');
});


Route::get('/unauthorized', function () {
    return view('errors.unauthorized');
});

Route::get('/set-success-notification', function () {
    session()->flash('success', 'Tindakan berhasil dilakukan!');
    return back();
});