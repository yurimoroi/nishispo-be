<?php

namespace App\Modules\Admin\Articles\Services;
use App\Modules\Articles\Repositories\TopArticleRepository;
use App\Http\ApiResponse\ApiResponse;
use App\Modules\Articles\Models\TopArticle;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\PaginateResource;
use App\Http\Resources\TopArticleResource;
use Illuminate\Support\Facades\Cache;

class TopArticleService
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        protected TopArticleRepository $topArticleRepository
        )
    {}

    public function createTopArticle(array $data){
        try{
            $topArticle = $this->topArticleRepository->create($data);
        } catch (\Throwable $th) {
            info($th->getTraceAsString());
            return ApiResponse::error($th->getMessage());
        }

        return ApiResponse::success($topArticle);
    }

    public function getTopArticles(){
        try {

            $topArticle = $this->topArticleRepository->top(request('per_page', TopArticle::LIMIT));
        } catch (\Throwable $th) {
            Log::error('Error retrieving top article: ' . $th->getTraceAsString());
            return ApiResponse::error($th->getMessage());
        }

        return ApiResponse::success(PaginateResource::make($topArticle, TopArticleResource::class));
    }

    public function getTopArticle(string $topArticleId){
        try{
            $topArticle = $this->topArticleRepository->find(
                id: $topArticleId,
                with:['article.user'],
            );

            if(! $topArticle) return ApiResponse::error("Top Article not found.");
        } catch (\Throwable $th) {
            Log::error("Error retrieving top article: {$topArticleId} :: " . $th->getMessage(), [
                'trace' => $th->getTraceAsString()
            ]);

            return ApiResponse::error("An unexpected error occurred while retrieving the top article.");
        }

        return ApiResponse::success(new TopArticleResource($topArticle));
    }

    public function updateTopArticle($data, $topArticleId){
        try{
            $validate = $data->validated();
            $updated = $this->topArticleRepository->update($topArticleId, $validate);

            if ($updated) {
                return ApiResponse::success('Top Article updated successfully.');
            }

            return ApiResponse::error('Top Article could not be updated.', 400);

        } catch (\Throwable $th) {
            Log::error("Error update top article with ID {$topArticleId}: " . $th->getMessage(), [
                'exception' => $th
            ]);
            return ApiResponse::error('An error occurred while trying to updating the Top Article.');
        }
    }

    public function removeTopArticle(string $topArticleId){
        try {
            $deleted = $this->topArticleRepository->delete($topArticleId);

            if ($deleted) {
                return ApiResponse::success('Top Article deleted successfully.');
            }

            return ApiResponse::error('Top Article could not be deleted.');
        } catch (\Throwable $th) {
            Log::error("Error deleting top article with ID {$topArticleId}: " . $th->getMessage(), [
                'exception' => $th
            ]);
            return ApiResponse::error('An error occurred while trying to delete the top article.');
        }
    }
}
