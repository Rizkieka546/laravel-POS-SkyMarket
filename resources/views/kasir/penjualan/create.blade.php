@extends('layouts.kasir')

@section('content')
<!-- Navbar -->
<header class="bg-white shadow-md py-4 px-6 flex justify-between items-center border-b">
    <h1 class="text-xl font-semibold text-indigo-700">Dashboard</h1>
    <span class="text-gray-700 font-medium">Halo, {{ Auth::user()->name }}</span>
</header>

<!-- Content -->
<main class="flex-1 p-6 flex gap-6">
    <!-- Produk List -->
    <div class="flex-1 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach ($barangs as $barang)
        <button
            onclick="tambahKeKeranjang({{ $barang->id }}, '{{ $barang->nama_barang }}', {{ $barang->harga_jual }}, {{ $barang->stok }})"
            class="p-4 bg-white shadow-md rounded-lg hover:shadow-xl transform transition hover:scale-105">
            <img src="{{ asset($barang->gambar ? 'storage/' . $barang->gambar : 'images/default.jpg') }}"
                alt="{{ $barang->nama_barang }}" class="w-full h-32 object-cover rounded-md mb-3">
            <h2 class="text-lg font-semibold text-gray-800">{{ $barang->nama_barang }}</h2>
            <p class="text-indigo-600 font-bold">Rp {{ number_format($barang->harga_jual, 0, ',', '.') }}</p>
            <span class="text-sm text-gray-600">Stok: {{ $barang->stok }}</span>
        </button>
        @endforeach
    </div>

    <!-- Keranjang -->
    <div class="w-96 bg-white p-6 border-l shadow-md rounded-lg h-screen overflow-hidden">
        <h2 class="text-xl font-bold mb-4 text-center text-gray-800">Keranjang</h2>
        <form action="{{ route('penjualan.store') }}" method="POST">
            @csrf
            <input type="hidden" name="keranjang_data" id="keranjang_data">
            <div id="order-items" class="overflow-auto max-h-96 space-y-3"></div>
            <div class="border-t pt-4 mt-4">
                <div class="flex justify-between text-lg font-medium">
                    <span>Subtotal</span>
                    <span id="subtotal">Rp 0</span>
                </div>
                <div class="flex justify-between text-xl font-bold mt-2">
                    <span>Total</span>
                    <span id="total">Rp 0</span>
                </div>
                <button type="submit"
                    class="mt-4 bg-indigo-500 text-white w-full py-3 rounded-lg hover:bg-indigo-600 transition">
                    Bayar Sekarang
                </button>
            </div>
        </form>
    </div>
</main>

<script>
let keranjang = {};

function tambahKeKeranjang(id, nama, harga, stok) {
    if (!keranjang[id]) {
        if (stok <= 0) {
            Swal.fire("Stok Habis!", "Barang ini tidak tersedia saat ini.", "error");
            return;
        }
        keranjang[id] = {
            jumlah: 1,
            harga,
            nama,
            stok
        };
    } else {
        if (keranjang[id].jumlah >= stok) {
            Swal.fire("Stok Tidak Cukup!", `Stok tersisa hanya ${stok} item.`, "warning");
            return;
        }
        keranjang[id].jumlah++;
    }
    updateKeranjang();
}

function updateKeranjang() {
    let orderItems = document.getElementById("order-items");
    let subtotal = 0;
    orderItems.innerHTML = "";
    for (let id in keranjang) {
        let item = keranjang[id];
        subtotal += item.harga * item.jumlah;
        orderItems.innerHTML += `
                    <div class='flex justify-between items-center p-3 bg-gray-100 rounded-lg shadow'>
                        <div>
                            <h3 class='text-lg font-semibold text-gray-800'>${item.nama}</h3>
                            <p class='text-indigo-600 font-bold'>Rp ${item.harga.toLocaleString()}</p>
                            <p class='text-sm text-gray-600'>Stok: ${item.stok}</p>
                        </div>
                        <div class='flex items-center'>
                            <button onclick='kurangiItem(${id})' class='px-3 py-1 bg-red-500 text-white rounded-lg hover:bg-red-600'>-</button>
                            <span class='mx-3 text-lg font-semibold'>${item.jumlah}</span>
                            <button onclick='tambahKeKeranjang(${id}, "${item.nama}", ${item.harga}, ${item.stok})' class='px-3 py-1 bg-green-500 text-white rounded-lg hover:bg-green-600'>+</button>
                        </div>
                    </div>
                `;
    }
    document.getElementById("subtotal").innerText = `Rp ${subtotal.toLocaleString()}`;
    document.getElementById("total").innerText = `Rp ${subtotal.toLocaleString()}`;
    document.getElementById("keranjang_data").value = JSON.stringify(keranjang);
}

function kurangiItem(id) {
    if (keranjang[id] && keranjang[id].jumlah > 1) {
        keranjang[id].jumlah--;
    } else {
        delete keranjang[id];
    }
    updateKeranjang();
}
</script>
@endsection