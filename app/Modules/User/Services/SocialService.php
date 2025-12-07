<?php

namespace App\Modules\User\Services;

use App\Exceptions\InvalidProviderException;
use App\Exceptions\InvalidSocialUnlikingException;
use App\Repositories\UserRepository;
use App\Http\ApiResponse\ApiResponse;
use Illuminate\Support\Facades\Auth;

class SocialService
{
    /**
     * Create a new class instance.
     */
    /**
     * Create a new class instance.
     */
    public function __construct(
        protected UserRepository $userRepository,
    ) {}

    public function linkSocialAccount($provider, $provider_id)
    {
        try {

            $user = Auth::user();

            $link = $this->userRepository->linkSocialProvider($provider, $provider_id, $user);

            if (!$link) {
                return  ApiResponse::error(__('provider_error_updating_links'));
            }
        } catch (InvalidProviderException $e) {
            return ApiResponse::error(__('invalid_provider'));
        } catch (\Throwable $th) {
            return ApiResponse::error($th->getMessage());
        }

        return ApiResponse::success(__('provider_link'));
    }

    public function unlinkSocialAccount($provider)
    {
        try {

            $user = Auth::user();

            $unlink = $this->userRepository->unlinkSocialProvider($provider, $user);

            if (!$unlink) {
                return  ApiResponse::error(__('provider_error_updating_links'));
            }

            return ApiResponse::success(__('provider_unlink'));
        } catch (InvalidProviderException $e) {
            return ApiResponse::error(__('invalid_provider'));
        } catch (InvalidSocialUnlikingException $e) {
            return $e->render(request());
        } catch (\Throwable $th) {
            return ApiResponse::error($th->getMessage());
        }
    }

    public function linkSocialAccountRegister($provider, $provider_id)
    {
        try {

            $link = $this->userRepository->linksocialProviderRegister($provider, $provider_id);

            if (!$link) {
                return  ApiResponse::error(__('provider_error_updating_links'));
            }
        } catch (InvalidProviderException $e) {
            return ApiResponse::error(__('invalid_provider'));
        } catch (\Throwable $th) {
            return ApiResponse::error($th->getMessage());
        }

        return  ApiResponse::success($link);
    }
}
