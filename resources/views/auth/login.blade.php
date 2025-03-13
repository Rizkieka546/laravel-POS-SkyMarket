<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="flex items-center justify-center min-h-screen bg-gradient-to-r from-teal-500 to-white">
    <div class="w-full max-w-md p-8 bg-white rounded-3xl shadow-2xl transform transition duration-500 hover:scale-105">
        <h2 class="text-3xl font-extrabold text-center text-gray-800">Selamat Datang</h2>
        <p class="text-center text-gray-500 text-sm">Masuk ke akun Anda untuk melanjutkan</p>
        <form class="mt-6" method="POST" action="{{ route('login.process') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-600 text-sm font-semibold" for="email">Email</label>
                <input type="email" name="email" id="email" required
                    class="w-full px-4 py-3 mt-2 border border-gray-300 rounded-xl focus:ring focus:ring-indigo-300 focus:outline-none">
                @error('email')
                <p class="text-red-500 text-xs">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-6 relative">
                <label class="block text-gray-600 text-sm font-semibold" for="password">Password</label>
                <input type="password" name="password" id="password" required
                    class="w-full px-4 py-3 mt-2 border border-gray-300 rounded-xl focus:ring focus:ring-indigo-300 focus:outline-none">
                @error('password')
                <p class="text-red-500 text-xs">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit"
                class="w-full px-4 py-3 text-white bg-teal-600 rounded-xl hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-300 shadow-lg transform transition duration-300 hover:scale-105">
                Masuk
            </button>
        </form>

    </div>
</body>

</html>