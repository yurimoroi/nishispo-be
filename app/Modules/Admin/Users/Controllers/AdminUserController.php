<?php

namespace App\Modules\Admin\Users\Controllers;

use App\Enums\ArticleStatusEnum;
use App\Exports\UsersDistributionPoint;
use App\Exports\UsersExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserEditRequest;
use App\Http\Resources\UserResource;
use App\Modules\Admin\Users\Services\AdminUserService;
use App\Modules\User\Models\User;
use App\Repositories\UserRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class AdminUserController extends Controller
{
    public function __construct(
        protected AdminUserService $adminUserService
    ) {}

    public function index()
    {
        $paginate = request('paginate', 1);
        return $this->adminUserService->index($paginate);
    }

    public function export()
    {   
        return Excel::download(new UsersExport(new UserRepository(new User())), 'users.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }

    public function delete(string $userId)
    {
        return $this->adminUserService->removeUser($userId);
    }

    public function show(string $userId)
    {
        return $this->adminUserService->show($userId);
    }

    public function update(UserEditRequest $request , string $userId)
    {
        $data = $request->validated();
        return $this->adminUserService->update($userId,$data);
    }

    public function contributorAcknowledge(string $userId)
    {
        return $this->adminUserService->contributorAcknowledge($userId);
    }

    public function distributionpointCSV()
    {
        // $startDate = Carbon::parse(request('start_date', now()))->startOfDay();
        // $endDate = Carbon::parse(request('end_date', now()))->endOfDay();
        
        // $users = User::withPublishedArticlesInRange($startDate, $endDate)->get();

        // // $users = $userRepo->distributionPointBodyCharactersCount($users);

        // return $users;
        $filename = Carbon::now()->format('Ymd_Hi')."_rakuten_point_list.xlsx";
        return Excel::download(new UsersDistributionPoint, $filename);
    }
}
