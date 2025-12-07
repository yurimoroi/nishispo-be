<?php

namespace App\Modules\Articles\Repositories;

use App\Modules\Articles\Models\ArticleCategory;
use App\Repositories\BaseRepository;
use Carbon\Carbon;


class ArticleCategoryRepository extends BaseRepository
{
    /**
     * Create a new class instance.
     */
    public function __construct(ArticleCategory $model)
    {
        parent::__construct($model);
    }
}
