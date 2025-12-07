<?php

namespace App\Modules\User\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ArticleCreateRequest;
use App\Http\Requests\ContributorApplyRequest;
use App\Http\Requests\FilterRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UserContributorUpdateRequest;
use App\Http\Requests\UserEditRequest;
use App\Modules\Articles\Services\ArticleService;
use App\Modules\User\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(
        protected UserService $userService,
        protected ArticleService $articleService
    )
    {}
    
    public function me(Request $request)
    {
        return $this->userService->me();
    }

    public function articles(FilterRequest $request)
    {
        return $this->userService->articles();
    }

    public function count()
    {
        return $this->articleService->countUserArticles();
    }

    public function article(string $id)
    {
        return $this->articleService->getArticle($id);
    }

    public function contributorApply(ContributorApplyRequest $request)
    {
        return $this->userService->contributorApply($request->validated());
    }

    public function affiliateWithdraw(string $id)
    {
        return $this->userService->affiliateWithdraw($id);
    }

    public function update(UserEditRequest $request)
    {
        $data = $request->validated();
        return $this->userService->update($data);
    }
}
