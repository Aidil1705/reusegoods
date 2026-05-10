<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    // List all conversations
    public function index()
    {
        $user = Auth::user();
        
        $conversations = Conversation::where(function ($query) use ($user) {
            $query->where('seller_id', $user->id)
                  ->orWhere('buyer_id', $user->id);
        })
        ->with(['seller', 'buyer', 'product', 'latestMessage'])
        ->orderBy('last_message_at', 'desc')
        ->paginate(10);

        return view('chat.conversations', compact('conversations'));
    }

    // Show single conversation
    public function show(Conversation $conversation)
    {
        $this->authorize('view', $conversation);
        
        $messages = $conversation->messages()->paginate(20, ['*'], 'page', 1);
        
        // Mark unread messages as read
        $conversation->messages()
            ->where('sender_id', '!=', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        return view('chat.show', compact('conversation', 'messages'));
    }

    // Send message
    public function sendMessage(Request $request, Conversation $conversation)
    {
        $this->authorize('view', $conversation);

        $validated = $request->validate([
            'message' => 'required|string|max:5000',
        ]);

        Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => Auth::id(),
            'message' => $validated['message'],
        ]);

        $conversation->update(['last_message_at' => now()]);

        return back()->with('success', 'Pesan terkirim');
    }

    // Create or get conversation
    public function startChat(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'product_id' => 'nullable|exists:products,id',
        ]);

        $user = Auth::user();
        $otherUser = User::findOrFail($validated['user_id']);

        if ($user->id === $otherUser->id) {
            return back()->with('error', 'Tidak bisa chat dengan diri sendiri');
        }

        // Find existing conversation or create new one
        $conversation = Conversation::where(function ($query) use ($user, $otherUser) {
            $query->where([
                ['seller_id', $user->id],
                ['buyer_id', $otherUser->id],
            ])->orWhere([
                ['seller_id', $otherUser->id],
                ['buyer_id', $user->id],
            ]);
        })->first();

        if (!$conversation) {
            // Determine who is seller and who is buyer
            $sellerRole = $user->setRoles()->first()->roles()->where('role_name', 'penjual')->exists();
            $buyerRole = $otherUser->setRoles()->first()->roles()->where('role_name', 'pembeli')->exists();

            if ($sellerRole && $buyerRole) {
                $conversation = Conversation::create([
                    'seller_id' => $user->id,
                    'buyer_id' => $otherUser->id,
                    'product_id' => $validated['product_id'] ?? null,
                ]);
            } else {
                $conversation = Conversation::create([
                    'seller_id' => $otherUser->id,
                    'buyer_id' => $user->id,
                    'product_id' => $validated['product_id'] ?? null,
                ]);
            }
        }

        return redirect()->route('chat.show', $conversation);
    }

    // Get unread count
    public function getUnreadCount()
    {
        $user = Auth::user();
        
        $count = Conversation::where(function ($query) use ($user) {
            $query->where('seller_id', $user->id)
                  ->orWhere('buyer_id', $user->id);
        })
        ->withCount(['messages' => function ($query) use ($user) {
            $query->where('sender_id', '!=', $user->id)
                  ->where('is_read', false);
        }])
        ->get()
        ->sum('messages_count');

        return response()->json(['unread_count' => $count]);
    }
}
