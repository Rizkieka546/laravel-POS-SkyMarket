@extends('layouts.kasir')

@section('content')

<!-- Content -->
<main class="flex-1 flex p-6 gap-6 overflow-hidden">
    <!-- Produk List -->
    <div class="flex-1 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 overflow-x-auto h-[calc(100vh-80px)] pr-2">
        @foreach ($barangs as $barang)
        <button
            onclick="tambahKeKeranjang({{ $barang->id }}, '{{ $barang->nama_barang }}', {{ $barang->harga_jual }}, {{ $barang->stok }})"
            class="p-4 bg-white shadow-md rounded-lg hover:shadow-xl transform transition hover:scale-105 flex flex-col justify-between items-center h-56 w-full">
            <img src="{{ asset($barang->gambar ? 'storage/barang/' . $barang->gambar : 'images/default.jpg') }}"
                alt="{{ $barang->nama_barang }}" class="w-full h-32 object-cover rounded-md">
            <div class="text-center">
                <h2 class="text-lg font-semibold text-gray-800 truncate w-40">{{ $barang->nama_barang }}</h2>

                <p class="text-teal-600 font-bold">Rp {{ number_format($barang->harga_jual, 0, ',', '.') }}</p>
                <span class="text-sm text-gray-600">Stok: {{ $barang->stok }}</span>
            </div>
        </button>
        @endforeach
    </div>

    <!-- Keranjang -->
    <div class="w-80 bg-white p-6 border-l shadow-md rounded-lg h-[calc(100vh-80px)] overflow-hidden flex flex-col">
        <h2 class="text-xl font-bold mb-4 text-center text-gray-800">Keranjang</h2>
        <form action="{{ route('penjualan.store') }}" method="POST" class="flex-1 flex flex-col">
            @csrf
            <input type="hidden" name="keranjang_data" id="keranjang_data">
            <div id="order-items" class="overflow-auto flex-1 space-y-3 max-h-60 border-b pb-4"></div>
            <div class="pt-4 mt-4">
                <div class="flex justify-between text-lg font-medium">
                    <span>Subtotal</span>
                    <span id="subtotal">Rp 0</span>
                </div>
                <div class="flex justify-between text-xl font-bold mt-2">
                    <span>Total</span>
                    <span id="total">Rp 0</span>
                </div>
                <button type="submit"
                    class="mt-4 bg-teal-500 text-white w-full py-3 rounded-lg hover:bg-teal-600 transition">
                    Bayar Sekarang
                </button>
            </div>
        </form>
    </div>
</main>

<script>
const keranjang = {};

function tambahKeKeranjang(id, nama, harga, stok) {
    if (!keranjang[id]) {
        if (stok <= 0) return Swal.fire("Stok Habis!", "Barang ini tidak tersedia saat ini.", "error");
        keranjang[id] = {
            jumlah: 1,
            harga,
            nama,
            stok
        };
    } else if (keranjang[id].jumlah < stok) {
        keranjang[id].jumlah++;
    } else {
        return Swal.fire("Stok Tidak Cukup!", `Stok tersisa hanya ${stok} item.`, "warning");
    }
    updateKeranjang();
}

function kurangiItem(id) {
    if (keranjang[id]) {
        keranjang[id].jumlah > 1 ? keranjang[id].jumlah-- : delete keranjang[id];
        updateKeranjang();
    }
}

function updateKeranjang() {
    const orderItems = document.getElementById("order-items");
    let subtotal = 0;
    orderItems.innerHTML = Object.entries(keranjang).map(([id, item]) => {
        subtotal += item.harga * item.jumlah;
        return `
            <div class='flex justify-between items-center p-3 bg-gray-100 rounded-lg shadow'>
                <div>
                    <h3 class='text-lg font-semibold text-gray-800 truncate w-32'>${item.nama}</h3>

                    <p class='text-teal-600 font-bold'>Rp ${item.harga.toLocaleString()}</p>
                    <p class='text-sm text-gray-600'>Stok: ${item.stok}</p>
                </div>
                <div class='flex items-center'>
                    <button onclick='kurangiItem(${id})' class='px-3 py-1 bg-red-500 text-white rounded-lg hover:bg-red-600'>-</button>
                    <span class='mx-3 text-lg font-semibold'>${item.jumlah}</span>
                    <button onclick='tambahKeKeranjang(${id}, "${item.nama}", ${item.harga}, ${item.stok})' class='px-3 py-1 bg-green-500 text-white rounded-lg hover:bg-green-600'>+</button>
                </div>
            </div>
        `;
    }).join("");
    document.getElementById("subtotal").innerText = document.getElementById("total").innerText =
        `Rp ${subtotal.toLocaleString()}`;
    document.getElementById("keranjang_data").value = JSON.stringify(keranjang);
}
</script>

<style>
.truncate {
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
    display: block;
    max-width: 100%;
}
</style>
@endsection