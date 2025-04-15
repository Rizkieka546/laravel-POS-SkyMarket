@extends('layouts.kasir')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-semibold mb-6">Pembayaran</h1>

        <div class="bg-white shadow-lg rounded-lg p-6">
            <div class="mb-4">
                <h5 class="text-lg font-medium">Nama Pelanggan: <span
                        class="font-light">{{ $penjualan->user->name ?? '-' }}</span></h5>
                <h5 class="text-lg font-medium">Tanggal: <span
                        class="font-light">{{ $penjualan->created_at->format('d-m-Y H:i') }}</span></h5>
            </div>

            <table class="min-w-full bg-gray-50 border border-gray-200 rounded-lg shadow-sm">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="p-3 text-left text-sm font-semibold">Barang</th>
                        <th class="p-3 text-left text-sm font-semibold">Jumlah</th>
                        <th class="p-3 text-left text-sm font-semibold">Harga</th>
                        <th class="p-3 text-left text-sm font-semibold">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($penjualan->detailPenjualan as $detail)
                        <tr>
                            <td class="p-3">{{ $detail->barang->nama_barang }}</td>
                            <td class="p-3">{{ $detail->jumlah }}</td>
                            <td class="p-3">Rp {{ number_format($detail->barang->harga_jual, 0, ',', '.') }}</td>
                            <td class="p-3">Rp {{ number_format($detail->sub_total, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <h4 class="text-right mt-4 text-xl font-semibold">Total: Rp
                {{ number_format($penjualan->total_bayar, 0, ',', '.') }}</h4>

            <form action="{{ route('transaksi.prosesPembayaran', ['id' => $penjualan->id]) }}" method="POST"
                class="mt-6">
                @csrf
                <div class="mb-4">
                    <label for="uang_diterima" class="block text-sm font-medium text-gray-700">Uang Diterima</label>
                    <input type="number"
                        class="mt-1 p-2 w-full border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        id="uang_diterima" name="uang_diterima" min="0" required>
                </div>

                <div class="mb-4">
                    <label for="kembalian" class="block text-sm font-medium text-gray-700">Kembalian</label>
                    <input type="text"
                        class="mt-1 p-2 w-full border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        id="kembalian" value="Rp 0" readonly>
                </div>

                <button type="submit"
                    class="bg-blue-500 text-white py-2 px-6 rounded-lg hover:bg-blue-600 transition duration-300">Proses
                    Pembayaran</button>
            </form>
        </div>
    </div>

    <script>
        const uangDiterimaInput = document.getElementById('uang_diterima');
        const kembalianInput = document.getElementById('kembalian');
        const totalBayar = {{ $penjualan->total_bayar }};

        uangDiterimaInput.addEventListener('input', function() {
            const uangDiterima = parseFloat(uangDiterimaInput.value) || 0;
            const kembalian = uangDiterima - totalBayar;

            if (kembalian < 0) {
                kembalianInput.value = 'Rp 0';
            } else {
                kembalianInput.value = 'Rp ' + kembalian.toLocaleString();
            }
        });
    </script>
@endsection
