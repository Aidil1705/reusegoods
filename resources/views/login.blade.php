<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - ReGoods</title>
    @vite('resources/css/app.css') <!-- Tailwind -->
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center">

    <div class="w-full max-w-md">
        
        <!-- Logo & Title -->
        <div class="text-center mb-6">
            <div class="flex justify-center mb-2">
                <div class="bg-green-500 p-3 rounded-lg">
                    <!-- Icon box sederhana -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4v10l8 4 8-4V7z" />
                    </svg>
                </div>
            </div>
            <h1 class="text-xl font-semibold text-gray-800">ReGoods</h1>
            <p class="text-gray-500 text-sm">Masuk ke akun Anda</p>
        </div>

        <!-- Card -->
        <div class="bg-white shadow-md rounded-2xl p-6">
            
            <form method="POST" action="{{ route('login.post') }}">
            @csrf

            <!-- Email -->
            <div class="mb-4">
                <label class="block text-sm text-gray-600 mb-1">Email</label>
                <input 
                    type="email" 
                    name="email"
                    placeholder="anda@example.com"
                    class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-400"
                    required
                >
            </div>

            <!-- Password -->
            <div class="mb-4">
                <label class="block text-sm text-gray-600 mb-1">Kata Sandi</label>
                <input 
                    type="password" 
                    name="password"
                    placeholder="********"
                    class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-400"
                    required
                >
            </div>

            <!-- Remember Me -->
            <div class="flex items-center mb-4">
                <input 
                    type="checkbox" 
                    name="remember" 
                    id="remember"
                    class="accent-green-500 mr-2"
                >
                <label for="remember" class="text-sm text-gray-600">
                    Ingat saya
                </label>
            </div>

            <!-- Button -->
            <button 
                type="submit"
                class="w-full bg-green-500 hover:bg-green-600 text-white font-medium py-2 rounded-lg transition"
            >
                Masuk
            </button>
        </form>

            <!-- Links -->
            <div class="text-center mt-4 text-sm text-gray-500">
                <p>
                    Belum punya akun? 
                    <a href="" class="text-green-500 hover:underline">Daftar</a>
                </p>

                <p class="mt-2">
                    <a href="" class="text-gray-400 hover:text-green-500">
                        Login Admin
                    </a>
                </p>
            </div>

        </div>

    </div>

</body>
</html>