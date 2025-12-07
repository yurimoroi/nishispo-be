<?php

namespace App\Enums;
use \Spatie\Enum\Enum;

/**
 * @method static self canView()
 * @method static self eventCreator()
 */

final class EventShowParticipantListEnum extends Enum
{
    public static function canView(): self
    {
        return new self(0); 
    }

    public static function eventCreator(): self
    {
        return new self(1); 
    }

    protected static function values(): array
    {
        return [
            'canView' => 0,
            'eventCreator' => 1
        ];
    }

    protected static function labels(): array
    {
        return [
            'canView' => __('event_show_participant_list_enum.can_view'),
            'eventCreator' => __('event_show_participant_list_enum.event_creator')
        ];
    }
}
