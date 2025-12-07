<?php

namespace App\Notifications;

use App\Modules\User\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordResetNotification extends ResetPassword implements ShouldQueue
{
    use Queueable;

    public $url;

    public function __construct($url)
    {
        $this->url = $url;
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('パスワード再発行のお知らせ')
            ->from('miyaspo_jimukyoku@spocomi.co.jp',config('app.name'))
            ->markdown('emails.reset_password',['url' => $this->url]);
    }
}
