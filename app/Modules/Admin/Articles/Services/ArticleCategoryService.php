<?php

namespace App\Modules\Admin\Articles\Services;

use App\Enums\ArticleEnum;
use App\Modules\Articles\Repositories\ArticleCategoryRepository;
use App\Http\ApiResponse\ApiResponse;
use App\Http\Resources\ArticleCategoryResource;
use App\Http\Resources\PaginateResource;
use App\Modules\Articles\Models\ArticleCategory;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ArticleCategoryService
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        protected ArticleCategoryRepository $articleCategoryRepository
    ) {}

    public function getArticleCategories()
    {
        try {
            $article_category = $this->articleCategoryRepository->all(
                orderBy: 'order',
                sortBy: 'asc',
                scopes: ['published'],
                paginate: true,
                perPage: ArticleCategory::ARTICLE_CATEGORY_PER_PAGE
            );
        } catch (\Throwable $th) {
            Log::error('Error retrieving article category: ' . $th->getTraceAsString());
            return ApiResponse::error($th->getMessage());
        }

        return ApiResponse::success(PaginateResource::make($article_category, ArticleCategoryResource::class));
    }

    public function getArticleCategory(string $articleCategoryId)
    {
        try {
            $articleCategory = Cache::remember("articleCategory.{$articleCategoryId}", 30, function () use ($articleCategoryId) {
                return $this->articleCategoryRepository->find(
                    $articleCategoryId,
                    ['company'],
                    ['*'],
                    [],
                    ['published']
                );
            });

            if (!$articleCategory) {
                return ApiResponse::error(__("Article category not found."), 404);
            }
        } catch (\Throwable $th) {
            Log::error("Error retrieving article category: {$articleCategoryId} :: " . $th->getMessage(), [
                'trace' => $th->getTraceAsString()
            ]);

            return ApiResponse::error("An unexpected error occurred while retrieving the article category.");
        }

        return ApiResponse::success(new ArticleCategoryResource($articleCategory));
    }

    public function updateArticleCategory($data, string $articleCategoryId)
    {
        try {

            $validate = $data->all();

            $updated = $this->articleCategoryRepository->update($articleCategoryId, $validate);

            if ($updated) {
                cache()->forget("articleCategory.{$updated->id}");

                return ApiResponse::success('Article category updated successfully.');
            }

            return ApiResponse::error('Article category could not be updated.', 400);
        } catch (\Throwable $th) {
            Log::error("Error update article category with ID {$articleCategoryId}: " . $th->getMessage(), [
                'exception' => $th
            ]);
            return ApiResponse::error('An error occurred while trying to updating the article category.');
        }
    }

    public function removeArticleCategory(string $articleCategoryId)
    {
        try {
            $deleted = $this->articleCategoryRepository->delete($articleCategoryId);

            if ($deleted) {
                return ApiResponse::success('Article category deleted successfully.');
            }

            return ApiResponse::error('Article category could not be deleted.');
        } catch (\Throwable $th) {
            Log::error("Error deleting article category with ID {$articleCategoryId}: " . $th->getMessage(), [
                'exception' => $th
            ]);
            return ApiResponse::error('An error occurred while trying to delete the article category.');
        }
    }

    public function createArticleCategory(array $data)
    {
        try {

            $articleCategory = $this->articleCategoryRepository->create($data);
        } catch (\Throwable $th) {
            info($th->getTraceAsString());
            return ApiResponse::error($th->getMessage());
        }

        return ApiResponse::success(new ArticleCategoryResource($articleCategory));
    }
}
