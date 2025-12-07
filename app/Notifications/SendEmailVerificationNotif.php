<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Bus\Queueable;

class SendEmailVerificationNotif extends VerifyEmail implements ShouldQueue
{
    use Queueable;
}
