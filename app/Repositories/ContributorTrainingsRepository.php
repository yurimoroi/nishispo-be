<?php

namespace App\Repositories;

use App\Modules\User\Models\ContributorTrainings;
use Illuminate\Support\Collection;

class ContributorTrainingsRepository extends BaseRepository
{
    /**
     * Create a new class instance.
     */
    public function __construct(ContributorTrainings $model)
    {
        parent::__construct($model);
    }

    public function allTrainingsPerUser($userId): Collection
    {
        return $this->model->with(['usersContributorTrainings' => function ($query) use ($userId) {
            $query->where('user_id', $userId);
        }])->get();
    }

    public function canUpdateUserContributorStatus($trainings): bool
    {
        if (empty($trainings)) {
            return false;
        }
    
        foreach($trainings as $data) {
            if ($data->users_contributor_trainings === null) {
                return false;
            }
        }
    
        return true;
    }
}
