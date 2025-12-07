<?php

namespace App\Enums;
use \Spatie\Enum\Enum;

/**
 * @method static self freeInput()
 * @method static self selectActivity()
 */

final class EventActivityLocationTypeEnum extends Enum
{
    public static function freeInput(): self
    {
        return new self(0); 
    }

    public static function selectActivity(): self
    {
        return new self(1); 
    }

    protected static function values(): array
    {
        return [
            'freeInput' => 0,
            'selectActivity' => 1
        ];
    }

    protected static function labels(): array
    {
        return [
            'freeInput' => __('event_activity_location_type.free_input'),
            'selectActivity' => __('event_activity_location_type.select')
        ];
    }
}
