<?php

namespace App\Http\Controllers\V1\Invoke;

use App\Enums\ArticleEnum;
use App\Http\ApiResponse\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleRankingResource;
use App\Modules\Articles\Models\Article;
use App\Modules\Articles\Models\ArticleViewingRank;
use Illuminate\Http\Request;

class ArticleRankingController extends Controller
{
   
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        try {
            $rankings = ArticleViewingRank::whereHas('article' , function($q) {
                $q->published()->latest();
            })
            ->with(['article' => function ($q) {
                return $q->with(['user'])->published();
            }])
            ->orderBy('view_count', 'desc')
            ->limit(Article::RANK_ARTICLE_LIMIT)
            ->get();
        } catch (\Throwable $th) {
            return ApiResponse::error($th->getMessage());
        }

        return ApiResponse::success(ArticleRankingResource::collection($rankings));
    }
}
