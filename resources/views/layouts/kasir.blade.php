<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Skymarket</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

</head>

<body class="bg-gray-100 flex">
    <!-- Sidebar -->
    <aside class="w-64 bg-gradient-to-b from-teal-400 to-teal-600 text-white p-6 shadow-lg min-h-screen flex flex-col">
        <div class="text-center text-2xl font-bold mb-6">
            <i class="fa-solid fa-store mr-2"></i> Skymarket
        </div>
        <nav class="space-y-4 flex-1">
            <a href="{{ route('dashboard.kasir') }}" class="flex items-center px-4 py-3 rounded-lg hover:bg-teal-500">
                <i class="fas fa-home mr-3"></i> <span>Dashboard</span>
            </a>
            <a href="{{ route('penjualan.index') }}" class="flex items-center px-4 py-3 rounded-lg hover:bg-teal-500">
                <i class="fas fa-shopping-cart mr-3"></i> <span>Penjualan</span>
            </a>
            <a href="{{ route('penjualan.create') }}" class="flex items-center px-4 py-3 rounded-lg hover:bg-teal-500">
                <i class="fas fa-cash-register mr-3"></i> <span>Kasir</span>
            </a>
            <a href="{{ route('transaksi.create') }}" class="flex items-center px-4 py-3 rounded-lg hover:bg-teal-500">
                <i class="fas fa-cash-register mr-3"></i> <span>Scan</span>
            </a>
        </nav>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col min-h-screen">
        <!-- Navbar -->
        <header class="bg-white shadow-md py-4 px-6 gap-4 flex justify-end items-center border-b">
            <span class="hidden md:inline">Halo, {{ Auth::user()->name }}</span>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="flex items-center text-black hover:text-red-400">
                    <i class="fa-solid fa-sign-out-alt mr-2"></i> Logout
                </button>
            </form>
        </header>

        @yield('content')
    </div>
</body>

</html>
