@extends('layouts.pelanggan')

@section('content')
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-2xl font-semibold text-gray-800 mb-4">Daftar Pengajuan Barang</h1>
        <x-notification />
        <!-- Tombol Tambah Pengajuan -->
        <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded mb-4 transition duration-300"
            data-modal="tambahPengajuanModal">Tambah Pengajuan</button>

        <!-- Tabel Pengajuan -->
        <div class="overflow-x-auto">
            <table class="w-full border border-gray-200 shadow-md rounded-lg overflow-hidden">
                <thead>
                    <tr class="bg-blue-500 text-white">
                        <th class="p-3 text-left">No</th>
                        <th class="p-3 text-left">Nama Pengaju</th>
                        <th class="p-3 text-left">Nama Barang</th>
                        <th class="p-3 text-left">Tanggal</th>
                        <th class="p-3 text-left">Jumlah</th>
                        <th class="p-3 text-left">Status</th>
                        <th class="p-3 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pengajuans as $key => $pengajuan)
                        <tr class="border-b border-gray-200 hover:bg-gray-100 transition duration-300">
                            <td class="p-3">{{ $key + 1 }}</td>
                            <td class="p-3">{{ $pengajuan->nama_pengaju }}</td>
                            <td class="p-3">{{ $pengajuan->nama_barang }}</td>
                            <td class="p-3">{{ $pengajuan->tanggal_pengajuan }}</td>
                            <td class="p-3">{{ $pengajuan->qty }}</td>
                            <td class="p-3">
                                <span
                                    class="px-3 py-1 rounded text-white text-sm 
                                    {{ $pengajuan->status == 'terpenuhi' ? 'bg-green-500' : 'bg-red-500' }}">
                                    {{ ucfirst($pengajuan->status) }}
                                </span>
                            </td>
                            <td class="p-3 flex space-x-2">
                                <button
                                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded transition duration-300"
                                    data-modal="editPengajuanModal{{ $pengajuan->id }}">Edit</button>
                                <form action="{{ route('pengajuan.destroy', $pengajuan->id) }}" method="POST"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded transition duration-300">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="flex justify-end space-x-2 mt-4">
                <a href="{{ route('export.excel') }}" class="bg-green-500 hover:bg-green-700 text-white px-4 py-2 rounded">
                    Export Excel
                </a>
                <a href="{{ route('export.pdf') }}" class="bg-red-500 hover:bg-red-700 text-white px-4 py-2 rounded">
                    Export PDF
                </a>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Pengajuan -->
    <div id="tambahPengajuanModal"
        class="fixed inset-0 hidden modal-overlay bg-gray-800 bg-opacity-50 flex justify-center items-center">
        <div class="bg-white p-6 rounded-lg w-96 shadow-lg">
            <div class="flex justify-between items-center">
                <h2 class="text-lg font-semibold">Tambah Pengajuan</h2>
                <button data-close="tambahPengajuanModal" class="text-gray-600 hover:text-gray-900">&times;</button>
            </div>
            <form action="{{ route('pengajuan.store') }}" method="POST" class="mt-4">
                @csrf

                <div class="mb-3">
                    <label class="block text-sm font-medium">Nama Barang</label>
                    <input type="text" name="nama_barang" required class="w-full border p-2 rounded">
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium">Tanggal Pengajuan</label>
                    <input type="date" name="tanggal_pengajuan" required class="w-full border p-2 rounded">
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium">Jumlah</label>
                    <input type="number" name="qty" min="1" required class="w-full border p-2 rounded">
                </div>
                <div class="flex justify-end">
                    <button type="button" data-close="tambahPengajuanModal"
                        class="px-4 py-2 bg-gray-400 text-white rounded mr-2">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Pengajuan (Looping) -->
    @foreach ($pengajuans as $pengajuan)
        <div id="editPengajuanModal{{ $pengajuan->id }}"
            class="fixed inset-0 hidden modal-overlay bg-gray-800 bg-opacity-50 flex justify-center items-center">
            <div class="bg-white p-6 rounded-lg w-96 shadow-lg">
                <div class="flex justify-between items-center">
                    <h2 class="text-lg font-semibold">Edit Pengajuan</h2>
                    <button data-close="editPengajuanModal{{ $pengajuan->id }}"
                        class="text-gray-600 hover:text-gray-900">&times;</button>
                </div>
                <form action="{{ route('pengajuan.update', $pengajuan->id) }}" method="POST" class="mt-4">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="block text-sm font-medium">Nama Barang</label>
                        <input type="text" name="nama_barang" value="{{ $pengajuan->nama_barang }}" required
                            class="w-full border p-2 rounded">
                    </div>
                    <div class="mb-3">
                        <label class="block text-sm font-medium">Tanggal Pengajuan</label>
                        <input type="date" name="tanggal_pengajuan" value="{{ $pengajuan->tanggal_pengajuan }}" required
                            class="w-full border p-2 rounded">
                    </div>
                    <div class="mb-3">
                        <label class="block text-sm font-medium">Jumlah</label>
                        <input type="number" name="qty" value="{{ $pengajuan->qty }}" min="1" required
                            class="w-full border p-2 rounded">
                    </div>
                    <div class="flex justify-end">
                        <button type="button" data-close="editPengajuanModal{{ $pengajuan->id }}"
                            class="px-4 py-2 bg-gray-400 text-white rounded mr-2">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-yellow-600 text-white rounded">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach
@endsection

<!-- Script Modal -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const modals = document.querySelectorAll("[data-modal]");
        const closeModalButtons = document.querySelectorAll("[data-close]");

        function openModal(modalId) {
            document.getElementById(modalId).classList.remove("hidden");
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add("hidden");
        }

        modals.forEach(button => {
            button.addEventListener("click", function() {
                openModal(this.getAttribute("data-modal"));
            });
        });

        closeModalButtons.forEach(button => {
            button.addEventListener("click", function() {
                closeModal(this.getAttribute("data-close"));
            });
        });

        document.querySelectorAll(".modal-overlay").forEach(overlay => {
            overlay.addEventListener("click", function(e) {
                if (e.target === this) {
                    closeModal(this.id);
                }
            });
        });
    });
</script>
