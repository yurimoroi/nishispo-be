<?php

namespace App\Http\Controllers\V1\Auth;

use App\Http\ApiResponse\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Modules\Company\Models\OrganizationUser;
use App\Modules\Company\Repositories\OrganizationRepository as RepositoriesOrganizationRepository;
use App\Repositories\MediaRepository;
use App\Repositories\OrganizationRepository;
use App\Repositories\UserRepository;
use Illuminate\Auth\Events\Registered;

class RegisterController extends Controller
{
    public function __construct(
        protected UserRepository $userRepo,
        protected RepositoriesOrganizationRepository $orgRepo,
        protected MediaRepository $mediaRepository,
    )
    {
        
    }
    /**
     * Handle the incoming request.
     */
    public function __invoke(RegisterRequest $request)
    {

        $user = $this->userRepo->create($request->validated());

        if ($user) {

            if (isset($request->affiliate_id) && $request->affiliate_id) {
                $this->orgRepo->affiliate(
                    organizations: $request->affiliate_id ? $request->affiliate_id : [],
                    user: $user,
                );
            }

            if($request->file('avatar')){
                $this->mediaRepository->uploadMedia($user , 'avatar' , 'avatar');
            }

            $user->newUserNotify();

            // event(new Registered($user));
        }

        return ApiResponse::success(
            message: 'User registered successfully',
            data: new UserResource($user)   
        );
    }
}
