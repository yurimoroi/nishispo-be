<?php

namespace App\Modules\Admin\Inquiry\Models;

use App\Mail\InquiryMail;
use App\Mail\InquiryMailAdmin;
use App\Mail\InquiryMailAdminReply;
use App\Mail\InquiryMailUser;
use App\Mail\InquiryReply;
use App\Modules\User\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\BelongsToCompany;
use Database\Factories\InquiryFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Mail;
use Illuminate\Notifications\Notifiable;

class Inquiry extends Model
{
    use HasFactory, HasUlid, SoftDeletes, BelongsToCompany, Notifiable;

    const DEFAULT_PER_PAGE = 5;

    protected $fillable = [
        'id',
        'company_id',
        'inquiry_type_id',
        'name',
        'body',
        'email',
        'reply',
    ];

    protected $with = ['inquiryType'];

    protected static function newFactory()
    {
        return InquiryFactory::new();
    }

    public function inquiryType(): BelongsTo
    {
        return $this->belongsTo(InquiryType::class);
    }

    public function sendReply()
    {
        $delay = now()->addSeconds(5);
        Mail::to($this->email)->later($delay, new InquiryMailAdminReply($this));
    }

    public function sendInquiry()
    {
        $primaryEmail = 'miyaspo_jimukyoku@spocomi.co.jp';
        Mail::to($primaryEmail)
            ->later(now()->addSeconds(5), new InquiryMailAdmin($this));

        Mail::to($this->email)
            ->later(now()->addSeconds(5), new InquiryMailUser($this));
    }
}
