<?php

namespace App\Modules\Admin\Inquiry\Controllers;

use App\Http\ApiResponse\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\InquiryCreateRequest;
use App\Modules\Admin\Inquiry\Models\InquiryType;
use App\Modules\Admin\Inquiry\Services\InquiryService;
use Illuminate\Http\Request;

class InquiryController extends Controller
{
    public function __construct(
        protected InquiryService $inquiryService
    ) {}

    public function index()
    {
        return $this->inquiryService->index();
    }

    public function show(string $id)
    {
        return $this->inquiryService->show($id);
    }

    public function update(string $id, Request $request)
    {
        $request->validate(['reply' => 'required']);

        $reply = $request->reply;

        return $this->inquiryService->update($id, $reply);
    }
    
    public function store(InquiryCreateRequest $request)
    {
        return $this->inquiryService->sendInquiry($request->validated());
    }

    public function inquiryTypes()
    {
        return ApiResponse::success(InquiryType::all());
    }

    public function delete(string $id)
    {
        return $this->inquiryService->delete($id);
    }
}
