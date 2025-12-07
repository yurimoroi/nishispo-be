<?php

namespace App\Modules\Admin\ContributorTrainigs\Services;

use App\Http\ApiResponse\ApiResponse;
use App\Http\Resources\ContributorTrainingsResource;
use App\Http\Resources\PaginateResource;
use App\Modules\Company\Models\ContributorTraining;
use App\Repositories\ContributorTrainingsRepository;
use Exception;

class AdminContributorTrainingService
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        protected ContributorTrainingsRepository $contributorTrainingsRepository
    ) {}

    public function index()
    {
        try {
            $list = $this->contributorTrainingsRepository->all(
                orderBy: 'no',
                sortBy:'asc',
                perPage: request('per_page', ContributorTraining::LIMIT_PER_PAGE),
                paginate: true,
            );
        } catch (\Throwable $th) {
            return ApiResponse::error($th->getMessage());
        }

        return ApiResponse::success(
            PaginateResource::make($list, ContributorTrainingsResource::class)
        );
    }

    public function show(string $id)
    {
        try {

            $training = $this->contributorTrainingsRepository->find(id: $id);

            if (!$training) throw new Exception("Training" . __("not_found_common"));
        } catch (\Throwable $th) {
            return ApiResponse::error($th->getMessage());
        }

        return ApiResponse::success(
            new ContributorTrainingsResource($training)
        );
    }

    public function create(array $data)
    {
        try {
            $training = $this->contributorTrainingsRepository->create($data);
        } catch (\Throwable $th) {
            return ApiResponse::error($th->getMessage());
        }

        return ApiResponse::success(
            new ContributorTrainingsResource($training)
        );
    }

    public function update(string $id, array $data)
    {
        try {

            $training = $this->contributorTrainingsRepository->find(id: $id);

            if (!$training) throw new Exception("Training" . __("not_found_common"));

            $training->update($data);
        } catch (\Throwable $th) {
            return ApiResponse::error($th->getMessage());
        }

        return ApiResponse::success(
            new ContributorTrainingsResource($training)
        );
    }

    public function delete(string $id)
    {
        try {

            $training = $this->contributorTrainingsRepository->find(id: $id);

            if (!$training) throw new Exception("Training " . __("not_found_common"));

            $training->delete();
        } catch (\Throwable $th) {
            return ApiResponse::error($th->getMessage());
        }

        return ApiResponse::success();
    }
}
