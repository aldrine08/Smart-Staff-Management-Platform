<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Message;
use App\Models\User;

class MentionNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $messageModel;
    public $mentionedUser;

    public function __construct(Message $message, User $mentionedUser)
    {
        $this->messageModel = $message;
        $this->mentionedUser = $mentionedUser;
    }

    public function build()
    {
        return $this->subject("You were mentioned in a chat")
                    ->view('emails.mention')
                    ->with([
                        'message' => $this->messageModel,
                        'mentionedUser' => $this->mentionedUser,
                    ]);
    }
}
