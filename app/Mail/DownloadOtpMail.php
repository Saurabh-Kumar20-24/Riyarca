<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DownloadOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $otp;
    public string $managerName;

    public function __construct(string $otp, string $managerName)
    {
        $this->otp = $otp;
        $this->managerName = $managerName;
    }

    public function build(): self
    {
        return $this->subject('Your Download OTP - Valid for 5 Minutes')
                    ->view('emails.download_otp');
    }
}