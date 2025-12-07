<?php

namespace App\Enums;
use \Spatie\Enum\Enum;

/**
 * @method static self notPossible()
 * @method static self possibleFileComplaint()
 */

final class EventLateDeclarationEnum extends Enum
{
    public static function notPossible(): self
    {
        return new self(0); 
    }

    public static function possibleFileComplaint(): self
    {
        return new self(1); 
    }

    protected static function values(): array
    {
        return [
            'notPossible' => 0,
            'possibleFileComplaint' => 1
        ];
    }

    protected static function labels(): array
    {
        return [
            'notPossible' => __('event_late_declaration_enum.not_possible'),
            'possibleFileComplaint' => __('event_late_declaration_enum.possible')
        ];
    }
}
