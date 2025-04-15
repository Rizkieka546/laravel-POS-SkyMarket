@extends('layouts.kasir')

@section('content')
    <div class="flex-1 p-6">

        <div class="bg-white p-6 shadow-md rounded-lg overflow-x-auto">
            <table class="w-full border-collapse text-gray-700">
                <thead>
                    <tr class="bg-gray-200 text-left text-sm uppercase">
                        <th class="border-b border-gray-300 px-4 py-3">No Faktur</th>
                        <th class="border-b border-gray-300 px-4 py-3">Tanggal</th>
                        <th class="border-b border-gray-300 px-4 py-3">Total Bayar</th>
                        <th class="border-b border-gray-300 px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($penjualans as $penjualan)
                        <tr class="hover:bg-gray-100 transition">
                            <td class="border-b border-gray-300 px-4 py-3 font-bold">{{ $penjualan->no_faktur }}</td>
                            <td class="border-b border-gray-300 px-4 py-3">{{ $penjualan->tgl_faktur->format('d-m-Y H:i') }}
                            </td>
                            <td class="border-b border-gray-300 px-4 py-3 font-semibold text-green-600">
                                Rp{{ number_format($penjualan->total_bayar, 0, ',', '.') }}
                            </td>
                            <td class="border-b border-gray-300 px-4 py-3 text-center">
                                <a href="{{ route('penjualan.show', $penjualan->id) }}"
                                    class="bg-gray-500 text-white px-3 py-2 rounded-lg shadow-md hover:bg-gray-600 transition">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Paginate -->
            <div class="mt-4">
                {{ $penjualans->links('vendor.pagination.tailwind') }}
            </div>
        </div>
    </div>
@endsection
