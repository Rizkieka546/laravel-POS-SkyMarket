@extends('layouts.kasir')

@section('content')
    <div class="max-w-6xl mx-auto p-6">
        <h3 class="text-3xl font-bold text-center text-gray-800 mb-8">Pembayaran Penjualan</h3>

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Kolom Tabel Penjualan -->
            <div class="md:col-span-2 bg-white rounded-xl shadow-lg border p-6">
                <h4 class="text-lg font-semibold text-gray-700 mb-4">Detail Barang</h4>
                <p class="text-sm text-gray-500 mb-4">Tanggal Faktur: <span
                        class="font-semibold">{{ $penjualan->tgl_faktur }}</span></p>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left border-collapse">
                        <thead class="bg-teal-400 text-white">
                            <tr>
                                <th class="px-4 py-2">Barang</th>
                                <th class="px-4 py-2 text-center">Jumlah</th>
                                <th class="px-4 py-2 text-right">Harga</th>
                                <th class="px-4 py-2 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="bg-gray-50 divide-y divide-gray-200">
                            @foreach ($penjualan->detailPenjualan as $detail)
                                <tr>
                                    <td class="px-4 py-2">{{ $detail->barang->nama_barang }}</td>
                                    <td class="px-4 py-2 text-center">{{ $detail->jumlah }}</td>
                                    <td class="px-4 py-2 text-right">Rp{{ number_format($detail->harga_jual, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-2 text-right">Rp{{ number_format($detail->sub_total, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Kolom Ringkasan dan Pembayaran -->
            <div class="bg-gray-50 rounded-xl shadow-lg border p-6">
                <h4 class="text-lg font-semibold text-gray-700 mb-4">Ringkasan Pembayaran</h4>

                <div class="mb-4">
                    <div class="flex justify-between py-2">
                        <span>Total:</span>
                        <span
                            class="font-bold text-gray-900">Rp{{ number_format($penjualan->total_bayar, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between py-2">
                        <span>Uang Diterima:</span>
                        <span id="uang_diterima_display" class="font-semibold">Rp0</span>
                    </div>
                    <div class="flex justify-between py-2">
                        <span>Kembalian:</span>
                        <span id="kembalian_display" class="font-bold text-green-600">Rp0</span>
                    </div>
                </div>

                <form action="{{ route('penjualan.prosesPembayaran', $penjualan->id) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="uang_diterima" class="block text-sm font-medium text-gray-700 mb-1">Uang
                            Diterima</label>
                        <input type="number" id="uang_diterima" name="uang_diterima" required
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-teal-400 outline-none" />
                    </div>
                    <button type="submit"
                        class="w-full flex items-center justify-center gap-2 bg-teal-500 hover:bg-teal-600 text-white py-3 rounded-lg font-bold transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.105 0-2 .895-2 2s.895 2 2 2 2-.895 2-2-.895-2-2-2zm0 8c-2.21 0-4-1.79-4-4H4c0 4.418 3.582 8 8 8s8-3.582 8-8h-4c0 2.21-1.79 4-4 4z" />
                        </svg>
                        Bayar Rp{{ number_format($penjualan->total_bayar, 0, ',', '.') }}
                    </button>
                </form>
            </div>
        </div>
    </div>


    <script>
        const uangDiterimaInput = document.getElementById('uang_diterima');
        const uangDiterimaDisplay = document.getElementById('uang_diterima_display');
        const kembalianDisplay = document.getElementById('kembalian_display');
        const totalBayar = {{ $penjualan->total_bayar }};

        uangDiterimaInput.addEventListener('input', function() {
            const uang = parseInt(this.value) || 0;
            const kembalian = uang - totalBayar;

            uangDiterimaDisplay.textContent = 'Rp' + uang.toLocaleString('id-ID');
            kembalianDisplay.textContent = (kembalian < 0 ? 'Rp0' : 'Rp' + kembalian.toLocaleString('id-ID'));
            kembalianDisplay.classList.toggle('text-red-600', kembalian < 0);
            kembalianDisplay.classList.toggle('text-green-600', kembalian >= 0);
        });
    </script>
@endsection
