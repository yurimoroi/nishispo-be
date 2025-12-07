<?php

namespace App\Modules\Company\Repositories;

use App\Enums\OrganizationUserStatus;
use App\Modules\Company\Models\Organization;
use App\Modules\Company\Models\OrganizationUser;
use App\Modules\User\Models\User;
use App\Repositories\BaseRepository;
use Illuminate\Support\Str;

class OrganizationRepository extends BaseRepository
{
    /**
     * Create a new class instance.
     */
    public function __construct(Organization $model)
    {
        parent::__construct($model);
    }

    public function affiliate(array $organizations, User $user): User
    {
        if (!empty($organizations)) {

            $syncOrganizations = array_map(function ($organization) use ($user) {
                $exists = $user->affiliate()->where('organization_id', $organization)->first();
                
                return [
                    "id" => $exists ? $exists->pivot->id : Str::ulid(),
                    "organization_id" => $organization,
                    "user_id" => $user->id,
                    "status" => $exists ? ($exists->pivot->status ?? OrganizationUserStatus::applyingForMembership()->value)
                        : OrganizationUserStatus::applyingForMembership()->value
                ];
            }, $organizations);

            $user->organizations()->sync($syncOrganizations);
        };

        return $user;
    }

    public function adminUsers(Organization $organization, array $users): Organization
    {
        if (!empty($users)) {
            $adminUsers = array_map(fn($user) => [
                "id" => Str::ulid(),
                "organization_id" => $organization->id,
                "user_id" => $user,
                "status" => OrganizationUserStatus::applyingForMembership()->value,
                "administrator_flg" => 1
            ], $users);

            $organization->adminUsers()->sync($adminUsers);
        }

        return $organization;
    }

    public function organizationUser(string $organizationId, string $userId)
    {
        return OrganizationUser::where([
            'organization_id' => $organizationId,
            'user_id' =>  $userId,
            'status' => OrganizationUserStatus::applyingForMembership()->value
        ])
            ->with(['user', 'organization'])
            ->first();
    }

    public function organizationUserApproved(string $organizationId, string $userId)
    {
        return OrganizationUser::where([
            'organization_id' => $organizationId,
            'user_id' =>  $userId,
            'status' => OrganizationUserStatus::afilliation()->value
        ])
            ->with(['user', 'organization'])
            ->first();
    }

    public function organizationUserWithdrawal(string $organizationId, string $userId)
    {
        return OrganizationUser::where([
            'organization_id' => $organizationId,
            'user_id' =>  $userId,
            'status' => OrganizationUserStatus::applyingForWithdrawal()->value
        ])
            ->with(['user', 'organization'])
            ->first();
    }
}
