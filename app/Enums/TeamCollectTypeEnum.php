<?php

namespace App\Enums;
use \Spatie\Enum\Enum;

/**
 * @method static self individualSettelement()
 * @method static self itemSettlement()
 */

final class TeamCollectTypeEnum extends Enum
{
    public static function individualSettelement(): self
    {
        return new self(0); 
    }

    public static function itemSettlement(): self
    {
        return new self(1); 
    }

    protected static function values(): array
    {
        return [
            'individualSettelement' => 0,
            'itemSettlement' => 1
        ];
    }

    protected static function labels(): array
    {
        return [
            'individualSettelement' => __('team_collect_type_enum.individual_settlement'),
            'itemSettlement' => __('team_collect_type_enum.item_settlement')
        ];
    }
}
