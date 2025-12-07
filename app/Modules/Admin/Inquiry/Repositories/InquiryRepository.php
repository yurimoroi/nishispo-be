<?php

namespace App\Modules\Admin\Inquiry\Repositories;

use App\Modules\Admin\Inquiry\Models\Inquiries;
use App\Modules\Admin\Inquiry\Models\Inquiry;
use App\Repositories\BaseRepository;

class InquiryRepository extends BaseRepository
{
    /**
     * Create a new class instance.
     */
    public function __construct(Inquiry $model)
    {
        parent::__construct($model);
    }
}
