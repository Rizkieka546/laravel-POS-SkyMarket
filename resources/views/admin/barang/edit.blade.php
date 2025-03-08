@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Edit Barang</h2>

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('barang.update', $barang->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nama_barang" class="form-label">Nama Barang</label>
            <input type="text" class="form-control" id="nama_barang" name="nama_barang"
                value="{{ old('nama_barang', $barang->nama_barang) }}" required>
        </div>

        <div class="mb-3">
            <label for="kategori_id" class="form-label">Kategori</label>
            <select class="form-control" id="kategori_id" name="kategori_id" required>
                <option value="">Pilih Kategori</option>
                @foreach ($kategoris as $kategori)
                <option value="{{ $kategori->id }}"
                    {{ old('kategori_id', $barang->kategori_id) == $kategori->id ? 'selected' : '' }}>
                    {{ $kategori->nama_kategori }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="stok_minimal" class="form-label">Stok Minimal</label>
            <input type="number" class="form-control" id="stok_minimal" name="stok_minimal" min="1"
                value="{{ old('stok_minimal', $barang->stok_minimal) }}" required>
        </div>

        <!-- Pratinjau Gambar -->
        <div class="mb-3">
            <label for="gambar" class="form-label">Gambar Barang</label>
            <input type="file" class="form-control" id="gambar" name="gambar" accept="image/*">
            <small class="text-muted">Maksimal 2MB, format: jpeg, png, jpg, gif</small>

            @if ($barang->gambar)
            <div class="mt-2">
                <p>Gambar Saat Ini:</p>
                <img src="{{ asset('storage/' . $barang->gambar) }}" alt="Gambar Barang" width="100">
            </div>
            @endif
        </div>

        <button type="submit" class="btn btn-success">Update</button>
        <a href="{{ route('barang.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection