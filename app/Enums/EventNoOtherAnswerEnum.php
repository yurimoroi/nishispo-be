<?php

namespace App\Enums;
use \Spatie\Enum\Enum;

/**
 * @method static self undecidedOptions()
 * @method static self notDisplayed()
 */

final class EventNoOtherAnswerEnum extends Enum
{
    public static function undecidedOptions(): self
    {
        return new self(0); 
    }

    public static function notDisplayed(): self
    {
        return new self(1); 
    }

    protected static function values(): array
    {
        return [
            'undecidedOptions' => 0,
            'notDisplayed' => 1
        ];
    }

    protected static function labels(): array
    {
        return [
            'undecidedOptions' => __('event_no_other_answer_enum.undecided'),
            'notDisplayed' => __('event_no_other_answer_enum.not_displayed')
        ];
    }
}
