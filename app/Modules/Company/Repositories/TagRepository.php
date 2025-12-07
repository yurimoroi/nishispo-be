<?php

namespace App\Modules\Company\Repositories;

use App\Modules\Articles\Models\ArticleTag;
use App\Repositories\BaseRepository;

class TagRepository extends BaseRepository
{
    /**
     * Create a new class instance.
     */
    public function __construct(ArticleTag $model)
    {
        parent::__construct($model);
    }       
}
