<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'ReGoods Penjual')</title>
    @vite('resources/css/app.css')
    <meta name="viewport" content="width=device-width, initial-scale=1" />
</head>
<body class="bg-gray-50 min-h-screen">

    <div class="flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow h-screen sticky top-0">
            <div class="p-6">
                <div class="flex items-center gap-3">
                    <div class="bg-green-500 p-2 rounded-md text-white">RG</div>
                    <div>
                        <div class="font-bold">ReGoods</div>
                        <div class="text-sm text-gray-500">Panel Penjual</div>
                    </div>
                </div>
            </div>

            <nav class="px-4 py-2">
                <a class="block px-3 py-2 rounded-md bg-green-50 text-green-600 mb-1" href="#">Ringkasan</a>
                <a class="block px-3 py-2 rounded-md hover:bg-gray-100" href="#">Kelola Admin</a>
                <a class="block px-3 py-2 rounded-md hover:bg-gray-100" href="#">Kelola Pengguna</a>
                <a class="block px-3 py-2 rounded-md hover:bg-gray-100" href="{{ route('penjual.produk.index') }}">Kelola Produk</a>
                <a class="block px-3 py-2 rounded-md hover:bg-gray-100" href="#">Transaksi</a>
                <a class="block px-3 py-2 rounded-md hover:bg-gray-100" href="#">Kategori</a>
            </nav>
        </aside>

        <!-- Main -->
        <div class="flex-1 min-h-screen">
            <header class="bg-white shadow-sm">
                <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
                    <h2 class="text-xl font-semibold">@yield('header', 'Ringkasan Admin')</h2>
                    <div class="flex items-center gap-4">
                        <div class="text-sm text-gray-500">{{ now()->format('d M Y') }}</div>
                        
                        <!-- Profile Dropdown -->
                        <div class="relative group">
                            <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center text-sm cursor-pointer">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}{{ strtoupper(substr(explode(' ', Auth::user()->name)[1] ?? '', 0, 1)) }}
                            </div>
                            
                            <!-- Dropdown Menu -->
                            <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg hidden group-hover:block z-10">
                                <div class="p-4 border-b">
                                    <p class="font-semibold text-gray-800">{{ Auth::user()->name }}</p>
                                    <p class="text-sm text-gray-500">{{ Auth::user()->email }}</p>
                                </div>
                                
                                <a href="{{ route('pembeli.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center gap-2">
                                    <span>🛒</span>
                                    Beralih ke Pembeli
                                </a>
                                
                                <form action="{{ route('logout') }}" method="POST" class="border-t">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center gap-2">
                                        <span>🚪</span>
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <main class="max-w-7xl mx-auto p-6">
                @yield('content')
            </main>
        </div>
    </div>

</body>
</html>
