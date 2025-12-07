<?php

namespace App\Modules\Admin\ContributorTrainigs\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContributorTrainingsRequest;
use App\Modules\Admin\ContributorTrainigs\Services\AdminContributorTrainingService;
use Illuminate\Http\Request;

class AdminContributorTrainingController extends Controller
{
    public function __construct(
        protected AdminContributorTrainingService $adminContributorTrainingService
    ) {}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->adminContributorTrainingService->index();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
       
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ContributorTrainingsRequest $request)
    {
        $data = $request->validated();
        return $this->adminContributorTrainingService->create($data );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return $this->adminContributorTrainingService->show($id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id, ContributorTrainingsRequest $request)
    {
      
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ContributorTrainingsRequest $request, string $id)
    {
        $data = $request->validated();
        return $this->adminContributorTrainingService->update($id , $data);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return $this->adminContributorTrainingService->delete($id);
    }
}
