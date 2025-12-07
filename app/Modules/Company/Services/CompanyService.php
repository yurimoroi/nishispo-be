<?php

namespace App\Modules\Company\Services;

use App\Enums\ArticleEnum;
use App\Enums\ArticleStatusEnum;
use App\Enums\RevisedArticleEnum;
use App\Enums\UserContributorStatus;
use App\Http\ApiResponse\ApiResponse;
use App\Http\Resources\ArticleCategoryResource;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\CompanyResource;
use App\Http\Resources\DashboardResource;
use App\Http\Resources\OrganizationResource;
use App\Http\Resources\PaginateResource;
use App\Modules\Company\Models\AlignmentMedia;
use App\Modules\Company\Models\Company;
use App\Modules\Company\Repositories\CategoryRepository;
use App\Modules\Company\Repositories\CompanyRepository;
use App\Modules\Company\Repositories\OrganizationRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Cache;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Support\Facades\Auth;

class CompanyService
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        protected OrganizationRepository $organizationRepository,
        protected CategoryRepository $categoryRepository,
        protected CompanyRepository $companyRepository
    ) {}

    public function dashboard()
    {
        try {
            $dashboard = $this->companyRepository->dashboard();
        } catch (\Throwable $th) {
            return ApiResponse::error($th->getMessage());
        }

        return ApiResponse::success(new DashboardResource($dashboard));
    }

    public function organizations()
    {
        try {
            $organizations = Cache::remember('company.organization', 60, function () {
                return $this->organizationRepository->all();
            });
        } catch (\Throwable $th) {
            return ApiResponse::error($th->getMessage());
        }

        return ApiResponse::success(OrganizationResource::collection($organizations));
    }

    public function publishedAlignmentMedia()
    {
        try {
            $alignmentMedia = AlignmentMedia::published()
            ->orderBy('order' , 'asc')
            ->latest()
            ->get();
        } catch (\Throwable $th) {
            return ApiResponse::error($th->getMessage());
        }

        return ApiResponse::success($alignmentMedia);
    }

    public function categoryWithArticles()
    {
        $limit = request('limit', 3);

        try {
            $categories = $this->categoryRepository->all(
                with: ['articles' => function ($query) use ($limit) {
                    $query->with('user')->limit($limit)->published();
                }]
            );
        } catch (\Throwable $th) {
            return ApiResponse::error($th->getMessage());
        }

        return ApiResponse::success(ArticleCategoryResource::collection($categories));
    }

    public function categoryArticles(string $categoryId)
    {
        $per_page = request('per_page', 10);

        try {
            $category = $this->categoryRepository->find($categoryId);

            if (!$category)  return ApiResponse::error(__("category_not_found"), 404);

            $category_articles = $category->articles()
                ->with(['user'])
                ->published()
                ->paginate($per_page);
        } catch (\Throwable $th) {
            return ApiResponse::error($th->getMessage());
        }

        return ApiResponse::success(
            [
                'articles' => PaginateResource::make($category_articles, ArticleResource::class),
                'category' => $category
            ]
        );
    }

    public function getByName(string $name)
    {
        $limit = request('limit',2);

        try {
            $category = $this->categoryRepository->findOnColumn("name",$name, ['articles' => function($q) use ($limit){
                $q->published()
                ->latest()
                ->limit($limit);
            }]);
            
            if (!$category) return ApiResponse::error(__("category_not_found"), 404);
                
        } catch (\Throwable $th) {
            return ApiResponse::error($th->getMessage());
        }

        return ApiResponse::success( new ArticleCategoryResource($category));
    }

    public function getCompany()
    {
        try {

            $company = Cache::remember('first_company', 60, function () {
                return Company::first();
            });

            return ApiResponse::success($company);
        } catch (\Exception $e) {
            return ApiResponse::error('An error occurred while retrieving the company.', 500, $e->getMessage());
        }
    }

    public function categories()
    {
        $categories = $this->categoryRepository->all();

        return ApiResponse::success(ArticleCategoryResource::collection($categories));
    }

    public function update(array $data)
    {
        try {
            $company = $this->companyRepository->find(id: auth()->user()->company_id);

            if (!$company) throw new Exception("Company" . __("not_found_common"));

            $company->update([...$data]);
            Cache::forget('first_company');
        } catch (\Throwable $th) {
            return ApiResponse::error($th->getMessage());
        }

        return ApiResponse::success();
    }

    public function searchOrg()
    {
        $limit = request('limit', 20);

        try {

            $org = $this->organizationRepository->all(
                allowedFilters: [
                    AllowedFilter::partial('keyword', 'name')
                ],
                limit: $limit,
            );
        } catch (\Throwable $th) {
            return ApiResponse::error($th->getMessage());
        }

        return ApiResponse::success($org);
    }

    public function updatePostLimit(array $data)
    {
        try {

            $company_id = Auth::user()->company_id;

            $company = $this->companyRepository->update($company_id,$data);

            if(!$company)
            {
                return ApiResponse::error('Failed in getting company');
            }

            return ApiResponse::success($company);

        } catch (\Throwable $th) {
            return ApiResponse::error($th->getMessage());
        }
    }
}
