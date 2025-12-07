<?php

namespace App\Modules\User\Services;

use App\Enums\ArticleStatusEnum;
use App\Enums\OrganizationUserStatus;
use App\Enums\UserContributorStatus;
use App\Exceptions\InvalidCurrentPassword;
use App\Exceptions\InvalidLoginCredentialException;
use App\Filters\ArticleCategoryFilter;
use App\Filters\ArticleSearchFilter;
use App\Filters\ArticleStatusFilter;
use App\Http\ApiResponse\ApiResponse;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\PaginateResource;
use App\Http\Resources\UserResource;
use App\Modules\Articles\Models\Article;
use App\Modules\Articles\Repositories\ArticleCategoryRepository;
use App\Modules\Articles\Repositories\ArticleRepository;
use App\Modules\Company\Repositories\OrganizationRepository;
use App\Repositories\UserRepository;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Spatie\QueryBuilder\AllowedFilter;

class UserService
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        protected UserRepository $userRepository,
        protected ArticleRepository $articleRepository,
        protected ArticleCategoryRepository $articleCategoryRepository,
        protected OrganizationRepository $organizationRepository,
    ) {}

    public function me()
    {
        $user = $this->userRepository->find(
            id:auth()->user()->id, 
            withCount: ['articles as published_count' => function ($q) {
                $q->where('status', ArticleStatusEnum::published()->value);
            },'articles as pending_count' => function($q) {
                $q->where('status' , ArticleStatusEnum::applyingPublish()->value);
            }]
    
    );
        return ApiResponse::success(new UserResource($user));
    }

    public function update(array $data)
    {
        
        try {
            $user = $this->userRepository->find(id: auth()->user()->id);

            $user->update($data);
            
            if(request()->file('avatar')){
                $user->addMediaFromRequest('avatar')->toMediaCollection('avatar');
            }

            if(!empty($data['affiliate_id'])){
                $this->organizationRepository->affiliate($data['affiliate_id'], $user);
            }

        } catch (\Throwable $th) {
            return ApiResponse::error($th->getMessage());
        }

        return ApiResponse::success(new UserResource($user));
    }

    public function articles()
    {
        try {

            $articles = $this->articleRepository->all(
                with: ['organization','categories','tags','revised'],
                allowedFilters: [
                    AllowedFilter::custom('search', new ArticleSearchFilter),
                    AllowedFilter::custom('categories', new ArticleCategoryFilter),
                    AllowedFilter::custom('status', new ArticleStatusFilter)
                ],
                orderBy: 'created_at',
                sortBy: 'desc',
                where: ['user_id' => Auth::user()->id],
                paginate: true,
                perPage: request('per_page', Article::LIMIT_PER_PAGE)
            );
        } catch (\Throwable $th) {
            return ApiResponse::error($th->getMessage());
        }

        return ApiResponse::success(PaginateResource::make($articles, ArticleResource::class));
    }

    public function changePassword()
    {
        try {
            $old_password = request('old_password', '');
            $password = request('password', '');

            $user = Auth()->user();

            if (!Hash::check($old_password,  $user->password)) {
                throw new InvalidCurrentPassword();
            }

            $user->password = Hash::make($password);
            $user->save();

            $user->sendChangePasswordNotification($password);
        } catch (InvalidCurrentPassword $e) {
            return $e->render(request());
        } catch (\Throwable $th) {
            return ApiResponse::error($th->getMessage());
        }

        return ApiResponse::success(message: __("password_change_success"));
    }

    public function resetPasswordRequest(array $data)
    {
        try {

            $user = $this->userRepository->findOnColumn(column: "login_id", value: $data['login_id']);

            if (!$user) {
                throw new InvalidLoginCredentialException();
            }

            if ($user->email !== $data['email']) {
                throw new InvalidLoginCredentialException();
            }

            $token = $this->userRepository->generatePasswordResetToken($user);

            $user->sendPasswordResetNotification($token);
        } catch (InvalidLoginCredentialException $e) {
            return $e->render(request());
        } catch (\Throwable $th) {
            return ApiResponse::error($th->getMessage());
        }

        return ApiResponse::success(message: __("send_reset_link_to_email"));
    }

    public function resetPassword(array $data)
    {
        try {

            $user = $this->userRepository->findOnColumn('email', $data['email']);

            $response = Password::reset(
                $data,
                function ($user, $password) {
                    $user->forceFill(['password' => bcrypt($password)])->save();
                    $user->sendChangePasswordNotification($password);
                }
            );

            if ($response !== Password::PASSWORD_RESET) {
                return ApiResponse::error(__("problem_occured"));
            }
        } catch (\Throwable $th) {
            return ApiResponse::error($th->getMessage());
        }

        return ApiResponse::success(message: __("success_reset_password"));
    }

    public function requestLoginId($email)
    {
        try {
            $user = $this->userRepository->findOnColumn("email", $email);

            if (!$user) {
                return ApiResponse::error(__("not_found_common"), 404);
            }

            $user->sendRequestLoginId();
        } catch (\Throwable $th) {
            return ApiResponse::error($th->getMessage());
        }

        return ApiResponse::success();
    }

    public function contributorApply(array $data)
    {
        try {

            $update = $this->userRepository->update(auth()->user()->id, [
                ...$data,
                "contributor_status" => UserContributorStatus::trainingInProgress()->value
            ]);

            if (!$update) {
                return ApiResponse::error(__('contributor_update_failed'));
            }

            return ApiResponse::success(__('user_contributor_updated_success'));
        } catch (\Throwable $th) {
            return ApiResponse::error('An error occurred while trying to updating the user contributor.');
        }
    }

    public function affiliateWithdraw(string $id)
    {
        try {
            $user = auth()->user();
           
            $organizationUser = $this->organizationRepository->organizationUserApproved($id, $user->id);

            if(!$organizationUser) throw new Exception(__("not_found_common"));

            $organizationUser->update([
                'status' => OrganizationUserStatus::applyingForWithdrawal()->value
            ]);
            
        } catch (\Throwable $th) {
            return ApiResponse::error($th->getMessage());
        }

        return ApiResponse::success();
    }
}
