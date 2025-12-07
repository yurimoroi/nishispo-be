<?php

namespace App\Modules\Admin\Organizations\Services;

use App\Enums\OrganizationUserStatus;
use App\Http\ApiResponse\ApiResponse;
use App\Http\Resources\OrganizationResource;
use App\Http\Resources\PaginateResource;
use App\Modules\Company\Models\Organization;
use App\Modules\Company\Models\OrganizationUser;
use App\Modules\Company\Repositories\OrganizationRepository;
use App\Repositories\MediaRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\MockObject\Stub\ReturnArgument;

class AdminOrgService
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        protected OrganizationRepository $organizationRepository,
        protected MediaRepository $mediaRepository
    ) {}

    public function index()
    {
        $perPage = request('per_page', Organization::DEFAULT_PAGINATE);
        try {
            $organizaion = $this->organizationRepository->all(
                paginate: true,
                perPage: $perPage
            );
        } catch (\Throwable $th) {
            return ApiResponse::error($th->getMessage());
        }

        return ApiResponse::success(PaginateResource::make($organizaion, OrganizationResource::class));
    }

    public function store(array $data)
    {
        try {
            $organization = DB::transaction(function () use ($data) {
                $organization = $this->organizationRepository->create($data);

                $this->organizationRepository->adminUsers($organization, $data['user_administrators']);

                $this->mediaRepository->uploadMedia(
                    model: $organization,
                    mediaRequestName: 'logo',
                    mediaCollectionName: 'organization-logo'
                );

                return $organization;
            });
        } catch (\Throwable $th) {
            return ApiResponse::error($th->getMessage());
        }

        return ApiResponse::success(new OrganizationResource($organization));
    }

    public function update(string $id, array $data)
    {
        try {
            $organization = $this->organizationRepository->find(id: $id);

            if (!$organization) throw new Exception("Organization " . __("not_found_common"));

            $organization->update($data);

            if(isset($data['user_administrators']) && !empty($data['user_administrators'])) {
                $this->organizationRepository->adminUsers($organization, $data['user_administrators']);
            }
        
            if (request()->file('logo')) {
                $organization->clearMediaCollection('organization-logo');
                $this->mediaRepository->uploadMedia(
                    model: $organization,
                    mediaRequestName: 'logo',
                    mediaCollectionName: 'organization-logo'
                );
            }
        } catch (\Throwable $th) {
            return ApiResponse::error($th->getMessage());
        }

        return ApiResponse::success(new OrganizationResource($organization));
    }

    public function delete(string $id)
    {
        try {
            $organizaion = $this->organizationRepository->find(id:$id);

            if(!$organizaion) throw new Exception("Organization ". __("not_found_common"));

            $organizaion->delete();
        
        } catch (\Throwable $th) {
            return ApiResponse::error($th->getMessage());
        }

        return ApiResponse::success();
    }

    public function show(string $id)
    {
        try {
            $organization = $this->organizationRepository->find(id:$id,with:['adminUsers']);
   
            if(!$organization) throw new Exception("Organization ". __("not_found_common"));
           
        } catch (\Throwable $th) {
            return ApiResponse::error($th->getMessage());
        }

        return ApiResponse::success(new OrganizationResource($organization));
    }

    public function affilliateApproved(string $id , string $userid)
    {
        try {
            $organizationUser = $this->organizationRepository->organizationUser($id,$userid);
            
            if(! $organizationUser) throw new Exception('User '. __("not_found_common"));
            
            $organizationUser->userApprovedNotify();
        } catch (\Throwable $th) {
            return ApiResponse::error($th->getMessage());
        }

        return ApiResponse::success();
    }

    public function affilliateWithdrawApproved(string $id , string $userid)
    {
        try {
            $organizationUser = $this->organizationRepository->organizationUserWithdrawal($id,$userid);
            
            if(! $organizationUser) throw new Exception('User '. __("not_found_common"));
            
            $organizationUser->update(['status' => OrganizationUserStatus::withdrawal()->value]);
        } catch (\Throwable $th) {
            return ApiResponse::error($th->getMessage());
        }

        return ApiResponse::success();
    }
}
