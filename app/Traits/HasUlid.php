<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasUlid
{
    protected static function bootHasUlid()
    {
        static::creating(function ($model) {
            if (!$model->getKey()) {
                $model->{$model->getKeyName()} = (string) Str::ulid();
            }
        });
    }
   
    public function getIncrementing()
    {
        return false;
    }

    public function getKeyType()
    {
        return 'string';
    }
}
