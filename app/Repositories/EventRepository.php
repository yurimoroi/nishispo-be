<?php

namespace App\Repositories;

use App\Modules\Event\Models\Event;

class EventRepository extends BaseRepository
{
    /**
     * Create a new class instance.
     */
    public function __construct(Event $model)
    {
        parent::__construct($model);
    }
}
