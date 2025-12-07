<?php

namespace App\Modules\User\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserContributorTrainingsRequest;
use App\Modules\User\Services\UserContributorTrainingsService;
use Illuminate\Http\Request;

class UserContributorTrainingsController extends Controller
{
    public function __construct(
        protected UserContributorTrainingsService $userContributorTrainingsService
    )
    {}

    public function store(UserContributorTrainingsRequest $request)
    {
        $data = $request->validated();
        return $this->userContributorTrainingsService->store($data);
    }

    public function contributorStatus()
    {
        return $this->userContributorTrainingsService->userContributorStatus();
    }
}
