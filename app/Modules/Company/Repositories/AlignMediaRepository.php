<?php

namespace App\Modules\Company\Repositories;

use App\Modules\Company\Models\AlignmentMedia;
use App\Repositories\BaseRepository;

class AlignMediaRepository extends BaseRepository
{
    /**
     * Create a new class instance.
     */
    public function __construct(AlignmentMedia $model)
    {
        parent::__construct($model);
    }
}
