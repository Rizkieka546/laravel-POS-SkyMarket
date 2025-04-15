@extends('layouts.kasir')

@section('content')
    <div class="container mx-auto p-6">
        <h3 class="text-3xl font-semibold mb-6">Transaksi Penjualan</h3>

        @if (session('success'))
            <div class="text-green-600 bg-green-100 p-4 mb-4 rounded-lg">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="text-red-600 bg-red-100 p-4 mb-4 rounded-lg">{{ session('error') }}</div>
        @endif

        <div class="mb-6">
            <form action="{{ route('transaksi.tambahBarang') }}" method="POST" class="flex items-center space-x-4">
                @csrf
                <input type="text" name="kode_barang" placeholder="Scan Barcode"
                    class="p-2 border border-gray-300 rounded-lg w-full max-w-xs focus:outline-none focus:ring-2 focus:ring-blue-500"
                    autofocus required>
                <input type="number" name="jumlah" value="1" min="1"
                    class="p-2 border border-gray-300 rounded-lg w-24 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>
                <button type="submit"
                    class="bg-blue-500 text-white p-2 rounded-lg hover:bg-blue-600 transition duration-300">Tambah</button>
            </form>
        </div>

        <h4 class="text-2xl font-medium mb-4">Keranjang</h4>
        <div class="overflow-x-auto bg-white shadow-lg rounded-lg">
            <table class="min-w-full table-auto">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="p-3 text-left">Kode</th>
                        <th class="p-3 text-left">Nama</th>
                        <th class="p-3 text-right">Harga</th>
                        <th class="p-3 text-right">Jumlah</th>
                        <th class="p-3 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @php $total = 0; @endphp
                    @foreach ($keranjang as $item)
                        <tr>
                            <td class="p-3">{{ $item['kode_barang'] }}</td>
                            <td class="p-3">{{ $item['nama_barang'] }}</td>
                            <td class="p-3 text-right">{{ number_format($item['harga_jual'], 0, ',', '.') }}</td>
                            <td class="p-3 text-right">{{ $item['jumlah'] }}</td>
                            <td class="p-3 text-right">{{ number_format($item['sub_total'], 0, ',', '.') }}</td>
                        </tr>
                        @php $total += $item['sub_total']; @endphp
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" class="p-3 text-right font-semibold">Total</td>
                        <td class="p-3 text-right font-semibold">{{ number_format($total, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="mt-6">
            <form action="{{ route('transaksi.simpan') }}" method="POST" class="flex justify-between items-center">
                @csrf
                <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                <button type="submit"
                    class="bg-green-500 text-white py-2 px-6 rounded-lg hover:bg-green-600 transition duration-300">Simpan
                    Transaksi</button>
            </form>
        </div>
    </div>
@endsection
