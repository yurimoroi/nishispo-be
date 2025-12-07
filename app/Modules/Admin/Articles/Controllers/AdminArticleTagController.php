<?php

namespace App\Modules\Admin\Articles\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ArticleTagCreateRequest;
use App\Modules\Admin\Articles\Services\ArticleTagService;
use Illuminate\Http\Request;

class AdminArticleTagController extends Controller
{
    public function __construct(protected ArticleTagService $articleTagService)
    {}

    public function index()
    {
        return $this->articleTagService->searchTags();
    }

    public function store(ArticleTagCreateRequest $request)
    {
        $data = $request->validated();
        return $this->articleTagService->createArticleTags($data);
    }
}
