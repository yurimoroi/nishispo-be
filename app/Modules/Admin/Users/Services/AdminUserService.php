<?php

namespace App\Modules\Admin\Users\Services;

use App\Enums\ArticleStatusEnum;
use App\Enums\UserContributorStatus;
use App\Filters\ArticleRelationFilter;
use App\Filters\ArticleRoleFilter;
use App\Filters\ArticleStatusFilter;
use App\Filters\UserContributorFilter;
use App\Filters\UserRoleFilter;
use App\Filters\UserSearchFilter;
use App\Http\ApiResponse\ApiResponse;
use App\Http\Resources\PaginateResource;
use App\Http\Resources\SimpleUserList;
use App\Http\Resources\UserResource;
use App\Modules\Articles\Models\Article;
use App\Modules\Articles\Repositories\ArticleCategoryRepository;
use App\Modules\Articles\Repositories\ArticleRepository;
use App\Modules\Company\Repositories\OrganizationRepository;
use App\Modules\User\Models\User;
use App\Repositories\UserRepository;
use Exception;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Support\Facades\Log;

class AdminUserService
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        protected UserRepository $userRepository,
        protected ArticleRepository $articleRepository,
        protected ArticleCategoryRepository $articleCategoryRepository,
        protected OrganizationRepository $organizationRepository
    ) {}

    public function index($isPaginate = true)
    {
        try {

            $article_status = request('filter.article_status');
        
            $users = $this->userRepository->all(
                with: [
                    'organizations',
                    'teams',
                    'organizationUser',
                    'articles' => function ($query) use ($article_status) {
                        if ($article_status) {
                            $query->where('status', ArticleStatusEnum::from($article_status)->value);
                        }
                    }
                ],
                allowedFilters: [
                    AllowedFilter::custom('role', new UserRoleFilter()),
                    AllowedFilter::exact('organization', 'organizations.id'),
                    AllowedFilter::custom('article_status', new ArticleRelationFilter()),
                    AllowedFilter::custom('status', new UserContributorFilter()),
                    AllowedFilter::custom('search', new UserSearchFilter()),
                ],
                prioritize: request('prioritize', []),
                orderBy:'created_at',
                sortBy:'desc',
                paginate: $isPaginate,
                limit: request('limit', 10),
                perPage: request('per_page', User::LIMIT_PER_PAGE),
                where: [['id', '!=', auth()->id()]]
            );

        } catch (\Throwable $th) {
            return ApiResponse::error($th->getMessage());
        }

        return $isPaginate
            ? ApiResponse::success(PaginateResource::make($users, UserResource::class))
            : ApiResponse::success(SimpleUserList::collection($users));
    }

    public function removeUser(string $userId)
    {
        try {
            $deleted = $this->userRepository->delete($userId);

            if ($deleted) {
                return ApiResponse::success('User deleted successfully.');
            }

            return ApiResponse::error('User could not be deleted.');
        } catch (\Throwable $th) {
            Log::error("Error deleting user with ID {$userId}: " . $th->getMessage(), [
                'exception' => $th
            ]);
            return ApiResponse::error('An error occurred while trying to delete the user.');
        }
    }

    public function show(string $id)
    {
        try {
            $user = $this->userRepository->find(
                id: $id,
                with: ['affiliate', 'company'],
                withCount: ['articles as published_count' => function ($q) {
                    $q->where('status', ArticleStatusEnum::published()->value);
                }, 'articles as pending_count' => function ($q) {
                    $q->where('status', ArticleStatusEnum::applyingPublish()->value);
                }]
            );

            if (!$user) throw new Exception("User " . __("not_found_common"));
        } catch (\Throwable $th) {
            return ApiResponse::error($th->getMessage());
        }

        return ApiResponse::success(new UserResource($user));
    }

    public function contributorAcknowledge(string $id)
    {
        try {
            $user = $this->userRepository->find(id: $id);

            if (!$user) throw new Exception("User " . __("not_found_common"));

            if ($user->contributor_status === UserContributorStatus::approved()->value) throw new Exception("Already an contributor.");

            $user->update([
                'contributor_status' => UserContributorStatus::approved()->value
            ]);

            $user->contributorApplicationApproved();
        } catch (\Throwable $th) {
            Log::error("Error deleting user with ID {$id}: " . $th->getMessage(), [
                'exception' => $th
            ]);
            return ApiResponse::error($th->getMessage());
        }

        return ApiResponse::success();
    }

    public function update(string $id, array $data)
    {
        try {
            $user = $this->userRepository->find(id: $id);

            if (!$user) throw new Exception("User " . __("not_found_common"));

            $user->update($data);

            if (request()->file('avatar')) {
                $user->addMediaFromRequest('avatar')->toMediaCollection('avatar');
            }

            if (!empty($data['affiliate_id'])) {
                $this->organizationRepository->affiliate($data['affiliate_id'], $user);
            }
        } catch (\Throwable $th) {

            return ApiResponse::error($th->getMessage());
        }

        return ApiResponse::success(new UserResource($user));
    }
}
