<?php

namespace App\Modules\Admin\Inquiry\Services;

use App\Http\ApiResponse\ApiResponse;
use App\Http\Resources\InquiryResource;
use App\Http\Resources\PaginateResource;
use App\Modules\Admin\Inquiry\Models\Inquiry;
use App\Modules\Admin\Inquiry\Repositories\InquiryRepository;
use Exception;
use App\Repositories\UserRepository;

class InquiryService
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        protected InquiryRepository $inquiryRepository,
        protected UserRepository $userRepository
    ) {}

    public function index()
    {
        try {
            $inquiries = $this->inquiryRepository->all(
                with: ['inquiryType'],
                orderBy: 'created_at',
                sortBy: 'desc',
                paginate: true,
                perPage: request('per_page', Inquiry::DEFAULT_PER_PAGE)
            );
        } catch (\Throwable $th) {
            return ApiResponse::error($th->getMessage());
        }

        return ApiResponse::success(PaginateResource::make($inquiries, InquiryResource::class));
    }

    public function show(string $id)
    {
        try {
            $inquiry = $this->inquiryRepository->find(
                id: $id,
                with: ['inquiryType']
            );

            if (!$inquiry) throw new Exception("Inquiry " . __("not_found_common"));
        } catch (\Throwable $th) {
            return ApiResponse::error($th->getMessage());
        }

        return ApiResponse::success(new InquiryResource($inquiry));
    }

    public function update(string $id, $replyContent)
    {
        try {
            $inquiry = $this->inquiryRepository->find(
                id: $id,
                with: ['inquiryType']
            );

            if (!$inquiry) throw new Exception("Inquiry " . __("not_found_common"));

            $inquiry->update(['reply' => $replyContent]);

            $inquiry->sendReply();
            
        } catch (\Throwable $th) {
            return ApiResponse::error($th->getMessage());
        }

        return ApiResponse::success(new InquiryResource($inquiry));
    }

    public function sendInquiry(array $data)
    {
        try {

            $create = $this->inquiryRepository->create($data);

            $inquiry = $this->inquiryRepository->findOnColumn("email", $data['email']);

            if (!$inquiry) {
                return ApiResponse::error(__("not_found_common"), 404);
            }

            $inquiry->sendInquiry();

        } catch (\Throwable $th) {
            return ApiResponse::error($th->getMessage());
        }

        return ApiResponse::success();
    }

    public function delete(String $id)
    {
        try {

            $deleted = $this->inquiryRepository->delete($id);

            if (!$deleted) throw new Exception(__('not_found_common'));

        } catch (\Throwable $th) {
            return ApiResponse::error($th->getMessage());
        }
        return ApiResponse::success();
    }
}
