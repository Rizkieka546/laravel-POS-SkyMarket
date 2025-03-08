@extends('layouts.kasir')

@section('content')
<div class="container">
    <h2 class="mb-4">Daftar Transaksi Penjualan</h2>

    @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('penjualan.create') }}" class="btn btn-primary mb-3">Tambah Transaksi</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No Faktur</th>
                <th>Tanggal</th>
                <th>Pelanggan</th>
                <th>Total Bayar</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($penjualans as $penjualan)
            <tr>
                <td>{{ $penjualan->no_faktur }}</td>
                <td>{{ $penjualan->tgl_faktur->format('d-m-Y H:i') }}</td>
                <td>{{ $penjualan->pelanggan->nama ?? '-' }}</td>
                <td>Rp{{ number_format($penjualan->total_bayar, 0, ',', '.') }}</td>
                <td>
                    <a href="{{ route('penjualan.show', $penjualan->id) }}" class="btn btn-info btn-sm">Detail</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection