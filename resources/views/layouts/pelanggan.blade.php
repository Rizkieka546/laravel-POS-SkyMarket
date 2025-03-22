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

<body class="bg-gray-100 flex">
    <!-- Sidebar -->
    <aside class="w-64 bg-gradient-to-b from-teal-400 to-teal-600 text-white p-6 shadow-lg min-h-screen flex flex-col">
        <div class="text-center text-2xl font-bold mb-6">
            <i class="fa-solid fa-store mr-2"></i> Skymarket
        </div>
        <nav class="space-y-4 flex-1">
            <a href="{{ route('dashboard.pelanggan') }}"
                class="flex items-center px-4 py-3 rounded-lg hover:bg-teal-500">
                <i class="fas fa-home mr-3"></i> <span>Dashboard</span>
            </a>
            @if (Auth::user()->membership)
                <a href="{{ route('pengajuan.index') }}"
                    class="flex items-center px-4 py-3 rounded-lg hover:bg-teal-500">
                    <i class="fas fa-shopping-cart mr-3"></i> <span>Pengajuan</span>
                </a>
            @endif
        </nav>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col min-h-screen">
        <!-- Navbar -->
        <header class="bg-white shadow-md py-4 px-6 flex justify-end items-center border-b">
            <div class="flex items-center space-x-4">
                @if (!Auth::user()->membership)
                    <a href="{{ route('membership.register') }}"
                        class="bg-teal-500 hover:bg-teal-600 text-white px-4 py-2 rounded-lg text-sm flex items-center">
                        <i class="fa-solid fa-user-plus mr-2"></i> Daftar Membership
                    </a>
                @endif
                <span class="hidden md:inline text-gray-800">Halo, {{ Auth::user()->name }}</span>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="flex items-center text-gray-800 hover:text-red-500 transition-all duration-200">
                        <i class="fa-solid fa-sign-out-alt mr-2"></i> Logout
                    </button>
                </form>
            </div>
        </header>

        <!-- Content Section -->
        <main class="flex-1 p-6">
            @yield('content')
        </main>
    </div>
</body>

</html>
