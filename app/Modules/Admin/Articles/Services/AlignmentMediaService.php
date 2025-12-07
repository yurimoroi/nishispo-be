<?php

namespace App\Modules\Admin\Articles\Services;

use App\Http\ApiResponse\ApiResponse;
use App\Http\Resources\AlignMediaResource;
use App\Modules\Articles\Repositories\TopArticleRepository;
use App\Modules\Company\Repositories\AlignMediaRepository;
use App\Repositories\MediaRepository;
use Exception;

class AlignmentMediaService
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        protected AlignMediaRepository $alignMediaRepository,
        protected MediaRepository $mediaRepository,
    ) {}

    public function create(array $data)
    {
        try {
            $media = $this->alignMediaRepository->create($data);

            $bannerMedia = request()->file('banner', []);

            if ($bannerMedia) {
                $this->mediaRepository->uploadMedia($media, 'banner', 'alignment-media-banner');
            }
        } catch (\Throwable $th) {
            return ApiResponse::error($th->getMessage());
        }

        return ApiResponse::success(new AlignMediaResource($media));
    }

    public function update($id, array $data)
    {
        try {
            $media = $this->alignMediaRepository->find(id: $id);

            if (!$media) throw new Exception("Media " . __("not_found_common"));
            $media->update($data);

            $bannerMedia = request()->file('banner', []);

            if ($bannerMedia) {
                $this->mediaRepository->uploadMedia($media, 'banner', 'alignment-media-banner');
            }
        } catch (\Throwable $th) {
            return ApiResponse::error($th->getMessage());
        }

        return ApiResponse::success(new AlignMediaResource($media));
    }

    public function delete($id)
    {
        try {
            $media = $this->alignMediaRepository->find(id: $id);

            if (!$media) throw new Exception("Media " . __("not_found_common"));
            $media->delete();
        } catch (\Throwable $th) {
            return ApiResponse::error($th->getMessage());
        }

        return ApiResponse::success();
    }
}
