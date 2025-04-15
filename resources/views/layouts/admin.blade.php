<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkyMarket</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="//unpkg.com/alpinejs" defer></script>

</head>

<body class="bg-gray-100 h-screen overflow-hidden">
    <div class="flex h-full">
        <!-- Sidebar -->
        <aside class="w-64 bg-teal-600 text-white p-6 shadow-lg fixed h-full">
            <div class="text-center text-2xl font-bold mb-6"><i class="fa-solid fa-store mr-2"></i>SkyMarket</div>
            <nav>
                <ul>
                    <li class="mb-3">
                        <a href="{{ route('dashboard.admin') }}"
                            class="flex items-center px-4 py-2 rounded-lg hover:bg-teal-500">
                            <i class="fa-solid fa-home mr-3"></i> Dashboard
                        </a>
                    </li>
                    <li class="mb-3">
                        <a href="{{ route('kategori.index') }}"
                            class="flex items-center px-4 py-2 rounded-lg hover:bg-teal-500">
                            <i class="fa-solid fa-list mr-3"></i> Kategori
                        </a>
                    </li>
                    <li class="mb-3">
                        <a href="{{ route('barang.index') }}"
                            class="flex items-center px-4 py-2 rounded-lg hover:bg-teal-500">
                            <i class="fa-solid fa-box-open mr-3"></i> Barang
                        </a>
                    </li>
                    <li class="mb-3">
                        <a href="{{ route('pemasok.index') }}"
                            class="flex items-center px-4 py-2 rounded-lg hover:bg-teal-500">
                            <i class="fa-solid fa-truck mr-3"></i> Pemasok
                        </a>
                    </li>
                    <li class="mb-3">
                        <a href="{{ route('pembelian.index') }}"
                            class="flex items-center px-4 py-2 rounded-lg hover:bg-teal-500">
                            <i class="fa-solid fa-cart-shopping mr-3"></i> Barang Masuk
                        </a>
                    </li>
                    <li class="mb-3">
                        <a href="{{ route('user.index') }}"
                            class="flex items-center px-4 py-2 rounded-lg hover:bg-teal-500">
                            <i class="fa-solid fa-user mr-3"></i> User
                        </a>
                    </li>
                    <li class="mb-3">

                        <a href="{{ route('pengajuan.admin') }}"
                            class="flex items-center px-4 py-2 rounded-lg hover:bg-teal-500">
                            <i class="fa-solid fa-clipboard-list mr-3"></i> Pengajuan
                        </a>
                    </li>

                </ul>
            </nav>
        </aside>
        <!-- End Sidebar -->

        <div class="flex-1 flex flex-col ml-64">
            <!-- Navbar -->
            <header class="bg-teal-500 text-white p-4 flex justify-end items-center shadow-md fixed w-full z-50">

                <div class="flex items-center space-x-4 mr-64">
                    <span class="hidden md:inline">Halo, {{ Auth::user()->name }}</span>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="flex items-center text-white hover:text-red-400">
                            <i class="fa-solid fa-sign-out-alt mr-2"></i> Logout
                        </button>
                    </form>
                </div>
            </header>


            <!-- End Navbar -->

            <!-- Content -->
            <main class="flex-1 p-6 overflow-y-auto mt-[64px]">
                @yield('content')
            </main>

        </div>
    </div>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>


</html>
