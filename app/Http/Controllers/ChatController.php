<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChatRoom;
use App\Models\Message;
use App\Models\User;
use App\Events\NewMessage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\MentionNotification;

class ChatController extends Controller
{
    public function index($roomId)
    {
        $user = auth()->user();

        // Fetch chat room by id with messages and mentions
        $room = ChatRoom::with(['messages.user','messages.mentions'])->findOrFail($roomId);

        return view('chat.index', compact('room','user'));
    }

    public function store(Request $request, $roomId)
    {
        $request->validate(['content' => 'required|string']);

    $room = ChatRoom::findOrFail($roomId);

    
        

        $message = Message::create([
            'chat_room_id' => $roomId,
            'user_id' => Auth::id(),
            'content' => $request->content,
        ]);

        // Detect mentions @username
        preg_match_all('/@(\w+)/', $request->content, $matches);
        if(!empty($matches[1])){
            foreach($matches[1] as $username){
                $mentionedUser = User::where('name', $username)->first();
                if($mentionedUser){
                    // Save mention in pivot table
                    $message->mentions()->attach($mentionedUser->id);

                    // Send email notification
                    // Pass both $message and $mentionedUser to the Mailable
                    Mail::to($mentionedUser->email)
                        ->send(new MentionNotification($message, $mentionedUser));
                }
            }
        }

        broadcast(new NewMessage($message))->toOthers();

        return response()->json([
    'success' => true,
    'message' => [
        'id' => $message->id,
        'content' => $message->content,
        'user' => [
            'name' => $message->user->name,
            'avatar' => $message->user->avatar,
        ],
        'created_at' => $message->created_at->diffForHumans(),
    ]
]);

    }

    public function show($id)
    {
        $chat = ChatRoom::find($id);

        if (!$chat) {
            abort(404, "Chat not found");
        }

        return view('chat.show', compact('chat'));
    }
}
