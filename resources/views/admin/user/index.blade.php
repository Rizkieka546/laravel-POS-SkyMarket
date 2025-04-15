@extends('layouts.admin')

@section('content')
    <div class="container mx-auto p-8 bg-gray-50 min-h-screen">

        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-semibold text-gray-800">Daftar Pengguna</h2>
            <a href="{{ route('user.create') }}"
                class="bg-teal-600 hover:bg-teal-700 text-white font-medium py-2 px-5 rounded-lg shadow-md transition duration-300">
                + Tambah User
            </a>
        </div>

        <x-notification />


        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-teal-500 text-white uppercase text-sm">
                        <th class="px-6 py-3 text-left">No</th>
                        <th class="px-6 py-3 text-left">Nama</th>
                        <th class="px-6 py-3 text-left">Email</th>
                        <th class="px-6 py-3 text-left">Role</th>
                        <th class="px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($users as $key => $user)
                        <tr class="hover:bg-gray-100 transition duration-300">
                            <td class="px-6 py-4 text-gray-800">{{ $key + 1 }}</td>
                            <td class="px-6 py-4">{{ $user->name }}</td>
                            <td class="px-6 py-4">{{ $user->email }}</td>
                            <td class="px-6 py-4">{{ ucfirst($user->role) }}</td>
                            <td class="px-6 py-4 flex justify-center space-x-2">
                                <a href="{{ route('user.edit', $user->id) }}"
                                    class="bg-[#66D2CE] hover:bg-white hover:text-[#66D2CE] text-white text-sm font-medium py-2 px-4 rounded-lg shadow-md transition duration-300">
                                    Edit
                                </a>
                                <form action="{{ route('user.destroy', $user->id) }}" method="POST"
                                    class="inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="bg-red-500 hover:text-red-500 hover:bg-white text-white text-sm font-medium py-2 px-4 rounded-lg shadow-md transition duration-300">
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteForms = document.querySelectorAll('.delete-form');

            deleteForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    Swal.fire({
                        title: 'Yakin ingin menghapus?',
                        text: "Data yang dihapus tidak bisa dikembalikan!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#e3342f',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
@endsection
