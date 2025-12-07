<?php

namespace App\Http\Controllers\V1\Invoke;

use App\Http\ApiResponse\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleCategoryResource;
use App\Modules\Company\Repositories\CategoryRepository;
use Illuminate\Http\Request;

class TopNavCategoryController extends Controller
{
    public function __construct(
        protected CategoryRepository $categoryRepository
    ) {}
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        try {
            $category = $this->categoryRepository->all(
                where:[
                    'show_head_flg' => 1
                ],
                orderBy:'order',
                sortBy:'asc'
            );
        } catch (\Throwable $th) {
            return ApiResponse::error($th->getMessage());
        }

        return ApiResponse::success(ArticleCategoryResource::collection($category));
    }
}
