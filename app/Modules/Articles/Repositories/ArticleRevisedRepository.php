<?php

namespace App\Modules\Articles\Repositories;

use App\Modules\Articles\Models\RevisedArticle;
use App\Repositories\BaseRepository;
use Illuminate\Support\Str;

class ArticleRevisedRepository extends BaseRepository
{
    /**
     * Create a new class instance.
     */
    public function __construct(RevisedArticle $model)
    {
        parent::__construct($model);
    }

    public function revisedArticleCategories(array $categories, RevisedArticle $article)
    {
        $syncCategories = array_map(fn($category) => [
            "id" => Str::ulid(),
            "revised_article_id" => $article->id,
            "article_category_id" => $category,
        ], $categories);

        $article->revisedArticleCategories()->sync($syncCategories);
    }

    public function revisedArticleTags(array $tags, RevisedArticle $article)
    {
        $syncTags = array_map(fn($tag) => [
            "id" =>  Str::ulid(),
            "revised_article_id" => $article->id,
            "article_tag_id" => $tag
        ], $tags);

        $article->revisedArticleTags()->sync($syncTags);
    }
}
