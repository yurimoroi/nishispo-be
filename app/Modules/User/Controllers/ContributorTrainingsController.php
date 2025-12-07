<?php

namespace App\Modules\User\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Enums\UserContributorStatus;
use Illuminate\Support\Facades\Auth;
use App\Http\ApiResponse\ApiResponse;
use App\Http\Requests\ContributorTrainingsRequest;
use App\Modules\User\Services\ContributorTrainingsService;

class ContributorTrainingsController extends Controller
{
     /**
     * Create a new class instance.
     */
    public function __construct(
        protected ContributorTrainingsService $contributorTrainingsService
    )
    {}

    public function index()
    {
        return $this->contributorTrainingsService->getUserContributorTrainings();
    }

    public function store(ContributorTrainingsRequest $request)
    {
        $data = $request->validated();
        return $this->contributorTrainingsService->store($data);
    }

}
