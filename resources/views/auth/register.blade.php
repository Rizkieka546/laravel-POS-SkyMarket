<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="flex items-center justify-center min-h-screen bg-gradient-to-r from-blue-500 to-indigo-600">
    <div class="w-full max-w-md p-6 bg-white rounded-2xl shadow-lg">
        <h2 class="text-2xl font-bold text-center text-gray-700">Daftar Akun</h2>

        @if (session('success'))
            <p class="text-green-500 text-center text-sm">{{ session('success') }}</p>
        @endif

        <form class="mt-6" method="POST" action="{{ route('register.process') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-600 text-sm font-medium" for="name">Nama Lengkap</label>
                <input type="text" id="name" name="name"
                    class="w-full px-4 py-2 mt-2 border rounded-lg focus:ring focus:ring-indigo-300 focus:outline-none"
                    required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-600 text-sm font-medium" for="email">Email</label>
                <input type="email" id="email" name="email"
                    class="w-full px-4 py-2 mt-2 border rounded-lg focus:ring focus:ring-indigo-300 focus:outline-none"
                    required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-600 text-sm font-medium" for="password">Password</label>
                <input type="password" id="password" name="password"
                    class="w-full px-4 py-2 mt-2 border rounded-lg focus:ring focus:ring-indigo-300 focus:outline-none"
                    required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-600 text-sm font-medium" for="password_confirmation">Konfirmasi
                    Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation"
                    class="w-full px-4 py-2 mt-2 border rounded-lg focus:ring focus:ring-indigo-300 focus:outline-none"
                    required>
            </div>
            <button type="submit"
                class="w-full px-4 py-2 text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-300">
                Daftar
            </button>
        </form>
        <p class="mt-4 text-sm text-center text-gray-600">Sudah punya akun? <a href="{{ route('login') }}"
                class="text-indigo-500 hover:underline">Masuk</a></p>
    </div>
</body>

</html>
