<?php

namespace App\Repositories;

use App\Exceptions\InvalidLoginCredentialException;
use App\Exceptions\InvalidAdminLoginException;
use App\Exceptions\InvalidProviderException;
use App\Exceptions\InvalidSocialUnlikingException;
use App\Exceptions\UnableToCreateUserException;
use App\Http\ApiResponse\ApiResponse;
use App\Http\Resources\UserResource;
use App\Modules\User\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class UserRepository extends BaseRepository
{

    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function create(array $data = [])
    {
        try {
            return DB::transaction(function () use ($data) {
                $data['nickname'] = $data['nickname'] ?? $data['given_name'] . " " . $data['family_name'];
                $data['advertiser_flg'] = 0;
                return $this->model->create($data);
            });
        } catch (\Exception $e) {
            throw new UnableToCreateUserException();
        }
    }

    public function findByEmail($email)
    {
        return $this->model->where('email', $email)->first();
    }

    public function login($login_id, $password, $remember_me, $isAdmin = false)
    {
        $user = $this->model->where('login_id', $login_id)->first();

        
        if (!$user || !Hash::check($password, $user->password)) {
            throw new InvalidLoginCredentialException();
        }

        if (!$isAdmin && $user->isSecretariat()) {
            throw new InvalidLoginCredentialException();
        }

        if ($isAdmin && !$user->isSecretariat()) {
            throw new InvalidAdminLoginException();
        }

        $expiresAt = $remember_me ? Carbon::now()->addMonth(1) : null;

        $token = $user->createToken('api_token', ['*'], $expiresAt)->plainTextToken;

        $user->load(['company', 'organizationUser']);

        $response = [
            'token_type' => 'bearer',
            'access_token' =>  $token,
            'user' => new UserResource($user)
        ];

        return ApiResponse::success($response);
    }

    public function getProviderColumn($provider)
    {
        $providers = [
            'facebook' => 'facebook_id',
            'twitter' => 'x_id',
            'line' => 'line_id',
            'instagram' => 'instagram_id',
        ];

        if (!isset($providers[$provider])) {
            throw new InvalidProviderException();
        }

        return $providers[$provider];
    }

    public function getActiveSocialProviders($user)
    {
        $socialMedias = [
            'facebook_id' => $user->facebook_id,
            'instagram_id' => $user->instagram_id,
            'line_id' => $user->line_id,
            'x_id' => $user->x_id,
        ];

        return array_filter($socialMedias);
    }

    public function unlinkSocialProvider($provider, $user)
    {
        $column = $this->getProviderColumn($provider);

        if (!$user->login_id) {
            $activeProviders = $this->getActiveSocialProviders($user);

            if (count($activeProviders) == 1) {
                throw new InvalidSocialUnlikingException();
            }
        }

        $user->update([$column => null]);

        return ApiResponse::success($user);
    }

    public function linksocialProviderRegister($provider, $provider_id)
    {
        $column = $this->getProviderColumn($provider);

        $checkProvider = $this->findOnColumn($column, $provider_id);

        if ($checkProvider) {
            throw new Exception(__("already_link"));
        }

        return __('social_available_linking');
    }


    public function linkSocialProvider($provider, $provider_id, $user = null)
    {
        $column = $this->getProviderColumn($provider);

        if ($user) {
            $checkProvider = $this->findOnColumn($column, $provider_id);

            if (!$checkProvider) {
                return $user->update([$column => $provider_id]);
            }

            throw new Exception(__("already_link"));
        }

        return $this->model->where($column, $provider_id)->first();
    }

    public function socialLogin($provider, $provider_id, $remember_me = false)
    {
        $user = $this->linkSocialProvider($provider, $provider_id);

        if (!$user) {
            throw new InvalidLoginCredentialException();
        }

        $expiresAt = $remember_me ? Carbon::now()->addMonth(1) : null;

        $token = $user->createToken('api_token', ['*'], $expiresAt)->plainTextToken;

        $user->load(['company', 'organizationUser']);

        $response = [
            'token_type' => 'bearer',
            'access_token' =>  $token,
            'user' => new UserResource($user)
        ];

        return ApiResponse::success($response);
    }

    public function avatar($userId)
    {
        $user = $this->find($userId);

        if (!$user) return;

        $user->addMediaFromRequest('avatar')->toMediaCollection('avatar');

        return $user;
    }

    public function generatePasswordResetToken(User $user)
    {
        return Password::createToken($user);
    }

    public function distributionPointBodyCharactersCount(Collection $users)
    {
        $users->each(function ($user) {
            $user->total_body_characters = $user->articles->sum('body_characters_count');
        });

        return $users;
    }
}
