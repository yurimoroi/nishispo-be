<?php

namespace App\Listeners;

use App\Events\ArticleRemandedEvent;
use App\Notifications\RemandArticleNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class EmailRemandedArticleOwner
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ArticleRemandedEvent $event): void
    {
        $delay = now()->addSeconds(5);
        $event->user->notify((new RemandArticleNotification($event->article))->delay($delay));
    }
}
