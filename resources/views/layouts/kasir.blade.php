<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Skymarket</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-72 bg-gradient-to-b from-indigo-600 to-indigo-800 text-white p-6 flex flex-col shadow-lg">
            <div class="text-center text-2xl font-bold mb-6">
                <i class="fa-solid fa-store mr-2"></i> Skymarket
            </div>
            <nav class="space-y-4">
                <a href="{{ route('penjualan.index') }}"
                    class="flex items-center px-4 py-3 rounded-lg hover:bg-indigo-500">
                    <i class="fas fa-shopping-cart mr-3"></i> <span>Penjualan</span>
                </a>
                <a href="{{ route('penjualan.create') }}"
                    class="flex items-center px-4 py-3 rounded-lg hover:bg-indigo-500">
                    <i class="fas fa-cash-register mr-3"></i> <span>Kasir</span>
                </a>
            </nav>
            <div class="mt-auto">
                <button
                    class="w-full bg-red-500 hover:bg-red-600 text-white px-4 py-3 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-sign-out-alt mr-2"></i> Logout
                </button>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            @yield('content')
        </div>
    </div>
</body>

</html>