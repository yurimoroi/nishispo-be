<?php

namespace App\Repositories;

use App\Modules\Admin\Information\Models\Informations;

class InformationRepository extends BaseRepository
{
    /**
     * Create a new class instance.
     */
    public function __construct(Informations $model)
    {
        parent::__construct($model);
    }
}
