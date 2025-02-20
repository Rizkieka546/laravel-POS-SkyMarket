<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="flex items-center justify-center min-h-screen bg-gradient-to-r from-blue-500 to-indigo-600">
    <div class="w-full max-w-md p-6 bg-white rounded-2xl shadow-lg">
        <h2 class="text-2xl font-bold text-center text-gray-700">Masuk ke Akun</h2>
        <form class="mt-6" method="POST" action="{{ route('login.process') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-600 text-sm font-medium" for="email">Email</label>
                <input type="email" name="email" id="email" required
                    class="w-full px-4 py-2 mt-2 border rounded-lg focus:ring focus:ring-indigo-300 focus:outline-none">
                @error('email')
                <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-6">
                <label class="block text-gray-600 text-sm font-medium" for="password">Password</label>
                <input type="password" name="password" id="password" required
                    class="w-full px-4 py-2 mt-2 border rounded-lg focus:ring focus:ring-indigo-300 focus:outline-none">
                @error('password')
                <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit"
                class="w-full px-4 py-2 text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-300">
                Masuk
            </button>
        </form>

        <p class="mt-4 text-sm text-center text-gray-600">Belum punya akun? <a href="{{ route('register') }}"
                class="text-indigo-500 hover:underline">Daftar</a></p>
    </div>
</body>

</html>