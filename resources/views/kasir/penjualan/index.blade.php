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
                        <button onclick="showStruk({{ $penjualan->id }})"
                            class="bg-teal-500 text-white px-3 py-2 rounded-lg shadow-md hover:bg-teal-600 transition">
                            Struk
                        </button>
                        <a href="{{ route('penjualan.show', $penjualan->id) }}"
                            class="bg-gray-500 text-white px-3 py-2 rounded-lg shadow-md hover:bg-gray-600 transition">
                            Detail
                        </a>
                    </td>
                </tr>

                <div id="modal-struk-{{ $penjualan->id }}"
                    class="fixed inset-0 flex justify-center items-center bg-gray-900 bg-opacity-50 hidden">
                    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-sm text-center border border-gray-300 transform transition-transform scale-95 opacity-0"
                        id="struk-{{ $penjualan->id }}">
                        <h3 class="text-2xl font-bold text-gray-800 mb-2 uppercase">SKYMARKET</h3>
                        <p class="text-gray-600 text-sm">{{ $penjualan->tgl_faktur->format('D, d/m/Y H:i') }}</p>
                        <hr class="my-3 border-gray-300">

                        <div class="text-left text-md">
                            @foreach ($penjualan->detailPenjualan as $index => $detail)
                            <div class="flex justify-between py-1">
                                <span>{{ $index + 1 }}. {{ $detail->barang->nama_barang }}
                                    ({{ $detail->jumlah }}x)</span>
                                <span
                                    class="font-semibold">Rp{{ number_format($detail->sub_total, 0, ',', '.') }}</span>
                            </div>
                            @endforeach
                        </div>

                        <hr class="my-3 border-gray-300">
                        <div class="text-md font-semibold text-left">
                            <div class="flex justify-between py-1">
                                <span>Total Bayar</span>
                                <span
                                    class="text-green-600">Rp{{ number_format($penjualan->total_bayar, 0, ',', '.') }}</span>
                            </div>
                        </div>
                        <hr class="my-3 border-gray-300">
                        <p class="text-md text-gray-500">TERIMAKASIH TELAH MEMBELI</p>

                        <div class="flex justify-between mt-4">
                            <button onclick="closeStruk({{ $penjualan->id }})"
                                class="bg-red-500 text-white px-3 py-2 rounded-lg hover:bg-red-600 transition">
                                Tutup
                            </button>
                            <button onclick="printStruk({{ $penjualan->id }})"
                                class="bg-green-500 text-white px-3 py-2 rounded-lg hover:bg-green-600 transition">
                                Cetak
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
function showStruk(id) {
    const modal = document.getElementById(`modal-struk-${id}`);
    const content = document.getElementById(`struk-${id}`);
    modal.classList.remove('hidden');
    setTimeout(() => {
        content.classList.remove('scale-95', 'opacity-0');
    }, 50);
}

function closeStruk(id) {
    const modal = document.getElementById(`modal-struk-${id}`);
    const content = document.getElementById(`struk-${id}`);
    content.classList.add('scale-95', 'opacity-0');
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 200);
}

function printStruk(id) {
    let struk = document.getElementById(`struk-${id}`).innerHTML;
    let originalContent = document.body.innerHTML;
    document.body.innerHTML = struk;
    window.print();
    document.body.innerHTML = originalContent;
}
</script>
@endsection