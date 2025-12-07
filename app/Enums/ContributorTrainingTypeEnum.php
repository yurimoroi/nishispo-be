<?php

namespace App\Enums;
use \Spatie\Enum\Enum;

/**
 * @method static self video()
 * @method static self blog()
 */

final class ContributorTrainingTypeEnum extends Enum
{
    public static function video(): self
    {
        return new self(0); 
    }

    public static function blog(): self
    {
        return new self(1); 
    }

    protected static function values(): array
    {
        return [
            'video' => 0,
            'blog' => 1
        ];
    }

    protected static function labels(): array
    {
        return [
            'video' => __('contributor_status_type_enum.video'),
            'blog' => __('contributor_status_type_enum.blog')
        ];
    }
}
