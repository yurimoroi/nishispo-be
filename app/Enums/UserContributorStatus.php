<?php

namespace App\Enums;
use \Spatie\Enum\Enum;

/**
 * @method static self notApplied()
 * @method static self trainingInProgress()
 * @method static self trainingCompleted()
 * @method static self approved()
 * @method static self notApproved()
 * @method static self notSpecified()
 * @method static self applied()
 * @method static self belongs()
 * @method static self applyingLeave()
 * @method static self setCharges()
 * @method static self notSetCharges()
 * @method static self cancelled()
 * @method static self notReleasedCharges()
 * @method static self leave()
 */
final class UserContributorStatus extends Enum
{
    public static function notApplied(): self
    {
        return new self(0); 
    }

    public static function trainingInProgress(): self
    {
        return new self(1); 
    }

    public static function trainingCompleted(): self
    {
        return new self(2); 
    }

    public static function approved(): self
    {
        return new self(3); 
    }

    public static function notApproved(): self
    {
        return new self(4); 
    }

    public static function notSpecified(): self
    {
        return new self(5); 
    }

    public static function applied(): self
    {
        return new self(6); 
    }

    public static function belongs(): self
    {
        return new self(7); 
    }

    public static function applyingLeave(): self
    {
        return new self(8); 
    }

    public static function setCharges(): self
    {
        return new self(9); 
    }

    public static function notSetCharges(): self
    {
        return new self(10); 
    }

    public static function cancelled(): self
    {
        return new self(11); 
    }

    public static function notReleasedCharges(): self
    {
        return new self(12); 
    }

    public static function leave(): self
    {
        return new self(13); 
    }


    

    protected static function values(): array
    {
        return [
            'notApplied' => 0,
            'trainingInProgress' => 1,
            'trainingCompleted' => 2,
            'approved' => 3,
            'notApproved' => 4,
            'notSpecified' => 5,
            'applied' => 6,
            'belongs' => 7,
            'applyingLeave' => 8,
            'setCharges' => 9,
            'notSetCharges' => 10,
            'cancelled' => 11,
            'notReleasedCharges' => 12,
            'leave' => 13
        ];
    }

    protected static function labels(): array
    {
        return [
            'notApplied' => __('user_contributor_status.not_applied'),
            'trainingInProgress' => __('user_contributor_status.training_inprogress'),
            "trainingCompleted" => __('user_contributor_status.training_completed'),
            "approved" => __('user_contributor_status.approved'),
            "notApproved" => __('user_contributor_status.notApproved'),
            "notSpecified" => __('user_contributor_status.notSpecified'),
            "applied" => __('user_contributor_status.applied'),
            "belongs" => __('user_contributor_status.belongs'),
            "applyingLeave" => __('user_contributor_status.applyingLeave'),
            "setCharges" => __('user_contributor_status.setCharges'),
            "notSetCharges" => __('user_contributor_status.notSetCharges'),
            "cancelled" => __('user_contributor_status.cancelled'),
            "notReleasedCharges" => __('user_contributor_status.notReleasedCharges'),
            "leave" => __('user_contributor_status.leave'),
        ];
    }

    public static function getAll(): array
    {
        $values = static::values();
        $labels = static::labels();

        return array_map(function ($key, $value) use ($labels) {
            return [
                'id' => $value,
                'label' => $labels[$key] ?? null,
            ];
        }, array_keys($values), array_values($values));
    }
}
