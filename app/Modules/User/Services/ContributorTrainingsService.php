<?php

namespace App\Modules\User\Services;

use App\Http\Resources\ContributorTrainingsResource;
use App\Repositories\ContributorTrainingsRepository;
use Illuminate\Support\Facades\Log;
use App\Http\ApiResponse\ApiResponse;
use App\Modules\Company\Models\ContributorTraining;
use App\Modules\User\Models\User;
use App\Repositories\UserRepository;

class ContributorTrainingsService
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        protected ContributorTrainingsRepository $contributorTrainingsRepository,
        protected UserRepository $userRepository
    ) {}

    public function getUserContributorTrainings()
    {
        try {
            $user = auth()->user();

            if (!$user) {
                return ApiResponse::error('No authenticated user found.', 401);
            }

            $contributorTrainings = $this->contributorTrainingsRepository->allTrainingsPerUser($user->id);
        } catch (\Throwable $th) {
            Log::error('Error retrieving contributor trainings: ' . $th->getTraceAsString());
            return ApiResponse::error($th->getMessage());
        }

        return ApiResponse::success(ContributorTrainingsResource::collection($contributorTrainings));
    }

    public function store(array $data)
    {
        return $this->contributorTrainingsRepository->create($data);
    }
}
