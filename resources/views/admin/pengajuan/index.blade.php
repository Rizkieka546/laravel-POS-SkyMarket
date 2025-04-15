@extends('layouts.admin')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-semibold mb-8 text-gray-900">Daftar Pengajuan</h1>

        <x-notification />

        <!-- Tombol Tambah Pengajuan -->
        <div class="mb-6 flex justify-end">
            <a href="{{ route('pengajuan.admin.create') }}"
                class="bg-teal-500 text-white py-2 px-6 rounded-lg shadow hover:bg-teal-600 transition-all duration-200">
                Tambah Pengajuan
            </a>
        </div>

        <div class="overflow-x-auto bg-white rounded-lg shadow">
            <table class="min-w-full border-separate border-spacing-0">
                <thead class="bg-teal-500 text-white">
                    <tr>
                        <th class="py-3 px-6 text-left text-sm font-medium">Nama Pengaju</th>
                        <th class="py-3 px-6 text-left text-sm font-medium">Nama Barang</th>
                        <th class="py-3 px-6 text-left text-sm font-medium">Tanggal Pengajuan</th>
                        <th class="py-3 px-6 text-left text-sm font-medium">Qty</th>
                        <th class="py-3 px-6 text-left text-sm font-medium">Status</th>
                        <th class="py-3 px-6 text-center text-sm font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pengajuan as $item)
                        <tr class="border-b hover:bg-gray-50 transition">
                            <td class="py-4 px-6 text-sm">{{ $item->nama_pengaju }}</td>
                            <td class="py-4 px-6 text-sm">{{ $item->nama_barang }}</td>
                            <td class="py-4 px-6 text-sm">
                                {{ \Carbon\Carbon::parse($item->tanggal_pengajuan)->format('d M Y') }}
                            </td>
                            <td class="py-4 px-6 text-sm">{{ $item->qty }}</td>
                            <td class="py-4 px-6 text-sm">
                                <span
                                    class="px-3 py-1 rounded-lg text-white 
                                    {{ $item->status == 'terpenuhi' ? 'bg-green-500' : 'bg-yellow-500' }}">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-center relative">
                                <!-- Form Status -->
                                <form action="{{ route('pengajuan.updateStatus', $item->id) }}" method="POST"
                                    class="inline-block status-form">
                                    @csrf
                                    @method('PUT')
                                    <label class="flex items-center cursor-pointer">
                                        <input type="checkbox" name="status" class="hidden status-toggle"
                                            data-id="{{ $item->id }}"
                                            {{ $item->status == 'terpenuhi' ? 'checked' : '' }}>
                                        <div class="w-12 h-6 bg-gray-300 rounded-full relative transition">
                                            <div
                                                class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full shadow transition-all
                                                {{ $item->status == 'terpenuhi' ? 'translate-x-6 bg-green-500' : '' }}">
                                            </div>
                                        </div>
                                    </label>
                                </form>

                                <!-- Tombol Edit -->
                                <a href="{{ route('pengajuan.admin.edit', $item->id) }}"
                                    class="text-blue-500 hover:underline mt-2 block text-sm"
                                    onclick="showLoadingIndicator()">Edit</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="px-6 py-4">
                {{ $pengajuan->links('vendor.pagination.tailwind') }}
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi -->
    <div id="status-modal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-40 hidden z-50">
        <div
            class="bg-white p-6 rounded-lg shadow-xl transform transition-all max-w-sm w-full text-center scale-95 opacity-0">
            <p id="modal-message" class="text-lg text-gray-800 mb-6">Apakah Anda yakin ingin mengubah status?</p>
            <div class="flex justify-center gap-4">
                <button id="cancel-btn"
                    class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50">
                    Batal
                </button>
                <button id="confirm-btn"
                    class="bg-teal-500 text-white px-4 py-2 rounded-lg hover:bg-teal-600 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-opacity-50">
                    Ya, Ubah
                </button>
            </div>
        </div>
    </div>

    <!-- Loading Spinner -->
    <div id="loading-indicator" class="hidden fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-30">
        <svg class="animate-spin h-10 w-10 text-teal-500" xmlns="http://www.w3.org/2000/svg" fill="none"
            viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0l4 4-4 4V8a4 4 0 00-4 4H4a8 8 0 018 8z">
            </path>
        </svg>
    </div>

    <!-- Script Modal dan Toggle -->
    <script>
        let selectedForm = null;
        let selectedToggle = null;

        document.querySelectorAll('.status-toggle').forEach(toggle => {
            toggle.addEventListener('change', function() {
                selectedForm = this.closest('form');
                selectedToggle = this;

                const modal = document.getElementById('status-modal');
                const message = document.getElementById('modal-message');
                const statusText = this.checked ? 'terpenuhi' : 'ditolak';

                message.innerText = `Apakah Anda yakin ingin mengubah status menjadi "${statusText}"?`;
                modal.classList.remove('hidden');

                // Animasi modal muncul
                const modalContent = modal.querySelector('.bg-white');
                setTimeout(() => {
                    modalContent.classList.remove('scale-95', 'opacity-0');
                    modalContent.classList.add('scale-100', 'opacity-100');
                }, 10);
            });
        });

        document.getElementById('cancel-btn').addEventListener('click', () => {
            document.getElementById('status-modal').classList.add('hidden');
            if (selectedToggle) {
                selectedToggle.checked = !selectedToggle.checked;
            }
        });

        document.getElementById('confirm-btn').addEventListener('click', () => {
            if (selectedForm) {
                showLoadingIndicator();
                selectedForm.submit();
            }
        });

        function showLoadingIndicator() {
            document.getElementById('loading-indicator').classList.remove('hidden');
        }
    </script>
@endsection
