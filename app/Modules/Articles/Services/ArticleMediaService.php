<?php

namespace App\Modules\Articles\Services;

use App\Http\ApiResponse\ApiResponse;
use App\Modules\Articles\Repositories\ArticleRepository;
use App\Repositories\MediaRepository;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;

class ArticleMediaService
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        protected MediaRepository $mediaRepository,
        protected ArticleRepository $articleRepository
    ) {}

    public function removeMedia($articleId, $mediaId)
    {

        try {
            $article = $this->articleRepository->find(id: $articleId);

            if (!$article) throw new Exception("Article " . __("not_found_common"));

            Gate::authorize('removeMedia', $article);

            $article->deleteMedia($mediaId);
            
            Cache::forget("article_{$article->id}_media_urls");
            Cache::forget("article.{$article->id}");
            Cache::forget("top-page");

        } catch (\Throwable $th) {
            return ApiResponse::error($th->getMessage());
        }

        return ApiResponse::success();
    }
}
