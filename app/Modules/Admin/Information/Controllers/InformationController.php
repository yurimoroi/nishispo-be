<?php

namespace App\Modules\Admin\Information\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\InformationCreateRequest;
use App\Http\Requests\InformationUpdateRequest;
use App\Modules\Admin\Information\Services\InformationService;

class InformationController extends Controller
{
    public function __construct(
        protected InformationService $informationService
        )
    {  }

    public function index()
    {
        return $this->informationService->informations();
    }

    public function store(InformationCreateRequest $request)
    {
        $data = $request->validated();
        return $this->informationService->create($data);
    }

    public function show(String $id)
    {
        return $this->informationService->information($id);
    }

    public function delete(String $id)
    {
        return $this->informationService->delete($id);
    }

    public function update(String $id, InformationUpdateRequest $request)
    {
        $data = $request->validated();
        return $this->informationService->update($id, $data);
    }
}
