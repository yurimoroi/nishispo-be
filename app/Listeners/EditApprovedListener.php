<?php

namespace App\Listeners;

use App\Events\EditRequestApproved;
use App\Notifications\EditApprovedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class EditApprovedListener
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
    public function handle(EditRequestApproved $event): void
    {
        $delay = now()->addSeconds(5);
        $user = $event->article->user;

        $user->notify((new EditApprovedNotification($event->article))->delay($delay));
    }
}
