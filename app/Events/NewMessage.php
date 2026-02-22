<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Message;

class NewMessage
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    use InteractsWithSockets, SerializesModels;

    public $message;
    public $chatRoomId;

    public function __construct(Message $message)
    {
        $this->message = $message;
        $this->chatRoomId = $message->chat_room_id;
    }

    public function broadcastOn()
    {
        return new PresenceChannel('chat-room.'.$this->chatRoomId);
    }
    
}
