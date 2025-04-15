<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Manajer</title>
</head>

<body class="bg-gray-50 font-sans">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-72 bg-[#493D9E] text-white shadow-xl p-6 flex flex-col fixed h-full">
            <h1 class="text-3xl font-extrabold text-center mb-6">SKYMARKET</h1>
            <nav class="space-y-4 flex-1">
                <a href="{{ route('dashboard.manajer') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-purple-600 transition">
                    <i class="fas fa-home"></i> <span>Dashboard</span>
                </a>
                <a href="{{ route('laporan.penjualan') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-purple-600 transition">
                    <i class="fas fa-calendar-check"></i> <span>Laporan Penjualan</span>
                </a>
                <a href="{{ route('laporan.pembelian') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-purple-600 transition">
                    <i class="fas fa-calendar-check"></i> <span>Laporan Pembelian</span>
                </a>
                <a href="{{ route('laporan.stok') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-purple-600 transition">
                    <i class="fas fa-box"></i> <span>Laporan Stok Barang</span>
                </a>
                <a href="{{ route('laporan.keuangan') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-purple-600 transition">
                    <i class="fas fa-chart-line"></i> <span>Laporan Keuangan</span>
                </a>
            </nav>
            <form action="{{ route('logout') }}" method="POST"
                class="mt-auto flex items-center gap-3 px-4 py-3 bg-red-500 hover:bg-red-600 rounded-lg transition text-center">
                @csrf
                <button type="submit">
                    <i class="fa-solid fa-sign-out-alt mr-2"></i> Logout
                </button>
            </form>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 ml-72 p-8 overflow-y-auto h-screen">
            @yield('content')
        </div>
    </div>
</body>

</html>
