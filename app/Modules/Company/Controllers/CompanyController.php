<?php

namespace App\Modules\Company\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CompanyInfoRequest;
use App\Http\Requests\CompanyPostLimitUpdateRequest;
use App\Modules\Company\Services\CompanyService;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CompanyController extends Controller
{
    public function __construct(
        protected CompanyService $companyService
    ) {}

    public function alignmentMedia(Request $request)
    {
        return $this->companyService->publishedAlignmentMedia();
    }

    public function getCompany()
    {
        return $this->companyService->getCompany();
    }

    public function dashboard()
    {
        return $this->companyService->dashboard();
    }

    public function update(CompanyInfoRequest $request)
    {
        $data = $request->validated();
        return $this->companyService->update($data);
    }

    public function search()
    {
        return $this->companyService->searchOrg();
    }

    public function updatePostLimit(CompanyPostLimitUpdateRequest $request)
    {
        return $this->companyService->updatePostLimit($request->validated());
    }
}
