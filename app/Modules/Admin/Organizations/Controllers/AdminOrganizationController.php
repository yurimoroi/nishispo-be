<?php

namespace App\Modules\Admin\Organizations\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrganizationCreateRequest;
use App\Http\Requests\OrganizationUpdateRequest;
use App\Modules\Admin\Organizations\Services\AdminOrgService;
use Illuminate\Http\Request;

class AdminOrganizationController extends Controller
{
    public function __construct(
        protected AdminOrgService $adminOrgService
    ){}

    public function index()
    {
        return $this->adminOrgService->index();
    }

    public function show(string $id)
    {
       
        return $this->adminOrgService->show($id);
    }

    public function store(OrganizationCreateRequest $request)
    {
        $data = $request->validated();
        return $this->adminOrgService->store( $data);
    }

    public function update(OrganizationUpdateRequest $request , string $id)
    {
        $data = $request->validated();
        return $this->adminOrgService->update($id , $data);
    }

    public function delete(string $id)
    {
        return $this->adminOrgService->delete($id);
    }

    public function affilliateApproved(string $id , string $userId)
    {
        return $this->adminOrgService->affilliateApproved($id,$userId);
    }

    public function affilliateWithdrawApproved(string $id , string $userId)
    {
        return $this->adminOrgService->affilliateWithdrawApproved($id,$userId);
    }
}
