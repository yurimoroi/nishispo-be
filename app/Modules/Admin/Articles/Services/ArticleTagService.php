<?php

namespace App\Modules\Admin\Articles\Services;
use App\Http\ApiResponse\ApiResponse;
use Spatie\QueryBuilder\AllowedFilter;
use App\Modules\Articles\Repositories\ArticleTagRepository;
use App\Http\Resources\PaginateResource;
use App\Http\Resources\TagResource;
use App\Modules\Articles\Models\ArticleTag;

class ArticleTagService
{
    /**
     * Create a new class instance.
     */
    public function __construct(protected ArticleTagRepository $articleTagRepository) {}

    public function searchTags()
    {
        $limit = request('limit' , ArticleTag::LIMIT_ITEMS);
        
        try {

            $articles_tag = $this->articleTagRepository->all(
                allowedFilters: [
                    AllowedFilter::partial('keyword', 'name')
                ],
                limit:  $limit,
            );
        } catch (\Throwable $th) {
            return ApiResponse::error($th->getMessage());
        }

        return ApiResponse::success($articles_tag);
    }

    public function createArticleTags(array $data)
    {
        try {
            
            $articleCategory = $this->articleTagRepository->create($data);
        } catch (\Throwable $th) {
            return ApiResponse::error($th->getMessage());
        }

        return ApiResponse::success(new TagResource($articleCategory));
    }
}
