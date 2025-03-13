@extends('layouts.admin')

@section('content')
<div class="container mx-auto p-8 bg-gray-50 min-h-screen">
    <div class="max-w-3xl mx-auto bg-white shadow-md rounded-lg p-6">
        <h2 class="text-3xl text-center font-bold text-gray-800 mb-6">Form Pembelian Barang</h2>

        @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 shadow-sm rounded-lg">
            {{ session('success') }}
        </div>
        @endif

        <form action="{{ route('pembelian.store') }}" method="POST" class="space-y-4">
            @csrf

            <!-- Pilih Pemasok -->
            <div>
                <label for="pemasok_id" class="block text-gray-700 font-medium mb-2">Pilih Pemasok</label>
                <select name="pemasok_id" id="pemasok_id"
                    class="w-full p-3 border rounded-lg focus:ring focus:ring-teal-300" required>
                    <option value=""> Pilih Pemasok </option>
                    @foreach($pemasok as $item)
                    <option value="{{ $item->id }}">{{ $item->nama_pemasok }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="kategori_id" class="block text-gray-700 font-medium mb-2">Pilih Kategori</label>
                <select name="kategori_id" id="kategori_id"
                    class="w-full p-3 border rounded-lg focus:ring focus:ring-teal-300" required>
                    <option value=""> Pilih Kategori </option>
                    @foreach($kategori as $item)
                    <option value="{{ $item->id }}">{{ $item->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>

            <div class="relative">
                <label for="nama_barang" class="block text-gray-700 font-medium mb-2">Nama Barang</label>
                <input type="text" name="nama_barang" id="nama_barang"
                    class="w-full p-3 border rounded-lg focus:ring focus:ring-teal-300"
                    placeholder="Cari atau masukkan nama barang baru" autocomplete="off">
                <ul id="barang-list" class="absolute w-full bg-white border rounded-lg mt-1 shadow-lg hidden"></ul>
            </div>

            <div>
                <label for="satuan" class="block text-gray-700 font-medium mb-2">Satuan Barang</label>
                <select name="satuan" id="satuan" class="w-full p-3 border rounded-lg focus:ring focus:ring-teal-300"
                    required>
                    <option value=""> Pilih Satuan </option>
                    <option value="pcs">Pcs</option>
                    <option value="kg">Kilogram (Kg)</option>
                    <option value="gram">Gram</option>
                    <option value="liter">Liter</option>
                    <option value="karung">Karung</option>
                    <option value="pack">Pack</option>
                </select>
            </div>

            <div>
                <label for="harga_beli" class="block text-gray-700 font-medium mb-2">Harga Beli</label>
                <input type="number" name="harga_beli" id="harga_beli"
                    class="w-full p-3 border rounded-lg focus:ring focus:ring-teal-300"
                    placeholder="Masukkan harga beli" required>
            </div>

            <div>
                <label for="jumlah" class="block text-gray-700 font-medium mb-2">Jumlah</label>
                <input type="number" name="jumlah" id="jumlah"
                    class="w-full p-3 border rounded-lg focus:ring focus:ring-teal-300"
                    placeholder="Masukkan jumlah barang" required>
            </div>

            <button type="submit"
                class="w-full bg-teal-600 hover:bg-teal-700 text-white font-semibold py-3 px-4 rounded-lg shadow-md transition duration-300">
                Simpan Pembelian
            </button>
        </form>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const namaBarangInput = document.getElementById("nama_barang");
    const kategoriSelect = document.getElementById("kategori_id");
    const satuanSelect = document.getElementById("satuan");
    const barangList = document.getElementById("barang-list");

    function searchBarang(query) {
        let kategoriId = kategoriSelect.value;
        if (!kategoriId) return;

        fetch(`{{ route('barang.search') }}?query=${query}&kategori_id=${kategoriId}`)
            .then(response => response.json())
            .then(data => {
                barangList.innerHTML = "";
                if (data.length > 0) {
                    data.forEach(item => {
                        let li = document.createElement("li");
                        li.textContent = item.nama_barang;
                        li.classList.add("p-3", "cursor-pointer", "hover:bg-gray-200");
                        li.addEventListener("click", function() {
                            namaBarangInput.value = item.nama_barang;
                            satuanSelect.value = item.satuan;
                            barangList.classList.add("hidden");
                        });
                        barangList.appendChild(li);
                    });
                    barangList.classList.remove("hidden");
                } else {
                    barangList.classList.add("hidden");
                }
            });
    }

    namaBarangInput.addEventListener("input", function() {
        let query = this.value;
        if (query.length > 2) {
            searchBarang(query);
        } else {
            barangList.classList.add("hidden");
        }
    });

    document.addEventListener("click", function(event) {
        if (!namaBarangInput.contains(event.target) && !barangList.contains(event.target)) {
            barangList.classList.add("hidden");
        }
    });

    kategoriSelect.addEventListener("change", function() {
        namaBarangInput.value = "";
        satuanSelect.value = "";
        barangList.classList.add("hidden");
    });
});
</script>
@endsection