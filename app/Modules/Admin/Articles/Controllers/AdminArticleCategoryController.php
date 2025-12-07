<?php

namespace App\Modules\Admin\Articles\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ArticleCreateRequest;
use App\Modules\Articles\Services\ArticleService;
use App\Modules\Admin\Articles\Services\ArticleCategoryService;
use App\Http\Requests\ArticleCategoryCreateRequest;
use App\Http\Requests\ArticleCategoryUpdateRequest;

class AdminArticleCategoryController extends Controller
{
    public function __construct(
        protected ArticleCategoryService $articleCategoryService,
        protected ArticleService $articleService
    ) {}

    public function storeArticleCategory(ArticleCategoryCreateRequest $request){
        $data = $request->validated();
        return $this->articleCategoryService->createArticleCategory($data);
    }

    public function index()
    {
        return $this->articleCategoryService->getArticleCategories();
    }

    public function delete(string $id)
    {
        return $this->articleCategoryService->removeArticleCategory($id);
    }

    public function show(string $id)
    {
        return $this->articleCategoryService->getArticleCategory($id);
    }

    public function update(ArticleCategoryUpdateRequest $request, string $id)
    {
        return $this->articleCategoryService->updateArticleCategory($request, $id);
    }

    public function store(ArticleCreateRequest $request)
    {
        return $this->articleService->create($request->validated());
    }

    public function storeArticle(ArticleCreateRequest $request)
    {
        return $this->articleService->create($request->validated());
    }
}
