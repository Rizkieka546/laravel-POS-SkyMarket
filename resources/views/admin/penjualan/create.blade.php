@extends('layouts.kasir')

@section('content')
<div class="container">
    @if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row">
        <!-- Kolom Daftar Barang -->
        <div class="col-md-7">
            <div class="row row-cols-3 g-3">
                @foreach ($barangs as $barang)
                <div class="col">
                    <div class="card shadow-sm barang-item text-center p-2" data-id="{{ $barang->id }}"
                        data-nama="{{ $barang->nama_barang }}" data-harga="{{ $barang->harga_jual }}"
                        data-stok="{{ $barang->stok }}">
                        <img src="{{ asset($barang->gambar ? 'storage/' . $barang->gambar : 'images/default.jpg') }}"
                            class="card-img-top" alt="{{ $barang->nama_barang }}"
                            style="height: 120px; object-fit: cover;">
                        <div class="card-body">
                            <h6 class="card-title">{{ $barang->nama_barang }}</h6>
                            <p class="card-text small">Stok: <span class="stok">{{ $barang->stok }}</span></p>
                            <p class="card-text fw-bold">Rp{{ number_format($barang->harga_jual, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Kolom Keranjang -->
        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-body">
                    <ul class="list-group mb-3" id="keranjang">
                        <li class="list-group-item text-center">Keranjang masih kosong</li>
                    </ul>
                    <h5 class="text-end">Total: <span id="totalHarga" class="fw-bold">Rp0</span></h5>
                    <form action="{{ route('penjualan.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="keranjang_data" id="keranjang_data">
                        <button type="submit" class="btn btn-success w-100 mt-2">Simpan Transaksi</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let keranjang = {};

    document.querySelectorAll('.barang-item').forEach(card => {
        card.addEventListener('click', function() {
            let id = this.dataset.id;
            let nama = this.dataset.nama;
            let harga = parseFloat(this.dataset.harga);
            let stokElement = this.querySelector('.stok');
            let stok = parseInt(stokElement.textContent);

            if (stok > 0) {
                if (!keranjang[id]) {
                    keranjang[id] = {
                        nama: nama,
                        harga: harga,
                        jumlah: 1
                    };
                } else {
                    keranjang[id].jumlah++;
                }
                stok--;
                stokElement.textContent = stok;
                updateKeranjang();
            }
        });
    });

    function updateKeranjang() {
        let keranjangList = document.getElementById('keranjang');
        let totalHargaEl = document.getElementById('totalHarga');
        keranjangList.innerHTML = '';
        let totalHarga = 0;

        Object.keys(keranjang).forEach(id => {
            let item = keranjang[id];
            let subTotal = item.jumlah * item.harga;
            totalHarga += subTotal;

            let li = document.createElement('li');
            li.classList.add('list-group-item', 'd-flex', 'justify-content-between',
                'align-items-center');
            li.innerHTML = `
                ${item.nama} (x${item.jumlah})
                <span class="fw-bold">Rp${subTotal.toLocaleString('id-ID')}</span>
                <button class="btn btn-sm btn-danger btn-hapus" data-id="${id}">X</button>
            `;
            keranjangList.appendChild(li);
        });

        if (Object.keys(keranjang).length === 0) {
            keranjangList.innerHTML = '<li class="list-group-item text-center">Keranjang masih kosong</li>';
        }

        totalHargaEl.textContent = `Rp${totalHarga.toLocaleString('id-ID')}`;
        document.getElementById('keranjang_data').value = JSON.stringify(keranjang);

        document.querySelectorAll('.btn-hapus').forEach(button => {
            button.addEventListener('click', function() {
                let id = this.dataset.id;
                let stokElement = document.querySelector(`[data-id="${id}"] .stok`);
                stokElement.textContent = parseInt(stokElement.textContent) + keranjang[id]
                    .jumlah;
                delete keranjang[id];
                updateKeranjang();
            });
        });
    }
});
</script>
@endsection