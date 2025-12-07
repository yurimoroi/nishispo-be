<?php

namespace App\Listeners;

use App\Events\ArticleApproved;
use App\Notifications\ApprovedArticleNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ArticleApprovedListener
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
    public function handle(ArticleApproved $event): void
    {
       $delay = now()->addSeconds(5);
       $user = $event->article->user;
       $user->notify((new ApprovedArticleNotification($event->article))->delay($delay));
    }
}
