<?php

namespace App\Modules\User\Services;

use App\Enums\UserContributorStatus;
use App\Http\ApiResponse\ApiResponse;
use App\Repositories\UserContributorTrainingsRepository;
use App\Repositories\ContributorTrainingsRepository;
use App\Repositories\UserRepository;
use Exception;

class UserContributorTrainingsService
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        protected UserContributorTrainingsRepository $userContributorTrainingsRepository,
        protected ContributorTrainingsRepository $contributorTrainingsRepository,
        protected UserRepository $userRepository
    ) {}

    public function store(array $data)
    {
        try {
            $createdTraining = $this->userContributorTrainingsRepository->create([
                ...$data,
                "user_id" => auth()->user()->id
            ]);

            $user = auth()->user();

            $trainings = $this->contributorTrainingsRepository->allTrainingsPerUser($user->id);

            $trainingsArray = $trainings->toArray();

            $filteredData = array_filter($trainingsArray, function ($item) {
                return $item['users_contributor_trainings'] === null;
            });

            $filteredData = array_values($filteredData);

            if (empty($filteredData)) {
                $this->userRepository->update($user->id, [
                    'contributor_status' => UserContributorStatus::trainingCompleted()->value
                ]);
            }

        } catch (\Throwable $th) {
            return ApiResponse::error($th->getMessage());
        }

        return ApiResponse::success($createdTraining);
    }

    public function userContributorStatus()
    {
        try{
            $statuses = UserContributorStatus::getAll();

            return ApiResponse::success($statuses);
        }catch(Exception $th){
            ApiResponse::error($th->getMessage());
        }
    }
}
