<?php

namespace App\Modules\Admin\Articles\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Articles\Services\ArticleService;
use App\Http\Requests\AdminArticleCreateRequest;
use App\Http\Requests\AdminArticleSelectionPopupRequest;
use App\Http\Requests\RemandArticleRequest;
use App\Modules\Admin\Articles\Services\AdminArticleService;

class AdminArticleController extends Controller
{
    public function __construct(
        protected ArticleService $articleService,
        protected AdminArticleService $adminArticleService,
    ) {}

    public function index(AdminArticleSelectionPopupRequest $request)
    {
        return $this->articleService->search();
    }

    public function store(AdminArticleCreateRequest $request)
    {
        return $this->articleService->create($request->validated());
    }

    public function show(string $id)
    {
        return $this->articleService->getArticle($id);
    }

    public function delete(string $id)
    {
        return $this->articleService->delete($id);
    }

    public function getStatus()
    {
        return $this->articleService->status();
    }

    public function articleToRemand(string $id)
    {
       return $this->adminArticleService->article($id);
    }

    public function remand(string $id,RemandArticleRequest $request)
    {
        $data = $request->validated();
        return $this->adminArticleService->remand($id,$data);
    }

    public function approved(string $id)
    {
        return $this->adminArticleService->approved($id);
    }

    public function editApproved(string $id)
    {
        return $this->adminArticleService->editApproved($id);
    }

    public function deleteApproved(string $id)
    {
        return $this->adminArticleService->deleteApproved($id);
    }

    public function searchToTop()
    {
        return $this->adminArticleService->searchToTop();
    }
}
