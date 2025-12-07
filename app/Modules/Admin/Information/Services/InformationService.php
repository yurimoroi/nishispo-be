<?php

namespace App\Modules\Admin\Information\Services;

use App\Modules\Admin\Information\Models\Informations;

use App\Http\ApiResponse\ApiResponse;
use App\Http\Resources\InformationResource;
use App\Http\Resources\PaginateResource;
use App\Repositories\InformationRepository;
use App\Repositories\MediaRepository;
use Exception;

class InformationService
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        protected InformationRepository $informationRepository,
        protected MediaRepository $mediaRepository
    ) {}

    public function informations()
    {
        try {

            $infos = $this->informationRepository->all(
                with: ['media','company'],
                orderBy: 'published_at',
                sortBy: 'desc',
                paginate: true,
                perPage: request('per_page', Informations::LIMIT_PER_PAGE)
            );

            return ApiResponse::success(PaginateResource::make($infos, InformationResource::class));
        } catch (\Throwable $th) {
            return ApiResponse::error($th->getMessage());
        }
    }

    public function information(String $id)
    {
        try {
            $info = $this->informationRepository->find(
                id: $id,
                with: ['media', 'company']
            );

            if(!$info) throw new Exception("Information ". __("not_found_common"));
        } catch (\Throwable $th) {
            return ApiResponse::error($th->getMessage());
        }

        return ApiResponse::success(new InformationResource($info));
    }

    public function delete(String $id)
    {
        try {

            $deleted = $this->informationRepository->delete($id);

            if (!$deleted) throw new Exception(__("info_failed_delete"));

        } catch (\Throwable $th) {
            return ApiResponse::error($th->getMessage());
        }
        return ApiResponse::success(__("info_delete_success"));
    }

    public function create(array $data)
    {
        try {

            $info = $this->informationRepository->create($data);

            if (!$info) throw new Exception(__("info_failed_create"));

            if (request()->hasFile('info_images')) {
                $this->mediaRepository->uploadMedia(
                    model: $info,
                    mediaRequestName: 'info_images',
                    mediaCollectionName: 'info-images'
                );
            }

            return ApiResponse::success(new InformationResource($info));
        } catch (\Throwable $th) {
            return ApiResponse::error($th->getMessage());
        }
    }

    public function update($id, array $data)
    {
        try {

            $info = $this->informationRepository->find(
                id: $id,
                with: ['media']
            );

            if (!$info) throw new Exception("Information ". __("not_found_common"));

            $info->update($data);

            if (isset($data['info_images'])) {
                $info->clearMediaCollection('info-images');

                $this->mediaRepository->uploadMedia(
                    model: $info,
                    mediaRequestName: 'info_images',
                    mediaCollectionName: 'info-images'
                );
            }

            return ApiResponse::success(new InformationResource($info));
        } catch (\Throwable $th) {
            return ApiResponse::error($th->getMessage());
        }
    }
}
