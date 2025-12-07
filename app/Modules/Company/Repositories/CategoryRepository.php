<?php

namespace App\Modules\Company\Repositories;

use App\Modules\Articles\Models\ArticleCategory;
use App\Repositories\BaseRepository;

class CategoryRepository extends BaseRepository
{
    /**
     * Create a new class instance.
     */
    public function __construct(ArticleCategory $model)
    {
        parent::__construct($model);
    }
}
