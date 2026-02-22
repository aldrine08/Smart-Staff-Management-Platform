<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AttendanceExportMail extends Mailable
{
    use Queueable, SerializesModels;

    public $filePath;
    

    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }

    public function build()
    {
        return $this->subject('Attendance Export')
                    ->view('emails.attendance_export')
                    ->attach($this->filePath, [
                        'as' => 'attendance.xlsx',
                        'mime' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    ]);
    }
}
