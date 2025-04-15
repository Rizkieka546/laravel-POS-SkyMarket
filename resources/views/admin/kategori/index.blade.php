@extends('layouts.admin')

@section('content')
    <div class="container mx-auto p-6">
        <div class="flex justify-end mb-4">
            <button id="openModalBtn"
                class="bg-teal-600 text-white px-5 py-2 rounded-lg shadow-md hover:bg-teal-700 transition duration-300 ease-in-out">
                + Tambah Kategori
            </button>
        </div>

        <x-notification />


        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-teal-500 text-white">
                            <th class="py-3 px-4 border">No</th>
                            <th class="py-3 px-4 border">Nama Kategori</th>
                            <th class="py-3 px-4 border">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-gray-100">
                        @foreach ($kategori as $index => $item)
                            <tr class="border-b hover:bg-gray-200 transition duration-200">
                                <td class="py-3 px-4 border">{{ $index + 1 }}</td>
                                <td class="py-3 px-4 border">{{ $item->nama_kategori }}</td>
                                <td class="py-3 px-4 border flex space-x-2">
                                    <button
                                        class="editModalBtn bg-[#66D2CE] hover:bg-white hover:text-[#66D2CE] text-white px-4 py-1 rounded-lg transition duration-300"
                                        data-id="{{ $item->id }}" data-nama="{{ $item->nama_kategori }}">
                                        Edit
                                    </button>
                                    <form action="{{ route('kategori.destroy', $item->id) }}" method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="bg-red-500 text-white px-4 py-1 rounded-lg hover:bg-red-600 transition duration-300">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal Pop-up -->
        <div id="modalKategori"
            class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center transition-opacity">
            <div class="bg-white p-6 rounded-lg shadow-lg w-96 transform scale-95 transition duration-300">
                <h2 class="text-lg font-bold mb-4 text-gray-700">Tambah / Edit Kategori</h2>
                <form id="kategoriForm" method="POST">
                    @csrf
                    <input type="hidden" id="kategoriId">
                    <div class="mb-4">
                        <label for="nama_kategori" class="block text-gray-700 font-bold mb-2">Nama Kategori</label>
                        <input type="text" name="nama_kategori" id="nama_kategori" required
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-300">
                            Simpan
                        </button>
                        <button type="button" id="closeModalBtn"
                            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded transition duration-300">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const modal = document.getElementById("modalKategori");
            const openModalBtn = document.getElementById("openModalBtn");
            const closeModalBtn = document.getElementById("closeModalBtn");
            const kategoriForm = document.getElementById("kategoriForm");
            const kategoriIdInput = document.getElementById("kategoriId");
            const namaKategoriInput = document.getElementById("nama_kategori");

            // Tambah Kategori
            openModalBtn.addEventListener("click", function() {
                kategoriForm.setAttribute("action", "{{ route('kategori.store') }}");
                kategoriForm.innerHTML += '@method('POST')';
                kategoriIdInput.value = "";
                namaKategoriInput.value = "";
                modal.classList.remove("hidden");
                modal.children[0].classList.add("scale-100");
            });

            // Edit Kategori
            document.querySelectorAll(".editModalBtn").forEach(button => {
                button.addEventListener("click", function() {
                    const id = this.getAttribute("data-id");
                    const nama = this.getAttribute("data-nama");

                    kategoriForm.setAttribute("action", `/kategori/${id}`);
                    kategoriForm.innerHTML += '@method('PUT')';
                    kategoriIdInput.value = id;
                    namaKategoriInput.value = nama;
                    modal.classList.remove("hidden");
                    modal.children[0].classList.add("scale-100");
                });
            });

            // Tutup Modal
            closeModalBtn.addEventListener("click", function() {
                modal.classList.add("hidden");
                modal.children[0].classList.remove("scale-100");
            });
        });
    </script>
@endsection
