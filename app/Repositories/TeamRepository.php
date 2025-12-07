<?php

namespace App\Repositories;

use App\Modules\Event\Models\Team;

class TeamRepository extends BaseRepository
{
   /**
     * Create a new class instance.
     */
    public function __construct(Team $model)
    {
        parent::__construct($model);
    }
}
