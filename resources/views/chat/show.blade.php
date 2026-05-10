@extends('layouts.pembeli')

@section('title', $conversation->seller->name . ' - Chat')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-md p-4 mb-4 flex items-center justify-between">
        <div>
            @php
                $otherUser = Auth::id() === $conversation->seller_id 
                    ? $conversation->buyer 
                    : $conversation->seller;
            @endphp
            <h2 class="text-xl font-bold text-gray-800">{{ $otherUser->name }}</h2>
            @if($conversation->product)
                <p class="text-sm text-gray-600">Tentang: {{ $conversation->product->name }}</p>
            @endif
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('chat.index') }}" class="text-gray-600 hover:text-gray-800 px-3 py-2 rounded-lg hover:bg-gray-100 transition">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M3 12l10-10v5h10v10h-10v5z"/></svg>
            </a>
            <a href="{{ route('pembeli.dashboard') }}" class="text-gray-600 hover:text-gray-800 px-3 py-2 rounded-lg hover:bg-gray-100 transition">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
            </a>
            <button onclick="if(confirm('Tutup percakapan?')) window.history.back();" class="text-gray-600 hover:text-gray-800 px-3 py-2 rounded-lg hover:bg-gray-100 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>
    </div>

    <!-- Messages Container -->
    <div class="bg-white rounded-2xl shadow-md p-6 h-96 overflow-y-auto mb-4" id="messagesContainer">
        @forelse($messages as $message)
            @php
                $isCurrentUser = $message->sender_id === Auth::id();
            @endphp
            
            <div class="mb-4 {{ $isCurrentUser ? 'text-right' : 'text-left' }}">
                <div class="inline-block {{ $isCurrentUser ? 'bg-emerald-500 text-white' : 'bg-gray-200 text-gray-800' }} rounded-2xl px-4 py-2 max-w-xs">
                    <p class="text-sm">{{ $message->message }}</p>
                    <p class="text-xs {{ $isCurrentUser ? 'text-emerald-100' : 'text-gray-600' }} mt-1">
                        {{ $message->created_at->format('H:i') }}
                        @if($isCurrentUser && $message->is_read)
                            <span>✓✓</span>
                        @endif
                    </p>
                </div>
            </div>
        @empty
            <div class="h-full flex items-center justify-center">
                <p class="text-gray-500 text-center">Belum ada pesan. Mulai percakapan!</p>
            </div>
        @endforelse
    </div>

    <!-- Message Form -->
    <form action="{{ route('chat.send', $conversation) }}" method="POST" class="flex gap-2">
        @csrf
        <input 
            type="text" 
            name="message" 
            placeholder="Tulis pesan..." 
            class="flex-1 rounded-2xl border border-gray-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500"
            required
        >
        <button 
            type="submit" 
            class="bg-emerald-500 hover:bg-emerald-600 text-white px-6 py-2 rounded-2xl font-medium transition"
        >
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                <path d="M16.6915026,12.4744748 L3.50612381,13.2599618 C3.19218622,13.2599618 3.03521743,13.4170592 3.03521743,13.5741566 L1.15159189,20.0151496 C0.8376543,20.8006365 0.99,21.89 1.77946707,22.52 C2.41,22.99 3.50612381,23.1 4.13399899,22.8429026 L21.714504,14.0454487 C22.6563168,13.5741566 23.1272231,12.6315722 22.9702544,11.6889879 L4.13399899,1.16390747 C3.34915502,0.9 2.40734225,1.00636533 1.77946707,1.4776575 C0.994623095,2.10604706 0.837654326,3.0486314 1.15159189,3.99701575 L3.03521743,10.4380088 C3.03521743,10.5951061 3.34915502,10.7522035 3.50612381,10.7522035 L16.6915026,11.5376904 C16.6915026,11.5376904 17.1624089,11.5376904 17.1624089,12.0089825 C17.1624089,12.4744748 16.6915026,12.4744748 16.6915026,12.4744748 Z" />
            </svg>
        </button>
    </form>

    @if ($errors->any())
        <div class="mt-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-2xl">
            {{ $errors->first('message') }}
        </div>
    @endif
</div>

<script>
    // Auto scroll to bottom
    const container = document.getElementById('messagesContainer');
    container.scrollTop = container.scrollHeight;
    
    // Auto refresh messages (optional - polling method)
    // setInterval(() => {
    //     location.reload();
    // }, 3000);
</script>
@endsection
