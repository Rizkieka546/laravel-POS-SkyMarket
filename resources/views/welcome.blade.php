<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Foodyoow</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-gray-50">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="w-72 bg-gradient-to-b from-blue-600 to-blue-800 text-white p-6 shadow-lg flex flex-col">
            <div class="text-center text-2xl font-bold mb-6">
                <i class="fa-solid fa-store mr-2"></i> Skymarket
            </div>
            <nav class="space-y-4">
                <a href="{{ route('penjualan.index') }}"
                    class="flex items-center px-4 py-3 rounded-lg hover:bg-blue-500">
                    <i class="fas fa-shopping-cart mr-3"></i> <span>Penjualan</span>
                </a>
                <a href="{{ route('penjualan.create') }}"
                    class="flex items-center px-4 py-3 rounded-lg hover:bg-blue-500">
                    <i class="fas fa-cash-register mr-3"></i> <span>Kasir</span>
                </a>
            </nav>
            <div class="mt-auto">
                <button
                    class="w-full bg-red-500 hover:bg-red-600 text-white px-4 py-3 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-sign-out-alt mr-2"></i> Logout
                </button>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Navbar -->
            <header class="bg-white shadow-md py-4 px-6 flex justify-end items-center border-b">
                <span class="text-gray-700 font-medium">Halo, {{ Auth::user()->name }}</span>
            </header>

            <!-- Content -->
            <main class="flex-1 overflow-y-auto">
                <div class="flex-1 flex p-6">
                    <div class="flex-1 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                        @foreach ($barangs as $barang)
                        <button
                            onclick="tambahKeKeranjang({{ $barang->id }}, '{{ $barang->nama_barang }}', {{ $barang->harga_jual }}, {{ $barang->stok }})"
                            class="text-center p-4 bg-gradient-to-b from-blue-50 to-blue-100 shadow-lg rounded-xl transform transition duration-300 hover:scale-105 hover:shadow-xl">
                            <img alt="{{ $barang->nama_barang }}" class="mx-auto mb-3 rounded-lg object-cover h-28 w-28"
                                src="{{ asset($barang->gambar ? 'storage/' . $barang->gambar : 'images/default.jpg') }}" />
                            <h2 class="text-lg font-semibold text-gray-800">{{ $barang->nama_barang }}</h2>
                            <p class="text-blue-500 font-bold">Rp {{ number_format($barang->harga_jual, 0, ',', '.') }}
                            </p>
                            <div class="mt-2 text-sm text-gray-600">Stok: {{$barang->stok}}</div>
                        </button>
                        @endforeach
                    </div>

                    <!-- Order Summary (Sidebar Keranjang) -->
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
                                    class="mt-4 bg-blue-500 text-white w-full py-3 rounded-lg hover:bg-blue-600 transition">
                                    Bayar Sekarang
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>
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
            <div class='flex justify-between items-center p-3 bg-gray-50 rounded-lg shadow'>
                <div>
                    <h3 class='text-lg font-semibold text-gray-800'>${item.nama}</h3>
                    <p class='text-blue-500 font-bold'>Rp ${item.harga.toLocaleString()}</p>
                    <p class='text-sm text-gray-600'>Stok: ${item.stok}</p>
                </div>
                <div class='flex items-center'>
                    <button onclick='kurangiItem(${id})' class='px-3 py-1 bg-red-500 text-white rounded-lg shadow hover:bg-red-600 transition'>-</button>
                    <span class='mx-3 text-lg font-semibold'>${item.jumlah}</span>
                    <button onclick='tambahKeKeranjang(${id}, "${item.nama}", ${item.harga}, ${item.stok})' class='px-3 py-1 bg-green-500 text-white rounded-lg shadow hover:bg-green-600 transition'>+</button>
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
</body>

</html>