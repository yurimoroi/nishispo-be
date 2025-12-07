<?php

namespace App\Modules\Company\Repositories;

use App\Enums\ArticleStatusEnum;
use App\Enums\RevisedArticleEnum;
use App\Enums\UserContributorStatus;
use App\Modules\Company\Models\Company;
use App\Repositories\BaseRepository;

class CompanyRepository extends BaseRepository
{
    public function __construct(Company $model)
    {
        parent::__construct($model);
    }

    public function dashboard()
    {
        return $this->model->where('id', auth()->user()->company_id)
            ->withCount([
                'users as contributor_waiting_approval' => function ($q) {
                    $q->where('contributor_status', UserContributorStatus::trainingCompleted()->value);
                },
                'articles as article_waiting_approval' => function ($q) {
                    $q->where('status', ArticleStatusEnum::applyingPublish()->value)
                    ->orWhere('status', ArticleStatusEnum::requestEdit()->value);
                },
                'articles as article_waiting_edit_approval' => function ($q) {
                    $q->where('status', ArticleStatusEnum::requestEdit()->value);
                },
                'articles as article_waiting_delete_approval' => function ($q) {
                    $q->where('status', ArticleStatusEnum::requestDelete()->value);
                },
                'articles as article_with_revision' => function ($q) {
                    $q->whereHas('revised', function ($q) {
                        $q->where('request_type', RevisedArticleEnum::correctionRequest()->value);
                    });
                }
            ])
            ->first();
    }
}
