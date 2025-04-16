@extends('layouts.admin')

@section('content')
    <div class="max-w-6xl mx-auto py-6 px-4">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800 mb-6">Data Absensi Karyawan</h1>

            <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-4">
                <div class="flex flex-wrap gap-3">
                    <button onclick="openCreateModal()"
                        class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition duration-200">
                        Tambah Absensi
                    </button>

                    <a href="{{ route('absensi.export.pdf') }}"
                        class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 transition duration-200">
                        Export PDF
                    </a>

                    <a href="{{ route('absensi.export.excel') }}"
                        class="bg-teal-500 text-white px-4 py-2 rounded-md hover:bg-teal-600 transition duration-200">
                        Export Excel
                    </a>



                </div>

                <form method="GET" class="flex flex-wrap gap-2">
                    <input type="text" name="search" placeholder="Cari nama..." value="{{ request('search') }}"
                        class="border px-3 py-2 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-300">
                    <input type="date" name="tanggal" value="{{ request('tanggal') }}"
                        class="border px-3 py-2 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-300">
                    <button
                        class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition duration-200">Filter</button>
                </form>
            </div>
        </div>

        <form action="{{ route('absensi.import') }}" method="POST" enctype="multipart/form-data" class="mb-4">
            @csrf
            <input type="file" name="file" required class="border px-3 py-2 rounded-md">
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">Import
                Excel</button>
        </form>

        <x-notification />

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
                <thead class="bg-gray-200 text-left text-gray-700">
                    <tr>
                        <th class="px-4 py-3">Nama</th>
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3">Masuk</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Selesai</th>
                        <th class="px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @foreach ($absensis as $absen)
                        <tr class="border-b hover:bg-gray-50 transition">
                            <td class="px-4 py-2">{{ $absen->nama_karyawan }}</td>
                            <td class="px-4 py-2">{{ $absen->tanggal_masuk }}</td>
                            <td class="px-4 py-2">{{ $absen->waktu_masuk }}</td>
                            <td class="px-4 py-2 capitalize">
                                <form action="{{ route('absensi.update', $absen->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <select name="status" class="border border-gray-300 rounded px-2 py-1"
                                        onchange="this.form.submit()">
                                        <option value="masuk" {{ $absen->status === 'masuk' ? 'selected' : '' }}>Masuk
                                        </option>
                                        <option value="sakit" {{ $absen->status === 'sakit' ? 'selected' : '' }}>Sakit
                                        </option>
                                        <option value="cuti" {{ $absen->status === 'cuti' ? 'selected' : '' }}>Cuti
                                        </option>
                                    </select>
                                </form>
                            </td>
                            <td class="px-4 py-2">
                                @if ($absen->status === 'masuk' && $absen->waktu_kerja_selesai === '00:00:00')
                                    <form id="selesaiForm-{{ $absen->id }}"
                                        action="{{ route('absensi.selesai', $absen->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <button type="button" onclick="confirmSelesai({{ $absen->id }})"
                                            class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700 text-sm">Selesai</button>
                                    </form>
                                @else
                                    {{ $absen->waktu_kerja_selesai }}
                                @endif
                            </td>
                            <td class="px-4 py-2 flex gap-2">
                                <button onclick="openEditModal({{ $absen }})"
                                    class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600 text-sm">Edit</button>

                                <form id="deleteForm-{{ $absen->id }}"
                                    action="{{ route('absensi.destroy', $absen->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="confirmDelete({{ $absen->id }})"
                                        class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700 text-sm">Hapus</button>
                                </form>

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="px-6 py-4">
                {{ $absensis->links('vendor.pagination.tailwind') }}
            </div>

        </div>

        <!-- Modal Create -->
        <div id="createModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
                <h2 class="text-xl font-semibold mb-4">Tambah Absensi</h2>
                <form action="{{ route('absensi.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block font-medium">Nama Karyawan</label>
                        <input type="text" name="nama_karyawan" class="w-full border border-gray-300 rounded px-3 py-2"
                            required>
                    </div>
                    <div class="mb-4">
                        <label class="block font-medium">Tanggal Masuk</label>
                        <input type="date" name="tanggal_masuk" class="w-full border border-gray-300 rounded px-3 py-2"
                            required>
                    </div>
                    <div class="mb-4">
                        <label class="block font-medium">Status</label>
                        <select name="status" class="w-full border border-gray-300 rounded px-3 py-2" required>
                            <option value="masuk">Masuk</option>
                            <option value="sakit">Sakit</option>
                            <option value="cuti">Cuti</option>
                        </select>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" onclick="closeCreateModal()"
                            class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">Batal</button>
                        <button type="submit"
                            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Edit -->
        <div id="editModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
                <h2 class="text-xl font-semibold mb-4">Edit Absensi</h2>
                <form id="editForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label class="block font-medium">Nama Karyawan</label>
                        <input type="text" name="nama_karyawan" id="edit_nama"
                            class="w-full border border-gray-300 rounded px-3 py-2" required>
                    </div>
                    <div class="mb-4">
                        <label class="block font-medium">Tanggal Masuk</label>
                        <input type="date" name="tanggal_masuk" id="edit_tanggal"
                            class="w-full border border-gray-300 rounded px-3 py-2" required>
                    </div>
                    <div class="mb-4">
                        <label class="block font-medium">Status</label>
                        <select name="status" id="edit_status" class="w-full border border-gray-300 rounded px-3 py-2"
                            required>
                            <option value="masuk">Masuk</option>
                            <option value="sakit">Sakit</option>
                            <option value="cuti">Cuti</option>
                        </select>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" onclick="closeEditModal()"
                            class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">Batal</button>
                        <button type="submit"
                            class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">Update</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal JS -->
        <script>
            function openCreateModal() {
                document.getElementById('createModal').classList.remove('hidden');
            }

            function closeCreateModal() {
                document.getElementById('createModal').classList.add('hidden');
            }

            function openEditModal(absen) {
                document.getElementById('edit_nama').value = absen.nama_karyawan;
                document.getElementById('edit_tanggal').value = absen.tanggal_masuk;
                document.getElementById('edit_status').value = absen.status;

                let form = document.getElementById('editForm');
                form.action = `/absensi/${absen.id}`;

                document.getElementById('editModal').classList.remove('hidden');
            }

            function closeEditModal() {
                document.getElementById('editModal').classList.add('hidden');
            }

            function confirmDelete(id) {
                Swal.fire({
                    title: 'Yakin ingin menghapus?',
                    text: "Data tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e3342f',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById(`deleteForm-${id}`).submit();
                    }
                });
            }

            function confirmSelesai(id) {
                Swal.fire({
                    title: 'Tandai selesai kerja?',
                    text: "Data akan diupdate menjadi selesai kerja!",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#38a169',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, tandai selesai',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById(`selesaiForm-${id}`).submit();
                    }
                });
            }
        </script>
    @endsection
