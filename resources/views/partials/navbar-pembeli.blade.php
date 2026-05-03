<nav class="bg-white shadow-sm px-6 py-3 flex items-center justify-between">
    
    <!-- Logo -->
    <div class="flex items-center gap-2">
        <div class="bg-green-500 p-2 rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4v10l8 4 8-4V7z" />
            </svg>
        </div>
        <span class="font-semibold text-lg">ReGoods</span>
    </div>

    <!-- Search -->
    <div class="w-1/2">
        <input type="text" placeholder="Cari produk..."
            class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-400 outline-none">
    </div>

    <!-- Right -->
    <div class="flex items-center gap-4">
        <div class="relative">
            🛒
            <span class="absolute -top-2 -right-2 bg-green-500 text-white text-xs px-1 rounded-full">0</span>
        </div>

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
                
<a href="{{ route('penjual.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center gap-2">
                    <span>🏪</span>
                    Beralih ke Penjual
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

</nav>