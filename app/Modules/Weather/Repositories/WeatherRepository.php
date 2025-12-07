<?php

namespace App\Modules\Weather\Repositories;

use App\Modules\Weather\Models\Weather;
use App\Repositories\BaseRepository;

class WeatherRepository extends BaseRepository
{
   /**
     * Create a new class instance.
     */
    public function __construct(Weather $model)
    {
        parent::__construct($model);
    }
}
