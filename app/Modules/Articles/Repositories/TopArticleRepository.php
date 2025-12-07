<?php

namespace App\Modules\Articles\Repositories;

use App\Modules\Articles\Models\Article;
use App\Modules\Articles\Models\TopArticle;
use App\Repositories\BaseRepository;

class TopArticleRepository extends BaseRepository
{
    /**
     * Create a new class instance.
     */
    public function __construct(TopArticle $model)
    {
        parent::__construct($model);
    }

    public function topArticles()
    {
        $articles = $this->model->whereHas('article', function ($q) {
            $q->published()->withoutTrashed();
        })
            ->published()
            ->with(['article' => function ($q) {
                $q
                    ->has('user')
                    ->with(['user', 'categories'])
                    ->published();
            },])
            ->orderBy('order', 'asc')
            ->limit(Article::TOP_PAGE_TOP_ARTICLE_LIMIT)
            ->get();

        $topArticles = $articles->filter(function ($topArticle, $key) {
            return !is_null($topArticle->article);
        });

        return $topArticles;
    }

    public function top(int $perPage)
    {
        return $this->model
            ->with(['article.user'])
            ->whereHas('article', fn($query) => $query->published())
            ->whereHas('article.user')
            ->orderBy('order', 'asc')
            ->paginate($perPage);
    }
}
