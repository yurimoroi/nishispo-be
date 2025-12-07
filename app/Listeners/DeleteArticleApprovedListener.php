<?php

namespace App\Listeners;

use App\Events\ArticleDeleteApproved;
use App\Notifications\DeleteApprovedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class DeleteArticleApprovedListener
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
    public function handle(ArticleDeleteApproved $event): void
    {
        $delay = now()->addSeconds(5);
        $event->article->user->notify((new DeleteApprovedNotification($event->article))->delay($delay));
    }
}
