<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('chat.{conversation}', function ($user, $conversationId) {
    $conversation = \App\Models\Conversation::find($conversationId);
    if (! $conversation) return false;
    return $user->id === $conversation->seller_id || $user->id === $conversation->buyer_id;
});
