<?php

namespace App\Http\Controllers\V1\Invoke;

use App\Enums\ArticleEnum;
use App\Http\ApiResponse\ApiResponse;
use App\Http\Controllers\Controller;
use App\Modules\Articles\Services\ArticleService;
use App\Repositories\ArticleRepository;
use Illuminate\Http\Request;

class SearchArticleController extends Controller
{

    public function __construct(protected ArticleService $articleService) {}
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        return $this->articleService->articleSearch();
    }
}
