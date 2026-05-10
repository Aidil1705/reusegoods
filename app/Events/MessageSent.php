<?php

namespace App\Events;

<<<<<<< HEAD
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
=======
use App\Models\Message;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
>>>>>>> ba632d81e36ff1a3d09e2e21b0b8364b25ca53b8
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

<<<<<<< HEAD
    public $message;
    public $conversation;
=======
    public Message $message;
>>>>>>> ba632d81e36ff1a3d09e2e21b0b8364b25ca53b8

    /**
     * Create a new event instance.
     */
<<<<<<< HEAD
    public function __construct(Message $message, Conversation $conversation)
    {
        $this->message = $message;
        $this->conversation = $conversation;
=======
    public function __construct(Message $message)
    {
        $this->message = $message->load('sender');
>>>>>>> ba632d81e36ff1a3d09e2e21b0b8364b25ca53b8
    }

    /**
     * Get the channels the event should broadcast on.
<<<<<<< HEAD
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('chat.' . $this->conversation->id),
        ];
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->message->id,
            'message' => $this->message->message,
            'sender_id' => $this->message->sender_id,
            'sender_name' => $this->message->sender->name,
            'created_at' => $this->message->created_at->toDateTimeString(),
            'file_path' => $this->message->file_path,
        ];
    }

    /**
     * Get the name of the event to broadcast as.
     */
    public function broadcastAs(): string
    {
        return 'message.sent';
    }
=======
     */
    public function broadcastOn()
    {
        return new PrivateChannel('chat.' . $this->message->conversation_id);
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->message->id,
            'conversation_id' => $this->message->conversation_id,
            'sender' => [
                'id' => $this->message->sender->id,
                'name' => $this->message->sender->name,
            ],
            'message' => $this->message->message,
            'created_at' => $this->message->created_at->toDateTimeString(),
        ];
    }
>>>>>>> ba632d81e36ff1a3d09e2e21b0b8364b25ca53b8
}
