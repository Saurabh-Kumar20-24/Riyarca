<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class OnboardingMail extends Mailable
{
    use Queueable, SerializesModels;

    public $employee;
    public $onboardingUrl;

    public function __construct(User $employee, string $onboardingUrl)
    {
        $this->employee      = $employee;
        $this->onboardingUrl = $onboardingUrl;
    }

    public function build()
    {
        return $this->subject('Welcome to SeoMagics – Action Required')
                    ->view('emails.onboarding');
    }
}