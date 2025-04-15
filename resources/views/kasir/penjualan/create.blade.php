@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <h1 class="text-2xl font-bold mb-4">Scan Barang (Barcode)</h1>

        {{-- Input Barcode --}}
        <div class="w-full mb-6">
            <label for="scan-barcode" class="block font-semibold text-gray-700 mb-2">Scan Barcode</label>
            <input type="text" id="scan-barcode" class="border p-2 w-full rounded shadow" placeholder="Scan barcode di sini"
                autofocus>
        </div>

        {{-- Keranjang --}}
        <form id="form-pembelian" method="POST" action="{{ route('pembelian.store') }}">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Daftar Belanja --}}
                <div class="bg-white p-4 rounded shadow">
                    <h2 class="text-lg font-semibold mb-2">Daftar Barang</h2>
                    <table class="w-full text-sm text-left">
                        <thead>
                            <tr class="border-b">
                                <th>Nama</th>
                                <th>Harga</th>
                                <th>Qty</th>
                                <th>Subtotal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="keranjang-list">
                            {{-- Diisi lewat JavaScript --}}
                        </tbody>
                    </table>
                </div>

                {{-- Ringkasan & Bayar --}}
                <div class="bg-white p-4 rounded shadow">
                    <h2 class="text-lg font-semibold mb-2">Total</h2>
                    <p class="text-xl font-bold mb-4" id="total">Rp 0</p>

                    {{-- Field tersembunyi untuk dikirim ke backend --}}
                    <input type="hidden" name="items" id="input-items">

                    <button type="submit"
                        class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded w-full">
                        Bayar Sekarang
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        const daftarBarang = @json($barangs); // Harus ada 'kode_barang', 'nama_barang', 'harga_jual', 'id'
        const keranjang = [];

        function updateKeranjangUI() {
            const tbody = document.getElementById('keranjang-list');
            tbody.innerHTML = '';
            let total = 0;

            keranjang.forEach((item, index) => {
                const subtotal = item.qty * item.harga;
                total += subtotal;

                const row = document.createElement('tr');
                row.innerHTML = `
                <td>${item.nama}</td>
                <td>Rp ${item.harga.toLocaleString()}</td>
                <td>${item.qty}</td>
                <td>Rp ${(subtotal).toLocaleString()}</td>
                <td><button type="button" onclick="hapusItem(${index})" class="text-red-500">Hapus</button></td>
            `;
                tbody.appendChild(row);
            });

            document.getElementById('total').innerText = `Rp ${total.toLocaleString()}`;
            document.getElementById('input-items').value = JSON.stringify(keranjang);
        }

        function tambahKeKeranjang(id, nama, harga) {
            const index = keranjang.findIndex(item => item.id === id);

            if (index !== -1) {
                keranjang[index].qty += 1;
            } else {
                keranjang.push({
                    id,
                    nama,
                    harga,
                    qty: 1
                });
            }

            updateKeranjangUI();

            Swal.fire({
                title: 'Berhasil!',
                text: `${nama} ditambahkan ke keranjang.`,
                icon: 'success',
                timer: 1000,
                showConfirmButton: false
            });
        }

        function hapusItem(index) {
            keranjang.splice(index, 1);
            updateKeranjangUI();
        }

        document.getElementById('scan-barcode').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const kode = this.value.trim();
                this.value = '';

                const barang = daftarBarang.find(b => b.kode_barang === kode);

                if (barang) {
                    tambahKeKeranjang(barang.id, barang.nama_barang, barang.harga_jual);
                } else {
                    Swal.fire("Barang Tidak Ditemukan", `Kode "${kode}" tidak terdaftar.`, "error");
                }
            }
        });
    </script>
@endsection
