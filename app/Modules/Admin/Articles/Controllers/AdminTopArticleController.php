<?php

namespace App\Modules\Admin\Articles\Controllers;
use App\Modules\Admin\Articles\Services\TopArticleService;
use App\Http\Requests\TopArticleCreateRequest;
use App\Http\Requests\TopArticleUpdateRequest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminTopArticleController extends Controller
{
    public function __construct(
        protected TopArticleService $topArticleService
    ) {}

    public function index(){
        return $this->topArticleService->getTopArticles();
    }

    public function store(TopArticleCreateRequest $request){    
        $data = $request->validated();
        return $this->topArticleService->createTopArticle($data);
    }

    public function show(string $id)
    {
        return $this->topArticleService->getTopArticle($id);
    }

    public function update(TopArticleUpdateRequest $request, string $id)
    {
        return $this->topArticleService->updateTopArticle($request, $id);
    }

    public function delete(string $id){
        return $this->topArticleService->removeTopArticle($id);
    }
}
