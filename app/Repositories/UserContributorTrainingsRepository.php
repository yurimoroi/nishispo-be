<?php

namespace App\Repositories;

use App\Modules\User\Models\UsersContributorTrainings;

class UserContributorTrainingsRepository extends BaseRepository
{
    /**
     * Create a new class instance.
     */
    public function __construct(UsersContributorTrainings $model)
    {
        parent::__construct($model);
    }
}
