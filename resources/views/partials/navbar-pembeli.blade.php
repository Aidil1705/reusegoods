<nav class="bg-white shadow-sm px-6 py-3 flex items-center justify-between">
    
    <!-- Logo -->
    <div class="flex items-center gap-2">
         <img src="{{ asset('images/logo/logo.jpeg') }}" alt="Logo ReGoods" class="h-12 w-12">
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

        <!-- Parent -->
<div x-data="{ open: false }" class="relative">

    <!-- Avatar -->
    <div 
        @click="open = !open"
        class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center text-sm cursor-pointer"
    >
        AN
    </div>

    <!-- Dropdown -->
    <div 
        x-show="open"
        @click.outside="open = false"
        x-transition
        class="absolute right-0 mt-2 w-40 bg-white shadow-lg rounded-lg border"
        style="display: none;"
    >
        <a href="#" class="block px-4 py-2 text-sm hover:bg-gray-100">
            Profile
        </a>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="w-full text-left px-4 py-2 text-sm hover:bg-gray-100">
                Logout
            </button>
        </form>
    </div>

</div>
    </div>

</nav>