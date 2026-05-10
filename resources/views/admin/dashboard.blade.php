@extends('admin.layouts.app')

@section('title', 'Ringkasan')
@section('page-title', 'Ringkasan Admin')

@section('content')

{{-- Stat Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">

    {{-- Total Barang --}}
    <div class="bg-white rounded-2xl border border-slate-100 p-5">
        <div class="flex items-start justify-between mb-4">
            <div class="w-10 h-10 rounded-xl bg-brand-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            <span class="text-xs font-500 text-brand-600 bg-brand-50 px-2 py-0.5 rounded-full" style="font-weight:500">+12 minggu ini</span>
        </div>
        <p class="text-2xl font-700 text-slate-800 mb-0.5" style="font-weight:700">{{ $totalProducts ?? 142 }}</p>
        <p class="text-sm text-slate-400">Total Barang Listing</p>
    </div>

    {{-- Pesanan Baru --}}
    <div class="bg-white rounded-2xl border border-slate-100 p-5">
        <div class="flex items-start justify-between mb-4">
            <div class="w-10 h-10 rounded-xl bg-orange-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <span class="text-xs font-500 text-orange-500 bg-orange-50 px-2 py-0.5 rounded-full" style="font-weight:500">3 menunggu</span>
        </div>
        <p class="text-2xl font-700 text-slate-800 mb-0.5" style="font-weight:700">{{ $totalOrders ?? 38 }}</p>
        <p class="text-sm text-slate-400">Total Pesanan</p>
    </div>

    {{-- Total Pengguna --}}
    <div class="bg-white rounded-2xl border border-slate-100 p-5">
        <div class="flex items-start justify-between mb-4">
            <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <span class="text-xs font-500 text-blue-500 bg-blue-50 px-2 py-0.5 rounded-full" style="font-weight:500">{{ $activeUsers ?? 3 }} aktif</span>
        </div>
        <p class="text-2xl font-700 text-slate-800 mb-0.5" style="font-weight:700">{{ $totalUsers ?? 4 }}</p>
        <p class="text-sm text-slate-400">Total Pengguna</p>
    </div>

    {{-- Total Admin --}}
    <div class="bg-white rounded-2xl border border-slate-100 p-5">
        <div class="flex items-start justify-between mb-4">
            <div class="w-10 h-10 rounded-xl bg-purple-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>
            <span class="text-xs font-500 text-purple-500 bg-purple-50 px-2 py-0.5 rounded-full" style="font-weight:500">Semua aktif</span>
        </div>
        <p class="text-2xl font-700 text-slate-800 mb-0.5" style="font-weight:700">{{ $totalAdmins ?? 2 }}</p>
        <p class="text-sm text-slate-400">Total Admin</p>
    </div>
</div>

{{-- Middle Row --}}
<div class="grid grid-cols-1 xl:grid-cols-3 gap-4 mb-6">

    {{-- Pesanan Menunggu --}}
    <div class="xl:col-span-2 bg-white rounded-2xl border border-slate-100">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
            <h2 class="text-sm font-600 text-slate-700" style="font-weight:600">Pesanan Perlu Diproses</h2>
            <a href="{{ route('admin.orders.index') }}" class="text-xs text-brand-600 hover:text-brand-700 font-500" style="font-weight:500">Lihat semua →</a>
        </div>
        <div class="divide-y divide-slate-50">
            @forelse($pendingOrders ?? [] as $order)
            <div class="flex items-center gap-4 px-6 py-3.5 hover:bg-slate-50 transition-colors">
                <div class="w-9 h-9 rounded-xl bg-slate-100 flex items-center justify-center flex-shrink-0 overflow-hidden">
                    @if($order->product_image)
                        <img src="{{ Storage::url($order->product_image) }}" class="w-full h-full object-cover">
                    @else
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-500 text-slate-700 truncate" style="font-weight:500">{{ $order->product_name }}</p>
                    <p class="text-xs text-slate-400">{{ $order->buyer_name }} · {{ $order->created_at->diffForHumans() }}</p>
                </div>
                <div class="text-right flex-shrink-0">
                    <p class="text-sm font-600 text-slate-800" style="font-weight:600">Rp{{ number_format($order->total_price, 0, ',', '.') }}</p>
                    <span class="text-[10px] font-500 px-2 py-0.5 rounded-full bg-yellow-50 text-yellow-600" style="font-weight:500">Menunggu</span>
                </div>
            </div>
            @empty
            {{-- Demo data --}}
            @foreach([
                ['name'=>'Magic Com Miyako 1.8L','buyer'=>'Alice Buyer','price'=>'125.000','time'=>'5 menit lalu','status'=>'Menunggu','color'=>'yellow'],
                ['name'=>'Setrika Philips GC1010','buyer'=>'Bob Seller','price'=>'80.000','time'=>'12 menit lalu','status'=>'Dikonfirmasi','color'=>'blue'],
                ['name'=>'Kipas Angin Cosmos 16"','buyer'=>'Diana Merchant','price'=>'145.000','time'=>'1 jam lalu','status'=>'Menunggu','color'=>'yellow'],
                ['name'=>'Dispenser Sanken S-888','buyer'=>'Charlie User','price'=>'210.000','time'=>'2 jam lalu','status'=>'Dikirim','color'=>'green'],
            ] as $order)
            <div class="flex items-center gap-4 px-6 py-3.5 hover:bg-slate-50 transition-colors">
                <div class="w-9 h-9 rounded-xl bg-slate-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-500 text-slate-700 truncate" style="font-weight:500">{{ $order['name'] }}</p>
                    <p class="text-xs text-slate-400">{{ $order['buyer'] }} · {{ $order['time'] }}</p>
                </div>
                <div class="text-right flex-shrink-0">
                    <p class="text-sm font-600 text-slate-800" style="font-weight:600">Rp{{ $order['price'] }}</p>
                    @if($order['color'] === 'yellow')
                        <span class="text-[10px] font-500 px-2 py-0.5 rounded-full bg-yellow-50 text-yellow-600" style="font-weight:500">{{ $order['status'] }}</span>
                    @elseif($order['color'] === 'blue')
                        <span class="text-[10px] font-500 px-2 py-0.5 rounded-full bg-blue-50 text-blue-600" style="font-weight:500">{{ $order['status'] }}</span>
                    @else
                        <span class="text-[10px] font-500 px-2 py-0.5 rounded-full bg-green-50 text-green-600" style="font-weight:500">{{ $order['status'] }}</span>
                    @endif
                </div>
            </div>
            @endforeach
            @endforelse
        </div>
    </div>

    {{-- Kategori Populer --}}
    <div class="bg-white rounded-2xl border border-slate-100">
        <div class="px-6 py-4 border-b border-slate-100">
            <h2 class="text-sm font-600 text-slate-700" style="font-weight:600">Kategori Terlaris</h2>
        </div>
        <div class="px-6 py-4 space-y-3">
            @foreach([
                ['label'=>'Peralatan Dapur','count'=>48,'pct'=>72,'color'=>'bg-brand-500'],
                ['label'=>'Elektronik Kamar','count'=>31,'pct'=>47,'color'=>'bg-blue-400'],
                ['label'=>'Furnitur Kecil','count'=>24,'pct'=>36,'color'=>'bg-purple-400'],
                ['label'=>'Alat Kebersihan','count'=>18,'pct'=>27,'color'=>'bg-orange-400'],
                ['label'=>'Buku & ATK','count'=>9,'pct'=>13,'color'=>'bg-pink-400'],
            ] as $cat)
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <span class="text-xs font-500 text-slate-600" style="font-weight:500">{{ $cat['label'] }}</span>
                    <span class="text-xs text-slate-400">{{ $cat['count'] }} barang</span>
                </div>
                <div class="h-1.5 bg-slate-100 rounded-full overflow-hidden">
                    <div class="{{ $cat['color'] }} h-full rounded-full" style="width: {{ $cat['pct'] }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Bottom Row --}}
<div class="grid grid-cols-1 xl:grid-cols-2 gap-4">

    {{-- Pengguna Terbaru --}}
    <div class="bg-white rounded-2xl border border-slate-100">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
            <h2 class="text-sm font-600 text-slate-700" style="font-weight:600">Pengguna Terbaru</h2>
            <a href="{{ route('admin.users.index') }}" class="text-xs text-brand-600 hover:text-brand-700 font-500" style="font-weight:500">Kelola →</a>
        </div>
        <div class="divide-y divide-slate-50">
            @forelse($recentUsers ?? [] as $user)
            <div class="flex items-center gap-3 px-6 py-3 hover:bg-slate-50 transition-colors">
                <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-xs font-600 text-slate-500 flex-shrink-0" style="font-weight:600">
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-500 text-slate-700 truncate" style="font-weight:500">{{ $user->name }}</p>
                    <p class="text-xs text-slate-400 truncate">{{ $user->email }}</p>
                </div>
                <span class="text-[10px] font-500 px-2.5 py-0.5 rounded-full flex-shrink-0 {{ $user->is_active ? 'bg-green-50 text-green-600' : 'bg-slate-100 text-slate-400' }}" style="font-weight:500">
                    {{ $user->is_active ? 'aktif' : 'nonaktif' }}
                </span>
            </div>
            @empty
            @foreach([
                ['name'=>'Alice Buyer','email'=>'alice@example.com','active'=>true,'initials'=>'AB'],
                ['name'=>'Bob Seller','email'=>'bob@example.com','active'=>true,'initials'=>'BS'],
                ['name'=>'Charlie User','email'=>'charlie@example.com','active'=>false,'initials'=>'CU'],
                ['name'=>'Diana Merchant','email'=>'diana@example.com','active'=>true,'initials'=>'DM'],
            ] as $user)
            <div class="flex items-center gap-3 px-6 py-3 hover:bg-slate-50 transition-colors">
                <div class="w-8 h-8 rounded-full bg-brand-100 flex items-center justify-center text-xs font-600 text-brand-700 flex-shrink-0" style="font-weight:600">
                    {{ $user['initials'] }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-500 text-slate-700" style="font-weight:500">{{ $user['name'] }}</p>
                    <p class="text-xs text-slate-400">{{ $user['email'] }}</p>
                </div>
                <span class="text-[10px] font-500 px-2.5 py-0.5 rounded-full flex-shrink-0 {{ $user['active'] ? 'bg-green-50 text-green-600' : 'bg-slate-100 text-slate-400' }}" style="font-weight:500">
                    {{ $user['active'] ? 'aktif' : 'nonaktif' }}
                </span>
            </div>
            @endforeach
            @endforelse
        </div>
    </div>

    {{-- Tim Admin --}}
    <div class="bg-white rounded-2xl border border-slate-100">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
            <h2 class="text-sm font-600 text-slate-700" style="font-weight:600">Tim Admin</h2>
            <a href="{{ route('admin.admins.index') }}" class="text-xs text-brand-600 hover:text-brand-700 font-500" style="font-weight:500">Kelola →</a>
        </div>
        <div class="divide-y divide-slate-50">
            @forelse($admins ?? [] as $admin)
            <div class="flex items-center gap-3 px-6 py-4 hover:bg-slate-50 transition-colors">
                <div class="w-9 h-9 rounded-full bg-purple-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-500 text-slate-700" style="font-weight:500">{{ $admin->name }}</p>
                    <p class="text-xs text-slate-400">{{ $admin->email }}</p>
                </div>
            </div>
            @empty
            @foreach([
                ['name'=>'John Admin','email'=>'john@regoods.com'],
                ['name'=>'Sarah Manager','email'=>'sarah@regoods.com'],
            ] as $admin)
            <div class="flex items-center gap-3 px-6 py-4 hover:bg-slate-50 transition-colors">
                <div class="w-9 h-9 rounded-full bg-purple-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-500 text-slate-700" style="font-weight:500">{{ $admin['name'] }}</p>
                    <p class="text-xs text-slate-400">{{ $admin['email'] }}</p>
                </div>
            </div>
            @endforeach
            @endforelse
        </div>
    </div>
</div>

@endsection