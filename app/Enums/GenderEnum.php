<?php

namespace App\Enums;
use \Spatie\Enum\Enum;

/**
 * @method static self noAnswer()
 * @method static self female()
 * @method static self male()
 */
final class GenderEnum extends Enum
{
    public static function noAnswer(): self
    {
        return new self(0); 
    }

    public static function female(): self
    {
        return new self(1); 
    }

    public static function male(): self
    {
        return new self(2); 
    }

    protected static function values(): array
    {
        return [
            'noAnswer' => 0,
            'female' => 1,
            'male' => 2,
        ];
    }

    protected static function labels(): array
    {
        return [
            'noAnswer' => __('gender_enum.no_answer'),
            'female' => __('gender_enum.female'),
            "male" => __('gender_enum.male'),
        ];
    }
}
