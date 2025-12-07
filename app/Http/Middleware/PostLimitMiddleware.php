<?php

namespace App\Http\Middleware;

use App\Http\ApiResponse\ApiResponse;
use App\Modules\Company\Repositories\CompanyRepository;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PostLimitMiddleware
{
    public function __construct(protected CompanyRepository $companyRepository) {}
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // get post limit of company
        $company = $this->companyRepository->find(
            id: $user->company_id,
            columns: ['id', 'post_limit', 'organization_member_post_limit', 'organization_post_limit'],
            withCount: ['articles' => function ($q) use ($user) {
                $q->where('user_id',  $user->id)
                    ->companyPostLimitCount();
            }]
        );

        $postLimit = $company->post_limit ?? 0;
        $userCreatedArticle = $company->articles_count ?? 0; // article count of the current user


        // - If your account has reached its daily article submission limit, 
        // a pop-up (CP-30) will appear stating "Today's default number of posts has been exceeded" 
        // and you will not be able to submit an article.

        if ($userCreatedArticle >= $postLimit) {
            return ApiResponse::error(__("post_limit_exceed"), 422);
        }
        
        // TODO:
        // need article check if organization

        return $next($request);
    }
}
