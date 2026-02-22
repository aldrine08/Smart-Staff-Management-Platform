<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ClockInMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $attendance;
    public $status;

    /**
     * Create a new message instance.
     */
     public function build()
{
    return $this
        ->subject('Staff Clock In Notification')
        ->view('emails.clockin'); // your Blade will use $status
}

    public function __construct($user, $attendance, $status)
{
    $this->user = $user;
    $this->attendance = $attendance;
    $this->status = $status;
}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Clock In Mail',
        );
    }

    /**
     * Get the message content definition.
     */
     public function content(): Content
{
    return new Content(
        view: 'emails.clockin', // must match your Blade file
    );
}

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
