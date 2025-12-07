<?php

namespace App\Notifications;

use App\Modules\Articles\Models\Article;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RemandArticleNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Article $article) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        // $remandUrl = config('app.frontend_url') . "/contributor/articles/{$this->article->id}/edit";

        return (new MailMessage)
            ->from('miyaspo_jimukyoku@spocomi.co.jp', config('app.name'))
            ->subject('記事差し戻しのお知らせ')
            ->markdown('emails.remand_article');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
