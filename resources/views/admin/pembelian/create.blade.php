@extends('layouts.admin')

@section('content')
<div class="container">
    <h2 class="mb-4">Tambah Pembelian</h2>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('pembelian.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="tanggal_masuk" class="form-label">Tanggal Masuk</label>
            <input type="date" name="tanggal_masuk" id="tanggal_masuk" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="pemasok_id" class="form-label">Pemasok</label>
            <select name="pemasok_id" id="pemasok_id" class="form-control" required>
                <option value="">-- Pilih Pemasok --</option>
                @foreach ($pemasok as $item)
                <option value="{{ $item->id }}">{{ $item->nama_pemasok }}</option>
                @endforeach
            </select>
        </div>

        <div id="barang-container">
            <div class="barang-item mb-3">
                <label class="form-label">Pilih Barang</label>
                <select name="barang_id[]" class="form-control barang-select" required>
                    <option value="">-- Pilih Barang --</option>
                    @foreach ($barang as $item)
                    <option value="{{ $item->id }}">{{ $item->nama_barang }}</option>
                    @endforeach
                </select>

                <label class="form-label mt-2">Harga Beli</label>
                <input type="number" name="harga_beli[]" class="form-control harga_beli" required
                    oninput="hitungHargaJual(this)">

                <label class="form-label mt-2">Harga Jual (5% lebih tinggi)</label>
                <input type="number" class="form-control harga_jual" readonly>

                <label class="form-label mt-2">Jumlah</label>
                <input type="number" name="jumlah[]" class="form-control" required>

                <button type="button" class="btn btn-danger mt-2" onclick="hapusBarang(this)">Hapus</button>
            </div>
        </div>

        <button type="button" class="btn btn-primary mt-3" onclick="tambahBarang()">Tambah Barang</button>
        <button type="submit" class="btn btn-success mt-3">Simpan</button>
    </form>
</div>

<script>
function hitungHargaJual(input) {
    let hargaBeli = parseFloat(input.value);
    let hargaJualField = input.closest('.barang-item').querySelector('.harga_jual');

    if (!isNaN(hargaBeli)) {
        let hargaJual = hargaBeli + (hargaBeli * 0.05);
        hargaJualField.value = hargaJual.toFixed(2);
    } else {
        hargaJualField.value = '';
    }
}

function tambahBarang() {
    let container = document.getElementById('barang-container');
    let barangItem = document.createElement('div');
    barangItem.classList.add('barang-item', 'mb-3');
    barangItem.innerHTML = `
        <label class="form-label">Pilih Barang</label>
        <select name="barang_id[]" class="form-control barang-select" required>
            <option value="">-- Pilih Barang --</option>
            @foreach ($barang as $item)
                <option value="{{ $item->id }}">{{ $item->nama_barang }}</option>
            @endforeach
        </select>

        <label class="form-label mt-2">Harga Beli</label>
        <input type="number" name="harga_beli[]" class="form-control harga_beli" required oninput="hitungHargaJual(this)">

        <label class="form-label mt-2">Harga Jual (5% lebih tinggi)</label>
        <input type="number" class="form-control harga_jual" readonly>

        <label class="form-label mt-2">Jumlah</label>
        <input type="number" name="jumlah[]" class="form-control" required>

        <button type="button" class="btn btn-danger mt-2" onclick="hapusBarang(this)">Hapus</button>
    `;
    container.appendChild(barangItem);
}

function hapusBarang(button) {
    button.closest('.barang-item').remove();
}
</script>
@endsection