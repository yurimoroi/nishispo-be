<?php

namespace App\Modules\User\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\SocialLinkRegisterRequest;
use App\Http\Requests\SocialLinkRequest;
use App\Http\Requests\SocialUnlinkRequest;
use App\Modules\User\Services\SocialService;

class SocialController extends Controller
{
     /**
     * Create a new class instance.
     */
    public function __construct(
        protected SocialService $socialService
    )
    {}

    public function linkSocial(SocialLinkRequest $request)
    {
        return $this->socialService->linkSocialAccount($request->provider, $request->provider_id);
    }

    public function unlinkSocial(SocialUnlinkRequest $request)
    {
        return $this->socialService->unlinkSocialAccount($request->provider);
    }

    public function linkSocialRegister(SocialLinkRegisterRequest $request)
    {
        return $this->socialService->linkSocialAccountRegister($request->provider, $request->provider_id);
    }
}
