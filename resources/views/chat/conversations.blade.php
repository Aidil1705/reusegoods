@extends('layouts.pembeli')

@section('title', 'Chat - ReGoods')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Pesan</h1>
            <p class="text-gray-600">Kelola percakapan Anda</p>
        </div>
        <a href="{{ route('pembeli.dashboard') }}" class="flex items-center gap-2 bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-2 rounded-2xl transition font-medium">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
            Kembali ke Beranda
        </a>
    </div>

    <!-- Conversations List -->
    <div class="grid grid-cols-1 gap-4">
        @forelse($conversations as $conversation)
            @php
                $otherUser = Auth::id() === $conversation->seller_id 
                    ? $conversation->buyer 
                    : $conversation->seller;
                $unreadCount = $conversation->unreadCount(Auth::id());
            @endphp
            
            <a href="{{ route('chat.show', $conversation) }}" class="block">
                <div class="bg-white rounded-2xl shadow hover:shadow-lg transition p-4 border-l-4 {{ $unreadCount > 0 ? 'border-emerald-500' : 'border-gray-200' }}">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <h3 class="font-semibold text-gray-800">{{ $otherUser->name }}</h3>
                                @if($conversation->product)
                                    <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded">
                                        {{ $conversation->product->name }}
                                    </span>
                                @endif
                                @if($unreadCount > 0)
                                    <span class="ml-auto text-xs font-bold bg-emerald-500 text-white px-2 py-1 rounded-full">
                                        {{ $unreadCount }}
                                    </span>
                                @endif
                            </div>
                            
                            @if($conversation->latestMessage)
                                <p class="text-sm text-gray-600 truncate">
                                    @if($conversation->latestMessage->sender_id === Auth::id())
                                        <span class="text-gray-500">Anda:</span>
                                    @else
                                        <span class="font-semibold">{{ $conversation->latestMessage->sender->name }}:</span>
                                    @endif
                                    {{ $conversation->latestMessage->message }}
                                </p>
                            @endif
                            
                            <p class="text-xs text-gray-500 mt-2">
                                {{ $conversation->last_message_at?->diffForHumans() ?? 'Baru dibuat' }}
                            </p>
                        </div>
                    </div>
                </div>
            </a>
        @empty
            <div class="bg-white rounded-2xl shadow p-12 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Belum Ada Percakapan</h3>
                <p class="text-gray-600">Mulai percakapan dengan menjelajahi produk atau hubungi penjual langsung</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($conversations->hasPages())
        <div class="mt-6">
            {{ $conversations->links() }}
        </div>
    @endif
</div>
@endsection
