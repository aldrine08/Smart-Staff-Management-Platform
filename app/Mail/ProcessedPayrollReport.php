<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class ProcessedPayrollReport extends Mailable
{
    use Queueable, SerializesModels;

    public $payrolls;

    public function __construct(Collection $payrolls)
    {
        $this->payrolls = $payrolls;
    }

    public function build()
    {
        return $this->subject('Processed Payroll Report')
                    ->view('emails.processed_payroll_report')
                    ->with(['payrolls' => $this->payrolls]);
    }
}
