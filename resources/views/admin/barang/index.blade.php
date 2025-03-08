@extends('layouts.admin')

@section('content')
<div class="container">
    <h1 class="mb-4">Daftar Barang</h1>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('barang.create') }}" class="btn btn-primary">Tambah Barang</a>

        <div>
            <button id="btnTable" class="btn btn-secondary">Tabel</button>
            <button id="btnGrid" class="btn btn-secondary">Grid</button>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Tampilan Tabel --}}
    <div id="tableView" class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Gambar</th>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Harga Jual</th>
                    <th>Stok</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($barangs as $barang)
                <tr>
                    <td>
                        <img src="{{ asset($barang->gambar ? 'storage/' . $barang->gambar : 'images/default.jpg') }}"
                            width="50" height="50" alt="gambar">
                    </td>
                    <td>{{ $barang->kode_barang }}</td>
                    <td>{{ $barang->nama_barang }}</td>
                    <td>Rp {{ number_format($barang->harga_jual, 0, ',', '.') }}</td>
                    <td>{{ $barang->stok }}</td>
                    <td>
                        <a href="{{ route('barang.edit', $barang->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Tampilan Grid --}}
    <div id="gridView" class="row d-none">
        @foreach($barangs as $barang)
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm">
                <img src="{{ asset($barang->gambar ? 'storage/' . $barang->gambar : 'images/default.jpg') }}"
                    class="card-img-top" alt="gambar">
                <div class="card-body text-center">
                    <h5 class="card-title">{{ $barang->nama_barang }}</h5>
                    <p class="text-muted">Rp {{ number_format($barang->harga_jual, 0, ',', '.') }}</p>
                    <p>Stok: {{ $barang->stok }}</p>
                    <a href="{{ route('barang.edit', $barang->id) }}" class="btn btn-warning btn-sm">Edit</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const tableView = document.getElementById("tableView");
    const gridView = document.getElementById("gridView");
    const btnTable = document.getElementById("btnTable");
    const btnGrid = document.getElementById("btnGrid");

    // Cek localStorage untuk menyimpan pilihan tampilan terakhir
    let viewMode = localStorage.getItem("viewMode") || "table";

    function setView(mode) {
        if (mode === "grid") {
            tableView.classList.add("d-none");
            gridView.classList.remove("d-none");
        } else {
            gridView.classList.add("d-none");
            tableView.classList.remove("d-none");
        }
        localStorage.setItem("viewMode", mode);
    }

    btnTable.addEventListener("click", () => setView("table"));
    btnGrid.addEventListener("click", () => setView("grid"));

    setView(viewMode); // Terapkan tampilan yang terakhir dipilih
});
</script>
@endsection