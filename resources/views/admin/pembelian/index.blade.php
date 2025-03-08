@extends('layouts.admin')

@section('content')
<div class="container">
    <h2 class="mb-4">Daftar Pembelian</h2>

    <!-- Notifikasi -->
    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <!-- Tombol Tambah Pembelian -->
    <a href="{{ route('pembelian.create') }}" class="btn btn-primary mb-3">Tambah Pembelian</a>

    <!-- Tabel Data Pembelian -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Kode Masuk</th>
                <th>Pemasok</th>
                <th>Total</th>
                <th>Tanggal Masuk</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pembelian as $item)
            <tr>
                <td>{{ $item->kode_masuk }}</td>
                <td>{{ $item->pemasok->nama_pemasok }}</td>
                <td>Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                <td>{{ $item->tanggal_masuk }}</td>
                <td>
                    <a href="{{ route('pembelian.show', $item->id) }}" class="btn btn-info btn-sm">Detail</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</div>
@endsection