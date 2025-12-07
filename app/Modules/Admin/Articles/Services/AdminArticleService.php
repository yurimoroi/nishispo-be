<?php

namespace App\Modules\Admin\Articles\Services;

use App\Enums\ArticleStatusEnum;
use App\Events\ArticleApproved;
use App\Events\ArticleDeleteApproved;
use App\Events\ArticleRemandedEvent;
use App\Events\EditRequestApproved;
use App\Http\ApiResponse\ApiResponse;
use App\Http\Resources\ArticleResource;
use App\Modules\Articles\Repositories\ArticleRepository;
use App\Repositories\MediaRepository;
use Exception;
use Spatie\QueryBuilder\AllowedFilter;
use App\Filters\ArticleCategoryFilter;
use App\Filters\ArticleDatePublishFilter;
use App\Filters\ArticleSearchKeywordFilter;
use App\Filters\ArticleStatusFilter;
use App\Http\Resources\PaginateResource;
use App\Modules\Articles\Models\Article;

class AdminArticleService
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        protected ArticleRepository $articleRepository,
        protected MediaRepository $mediaRepository
    ) {}

    public function article(string $id)
    {
        try {
            $article = $this->articleRepository->find(id: $id);
        } catch (\Throwable $th) {
            return ApiResponse::error($th->getMessage());
        }

        return ApiResponse::success($article);
    }

    public function remand(string $id, array $data)
    {
        try {
            $article = $this->articleRepository->find(id: $id, with: ['user']);

            if (!$article) return ApiResponse::error(__("not_found_common"));

            // - The article status will be changed to Remand. 
            // - An email will be sent to the article poster's email address indicating that the article has been remanded.

            $data = [...$data, 'user_id' => $article->user_id];

            $this->articleRepository->remand($article, $data);

            $this->articleRepository->update(id: $id, data: ['status' => ArticleStatusEnum::remand()->value]);

            // this event will triger an email notification
            event(new ArticleRemandedEvent($article, $article->user));
        } catch (\Throwable $th) {
            return ApiResponse::error($th->getMessage());
        }

        return ApiResponse::success();
    }

    public function approved(string $id)
    {
        try {
            $article = $this->articleRepository->find(id: $id, with: ['user']);

            if (!$article) throw new Exception("Article " . __("not_found_common"));

            $this->articleRepository->approved($article);

            event(new ArticleApproved($article));
        } catch (\Throwable $th) {
            return ApiResponse::error($th->getMessage());
        }

        return ApiResponse::success();
    }

    public function editApproved(string $id)
    {
        try {
            $article = $this->articleRepository->find(id: $id, with: ['user','revised']);
            
            if (!$article) throw new Exception("Article " . __("not_found_common"));

            if (!$article->revised) throw new Exception("Article has no revisions." );

            $this->articleRepository->editApproved($article , $article->revised);

            $this->mediaRepository->revisedArticleMedia($article,$article->revised);

            event(new EditRequestApproved($article));
        } catch (\Throwable $th) {
            return ApiResponse::error($th->getMessage());
        }

        return ApiResponse::success();
    }

    public function deleteApproved(string $id)
    {
        
        try {
            $article = $this->articleRepository->find(id: $id, with: ['user','revised']);
            
            if (!$article) throw new Exception("Article " . __("not_found_common"));

            $this->articleRepository->deleteApproved($article);

            event(new ArticleDeleteApproved($article));
        } catch (\Throwable $th) {
            return ApiResponse::error($th->getMessage());
        }

        return ApiResponse::success();
    }

    public function searchToTop()
    {
        try {

            $articles = $this->articleRepository->all(
                with: ['user', 'categories', 'topArticle','organization'],
                scopes: ['published'],
                allowedFilters: [
                    AllowedFilter::custom('status', new ArticleStatusFilter),
                    AllowedFilter::custom('search', new ArticleSearchKeywordFilter),
                    AllowedFilter::custom('categories', new ArticleCategoryFilter),
                    AllowedFilter::custom('dates', new ArticleDatePublishFilter)
                ],
                orderBy:'created_at',
                sortBy:'desc',
                paginate: true,
                perPage: request('per_page', Article::LIMIT_PER_PAGE)
            );
        } catch (\Throwable $th) {
            return ApiResponse::error($th->getMessage());
        }

        return ApiResponse::success(PaginateResource::make($articles, ArticleResource::class));
    }
}
