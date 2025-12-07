<?php

namespace App\Modules\Company\Controllers;

use App\Http\ApiResponse\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrganizationResource;
use App\Modules\Company\Services\CompanyService;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{

    public function __construct(protected CompanyService $companyService) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->companyService->organizations();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
