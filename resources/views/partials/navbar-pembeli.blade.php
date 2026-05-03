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

        <!-- Profile Dropdown -->
        <div class="relative" data-dropdown>
            <button type="button" data-dropdown-trigger class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center text-sm cursor-pointer">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}{{ strtoupper(substr(explode(' ', Auth::user()->name)[1] ?? '', 0, 1)) }}
            </button>
            
            <!-- Dropdown Menu -->
            <div data-dropdown-menu class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg hidden z-10">
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const dropdowns = document.querySelectorAll('[data-dropdown]');

        dropdowns.forEach(function (dropdown) {
            const trigger = dropdown.querySelector('[data-dropdown-trigger]');
            const menu = dropdown.querySelector('[data-dropdown-menu]');

            if (!trigger || !menu) {
                return;
            }

            trigger.addEventListener('click', function (event) {
                event.preventDefault();
                event.stopPropagation();

                dropdowns.forEach(function (otherDropdown) {
                    if (otherDropdown !== dropdown) {
                        const otherMenu = otherDropdown.querySelector('[data-dropdown-menu]');
                        if (otherMenu) {
                            otherMenu.classList.add('hidden');
                        }
                    }
                });

                menu.classList.toggle('hidden');
            });
        });

        document.addEventListener('click', function (event) {
            dropdowns.forEach(function (dropdown) {
                const menu = dropdown.querySelector('[data-dropdown-menu]');
                if (menu && !dropdown.contains(event.target)) {
                    menu.classList.add('hidden');
                }
            });
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                dropdowns.forEach(function (dropdown) {
                    const menu = dropdown.querySelector('[data-dropdown-menu]');
                    if (menu) {
                        menu.classList.add('hidden');
                    }
                });
            }
        });
    });
</script>