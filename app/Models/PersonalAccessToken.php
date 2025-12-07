<?php

namespace App\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;

class PersonalAccessToken extends SanctumPersonalAccessToken
{
    use HasFactory, HasUlid;

    protected $table = 'personal_access_tokens';

    protected $fillable = [
        'id',
        'name',
        'tokenable_type',
        'tokenable_id',
        'token',
        'abilities',
        'last_used_at',
        'expires_at',
    ];
}
