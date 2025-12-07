<?php

namespace App\Modules\Articles\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ArticleCreateRequest;
use App\Http\Requests\ArticleSaveRequest;
use App\Http\Requests\ArticleUpdateRequest;
use App\Modules\Articles\Services\ArticleMediaService;
use App\Modules\Articles\Services\ArticleService;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function __construct(
        protected ArticleService $articleService,
        protected ArticleMediaService $articleMediaService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->articleService->topPage();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ArticleCreateRequest $request)
    {
        $data = $request->validated();
        return $this->articleService->create($data);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return $this->articleService->article($id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(string $id, ArticleUpdateRequest $request)
    {
        $data = $request->validated();
        return $this->articleService->update($data, $id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return $this->articleService->delete($id);
    }

    public function save(string $id, ArticleSaveRequest $request)
    {
        // for article that are already published, articles will be saved on revised_article and the article status will be
        // ArticleStatusEnum::requestEdit() and details will be saved on revised_article.
        // RevisedArticleEnum::correctionRequest() will be used on request_type.
        // return auth()->user();
        $data = $request->validated();
        return $this->articleService->save($id, $data);
    }

    public function deleteRequest(string $id, Request $request)
    {
        $request->validate(['comment' =>'required']);
        
        return $this->articleService->deleteRequest($id);
    }

    public function withdrawalOfRequest(string $id)
    {
        return $this->articleService->withdrawalOfRequest($id);
    }

    public function removeArticleMedia($articleId, $mediaId)
    {
        return $this->articleMediaService->removeMedia($articleId, $mediaId);
    }

    public function getStatus()
    {
        return $this->articleService->status();
    }
}
