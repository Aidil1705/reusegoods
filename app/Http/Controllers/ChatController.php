<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\MessageSent;

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
        abort_unless(
            $conversation->seller_id === Auth::id() || $conversation->buyer_id === Auth::id(),
            403
        );
        
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
        abort_unless(
            $conversation->seller_id === Auth::id() || $conversation->buyer_id === Auth::id(),
            403
        );

        $validated = $request->validate([
            'message' => 'required|string|max:5000',
        ]);

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => Auth::id(),
            'message' => $validated['message'],
        ]);

        $message = $conversation->messages()->latest('id')->first();
        if ($message) {
            event(new MessageSent($message));
        }

        $conversation->update(['last_message_at' => now()]);

        // Broadcast the message using Reverb
        MessageSent::dispatch($message, $conversation);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message->load('sender'),
            ]);
        }

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
        $product = !empty($validated['product_id']) ? Product::find($validated['product_id']) : null;

        if ($user->id === $otherUser->id) {
            return response()->json([
                'error' => 'Tidak bisa chat dengan diri sendiri'
            ], 422);
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
            $sellerId = $product?->user_id ?? $otherUser->id;
            $buyerId = $sellerId === $user->id ? $otherUser->id : $user->id;

            $conversation = Conversation::create([
                'seller_id' => $sellerId,
                'buyer_id' => $buyerId,
                'product_id' => $validated['product_id'] ?? null,
            ]);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'conversation_id' => $conversation->id,
                'redirect' => route('chat.show', $conversation)
            ]);
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

    // Polling endpoint: return messages after given id
    public function pollMessages(Request $request, Conversation $conversation)
    {
        abort_unless(
            $conversation->seller_id === Auth::id() || $conversation->buyer_id === Auth::id(),
            403
        );

        $afterId = $request->query('after_id');

        $query = $conversation->messages()->with('sender')->orderBy('id', 'asc');

        if ($afterId) {
            $query->where('id', '>', (int) $afterId);
        }

        $messages = $query->get()->map(function ($m) {
            return [
                'id' => $m->id,
                'sender' => ['id' => $m->sender->id, 'name' => $m->sender->name],
                'message' => $m->message,
                'created_at' => $m->created_at->toDateTimeString(),
                'is_current_user' => $m->sender_id === Auth::id(),
                'is_read' => (bool) $m->is_read,
            ];
        });

        return response()->json(['messages' => $messages]);
    }
}
